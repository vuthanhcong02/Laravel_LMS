<div class="w-full flex flex-col items-center">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
            <span class="material-symbols-outlined text-[20px]">style</span>
        </div>
        <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400">Flashcard</h3>
    </div>
    
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-3 mb-8 bg-slate-100/50 dark:bg-slate-800/50 p-2 rounded-2xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button class="px-5 py-2.5 rounded-xl bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 font-black text-sm shadow-md transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">style</span> Flashcard
        </button>
        <button @click="viewMode = 'match'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">extension</span> Nối từ
        </button>
        <button @click="viewMode = 'quiz'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">quiz</span> Trắc nghiệm
        </button>
        <button @click="viewMode = 'typing'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">keyboard</span> Luyện gõ
        </button>
    </div>

    <div class="w-full max-w-3xl h-[380px] perspective relative">
        <!-- Play word audio button (z-10 so it's clickable above the flip overlay) -->
        <button @click.stop="playWordAudio(currentWord().word)" class="absolute top-6 right-6 z-10 w-10 h-10 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-500 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-slate-600 transition-colors">
            <span class="material-symbols-outlined text-[20px]">volume_up</span>
        </button>

        <!-- Flashcard UI implementation -->
        <div 
            class="w-full h-full preserve-3d transition-all duration-300 relative cursor-pointer"
            :class="{'rotate-y-180': flipped}"
            @click="flipCard()"
        >
            <!-- FRONT -->
            <div class="backface-hidden w-full h-full bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl flex flex-col items-center justify-center p-6 absolute top-0 left-0">
                <div class="absolute top-6 left-1/2 -translate-x-1/2 px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-full text-xs font-bold text-slate-500 dark:text-slate-400" x-text="(currentIndex + 1) + ' / ' + vocabList.length"></div>
                <h3 class="text-7xl font-black text-slate-800 dark:text-white" x-text="currentWord().word"></h3>
                <div class="mt-8 text-sm font-bold text-slate-400">Chạm để xem mặt sau</div>
            </div>

            <!-- BACK -->
            <div class="backface-hidden w-full h-full bg-slate-50 dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-600 shadow-xl flex flex-col items-center justify-center p-6 absolute top-0 left-0 rotate-y-180">
                <div class="absolute top-6 left-1/2 -translate-x-1/2 px-3 py-1 bg-slate-200 dark:bg-slate-800 rounded-full text-xs font-bold text-slate-500 dark:text-slate-400" x-text="(currentIndex + 1) + ' / ' + vocabList.length"></div>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white mb-2" x-text="currentWord().word"></h3>
                <div class="text-xl font-bold text-blue-500 dark:text-blue-400 mb-6 italic" x-text="'[' + currentWord().pinyin + ']'"></div>
                <div class="text-2xl font-bold text-slate-600 dark:text-slate-300 text-center px-4" x-text="currentWord().meaning"></div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="flex items-center gap-6 mt-10">
        <button @click="prevWord()" class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors active:scale-95">
            <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button @click="shuffleList()" class="px-6 py-3 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-2 transition-colors active:scale-95" :class="isShuffled ? 'bg-blue-500 text-white border-blue-500' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50'">
            <span class="material-symbols-outlined text-[18px]">shuffle</span>
            <span class="font-bold text-sm" x-text="isShuffled ? 'Đang trộn' : 'Trộn thẻ'"></span>
        </button>
        <button @click="nextWord()" class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors active:scale-95">
            <span class="material-symbols-outlined">chevron_right</span>
        </button>
    </div>
</div>
