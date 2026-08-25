<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ 
    sidebarOpen: false, 
    sidebarCollapsed: false, 
    isLoggedIn: true, 
    langOpen: false, 
    currentLang: 'Việt Nam', 
    darkMode: false,
    socialDockExpanded: true,
    searchKeyword: '',
    authModalOpen: false,
    authModalTab: 'login',
    authEmail: '',
    authPassword: '',
    authName: '',
    authRemember: false,
    authShowPassword: false,
    authLoading: false,
    @yield('alpine-data') 
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Khóa học HSK - Tiếng Trung XIAOMU LMS')</title>
    <meta name="description" content="Khám phá các khóa học HSK từ sơ cấp HSK 1 đến cao cấp HSK 6.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --brand-primary: #e07a5f; --brand-primary-hover: #c86349; --brand-bg: #f8f6f3; --card-border: #e8e2d9; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: var(--brand-bg); color: #1e1b18; -webkit-font-smoothing: antialiased; }
        .zh-text { font-family: 'Noto Sans SC', sans-serif; }
        .lms-card { background-color: #ffffff; border: 1px solid var(--card-border); border-radius: 1.25rem; box-shadow: 0 4px 16px -2px rgba(30, 27, 24, 0.03); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
        .dark .lms-card { background-color: #181615; border-color: #2d2926; box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.5); }
        .lms-card:hover { border-color: #e07a5f; transform: translateY(-2px); box-shadow: 0 10px 24px -6px rgba(224, 122, 95, 0.12); }
        .dark .lms-card:hover { border-color: #e07a5f; box-shadow: 0 10px 24px -6px rgba(224, 122, 95, 0.15); }
        .btn-tactile { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        .btn-tactile:active { transform: scale(0.96); }
        .nav-item-active { background-color: #fff2ee; color: #e07a5f; font-weight: 700; }
        .dark .nav-item-active { background-color: #2a221f; color: #f4978e; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Smooth 60fps Floating Social Dock Animation */
        .social-dock-panel {
            display: grid;
            grid-template-rows: 0fr;
            opacity: 0;
            margin-top: 0px;
            padding-top: 0px;
            border-top: 1px solid transparent;
            transition: grid-template-rows 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 0.25s ease-out,
                        margin-top 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        padding-top 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        border-color 0.25s ease-out;
        }
        .social-dock-panel.expanded {
            grid-template-rows: 1fr;
            opacity: 1;
            margin-top: 8px;
            padding-top: 8px;
            border-color: var(--card-border, #e8e2d9);
        }
        .dark .social-dock-panel.expanded {
            border-color: #2d2926;
        }
        .social-dock-items {
            min-height: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .social-dock-panel.expanded .social-dock-items {
            overflow: visible;
        }
        
        @yield('custom-css')
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full antialiased bg-[#f8f6f3] dark:bg-[#0e0c0b] text-slate-800 dark:text-slate-100 selection:bg-[#e07a5f] selection:text-white transition-colors duration-200" x-init="setTimeout(() => { document.getElementById('global-preloader').style.opacity = '0'; setTimeout(() => document.getElementById('global-preloader').remove(), 300) }, 100)">

    <!-- PRELOADER MÀN HÌNH CHỜ -->
    <div id="global-preloader" class="fixed inset-0 z-[9999] bg-[#f8f6f3] dark:bg-[#0e0c0b] flex flex-col items-center justify-center transition-opacity duration-300">
        <div class="relative flex items-center justify-center w-20 h-20">
            <div class="absolute inset-0 border-4 border-[#fcdccf] dark:border-[#42271f] rounded-full"></div>
            <div class="absolute inset-0 border-4 border-[#e07a5f] rounded-full border-t-transparent animate-spin"></div>
            <img src="{{ asset('logo.png') }}" class="w-12 h-12 rounded-full p-1" alt="Loading">
        </div>
        <p class="mt-4 text-xs font-bold text-[#e07a5f] animate-pulse tracking-widest uppercase">Đang tải dữ liệu...</p>
    </div>

    <div class="flex h-full w-full overflow-hidden relative">

        <!-- Overlay cho mobile sidebar -->
        <div x-show="sidebarOpen" x-cloak 
             x-transition:enter="transition-opacity ease-out duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden"
             style="display: none;"></div>

        <!-- Sidebar Navigation -->
        @include('components.lms.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <!-- TOPBAR HEADER -->
            @include('components.lms.header')

            <!-- Page Body -->
            <div class="flex-1 overflow-y-auto no-scrollbar transition-all duration-300 space-y-6 p-4 sm:p-6 pr-14 sm:pr-16"
                 :class="sidebarCollapsed ? 'p-4 sm:p-6 pl-4 sm:pl-6 pr-14 sm:pr-16' : 'p-4 sm:p-6 pr-14 sm:pr-16'">
                
                <div class="mx-auto space-y-6 transition-all duration-300 max-w-6xl"
                     :class="sidebarCollapsed ? 'max-w-7xl' : 'max-w-6xl'">
                     
                    @yield('content')
                    
                </div>
            </div>
            
        </main>
    </div>

        <!-- FLOATING SOCIAL SHARE DOCK (THU GỌN / MỞ RỘNG SIÊU MƯỢT MÀ 60FPS) -->
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-50 flex flex-col items-end">
        <div class="bg-white/95 dark:bg-[#181615]/95 backdrop-blur-md border-l border-y border-[#e8e2d9] dark:border-[#2d2926] rounded-l-2xl px-2 py-2.5 shadow-xl ring-1 ring-black/5 dark:ring-white/5 flex flex-col items-center transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
            
            <!-- Nút Nguồn Terracotta Share -->
            <button @click="socialDockExpanded = !socialDockExpanded" 
                    type="button"
                    class="w-9 h-9 rounded-full bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white flex items-center justify-center text-xs transition-all btn-tactile shadow-md shadow-[#e07a5f]/30 hover:scale-105 group relative cursor-pointer select-none" 
                    :title="socialDockExpanded ? 'Thu gọn Mạng xã hội' : 'Mở rộng Mạng xã hội'">
                <i class="fa-solid fa-share-nodes pointer-events-none transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]" :class="socialDockExpanded ? 'rotate-0' : '-rotate-90'"></i>
                <span class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50" x-text="socialDockExpanded ? 'Thu gọn' : 'Mở rộng Mạng xã hội'"></span>
            </button>

            <!-- Panel 5 Icon Mạng xã hội (CSS Grid 60FPS Animated) -->
            <div class="social-dock-panel" :class="{ 'expanded': socialDockExpanded }">
                <div class="social-dock-items">
                    
                    <!-- 1. Facebook -->
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-[#1877f2] text-white flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-[#1877f2]/30 transition-all btn-tactile group relative" title="Facebook XIAOMU">
                        <i class="fa-brands fa-facebook-f"></i>
                        <span class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">Facebook Fanpage</span>
                    </a>

                    <!-- 2. YouTube -->
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-[#FF0000] text-white flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-[#FF0000]/30 transition-all btn-tactile group relative" title="YouTube Bài giảng">
                        <i class="fa-brands fa-youtube"></i>
                        <span class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">Kênh YouTube Bài giảng</span>
                    </a>

                    <!-- 3. Instagram -->
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-rose-500/30 transition-all btn-tactile group relative" title="Instagram XIAOMU">
                        <i class="fa-brands fa-instagram"></i>
                        <span class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">Instagram Story</span>
                    </a>

                    <!-- 4. TikTok -->
                    <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-[#111111] dark:bg-[#222222] text-white border border-slate-700/30 flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-black/40 transition-all btn-tactile group relative" title="TikTok Từ vựng 60s">
                        <i class="fa-brands fa-tiktok"></i>
                        <span class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">TikTok Từ vựng 60s</span>
                    </a>

                    <!-- 5. Zalo Official -->
                    <a href="https://zalo.me" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-[#0068ff] text-white font-black text-[10px] tracking-tight flex items-center justify-center hover:scale-110 hover:shadow-lg hover:shadow-[#0068ff]/30 transition-all btn-tactile group relative" title="Zalo Official">
                        <span>Zalo</span>
                        <span class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">Zalo OA Hỗ trợ</span>
                    </a>

                </div>
            </div>

        </div>
    </div>


    <!-- POPUP AUTH MODAL DÙNG CHUNG -->
    @include('components.lms.auth-modal')

    @yield('scripts')
</body>
</html>
