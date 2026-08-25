<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ sidebarOpen: false, sidebarCollapsed: false, isLoggedIn: true, langOpen: false, currentLang: 'Việt Nam', darkMode: false, levelFilter: 'all', searchKeyword: '', selectedCourse: null, socialDockExpanded: true, lessons: [
    { id: 1, code: 'H1L01', hanzi: '你好！', vi: 'Xin chào', countVocab: 7, isUnlocked: true },
    { id: 2, code: 'H1L02', hanzi: '谢谢你！', vi: 'Cảm ơn anh!', countVocab: 9, isUnlocked: true },
    { id: 3, code: 'H1L03', hanzi: '你叫什么名字？', vi: 'Cô tên gì?', countVocab: 10, isUnlocked: true },
    { id: 4, code: 'H1L04', hanzi: '她是我的汉语老师。', vi: 'Cô ấy là giáo viên tiếng Trung của tôi.', countVocab: 12, isUnlocked: true },
    { id: 5, code: 'H1L05', hanzi: '她女儿今年二十岁。', vi: 'Con gái cô ấy năm nay 20 tuổi.', countVocab: 11, isUnlocked: true },
    { id: 6, code: 'H1L06', hanzi: '我会说汉语。', vi: 'Tôi biết nói tiếng Trung.', countVocab: 10, isUnlocked: true },
    { id: 7, code: 'H1L07', hanzi: '今天几号？', vi: 'Hôm nay ngày mấy?', countVocab: 8, isUnlocked: true }
] }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa học HSK - Tiếng Trung XIAOMU LMS</title>
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
                    <img src="{{ asset('logo.png') }}" alt="XIAOMU Logo" class="w-10 h-10 rounded-full object-cover shrink-0 group-hover:scale-105 transition-transform duration-200">
                    <div x-show="!sidebarCollapsed" class="flex flex-col min-w-0 transition-opacity duration-200">
                        <span class="font-bold text-base tracking-tight text-slate-900 dark:text-white leading-none">XIAOMU</span>
                        <span class="text-xs font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">Tiếng Trung LMS</span>
                    </div>
                </a>

                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Menu chính -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5 no-scrollbar">
                <div>
                    <div x-show="!sidebarCollapsed" class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Học tập</div>
                    <div class="space-y-1">
                        <a href="{{ url('/demo-ui') }}" class="group flex items-center gap-3 rounded-xl text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Trang chủ' : ''">
                            <i class="fa-solid fa-house text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Trang chủ</span>
                        </a>
                        <a href="{{ url('/demo-courses') }}" @click="selectedCourse = null" class="flex items-center gap-3 rounded-xl text-xs nav-item-active transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Khóa học HSK' : ''">
                            <i class="fa-solid fa-book-open text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Khóa học HSK</span>
                        </a>
                        <a href="{{ url('/demo-exams') }}" class="group flex items-center gap-3 rounded-xl text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện thi HSK' : ''">
                            <i class="fa-solid fa-file-pen text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện thi HSK</span>
                        </a>
                        <a href="{{ url('/demo-flashcards') }}" class="group flex items-center gap-3 rounded-xl text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Thẻ ghi nhớ' : ''">
                            <i class="fa-solid fa-layer-group text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Thẻ ghi nhớ</span>
                        </a>
                        <a href="{{ url('/demo-etymology') }}" class="group flex items-center gap-3 rounded-xl text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Chiết tự chữ Hán' : ''">
                            <i class="fa-solid fa-puzzle-piece text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Chiết tự chữ Hán</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Chân trang Hồ sơ cá nhân -->
            <div class="border-t border-[#e8e2d9] dark:border-[#262220] bg-[#faf6f2] dark:bg-slate-900/60 shrink-0 transition-all">
                <div x-show="isLoggedIn" class="p-3">
                    <a href="#" class="flex items-center gap-3 min-w-0 group" :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="relative shrink-0">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 group-hover:border-[#e07a5f] transition-colors">
                            <span class="absolute -bottom-1 -right-1 bg-slate-800 dark:bg-slate-900 text-slate-200 text-[9px] font-bold px-1 py-0.2 rounded-full border border-slate-600 leading-none">Lv.1</span>
                        </div>
                        <div x-show="!sidebarCollapsed" class="truncate min-w-0">
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors">Vũ Thành Công</p>
                            <p class="text-xs text-slate-400 font-semibold truncate">Hồ sơ</p>
                        </div>
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
                        <input type="text" x-model="searchKeyword" placeholder="Tìm kiếm khóa học HSK..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    <!-- Dynamic Language Selector Dropdown -->
                    <div class="relative">
                        <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-slate-300 transition-all btn-tactile">
                            <template x-if="currentLang === 'Việt Nam'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#da251d"/><polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/></svg>
                            </template>
                            <template x-if="currentLang === '中文'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20"><rect width="30" height="20" fill="#ee1c25"/><polygon points="5,3 5.6,4.8 7.4,4.8 5.9,5.9 6.5,7.7 5,6.6 3.5,7.7 4.1,5.9 2.6,4.8 4.4,4.8" fill="#ffde00"/></svg>
                            </template>
                            <template x-if="currentLang === 'English'">
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30"><clipPath id="s_uk_c14"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="t_uk_c14"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath><g clip-path="url(#s_uk_c14)"><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_c14)"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/></g></svg>
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

                    <!-- Streak Badge -->
                    <div class="hidden sm:flex items-center gap-1.5 bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] px-3 py-1.5 rounded-xl shadow-xs">
                        <i class="fa-solid fa-fire text-[#e07a5f] text-sm animate-pulse"></i>
                        <span class="text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">14 Ngày học</span>
                    </div>

                    <!-- Dark Mode Switch -->
                    <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
                    </button>
                </div>
            </header>

            <!-- Page Body -->
            <div class="flex-1 overflow-y-auto no-scrollbar transition-all duration-300 space-y-6"
                 :class="sidebarCollapsed ? 'p-4 sm:p-6 pl-4 sm:pl-6 pr-14 sm:pr-16' : 'p-4 sm:p-6 pr-14 sm:pr-16'">
                
                <div class="mx-auto space-y-6 transition-all duration-300"
                     :class="sidebarCollapsed ? 'max-w-7xl' : 'max-w-6xl'">

                    <!-- ========================================================================= -->
                    <!-- LEVEL 1: DANH SÁCH KHÓA HỌC NGOÀI CÙNG (TOÀN BỘ TEXT-XL H1 CHUẨN) -->
                    <!-- ========================================================================= -->
                    <div x-show="selectedCourse === null" class="space-y-6">
                        
                        <!-- Banner Tiêu đề Trang Khóa học (H1: text-xl font-bold) -->
                        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden">
                            <div class="space-y-1.5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                                    <i class="fa-solid fa-graduation-cap text-[#e07a5f]"></i> Lộ trình chuẩn hóa HSK 1 - HSK 6
                                </div>
                                <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                                    Khóa học Tiếng Trung HSK
                                </h1>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal leading-relaxed">
                                    Tổng hợp bài giảng video, từ vựng chữ Hán, mẫu câu giao tiếp và ngữ pháp chuyên sâu. Click vào từng khóa để xem danh sách bài học.
                                </p>
                            </div>
                        </div>

                        <!-- Filter Bar Buttons (text-xs font-semibold) -->
                        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                            <button @click="levelFilter = 'all'" :class="levelFilter === 'all' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                                Tất cả khóa học
                            </button>
                            <button @click="levelFilter = 'hsk12'" :class="levelFilter === 'hsk12' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                                HSK 1 - HSK 2 (Sơ cấp)
                            </button>
                            <button @click="levelFilter = 'hsk34'" :class="levelFilter === 'hsk34' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                                HSK 3 - HSK 4 (Trung cấp)
                            </button>
                            <button @click="levelFilter = 'hsk56'" :class="levelFilter === 'hsk56' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                                HSK 5 - HSK 6 (Cao cấp)
                            </button>
                        </div>

                        <!-- Grid các Thẻ Khóa học (Card Title: text-base font-bold, Body: text-xs font-normal) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                            
                            <!-- Card Khóa 1: HSK 1 -->
                            <div class="lms-card p-5 flex flex-col justify-between space-y-4 group">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">HSK 1 • Sơ cấp I</span>
                                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400"><i class="fa-solid fa-circle-check mr-1"></i>Đã đăng ký</span>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">HSK 1: Khởi đầu Hán ngữ</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed">Lộ trình bài học chính khóa bám sát cấu trúc khung đề thi HSK. Nắm vững 150 từ vựng cốt lõi & Pinyin giọng chuẩn.</p>
                                    
                                    <div class="space-y-1.5 pt-1">
                                        <div class="flex justify-between text-xs font-semibold text-slate-500">
                                            <span>Tiến độ học</span>
                                            <span class="font-bold text-[#e07a5f]">12/15 bài (80%)</span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-[#e07a5f] to-[#c86349]" style="width: 80%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                                    <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                                        <span><i class="fa-regular fa-clock mr-1"></i>15 bài</span>
                                        <span><i class="fa-solid fa-user-group mr-1"></i>1.2k học viên</span>
                                    </div>
                                    <button @click="selectedCourse = 'hsk1'" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5 shadow-xs">
                                        <span>Vào học</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Card Khóa 2: HSK 2 -->
                            <div class="lms-card p-5 flex flex-col justify-between space-y-4 group">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">HSK 2 • Sơ cấp II</span>
                                        <span class="text-xs font-semibold text-[#e07a5f]"><i class="fa-solid fa-play mr-1"></i>Đang học</span>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">HSK 2: Giao tiếp Đời sống</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed">Mở rộng 300 từ vựng, cấu trúc câu hỏi phức hợp và kỹ năng hỏi đường, mua sắm, đặt phòng khách sạn.</p>
                                    
                                    <div class="space-y-1.5 pt-1">
                                        <div class="flex justify-between text-xs font-semibold text-slate-500">
                                            <span>Tiến độ học</span>
                                            <span class="font-bold text-[#e07a5f]">6/15 bài (40%)</span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-[#e07a5f] to-[#c86349]" style="width: 40%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                                    <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                                        <span><i class="fa-regular fa-clock mr-1"></i>15 bài</span>
                                        <span><i class="fa-solid fa-user-group mr-1"></i>980</span>
                                    </div>
                                    <button @click="selectedCourse = 'hsk1'" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5 shadow-xs">
                                        <span>Học tiếp</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Card Khóa 3: HSK 3 -->
                            <div class="lms-card p-5 flex flex-col justify-between space-y-4 group">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#0284c7]/15 text-[#0284c7] border border-[#0284c7]/30">HSK 3 • Trung cấp</span>
                                        <span class="text-xs font-medium text-slate-400">Chưa đăng ký</span>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">HSK 3: Đọc hiểu Văn bản</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed">Tích lũy 600 từ vựng, tự tin đọc đoạn văn ngắn không cần Pinyin và viết đúng bộ thủ chữ Hán chuẩn nét.</p>
                                    
                                    <div class="space-y-1.5 pt-1">
                                        <div class="flex justify-between text-xs font-semibold text-slate-500">
                                            <span>Tiến độ học</span>
                                            <span class="font-semibold text-slate-400">0/20 bài</span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] overflow-hidden">
                                            <div class="h-full rounded-full bg-slate-300 dark:bg-slate-700" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                                    <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                                        <span><i class="fa-regular fa-clock mr-1"></i>20 bài</span>
                                        <span><i class="fa-solid fa-user-group mr-1"></i>750</span>
                                    </div>
                                    <button @click="selectedCourse = 'hsk1'" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-slate-300 text-xs font-bold btn-tactile flex items-center gap-1.5">
                                        <span>Xem bài học</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- ========================================================================= -->
                    <!-- LEVEL 2: DANH SÁCH BÀI HỌC (ĐỒNG BỘ 100% CỠ CHỮ H1 TEXT-XL, CARD TEXT-BASE) -->
                    <!-- ========================================================================= -->
                    <div x-show="selectedCourse !== null" class="space-y-6" style="display: none;">
                        
                        <!-- Nút Quay lại -->
                        <button @click="selectedCourse = null" class="inline-flex items-center gap-2 text-xs font-bold text-[#e07a5f] hover:text-[#c86349] transition-colors btn-tactile">
                            <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại danh sách khóa học
                        </button>

                        <!-- Banner Tiêu đề HSK 1 (H1 chuẩn text-xl font-bold đồng nhất) -->
                        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <!-- Badge HSK1 chuẩn text-xs font-bold -->
                                <div class="w-10 h-10 rounded-xl bg-[#f59e0b] text-slate-950 font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                                    HSK 1
                                </div>
                                <div class="space-y-1">
                                    <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                                        HSK 1 - Khởi đầu Hán ngữ
                                    </h1>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                                        Lộ trình bài học chính khóa bám sát cấu trúc khung chuẩn
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">
                                    15 Bài học
                                </span>
                                <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">
                                    150 Từ vựng
                                </span>
                            </div>
                        </div>

                        <!-- Danh sách các Thẻ Bài học (Chữ Hán: text-base font-bold zh-text đồng nhất 100%) -->
                        <div class="space-y-3">
                            <template x-for="(item, index) in lessons" :key="item.id">
                                <a :href="'{{ url('/demo-course-detail') }}?lesson=' + item.id"
                                   class="lms-card p-4 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between gap-4 group hover:border-[#e07a5f] transition-all cursor-pointer block">
                                    
                                    <div class="flex items-center gap-4 min-w-0">
                                        <!-- Số thứ tự: text-xs font-bold font-mono -->
                                        <div class="w-8 h-8 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 dark:text-slate-500 font-bold text-xs flex items-center justify-center font-mono shrink-0 group-hover:text-[#e07a5f] group-hover:border-[#e07a5f]/40 transition-colors" x-text="index < 9 ? '0' + (index + 1) : (index + 1)">01</div>

                                        <!-- Tiêu đề Bài học: Mã bài text-xs, Tiêu đề text-base font-bold, Subtitle text-xs -->
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="text-xs font-semibold text-[#e07a5f] uppercase tracking-wider" x-text="'MÃ BÀI: ' + item.code">MÃ BÀI: H1L01</span>
                                            </div>
                                            <h3 class="text-base font-bold zh-text text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors leading-tight" x-text="item.hanzi">你好！</h3>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate font-normal" x-text="item.vi">Xin chào</p>
                                        </div>
                                    </div>

                                    <!-- Nút Play Tròn w-8 h-8 -->
                                    <div class="shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-[#f59e0b] hover:bg-[#d97706] text-slate-950 flex items-center justify-center shadow-xs transition-transform group-hover:scale-105 btn-tactile">
                                            <i class="fa-solid fa-play ml-0.5 text-[11px]"></i>
                                        </div>
                                    </div>

                                </a>
                            </template>
                        </div>

                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
