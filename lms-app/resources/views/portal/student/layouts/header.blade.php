<header
    class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-primary/20 bg-white dark:bg-slate-900 px-6 py-3 lg:px-10">
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
                href="{{ route('home') }}">Home</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('support.index') }}">Support</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 text-amber-600 dark:text-amber-400 text-xs font-bold shadow-xs" title="{{ __('Chuỗi ngày học liên tục & Cấp độ') }}">
                    <div class="flex items-center gap-1">
                        <span>🔥</span>
                        <span>{{ auth()->user()->current_streak ?? 0 }} {{ __('ngày') }}</span>
                    </div>
                    <span class="text-slate-300 dark:text-slate-600 font-normal">•</span>
                    <span class="px-1.5 py-0.5 rounded bg-amber-200/60 dark:bg-amber-900/60 text-[11px] font-bold text-amber-700 dark:text-amber-300">{{ auth()->user()->level_badge }}</span>
                </div>
            @endauth

            @if(Auth::check())
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center gap-3 rounded-xl px-2 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">

                        @php
                            $avatar = Auth::user()->avatar;
                            $avatarUrl = $avatar
                                ? (str_starts_with($avatar, 'http')
                                    ? $avatar
                                    : asset('storage/' . $avatar))
                                : null;
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
                                @php
                                    $roleLabels = \App\Models\User::getAllRole() + [
                                        \App\Models\User::ROLE_ADMIN => 'Admin',
                                    ];
                                @endphp
                                {{ $roleLabels[Auth::user()->role] ?? 'Học viên' }}
                            </span>
                        </div>
                        <span class="material-symbols-outlined text-slate-400 text-base hidden lg:block"
                            x-text="userMenuOpen ? 'expand_less' : 'expand_more'"></span>
                    </button>

                    <div x-show="userMenuOpen" @click.outside="userMenuOpen = false"
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
                                <span class="material-symbols-outlined text-base">manage_accounts</span> Profile
                            </a>
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-base">settings</span> Settings
                            </a>
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-700 py-1">
                            <form method="POST" action="{{ route('admin.logout') }}" @click.stop>
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                                    <span class="material-symbols-outlined text-base">logout</span> Log Out
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
