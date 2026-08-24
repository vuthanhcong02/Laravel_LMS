<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ sidebarOpen: false, sidebarCollapsed: false, isLoggedIn: true, langOpen: false, currentLang: 'Việt Nam', darkMode: false, rankTab: 'week', rankMetric: 'time', socialDockExpanded: true }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiếng Trung XiaoMu - Trang chủ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Inter + Noto Sans SC (Chữ Hán tự) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --brand-primary: #e07a5f;
            --brand-primary-hover: #c86349;
            --brand-bg: #f8f6f3;
            --card-border: #e8e2d9;
        }

        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--brand-bg);
            color: #1e1b18;
            -webkit-font-smoothing: antialiased;
        }
        
        .zh-text { font-family: 'Noto Sans SC', sans-serif; }

        /* KEYFRAME ANIMATIONS (UI/UX Pro Max) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes flameFlicker {
            0%, 100% { transform: scale(1) rotate(-1deg); }
            25% { transform: scale(1.08) rotate(2deg); }
            50% { transform: scale(0.95) rotate(-2deg); }
            75% { transform: scale(1.05) rotate(1deg); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-flame {
            display: inline-block;
            animation: flameFlicker 1.8s ease-in-out infinite;
        }

        .shimmer-bg {
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            background-size: 200% 100%;
            animation: shimmer 2.5s infinite;
        }

        /* Thẻ Card Bo tròn & Tinh Tế Chuẩn Cao Cấp */
        .lms-card {
            background-color: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 1.25rem;
            box-shadow: 0 4px 16px -2px rgba(30, 27, 24, 0.03);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dark .lms-card {
            background-color: #181615;
            border-color: #2a2624;
            box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.5);
        }

        .lms-card:hover {
            border-color: #d8cebf;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px -6px rgba(224, 122, 95, 0.1);
        }

        .dark .lms-card:hover {
            border-color: #383330;
            box-shadow: 0 10px 24px -6px rgba(0, 0, 0, 0.7);
        }

        /* Nút bấm Tactile Press Response */
        .btn-tactile {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-tactile:active {
            transform: scale(0.96);
        }

        /* Nút Menu Active sạch sẽ */
        .nav-item-active {
            background-color: #fff2ee;
            color: #e07a5f;
            font-weight: 700;
        }

        .dark .nav-item-active {
            background-color: #2a221f;
            color: #f4978e;
        }

        /* Custom Scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-full antialiased bg-[#f8f6f3] dark:bg-[#0e0c0b] text-slate-800 dark:text-slate-100 selection:bg-[#e07a5f] selection:text-white transition-colors duration-200">

    <div class="flex h-full w-full overflow-hidden relative">

        <!-- Overlay cho mobile sidebar -->
        <div x-show="sidebarOpen" 
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
        <aside class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-[#141211] border-r border-[#e8e2d9] dark:border-[#262220] flex flex-col transition-all duration-300 ease-out lg:static shrink-0 h-screen overflow-hidden"
               :class="{ 
                   'translate-x-0': sidebarOpen, 
                   '-translate-x-full lg:translate-x-0': !sidebarOpen, 
                   'w-64': !sidebarCollapsed, 
                   'w-20': sidebarCollapsed 
               }">
            
            <!-- KHU VỰC LOGO THƯƠNG HIỆU -->
            <div class="h-20 flex items-center justify-between px-4 border-b border-[#e8e2d9] dark:border-[#262220] shrink-0">
                <a href="{{ url('/demo-ui') }}" class="flex items-center gap-3 group min-w-0" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                    <img src="{{ asset('logo.png') }}" alt="XiaoMu Logo" class="w-10 h-10 rounded-full object-cover shrink-0 group-hover:scale-105 transition-transform duration-200">

                    <div x-show="!sidebarCollapsed" class="flex flex-col min-w-0 transition-opacity duration-200">
                        <span class="font-bold text-lg tracking-tight text-slate-900 dark:text-white leading-none">XiaoMu</span>
                        <span class="text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">
                            Tiếng Trung LMS
                        </span>
                    </div>
                </a>

                <!-- Nút đóng trên Mobile -->
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Menu chính (Tự co giãn scroll nội dung) -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5 no-scrollbar">
                
                <!-- Nhóm Học tập -->
                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Học tập</div>
                    <div class="space-y-1">
                        <!-- Trang chủ -->
                        <a href="{{ url('/demo-ui') }}" class="flex items-center gap-3 rounded-xl text-sm nav-item-active transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Trang chủ' : ''">
                            <i class="fa-solid fa-house text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Trang chủ</span>
                        </a>

                        <!-- Khóa học -->
                        <a href="{{ url('/demo-courses') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Khóa học HSK' : ''">
                            <i class="fa-solid fa-book-open text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Khóa học HSK</span>
                        </a>

                        <!-- Luyện thi HSK -->
                        <a href="{{ url('/demo-exams') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện thi HSK' : ''">
                            <i class="fa-solid fa-file-pen text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện thi HSK</span>
                        </a>

                        <!-- Thẻ ghi nhớ -->
                        <a href="{{ url('/demo-flashcards') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Thẻ ghi nhớ' : ''">
                            <i class="fa-solid fa-layer-group text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Thẻ ghi nhớ</span>
                        </a>
                        <a href="{{ url('/demo-etymology') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Chiết tự chữ Hán' : ''">
                            <i class="fa-solid fa-puzzle-piece text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Chiết tự chữ Hán</span>
                        </a>

                        <!-- Bảng phiên âm Pinyin -->
                        <a href="{{ url('/demo-pinyin-chart') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Bảng Pinyin' : ''">
                            <i class="fa-solid fa-table-cells text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Bảng Pinyin</span>
                        </a>

                        <!-- Luyện tập Pinyin -->
                        <a href="{{ url('/demo-pinyin-practice') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện tập Pinyin' : ''">
                            <i class="fa-solid fa-headset text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện tập Pinyin</span>
                        </a>

                        <!-- Góc chia sẻ (Blog) -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Góc chia sẻ' : ''">
                            <i class="fa-solid fa-newspaper text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Góc chia sẻ</span>
                        </a>
                    </div>
                </div>

                <!-- Nhóm Cá nhân & Hệ thống (Bám sát thiết kế hình ảnh) -->
                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Hệ thống</div>
                    <div class="space-y-1">
                        <!-- Từ vựng của tôi (Bám sát hình 1) -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Từ vựng của tôi' : ''">
                            <i class="fa-solid fa-font text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Từ vựng của tôi</span>
                        </a>

                        <!-- Liên hệ -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-[#e07a5f] dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Liên hệ hỗ trợ' : ''">
                            <i class="fa-solid fa-envelope text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Liên hệ hỗ trợ</span>
                        </a>

                        <!-- Cài đặt -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Cài đặt tài khoản' : ''">
                            <i class="fa-solid fa-gear text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Cài đặt tài khoản</span>
                        </a>

                        <!-- Đăng xuất (Bám sát hình 1 - Chữ đỏ & Icon Đăng xuất) -->
                        <button x-show="isLoggedIn" @click="isLoggedIn = false" class="group flex items-center gap-3 w-full rounded-xl text-sm text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Đăng xuất' : ''">
                            <i class="fa-solid fa-arrow-right-from-bracket text-red-500 text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate font-semibold">Đăng xuất</span>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Banner Quảng cáo Nâng cấp -->
            <div x-show="!sidebarCollapsed" class="p-3.5 m-3 rounded-2xl bg-gradient-to-br from-[#fff2ee] to-[#fdeae3] dark:from-slate-800 dark:to-slate-900 border border-[#fcdccf] dark:border-slate-700 shadow-xs relative overflow-hidden shrink-0 transition-all">
                <div class="flex items-center gap-2 text-[#e07a5f] font-bold text-xs mb-1">
                    <i class="fa-solid fa-crown text-amber-500"></i> NÂNG CẤP TÀI KHOẢN
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 mb-2 leading-relaxed">Mở khóa toàn bộ các bộ đề thi HSK 1 - HSK 6 nâng cao.</p>
                <button class="w-full py-1.5 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs shadow-md transition-all btn-tactile hover:shadow-lg">
                    Nâng cấp ngay
                </button>
            </div>

            <!-- Chân trang Hồ sơ cá nhân (Ghim cố định shrink-0 ở đáy Sidebar) -->
            <div class="border-t border-[#e8e2d9] dark:border-[#262220] bg-[#faf6f2] dark:bg-slate-900/60 shrink-0 transition-all">
                
                <!-- TRẠNG THÁI 1: KHI ĐÃ ĐĂNG NHẬP (BÁM SÁT HÌNH 1) -->
                <div x-show="isLoggedIn" class="p-3">
                    <a href="#" class="flex items-center gap-3 min-w-0 group" :class="sidebarCollapsed ? 'justify-center' : ''">
                        <!-- Avatar tròn có huy hiệu Lv.1 ở góc dưới -->
                        <div class="relative shrink-0">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 group-hover:border-[#e07a5f] transition-colors">
                            <span class="absolute -bottom-1 -right-1 bg-slate-800 dark:bg-slate-900 text-slate-200 text-[9px] font-bold px-1 py-0.2 rounded-full border border-slate-600 leading-none">Lv.1</span>
                        </div>

                        <div x-show="!sidebarCollapsed" class="truncate min-w-0">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors">Vũ Thành Công</p>
                            <p class="text-xs text-slate-400 font-medium truncate">Hồ sơ</p>
                        </div>
                    </a>
                </div>

                <!-- TRẠNG THÁI 2: KHI CHƯA ĐĂNG NHẬP (GUEST USER) -->
                <div x-show="!isLoggedIn" class="p-3 w-full">
                    <a href="{{ url('/login') }}" @click.prevent="isLoggedIn = true" class="w-full py-2.5 bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold rounded-xl text-center shadow-md transition-all btn-tactile flex items-center justify-center gap-2" :title="sidebarCollapsed ? 'Đăng nhập' : ''">
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        <span x-show="!sidebarCollapsed">Đăng nhập</span>
                    </a>
                </div>

                <!-- Nút Thu gọn Sidebar dưới cùng (BÁM SÁT HÌNH 1: « Thu gọn) -->
                <div class="border-t border-[#e8e2d9] dark:border-[#262220] py-2 px-3 bg-white/50 dark:bg-slate-900/40">
                    <button @click="sidebarCollapsed = !sidebarCollapsed" 
                            class="w-full py-1.5 flex items-center justify-center gap-2 text-xs font-bold text-slate-500 hover:text-[#e07a5f] dark:text-slate-400 dark:hover:text-white transition-colors btn-tactile" 
                            :title="sidebarCollapsed ? 'Mở rộng Sidebar' : 'Thu gọn Sidebar'">
                        <i class="fa-solid text-xs transition-transform duration-300" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
                        <span x-show="!sidebarCollapsed" class="tracking-wide">Thu gọn</span>
                    </button>
                </div>

            </div>
        </aside>

        <!-- Vùng nội dung chính -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Topbar Header -->
            <header class="h-20 bg-white dark:bg-[#141211] border-b border-[#e8e2d9] dark:border-[#262220] flex items-center justify-between px-4 sm:px-6 pr-14 sm:pr-16 shrink-0 gap-4 transition-colors">
                
                <!-- Ô tìm kiếm & Nút mở Menu Mobile & Nút Thu gọn Desktop -->
                <div class="flex items-center gap-3 flex-1 max-w-lg">
                    <!-- Nút mở Drawer Mobile -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>

                    <!-- Nút Thu gọn / Mở rộng Sidebar trên Desktop Topbar Header -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile" title="Thu gọn / Mở rộng Sidebar Navigation">
                        <i class="fa-solid text-base transition-transform duration-300" :class="sidebarCollapsed ? 'fa-indent rotate-180 text-[#e07a5f]' : 'fa-bars-staggered'"></i>
                    </button>

                    <div class="relative w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" placeholder="Tìm kiếm khóa học, đề thi HSK, từ vựng..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
                    </div>
                </div>

                <!-- Điều khiển bên phải Header -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Chuyển đổi Ngôn ngữ -->
                    <div class="relative">
                        <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-slate-300 transition-all btn-tactile">
                            <!-- SVG Cờ Việt Nam -->
                            <template x-if="currentLang === 'Việt Nam'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20">
                                    <rect width="30" height="20" fill="#da251d"/>
                                    <polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/>
                                </svg>
                            </template>
                            <!-- SVG Cờ Trung Quốc -->
                            <template x-if="currentLang === '中文'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20">
                                    <rect width="30" height="20" fill="#ee1c25"/>
                                    <polygon points="5,3 5.6,4.8 7.4,4.8 5.9,5.9 6.5,7.7 5,6.6 3.5,7.7 4.1,5.9 2.6,4.8 4.4,4.8" fill="#ffde00"/>
                                </svg>
                            </template>
                            <!-- SVG Cờ Anh Quốc -->
                            <template x-if="currentLang === 'English'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30">
                                    <clipPath id="s_uk"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                                    <clipPath id="t_uk"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                                    <g clip-path="url(#s_uk)">
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk)"/>
                                        <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                                        <path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/>
                                    </g>
                                </svg>
                            </template>

                            <span class="font-bold zh-text" x-text="currentLang">Việt Nam</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': langOpen }"></i>
                        </button>

                        <!-- Menu Option chuẩn quốc tế -->
                        <div x-show="langOpen" 
                             x-transition
                             class="absolute right-0 mt-2 w-40 rounded-2xl bg-white dark:bg-slate-900 border border-[#e8e2d9] dark:border-slate-800 shadow-xl py-1.5 z-50 text-xs" 
                             style="display: none;">
                            <button @click="currentLang = 'Việt Nam'; langOpen = false" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-slate-800 font-bold transition-colors" :class="currentLang === 'Việt Nam' ? 'text-[#e07a5f]' : 'text-slate-700 dark:text-slate-300'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20">
                                    <rect width="30" height="20" fill="#da251d"/>
                                    <polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/>
                                </svg>
                                <span>Việt Nam</span> 
                                <i x-show="currentLang === 'Việt Nam'" class="fa-solid fa-check ml-auto text-[10px]"></i>
                            </button>

                            <button @click="currentLang = '中文'; langOpen = false" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-slate-800 font-bold transition-colors zh-text" :class="currentLang === '中文' ? 'text-[#e07a5f]' : 'text-slate-700 dark:text-slate-300'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20">
                                    <rect width="30" height="20" fill="#ee1c25"/>
                                    <polygon points="5,3 5.6,4.8 7.4,4.8 5.9,5.9 6.5,7.7 5,6.6 3.5,7.7 4.1,5.9 2.6,4.8 4.4,4.8" fill="#ffde00"/>
                                </svg>
                                <span>中文 (简体)</span> 
                                <i x-show="currentLang === '中文'" class="fa-solid fa-check ml-auto text-[10px]"></i>
                            </button>

                            <button @click="currentLang = 'English'; langOpen = false" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-slate-800 font-bold transition-colors" :class="currentLang === 'English' ? 'text-[#e07a5f]' : 'text-slate-700 dark:text-slate-300'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30">
                                    <clipPath id="s_uk2"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                                    <clipPath id="t_uk2"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                                    <g clip-path="url(#s_uk2)">
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk2)"/>
                                        <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                                        <path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/>
                                    </g>
                                </svg>
                                <span>English</span> 
                                <i x-show="currentLang === 'English'" class="fa-solid fa-check ml-auto text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Huy hiệu Streak 14 Ngày học -->
                    <div class="hidden sm:flex items-center gap-1.5 bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] px-3 py-1.5 rounded-xl shadow-xs">
                        <i class="fa-solid fa-fire text-[#e07a5f] text-sm animate-flame"></i>
                        <span class="text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">14 Ngày học</span>
                    </div>

                    <!-- Bật/Tắt Chế độ Tối -->
                    <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile" title="Bật/Tắt Chế độ Tối">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
                    </button>
                </div>
            </header>

            <!-- Nội dung Trang chủ (Tự động mở rộng & điều chỉnh padding khi thu gọn Sidebar) -->
            <div class="flex-1 overflow-y-auto custom-scroll pb-20 lg:pb-10 no-scrollbar transition-all duration-300"
                 :class="sidebarCollapsed ? 'p-4 sm:p-6 pl-4 sm:pl-6 pr-14 sm:pr-16' : 'p-4 sm:p-6 pr-14 sm:pr-16'">
                
                <div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 transition-all duration-300"
                     :class="sidebarCollapsed ? 'max-w-7xl' : 'max-w-6xl'">
                    
                    <!-- Luồng nội dung chính (2 cột) -->
                    <div class="lg:col-span-2 space-y-8 animate-fade-in-up">
                        
                        <!-- Banner Chào mừng -->
                        <div class="lms-card p-6 sm:p-8 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
                            <div class="absolute inset-0 shimmer-bg pointer-events-none opacity-50"></div>

                            <div class="flex items-center justify-between relative z-10">
                                <div class="space-y-2">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                                        <i class="fa-solid fa-bullseye text-amber-500"></i> Mục tiêu hiện tại: HSK 5 Cao cấp
                                    </div>
                                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                                        Chào Công, sẵn sàng luyện tiếng Trung hôm nay? 👋
                                    </h1>
                                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                                        Hoàn thành 1 bài thi thử và ôn tập 20 từ vựng để giữ vững chuỗi 14 ngày học liên tiếp!
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Nhiệm vụ hàng ngày -->
                        <div class="lms-card p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-[#e07a5f]"></i> Nhiệm vụ hàng ngày
                                </h2>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">2/3 Hoàn thành</span>
                            </div>

                            <div class="space-y-3">
                                <!-- Nhiệm vụ 1 -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-bold shadow-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-900 dark:text-white line-through opacity-70">Luyện tập 10 câu Pinyin</p>
                                            <p class="text-[10px] text-slate-400">Thưởng +50 điểm</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Đã xong</span>
                                </div>

                                <!-- Nhiệm vụ 2 -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-bold shadow-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-900 dark:text-white line-through opacity-70">Ôn tập 20 thẻ ghi nhớ từ vựng</p>
                                            <p class="text-[10px] text-slate-400">Thưởng +30 điểm</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Đã xong</span>
                                </div>

                                <!-- Nhiệm vụ 3 -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-[#221f1d] border border-[#e8e2d9] dark:border-[#2e2a27] shadow-xs">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-rose-400 flex items-center justify-center text-sm font-bold">
                                            <i class="fa-solid fa-fire animate-flame"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-[#e07a5f]">Làm 1 đề thi thử HSK 5</p>
                                            <p class="text-[10px] text-[#e07a5f]">Nhận thưởng +100 điểm</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.hsk-mock-exams.index') }}" class="px-3.5 py-1.5 bg-[#e07a5f] text-white rounded-lg text-xs font-bold shadow-xs hover:bg-[#c86349] transition-all btn-tactile">
                                        Làm ngay
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 📅 WIDGET 90 NGÀY GẦN NHẤT -->
                        <div x-data="{
                            selectedDay: {
                                fullDateStr: 'Chủ Nhật, 23 tháng 8, 2026',
                                isToday: true,
                                minutes: 7,
                                summary: 'Thi thử HSK 5 & Luyện tập 10 câu Pinyin',
                                hasActivity: true
                            },
                            selectDay(dateStr, isToday, minutes, summary, hasActivity) {
                                this.selectedDay = { fullDateStr: dateStr, isToday: isToday, minutes: minutes, summary: summary, hasActivity: hasActivity };
                            }
                        }" class="lms-card p-5 sm:p-6 space-y-4">
                            <!-- Title Header -->
                            <div class="space-y-1">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <i class="fa-regular fa-calendar-days text-[#e07a5f]"></i> 90 ngày gần nhất
                                </h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                                    Bấm vào một ngày để xem chi tiết. Ô càng đậm = học càng nhiều.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-stretch">
                                
                                <!-- Heatmap Grid Panel (6 cols in LG) -->
                                <div class="lg:col-span-6 flex flex-col justify-between bg-[#fcfaf7] dark:bg-[#23201e] p-3.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="space-y-2">
                                        
                                        <!-- NHÃN 3 THÁNG GẦN NHẤT CHUẨN ĐỒNG ĐỀU -->
                                        <div class="flex items-center justify-between text-[10px] font-bold text-slate-600 dark:text-slate-400 pl-6 pr-2">
                                            <span>Tháng 6</span>
                                            <span>Tháng 7</span>
                                            <span>Tháng 8</span>
                                        </div>

                                        <!-- LƯỚI HEATMAP 13 TUẦN LIỀN NHAU DI DÍT ĐỀU ĐẶN GAP-1 -->
                                        <div class="flex items-center gap-1.5">
                                            <!-- Trục Thứ trong tuần -->
                                            <div class="flex flex-col justify-between text-[9px] font-medium text-slate-400 dark:text-slate-500 h-24 pr-1 py-0.5 shrink-0">
                                                <span>T3</span>
                                                <span>T5</span>
                                                <span>T7</span>
                                            </div>

                                            <!-- Vùng chứa 13 cột di dít đều đặn không khoảng cách đại diện cho 91 ngày -->
                                            <div class="grid grid-flow-col grid-rows-7 gap-1 flex-1 overflow-x-auto no-scrollbar py-0.5">
                                                
                                                <!-- Cột 1 -->
                                                <button @click="selectDay('Thứ Hai, 25 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]" title="25 thg 5, 2026"></button>
                                                <button @click="selectDay('Thứ Ba, 26 tháng 5, 2026', false, 15, 'Học 20 Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]" title="26 thg 5, 2026: 15p"></button>
                                                <button @click="selectDay('Thứ Tư, 27 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 28 tháng 5, 2026', false, 30, 'Luyện tập Pinyin 15 câu', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 29 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 30 tháng 5, 2026', false, 45, 'Ôn tập 50 Thẻ ghi nhớ', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 31 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 2 -->
                                                <button @click="selectDay('Thứ Hai, 1 tháng 6, 2026', false, 20, 'Học Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 2 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 3 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 4 tháng 6, 2026', false, 35, 'Luyện Nghe HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 5 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 6 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 7 tháng 6, 2026', false, 15, 'Luyện phát âm Pinyin', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 3 -->
                                                <button @click="selectDay('Thứ Hai, 8 tháng 6, 2026', false, 30, 'Thi thử HSK 4 phần Đọc', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 9 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 10 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 11 tháng 6, 2026', false, 50, 'Làm 1 Đề thi HSK 5 hoàn chỉnh', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 12 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 13 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 14 tháng 6, 2026', false, 20, 'Ôn tập bộ Flashcards', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 4 -->
                                                <button @click="selectDay('Thứ Hai, 15 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 16 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 17 tháng 6, 2026', false, 15, 'Đã điểm danh hàng ngày', true)" class="w-3 h-3 rounded-[2px] bg-[#06b6d4] border border-[#a5f3fc] hover:ring-1 hover:ring-[#e07a5f]" title="17 thg 6: Đã điểm danh"></button>
                                                <button @click="selectDay('Thứ Năm, 18 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 19 tháng 6, 2026', false, 30, 'Luyện tập Ngữ pháp HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 20 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 21 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 5 -->
                                                <button @click="selectDay('Thứ Hai, 22 tháng 6, 2026', false, 15, 'Đọc Blog tiếng Trung', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 23 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 24 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 25 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 26 tháng 6, 2026', false, 40, 'Học 30 Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 27 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 28 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 6 -->
                                                <button @click="selectDay('Thứ Hai, 29 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 30 tháng 6, 2026', false, 25, 'Ôn tập Pinyin nâng cao', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 1 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 2 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 3 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 4 tháng 7, 2026', false, 15, 'Học Từ vựng chủ đề Giao tiếp', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 5 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 7 -->
                                                <button @click="selectDay('Thứ Hai, 6 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 7 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 8 tháng 7, 2026', false, 45, 'Thi thử HSK 5 phần Viết', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 9 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 10 tháng 7, 2026', false, 30, 'Luyện dịch văn bản HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 11 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 12 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 8 -->
                                                <button @click="selectDay('Thứ Hai, 13 tháng 7, 2026', false, 25, 'Học 20 Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 14 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 15 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 16 tháng 7, 2026', false, 15, 'Luyện phát âm chuẩn', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 17 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 18 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 19 tháng 7, 2026', false, 50, 'Thi thử Đề HSK 5 Số 02', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 9 -->
                                                <button @click="selectDay('Thứ Hai, 20 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 21 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 22 tháng 7, 2026', false, 30, 'Luyện tập 15 câu Pinyin', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 23 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 24 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 25 tháng 7, 2026', false, 15, 'Học Thẻ từ vựng', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 26 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 10 -->
                                                <button @click="selectDay('Thứ Hai, 27 tháng 7, 2026', false, 15, 'Ôn tập Ngữ pháp', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 28 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 29 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 30 tháng 7, 2026', false, 40, 'Thi thử Đọc HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 31 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 1 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]" title="1 thg 8, 2026: Chưa học"></button>
                                                <button @click="selectDay('Chủ Nhật, 2 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 11 -->
                                                <button @click="selectDay('Thứ Hai, 3 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 4 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 5 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 6 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 7 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 8 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 9 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 12 -->
                                                <button @click="selectDay('Thứ Hai, 10 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 11 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 12 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 13 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 14 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 15 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 16 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 13 (Hôm nay 23/8/2026 - Tile cuối cùng viền đỏ cờ) -->
                                                <button @click="selectDay('Thứ Hai, 17 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 18 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 19 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 20 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 21 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 22 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 23 tháng 8, 2026', true, 7, 'Thi thử HSK 5 & Luyện tập 10 câu Pinyin', true)" class="w-3 h-3 rounded-[2px] bg-white dark:bg-[#23201e] border-2 border-[#e07a5f] hover:ring-2 hover:ring-[#e07a5f]/40" title="Hôm nay 23 thg 8: 7p"></button>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Legend Bar -->
                                    <div class="pt-2 flex flex-wrap items-center justify-between text-[10px] font-normal text-slate-500 dark:text-slate-400 gap-1.5 border-t border-[#e8e2d9] dark:border-[#2d2926] mt-2">
                                        <div class="flex items-center gap-1">
                                            <span>Ít</span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926]"></span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e]"></span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f]"></span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#10b981] dark:bg-[#34d399]"></span>
                                            <span>Nhiều</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-[2px] border border-[#06b6d4]"></span> Đã điểm danh</span>
                                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-[2px] bg-[#06b6d4]"></span> Freeze</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Middle Panel: Selected Day Activity Card (3 cols in LG) -->
                                <div class="lg:col-span-3 flex flex-col justify-between p-3.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="space-y-2">
                                        <div class="flex items-start justify-between gap-1.5">
                                            <span class="text-xs font-bold text-slate-800 dark:text-white leading-snug" x-text="selectedDay.fullDateStr">Chủ Nhật, 23 tháng 8, 2026</span>
                                            <template x-if="selectedDay.isToday">
                                                <span class="px-2 py-0.5 rounded-full border border-[#e07a5f] text-[#e07a5f] font-semibold text-[10px] shrink-0">Hôm nay</span>
                                            </template>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal leading-relaxed" x-text="selectedDay.hasActivity ? selectedDay.summary : 'Không có hoạt động nào ngày này'">
                                            Không có hoạt động nào ngày này
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1 text-xs text-slate-500 font-medium pt-2 mt-3 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> <span x-text="selectedDay.minutes + 'p'">7p</span>
                                    </div>
                                </div>

                                <!-- Right Panel: TỔNG KẾT 90 NGÀY Card (3 cols in LG) -->
                                <div class="lg:col-span-3 flex flex-col justify-between p-3.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="space-y-2.5">
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TỔNG KẾT 90 NGÀY</div>
                                        
                                        <div class="space-y-2 text-xs">
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-solid fa-bolt-lightning text-emerald-500 text-[11px]"></i> Ngày có học
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">1 / 90</span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-regular fa-clock text-[#0284c7] text-[11px]"></i> Thời gian học
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">2p</span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-solid fa-chart-line text-[#e07a5f] text-[11px]"></i> Chuỗi dài nhất
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">1 ngày</span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-solid fa-feather-pointed text-amber-500 text-[11px]"></i> Hoạt động chính
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">Chính tả</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- 🏆 CỘT BẢNG XẾP HẠNG CHI TIẾT (BỘ LỌC CÙNG 1 DÒNG DUY NHẤT KHÔNG XUỐNG DÒNG) -->
                    <div class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                        
                        <!-- CARD BẢNG XẾP HẠNG -->
                        <div class="lms-card p-5 sm:p-6 space-y-4">
                            
                            <!-- Header Bảng xếp hạng -->
                            <div class="space-y-1">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <i class="fa-solid fa-trophy text-amber-500 text-lg"></i> Bảng xếp hạng
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Xem bạn đang đứng thứ mấy so với các bạn khác</p>
                                <p class="text-[11px] font-semibold text-slate-400">17 thg 8 → 23 thg 8</p>
                            </div>

                            <!-- BỘ LỌC CÙNG 1 DÒNG DUY NHẤT (FLEX-NOWRAP, KÍCH THƯỚC COMPACT PHÙ HỢP 100%) -->
                            <div class="flex items-center justify-between gap-1.5 flex-nowrap overflow-x-auto no-scrollbar border-b border-[#e8e2d9] dark:border-[#2a2624] pb-3">
                                <!-- Filter 1: Thời gian -->
                                <div class="flex bg-[#fcfaf7] dark:bg-[#23201e] p-0.5 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-semibold shrink-0">
                                    <button @click="rankTab = 'week'" :class="rankTab === 'week' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2.5 py-1 rounded-full transition-all">Tuần này</button>
                                    <button @click="rankTab = 'month'" :class="rankTab === 'month' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2.5 py-1 rounded-full transition-all">Tháng này</button>
                                </div>

                                <!-- Filter 2: Metric -->
                                <div class="flex bg-[#fcfaf7] dark:bg-[#23201e] p-0.5 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-semibold shrink-0">
                                    <button @click="rankMetric = 'xp'" :class="rankMetric === 'xp' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2 py-1 rounded-full transition-all flex items-center gap-1">✨ XP</button>
                                    <button @click="rankMetric = 'time'" :class="rankMetric === 'time' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2 py-1 rounded-full transition-all flex items-center gap-1"><i class="fa-regular fa-clock"></i> Thời gian học</button>
                                </div>
                            </div>

                            <!-- CARD VỊ TRÍ HIỆN TẠI NỔI BẬT CỦA BẠN -->
                            <div class="p-3 rounded-2xl bg-[#fff7f4] dark:bg-[#2e1d1b] border-2 border-[#e07a5f] shadow-xs relative overflow-hidden flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 w-9 text-center shrink-0">#3854</span>
                                    <div class="relative shrink-0">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-9 h-9 rounded-full object-cover border-2 border-[#e07a5f]">
                                        <span class="absolute -bottom-1 -right-1 bg-slate-800 text-white text-[9px] font-semibold px-1 rounded-full border border-white">Lv.1</span>
                                    </div>
                                    <div class="truncate">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-bold text-slate-800 dark:text-white truncate">Vũ Thành Công</span>
                                            <span class="px-1.5 py-0.2 bg-[#e07a5f] text-white text-[9px] font-bold rounded-md uppercase tracking-wider">YOU</span>
                                        </div>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Lv 1</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-[#e07a5f] dark:text-[#f4978e] shrink-0 flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[#0284c7]"></i> 7p
                                </span>
                            </div>

                            <!-- DANH SÁCH BẢNG XẾP HẠNG THÀNH VIÊN (#1 → #8) -->
                            <div class="space-y-2">
                                
                                <!-- Hạng 1 (Thẻ Vàng) -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#fffbeb] dark:bg-[#23201e] border border-[#f59e0b]/80 dark:border-[#2d2926] hover:shadow-xs transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-[#d97706] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">#1</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-9 h-9 rounded-full object-cover border border-[#f59e0b]">
                                            <span class="absolute -bottom-1 -right-1 bg-purple-600 text-white text-[9px] font-bold px-1 rounded-full border border-white">Lv.63</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate zh-text">胡佑芳 🌿</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">Lv 63</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-white shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 25g 24p
                                    </span>
                                </div>

                                <!-- Hạng 2 (Thẻ Bạc) -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#f0f9ff] dark:bg-[#23201e] border border-[#38bdf8]/80 dark:border-[#2d2926] hover:shadow-xs transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-[#0284c7] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">#2</span>
                                        <div class="relative shrink-0">
                                            <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold text-xs">T</div>
                                            <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white text-[9px] font-bold px-1 rounded-full border border-white">Lv.23</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate">Thảo Trần</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">Lv 23</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-white shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 20g 0p
                                    </span>
                                </div>

                                <!-- Hạng 3 (Thẻ Đồng) -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#fff7ed] dark:bg-[#23201e] border border-[#f97316]/80 dark:border-[#2d2926] hover:shadow-xs transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-[#c2410c] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">#3</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-9 h-9 rounded-full object-cover border border-[#ea580c]">
                                            <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white text-[9px] font-bold px-1 rounded-full border border-white">Lv.21</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate">Nhi Trần</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">Lv 21</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-white shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 18g 31p
                                    </span>
                                </div>

                                <!-- Hạng 4 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#4</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-8 h-8 rounded-full object-cover">
                                            <span class="absolute -bottom-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.11</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Vũ Thị oanh</p>
                                            <p class="text-[10px] text-slate-400">Lv 11</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 16g 11p
                                    </span>
                                </div>

                                <!-- Hạng 5 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#5</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-8 h-8 rounded-full object-cover">
                                            <span class="absolute -bottom-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.12</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Xiaoyu</p>
                                            <p class="text-[10px] text-slate-400">Lv 12</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 14g 38p
                                    </span>
                                </div>

                                <!-- Hạng 6 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#6</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&q=80" class="w-8 h-8 rounded-full object-cover">
                                            <span class="absolute -bottom-1 -right-1 bg-purple-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.49</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Cù Dung 🧑‍🎓</p>
                                            <p class="text-[10px] text-slate-400">Lv 49</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 13g 55p
                                    </span>
                                </div>

                                <!-- Hạng 7 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#7</span>
                                        <div class="relative shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold text-xs">Vi</div>
                                            <span class="absolute -bottom-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.11</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Vi Vi</p>
                                            <p class="text-[10px] text-slate-400">Lv 11</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 13g 16p
                                    </span>
                                </div>

                                <!-- Hạng 8 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#8</span>
                                        <div class="relative shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-slate-300 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-user"></i></div>
                                            <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.27</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Hoa Trần</p>
                                            <p class="text-[10px] text-slate-400">Lv 27</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 13g 3p
                                    </span>
                                </div>

                            </div>

                        </div>

                        <!-- Widget Thẻ từ vựng mỗi ngày -->
                        <div x-data="{ playing: false }" class="lms-card p-6 space-y-3 relative group">
                            <div class="flex items-center justify-between text-xs text-[#e07a5f] font-bold">
                                <span>Từ vựng hôm nay</span>
                                <span class="px-2 py-0.5 bg-[#fff2ee] dark:bg-slate-800 rounded text-[10px] font-bold">HSK 5</span>
                            </div>

                            <div class="py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div>
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white zh-text">坚持</span>
                                        <span class="text-sm font-bold text-[#e07a5f]">jiān chí</span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-1">Động từ: Kiên trì, giữ vững mục tiêu</p>
                                </div>
                                <button @click="playing = true; setTimeout(() => playing = false, 1200)" class="w-10 h-10 rounded-full bg-[#fff2ee] dark:bg-slate-800 text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white flex items-center justify-center transition-all btn-tactile shadow-xs" title="Nghe phát âm">
                                    <i class="fa-solid" :class="playing ? 'fa-volume-high animate-bounce' : 'fa-volume-low'"></i>
                                </button>
                            </div>

                            <div class="p-3.5 bg-[#faf6f2] dark:bg-slate-800/80 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-xs">
                                <p class="text-slate-900 dark:text-white zh-text font-medium leading-relaxed">
                                    例句: 只要坚持努力，就一定能通过 HSK 5 级考试。
                                </p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-normal">
                                    (Chỉ cần kiên trì nỗ lực thì nhất định sẽ đỗ HSK 5).
                                </p>
                            </div>
                        </div>

                        <!-- Widget Kết nối Mạng xã hội XiaoMu (5 Nền tảng: FB, YT, Insta, TikTok, Zalo) -->
                        <div class="lms-card p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <i class="fa-solid fa-globe text-[#e07a5f]"></i> Kết nối cùng XiaoMu
                                </h3>
                                <span class="text-[10px] font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-slate-800 px-2 py-0.5 rounded">Cộng đồng</span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal leading-relaxed">
                                Theo dõi các kênh truyền thông chính thức để nhận mẹo thi HSK và tài liệu tiếng Trung mỗi ngày!
                            </p>

                            <div class="grid grid-cols-2 gap-2.5 pt-1">
                                <!-- Facebook -->
                                <a href="https://facebook.com" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile">
                                    <div class="w-7 h-7 rounded-lg bg-[#1877f2] text-white flex items-center justify-center text-xs shrink-0">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">Facebook</p>
                                        <p class="text-[10px] text-slate-400 truncate">120K Follow</p>
                                    </div>
                                </a>

                                <!-- YouTube -->
                                <a href="https://youtube.com" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile">
                                    <div class="w-7 h-7 rounded-lg bg-[#FF0000] text-white flex items-center justify-center text-xs shrink-0">
                                        <i class="fa-brands fa-youtube"></i>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">YouTube</p>
                                        <p class="text-[10px] text-slate-400 truncate">Bài giảng HSK</p>
                                    </div>
                                </a>

                                <!-- Instagram -->
                                <a href="https://instagram.com" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile">
                                    <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white flex items-center justify-center text-xs shrink-0">
                                        <i class="fa-brands fa-instagram"></i>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">Instagram</p>
                                        <p class="text-[10px] text-slate-400 truncate">Hình ảnh & Story</p>
                                    </div>
                                </a>

                                <!-- TikTok -->
                                <a href="https://tiktok.com" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile">
                                    <div class="w-7 h-7 rounded-lg bg-black text-white flex items-center justify-center text-xs shrink-0">
                                        <i class="fa-brands fa-tiktok"></i>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">TikTok</p>
                                        <p class="text-[10px] text-slate-400 truncate">Từ vựng 60s</p>
                                    </div>
                                </a>

                                <!-- Zalo Official -->
                                <a href="https://zalo.me" target="_blank" class="col-span-2 flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile">
                                    <div class="w-7 h-7 rounded-lg bg-[#0068ff] text-white font-extrabold flex items-center justify-center text-[10px] shrink-0">
                                        Zalo
                                    </div>
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">Zalo Official Account</p>
                                        <p class="text-[10px] text-slate-400 truncate">Hỗ trợ tư vấn & nhận tài liệu trực tiếp</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Bottom Bar trên điện thoại -->
            <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white dark:bg-[#141211] border-t border-[#e8e2d9] dark:border-[#262220] flex items-center justify-around py-2.5 px-2 z-30 shadow-md">
                <a href="{{ url('/demo-ui') }}" class="flex flex-col items-center gap-0.5 text-[#e07a5f] btn-tactile">
                    <i class="fa-solid fa-house text-base"></i>
                    <span class="text-[10px] font-bold">Trang chủ</span>
                </a>
                <a href="{{ url('/demo-courses') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-book-open text-base"></i>
                    <span class="text-[10px] font-medium">Khóa học</span>
                </a>
                <a href="{{ url('/demo-exams') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-file-pen text-base"></i>
                    <span class="text-[10px] font-medium">Luyện thi</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-user text-base"></i>
                    <span class="text-[10px] font-medium">Cá nhân</span>
                </a>
            </div>

        </main>
    </div>

    <!-- FLOATING SOCIAL SHARE DOCK (THU VÀO MỞ RA CỰC KỲ MƯỢT MÀ BẰNG CSS GRID ROWS 60FPS) -->
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-50 flex flex-col items-end">
        <div class="bg-white/90 dark:bg-[#181615]/90 backdrop-blur-xl border-l border-y border-[#e8e2d9]/90 dark:border-[#2d2926] rounded-l-2xl rounded-r-none pl-2 pr-1.5 py-3 shadow-[0_12px_36px_-6px_rgba(0,0,0,0.15)] dark:shadow-[0_12px_36px_-6px_rgba(0,0,0,0.6)] ring-1 ring-black/5 dark:ring-white/5 flex flex-col items-center transition-all duration-300">
            
            <!-- Nút Nguồn Terracotta Share (TAY CẦM BẤM BẬT/TẮT - BẤM VÀO BẤT KỲ ĐÂU TRÊN NÚT ĐỀU THU PHÓNG) -->
            <button @click="socialDockExpanded = !socialDockExpanded" 
                    type="button"
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white flex items-center justify-center text-sm transition-all btn-tactile shadow-md shadow-[#e07a5f]/30 hover:scale-105 group relative cursor-pointer z-10 select-none" 
                    :title="socialDockExpanded ? 'Thu gọn Mạng xã hội' : 'Mở rộng Mạng xã hội'">
                <i class="fa-solid pointer-events-none transition-transform duration-300 ease-out" :class="socialDockExpanded ? 'fa-share-nodes' : 'fa-chevron-left'"></i>
                <span class="absolute right-14 px-3 py-1.5 rounded-xl bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-xl backdrop-blur-sm opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-200 whitespace-nowrap" x-text="socialDockExpanded ? 'Thu gọn' : 'Mở rộng Mạng xã hội'"></span>
            </button>

            <!-- Đường Kẻ Phân Cách Ngang -->
            <div class="w-6 border-b border-[#e8e2d9] dark:border-[#2d2926] transition-all duration-300 ease-in-out"
                 :class="socialDockExpanded ? 'opacity-100 my-2.5 scale-y-100' : 'opacity-0 my-0 scale-y-0 h-0 border-transparent'"></div>

            <!-- Cột Dọc 5 Icon Mạng xã hội (ANIMATION 60FPS CSS GRID ROWS CHUẨN XỊN SILKY SMOOTH) -->
            <div class="grid transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
                 :class="socialDockExpanded ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                <div class="overflow-hidden flex flex-col items-center gap-2.5">
                    
                    <!-- 1. Facebook -->
                    <a href="https://facebook.com" target="_blank" class="w-10 h-10 rounded-full bg-[#1877f2] text-white flex items-center justify-center text-sm hover:scale-110 hover:shadow-lg hover:shadow-[#1877f2]/30 transition-all btn-tactile group relative" title="Facebook XiaoMu">
                        <i class="fa-brands fa-facebook-f"></i>
                        <span class="absolute right-14 px-3 py-1.5 rounded-xl bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-xl backdrop-blur-sm opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-200 whitespace-nowrap">Facebook Fanpage</span>
                    </a>

                    <!-- 2. YouTube -->
                    <a href="https://youtube.com" target="_blank" class="w-10 h-10 rounded-full bg-[#FF0000] text-white flex items-center justify-center text-sm hover:scale-110 hover:shadow-lg hover:shadow-[#FF0000]/30 transition-all btn-tactile group relative" title="YouTube Bài giảng">
                        <i class="fa-brands fa-youtube"></i>
                        <span class="absolute right-14 px-3 py-1.5 rounded-xl bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-xl backdrop-blur-sm opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-200 whitespace-nowrap">Kênh YouTube Bài giảng</span>
                    </a>

                    <!-- 3. Instagram -->
                    <a href="https://instagram.com" target="_blank" class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white flex items-center justify-center text-sm hover:scale-110 hover:shadow-lg hover:shadow-rose-500/30 transition-all btn-tactile group relative" title="Instagram XiaoMu">
                        <i class="fa-brands fa-instagram"></i>
                        <span class="absolute right-14 px-3 py-1.5 rounded-xl bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-xl backdrop-blur-sm opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-200 whitespace-nowrap">Instagram Story</span>
                    </a>

                    <!-- 4. TikTok -->
                    <a href="https://tiktok.com" target="_blank" class="w-10 h-10 rounded-full bg-[#111111] dark:bg-[#222222] text-white border border-slate-700/30 flex items-center justify-center text-sm hover:scale-110 hover:shadow-lg hover:shadow-black/40 transition-all btn-tactile group relative" title="TikTok Từ vựng 60s">
                        <i class="fa-brands fa-tiktok"></i>
                        <span class="absolute right-14 px-3 py-1.5 rounded-xl bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-xl backdrop-blur-sm opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-200 whitespace-nowrap">TikTok Từ vựng 60s</span>
                    </a>

                    <!-- 5. Zalo Official -->
                    <a href="https://zalo.me" target="_blank" class="w-10 h-10 rounded-full bg-[#0068ff] text-white font-extrabold flex items-center justify-center text-xs hover:scale-110 hover:shadow-lg hover:shadow-[#0068ff]/30 transition-all btn-tactile group relative" title="Zalo Official">
                        Zalo
                        <span class="absolute right-14 px-3 py-1.5 rounded-xl bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 text-[11px] font-bold shadow-xl backdrop-blur-sm opacity-0 pointer-events-none group-hover:opacity-100 group-hover:-translate-x-1 transition-all duration-200 whitespace-nowrap">Zalo OA Hỗ trợ</span>
                    </a>

                </div>
            </div>

        </div>
    </div>

</body>
</html>
