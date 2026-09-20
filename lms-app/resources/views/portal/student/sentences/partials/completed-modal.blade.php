<template x-if="status === 'completed'">
    <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 sm:p-6 text-center space-y-3.5 shadow-xs max-w-md mx-auto my-3">
        <div class="size-11 rounded-xl bg-[#fff2ee] dark:bg-[#2a201c] flex items-center justify-center text-[#e07a5f] mx-auto shadow-xs">
            <i class="fa-solid fa-trophy text-base"></i>
        </div>

        <div class="space-y-1">
            <h2 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">
                {{ __('Hoàn thành xuất sắc bài học!') }}
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                {{ __('Bạn đã hoàn thành tất cả các câu trong chủ đề') }} <strong class="text-slate-700 dark:text-slate-200" x-text="topic.titleVi || topic.title"></strong>.
            </p>
        </div>

        <template x-if="isSubmittingExp">
            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926] flex items-center justify-center gap-2 text-xs font-medium text-slate-500">
                <i class="fa-solid fa-spinner fa-spin text-[#e07a5f]"></i>
                <span>{{ __('Đang cập nhật kết quả và điểm kinh nghiệm...') }}</span>
            </div>
        </template>

        <template x-if="expResponse && expResponse.gamification && expResponse.gamification.exp_gained">
            <div class="p-2.5 rounded-xl bg-gradient-to-br from-[#fff6f3] to-[#fff1ec] dark:from-[#251d1a] dark:to-[#1f1714] border border-[#fcdccf] dark:border-[#3d271e] space-y-1.5">
                <div class="flex items-center justify-center gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#e07a5f] text-white text-[10px] font-bold shadow-xs">
                        <i class="fa-solid fa-bolt text-[9px]"></i>
                        <span x-text="'+' + expResponse.gamification.exp_gained + ' EXP'"></span>
                    </span>
                    <template x-if="expResponse.user && expResponse.user.current_streak">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400 text-[10px] font-bold">
                            <span>🔥</span>
                            <span x-text="expResponse.user.current_streak + ' ' + '{{ __('ngày') }}'"></span>
                        </span>
                    </template>
                    <template x-if="expResponse.user && expResponse.user.level">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-900/10 dark:bg-white/10 text-slate-700 dark:text-slate-200 text-[10px] font-bold"
                              x-text="'Lv.' + expResponse.user.level"></span>
                    </template>
                </div>

                <template x-if="expResponse.bonus_info && expResponse.bonus_info.length > 0">
                    <div class="flex flex-wrap items-center justify-center gap-1 pt-0.5">
                        <template x-for="(bonus, bIdx) in expResponse.bonus_info" :key="bIdx">
                            <span class="text-[10px] font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[9px]"></i>
                                <span x-text="bonus"></span>
                            </span>
                        </template>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="expResponse && expResponse.require_login">
            <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/40 text-xs text-amber-700 dark:text-amber-300 flex items-center justify-between gap-2">
                <span class="text-left font-medium text-[11px]">{{ __('Đăng nhập để nhận điểm EXP và duy trì Streak!') }}</span>
                <a href="{{ route('login') }}" class="px-2.5 py-1 rounded-lg bg-amber-500 text-white font-bold text-[10px] shrink-0 hover:bg-amber-600 transition-colors">
                    {{ __('Đăng nhập') }}
                </a>
            </div>
        </template>

        <div class="grid grid-cols-2 gap-2 max-w-xs mx-auto">
            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926] space-y-0.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Tổng điểm') }}</span>
                <p class="text-sm font-bold text-[#e07a5f]" x-text="stats.score + ' đ'"></p>
            </div>
            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926] space-y-0.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Số câu hoàn thành') }}</span>
                <p class="text-sm font-bold text-emerald-600" x-text="sentences.length + '/' + sentences.length"></p>
            </div>
        </div>

        <div class="pt-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 w-full max-w-[410px] mx-auto">
                @if($mode === 'scramble' || $mode === 'ghep-cau')
                    <button @click="restartPractice()"
                            class="w-full py-2.5 px-3.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all btn-tactile flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                        <i class="fa-solid fa-rotate-right text-[11px]"></i>
                        <span x-text="'{{ __('Làm lại ') }}' + sentences.length + ' {{ __('câu này') }}'"></span>
                    </button>

                    <a href="{{ route('sentences.index', ['mode' => 'ghep-cau', 'level' => $level]) }}"
                        class="w-full py-2.5 px-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:text-[#e07a5f] text-xs font-bold transition-all btn-tactile flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-list-check text-xs"></i>
                        <span>{{ __('Quay về trang chủ đề') }}</span>
                    </a>
                @else
                    @if($isRandom ?? false)
                        <button @click="loadNextRandomRound()"
                                :disabled="isLoadingNextRound"
                                class="w-full py-2.5 px-3.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all btn-tactile flex items-center justify-center gap-1.5 cursor-pointer disabled:opacity-70 whitespace-nowrap">
                            <template x-if="isLoadingNextRound">
                                <i class="fa-solid fa-spinner fa-spin text-xs"></i>
                            </template>
                            <template x-if="!isLoadingNextRound">
                                <i class="fa-solid fa-forward-step text-xs"></i>
                            </template>
                            <span x-text="'{{ __('Luyện tiếp ') }}' + (sentences.length || 15) + ' {{ __('câu khác') }}'"></span>
                        </button>
                    @else
                        <a href="{{ route('sentences.random', ['mode' => $mode, 'level' => $level]) }}"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all btn-tactile flex items-center justify-center gap-1.5 whitespace-nowrap">
                            <i class="fa-solid fa-shuffle text-xs"></i>
                            <span>{{ __('Luyện 15 câu ngẫu nhiên') }}</span>
                        </a>
                    @endif

                    <button @click="restartPractice()"
                            class="w-full py-2.5 px-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:text-[#e07a5f] text-xs font-bold transition-all btn-tactile flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                        <i class="fa-solid fa-rotate-right text-[11px]"></i>
                        <span x-text="'{{ __('Luyện tập lại ') }}' + sentences.length + ' {{ __('câu này') }}'"></span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</template>
