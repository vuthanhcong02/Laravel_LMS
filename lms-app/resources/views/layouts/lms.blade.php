<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    @open-auth-modal.window="authModalOpen = true; if ($event.detail && $event.detail.tab) authModalTab = $event.detail.tab; if ($event.detail && $event.detail.redirect) authRedirectUrl = $event.detail.redirect;"
    @open-contact-modal.window="contactModalOpen = true"
    x-data="{
    sidebarOpen: false,
    sidebarCollapsed: false,
    isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
    langOpen: false,
    currentLang: 'Việt Nam',
    darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    socialDockExpanded: true,
    searchKeyword: '',
    authModalOpen: {{ auth()->guest() && ($errors->any() || session('status')) ? 'true' : 'false' }},
    authModalTab: '{{ session('auth_tab') ?? (old('first_name') ? 'register' : 'login') }}',
    authRedirectUrl: '',
    contactModalOpen: false,
    authEmail: '',
    authPassword: '',
    authFirstName: '',
    authLastName: '',
    authRemember: false,
    authShowPassword: false,
    authLoading: false,
    @yield('alpine-data')
}">

<head>
    <!-- Ngăn FOUC Dark Mode (Flash of Unstyled Content) -->
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Khóa học HSK - Tiếng Trung XIAOMU LMS')</title>
    <meta name="description" content="Khám phá các khóa học HSK từ sơ cấp HSK 1 đến cao cấp HSK 6.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @yield('custom-css')
    </style>
</head>

<body
    class="h-full antialiased bg-[#f8f6f3] dark:bg-[#0e0c0b] text-slate-800 dark:text-slate-100 selection:bg-[#e07a5f] selection:text-white transition-colors duration-200"
    x-init="setTimeout(() => { document.getElementById('global-preloader').style.opacity = '0';
        setTimeout(() => document.getElementById('global-preloader').remove(), 300) }, 100)">

    <!-- PRELOADER MÀN HÌNH CHỜ -->
    <div id="global-preloader"
        class="fixed inset-0 z-[9999] bg-[#f8f6f3] dark:bg-[#0e0c0b] flex flex-col items-center justify-center transition-opacity duration-300">
        <div class="relative flex items-center justify-center w-20 h-20">
            <div class="absolute inset-0 border-4 border-[#fcdccf] dark:border-[#42271f] rounded-full"></div>
            <div class="absolute inset-0 border-4 border-[#e07a5f] rounded-full border-t-transparent animate-spin">
            </div>
            <img src="{{ asset('logo.png') }}" class="w-12 h-12 rounded-full p-1" alt="Loading">
        </div>
        <p class="mt-4 text-xs font-bold text-[#e07a5f] animate-pulse tracking-widest uppercase">Đang tải dữ liệu...</p>
    </div>

    <div class="flex h-full w-full overflow-hidden relative">

        <!-- Overlay cho mobile sidebar -->
        <div x-show="sidebarOpen" x-cloak x-transition:enter="transition-opacity ease-out duration-250"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden" style="display: none;"></div>

        <!-- Sidebar Navigation -->
        @include('components.lms.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

            <!-- TOPBAR HEADER -->
            @include('components.lms.header')

            @hasSection('sub-header')
                <div class="px-4 sm:p-6 pb-0 sm:pb-0 pr-14 sm:pr-16 pt-4 shrink-0 bg-[#f8f6f3] dark:bg-[#0e0c0b] z-20 transition-all duration-300"
                    :class="sidebarCollapsed ? 'px-4 sm:p-6 pl-4 sm:pl-6 pr-14 sm:pr-16 pb-0 sm:pb-0' :
                        'px-4 sm:p-6 pb-0 sm:pb-0 pr-14 sm:pr-16'">
                    <div class="mx-auto max-w-6xl transition-all duration-300"
                        :class="sidebarCollapsed ? 'max-w-7xl' : 'max-w-6xl'">
                        @yield('sub-header')
                    </div>
                </div>
            @endif

            <!-- Page Body -->
            <div class="flex-1 overflow-y-auto no-scrollbar transition-all duration-300 space-y-6 px-4 sm:px-6 pt-8 pb-6 pr-14 sm:pr-16"
                :class="sidebarCollapsed ? 'px-4 sm:px-6 pt-8 pb-6 pl-4 sm:pl-6 pr-14 sm:pr-16' :
                    'px-4 sm:px-6 pt-8 pb-6 pr-14 sm:pr-16'">

                <div class="mx-auto space-y-6 transition-all duration-300 max-w-6xl"
                    :class="sidebarCollapsed ? 'max-w-7xl' : 'max-w-6xl'">

                    @yield('content')

                </div>
            </div>

        </main>
    </div>

    <!-- FLOATING SOCIAL SHARE DOCK (THU GỌN / MỞ RỘNG SIÊU MƯỢT MÀ 60FPS) -->
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-50 flex flex-col items-end">
        <div
            class="bg-white/95 dark:bg-[#181615]/95 backdrop-blur-md border-l border-y border-[#e8e2d9] dark:border-[#2d2926] rounded-l-2xl px-2 py-2.5 shadow-xl ring-1 ring-black/5 dark:ring-white/5 flex flex-col items-center transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

            <!-- Nút Nguồn Terracotta Share -->
            <button @click="socialDockExpanded = !socialDockExpanded" type="button"
                class="w-9 h-9 rounded-full bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white flex items-center justify-center text-xs transition-all btn-tactile shadow-md shadow-[#e07a5f]/30 hover:scale-105 group relative cursor-pointer select-none"
                :title="socialDockExpanded ? 'Thu gọn Mạng xã hội' : 'Mở rộng Mạng xã hội'">
                <i class="fa-solid fa-share-nodes pointer-events-none transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]"
                    :class="socialDockExpanded ? 'rotate-0' : '-rotate-90'"></i>
                <span
                    class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50"
                    x-text="socialDockExpanded ? 'Thu gọn' : 'Mở rộng Mạng xã hội'"></span>
            </button>

            <!-- Panel 5 Icon Mạng xã hội (CSS Grid 60FPS Animated) -->
            <div class="social-dock-panel" :class="{ 'expanded': socialDockExpanded }">
                <div class="social-dock-items">

                    <!-- 1. Facebook -->
                    <a href="https://www.facebook.com/profile.php?id=61589009699142" target="_blank"
                        rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-[#1877f2] text-white flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-[#1877f2]/30 transition-all btn-tactile group relative"
                        title="Facebook XIAOMU">
                        <i class="fa-brands fa-facebook-f"></i>
                        <span
                            class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">Facebook</span>
                    </a>

                    <!-- 2. YouTube -->
                    <a href="https://www.youtube.com/@Chiettuchuhan" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-[#FF0000] text-white flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-[#FF0000]/30 transition-all btn-tactile group relative"
                        title="YouTube Bài giảng">
                        <i class="fa-brands fa-youtube"></i>
                        <span
                            class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">YouTube</span>
                    </a>



                    <!-- 4. TikTok -->
                    <a href="https://www.tiktok.com/@chiettuchuhan55" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-[#111111] dark:bg-[#222222] text-white border border-slate-700/30 flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-black/40 transition-all btn-tactile group relative"
                        title="TikTok Từ vựng 60s">
                        <i class="fa-brands fa-tiktok"></i>
                        <span
                            class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">TikTok</span>
                    </a>

                    <!-- 5. Zalo -->
                    <a href="https://zalo.me/0395294739" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-[#0068ff] text-white font-black text-[10px] tracking-tight flex items-center justify-center hover:scale-110 hover:shadow-lg hover:shadow-[#0068ff]/30 transition-all btn-tactile group relative"
                        title="Zalo">
                        <span>Zalo</span>
                        <span
                            class="absolute right-12 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-lg backdrop-blur-xs opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-150 whitespace-nowrap z-50">Zalo</span>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- Global Toast Notification -->
    @if (session('status') && auth()->check())
        <div x-data="{ showToast: true }" x-show="showToast" x-init="setTimeout(() => showToast = false, 5000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-[9999] flex items-center gap-3 p-4 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xl">
            <div
                class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-white">Thành công</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ session('status') }}</p>
            </div>
            <button @click="showToast = false"
                class="ml-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @include('components.lms.auth-modal')
    @include('components.lms.contact-modal')

    @yield('scripts')
</body>

</html>
