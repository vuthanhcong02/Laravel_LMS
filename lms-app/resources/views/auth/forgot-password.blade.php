<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ langOpen: false, currentLang: 'Việt Nam', darkMode: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - XIAOMU Tiếng Trung LMS</title>
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
                            <clipPath id="s_uk_for"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                            <clipPath id="t_uk_for"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                            <g clip-path="url(#s_uk_for)">
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_for)"/>
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
                            <clipPath id="s_uk2_for"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                            <clipPath id="t_uk2_for"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                            <g clip-path="url(#s_uk2_for)">
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk2_for)"/>
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
            <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs transition-colors btn-tactile">
                <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
            </button>
        </div>
    </header>

    <!-- Nội dung chính Form Quên Mật Khẩu -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-auto">
        <div class="w-full max-w-md">
            <div class="lms-card p-6 sm:p-10 space-y-6">
                
                <!-- Icon & Header Form -->
                <div class="text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] border border-[#fcdccf] dark:border-[#42271f] flex items-center justify-center text-xl mx-auto shadow-xs">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Quên mật khẩu? 🔒</h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-normal leading-relaxed">
                        Đừng lo lắng! Nhập địa chỉ Email đăng ký của bạn và chúng tôi sẽ gửi liên kết khôi phục mật khẩu.
                    </p>
                </div>

                <!-- Flash Messages -->
                @if (session('status'))
                    <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-base"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Form Quên mật khẩu -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Ô Email -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Địa chỉ Email đã đăng ký</label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="example@gmail.com" 
                                   class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('email') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-10 pr-4 py-3 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        </div>
                        @error('email')
                            <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nút Submit Gửi Link -->
                    <button type="submit" class="w-full py-3 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs sm:text-sm shadow-md transition-all btn-tactile hover:shadow-lg">
                        Gửi liên kết khôi phục
                    </button>
                </form>

                <!-- Footer Quay lại Đăng nhập -->
                <div class="text-center pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#f4978e] transition-colors">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span>Quay lại trang Đăng nhập</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

</body>
</html>
