<section>
    <header>
        <h2 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-2">
            Thông tin cá nhân
        </h2>
        <p class="text-slate-600 dark:text-slate-400 mb-6">
            Cập nhật thông tin tài khoản và địa chỉ email của bạn.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <div class="flex items-center gap-6" x-data="avatarUpload()">
            <div class="relative group">
                <img :src="imageUrl" 
                     class="w-24 h-24 rounded-full object-cover border-4 border-slate-100 dark:border-slate-700 shadow-sm"
                     alt="Avatar">
                <label for="avatar" class="absolute inset-0 bg-black/50 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                    <span class="material-symbols-outlined">photo_camera</span>
                </label>
            </div>
            <div>
                <label for="avatar" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-lg cursor-pointer transition-all">
                    <span class="material-symbols-outlined text-[20px]">upload</span>
                    Tải ảnh mới
                </label>
                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/jpeg, image/png, image/jpg, image/gif" @change="fileChosen">
                <p class="text-xs text-slate-500 mt-2">Định dạng JPG, PNG hoặc GIF. Tối đa 2MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Họ</label>
                <input id="first_name" name="first_name" type="text" 
                       class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" 
                       value="{{ old('first_name', $user->first_name) }}" required autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tên</label>
                <input id="last_name" name="last_name" type="text" 
                       class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" 
                       value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
            <input id="email" name="email" type="email" 
                   class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" 
                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-800 dark:text-slate-200">
                        Email của bạn chưa được xác minh.

                        <button form="send-verification" class="underline text-sm text-primary hover:text-primary/80 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Nhấn vào đây để gửi lại email xác minh.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" 
                    class="px-6 py-3 bg-primary text-white font-bold rounded-lg hover:opacity-90 shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Lưu thay đổi
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm font-medium text-green-600 dark:text-green-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Đã lưu thành công.
                </p>
            @endif
        </div>
    </form>

    <script>
        function avatarUpload() {
            return {
                imageUrl: '{{ $user->avatar ?? "https://ui-avatars.com/api/?name=" . urlencode($user->first_name . " " . $user->last_name) . "&color=FFFFFF&background=8fc0e0" }}',
                fileChosen(event) {
                    if (event.target.files.length > 0) {
                        this.imageUrl = URL.createObjectURL(event.target.files[0]);
                    }
                }
            }
        }
    </script>
</section>
