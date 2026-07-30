<div class="w-full flex flex-col items-center" x-data="matchGameComponent()">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 text-white flex items-center justify-center shadow-lg shadow-green-500/30">
            <span class="material-symbols-outlined text-[20px]">extension</span>
        </div>
        <h3 class="text-2xl font-black text-green-600 dark:text-green-400">Nối từ</h3>
    </div>
    
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-3 mb-8 bg-slate-100/50 dark:bg-slate-800/50 p-2 rounded-xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button @click="viewMode = 'flashcard'" class="px-5 py-2.5 rounded-lg text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">style</span> Flashcard
        </button>
        <button class="px-5 py-2.5 rounded-lg bg-white dark:bg-slate-700 text-green-600 dark:text-green-400 font-black text-sm shadow-md transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">extension</span> Nối từ
        </button>
        <button @click="viewMode = 'quiz'" class="px-5 py-2.5 rounded-lg text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">quiz</span> Trắc nghiệm
        </button>
        <button @click="viewMode = 'typing'" class="px-5 py-2.5 rounded-lg text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">keyboard</span> Luyện gõ
        </button>
    </div>

    <!-- Match Game UI -->
    <div class="w-full max-w-5xl bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/60 dark:border-slate-700/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)]">
        <!-- Progress Header -->
        <div class="flex justify-between items-center mb-8 px-4 bg-white/60 dark:bg-slate-800/60 py-3 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400">
                    <span class="material-symbols-outlined text-[18px]">sports_score</span>
                </div>
                <div class="text-sm font-bold text-slate-500 dark:text-slate-400">
                    TIẾN ĐỘ: <span class="text-green-600 dark:text-green-400 text-lg ml-1" x-text="matchedPairs + '/' + vocabList.length"></span>
                </div>
            </div>
            <div class="text-sm font-black text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-4 py-1.5 rounded-full">
                TRANG <span class="text-slate-700 dark:text-slate-300 ml-1" x-text="currentPage + 1"></span>
            </div>
        </div>

        <!-- The 2 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <!-- Left Column: Words -->
            <div class="flex flex-col gap-3">
                <template x-for="(item, index) in currentLeftWords" :key="'left-'+item.id">
                    <button 
                        @click="selectLeft(item)"
                        class="relative w-full flex items-center bg-white dark:bg-slate-800 border-2 rounded-2xl p-3.5 sm:p-4 transition-all duration-300 ease-out group overflow-hidden"
                        :class="[
                            item.matched ? 'opacity-40 grayscale pointer-events-none scale-95' : 'opacity-100 hover:-translate-y-0.5 hover:shadow-md',
                            selectedLeft && selectedLeft.id === item.id ? 'border-green-500 ring-4 ring-green-500/20 scale-[1.01] shadow-md z-10' : 'border-slate-100 dark:border-slate-700/80 hover:border-green-300 dark:hover:border-green-700'
                        ]"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-transparent dark:from-green-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-700/50 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold group-hover:bg-green-100 group-hover:text-green-600 transition-colors shrink-0 z-10" x-text="index + 1"></div>
                        <div class="ml-3.5 text-lg sm:text-xl font-black text-slate-800 dark:text-white tracking-wide text-left z-10" x-text="item.word"></div>
                    </button>
                </template>
            </div>

            <!-- Right Column: Meanings -->
            <div class="flex flex-col gap-3">
                <template x-for="(item, index) in currentRightWords" :key="'right-'+item.id">
                    <button 
                        @click="selectRight(item)"
                        class="relative w-full flex items-center bg-white dark:bg-slate-800 border-2 rounded-2xl p-3.5 sm:p-4 transition-all duration-300 ease-out group overflow-hidden"
                        :class="[
                            item.matched ? 'opacity-40 grayscale pointer-events-none scale-95' : 'opacity-100 hover:-translate-y-0.5 hover:shadow-md',
                            selectedRight && selectedRight.id === item.id ? 'border-green-500 ring-4 ring-green-500/20 scale-[1.01] shadow-md z-10' : 'border-slate-100 dark:border-slate-700/80 hover:border-green-300 dark:hover:border-green-700'
                        ]"
                    >
                        <div class="absolute inset-0 bg-gradient-to-bl from-green-50/50 to-transparent dark:from-green-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-700/50 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold group-hover:bg-green-100 group-hover:text-green-600 transition-colors shrink-0 z-10" x-text="rightHotkeys[index]"></div>
                        <div class="ml-3.5 text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300 leading-snug text-left z-10" x-text="item.meaning"></div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Success Message when page is done -->
        <div x-show="isPageComplete" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="mt-12 flex flex-col items-center justify-center gap-4">
            <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-500 mb-2">
                <span class="material-symbols-outlined text-[32px]">check_circle</span>
            </div>
            <button @click="nextPage()" class="px-10 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white font-black text-lg rounded-2xl shadow-[0_10px_25px_rgba(34,197,94,0.3)] transition-all hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                <span x-text="matchedPairs === vocabList.length ? 'Tuyệt vời! Chơi lại từ đầu' : 'Trang tiếp theo'"></span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </div>
    </div>
</div>
