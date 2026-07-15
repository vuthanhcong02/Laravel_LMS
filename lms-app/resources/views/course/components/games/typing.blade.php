<div class="w-full flex flex-col items-center" x-data="typingGameComponent()">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-red-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/30">
            <span class="material-symbols-outlined text-[20px]">keyboard</span>
        </div>
        <h3 class="text-2xl font-black text-orange-600 dark:text-orange-400">Luyện gõ</h3>
    </div>
    
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-3 mb-8 bg-slate-100/50 dark:bg-slate-800/50 p-2 rounded-2xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button @click="viewMode = 'flashcard'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">style</span> Flashcard
        </button>
        <button @click="viewMode = 'match'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">extension</span> Nối từ
        </button>
        <button @click="viewMode = 'quiz'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">quiz</span> Trắc nghiệm
        </button>
        <button class="px-5 py-2.5 rounded-xl bg-white dark:bg-slate-700 text-orange-600 dark:text-orange-400 font-black text-sm shadow-md transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">keyboard</span> Luyện gõ
        </button>
    </div>

    <!-- Typing UI -->
    <div class="w-full max-w-3xl bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/60 dark:border-slate-700/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)]">
        
        <template x-if="!isGameOver">
            <div>
                <!-- Progress Header -->
                <div class="flex justify-between items-center mb-8">
                    <div class="text-sm font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-4 py-2 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                        TỪ VỰNG <span class="text-orange-600 dark:text-orange-400 text-lg ml-1" x-text="(currentIndex + 1) + '/' + shuffledList.length"></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-4 py-2 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                        <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span> <span x-text="score"></span>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="w-full bg-white dark:bg-slate-800 rounded-3xl p-8 mb-8 text-center border-2 border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden transition-colors duration-300"
                     :class="{'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20': showError, 'border-green-500 dark:border-green-500 bg-green-50 dark:bg-green-900/20': showSuccess}">
                    
                    <button @click="playWordAudio(currentWord.word)" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center hover:bg-orange-100 dark:hover:bg-orange-900/50 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">volume_up</span>
                    </button>
                    
                    <div class="text-sm font-bold text-slate-400 dark:text-slate-500 mb-4 uppercase tracking-widest">Gõ chữ Hán cho từ này</div>
                    <div class="text-xl md:text-2xl font-black text-slate-700 dark:text-slate-300 mb-2" x-text="currentWord.meaning"></div>
                </div>

                <!-- Input Area -->
                <div class="relative w-full max-w-md mx-auto">
                    <input 
                        x-ref="typingInput"
                        type="text" 
                        x-model="userInput"
                        @keydown.enter="checkAnswer()"
                        :disabled="hasAnswered"
                        class="w-full bg-white dark:bg-slate-800 border-2 rounded-2xl px-6 py-4 text-2xl font-black text-center text-slate-800 dark:text-white focus:outline-none focus:ring-4 transition-all duration-300"
                        :class="getInputClass()"
                        placeholder="Nhập chữ Hán..."
                        autocomplete="off"
                    >
                    
                    <button 
                        x-show="!hasAnswered"
                        @click="checkAnswer()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center hover:bg-orange-600 active:scale-95 transition-all"
                    >
                        <span class="material-symbols-outlined">keyboard_return</span>
                    </button>
                </div>
                
                <div class="text-center mt-6 h-8">
                    <button x-show="showError" @click="skipWord()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-bold text-sm underline transition-colors">Bỏ qua câu này</button>
                    <div x-show="showSuccess" class="text-green-500 font-bold animate-pulse">Chính xác! Đang qua từ tiếp theo...</div>
                </div>
            </div>
        </template>

        <!-- Game Over Screen -->
        <template x-if="isGameOver">
            <div class="py-10 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white text-4xl mb-6 shadow-xl shadow-orange-500/30">
                    <span class="material-symbols-outlined text-[40px]" x-text="score === shuffledList.length ? 'emoji_events' : 'thumb_up'"></span>
                </div>
                <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2">Hoàn thành!</h2>
                <p class="text-slate-500 dark:text-slate-400 mb-8 text-lg">Bạn đã gõ đúng <span class="text-orange-600 dark:text-orange-400 font-black" x-text="score"></span>/<span x-text="shuffledList.length"></span> từ vựng.</p>
                
                <button @click="initTyping()" class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/30 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined">refresh</span> Luyện tập lại
                </button>
            </div>
        </template>
    </div>
</div>
