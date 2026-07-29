<div class="w-full flex flex-col items-center" x-data="quizGameComponent()">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-purple-500/30">
            <span class="material-symbols-outlined text-[20px]">quiz</span>
        </div>
        <h3 class="text-2xl font-black text-purple-600 dark:text-purple-400">Trắc nghiệm</h3>
    </div>
    
    <!-- Game Switcher Buttons -->
    <div class="flex items-center justify-center gap-3 mb-8 bg-slate-100/50 dark:bg-slate-800/50 p-2 rounded-2xl border border-slate-200/50 dark:border-slate-700/50 backdrop-blur-md">
        <button @click="viewMode = 'flashcard'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">style</span> Flashcard
        </button>
        <button @click="viewMode = 'match'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">extension</span> Nối từ
        </button>
        <button class="px-5 py-2.5 rounded-xl bg-white dark:bg-slate-700 text-purple-600 dark:text-purple-400 font-black text-sm shadow-md transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">quiz</span> Trắc nghiệm
        </button>
        <button @click="viewMode = 'typing'" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">keyboard</span> Luyện gõ
        </button>
    </div>

    <!-- Quiz UI -->
    <div class="w-full max-w-3xl bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/60 dark:border-slate-700/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)]">
        
        <template x-if="!isGameOver">
            <div>
                <!-- Progress Header -->
                <div class="flex justify-between items-center mb-8">
                    <div class="text-sm font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-4 py-2 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                        CÂU HỎI <span class="text-purple-600 dark:text-purple-400 text-lg ml-1" x-text="(currentIndex + 1) + '/' + questions.length"></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-400 dark:text-slate-500 bg-white/60 dark:bg-slate-800/60 px-4 py-2 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                        <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span> <span x-text="score"></span>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="w-full bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 mb-6 text-center border-2 border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                    <!-- Audio Button -->
                    <button @click="playWordAudio(currentQuestion.word.word)" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">volume_up</span>
                    </button>
                    
                    <div class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-3 uppercase tracking-widest" x-text="currentQuestion.type === 'meaning' ? 'Chọn nghĩa đúng' : 'Chọn chữ Hán đúng'"></div>
                    <div class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-800 dark:text-white" x-text="currentQuestion.questionText"></div>
                </div>

                <!-- Answer Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <template x-for="(option, index) in currentQuestion.options" :key="'opt-'+index">
                        <button 
                            @click="selectAnswer(index)"
                            :disabled="hasAnswered"
                            class="relative w-full flex items-center bg-white dark:bg-slate-800 border-2 rounded-2xl p-3.5 sm:p-4 transition-all duration-200 group"
                            :class="getOptionClass(index)"
                        >
                            <div class="w-6 h-6 rounded bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold transition-colors shrink-0 z-10"
                                 :class="getOptionBadgeClass(index)"
                                 x-text="index + 1"></div>
                            <div class="ml-3.5 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 text-left z-10" x-text="option.text"></div>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <!-- Game Over Screen -->
        <template x-if="isGameOver">
            <div class="py-10 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center text-white text-4xl mb-6 shadow-xl shadow-purple-500/30">
                    <span class="material-symbols-outlined text-[40px]" x-text="score === questions.length ? 'emoji_events' : 'thumb_up'"></span>
                </div>
                <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2">Hoàn thành!</h2>
                <p class="text-slate-500 dark:text-slate-400 mb-8 text-lg">Bạn đã trả lời đúng <span class="text-purple-600 dark:text-purple-400 font-black" x-text="score"></span>/<span x-text="questions.length"></span> câu hỏi.</p>
                
                <button @click="initQuiz()" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined">refresh</span> Làm lại
                </button>
            </div>
        </template>
    </div>
</div>
