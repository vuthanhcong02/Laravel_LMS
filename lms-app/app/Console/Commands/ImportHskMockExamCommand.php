<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\HskLevel;
use App\Models\HskMockExam;
use App\Models\HskMockExamSection;
use App\Models\HskMockExamQuestionGroup;
use App\Models\HskMockExamQuestion;
use App\Models\HskMockExamOption;

class ImportHskMockExamCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:import-hsk {exam : Exam ID (e.g., H10000) or Path to the exam folder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import an HSK mock exam from a JSON file and move media to storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $input = $this->argument('exam');
        $folderPath = base_path($input);
        
        // If direct path doesn't exist, try to find it in mock_exam/HSK*
        if (!File::isDirectory($folderPath)) {
            $matched = false;
            foreach (glob(base_path('mock_exam/HSK*/' . $input), GLOB_ONLYDIR) as $dir) {
                $folderPath = $dir;
                $matched = true;
                break;
            }
            if (!$matched) {
                $this->error("Directory not found for exam: {$input}");
                return 1;
            }
        }

        $jsonFile = $folderPath . '/exam.json';
        if (!File::exists($jsonFile)) {
            $this->error("exam.json not found in {$folderPath}");
            return 1;
        }

        $this->info("Parsing JSON...");
        $data = json_decode(File::get($jsonFile), true);

        if (!$data || !isset($data['exam_id'])) {
            $this->error("Invalid JSON format or missing exam_id");
            return 1;
        }

        $examId = $data['exam_id'];
        $levelCode = 'hsk' . ($data['level'] ?? 1);
        
        $hskLevel = HskLevel::where('level_code', $levelCode)->first();
        if (!$hskLevel) {
            $this->error("HSK Level '{$levelCode}' not found in database.");
            return 1;
        }

        $this->info("Moving media files to storage...");
        $storagePath = storage_path('app/public/hsk_mock_exams/' . $examId);
        
        if (!File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Copy audio and images folders
        if (File::isDirectory($folderPath . '/audio')) {
            File::copyDirectory($folderPath . '/audio', $storagePath . '/audio');
        }
        if (File::isDirectory($folderPath . '/images')) {
            File::copyDirectory($folderPath . '/images', $storagePath . '/images');
        }

        // Delete Zone.Identifier files (Windows metadata) copied along with images
        // These files are named like: "image.jpeg:Zone.Identifier"
        foreach ([$storagePath . '/images', $storagePath . '/audio'] as $dir) {
            if (File::isDirectory($dir)) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
                foreach ($files as $file) {
                    if ($file->isFile() && str_contains($file->getFilename(), 'Zone.Identifier')) {
                        @unlink($file->getPathname());
                    }
                }
            }
        }

        $this->info("Importing database records for {$examId}...");

        DB::beginTransaction();
        try {
            // Delete existing if any to avoid duplicates during testing
            $existingExam = HskMockExam::where('title', "Đề thi HSK Cấp {$data['level']} ({$examId})")->first();
            if ($existingExam) {
                $existingExam->delete();
                $this->warn("Existing exam deleted.");
            }

            // Duration mapping based on HSK level
            $durationMap = [
                1 => 40,
                2 => 55,
                3 => 90,
                4 => 105,
                5 => 125,
                6 => 140
            ];
            $duration = $durationMap[$data['level'] ?? 1] ?? 60;

            // Find the actual audio file name in the source folder
            $audioFile = null;
            $audioFiles = glob($folderPath . '/audio/*.mp3');
            if (!empty($audioFiles)) {
                $audioFileName = basename($audioFiles[0]);
                $audioFile = "hsk_mock_exams/{$examId}/audio/{$audioFileName}";
            }

            $exam = HskMockExam::create([
                'hsk_level_id' => $hskLevel->id,
                'title' => "Đề thi HSK Cấp {$data['level']} ({$examId})",
                'duration' => $duration,
                'total_questions' => 0, // Will update later
                'total_score' => 100, // Or calculate based on questions
                'pass_score' => 60,
                'audio_file' => $audioFile,
                'is_published' => true,
            ]);

            $totalQuestions = 0;
            $sectionOrder = 1;
            $globalQuestionOrder = 1;

            foreach ($data['sections'] as $sectionData) {
                $section = HskMockExamSection::create([
                    'hsk_mock_exam_id' => $exam->id,
                    'name' => ucfirst($sectionData['section']),
                    'skill_type' => $sectionData['section'],
                    'order_index' => $sectionOrder++,
                ]);

                $groupOrder = 1;
                foreach ($sectionData['parts'] as $partData) {
                    $passageImage = null;
                    if (!empty($partData['images']) && is_array($partData['images'])) {
                        $passageImage = implode(',', array_map(function($img) use ($examId) {
                            return "hsk_mock_exams/{$examId}/" . $img;
                        }, $partData['images']));
                    } elseif (!empty($partData['image'])) {
                        $passageImage = "hsk_mock_exams/{$examId}/" . $partData['image'];
                    }

                    $instructions = $partData['instructions'] ?? null;
                    if ($instructions && str_contains($instructions, '<div')) {
                        $instructions = strip_tags($instructions, '<ruby><rt>');
                        $instructions = trim(preg_replace('/[\r\n]+/', "\n", $instructions));
                    }

                    $group = HskMockExamQuestionGroup::create([
                        'hsk_mock_exam_section_id' => $section->id,
                        'passage_text' => $instructions,
                        'passage_image' => $passageImage,
                        'order_index' => $groupOrder++,
                    ]);

                    foreach ($partData['questions'] as $qData) {
                        $totalQuestions++;
                        
                        $imagePath = null;
                        if (!empty($qData['image'])) {
                            $imagePath = "hsk_mock_exams/{$examId}/" . $qData['image'];
                        }
                        
                        $audioPath = null;
                        if (!empty($qData['audio'])) {
                            $audioPath = "hsk_mock_exams/{$examId}/" . $qData['audio'];
                        }

                        $question = HskMockExamQuestion::create([
                            'hsk_mock_exam_group_id' => $group->id,
                            'hsk_mock_exam_section_id' => $section->id,
                            'question_type' => $this->mapQuestionType($qData['type']),
                            'title' => $qData['question_text'] ?? null,
                            'image' => $imagePath,
                            'audio_file' => $audioPath,
                            'points' => 1,
                            'order_index' => $globalQuestionOrder++,
                        ]);

                        $optionOrder = 1;
                        if (!empty($qData['options'])) {
                            foreach ($qData['options'] as $optItem) {
                                $optText = is_array($optItem) ? ($optItem['text'] ?? '') : (is_string($optItem) ? $optItem : '');
                                $optImage = (is_array($optItem) && !empty($optItem['image'])) ? "hsk_mock_exams/{$examId}/" . $optItem['image'] : null;
                                
                                HskMockExamOption::create([
                                    'hsk_mock_exam_question_id' => $question->id,
                                    'content' => $optText,
                                    'image' => $optImage,
                                    'is_correct' => ($optText === ($qData['correct_answer'] ?? null)),
                                    'order_index' => $optionOrder++,
                                ]);
                            }
                        }
                    }
                }
            }

            $exam->update([
                'total_questions' => $totalQuestions,
                'total_score' => $totalQuestions * 1, // Example: 1 point per question
            ]);

            DB::commit();
            $this->info("Successfully imported {$examId}!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function mapQuestionType($jsonType)
    {
        return match($jsonType) {
            'true_false' => 'true_false',
            'multiple_choice' => 'single_choice',
            default => 'single_choice'
        };
    }
}
