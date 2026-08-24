<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ sidebarOpen: false, sidebarCollapsed: false, isLoggedIn: true, langOpen: false, currentLang: 'Việt Nam', darkMode: false, score: 80, currentQuestion: 1, selectedAnswer: null, isAnswered: false, socialDockExpanded: true, questions: [
    { pinyin: 'nǐ hǎo', hanzi: '你好', options: ['nǐ hǎo', 'ní hǎo', 'nì hāo', 'nǐ hāo'], correct: 0 },
    { pinyin: 'xièxie', hanzi: '谢谢', options: ['xièxie', 'xiēxie', 'xiěxie', 'xièxiē'], correct: 0 }
] }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luyện nghe & Nhận diện Pinyin - Tiếng Trung XiaoMu LMS</title>
    <meta name="description" content="Luyện tập phản xạ nghe phiên âm Pinyin và chọn Thanh điệu chuẩn xác.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --brand-primary: #e07a5f; --brand-primary-hover: #c86349; --brand-bg: #f8f6f3; --card-border: #e8e2d9; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: var(--brand-bg); color: #1e1b18; -webkit-font-smoothing: antialiased; }
        .zh-text { font-family: 'Noto Sans SC', sans-serif; }
        .lms-card { background-color: #ffffff; border: 1px solid var(--card-border); border-radius: 1.25rem; box-shadow: 0 4px 16px -2px rgba(30, 27, 24, 0.03); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
        .dark .lms-card { background-color: #181615; border-color: #2a2624; box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.5); }
        .btn-tactile { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        .btn-tactile:active { transform: scale(0.96); }
        .nav-item-active { background-color: #fff2ee; color: #e07a5f; font-weight: 700; }
        .dark .nav-item-active { background-color: #2a221f; color: #f4978e; }
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
                        <span class="text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">Tiếng Trung LMS</span>
                    </div>
                </a>

                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Menu chính -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5 no-scrollbar">
                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Học tập</div>
                    <div class="space-y-1">
                        <a href="{{ url('/demo-ui') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Trang chủ' : ''">
                            <i class="fa-solid fa-house text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Trang chủ</span>
                        </a>
                        <a href="{{ url('/demo-courses') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Khóa học HSK' : ''">
                            <i class="fa-solid fa-book-open text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Khóa học HSK</span>
                        </a>
                        <a href="{{ url('/demo-exams') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện thi HSK' : ''">
                            <i class="fa-solid fa-file-pen text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện thi HSK</span>
                        </a>
                        <a href="{{ url('/demo-flashcards') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Thẻ ghi nhớ' : ''">
                            <i class="fa-solid fa-layer-group text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Thẻ ghi nhớ</span>
                        </a>
                        <a href="{{ url('/demo-etymology') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Chiết tự chữ Hán' : ''">
                            <i class="fa-solid fa-puzzle-piece text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Chiết tự chữ Hán</span>
                        </a>
                        <a href="{{ url('/demo-pinyin-chart') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Bảng Pinyin' : ''">
                            <i class="fa-solid fa-table-cells text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Bảng Pinyin</span>
                        </a>
                        <a href="{{ url('/demo-pinyin-practice') }}" class="flex items-center gap-3 rounded-xl text-sm nav-item-active transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện tập Pinyin' : ''">
                            <i class="fa-solid fa-headset text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện tập Pinyin</span>
                        </a>
                    </div>
                </div>
                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Hệ thống</div>
                    <div class="space-y-1">
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Từ vựng của tôi' : ''">
                            <i class="fa-solid fa-font text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Từ vựng của tôi</span>
                        </a>
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-[#e07a5f] dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Liên hệ hỗ trợ' : ''">
                            <i class="fa-solid fa-envelope text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Liên hệ hỗ trợ</span>
                        </a>
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Cài đặt tài khoản' : ''">
                            <i class="fa-solid fa-gear text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Cài đặt tài khoản</span>
                        </a>
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

            <!-- Chân trang Hồ sơ cá nhân -->
            <div class="border-t border-[#e8e2d9] dark:border-[#262220] bg-[#faf6f2] dark:bg-slate-900/60 shrink-0 transition-all">
                <div x-show="isLoggedIn" class="p-3">
                    <a href="#" class="flex items-center gap-3 min-w-0 group" :class="sidebarCollapsed ? 'justify-center' : ''">
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

                <div x-show="!isLoggedIn" class="p-3 w-full">
                    <a href="{{ url('/login') }}" @click.prevent="isLoggedIn = true" class="w-full py-2.5 bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold rounded-xl text-center shadow-md transition-all btn-tactile flex items-center justify-center gap-2" :title="sidebarCollapsed ? 'Đăng nhập' : ''">
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        <span x-show="!sidebarCollapsed">Đăng nhập</span>
                    </a>
                </div>

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

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <!-- TOPBAR HEADER -->
            <header class="h-20 bg-white dark:bg-[#141211] border-b border-[#e8e2d9] dark:border-[#262220] flex items-center justify-between px-4 sm:px-6 pr-14 sm:pr-16 shrink-0 gap-4 transition-colors">
                <div class="flex items-center gap-3 flex-1 max-w-lg">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile" title="Thu gọn / Mở rộng Sidebar Navigation">
                        <i class="fa-solid text-base transition-transform duration-300" :class="sidebarCollapsed ? 'fa-indent rotate-180 text-[#e07a5f]' : 'fa-bars-staggered'"></i>
                    </button>

                    <div class="relative w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" placeholder="Tìm kiếm bài luyện âm Pinyin..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    <div class="relative">
                        <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-slate-300 transition-all btn-tactile">
                            <template x-if="currentLang === 'Việt Nam'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#da251d"/><polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/></svg>
                            </template>
                            <template x-if="currentLang === '中文'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#ee1c25"/><polygon points="5,3 5.6,4.8 7.4,4.8 5.9,5.9 6.5,7.7 5,6.6 3.5,7.7 4.1,5.9 2.6,4.8 4.4,4.8" fill="#ffde00"/></svg>
                            </template>
                            <template x-if="currentLang === 'English'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30"><clipPath id="s_uk_pr6"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="t_uk_pr6"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath><g clip-path="url(#s_uk_pr6)"><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_pr6)"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/></g></svg>
                            </template>
                            <span class="hidden md:inline font-bold" x-text="currentLang">Việt Nam</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div x-show="langOpen" class="absolute right-0 mt-2 w-40 rounded-2xl bg-white dark:bg-[#1c1917] border border-[#e8e2d9] dark:border-[#2a2624] shadow-xl py-1.5 z-50 text-xs" style="display: none;">
                            <button @click="currentLang = 'Việt Nam'; langOpen = false" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200"><span>🇻🇳</span> <span>Việt Nam</span></button>
                            <button @click="currentLang = '中文'; langOpen = false" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200 zh-text"><span>🇨🇳</span> <span>中文 (简体)</span></button>
                            <button @click="currentLang = 'English'; langOpen = false" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] font-bold text-slate-700 dark:text-slate-200"><span>🇬🇧</span> <span>English</span></button>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-1.5 bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] px-3 py-1.5 rounded-xl shadow-xs">
                        <i class="fa-solid fa-fire text-[#e07a5f] text-sm animate-pulse"></i>
                        <span class="text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">14 Ngày học</span>
                    </div>

                    <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
                    </button>
                </div>
            </header>

            <!-- Page Body (Tự động mở rộng & điều chỉnh padding khi thu gọn Sidebar) -->
            <div class="flex-1 overflow-y-auto no-scrollbar transition-all duration-300 space-y-6"
                 :class="sidebarCollapsed ? 'p-4 sm:p-6 pl-4 sm:pl-6 pr-14 sm:pr-16' : 'p-4 sm:p-6 pr-14 sm:pr-16'">
                
                <div class="mx-auto space-y-6 transition-all duration-300"
                     :class="sidebarCollapsed ? 'max-w-4xl' : 'max-w-3xl'">

                    <!-- Banner Tiêu đề Trang (Bám sát Demo-UI) -->
                    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                            <div class="space-y-1.5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                                    <i class="fa-solid fa-headset text-[#e07a5f]"></i> Luyện phản xạ âm thanh Pinyin
                                </div>
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                                    Luyện tập Phản xạ Pinyin
                                </h1>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                                    Nghe phát âm chuẩn giọng Bắc Kinh và chọn đáp án phiên âm có Thanh điệu đúng.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar & Score -->
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-500">Câu hỏi <span x-text="currentQuestion">1</span>/10</span>
                            <div class="w-32 h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full rounded-full bg-[#e07a5f]" style="width: 10%;"></div>
                            </div>
                        </div>

                        <div class="px-3.5 py-1.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 text-xs font-bold">
                            <i class="fa-solid fa-trophy mr-1"></i>Điểm: <span x-text="score">80</span>
                        </div>
                    </div>

                    <!-- Practice Question Card -->
                    <div class="lms-card p-6 sm:p-8 space-y-6 text-center">
                        <div class="space-y-3">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nghe mẫu phát âm</div>
                            
                            <button onclick="alert('Đang phát đoạn âm Pinyin bài luyện tập...')" class="w-20 h-20 rounded-full bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white text-2xl flex items-center justify-center btn-tactile shadow-lg shadow-[#e07a5f]/30 mx-auto hover:scale-105">
                                <i class="fa-solid fa-volume-high animate-pulse"></i>
                            </button>

                            <div class="text-2xl font-bold zh-text text-slate-900 dark:text-white pt-2">你好</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto pt-2">
                            <template x-for="(opt, idx) in questions[0].options" :key="idx">
                                <button @click="selectedAnswer = idx; isAnswered = true" :class="selectedAnswer === idx ? (idx === 0 ? 'bg-emerald-600 text-white font-bold border-emerald-600' : 'bg-red-500 text-white font-bold border-red-500') : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-800 dark:text-slate-200 border-[#e8e2d9] dark:border-[#2d2926]'" class="p-4 rounded-2xl border text-base font-bold btn-tactile shadow-xs flex items-center justify-center gap-2">
                                    <span x-text="opt"></span>
                                    <i x-show="selectedAnswer === idx && idx === 0" class="fa-solid fa-circle-check text-white"></i>
                                </button>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <!-- FLOATING SOCIAL SHARE DOCK -->
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-50 flex flex-col items-end">
        <div class="bg-white/90 dark:bg-[#181615]/90 backdrop-blur-xl border-l border-y border-[#e8e2d9]/90 dark:border-[#2d2926] rounded-l-2xl rounded-r-none pl-2 pr-1.5 py-3 shadow-xl ring-1 ring-black/5 flex flex-col items-center">
            <button @click="socialDockExpanded = !socialDockExpanded" type="button" class="w-10 h-10 rounded-full bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white flex items-center justify-center text-sm transition-all btn-tactile shadow-md shadow-[#e07a5f]/30 hover:scale-105 group relative cursor-pointer z-10 select-none">
                <i class="fa-solid pointer-events-none transition-transform duration-300 ease-out" :class="socialDockExpanded ? 'fa-share-nodes' : 'fa-chevron-left'"></i>
            </button>
            <div class="w-6 border-b border-[#e8e2d9] dark:border-[#2d2926] transition-all duration-300 ease-in-out" :class="socialDockExpanded ? 'opacity-100 my-2.5 scale-y-100' : 'opacity-0 my-0 scale-y-0 h-0 border-transparent'"></div>
            <div class="grid transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]" :class="socialDockExpanded ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                <div class="overflow-hidden flex flex-col items-center gap-2.5">
                    <a href="https://facebook.com" target="_blank" class="w-10 h-10 rounded-full bg-[#1877f2] text-white flex items-center justify-center text-sm hover:scale-110 transition-all btn-tactile"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://youtube.com" target="_blank" class="w-10 h-10 rounded-full bg-[#FF0000] text-white flex items-center justify-center text-sm hover:scale-110 transition-all btn-tactile"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://instagram.com" target="_blank" class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white flex items-center justify-center text-sm hover:scale-110 transition-all btn-tactile"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://tiktok.com" target="_blank" class="w-10 h-10 rounded-full bg-[#111111] dark:bg-[#222222] text-white border border-slate-700/30 flex items-center justify-center text-sm hover:scale-110 transition-all btn-tactile"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://zalo.me" target="_blank" class="w-10 h-10 rounded-full bg-[#0068ff] text-white font-extrabold flex items-center justify-center text-xs hover:scale-110 transition-all btn-tactile">Zalo</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
