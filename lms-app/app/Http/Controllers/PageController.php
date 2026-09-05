<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\HskLesson;
use App\Models\HskLevel;
use App\Models\HskVocabulary;
use App\Models\User;
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

    public function getDemoHome()
    {
        $wordOfDay = HskVocabulary::inRandomOrder()->first();

        $suggestedLesson = HskLesson::withCount('vocabList')
            ->whereHas('level', function ($query) {
                $query->where('slug', 'hsk-1')->orWhere('level_code', 'hsk1');
            })
            ->where('lesson_number', 1)
            ->first();

        return view('home', compact('wordOfDay', 'suggestedLesson'));
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

        return response()->json([
            'success' => true,
            'message' => __('Đã lưu trạng thái học của từ vựng thành công.')
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
}
