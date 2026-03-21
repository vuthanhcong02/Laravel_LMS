<header
    class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-primary/20 bg-white dark:bg-slate-900 px-6 py-3 lg:px-10">
    <div class="flex items-center gap-8">
        <div class="flex items-center gap-3 text-primary">
            <button @click="sidebarOpen = !sidebarOpen"
                class="md:hidden flex items-center justify-center p-2 -ml-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined">school</span>
            </div>
            <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">XiaoMu Chinese LMS
            </h2>
        </div>
        <label class="hidden md:flex flex-col min-w-40 !h-10 max-w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full overflow-hidden">
                <div
                    class="text-slate-500 flex border-none bg-slate-100 dark:bg-slate-800 items-center justify-center pl-4">
                    <span class="material-symbols-outlined text-sm">search</span>
                </div>
                <input
                    class="form-input flex w-full min-w-0 flex-1 border-none bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-0 focus:ring-0 h-full placeholder:text-slate-500 px-4 text-sm font-normal"
                    placeholder="Search data..." />
            </div>
        </label>
    </div>
    <div class="flex flex-1 justify-end gap-6 items-center">
        <nav class="hidden lg:flex items-center gap-8">
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('home') }}">Home</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="#">Settings</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="#">Support</a>
        </nav>
        <div class="flex gap-3">
            <button
                class="flex items-center justify-center rounded-lg size-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary/20 transition-colors">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            {{-- User Info Dropdown --}}
            <div class="relative" x-data="{ userMenuOpen: false }">
                <button @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center gap-3 rounded-xl px-2 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    {{-- Avatar --}}
                    @php
                        $avatar = Auth::user()->avatar;
                        $avatarUrl = $avatar
                            ? (str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar))
                            : null;
                    @endphp
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}"
                            class="size-9 rounded-full border-2 border-primary object-cover"
                            alt="{{ Auth::user()->first_name }}">
                    @else
                        <div class="size-9 rounded-full border-2 border-primary bg-primary flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                    @endif
                    {{-- Name & Role --}}
                    <div class="hidden lg:flex flex-col items-start leading-tight">
                        <span class="text-sm font-semibold text-slate-800 dark:text-white">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </span>
                        <span class="text-xs text-primary font-medium">
                            @php
                                $roleLabels = \App\Models\User::getAllRole() + [\App\Models\User::ROLE_ADMIN => 'Admin'];
                            @endphp
                            {{ $roleLabels[Auth::user()->role] ?? 'Unknown' }}
                        </span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 text-base hidden lg:block" x-text="userMenuOpen ? 'expand_less' : 'expand_more'"></span>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="userMenuOpen"
                    @click.outside="userMenuOpen = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg z-50 overflow-hidden"
                    style="display: none;">
                    {{-- User summary at top --}}
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    {{-- Menu items --}}
                    <div class="py-1">
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-base">manage_accounts</span> Profile
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-base">settings</span> Settings
                        </a>
                    </div>
                    {{-- Logout --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 py-1">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
