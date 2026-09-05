<?php

namespace App\Http\Controllers;

use App\Models\PinyinTone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PinyinQuizController extends Controller
{
    /**
     * Hiển thị trang Luyện Nghe Phản Xạ Pinyin.
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
}

