<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ langOpen: false, currentLang: 'Việt Nam', darkMode: false, showPassword: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - XIAOMU Tiếng Trung LMS</title>
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

        @keyframes floatCard {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(1deg); }
        }

        .animate-float {
            animation: floatCard 4s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: floatCard 5s ease-in-out 1.5s infinite;
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

        /* Nút bấm Tactile Press Response */
        .btn-tactile {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-tactile:active {
            transform: scale(0.97);
        }
    </style>
</head>
<body class="h-full bg-[#f8f6f3] dark:bg-[#0e0c0b] text-slate-800 dark:text-slate-100 flex flex-col transition-colors duration-200">

    <!-- Topbar Header Đơn Giản -->
    <header class="h-20 border-b border-[#e8e2d9] dark:border-[#262220] px-4 sm:px-8 flex items-center justify-between shrink-0 bg-white/80 dark:bg-[#141211]/80 backdrop-blur-md sticky top-0 z-30">
        <!-- Logo Mascot Thương hiệu -->
        <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
            <img src="{{ asset('logo.png') }}" alt="XIAOMU Logo" class="w-10 h-10 rounded-full object-cover shrink-0 group-hover:scale-105 transition-transform duration-200">
            <div class="flex flex-col min-w-0">
                <span class="font-bold text-lg tracking-tight text-slate-900 dark:text-white leading-none">XIAOMU</span>
                <span class="text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">
                    Tiếng Trung LMS
                </span>
            </div>
        </a>

        <!-- Controls Phải Header -->
        <div class="flex items-center gap-3">
            <!-- Chuyển đổi Ngôn ngữ Dropdown với SVG Cờ các nước -->
            <div class="relative">
                <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-slate-300 transition-all btn-tactile">
                    <template x-if="currentLang === 'Việt Nam'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20">
                            <rect width="30" height="20" fill="#da251d"/>
                            <polygon points="15,4 16.5,8.5 21.2,8.5 17.4,11.3 18.8,15.8 15,13 11.2,15.8 12.6,11.3 8.8,8.5 13.5,8.5" fill="#ffff00"/>
                        </svg>
                    </template>
                    <template x-if="currentLang === '中文'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 30 20">
                            <rect width="30" height="20" fill="#ee1c25"/>
                            <polygon points="5,3 5.6,4.8 7.4,4.8 5.9,5.9 6.5,7.7 5,6.6 3.5,7.7 4.1,5.9 2.6,4.8 4.4,4.8" fill="#ffde00"/>
                        </svg>
                    </template>
                    <template x-if="currentLang === 'English'">
                        <svg class="w-5 h-3.5 rounded-xs object-cover border border-slate-200 shrink-0" viewBox="0 0 60 30">
                            <clipPath id="s_uk_log"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                            <clipPath id="t_uk_log"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                            <g clip-path="url(#s_uk_log)">
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_log)"/>
                                <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                                <path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/>
                            </g>
                        </svg>
                    </template>

                    <span class="font-bold zh-text" x-text="currentLang">Việt Nam</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': langOpen }"></i>
                </button>

                <!-- Menu Option xổ xuống -->
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
                            <clipPath id="s_uk2_log"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                            <clipPath id="t_uk2_log"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                            <g clip-path="url(#s_uk2_log)">
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk2_log)"/>
                                <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                                <path d="M30,0 v30 M0,15 h60" stroke="#cf142b" stroke-width="6"/>
                            </g>
                        </svg>
                        <span>English</span> 
                        <i x-show="currentLang === 'English'" class="fa-solid fa-check ml-auto text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- Bật/Tắt Chế độ Tối -->
            <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile" title="Bật/Tắt Chế độ Tối">
                <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
            </button>
        </div>
    </header>

    <!-- Nội dung chính Split-Screen Layout -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-10 my-auto">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Cột Trái (Hero Motivation Graphic Banner) -->
            <div class="hidden lg:flex lg:col-span-6 flex-col justify-between p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-[#fff7f4] via-[#fff2ee] to-[#fdeae3] dark:from-[#1e1917] dark:via-[#1c1816] dark:to-[#241c19] border border-[#fcdccf] dark:border-[#382622] relative overflow-hidden min-h-[520px]">
                
                <!-- Graphic Floating Chinese Cards -->
                <div class="space-y-4 relative z-10">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 dark:bg-slate-800/80 border border-[#fcdccf] dark:border-slate-700 text-[#e07a5f] text-xs font-bold shadow-xs">
                        <i class="fa-solid fa-fire text-amber-500"></i> Lộ trình HSK 1 - HSK 6 Thông minh
                    </div>

                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                        Chinh phục Tiếng Trung dễ dàng hơn mỗi ngày
                    </h1>

                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                        Hệ thống học từ vựng thông minh, bài thi thử HSK sát đề thật và cộng đồng luyện tập sôi nổi cùng XIAOMU.
                    </p>
                </div>

                <!-- Thẻ từ vựng Floating Art Card -->
                <div class="my-6 relative z-10 grid grid-cols-2 gap-3">
                    <div class="p-4 rounded-2xl bg-white/90 dark:bg-[#221d1b]/90 border border-[#e8e2d9] dark:border-[#332b28] shadow-sm animate-float">
                        <span class="text-2xl font-extrabold text-[#e07a5f] zh-text">坚持</span>
                        <p class="text-xs font-bold text-slate-800 dark:text-white mt-1">jiān chí</p>
                        <p class="text-[11px] text-slate-500 font-normal">Kiên trì tới cùng</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/90 dark:bg-[#221d1b]/90 border border-[#e8e2d9] dark:border-[#332b28] shadow-sm animate-float-delayed">
                        <span class="text-2xl font-extrabold text-emerald-500 zh-text">加油</span>
                        <p class="text-xs font-bold text-slate-800 dark:text-white mt-1">jiā yóu</p>
                        <p class="text-[11px] text-slate-500 font-normal">Cố gắng lên!</p>
                    </div>
                </div>

                <!-- Footer Feature Bullet -->
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 dark:text-slate-400 pt-4 border-t border-[#fcdccf] dark:border-[#382622] relative z-10">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-500"></i> Đề thi HSK chuẩn</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-500"></i> Chuỗi 90 ngày học</span>
                </div>
            </div>

            <!-- Cột Phải (Card Form Đăng nhập) -->
            <div class="lg:col-span-6 w-full max-w-md mx-auto">
                <div class="lms-card p-6 sm:p-10 space-y-6">
                    
                    <!-- Header Form -->
                    <div class="text-center space-y-2">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Chào mừng quay lại! 👋</h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-normal">
                            Đăng nhập tài khoản XIAOMU để tiếp tục chuỗi học tập
                        </p>
                    </div>

                    <!-- Flash Messages -->
                    @if (session('status'))
                        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-xs font-bold text-rose-600 dark:text-rose-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form Đăng nhập -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Ô Email -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Địa chỉ Email</label>
                            <div class="relative">
                                <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="example@gmail.com" 
                                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('email') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-10 pr-4 py-3 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                            </div>
                            @error('email')
                                <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ô Mật khẩu -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Mật khẩu</label>
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#e07a5f] hover:underline">Quên mật khẩu?</a>
                            </div>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••" 
                                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('password') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-10 pr-10 py-3 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ghi nhớ đăng nhập -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#e07a5f] focus:ring-[#e07a5f]">
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Ghi nhớ đăng nhập</span>
                            </label>
                        </div>

                        <!-- Nút Submit Đăng nhập -->
                        <button type="submit" class="w-full py-3 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs sm:text-sm shadow-md transition-all btn-tactile hover:shadow-lg mt-2">
                            Đăng nhập ngay
                        </button>
                    </form>

                    <!-- Divider Hoặc Đăng nhập bằng -->
                    <div class="relative flex items-center justify-center my-4">
                        <div class="border-t border-[#e8e2d9] dark:border-[#2d2926] w-full"></div>
                        <span class="bg-white dark:bg-[#181615] px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider absolute">Hoặc</span>
                    </div>

                    <!-- Social Login (Chỉ giữ lại Google) -->
                    <div>
                        <button type="button" class="w-full flex items-center justify-center gap-2.5 py-3 px-4 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-[#282422] transition-colors btn-tactile shadow-xs">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Đăng nhập với Google</span>
                        </button>
                    </div>

                    <!-- Footer Link Đăng ký -->
                    <div class="text-center pt-2">
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Chưa có tài khoản? 
                            <a href="{{ route('register') }}" class="font-bold text-[#e07a5f] hover:underline">Tạo tài khoản mới</a>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>
