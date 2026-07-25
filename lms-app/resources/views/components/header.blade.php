@php
    $isHomeActive = request()->routeIs('home');
    $isHskActive =
        request()->routeIs('flashcards') || request()->routeIs('student.quizzes.*') || request()->routeIs('courses');
    $isBlogActive = request()->routeIs('blog');
    $isContactActive = request()->routeIs('contact');
@endphp

<header x-data="{ mobileMenuOpen: false, openHSK: false }"
    class="sticky top-0 z-50 w-full border-b border-primary/10 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}"
                class="group flex items-center gap-3 sm:gap-4 px-2 py-1.5 -mx-2 rounded-2xl transition-all duration-300 active:scale-[0.97] dark:focus-visible:ring-offset-slate-900">
                <div
                    class="relative shrink-0 p-[2.5px] rounded-full overflow-hidden bg-gradient-to-br from-primary via-orange-400 to-amber-300 shadow-lg shadow-primary/30">
                    <div class="rounded-full overflow-hidden w-10 h-10 sm:w-12 sm:h-12">
                        <img src="{{ asset('logo.png') }}" alt="XiaoMu Logo"
                            class="w-full h-full object-cover object-center">
                    </div>
                </div>
                <div class="flex flex-col leading-none">
                    <span
                        class="text-base sm:text-lg font-black tracking-tight text-slate-900 dark:text-white group-hover:text-primary transition-colors duration-300">小木</span>
                    <span
                        class="text-[11px] sm:text-xs font-semibold tracking-[0.15em] uppercase text-primary/80 dark:text-primary/90 mt-0.5 transition-colors duration-300 group-hover:text-primary">Tiếng
                        Trung</span>
                </div>
            </a>
        </div>
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-8">
            <!-- Home Link -->
            <a class="relative py-2 text-sm font-semibold transition-colors {{ $isHomeActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('home') }}">
                Trang chủ
            </a>

            <!-- Dropdown Menu for HSK Features (Học Hán ngữ) -->
            <div class="relative" @mouseenter="openHSK = true" @mouseleave="openHSK = false"
                @click.away="openHSK = false">
                <button @click="openHSK = !openHSK"
                    class="relative py-2 flex items-center gap-1 text-sm font-semibold transition-colors focus:outline-none {{ $isHskActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}">
                    <span>Học Hán ngữ</span>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200"
                        :class="openHSK ? 'rotate-180' : ''">expand_more</span>
                </button>

                <!-- HSK Dropdown List (Glassmorphism design) -->
                <div x-show="openHSK" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95" style="display: none;"
                    class="absolute left-1/2 -translate-x-1/2 mt-3 w-64 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800/80 p-2.5 z-50 origin-top">
                    <div class="space-y-1">
                        <!-- Flashcard Link -->
                        <a href="{{ route('flashcards') }}"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('flashcards') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-primary' }}">
                            <span class="material-symbols-outlined text-[20px] font-bold">style</span>
                            <div class="text-left">
                                <p class="leading-none text-xs font-bold">Flashcard</p>
                                <p class="text-[9px] font-medium text-slate-400 dark:text-slate-500 mt-1.5">Luyện nhớ
                                    chữ Hán và phiên âm</p>
                            </div>
                        </a>

                        <!-- Pinyin Link -->
                        <a href="{{ url('/pinyin') }}"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->is('pinyin*') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-primary' }}">
                            <span class="material-symbols-outlined text-[20px] font-bold">sort_by_alpha</span>
                            <div class="text-left">
                                <p class="leading-none text-xs font-bold">Bảng Pinyin</p>
                                <p class="text-[9px] font-medium text-slate-400 dark:text-slate-500 mt-1.5">Học phát âm chuẩn tiếng Trung</p>
                            </div>
                        </a>

                        <!-- Course Curriculum Link (Giáo trình HSK) -->
                        <a href="{{ route('courses') }}"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('courses') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-primary' }}">
                            <span class="material-symbols-outlined text-[20px] font-bold">auto_stories</span>
                            <div class="text-left">
                                <p class="leading-none text-xs font-bold">Giáo trình chuẩn HSK</p>
                                <p class="text-[9px] font-medium text-slate-400 dark:text-slate-500 mt-1.5">Học theo lộ
                                    trình bài bản</p>
                            </div>
                        </a>

                        <!-- Quiz / Mock Test Link -->
                        <a href="{{ route('student.quizzes.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('student.quizzes.*') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-355 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-primary' }}">
                            <span class="material-symbols-outlined text-[20px] font-bold">fact_check</span>
                            <div class="text-left">
                                <p class="leading-none text-xs font-bold">Thi thử HSK</p>
                                <p class="text-[9px] font-medium text-slate-400 dark:text-slate-500 mt-1.5">Đánh giá
                                    năng lực thực tế</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Blog Link -->
            <a class="relative py-2 text-sm font-semibold transition-colors {{ $isBlogActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('blog') }}">
                Tin tức
            </a>

            <!-- Contact Link -->
            <a class="relative py-2 text-sm font-semibold transition-colors {{ $isContactActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('contact') }}">
                Liên hệ
            </a>
        </nav>

        <!-- Auth and Mobile menu triggers -->
        <div class="flex items-center gap-3">
            <!-- Theme Toggle Button -->
            <button x-data="{ isDark: document.documentElement.classList.contains('dark') }"
                @click="
                    isDark = !isDark;
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        localStorage.theme = 'dark';
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.theme = 'light';
                    }
                "
                class="relative flex h-10 w-10 items-center justify-center rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm transition-all duration-300 focus:outline-none hover:bg-slate-50 dark:hover:bg-slate-700 active:scale-95 overflow-hidden"
                title="Toggle Dark/Light Mode">
                <span
                    class="material-symbols-outlined text-[20px] absolute transition-all duration-500 transform text-slate-500"
                    :class="isDark ? '-translate-y-10 opacity-0 rotate-90' : 'translate-y-0 opacity-100 rotate-0'">
                    dark_mode
                </span>
                <span
                    class="material-symbols-outlined text-[20px] absolute transition-all duration-500 transform text-slate-500 dark:text-slate-400"
                    :class="isDark ? 'translate-y-0 opacity-100 rotate-0' : 'translate-y-10 opacity-0 -rotate-90'">
                    light_mode
                </span>
            </button>

            @auth
                <!-- Authenticated User Menu Dropdown -->
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
                <!-- Guest Actions -->
                <a href="{{ route('login') }}"
                    class="px-5 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-primary transition-colors">Đăng
                    nhập</a>
                <a href="{{ route('register') }}"
                    class="rounded-2xl bg-primary px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/40">Đăng
                    ký</a>
            @endauth

            <!-- Mobile Hamburger Menu Toggle Trigger -->
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="flex h-10 w-10 md:hidden items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800 text-slate-600 dark:text-slate-300 active:scale-95 transition-all focus:outline-none">
                <span class="material-symbols-outlined text-[22px] font-bold"
                    x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer Panel -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="-translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150 transform"
        x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-4 opacity-0"
        style="display: none;"
        class="w-full md:hidden bg-white/95 dark:bg-background-dark/95 border-b border-primary/10 backdrop-blur-md">
        <div class="px-6 py-4 flex flex-col gap-4" x-data="{ mobileHSKOpen: false }">
            <!-- Home Navigation -->
            <a href="{{ route('home') }}"
                class="text-sm font-bold flex items-center gap-3 py-2 border-b border-slate-100 dark:border-slate-800/80 transition-colors {{ $isHomeActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-[20px]">home</span>
                <span>Trang chủ</span>
            </a>

            <!-- HSK Submenu Accordion (Học Hán ngữ) -->
            <div class="border-b border-slate-100 dark:border-slate-800/80 pb-2">
                <button @click="mobileHSKOpen = !mobileHSKOpen"
                    class="w-full flex items-center justify-between py-2 text-sm font-bold transition-colors focus:outline-none {{ $isHskActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">translate</span>
                        <span>Học Hán ngữ</span>
                    </div>
                    <span class="material-symbols-outlined text-[20px] transition-transform duration-200"
                        :class="mobileHSKOpen ? 'rotate-180' : ''">expand_more</span>
                </button>

                <!-- Accordion Content List -->
                <div x-show="mobileHSKOpen" x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="-translate-y-2 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100" class="mt-2 pl-9 flex flex-col gap-3">
                    <!-- Flashcard -->
                    <a href="{{ route('flashcards') }}"
                        class="text-xs font-semibold flex items-center gap-2.5 transition-colors {{ request()->routeIs('flashcards') ? 'text-primary' : 'text-slate-500 dark:text-slate-400' }}">
                        <span class="material-symbols-outlined text-[18px]">style</span>
                        <span>Flashcard</span>
                    </a>

                    <!-- Pinyin -->
                    <a href="{{ url('/pinyin') }}"
                        class="text-xs font-semibold flex items-center gap-2.5 transition-colors {{ request()->is('pinyin*') ? 'text-primary' : 'text-slate-500 dark:text-slate-400' }}">
                        <span class="material-symbols-outlined text-[18px]">sort_by_alpha</span>
                        <span>Bảng Pinyin</span>
                    </a>

                    <!-- Course Curriculum (Giáo trình HSK) -->
                    <a href="{{ route('courses') }}"
                        class="text-xs font-semibold flex items-center gap-2.5 transition-colors {{ request()->routeIs('courses') ? 'text-primary' : 'text-slate-500 dark:text-slate-400' }}">
                        <span class="material-symbols-outlined text-[18px]">auto_stories</span>
                        <span>Giáo trình chuẩn HSK</span>
                    </a>

                    <!-- Quiz Mock Tests -->
                    <a href="{{ route('student.quizzes.index') }}"
                        class="text-xs font-semibold flex items-center gap-2.5 transition-colors {{ request()->routeIs('student.quizzes.*') ? 'text-primary' : 'text-slate-500 dark:text-slate-400' }}">
                        <span class="material-symbols-outlined text-[18px]">fact_check</span>
                        <span>Thi thử HSK</span>
                    </a>
                </div>
            </div>

            <!-- Blog Navigation -->
            <a href="{{ route('blog') }}"
                class="text-sm font-bold flex items-center gap-3 py-2 border-b border-slate-100 dark:border-slate-800/80 transition-colors {{ $isBlogActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-[20px]">rss_feed</span>
                <span>Tin tức</span>
            </a>

            <!-- Contact Navigation -->
            <a href="{{ route('contact') }}"
                class="text-sm font-bold flex items-center gap-3 py-2 border-b border-slate-100 dark:border-slate-800/80 transition-colors {{ $isContactActive ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-[20px]">mail</span>
                <span>Liên hệ</span>
            </a>
        </div>
    </div>
</header>
