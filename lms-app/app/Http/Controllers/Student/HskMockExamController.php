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
        if ($request->filled('exam')) {
            $exam = HskMockExam::with('hskLevel')->find($request->get('exam'));
            if ($exam && $exam->hskLevel) {
                $levelNumber = str_replace('hsk', '', strtolower($exam->hskLevel->level_code));
                return redirect()->to(route('student.hsk-mock-exams.show', ['level' => $levelNumber]) . '?exam=' . $exam->id . '#exam-' . $exam->id);
            }
        }

        $userId = auth()->id();

        $hskLevels = $this->hskMockExamService->getHskLevels();

        $userStats = $this->hskMockExamService->getUserStats($userId);
        $completedExamsCount = $userStats['completedExamsCount'];
        $highestScore = $userStats['highestScore'];

        $globalPassRate = $this->hskMockExamService->getGlobalPassRate();
        $totalExamsCount = $this->hskMockExamService->getTotalExamsCount();
        $totalAttempts = $this->hskMockExamService->getTotalAttempts();

        // Leaderboard rankings (top students overall or filtered by level and timeframe)
        $leaderboardLimit = (int) $request->get('limit', 8);
        $leaderboardLevel = $request->get('leaderboard_level');
        $timeframe = $request->get('timeframe', 'all_time');
        $leaderboardData = $this->hskMockExamService->getLeaderboard($leaderboardLevel, $leaderboardLimit, $userId, $timeframe);

        $formatResultItem = function ($result, $index = null) {
            if (!$result) return null;
            $levelCode = strtolower($result->mockExam->hskLevel->level_code ?? 'hsk1');
            $badgeBg = 'bg-slate-500/10 text-slate-700 dark:text-slate-300 border-slate-500/20';
            if (str_contains($levelCode, 'hsk1')) {
                $badgeBg = 'bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-500/20';
            } elseif (str_contains($levelCode, 'hsk2')) {
                $badgeBg = 'bg-blue-500/10 text-blue-700 dark:text-blue-300 border-blue-500/20';
            } elseif (str_contains($levelCode, 'hsk3')) {
                $badgeBg = 'bg-sky-500/10 text-sky-700 dark:text-sky-300 border-sky-500/20';
            } elseif (str_contains($levelCode, 'hsk4')) {
                $badgeBg = 'bg-purple-500/10 text-purple-700 dark:text-purple-300 border-purple-500/20';
            } elseif (str_contains($levelCode, 'hsk5')) {
                $badgeBg = 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-500/20';
            } elseif (str_contains($levelCode, 'hsk6')) {
                $badgeBg = 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border-indigo-500/20';
            }

            $fullName = trim(($result->user->last_name ?? '') . ' ' . ($result->user->first_name ?? __('Người dùng')));
            return [
                'rank' => $index !== null ? ($index + 1) : null,
                'user_id' => $result->user_id,
                'avatar' => $result->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($fullName),
                'name' => $fullName,
                'level' => strtoupper($levelCode),
                'badgeBg' => $badgeBg,
                'score' => $result->total_score . ' ' . __('Điểm'),
                'streak' => (int) ($result->user->current_streak ?? 0),
                'exp_total' => (int) ($result->user->exp_total ?? 0),
                'time' => floor($result->duration_seconds / 60) . 'p ' . str_pad($result->duration_seconds % 60, 2, '0', STR_PAD_LEFT) . 's',
            ];
        };

        $leaderboard = $leaderboardData['topList']->map(function ($result, $index) use ($formatResultItem) {
            return $formatResultItem($result, $index);
        })->values();

        $currentUserRank = $leaderboardData['currentUserRank'];
        $currentUserResult = $leaderboardData['currentUserResult'] ? $formatResultItem($leaderboardData['currentUserResult']) : null;
        if ($currentUserResult && $currentUserRank) {
            $currentUserResult['rank'] = $currentUserRank;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'leaderboard' => $leaderboard,
                'currentUserRank' => $currentUserRank,
                'currentUserResult' => $currentUserResult,
            ]);
        }

        $userHistory = $this->hskMockExamService->getUserExamHistory($userId);

        return view('portal.student.hsk-mock-exams.index', compact(
            'hskLevels',
            'completedExamsCount',
            'highestScore',
            'globalPassRate',
            'totalExamsCount',
            'totalAttempts',
            'leaderboard',
            'leaderboardLevel',
            'currentUserRank',
            'currentUserResult',
            'userHistory'
        ));
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
