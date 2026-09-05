{{-- Modal Popup Liên Hệ & Hỗ Trợ --}}
<div x-show="contactModalOpen" x-cloak
     @keydown.escape.window="contactModalOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="contact-modal-title" role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="contactModalOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="contactModalOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    {{-- Modal Box Container --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="contactModalOpen"
             x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.outside="contactModalOpen = false"
             x-data="{
                 name: '{{ auth()->check() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : '' }}',
                 email: '{{ auth()->check() ? auth()->user()->email : '' }}',
                 phone: '',
                 topic: 'tu-van',
                 message: '',
                 website: '',
                 loading: false,
                 success: false,
                 errorMessage: '',
                 fieldErrors: {},

                 resetForm() {
                     this.name = '{{ auth()->check() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : '' }}';
                     this.email = '{{ auth()->check() ? auth()->user()->email : '' }}';
                     this.phone = '';
                     this.topic = 'tu-van';
                     this.message = '';
                     this.website = '';
                     this.success = false;
                     this.errorMessage = '';
                     this.fieldErrors = {};
                 },

                 submitForm() {
                     this.loading = true;
                     this.errorMessage = '';
                     this.fieldErrors = {};

                     fetch('{{ route('contact.store') }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'Accept': 'application/json',
                             'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                         },
                         body: JSON.stringify({
                             name: this.name,
                             email: this.email,
                             phone: this.phone,
                             topics: [this.topic],
                             message: this.message,
                             website: this.website
                         })
                     })
                     .then(async response => {
                         const data = await response.json();
                         if (response.ok && data.success) {
                             this.success = true;
                             this.message = '';
                         } else if (response.status === 429) {
                             this.errorMessage = '{{ __('Bạn đã gửi yêu cầu quá nhiều lần liên tiếp. Vui lòng chờ 1 phút trước khi thử lại.') }}';
                         } else if (response.status === 422) {
                             this.fieldErrors = data.errors || {};
                             this.errorMessage = '{{ __('Vui lòng kiểm tra lại thông tin nhập bên dưới.') }}';
                         } else {
                             this.errorMessage = data.message || '{{ __('Đã có lỗi xảy ra, vui lòng thử lại sau.') }}';
                         }
                     })
                     .catch(err => {
                         console.error('Contact Form Error:', err);
                         this.errorMessage = '{{ __('Không thể kết nối đến máy chủ. Vui lòng thử lại sau.') }}';
                     })
                     .finally(() => {
                         this.loading = false;
                     });
                 }
             }"
             class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-left shadow-2xl transition-all w-full max-w-2xl sm:max-w-[720px] lg:max-w-3xl my-8">

            {{-- Nút đóng Modal --}}
            <button @click="contactModalOpen = false"
                    class="absolute right-4 top-4 z-10 w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 dark:bg-[#201d1b] text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors btn-tactile"
                    :title="'{{ __('Đóng') }}'">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            {{-- Nội dung Modal --}}
            <div class="p-6 sm:p-8 space-y-6">

                {{-- Header Modal --}}
                <div class="flex items-start gap-4 pr-8">
                    <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-xl shrink-0 shadow-xs">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight" id="contact-modal-title">
                            {{ __('Liên Hệ & Hỗ Trợ Học Tập') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            {{ __('Đội ngũ giảng viên và hỗ trợ viên XIAOMU luôn sẵn sàng giải đáp thắc mắc và đồng hành cùng bạn 24/7.') }}
                        </p>
                    </div>
                </div>

                {{-- Các kênh liên hệ trực tiếp nhanh --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="tel:0395294730"
                       class="p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/50 flex items-center gap-3 transition-all group btn-tactile">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">{{ __('Hotline tư vấn') }}</p>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#e07a5f] whitespace-nowrap">0395 294 730</p>
                        </div>
                    </a>

                    <a href="https://zalo.me/0395294739" target="_blank" rel="noopener noreferrer"
                       class="p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/50 flex items-center gap-3 transition-all group btn-tactile">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-950/40 text-[#0068ff] flex items-center justify-center text-xs font-black shrink-0">
                            <span>Zalo</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">{{ __('Hỗ trợ Zalo') }}</p>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#e07a5f] whitespace-nowrap">0395 294 739</p>
                        </div>
                    </a>

                    <a href="mailto:{{ config('mail.support_address', 'xiaomuhsk@gmail.com') }}"
                       class="p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/50 flex items-center gap-3 transition-all group btn-tactile">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">{{ __('Email hỗ trợ') }}</p>
                            <p class="text-[11px] sm:text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#e07a5f] select-all whitespace-nowrap leading-tight">{{ config('mail.support_address', 'xiaomuhsk@gmail.com') }}</p>
                        </div>
                    </a>
                </div>

                {{-- Khối Form gửi yêu cầu --}}
                <div class="pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">

                    {{-- Thông báo gửi thành công --}}
                    <template x-if="success">
                        <div class="p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-center space-y-3 my-2">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl mx-auto shadow-xs">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm sm:text-base font-bold text-emerald-800 dark:text-emerald-300">
                                    {{ __('Gửi yêu cầu hỗ trợ thành công!') }}
                                </h4>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400">
                                    {{ __('Cảm ơn bạn đã liên hệ. Đội ngũ tư vấn viên XIAOMU sẽ liên hệ và giải đáp sớm nhất có thể.') }}
                                </p>
                            </div>
                            <button @click="resetForm()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold btn-tactile shadow-xs">
                                {{ __('Gửi yêu cầu khác') }}
                            </button>
                        </div>
                    </template>

                    {{-- Form nhập liệu --}}
                    <form x-show="!success" @submit.prevent="submitForm()" class="space-y-4">

                        {{-- Thông báo lỗi nếu có --}}
                        <template x-if="errorMessage">
                            <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-xs flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                                <span x-text="errorMessage"></span>
                            </div>
                        </template>

                        {{-- Honeypot chống bot tự động --}}
                        <input type="text" name="website" x-model="website" class="hidden" tabindex="-1" autocomplete="off">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ __('Họ và tên') }} <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" x-model="name" required
                                       placeholder="{{ __('Nhập họ tên của bạn') }}"
                                       :class="fieldErrors.name ? 'border-rose-400 dark:border-rose-600 focus:border-rose-500 bg-rose-50/30' : 'border-[#e8e2d9] dark:border-[#2d2926] focus:border-[#e07a5f]'"
                                       class="w-full h-10 px-3.5 rounded-xl border bg-[#f8f6f3] dark:bg-[#201d1b] text-xs text-slate-800 dark:text-white focus:outline-hidden transition-all">
                                <template x-if="fieldErrors.name">
                                    <p class="text-[11px] text-rose-500 font-medium mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                        <span x-text="fieldErrors.name[0]"></span>
                                    </p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ __('Email') }} <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" x-model="email" required
                                       placeholder="email@example.com"
                                       :class="fieldErrors.email ? 'border-rose-400 dark:border-rose-600 focus:border-rose-500 bg-rose-50/30' : 'border-[#e8e2d9] dark:border-[#2d2926] focus:border-[#e07a5f]'"
                                       class="w-full h-10 px-3.5 rounded-xl border bg-[#f8f6f3] dark:bg-[#201d1b] text-xs text-slate-800 dark:text-white focus:outline-hidden transition-all">
                                <template x-if="fieldErrors.email">
                                    <p class="text-[11px] text-rose-500 font-medium mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                        <span x-text="fieldErrors.email[0]"></span>
                                    </p>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ __('Số điện thoại') }}
                                </label>
                                <input type="tel" x-model="phone"
                                       placeholder="09xx xxx xxx"
                                       :class="fieldErrors.phone ? 'border-rose-400 dark:border-rose-600 focus:border-rose-500 bg-rose-50/30' : 'border-[#e8e2d9] dark:border-[#2d2926] focus:border-[#e07a5f]'"
                                       class="w-full h-10 px-3.5 rounded-xl border bg-[#f8f6f3] dark:bg-[#201d1b] text-xs text-slate-800 dark:text-white focus:outline-hidden transition-all">
                                <template x-if="fieldErrors.phone">
                                    <p class="text-[11px] text-rose-500 font-medium mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                        <span x-text="fieldErrors.phone[0]"></span>
                                    </p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ __('Chủ đề liên hệ') }}
                                </label>
                                <select x-model="topic"
                                        class="w-full h-10 px-3.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] text-xs text-slate-800 dark:text-white focus:border-[#e07a5f] focus:outline-hidden transition-all">
                                    <option value="tu-van">{{ __('Tư vấn lộ trình học & Khóa học') }}</option>
                                    <option value="ho-tro">{{ __('Hỗ trợ kỹ thuật & Lỗi tính năng') }}</option>
                                    <option value="gop-y">{{ __('Góp ý & Đóng góp ý kiến') }}</option>
                                    <option value="khac">{{ __('Chủ đề khác') }}</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                {{ __('Nội dung tin nhắn / Câu hỏi') }} <span class="text-rose-500">*</span>
                            </label>
                            <textarea x-model="message" rows="3" required
                                      placeholder="{{ __('Mô tả chi tiết thắc mắc hoặc nội dung bạn cần hỗ trợ...') }}"
                                      :class="fieldErrors.message ? 'border-rose-400 dark:border-rose-600 focus:border-rose-500 bg-rose-50/30' : 'border-[#e8e2d9] dark:border-[#2d2926] focus:border-[#e07a5f]'"
                                      class="w-full p-3.5 rounded-xl border bg-[#f8f6f3] dark:bg-[#201d1b] text-xs text-slate-800 dark:text-white focus:outline-hidden transition-all resize-none"></textarea>
                            <template x-if="fieldErrors.message">
                                <p class="text-[11px] text-rose-500 font-medium mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                    <span x-text="fieldErrors.message[0]"></span>
                                </p>
                            </template>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="contactModalOpen = false"
                                    class="px-4 py-2.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 btn-tactile">
                                {{ __('Huỷ') }}
                            </button>

                            <button type="submit" :disabled="loading"
                                    class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                <i x-show="loading" class="fa-solid fa-spinner fa-spin text-xs"></i>
                                <i x-show="!loading" class="fa-solid fa-paper-plane text-xs"></i>
                                <span x-text="loading ? '{{ __('Đang gửi...') }}' : '{{ __('Gửi yêu cầu hỗ trợ') }}'"></span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
