<template x-if="mode === 'dictation' && status !== 'completed' && currentSentence">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch">

        <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 sm:p-6 shadow-xs flex flex-col justify-between space-y-4 h-full text-center">

            <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2 py-0.5 rounded-full bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926]">
                    {{ __('Luyện nghe câu') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">
                    {{ __('Space để nghe') }}
                </span>
            </div>

            <div class="space-y-4 my-auto py-2">
                <div class="py-1">
                    <button @click="playSentenceAudio()"
                            :class="isPlayingAudio ? 'bg-[#c86349] ring-8 ring-[#e07a5f]/20 scale-105' : 'bg-[#e07a5f] hover:bg-[#c86349] hover:scale-105 shadow-md shadow-[#e07a5f]/30'"
                            class="size-20 sm:size-24 rounded-full text-white mx-auto flex items-center justify-center transition-all duration-300 btn-tactile cursor-pointer"
                            title="{{ __('Bấm để nghe âm thanh') }}">
                        <i class="fa-solid fa-volume-high text-2xl sm:text-3xl" :class="{ 'animate-pulse scale-110': isPlayingAudio }"></i>
                    </button>
                </div>

                <div class="space-y-1 max-w-md mx-auto">
                    <p class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200" x-text="currentSentence.meaningVi || currentSentence.meaning"></p>
                    <div x-show="showPinyin">
                        <span class="text-xs font-semibold text-[#e07a5f]" x-text="currentSentence.pinyin"></span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#e8e2d9]/60 dark:border-[#2d2926] flex items-center justify-center gap-2">
                <button @click="giveDictationHint()"
                        :disabled="hintsLeft <= 0"
                        :class="{
                            'opacity-40 cursor-not-allowed bg-slate-100 dark:bg-[#1a1716] text-slate-400 dark:text-slate-600': hintsLeft <= 0,
                            'hover:text-amber-500 cursor-pointer bg-[#f8f6f3] dark:bg-[#23201e] text-slate-700 dark:text-slate-300': hintsLeft > 0
                        }"
                        class="px-3 py-1.5 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-semibold transition-all btn-tactile flex items-center gap-1.5"
                        :title="hintsLeft <= 0 ? '{{ __('Đã hết lượt gợi ý cho chủ đề này (tối đa 3 lần)') }}' : '{{ __('Gợi ý ký tự tiếp theo') }} (' + hintsLeft + '/3)'">
                    <i class="fa-solid fa-lightbulb text-xs" :class="hintsLeft > 0 ? 'text-amber-500' : 'text-slate-400 dark:text-slate-600'"></i>
                    <span>{{ __('Gợi ý ký tự tiếp theo') }}</span>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                          :class="hintsLeft > 0 ? 'bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-300/60 dark:border-amber-700/50' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                        <span x-text="hintsLeft"></span>/3
                    </span>
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 sm:p-6 shadow-xs flex flex-col justify-between space-y-4 h-full">

            <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-keyboard text-[#e07a5f]"></i>
                    {{ __('Nhập chữ Hán nghe được:') }}
                </span>
                <span class="text-slate-400 font-mono text-[11px]" x-text="dictationInput.length + ' / ' + (currentSentence.hanzi ? currentSentence.hanzi.replace(/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)\s]/g, '').length : 0) + ' chữ'"></span>
            </div>

            <div class="space-y-3 my-auto">

                <div class="relative">
                    <textarea x-model="dictationInput"
                              @keydown.enter.prevent="checkDictation()"
                              placeholder="{{ __('Gõ hoặc dán chữ Hán vào đây...') }}"
                              rows="3"
                              class="w-full zh-text p-3.5 text-base sm:text-lg font-bold bg-[#f8f6f3] dark:bg-[#23201e] border rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] transition-all resize-none shadow-xs"
                              :class="{
                                  'border-[#e8e2d9] dark:border-[#2d2926]': !dictationChecked,
                                  'border-emerald-500 bg-emerald-50/20': status === 'correct',
                                  'border-rose-400 bg-rose-50/20': status === 'wrong'
                              }"></textarea>
                </div>

                <div x-show="dictationChecked" x-transition class="p-3 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span>{{ __('Độ chính xác:') }}</span>
                        <span class="px-2 py-0.5 rounded-md text-xs font-bold"
                              :class="status === 'correct' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300'"
                              x-text="(dictationResult ? dictationResult.accuracy : 0) + '%'"></span>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                        <template x-for="(item, idx) in (dictationResult ? dictationResult.chars : [])" :key="idx">
                            <span class="zh-text px-2 py-1 rounded-lg text-sm font-bold border"
                                  :class="item.correct ? 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-300' : 'bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border-rose-300'"
                                  x-text="item.char"
                                  :title="'Kỳ vọng: ' + item.expected"></span>
                        </template>
                    </div>

                    <div x-show="status === 'wrong'" class="text-[11px] text-slate-500 dark:text-slate-400 pt-1">
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ __('Đáp án đúng:') }}</span>
                        <span class="zh-text font-bold text-emerald-600 dark:text-emerald-400 ml-1" x-text="currentSentence.hanzi"></span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <template x-if="status !== 'correct'">
                    <button @click="checkDictation()"
                            :disabled="!dictationInput.trim()"
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-white bg-[#e07a5f] hover:bg-[#c86349] disabled:opacity-50 disabled:cursor-not-allowed shadow-sm shadow-[#e07a5f]/30 transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                        <span>{{ __('Kiểm tra chính tả') }}</span>
                    </button>
                </template>

                <template x-if="status === 'correct'">
                    <button @click="nextSentence()"
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-white bg-emerald-500 hover:bg-emerald-600 shadow-sm shadow-emerald-500/30 transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer animate-bounce-subtle">
                        <span x-text="currentIndex + 1 < sentences.length ? '{{ __('Câu tiếp theo') }}' : '{{ __('Xem kết quả') }}'"></span>
                        <i class="fa-solid fa-arrow-right text-sm"></i>
                    </button>
                </template>
            </div>
        </div>
    </div>
</template>
