<section class="w-full py-12 md:py-16 relative overflow-hidden bg-gradient-to-r from-slate-950 via-[#1A2B3C] to-slate-950 border-b border-primary/20 transition-all duration-300">
    <!-- Grid Pattern Overlay (SaaS Coordinates) -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:32px_32px] opacity-80 pointer-events-none"></div>

    <!-- Soft Blur Orbs for background depth -->
    <div class="absolute right-[-10%] top-[-20%] w-[350px] h-[350px] bg-primary/30 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute left-[30%] bottom-[-50%] w-[250px] h-[250px] bg-indigo-500/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 flex flex-col items-start gap-4">
        <!-- Interactive Status Badge -->
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 border border-primary/30 text-[9px] font-black text-primary uppercase tracking-widest shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
            <span>Học tập & Rèn luyện</span>
        </div>

        <!-- Glassmorphism Breadcrumb Navigation (High Contrast Dark) -->
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 px-4 py-2 rounded-full bg-slate-950/60 border border-white/20 backdrop-blur-md text-[11px] md:text-xs text-white font-semibold font-sans">
                <li class="flex items-center">
                    <a class="flex items-center hover:text-primary transition-colors gap-1 text-slate-350" href="{{ route('home') }}">
                        <span class="material-symbols-outlined text-[15px] font-bold">home</span>
                        <span>Trang chủ</span>
                    </a>
                </li>
                <li class="flex items-center">
                    <span class="material-symbols-outlined text-[14px] opacity-40 text-slate-400">chevron_right</span>
                </li>
                <li class="flex items-center text-primary font-bold">
                    <span>@yield('breadcrumb')</span>
                </li>
            </ol>
        </nav>

        <!-- Main Banner Title & Dynamic Subtitle -->
        <div class="flex flex-col gap-2 mt-1">
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight drop-shadow-sm font-poppins leading-none">
                @yield('breadcrumb')
            </h1>
            
            <p class="text-xs md:text-sm text-slate-300 font-medium max-w-2xl leading-relaxed mt-1 opacity-95">
                @hasSection('breadcrumb_desc')
                    @yield('breadcrumb_desc')
                @else
                    Hệ thống học tiếng Trung trực tuyến thông minh cùng XiaoMu Chinese.
                @endif
            </p>
        </div>
    </div>
</section>
