    <template x-if="vocabSubView === 'match'">
        <div x-data="vocabMatchEngine()" 
             x-init="initMatchGame(vocabularies)"
             class="space-y-5 max-w-3xl mx-auto">
        <!-- Header Bar -->
        <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xs">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-10 h-10 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f4978e] flex items-center justify-center text-base font-bold shrink-0">
                    <i class="fa-solid fa-puzzle-piece"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Thử thách Nối từ HSK') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Nối Hán tự với bản dịch tương ứng nhanh và chính xác nhất.') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold text-slate-600 dark:text-slate-300">
                    <i class="fa-regular fa-clock text-[#0284c7]"></i>
                    <span x-text="formatTime(timer)">00:00</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f]">
                    <i class="fa-solid fa-fire"></i>
                    <span>{{ __('Điểm:') }} <span x-text="score">0</span></span>
                </div>
                <button @click="initMatchGame(vocabularies)" class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-500 hover:text-[#e07a5f] hover:border-[#e07a5f] text-xs font-bold transition-all btn-tactile" title="{{ __('Chơi lại') }}">
                    <i class="fa-solid fa-arrow-rotate-right"></i>
                </button>
            </div>
        </div>
        <!-- Not Enough Words Warning -->
        <template x-if="!vocabularies || vocabularies.length < 2">
            <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-3">
                <i class="fa-solid fa-circle-exclamation text-3xl text-[#f59e0b]"></i>
                <h4 class="text-base font-bold text-slate-800 dark:text-white">{{ __('Cần ít nhất 2 từ vựng để chơi!') }}</h4>
                <p class="text-xs text-slate-500">{{ __('Bài học này chưa có đủ từ vựng để tạo bàn chơi nối từ.') }}</p>
                <button @click="vocabSubView = 'table'" class="px-4 py-2 bg-[#e07a5f] text-white text-xs font-bold rounded-xl btn-tactile">{{ __('Xem Bảng từ') }}</button>
            </div>
        </template>
        <!-- Main Game Area -->
        <template x-if="vocabularies && vocabularies.length >= 2 && !isCompleted">
            <div class="space-y-4">
                <!-- Progress indicator -->
                <div class="flex items-center justify-between text-xs font-semibold text-slate-400 px-1">
                    <span>{{ __('Đã hoàn thành:') }} <span class="text-[#e07a5f] font-bold" x-text="matchedPairs.length + ' / ' + currentPairs.length"></span></span>
                    <span x-text="Math.round((matchedPairs.length / currentPairs.length) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-[#e8e2d9]/50 dark:bg-[#2d2926] rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#e07a5f] h-full rounded-full transition-all duration-300" :style="'width: ' + ((matchedPairs.length / currentPairs.length) * 100) + '%'"></div>
                </div>
                <!-- 2 Columns Grid -->
                <div class="grid grid-cols-2 gap-3 sm:gap-4 pt-2">
                    <div class="space-y-2.5">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center mb-1">
                            {{ __('Chữ Hán') }}
                        </div>
                        <template x-for="item in leftItems" :key="'l_' + item.id">
                            <div @click="selectLeft(item)"
                                 :class="[
                                     item.isMatched ? 'opacity-40 pointer-events-none bg-emerald-50 dark:bg-emerald-950/20 border-emerald-400 text-emerald-600' :
                                     (selectedLeft?.id === item.id ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] ring-2 ring-[#e07a5f]/30 shadow-md translate-x-1' : 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/60 hover:shadow-2xs'),
                                     item.isWrong ? 'border-rose-500 bg-rose-50 dark:bg-rose-950/20 text-rose-600 animate-shake' : ''
                                 ]"
                                 class="p-3.5 sm:p-4 rounded-xl border transition-all duration-150 cursor-pointer flex items-center justify-between select-none h-[64px] sm:h-[72px]">
                                <div class="flex items-center gap-2">
                                    <span class="text-base sm:text-lg font-bold zh-text text-slate-900 dark:text-white" x-text="item.word"></span>
                                    @if(hsk_should_show_pinyin($currentLesson->level ?? null))
                                        <span class="text-[11px] font-mono text-[#e07a5f] font-semibold" x-text="'[' + item.pinyin + ']'"></span>
                                    @endif
                                </div>
                                <button @click.stop="window.playAudio(item.audio_url || ('https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(item.word) + '&type=1'))" class="w-7 h-7 rounded-lg text-slate-400 hover:text-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] flex items-center justify-center text-xs transition-colors" title="{{ __('Nghe') }}">
                                    <i class="fa-solid fa-volume-high"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <div class="space-y-2.5">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center mb-1">
                            {{ __('Ý Nghĩa Tiếng Việt') }}
                        </div>
                        <template x-for="item in rightItems" :key="'r_' + item.id">
                            <div @click="selectRight(item)"
                                 :class="[
                                     item.isMatched ? 'opacity-40 pointer-events-none bg-emerald-50 dark:bg-emerald-950/20 border-emerald-400 text-emerald-600' :
                                     (selectedRight?.id === item.id ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] ring-2 ring-[#e07a5f]/30 shadow-md -translate-x-1' : 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/60 hover:shadow-2xs'),
                                     item.isWrong ? 'border-rose-500 bg-rose-50 dark:bg-rose-950/20 text-rose-600 animate-shake' : ''
                                 ]"
                                 class="p-3.5 sm:p-4 rounded-xl border transition-all duration-150 cursor-pointer flex items-center justify-between select-none h-[64px] sm:h-[72px]">
                                <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 line-clamp-2" x-text="item.meaning"></span>
                                <template x-if="item.isMatched">
                                    <i class="fa-solid fa-check text-emerald-500 text-xs"></i>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        <!-- Completion Victory Screen -->
        <template x-if="isCompleted">
            <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-5 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#f59e0b] flex items-center justify-center text-3xl mx-auto shadow-inner">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Xuất sắc! Bạn đã nối đúng toàn bộ từ vựng!') }}</h3>
                    <p class="text-xs text-slate-500">{{ __('Khả năng phản xạ và ghi nhớ từ mới của bạn rất tốt.') }}</p>
                </div>
                <!-- Stats Grid -->
                <div class="grid grid-cols-3 gap-3 max-w-sm mx-auto py-2">
                    <div class="p-3 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                        <div class="text-lg font-bold text-[#e07a5f]" x-text="score"></div>
                        <div class="text-[10px] font-semibold text-slate-400">{{ __('Điểm số') }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                        <div class="text-lg font-bold text-[#0284c7]" x-text="formatTime(timer)"></div>
                        <div class="text-[10px] font-semibold text-slate-400">{{ __('Thời gian') }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                        <div class="text-lg font-bold text-emerald-500" x-text="accuracy + '%'"></div>
                        <div class="text-[10px] font-semibold text-slate-400">{{ __('Chính xác') }}</div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="initMatchGame(vocabularies)" class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs btn-tactile flex items-center gap-2">
                        <i class="fa-solid fa-rotate-right"></i> {{ __('Chơi lượt mới') }}
                    </button>
                    <button @click="vocabSubView = 'table'" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] font-bold text-xs btn-tactile">
                        {{ __('Về Bảng từ') }}
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>
