    <template x-if="vocabSubView === 'typing'">
        <div x-data="vocabTypingEngine()" 
             x-init="initTyping(vocabularies)"
             class="space-y-5 max-w-xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <button @click="vocabSubView = 'table'" class="text-xs font-bold text-slate-500 hover:text-[#e07a5f] transition-colors flex items-center gap-1.5 btn-tactile">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Quay lại Bảng từ') }}
            </button>
            <div class="text-xs font-bold text-slate-400 flex items-center gap-2">
                <span>{{ __('Chính xác:') }} <span class="text-emerald-500 font-bold" x-text="correctCount + ' / ' + words.length"></span></span>
            </div>
        </div>
        <template x-if="!words || words.length === 0">
            <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-3">
                <i class="fa-solid fa-circle-exclamation text-3xl text-[#f59e0b]"></i>
                <h4 class="text-base font-bold text-slate-800 dark:text-white">{{ __('Chưa có từ vựng để luyện gõ!') }}</h4>
                <button @click="vocabSubView = 'table'" class="px-4 py-2 bg-[#e07a5f] text-white text-xs font-bold rounded-xl btn-tactile">{{ __('Xem Bảng từ') }}</button>
            </div>
        </template>
        <!-- Main Typing Card -->
        <template x-if="words && words.length > 0 && !isCompleted">
            <div class="space-y-4">
                <!-- Progress Header -->
                <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                    <span>{{ __('Từ vựng') }} <span class="text-slate-800 dark:text-white font-bold" x-text="(currentIndex + 1) + ' / ' + words.length"></span></span>
                    <span class="text-[#e07a5f] font-bold" x-text="Math.round(((currentIndex + 1) / words.length) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-[#e8e2d9]/50 dark:bg-[#2d2926] rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#e07a5f] h-full rounded-full transition-all duration-300" :style="'width: ' + (((currentIndex + 1) / words.length) * 100) + '%'"></div>
                </div>
                <!-- Prompt Card -->
                <div class="lms-card p-6 sm:p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-lg bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[10px] font-bold text-slate-500 uppercase tracking-widest" x-text="currentWord?.type || '{{ __('Từ vựng') }}'"></span>
                        <button @click="window.playAudio(currentWord?.audio_url || ('https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(currentWord?.word) + '&type=1'))" class="w-9 h-9 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs hover:scale-105 transition-all btn-tactile" title="{{ __('Phát âm') }}">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                    </div>
                    <div class="space-y-2 py-3">
                        <div class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white" x-text="currentWord?.meaning"></div>
                        <template x-if="showHint">
                            @if(hsk_should_show_pinyin($currentLesson->level ?? null))
                                <div class="text-sm font-mono font-bold text-[#e07a5f] transition-all" x-text="'Gợi ý: [' + currentWord?.pinyin + '] ' + currentWord?.word"></div>
                            @else
                                <div class="text-sm font-mono font-bold text-[#e07a5f] transition-all" x-text="'Gợi ý: ' + currentWord?.word"></div>
                            @endif
                        </template>
                    </div>
                    <!-- Input Box -->
                    <div class="max-w-md mx-auto space-y-3">
                        <div class="relative">
                            <input type="text" 
                                   x-model="inputVal" 
                                   @keyup.enter="checkInput()"
                                   :readonly="status === 'correct'"
                                   placeholder="{{ __('Gõ chữ Hán hoặc Pinyin của từ này...') }}"
                                   :class="[
                                       status === 'idle' ? 'border-[#e8e2d9] dark:border-[#2d2926] focus:border-[#e07a5f] focus:ring-1 focus:ring-[#e07a5f]' :
                                       (status === 'correct' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-300 font-bold' :
                                       'border-rose-400 bg-rose-50/50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300')
                                   ]"
                                   class="w-full px-4 py-3 pr-10 rounded-xl text-sm bg-[#fcfaf7] dark:bg-[#23201e] text-slate-900 dark:text-white outline-none transition-all">
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center">
                                <i x-show="status === 'correct'" style="display: none;" class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                                <i x-show="status === 'wrong'" style="display: none;" class="fa-solid fa-circle-xmark text-rose-500 text-base"></i>
                            </div>
                        </div>
                        <!-- Feedback and Actions -->
                        <div class="flex items-center justify-between pt-1">
                            <button @click="showHint = !showHint" class="text-xs text-slate-400 hover:text-[#e07a5f] font-semibold transition-colors flex items-center gap-1 btn-tactile">
                                <i class="fa-regular fa-lightbulb"></i>
                                <span x-text="showHint ? '{{ __('Ẩn gợi ý') }}' : '{{ __('Xem gợi ý') }}'"></span>
                            </button>
                            <template x-if="status !== 'correct'">
                                <button @click="checkInput()" class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs btn-tactile flex items-center gap-1.5">
                                    {{ __('Kiểm tra') }}
                                </button>
                            </template>
                            <template x-if="status === 'correct'">
                                <button @click="nextWord()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs btn-tactile flex items-center gap-1.5">
                                    <span x-text="currentIndex < words.length - 1 ? '{{ __('Từ tiếp theo') }}' : '{{ __('Xem kết quả') }}'"></span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <!-- Typing Completion Screen -->
        <template x-if="isCompleted">
            <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center space-y-6 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-4xl mx-auto shadow-inner">
                    <i class="fa-solid fa-keyboard"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Hoàn thành bài Luyện gõ!') }}</h3>
                    <p class="text-xs text-slate-500">{{ __('Bạn đã luyện tập gõ qua tất cả các từ vựng của bài học này.') }}</p>
                </div>
                <div class="p-4 bg-[#fcfaf7] dark:bg-[#23201e] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] max-w-xs mx-auto">
                    <div class="text-2xl font-bold text-emerald-500" x-text="correctCount + ' / ' + words.length"></div>
                    <div class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ __('Từ vựng gõ đúng ngay lần đầu') }}</div>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="initTyping(vocabularies)" class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs btn-tactile flex items-center gap-2">
                        <i class="fa-solid fa-rotate-right"></i> {{ __('Luyện gõ lại') }}
                    </button>
                    <button @click="vocabSubView = 'table'" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] font-bold text-xs btn-tactile">
                        {{ __('Về Bảng từ') }}
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>
