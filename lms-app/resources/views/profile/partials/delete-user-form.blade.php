<section class="space-y-6">
    <header>
        <h2 class="font-heading text-2xl font-bold text-red-600 dark:text-red-400 mb-2">
            Xóa tài khoản
        </h2>
        <p class="text-slate-600 dark:text-slate-400 mb-6">
            Sau khi tài khoản của bạn bị xóa, tất cả các tài nguyên và dữ liệu kinh nghiệm học tập sẽ bị xóa vĩnh viễn. 
            Trước khi xóa tài khoản, vui lòng sao lưu bất kỳ dữ liệu hoặc thông tin nào mà bạn muốn giữ lại.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 shadow-lg shadow-red-600/30 transition-all flex items-center justify-center gap-2"
    >
        <span class="material-symbols-outlined text-lg">delete_forever</span>
        Xóa tài khoản
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white dark:bg-slate-800 rounded-2xl">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                Bạn có chắc chắn muốn xóa tài khoản của mình không?
            </h2>

            <p class="text-slate-600 dark:text-slate-400 mb-6">
                Khi tài khoản của bạn bị xóa, tất cả dữ liệu sẽ không thể khôi phục. 
                Vui lòng nhập mật khẩu của bạn để xác nhận muốn xóa vĩnh viễn tài khoản này.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Mật khẩu</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                    placeholder="Nhập mật khẩu của bạn"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" 
                        class="px-6 py-3 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-all">
                    Hủy
                </button>

                <button type="submit" 
                        class="px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 shadow-lg shadow-red-600/30 transition-all">
                    Xác nhận xóa
                </button>
            </div>
        </form>
    </x-modal>
</section>
