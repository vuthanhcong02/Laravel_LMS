<header
    class="sticky top-0 z-50 w-full border-b border-primary/10 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
        <div class="flex items-center gap-2">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-white">
                <span class="material-symbols-outlined">translate</span>
            </div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">XiaoMu <span
                    class="text-primary">Chinese</span></h2>
        </div>
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('home') }}">Home</a>
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('flashcards') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('flashcards') }}">FlashCard</a>
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('student.quizzes.*') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('student.quizzes.index') }}">Thi thử HSK</a>
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('blog') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('blog') }}">Blog</a>
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('contact') }}">Contact</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-2 focus:outline-none">
                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->first_name . ' ' . auth()->user()->last_name) . '&color=FFFFFF&background=8fc0e0' }}"
                            alt="Avatar" class="h-10 w-10 rounded-full object-cover border-2 border-primary/20">
                        <span
                            class="text-sm font-bold text-slate-700 dark:text-slate-200 hidden md:block">{{ auth()->user()->first_name }}
                            {{ auth()->user()->last_name }}</span>
                        <span class="material-symbols-outlined text-slate-400">expand_more</span>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95" style="display: none;"
                        class="absolute right-0 mt-3 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 p-2 z-50 origin-top-right">

                        <div class="px-3 py-3 border-b border-slate-100 dark:border-slate-800 mb-2">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                {{ auth()->user()->email }}</p>
                        </div>

                        <div class="space-y-1">
                            @if (auth()->user()->role !== \App\Models\User::ROLE_GUEST)
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-xl hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary transition-all">
                                    <span class="material-symbols-outlined text-[20px]">grid_view</span>
                                    Trang tổng quan
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-xl hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary transition-all">
                                <span class="material-symbols-outlined text-[20px]">person</span>
                                Hồ sơ cá nhân
                            </a>
                        </div>

                        <div class="h-[1px] bg-slate-100 dark:bg-slate-800 my-2"></div>

                        <form method="POST" action="{{ route('logout') }}" class="w-full" @click.stop>
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-left">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="px-5 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-primary transition-colors">Đăng
                    nhập</a>
                <a href="{{ route('register') }}"
                    class="rounded-xl bg-primary px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">Đăng
                    ký</a>
            @endauth
        </div>
    </div>
</header>
