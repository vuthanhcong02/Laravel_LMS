<section>
    <header>
        <h2 class="font-heading text-lg sm:text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-1.5 sm:mb-2">
            Đổi mật khẩu
        </h2>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-6">
            Đảm bảo tài khoản của bạn đang sử dụng mật khẩu dài, ngẫu nhiên để luôn được an toàn.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4 sm:space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Mật khẩu hiện tại</label>
            <div class="relative flex items-center">
                <input id="update_password_current_password" name="current_password" type="password" 
                       class="w-full px-3.5 sm:px-4 pr-11 py-2.5 sm:py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-xs sm:text-sm text-slate-900 dark:text-white" 
                       autocomplete="current-password" />
                <button type="button" 
                        onclick="togglePasswordVisibility('update_password_current_password', this)"
                        class="absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-primary transition-colors focus:outline-none"
                        aria-label="Hiện/ẩn mật khẩu">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px] select-none">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Mật khẩu mới</label>
            <div class="relative flex items-center">
                <input id="update_password_password" name="password" type="password" 
                       class="w-full px-3.5 sm:px-4 pr-11 py-2.5 sm:py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-xs sm:text-sm text-slate-900 dark:text-white" 
                       autocomplete="new-password" />
                <button type="button" 
                        onclick="togglePasswordVisibility('update_password_password', this)"
                        class="absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-primary transition-colors focus:outline-none"
                        aria-label="Hiện/ẩn mật khẩu">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px] select-none">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Xác nhận mật khẩu</label>
            <div class="relative flex items-center">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                       class="w-full px-3.5 sm:px-4 pr-11 py-2.5 sm:py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-xs sm:text-sm text-slate-900 dark:text-white" 
                       autocomplete="new-password" />
                <button type="button" 
                        onclick="togglePasswordVisibility('update_password_password_confirmation', this)"
                        class="absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-primary transition-colors focus:outline-none"
                        aria-label="Hiện/ẩn mật khẩu">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px] select-none">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 mt-6 sm:mt-8">
            <button type="submit" 
                    class="px-5 sm:px-6 py-2.5 sm:py-3 bg-primary text-white text-xs sm:text-sm font-bold rounded-lg hover:opacity-90 shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base sm:text-lg">key</span>
                Đổi mật khẩu
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-xs sm:text-sm font-medium text-green-600 dark:text-green-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Đã lưu thành công.
                </p>
            @endif
        </div>
    </form>
</section>
