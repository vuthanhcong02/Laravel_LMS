<?php

namespace App\Services;

use App\Models\Pinyin;
use App\Models\PinyinFinal;
use App\Models\PinyinInitial;
use Illuminate\Support\Facades\Cache;

class PinyinService
{
    /**
     * Get the pinyin grid data.
     *
     * @return array
     */
    public function getGridData()
    {
        return Cache::rememberForever('pinyin_data_v10', function () {
            $initialsOrder = ['b', 'p', 'm', 'f', 'd', 't', 'n', 'l', 'g', 'k', 'h', 'z', 'c', 's', 'zh', 'ch', 'sh', 'r', 'j', 'q', 'x'];

            $finalsColumns = [
                'a' => 'a', 'o' => 'o', 'e' => 'e', 'i_zcs' => 'i', 'i_zh' => 'i', 'er' => 'er', 
                'ai' => 'ai', 'ei' => 'ei', 'ao' => 'ao', 'ou' => 'ou', 'an' => 'an', 'en' => 'en', 
                'ang' => 'ang', 'eng' => 'eng', 'ong' => 'ong', 
                'i' => 'i', 'ia' => 'ia', 'iao' => 'iao', 'ie' => 'ie', 'iu' => 'iu', 'ian' => 'ian', 
                'in' => 'in', 'iang' => 'iang', 'ing' => 'ing', 'iong' => 'iong', 
                'u' => 'u', 'ua' => 'ua', 'uo' => 'uo', 'uai' => 'uai', 'ui' => 'ui', 'uan' => 'uan', 
                'un' => 'un', 'uang' => 'uang', 'ueng' => 'ueng', 
                'uu' => 'uu', 'ue' => 'ue', 'uue' => 'uue', 'uun' => 'uun'
            ];

            $allFinals   = PinyinFinal::all();
            $allInitials = PinyinInitial::all();

            $finalIdByName = $allFinals->pluck('id', 'name');
            if (!$finalIdByName->has('ueng')) {
                $finalIdByName->put('ueng', 999);
            }

            $initials = $allInitials->whereIn('name', $initialsOrder)
                                    ->sortBy(fn($m) => array_search($m->name, $initialsOrder))
                                    ->values();

            $allPinyins = Pinyin::with('tones.examples')->get();
            $pinyins = $allPinyins->keyBy(fn($item) => ($item->initial_id ?? 'null') . '_' . $item->final_id);
            $pinyinsByFull = $allPinyins->keyBy('full');

            $hiddenPinyins = ['diang', 'nia', 'nun', 'nuue', 'lo', 'luun', 'shong', 'luue', 'muo', 'sei', 'rei'];
            foreach ($hiddenPinyins as $hidden) {
                $pinyinObj = $pinyinsByFull->get($hidden);
                if ($pinyinObj) {
                    $pinyins->forget(($pinyinObj->initial_id ?? 'null') . '_' . $pinyinObj->final_id);
                }
            }

            $lInitial = $allInitials->firstWhere('name', 'l');
            $ueFinalId = $finalIdByName->get('ue');
            if ($lInitial && $ueFinalId) {
                $pinyins->put($lInitial->id . '_' . $ueFinalId, (object)[
                    'full' => 'lue',
                    'tones' => []
                ]);
            }

            $rInitial = $allInitials->firstWhere('name', 'r');
            $uaFinalId = $finalIdByName->get('ua');
            if ($rInitial && $uaFinalId) {
                $pinyins->put($rInitial->id . '_' . $uaFinalId, (object)[
                    'full' => 'rua',
                    'tones' => []
                ]);
            }

            $tInitial = $allInitials->firstWhere('name', 't');
            $eiFinalId = $finalIdByName->get('ei');
            if ($tInitial && $eiFinalId) {
                $pinyins->put($tInitial->id . '_' . $eiFinalId, (object)[
                    'full' => 'tei',
                    'tones' => []
                ]);
            }

            $kInitial = $allInitials->firstWhere('name', 'k');
            if ($kInitial && $eiFinalId) {
                $pinyins->put($kInitial->id . '_' . $eiFinalId, (object)[
                    'full' => 'kei',
                    'tones' => []
                ]);
            }

            $jqxyInitialNames = ['j', 'q', 'x'];
            
            $jqxyUeAliasMap = [
                'uu'  => $finalIdByName->get('u'),
                'uue' => $finalIdByName->get('uan'),
                'uun' => $finalIdByName->get('un'),
            ];
            $jqxyHideUGroupFinalNames = ['u', 'uan', 'un'];

            $standaloneFullStrings = [
                'a' => 'a', 'o' => 'o', 'e' => 'e', 'er' => 'er', 'ai' => 'ai', 'ao' => 'ao', 'ou' => 'ou', 
                'an' => 'an', 'en' => 'en', 'ang' => 'ang', 'eng' => 'eng',
                'i' => 'yi', 'ia' => 'ya', 'iao' => 'yao', 'ie' => 'ye', 'iu' => 'you', 'ian' => 'yan', 
                'in' => 'yin', 'iang' => 'yang', 'ing' => 'ying', 'iong' => 'yong',
                'u' => 'wu', 'ua' => 'wa', 'uo' => 'wo', 'uai' => 'wai', 'ui' => 'wei', 'uan' => 'wan', 
                'un' => 'wen', 'uang' => 'wang', 'ueng' => 'weng',
                'uu' => 'yu', 'ue' => 'yue', 'uue' => 'yuan', 'uun' => 'yun'
            ];

            $standaloneRow = [];
            foreach ($finalsColumns as $colKey => $dbFinalName) {
                if (isset($standaloneFullStrings[$colKey])) {
                    $standaloneRow[$colKey] = $pinyinsByFull->get($standaloneFullStrings[$colKey]);
                } else {
                    $standaloneRow[$colKey] = null;
                }
            }

            $missingVisualFinals = ['o', 'eng'];
            foreach ($missingVisualFinals as $missingKey) {
                if (empty($standaloneRow[$missingKey])) {
                    $standaloneRow[$missingKey] = (object)[
                        'full' => $missingKey,
                        'tones' => []
                    ];
                }
            }

            return compact('initials', 'finalsColumns', 'finalIdByName', 'pinyins', 'standaloneRow', 'jqxyInitialNames', 'jqxyUeAliasMap', 'jqxyHideUGroupFinalNames');
        });
    }
}
