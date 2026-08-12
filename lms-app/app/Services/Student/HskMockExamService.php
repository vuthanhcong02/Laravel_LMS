<?php

namespace App\Services\Student;

use App\Models\HskLevel;
use App\Models\HskMockExamResult;
use App\Models\HskMockExam;

class HskMockExamService
{
    /**
     * Get all HSK levels with mock exams count
     */
    public function getHskLevels()
    {
        return HskLevel::withCount(['mockExams' => function($q) {
            $q->where('is_published', true);
        }])->orderBy('level_code')->get();
    }

    /**
     * Get a specific HSK level by code with its mock exams
     */
    public function getHskLevelWithMockExams($levelCode)
    {
        return HskLevel::where('level_code', $levelCode)
            ->with(['mockExams' => function($q) {
                $q->where('is_published', true)->orderBy('id', 'desc');
            }])
            ->firstOrFail();
    }

    /**
     * Get a specific exam with all its nested structure (sections, groups, questions, options)
     */
    public function getExamForTaking($examId)
    {
        return HskMockExam::where('is_published', true)
            ->with([
                'sections.questionGroups.questions.options'
            ])->findOrFail($examId);
    }

    public function getUserStats($userId)
    {
        $stats = [
            'completedExamsCount' => 0,
            'highestScore' => 0,
        ];

        if ($userId) {
            $statsData = HskMockExamResult::where('user_id', $userId)
                ->where('status', 'completed')
                ->selectRaw('COUNT(*) as count, MAX(total_score) as max_score')
                ->first();

            $stats['completedExamsCount'] = $statsData->count ?? 0;
            $stats['highestScore'] = $statsData->max_score ?? 0;
        }

        return $stats;
    }

    /**
     * Get leaderboard
     */
    public function getLeaderboard($levelCode = null, $limit = 10)
    {
        $query = HskMockExamResult::with(['user', 'mockExam.hskLevel'])
            ->where('status', 'completed');

        if ($levelCode && $levelCode !== 'all') {
            $query->whereHas('mockExam.hskLevel', function ($q) use ($levelCode) {
                $q->where('level_code', $levelCode);
            });
        }

        return $query->orderByDesc('total_score')->take($limit)->get();
    }
}
