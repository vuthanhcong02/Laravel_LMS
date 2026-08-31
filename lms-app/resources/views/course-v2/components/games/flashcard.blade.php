    <div x-show="vocabSubView === 'flashcard'" style="display: none;" class="space-y-4 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-2">
            <button @click="vocabSubView = 'table'" class="text-xs font-bold text-slate-500 hover:text-[#e07a5f] transition-colors flex items-center gap-1.5 btn-tactile">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Quay lại Bảng từ') }}
            </button>
            <div class="text-xs font-bold text-slate-400">
                {{ __('Chế độ:') }} <span class="text-[#e07a5f]">{{ __('Thẻ ghi nhớ 3D') }}</span>
            </div>
        </div>

        <template x-if="vocabularies && vocabularies.length > 0">
            <div>
                <!-- Flashcard Container 3D -->
                <div class="relative w-full aspect-[4/3] sm:aspect-[3/2] perspective-1000 cursor-pointer group select-none" @click="fcFlipped = !fcFlipped">
                    <!-- Inner Card -->
                    <div class="w-full h-full transition-transform duration-500 transform-style-3d relative" :class="fcFlipped ? 'rotate-y-180' : ''">
                        
                        <!-- Mặt trước (Front): Chữ Hán -->
                        <div class="absolute inset-0 w-full h-full bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl p-6 sm:p-8 flex flex-col justify-between items-center backface-hidden shadow-sm">
                            <div class="w-full flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold text-slate-500 dark:text-slate-400" x-text="(fcIndex + 1) + ' / ' + vocabularies.length"></span>
                                <button @click.stop="window.playAudio(vocabularies[fcIndex]?.audio_url || ('https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(vocabularies[fcIndex]?.word) + '&type=1'))" class="w-10 h-10 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white flex items-center justify-center text-sm transition-colors btn-tactile" title="{{ __('Phát âm') }}">
                                    <i class="fa-solid fa-volume-high"></i>
                                </button>
                            </div>

                            <div class="my-auto text-center space-y-2">
                                <div class="text-5xl sm:text-7xl font-bold zh-text text-slate-900 dark:text-white tracking-wide" x-text="vocabularies[fcIndex]?.word"></div>
                            </div>

                            <div class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-auto flex items-center gap-1.5">
                                <i class="fa-solid fa-rotate text-xs text-[#e07a5f]"></i> {{ __('Click để lật xem nghĩa & Pinyin') }}
                            </div>
                        </div>

                        <!-- Mặt sau (Back): Pinyin & Nghĩa -->
                        <div class="absolute inset-0 w-full h-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] rounded-2xl p-6 sm:p-8 flex flex-col justify-between items-center backface-hidden rotate-y-180 shadow-sm">
                            <div class="w-full flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full bg-white/60 dark:bg-black/30 text-[#e07a5f] text-xs font-bold" x-text="(fcIndex + 1) + ' / ' + vocabularies.length"></span>
                                <button @click.stop="window.playAudio(vocabularies[fcIndex]?.audio_url || ('https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(vocabularies[fcIndex]?.word) + '&type=1'))" class="w-10 h-10 rounded-full bg-white/60 dark:bg-black/30 text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white flex items-center justify-center text-sm transition-all btn-tactile" title="{{ __('Phát âm') }}">
                                    <i class="fa-solid fa-volume-high"></i>
                                </button>
                            </div>

                            <div class="my-auto text-center space-y-3 w-full">
                                @if(hsk_should_show_pinyin($currentLesson->level ?? null))
                                <div class="text-2xl sm:text-3xl font-mono font-bold text-[#e07a5f] tracking-wider" x-text="'[' + (vocabularies[fcIndex]?.pinyin || '') + ']'"></div>
                            @endif
                                
                                <div class="px-4 space-y-1">
                                    <template x-if="vocabularies[fcIndex]?.type">
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-white/60 dark:bg-black/30 text-[#e07a5f] border border-[#e07a5f]/20 uppercase tracking-wide inline-block" x-text="vocabularies[fcIndex]?.type"></span>
                                    </template>
                                    <div class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-slate-100" x-text="vocabularies[fcIndex]?.meaning"></div>
                                </div>
                                
                                <template x-if="vocabularies[fcIndex]?.example">
                                    <div class="mt-3 p-3 bg-white/60 dark:bg-black/30 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 italic mx-auto max-w-md border border-[#e07a5f]/15" x-text="vocabularies[fcIndex]?.example"></div>
                                </template>
                            </div>

                            <div class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-auto flex items-center gap-1.5">
                                <i class="fa-solid fa-rotate text-xs text-[#e07a5f]"></i> {{ __('Click để lật lại chữ Hán') }}
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Điều khiển Flashcard -->
                <div class="flex items-center justify-between mt-6 max-w-sm mx-auto">
                    <button @click="fcIndex = fcIndex > 0 ? fcIndex - 1 : vocabularies.length - 1; fcFlipped = false;" class="w-12 h-12 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-400 hover:text-[#e07a5f] hover:border-[#e07a5f] shadow-2xs flex items-center justify-center transition-all btn-tactile" title="{{ __('Thẻ trước') }}">
                        <i class="fa-solid fa-arrow-left text-base"></i>
                    </button>

                    <button @click="fcFlipped = !fcFlipped" class="px-6 py-3 rounded-2xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs flex items-center gap-2 btn-tactile">
                        <i class="fa-solid fa-rotate"></i> {{ __('Lật thẻ') }}
                    </button>

                    <button @click="fcIndex = fcIndex < vocabularies.length - 1 ? fcIndex + 1 : 0; fcFlipped = false;" class="w-12 h-12 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-400 hover:text-[#e07a5f] hover:border-[#e07a5f] shadow-2xs flex items-center justify-center transition-all btn-tactile" title="{{ __('Thẻ tiếp') }}">
                        <i class="fa-solid fa-arrow-right text-base"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>
