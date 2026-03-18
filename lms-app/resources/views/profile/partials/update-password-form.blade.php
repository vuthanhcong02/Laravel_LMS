<section>
    <header>
        <h2 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-2">
            Đổi mật khẩu
        </h2>
        <p class="text-slate-600 dark:text-slate-400 mb-6">
            Đảm bảo tài khoản của bạn đang sử dụng mật khẩu dài, ngẫu nhiên để luôn được an toàn.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mật khẩu hiện tại</label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" 
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mật khẩu mới</label>
            <input id="update_password_password" name="password" type="password" 
                   class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Xác nhận mật khẩu</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" 
                    class="px-6 py-3 bg-primary text-white font-bold rounded-lg hover:opacity-90 shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-lg">key</span>
                Đổi mật khẩu
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm font-medium text-green-600 dark:text-green-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Đã lưu thành công.
                </p>
            @endif
        </div>
    </form>
</section>
