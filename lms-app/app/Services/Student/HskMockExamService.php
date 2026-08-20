<?php

namespace App\Services\Student;

use App\Models\HskLevel;
use App\Models\HskMockExam;
use App\Models\HskMockExamResult;
use App\Models\HskMockExamUserAnswer;
use Illuminate\Support\Facades\DB;

class HskMockExamService
{
    /**
     * Get all HSK levels with mock exams count
     */
    public function getHskLevels()
    {
        return HskLevel::withCount(['mockExams' => function ($q) {
            $q->where('is_published', true);
        }])->orderBy('level_code')->get();
    }

    /**
     * Get a specific HSK level by code with its mock exams
     */
    public function getHskLevelWithMockExams($levelCode, $userId = null)
    {
        return HskLevel::where('level_code', $levelCode)
            ->with(['mockExams' => function ($q) use ($userId) {
                $q->where('is_published', true)
                    ->withCount('results')
                    ->with(['results' => function ($q2) use ($userId) {
                        if ($userId) {
                            $q2->where('user_id', $userId);
                        } else {
                            // If no user, just load empty to avoid loading all results
                            $q2->whereRaw('1 = 0');
                        }
                    }])
                    ->orderBy('id', 'desc');
            }])
            ->firstOrFail();
    }

    /**
     * Get basic exam info for intro page
     */
    public function getExamForIntro($examId)
    {
        return HskMockExam::where('is_published', true)
            ->with('hskLevel')
            ->findOrFail($examId);
    }

    /**
     * Create a new exam taking session (HskMockExamResult)
     */
    public function createExamSession($examId, $userId)
    {
        return HskMockExamResult::create([
            'user_id' => $userId,
            'hsk_mock_exam_id' => $examId,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    /**
     * Get in-progress exam session with optimized queries to prevent N+1
     */
    public function getExamSession($uuid, $userId)
    {
        return HskMockExamResult::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->with([
                'mockExam.hskLevel',
                'mockExam.sections' => function ($q) {
                    $q->orderBy('order_index');
                },
                'mockExam.sections.questionGroups' => function ($q) {
                    $q->orderBy('order_index');
                },
                'mockExam.sections.questionGroups.questions' => function ($q) {
                    $q->orderBy('order_index');
                },
                'mockExam.sections.questionGroups.questions.options' => function ($q) {
                    $q->orderBy('order_index');
                }
            ])
            ->firstOrFail();
    }

    /**
     * Submit and grade HSK mock exam using standard HSK scoring rules
     */
    public function submitExam($uuid, $userId, array $answers)
    {
        // Check if already submitted/completed to prevent 404 on double clicks
        $alreadyCompleted = HskMockExamResult::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->whereIn('status', ['completed', 'grading'])
            ->first();

        if ($alreadyCompleted) {
            return $alreadyCompleted;
        }

        return DB::transaction(function () use ($uuid, $userId, $answers) {
            $result = HskMockExamResult::where('uuid', $uuid)
                ->where('user_id', $userId)
                ->where('status', 'in_progress')
                ->firstOrFail();

            $exam = HskMockExam::with([
                'hskLevel',
                'sections.questionGroups.questions.options'
            ])->findOrFail($result->hsk_mock_exam_id);

            $result->update(['status' => 'grading']);

            $scores = [
                'listening' => ['correct' => 0, 'total' => 0],
                'reading' => ['correct' => 0, 'total' => 0],
                'writing' => ['correct' => 0, 'total' => 0],
            ];

            foreach ($exam->sections as $section) {
                $skill = strtolower($section->skill_type); // 'listening', 'reading', 'writing'
                if (!isset($scores[$skill])) {
                    $scores[$skill] = ['correct' => 0, 'total' => 0];
                }

                foreach ($section->questionGroups as $group) {
                    foreach ($group->questions as $question) {
                        if ($question->is_example) {
                            continue;
                        }

                        $scores[$skill]['total']++;

                        $studentAns = $answers[$question->id] ?? null;
                        $selectedOptionId = null;
                        $textAnswer = null;
                        $isCorrect = false;

                        if (!empty($studentAns)) {
                            if (is_numeric($studentAns)) {
                                // Student submitted an Option ID (standard single choice)
                                $option = $question->options->firstWhere('id', (int) $studentAns);
                                if ($option) {
                                    $selectedOptionId = $option->id;
                                    $textAnswer = $option->content;
                                    if ($option->is_correct) {
                                        $isCorrect = true;
                                        $scores[$skill]['correct']++;
                                    }
                                }
                            } else {
                                // Student submitted a string answer (matching letter like "A", "E" or text)
                                $textAnswer = trim($studentAns);

                                // Find if any option of this question matches the text
                                $matchedOption = $question->options->first(function ($opt) use ($textAnswer) {
                                    return strtoupper(trim($opt->content ?? '')) === strtoupper($textAnswer);
                                });

                                if ($matchedOption) {
                                    $selectedOptionId = $matchedOption->id;
                                    if ($matchedOption->is_correct) {
                                        $isCorrect = true;
                                        $scores[$skill]['correct']++;
                                    }
                                } else {
                                    // If no option matches, check if it matches the correct option's content directly
                                    $correctOption = $question->options->firstWhere('is_correct', true);
                                    if ($correctOption && strtoupper(trim($correctOption->content ?? '')) === strtoupper($textAnswer)) {
                                        $isCorrect = true;
                                        $scores[$skill]['correct']++;
                                    }
                                }
                            }
                        }

                        HskMockExamUserAnswer::create([
                            'hsk_mock_exam_result_id' => $result->id,
                            'hsk_mock_exam_question_id' => $question->id,
                            'selected_option_id' => $selectedOptionId,
                            'text_answer' => $textAnswer,
                            'is_correct' => $isCorrect,
                        ]);
                    }
                }
            }

            // Calculate standard HSK scores (each section out of 100 points)
            $listeningScore = $scores['listening']['total'] > 0
                ? (int) round(($scores['listening']['correct'] / $scores['listening']['total']) * 100)
                : 0;

            $readingScore = $scores['reading']['total'] > 0
                ? (int) round(($scores['reading']['correct'] / $scores['reading']['total']) * 100)
                : 0;

            $writingScore = $scores['writing']['total'] > 0
                ? (int) round(($scores['writing']['correct'] / $scores['writing']['total']) * 100)
                : 0;

            $totalScore = $listeningScore + $readingScore + $writingScore;

            $result->update([
                'listening_score' => $listeningScore,
                'reading_score' => $readingScore,
                'writing_score' => $writingScore,
                'total_score' => $totalScore,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $exam->increment('attempt_count');

            return $result;
        });
    }

    /**
     * Get result details for completed HSK mock exam
     */
    public function getResultDetail($uuid, $userId)
    {
        return HskMockExamResult::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->with([
                'mockExam.hskLevel',
                'userAnswers.question.options',
                'userAnswers.question.hskMockExamSection'
            ])
            ->firstOrFail();
    }

    /**
     * Get completed exam count and highest score for a user
     */
    public function getUserStats($userId)
    {
        $stats = [
            'completedExamsCount' => 0,
            'highestScore' => 0,
        ];

        if ($userId) {
            $statsData = HskMockExamResult::where('user_id', $userId)
                ->where('status', 'completed')
                ->selectRaw('COUNT(DISTINCT hsk_mock_exam_id) as count, MAX(total_score) as max_score')
                ->first();

            $stats['completedExamsCount'] = $statsData->count ?? 0;
            $stats['highestScore'] = $statsData->max_score ?? 0;
        }

        return $stats;
    }

    /**
     * Get leaderboard
     */
    public function getLeaderboard($levelCode = null, $limit = 10, $currentUserId = null)
    {
        $subQuery = DB::table('hsk_mock_exam_results as r')
            ->selectRaw(
                'r.*, TIMESTAMPDIFF(SECOND, r.started_at, r.completed_at) as duration_seconds,
                ROW_NUMBER() OVER (PARTITION BY r.user_id ORDER BY r.total_score DESC, TIMESTAMPDIFF(SECOND, r.started_at, r.completed_at) ASC) as rn'
            )
            ->where('r.status', 'completed');

        if ($levelCode && $levelCode !== 'all') {
            $subQuery->join('hsk_mock_exams as e', 'r.hsk_mock_exam_id', '=', 'e.id')
                ->join('hsk_levels as l', 'e.hsk_level_id', '=', 'l.id')
                ->where('l.level_code', $levelCode);
        }

        $rankedIds = DB::table(DB::raw("({$subQuery->toSql()}) as ranked"))
            ->mergeBindings($subQuery)
            ->where('rn', 1)
            ->orderByDesc('total_score')
            ->orderBy('duration_seconds')
            ->pluck('id');

        $allRanked = HskMockExamResult::with(['user', 'mockExam.hskLevel'])
            ->selectRaw('*, TIMESTAMPDIFF(SECOND, started_at, completed_at) as duration_seconds')
            ->whereIn('id', $rankedIds)
            ->orderByDesc('total_score')
            ->orderByRaw('TIMESTAMPDIFF(SECOND, started_at, completed_at) ASC')
            ->get();

        $topList = $allRanked->take($limit);

        $currentUserRank = null;
        $currentUserResult = null;

        if ($currentUserId) {
            $userIndex = $allRanked->search(fn($item) => $item->user_id == $currentUserId);

            if ($userIndex !== false) {
                $currentUserRank = $userIndex + 1;
                $currentUserResult = $allRanked[$userIndex];
            }
        }

        return [
            'topList'           => $topList,
            'currentUserRank'   => $currentUserRank,
            'currentUserResult' => $currentUserResult,
        ];
    }
}
