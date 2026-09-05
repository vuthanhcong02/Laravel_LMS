<div x-show="authModalOpen" x-cloak
    class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto" style="display: none;">
    <div x-show="authModalOpen" x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="authModalOpen = false"
        class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-md"></div>
    <div x-show="authModalOpen" x-transition:enter="transition-all ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition-all ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full max-w-md bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-6 sm:p-8 shadow-2xl z-10 my-auto">
        <button @click="authModalOpen = false"
            class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[#f8f6f3] dark:bg-[#23201e] text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center justify-center text-xs transition-colors btn-tactile cursor-pointer"
            title="Đóng">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
        <div class="flex flex-col items-center text-center pt-1 mb-5">
            <div
                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#fff2ee] to-[#fdeae3] dark:from-[#2a221f] dark:to-[#1e1715] border border-[#fcdccf] dark:border-[#42271f] p-1.5 shadow-sm mb-3 flex items-center justify-center shrink-0">
                <img src="{{ asset('logo.png') }}" alt="XIAOMU Logo" class="w-full h-full rounded-xl object-cover">
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                <span
                    x-text="authModalTab === 'login' ? 'Đăng nhập' : (authModalTab === 'register' ? 'Tạo tài khoản mới' : 'Khôi phục mật khẩu')"></span>
                <span class="text-[#e07a5f]">XIAOMU</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"
                x-text="authModalTab === 'login' ? 'Tiếp tục hành trình chinh phục tiếng Trung của bạn' : (authModalTab === 'register' ? 'Bắt đầu lộ trình luyện thi HSK thông minh hôm nay' : 'Nhập email của bạn để nhận liên kết đặt lại mật khẩu')">
            </p>
        </div>
        @if (session('status'))
            <div
                class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-sm shrink-0"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div
                class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 text-xs font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                        {{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div x-show="authModalTab === 'login'" class="space-y-4">
            <form action="{{ route('login') }}" method="POST" class="space-y-3.5">
                @csrf
                <input type="hidden" name="redirect_to" :value="authRedirectUrl">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Địa chỉ
                        Email</label>
                    <div class="relative">
                        <i
                            class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" name="email" x-model="authEmail" value="{{ old('email') }}" required
                            placeholder="name@example.com"
                            class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Mật khẩu</label>
                        <button type="button" @click="authModalTab = 'forgot'"
                            class="text-[11px] font-semibold text-[#e07a5f] hover:underline cursor-pointer">
                            Quên mật khẩu?
                        </button>
                    </div>
                    <div class="relative">
                        <i
                            class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input :type="authShowPassword ? 'text' : 'password'" name="password" x-model="authPassword"
                            required placeholder="Nhập mật khẩu..."
                            class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        <button type="button" @click="authShowPassword = !authShowPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                            <i class="fa-regular pointer-events-none"
                                :class="authShowPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center">
                    <label
                        class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" x-model="authRemember"
                            class="rounded text-[#e07a5f] focus:ring-[#e07a5f]/20 border-slate-300 dark:border-slate-700 dark:bg-slate-800">
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                </div>
                <button type="submit"
                    class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] hover:from-[#c86349] hover:to-[#b55238] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/25 hover:shadow-lg transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer">
                    <span>Đăng nhập ngay</span>
                </button>
            </form>
            <div class="flex items-center gap-3 my-4">
                <div class="h-px bg-[#e8e2d9] dark:bg-[#2d2926] flex-1"></div>
                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">hoặc</span>
                <div class="h-px bg-[#e8e2d9] dark:bg-[#2d2926] flex-1"></div>
            </div>
            <a :href="'{{ route('socialite.redirect', ['provider' => 'google'], false) }}' + (authRedirectUrl ? '?redirect_to=' + encodeURIComponent(authRedirectUrl) : '')"
                class="w-full py-2.5 px-4 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] hover:bg-[#f8f6f3] dark:hover:bg-[#2a2624] text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center justify-center gap-3 transition-all btn-tactile shadow-xs cursor-pointer">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                <span>{{ __('Tiếp tục với Google') }}</span>
            </a>
            <div class="text-center pt-2">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Chưa có tài khoản?') }}
                    <button type="button" @click="authModalTab = 'register'"
                        class="font-bold text-[#e07a5f] hover:underline cursor-pointer">
                        {{ __('Đăng ký tài khoản') }}
                    </button>
                </p>
            </div>
        </div>
        <div x-show="authModalTab === 'register'" class="space-y-4" style="display: none;">
            <form action="{{ route('register') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="redirect_to" :value="authRedirectUrl">
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Họ và tên đệm') }}</label>
                        <input type="text" name="first_name" x-model="authFirstName"
                            value="{{ old('first_name') }}" required placeholder="{{ __('Nhập họ và tên đệm') }}"
                            class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl px-3 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Tên') }}</label>
                        <input type="text" name="last_name" x-model="authLastName"
                            value="{{ old('last_name') }}" required placeholder="{{ __('Nhập tên của bạn') }}"
                            class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl px-3 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Địa chỉ Email') }}</label>
                    <div class="relative">
                        <i
                            class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" name="email" x-model="authEmail" value="{{ old('email') }}"
                            required placeholder="{{ __('Nhập địa chỉ email của bạn') }}"
                            class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Mật khẩu') }}</label>
                    <div class="relative">
                        <i
                            class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input :type="authShowPassword ? 'text' : 'password'" name="password" x-model="authPassword"
                            required placeholder="{{ __('Nhập mật khẩu của bạn') }}"
                            class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                        <button type="button" @click="authShowPassword = !authShowPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                            <i class="fa-regular pointer-events-none"
                                :class="authShowPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-start gap-2 pt-1">
                    <input type="checkbox" required
                        class="mt-0.5 rounded text-[#e07a5f] focus:ring-[#e07a5f]/20 border-slate-300 dark:border-slate-700 dark:bg-slate-800">
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight select-none">
                        {{ __('Tôi đồng ý với') }} <a href="#" class="text-[#e07a5f] underline">{{ __('Điều khoản dịch vụ') }}</a> {{ __('và') }} <a
                            href="#" class="text-[#e07a5f] underline">{{ __('Chính sách bảo mật') }}</a> {{ __('của XIAOMU.') }}
                    </span>
                </div>
                <button type="submit"
                    class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] hover:from-[#c86349] hover:to-[#b55238] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/25 hover:shadow-lg transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer">
                    <span>{{ __('Tạo tài khoản miễn phí') }}</span>
                </button>
            </form>
            <div class="flex items-center gap-3 my-4">
                <div class="h-px bg-[#e8e2d9] dark:bg-[#2d2926] flex-1"></div>
                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">{{ __('hoặc') }}</span>
                <div class="h-px bg-[#e8e2d9] dark:bg-[#2d2926] flex-1"></div>
            </div>
            <a :href="'{{ route('socialite.redirect', ['provider' => 'google'], false) }}' + (authRedirectUrl ? '?redirect_to=' + encodeURIComponent(authRedirectUrl) : '')"
                class="w-full py-2.5 px-4 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] hover:bg-[#f8f6f3] dark:hover:bg-[#2a2624] text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center justify-center gap-3 transition-all btn-tactile shadow-xs cursor-pointer">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                <span>{{ __('Đăng ký nhanh với Google') }}</span>
            </a>
            <div class="text-center pt-2">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Đã có tài khoản?
                    <button type="button" @click="authModalTab = 'login'"
                        class="font-bold text-[#e07a5f] hover:underline cursor-pointer">
                        Đăng nhập ngay
                    </button>
                </p>
            </div>
        </div>
        <div x-show="authModalTab === 'forgot'" class="space-y-4" style="display: none;" x-data="{
            forgotLoading: false,
            forgotStatus: null,
            forgotError: null,
            async submitForgot() {
                if (this.forgotLoading) return;
                this.forgotLoading = true;
                this.forgotStatus = null;
                this.forgotError = null;
                try {
                    const response = await fetch('{{ route('password.email') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: authEmail })
                    });
                    const data = await response.json();
                    if (response.ok) {
                        this.forgotStatus = data.status || 'Đã gửi liên kết khôi phục.';
                    } else {
                        this.forgotError = data.message || (data.errors && data.errors.email ? data.errors.email[0] : 'Có lỗi xảy ra.');
                    }
                } catch (e) {
                    this.forgotError = 'Lỗi kết nối mạng, vui lòng thử lại.';
                }
                this.forgotLoading = false;
            }
        }">
            <div
                class="p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                <i class="fa-solid fa-circle-info text-[#e07a5f] mr-1"></i>
                Vui lòng nhập địa chỉ email bạn đã sử dụng để đăng ký tài khoản. Hệ thống sẽ gửi một đường dẫn đặt lại
                mật khẩu an toàn tới hòm thư của bạn.
            </div>
            <div x-show="forgotStatus" x-cloak
                class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 text-xs font-semibold flex items-center gap-2 transition-all">
                <i class="fa-solid fa-circle-check text-sm shrink-0"></i>
                <span x-text="forgotStatus"></span>
            </div>
            <div x-show="forgotError" x-cloak
                class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 text-xs font-semibold flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-circle-exclamation text-[10px] shrink-0"></i>
                <span x-text="forgotError"></span>
            </div>
            <form @submit.prevent="submitForgot" class="space-y-3.5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Địa chỉ
                        Email</label>
                    <div class="relative">
                        <i
                            class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" x-model="authEmail" required placeholder="name@example.com"
                            class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all"
                            :disabled="forgotLoading">
                    </div>
                </div>
                <button type="submit" :disabled="forgotLoading"
                    class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] hover:from-[#c86349] hover:to-[#b55238] disabled:from-slate-400 disabled:to-slate-500 text-white text-xs font-bold shadow-md shadow-[#e07a5f]/25 hover:shadow-lg transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer">
                    <i x-show="!forgotLoading" class="fa-regular fa-paper-plane text-xs"></i>
                    <i x-show="forgotLoading" class="fa-solid fa-spinner fa-spin text-xs" x-cloak></i>
                    <span x-text="forgotLoading ? 'Đang gửi...' : 'Gửi liên kết khôi phục'"></span>
                </button>
            </form>
            <div class="text-center pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                <button type="button" @click="authModalTab = 'login'"
                    class="font-bold text-xs text-[#e07a5f] hover:underline cursor-pointer inline-flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Quay lại Đăng nhập</span>
                </button>
            </div>
        </div>
    </div>
</div>
