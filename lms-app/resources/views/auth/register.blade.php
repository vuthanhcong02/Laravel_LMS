<!DOCTYPE html>
<html lang="vi" class="h-full" :class="{ 'dark': darkMode }" x-data="{ langOpen: false, currentLang: 'Việt Nam', darkMode: false, showPassword: false, showConfirmPassword: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo tài khoản mới - XIAOMU Tiếng Trung LMS</title>
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
        
        .zh-text { font-family: 'Inter', 'Noto Sans SC', sans-serif; }

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
                            <clipPath id="s_uk_reg"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                            <clipPath id="t_uk_reg"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                            <g clip-path="url(#s_uk_reg)">
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk_reg)"/>
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
                            <clipPath id="s_uk2_reg"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                            <clipPath id="t_uk2_reg"><path d="M30,15 L60,0 v30 z M30,15 L0,30 v-30 z M30,15 L0,0 h60 z M30,15 L60,30 h-60 z"/></clipPath>
                            <g clip-path="url(#s_uk2_reg)">
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="4" clip-path="url(#t_uk2_reg)"/>
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

    <!-- Nội dung chính Split-Screen Layout -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-10 my-auto">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Cột Trái (Hero Motivation Graphic Banner) -->
            <div class="hidden lg:flex lg:col-span-6 flex-col justify-between p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-[#fff7f4] via-[#fff2ee] to-[#fdeae3] dark:from-[#1e1917] dark:via-[#1c1816] dark:to-[#241c19] border border-[#fcdccf] dark:border-[#382622] relative overflow-hidden min-h-[560px]">
                
                <div class="space-y-4 relative z-10">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 dark:bg-slate-800/80 border border-[#fcdccf] dark:border-slate-700 text-[#e07a5f] text-xs font-bold shadow-xs">
                        <i class="fa-solid fa-user-plus text-emerald-500"></i> Miễn phí 100% Khám phá
                    </div>

                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                        Tạo tài khoản & Bắt đầu hành trình chinh phục HSK
                    </h1>

                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                        Gia nhập cộng đồng hơn 5,000+ học viên luyện thi HSK 1 - HSK 6 cùng các bộ công cụ thông minh chuẩn quốc tế.
                    </p>
                </div>

                <!-- Thẻ từ vựng Floating Art Card -->
                <div class="my-6 relative z-10 grid grid-cols-2 gap-3">
                    <div class="p-4 rounded-2xl bg-white/90 dark:bg-[#221d1b]/90 border border-[#e8e2d9] dark:border-[#332b28] shadow-sm animate-float">
                        <span class="text-2xl font-extrabold text-[#e07a5f] zh-text">梦想</span>
                        <p class="text-xs font-bold text-slate-800 dark:text-white mt-1">mèng xiǎng</p>
                        <p class="text-[11px] text-slate-500 font-normal">Ước mơ & Mục tiêu</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/90 dark:bg-[#221d1b]/90 border border-[#e8e2d9] dark:border-[#332b28] shadow-sm animate-float-delayed">
                        <span class="text-2xl font-extrabold text-amber-500 zh-text">成功</span>
                        <p class="text-xs font-bold text-slate-800 dark:text-white mt-1">chéng gōng</p>
                        <p class="text-[11px] text-slate-500 font-normal">Đạt đỉnh thành công</p>
                    </div>
                </div>

                <!-- Footer Feature Bullet -->
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 dark:text-slate-400 pt-4 border-t border-[#fcdccf] dark:border-[#382622] relative z-10">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-500"></i> Thi thử HSK miễn phí</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-500"></i> Bảng Pinyin phát âm</span>
                </div>
            </div>

            <!-- Cột Phải (Card Form Đăng ký) -->
            <div class="lg:col-span-6 w-full max-w-md mx-auto">
                <div class="lms-card p-6 sm:p-10 space-y-5">
                    
                    <!-- Header Form -->
                    <div class="text-center space-y-1.5">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Tạo tài khoản mới ✨</h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-normal">
                            Bắt đầu hành trình học tiếng Trung ngay hôm nay
                        </p>
                    </div>

                    <!-- Form Đăng ký -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                        @csrf

                        <!-- Ô Họ và Tên -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Họ và Tên') }}</label>
                            <div class="relative">
                                <i class="fa-regular fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="{{ __('Nhập họ và tên của bạn') }}" 
                                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('name') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                            </div>
                            @error('name')
                                <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ô Email -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Địa chỉ Email') }}</label>
                            <div class="relative">
                                <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="{{ __('Nhập địa chỉ email của bạn') }}" 
                                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('email') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                            </div>
                            @error('email')
                                <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ô Mật khẩu -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Mật khẩu') }}</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="{{ __('Nhập mật khẩu của bạn') }}" 
                                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('password') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-10 pr-10 py-2.5 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ô Xác nhận Mật khẩu -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Nhập lại Mật khẩu') }}</label>
                            <div class="relative">
                                <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required placeholder="{{ __('Nhập lại mật khẩu để xác nhận') }}" 
                                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-10 pr-10 py-2.5 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <i class="fa-solid" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Điều khoản sử dụng -->
                        <div class="pt-1">
                            <label class="flex items-start gap-2 cursor-pointer select-none">
                                <input type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-slate-300 text-[#e07a5f] focus:ring-[#e07a5f]">
                                <span class="text-xs font-medium text-slate-600 dark:text-slate-400 leading-tight">
                                    Tôi đồng ý với <a href="#" class="text-[#e07a5f] font-bold hover:underline">Điều khoản dịch vụ</a> và <a href="#" class="text-[#e07a5f] font-bold hover:underline">Chính sách bảo mật</a>.
                                </span>
                            </label>
                        </div>

                        <!-- Nút Submit Đăng ký -->
                        <button type="submit" class="w-full py-3 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs sm:text-sm shadow-md transition-all btn-tactile hover:shadow-lg mt-1">
                            Tạo tài khoản miễn phí
                        </button>
                    </form>

                    <!-- Footer Link Đăng nhập -->
                    <div class="text-center pt-1 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Đã có tài khoản? 
                            <a href="{{ route('login') }}" class="font-bold text-[#e07a5f] hover:underline">Đăng nhập ngay</a>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>
