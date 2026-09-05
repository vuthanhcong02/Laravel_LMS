<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="examTimer">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng thi Luyện thi HSK 1 - Mã H1001 - XiaoMu LMS</title>
    <meta name="description" content="Phòng thi Luyện thi HSK trực tuyến chuẩn Bộ Giáo Dục Trung Quốc với giao diện chuyên nghiệp.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        .zh-text { font-family: 'Inter', 'Noto Sans SC', sans-serif; }
        /* Native Ruby pinyin formatting & high-contrast visibility */
        ruby { font-size: 1.1em; }
        rt {
            font-size: 0.55em;
            color: #64748b;
            font-weight: 600;
            user-select: none;
            text-align: center;
        }
        .dark rt { color: #94a3b8; }
        /* Card hover transitions */
        .q-card {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .q-card:hover {
            border-color: rgba(224, 122, 95, 0.4);
            box-shadow: 0 4px 20px -2px rgba(224, 122, 95, 0.08);
        }
        html { scroll-behavior: smooth; }
        @keyframes timer-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .timer-warning { animation: timer-pulse 1s ease-in-out infinite; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .btn-tactile { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        .btn-tactile:active { transform: scale(0.96); }
    </style>
</head>
<body class="bg-[#f8f6f3] dark:bg-[#0e0c0b] text-slate-900 dark:text-slate-100 h-screen overflow-hidden flex flex-col">
    <!-- Top Header Navigation Bar -->
    <header class="bg-white dark:bg-[#141211] border-b border-[#e8e2d9] dark:border-[#262220] h-16 flex items-center justify-between px-4 md:px-6 shrink-0 z-30 shadow-xs">
        <!-- Left: Back Exit Button & Exam Title -->
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <button type="button" @click="showExitModal = true" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors shrink-0 btn-tactile" title="Thoát phòng thi">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <div class="min-w-0">
                <h1 class="font-bold text-slate-900 dark:text-white text-sm md:text-base truncate leading-tight">
                    Luyện thi HSK 1 · Mã H1001 (Đề chuẩn)
                </h1>
                <p class="text-[11px] text-slate-400 font-medium truncate">Phòng thi trực tuyến tự động chấm điểm</p>
            </div>
        </div>
        <!-- Center: Embedded Audio Player -->
        <div class="flex-1 flex justify-center px-4 hidden lg:flex">
            <div class="w-full max-w-sm bg-[#faf6f2] dark:bg-slate-800/80 rounded-xl px-3.5 py-1.5 border border-[#e8e2d9] dark:border-slate-700 flex items-center gap-3">
                <i class="fa-solid fa-volume-high text-[#e07a5f] text-sm shrink-0"></i>
                <div class="flex-1 flex items-center gap-2">
                    <button @click="toggleAudio()" class="w-7 h-7 rounded-full bg-[#e07a5f] text-white flex items-center justify-center text-xs shrink-0 btn-tactile shadow-xs">
                        <i class="fa-solid" :class="isPlaying ? 'fa-pause' : 'fa-play ml-0.5'"></i>
                    </button>
                    <div class="flex-1 space-y-1">
                        <div class="flex justify-between text-[10px] text-slate-500 dark:text-slate-400 font-bold">
                            <span>Audio Nghe hiểu HSK 1</span>
                            <span>04:15 / 15:00</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden cursor-pointer">
                            <div class="h-full bg-[#e07a5f] rounded-full" style="width: 28%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right: Countdown Timer & Submit Button -->
        <div class="flex items-center gap-3 flex-1 justify-end">
            <!-- Timer Badge -->
            <div :class="timeRemaining <= 300 ? 'bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 timer-warning' : 'bg-[#fff2ee] dark:bg-[#251d1a] border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] dark:text-[#f4978e]'"
                 class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl border transition-colors font-mono font-bold text-xs sm:text-sm">
                <i class="fa-regular fa-clock text-[#e07a5f] text-sm"></i>
                <span x-text="formatTime(timeRemaining)">35:00</span>
            </div>
            <!-- Dark Mode Switch -->
            <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile">
                <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
            </button>
            <!-- Mobile Palette Toggle -->
            <button type="button" @click="mobileNavOpen = !mobileNavOpen" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 md:hidden btn-tactile relative">
                <i class="fa-solid fa-grid-2 text-sm"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#e07a5f] text-white text-[9px] font-bold flex items-center justify-center" x-text="Object.keys(answers).length" x-show="Object.keys(answers).length > 0"></span>
            </button>
        </div>
    </header>
    <!-- Mobile Audio Sticky Bar -->
    <div class="lg:hidden bg-slate-900 text-white px-4 py-2 border-b border-slate-800 flex items-center justify-between gap-3 text-xs shrink-0 shadow-md">
        <div class="flex items-center gap-2 text-amber-400 font-bold shrink-0">
            <i class="fa-solid fa-volume-high"></i>
            <span>Audio Nghe:</span>
        </div>
        <div class="flex-1 flex items-center gap-2">
            <button @click="isPlaying = !isPlaying" class="w-6 h-6 rounded-full bg-[#e07a5f] text-white flex items-center justify-center text-[10px]">
                <i class="fa-solid" :class="isPlaying ? 'fa-pause' : 'fa-play ml-0.5'"></i>
            </button>
            <div class="flex-1 h-1.5 rounded-full bg-slate-700 overflow-hidden">
                <div class="h-full bg-[#e07a5f]" style="width: 28%;"></div>
            </div>
            <span class="text-[10px] text-slate-400 font-mono">04:15</span>
        </div>
    </div>
    <!-- Main Workspace Container -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- LEFT: SCROLLABLE QUESTIONS LIST -->
        <main class="flex-1 overflow-y-auto bg-[#f8f6f3] dark:bg-[#0e0c0b] scroll-smooth no-scrollbar" id="question-container">
            <div class="max-w-3xl mx-auto px-4 md:px-6 py-6 pb-28 space-y-8">
                <div>
                    <!-- Section Title Banner -->
                    <div class="flex items-center justify-between gap-4 mb-6 p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] text-white shadow-md">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center font-black text-lg">1</span>
                            <div>
                                <h2 class="text-base sm:text-lg font-black uppercase tracking-wide">Phần 1: Nghe Hiểu (听力)</h2>
                                <p class="text-xs text-white/80 font-medium">20 câu hỏi • Thời gian 15 phút</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-white/20 text-xs font-bold"><i class="fa-solid fa-volume-high mr-1"></i>Audio 100%</span>
                    </div>
                    <div class="bg-amber-50/70 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 sm:p-5 mb-6 space-y-3">
                        <div class="flex items-center justify-between font-bold text-amber-800 dark:text-amber-300 text-xs mb-2">
                            <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold"><i class="fa-solid fa-circle-check mr-1"></i>Đáp án mẫu: A</span>
                        </div>
                        <div class="p-4 bg-white dark:bg-[#181615] rounded-xl border border-amber-200 dark:border-amber-900/50 shadow-xs">
                            <p class="text-xs font-bold text-slate-500 mb-2">Bạn nghe thấy đoạn hội thoại: "你好！很高兴认识你。"</p>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="p-3 rounded-xl border-2 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 text-center">
                                    <div class="text-xs font-black text-emerald-600 dark:text-emerald-400 mb-1">A ✓</div>
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="h-16 w-full object-cover rounded-lg">
                                </div>
                                <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-center opacity-60">
                                    <div class="text-xs font-bold text-slate-400 mb-1">B</div>
                                    <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="h-16 w-full object-cover rounded-lg">
                                </div>
                                <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-center opacity-60">
                                    <div class="text-xs font-bold text-slate-400 mb-1">C</div>
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="h-16 w-full object-cover rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Real Questions 1 to 5 (Listening) -->
                    <div class="space-y-5">
                        <!-- Question 1 -->
                        <div class="q-card scroll-mt-24 p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4" id="q-1">
                            <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs flex items-center justify-center border border-[#fcdccf] dark:border-[#42271f]">1</span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Nghe đoạn thoại và chọn hình ảnh phù hợp</span>
                                </div>
                                <button @click="playAudio()" class="px-3 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] text-xs font-bold btn-tactile flex items-center gap-1.5">
                                    <i class="fa-solid fa-volume-high text-xs"></i> Nghe Audio
                                </button>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q1" value="A" @change="answers[1] = 'A'" class="peer hidden">
                                    <div class="p-3 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all text-center">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-xs font-bold group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">A</span>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" class="h-24 w-full object-cover rounded-lg">
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q1" value="B" @change="answers[1] = 'B'" class="peer hidden">
                                    <div class="p-3 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all text-center">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-xs font-bold group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">B</span>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" class="h-24 w-full object-cover rounded-lg">
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q1" value="C" @change="answers[1] = 'C'" class="peer hidden">
                                    <div class="p-3 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all text-center">
                                        <div class="flex justify-between items-center pb-2 mb-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-xs font-bold group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">C</span>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" class="h-24 w-full object-cover rounded-lg">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!-- Question 2 -->
                        <div class="q-card scroll-mt-24 p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4" id="q-2">
                            <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs flex items-center justify-center border border-[#fcdccf] dark:border-[#42271f]">2</span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Nghe đoạn thoại và phán đoán Đúng / Sai</span>
                                </div>
                                <button @click="playAudio()" class="px-3 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] text-xs font-bold btn-tactile flex items-center gap-1.5">
                                    <i class="fa-solid fa-volume-high text-xs"></i> Nghe Audio
                                </button>
                            </div>
                            <div class="p-3 bg-[#faf6f2] dark:bg-[#201d1b] rounded-xl text-center">
                                <p class="text-sm font-bold text-slate-900 dark:text-white zh-text">
                                    <ruby>我<rt>wǒ</rt></ruby><ruby>想<rt>xiǎng</rt></ruby><ruby>吃<rt>chī</rt></ruby><ruby>苹<rt>píng</rt></ruby><ruby>果<rt>guǒ</rt></ruby>。
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q2" value="TRUE" @change="answers[2] = 'TRUE'" class="peer hidden">
                                    <div class="p-3.5 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 transition-all flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check text-emerald-500 text-base"></i>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Đúng (正确)</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q2" value="FALSE" @change="answers[2] = 'FALSE'" class="peer hidden">
                                    <div class="p-3.5 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-rose-500 peer-checked:bg-rose-50/50 transition-all flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-xmark text-rose-500 text-base"></i>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Sai (错误)</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <!-- Section Title Banner -->
                    <div class="flex items-center justify-between gap-4 mb-6 p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] text-white shadow-md">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center font-black text-lg">2</span>
                            <div>
                                <h2 class="text-base sm:text-lg font-black uppercase tracking-wide">Phần 2: Đọc Hiểu (阅读)</h2>
                                <p class="text-xs text-white/80 font-medium">20 câu hỏi • Thời gian 17 phút</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-white/20 text-xs font-bold"><i class="fa-solid fa-book-open mr-1"></i>Đọc chữ Hán</span>
                    </div>
                    <!-- Real Questions 3 & 4 (Reading) -->
                    <div class="space-y-5">
                        <!-- Question 3 -->
                        <div class="q-card scroll-mt-24 p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4" id="q-3">
                            <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs flex items-center justify-center border border-[#fcdccf] dark:border-[#42271f]">3</span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Chọn nghĩa tiếng Việt chính xác nhất cho từ vựng bên dưới</span>
                                </div>
                            </div>
                            <div class="p-4 bg-[#faf6f2] dark:bg-[#201d1b] rounded-xl text-center space-y-1">
                                <div class="text-2xl font-bold zh-text text-slate-900 dark:text-white">
                                    <ruby>谢<rt>xiè</rt></ruby><ruby>谢<rt>xie</rt></ruby>
                                </div>
                            </div>
                            <div class="space-y-2.5">
                                <label class="cursor-pointer group block">
                                    <input type="radio" name="q3" value="A" @change="answers[3] = 'A'" class="peer hidden">
                                    <div class="p-3.5 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold flex items-center justify-center group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">A</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Cảm ơn</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer group block">
                                    <input type="radio" name="q3" value="B" @change="answers[3] = 'B'" class="peer hidden">
                                    <div class="p-3.5 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold flex items-center justify-center group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">B</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Tạm biệt</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer group block">
                                    <input type="radio" name="q3" value="C" @change="answers[3] = 'C'" class="peer hidden">
                                    <div class="p-3.5 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold flex items-center justify-center group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">C</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Xin lỗi</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!-- Question 4 -->
                        <div class="q-card scroll-mt-24 p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4" id="q-4">
                            <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs flex items-center justify-center border border-[#fcdccf] dark:border-[#42271f]">4</span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Điền từ thích hợp vào chỗ trống (填空)</span>
                                </div>
                            </div>
                            <div class="p-4 bg-[#faf6f2] dark:bg-[#201d1b] rounded-xl text-center">
                                <p class="text-base font-bold text-slate-900 dark:text-white zh-text">
                                    <ruby>他<rt>tā</rt></ruby><ruby>是<rt>shì</rt></ruby> ______ <ruby>人<rt>rén</rt></ruby>。
                                </p>
                            </div>
                            <div class="space-y-2.5">
                                <label class="cursor-pointer group block">
                                    <input type="radio" name="q4" value="A" @change="answers[4] = 'A'" class="peer hidden">
                                    <div class="p-3.5 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold flex items-center justify-center group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">A</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 zh-text"><ruby>中<rt>zhōng</rt></ruby><ruby>国<rt>guó</rt></ruby> (Trung Quốc)</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer group block">
                                    <input type="radio" name="q4" value="B" @change="answers[4] = 'B'" class="peer hidden">
                                    <div class="p-3.5 rounded-xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff2ee]/40 transition-all flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold flex items-center justify-center group-[.peer:checked+div]:bg-[#e07a5f] group-[.peer:checked+div]:text-white">B</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 zh-text"><ruby>明<rt>míng</rt></ruby><ruby>天<rt>tiān</rt></ruby> (Ngày mai)</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- RIGHT: FIXED QUESTION PALETTE SIDEBAR (w-72) -->
        <aside class="w-72 bg-white dark:bg-[#141211] border-l border-[#e8e2d9] dark:border-[#262220] flex flex-col shrink-0 hidden md:flex z-20">
            <!-- Header & Progress Tracking Bar -->
            <div class="p-4 border-b border-[#e8e2d9] dark:border-[#262220] space-y-2">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm">Bảng câu hỏi phòng thi</h3>
                    <span class="text-xs font-bold text-[#e07a5f]" x-text="Object.keys(answers).length + '/4 câu'">0/4 câu</span>
                </div>
                <div class="h-2 bg-[#f8f6f3] dark:bg-slate-800 rounded-full overflow-hidden border border-[#e8e2d9] dark:border-slate-700">
                    <div class="h-full bg-gradient-to-r from-[#e07a5f] to-[#c86349] rounded-full transition-all duration-300"
                         :style="'width: ' + ((Object.keys(answers).length / 4) * 100) + '%'"></div>
                </div>
            </div>
            <!-- Question Numbers Palette Grid -->
            <div class="flex-1 overflow-y-auto p-4 space-y-5 no-scrollbar">
                <!-- Section 1 Palette -->
                <div>
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Phần 1: Nghe hiểu (2 câu)</p>
                    </div>
                    <div class="grid grid-cols-5 gap-2">
                        <button @click="scrollToQuestion(1)" :class="answers[1] ? 'bg-[#e07a5f] text-white border-[#e07a5f] font-bold shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700'" class="w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border transition-all btn-tactile">
                            1
                        </button>
                        <button @click="scrollToQuestion(2)" :class="answers[2] ? 'bg-[#e07a5f] text-white border-[#e07a5f] font-bold shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700'" class="w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border transition-all btn-tactile">
                            2
                        </button>
                    </div>
                </div>
                <!-- Section 2 Palette -->
                <div>
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Phần 2: Đọc hiểu (2 câu)</p>
                    </div>
                    <div class="grid grid-cols-5 gap-2">
                        <button @click="scrollToQuestion(3)" :class="answers[3] ? 'bg-[#e07a5f] text-white border-[#e07a5f] font-bold shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700'" class="w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border transition-all btn-tactile">
                            3
                        </button>
                        <button @click="scrollToQuestion(4)" :class="answers[4] ? 'bg-[#e07a5f] text-white border-[#e07a5f] font-bold shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700'" class="w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border transition-all btn-tactile">
                            4
                        </button>
                    </div>
                </div>
            </div>
            <!-- Bottom Legend & Submit Button -->
            <div class="p-4 border-t border-[#e8e2d9] dark:border-[#262220] space-y-3 bg-[#faf6f2] dark:bg-slate-900/60">
                <div class="flex items-center justify-around text-xs text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-[#e07a5f]"></div>
                        <span class="font-medium">Đã làm</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-600"></div>
                        <span class="font-medium">Chưa làm</span>
                    </div>
                </div>
                <button type="button" @click="showSubmitModal = true" class="w-full bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold py-2.5 rounded-xl shadow-md transition-all btn-tactile flex items-center justify-center gap-2 text-xs">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Nộp bài ngay</span>
                </button>
            </div>
        </aside>
    </div>
    <!-- MODAL 1: CONFIRM SUBMIT MODAL -->
    <div x-show="showSubmitModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs" style="display: none;">
        <div class="bg-white dark:bg-[#181615] rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 border border-[#e8e2d9] dark:border-[#2d2926] space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Xác nhận nộp bài thi</h3>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Bạn đã làm được <strong class="text-[#e07a5f] font-bold" x-text="Object.keys(answers).length">0</strong> / 4 câu hỏi.
            </p>
            <p class="text-[11px] text-slate-400">
                Sau khi nộp bài, hệ thống sẽ tính điểm tự động và trả kết quả chi tiết kèm đáp án giải thích.
            </p>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="showSubmitModal = false" class="flex-1 py-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors btn-tactile">
                    Làm tiếp
                </button>
                <button type="button" @click="showSubmitModal = false; alert('Nộp bài thi thành công! Điểm số demo của bạn: 190/200 điểm.')" class="flex-1 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs transition-colors btn-tactile shadow-md">
                    Nộp ngay
                </button>
            </div>
        </div>
    </div>
    <!-- MODAL 2: CONFIRM EXIT MODAL -->
    <div x-show="showExitModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs" style="display: none;">
        <div class="bg-white dark:bg-[#181615] rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 border border-[#e8e2d9] dark:border-[#2d2926] space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-950/60 text-red-500 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Xác nhận thoát phòng thi</h3>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Bạn có chắc chắn muốn thoát khỏi bài thi lúc này?
            </p>
            <div class="p-3 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/40 text-[11px] text-red-600 dark:text-red-400 leading-relaxed font-medium">
                ⚠️ Lưu ý: Thời gian làm bài vẫn tiếp tục đếm ngược. Nếu hết giờ mà bạn chưa nộp bài, kết quả thi này sẽ bị hủy bỏ hoàn toàn.
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="showExitModal = false" class="flex-1 py-2.5 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors btn-tactile">
                    Làm tiếp
                </button>
                <a href="{{ url('/demo-exams') }}" class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-xs text-center transition-colors btn-tactile shadow-md">
                    Đồng ý thoát
                </a>
            </div>
        </div>
    </div>
    <!-- ALPINE & JS LOGIC -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('examTimer', () => ({
                timeRemaining: 2100, // 35 minutes
                isPlaying: false,
                mobileNavOpen: false,
                showSubmitModal: false,
                showExitModal: false,
                answers: {},
                darkMode: false,
                init() {
                    const timer = setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                        } else {
                            clearInterval(timer);
                            alert('Hết giờ làm bài! Bài thi đã được tự động nộp.');
                        }
                    }, 1000);
                },
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                    const s = (seconds % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                },
                toggleAudio() {
                    this.isPlaying = !this.isPlaying;
                },
                playAudio() {
                    this.isPlaying = true;
                    alert('Đang phát file nghe âm thanh của câu hỏi này...');
                },
                scrollToQuestion(qNum) {
                    const el = document.getElementById('q-' + qNum);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }));
        });
    </script>
</body>
</html>
