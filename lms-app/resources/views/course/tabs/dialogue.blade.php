<!-- Tab 2: Dialogue Content Panel -->
                        <div x-show="activeTab === 'hoi-thoai'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-left">
                                <!-- Dialogue Chat Box (Left column) -->
                                <div class="lg:col-span-3 order-2 lg:order-1">
                                    @if(isset($currentLesson) && $currentLesson->dialogueSections)
                                        @foreach($currentLesson->dialogueSections as $sIdx => $section)
                                            <div x-data="{ dialogues: @js($section->dialogues) }">
                                                <div x-show="currentDialogueSectionIdx === {{ $sIdx }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm space-y-4 flex flex-col">
                                            <!-- Top Dialogue section header with audio player -->
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800/80">
                                                <div class="flex items-center gap-3 shrink-0">
                                                    <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                        <span class="material-symbols-outlined text-xl">forum</span>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-base font-black text-slate-800 dark:text-white">{{ $section->title }}</h4>
                                                        <p class="text-xs text-slate-400 font-bold mt-0.5">Luyện nghe & khẩu ngữ</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Dynamic Audio Player -->
                                                <div class="w-full md:w-1/2 lg:w-[350px]">
                                                    <audio src="{{ $section->audio_path ? (str_starts_with($section->audio_path, 'http') ? $section->audio_path : (str_starts_with($section->audio_path, 'audio/') ? '/storage/hsk_media/' . $section->audio_path : '/storage/hsk_media/audio/' . $section->audio_path)) : '' }}" controls class="w-full h-10 outline-none"></audio>
                                                </div>
                                            </div>

                                            <!-- Control Options bar -->
                                            <div class="flex flex-wrap gap-2 mb-4">
                                                <button 
                                                    @click="modePinyin = !modePinyin; modeNghe = false; modeGo = false; modeDich = false;"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modePinyin ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="font-black text-[16px]">A</span> Pinyin
                                                </button>
                                                
                                                <button 
                                                    @click="modeHanyu = !modeHanyu; modeNghe = false; modeGo = false; modeDich = false;"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modeHanyu ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="font-black text-[16px]">字</span> Chữ Hán
                                                </button>
                                                
                                                <button 
                                                    @click="modeGo = !modeGo; if(modeGo) { modeNghe = false; modeDich = false; quizIndex = 0; quizInput = ''; quizStatus = 'typing'; }"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border ml-0 sm:ml-4"
                                                    :class="modeGo ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">keyboard</span> Luyện gõ
                                                </button>
                                                
                                                <button 
                                                    @click="modeNghe = !modeNghe; if(modeNghe) { modeGo = false; modeDich = false; quizIndex = 0; quizInput = ''; quizStatus = 'typing'; }"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modeNghe ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">volume_up</span> Luyện nghe
                                                </button>
                                                
                                                <button 
                                                    @click="modeDich = !modeDich; if(modeDich) { modeNghe = false; modeGo = false; quizIndex = 0; quizInput = ''; quizStatus = 'typing'; }"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modeDich ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">translate</span> Luyện dịch
                                                </button>
                                            </div>

                                            <!-- Quiz Mode (Luyện Nghe / Luyện Gõ / Luyện Dịch) -->
                                            <div x-show="modeNghe || modeGo || modeDich" class="mt-6 mb-4 w-full">
                                                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 relative">
                                                    <!-- Nghe Mode Header -->
                                                    <div x-show="modeNghe" class="text-center space-y-4 mb-6">
                                                        <h4 class="text-[13px] font-bold text-primary uppercase tracking-widest">Nghe và gõ lại chữ Hán</h4>
                                                        
                                                        <button 
                                                            x-show="dialogues[quizIndex].audio_path"
                                                            @click="playAudio(dialogues[quizIndex].audio_path)"
                                                            class="w-16 h-16 rounded-full bg-primary text-white shadow-lg shadow-primary/30 flex items-center justify-center hover:hover:bg-primary/90 transition-all mx-auto focus:outline-none active:scale-95"
                                                        >
                                                            <span class="material-symbols-outlined text-3xl">volume_up</span>
                                                        </button>
                                                        
                                                        <p class="text-sm text-slate-400 italic">
                                                            Gợi ý: <span x-text="dialogues[quizIndex].role"></span> đang nói...
                                                        </p>
                                                    </div>

                                                    <!-- Gõ Mode Header -->
                                                    <div x-show="modeGo" class="text-left mb-6">
                                                        <p class="text-2xl font-black text-slate-800 tracking-wide font-chinese flex items-center gap-2" x-show="!(modePinyin && window.alignPinyin(dialogues[quizIndex].character, dialogues[quizIndex].pinyin, currentLevelObj?.level_code))">
                                                            <span x-text="dialogues[quizIndex].role + ':'" class="text-slate-800 font-bold"></span> 
                                                            <span x-text="dialogues[quizIndex].character"></span>
                                                        </p>
                                                        <p x-show="modePinyin && !window.alignPinyin(dialogues[quizIndex].character, dialogues[quizIndex].pinyin, currentLevelObj?.level_code)" class="text-sm text-slate-400 mt-2 font-medium" x-text="dialogues[quizIndex].pinyin"></p>
                                                        
                                                        <div x-show="modePinyin && window.alignPinyin(dialogues[quizIndex].character, dialogues[quizIndex].pinyin, currentLevelObj?.level_code)" class="flex items-center gap-2">
                                                            <span x-text="dialogues[quizIndex].role + ':'" class="text-slate-800 font-bold text-xl mb-1 self-end"></span> 
                                                            <div class="flex flex-wrap items-end gap-x-1.5 gap-y-2 leading-none">
                                                                <template x-for="(pair, idx) in window.alignPinyin(dialogues[quizIndex].character, dialogues[quizIndex].pinyin, currentLevelObj?.level_code)" :key="idx">
                                                                    <div class="flex flex-col items-center">
                                                                        <span class="text-[13px] text-slate-500 dark:text-slate-400 font-pinyin font-normal leading-none mb-1.5" x-html="pair.p === '.' ? '&nbsp;' : pair.p"></span>
                                                                        <span class="font-chinese text-[24px] font-black text-slate-800 dark:text-slate-200 leading-none" x-text="pair.h"></span>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                        <p x-show="modeNghia" class="text-sm text-slate-500 mt-1 font-medium" x-text="dialogues[quizIndex].translation"></p>
                                                    </div>
                                                    
                                                    <!-- Dịch Mode Header -->
                                                    <div x-show="modeDich" class="text-left mb-4">
                                                        <p class="text-[15px] text-slate-700">
                                                            <span class="font-bold text-slate-800" x-text="dialogues[quizIndex].role + ':'"></span>
                                                            <span class="italic" x-text="dialogues[quizIndex].translation"></span>
                                                        </p>
                                                    </div>

                                                    <!-- Input Area -->
                                                    <div class="mb-6 relative">
                                                        <input 
                                                            type="text" 
                                                            x-model="quizInput"
                                                            :placeholder="modeNghe ? 'Nghe được gì, gõ nấy...' : (modeGo ? 'Gõ lại câu chữ Hán đầy đủ...' : 'Dịch sang tiếng Trung...')"
                                                            class="w-full px-4 py-4 rounded-lg border-2 text-lg font-chinese focus:outline-none transition-colors pr-12"
                                                            :class="quizStatus === 'correct' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-700' : 
                                                                   (quizStatus === 'incorrect' ? 'border-red-500 bg-red-50/50 text-red-700' : 'border-slate-300 focus:border-primary')"
                                                            @keyup.enter="quizCheck()"
                                                        >
                                                        
                                                        <!-- Result Icons -->
                                                        <div x-show="quizStatus === 'correct'" class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500">
                                                            <span class="material-symbols-outlined text-2xl">check_circle</span>
                                                        </div>
                                                        <div x-show="quizStatus === 'incorrect'" class="absolute right-4 top-1/2 -translate-y-1/2 text-red-500">
                                                            <span class="material-symbols-outlined text-2xl">cancel</span>
                                                        </div>
                                                    </div>

                                                    <!-- Quiz Controls -->
                                                    <div class="flex flex-wrap items-center justify-between gap-4">
                                                        <div class="flex gap-2">
                                                            <button 
                                                                @click="quizCheck()"
                                                                class="px-5 py-2.5 rounded-lg font-bold text-sm transition-all focus:outline-none bg-primary text-white hover:bg-primary/90 shadow-sm active:scale-95"
                                                            >
                                                                Kiểm tra
                                                            </button>
                                                            <button 
                                                                x-show="quizStatus === 'correct'"
                                                                @click="quizNext()"
                                                                :disabled="quizIndex >= dialogues.length - 1"
                                                                class="px-5 py-2.5 rounded-lg border border-slate-300 font-bold text-sm transition-all focus:outline-none text-slate-700 hover:bg-slate-50 active:scale-95"
                                                                :class="quizIndex >= dialogues.length - 1 ? 'opacity-50 cursor-not-allowed' : ''"
                                                            >
                                                                Câu tiếp
                                                            </button>
                                                            <button 
                                                                @click="quizRetry()"
                                                                class="px-5 py-2.5 rounded-lg border border-slate-300 font-bold text-sm transition-all focus:outline-none text-slate-700 hover:bg-slate-50 active:scale-95"
                                                            >
                                                                Làm lại
                                                            </button>
                                                        </div>
                                                        
                                                        <button 
                                                            @click="modeNghe = false; modeGo = false; modeDich = false;"
                                                            class="px-5 py-2.5 rounded-lg border border-slate-300 font-bold text-sm transition-all focus:outline-none text-slate-700 hover:bg-slate-50 active:scale-95"
                                                        >
                                                            Thoát
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Footer counter -->
                                                    <div class="mt-6 pt-4 border-t border-slate-100 text-left text-sm text-slate-400 font-medium">
                                                        Câu <span x-text="quizIndex + 1"></span>/<span x-text="dialogues.length"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Dialogue WeChat Style Bubbles -->
                                            <div class="space-y-4 pt-3 flex flex-col" x-show="!modeNghe && !modeGo && !modeDich">
                                                @php
                                                    $speakerAlignments = [];
                                                    $alignClasses = ['self-start flex-row text-left', 'self-end flex-row-reverse text-right'];
                                                    $avatarClasses = ['bg-teal-500 shadow-teal-500/10', 'bg-indigo-500 shadow-indigo-500/10', 'bg-rose-500 shadow-rose-500/10', 'bg-amber-500 shadow-amber-500/10'];
                                                    $bubbleClasses = ['bg-teal-50/40 dark:bg-teal-950/10 border-teal-100 dark:border-teal-900/30 text-slate-800 dark:text-slate-200 rounded-bl-none', 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-br-none'];
                                                @endphp
                                                @foreach($section->dialogues as $lineIdx => $line)
                                                    @php
                                                        $roleKey = $line->role ?: 'Unknown';
                                                        if (!isset($speakerAlignments[$roleKey])) {
                                                            $idx = count($speakerAlignments);
                                                            $speakerAlignments[$roleKey] = [
                                                                'align' => $alignClasses[$idx % 2],
                                                                'avatar' => $avatarClasses[$idx % count($avatarClasses)],
                                                                'bubble' => $bubbleClasses[$idx % 2],
                                                                'avatarText' => mb_substr($roleKey, 0, 1)
                                                            ];
                                                        }
                                                        $style = $speakerAlignments[$roleKey];
                                                    @endphp
                                                    <div 
                                                        class="flex gap-2 max-w-md items-end {{ $style['align'] }}"
                                                    >
                                                        <!-- Avatar Icon -->
                                                        <div 
                                                            class="h-7 w-7 rounded-full flex items-center justify-center text-[10px] font-black text-white shrink-0 shadow-sm {{ $style['avatar'] }}"
                                                        >{{ $style['avatarText'] }}</div>

                                                        <!-- Text bubble container -->
                                                        <div class="space-y-1 relative">
                                                            <div 
                                                                class="p-3.5 rounded-2xl shadow-sm border text-left {{ $style['bubble'] }}"
                                                            >
                                                                <!-- Play Individual line sound -->
                                                                <div class="flex justify-between items-center mb-1 gap-4">
                                                                    <span class="text-[9px] font-black uppercase text-slate-400">{{ $line->role }}</span>
                                                                    @if($line->audio_path)
                                                                    <button 
                                                                        @click="playAudio('{{ $line->audio_path }}')"
                                                                        class="h-5 w-5 rounded-full bg-primary/10 hover:bg-primary hover:text-white transition-all duration-205 flex items-center justify-center text-primary"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[11px]">volume_up</span>
                                                                    </button>
                                                                    @endif
                                                                </div>

                                                                <!-- Character display & pinyin toggles -->
                                                                <div x-data="{ l_c: {{ json_encode($line->character) }}, l_p: {{ json_encode($line->pinyin) }} }">
                                                                    <template x-if="window.alignPinyin(l_c, l_p, currentLevelObj?.level_code)">
                                                                        <div class="flex flex-wrap items-end gap-x-1.5 gap-y-2 leading-none" x-show="modePinyin && modeHanyu">
                                                                            <template x-for="(pair, idx) in window.alignPinyin(l_c, l_p, currentLevelObj?.level_code)" :key="idx">
                                                                                <div class="flex flex-col items-center">
                                                                                    <span class="text-[12px] text-slate-500 dark:text-slate-400 font-pinyin font-normal leading-none mb-1" x-html="pair.p === '.' ? '&nbsp;' : pair.p"></span>
                                                                                    <span class="font-chinese text-[18px] font-bold text-slate-800 dark:text-slate-200 leading-none" x-text="pair.h"></span>
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                    </template>
                                                                    
                                                                    <template x-if="!window.alignPinyin(l_c, l_p, currentLevelObj?.level_code)">
                                                                        <div>
                                                                            <p x-show="modePinyin && modeHanyu" class="text-xs text-slate-400 dark:text-slate-550 italic mb-0.5 block" x-text="l_p"></p>
                                                                            <p x-show="modeHanyu && modePinyin" class="text-lg font-bold tracking-wide leading-relaxed block" x-text="l_c"></p>
                                                                        </div>
                                                                    </template>
                                                                    
                                                                    <p x-show="modePinyin && !modeHanyu" class="text-xs text-slate-400 dark:text-slate-550 italic block" x-text="l_p"></p>
                                                                    <p x-show="modeHanyu && !modePinyin" class="text-lg font-bold tracking-wide leading-relaxed block" x-text="l_c"></p>
                                                                </div>
                                                                
                                                                <!-- Empty state placeholder (khi tắt cả pinyin và chữ Hán) -->
                                                                <p x-show="!modePinyin && !modeHanyu" class="text-xs text-slate-400 dark:text-slate-600 italic py-1 opacity-60">(Đang ẩn nội dung)</p>
                                                                <!-- Luyện dịch toggle text -->
                                                                <p x-show="modeNghia" class="text-xs text-slate-505 dark:text-slate-455 mt-1.5 pt-1.5 border-t border-slate-100 dark:border-slate-800/80 font-semibold">{{ $line->translation }}</p>
                                                            </div>

                                                            <!-- Typing field for typing exercise -->
                                                            <div x-show="modeGo" class="mt-1.5 w-60">
                                                                <input 
                                                                    type="text" 
                                                                    placeholder="Luyện gõ câu hội thoại này..." 
                                                                    class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none bg-slate-50 dark:bg-slate-900"
                                                                    x-model="typeInputs['{{ $sIdx }}_{{ $lineIdx }}']"
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                                </div>

                                <!-- Playlist Menu navigation (Right column) -->
                                <div class="lg:col-span-1 order-1 lg:order-2 space-y-4">
                                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-2xl p-4 text-left">
                                        <h5 class="text-xs font-black text-slate-855 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3 flex items-center gap-1.5 uppercase tracking-wider">
                                            <span class="material-symbols-outlined text-[16px]">playlist_play</span>
                                            <span>Chọn bài khóa</span>
                                        </h5>

                                        <div class="flex flex-col gap-1.5">
                                            @if(isset($currentLesson) && $currentLesson->dialogueSections)
                                                @foreach($currentLesson->dialogueSections as $sIdx => $section)
                                                    <button 
                                                        @click="currentDialogueSectionIdx = {{ $sIdx }}"
                                                        class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-bold transition-all text-left flex items-start gap-2.5"
                                                        :class="currentDialogueSectionIdx === {{ $sIdx }} 
                                                            ? 'bg-primary/10 text-primary border-primary/20 font-black shadow-sm' 
                                                            : 'bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-805 text-slate-605 dark:text-slate-405 hover:bg-slate-50'"
                                                    >
                                                        <span 
                                                            class="h-5 w-5 shrink-0 mt-[1px] rounded-full text-[9px] flex items-center justify-center font-black"
                                                            :class="currentDialogueSectionIdx === {{ $sIdx }} ? 'bg-primary text-white' : 'bg-slate-100 text-slate-500 dark:bg-slate-800'"
                                                        >{{ $sIdx + 1 }}</span>
                                                        <span class="leading-relaxed">{{ $section->title }}</span>
                                                    </button>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        