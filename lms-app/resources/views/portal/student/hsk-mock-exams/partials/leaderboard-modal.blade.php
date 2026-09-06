{{-- Top 20 HSK Mock Exam Leaderboard Modal --}}
<div x-show="fullLeaderboardOpen" x-cloak
     @keydown.escape.window="fullLeaderboardOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="leaderboard-modal-title" role="dialog" aria-modal="true">
    
    {{-- Backdrop overlay --}}
    <div x-show="fullLeaderboardOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="fullLeaderboardOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    {{-- Modal Box Container --}}
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div x-show="fullLeaderboardOpen"
             x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.outside="fullLeaderboardOpen = false"
             class="relative w-full max-w-2xl bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] text-left">
            
            {{-- Modal Header --}}
            <div class="p-4 sm:p-5 border-b border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between bg-[#fcfaf7] dark:bg-[#1d1a18]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-lg shrink-0 border border-amber-500/20 shadow-xs">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div>
                        <h3 id="leaderboard-modal-title" class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>{{ __('Bảng Xếp Hạng Đầy Đủ') }}</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#e07a5f]/10 text-[#e07a5f] border border-[#e07a5f]/20">TOP 20</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ __('Vinh danh những học viên có thành tích thi thử HSK xuất sắc nhất') }}
                        </p>
                    </div>
                </div>
                <button type="button" 
                        @click="fullLeaderboardOpen = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- HSK Level & Timeframe Filters --}}
            <div class="p-3 sm:p-4 border-b border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] space-y-3">
                <div class="flex items-center justify-between gap-2.5">
                    {{-- HSK Level Tabs --}}
                    <div class="flex items-center gap-1 overflow-x-auto no-scrollbar py-0.5 flex-1 min-w-0">
                        <button type="button" @click="fullLeaderboardLevel = 'all'"
                                :class="fullLeaderboardLevel === 'all' ? 'bg-[#e07a5f] text-white shadow-xs font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-400 border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]'"
                                class="px-2.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer shrink-0">
                            {{ __('Tất cả') }}
                        </button>
                        <template x-for="level in [1, 2, 3, 4, 5, 6]" :key="level">
                            <button type="button" @click="fullLeaderboardLevel = 'hsk' + level"
                                    :class="fullLeaderboardLevel === ('hsk' + level) ? 'bg-[#e07a5f] text-white shadow-xs font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-400 border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]'"
                                    class="px-2.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer shrink-0"
                                    x-text="'HSK ' + level">
                            </button>
                        </template>
                    </div>

                    {{-- Timeframe Dropdown --}}
                    <div class="shrink-0 relative">
                        <select x-model="fullLeaderboardFilter"
                                class="text-xs bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-3 pr-8 py-1.5 font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#e07a5f] cursor-pointer shadow-xs appearance-none transition-all hover:border-[#e07a5f]">
                            <option value="all_time">{{ __('Toàn thời gian') }}</option>
                            <option value="month">{{ __('Tháng này') }}</option>
                            <option value="week">{{ __('Tuần này') }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>

                {{-- Current User Rank Banner (if exists) --}}
                <div x-show="currentUserResult && currentUserRank" 
                     class="p-2.5 rounded-xl bg-gradient-to-r from-amber-500/10 via-[#e07a5f]/10 to-amber-500/5 border border-amber-500/20 flex items-center justify-between">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-amber-500 to-amber-400 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs">
                            <i class="fa-solid fa-star text-[10px]"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[11px] text-amber-900 dark:text-amber-200 font-semibold flex items-center gap-1.5">
                                <span>{{ __('Vị trí của bạn') }}</span>
                                <span class="font-bold text-[#e07a5f]" x-text="'#' + currentUserRank"></span>
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate" x-text="currentUserResult ? currentUserResult.name : ''"></div>
                        </div>
                    </div>
                    <div class="text-right shrink-0" x-show="currentUserResult">
                        <div class="text-xs font-bold text-[#e07a5f]" x-text="currentUserResult ? currentUserResult.score : ''"></div>
                        <div class="text-[10px] text-slate-400 flex items-center justify-end gap-1">
                            <i class="fa-solid fa-clock text-[#0284c7] text-[9px]"></i>
                            <span x-text="currentUserResult ? currentUserResult.time : ''"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top 20 Ranked Student List --}}
            <div class="relative flex-1 overflow-y-auto p-3 sm:p-4 space-y-2 min-h-[260px] max-h-[55vh]">
                {{-- Loading Spinner Overlay --}}
                <div x-show="loadingFullLeaderboard" 
                     class="absolute inset-0 bg-white/80 dark:bg-[#181615]/80 backdrop-blur-xs flex flex-col items-center justify-center z-10 space-y-2"
                     x-transition>
                    <i class="fa-solid fa-spinner animate-spin text-[#e07a5f] text-2xl"></i>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Đang tải bảng xếp hạng...') }}</span>
                </div>

                {{-- Leaderboard Items --}}
                <template x-for="item in fullLeaderboard" :key="item.rank">
                    <div class="flex items-center justify-between p-3 rounded-xl transition-all btn-tactile"
                         :class="{
                             'bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-transparent border border-amber-500/30 shadow-xs': item.rank === 1,
                             'bg-gradient-to-r from-slate-200/60 via-slate-100/40 to-transparent dark:from-slate-700/40 dark:via-slate-800/20 dark:to-transparent border border-slate-300 dark:border-slate-600': item.rank === 2,
                             'bg-gradient-to-r from-amber-700/15 via-amber-700/5 to-transparent border border-amber-700/30': item.rank === 3,
                             'bg-[#fcfaf7] dark:bg-[#1d1a18] border border-transparent hover:border-[#e8e2d9] dark:hover:border-[#2d2926]': item.rank > 3
                         }">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Rank Badges --}}
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black shrink-0"
                                 :class="{
                                     'bg-amber-500 text-white shadow-xs': item.rank === 1,
                                     'bg-slate-400 text-white': item.rank === 2,
                                     'bg-amber-700 text-white': item.rank === 3,
                                     'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700': item.rank > 3
                                 }">
                                <template x-if="item.rank === 1">
                                    <i class="fa-solid fa-crown text-[11px]"></i>
                                </template>
                                <template x-if="item.rank !== 1">
                                    <span x-text="'#' + item.rank"></span>
                                </template>
                            </div>

                            {{-- Student Avatar --}}
                            <img :src="item.avatar" 
                                 :alt="item.name"
                                 class="w-9 h-9 rounded-full object-cover border border-white dark:border-[#25211e] shrink-0 shadow-2xs" />

                            {{-- Student Info --}}
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100 truncate" x-text="item.name"></p>
                                    <template x-if="item.rank <= 3">
                                        <span class="text-[10px] font-bold text-amber-500">
                                            <i class="fa-solid fa-medal"></i>
                                        </span>
                                    </template>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded border" :class="item.badgeBg" x-text="item.level"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Total Score & Duration --}}
                        <div class="text-right shrink-0">
                            <div class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white" x-text="item.score"></div>
                            <div class="text-[10px] text-slate-400 flex items-center justify-end gap-1 mt-0.5">
                                <i class="fa-solid fa-clock text-[#0284c7] text-[9px]"></i>
                                <span x-text="item.time"></span>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="fullLeaderboard.length === 0 && !loadingFullLeaderboard" 
                     class="py-12 text-center text-slate-400 space-y-2">
                    <i class="fa-solid fa-award text-3xl text-slate-300 dark:text-slate-600 block"></i>
                    <p class="text-xs">{{ __('Chưa có dữ liệu bảng xếp hạng cho bộ lọc đã chọn.') }}</p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-3 sm:p-4 border-t border-[#e8e2d9] dark:border-[#2d2926] bg-[#fcfaf7] dark:bg-[#1d1a18] flex items-center justify-between">
                <div class="text-[11px] text-slate-500 dark:text-slate-400">
                    <span>{{ __('Hiển thị:') }}</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200" x-text="fullLeaderboard.length"></span>
                    <span>{{ __('học viên') }}</span>
                </div>
                <button type="button" 
                        @click="fullLeaderboardOpen = false"
                        class="px-4 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs btn-tactile cursor-pointer">
                    {{ __('Đóng') }}
                </button>
            </div>
        </div>
    </div>
</div>
