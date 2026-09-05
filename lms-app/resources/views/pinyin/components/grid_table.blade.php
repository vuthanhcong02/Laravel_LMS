<table class="text-center border-collapse bg-white w-full" style="font-size:11px; border-spacing:0;">
    <thead>
        <tr>
            <th class="sticky left-0 top-0 z-30 p-0 bg-[#8cb4f5] border border-slate-600 text-slate-800"
                style="min-width:30px; width:30px;">
                <button @click="isFullscreen = !isFullscreen"
                        class="w-full h-full flex items-center justify-center p-1 hover:bg-blue-400/50 transition-colors cursor-pointer"
                        title="{{ __('Phóng to / Thu nhỏ (Phím F)') }}">
                    <span class="material-symbols-outlined" style="font-size:14px;"
                          x-text="isFullscreen ? 'fullscreen_exit' : 'fullscreen'"></span>
                </button>
            </th>
            @foreach($finalsColumns as $colKey => $dbFinalName)
            <th class="p-0.5 bg-[#8cb4f5] border border-slate-600 font-bold text-slate-900 whitespace-nowrap"
                style="min-width:26px;">
                {{ str_replace(['i_zcs', 'i_zh', 'ueng', 'uue', 'uun', 'uu'], ['i', 'i', 'ueng', 'üan', 'ün', 'ü'], $colKey) }}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        {{-- ROW "-" --}}
        <tr class="hover:brightness-95 transition-all">
            <td class="sticky left-0 z-10 text-center p-0.5 bg-[#8cc274] border border-slate-600 font-bold text-slate-900"
                style="min-width:30px; width:30px;">-</td>
            @foreach($finalsColumns as $colKey => $dbFinalName)
                @php $pinyin = $standaloneRow[$colKey] ?? null; @endphp
                <td class="p-0 border border-slate-600 {{ $pinyin ? 'bg-white hover:bg-blue-100' : 'bg-white' }}"
                    style="min-width:26px;">
                    @if($pinyin)
                    <button type="button"
                        @click='currentPinyin = @json($pinyin); selectedTone = (currentPinyin.tones && currentPinyin.tones.length > 0) ? currentPinyin.tones[0] : null'
                        class="w-full h-full px-0.5 py-1 flex items-center justify-center font-medium text-slate-900 hover:text-blue-700 transition-colors active:scale-95 cursor-pointer whitespace-nowrap"
                        style="font-size:11px; min-height:20px;">
                        {{ str_replace(['luu', 'nuu', 'luue', 'nuue', 'nue'], ['lü', 'nü', 'lüan', 'nüan', 'nüe'], $pinyin->full) }}
                    </button>
                    @endif
                </td>
            @endforeach
        </tr>
        @foreach($initials as $initial)
        @php 
            $isJqx = in_array($initial->name, $jqxyInitialNames); 
            $isZcs = in_array($initial->name, ['z', 'c', 's']);
            $isZhChShR = in_array($initial->name, ['zh', 'ch', 'sh', 'r']);
        @endphp
        <tr class="hover:brightness-95 transition-all">
            <td class="sticky left-0 z-10 text-center p-0.5 bg-[#8cc274] border border-slate-600 font-bold text-slate-900"
                style="min-width:30px; width:30px;">
                {{ $initial->name }}
            </td>
            @foreach($finalsColumns as $colKey => $dbFinalName)
                @php
                    $pinyin = null;
                    $dbFinalId = $finalIdByName->get($dbFinalName);
                    // Xử lý 3 cột 'i' đặc biệt
                    if ($colKey === 'i_zcs') {
                        if ($isZcs) $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                    } 
                    elseif ($colKey === 'i_zh') {
                        if ($isZhChShR) $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                    } 
                    elseif ($colKey === 'i') {
                        if (!$isZcs && !$isZhChShR) $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                    }
                    // Xử lý nhóm j/q/x cho u/ü
                    elseif ($isJqx) {
                        if (in_array($dbFinalName, $jqxyHideUGroupFinalNames)) {
                            $pinyin = null; // Cột u/uan/un để trống
                        } elseif (array_key_exists($dbFinalName, $jqxyUeAliasMap)) {
                            $aliasFinalId = $jqxyUeAliasMap[$dbFinalName];
                            $pinyin = $pinyins->get($initial->id . '_' . $aliasFinalId);
                        } else {
                            $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                        }
                    } 
                    // Các cột bình thường
                    else {
                        $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                    }
                @endphp
                <td class="p-0 border border-slate-600 {{ $pinyin ? 'bg-white hover:bg-blue-100' : 'bg-white' }}"
                    style="min-width:26px;">
                    @if($pinyin)
                    <button type="button"
                        @click='currentPinyin = @json($pinyin); selectedTone = (currentPinyin.tones && currentPinyin.tones.length > 0) ? currentPinyin.tones[0] : null'
                        class="w-full h-full px-0.5 py-1 flex items-center justify-center font-medium text-slate-900 hover:text-blue-700 transition-colors active:scale-95 cursor-pointer whitespace-nowrap"
                        style="font-size:11px; min-height:20px;">
                        {{ str_replace(['luu', 'nuu', 'luue', 'nuue', 'nue', 'lue'], ['lü', 'nü', 'lüan', 'nüan', 'nüe', 'lüe'], $pinyin->full) }}
                    </button>
                    @endif
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
