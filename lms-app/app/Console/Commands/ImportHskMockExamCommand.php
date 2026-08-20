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

        $examId = $data['exam_id'] ?? $data['exam_code'] ?? null;
        if (!$examId) {
            $this->error("Invalid JSON format or missing exam_id/exam_code");
            return 1;
        }

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
                $rawSectionName = $sectionData['section'] ?? $sectionData['section_name'] ?? 'Unknown';
                
                $skillType = strtolower($rawSectionName);
                if (str_contains($skillType, '听力') || str_contains($skillType, 'listening')) $skillType = 'listening';
                elseif (str_contains($skillType, '阅读') || str_contains($skillType, 'reading')) $skillType = 'reading';
                elseif (str_contains($skillType, '书写') || str_contains($skillType, 'writing')) $skillType = 'writing';
                else $skillType = 'listening'; // default fallback

                $section = HskMockExamSection::create([
                    'hsk_mock_exam_id' => $exam->id,
                    'name' => ucfirst($rawSectionName),
                    'skill_type' => $skillType,
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

                    $firstQ = $partData['questions'][0] ?? null;
                    $groupType = $partData['type'] ?? "{$skillType}_true_false"; // Default fallback
                    
                    if (empty($partData['type']) && $firstQ) {
                        $qType = $firstQ['type'] ?? null;
                        if (!$qType && isset($firstQ['options']) && count($firstQ['options']) == 2 && in_array('√', $firstQ['options'])) {
                            $qType = 'true_false';
                        }
                        
                        if ($skillType === 'listening') {
                            if ($qType === 'true_false') {
                                $groupType = 'listening_true_false';
                            } else {
                                $hasImageOption = false;
                                if (!empty($firstQ['options']) && is_array($firstQ['options'][0]) && !empty($firstQ['options'][0]['image'])) {
                                    $hasImageOption = true;
                                }
                                if ($hasImageOption) {
                                    $groupType = 'listening_image_choice';
                                } elseif (!empty($passageImage) || !empty($partData['images'])) {
                                    $groupType = 'listening_matching_images';
                                } else {
                                    $groupType = 'listening_dialogue_choice';
                                }
                            }
                        } elseif ($skillType === 'reading') {
                            if ($qType === 'true_false') {
                                $groupType = 'reading_true_false';
                            } elseif (!empty($passageImage) || !empty($partData['images'])) {
                                $groupType = 'reading_matching_sentences';
                            } else {
                                $groupType = 'reading_passage_choice';
                            }
                        } elseif ($skillType === 'writing') {
                            $groupType = 'writing_sentence_building';
                        }
                    }

                    // Build passage_text for shared option banks if AI output options in questions
                    $passageText = null;
                    if (in_array($groupType, ['reading_matching_sentences', 'reading_fill_in_blank'])) {
                        $passageText = $partData['passage_text'] ?? null;
                        if (!$passageText && $firstQ && !empty($firstQ['options']) && is_array($firstQ['options'])) {
                            if (is_string($firstQ['options'][0]) && !in_array($firstQ['options'][0], ['√', '×', 'A', 'B', 'C', 'D', 'E', 'F'])) {
                                $parsedOptions = [];
                                foreach ($firstQ['options'] as $idx => $optString) {
                                    $letter = chr(65 + $idx);
                                    $text = trim(preg_replace('/^[A-F][\.\s]+/', '', $optString));
                                    $parsedOptions[] = ['letter' => $letter, 'html' => $text, 'pinyin' => null, 'hanzi' => null];
                                }
                                $exAnswer = '';
                                $exQ = collect($partData['questions'])->where('is_example', true)->first();
                                if ($exQ && !empty($exQ['correct_answer'])) {
                                    $exAnswer = $exQ['correct_answer'];
                                }
                                $passageText = json_encode(['options' => $parsedOptions, 'ex_a_letter' => $exAnswer]);
                            }
                        }
                    }
                    
                    $partTitle = $partData['name'] ?? (isset($partData['part_number']) ? 'Phần ' . $partData['part_number'] : 'Part ' . $groupOrder);

                    $group = HskMockExamQuestionGroup::create([
                        'hsk_mock_exam_section_id' => $section->id,
                        'group_type' => $groupType,
                        'title' => $partTitle,
                        'passage_text' => $passageText ?? $instructions,
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

                        $qType = $qData['type'] ?? null;
                        if (!$qType && isset($qData['options']) && count($qData['options']) == 2 && in_array('√', $qData['options'])) {
                            $qType = 'true_false';
                        }

                        $question = HskMockExamQuestion::create([
                            'hsk_mock_exam_group_id' => $group->id,
                            'hsk_mock_exam_section_id' => $section->id,
                            'question_type' => $this->mapQuestionType($qType),
                            'title' => $qData['question_text'] ?? null,
                            'image' => $imagePath,
                            'audio_file' => $audioPath,
                            'points' => 1,
                            'order_index' => $globalQuestionOrder++,
                            'is_example' => $qData['is_example'] ?? false,
                        ]);

                        if (!empty($qData['options'])) {
                            foreach ($qData['options'] as $idx => $opt) {
                                if (is_array($opt)) {
                                    $optText = $opt['text'] ?? $opt['option_code'] ?? '';
                                    $optImage = !empty($opt['image']) ? "hsk_mock_exams/{$examId}/" . $opt['image'] : null;
                                } else {
                                    $optText = $opt;
                                    $optImage = null;
                                }
                                
                                $isCorrect = false;
                                if (isset($qData['correct_answer'])) {
                                    if (is_array($qData['correct_answer'])) {
                                        $isCorrect = in_array(chr(65 + $idx), $qData['correct_answer']) || in_array($optText, $qData['correct_answer']);
                                    } else {
                                        $isCorrect = ($qData['correct_answer'] === chr(65 + $idx) || $qData['correct_answer'] == $optText);
                                    }
                                }
                                
                                if (in_array($groupType, ['listening_dialogue_choice', 'listening_image_choice', 'reading_passage_choice'])) {
                                    $optText = trim(preg_replace('/^[A-F][\.\s]+/', '', $optText));
                                }
                                
                                if (in_array($groupType, ['reading_matching_sentences', 'reading_fill_in_blank']) && is_string($opt) && !in_array($opt, ['A', 'B', 'C', 'D', 'E', 'F'])) {
                                    $optText = chr(65 + $idx);
                                }

                                HskMockExamOption::create([
                                    'hsk_mock_exam_question_id' => $question->id,
                                    'content' => $optText,
                                    'image' => $optImage,
                                    'is_correct' => $isCorrect,
                                    'order_index' => $idx + 1,
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
