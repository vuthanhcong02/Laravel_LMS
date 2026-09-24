<div x-show="showCardModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
    <div x-show="showCardModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showCardModal = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <div x-show="showCardModal"
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-lg bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-6 overflow-hidden z-10">

        <div class="flex items-center justify-between pb-4 border-b border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-plus-circle"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white"
                    x-text="cardModalMode === 'create' ? '{{ __('Thêm Từ Vựng Mới') }}' : '{{ __('Chỉnh Sửa Từ Vựng') }}'"></h3>
            </div>
            <button @click="showCardModal = false"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form @submit.prevent="submitCardForm()" class="space-y-4 pt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Chữ Hán') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text"
                               x-model="cardForm.word"
                               @input="onWordInput()"
                               placeholder="{{ __('Ví dụ: 苹果, 咖啡...') }}"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-sm font-bold zh-text text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all">
                        <button type="button"
                                @click="speak(cardForm.word)"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#e07a5f] text-xs p-1"
                                :title="'{{ __('Nghe phát âm') }}'">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">
                            {{ __('Phiên âm Pinyin') }} <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> {{ __('Tự động sinh') }}
                        </span>
                    </div>
                    <input type="text"
                           x-model="cardForm.pinyin"
                           placeholder="{{ __('Ví dụ: píng guǒ, kā fēi...') }}"
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-medium text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('Ý nghĩa / Dịch nghĩa tiếng Việt') }} <span class="text-rose-500">*</span>
                </label>
                <textarea x-model="cardForm.meaning"
                          rows="2"
                          placeholder="{{ __('Ví dụ: Quả táo (danh từ), Cà phê...') }}"
                          required
                          class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-medium text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all resize-none"></textarea>
            </div>

            <div class="space-y-3 pt-1 border-t border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Câu ví dụ tiếng Trung') }} <span class="text-slate-400 text-[10px] font-normal">({{ __('Tùy chọn') }})</span>
                    </label>
                    <input type="text"
                           x-model="cardForm.example"
                           placeholder="{{ __('Ví dụ: 我喜欢吃苹果。') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-medium zh-text text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Dịch nghĩa câu ví dụ') }} <span class="text-slate-400 text-[10px] font-normal">({{ __('Tùy chọn') }})</span>
                    </label>
                    <input type="text"
                           x-model="cardForm.example_meaning"
                           placeholder="{{ __('Ví dụ: Tôi thích ăn táo.') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-medium text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-end gap-2.5">
                <button type="button"
                        @click="showCardModal = false"
                        class="px-4 py-2 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    {{ __('Hủy bỏ') }}
                </button>
                <button type="submit"
                        :disabled="isSubmittingCard"
                        class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 disabled:opacity-50">
                    <i x-show="isSubmittingCard" class="fa-solid fa-spinner fa-spin text-xs"></i>
                    <span x-text="cardModalMode === 'create' ? '{{ __('Thêm Vào Bộ Thẻ') }}' : '{{ __('Cập Nhật Từ') }}'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
