<template x-if="(mode === 'scramble' || mode === 'ghep-cau') && status !== 'completed' && currentSentence">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch">

        <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 shadow-xs flex flex-col justify-between space-y-4 h-full">

            <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2 py-0.5 rounded-full bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926]">
                    {{ __('Đề bài') }}
                </span>
                <span class="text-xs text-slate-400 font-medium truncate max-w-[200px]" x-text="topic.titleVi || topic.title"></span>
            </div>

            <div class="space-y-4 my-auto py-2">
                <div class="space-y-1.5 text-center max-w-md mx-auto">
                    <span class="text-[10px] font-bold text-[#e07a5f] uppercase tracking-wider px-2 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2d201a] inline-block">
                        {{ __('Dịch sang tiếng Trung') }}
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white leading-relaxed pt-1" x-text="currentSentence.meaningVi || currentSentence.meaning"></h2>
                </div>

                <div x-show="showPinyin" class="text-center">
                    <span class="text-xs sm:text-sm font-semibold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2d201a] px-3 py-1 rounded-xl border border-[#fcdccf] dark:border-[#3d271e] inline-block tracking-wide shadow-xs" x-text="currentSentence.pinyin"></span>
                </div>

                <div x-show="status === 'correct'" x-transition class="pt-3 border-t border-emerald-100 dark:border-emerald-950 space-y-2.5 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button @click="playSentenceAudio()"
                                :class="isPlayingAudio ? 'bg-[#c86349] ring-3 ring-[#e07a5f]/30 scale-105' : 'bg-[#e07a5f] hover:bg-[#c86349]'"
                                class="size-9 rounded-xl text-white shadow-sm shadow-[#e07a5f]/30 flex items-center justify-center transition-all btn-tactile cursor-pointer"
                                title="{{ __('Nghe lại phát âm chuẩn') }}">
                            <i class="fa-solid fa-volume-high text-xs" :class="{ 'animate-pulse': isPlayingAudio }"></i>
                        </button>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ __('Phát âm chuẩn câu đúng:') }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-1.5 min-h-[36px]">
                        <template x-for="(item, idx) in (karaokeTokens.length > 0 ? karaokeTokens : currentSentence.words)" :key="idx">
                            <span class="zh-text text-lg sm:text-xl font-bold transition-all rounded-lg"
                                  :class="{
                                      'bg-[#e07a5f] text-white scale-105 shadow-xs px-2 py-0.5': (activeTokenIdx !== -1 ? activeTokenIdx === idx : activeWordIdx === idx),
                                      'text-emerald-600 dark:text-emerald-400 px-1.5 py-0.5': (activeTokenIdx !== -1 ? activeTokenIdx !== idx : activeWordIdx !== idx) && !item.isPunctuation,
                                      'text-emerald-500/80 dark:text-emerald-400/80 font-normal px-0.5': item.isPunctuation
                                  }"
                                  x-text="item.hanzi"></span>
                        </template>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#e8e2d9]/60 dark:border-[#2d2926] flex items-center justify-center gap-2">
                <button @click="giveHint()"
                        :disabled="hintsLeft <= 0 || status === 'correct'"
                        :class="{
                            'opacity-40 cursor-not-allowed bg-slate-100 dark:bg-[#1a1716] text-slate-400 dark:text-slate-600': hintsLeft <= 0,
                            'hover:text-amber-500 cursor-pointer bg-[#f8f6f3] dark:bg-[#23201e] text-slate-700 dark:text-slate-300': hintsLeft > 0
                        }"
                        class="px-3 py-1.5 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-semibold transition-all btn-tactile flex items-center gap-1.5"
                        :title="hintsLeft <= 0 ? '{{ __('Đã hết lượt gợi ý cho chủ đề này (tối đa 3 lần)') }}' : '{{ __('Gợi ý từ tiếp theo') }} (' + hintsLeft + '/3)'">
                    <i class="fa-solid fa-lightbulb text-xs" :class="hintsLeft > 0 ? 'text-amber-500' : 'text-slate-400 dark:text-slate-600'"></i>
                    <span>{{ __('Gợi ý từ tiếp theo') }}</span>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                          :class="hintsLeft > 0 ? 'bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-300/60 dark:border-amber-700/50' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                        <span x-text="hintsLeft"></span>/3
                    </span>
                </button>
                <button @click="playSentenceAudio()"
                        x-show="status === 'correct'"
                        class="px-3.5 py-1.5 rounded-lg bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#0284c7] text-xs font-semibold transition-all btn-tactile flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-volume-high text-[#0284c7] text-xs"></i>
                    <span>{{ __('Nghe lại') }}</span>
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 shadow-xs flex flex-col justify-between space-y-4 h-full">

            <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-puzzle-piece text-[#e07a5f]"></i>
                    {{ __('Ghép câu') }}
                </span>
                <button @click="resetChips()"
                        :disabled="selectedChips.length === 0 || status === 'correct'"
                        class="text-[11px] font-semibold text-slate-400 hover:text-[#e07a5f] disabled:opacity-40 transition-colors flex items-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-arrow-rotate-left text-[10px]"></i>
                    <span>{{ __('Làm lại') }}</span>
                </button>
            </div>

            <div class="space-y-3.5 my-auto">

                <div class="bg-[#f8f6f3] dark:bg-[#23201e] rounded-xl border-2 border-dashed border-[#e8e2d9] dark:border-[#2d2926] p-3.5 min-h-[88px] flex flex-wrap items-center justify-center content-center gap-2 transition-all relative"
                     :class="{
                         'border-emerald-400 dark:border-emerald-600 bg-emerald-50/30 dark:bg-emerald-950/20': status === 'correct',
                         'border-rose-400 dark:border-rose-600 bg-rose-50/30 dark:bg-rose-950/20': status === 'wrong'
                     }">
                    <template x-if="selectedChips.length === 0">
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 italic text-center select-none">
                            {{ __('Bấm chọn các thẻ từ bên dưới để ghép vào đây...') }}
                        </p>
                    </template>

                    <template x-for="chip in selectedChips" :key="chip.id">
                        <button @click="unselectChip(chip)"
                                class="zh-text px-3 py-1.5 rounded-xl text-base sm:text-lg font-bold shadow-xs transition-all transform active:scale-95 cursor-pointer"
                                :class="status === 'correct' ? 'bg-emerald-500 text-white shadow-emerald-500/20' : (status === 'wrong' ? 'bg-rose-500 text-white shadow-rose-500/20' : 'bg-[#e07a5f] text-white shadow-[#e07a5f]/20 hover:bg-[#c86349]')">
                            <span x-text="chip.hanzi"></span>
                        </button>
                    </template>
                </div>

                <div class="pt-1">
                    <div class="flex flex-wrap items-center justify-center gap-2 min-h-[64px]">
                        <template x-for="chip in availableChips" :key="chip.id">
                            <button @click="selectChip(chip)"
                                    class="zh-text px-3 py-1.5 rounded-xl text-base sm:text-lg font-bold bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-slate-100 hover:border-[#e07a5f] hover:text-[#e07a5f] hover:-translate-y-0.5 shadow-xs transition-all transform active:scale-95 btn-tactile cursor-pointer">
                                <span x-text="chip.hanzi"></span>
                            </button>
                        </template>

                        <template x-if="availableChips.length === 0 && selectedChips.length > 0">
                            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 text-center py-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('Đã xếp hết các thẻ từ!') }}</span>
                            </p>
                        </template>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <template x-if="status !== 'correct'">
                    <button @click="checkAnswer()"
                            :disabled="selectedChips.length === 0"
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-white bg-[#e07a5f] hover:bg-[#c86349] disabled:opacity-50 disabled:cursor-not-allowed shadow-sm shadow-[#e07a5f]/30 transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                        <span>{{ __('Kiểm tra đáp án') }}</span>
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
