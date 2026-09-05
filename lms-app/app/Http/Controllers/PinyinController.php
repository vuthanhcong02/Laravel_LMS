<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
