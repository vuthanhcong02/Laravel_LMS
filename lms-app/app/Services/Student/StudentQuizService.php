<?php

namespace App\Services\Student;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\Question;
use App\Models\Option;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\GamificationService;
use App\Enums\QuestionType;
use Illuminate\Support\Facades\DB;

class StudentQuizService
{
    public function __construct(private GamificationService $gamificationService)
    {
    }

    /**
     * List all quizzes from enrolled courses of the student
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function listForStudent(int $userId)
    {
        // Get enrolled course IDs
        $courseIds = Enrollment::where('user_id', $userId)
            ->pluck('course_id');

        return Quiz::whereIn('course_id', $courseIds)
            ->with([
                'course',
                'questions:id,quiz_id,marks',
                'attempts' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)->with('answers:id,attempt_id,marks_obtained')->latest();
                }
            ])
            ->get()
            ->map(function ($quiz) {
                $dueValid = is_null($quiz->due_date) || now()->isBefore($quiz->due_date);

                // Check attempts for this quiz
                foreach ($quiz->attempts as $key => $att) {
                    $isExpiredByTime = $quiz->time_limit > 0 && now()->diffInMinutes($att->started_at) >= $quiz->time_limit;
                    $isEmpty = $att->answers->count() === 0 && ((float) $att->score === 0.0 || $att->score === null);

                    // If attempt expired/timed-out with 0 answers & 0 score and deadline is still open:
                    // Discard empty attempt so student can start afresh (Method 2)
                    if ($isEmpty && ($att->completed_at || $isExpiredByTime) && $dueValid) {
                        $att->delete();
                        $quiz->attempts->forget($key);
                        continue;
                    }

                    // If active attempt is expired with answers or past due date -> auto submit
                    if (is_null($att->completed_at) && ($isExpiredByTime || ($quiz->due_date && now()->isAfter($quiz->due_date)))) {
                        $this->autoSubmitAttempt($att);
                    }
                }

                $activeAttempt = $quiz->attempts->first(fn($a) => is_null($a->completed_at));
                $completedAttempts = $quiz->attempts->filter(fn($a) => !is_null($a->completed_at));
                $quiz->highest_score = $completedAttempts->max('score');
                $quiz->attempts_count = $completedAttempts->count();
                $latestAttempt = $quiz->attempts->first();

                if ($activeAttempt) {
                    $quiz->status = 'in_progress';
                    $quiz->active_attempt_id = $activeAttempt->id;
                } elseif ($quiz->attempts_count > 0) {
                    if ($latestAttempt && $latestAttempt->grading_status === 'needs_grading') {
                        $quiz->status = 'needs_grading';
                    } elseif ($latestAttempt && ($latestAttempt->answers->count() === 0 || $latestAttempt->raw_score === 0.0 || $latestAttempt->raw_score === null)) {
                        $quiz->status = 'abandoned';
                    } else {
                        $quiz->status = 'completed';
                    }
                } else {
                    if ($quiz->due_date && now()->isAfter($quiz->due_date)) {
                        $quiz->status = 'expired';
                    } else {
                        $quiz->status = 'not_started';
                    }
                }

                return $quiz;
            });
    }

    /**
     * Get quiz details if student is enrolled in the course
     *
     * @param int $quizId
     * @param int $userId
     * @return Quiz
     */
    public function getQuizDetails(int $quizId, int $userId): Quiz
    {
        $quiz = Quiz::with([
            'course',
            'questions:id,quiz_id,marks',
            'attempts' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->with('answers:id,attempt_id,marks_obtained')->latest();
            }
        ])->findOrFail($quizId);

        // Check if student is enrolled in the course
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('course_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(404);
        }

        $dueValid = is_null($quiz->due_date) || now()->isBefore($quiz->due_date);

        // Discard any empty attempt if deadline is still open, or auto-submit expired active attempt
        foreach ($quiz->attempts as $key => $att) {
            $isExpiredByTime = $quiz->time_limit > 0 && now()->diffInMinutes($att->started_at) >= $quiz->time_limit;
            $isEmpty = $att->answers->count() === 0 && ((float) $att->score === 0.0 || $att->score === null);

            if ($isEmpty && ($att->completed_at || $isExpiredByTime) && $dueValid) {
                $att->delete();
                $quiz->attempts->forget($key);
                continue;
            }

            if (is_null($att->completed_at) && ($isExpiredByTime || ($quiz->due_date && now()->isAfter($quiz->due_date)))) {
                $this->autoSubmitAttempt($att);
            }
        }

        return $quiz;
    }

    /**
     * Start a new attempt or return the active one (Single-attempt enforced)
     *
     * @param int $quizId
     * @param int $userId
     * @return QuizAttempt
     */
    public function startAttempt(int $quizId, int $userId): QuizAttempt
    {
        $quiz = $this->getQuizDetails($quizId, $userId);

        // Check if deadline has passed
        if ($quiz->due_date && now()->isAfter($quiz->due_date)) {
            abort(403, __('Bài kiểm tra này đã quá hạn nộp.'));
        }

        // Single-attempt rule: strictly 1 attempt allowed per student (unless previous was empty & due_date open)
        $existingAttempt = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->latest()
            ->first();

        if ($existingAttempt) {
            $isExpiredByTime = $quiz->time_limit > 0 && now()->diffInMinutes($existingAttempt->started_at) >= $quiz->time_limit;
            $hasNoAnswers = $existingAttempt->answers()->count() === 0 && ((float) $existingAttempt->score === 0.0 || $existingAttempt->score === null);
            $dueValid = is_null($quiz->due_date) || now()->isBefore($quiz->due_date);

            // If attempt was abandoned/empty (0 answers & 0 score) and due date is still open:
            // Remove the stale attempt so student can start afresh (Method 2)
            if ($hasNoAnswers && ($existingAttempt->completed_at || $isExpiredByTime) && $dueValid) {
                $existingAttempt->delete();
                $existingAttempt = null;
            } elseif ($existingAttempt->completed_at) {
                return $existingAttempt;
            } elseif ($isExpiredByTime) {
                $this->autoSubmitAttempt($existingAttempt);
                return $existingAttempt;
            } else {
                return $existingAttempt;
            }
        }

        $totalMarks = (float) $quiz->questions()->sum('marks');

        // Create the single new attempt
        return QuizAttempt::create([
            'quiz_id'     => $quizId,
            'user_id'     => $userId,
            'score'       => 0,
            'raw_score'   => 0,
            'total_marks' => $totalMarks,
            'started_at'  => now(),
            'completed_at'=> null,
        ]);
    }

    /**
     * Get attempt details with questions (hide correct answers for taking)
     *
     * @param int $attemptId
     * @param int $userId
     * @return QuizAttempt
     */
    public function getAttemptForTaking(int $attemptId, int $userId): QuizAttempt
    {
        $attempt = QuizAttempt::with(['quiz.course', 'quiz.questions'])->findOrFail($attemptId);

        if ($attempt->user_id !== $userId) {
            abort(404);
        }

        // Check if student is enrolled in the course
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('course_id', $attempt->quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(404);
        }

        if ($attempt->completed_at) {
            return $attempt;
        }

        // Check time limit or deadline expiration
        $quiz = $attempt->quiz;
        $isExpiredByTime = $quiz->time_limit > 0 && now()->diffInMinutes($attempt->started_at) >= $quiz->time_limit;
        $isExpiredByDueDate = $quiz->due_date && now()->isAfter($quiz->due_date);

        if ($isExpiredByTime || $isExpiredByDueDate) {
            $isEmpty = $attempt->answers()->count() === 0 && ((float) $attempt->score === 0.0 || $attempt->score === null);
            $dueValid = is_null($quiz->due_date) || now()->isBefore($quiz->due_date);

            if ($isEmpty && $dueValid) {
                $attempt->delete();
                abort(303, route('student.quizzes.show', $quiz->id));
            }

            $this->autoSubmitAttempt($attempt);
            return $attempt;
        }

        // Load questions & options (excluding correct flag)
        $attempt->quiz->load(['questions' => function ($query) {
            $query->with(['options' => function ($q) {
                $q->select('id', 'question_id', 'option_text'); // Hide is_correct
            }]);
        }]);

        // Load existing answers to restore progress
        $attempt->load('answers');

        return $attempt;
    }

    /**
     * Submit user answers and calculate score
     *
     * @param int $attemptId
     * @param int $userId
     * @param array $submittedAnswers
     * @return array{attempt: QuizAttempt, exp_result: array|null}
     */
    public function submitAttempt(int $attemptId, int $userId, array $submittedAnswers): array
    {
        $attempt = QuizAttempt::with('quiz')->findOrFail($attemptId);

        if ($attempt->user_id !== $userId) {
            abort(403);
        }

        // Check if student is enrolled in the course
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('course_id', $attempt->quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, __('Bạn không còn quyền truy cập khóa học này.'));
        }

        if ($attempt->completed_at) {
            return [
                'attempt'    => $attempt,
                'exp_result' => null,
            ];
        }

        $expResult = null;

        DB::transaction(function () use ($attempt, $submittedAnswers) {
            $quiz = Quiz::with('questions.options')->findOrFail($attempt->quiz_id);
            $totalScore = 0;

            // Clear any temporary progress answers
            QuizAttemptAnswer::where('attempt_id', $attempt->id)->delete();

            $hasManualEssay = false;

            foreach ($quiz->questions as $question) {
                $questionId = $question->id;
                $answerData = $submittedAnswers[$questionId] ?? null;

                if ($question->type === QuestionType::MULTIPLE_CHOICE || $question->type === QuestionType::TRUE_FALSE) {
                    if (is_array($answerData)) {
                        $selectedOptionId = !empty($answerData['option_id']) ? (int)$answerData['option_id'] : null;
                    } else {
                        $selectedOptionId = !empty($answerData) ? (int)$answerData : null;
                    }

                    $isCorrect = false;
                    $marksObtained = 0;

                    if ($selectedOptionId) {
                        // Check if answer is correct
                        $correctOption = $question->options->first(fn($opt) => $opt->is_correct);
                        $isCorrect = $correctOption && $correctOption->id === $selectedOptionId;

                        if ($isCorrect) {
                            $marksObtained = (float) $question->marks;
                            $totalScore += $marksObtained;
                        }
                    }

                    QuizAttemptAnswer::create([
                        'attempt_id'     => $attempt->id,
                        'question_id'    => $questionId,
                        'option_id'      => $selectedOptionId,
                        'text_answer'    => null,
                        'marks_obtained' => $marksObtained,
                        'is_correct'     => $isCorrect,
                    ]);
                } elseif ($question->type === QuestionType::ESSAY) {
                    if (is_array($answerData)) {
                        $textAnswer = isset($answerData['text_answer']) ? trim((string)$answerData['text_answer']) : null;
                    } else {
                        $textAnswer = is_string($answerData) ? trim($answerData) : null;
                    }
                    $gradingType = $question->essay_grading_type ?? 'manual';

                    if ($gradingType === 'auto' && !empty($question->correct_answer_text)) {
                        // Auto-grade essay against sample answer(s)
                        $isCorrect = $this->checkEssayAnswer(
                            $textAnswer ?? '',
                            $question->correct_answer_text,
                            (bool)$question->case_sensitive
                        );

                        $marksObtained = $isCorrect ? (float) $question->marks : 0;
                        if ($isCorrect) {
                            $totalScore += $marksObtained;
                        }

                        QuizAttemptAnswer::create([
                            'attempt_id'     => $attempt->id,
                            'question_id'    => $questionId,
                            'option_id'      => null,
                            'text_answer'    => $textAnswer,
                            'marks_obtained' => $marksObtained,
                            'is_correct'     => $isCorrect,
                        ]);
                    } else {
                        // Manual grading by teacher
                        $hasManualEssay = true;

                        QuizAttemptAnswer::create([
                            'attempt_id'     => $attempt->id,
                            'question_id'    => $questionId,
                            'option_id'      => null,
                            'text_answer'    => $textAnswer,
                            'marks_obtained' => null,
                            'is_correct'     => null,
                        ]);
                    }
                }
            }

            $rawScore = $totalScore;
            $totalMarks = (float) $quiz->questions->sum('marks');
            $scoreOnScale10 = $totalMarks > 0 ? round(($rawScore / $totalMarks) * 10, 2) : 0;

            $attempt->update([
                'raw_score'      => $rawScore,
                'total_marks'    => $totalMarks,
                'score'          => $scoreOnScale10,
                'grading_status' => $hasManualEssay ? 'needs_grading' : 'graded',
                'completed_at'   => now(),
            ]);
        });

        // Award EXP to student if the quiz is automatically graded and meets minimum score threshold
        $attempt->refresh();
        if ($attempt->grading_status === 'graded') {
            $quiz = $attempt->quiz()->with('questions')->first();
            $totalMarks = (float) ($quiz?->questions->sum('marks') ?: 10);
            $scorePercent = $totalMarks > 0 ? ($attempt->score / $totalMarks) * 100 : 0;
            $minScorePercent = (float) config('gamification.actions.course_quiz.min_score_percent', 40);

            if ($scorePercent >= $minScorePercent) {
                $user = User::find($userId);
                if ($user) {
                    $expResult = $this->gamificationService->awardExp(
                        $user,
                        'course_quiz',
                        $attempt->quiz_id,
                        ['score' => $attempt->score, 'percent' => $scorePercent]
                    );
                }
            }
        }

        return [
            'attempt'    => $attempt,
            'exp_result' => $expResult,
        ];
    }

    /**
     * Match essay response against acceptable answer patterns
     */
    private function checkEssayAnswer(string $userAnswer, string $correctAnswersText, bool $caseSensitive): bool
    {
        if ($userAnswer === '') {
            return false;
        }

        // Split answer patterns (supports separator by ;, |, or newline)
        $acceptableAnswers = preg_split('/[;\n|]+/', $correctAnswersText);
        $cleanUserAnswer = $this->normalizeString($userAnswer, $caseSensitive);

        foreach ($acceptableAnswers as $pattern) {
            $cleanPattern = $this->normalizeString($pattern, $caseSensitive);
            if ($cleanPattern !== '' && $cleanUserAnswer === $cleanPattern) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize string for text matching
     */
    private function normalizeString(string $text, bool $caseSensitive): string
    {
        $text = trim($text);
        if (!$caseSensitive) {
            $text = mb_strtolower($text, 'UTF-8');
        }
        // Remove redundant whitespace between words
        return preg_replace('/\s+/u', ' ', $text);
    }

    /**
     * Get attempt results with correctness information
     *
     * @param int $attemptId
     * @param int $userId
     * @return QuizAttempt
     */
    public function getAttemptResult(int $attemptId, int $userId): QuizAttempt
    {
        $attempt = QuizAttempt::with([
            'quiz.course',
            'quiz.questions.options',
            'quiz.questions.options' => function ($q) {
                $q->orderBy('id');
            },
            'answers'
        ])->findOrFail($attemptId);

        if ($attempt->user_id !== $userId) {
            abort(403);
        }

        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('course_id', $attempt->quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, __('Bạn không còn quyền truy cập khóa học này.'));
        }

        if (!$attempt->completed_at) {
            abort(400, __('Bài thi chưa được nộp.'));
        }

        return $attempt;
    }

    /**
     * Auto submit an expired attempt with scale-10 normalized score
     *
     * @param QuizAttempt $attempt
     * @return void
     */
    private function autoSubmitAttempt(QuizAttempt $attempt)
    {
        $quiz = $attempt->quiz()->with('questions')->first();
        $totalMarks = (float) ($quiz?->questions->sum('marks') ?: 0);
        $rawScore = (float) $attempt->answers()->sum('marks_obtained');
        $scoreOnScale10 = $totalMarks > 0 ? round(($rawScore / $totalMarks) * 10, 2) : 0;
        $hasPendingEssay = $attempt->answers()->whereNull('marks_obtained')->exists();

        $attempt->update([
            'raw_score'      => $rawScore,
            'total_marks'    => $totalMarks,
            'score'          => $scoreOnScale10,
            'grading_status' => $hasPendingEssay ? 'needs_grading' : 'graded',
            'completed_at'   => now(),
        ]);
    }
}
