<section x-data="passwordUpdateForm()">
    <header class="mb-6">
        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-[#e07a5f]"></i>
            <span>{{ __('Đổi mật khẩu bảo vệ') }}</span>
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Đảm bảo tài khoản của bạn đang sử dụng mật khẩu mạnh, kết hợp chữ cái, số và ký tự đặc biệt để tối ưu bảo mật.') }}
        </p>
    </header>
    <form id="passwordForm" @submit.prevent="submitPasswordForm" class="space-y-4">
        @csrf
        @method('put')
        <div>
            <label for="update_password_current_password" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Mật khẩu hiện tại') }}</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input id="update_password_current_password" 
                       name="current_password" 
                       :type="showCurrent ? 'text' : 'password'" 
                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @if($errors->updatePassword->get('current_password')) border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @endif rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all font-medium" 
                       placeholder="{{ __('Nhập mật khẩu đang sử dụng...') }}"
                       autocomplete="current-password" />
                <button type="button" 
                        @click="showCurrent = !showCurrent"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs cursor-pointer">
                    <i class="fa-regular pointer-events-none" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <p x-show="errors.current_password" x-text="errors.current_password" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;"></p>
        </div>
        <div>
            <label for="update_password_password" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Mật khẩu mới') }}</label>
            <div class="relative">
                <i class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input id="update_password_password" 
                       name="password" 
                       :type="showNew ? 'text' : 'password'" 
                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @if($errors->updatePassword->get('password')) border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @endif rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all font-medium" 
                       placeholder="{{ __('Tối thiểu 8 ký tự...') }}"
                       autocomplete="new-password" />
                <button type="button" 
                        @click="showNew = !showNew"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs cursor-pointer">
                    <i class="fa-regular pointer-events-none" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <p x-show="errors.password" x-text="errors.password" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;"></p>
        </div>
        <div>
            <label for="update_password_password_confirmation" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Xác nhận mật khẩu mới') }}</label>
            <div class="relative">
                <i class="fa-solid fa-check-double absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input id="update_password_password_confirmation" 
                       name="password_confirmation" 
                       :type="showConfirm ? 'text' : 'password'" 
                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @if($errors->updatePassword->get('password_confirmation')) border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @endif rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all font-medium" 
                       placeholder="{{ __('Nhập lại mật khẩu mới...') }}"
                       autocomplete="new-password" />
                <button type="button" 
                        @click="showConfirm = !showConfirm"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs cursor-pointer">
                    <i class="fa-regular pointer-events-none" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;"></p>
        </div>
        <div class="flex items-center gap-3 pt-3 border-t border-[#e8e2d9] dark:border-[#2d2926]">
            <button type="submit" :disabled="loading"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] hover:from-[#c86349] hover:to-[#b55238] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/25 hover:shadow-lg transition-all btn-tactile flex items-center gap-2 cursor-pointer disabled:opacity-75">
                <i x-show="!loading" class="fa-solid fa-lock text-xs"></i>
                <i x-show="loading" class="fa-solid fa-spinner fa-spin text-xs" style="display: none;"></i>
                <span x-text="loading ? '{{ __('Đang cập nhật...') }}' : '{{ __('Cập nhật mật khẩu') }}'">{{ __('Cập nhật mật khẩu') }}</span>
            </button>
            <div x-show="successMessage" x-transition x-init="$watch('successMessage', val => { if (val) setTimeout(() => successMessage = '', 3000) })" style="display: none;"
                 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-900/50">
                <i class="fa-solid fa-circle-check text-xs"></i>
                <span x-text="successMessage"></span>
            </div>
        </div>
    </form>
</section>
<script>
    function passwordUpdateForm() {
        return {
            showCurrent: false, 
            showNew: false, 
            showConfirm: false,
            loading: false,
            errors: {},
            successMessage: '',
            async submitPasswordForm() {
                this.loading = true;
                this.errors = {};
                this.successMessage = '';
                let form = document.getElementById('passwordForm');
                let formData = new FormData(form);
                try {
                    const response = await fetch('{{ route("password.update") }}', {
                        method: 'POST', // Blade has @method('put') which adds _method=put to FormData
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        this.successMessage = data.message;
                        form.reset();
                    } else if (response.status === 422) {
                        for (const key in data.errors) {
                            this.errors[key] = data.errors[key][0];
                        }
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
