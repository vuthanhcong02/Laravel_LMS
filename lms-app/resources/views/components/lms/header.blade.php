<header class="h-20 bg-white dark:bg-[#141211] border-b border-[#e8e2d9] dark:border-[#262220] flex items-center justify-between px-6 lg:px-8 shrink-0 transition-colors">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = true" class="lg:hidden p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile" title="{{ __('Thu gọn / Mở rộng Sidebar Navigation') }}">
            <i class="fa-solid text-base transition-transform duration-300" :class="sidebarCollapsed ? 'fa-indent rotate-180 text-[#e07a5f]' : 'fa-bars-staggered'"></i>
        </button>

        @hasSection('header-left')
            @yield('header-left')
        @else
            {{-- <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" x-model="searchKeyword" placeholder="Tìm kiếm khóa học HSK..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
            </div> --}}
        @endif
    </div>

    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
        @hasSection('header-right')
            @yield('header-right')
        @else
            <!-- Dynamic Language Selector Dropdown -->
            <div class="relative">
                <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-slate-300 transition-all btn-tactile">
                    <template x-if="currentLang === 'Việt Nam'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#da251d"/><polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/></svg>
                    </template>
                    <template x-if="currentLang === '中文'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#ee1c25"/><polygon points="5,3 5.6,4.8 7.4,4.8 5.9,5.9 6.5,7.7 5,6.6 3.5,7.7 4.1,5.9 2.6,4.8 4.4,4.8" fill="#ffde00"/></svg>
                    </template>
                    <template x-if="currentLang === 'English'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30"><clipPath id="s_uk_c14"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="t_uk_c14"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath><g clip-path="url(#s_uk_c14)"><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_c14)"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/></g></svg>
                    </template>
                    <span class="hidden md:inline font-bold" x-text="currentLang">Việt Nam</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                </button>
                <div x-show="langOpen" class="absolute right-0 mt-2 w-max min-w-[140px] rounded-2xl bg-white dark:bg-[#1c1917] border border-[#e8e2d9] dark:border-[#2a2624] shadow-xl py-1.5 z-50 text-xs" style="display: none;">
                    <button @click="currentLang = 'Việt Nam'; langOpen = false" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#da251d"/><polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/></svg>
                        <span class="whitespace-nowrap">Việt Nam</span>
                    </button>
                    <button @click="currentLang = '中文'; langOpen = false" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200 zh-text">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#ee1c25"/><polygon points="5,3 5.6,4.8 7.4,4.8 5.9,5.9 6.5,7.7 5,6.6 3.5,7.7 4.1,5.9 2.6,4.8 4.4,4.8" fill="#ffde00"/></svg>
                        <span class="whitespace-nowrap">中文 (简体)</span>
                    </button>
                    <button @click="currentLang = 'English'; langOpen = false" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30"><clipPath id="s_uk_c14"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="t_uk_c14"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath><g clip-path="url(#s_uk_c14)"><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_c14)"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/></g></svg>
                        <span class="whitespace-nowrap">English</span>
                    </button>
                </div>
            </div>

            <!-- Streak Badge -->
            <div class="hidden sm:flex items-center gap-1.5 bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] px-3 py-1.5 rounded-xl shadow-xs">
                <i class="fa-solid fa-fire text-[#e07a5f] text-sm animate-pulse"></i>
                <span class="text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">14 Ngày học</span>
            </div>
        @endif

        <!-- Dark Mode Switch -->
        <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile cursor-pointer">
            <i class="fa-solid pointer-events-none" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
        </button>
    </div>
</header>
