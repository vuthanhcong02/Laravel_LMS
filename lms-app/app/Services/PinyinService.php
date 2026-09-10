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
        return Cache::rememberForever('pinyin_data', function () {
            $initialsOrder = ['b', 'p', 'm', 'f', 'd', 't', 'n', 'l', 'g', 'k', 'h', 'z', 'c', 's', 'zh', 'ch', 'sh', 'r', 'j', 'q', 'x'];

            $allFinals   = PinyinFinal::all();
            $allInitials = PinyinInitial::all();

            $finalIdByName = $allFinals->pluck('id', 'name');
            if (!$finalIdByName->has('ueng')) {
                $finalIdByName->put('ueng', 999);
            }

            $uFinalName   = $finalIdByName->has('ü') ? 'ü' : 'uu';
            $ueFinalName  = $finalIdByName->has('üe') ? 'üe' : 'ue';
            $uanFinalName = $finalIdByName->has('üan') ? 'üan' : 'uue';
            $unFinalName  = $finalIdByName->has('ün') ? 'ün' : 'uun';

            $finalsColumns = [
                'a' => 'a',
                'o' => 'o',
                'e' => 'e',
                'i_zcs' => 'i',
                'i_zh' => 'i',
                'er' => 'er',
                'ai' => 'ai',
                'ei' => 'ei',
                'ao' => 'ao',
                'ou' => 'ou',
                'an' => 'an',
                'en' => 'en',
                'ang' => 'ang',
                'eng' => 'eng',
                'ong' => 'ong',
                'i' => 'i',
                'ia' => 'ia',
                'iao' => 'iao',
                'ie' => 'ie',
                'iu' => 'iu',
                'ian' => 'ian',
                'in' => 'in',
                'iang' => 'iang',
                'ing' => 'ing',
                'iong' => 'iong',
                'u' => 'u',
                'ua' => 'ua',
                'uo' => 'uo',
                'uai' => 'uai',
                'ui' => 'ui',
                'uan' => 'uan',
                'un' => 'un',
                'uang' => 'uang',
                'ueng' => 'ueng',
                'uu' => $uFinalName,
                'ue' => $ueFinalName,
                'uue' => $uanFinalName,
                'uun' => $unFinalName
            ];

            $finalsColumns = [
                'a' => 'a',
                'o' => 'o',
                'e' => 'e',
                'i_zcs' => 'i',
                'i_zh' => 'i',
                'er' => 'er',
                'ai' => 'ai',
                'ei' => 'ei',
                'ao' => 'ao',
                'ou' => 'ou',
                'an' => 'an',
                'en' => 'en',
                'ang' => 'ang',
                'eng' => 'eng',
                'ong' => 'ong',
                'i' => 'i',
                'ia' => 'ia',
                'iao' => 'iao',
                'ie' => 'ie',
                'iu' => 'iu',
                'ian' => 'ian',
                'in' => 'in',
                'iang' => 'iang',
                'ing' => 'ing',
                'iong' => 'iong',
                'u' => 'u',
                'ua' => 'ua',
                'uo' => 'uo',
                'uai' => 'uai',
                'ui' => 'ui',
                'uan' => 'uan',
                'un' => 'un',
                'uang' => 'uang',
                'ueng' => 'ueng',
                'uu' => $uFinalName,
                'ue' => $ueFinalName,
                'uue' => $uanFinalName,
                'uun' => $unFinalName
            ];

            $initials = $allInitials->whereIn('name', $initialsOrder)
                ->sortBy(fn($m) => array_search($m->name, $initialsOrder))
                ->values();

            $allPinyins = Pinyin::select(['id', 'initial_id', 'final_id', 'full'])->get();
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
            $ueFinalId = $finalIdByName->get($ueFinalName);
            if ($lInitial && $ueFinalId) {
                $luePinyin = $pinyinsByFull->get('lue') ?? $pinyinsByFull->get('lüe') ?? (object)[
                    'id' => null,
                    'full' => 'lue',
                    'tones' => []
                ];
                $pinyins->put($lInitial->id . '_' . $ueFinalId, $luePinyin);
            }

            $nInitial = $allInitials->firstWhere('name', 'n');
            if ($nInitial && $ueFinalId) {
                $nuePinyin = $pinyinsByFull->get('nue') ?? $pinyinsByFull->get('nüe');
                if ($nuePinyin) {
                    $pinyins->put($nInitial->id . '_' . $ueFinalId, $nuePinyin);
                }
            }

            $rInitial = $allInitials->firstWhere('name', 'r');
            $uaFinalId = $finalIdByName->get('ua');
            if ($rInitial && $uaFinalId) {
                $pinyins->put($rInitial->id . '_' . $uaFinalId, (object)[
                    'id' => null,
                    'full' => 'rua',
                    'tones' => []
                ]);
            }

            $tInitial = $allInitials->firstWhere('name', 't');
            $eiFinalId = $finalIdByName->get('ei');
            if ($tInitial && $eiFinalId) {
                $pinyins->put($tInitial->id . '_' . $eiFinalId, (object)[
                    'id' => null,
                    'full' => 'tei',
                    'tones' => []
                ]);
            }

            $kInitial = $allInitials->firstWhere('name', 'k');
            if ($kInitial && $eiFinalId) {
                $pinyins->put($kInitial->id . '_' . $eiFinalId, (object)[
                    'id' => null,
                    'full' => 'kei',
                    'tones' => []
                ]);
            }

            $jqxyInitialNames = ['j', 'q', 'x'];

            $juPinyin = $pinyinsByFull->get('ju');
            $juFinalId = $juPinyin ? $juPinyin->final_id : $finalIdByName->get('u');

            $juePinyin = $pinyinsByFull->get('jue');
            $jueFinalId = $juePinyin ? $juePinyin->final_id : $finalIdByName->get($ueFinalName);

            $juanPinyin = $pinyinsByFull->get('juan');
            $juanFinalId = $juanPinyin ? $juanPinyin->final_id : $finalIdByName->get('uan');

            $junPinyin = $pinyinsByFull->get('jun');
            $junFinalId = $junPinyin ? $junPinyin->final_id : $finalIdByName->get('un');

            $jqxyUeAliasMap = [
                $uFinalName   => $juFinalId,
                $uanFinalName => $juanFinalId,
                $unFinalName  => $junFinalId,
                'uu'          => $juFinalId,
                'uue'         => $juanFinalId,
                'uun'         => $junFinalId,
                'ü'           => $juFinalId,
                'üan'         => $juanFinalId,
                'ün'          => $junFinalId,
                'ue'          => $jueFinalId,
                'üe'          => $jueFinalId,
            ];
            $jqxyHideUGroupFinalNames = ['u', 'uan', 'un'];

            $nInitialId = $allInitials->firstWhere('name', 'n')->id ?? null;
            $lInitialId = $allInitials->firstWhere('name', 'l')->id ?? null;
            
            $uColumnId = $finalIdByName->get($uFinalName);
            $ueColumnId = $finalIdByName->get($ueFinalName);
            $uanColumnId = $finalIdByName->get($uanFinalName);
            
            if ($nInitialId) {
                if ($p = ($pinyinsByFull->get('nuu') ?? $pinyinsByFull->get('nü'))) $pinyins->put($nInitialId . '_' . $uColumnId, $p);
                if ($p = ($pinyinsByFull->get('nue') ?? $pinyinsByFull->get('nüe'))) $pinyins->put($nInitialId . '_' . $ueColumnId, $p);
                if ($p = ($pinyinsByFull->get('nuue') ?? $pinyinsByFull->get('nüan'))) $pinyins->put($nInitialId . '_' . $uanColumnId, $p);
            }
            if ($lInitialId) {
                if ($p = ($pinyinsByFull->get('luu') ?? $pinyinsByFull->get('lü'))) $pinyins->put($lInitialId . '_' . $uColumnId, $p);
                if ($p = ($pinyinsByFull->get('lue') ?? $pinyinsByFull->get('lüe'))) $pinyins->put($lInitialId . '_' . $ueColumnId, $p);
                if ($p = ($pinyinsByFull->get('luue') ?? $pinyinsByFull->get('lüan'))) $pinyins->put($lInitialId . '_' . $uanColumnId, $p);
            }

            $standaloneFullStrings = [
                'a' => 'a',
                'o' => 'o',
                'e' => 'e',
                'er' => 'er',
                'ai' => 'ai',
                'ao' => 'ao',
                'ou' => 'ou',
                'an' => 'an',
                'en' => 'en',
                'ang' => 'ang',
                'eng' => 'eng',
                'i' => 'yi',
                'ia' => 'ya',
                'iao' => 'yao',
                'ie' => 'ye',
                'iu' => 'you',
                'ian' => 'yan',
                'in' => 'yin',
                'iang' => 'yang',
                'ing' => 'ying',
                'iong' => 'yong',
                'u' => 'wu',
                'ua' => 'wa',
                'uo' => 'wo',
                'uai' => 'wai',
                'ui' => 'wei',
                'uan' => 'wan',
                'un' => 'wen',
                'uang' => 'wang',
                'ueng' => 'weng',
                'uu' => 'yu',
                'ue' => 'yue',
                'uue' => 'yuan',
                'uun' => 'yun'
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
                        'id' => null,
                        'full' => $missingKey,
                        'tones' => []
                    ];
                }
            }

            return compact('initials', 'finalsColumns', 'finalIdByName', 'pinyins', 'standaloneRow', 'jqxyInitialNames', 'jqxyUeAliasMap', 'jqxyHideUGroupFinalNames');
        });
    }
}
