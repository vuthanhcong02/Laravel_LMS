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
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('courses') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('courses') }}">Courses</a>
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('roadmap') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('roadmap') }}">HSK Roadmap</a>
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('blog') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('blog') }}">Blog</a>
            <a class="text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'text-primary font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-primary' }}"
                href="{{ route('contact') }}">Contact</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}"
                class="px-5 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-primary transition-colors">Login</a>
            <a href="{{ route('register') }}"
                class="rounded-xl bg-primary px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">Register</a>
        </div>
    </div>
</header>
