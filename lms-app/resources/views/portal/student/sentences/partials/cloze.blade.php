<template x-if="mode === 'cloze' && status !== 'completed' && currentSentence">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch">

        <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 shadow-xs flex flex-col justify-between space-y-4 h-full">

            <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2 py-0.5 rounded-full bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926]">
                    {{ __('Câu hỏi') }}
                </span>
            </div>

            <div class="space-y-4 my-auto text-center py-2">
                <div class="py-2">
                    <div class="zh-text text-xl sm:text-2xl font-bold text-slate-900 dark:text-white leading-relaxed inline-flex items-center flex-wrap justify-center gap-1.5">
                        <span x-text="clozeData.prefix"></span>

                        <span class="inline-flex items-center justify-center min-w-[70px] px-3.5 py-1 rounded-xl border-2 transition-all font-bold"
                              :class="{
                                  'border-dashed border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2d201a] text-[#e07a5f] animate-pulse': !clozeData.userChoice,
                                  'border-emerald-500 bg-emerald-500 text-white shadow-xs': clozeData.userChoice && status === 'correct',
                                  'border-rose-500 bg-rose-500 text-white shadow-xs': clozeData.userChoice && status === 'wrong'
                              }"
                              x-text="clozeData.userChoice || '____'"></span>

                        <span x-text="clozeData.suffix"></span>
                    </div>
                </div>

                <div class="space-y-1.5 max-w-md mx-auto">
                    <div x-show="showPinyin">
                        <span class="text-xs sm:text-sm font-semibold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2d201a] px-3 py-1 rounded-xl border border-[#fcdccf] dark:border-[#3d271e] inline-block tracking-wide" x-text="currentSentence.pinyin"></span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed" x-text="currentSentence.meaningVi || currentSentence.meaning"></p>
                </div>

                <div x-show="status === 'correct'" x-transition class="pt-2 text-center">
                    <button @click="playSentenceAudio()"
                            class="px-3.5 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-xs font-bold inline-flex items-center gap-1.5 hover:bg-emerald-100 transition-all cursor-pointer">
                        <i class="fa-solid fa-volume-high text-xs"></i>
                        <span>{{ __('Phát âm cả câu') }}</span>
                    </button>
                </div>
            </div>

            <div class="pt-3 border-t border-[#e8e2d9]/60 dark:border-[#2d2926] text-center text-xs text-slate-400">
                {{ __('Bấm vào 1 trong 4 phương án bên phải để kiểm tra') }}
            </div>
        </div>

        <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 shadow-xs flex flex-col justify-between space-y-4 h-full">

            <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-[#e07a5f]"></i>
                    {{ __('Các phương án lựa chọn:') }}
                </span>
                <span x-show="status === 'wrong'" class="text-rose-500 font-semibold text-[11px] flex items-center gap-1">
                    <i class="fa-solid fa-circle-xmark"></i> {{ __('Chưa chính xác!') }}
                </span>
            </div>

            <template x-if="isReviewingWrong">
                <div class="px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/25 border border-amber-200/60 dark:border-amber-800/40 text-amber-700 dark:text-amber-300 text-xs font-bold flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                    <span>{{ __('Làm lại câu bạn đã trả lời chưa đúng') }}</span>
                </div>
            </template>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 my-auto">
                <template x-for="option in clozeData.options" :key="option.id">
                    <button @click="selectClozeOption(option)"
                            :disabled="clozeData.isAnswered"
                            class="p-3.5 rounded-xl border text-left transition-all transform active:scale-98 btn-tactile flex items-center justify-between group"
                            :class="{
                                'cursor-pointer': !clozeData.isAnswered,
                                'cursor-default': clozeData.isAnswered,
                                'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:bg-[#fff7f4] dark:hover:bg-[#2a2220]': !clozeData.userChoice,
                                'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] dark:border-[#2d2926] opacity-40': clozeData.isAnswered && clozeData.userChoice !== option.text,
                                'bg-emerald-500 border-emerald-500 text-white shadow-sm shadow-emerald-500/30': clozeData.isAnswered && clozeData.userChoice === option.text && option.correct,
                                'bg-rose-500 border-rose-500 text-white shadow-sm shadow-rose-500/30': clozeData.isAnswered && clozeData.userChoice === option.text && !option.correct
                            }">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="size-6 rounded-lg flex items-center justify-center text-xs font-bold transition-colors shrink-0"
                                  :class="(clozeData.userChoice === option.text) ? 'bg-white/20 text-white' : 'bg-white dark:bg-[#181615] text-slate-500 group-hover:text-[#e07a5f] group-hover:bg-[#fff2ee] border border-[#e8e2d9]/60 dark:border-[#2d2926]'">
                                <span x-text="option.label"></span>
                            </span>
                            <span class="zh-text text-base sm:text-lg font-bold truncate"
                                  :class="(clozeData.userChoice === option.text) ? 'text-white' : 'text-slate-800 dark:text-slate-100 group-hover:text-[#e07a5f]'"
                                  x-text="option.text"></span>
                        </div>
                        <div x-show="clozeData.isAnswered && clozeData.userChoice === option.text" class="shrink-0 ml-1">
                            <i class="fa-solid text-sm text-white" :class="option.correct ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                        </div>
                    </button>
                </template>
            </div>

            <div class="pt-3 border-t border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <template x-if="status === 'correct'">
                    <button @click="nextSentence()"
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-white bg-emerald-500 hover:bg-emerald-600 shadow-sm shadow-emerald-500/30 transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer animate-bounce-subtle">
                        <span x-text="currentIndex + 1 < sentences.length ? '{{ __('Câu tiếp theo') }}' : (retryQueue.length > 0 ? '{{ __('Làm lại các câu sai') }}' : '{{ __('Xem kết quả') }}')"></span>
                        <i class="fa-solid fa-arrow-right text-sm"></i>
                    </button>
                </template>
                <template x-if="status === 'wrong'">
                    <div class="py-2.5 text-center text-rose-500 font-bold text-xs flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-xmark text-sm animate-pulse"></i>
                        <span>{{ __('Chưa chính xác! Đang chuyển tiếp...') }}</span>
                    </div>
                </template>
                <template x-if="status === 'idle'">
                    <p class="text-xs text-slate-400 text-center py-2 font-medium">
                        {{ __('Chọn từ đúng để kiểm tra') }}
                    </p>
                </template>
            </div>
        </div>
    </div>
</template>
