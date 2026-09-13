<header
    class="h-20 bg-white dark:bg-[#141211] border-b border-[#e8e2d9] dark:border-[#262220] flex items-center justify-between px-6 lg:px-8 shrink-0 transition-colors">
    <div class="flex items-center gap-3">
        @if (!View::hasSection('hide_sidebar'))
            <button type="button" @click="sidebarOpen = true"
                class="lg:hidden p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile cursor-pointer select-none">
                <i class="fa-solid fa-bars text-lg pointer-events-none select-none"></i>
            </button>
            <button type="button" @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden lg:flex p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile cursor-pointer select-none"
                title="{{ __('Thu gọn / Mở rộng Sidebar Navigation') }}">
                <i class="fa-solid text-base transition-transform duration-300 pointer-events-none select-none"
                    :class="sidebarCollapsed ? 'fa-indent rotate-180 text-[#e07a5f]' : 'fa-bars-staggered'"></i>
            </button>
        @else
            <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0">
                <img src="{{ asset('logo.png') }}" alt="XiaoMu Logo"
                    class="w-10 h-10 rounded-full object-cover shrink-0 group-hover:scale-105 transition-transform duration-200">
                <div class="flex flex-col min-w-0">
                    <span
                        class="font-bold text-lg tracking-tight text-slate-900 dark:text-white leading-none">XiaoMu</span>
                    <span
                        class="text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">
                        {{ __('Tiếng Trung') }}
                    </span>
                </div>
            </a>
        @endif
        @hasSection('header-left')
            @yield('header-left')
        @endif
    </div>

    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
        @hasSection('header-right')
            @yield('header-right')
        @else
            <div class="relative">
                <button @click="langOpen = !langOpen" @click.outside="langOpen = false"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-slate-300 transition-all btn-tactile">
                    <template x-if="currentLang === 'Việt Nam'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0"
                            viewBox="0 0 30 20">
                            <rect width="30" height="20" fill="#da251d" />
                            <polygon
                                points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5"
                                fill="#ffff00" />
                        </svg>
                    </template>
                    <span class="hidden md:inline font-bold" x-text="currentLang">Việt Nam</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                </button>
                <div x-show="langOpen"
                    class="absolute right-0 mt-2 w-max min-w-[140px] rounded-2xl bg-white dark:bg-[#1c1917] border border-[#e8e2d9] dark:border-[#2a2624] shadow-xl py-1.5 z-50 text-xs"
                    style="display: none;">
                    <button @click="currentLang = 'Việt Nam'; langOpen = false"
                        class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0"
                            viewBox="0 0 30 20">
                            <rect width="30" height="20" fill="#da251d" />
                            <polygon
                                points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5"
                                fill="#ffff00" />
                        </svg>
                        <span class="whitespace-nowrap">Việt Nam</span>
                    </button>
                </div>
            </div>
        @endif

        <div x-data="headerStreakWidget({
            isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
            streak: {{ auth()->check() ? auth()->user()->current_streak ?? 0 : 0 }},
            longestStreak: {{ auth()->check() ? auth()->user()->longest_streak ?? 0 : 0 }},
            todayExp: {{ auth()->check() ? auth()->user()->today_exp ?? 0 : 0 }},
            expTotal: {{ auth()->check() ? auth()->user()->exp_total ?? 0 : 0 }},
            maxLevel: {{ config('gamification.levels.max_level', 30) }},
            thresholds: @js(config('gamification.levels.thresholds'))
        })" @exp-updated.window="onExpUpdated($event.detail)" class="relative">
            <button type="button" @click="tooltipOpen = !tooltipOpen" @click.outside="tooltipOpen = false"
                class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl border bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:border-slate-300 transition-all btn-tactile cursor-pointer select-none">
                
                <div class="flex items-center gap-1">
                    <span class="text-sm leading-none">🔥</span>
                    <span class="text-xs font-bold font-mono tracking-tight text-amber-600 dark:text-amber-400" x-text="streak"></span>
                    <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 hidden sm:inline">{{ __('ngày') }}</span>
                </div>

                <div class="w-px h-3.5 bg-[#e8e2d9] dark:bg-[#2d2926]"></div>

                <div class="flex items-center gap-1.5">
                    <span class="text-[11px] font-bold text-[#e07a5f] dark:text-[#f4978e] px-1.5 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf]/50 dark:border-[#e07a5f]/20 leading-none" x-text="levelBadge"></span>
                    
                    <div class="w-10 sm:w-12 h-2 bg-slate-100 dark:bg-white/10 rounded-full overflow-hidden p-[1px] hidden xs:block">
                        <div class="h-full bg-gradient-to-r from-[#e07a5f] to-[#f4978e] rounded-full transition-all duration-500 ease-out"
                            :style="`width: ${levelPercent}%`"></div>
                    </div>
                </div>
            </button>

            <div x-show="tooltipOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                class="absolute right-0 mt-2 w-80 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xl p-4 z-50 space-y-3"
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
                            @click="tooltipOpen = false; authModalOpen = true; authModalTab = 'login'"
                            class="w-full py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/20 transition-all btn-tactile cursor-pointer">
                            {{ __('Đăng nhập để giữ chuỗi') }}
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Dark Mode Switch -->
        <button @click="darkMode = !darkMode"
            class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile cursor-pointer">
            <i class="fa-solid pointer-events-none"
                :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
        </button>

        @if (View::hasSection('hide_sidebar') && !View::hasSection('hide_auth'))
            @auth
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2 p-1 pl-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 hover:border-[#e07a5f] bg-white dark:bg-slate-800 transition-all btn-tactile">
                    <span
                        class="text-xs font-bold text-slate-800 dark:text-white hidden sm:inline">{{ auth()->user()->first_name }}</span>
                    <img src="{{ auth()->user()->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80' }}"
                        alt="Avatar" class="w-7 h-7 rounded-full object-cover">
                </a>
            @else
                <button @click="authModalOpen = true; authModalTab = 'login'"
                    class="px-4 py-2 bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold rounded-xl shadow-xs transition-all btn-tactile cursor-pointer">
                    {{ __('Đăng nhập') }}
                </button>
            @endauth
        @endif
    </div>
</header>
