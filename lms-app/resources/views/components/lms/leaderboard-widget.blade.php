@props(['initialLeaderboard' => []])

<div id="leaderboard-section" class="space-y-4" x-data="gamificationLeaderboard({ items: @js($initialLeaderboard), timeframe: 'all_time' })">
    <div class="lms-card p-4 space-y-3 sticky top-6 border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">

        <!-- Header -->
        <div class="flex items-center justify-between pb-2.5 border-b border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center gap-2.5 min-w-0">
                <div
                    class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-400 text-white flex items-center justify-center shadow-xs text-sm shrink-0">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ __('Bảng Xếp Hạng') }}
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                        {{ __('Top 5 học viên tích cực theo Chuỗi & EXP') }}
                    </p>
                </div>
            </div>
            <span
                class="text-[10px] font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded-full border border-[#fcdccf]/60 dark:border-[#e07a5f]/20 shrink-0">
                Top 5
            </span>
        </div>

        <div
            class="grid grid-cols-3 p-1 rounded-xl bg-[#f0ebe3] dark:bg-[#201c1a] border border-[#e8e2d9]/60 dark:border-[#2d2926] gap-1">
            <button type="button" @click="changeTimeframe('all_time')"
                :class="timeframe === 'all_time'
                    ?
                    'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium'"
                class="py-1 px-1 rounded-lg text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                {{ __('Tất cả') }}
            </button>
            <button type="button" @click="changeTimeframe('month')"
                :class="timeframe === 'month'
                    ?
                    'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium'"
                class="py-1 px-1 rounded-lg text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                {{ __('Tháng này') }}
            </button>
            <button type="button" @click="changeTimeframe('week')"
                :class="timeframe === 'week'
                    ?
                    'bg-white dark:bg-[#2d2926] text-[#e07a5f] dark:text-[#f28e75] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium'"
                class="py-1 px-1 rounded-lg text-[11px] whitespace-nowrap transition-all duration-150 text-center cursor-pointer select-none">
                {{ __('Tuần này') }}
            </button>
        </div>

        <div class="relative min-h-[140px]">
            <div x-show="loading"
                class="absolute inset-0 bg-white/80 dark:bg-[#181615]/80 backdrop-blur-[1px] flex items-center justify-center z-10 rounded-xl"
                x-transition>
                <i class="fa-solid fa-spinner animate-spin text-[#e07a5f] text-base"></i>
            </div>

            <div class="space-y-1.5 max-h-[360px] overflow-y-auto pr-0.5 no-scrollbar">
                <template x-for="item in leaderboard" :key="item.user_id || item.rank">
                    <div class="flex items-center justify-between py-1.5 px-2.5 rounded-xl transition-all duration-150 border"
                        :class="{
                            'bg-amber-500/10 dark:bg-amber-500/15 border-amber-500/30 dark:border-amber-500/30': item
                                .rank === 1,
                            'bg-slate-500/5 dark:bg-slate-400/5 border-slate-300/60 dark:border-slate-700/60': item
                                .rank === 2,
                            'bg-orange-500/5 dark:bg-orange-900/10 border-orange-300/50 dark:border-orange-900/40': item
                                .rank === 3,
                            'bg-[#fcfaf7] dark:bg-[#1e1b19]/40 border-transparent hover:bg-white dark:hover:bg-[#23201e] hover:border-[#e8e2d9] dark:hover:border-[#2d2926]': item
                                .rank > 3
                        }">

                        <div class="flex items-center gap-2 min-w-0 flex-1 pr-2">
                            <div class="w-5 h-5 flex items-center justify-center shrink-0">
                                <template x-if="item.rank === 1">
                                    <span
                                        class="w-5 h-5 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 text-slate-950 font-bold text-[10px] flex items-center justify-center shadow-xs">
                                        1
                                    </span>
                                </template>
                                <template x-if="item.rank === 2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-gradient-to-br from-slate-200 to-slate-400 dark:from-slate-500 dark:to-slate-600 text-slate-800 dark:text-white font-bold text-[10px] flex items-center justify-center shadow-xs">
                                        2
                                    </span>
                                </template>
                                <template x-if="item.rank === 3">
                                    <span
                                        class="w-5 h-5 rounded-full bg-gradient-to-br from-amber-600 to-amber-700 text-white font-bold text-[10px] flex items-center justify-center shadow-xs">
                                        3
                                    </span>
                                </template>
                                <template x-if="item.rank > 3">
                                    <span class="text-slate-400 dark:text-slate-500 font-semibold text-[11px]"
                                        x-text="item.rank"></span>
                                </template>
                            </div>

                            <img :src="item.avatar" :alt="item.name"
                                class="w-7 h-7 rounded-full object-cover ring-1 ring-[#e8e2d9] dark:ring-[#332e2b] shrink-0" />

                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate leading-tight"
                                    x-text="item.name"></p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <div
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f28e75] font-bold text-[11px] border border-[#fcdccf]/50 dark:border-[#e07a5f]/20">
                                <i class="fa-solid fa-bolt text-[10px] text-amber-500"></i>
                                <span x-text="item.exp"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="leaderboard.length === 0 && !loading" class="py-6 text-center space-y-1.5">
                    <div
                        class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-sm mx-auto shadow-xs">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200">
                        {{ __('Chưa có dữ liệu xếp hạng') }}
                    </p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 max-w-[180px] mx-auto leading-relaxed">
                        {{ __('Hãy hoàn thành bài học hôm nay để ghi danh lên bảng vàng!') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
