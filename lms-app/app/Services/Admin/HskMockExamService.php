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
        $examId = $data['exam_id'] ?? $data['exam_code'] ?? null;
        if (!$examId) {
            throw new \Exception('Invalid JSON file or missing exam_id/exam_code.');
        }
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
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    // Prevent Zip Slip / Path Traversal
                    if (strpos($filename, '..') !== false || strpos($filename, '/') === 0 || strpos($filename, '\\') === 0) {
                        continue;
                    }
                    $zip->extractTo($storagePath, $filename);
                }
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
                        if (empty($qData['is_example'])) {
                            $totalQuestions++;
                        }

                        $imagePath = !empty($qData['image']) ? "hsk_mock_exams/{$examId}/" . $qData['image'] : null;
                        $audioPath = !empty($qData['audio']) ? "hsk_mock_exams/{$examId}/" . $qData['audio'] : null;
                        
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
        // Pre-load all relationships into memory to avoid N+1 SELECT queries
        $hskMockExam->load('sections.questionGroups.questions.options');

        DB::beginTransaction();
        try {
            $hskMockExam->update([
                'title' => $data['title'],
                'duration' => $data['duration'],
                'is_published' => $data['is_published'],
            ]);

            $totalQuestions = 0;

            foreach ($data['sections'] as $sectionData) {
                $section = $hskMockExam->sections->firstWhere('id', $sectionData['id']);
                if (!$section) continue;
                $section->update([
                    'name' => $sectionData['name'],
                ]);

                $groups = $sectionData['question_groups'] ?? ($sectionData['questionGroups'] ?? ($sectionData['groups'] ?? []));

                foreach ($groups as $groupData) {
                    $group = $section->questionGroups->firstWhere('id', $groupData['id']);
                    if (!$group) continue;
                    $group->update([
                        'passage_text' => $groupData['passage_text'] ?? null,
                        'passage_image' => $groupData['passage_image'] ?? null,
                    ]);

                    foreach ($groupData['questions'] as $questionData) {
                        $question = $group->questions->firstWhere('id', $questionData['id']);
                        if (!$question) continue;
                        if (!$question->is_example) {
                            $totalQuestions++;
                        }
                        $question->update([
                            'title' => $questionData['title'] ?? null,
                            'image' => $questionData['image'] ?? null,
                            'audio_file' => $questionData['audio_file'] ?? null,
                        ]);

                        if (!empty($questionData['options'])) {
                            foreach ($questionData['options'] as $optionData) {
                                $option = $question->options->firstWhere('id', $optionData['id']);
                                if (!$option) continue;
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
