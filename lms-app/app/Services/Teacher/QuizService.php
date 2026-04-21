<?php

namespace App\Services\Teacher;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\Course;
use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service to handle Quiz and Question management for Teachers
 */
class QuizService
{
    /**
     * List quizzes for a specific teacher with pagination
     * 
     * @param int $teacherId
     * @return LengthAwarePaginator
     */
    public function listForTeacher(int $teacherId): LengthAwarePaginator
    {
        return Quiz::whereHas('course', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->with(['course', 'questions'])
        ->latest()
        ->paginate(15);
    }

    /**
     * Get courses managed by a teacher to link quizzes
     * 
     * @param int $teacherId
     * @return Collection
     */
    public function teacherCourses(int $teacherId): Collection
    {
        return Course::where('teacher_id', $teacherId)->get();
    }

    /**
     * Create a new quiz
     * 
     * @param array $data
     * @return Quiz
     */
    public function createQuiz(array $data): Quiz
    {
        return Quiz::create($data);
    }

    /**
     * Update an existing quiz
     * 
     * @param Quiz $quiz
     * @param array $data
     * @return Quiz
     */
    public function updateQuiz(Quiz $quiz, array $data): Quiz
    {
        $quiz->update($data);
        return $quiz->fresh();
    }

    /**
     * Save questions and their options for a quiz
     * 
     * @param Quiz $quiz
     * @param array $questionsData Array of question data with options and files
     * @return void
     */
    public function saveQuestions(Quiz $quiz, array $questionsData): void
    {
        DB::transaction(function () use ($quiz, $questionsData) {
            // Track existing question IDs to delete those not in the new data
            $existingQuestionIds = $quiz->questions()->pluck('id')->toArray();
            $newQuestionIds = [];

            foreach ($questionsData as $qData) {
                $incomingId = $qData['id'] ?? null;
                $questionId = ($incomingId && in_array($incomingId, $existingQuestionIds))
                    ? $incomingId
                    : null;

                $questionFields = [
                    'type'          => $qData['type'],
                    'question_text' => $qData['question_text'],
                    'marks'         => $qData['marks'] ?? 1,
                ];

                if (isset($qData['image'])) {
                    if ($questionId) {
                        $old = Question::find($questionId);
                        $this->deleteMedia($old?->image_path);
                    }
                    $questionFields['image_path'] = $this->uploadMedia($qData['image'], 'quizzes/images');
                }

                if (isset($qData['audio'])) {
                    if ($questionId) {
                        $old = $old ?? Question::find($questionId);
                        $this->deleteMedia($old?->audio_path);
                    }
                    $questionFields['audio_path'] = $this->uploadMedia($qData['audio'], 'quizzes/audio');
                }

                $question = $quiz->questions()->updateOrCreate(['id' => $questionId], $questionFields);
                $newQuestionIds[] = $question->id;

                // Handle Options for MCQ and True/False
                if ($question->type !== QuestionType::ESSAY) {
                    $this->saveOptions($question, $qData['options'] ?? []);
                }
            }

            // Delete removed questions and their files
            $toDelete = array_diff($existingQuestionIds, $newQuestionIds);
            if (!empty($toDelete)) {
                $questionsToDelete = Question::whereIn('id', $toDelete)->get();
                foreach ($questionsToDelete as $q) {
                    $this->deleteMedia($q->image_path);
                    $this->deleteMedia($q->audio_path);
                    $q->delete();
                }
            }
        });
    }

    /**
     * Save options for a specific question
     * 
     * @param Question $question
     * @param array $optionsData
     * @return void
     */
    private function saveOptions(Question $question, array $optionsData): void
    {
        $existingOptionIds = $question->options()->pluck('id')->toArray();
        $newOptionIds = [];

        foreach ($optionsData as $oData) {
            $option = $question->options()->updateOrCreate(
                ['id' => $oData['id'] ?? null],
                [
                    'option_text' => $oData['option_text'],
                    'is_correct' => (bool)($oData['is_correct'] ?? false),
                ]
            );
            $newOptionIds[] = $option->id;
        }

        // Delete removed options
        $toDelete = array_diff($existingOptionIds, $newOptionIds);
        if (!empty($toDelete)) {
            Option::whereIn('id', $toDelete)->delete();
        }
    }

    /**
     * Upload media file and return path
     * 
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    private function uploadMedia(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }

    /**
     * Import questions from a CSV file
     * 
     * @param Quiz $quiz
     * @param UploadedFile $file
     * @return array Result of the import (success, errors)
     */
    /**
     * Maximum number of questions allowed to import at once (prevent OOM)
     */
    private const MAX_CSV_IMPORT_ROWS = 100;

    public function importFromCsv(Quiz $quiz, UploadedFile $file): array
    {
        $filePath = $file->getRealPath();
        
        // Auto-detect delimiter (Issue 7 - Robustness)
        $firstLine = fgets(fopen($filePath, 'r'));
        $delimiter = ',';
        if (str_contains($firstLine, ';')) $delimiter = ';';
        if (str_contains($firstLine, "\t")) $delimiter = "\t";

        $handle = fopen($filePath, 'r');
        fgetcsv($handle, 0, $delimiter); // Skip header row

        $count = 0;
        $errors = [];

        $mcLabel = mb_strtolower(__('Trắc nghiệm'), 'UTF-8');
        $tfLabel = mb_strtolower(__('Đúng/Sai'), 'UTF-8');
        $essayLabel = mb_strtolower(__('Tự luận'), 'UTF-8');

        DB::transaction(function () use ($quiz, $handle, $delimiter, $mcLabel, $tfLabel, $essayLabel, &$count, &$errors) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                // Skip empty rows
                if (empty(array_filter($row))) continue;

                if ($count >= self::MAX_CSV_IMPORT_ROWS) {
                    $errors[] = __('Chỉ được phép import tối đa :max câu hỏi mỗi lần.', [
                        'max' => self::MAX_CSV_IMPORT_ROWS,
                    ]);
                    break;
                }

                // Expected CSV Format: Question Text, Type, Marks, Option A, Option B, Option C, Option D, Correct Option (A/B/C/D)
                if (count($row) < 8) {
                    $errors[] = __('Dòng :line không đủ cột dữ liệu (Yêu cầu 8 cột).', ['line' => $count + 2]);
                    continue;
                }

                $typeStr = mb_strtolower(trim($row[1]), 'UTF-8');
                $type = match($typeStr) {
                    $mcLabel    => QuestionType::MULTIPLE_CHOICE,
                    $tfLabel    => QuestionType::TRUE_FALSE,
                    $essayLabel => QuestionType::ESSAY,
                    // Fallback to English labels
                    'trắc nghiệm', 'multiple choice' => QuestionType::MULTIPLE_CHOICE,
                    'đúng/sai', 'true/false'         => QuestionType::TRUE_FALSE,
                    'tự luận', 'essay'               => QuestionType::ESSAY,
                    default                          => QuestionType::MULTIPLE_CHOICE
                };

                $question = $quiz->questions()->create([
                    'type'          => $type,
                    'question_text' => $row[0] ?: __('(Không có nội dung)'),
                    'marks'         => floatval($row[2]) ?: 1,
                ]);

                // Options (Only for MCQ/TF)
                if ($type !== QuestionType::ESSAY) {
                    $correctLetter = strtoupper(trim($row[7])); // A, B, C or D
                    $letters = ['A', 'B', 'C', 'D'];

                    for ($i = 0; $i < 4; $i++) {
                        if (!isset($row[3 + $i]) || empty(trim($row[3 + $i]))) continue;

                        $question->options()->create([
                            'option_text' => $row[3 + $i],
                            'is_correct'  => ($letters[$i] === $correctLetter),
                        ]);
                    }
                }

                $count++;
            }
        });

        fclose($handle);

        return [
            'count'  => $count,
            'errors' => $errors,
        ];
    }

    /**
     * Generate CSV template content for importing questions
     * 
     * @return string
     */
    public function generateCsvTemplate(): string
    {
        $headers = [
            __('Question Text'),
            __('Type'),
            __('Marks'),
            __('Option A'),
            __('Option B'),
            __('Option C'),
            __('Option D'),
            __('Correct Option'),
        ];

        // Example data rows - 3 types of questions, one row each (Issue 9)
        $rows = [
            // MCQ Example
            [
                __('Thủ đô của Việt Nam là gì?'),
                __('Trắc nghiệm'),
                '1.0',
                'Hà Nội',
                'TP.HCM',
                'Đà Nẵng',
                'Cần Thơ',
                'A'
            ],
            // True/False Example
            [
                __('Hà Nội có phải là thủ đô của Việt Nam không?'),
                __('Đúng/Sai'),
                '1.0',
                __('Đúng'),
                __('Sai'),
                '',
                '',
                'A'
            ],
            // Essay Example
            [
                __('Hãy viết một đoạn văn ngắn nêu cảm nhận của em về mùa thu Hà Nội.'),
                __('Tự luận'),
                '5.0',
                '',
                '',
                '',
                '',
                ''
            ]
        ];

        $output = fopen('php://temp', 'r+');
        
        // Add BOM for UTF-8 Excel support
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, $headers);

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return $content;
    }

    /**
     * Delete all media files of the quiz before deleting the Quiz from DB
     *
     * @param Quiz $quiz
     * @return void
     */
    public function deleteQuiz(Quiz $quiz): void
    {
        $quiz->load('questions');

        foreach ($quiz->questions as $question) {
            $this->deleteMedia($question->image_path);
            $this->deleteMedia($question->audio_path);
        }

        $quiz->delete();
    }

    /**
     * Delete media file from storage
     * 
     * @param string|null $path
     * @return void
     */
    private function deleteMedia(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
