<header class="h-20 bg-white dark:bg-[#141211] border-b border-[#e8e2d9] dark:border-[#262220] flex items-center justify-between px-6 lg:px-8 shrink-0 transition-colors">
    <div class="flex items-center gap-3">
        @if (!View::hasSection('hide_sidebar'))
            <button type="button" @click="sidebarOpen = true" class="lg:hidden p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile cursor-pointer select-none">
                <i class="fa-solid fa-bars text-lg pointer-events-none select-none"></i>
            </button>
            <button type="button" @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile cursor-pointer select-none" title="{{ __('Thu gọn / Mở rộng Sidebar Navigation') }}">
                <i class="fa-solid text-base transition-transform duration-300 pointer-events-none select-none" :class="sidebarCollapsed ? 'fa-indent rotate-180 text-[#e07a5f]' : 'fa-bars-staggered'"></i>
            </button>
        @else
            <!-- Logo thương hiệu khi không có sidebar -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0">
                <img src="{{ asset('logo.png') }}" alt="XiaoMu Logo" class="w-10 h-10 rounded-full object-cover shrink-0 group-hover:scale-105 transition-transform duration-200">
                <div class="flex flex-col min-w-0">
                    <span class="font-bold text-lg tracking-tight text-slate-900 dark:text-white leading-none">XiaoMu</span>
                    <span class="text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">
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
            <!-- Dynamic Language Selector Dropdown -->
            <div class="relative">
                <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-slate-300 transition-all btn-tactile">
                    <template x-if="currentLang === 'Việt Nam'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#da251d"/><polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/></svg>
                    </template>
                    <span class="hidden md:inline font-bold" x-text="currentLang">Việt Nam</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                </button>
                <div x-show="langOpen" class="absolute right-0 mt-2 w-max min-w-[140px] rounded-2xl bg-white dark:bg-[#1c1917] border border-[#e8e2d9] dark:border-[#2a2624] shadow-xl py-1.5 z-50 text-xs" style="display: none;">
                    <button @click="currentLang = 'Việt Nam'; langOpen = false" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#da251d"/><polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/></svg>
                        <span class="whitespace-nowrap">Việt Nam</span>
                    </button>
                </div>
            </div>
        @endif
        <!-- Dark Mode Switch -->
        <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile cursor-pointer">
            <i class="fa-solid pointer-events-none" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
        </button>

        @if (View::hasSection('hide_sidebar') && !View::hasSection('hide_auth'))
            <!-- Nút Auth / Profile khi ẩn sidebar -->
            @auth
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 p-1 pl-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 hover:border-[#e07a5f] bg-white dark:bg-slate-800 transition-all btn-tactile">
                    <span class="text-xs font-bold text-slate-800 dark:text-white hidden sm:inline">{{ auth()->user()->first_name }}</span>
                    <img src="{{ auth()->user()->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80' }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover">
                </a>
            @else
                <button @click="authModalOpen = true; authModalTab = 'login'" class="px-4 py-2 bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold rounded-xl shadow-xs transition-all btn-tactile cursor-pointer">
                    {{ __('Đăng nhập') }}
                </button>
            @endauth
        @endif
    </div>
</header>
