@php
    $isLoggedIn = auth()->check();
    $user = auth()->user();
    $streak = $isLoggedIn ? ($user->current_streak ?? 0) : 0;
    $longestStreak = $isLoggedIn ? ($user->longest_streak ?? 0) : 0;
    $todayExp = $isLoggedIn ? ($user->today_exp ?? 0) : 0;
    $expTotal = $isLoggedIn ? ($user->exp_total ?? 0) : 0;
    $levelBadge = $isLoggedIn ? ($user->level_badge ?? 'Lv.1') : 'Lv.1';
    $level = $isLoggedIn ? ($user->level ?? 1) : 1;
    $levelPercent = $isLoggedIn ? ($user->level_progress_percent ?? 0) : 0;
    $maxLevel = config('gamification.levels.max_level', 30);
    $thresholds = config('gamification.levels.thresholds', []);
@endphp

<div x-data="headerStreakWidget({
    isLoggedIn: {{ $isLoggedIn ? 'true' : 'false' }},
    streak: {{ $streak }},
    longestStreak: {{ $longestStreak }},
    todayExp: {{ $todayExp }},
    expTotal: {{ $expTotal }},
    maxLevel: {{ $maxLevel }},
    thresholds: @js($thresholds)
})" @exp-updated.window="onExpUpdated($event.detail)" class="relative">
    <button type="button" @click="tooltipOpen = !tooltipOpen" @click.outside="tooltipOpen = false"
        class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl border bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-700 transition-all btn-tactile cursor-pointer select-none"
        title="{{ __('Chuỗi ngày học liên tục & Cấp độ') }}">
        
        <div class="flex items-center gap-1">
            <span class="text-sm leading-none">🔥</span>
            <span class="text-xs font-bold font-mono tracking-tight text-amber-600 dark:text-amber-400" x-text="streak">{{ $streak }}</span>
            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 hidden sm:inline">{{ __('ngày') }}</span>
        </div>

        <div class="w-px h-3.5 bg-[#e8e2d9] dark:bg-[#2d2926]"></div>

        <div class="flex items-center gap-1.5">
            <span class="text-[11px] font-bold text-[#e07a5f] dark:text-[#f4978e] px-1.5 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf]/50 dark:border-[#e07a5f]/20 leading-none" x-text="levelBadge">{{ $levelBadge }}</span>
            
            <div class="w-10 sm:w-12 h-2 bg-slate-100 dark:bg-white/10 rounded-full overflow-hidden p-[1px] hidden xs:block">
                <div class="h-full bg-gradient-to-r from-[#e07a5f] to-[#f4978e] rounded-full transition-all duration-500 ease-out"
                    :style="`width: ${levelPercent}%`"
                    style="width: {{ $levelPercent }}%;"></div>
            </div>
        </div>
    </button>

    <div x-show="tooltipOpen" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
        class="absolute right-0 mt-2 w-80 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xl p-4 z-50 space-y-3 text-left"
        style="display: none;">

        <template x-if="isLoggedIn">
            <div class="space-y-4">
                <div class="flex items-start justify-between border-b border-[#e8e2d9] dark:border-white/10 pb-3.5">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl mt-0.5">🔥</span>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-tight mb-1">
                                {{ __('Chuỗi học tập') }}
                            </h4>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-snug block">
                                {{ __('Học mỗi ngày để giữ chuỗi liên tục') }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f4978e] border border-[#fcdccf]/60 dark:border-[#e07a5f]/20 shrink-0 whitespace-nowrap"
                        x-text="levelBadge"></span>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-end">
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Tiến trình cấp độ') }}</span>
                            <template x-if="isMaxLevel">
                                <span class="text-[10px] font-bold text-amber-500 bg-amber-100 dark:bg-amber-900/30 px-1.5 py-0.5 rounded leading-none">MAX</span>
                            </template>
                        </div>
                        <span class="text-slate-900 dark:text-white font-bold text-xs">
                            <template x-if="!isMaxLevel">
                                <span>
                                    <span class="text-[#e07a5f]" x-text="levelInfo.expInLevel"></span>/<span x-text="levelInfo.expNeeded"></span>
                                    <span class="text-[10px] text-slate-400 font-bold ml-0.5">EXP</span>
                                </span>
                            </template>
                            <template x-if="isMaxLevel">
                                <span class="text-[#e07a5f] font-bold text-xs">{{ __('Cấp độ tối đa') }}</span>
                            </template>
                        </span>
                    </div>

                    <div class="w-full h-2.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden p-[2px]">
                        <div class="h-full bg-gradient-to-r from-[#e07a5f] to-[#f4978e] rounded-full transition-all duration-500 ease-out"
                            :style="`width: ${levelPercent}%`"></div>
                    </div>

                    <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                        <span x-text="levelBadge"></span>
                        <span x-text="isMaxLevel ? 'Lv.30 (MAX)' : `Lv.${level + 1}`"></span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-1">
                    <div class="py-2.5 px-2 rounded-[20px] bg-[#fcfaf7] dark:bg-white/5 border border-transparent dark:border-white/5 text-center flex flex-col justify-center items-center h-full hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                        <div class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase mb-1">
                            {{ __('Hiện tại') }}</div>
                        <div class="text-[11px] font-bold text-[#e07a5f] whitespace-nowrap">🔥 <span
                                x-text="streak"></span> {{ __('ngày') }}</div>
                    </div>
                    <div class="py-2.5 px-2 rounded-[20px] bg-[#fcfaf7] dark:bg-white/5 border border-transparent dark:border-white/5 text-center flex flex-col justify-center items-center h-full hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                        <div class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase mb-1">
                            {{ __('Kỷ lục') }}</div>
                        <div class="text-[11px] font-bold text-amber-500 whitespace-nowrap">🏆 <span
                                x-text="longestStreak"></span> {{ __('ngày') }}</div>
                    </div>
                    <div class="py-2.5 px-2 rounded-[20px] bg-[#fcfaf7] dark:bg-white/5 border border-transparent dark:border-white/5 text-center flex flex-col justify-center items-center h-full hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                        <div class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase mb-1">
                            {{ __('Tổng EXP') }}</div>
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200 whitespace-nowrap">
                            ⚡ <span x-text="expTotal"></span></div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="!isLoggedIn">
            <div class="space-y-3 text-center py-1">
                <div class="w-12 h-12 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center mx-auto text-xl shadow-xs">
                    🔥
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                        {{ __('Bắt đầu Chuỗi Học Tập!') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Đăng nhập ngay để ghi nhận chuỗi ngày học liên tục, tích lũy điểm EXP, nâng cấp bậc và đua Top Bảng Xếp Hạng.') }}
                    </p>
                </div>
                <button type="button"
                    @click="tooltipOpen = false; $dispatch('open-auth-modal', { tab: 'login' })"
                    class="w-full py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/20 transition-all btn-tactile cursor-pointer">
                    {{ __('Đăng nhập để giữ chuỗi') }}
                </button>
            </div>
        </template>
    </div>
</div>
