<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PinyinTone;
use Illuminate\Support\Facades\Cache;

class PinyinQuizController extends Controller
{
    /**
     * Hiển thị trang Luyện Nghe Phản Xạ Pinyin.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $quizData = Cache::rememberForever('pinyin_quiz_data_v2', function () {
            $tones = PinyinTone::with(['pinyin.initial', 'pinyin.final'])
                ->whereNotNull('audio')
                ->where('audio', '!=', '')
                ->get()
                ->map(function ($tone) {
                    return [
                        'id' => $tone->id,
                        'pinyin_id' => $tone->pinyin_id,
                        'display' => $tone->display, // Example: "mā"
                        'tone_number' => $tone->tone, // Tone number 1, 2, 3, 4, 0
                        'audio_path' => $tone->audio, // MP3 file path
                        'full_pinyin' => $tone->pinyin->full ?? '',
                        'initial' => $tone->pinyin->initial->name ?? '',
                        'final' => $tone->pinyin->final->name ?? '',
                    ];
                });

            return $tones->values();
        });

        return view('pinyin.quiz', [
            'quizTonesJson' => json_encode($quizData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
        ]);
    }
}
