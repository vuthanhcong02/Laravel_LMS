<?php

namespace App\Services\Student;

use App\Models\HskLevel;
use App\Models\HskMockExamResult;

class HskMockExamService
{
    /**
     * Get all HSK levels with mock exams count
     */
    public function getHskLevels()
    {
        return HskLevel::withCount('mockExams')->orderBy('level_code')->get();
    }

    /**
     * Get a specific HSK level by code with its mock exams
     */
    public function getHskLevelWithMockExams($levelCode)
    {
        return HskLevel::where('level_code', $levelCode)->with('mockExams')->firstOrFail();
    }

    /**
     * Get user statistics
     */
    public function getUserStats($userId)
    {
        $stats = [
            'completedExamsCount' => 0,
            'highestScore' => 0,
        ];

        if ($userId) {
            $stats['completedExamsCount'] = HskMockExamResult::where('user_id', $userId)
                ->where('status', 'completed')
                ->count();

            $stats['highestScore'] = HskMockExamResult::where('user_id', $userId)
                ->where('status', 'completed')
                ->max('total_score') ?? 0;
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
