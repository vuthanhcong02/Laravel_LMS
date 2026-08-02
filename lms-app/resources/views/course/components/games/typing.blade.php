<div class="w-full flex flex-col items-center" x-data="typingGameComponent()">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-400 to-red-500 text-white flex items-center justify-center shadow-md shadow-orange-500/20">
            <span class="material-symbols-outlined text-[18px]">keyboard</span>
        </div>
        <h3 class="text-lg font-extrabold text-orange-600 dark:text-orange-400">Luyện gõ</h3>
    </div>
    
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-1.5 mb-6 bg-slate-100/60 dark:bg-slate-800/60 p-1.5 rounded-xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button @click="viewMode = 'flashcard'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">style</span> Flashcard
        </button>
        <button @click="viewMode = 'match'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">extension</span> Nối từ
        </button>
        <button @click="viewMode = 'quiz'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">quiz</span> Trắc nghiệm
        </button>
        <button class="px-3.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-orange-600 dark:text-orange-400 font-bold text-xs sm:text-sm shadow-sm transition-all flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">keyboard</span> Luyện gõ
        </button>
    </div>

    <!-- Typing UI -->
    <div class="w-full max-w-2xl bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl rounded-2xl p-5 md:p-6 border border-white/60 dark:border-slate-700/60 shadow-sm">
        
        <template x-if="!isGameOver">
            <div>
                <!-- Progress Header -->
                <div class="flex justify-between items-center mb-6">
                    <div class="text-xs font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700 shadow-sm">
                        TỪ VỰNG <span class="text-orange-600 dark:text-orange-400 text-sm ml-1" x-text="(currentIndex + 1) + '/' + shuffledList.length"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700 shadow-sm">
                        <span class="material-symbols-outlined text-green-500 text-[16px]">check_circle</span> <span x-text="score"></span>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="w-full bg-white dark:bg-slate-800 rounded-2xl p-5 sm:p-6 mb-5 text-center border-2 border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden transition-colors duration-300"
                     :class="{'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20': showError, 'border-green-500 dark:border-green-500 bg-green-50 dark:bg-green-900/20': showSuccess}">
                    
                    <button @click="playWordAudio(currentWord.word)" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center hover:bg-orange-100 dark:hover:bg-orange-900/50 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">volume_up</span>
                    </button>
                    
                    <div class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-2 uppercase tracking-wider">Gõ chữ Hán cho từ này</div>
                    <div class="text-base sm:text-lg font-bold text-slate-700 dark:text-slate-300 mb-1 leading-relaxed" x-text="currentWord.meaning"></div>
                </div>

                <!-- Input Area -->
                <div class="relative w-full max-w-sm mx-auto">
                    <input 
                        x-ref="typingInput"
                        type="text" 
                        x-model="userInput"
                        @keydown.enter="checkAnswer()"
                        :disabled="hasAnswered"
                        class="w-full bg-white dark:bg-slate-800 border-2 rounded-xl px-4 py-2.5 text-lg font-bold text-center text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all duration-300"
                        :class="getInputClass()"
                        placeholder="Nhập chữ Hán..."
                        autocomplete="off"
                    >
                    
                    <button 
                        x-show="!hasAnswered"
                        @click="checkAnswer()"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center hover:bg-orange-600 active:scale-95 transition-all"
                    >
                        <span class="material-symbols-outlined text-[18px]">keyboard_return</span>
                    </button>
                </div>
                
                <div class="text-center mt-5 h-7">
                    <button x-show="hasFailedCurrent && !showSuccess" @click="skipWord()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-bold text-xs underline transition-colors">Bỏ qua câu này</button>
                    <div x-show="showSuccess" class="text-green-500 font-bold text-xs animate-pulse">Chính xác! Đang qua từ tiếp theo...</div>
                </div>
            </div>
        </template>

        <!-- Game Over Screen -->
        <template x-if="isGameOver">
            <div class="py-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white text-2xl mb-4 shadow-md shadow-orange-500/20">
                    <span class="material-symbols-outlined text-[28px]" x-text="score === shuffledList.length ? 'emoji_events' : 'thumb_up'"></span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Hoàn thành!</h2>
                <p class="text-slate-500 dark:text-slate-400 mb-6 text-sm">Bạn đã gõ đúng <span class="text-orange-600 dark:text-orange-400 font-bold" x-text="score"></span>/<span x-text="shuffledList.length"></span> từ vựng.</p>
                
                <button @click="initTyping()" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl shadow-md shadow-orange-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">refresh</span> Luyện tập lại
                </button>
            </div>
        </template>
    </div>
</div>
