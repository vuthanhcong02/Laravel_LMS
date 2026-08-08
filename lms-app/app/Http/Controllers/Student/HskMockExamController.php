<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Student\HskMockExamService;

class HskMockExamController extends Controller
{
    public function __construct(protected HskMockExamService $hskMockExamService) {}

    /**
     * Display HSK Mock Exams Menu
     */
    public function index(Request $request)
    {
        $hskLevels = $this->hskMockExamService->getHskLevels();

        $userId = auth()->id();
        $userStats = $this->hskMockExamService->getUserStats($userId);

        $completedExamsCount = $userStats['completedExamsCount'];
        $highestScore = $userStats['highestScore'];

        // Leaderboard (Top 10 highest scores overall or by level)
        $leaderboardLevel = $request->get('leaderboard_level');
        $leaderboard = $this->hskMockExamService->getLeaderboard($leaderboardLevel);

        return view('portal.student.hsk-mock-exams.index', compact('hskLevels', 'completedExamsCount', 'highestScore', 'leaderboard', 'leaderboardLevel'));
    }

    public function show($level)
    {
        $levelCode = 'hsk' . $level;
        $hskLevel = $this->hskMockExamService->getHskLevelWithMockExams($levelCode);

        return view('portal.student.hsk-mock-exams.show', compact('level', 'hskLevel'));
    }

    public function take($level, $id)
    {
        $exam = $this->hskMockExamService->getExamForTaking($id);
        return view('portal.student.hsk-mock-exams.take', compact('level', 'exam'));
    }

    public function submit(Request $request, $level, $id)
    {
        dd($request->all(), 'Logic chấm điểm sẽ được implement tiếp theo.');
    }
}
