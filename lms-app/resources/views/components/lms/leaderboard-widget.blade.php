@props([
    'type' => 'gamification', // 'gamification' | 'hsk'
    'initialLeaderboard' => [],
    'title' => null,
    'subtitle' => null,
    'badge' => null,
    'showLevelFilter' => false,
    'showFullModalButton' => false,
])

@php
    $isHsk = ($type === 'hsk');
    $defaultTitle = __('Bảng Xếp Hạng');
    $defaultSubtitle = $isHsk ? __('Top 8 Học viên xuất sắc nhất') : __('Bục vinh danh học viên xuất sắc nhất');
    $defaultBadge = $isHsk ? null : __('Top Vinh Danh');
    
    $resolvedTitle = $title ?? $defaultTitle;
    $resolvedSubtitle = $subtitle ?? $defaultSubtitle;
    $resolvedBadge = $badge ?? $defaultBadge;
@endphp

<div id="leaderboard-section" 
     class="space-y-4" 
     @if(!$isHsk) x-data="gamificationLeaderboard({ items: @js($initialLeaderboard), timeframe: 'all_time' })" @endif>
    <div class="lms-card p-3.5 space-y-3 sticky top-6 border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">

        <!-- Header -->
        <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-amber-500 to-amber-400 text-white flex items-center justify-center shadow-xs text-xs shrink-0">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $resolvedTitle }}
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">
                        {{ $resolvedSubtitle }}
                    </p>
                </div>
            </div>

            @if($isHsk && $showLevelFilter)
                <!-- HSK Level Dropdown -->
                <div class="shrink-0">
                    <select x-model="leaderboardLevel" 
                            class="text-[10px] sm:text-[11px] bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-lg px-2 py-1 font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#e07a5f] cursor-pointer">
                        <option value="all">{{ __('Tất cả cấp') }}</option>
                        <option value="hsk1">HSK 1</option>
                        <option value="hsk2">HSK 2</option>
                        <option value="hsk3">HSK 3</option>
                        <option value="hsk4">HSK 4</option>
                        <option value="hsk5">HSK 5</option>
                        <option value="hsk6">HSK 6</option>
                    </select>
                </div>
            @elseif($resolvedBadge)
                <span class="text-[9px] font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded-full border border-[#fcdccf]/60 dark:border-[#e07a5f]/20 shrink-0">
                    {{ $resolvedBadge }}
                </span>
            @endif
        </div>

        <!-- Timeframe Filter Tabs -->
        <div class="grid grid-cols-3 p-1 rounded-xl bg-[#f0ebe3] dark:bg-[#201c1a] border border-[#e8e2d9] dark:border-[#2d2926] gap-1">
            @if($isHsk)
                <button type="button" @click="leaderboardFilter = 'all_time'"
                    :class="leaderboardFilter === 'all_time'
                        ? 'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs border border-[#e8e2d9] dark:border-[#3e3834]'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-transparent font-medium'"
                    class="py-1 px-1 rounded-lg text-[10px] sm:text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                    {{ __('Toàn thời gian') }}
                </button>
                <button type="button" @click="leaderboardFilter = 'month'"
                    :class="leaderboardFilter === 'month'
                        ? 'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs border border-[#e8e2d9] dark:border-[#3e3834]'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-transparent font-medium'"
                    class="py-1 px-1 rounded-lg text-[10px] sm:text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                    {{ __('Tháng này') }}
                </button>
                <button type="button" @click="leaderboardFilter = 'week'"
                    :class="leaderboardFilter === 'week'
                        ? 'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs border border-[#e8e2d9] dark:border-[#3e3834]'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-transparent font-medium'"
                    class="py-1 px-1 rounded-lg text-[10px] sm:text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                    {{ __('Tuần này') }}
                </button>
            @else
                <button type="button" @click="changeTimeframe('all_time')"
                    :class="timeframe === 'all_time'
                        ? 'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs border border-[#e8e2d9] dark:border-[#3e3834]'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-transparent font-medium'"
                    class="py-1 px-1 rounded-lg text-[10px] sm:text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                    {{ __('Tất cả') }}
                </button>
                <button type="button" @click="changeTimeframe('month')"
                    :class="timeframe === 'month'
                        ? 'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs border border-[#e8e2d9] dark:border-[#3e3834]'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-transparent font-medium'"
                    class="py-1 px-1 rounded-lg text-[10px] sm:text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                    {{ __('Tháng này') }}
                </button>
                <button type="button" @click="changeTimeframe('week')"
                    :class="timeframe === 'week'
                        ? 'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs border border-[#e8e2d9] dark:border-[#3e3834]'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-transparent font-medium'"
                    class="py-1 px-1 rounded-lg text-[10px] sm:text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                    {{ __('Tuần này') }}
                </button>
            @endif
        </div>

        <!-- Main Content Area -->
        <div class="relative min-h-[160px]">
            <!-- Loading Overlay -->
            <div x-show="{{ $isHsk ? 'loadingLeaderboard' : 'loading' }}"
                class="absolute inset-0 bg-white/80 dark:bg-[#181615]/80 backdrop-blur-[1px] flex items-center justify-center z-20 rounded-xl"
                x-transition>
                <i class="fa-solid fa-spinner animate-spin text-[#e07a5f] text-sm"></i>
            </div>

            <!-- Leaderboard Active Content -->
            <div x-show="hasTop3" class="space-y-2.5">
                <!-- COMPACT TOP 3 PODIUM CONTAINER -->
                <div class="rounded-xl p-2 bg-[#faf7f2]/80 dark:bg-[#1c1917]/70 border border-[#e8e2d9]/70 dark:border-[#2d2926] shadow-2xs">
                    <div class="grid grid-cols-3 items-end gap-1.5 pt-2">

                        <!-- RANK #2 (LEFT - SILVER PODIUM) -->
                        <div class="flex flex-col items-center text-center">
                            <template x-if="top2">
                                <div class="flex flex-col items-center w-full min-w-0">
                                    <div class="relative flex flex-col items-center mb-0.5">
                                        <img :src="top2.avatar" :alt="top2.name"
                                            referrerpolicy="no-referrer"
                                            onerror="const fb='https://ui-avatars.com/api/?name=' + encodeURIComponent(this.alt || 'User') + '&color=FFFFFF&background=e07a5f'; if (this.src !== fb) { this.src = fb; }"
                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover ring-1.5 ring-slate-300 dark:ring-slate-500 shadow-xs bg-slate-100 dark:bg-slate-800" />
                                        <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-gradient-to-tr from-slate-300 to-slate-100 dark:from-slate-500 dark:to-slate-300 text-slate-800 dark:text-slate-900 font-bold text-[8px] shadow-2xs ring-1.5 ring-white dark:ring-[#181615] -mt-1.5 z-10">
                                            2
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-800 dark:text-slate-100 truncate w-full px-0.5 leading-tight"
                                        x-text="top2.name"></span>
                                    @if($isHsk)
                                        <div class="flex items-center justify-center gap-1 mt-0.5">
                                            <span class="text-[8px] font-bold px-1 rounded border leading-tight" :class="top2.badgeBg" x-text="top2.level"></span>
                                        </div>
                                        <div class="mt-0.5">
                                            <span class="text-[9px] font-bold text-slate-700 dark:text-slate-200 block leading-tight" x-text="top2.score"></span>
                                            <span class="text-[8px] font-semibold text-slate-400 dark:text-slate-500 inline-flex items-center justify-center gap-0.5 leading-tight">
                                                <i class="fa-solid fa-clock text-[#0284c7] text-[7px]"></i>
                                                <span x-text="top2.time"></span>
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 mt-0.2" x-text="top2.exp"></span>
                                    @endif
                                </div>
                            </template>
                            <template x-if="!top2">
                                <div class="flex flex-col items-center opacity-30 pb-0.5">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-400 text-[9px] font-bold">2</div>
                                    <span class="text-[9px] font-semibold text-slate-400 mt-0.5">-</span>
                                </div>
                            </template>

                            <!-- Silver Pedestal -->
                            <div class="w-9 sm:w-10 h-8 rounded-t-md bg-gradient-to-t from-slate-400 via-slate-300 to-slate-200 dark:from-slate-700 dark:via-slate-600 dark:to-slate-500 flex items-center justify-center mt-1.5 shadow-2xs mx-auto">
                                <span class="text-[10px] font-bold text-slate-700/60 dark:text-slate-200/50 select-none">
                                    #2
                                </span>
                            </div>
                        </div>

                        <!-- RANK #1 (CENTER - GOLD PODIUM) -->
                        <div class="flex flex-col items-center text-center">
                            <template x-if="top1">
                                <div class="flex flex-col items-center w-full min-w-0">
                                    <div class="mb-0.5 text-amber-500 animate-bounce leading-none">
                                        <i class="fa-solid fa-crown text-[11px] drop-shadow-[0_1px_3px_rgba(245,158,11,0.5)]"></i>
                                    </div>
                                    <div class="relative flex flex-col items-center mb-0.5">
                                        <img :src="top1.avatar" :alt="top1.name"
                                            referrerpolicy="no-referrer"
                                            onerror="const fb='https://ui-avatars.com/api/?name=' + encodeURIComponent(this.alt || 'User') + '&color=FFFFFF&background=e07a5f'; if (this.src !== fb) { this.src = fb; }"
                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover ring-2 ring-amber-400 shadow-xs shadow-amber-500/20 bg-amber-50 dark:bg-amber-950/40" />
                                        <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 text-amber-950 font-bold text-[9px] shadow-2xs ring-1.5 ring-white dark:ring-[#181615] -mt-1.5 z-10">
                                            1
                                        </span>
                                    </div>
                                    <span class="text-[10px] sm:text-[11px] font-bold text-slate-900 dark:text-white truncate w-full px-0.5 leading-tight"
                                        x-text="top1.name"></span>
                                    @if($isHsk)
                                        <div class="flex items-center justify-center gap-1 mt-0.5">
                                            <span class="text-[8px] font-bold px-1 rounded border leading-tight" :class="top1.badgeBg" x-text="top1.level"></span>
                                        </div>
                                        <div class="mt-0.5">
                                            <span class="text-[9px] sm:text-[10px] font-bold text-[#e07a5f] dark:text-[#f4978e] block leading-tight" x-text="top1.score"></span>
                                            <span class="text-[8px] font-semibold text-slate-400 dark:text-slate-500 inline-flex items-center justify-center gap-0.5 leading-tight">
                                                <i class="fa-solid fa-clock text-[#0284c7] text-[7px]"></i>
                                                <span x-text="top1.time"></span>
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[9px] sm:text-[10px] font-bold text-[#e07a5f] dark:text-[#f4978e] mt-0.2" x-text="top1.exp"></span>
                                    @endif
                                </div>
                            </template>
                            <template x-if="!top1">
                                <div class="flex flex-col items-center opacity-30 pb-0.5">
                                    <div class="w-8 h-8 rounded-full bg-amber-200 dark:bg-amber-800 flex items-center justify-center text-amber-500 text-xs font-bold">1</div>
                                    <span class="text-[9px] font-semibold text-slate-400 mt-0.5">-</span>
                                </div>
                            </template>

                            <!-- Gold Pedestal -->
                            <div class="w-10 sm:w-11 h-12 rounded-t-md bg-gradient-to-t from-amber-600 via-amber-400 to-amber-300 text-amber-950 flex items-center justify-center mt-1.5 shadow-xs shadow-amber-500/20 border-t border-amber-100 mx-auto">
                                <span class="text-[11px] font-bold text-amber-950/60 select-none">
                                    #1
                                </span>
                            </div>
                        </div>

                        <!-- RANK #3 (RIGHT - BRONZE PODIUM) -->
                        <div class="flex flex-col items-center text-center">
                            <template x-if="top3">
                                <div class="flex flex-col items-center w-full min-w-0">
                                    <div class="relative flex flex-col items-center mb-0.5">
                                        <img :src="top3.avatar" :alt="top3.name"
                                            referrerpolicy="no-referrer"
                                            onerror="const fb='https://ui-avatars.com/api/?name=' + encodeURIComponent(this.alt || 'User') + '&color=FFFFFF&background=e07a5f'; if (this.src !== fb) { this.src = fb; }"
                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover ring-1.5 ring-amber-700/60 dark:ring-amber-600/60 shadow-xs bg-orange-50 dark:bg-orange-950/20" />
                                        <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-gradient-to-tr from-amber-700 to-amber-500 text-white font-bold text-[8px] shadow-2xs ring-1.5 ring-white dark:ring-[#181615] -mt-1.5 z-10">
                                            3
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-800 dark:text-slate-100 truncate w-full px-0.5 leading-tight"
                                        x-text="top3.name"></span>
                                    @if($isHsk)
                                        <div class="flex items-center justify-center gap-1 mt-0.5">
                                            <span class="text-[8px] font-bold px-1 rounded border leading-tight" :class="top3.badgeBg" x-text="top3.level"></span>
                                        </div>
                                        <div class="mt-0.5">
                                            <span class="text-[9px] font-bold text-slate-700 dark:text-slate-200 block leading-tight" x-text="top3.score"></span>
                                            <span class="text-[8px] font-semibold text-slate-400 dark:text-slate-500 inline-flex items-center justify-center gap-0.5 leading-tight">
                                                <i class="fa-solid fa-clock text-[#0284c7] text-[7px]"></i>
                                                <span x-text="top3.time"></span>
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 mt-0.2" x-text="top3.exp"></span>
                                    @endif
                                </div>
                            </template>
                            <template x-if="!top3">
                                <div class="flex flex-col items-center opacity-30 pb-0.5">
                                    <div class="w-6 h-6 rounded-full bg-orange-200 dark:bg-orange-800 flex items-center justify-center text-orange-500 text-[9px] font-bold">3</div>
                                    <span class="text-[9px] font-semibold text-slate-400 mt-0.5">-</span>
                                </div>
                            </template>

                            <!-- Bronze Pedestal -->
                            <div class="w-9 sm:w-10 h-6 rounded-t-md bg-gradient-to-t from-amber-700 via-orange-500 to-orange-400 text-amber-950 flex items-center justify-center mt-1.5 shadow-2xs mx-auto">
                                <span class="text-[9px] font-bold text-amber-950/60 select-none">
                                    #3
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOP 4+ LIST -->
                <div x-show="restItems.length > 0" class="space-y-1.5 pt-0.5">
                    <div class="flex items-center justify-between px-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <span>{{ __('Thứ hạng tiếp theo') }}</span>
                        <span>{{ $isHsk ? __('Điểm & Thời gian') : __('EXP') }}</span>
                    </div>

                    <div class="space-y-1.5 max-h-[180px] overflow-y-auto pr-0.5 no-scrollbar">
                        <template x-for="item in restItems" :key="item.user_id || item.rank">
                            <div class="flex items-center justify-between py-1.5 px-2.5 rounded-xl transition-all duration-150 border bg-[#fcfaf7] dark:bg-[#1e1b19]/60 border-[#e8e2d9]/60 dark:border-[#2d2926] hover:bg-white dark:hover:bg-[#23201e] hover:border-[#e8e2d9] dark:hover:border-[#3a3531]">
                                <div class="flex items-center gap-2 min-w-0 flex-1 pr-2">
                                    <span class="w-4 text-slate-400 dark:text-slate-400 font-bold text-[11px] shrink-0 text-center"
                                        x-text="'#' + item.rank"></span>
                                    <img :src="item.avatar" :alt="item.name"
                                        referrerpolicy="no-referrer"
                                        onerror="const fb='https://ui-avatars.com/api/?name=' + encodeURIComponent(this.alt || 'User') + '&color=FFFFFF&background=e07a5f'; if (this.src !== fb) { this.src = fb; }"
                                        class="w-7 h-7 rounded-full object-cover ring-1.5 ring-[#e8e2d9] dark:ring-[#332e2b] shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate leading-tight"
                                                x-text="item.name"></p>
                                            @if($isHsk)
                                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded border leading-none shrink-0"
                                                    :class="item.badgeBg"
                                                    x-text="item.level"></span>
                                            @else
                                                <span class="text-[9px] font-bold text-[#e07a5f] dark:text-[#f4978e] px-1.5 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf]/60 dark:border-[#e07a5f]/20 leading-none shrink-0"
                                                    x-text="item.badge || item.level_badge"></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    @if($isHsk)
                                        <div class="text-right">
                                            <div class="text-xs font-bold text-slate-900 dark:text-white leading-tight" x-text="item.score"></div>
                                            <div class="text-[10px] text-slate-400 flex items-center justify-end gap-1 mt-0.5">
                                                <i class="fa-solid fa-clock text-[#0284c7] text-[9px]"></i>
                                                <span x-text="item.time"></span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f28e75] font-bold text-[11px] border border-[#fcdccf]/60 dark:border-[#e07a5f]/20">
                                            <i class="fa-solid fa-bolt text-[10px] text-amber-500"></i>
                                            <span x-text="item.exp"></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- EMPTY STATE -->
            <div x-show="!hasTop3 && !({{ $isHsk ? 'loadingLeaderboard' : 'loading' }})" class="py-6 text-center space-y-1.5">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs mx-auto shadow-2xs">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">
                    {{ __('Chưa có dữ liệu bảng xếp hạng') }}
                </p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 max-w-[180px] mx-auto leading-relaxed">
                    {{ $isHsk ? __('Hãy hoàn thành bài thi thử hôm nay để ghi danh lên bục vinh danh!') : __('Hãy hoàn thành bài học hôm nay để ghi danh lên bục vinh danh!') }}
                </p>
            </div>
        </div>

        @if($isHsk && $showFullModalButton)
            <!-- Footer button for Top 20 Modal -->
            <div class="pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926] text-center">
                <button type="button" 
                        @click="openFullLeaderboard()" 
                        class="text-xs font-semibold text-[#e07a5f] hover:underline flex items-center justify-center gap-1.5 w-full py-1 cursor-pointer">
                    <span>{{ __('Xem toàn bộ bảng xếp hạng (Top 20)') }}</span>
                    <i class="fa-solid fa-angle-right text-[10px]"></i>
                </button>
            </div>
        @endif
    </div>
</div>
