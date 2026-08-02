<div class="w-full flex flex-col items-center" x-data="quizGameComponent()">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-purple-500/20">
            <span class="material-symbols-outlined text-[18px]">quiz</span>
        </div>
        <h3 class="text-lg font-extrabold text-purple-600 dark:text-purple-400">Trắc nghiệm</h3>
    </div>
    
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-1.5 mb-6 bg-slate-100/60 dark:bg-slate-800/60 p-1.5 rounded-xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button @click="viewMode = 'flashcard'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">style</span> Flashcard
        </button>
        <button @click="viewMode = 'match'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">extension</span> Nối từ
        </button>
        <button class="px-3.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-purple-600 dark:text-purple-400 font-bold text-xs sm:text-sm shadow-sm transition-all flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">quiz</span> Trắc nghiệm
        </button>
        <button @click="viewMode = 'typing'" class="px-3.5 py-1.5 rounded-lg text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm hover:bg-white dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">keyboard</span> Luyện gõ
        </button>
    </div>

    <!-- Quiz UI -->
    <div class="w-full max-w-2xl bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl rounded-2xl p-5 md:p-6 border border-white/60 dark:border-slate-700/60 shadow-sm">
        
        <template x-if="!isGameOver">
            <div>
                <!-- Progress Header -->
                <div class="flex justify-between items-center mb-6">
                    <div class="text-xs font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700 shadow-sm">
                        CÂU HỎI <span class="text-purple-600 dark:text-purple-400 text-sm ml-1" x-text="(currentIndex + 1) + '/' + questions.length"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700 shadow-sm">
                        <span class="material-symbols-outlined text-green-500 text-[16px]">check_circle</span> <span x-text="score"></span>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="w-full bg-white dark:bg-slate-800 rounded-2xl p-5 sm:p-6 mb-5 text-center border-2 border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                    <!-- Audio Button -->
                    <button @click="playWordAudio(currentQuestion.word.word)" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">volume_up</span>
                    </button>
                    
                    <div class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-2 uppercase tracking-wider" x-text="currentQuestion.type === 'meaning' ? 'Chọn nghĩa đúng' : 'Chọn chữ Hán đúng'"></div>
                    <div class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white" x-text="currentQuestion.questionText"></div>
                </div>

                <!-- Answer Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <template x-for="(option, index) in currentQuestion.options" :key="'opt-'+index">
                        <button 
                            @click="selectAnswer(index)"
                            :disabled="hasAnswered"
                            class="relative w-full flex items-center bg-white dark:bg-slate-800 border-2 rounded-xl p-3 transition-all duration-200 group"
                            :class="getOptionClass(index)"
                        >
                            <div class="w-5 h-5 rounded bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold transition-colors shrink-0 z-10"
                                 :class="getOptionBadgeClass(index)"
                                 x-text="index + 1"></div>
                            <div class="ml-3 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 text-left z-10" x-text="option.text"></div>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <!-- Game Over Screen -->
        <template x-if="isGameOver">
            <div class="py-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center text-white text-2xl mb-4 shadow-md shadow-purple-500/20">
                    <span class="material-symbols-outlined text-[28px]" x-text="score === questions.length ? 'emoji_events' : 'thumb_up'"></span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Hoàn thành!</h2>
                <p class="text-slate-500 dark:text-slate-400 mb-6 text-sm">Bạn đã trả lời đúng <span class="text-purple-600 dark:text-purple-400 font-bold" x-text="score"></span>/<span x-text="questions.length"></span> câu hỏi.</p>
                
                <button @click="initQuiz()" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">refresh</span> Làm lại
                </button>
            </div>
        </template>
    </div>
</div>
