<div class="w-full flex flex-col items-center">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20">
            <span class="material-symbols-outlined text-[18px]">style</span>
        </div>
        <h3 class="text-lg font-extrabold text-blue-600 dark:text-blue-400">Flashcard</h3>
    </div>
    
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-1.5 mb-6 bg-slate-100/60 dark:bg-slate-800/60 p-1.5 rounded-xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button class="px-3.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 font-bold text-xs sm:text-sm shadow-sm transition-all flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">style</span> Flashcard
        </button>
        <button @click="viewMode = 'match'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">extension</span> Nối từ
        </button>
        <button @click="viewMode = 'quiz'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">quiz</span> Trắc nghiệm
        </button>
        <button @click="viewMode = 'typing'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">keyboard</span> Luyện gõ
        </button>
    </div>

    <div class="w-full max-w-2xl h-[320px] perspective relative">
        <!-- Play word audio button (z-10 so it's clickable above the flip overlay) -->
        <button @click.stop="playWordAudio(currentWord().word)" class="absolute top-5 right-5 z-10 w-8 h-8 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-500 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-slate-600 transition-colors">
            <span class="material-symbols-outlined text-[18px]">volume_up</span>
        </button>

        <!-- Flashcard UI implementation -->
        <div 
            class="w-full h-full preserve-3d transition-all duration-300 relative cursor-pointer"
            :class="{'rotate-y-180': flipped}"
            @click="flipCard()"
        >
            <!-- FRONT -->
            <div class="backface-hidden w-full h-full bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-lg flex flex-col items-center justify-center p-6 absolute top-0 left-0">
                <div class="absolute top-5 left-1/2 -translate-x-1/2 px-3 py-0.5 bg-slate-100 dark:bg-slate-700 rounded-full text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="(currentIndex + 1) + ' / ' + vocabList.length"></div>
                <h3 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-800 dark:text-white" x-text="currentWord().word"></h3>
                <div class="mt-6 text-xs font-medium text-slate-400">Chạm để xem mặt sau</div>
            </div>

            <!-- BACK -->
            <div class="backface-hidden w-full h-full bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-600 shadow-lg flex flex-col items-center justify-center p-6 absolute top-0 left-0 rotate-y-180">
                <div class="absolute top-5 left-1/2 -translate-x-1/2 px-3 py-0.5 bg-slate-200 dark:bg-slate-800 rounded-full text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="(currentIndex + 1) + ' / ' + vocabList.length"></div>
                <h3 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white mb-1" x-text="currentWord().word"></h3>
                <div class="text-xs sm:text-sm font-bold text-blue-500 dark:text-blue-400 mb-3 italic" x-text="'[' + currentWord().pinyin + ']'"></div>
                <div class="text-xs sm:text-sm md:text-base font-semibold text-slate-600 dark:text-slate-300 text-center px-4 leading-relaxed" x-text="currentWord().meaning"></div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="flex items-center gap-4 mt-6">
        <button @click="prevWord()" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
        </button>
        <button @click="shuffleList()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-1.5 transition-colors active:scale-95" :class="isShuffled ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50'">
            <span class="material-symbols-outlined text-[16px]">shuffle</span>
            <span class="font-bold text-xs" x-text="isShuffling ? 'Đang trộn...' : (isShuffled ? 'Bỏ trộn' : 'Trộn thẻ')"></span>
        </button>
        <button @click="nextWord()" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
        </button>
    </div>
</div>
