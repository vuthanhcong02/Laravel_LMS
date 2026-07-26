<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinyin;
use App\Models\PinyinFinal;
use App\Models\PinyinInitial;
use Illuminate\Support\Facades\Cache;

class PinyinController extends Controller
{
    public function index()
    {
        $data = Cache::rememberForever('pinyin_data', function () {
            $initials = PinyinInitial::orderBy('order')->get();
            $finals = PinyinFinal::orderBy('order')->get();
            $pinyins = Pinyin::with('tones.examples')->get()->keyBy(function($item) {
                return $item->initial_id . '_' . $item->final_id;
            });
            
            return compact('initials', 'finals', 'pinyins');
        });

        return view('pinyin.index', $data);
    }
}
