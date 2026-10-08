<?php

namespace App\Services\Teacher;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
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
        ->with(['course'])
        ->withCount('questions')
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
     * @param UploadedFile|null $audioFile
     * @return Quiz
     */
    public function createQuiz(array $data, ?UploadedFile $audioFile = null): Quiz
    {
        if ($audioFile) {
            $data['audio_path'] = $this->uploadMedia($audioFile, 'quizzes/audio');
        }

        return Quiz::create($data);
    }

    /**
     * Update an existing quiz
     * 
     * @param Quiz $quiz
     * @param array $data
     * @param UploadedFile|null $audioFile
     * @return Quiz
     */
    public function updateQuiz(Quiz $quiz, array $data, ?UploadedFile $audioFile = null): Quiz
    {
        if (!empty($data['remove_audio'])) {
            $this->deleteMedia($quiz->audio_path);
            $data['audio_path'] = null;
        }

        if ($audioFile) {
            $this->deleteMedia($quiz->audio_path);
            $data['audio_path'] = $this->uploadMedia($audioFile, 'quizzes/audio');
        }

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
                    'type'                => $qData['type'],
                    'question_text'       => $qData['question_text'],
                    'marks'               => $qData['marks'] ?? 1,
                    'essay_grading_type'  => $qData['essay_grading_type'] ?? 'manual',
                    'correct_answer_text' => !empty($qData['correct_answer_text']) ? trim($qData['correct_answer_text']) : null,
                    'case_sensitive'      => !empty($qData['case_sensitive']),
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

        // Delete global audio
        $this->deleteMedia($quiz->audio_path);

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

    /**
     * Get comprehensive submission results and statistics for a quiz
     */
    public function getQuizResults(Quiz $quiz): array
    {
        $quiz->load([
            'course.enrollments.user',
            'questions',
            'attempts.user',
        ]);

        $totalMarks = (float) $quiz->questions->sum('marks');
        if ($totalMarks <= 0) {
            $totalMarks = 10.0;
        }

        // Enrolled students in this class
        $enrolledStudents = $quiz->course ? $quiz->course->enrollments->map(function ($enrollment) {
            return $enrollment->user;
        })->filter() : collect();

        // Group attempts by user_id
        $attemptsByUser = $quiz->attempts->groupBy('user_id');

        $studentResults = [];
        $completedScores = [];

        foreach ($enrolledStudents as $student) {
            $studentAttempts = $attemptsByUser->get($student->id, collect());
            
            // Get latest or highest scoring completed attempt
            $completedAttempts = $studentAttempts->filter(fn($a) => !is_null($a->completed_at));
            $activeAttempt = $studentAttempts->first(fn($a) => is_null($a->completed_at));

            $bestAttempt = $completedAttempts->sortByDesc('score')->first();

            if ($bestAttempt) {
                $status = 'completed'; // Submitted
                $score = (float) $bestAttempt->score;
                $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 1) : 0;
                $completedScores[] = $score;
                $completedAt = $bestAttempt->completed_at;
                $durationMinutes = $bestAttempt->started_at && $bestAttempt->completed_at 
                    ? $bestAttempt->started_at->diffInMinutes($bestAttempt->completed_at) 
                    : 0;
                $attemptId = $bestAttempt->id;
            } elseif ($activeAttempt) {
                $status = 'in_progress'; // In progress
                $score = null;
                $percentage = null;
                $completedAt = null;
                $durationMinutes = null;
                $attemptId = $activeAttempt->id;
            } else {
                $status = 'not_started'; // Not started
                $score = null;
                $percentage = null;
                $completedAt = null;
                $durationMinutes = null;
                $attemptId = null;
            }

            $studentResults[] = [
                'student_id'        => $student->id,
                'name'              => $student->full_name,
                'email'             => $student->email,
                'avatar_url'        => $student->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name),
                'level_badge'       => $student->level_badge ?? 'Lv.1',
                'status'            => $status,
                'score'             => $score,
                'percentage'        => $percentage,
                'total_marks'       => $totalMarks,
                'completed_at'      => $completedAt,
                'duration_minutes'  => $durationMinutes,
                'attempts_count'    => $studentAttempts->count(),
                'attempt_id'        => $attemptId,
            ];
        }

        // Include students who took quiz but are not in current enrollment list (if any)
        foreach ($attemptsByUser as $userId => $userAttempts) {
            if (!$enrolledStudents->contains('id', $userId)) {
                $user = $userAttempts->first()?->user;
                if (!$user) continue;

                $completedAttempts = $userAttempts->filter(fn($a) => !is_null($a->completed_at));
                $bestAttempt = $completedAttempts->sortByDesc('score')->first();
                $activeAttempt = $userAttempts->first(fn($a) => is_null($a->completed_at));

                if ($bestAttempt) {
                    $status = 'completed';
                    $score = (float) $bestAttempt->score;
                    $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 1) : 0;
                    $completedScores[] = $score;
                    $completedAt = $bestAttempt->completed_at;
                    $durationMinutes = $bestAttempt->started_at && $bestAttempt->completed_at 
                        ? $bestAttempt->started_at->diffInMinutes($bestAttempt->completed_at) 
                        : 0;
                    $attemptId = $bestAttempt->id;
                } else {
                    $status = $activeAttempt ? 'in_progress' : 'not_started';
                    $score = null;
                    $percentage = null;
                    $completedAt = null;
                    $durationMinutes = null;
                    $attemptId = $activeAttempt?->id;
                }

                $studentResults[] = [
                    'student_id'        => $user->id,
                    'name'              => $user->full_name,
                    'email'             => $user->email,
                    'avatar_url'        => $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name),
                    'level_badge'       => $user->level_badge ?? 'Lv.1',
                    'status'            => $status,
                    'score'             => $score,
                    'percentage'        => $percentage,
                    'total_marks'       => $totalMarks,
                    'completed_at'      => $completedAt,
                    'duration_minutes'  => $durationMinutes,
                    'attempts_count'    => $userAttempts->count(),
                    'attempt_id'        => $attemptId,
                    'grading_status'    => $bestAttempt?->grading_status ?? 'graded',
                ];
            }
        }

        // Sort: Submitted students first (descending score), then in_progress and not_started
        usort($studentResults, function ($a, $b) {
            $statusOrder = ['completed' => 1, 'in_progress' => 2, 'not_started' => 3];
            $orderA = $statusOrder[$a['status']] ?? 4;
            $orderB = $statusOrder[$b['status']] ?? 4;
            if ($orderA !== $orderB) {
                return $orderA <=> $orderB;
            }
            if ($a['status'] === 'completed' && $b['status'] === 'completed') {
                return $b['score'] <=> $a['score'];
            }
            return strcmp($a['name'], $b['name']);
        });

        // Calculate statistics
        $totalStudents = count($studentResults);
        $submittedCount = count($completedScores);
        $inProgressCount = collect($studentResults)->where('status', 'in_progress')->count();
        $notStartedCount = collect($studentResults)->where('status', 'not_started')->count();

        $submissionRate = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100, 1) : 0;
        $averageScore = $submittedCount > 0 ? round(array_sum($completedScores) / $submittedCount, 2) : 0;
        $highestScore = $submittedCount > 0 ? max($completedScores) : 0;
        $lowestScore = $submittedCount > 0 ? min($completedScores) : 0;

        $passedCount = collect($completedScores)->filter(fn($sc) => ($totalMarks > 0 && ($sc / $totalMarks) >= 0.5))->count();
        $passRate = $submittedCount > 0 ? round(($passedCount / $submittedCount) * 100, 1) : 0;

        return [
            'quiz'              => $quiz,
            'total_marks'       => $totalMarks,
            'student_results'   => $studentResults,
            'stats'             => [
                'total_students'    => $totalStudents,
                'submitted_count'   => $submittedCount,
                'in_progress_count' => $inProgressCount,
                'not_started_count' => $notStartedCount,
                'submission_rate'   => $submissionRate,
                'average_score'     => $averageScore,
                'highest_score'     => $highestScore,
                'lowest_score'      => $lowestScore,
                'pass_rate'         => $passRate,
                'passed_count'      => $passedCount,
            ]
        ];
    }

    /**
     * Get full details of a specific student attempt
     */
    public function getAttemptDetail(int $attemptId, int $teacherId): array
    {
        $attempt = QuizAttempt::with([
            'quiz.course',
            'quiz.questions.options',
            'user',
            'answers.question.options',
            'answers.option'
        ])->findOrFail($attemptId);

        // Authorization check: Course teacher owner or Admin
        if ($attempt->quiz->course->teacher_id !== $teacherId && auth()->user()->role !== \App\Models\User::ROLE_ADMIN) {
            abort(403, __('Bạn không có quyền xem bài làm này.'));
        }

        $answersByQuestion = $attempt->answers->keyBy('question_id');
        $totalMarks = (float) $attempt->quiz->questions->sum('marks');
        if ($totalMarks <= 0) {
            $totalMarks = 10.0;
        }

        $questionsDetail = [];
        foreach ($attempt->quiz->questions as $question) {
            $answer = $answersByQuestion->get($question->id);
            $selectedOption = $answer?->option;
            $correctOption = $question->options->first(fn($opt) => $opt->is_correct);

            $isCorrect = false;
            if ($question->type === QuestionType::MULTIPLE_CHOICE || $question->type === QuestionType::TRUE_FALSE) {
                $isCorrect = $selectedOption && $selectedOption->is_correct;
            }

            $questionsDetail[] = [
                'id'                  => $question->id,
                'type'                => $question->type->value,
                'type_label'          => $question->type->label(),
                'question_text'       => $question->question_text,
                'image_url'           => $question->image_path ? asset('storage/' . $question->image_path) : null,
                'audio_url'           => $question->audio_path ? asset('storage/' . $question->audio_path) : null,
                'marks'               => (float) $question->marks,
                'essay_grading_type'  => $question->essay_grading_type ?? 'manual',
                'correct_answer_text' => $question->correct_answer_text,
                'case_sensitive'      => (bool) $question->case_sensitive,
                'options'             => $question->options->map(fn($o) => [
                    'id'            => $o->id,
                    'option_text'   => $o->option_text,
                    'is_correct'    => (bool) $o->is_correct,
                    'is_selected'   => $selectedOption && $selectedOption->id === $o->id,
                ])->toArray(),
                'is_answered'         => !is_null($answer),
                'is_correct'          => !is_null($answer?->is_correct) ? (bool) $answer->is_correct : $isCorrect,
                'text_answer'         => $answer?->text_answer,
                'marks_obtained'      => !is_null($answer?->marks_obtained) ? (float) $answer->marks_obtained : null,
                'teacher_feedback'    => $answer?->teacher_feedback,
                'selected_option_id'  => $selectedOption?->id,
            ];
        }

        return [
            'attempt'           => $attempt,
            'student'           => $attempt->user,
            'quiz'              => $attempt->quiz,
            'total_marks'       => $totalMarks,
            'score'             => (float) $attempt->score,
            'grading_status'    => $attempt->grading_status ?? 'graded',
            'percentage'        => $totalMarks > 0 ? round(((float)$attempt->score / $totalMarks) * 100, 1) : 0,
            'started_at'        => $attempt->started_at,
            'completed_at'      => $attempt->completed_at,
            'duration_minutes'  => $attempt->started_at && $attempt->completed_at ? $attempt->started_at->diffInMinutes($attempt->completed_at) : 0,
            'questions'         => $questionsDetail,
        ];
    }

    /**
     * Manually grade essay questions for a quiz attempt.
     */
    public function gradeAttempt(int $attemptId, int $teacherId, array $grades): array
    {
        $attempt = QuizAttempt::with(['quiz.course', 'quiz.questions', 'answers'])->findOrFail($attemptId);

        // Authorization check: Course teacher or Admin
        if ($attempt->quiz->course->teacher_id !== $teacherId && auth()->user()->role !== \App\Models\User::ROLE_ADMIN) {
            abort(403, __('Bạn không có quyền chấm bài làm này.'));
        }

        DB::transaction(function () use ($attempt, $grades) {
            $questionsMap = $attempt->quiz->questions->keyBy('id');

            foreach ($grades as $gradeData) {
                $qId = (int) ($gradeData['question_id'] ?? 0);
                $question = $questionsMap->get($qId);

                if (!$question) {
                    continue;
                }

                $maxMarks = (float) $question->marks;
                $marksObtained = isset($gradeData['marks_obtained']) ? max(0, min($maxMarks, (float)$gradeData['marks_obtained'])) : 0;
                $feedback = isset($gradeData['feedback']) ? trim((string)$gradeData['feedback']) : null;

                $answer = QuizAttemptAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $qId)
                    ->first();

                if ($answer) {
                    $answer->update([
                        'marks_obtained'   => $marksObtained,
                        'is_correct'       => $marksObtained > 0,
                        'teacher_feedback' => $feedback,
                    ]);
                }
            }

            // Recalculate total score from all answers
            $totalScore = (float) QuizAttemptAnswer::where('attempt_id', $attempt->id)->sum('marks_obtained');

            $attempt->update([
                'score'          => $totalScore,
                'grading_status' => 'graded',
            ]);
        });

        return $this->getAttemptDetail($attemptId, $teacherId);
    }
}
