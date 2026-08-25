<!-- MODAL POPUP ĐĂNG NHẬP / ĐĂNG KÝ XIAOMU LMS -->
<div x-show="authModalOpen" 
     x-cloak
     class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop làm mờ có hiệu ứng Fade -->
    <div x-show="authModalOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="authModalOpen = false"
         class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-md"></div>

    <!-- Hộp thoại Modal chính có hiệu ứng Zoom In / Scale -->
    <div x-show="authModalOpen"
         x-transition:enter="transition-all ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition-all ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="relative w-full max-w-md bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-6 sm:p-8 shadow-2xl z-10 my-auto">
        
        <!-- Nút Đóng Modal (Góc trên phải) -->
        <button @click="authModalOpen = false" 
                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[#f8f6f3] dark:bg-[#23201e] text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center justify-center text-xs transition-colors btn-tactile"
                title="Đóng">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <!-- Brand Header Logo & Tiêu đề đầy đủ không bị cắt -->
        <div class="flex flex-col items-center text-center pt-1 mb-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#fff2ee] to-[#fdeae3] dark:from-[#2a221f] dark:to-[#1e1715] border border-[#fcdccf] dark:border-[#42271f] p-1.5 shadow-sm mb-3 flex items-center justify-center shrink-0">
                <img src="{{ asset('logo.png') }}" alt="XIAOMU Logo" class="w-full h-full rounded-xl object-cover">
            </div>
            
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                <span x-text="authModalTab === 'login' ? 'Đăng nhập' : 'Tạo tài khoản mới'"></span> 
                <span class="text-[#e07a5f]">XIAOMU</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" 
               x-text="authModalTab === 'login' ? 'Tiếp tục hành trình chinh phục tiếng Trung của bạn' : 'Bắt đầu lộ trình luyện thi HSK thông minh hôm nay'">
            </p>
        </div>

        <!-- ======================= 1. FORM ĐĂNG NHẬP ======================= -->
        <div x-show="authModalTab === 'login'" class="space-y-4">
            
            <!-- Form Đăng nhập Email -->
            <form @submit.prevent="authLoading = true; setTimeout(() => { isLoggedIn = true; authLoading = false; authModalOpen = false; }, 700)" class="space-y-3.5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Địa chỉ Email</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" 
                               x-model="authEmail"
                               required
                               placeholder="name@example.com" 
                               class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Mật khẩu</label>
                        <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-[#e07a5f] hover:underline">
                            Quên mật khẩu?
                        </a>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input :type="authShowPassword ? 'text' : 'password'" 
                               x-model="authPassword"
                               required
                               placeholder="Nhập mật khẩu..." 
                               class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        <button type="button" 
                                @click="authShowPassword = !authShowPassword" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                            <i class="fa-regular" :class="authShowPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" x-model="authRemember" class="rounded text-[#e07a5f] focus:ring-[#e07a5f]/20 border-slate-300 dark:border-slate-700 dark:bg-slate-800">
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <!-- Nút Submit Đăng nhập -->
                <button type="submit" 
                        class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] hover:from-[#c86349] hover:to-[#b55238] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/25 hover:shadow-lg transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer">
                    <template x-if="authLoading">
                        <i class="fa-solid fa-circle-notch animate-spin text-sm"></i>
                    </template>
                    <span x-text="authLoading ? 'Đang xử lý...' : 'Đăng nhập ngay'"></span>
                </button>
            </form>

            <!-- Divider cân đối 2 bên -->
            <div class="flex items-center gap-3 my-4">
                <div class="h-px bg-[#e8e2d9] dark:border-[#2d2926] dark:bg-[#2d2926] flex-1"></div>
                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">hoặc</span>
                <div class="h-px bg-[#e8e2d9] dark:border-[#2d2926] dark:bg-[#2d2926] flex-1"></div>
            </div>

            <!-- Đăng nhập bằng Google (Để ở dưới) -->
            <a href="{{ route('socialite.redirect', 'google') }}" 
               class="w-full py-2.5 px-4 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] hover:bg-[#f8f6f3] dark:hover:bg-[#2a2624] text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center justify-center gap-3 transition-all btn-tactile shadow-xs">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Tiếp tục với Google</span>
            </a>

            <!-- Chuyển sang Đăng ký -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Chưa có tài khoản? 
                    <button type="button" @click="authModalTab = 'register'" class="font-bold text-[#e07a5f] hover:underline cursor-pointer">
                        Đăng ký tài khoản
                    </button>
                </p>
            </div>
        </div>

        <!-- ======================= 2. FORM ĐĂNG KÝ ======================= -->
        <div x-show="authModalTab === 'register'" class="space-y-4" style="display: none;">
            
            <!-- Form Đăng ký Email -->
            <form @submit.prevent="authLoading = true; setTimeout(() => { isLoggedIn = true; authLoading = false; authModalOpen = false; }, 700)" class="space-y-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Họ và tên</label>
                    <div class="relative">
                        <i class="fa-regular fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               x-model="authName"
                               required
                               placeholder="Nguyễn Văn A" 
                               class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Địa chỉ Email</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" 
                               x-model="authEmail"
                               required
                               placeholder="name@example.com" 
                               class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Mật khẩu</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input :type="authShowPassword ? 'text' : 'password'" 
                               x-model="authPassword"
                               required
                               placeholder="Tối thiểu 8 ký tự..." 
                               class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        <button type="button" 
                                @click="authShowPassword = !authShowPassword" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                            <i class="fa-regular" :class="authShowPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-start gap-2 pt-1">
                    <input type="checkbox" required class="mt-0.5 rounded text-[#e07a5f] focus:ring-[#e07a5f]/20 border-slate-300 dark:border-slate-700 dark:bg-slate-800">
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight select-none">
                        Tôi đồng ý với <a href="#" class="text-[#e07a5f] underline">Điều khoản dịch vụ</a> và <a href="#" class="text-[#e07a5f] underline">Chính sách bảo mật</a> của XIAOMU.
                    </span>
                </div>

                <!-- Nút Submit Đăng ký -->
                <button type="submit" 
                        class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] hover:from-[#c86349] hover:to-[#b55238] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/25 hover:shadow-lg transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer">
                    <template x-if="authLoading">
                        <i class="fa-solid fa-circle-notch animate-spin text-sm"></i>
                    </template>
                    <span x-text="authLoading ? 'Đang tạo tài khoản...' : 'Tạo tài khoản miễn phí'"></span>
                </button>
            </form>

            <!-- Divider cân đối 2 bên -->
            <div class="flex items-center gap-3 my-4">
                <div class="h-px bg-[#e8e2d9] dark:border-[#2d2926] dark:bg-[#2d2926] flex-1"></div>
                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">hoặc</span>
                <div class="h-px bg-[#e8e2d9] dark:border-[#2d2926] dark:bg-[#2d2926] flex-1"></div>
            </div>

            <!-- Đăng ký nhanh với Google (Để ở dưới) -->
            <a href="{{ route('socialite.redirect', 'google') }}" 
               class="w-full py-2.5 px-4 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] hover:bg-[#f8f6f3] dark:hover:bg-[#2a2624] text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center justify-center gap-3 transition-all btn-tactile shadow-xs">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Đăng ký nhanh với Google</span>
            </a>

            <!-- Chuyển sang Đăng nhập -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Đã có tài khoản? 
                    <button type="button" @click="authModalTab = 'login'" class="font-bold text-[#e07a5f] hover:underline cursor-pointer">
                        Đăng nhập ngay
                    </button>
                </p>
            </div>
        </div>

    </div>
</div>
