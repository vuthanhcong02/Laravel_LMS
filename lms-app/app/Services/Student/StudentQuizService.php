<?php

namespace App\Services\Student;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\Question;
use App\Models\Option;
use App\Models\Enrollment;
use App\Enums\QuestionType;
use Illuminate\Support\Facades\DB;

class StudentQuizService
{
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
            ->with(['course', 'attempts' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->latest();
            }])
            ->get()
            ->map(function ($quiz) {
                // Find highest score and status
                $completedAttempts = $quiz->attempts->filter(fn($a) => !is_null($a->completed_at));
                $activeAttempt = $quiz->attempts->first(fn($a) => is_null($a->completed_at));

                $quiz->highest_score = $completedAttempts->max('score');
                $quiz->attempts_count = $completedAttempts->count();

                if ($activeAttempt) {
                    $quiz->status = 'in_progress';
                    $quiz->active_attempt_id = $activeAttempt->id;
                } elseif ($quiz->attempts_count > 0) {
                    $quiz->status = 'completed';
                } else {
                    $quiz->status = 'not_started';
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
        $quiz = Quiz::with('course')->findOrFail($quizId);

        // Check enrollment
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('course_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, __('Bạn chưa đăng ký khóa học này.'));
        }

        return $quiz;
    }

    /**
     * Start a new attempt or return the active one
     *
     * @param int $quizId
     * @param int $userId
     * @return QuizAttempt
     */
    public function startAttempt(int $quizId, int $userId): QuizAttempt
    {
        $quiz = $this->getQuizDetails($quizId, $userId);

        // Check if there is an active attempt
        $activeAttempt = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->whereNull('completed_at')
            ->first();

        if ($activeAttempt) {
            // Check if the active attempt is expired
            if ($quiz->time_limit > 0) {
                $elapsedMinutes = now()->diffInMinutes($activeAttempt->started_at);
                if ($elapsedMinutes >= $quiz->time_limit) {
                    // Auto-submit expired attempt with score 0 or recalculated
                    $this->autoSubmitAttempt($activeAttempt);
                } else {
                    return $activeAttempt;
                }
            } else {
                return $activeAttempt;
            }
        }

        // Create new attempt
        return QuizAttempt::create([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'score' => 0,
            'started_at' => now(),
            'completed_at' => null,
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
        $attempt = QuizAttempt::with(['quiz.course'])->findOrFail($attemptId);

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
            abort(400, __('Bài thi này đã được nộp.'));
        }

        // Check time limit
        $quiz = $attempt->quiz;
        if ($quiz->time_limit > 0) {
            $elapsedMinutes = now()->diffInMinutes($attempt->started_at);
            if ($elapsedMinutes >= $quiz->time_limit) {
                $this->autoSubmitAttempt($attempt);
                abort(400, __('Thời gian làm bài đã kết thúc.'));
            }
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
     * @return QuizAttempt
     */
    public function submitAttempt(int $attemptId, int $userId, array $submittedAnswers): QuizAttempt
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
            return $attempt;
        }

        DB::transaction(function () use ($attempt, $submittedAnswers) {
            $quiz = Quiz::with('questions.options')->findOrFail($attempt->quiz_id);
            $totalScore = 0;

            // Clear any temporary progress answers
            QuizAttemptAnswer::where('attempt_id', $attempt->id)->delete();

            foreach ($quiz->questions as $question) {
                $questionId = $question->id;
                $answerData = $submittedAnswers[$questionId] ?? null;

                if ($question->type === QuestionType::MULTIPLE_CHOICE || $question->type === QuestionType::TRUE_FALSE) {
                    $selectedOptionId = !empty($answerData['option_id']) ? (int)$answerData['option_id'] : null;

                    if ($selectedOptionId) {
                        // Check if answer is correct
                        $correctOption = $question->options->first(fn($opt) => $opt->is_correct);
                        $isCorrect = $correctOption && $correctOption->id === $selectedOptionId;

                        if ($isCorrect) {
                            $totalScore += $question->marks;
                        }

                        QuizAttemptAnswer::create([
                            'attempt_id' => $attempt->id,
                            'question_id' => $questionId,
                            'option_id' => $selectedOptionId,
                            'text_answer' => null,
                        ]);
                    }
                } elseif ($question->type === QuestionType::ESSAY) {
                    $textAnswer = $answerData['text_answer'] ?? null;

                    QuizAttemptAnswer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                        'option_id' => null,
                        'text_answer' => $textAnswer,
                    ]);
                    // Essay questions do not add to auto-score until teacher grades them
                }
            }

            $attempt->update([
                'score' => $totalScore,
                'completed_at' => now(),
            ]);
        });

        return $attempt;
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
     * Auto submit an expired attempt
     *
     * @param QuizAttempt $attempt
     * @return void
     */
    private function autoSubmitAttempt(QuizAttempt $attempt)
    {
        // Simply close the attempt with current date and recalculate score based on what was saved
        // (For simplicity, if they haven't saved temporary answers, they get 0)
        $attempt->update([
            'completed_at' => $attempt->started_at->addMinutes($attempt->quiz->time_limit),
        ]);
    }
}
