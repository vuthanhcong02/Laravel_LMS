<div class="w-full flex flex-col items-center" x-data="matchGameComponent()">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-400 to-emerald-600 text-white flex items-center justify-center shadow-md shadow-green-500/20">
            <span class="material-symbols-outlined text-[18px]">extension</span>
        </div>
        <h3 class="text-lg font-extrabold text-green-600 dark:text-green-400">Nối từ</h3>
    </div>
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-1.5 mb-6 bg-slate-100/60 dark:bg-slate-800/60 p-1.5 rounded-xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button @click="viewMode = 'flashcard'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">style</span> Flashcard
        </button>
        <button class="px-3.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-green-600 dark:text-green-400 font-bold text-xs sm:text-sm shadow-sm transition-all flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">extension</span> Nối từ
        </button>
        <button @click="viewMode = 'quiz'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">quiz</span> Trắc nghiệm
        </button>
        <button @click="viewMode = 'typing'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">keyboard</span> Luyện gõ
        </button>
    </div>
    <!-- Match Game UI -->
    <div class="w-full max-w-4xl bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl rounded-2xl p-5 md:p-6 border border-white/60 dark:border-slate-700/60 shadow-sm">
        <!-- Progress Header -->
        <div class="flex justify-between items-center mb-6 px-4 bg-white/60 dark:bg-slate-800/60 py-2.5 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400">
                    <span class="material-symbols-outlined text-[16px]">sports_score</span>
                </div>
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400">
                    TIẾN ĐỘ: <span class="text-green-600 dark:text-green-400 text-sm ml-1" x-text="matchedPairs + '/' + vocabList.length"></span>
                </div>
            </div>
            <div class="text-xs font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full">
                TRANG <span class="text-slate-700 dark:text-slate-300 ml-1" x-text="currentPage + 1"></span>
            </div>
        </div>
        <!-- The 2 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
            <!-- Left Column: Words -->
            <div class="flex flex-col gap-2.5">
                <template x-for="(item, index) in currentLeftWords" :key="'left-'+item.id">
                    <button 
                        @click="selectLeft(item)"
                        class="relative w-full flex items-center bg-white dark:bg-slate-800 border-2 rounded-xl p-3 transition-all duration-300 ease-out group overflow-hidden"
                        :class="[
                            item.matched ? 'opacity-40 grayscale pointer-events-none scale-95' : 'opacity-100 hover:-translate-y-0.5 hover:shadow-md',
                            selectedLeft && selectedLeft.id === item.id ? 'border-green-500 ring-4 ring-green-500/20 scale-[1.01] shadow-md z-10' : 'border-slate-100 dark:border-slate-700/80 hover:border-green-300 dark:hover:border-green-700'
                        ]"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-transparent dark:from-green-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-700/50 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold group-hover:bg-green-100 group-hover:text-green-600 transition-colors shrink-0 z-10" x-text="index + 1"></div>
                        <div class="ml-3 text-base sm:text-lg font-bold text-slate-800 dark:text-white tracking-wide text-left z-10" x-text="item.word"></div>
                    </button>
                </template>
            </div>
            <!-- Right Column: Meanings -->
            <div class="flex flex-col gap-2.5">
                <template x-for="(item, index) in currentRightWords" :key="'right-'+item.id">
                    <button 
                        @click="selectRight(item)"
                        class="relative w-full flex items-center bg-white dark:bg-slate-800 border-2 rounded-xl p-3 transition-all duration-300 ease-out group overflow-hidden"
                        :class="[
                            item.matched ? 'opacity-40 grayscale pointer-events-none scale-95' : 'opacity-100 hover:-translate-y-0.5 hover:shadow-md',
                            selectedRight && selectedRight.id === item.id ? 'border-green-500 ring-4 ring-green-500/20 scale-[1.01] shadow-md z-10' : 'border-slate-100 dark:border-slate-700/80 hover:border-green-300 dark:hover:border-green-700'
                        ]"
                    >
                        <div class="absolute inset-0 bg-gradient-to-bl from-green-50/50 to-transparent dark:from-green-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-700/50 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold group-hover:bg-green-100 group-hover:text-green-600 transition-colors shrink-0 z-10" x-text="rightHotkeys[index]"></div>
                        <div class="ml-3 text-xs sm:text-sm leading-snug text-left z-10">
                            @if(hsk_should_show_pinyin($currentLesson->level ?? null))
                                <template x-if="item.pinyin">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 mr-1" x-text="item.pinyin"></span>
                                </template>
                            @endif
                            @if(hsk_should_show_pinyin($currentLesson->level ?? null))
                                <span class="font-medium text-slate-500 dark:text-slate-400" x-text="item.pinyin ? '(' + item.meaning + ')' : item.meaning"></span>
                            @else
                                <span class="font-medium text-slate-500 dark:text-slate-400" x-text="item.meaning"></span>
                            @endif
                        </div>
                    </button>
                </template>
            </div>
        </div>
        <!-- Success Message when page is done -->
        <div x-show="isPageComplete" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="mt-8 flex flex-col items-center justify-center gap-3">
            <div class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-500 mb-1">
                <span class="material-symbols-outlined text-[28px]">check_circle</span>
            </div>
            <button @click="nextPage()" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                <span x-text="matchedPairs === vocabList.length ? 'Chơi lại từ đầu' : 'Trang tiếp theo'"></span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </div>
    </div>
</div>
