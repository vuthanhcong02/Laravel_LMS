<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ sidebarOpen: false, sidebarCollapsed: false, isLoggedIn: true, langOpen: false, currentLang: 'Việt Nam', darkMode: false, selectedRadical: 'all', selectedLevel: 'all', searchQuery: '', activeTabMap: {}, socialDockExpanded: true, characters: [
    { 
        id: 1,
        hanzi: '好', 
        pinyin: 'hǎo', 
        hanviet: 'HẢO', 
        type: 'Tính từ', 
        meaning: 'Tốt, hay, đẹp, giỏi, an lành', 
        level: 'HSK 1',
        radicalPrimary: '女',
        exampleZh: '你好！很高兴认识你。', 
        exampleVi: 'Xin chào! Rất vui được quen biết bạn.',
        etymology: {
            structure: 'Trái - Phải (左右结构)',
            category: 'Chữ Hội ý (會意字)',
            radicals: [
                { radical: '女', pinyin: 'nǚ', meaning: 'Bộ Nữ (Người phụ nữ / Mẹ)' },
                { radical: '子', pinyin: 'zǐ', meaning: 'Bộ Tử (Đứa con trẻ)' }
            ],
            story: 'Hình ảnh người mẹ tay ẵm đứa con nhỏ là biểu tượng ấm áp nhất của tình mẫu tử, đại diện cho những điều TỐT ĐẸP, AN LÀNH (好).'
        },
        strokes: {
            count: 6,
            rules: 'Viết bên Trái (Bộ Nữ) trước ➔ Bên Phải (Bộ Tử) sau. Viết từ trên xuống dưới.',
            steps: ['𡦹 (Phẩy chấm)', 'ノ (Phẩy)', '一 (Ngang)', '㇇ (Ngang gấp)', '亅 (Sổ móc)', '一 (Ngang)']
        }
    },
    { 
        id: 2,
        hanzi: '谢', 
        pinyin: 'xiè', 
        hanviet: 'TẠ', 
        type: 'Động từ', 
        meaning: 'Cảm ơn, tạ ơn, từ chối', 
        level: 'HSK 1',
        radicalPrimary: '讠',
        exampleZh: '非常谢谢你的帮助。', 
        exampleVi: 'Rất cảm ơn sự giúp đỡ của bạn.',
        etymology: {
            structure: 'Trái - Giữa - Phải (左中右结构)',
            category: 'Chữ Hình thanh (形聲字)',
            radicals: [
                { radical: '讠', pinyin: 'yán', meaning: 'Bộ Ngôn (Lời nói)' },
                { radical: '身', pinyin: 'shēn', meaning: 'Bộ Thân (Thân thể)' },
                { radical: '寸', pinyin: 'cùn', meaning: 'Bộ Thốn (Lễ nghi / Quy tắc)' }
            ],
            story: 'Dùng Lời nói (讠) cúi gập Thân thể (身) đúng Lễ nghi (寸) để bày tỏ lòng CẢM ƠN (谢).'
        },
        strokes: {
            count: 12,
            rules: 'Viết từ trái sang phải: Bộ Ngôn ➔ Bộ Thân ➔ Bộ Thốn.',
            steps: ['丶 (Chấm)', '㇊ (Ngang gập)', 'ノ (Phẩy)', '丨 (Sổ)', '𠃍 (Ngang gập)', '一 (Ngang)', '一 (Ngang)', 'ノ (Phẩy)', '一 (Ngang)', '亅 (Sổ móc)', '丶 (Chấm)']
        }
    },
    { 
        id: 3,
        hanzi: '学', 
        pinyin: 'xué', 
        hanviet: 'HỌC', 
        type: 'Động từ', 
        meaning: 'Học tập, tiếp thu, bắt chước', 
        level: 'HSK 1',
        radicalPrimary: '冖',
        exampleZh: '我在中国学习汉语。', 
        exampleVi: 'Tôi đang học tiếng Trung ở Trung Quốc.',
        etymology: {
            structure: 'Trên - Dưới (上下结构)',
            category: 'Chữ Hội ý (會意字)',
            radicals: [
                { radical: '⺌', pinyin: 'xiǎo', meaning: 'Bộ Mái nhà / Tri thức' },
                { radical: '冖', pinyin: 'mì', meaning: 'Bộ Mịch (Trùm chăn / Mái trường)' },
                { radical: '子', pinyin: 'zǐ', meaning: 'Bộ Tử (Đứa trẻ)' }
            ],
            story: 'Đứa trẻ (子) ngồi dưới mái trường (冖) rèn luyện trí tuệ để gặt hái tri thức ➔ Ý nghĩa của việc HỌC (学).'
        },
        strokes: {
            count: 8,
            rules: 'Viết phần Trên (Mái trường) trước ➔ Phần Dưới (Đứa trẻ) sau.',
            steps: ['丶', 'ノ', '丶', '冖', '㇇', '亅', '一']
        }
    },
    { 
        id: 4,
        hanzi: '水', 
        pinyin: 'shuǐ', 
        hanviet: 'THỦY', 
        type: 'Danh từ', 
        meaning: 'Nước, chất lỏng', 
        level: 'HSK 1',
        radicalPrimary: '水',
        exampleZh: '请喝一杯水。', 
        exampleVi: 'Xin hãy uống một cốc nước.',
        etymology: {
            structure: 'Độc lập (独体字)',
            category: 'Chữ Tượng hình (象形字)',
            radicals: [
                { radical: '水', pinyin: 'shuǐ', meaning: 'Bộ Thủy (Dòng nước chảy)' }
            ],
            story: 'Nét giữa tượng trưng cho dòng sông chính, các nét hai bên tượng trưng cho những giọt nước bọt tung tóe ➔ NƯỚC (水).'
        },
        strokes: {
            count: 4,
            rules: 'Viết nét Sổ móc ở Giữa trước ➔ Viết các nét hai Bên sau.',
            steps: ['亅 (Sổ móc)', '㇇ (Ngang gập phẩy)', 'ノ (Phẩy)', '㇏ (Mác)']
        }
    }
] }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chiết Tự Chữ Hán & Bộ Thủ - Tiếng Trung XiaoMu LMS</title>
    <meta name="description" content="Tra cứu chiết tự chữ Hán, bộ thủ cấu thành, quy tắc viết nét bút thuận và mẹo nhớ chữ Hán dễ dàng.">
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
            
            <!-- LOGO THƯƠNG HIỆU -->
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
                        <a href="{{ url('/demo-etymology') }}" class="flex items-center gap-3 rounded-xl text-sm nav-item-active transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Chiết tự chữ Hán' : ''">
                            <i class="fa-solid fa-puzzle-piece text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Chiết tự chữ Hán</span>
                        </a>
                        <a href="{{ url('/demo-pinyin-chart') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Bảng Pinyin' : ''">
                            <i class="fa-solid fa-table-cells text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Bảng Pinyin</span>
                        </a>
                        <a href="{{ url('/demo-pinyin-practice') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện tập Pinyin' : ''">
                            <i class="fa-solid fa-headset text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện tập Pinyin</span>
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
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors">Vũ Thành Công</p>
                            <p class="text-xs text-slate-400 font-medium truncate">Hồ sơ</p>
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
            
            <!-- TOPBAR HEADER (ĐỒNG BỘ 100% VỚI CÁC TRANG DEMO KHÁC) -->
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
                        <input type="text" x-model="searchQuery" placeholder="Tìm kiếm bộ thủ, chữ Hán, pinyin..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
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
                                <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30"><clipPath id="s_uk_e1"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="t_uk_e1"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath><g clip-path="url(#s_uk_e1)"><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_e1)"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/></g></svg>
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

            <!-- Page Body (Tự động mở rộng & điều chỉnh padding khi thu gọn Sidebar) -->
            <div class="flex-1 overflow-y-auto no-scrollbar transition-all duration-300 space-y-6"
                 :class="sidebarCollapsed ? 'p-4 sm:p-6 pl-4 sm:pl-6 pr-14 sm:pr-16' : 'p-4 sm:p-6 pr-14 sm:pr-16'">
                
                <div class="mx-auto space-y-6 transition-all duration-300"
                     :class="sidebarCollapsed ? 'max-w-7xl' : 'max-w-6xl'">

                    <!-- Banner Tiêu đề Trang Chiết tự -->
                    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden">
                        <div class="space-y-1.5">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                                <i class="fa-solid fa-puzzle-piece text-[#e07a5f]"></i> Tra Cứu Chiết Tự Chữ Hán (漢字拆字)
                            </div>
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                                Chiết Tự Chữ Hán & Quy Tắc Nét Bút Thuận
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                                Phân tích hình thái chữ Hán qua 214 Bộ thủ, quy tắc viết nét bút thuận chuẩn và mẹo ghi nhớ nguồn gốc chữ.
                            </p>
                        </div>
                    </div>

                    <!-- RADICAL & HSK FILTERS BAR -->
                    <div class="lms-card p-4 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
                            <!-- Bộ thủ Filter Tabs -->
                            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                                <span class="text-slate-400 font-bold shrink-0">Bộ thủ:</span>
                                <button @click="selectedRadical = 'all'" :class="selectedRadical === 'all' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0">
                                    Tất cả Bộ thủ
                                </button>
                                <button @click="selectedRadical = '女'" :class="selectedRadical === '女' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                                    女 (Bộ Nữ)
                                </button>
                                <button @click="selectedRadical = '讠'" :class="selectedRadical === '讠' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                                    讠 (Bộ Ngôn)
                                </button>
                                <button @click="selectedRadical = '冖'" :class="selectedRadical === '冖' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                                    冖 (Bộ Mịch)
                                </button>
                                <button @click="selectedRadical = '水'" :class="selectedRadical === '水' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                                    水 (Bộ Thủy)
                                </button>
                            </div>

                            <!-- Level Filter -->
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-slate-400 font-bold">Cấp độ:</span>
                                <select x-model="selectedLevel" class="bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-800 dark:text-white focus:outline-none focus:border-[#e07a5f]">
                                    <option value="all">Tất cả HSK</option>
                                    <option value="HSK 1">HSK 1</option>
                                    <option value="HSK 2">HSK 2</option>
                                    <option value="HSK 3">HSK 3</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- CHARACTER ETYMOLOGY CARDS LIST (1/2 LEFT vs UPPER/LOWER RIGHT LAYOUT) -->
                    <div class="space-y-6">
                        <template x-for="item in characters" :key="item.id">
                            <div x-show="(selectedRadical === 'all' || item.radicalPrimary === selectedRadical) && (selectedLevel === 'all' || item.level === selectedLevel) && (searchQuery === '' || item.hanzi.includes(searchQuery) || item.pinyin.includes(searchQuery) || item.meaning.includes(searchQuery))"
                                 class="lms-card p-5 sm:p-6 space-y-4 hover:border-[#e07a5f]/50 transition-all shadow-sm">
                                
                                <!-- Card Header Bar -->
                                <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                                    <div class="flex items-center gap-3">
                                        <span class="text-3xl font-bold zh-text text-[#e07a5f]" x-text="item.hanzi">好</span>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="item.pinyin">hǎo</span>
                                                <span class="text-xs font-semibold text-slate-400" x-text="'• Hán-Việt: ' + item.hanviet"></span>
                                            </div>
                                            <span class="text-[11px] font-bold text-amber-700 dark:text-amber-400" x-text="item.type">Tính từ</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] text-xs font-bold" x-text="item.level">HSK 1</span>
                                    </div>
                                </div>

                                <!-- LAYOUT CHÍNH: 1/2 BÊN TRÁI VS 1/2 BÊN PHẢI (NỬA TRÊN CÁCH VIẾT, NỬA DƯỚI CHIẾT TỰ) -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    
                                    <!-- 1/2 BÊN TRÁI: NGHĨA, PHÁT ÂM & VÍ DỤ MẪU -->
                                    <div class="p-4 rounded-2xl bg-[#faf6f2] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] flex flex-col justify-between space-y-3">
                                        <div>
                                            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2 mb-3">
                                                <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-black">
                                                    1/2 Bên Trái: Phát âm & Dịch nghĩa
                                                </span>
                                                <button @click="alert('Phát âm chuẩn giọng Bắc Kinh')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 text-[#e07a5f] text-[11px] font-bold btn-tactile border border-[#e8e2d9] dark:border-slate-700">
                                                    <i class="fa-solid fa-volume-high mr-1"></i>Nghe phát âm
                                                </button>
                                            </div>

                                            <div class="space-y-1 mb-3">
                                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Giải nghĩa tiếng Việt:</span>
                                                <div class="text-base sm:text-lg font-bold text-slate-900 dark:text-white" x-text="item.meaning">Tốt, hay, đẹp, giỏi</div>
                                            </div>
                                        </div>

                                        <div class="p-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1">
                                            <div class="font-bold text-slate-400 text-[10px]"><i class="fa-solid fa-quote-left text-[#e07a5f] mr-1"></i>Ví dụ mẫu HSK:</div>
                                            <div class="text-sm font-bold zh-text text-slate-800 dark:text-slate-100" x-text="item.exampleZh">你好！很高兴认识你。</div>
                                            <div class="text-xs text-slate-500" x-text="item.exampleVi">Xin chào! Rất vui được quen biết bạn.</div>
                                        </div>
                                    </div>

                                    <!-- 1/2 BÊN PHẢI: CHIA THÀNH 2 NỬA DỌC (NỬA TRÊN CÁCH VIẾT, NỬA DƯỚI CHIẾT TỰ) -->
                                    <div class="flex flex-col gap-4">
                                        
                                        <!-- NỬA TRÊN (BÊN PHẢI): KHUNG CÁCH VIẾT & BÚT THUẬN -->
                                        <div class="p-4 rounded-2xl bg-white dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2.5 shadow-xs">
                                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                                                <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-black">
                                                    ✍️ Nửa trên: Khung cách viết (Bút thuận)
                                                </span>
                                                <span class="text-xs font-bold text-slate-400" x-text="item.strokes.count + ' Nét vẽ'">6 Nét vẽ</span>
                                            </div>

                                            <div class="text-xs text-slate-600 dark:text-slate-300">
                                                Quy tắc nét: <strong class="text-slate-800 dark:text-white" x-text="item.strokes.rules"></strong>
                                            </div>

                                            <div class="flex items-center justify-between pt-1">
                                                <div class="flex flex-wrap gap-1">
                                                    <template x-for="(st, idx) in item.strokes.steps" :key="idx">
                                                        <span class="px-2 py-0.5 rounded bg-[#f8f6f3] dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-slate-700" x-text="st"></span>
                                                    </template>
                                                </div>
                                                <button @click="alert('Đang phát hoạt ảnh tập viết nét chữ Hán từng bước chuẩn Bút thuận!')" class="px-3 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs btn-tactile shrink-0">
                                                    <i class="fa-solid fa-pen-line mr-1"></i>Xem nét viết
                                                </button>
                                            </div>
                                        </div>

                                        <!-- NỬA DƯỚI (BÊN PHẢI): KHUNG CHIẾT TỰ CHỮ HÁN -->
                                        <div class="p-4 rounded-2xl bg-white dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2.5 shadow-xs">
                                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                                                <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-black">
                                                    🧩 Nửa dưới: Chiết tự Chữ Hán
                                                </span>
                                                <span class="text-xs font-bold text-slate-400" x-text="item.etymology.category">Chữ Hội ý</span>
                                            </div>

                                            <!-- Bộ thủ hợp thành -->
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="text-slate-400 font-bold shrink-0">Bộ thủ:</span>
                                                <template x-for="rad in item.etymology.radicals" :key="rad.radical">
                                                    <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] text-xs font-bold border border-[#fcdccf] dark:border-[#42271f]" x-text="rad.radical + ' (' + rad.meaning + ')'"></span>
                                                </template>
                                            </div>

                                            <!-- Mẹo ghi nhớ chiết tự -->
                                            <div class="p-3 rounded-xl bg-[#fff2ee]/60 dark:bg-[#2a221f]/60 text-slate-700 dark:text-slate-300 text-xs leading-relaxed">
                                                <strong class="text-[#e07a5f] font-bold block mb-1"><i class="fa-solid fa-brain mr-1"></i>Chiết tự ghi nhớ:</strong>
                                                <span x-text="item.etymology.story"></span>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
