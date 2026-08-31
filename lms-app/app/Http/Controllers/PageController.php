<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\HskLesson;
use App\Models\HskVocabulary;
use App\Models\HskLevel;
use Illuminate\Http\Request;

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

    public function getViewCourses(Request $request)
    {
        $levels = HskLevel::with([
            'lessons' => function ($query) {
                $query->orderBy('lesson_number', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        $levelId = $request->query('level');
        $currentLevel = null;
        if ($levelId) {
            $currentLevel = HskLevel::with([
                'lessons' => function ($query) {
                    $query->orderBy('lesson_number', 'asc');
                }
            ])->find($levelId);
        }

        return view('course.layout', [
            'levels' => $levels,
            'currentLevel' => $currentLevel,
            'currentLesson' => null,
            'activeTab' => null
        ]);
    }

    public function showCourseLesson($levelSlug, $lessonSlug, $tab = 'tu-vung')
    {
        $levels = HskLevel::with([
            'lessons' => function ($query) {
                $query->orderBy('lesson_number', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        $currentLevel = HskLevel::where('slug', $levelSlug)->firstOrFail();

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
        $levels = HskLevel::with([
            'lessons' => function ($query) {
                $query->orderBy('lesson_number', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        return view('course-v2.index', [
            'levels' => $levels,
            'currentLevel' => null,
            'currentLesson' => null,
            'activeTab' => null
        ]);
    }

    public function showCourseLevelV2($levelSlug)
    {
        $levels = HskLevel::with([
            'lessons' => function ($query) {
                $query->orderBy('lesson_number', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        $currentLevel = HskLevel::with([
            'lessons' => function ($query) {
                $query->orderBy('lesson_number', 'asc');
            }
        ])->where('slug', $levelSlug)->firstOrFail();

        return view('course-v2.level', [
            'levels' => $levels,
            'currentLevel' => $currentLevel,
            'currentLesson' => null,
            'activeTab' => null
        ]);
    }

    public function showCourseLessonV2($levelSlug, $lessonSlug, $tab = 'tu-vung')
    {
        $levels = HskLevel::with([
            'lessons' => function ($query) {
                $query->orderBy('lesson_number', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        $currentLevel = HskLevel::where('slug', $levelSlug)->firstOrFail();

        $currentLesson = HskLesson::with([
            'vocabList',
            'grammarList',
            'dialogueSections.dialogues',
            'practices.sections.questions'
        ])->where('hsk_level_id', $currentLevel->id)->where('slug', $lessonSlug)->firstOrFail();

        $activeTab = $tab;

        return view('course-v2.show', compact('levels', 'currentLevel', 'currentLesson', 'activeTab'));
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

    public function getViewFlashcards()
    {
        $query = HskVocabulary::where('hsk_version', '3.0')
            ->select('id', 'word', 'pinyin', 'meaning', 'meaning_en', 'level', 'topic', 'example', 'example_meaning');

        // If user is logged in, filter out already learned vocabularies
        if (auth()->check()) {
            $query->whereDoesntHave('usersWhoRemembered', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $allVocabularies = $query->get();

        $vocabularies = $allVocabularies->groupBy('level');

        $topics = $allVocabularies->whereNotNull('topic')->groupBy('topic');

        return view('flashcard', compact('vocabularies', 'topics'));
    }

    /**
     * Save learned vocabulary to database.
     */
    public function rememberVocabulary(Request $request)
    {
        $request->validate([
            'vocabulary_id' => 'required|exists:hsk_vocabularies,id',
        ]);

        // Associate vocabulary with user in pivot table
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->rememberedVocabularies()->syncWithoutDetaching($request->vocabulary_id);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu trạng thái học của từ vựng thành công.'
        ]);
    }
}
