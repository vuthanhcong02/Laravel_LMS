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
    </div>
    <div class="flex flex-1 justify-end gap-6 items-center">
        <nav class="hidden lg:flex items-center gap-8">
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('home') }}">Home</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('admin.support.index') }}">Support</a>
        </nav>
        <div class="flex gap-3">
            {{-- Notifications Dropdown --}}
            <div class="relative" x-data="{ notifOpen: false }">
                <button @click="notifOpen = !notifOpen"
                    class="relative flex items-center justify-center rounded-lg size-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span
                        class="absolute top-2.5 right-2.5 size-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-800"></span>
                </button>

                {{-- Notification Popup --}}
                <div x-show="notifOpen" @click.outside="notifOpen = false"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute -right-16 md:right-0 mt-2 w-80 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg z-50 overflow-hidden flex flex-col"
                    style="display: none;">
                    <div
                        class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">Thông báo</p>
                        <a href="#" class="text-xs text-primary font-medium hover:underline">Đánh dấu đã đọc</a>
                    </div>
                    <div class="max-h-[320px] overflow-y-auto">
                        {{-- Dummy Notification Item 1 --}}
                        <a href="#"
                            class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800/50 relative">
                            <div class="size-2 bg-primary rounded-full absolute left-1.5 top-4"></div>
                            <div
                                class="size-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-xl">campaign</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Chào mừng đến với
                                    XiaoMu!</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">Khám phá các
                                    khóa học và bắt đầu hành trình chinh phục tiếng Trung của bạn.</p>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Vừa xong</p>
                            </div>
                        </a>
                        {{-- Dummy Notification Item 2 --}}
                        <a href="#"
                            class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800/50">
                            <div
                                class="size-10 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-xl">check_circle</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Học sinh hoàn thành
                                    bài học</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">Học sinh
                                    Nguyễn Văn A đã hoàn thành bài học "Phát âm cơ bản".</p>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">2 giờ trước</p>
                            </div>
                        </a>
                        {{-- Dummy Notification Item 3 --}}
                        <a href="#"
                            class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800/50">
                            <div
                                class="size-10 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-xl">support_agent</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Yêu cầu hỗ trợ</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">Có 1 yêu cầu
                                    hỗ trợ mới cần được xử lý sớm.</p>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">1 ngày trước</p>
                            </div>
                        </a>
                    </div>
                    <a href="#"
                        class="block px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-center text-sm text-primary font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors border-t border-slate-100 dark:border-slate-800">Xem
                        tất cả thông báo</a>
                </div>
            </div>
            {{-- User Info Dropdown --}}
            <div class="relative" x-data="{ userMenuOpen: false }">
                <button @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center gap-3 rounded-xl px-2 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    {{-- Avatar --}}
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
                                $roleLabels = \App\Models\User::getAllRole() + [
                                    \App\Models\User::ROLE_ADMIN => 'Admin',
                                ];
                            @endphp
                            {{ $roleLabels[Auth::user()->role] ?? 'Unknown' }}
                        </span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 text-base hidden lg:block"
                        x-text="userMenuOpen ? 'expand_less' : 'expand_more'"></span>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="userMenuOpen" @click.outside="userMenuOpen = false"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg z-50 overflow-hidden"
                    style="display: none;">
                    {{-- User summary at top --}}
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    {{-- Menu items --}}
                    <div class="py-1">
                        <a href="{{ route('admin.profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-base">manage_accounts</span> Profile
                        </a>
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-base">settings</span> Settings
                        </a>
                    </div>
                    {{-- Logout --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 py-1">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
