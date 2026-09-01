<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitHskMockExamRequest;
use App\Models\HskMockExam;
use App\Models\HskMockExamResult;
use App\Services\Student\HskMockExamService;
use Illuminate\Http\Request;

class HskMockExamController extends Controller
{
    public function __construct(protected HskMockExamService $hskMockExamService) {}

    /**
     * Display HSK Mock Exams Menu
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $hskLevels = $this->hskMockExamService->getHskLevels();

        $userStats = $this->hskMockExamService->getUserStats($userId);
        $completedExamsCount = $userStats['completedExamsCount'];
        $highestScore = $userStats['highestScore'];

        $globalPassRate = $this->hskMockExamService->getGlobalPassRate();
        $totalExamsCount = $this->hskMockExamService->getTotalExamsCount();
        $totalAttempts = $this->hskMockExamService->getTotalAttempts();

        // Leaderboard (Top 10 highest scores overall or by level)
        $leaderboardLevel = $request->get('leaderboard_level');
        $leaderboardData = $this->hskMockExamService->getLeaderboard($leaderboardLevel, 10, $userId);
        
        $leaderboard = $leaderboardData['topList']->map(function ($result, $index) {
            $levelCode = strtolower($result->mockExam->hskLevel->level_code ?? 'hsk1');
            $badgeBg = 'bg-slate-100 text-slate-800 border-slate-200';
            if (str_contains($levelCode, 'hsk1')) $badgeBg = 'bg-amber-100 text-amber-800 border-amber-200';
            elseif (str_contains($levelCode, 'hsk2')) $badgeBg = 'bg-blue-100 text-blue-800 border-blue-200';
            elseif (str_contains($levelCode, 'hsk3')) $badgeBg = 'bg-indigo-100 text-indigo-800 border-indigo-200';
            elseif (str_contains($levelCode, 'hsk4')) $badgeBg = 'bg-purple-100 text-purple-800 border-purple-200';
            elseif (str_contains($levelCode, 'hsk5')) $badgeBg = 'bg-rose-100 text-rose-800 border-rose-200';
            elseif (str_contains($levelCode, 'hsk6')) $badgeBg = 'bg-emerald-100 text-emerald-800 border-emerald-200';

            $fullName = trim(($result->user->last_name ?? '') . ' ' . ($result->user->first_name ?? 'Người dùng'));
            return [
                'rank' => $index + 1,
                'avatar' => $result->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($fullName),
                'name' => $fullName,
                'level' => strtoupper($levelCode),
                'badgeBg' => $badgeBg,
                'score' => $result->total_score . ' Điểm',
                'time' => floor($result->duration_seconds / 60) . 'p ' . str_pad($result->duration_seconds % 60, 2, '0', STR_PAD_LEFT) . 's',
            ];
        })->values();

        $currentUserRank = $leaderboardData['currentUserRank'];
        $currentUserResult = $leaderboardData['currentUserResult'];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'leaderboard' => $leaderboard,
                'currentUserRank' => $currentUserRank,
                'currentUserResult' => $currentUserResult,
            ]);
        }

        return view('portal.student.hsk-mock-exams.index', compact('hskLevels', 'completedExamsCount', 'highestScore', 'globalPassRate', 'totalExamsCount', 'totalAttempts', 'leaderboard', 'leaderboardLevel', 'currentUserRank', 'currentUserResult'));
    }

    /**
     * Display list of exams for a specific HSK level
     */
    public function show($level)
    {
        $levelCode = 'hsk' . $level;
        $hskLevel = $this->hskMockExamService->getHskLevelWithMockExams($levelCode, auth()->id());

        return view('portal.student.hsk-mock-exams.show', compact('level', 'hskLevel'));
    }

    /**
     * Start HSK exam session (creates result and redirects to UUID URL)
     */
    public function startExam($level, $id)
    {
        $exam = HskMockExam::where('id', $id)
            ->where('is_published', true)
            ->whereHas('hskLevel', fn($q) => $q->where('level_code', 'hsk' . $level))
            ->firstOrFail();

        $userId = auth()->id();

        $existing = HskMockExamResult::with('mockExam')
            ->where('hsk_mock_exam_id', $exam->id)
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            $elapsedSeconds = now()->diffInSeconds($existing->started_at);
            $totalSeconds = $existing->mockExam->duration * 60;

            if ($elapsedSeconds >= $totalSeconds) {
                $existing->delete();
                $result = $this->hskMockExamService->createExamSession($exam->id, $userId);

                return redirect()->route('student.hsk-mock-exams.take', ['uuid' => $result->uuid]);
            }

            return redirect()->route('student.hsk-mock-exams.take', ['uuid' => $existing->uuid]);
        }

        $result = $this->hskMockExamService->createExamSession($exam->id, $userId);

        return redirect()->route('student.hsk-mock-exams.take', ['uuid' => $result->uuid]);
    }

    /**
     * Show HSK exam taking interface for active UUID session
     */
    public function takeExam($uuid)
    {
        $result = $this->hskMockExamService->getExamSession($uuid, auth()->id());

        // If the exam is completed, redirect to the result page
        if (in_array($result->status, ['completed', 'grading'])) {
            return redirect()->route('student.hsk-mock-exams.result', ['uuid' => $result->uuid]);
        }

        $exam = $result->mockExam;
        $level = str_replace('hsk', '', strtolower($exam->hskLevel->level_code));

        // Calculate exact remaining seconds from started_at
        $elapsedSeconds = now()->diffInSeconds($result->started_at);
        $totalSeconds = $exam->duration * 60;
        $timeRemaining = max(0, $totalSeconds - $elapsedSeconds);

        if ($timeRemaining <= 0) {
            $result->delete();
            return redirect()->route('student.hsk-mock-exams.show', $level)
                ->with('error', 'Bài thi đã quá thời gian làm bài nên đã bị hủy.');
        }

        return view('portal.student.hsk-mock-exams.take', compact('level', 'exam', 'result', 'timeRemaining'));
    }

    /**
     * Submit HSK exam session and calculate standard HSK scores
     */
    public function submitExam(SubmitHskMockExamRequest $request, $uuid)
    {
        $answers = $request->input('answers', []);
        $result = $this->hskMockExamService->submitExam($uuid, auth()->id(), $answers);

        return redirect()->route('student.hsk-mock-exams.result', [
            'uuid' => $result->uuid
        ]);
    }

    /**
     * Display exam results page
     */
    public function showResult($uuid)
    {
        $result = $this->hskMockExamService->getResultDetail($uuid, auth()->id());
        $level = str_replace('hsk', '', strtolower($result->mockExam->hskLevel->level_code));
        return view('portal.student.hsk-mock-exams.result', compact('level', 'result'));
    }
}
