<?php

namespace App\Http\Controllers;

use App\Models\PinyinTone;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PinyinQuizController extends Controller
{
    /**
     * Display Pinyin Listening Comprehension page.
     *
     * @return View
     */
    public function index(): View
    {
        $quizData = Cache::rememberForever('pinyin_quiz_data_v4', function () {
            $tones = PinyinTone::with(['pinyin.initial', 'pinyin.final'])
                ->whereNotNull('audio')
                ->where('audio', '!=', '')
                ->get()
                ->map(function ($tone) {
                    $cleanFull = str_replace(['uue', 'uun', 'uu'], ['üe', 'ün', 'ü'], $tone->pinyin->full ?? '');
                    $cleanFinal = str_replace(['uue', 'uun', 'uu'], ['üe', 'ün', 'ü'], $tone->pinyin->final->name ?? '');

                    return [
                        'id' => $tone->id,
                        'pinyin_id' => $tone->pinyin_id,
                        'display' => pinyin_tone_to_unicode($tone->display), // Ví dụ: "nǚ", "biān"
                        'raw_display' => $tone->display, // Ví dụ: "nuu3", "bian1"
                        'tone_number' => $tone->tone, // Tone number 1, 2, 3, 4, 0
                        'audio_path' => $tone->audio, // MP3 file path
                        'full_pinyin' => $cleanFull,
                        'initial' => $tone->pinyin->initial->name ?? '',
                        'final' => $cleanFinal,
                    ];
                });

            return $tones->values();
        });

        return view('pinyin.quiz', [
            'quizTonesJson' => json_encode($quizData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
        ]);
    }

    /**
     * Submit Pinyin practice results and reward user with experience points (EXP).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        $request->validate([
            'quiz_length'   => 'required|integer|min:5',
            'correct_count' => 'required|integer|min:0',
            'score'         => 'required|integer|min:0',
        ]);

        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => __('Vui lòng đăng nhập để lưu kết quả và nhận điểm kinh nghiệm.'),
            ]);
        }

        // User must answer at least 40% of questions correctly to avoid spamming
        $minCorrect = (int) ceil($request->quiz_length * 0.4);
        $expResult = null;

        if ($request->correct_count >= $minCorrect) {
            $gamificationService = app(GamificationService::class);
            $expResult = $gamificationService->awardExp($user, 'pinyin_practice');
        }

        return response()->json([
            'success' => true,
            'message' => $expResult ? __('Chúc mừng! Bạn nhận được :exp EXP từ bài luyện tập Pinyin.', ['exp' => $expResult['exp_gained']]) : __('Đã hoàn thành bài luyện tập.'),
            'gamification' => $expResult,
            'user' => [
                'current_streak' => $user->fresh()->current_streak,
                'today_exp' => $user->fresh()->today_exp,
                'exp_total' => $user->fresh()->exp_total,
                'progress_percent' => $user->fresh()->daily_progress_percent,
            ],
        ]);
    }
}
