<?php

namespace App\Services\Admin;

use App\Models\HskLevel;
use App\Models\HskMockExam;
use App\Models\HskMockExamSection;
use App\Models\HskMockExamQuestionGroup;
use App\Models\HskMockExamQuestion;
use App\Models\HskMockExamOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class HskMockExamService
{
    /**
     * Import exam from JSON data
     */
    public function importExam(array $data, ?string $zipRealPath): array
    {
        if (!isset($data['exam_id'])) {
            throw new \Exception('Invalid JSON file or missing exam_id.');
        }

        $examId = $data['exam_id'];
        $levelCode = 'hsk' . ($data['level'] ?? 1);

        $hskLevel = HskLevel::where('level_code', $levelCode)->first();
        if (!$hskLevel) {
            throw new \Exception("HSK level '{$levelCode}' does not exist in the system.");
        }

        $storagePath = storage_path('app/public/hsk_mock_exams/' . $examId);
        if (!File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Extract media ZIP file (images/audio) if available
        if ($zipRealPath) {
            $zip = new ZipArchive();
            if ($zip->open($zipRealPath) === true) {
                $zip->extractTo($storagePath);
                $zip->close();
            }
        }

        DB::beginTransaction();
        try {
            // Delete exam with the same code if it already exists to re-import
            $existingExam = HskMockExam::where('title', 'LIKE', "%({$examId})%")->first();
            if ($existingExam) {
                $existingExam->delete();
            }

            $durationMap = [1 => 40, 2 => 55, 3 => 90, 4 => 105, 5 => 125, 6 => 140];
            $duration = $durationMap[$data['level'] ?? 1] ?? 60;

            $audioFiles = glob($storagePath . '/audio/*.mp3');
            $audioFile = !empty($audioFiles) ? "hsk_mock_exams/{$examId}/audio/" . basename($audioFiles[0]) : null;

            $exam = HskMockExam::create([
                'hsk_level_id' => $hskLevel->id,
                'title' => "Đề thi HSK Cấp {$data['level']} ({$examId})",
                'duration' => $duration,
                'total_questions' => 0,
                'total_score' => 100,
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
                        $passageImage = implode(',', array_map(function ($img) use ($examId) {
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

                        $imagePath = !empty($qData['image']) ? "hsk_mock_exams/{$examId}/" . $qData['image'] : null;
                        $audioPath = !empty($qData['audio']) ? "hsk_mock_exams/{$examId}/" . $qData['audio'] : null;

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

                        if (!empty($qData['options'])) {
                            foreach ($qData['options'] as $idx => $optStr) {
                                $isCorrect = false;
                                if (isset($qData['correct_answer'])) {
                                    $cleanCorrect = strtoupper(trim($qData['correct_answer']));
                                    $optionLetter = chr(65 + $idx); // A, B, C, D...
                                    
                                    if ($cleanCorrect === $optionLetter) {
                                        $isCorrect = true;
                                    } elseif (strtoupper(trim($optStr)) === $cleanCorrect) {
                                        $isCorrect = true;
                                    } else {
                                        $firstChar = strtoupper(substr(trim($optStr), 0, 1));
                                        if ($firstChar === $cleanCorrect) {
                                            $isCorrect = true;
                                        }
                                    }
                                }
                                HskMockExamOption::create([
                                    'hsk_mock_exam_question_id' => $question->id,
                                    'content' => $optStr,
                                    'is_correct' => $isCorrect,
                                    'order_index' => $idx + 1,
                                ]);
                            }
                        } elseif (isset($qData['correct_answer']) && in_array($qData['correct_answer'], ['√', '×'])) {
                            HskMockExamOption::create([
                                'hsk_mock_exam_question_id' => $question->id,
                                'content' => 'Đúng (√)',
                                'is_correct' => ($qData['correct_answer'] === '√'),
                                'order_index' => 1,
                            ]);
                            HskMockExamOption::create([
                                'hsk_mock_exam_question_id' => $question->id,
                                'content' => 'Sai (×)',
                                'is_correct' => ($qData['correct_answer'] === '×'),
                                'order_index' => 2,
                            ]);
                        }
                    }
                }
            }

            $exam->update(['total_questions' => $totalQuestions]);

            DB::commit();
            return [
                'success' => true,
                'exam_id' => $examId,
                'total_questions' => $totalQuestions
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Map string question type to enum equivalent
     */
    private function mapQuestionType($type): string
    {
        return match ($type) {
            'true_false' => 'true_false',
            'multiple_choice' => 'single_choice',
            'matching' => 'matching',
            'fill_in_the_blank' => 'fill_blank',
            default => 'single_choice',
        };
    }

    /**
     * Update exam data from Alpine.js Editor
     */
    public function updateExamData(HskMockExam $hskMockExam, array $data): void
    {
        DB::beginTransaction();
        try {
            $hskMockExam->update([
                'title' => $data['title'],
                'duration' => $data['duration'],
                'is_published' => $data['is_published'],
            ]);

            $totalQuestions = 0;

            foreach ($data['sections'] as $sectionData) {
                $section = HskMockExamSection::findOrFail($sectionData['id']);
                $section->update([
                    'name' => $sectionData['name'],
                ]);

                $groups = $sectionData['question_groups'] ?? ($sectionData['questionGroups'] ?? ($sectionData['groups'] ?? []));

                foreach ($groups as $groupData) {
                    $group = HskMockExamQuestionGroup::findOrFail($groupData['id']);
                    $group->update([
                        'passage_text' => $groupData['passage_text'] ?? null,
                        'passage_image' => $groupData['passage_image'] ?? null,
                    ]);

                    foreach ($groupData['questions'] as $questionData) {
                        $totalQuestions++;
                        $question = HskMockExamQuestion::findOrFail($questionData['id']);
                        $question->update([
                            'title' => $questionData['title'] ?? null,
                            'image' => $questionData['image'] ?? null,
                            'audio_file' => $questionData['audio_file'] ?? null,
                        ]);

                        if (!empty($questionData['options'])) {
                            foreach ($questionData['options'] as $optionData) {
                                $option = HskMockExamOption::findOrFail($optionData['id']);
                                $option->update([
                                    'content' => $optionData['content'],
                                    'is_correct' => (bool)$optionData['is_correct'],
                                ]);
                            }
                        }
                    }
                }
            }

            $hskMockExam->update(['total_questions' => $totalQuestions]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Upload an image from local computer
     */
    public function uploadImage($file): string
    {
        return $file->store('hsk_mock_exams/uploads', 'public');
    }

    /**
     * Create an empty exam structure
     */
    public function createEmptyExam(array $data, \App\Models\HskLevel $hskLevel): HskMockExam
    {
        $exam = HskMockExam::create([
            'title' => $data['title'],
            'hsk_level_id' => $hskLevel->id,
            'duration' => $data['duration'],
            'audio_file' => null,
            'total_score' => 100,
            'total_questions' => 0
        ]);

        $levelCode = $hskLevel->level_code;
        $structure = config("hsk_structure.{$levelCode}", config('hsk_structure.default'));
        
        $orderSec = 1;
        $globalQuestionOrder = 1;

        foreach ($structure as $skill => $secData) {
            $section = HskMockExamSection::create([
                'hsk_mock_exam_id' => $exam->id,
                'name' => $secData['name'],
                'skill_type' => $skill,
                'order_index' => $orderSec++
            ]);

            $groupOrder = 1;
            foreach ($secData['parts'] as $partData) {
                $group = HskMockExamQuestionGroup::create([
                    'hsk_mock_exam_section_id' => $section->id,
                    'title' => 'Part ' . $groupOrder,
                    'group_type' => $partData['group_type'],
                    'passage_text' => null,
                    'order_index' => $groupOrder++
                ]);

                $qType = 'single_choice';
                if (str_contains($partData['group_type'], 'true_false')) $qType = 'true_false';
                if (str_contains($partData['group_type'], 'fill_in_blank')) $qType = 'fill_blank';
                if (str_contains($partData['group_type'], 'matching')) $qType = 'matching';

                for ($i = 0; $i < $partData['questions']; $i++) {
                    HskMockExamQuestion::create([
                        'hsk_mock_exam_group_id' => $group->id,
                        'hsk_mock_exam_section_id' => $section->id,
                        'question_type' => $qType,
                        'title' => null,
                        'points' => 1,
                        'order_index' => $globalQuestionOrder++
                    ]);
                }
            }
        }

        $exam->update(['total_questions' => $globalQuestionOrder - 1]);

        return $exam;
    }

    /**
     * Handle CSV parsing
     */
    public function parseCsvToData($filePath): array
    {
        $file = fopen($filePath, 'r');
        $bom = fread($file, 3);
        if ($bom != "\xEF\xBB\xBF") {
            rewind($file);
        }
        $headers = fgetcsv($file);
        
        $examId = 'H99999';
        $level = 1;
        $sections = [];

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < count($headers)) continue;
            
            $rowAssoc = array_combine($headers, $row);
            
            $examId = $rowAssoc['exam_id'] ?: $examId;
            $level = $rowAssoc['level'] ?: $level;
            
            $secName = $rowAssoc['section'];
            $partName = $rowAssoc['part'];
            
            if (!isset($sections[$secName])) {
                $sections[$secName] = ['section' => $secName, 'parts' => []];
            }
            if (!isset($sections[$secName]['parts'][$partName])) {
                $sections[$secName]['parts'][$partName] = [
                    'name' => 'Phần ' . $partName,
                    'instructions' => $rowAssoc['passage_text'],
                    'image' => $rowAssoc['passage_image'],
                    'questions' => []
                ];
            }
            
            $options = !empty($rowAssoc['options']) ? explode('|', $rowAssoc['options']) : [];
            
            $sections[$secName]['parts'][$partName]['questions'][] = [
                'type' => $rowAssoc['question_type'],
                'question_text' => $rowAssoc['question_text'],
                'options' => $options,
                'correct_answer' => $rowAssoc['correct_answer'],
                'image' => !empty($rowAssoc['question_image']) ? 'images/' . $rowAssoc['question_image'] : null,
                'audio' => !empty($rowAssoc['question_audio']) ? 'audio/' . $rowAssoc['question_audio'] : null,
            ];
        }
        fclose($file);
        
        $sectionsArray = [];
        foreach ($sections as $sec) {
            $sec['parts'] = array_values($sec['parts']);
            $sectionsArray[] = $sec;
        }

        return ['exam_id' => $examId, 'level' => $level, 'sections' => $sectionsArray];
    }
}
