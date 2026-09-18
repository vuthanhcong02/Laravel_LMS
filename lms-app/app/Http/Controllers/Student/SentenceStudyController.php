<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\SentenceStudyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SentenceStudyController extends Controller
{
    /**
     * @param SentenceStudyService $sentenceService
     */
    public function __construct(
        protected SentenceStudyService $sentenceService
    ) {}

    /**
     * Display sentence study topics list by HSK level
     */
    public function index(Request $request): View
    {
        $selectedLevel = $request->query('level', 'HSK1');
        $search = trim((string) $request->query('q', ''));
        $mode = $request->query('mode', 'scramble');

        $data = $this->sentenceService->getTopicsData($selectedLevel, $search);

        return view('portal.student.sentences.index', array_merge($data, [
            'levels' => $this->sentenceService->getLevels(),
            'search' => $search,
            'mode' => $mode,
        ]));
    }

    /**
     * Practice screen for interactive sentence builder by topic
     */
    public function practice(Request $request, string $level, string $slug): View
    {
        $mode = $request->query('mode', 'scramble');
        $data = $this->sentenceService->getPracticeData($level, $slug);

        return view('portal.student.sentences.practice', array_merge($data, [
            'mode' => $mode,
            'isRandom' => false,
        ]));
    }

    /**
     * Random sentence practice screen by HSK level
     */
    public function random(Request $request): View
    {
        $selectedLevel = $request->query('level', 'HSK1');
        $mode = $request->query('mode', 'cloze');
        $limit = (int) $request->query('limit', 15);

        $data = $this->sentenceService->getRandomPracticeData($selectedLevel, $mode, $limit);

        return view('portal.student.sentences.practice', array_merge($data, [
            'mode' => $mode,
            'isRandom' => true,
        ]));
    }

    /**
     * Complete a practice session and award EXP
     */
    public function completePractice(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => __('Vui lòng đăng nhập để lưu kết quả và nhận điểm kinh nghiệm.'),
            ]);
        }

        $validated = $request->validate([
            'topic_id'        => 'nullable|string|max:150',
            'level'           => 'required|string|in:HSK1,HSK2,HSK3,HSK4,HSK5,HSK6,HSK7,HSK8,HSK9',
            'mode'            => 'required|string|in:scramble,cloze,dictation',
            'total_sentences' => 'required|integer|min:1|max:50',
            'correct_count'   => 'required|integer|min:0|lte:total_sentences',
            'hints_used'      => 'nullable|integer|min:0|max:10',
            'score'           => 'nullable|integer|min:0',
        ]);

        $result = $this->sentenceService->completePracticeSession(
            $request->user(),
            $validated
        );

        return response()->json($result);
    }

    /**
     * Fetch more random practice sentences via AJAX without page reload
     */
    public function moreRandomSentences(Request $request): JsonResponse
    {
        $level = (string) $request->query('level', 'HSK1');
        $limit = (int) $request->query('limit', 15);

        $sentences = $this->sentenceService->getRandomSentencesOnly($level, $limit);

        return response()->json([
            'success' => true,
            'sentences' => $sentences,
        ]);
    }
}
