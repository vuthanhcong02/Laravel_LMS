<header
    class="sticky top-0 z-50 h-16 flex items-center justify-between whitespace-nowrap border-b border-primary/20 bg-white dark:bg-slate-900 px-6 py-3">
    <div class="flex items-center gap-8">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen"
                class="md:hidden flex items-center justify-center p-2 -ml-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="size-9 bg-primary/10 text-primary rounded-xl flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-xl">school</span>
            </div>
            <div class="flex flex-col">
                <span class="text-slate-900 dark:text-white text-base font-bold leading-tight tracking-tight">XIAOMU</span>
                <span class="text-primary text-[11px] font-semibold tracking-wide leading-none">Tiếng Trung LMS</span>
            </div>
        </div>
    </div>
    <div class="flex flex-1 justify-end gap-6 items-center">
        <nav class="hidden lg:flex items-center gap-8">
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('home') }}">{{ __('Trang chủ') }}</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('support.index') }}">{{ __('Hỗ trợ') }}</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                <x-lms.header-streak-widget />
            @endauth

            <div x-data="{
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                toggle() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    if (this.theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    
                    fetch('{{ route('settings.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ theme: this.theme })
                    });
                }
            }">
                <button @click="toggle()" class="size-9 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors" title="{{ __('Chuyển đổi giao diện sáng/tối') }}">
                    <span class="material-symbols-outlined text-[22px]" x-text="theme === 'dark' ? 'light_mode' : 'dark_mode'"></span>
                </button>
            </div>

            @if(Auth::check())
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center gap-3 rounded-xl px-2 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                        @php
                            $avatarUrl = Auth::user()->avatar_url;
                        @endphp
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" class="size-9 rounded-full border-2 border-primary object-cover"
                                alt="{{ Auth::user()->first_name }}">
                        @else
                            <div
                                class="size-9 rounded-full border-2 border-primary bg-primary flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? 'S', 0, 1)) }}
                            </div>
                        @endif

                        <div class="hidden lg:flex flex-col items-start leading-tight">
                            <span class="text-sm font-semibold text-slate-800 dark:text-white">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            </span>
                            <span class="text-xs text-primary font-medium">
                                {{ __('Học viên') }}
                            </span>
                        </div>
                        <span class="material-symbols-outlined text-slate-400 text-base hidden lg:block"
                            x-text="userMenuOpen ? 'expand_less' : 'expand_more'"></span>
                    </button>

                    <div x-show="userMenuOpen" @click.outside="userMenuOpen = false" x-cloak
                        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg z-50 overflow-hidden"
                        style="display: none;">

                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('student.profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-base">manage_accounts</span> {{ __('Hồ sơ cá nhân') }}
                            </a>
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-base">settings</span> {{ __('Cài đặt') }}
                            </a>
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-700 py-1">
                            <form method="POST" action="{{ route('logout') }}" @click.stop>
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <span class="material-symbols-outlined text-base">logout</span> {{ __('Đăng xuất') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl bg-primary text-white text-xs font-bold shadow-xs hover:bg-primary/90 transition-all">
                        {{ __('Đăng nhập') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</header>
