<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang Thi: HSK {{ $level }} - Mã Đề {{ str_pad($id, 2, '0', STR_PAD_LEFT) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 h-screen overflow-hidden flex flex-col" x-data="examTimer()">

    {{-- Header --}}
    <header class="bg-white dark:bg-[#151c2c] border-b border-slate-200 dark:border-slate-800 h-16 flex items-center justify-between px-6 shrink-0 z-10 shadow-sm">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
            <div>
                <h1 class="text-sm font-bold text-slate-800 dark:text-white">Đề Thi Thử HSK {{ $level }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Mã Đề: {{ str_pad($id, 2, '0', STR_PAD_LEFT) }} • Môn: Nghe & Đọc</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            {{-- Timer --}}
            <div class="flex items-center gap-2 bg-rose-50 dark:bg-rose-500/10 px-4 py-2 rounded-xl border border-rose-100 dark:border-rose-500/20">
                <span class="material-symbols-outlined text-rose-500 text-[20px] animate-pulse">timer</span>
                <span class="font-bold text-rose-600 dark:text-rose-400 font-mono text-lg" x-text="formatTime(timeRemaining)">40:00</span>
            </div>
            
            {{-- Submit button --}}
            <button class="bg-primary hover:bg-primary-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-primary/20 transition-all active:scale-95 flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-[18px]">send</span> Nộp bài
            </button>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="flex-1 flex overflow-hidden">
        
        {{-- Left Content: Questions --}}
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-[#0b1120] p-6 lg:p-10 scroll-smooth" id="question-container">
            <div class="max-w-4xl mx-auto space-y-10 pb-32">
                
                {{-- Audio Player (Listening) --}}
                <div class="bg-white dark:bg-slate-800/60 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700/50 sticky top-0 z-10 flex flex-col sm:flex-row sm:items-center gap-4 backdrop-blur-md">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-[24px]">headphones</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800 dark:text-white mb-2">Phần 1: Nghe hiểu</p>
                        <div class="flex items-center gap-3">
                            <button class="w-8 h-8 flex items-center justify-center rounded-full bg-primary text-white shadow-sm hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                            </button>
                            <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden relative cursor-pointer">
                                <div class="absolute top-0 left-0 h-full bg-primary w-1/3 rounded-full"></div>
                            </div>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 font-mono">01:24 / 04:30</span>
                        </div>
                    </div>
                </div>

                {{-- Question 1 --}}
                <div class="bg-white dark:bg-slate-800/40 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700/50 scroll-mt-24" id="q-1">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-black text-sm flex items-center justify-center shrink-0">1</div>
                        <div class="flex-1 space-y-4">
                            <p class="text-base text-slate-800 dark:text-white font-medium">Nghe đoạn hội thoại và chọn bức tranh tương ứng.</p>
                            
                            {{-- Options --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach(['A', 'B', 'C'] as $opt)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q1" class="peer hidden" value="{{ $opt }}">
                                    <div class="border-2 border-slate-200 dark:border-slate-700 rounded-xl p-2 transition-all peer-checked:border-primary peer-checked:bg-primary/5 group-hover:border-primary/50 relative">
                                        <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                                            <span class="material-symbols-outlined text-white text-[12px] opacity-0 peer-checked:opacity-100">check</span>
                                        </div>
                                        <div class="w-full h-32 bg-slate-100 dark:bg-slate-800 rounded-lg mb-2 flex items-center justify-center text-slate-400 dark:text-slate-500">Ảnh {{ $opt }}</div>
                                        <p class="text-center font-bold text-slate-700 dark:text-slate-300">{{ $opt }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Question 2 --}}
                <div class="bg-white dark:bg-slate-800/40 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700/50 scroll-mt-24" id="q-2">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-black text-sm flex items-center justify-center shrink-0">2</div>
                        <div class="flex-1 space-y-4">
                            <p class="text-base text-slate-800 dark:text-white font-medium">Nghe và xác định xem phát biểu sau đây đúng hay sai.</p>
                            <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 font-medium text-lg text-slate-700 dark:text-slate-200 text-center">
                                他在喝茶。 (Anh ấy đang uống trà)
                            </div>
                            
                            {{-- Options --}}
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q2" class="peer hidden" value="True">
                                    <div class="border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center justify-between transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 group-hover:border-emerald-500/50">
                                        <span class="font-bold text-slate-700 dark:text-slate-300 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-400 flex items-center gap-2"><span class="material-symbols-outlined text-emerald-500">check_circle</span> Đúng</span>
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center transition-all">
                                            <span class="material-symbols-outlined text-white text-[12px] opacity-0 peer-checked:opacity-100">check</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q2" class="peer hidden" value="False">
                                    <div class="border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center justify-between transition-all peer-checked:border-rose-500 peer-checked:bg-rose-500/10 group-hover:border-rose-500/50">
                                        <span class="font-bold text-slate-700 dark:text-slate-300 peer-checked:text-rose-600 dark:peer-checked:text-rose-400 flex items-center gap-2"><span class="material-symbols-outlined text-rose-500">cancel</span> Sai</span>
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-rose-500 peer-checked:bg-rose-500 flex items-center justify-center transition-all">
                                            <span class="material-symbols-outlined text-white text-[12px] opacity-0 peer-checked:opacity-100">check</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-4 py-4">
                    <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700/50"></div>
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Phần 2: Đọc hiểu</span>
                    <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700/50"></div>
                </div>

                {{-- Question 3 --}}
                <div class="bg-white dark:bg-slate-800/40 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700/50 scroll-mt-24" id="q-3">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-black text-sm flex items-center justify-center shrink-0">3</div>
                        <div class="flex-1 space-y-4">
                            <p class="text-base text-slate-800 dark:text-white font-medium">Chọn từ điền vào chỗ trống:</p>
                            <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 text-lg text-slate-800 dark:text-slate-200 font-medium">
                                昨天我买了一（____）书。
                            </div>
                            
                            {{-- Options --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach(['本 (běn)', '个 (gè)', '只 (zhī)', '件 (jiàn)'] as $index => $opt)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="q3" class="peer hidden" value="{{ $index }}">
                                    <div class="border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center gap-3 transition-all peer-checked:border-primary peer-checked:bg-primary/10 group-hover:border-primary/50">
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all shrink-0">
                                            <span class="material-symbols-outlined text-white text-[12px] opacity-0 peer-checked:opacity-100">check</span>
                                        </div>
                                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ chr(65 + $index) }}. {{ $opt }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        {{-- Right Sidebar: Palette --}}
        <aside class="w-80 bg-white dark:bg-[#151c2c] border-l border-slate-200 dark:border-slate-800 flex flex-col shadow-[-4px_0_15px_rgba(0,0,0,0.02)] shrink-0 hidden md:flex">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Bảng Câu Hỏi</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Đã làm: <span class="font-bold text-primary">1</span>/40</p>
            </div>
            <div class="flex-1 overflow-y-auto p-5">
                
                <div class="mb-6">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Phần 1: Nghe</h4>
                    <div class="grid grid-cols-5 gap-2">
                        {{-- Done --}}
                        <a href="#q-1" class="w-10 h-10 rounded-lg bg-primary text-white font-bold text-sm flex items-center justify-center hover:scale-110 transition-transform">1</a>
                        {{-- Current --}}
                        <a href="#q-2" class="w-10 h-10 rounded-lg bg-primary/10 border-2 border-primary text-primary dark:text-primary-400 font-bold text-sm flex items-center justify-center hover:scale-110 transition-transform">2</a>
                        {{-- Unanswered --}}
                        @for($i=3; $i<=20; $i++)
                        <a href="#q-{{$i}}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold text-sm flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            {{ $i }}
                        </a>
                        @endfor
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Phần 2: Đọc</h4>
                    <div class="grid grid-cols-5 gap-2">
                        @for($i=21; $i<=40; $i++)
                        <a href="#q-{{$i}}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold text-sm flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            {{ $i }}
                        </a>
                        @endfor
                    </div>
                </div>

            </div>
            
            <div class="p-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium mb-2">
                    <div class="w-4 h-4 rounded bg-primary"></div> Đã làm
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium mb-2">
                    <div class="w-4 h-4 rounded bg-primary/10 border border-primary"></div> Đang xem
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium">
                    <div class="w-4 h-4 rounded bg-slate-100 dark:bg-slate-800"></div> Chưa làm
                </div>
            </div>
        </aside>

    </div>

    {{-- Script for Mock Timer --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('examTimer', () => ({
                timeRemaining: 40 * 60, // 40 phút
                init() {
                    setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                        }
                    }, 1000);
                },
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                    const s = (seconds % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                }
            }))
        })
    </script>
</body>
</html>
