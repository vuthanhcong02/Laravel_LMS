<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                {{ __('Bộ Thẻ Ghi Nhớ Của Bạn') }}
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ __('Tự tạo các chủ đề từ vựng riêng theo sở thích, ngành nghề hoặc nhu cầu giao tiếp thực tế.') }}
            </p>
        </div>

        @auth
            <template x-if="decks.length > 0">
                <button @click="openCreateDeckModal()"
                        class="px-4 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all flex items-center justify-center gap-2 btn-tactile shrink-0 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>{{ __('Tạo Bộ Thẻ Mới') }}</span>
                </button>
            </template>
        @endauth
    </div>

    @guest
        <div class="lms-card p-8 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center flex flex-col items-center justify-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-2xl shadow-xs">
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="space-y-1.5 max-w-md">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                    {{ __('Đăng nhập để tạo bộ thẻ cá nhân') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Lưu trữ không giới hạn bộ thẻ từ vựng của riêng bạn, theo dõi tiến độ học tập và đồng bộ trên mọi thiết bị.') }}
                </p>
            </div>
            <button @click="requireLogin()"
                    class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>{{ __('Đăng nhập ngay') }}</span>
            </button>
        </div>
    @endguest

    @auth
        <template x-if="isLoadingDecks">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <template x-for="i in 3" :key="i">
                    <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] animate-pulse space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 dark:bg-slate-800"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded-md w-3/4"></div>
                                <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded-md w-1/2"></div>
                            </div>
                        </div>
                        <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded-full w-full"></div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!isLoadingDecks && decks.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <template x-for="deck in decks" :key="deck.id">
                    <div class="lms-card p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl flex flex-col justify-between gap-4 shadow-xs hover:border-[#e07a5f]/50 hover:shadow-md transition-all group cursor-pointer"
                         @click="openDeck(deck)">

                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-white text-base shadow-xs shrink-0 group-hover:scale-105 transition-transform"
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

                                <div class="flex items-center gap-1.5" @click.stop>
                                    <button @click="openEditDeckModal(deck)"
                                            class="w-8 h-8 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-500 hover:text-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] text-xs flex items-center justify-center transition-colors"
                                            :title="'{{ __('Chỉnh sửa bộ thẻ') }}'">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button @click="deleteDeck(deck.id)"
                                            class="w-8 h-8 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-500 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 text-xs flex items-center justify-center transition-colors"
                                            :title="'{{ __('Xóa bộ thẻ') }}'">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-[#e07a5f] transition-colors"
                                    x-text="deck.title"></h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 min-h-[32px]"
                                   x-text="deck.description || '{{ __('Không có mô tả') }}'"></p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-[#e8e2d9]/70 dark:border-[#2d2926]">
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-[11px] font-bold">
                                    <span class="text-slate-500 dark:text-slate-400">
                                        <span x-text="deck.remembered_cards ?? 0"></span>/<span x-text="deck.total_cards ?? 0"></span> {{ __('từ đã thuộc') }}
                                    </span>
                                    <span class="text-[#e07a5f]" x-text="(deck.progress_percentage ?? 0) + '%'"></span>
                                </div>
                                <div class="w-full h-1.5 bg-[#f8f6f3] dark:bg-[#201d1b] rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         :style="'width: ' + (deck.progress_percentage ?? 0) + '%; background-color: ' + (deck.color || '#e07a5f')"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                    <i class="fa-regular fa-clone text-xs"></i>
                                    <span x-text="(deck.total_cards ?? 0) + ' {{ __('từ vựng') }}'"></span>
                                </span>

                                <span class="text-xs font-bold text-[#e07a5f] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                    <span>{{ __('Vào học') }}</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!isLoadingDecks && decks.length === 0">
            <div class="lms-card p-10 sm:p-12 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center flex flex-col items-center justify-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-2xl shadow-xs">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="space-y-1.5 max-w-md">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">
                        {{ __('Bạn chưa có bộ thẻ flashcard nào') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Hãy tạo bộ thẻ đầu tiên của bạn để thêm từ vựng, tự động tạo pinyin và bắt đầu luyện tập 3D.') }}
                    </p>
                </div>
                <button @click="openCreateDeckModal()"
                        class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>{{ __('Tạo Bộ Thẻ Đầu Tiên') }}</span>
                </button>
            </div>
        </template>
    @endauth
</div>
