<?php

namespace App\Http\Controllers;

use App\Events\UserEarnedExp;
use App\Models\Blog;
use App\Models\HskLesson;
use App\Models\HskLevel;
use App\Models\HskVocabulary;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Overtrue\Pinyin\Pinyin;

class PageController extends Controller
{
    public function getViewHome()
    {
        $latestBlogs = Blog::with(['author', 'category'])
            ->published()
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('latestBlogs'));
    }

    public function getViewAbout()
    {
        return view('about');
    }

    public function getViewContact()
    {
        return view('contact');
    }

    public function getVIewRoadMap()
    {
        return view('roadmap');
    }

    private function getLevelsWithLessons()
    {
        return HskLevel::with([
            'lessons' => function ($query) {
                $query->orderBy('lesson_number', 'asc');
            }
        ])->orderBy('id', 'asc')->get();
    }

    public function getViewCourses(Request $request)
    {
        $levels = $this->getLevelsWithLessons();

        $levelId = $request->query('level');
        $currentLevel = $levelId ? $levels->firstWhere('id', $levelId) : null;

        return view('course.layout', [
            'levels' => $levels,
            'currentLevel' => $currentLevel,
            'currentLesson' => null,
            'activeTab' => null
        ]);
    }

    public function showCourseLesson($levelSlug, $lessonSlug, $tab = 'tu-vung')
    {
        $levels = $this->getLevelsWithLessons();

        $currentLevel = $levels->firstWhere('slug', $levelSlug);
        if (!$currentLevel) abort(404);

        $currentLesson = HskLesson::with([
            'vocabList',
            'grammarList',
            'dialogueSections.dialogues',
            'practices.sections.questions'
        ])->where('hsk_level_id', $currentLevel->id)->where('slug', $lessonSlug)->firstOrFail();

        $activeTab = $tab;

        return view('course.layout', compact('levels', 'currentLevel', 'currentLesson', 'activeTab'));
    }

    public function getViewCoursesV2(Request $request)
    {
        $levels = $this->getLevelsWithLessons();

        return view('course-v2.index', [
            'levels' => $levels,
            'currentLevel' => null,
            'currentLesson' => null,
            'activeTab' => null
        ]);
    }

    public function showCourseLevelV2($levelSlug)
    {
        $levels = $this->getLevelsWithLessons();

        $currentLevel = $levels->firstWhere('slug', $levelSlug);
        if (!$currentLevel) abort(404);

        return view('course-v2.level', [
            'levels' => $levels,
            'currentLevel' => $currentLevel,
            'currentLesson' => null,
            'activeTab' => null
        ]);
    }

    public function showCourseLessonV2($levelSlug, $lessonSlug, $tab = 'tu-vung')
    {
        $levels = $this->getLevelsWithLessons();

        $currentLevel = $levels->firstWhere('slug', $levelSlug);
        if (!$currentLevel) abort(404);

        $currentLesson = HskLesson::with([
            'vocabList',
            'grammarList',
            'dialogueSections.dialogues',
            'practices.sections.questions'
        ])->where('hsk_level_id', $currentLevel->id)->where('slug', $lessonSlug)->firstOrFail();

        $activeTab = $tab;

        return view('course-v2.show', compact('levels', 'currentLevel', 'currentLesson', 'activeTab'));
    }

    public function getDemoHome(GamificationService $gamificationService)
    {
        $wordOfDay = HskVocabulary::inRandomOrder()->first();

        $suggestedLesson = HskLesson::withCount('vocabList')
            ->whereHas('level', function ($query) {
                $query->where('slug', 'hsk-1')->orWhere('level_code', 'hsk1');
            })
            ->where('lesson_number', 1)
            ->first();

        $completedLessonsCount = 0;
        if (auth()->check()) {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $completedLessonsCount = $user->expTransactions()
                ->where('action_type', 'course_lesson')
                ->distinct('reference_id')
                ->count();
        }

        $leaderboardData = $gamificationService->getGamificationLeaderboard('all_time', 5);
        $initialLeaderboard = $leaderboardData['leaderboard'] ?? [];

        return view('home', compact('wordOfDay', 'suggestedLesson', 'completedLessonsCount', 'initialLeaderboard'));
    }

    public function getViewBlog()
    {
        $featuredBlog = Blog::with(['author', 'category'])
            ->published()
            ->latest()
            ->first();

        $blogs = Blog::with(['author', 'category'])
            ->published()
            ->latest()
            ->paginate(9);

        return view('blog', compact('featuredBlog', 'blogs'));
    }

    /**
     * Display the flashcards study view.
     */
    public function getViewFlashcards(): View
    {
        $allVocabularies = HskVocabulary::where('hsk_version', '3.0')
            ->select('id', 'word', 'pinyin', 'meaning', 'meaning_en', 'level', 'example', 'example_meaning')
            ->get();

        /** @var User $user */
        $user = auth()->user();
        $rememberedIds = $user ? $user->rememberedVocabularies()->pluck('hsk_vocabularies.id')->toArray() : [];

        $vocabularies = $allVocabularies->groupBy('level');

        return view('flashcard', compact('vocabularies', 'rememberedIds'));
    }

    /**
     * Save learned vocabulary to database.
     */
    public function rememberVocabulary(Request $request): JsonResponse
    {
        $request->validate([
            'vocabulary_id' => 'required|exists:hsk_vocabularies,id',
        ]);

        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => __('Vui lòng đăng nhập để lưu tiến độ học tập vào tài khoản.')
            ], 401);
        }

        // Associate vocabulary with user in pivot table
        $user->rememberedVocabularies()->syncWithoutDetaching($request->vocabulary_id);

        // Dispatch event for user earning experience
        event(new UserEarnedExp($user, 'flashcard_remember', (int) $request->vocabulary_id));

        return response()->json([
            'success' => true,
            'message' => __('Đã lưu trạng thái học của từ vựng thành công.')
        ]);
    }

    /**
     * Mark lesson tab as completed and reward user with experience points.
     */
    public function markLessonTab(Request $request, $lessonId): JsonResponse
    {
        $request->validate([
            'tab' => 'required|string|in:tu-vung,hoi-thoai,ngu-phap,luyen-tap',
        ]);

        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => __('Vui lòng đăng nhập để lưu tiến độ và nhận điểm kinh nghiệm.')
            ], 401);
        }

        $lesson = HskLesson::findOrFail($lessonId);

        $tabMapping = [
            'tu-vung'   => 'course_vocab',
            'hoi-thoai' => 'course_dialogue',
            'ngu-phap'  => 'course_grammar',
            'luyen-tap' => 'course_practice',
        ];

        $actionType = $tabMapping[$request->tab];

        // Call GamificationService to get immediate results for UI
        $gamificationService = app(GamificationService::class);
        $result = $gamificationService->awardExp($user, $actionType, (int) $lesson->id);

        // [PERF-2] Merge 4 fresh() into 1 query
        $freshUser = $user->fresh();

        return response()->json([
            'success'      => true,
            'message'      => $result ? __('Hoàn thành tab bài học, bạn nhận được :exp EXP!', ['exp' => $result['exp_gained']]) : __('Đã ghi nhận tiến độ.'),
            'gamification' => $result,
            'user'         => [
                'current_streak'   => $freshUser->current_streak,
                'today_exp'        => $freshUser->today_exp,
                'exp_total'        => $freshUser->exp_total,
                'progress_percent' => $freshUser->daily_progress_percent,
            ],
        ]);
    }

    /**
     * Remove vocabulary from learned list (move back to study list).
     */
    public function unrememberVocabulary(Request $request): JsonResponse
    {
        $request->validate([
            'vocabulary_id' => 'required|exists:hsk_vocabularies,id',
        ]);

        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => __('Vui lòng đăng nhập để thực hiện thao tác này.')
            ], 401);
        }

        $user->rememberedVocabularies()->detach($request->vocabulary_id);

        return response()->json([
            'success' => true,
            'message' => __('Đã chuyển từ vựng về danh sách đang học.')
        ]);
    }

    /**
     * Reset learned vocabulary progress for a specific HSK level.
     */
    public function resetVocabularyProgress(Request $request): JsonResponse
    {
        $request->validate([
            'level' => 'required|integer|min:1|max:9',
        ]);

        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => __('Vui lòng đăng nhập để thực hiện thao tác này.')
            ], 401);
        }

        $vocabIds = HskVocabulary::where('hsk_version', '3.0')
            ->where('level', $request->level)
            ->pluck('id');

        $user->rememberedVocabularies()->detach($vocabIds);

        return response()->json([
            'success' => true,
            'message' => __('Đã đặt lại tiến độ học tập thành công.')
        ]);
    }

    /**
     * API get Gamification Leaderboard
     */
    public function getGamificationLeaderboard(Request $request, GamificationService $gamificationService): JsonResponse
    {
        // [SEC-3] Validate timeframe parameters
        $allowedTimeframes = ['all_time', 'month', 'week'];
        $timeframe = in_array($request->get('timeframe'), $allowedTimeframes)
            ? $request->get('timeframe')
            : 'all_time';

        // [SEC-2] Limit max 50 to avoid query all users table
        $limit = min((int) $request->get('limit', 5), 50);

        // [CQ-2] Removed unused $userId parameter
        $data = $gamificationService->getGamificationLeaderboard($timeframe, $limit);

        return response()->json([
            'success'   => true,
            'data'      => $data['leaderboard'],
            'timeframe' => $data['timeframe'],
        ]);
    }
}
