<section x-data="avatarUpload('{{ $user->avatar_url }}')">
    <header class="mb-6">
        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
            <i class="fa-regular fa-id-badge text-[#e07a5f]"></i>
            <span>{{ __('Thông tin cá nhân') }}</span>
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Cập nhật họ tên, địa chỉ email và ảnh đại diện hiển thị trên toàn hệ thống XIAOMU LMS.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profileForm" @submit.prevent="submitProfileForm" class="space-y-5">
        @csrf
        @method('patch')

        <!-- Khu vực Tải ảnh đại diện trực tiếp -->
        <div class="p-4 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
            <div class="relative group shrink-0">
                <img :src="imageUrl" 
                     class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover border-2 border-white dark:border-[#2d2926] shadow-md transition-transform group-hover:scale-105"
                     alt="{{ $user->first_name }} {{ $user->last_name }}">
                
                <label for="avatar_input" class="absolute inset-0 bg-black/50 text-white rounded-2xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity backdrop-blur-xs">
                    <i class="fa-solid fa-camera text-base mb-1"></i>
                    <span class="text-[10px] font-bold">{{ __('Thay ảnh') }}</span>
                </label>
            </div>
            
            <div class="flex-1 text-center sm:text-left">
                <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 mb-1">{{ __('Ảnh đại diện tài khoản') }}</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">
                    {{ __('Định dạng hỗ trợ: JPG, PNG hoặc GIF. Dung lượng tối đa 2MB.') }}
                </p>
                <div class="flex items-center justify-center sm:justify-start gap-2.5">
                    <label for="avatar_input" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] text-slate-700 dark:text-slate-200 text-xs font-bold cursor-pointer transition-all shadow-xs btn-tactile">
                        <i class="fa-solid fa-cloud-arrow-up text-[#e07a5f]"></i>
                        <span>{{ __('Chọn ảnh mới') }}</span>
                    </label>
                    <input type="file" id="avatar_input" name="avatar" class="hidden" accept="image/jpeg,image/png,image/jpg,image/gif" @change="fileChosen">
                    
                    <template x-if="hasNewImage">
                        <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-xs"></i> {{ __('Đã chọn ảnh mới') }}
                        </span>
                    </template>
                </div>
                <p x-show="errors.avatar" x-text="errors.avatar" class="text-[11px] font-semibold text-rose-500 mt-2" style="display: none;"></p>
            </div>
        </div>

        <!-- Ô nhập Họ & Tên -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Họ và tên đệm') }}</label>
                <div class="relative">
                    <i class="fa-regular fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input id="first_name" name="first_name" type="text" 
                           class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('first_name') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all font-medium" 
                           value="{{ old('first_name', $user->first_name) }}" required autocomplete="given-name" placeholder="{{ __('Nguyễn Văn') }}" />
                </div>
                <p x-show="errors.first_name" x-text="errors.first_name" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;"></p>
            </div>

            <div>
                <label for="last_name" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Tên') }}</label>
                <div class="relative">
                    <i class="fa-regular fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input id="last_name" name="last_name" type="text" 
                           class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('last_name') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all font-medium" 
                           value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name" placeholder="{{ __('An') }}" />
                </div>
                <p x-show="errors.last_name" x-text="errors.last_name" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;"></p>
            </div>
        </div>

        <!-- Ô nhập Email -->
        <div>
            <label for="email" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Địa chỉ Email') }}</label>
            <div class="relative">
                <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input id="email" name="email" type="email" 
                       class="w-full bg-[#f8f6f3] dark:bg-[#201d1b] border @error('email') border-rose-500 @else border-[#e8e2d9] dark:border-[#2d2926] @enderror rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all font-medium" 
                       value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="name@example.com" />
            </div>
            <p x-show="errors.email" x-text="errors.email" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;"></p>

            <!-- Trạng thái xác thực Email -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 text-xs text-amber-700 dark:text-amber-300 flex items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 shrink-0"></i>
                        <span>{{ __('Email của bạn chưa được xác minh.') }}</span>
                    </div>
                    <button form="send-verification" class="text-xs font-bold text-[#e07a5f] hover:underline whitespace-nowrap cursor-pointer">
                        {{ __('Gửi lại link xác minh') }}
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        {{ __('Một liên kết xác minh mới đã được gửi tới email của bạn.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Nút Submit & Thông báo lưu -->
        <div class="flex items-center gap-3 pt-3 border-t border-[#e8e2d9] dark:border-[#2d2926]">
            <button type="submit" :disabled="loading"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] hover:from-[#c86349] hover:to-[#b55238] text-white text-xs font-bold shadow-md shadow-[#e07a5f]/25 hover:shadow-lg transition-all btn-tactile flex items-center gap-2 cursor-pointer disabled:opacity-75">
                <i x-show="!loading" class="fa-regular fa-floppy-disk text-xs"></i>
                <i x-show="loading" class="fa-solid fa-spinner fa-spin text-xs" style="display: none;"></i>
                <span x-text="loading ? '{{ __('Đang lưu...') }}' : '{{ __('Lưu thay đổi') }}'">{{ __('Lưu thay đổi') }}</span>
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
    function avatarUpload(defaultUrl) {
        return {
            imageUrl: defaultUrl || 'https://ui-avatars.com/api/?name=User&background=fdeae3&color=e07a5f',
            hasNewImage: false,
            loading: false,
            errors: {},
            successMessage: '',
            fileChosen(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                        this.hasNewImage = true;
                    };
                    reader.readAsDataURL(file);
                }
            },
            async submitProfileForm() {
                this.loading = true;
                this.errors = {};
                this.successMessage = '';
                
                let form = document.getElementById('profileForm');
                let formData = new FormData(form);
                
                try {
                    const response = await fetch('{{ route("profile.update") }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        this.successMessage = data.message;
                        this.hasNewImage = false;
                        
                        // Cập nhật URL ảnh mới từ server trả về nếu có
                        if (data.user && data.user.avatar_url) {
                            this.imageUrl = data.user.avatar_url;
                        }
                        
                        // Phát sự kiện global để sidebar cập nhật
                        window.dispatchEvent(new CustomEvent('profile-updated', {
                            detail: {
                                name: data.user ? (data.user.first_name + ' ' + data.user.last_name) : (formData.get('first_name') + ' ' + formData.get('last_name')),
                                avatar: data.user ? data.user.avatar_url : this.imageUrl
                            }
                        }));
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
