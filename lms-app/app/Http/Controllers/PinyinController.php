<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Pinyin;
use App\Models\PinyinFinal;
use App\Models\PinyinInitial;
use Illuminate\Support\Facades\Cache;
use App\Services\PinyinService;

class PinyinController extends Controller
{
    protected $pinyinService;

    public function __construct(PinyinService $pinyinService)
    {
        $this->pinyinService = $pinyinService;
    }

    public function index()
    {
        $data = $this->pinyinService->getGridData();

        return view('pinyin.index', $data);
    }

    /**
     * Return pinyin detail (tones + examples) by ID.
     */
    public function detail(int $id): JsonResponse
    {
        $data = Cache::rememberForever('pinyin_detail_v1_' . $id, function () use ($id) {
            $pinyin = Pinyin::with('tones.examples')->findOrFail($id);

            return [
                'id'   => $pinyin->id,
                'full' => $pinyin->full,
                'tones' => $pinyin->tones->map(function ($tone) {
                    return [
                        'id'      => $tone->id,
                        'tone'    => $tone->tone,
                        'display' => $tone->display,
                        'audio'   => $tone->audio,
                        'examples' => $tone->examples->map(function ($ex) {
                            return [
                                'id'     => $ex->id,
                                'hanzi'  => $ex->hanzi,
                                'pinyin' => $ex->pinyin,
                                'meaning'=> $ex->meaning,
                                'level'  => $ex->level ?? null,
                            ];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ];
        });

        return response()->json($data);
    }
}
