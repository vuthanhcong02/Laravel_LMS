<table
    class="pinyin-matrix-table text-center border-collapse bg-white w-full border border-slate-300 dark:border-slate-700 select-none"
    style="font-size:11px; border-spacing:0;"
    x-data="pinyinCrosshair"
    @mouseover="handleMouseOver($event)"
    @mouseleave="clearHighlight()">
    <thead>
        <tr>
            <th class="sticky left-0 top-0 z-30 p-0 bg-[#8cb4f5] border-b-2 border-r border-slate-400 dark:border-slate-600 text-slate-800"
                style="min-width:30px; width:30px;">
                <button @click="isFullscreen = !isFullscreen"
                    class="w-full h-full flex items-center justify-center p-1 hover:bg-blue-400/50 transition-colors cursor-pointer"
                    title="{{ __('Phóng to / Thu nhỏ (Phím F)') }}">
                    <span class="material-symbols-outlined" style="font-size:14px;"
                        x-text="isFullscreen ? 'fullscreen_exit' : 'fullscreen'"></span>
                </button>
            </th>
            @foreach ($finalsColumns as $colKey => $dbFinalName)
                <th data-col="{{ $colKey }}"
                    class="p-0.5 bg-[#8cb4f5] border-b-2 border-slate-400 dark:border-slate-600 font-bold text-slate-900 whitespace-nowrap"
                    style="min-width:26px;">
                    {{ str_replace(['i_zcs', 'i_zh', 'ueng', 'uue', 'uun', 'uu', 'ue'], ['i', 'i', 'ueng', 'üan', 'ün', 'ü', 'üe'], $colKey) }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr class="hover:brightness-95 pinyin-row-divider">
            <td class="sticky left-0 z-10 text-center p-0 bg-[#8cc274] border-r border-slate-300 dark:border-slate-700 font-bold text-slate-900"
                style="min-width:30px; width:30px; height:22px; line-height:22px;">-</td>
            @foreach ($finalsColumns as $colKey => $dbFinalName)
                @php $pinyin = $standaloneRow[$colKey] ?? null; @endphp
                <td data-col="{{ $colKey }}" class="p-0"
                    style="min-width:26px; height:22px;">
                    @if ($pinyin)
                        @php
                            $standaloneDisplay = str_replace(
                                ['luu', 'nuu', 'luue', 'nuue', 'nue', 'lue'],
                                ['lü', 'nü', 'lüan', 'nüan', 'nüe', 'lüe'],
                                $pinyin->full
                            );
                            $pId = $pinyin->id ?? null;
                        @endphp
                        <button type="button"
                            @if($pId) data-pid="{{ $pId }}" @endif
                            @click="$dispatch('pinyin-load', { id: {{ $pId ? $pId : 'null' }}, full: '{{ $standaloneDisplay }}' })"
                            class="w-full h-full px-0.5 flex items-center justify-center font-medium text-slate-900 hover:text-blue-700 active:scale-95 cursor-pointer whitespace-nowrap"
                            style="font-size:11px; height:22px; line-height:22px;">
                            {{ $standaloneDisplay }}
                        </button>
                    @endif
                </td>
            @endforeach
        </tr>
        @foreach ($initials as $initial)
            @php
                $isJqx = in_array($initial->name, $jqxyInitialNames);
                $isZcs = in_array($initial->name, ['z', 'c', 's']);
                $isZhChShR = in_array($initial->name, ['zh', 'ch', 'sh', 'r']);
                $isGroupEnd = in_array($initial->name, ['f', 'l', 'h', 's', 'r']);
            @endphp
            <tr class="hover:brightness-95 {{ $isGroupEnd ? 'pinyin-row-divider' : '' }}">
                <td class="sticky left-0 z-10 text-center p-0 bg-[#8cc274] border-r border-slate-300 dark:border-slate-700 font-bold text-slate-900"
                    style="min-width:30px; width:30px; height:22px; line-height:22px;">
                    {{ $initial->name }}
                </td>
                @foreach ($finalsColumns as $colKey => $dbFinalName)
                    @php
                        $pinyin = null;
                        $dbFinalId = $finalIdByName->get($dbFinalName);
                        if ($colKey === 'i_zcs') {
                            if ($isZcs) {
                                $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                            }
                        } elseif ($colKey === 'i_zh') {
                            if ($isZhChShR) {
                                $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                            }
                        } elseif ($colKey === 'i') {
                            if (!$isZcs && !$isZhChShR) {
                                $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                            }
                        }
                        elseif ($isJqx) {
                            if (in_array($dbFinalName, $jqxyHideUGroupFinalNames)) {
                                $pinyin = null;
                            } elseif (array_key_exists($dbFinalName, $jqxyUeAliasMap)) {
                                $aliasFinalId = $jqxyUeAliasMap[$dbFinalName];
                                $pinyin = $pinyins->get($initial->id . '_' . $aliasFinalId);
                            } else {
                                $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                            }
                        }
                        else {
                            $pinyin = $pinyins->get($initial->id . '_' . $dbFinalId);
                        }
                        $displayFull = $pinyin ? str_replace(
                            ['luu', 'nuu', 'luue', 'nuue', 'nue', 'lue'],
                            ['lü',  'nü',  'lüan', 'nüan', 'nüe', 'lüe'],
                            $pinyin->full
                        ) : '';
                    @endphp
                    <td data-col="{{ $colKey }}"
                        class="p-0"
                        style="min-width:26px; height:22px;">
                        @if ($pinyin)
                            @php $pId = $pinyin->id ?? null; @endphp
                            <button type="button"
                                @if($pId) data-pid="{{ $pId }}" @endif
                                @click="$dispatch('pinyin-load', { id: {{ $pId ? $pId : 'null' }}, full: '{{ $displayFull }}' })"
                                class="w-full h-full px-0.5 flex items-center justify-center font-medium text-slate-900 hover:text-blue-700 active:scale-95 cursor-pointer whitespace-nowrap"
                                style="font-size:11px; height:22px; line-height:22px;">
                                {{ $displayFull }}
                            </button>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
