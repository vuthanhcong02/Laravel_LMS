<div class="space-y-5 max-w-2xl mx-auto py-2">
    <div class="flex items-center justify-between">
        <div class="inline-flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs shadow-xs">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Trắc Nghiệm Nhanh') }}</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Chọn nghĩa tiếng Việt chính xác cho chữ Hán.') }}</p>
            </div>
        </div>

        <button type="button"
                @click="initQuiz()"
                class="w-8 h-8 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-500 hover:text-[#e07a5f] hover:border-[#e07a5f] text-xs flex items-center justify-center transition-all btn-tactile cursor-pointer"
                :title="'{{ __('Làm lại') }}'">
            <i class="fa-solid fa-rotate-right"></i>
        </button>
    </div>

    <template x-if="!quizQuestions || quizQuestions.length === 0">
        <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center space-y-4 shadow-xs">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 text-amber-500 flex items-center justify-center text-2xl mx-auto shadow-xs">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div class="space-y-1 max-w-sm mx-auto">
                <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Cần ít nhất 4 từ vựng để tạo bài trắc nghiệm') }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Danh mục này hiện chưa có đủ số lượng từ vựng cần thiết để tạo 4 đáp án lựa chọn.') }}</p>
            </div>
            <button type="button"
                    @click="switchPracticeMode('flashcard')"
                    class="px-4 py-2 bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold rounded-xl btn-tactile shadow-xs cursor-pointer">
                {{ __('Chuyển về Lật thẻ 3D') }}
            </button>
        </div>
    </template>

    <template x-if="quizQuestions && quizQuestions.length > 0 && !quizIsCompleted">
        <div class="space-y-4">
            <div class="space-y-1.5">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-400 px-0.5">
                    <span>
                        {{ __('Câu hỏi') }}
                        <strong class="text-slate-800 dark:text-white font-bold" x-text="(quizCurrentIndex + 1) + ' / ' + quizQuestions.length"></strong>
                    </span>
                    <span class="text-[#e07a5f] font-bold" x-text="Math.round(((quizCurrentIndex + 1) / quizQuestions.length) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-[#e8e2d9]/60 dark:bg-[#2d2926] rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#e07a5f] h-full rounded-full transition-all duration-300"
                         :style="'width: ' + (((quizCurrentIndex + 1) / quizQuestions.length) * 100) + '%'"></div>
                </div>
            </div>

            <div class="lms-card p-6 sm:p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center space-y-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest"
                          x-text="'{{ __('Từ vựng') }}'"></span>

                    <button type="button"
                            @click="speak(currentQuizQuestion ? currentQuizQuestion.word : '')"
                            class="w-8 h-8 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs hover:scale-110 transition-all btn-tactile cursor-pointer"
                            :title="'{{ __('Nghe phát âm') }}'">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                </div>

                <div class="space-y-2 py-3">
                    <div class="text-5xl sm:text-7xl font-bold zh-text text-slate-900 dark:text-white tracking-wide"
                         x-text="currentQuizQuestion ? currentQuizQuestion.word : ''"></div>
                    <div class="text-sm sm:text-base font-bold text-[#e07a5f]"
                         x-text="'[' + (currentQuizQuestion && currentQuizQuestion.pinyin ? currentQuizQuestion.pinyin : '') + ']'"></div>
                </div>

                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    {{ __('Chọn ý nghĩa tiếng Việt chính xác nhất:') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <template x-for="(opt, oIdx) in (currentQuizQuestion && currentQuizQuestion.options ? currentQuizQuestion.options : [])" :key="quizCurrentIndex + '_' + oIdx">
                    <button type="button"
                            @click="selectQuizOption(opt)"
                            :disabled="quizIsAnswered"
                            :class="[
                                !quizIsAnswered ? 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:bg-[#fff2ee]/30 dark:hover:bg-slate-800/50 text-slate-800 dark:text-slate-200' :
                                (opt.isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-500 text-emerald-700 dark:text-emerald-300 font-bold ring-1 ring-emerald-500' :
                                (quizSelectedOption === opt ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-500 text-rose-700 dark:text-rose-300' : 'opacity-50 bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] text-slate-500'))
                            ]"
                            class="p-4 rounded-2xl border text-left text-xs sm:text-sm font-semibold transition-all flex items-center justify-between btn-tactile shadow-xs cursor-pointer">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-bold flex items-center justify-center text-slate-500 shrink-0"
                                  x-text="String.fromCharCode(65 + oIdx)"></span>
                            <span x-text="typeof opt === 'object' ? (opt.text || opt.meaning || opt.word) : opt"></span>
                        </div>
                        <template x-if="quizIsAnswered && opt.isCorrect">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                        </template>
                        <template x-if="quizIsAnswered && quizSelectedOption === opt && !opt.isCorrect">
                            <i class="fa-solid fa-circle-xmark text-rose-500 text-base"></i>
                        </template>
                    </button>
                </template>
            </div>

            <template x-if="quizIsAnswered">
                <div class="lms-card p-4 sm:p-5 rounded-2xl border transition-all flex flex-col sm:flex-row items-center justify-between gap-4"
                     :class="(quizSelectedOption && quizSelectedOption.isCorrect) ? 'bg-emerald-50/80 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800' : 'bg-rose-50/80 dark:bg-rose-950/20 border-rose-300 dark:border-rose-800'">
                    <div class="space-y-1 text-left w-full sm:w-auto">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold"
                                  :class="(quizSelectedOption && quizSelectedOption.isCorrect) ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                                  x-text="(quizSelectedOption && quizSelectedOption.isCorrect) ? '{{ __('Chính xác!') }}' : '{{ __('Chưa chính xác!') }}'"></span>
                        </div>
                        <template x-if="currentQuizQuestion && currentQuizQuestion.example">
                            <p class="text-[11px] text-slate-600 dark:text-slate-400 italic"
                               x-text="'Ví dụ: ' + currentQuizQuestion.example"></p>
                        </template>
                    </div>
                    <button type="button"
                            @click="nextQuizQuestion()"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs btn-tactile flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                        <span x-text="quizCurrentIndex < quizQuestions.length - 1 ? '{{ __('Câu tiếp theo') }}' : '{{ __('Xem kết quả') }}'"></span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </template>
        </div>
    </template>

    <template x-if="quizIsCompleted">
        <div class="lms-card p-8 sm:p-10 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center space-y-6 shadow-sm">
            <div class="w-20 h-20 rounded-3xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-4xl mx-auto shadow-xs">
                <i class="fa-solid fa-award"></i>
            </div>
            <div class="space-y-2 max-w-md mx-auto">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                    {{ __('Hoàn Thành Bài Trắc Nghiệm!') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Bạn đã hoàn thành lượt luyện tập trắc nghiệm từ vựng.') }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 max-w-xs mx-auto">
                <div class="p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">{{ __('Số câu đúng') }}</div>
                    <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400"
                         x-text="quizCorrectCount + ' / ' + quizQuestions.length"></div>
                </div>
                <div class="p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">{{ __('Độ chính xác') }}</div>
                    <div class="text-lg font-bold text-[#e07a5f]"
                         x-text="Math.round((quizCorrectCount / (quizQuestions.length || 1)) * 100) + '%'"></div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <button type="button"
                        @click="initQuiz()"
                        class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                    <span>{{ __('Luyện tập lại') }}</span>
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
