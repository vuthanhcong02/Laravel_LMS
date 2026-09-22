<div x-show="showDeckModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
    <div x-show="showDeckModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showDeckModal = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <div x-show="showDeckModal"
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-md bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-6 overflow-hidden z-10">

        <div class="flex items-center justify-between pb-4 border-b border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm shadow-xs"
                     :style="'background-color: ' + deckForm.color">
                    <template x-if="deckForm.icon === 'fa-book-open'"><i class="fa-solid fa-book-open"></i></template>
                    <template x-if="deckForm.icon === 'fa-layer-group'"><i class="fa-solid fa-layer-group"></i></template>
                    <template x-if="deckForm.icon === 'fa-utensils'"><i class="fa-solid fa-utensils"></i></template>
                    <template x-if="deckForm.icon === 'fa-plane'"><i class="fa-solid fa-plane"></i></template>
                    <template x-if="deckForm.icon === 'fa-briefcase'"><i class="fa-solid fa-briefcase"></i></template>
                    <template x-if="deckForm.icon === 'fa-heart'"><i class="fa-solid fa-heart"></i></template>
                    <template x-if="deckForm.icon === 'fa-star'"><i class="fa-solid fa-star"></i></template>
                    <template x-if="deckForm.icon === 'fa-comments'"><i class="fa-solid fa-comments"></i></template>
                    <template x-if="!deckForm.icon || !['fa-book-open', 'fa-layer-group', 'fa-utensils', 'fa-plane', 'fa-briefcase', 'fa-heart', 'fa-star', 'fa-comments'].includes(deckForm.icon)"><i class="fa-solid fa-book-open"></i></template>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white"
                    x-text="deckModalMode === 'create' ? '{{ __('Tạo Bộ Thẻ Mới') }}' : '{{ __('Chỉnh Sửa Bộ Thẻ') }}'"></h3>
            </div>
            <button @click="showDeckModal = false"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form @submit.prevent="submitDeckForm()" class="space-y-4 pt-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('Tên bộ thẻ') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       x-model="deckForm.title"
                       placeholder="{{ __('Ví dụ: Từ vựng Gọi món Nhà hàng, Giao tiếp công sở...') }}"
                       required
                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-medium text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('Mô tả ngắn gọn') }}
                </label>
                <textarea x-model="deckForm.description"
                          rows="2"
                          placeholder="{{ __('Ghi chú mục tiêu hoặc nội dung của bộ thẻ này...') }}"
                          class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-medium text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('Màu sắc đại diện') }}
                </label>
                <div class="flex items-center gap-2.5">
                    <template x-for="c in deckColorOptions" :key="c.value">
                        <button type="button"
                                @click="deckForm.color = c.value"
                                class="w-7 h-7 rounded-full border-2 transition-all flex items-center justify-center cursor-pointer"
                                :class="deckForm.color === c.value ? 'border-slate-800 dark:border-white scale-110' : 'border-transparent opacity-80 hover:opacity-100'"
                                :style="'background-color: ' + c.value">
                            <i x-show="deckForm.color === c.value" class="fa-solid fa-check text-[10px] text-white"></i>
                        </button>
                    </template>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('Biểu tượng icon') }}
                </label>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="item in deckIconOptions" :key="item.icon">
                        <button type="button"
                                @click="deckForm.icon = item.icon"
                                class="h-9 rounded-xl border flex items-center justify-center gap-1.5 text-xs font-semibold transition-all cursor-pointer"
                                :class="deckForm.icon === item.icon ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] text-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-400 hover:border-[#e07a5f]/40'">
                            <i class="fa-solid" :class="item.icon"></i>
                            <span class="text-[10px]" x-text="item.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-end gap-2.5">
                <button type="button"
                        @click="showDeckModal = false"
                        class="px-4 py-2 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    {{ __('Hủy bỏ') }}
                </button>
                <button type="submit"
                        :disabled="isSubmittingDeck"
                        class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 disabled:opacity-50">
                    <i x-show="isSubmittingDeck" class="fa-solid fa-spinner fa-spin text-xs"></i>
                    <span x-text="deckModalMode === 'create' ? '{{ __('Tạo Bộ Thẻ') }}' : '{{ __('Lưu Thay Đổi') }}'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
