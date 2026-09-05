    <template x-if="vocabSubView === 'quiz'">
        <div x-data="vocabQuizEngine()" 
             x-init="initQuiz(vocabularies)"
             class="space-y-5 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <button @click="vocabSubView = 'table'" class="text-xs font-bold text-slate-500 hover:text-[#e07a5f] transition-colors flex items-center gap-1.5 btn-tactile">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Quay lại Bảng từ') }}
            </button>
            <div class="text-xs font-bold text-slate-400 flex items-center gap-2">
                <span>{{ __('Điểm:') }} <span class="text-[#e07a5f] font-bold" x-text="score">0</span></span>
                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                <span>{{ __('Streak:') }} <span class="text-[#f59e0b] font-bold" x-text="streak + ' 🔥'">0 🔥</span></span>
            </div>
        </div>
        <template x-if="!questions || questions.length === 0">
            <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-3">
                <i class="fa-solid fa-circle-exclamation text-3xl text-[#f59e0b]"></i>
                <h4 class="text-base font-bold text-slate-800 dark:text-white">{{ __('Cần ít nhất 4 từ vựng để tạo bài trắc nghiệm!') }}</h4>
                <p class="text-xs text-slate-500">{{ __('Bài học này chưa đủ lượng từ vựng để sinh câu hỏi trắc nghiệm.') }}</p>
                <button @click="vocabSubView = 'table'" class="px-4 py-2 bg-[#e07a5f] text-white text-xs font-bold rounded-xl btn-tactile">{{ __('Xem Bảng từ') }}</button>
            </div>
        </template>
        <!-- Main Quiz Question Card -->
        <template x-if="questions && questions.length > 0 && !isCompleted">
            <div class="space-y-4">
                <!-- Progress Header -->
                <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                    <span>{{ __('Câu hỏi') }} <span class="text-slate-800 dark:text-white font-bold" x-text="(currentIndex + 1) + ' / ' + questions.length"></span></span>
                    <span class="text-[#e07a5f] font-bold" x-text="Math.round(((currentIndex + 1) / questions.length) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-[#e8e2d9]/50 dark:bg-[#2d2926] rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#e07a5f] h-full rounded-full transition-all duration-300" :style="'width: ' + (((currentIndex + 1) / questions.length) * 100) + '%'"></div>
                </div>
                <!-- Question Prompt Card -->
                <div class="lms-card p-6 sm:p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-lg bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest" x-text="currentQuestion?.type || '{{ __('Từ vựng') }}'"></span>
                        <button @click="window.playAudio(currentQuestion?.audio_url || ('https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(currentQuestion?.word) + '&type=1'))" class="w-9 h-9 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs hover:scale-105 transition-all btn-tactile" title="{{ __('Phát âm') }}">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                    </div>
                    <div class="space-y-2 py-2">
                        <div class="text-4xl sm:text-6xl font-bold zh-text text-slate-900 dark:text-white tracking-wide" x-text="currentQuestion?.word"></div>
                        @if(hsk_should_show_pinyin($currentLesson->level ?? null))
                            <div class="text-sm font-mono font-bold text-[#e07a5f]" x-text="'[' + (currentQuestion?.pinyin || '') + ']'"></div>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        {{ __('Chọn ý nghĩa tiếng Việt chính xác nhất:') }}
                    </p>
                </div>
                <!-- Options List -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <template x-for="(opt, oIdx) in currentQuestion?.options" :key="currentIndex + '_' + oIdx">
                        <button @click="selectAnswer(oIdx)"
                                :disabled="isAnswered"
                                :class="[
                                    !isAnswered ? 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:bg-[#fff2ee]/30 dark:hover:bg-slate-800/50 text-slate-800 dark:text-slate-200' :
                                    (oIdx === currentQuestion.correctOptionIdx ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500 text-emerald-700 dark:text-emerald-300 font-bold ring-1 ring-emerald-500' :
                                    (selectedOption === oIdx ? 'bg-rose-50 dark:bg-rose-950/30 border-rose-500 text-rose-700 dark:text-rose-300' : 'opacity-50 bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] text-slate-500'))
                                ]"
                                class="p-4 rounded-xl border text-left text-xs sm:text-sm font-semibold transition-all flex items-center justify-between btn-tactile shadow-2xs">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-lg bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-bold flex items-center justify-center text-slate-500" x-text="String.fromCharCode(65 + oIdx)"></span>
                                <span x-text="opt"></span>
                            </div>
                            <template x-if="isAnswered && oIdx === currentQuestion.correctOptionIdx">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                            </template>
                            <template x-if="isAnswered && selectedOption === oIdx && oIdx !== currentQuestion.correctOptionIdx">
                                <i class="fa-solid fa-circle-xmark text-rose-500 text-base"></i>
                            </template>
                        </button>
                    </template>
                </div>
                <!-- Explanation & Next Button Card -->
                <template x-if="isAnswered">
                    <div class="lms-card p-4 sm:p-5 rounded-2xl border transition-all flex flex-col sm:flex-row items-center justify-between gap-4"
                         :class="selectedOption === currentQuestion.correctOptionIdx ? 'bg-emerald-50/70 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800' : 'bg-rose-50/70 dark:bg-rose-950/20 border-rose-300 dark:border-rose-800'">
                        <div class="space-y-1 text-left w-full sm:w-auto">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold" :class="selectedOption === currentQuestion.correctOptionIdx ? 'text-emerald-600' : 'text-rose-600'" x-text="selectedOption === currentQuestion.correctOptionIdx ? '{{ __('Chính xác! +10 điểm') }}' : '{{ __('Chưa chính xác!') }}'"></span>
                            </div>
                            <template x-if="currentQuestion.example">
                                <p class="text-[11px] text-slate-600 dark:text-slate-400 italic" x-text="'Ví dụ: ' + currentQuestion.example"></p>
                            </template>
                        </div>
                        <button @click="nextQuestion()" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs btn-tactile flex items-center justify-center gap-2 shrink-0">
                            <span x-text="currentIndex < questions.length - 1 ? '{{ __('Câu tiếp theo') }}' : '{{ __('Xem kết quả') }}'"></span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </template>
            </div>
        </template>
        <!-- Quiz Completion Screen -->
        <template x-if="isCompleted">
            <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-6 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-4xl mx-auto shadow-inner">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Hoàn thành bài kiểm tra!') }}</h3>
                    <p class="text-xs text-slate-500" x-text="correctCount === questions.length ? '{{ __('Xuất sắc! Bạn đã trả lời đúng 100% câu hỏi.') }}' : '{{ __('Rất tốt! Hãy tiếp tục luyện tập để nhớ lâu hơn.') }}'"></p>
                </div>
                <!-- Result Score Counter -->
                <div class="p-4 bg-[#fcfaf7] dark:bg-[#23201e] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] max-w-sm mx-auto flex items-center justify-around">
                    <div>
                        <div class="text-2xl font-bold text-[#e07a5f]" x-text="correctCount + ' / ' + questions.length"></div>
                        <div class="text-[10px] font-semibold text-slate-400">{{ __('Câu đúng') }}</div>
                    </div>
                    <div class="w-px h-8 bg-[#e8e2d9] dark:bg-[#2d2926]"></div>
                    <div>
                        <div class="text-2xl font-bold text-[#0284c7]" x-text="score"></div>
                        <div class="text-[10px] font-semibold text-slate-400">{{ __('Tổng điểm') }}</div>
                    </div>
                    <div class="w-px h-8 bg-[#e8e2d9] dark:bg-[#2d2926]"></div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-500" x-text="Math.round((correctCount / questions.length) * 100) + '%'"></div>
                        <div class="text-[10px] font-semibold text-slate-400">{{ __('Tỷ lệ đúng') }}</div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="initQuiz(vocabularies)" class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs btn-tactile flex items-center gap-2">
                        <i class="fa-solid fa-rotate-right"></i> {{ __('Làm lại bài thi') }}
                    </button>
                    <button @click="vocabSubView = 'table'" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] font-bold text-xs btn-tactile">
                        {{ __('Về Bảng từ') }}
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>
