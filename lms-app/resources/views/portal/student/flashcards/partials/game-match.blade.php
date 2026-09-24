<div class="space-y-5 max-w-2xl mx-auto py-2">
    <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xs">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="w-10 h-10 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-base font-bold shrink-0 shadow-xs">
                <i class="fa-solid fa-puzzle-piece"></i>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">{{ __('Thử Thách Nối Thẻ Ghép Từ') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Nối từng chữ Hán với nghĩa tiếng Việt tương ứng nhanh và chính xác nhất.') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-end">
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold text-slate-600 dark:text-slate-300">
                <i class="fa-regular fa-clock text-[#0284c7]"></i>
                <span x-text="formatMatchTime(matchTimer)">00:00</span>
            </div>

            <button type="button"
                    @click="initMatchGame()"
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-500 hover:text-[#e07a5f] hover:border-[#e07a5f] text-xs flex items-center justify-center transition-all btn-tactile cursor-pointer"
                    :title="'{{ __('Chơi lại') }}'">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </div>

    <template x-if="!matchCurrentPairs || matchCurrentPairs.length < 2">
        <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center space-y-4 shadow-xs">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 text-amber-500 flex items-center justify-center text-2xl mx-auto shadow-xs">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div class="space-y-1 max-w-sm mx-auto">
                <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Cần ít nhất 2 từ vựng để chơi nối thẻ') }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Hãy bổ sung thêm từ vựng để tạo bàn chơi ghép cặp.') }}</p>
            </div>
            <button type="button"
                    @click="switchPracticeMode('flashcard')"
                    class="px-4 py-2 bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold rounded-xl btn-tactile shadow-xs cursor-pointer">
                {{ __('Chuyển về Lật thẻ 3D') }}
            </button>
        </div>
    </template>

    <template x-if="matchCurrentPairs && matchCurrentPairs.length >= 2 && !matchIsCompleted">
        <div class="space-y-4">
            <div class="space-y-1.5">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-400 px-0.5">
                    <span>{{ __('Đã hoàn thành:') }} <strong class="text-[#e07a5f] font-bold" x-text="matchMatchedPairs.length + ' / ' + matchCurrentPairs.length"></strong></span>
                    <span x-text="Math.round((matchMatchedPairs.length / matchCurrentPairs.length) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-[#e8e2d9]/60 dark:bg-[#2d2926] rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#e07a5f] h-full rounded-full transition-all duration-300"
                         :style="'width: ' + ((matchMatchedPairs.length / matchCurrentPairs.length) * 100) + '%'"></div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 pt-1">
                <div class="space-y-2.5">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-1">
                        {{ __('Chữ Hán') }}
                    </div>
                    <template x-for="item in (matchLeftItems || [])" :key="'l_' + item.id">
                        <div @click="selectMatchLeft(item)"
                             :class="[
                                 item.isMatched ? 'opacity-30 pointer-events-none bg-emerald-50 dark:bg-emerald-950/20 border-emerald-400 text-emerald-600 scale-95' :
                                 (matchSelectedLeft && matchSelectedLeft.id === item.id ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] ring-2 ring-[#e07a5f]/40 shadow-sm translate-x-1' : 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/60 hover:shadow-2xs'),
                                 item.isWrong ? 'border-rose-500 bg-rose-50 dark:bg-rose-950/30 text-rose-600 animate-shake' : ''
                             ]"
                             class="p-3.5 sm:p-4 rounded-2xl border transition-all duration-150 cursor-pointer flex items-center justify-between select-none h-[64px] sm:h-[72px]">
                            <div class="flex items-center gap-2">
                                <span class="text-lg sm:text-xl font-bold zh-text text-slate-900 dark:text-white" x-text="item.word"></span>
                                <template x-if="item.pinyin">
                                    <span class="text-xs sm:text-sm font-semibold text-[#e07a5f]" x-text="'[' + item.pinyin + ']'"></span>
                                </template>
                            </div>
                            <button type="button"
                                    @click.stop="speak(item.word)"
                                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] flex items-center justify-center text-xs transition-colors cursor-pointer"
                                    :title="'{{ __('Nghe phát âm') }}'">
                                <i class="fa-solid fa-volume-high"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="space-y-2.5">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-1">
                        {{ __('Ý Nghĩa Tiếng Việt') }}
                    </div>
                    <template x-for="item in (matchRightItems || [])" :key="'r_' + item.id">
                        <div @click="selectMatchRight(item)"
                             :class="[
                                 item.isMatched ? 'opacity-30 pointer-events-none bg-emerald-50 dark:bg-emerald-950/20 border-emerald-400 text-emerald-600 scale-95' :
                                 (matchSelectedRight && matchSelectedRight.id === item.id ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] ring-2 ring-[#e07a5f]/40 shadow-sm -translate-x-1' : 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/60 hover:shadow-2xs'),
                                 item.isWrong ? 'border-rose-500 bg-rose-50 dark:bg-rose-950/30 text-rose-600 animate-shake' : ''
                             ]"
                             class="p-3.5 sm:p-4 rounded-2xl border transition-all duration-150 cursor-pointer flex items-center justify-between select-none h-[64px] sm:h-[72px]">
                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 line-clamp-2" x-text="item.meaning"></span>
                            <template x-if="item.isMatched">
                                <i class="fa-solid fa-check text-emerald-500 text-xs shrink-0"></i>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>

    <template x-if="matchIsCompleted">
        <div class="lms-card p-8 sm:p-10 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center space-y-6 shadow-sm">
            <div class="w-20 h-20 rounded-3xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#f59e0b] flex items-center justify-center text-4xl mx-auto shadow-xs">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div class="space-y-2 max-w-md mx-auto">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                    {{ __('Xuất Sắc! Hoàn Thành Ghép Thẻ!') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Bạn đã nối chính xác tất cả các cặp từ vựng trong thời gian ấn tượng.') }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 max-w-xs mx-auto">
                <div class="p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">{{ __('Thời gian') }}</div>
                    <div class="text-lg font-bold text-[#0284c7]" x-text="formatMatchTime(matchTimer)"></div>
                </div>
                <div class="p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">{{ __('Độ chính xác') }}</div>
                    <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400" x-text="matchAccuracy + '%'"></div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <button type="button"
                        @click="initMatchGame()"
                        class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                    <span>{{ __('Chơi ván mới') }}</span>
                </button>
                <button type="button"
                        @click="switchPracticeMode('flashcard')"
                        class="px-5 py-2.5 rounded-xl bg-white dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 text-xs font-bold btn-tactile hover:border-[#e07a5f] flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-layer-group text-xs text-[#e07a5f]"></i>
                    <span>{{ __('Chuyển về Lật thẻ 3D') }}</span>
                </button>
            </div>
        </div>
    </template>
</div>
