<div x-show="activeTab === 'hoi-thoai'" 
     x-data="{ 
         modePinyin: {{ hsk_should_show_pinyin($currentLesson->level ?? null) ? 'true' : 'false' }}, 
         modeHanyu: true, 
         modeNghia: true, 
         modeLuyenDich: false,
         modeGo: false, 
         currentDialogueSectionIdx: 0, 
         typeInputs: {},
         transInputs: {},
         checkTyping(input, target) {
             if (!input || !target) return null;
             let cleanInput = input.replace(/[^\p{L}\p{N}]/gu, '').toLowerCase();
             let cleanTarget = target.replace(/[^\p{L}\p{N}]/gu, '').toLowerCase();
             return cleanInput === cleanTarget;
         }
     }"
     class="space-y-4">
    @if(isset($currentLesson) && $currentLesson->dialogueSections && $currentLesson->dialogueSections->count() > 0)
        <!-- Control Toggles -->
        <div class="flex items-center gap-1.5 p-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl overflow-x-auto no-scrollbar">
            <div class="px-2.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest shrink-0">{{ __('Hiển thị:') }}</div>
            <button @click="modePinyin = !modePinyin" 
                    :class="modePinyin ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-eye" x-show="modePinyin"></i>
                <i class="fa-solid fa-eye-slash opacity-50" x-show="!modePinyin"></i>
                <span>Pinyin</span>
            </button>
            <button @click="modeHanyu = !modeHanyu" 
                    :class="modeHanyu ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-eye" x-show="modeHanyu"></i>
                <i class="fa-solid fa-eye-slash opacity-50" x-show="!modeHanyu"></i>
                <span>Hán Tự</span>
            </button>
            <button @click="modeNghia = !modeNghia" 
                    :class="modeNghia ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-eye" x-show="modeNghia"></i>
                <i class="fa-solid fa-eye-slash opacity-50" x-show="!modeNghia"></i>
                <span>Dịch Nghĩa</span>
            </button>
            <div class="w-px h-4 bg-[#e8e2d9] dark:bg-[#2d2926] mx-1 shrink-0"></div>
            <button @click="modeLuyenDich = !modeLuyenDich; if(modeLuyenDich) modeGo = false;" 
                    :class="modeLuyenDich ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-pen-nib" :class="modeLuyenDich ? 'text-[#e07a5f]' : ''"></i>
                <span>Luyện dịch</span>
            </button>
            <button @click="modeGo = !modeGo; if(modeGo) modeLuyenDich = false;" 
                    :class="modeGo ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-keyboard" :class="modeGo ? 'text-[#e07a5f]' : ''"></i>
                <span>Luyện gõ</span>
            </button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Main content (Left column) -->
            <div class="lg:col-span-3 order-2 lg:order-1 relative min-h-[400px]">
                @foreach ($currentLesson->dialogueSections as $sIdx => $section)
                    <div x-show="currentDialogueSectionIdx === {{ $sIdx }}" 
                         style="display: none;" 
                         class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-4 gap-4">
                            <div class="space-y-0.5">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $section->title }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">{{ __('Nghe và luyện tập hội thoại') }}</p>
                            </div>
                            @if($section->audio_path)
                                @php
                                    $audioSrc = str_starts_with($section->audio_path, 'http') 
                                        ? $section->audio_path 
                                        : (str_starts_with($section->audio_path, 'audio/') 
                                            ? '/storage/hsk_media/' . $section->audio_path 
                                            : (str_starts_with($section->audio_path, '/storage/') 
                                                ? $section->audio_path 
                                                : '/storage/hsk_media/audio/' . $section->audio_path));
                                @endphp
                                <div class="w-full sm:w-72 md:w-80 shrink-0">
                                    <audio controls class="w-full h-9 rounded-xl focus:outline-none" src="{{ $audioSrc }}"></audio>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-3">
                            @foreach($section->dialogues as $lineIdx => $dialogue)
                                @php
                                    $roleColors = ['text-[#e07a5f]', 'text-emerald-600 dark:text-emerald-400', 'text-blue-600 dark:text-blue-400', 'text-purple-600 dark:text-purple-400'];
                                    $colorIndex = crc32($dialogue->role) % count($roleColors);
                                    $roleColor = $roleColors[$colorIndex];
                                    $lineAudio = $dialogue->audio_path 
                                        ? (str_starts_with($dialogue->audio_path, 'http') ? $dialogue->audio_path : (str_starts_with($dialogue->audio_path, 'audio/') ? '/storage/hsk_media/' . $dialogue->audio_path : '/storage/hsk_media/audio/' . $dialogue->audio_path))
                                        : null;
                                    $playParam = $lineAudio ? $lineAudio : addslashes($dialogue->character);
                                @endphp
                                <div class="p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1 relative group">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold {{ $roleColor }}">{{ $dialogue->role }}:</span>
                                        <button onclick="window.playAudio('{{ $playParam }}')" 
                                                class="w-7 h-7 rounded-lg bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 hover:text-[#e07a5f] hover:border-[#e07a5f] opacity-70 group-hover:opacity-100 transition-all flex items-center justify-center btn-tactile shadow-2xs"
                                                title="{{ __('Nghe phát âm câu này') }}">
                                            <i class="fa-solid fa-volume-high text-[11px]"></i>
                                        </button>
                                    </div>
                                    <!-- Dynamic text rendering based on modePinyin and modeHanyu -->
                                    <div class="flex flex-col gap-1 mt-1 cursor-pointer" onclick="window.playAudio('{{ $playParam }}')" title="{{ __('Bấm để nghe phát âm') }}">
                                        <div class="flex flex-wrap items-end gap-x-0.5 leading-none" x-data="{ l_c: '{{ addslashes($dialogue->character) }}', l_p: '{{ addslashes($dialogue->pinyin) }}' }">
                                            @php
                                                $pArr = array_filter(preg_split('/\s+/', trim($dialogue->pinyin)));
                                                $hArr = preg_split('//u', preg_replace('/\s+/u', '', $dialogue->character), -1, PREG_SPLIT_NO_EMPTY);
                                            @endphp
                                            <!-- Pinyin & Hanzi combined -->
                                            <template x-if="modePinyin && modeHanyu">
                                                <div class="flex flex-wrap items-end gap-x-0.5 leading-none zh-text">
                                                    @if(empty(trim($dialogue->pinyin)) || !preg_match('/\p{Han}/u', $dialogue->character))
                                                        <span class="text-sm font-medium zh-text text-slate-800 dark:text-slate-100 tracking-wide">{{ $dialogue->character }}</span>
                                                    @else
                                                        @foreach($hArr as $i => $char)
                                                            @if(preg_match('/\p{Han}/u', $char))
                                                                <ruby class="leading-none" style="ruby-position: over;">
                                                                    <span class="text-sm font-medium zh-text text-slate-800 dark:text-slate-100">{{ $char }}</span>
                                                                    <rt class="tracking-tighter text-[10px] text-[#e07a5f] font-normal mb-0.5 select-none">{{ $pArr[$i] ?? '' }}</rt>
                                                                </ruby>
                                                            @else
                                                                <span class="text-sm font-medium leading-none translate-y-[-2px] text-slate-800 dark:text-slate-100">{{ $char }}</span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </template>
                                            <!-- Pinyin only -->
                                            <p x-show="modePinyin && !modeHanyu" class="text-xs font-medium text-[#e07a5f] italic tracking-wide" x-text="l_p"></p>
                                            <!-- Hanzi only -->
                                            <p x-show="modeHanyu && !modePinyin" class="text-sm font-medium zh-text text-slate-800 dark:text-slate-100 tracking-wide leading-relaxed" x-text="l_c"></p>
                                        </div>
                                        <!-- Hidden state -->
                                        <p x-show="!modePinyin && !modeHanyu" class="text-xs text-slate-400 dark:text-slate-600 italic py-1 opacity-60">
                                            ({{ __('Đang ẩn nội dung') }})
                                        </p>
                                    </div>
                                    <div x-show="modeNghia" class="text-xs text-slate-500 dark:text-slate-400 font-medium pt-2 mt-1 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                                        {{ $dialogue->translation }}
                                    </div>
                                    @php
                                        $isHanzi = preg_match('/\p{Han}/u', $dialogue->character);
                                    @endphp
                                    <div x-show="modeLuyenDich" class="mt-2 w-full">
                                        <div class="relative">
                                            <textarea rows="2" placeholder="{{ $isHanzi ? __('Luyện dịch sang tiếng Việt...') : __('(Không áp dụng cho câu này)') }}"
                                                class="w-full px-3 py-2 pr-8 border rounded-xl text-xs focus:outline-none transition-colors disabled:bg-slate-100 dark:disabled:bg-[#2a2624] disabled:text-slate-400 disabled:cursor-not-allowed disabled:opacity-70 resize-y min-h-[40px] max-h-[150px]"
                                                :class="
                                                    !transInputs['{{ $sIdx }}_{{ $lineIdx }}'] ? 'border-[#e8e2d9] dark:border-[#2d2926] focus:border-[#e07a5f] bg-white dark:bg-[#181615] text-slate-800 dark:text-white' :
                                                    (checkTyping(transInputs['{{ $sIdx }}_{{ $lineIdx }}'], '{{ addslashes($dialogue->translation) }}') 
                                                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' 
                                                        : 'border-rose-400 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400')
                                                "
                                                @if(!$isHanzi) disabled @endif
                                                x-model="transInputs['{{ $sIdx }}_{{ $lineIdx }}']"></textarea>
                                            <!-- Status Icon -->
                                            <div class="absolute right-3 top-1/2 -translate-y-1/2" x-show="transInputs['{{ $sIdx }}_{{ $lineIdx }}']" x-cloak>
                                                <i class="fa-solid fa-circle-check text-emerald-500" x-show="checkTyping(transInputs['{{ $sIdx }}_{{ $lineIdx }}'], '{{ addslashes($dialogue->translation) }}') === true"></i>
                                                <i class="fa-solid fa-circle-xmark text-rose-500" x-show="checkTyping(transInputs['{{ $sIdx }}_{{ $lineIdx }}'], '{{ addslashes($dialogue->translation) }}') === false"></i>
                                            </div>
                                        </div>
                                        @if($isHanzi)
                                            <!-- Reveal Answer Button -->
                                            <div class="mt-1.5 text-[10px] text-slate-400 hover:text-[#e07a5f] cursor-pointer transition-colors flex items-center gap-1 w-fit" 
                                                 @click="transInputs['{{ $sIdx }}_{{ $lineIdx }}'] = '{{ addslashes($dialogue->translation) }}'">
                                                <i class="fa-solid fa-lightbulb"></i> Xem đáp án
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Typing exercise input -->
                                    <div x-show="modeGo" class="mt-2 w-full">
                                        <div class="relative">
                                            <textarea rows="2" placeholder="{{ $isHanzi ? __('Luyện gõ câu hội thoại này...') : __('(Không áp dụng cho câu này)') }}"
                                                class="w-full px-3 py-2 pr-8 border rounded-xl text-xs focus:outline-none transition-colors disabled:bg-slate-100 dark:disabled:bg-[#2a2624] disabled:text-slate-400 disabled:cursor-not-allowed disabled:opacity-70 resize-y min-h-[40px] max-h-[150px]"
                                                :class="
                                                    !typeInputs['{{ $sIdx }}_{{ $lineIdx }}'] ? 'border-[#e8e2d9] dark:border-[#2d2926] focus:border-[#e07a5f] bg-white dark:bg-[#181615] text-slate-800 dark:text-white' :
                                                    (checkTyping(typeInputs['{{ $sIdx }}_{{ $lineIdx }}'], '{{ addslashes($dialogue->character) }}') 
                                                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' 
                                                        : 'border-rose-400 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400')
                                                "
                                                @if(!$isHanzi) disabled @endif
                                                x-model="typeInputs['{{ $sIdx }}_{{ $lineIdx }}']"></textarea>
                                            <!-- Status Icon -->
                                            <div class="absolute right-3 top-1/2 -translate-y-1/2" x-show="typeInputs['{{ $sIdx }}_{{ $lineIdx }}']" x-cloak>
                                                <i class="fa-solid fa-circle-check text-emerald-500" x-show="checkTyping(typeInputs['{{ $sIdx }}_{{ $lineIdx }}'], '{{ addslashes($dialogue->character) }}') === true"></i>
                                                <i class="fa-solid fa-circle-xmark text-rose-500" x-show="checkTyping(typeInputs['{{ $sIdx }}_{{ $lineIdx }}'], '{{ addslashes($dialogue->character) }}') === false"></i>
                                            </div>
                                        </div>
                                        @if($isHanzi)
                                            <!-- Reveal Answer Button -->
                                            <div class="mt-1.5 text-[10px] text-slate-400 hover:text-[#e07a5f] cursor-pointer transition-colors flex items-center gap-1 w-fit" 
                                                 @click="typeInputs['{{ $sIdx }}_{{ $lineIdx }}'] = '{{ addslashes($dialogue->character) }}'">
                                                <i class="fa-solid fa-lightbulb"></i> Xem đáp án
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Playlist Menu navigation (Right column) -->
            <div class="lg:col-span-1 order-1 lg:order-2 space-y-4">
                <div class="bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl p-4 text-left">
                    <h5 class="text-sm font-bold text-slate-900 dark:text-white border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-list-ol text-[#e07a5f]"></i>
                        <span>{{ __('Điều hướng bài khóa') }}</span>
                    </h5>
                    <div class="flex flex-col space-y-2 relative before:content-[''] before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-px before:bg-[#e8e2d9] dark:before:bg-[#2d2926]">
                        @foreach ($currentLesson->dialogueSections as $sIdx => $section)
                            @php
                                $titleParts = preg_split('/\s*[\(（]\s*/', $section->title);
                                $hanTitle = trim($titleParts[0] ?? $section->title);
                                $viTitle = isset($titleParts[1]) ? trim(rtrim($titleParts[1], ')）')) : '';
                            @endphp
                            <button @click="currentDialogueSectionIdx = {{ $sIdx }}"
                                class="group relative flex items-center gap-3 py-1.5 text-left z-10 cursor-pointer"
                                title="{{ $section->title }}">
                                <div class="h-10 w-10 shrink-0 rounded-full flex items-center justify-center text-xs font-bold transition-all border-4 border-white dark:border-[#181615]"
                                    :class="currentDialogueSectionIdx === {{ $sIdx }} ?
                                        'bg-[#e07a5f] text-white shadow-sm' :
                                        'bg-[#fcfaf7] dark:bg-[#23201e] text-slate-400 group-hover:bg-[#fff2ee] dark:group-hover:bg-[#2c221e] group-hover:text-[#e07a5f] border-[#e8e2d9] dark:border-[#2d2926]'">
                                    <span>{{ $sIdx + 1 }}</span>
                                </div>
                                <div class="flex flex-col min-w-0 flex-1">
                                    <span class="text-sm font-bold transition-colors line-clamp-2"
                                        :class="currentDialogueSectionIdx === {{ $sIdx }} ? 'text-[#e07a5f]' :
                                            'text-slate-600 dark:text-slate-400 group-hover:text-[#e07a5f]'"
                                        title="{{ $section->title }}">{{ $hanTitle }}</span>
                                    @if ($viTitle)
                                        <span class="text-[10px] text-slate-400 font-medium line-clamp-1"
                                            title="{{ $section->title }}">{{ $viTitle }}</span>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <x-lms.empty-state 
            icon="fa-solid fa-comments"
            :title="__('Chưa có bài khóa')"
            :description="__('Nội dung bài khóa cho bài học này đang được biên soạn và sẽ sớm ra mắt. Vui lòng quay lại sau.')"
        />
    @endif
</div>