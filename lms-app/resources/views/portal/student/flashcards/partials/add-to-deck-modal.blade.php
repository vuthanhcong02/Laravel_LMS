{{-- Modal Thêm từ vựng HSK vào Bộ thẻ cá nhân --}}
<div x-show="showAddToDeckModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
    {{-- Backdrop --}}
    <div x-show="showAddToDeckModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showAddToDeckModal = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    {{-- Modal Dialog Panel --}}
    <div x-show="showAddToDeckModal"
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-md bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-5 sm:p-6 overflow-hidden z-10 space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-sm shadow-xs shrink-0">
                    <i class="fa-solid fa-folder-plus"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-tight">
                        {{ __('Lưu Vào Bộ Thẻ Của Bạn') }}
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        {{ __('Chọn bộ thẻ để ôn tập theo lộ trình cá nhân') }}
                    </p>
                </div>
            </div>
            <button type="button"
                    @click="showAddToDeckModal = false"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Word Preview Box --}}
        <div class="p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="flex items-baseline gap-2">
                    <span class="text-xl font-bold zh-text text-slate-900 dark:text-white tracking-wide"
                          x-text="addToDeckWord?.word || ''"></span>
                    <span class="text-xs font-semibold text-[#e07a5f]"
                          x-text="addToDeckWord?.pinyin ? '[' + addToDeckWord.pinyin + ']' : ''"></span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium truncate mt-0.5"
                   x-text="addToDeckWord?.meaning || ''"></p>
            </div>
            <button type="button"
                    @click="speak(addToDeckWord?.word)"
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2a221f] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] hover:scale-105 transition-all flex items-center justify-center text-xs shrink-0 shadow-xs"
                    :title="'{{ __('Nghe phát âm') }}'">
                <i class="fa-solid fa-volume-high"></i>
            </button>
        </div>

        {{-- Decks List --}}
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs font-bold text-slate-600 dark:text-slate-400">
                <span>{{ __('Danh sách bộ thẻ') }}</span>
                <span class="text-[11px] font-normal text-slate-400"
                      x-show="!isLoadingDecksForModal"
                      x-text="userDecksForModal.length + ' {{ __('bộ thẻ') }}'"></span>
            </div>

            {{-- Loading Skeleton --}}
            <template x-if="isLoadingDecksForModal">
                <div class="space-y-2 py-2">
                    <template x-for="i in 3" :key="i">
                        <div class="p-3 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between animate-pulse">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-slate-200 dark:bg-slate-800"></div>
                                <div class="space-y-1.5">
                                    <div class="h-3 w-28 bg-slate-200 dark:bg-slate-800 rounded-sm"></div>
                                    <div class="h-2 w-16 bg-slate-200 dark:bg-slate-800 rounded-sm"></div>
                                </div>
                            </div>
                            <div class="h-7 w-16 bg-slate-200 dark:bg-slate-800 rounded-lg"></div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Empty State --}}
            <template x-if="!isLoadingDecksForModal && userDecksForModal.length === 0">
                <div class="p-6 rounded-2xl bg-[#faf7f3] dark:bg-[#201d1b] border border-dashed border-[#e8e2d9] dark:border-[#2d2926] text-center space-y-2">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-base">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">
                        {{ __('Bạn chưa có bộ thẻ cá nhân nào') }}
                    </p>
                    <p class="text-[11px] text-slate-400">
                        {{ __('Tạo nhanh bộ thẻ đầu tiên ở ô bên dưới để lưu từ vựng này ngay!') }}
                    </p>
                </div>
            </template>

            {{-- Decks Items List (Scrollable) --}}
            <template x-if="!isLoadingDecksForModal && userDecksForModal.length > 0">
                <div class="max-h-56 overflow-y-auto no-scrollbar space-y-2 pr-0.5">
                    <template x-for="deck in userDecksForModal" :key="deck.id">
                        <div class="p-3 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40 transition-all flex items-center justify-between gap-3 shadow-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-xs shrink-0 shadow-2xs"
                                     :style="'background-color: ' + (deck.color || '#e07a5f')">
                                    <template x-if="deck.icon === 'fa-book-open'"><i class="fa-solid fa-book-open"></i></template>
                                    <template x-if="deck.icon === 'fa-layer-group'"><i class="fa-solid fa-layer-group"></i></template>
                                    <template x-if="deck.icon === 'fa-utensils'"><i class="fa-solid fa-utensils"></i></template>
                                    <template x-if="deck.icon === 'fa-plane'"><i class="fa-solid fa-plane"></i></template>
                                    <template x-if="deck.icon === 'fa-briefcase'"><i class="fa-solid fa-briefcase"></i></template>
                                    <template x-if="deck.icon === 'fa-heart'"><i class="fa-solid fa-heart"></i></template>
                                    <template x-if="deck.icon === 'fa-star'"><i class="fa-solid fa-star"></i></template>
                                    <template x-if="deck.icon === 'fa-comments'"><i class="fa-solid fa-comments"></i></template>
                                    <template x-if="!deck.icon || !['fa-book-open', 'fa-layer-group', 'fa-utensils', 'fa-plane', 'fa-briefcase', 'fa-heart', 'fa-star', 'fa-comments'].includes(deck.icon)"><i class="fa-solid fa-book-open"></i></template>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white truncate"
                                        x-text="deck.title"></h4>
                                    <p class="text-[10px] text-slate-400 font-medium">
                                        <span x-text="deck.total_cards ?? 0"></span> {{ __('từ vựng') }}
                                    </p>
                                </div>
                            </div>

                            <div class="shrink-0">
                                {{-- Already Added Badge --}}
                                <template x-if="addedDeckIds.includes(deck.id)">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/60 text-[11px] font-bold">
                                        <i class="fa-solid fa-circle-check text-xs"></i>
                                        <span>{{ __('Đã có') }}</span>
                                    </span>
                                </template>

                                {{-- Add Button --}}
                                <template x-if="!addedDeckIds.includes(deck.id)">
                                    <button type="button"
                                            @click="addWordToSpecificDeck(deck.id)"
                                            :disabled="isAddingToDeckId === deck.id"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white border border-[#fcdccf] dark:border-[#3a2824] hover:border-[#e07a5f] text-xs font-bold transition-all btn-tactile cursor-pointer shadow-xs disabled:opacity-50">
                                        <template x-if="isAddingToDeckId === deck.id">
                                            <i class="fa-solid fa-spinner animate-spin text-xs"></i>
                                        </template>
                                        <template x-if="isAddingToDeckId !== deck.id">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                        </template>
                                        <span>{{ __('Thêm vào') }}</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        {{-- Quick Create Deck Section --}}
        <div class="pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
            <div x-show="!showQuickCreateDeck" class="flex justify-between items-center">
                <button type="button"
                        @click="showQuickCreateDeck = true; $nextTick(() => $refs.quickDeckInput?.focus())"
                        class="text-xs font-bold text-[#e07a5f] hover:text-[#c86349] hover:underline flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>{{ __('Tạo bộ thẻ mới & thêm từ này') }}</span>
                </button>
            </div>

            <div x-show="showQuickCreateDeck"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="p-4 rounded-2xl bg-[#faf7f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3.5">
                <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs shadow-xs transition-colors shrink-0"
                             :style="'background-color: ' + quickDeckColor">
                            <i class="fa-solid" :class="quickDeckIcon"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-white">
                            {{ __('Tạo bộ thẻ mới & lưu từ này') }}
                        </h4>
                    </div>
                    <button type="button"
                            @click="showQuickCreateDeck = false; quickDeckTitle = ''"
                            class="w-6 h-6 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>

                {{-- Tên bộ thẻ --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Tên bộ thẻ') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           x-ref="quickDeckInput"
                           x-model="quickDeckTitle"
                           @keydown.enter.prevent="quickCreateDeckAndAddWord()"
                           placeholder="{{ __('Ví dụ: Từ vựng Gọi món Nhà hàng, Giao tiếp...') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-medium text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] transition-all">
                </div>

                {{-- Màu sắc đại diện --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Màu sắc đại diện') }}
                    </label>
                    <div class="flex items-center gap-2.5">
                        <template x-for="c in deckColorOptions" :key="c.value">
                            <button type="button"
                                    @click="quickDeckColor = c.value"
                                    class="w-7 h-7 rounded-full border-2 transition-all flex items-center justify-center cursor-pointer"
                                    :class="quickDeckColor === c.value ? 'border-slate-800 dark:border-white scale-110 shadow-xs' : 'border-transparent opacity-80 hover:opacity-100'"
                                    :style="'background-color: ' + c.value"
                                    :title="c.label">
                                <i x-show="quickDeckColor === c.value" class="fa-solid fa-check text-[10px] text-white"></i>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Biểu tượng icon --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Biểu tượng icon') }}
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="item in deckIconOptions" :key="item.icon">
                            <button type="button"
                                    @click="quickDeckIcon = item.icon"
                                    class="h-9 rounded-xl border flex items-center justify-center gap-1.5 text-xs font-semibold transition-all cursor-pointer"
                                    :class="quickDeckIcon === item.icon ?
                                        'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] text-[#e07a5f]' :
                                        'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-400 hover:border-[#e07a5f]/40'">
                                <i class="fa-solid" :class="item.icon"></i>
                                <span class="text-[10px]" x-text="item.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-end gap-2.5">
                    <button type="button"
                            @click="showQuickCreateDeck = false; quickDeckTitle = ''"
                            class="px-4 py-2 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all cursor-pointer">
                        {{ __('Hủy bỏ') }}
                    </button>
                    <button type="button"
                            @click="quickCreateDeckAndAddWord()"
                            :disabled="!quickDeckTitle.trim() || isQuickCreating"
                            class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 disabled:opacity-50 btn-tactile cursor-pointer">
                        <template x-if="isQuickCreating">
                            <i class="fa-solid fa-spinner animate-spin text-xs"></i>
                        </template>
                        <template x-if="!isQuickCreating">
                            <i class="fa-solid fa-folder-plus text-xs"></i>
                        </template>
                        <span>{{ __('Tạo Bộ Thẻ & Thêm') }}</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Footer Buttons --}}
        <div class="pt-2 flex justify-end">
            <button type="button"
                    @click="showAddToDeckModal = false"
                    class="px-5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-bold transition-all btn-tactile cursor-pointer">
                {{ __('Đóng') }}
            </button>
        </div>
    </div>
</div>
