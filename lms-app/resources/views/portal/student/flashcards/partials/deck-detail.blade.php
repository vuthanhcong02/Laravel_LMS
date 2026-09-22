<div class="space-y-2.5">
    <div class="flex items-center justify-between gap-2">
        <button @click="closeDeck()"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:border-[#e07a5f] hover:text-[#e07a5f] text-[11px] font-semibold transition-all shadow-2xs btn-tactile cursor-pointer">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>{{ __('Tất cả bộ thẻ') }}</span>
        </button>

        <div class="flex items-center gap-1.5">
            <button @click="openCreateCardModal()"
                    class="px-2.5 py-1 rounded-lg bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-semibold shadow-2xs transition-all flex items-center gap-1 btn-tactile cursor-pointer">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>{{ __('Thêm từ vựng mới') }}</span>
            </button>
            <button @click="openEditDeckModal(selectedDeck)"
                    class="w-7 h-7 rounded-lg bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] hover:border-[#e07a5f] text-xs flex items-center justify-center transition-all btn-tactile cursor-pointer"
                    :title="'{{ __('Chỉnh sửa thông tin bộ thẻ') }}'">
                <i class="fa-solid fa-pen-to-square text-[11px]"></i>
            </button>
            <button @click="deleteDeck(selectedDeck.id)"
                    class="w-7 h-7 rounded-lg bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-rose-500 hover:border-rose-300 text-xs flex items-center justify-center transition-all btn-tactile cursor-pointer"
                    :title="'{{ __('Xóa bộ thẻ') }}'">
                <i class="fa-solid fa-trash text-[11px]"></i>
            </button>
        </div>
    </div>

    <div class="lms-card px-3.5 py-2.5 sm:px-4 sm:py-3 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 shadow-2xs">
        <!-- Left: Deck Info & Progress side-by-side -->
        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-wrap sm:flex-nowrap">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white text-base shadow-xs shrink-0"
                     :style="'background-color: ' + ((selectedDeck && selectedDeck.color) ? selectedDeck.color : '#e07a5f')">
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-book-open'">
                        <i class="fa-solid fa-book-open"></i>
                    </template>
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-layer-group'">
                        <i class="fa-solid fa-layer-group"></i>
                    </template>
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-utensils'">
                        <i class="fa-solid fa-utensils"></i>
                    </template>
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-plane'">
                        <i class="fa-solid fa-plane"></i>
                    </template>
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-briefcase'">
                        <i class="fa-solid fa-briefcase"></i>
                    </template>
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-heart'">
                        <i class="fa-solid fa-heart"></i>
                    </template>
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-star'">
                        <i class="fa-solid fa-star"></i>
                    </template>
                    <template x-if="selectedDeck && selectedDeck.icon === 'fa-comments'">
                        <i class="fa-solid fa-comments"></i>
                    </template>
                    <template x-if="!selectedDeck || !['fa-book-open', 'fa-layer-group', 'fa-utensils', 'fa-plane', 'fa-briefcase', 'fa-heart', 'fa-star', 'fa-comments'].includes(selectedDeck.icon)">
                        <i class="fa-solid fa-book-open"></i>
                    </template>
                </div>
                <div class="min-w-0 leading-tight space-y-0.5">
                    <h1 class="text-base font-bold text-slate-900 dark:text-white tracking-tight truncate max-w-[120px] sm:max-w-xs"
                        x-text="selectedDeck ? selectedDeck.title : ''"></h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-[120px] sm:max-w-[200px]"
                       x-text="(selectedDeck && selectedDeck.description) ? selectedDeck.description : '{{ __('Không có mô tả bổ sung') }}'"></p>
                </div>
            </div>

            <!-- Divider -->
            <div class="hidden sm:block h-8 w-px bg-[#e8e2d9] dark:bg-[#2d2926] shrink-0"></div>

            <!-- Progress section placed on the LEFT beside deck info -->
            <div class="space-y-0.5 min-w-[115px] sm:min-w-[130px] shrink-0">
                <div class="flex items-center justify-between text-[10px] font-bold">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Tiến độ') }}</span>
                    <span class="text-[#e07a5f]" x-text="(selectedDeck.progress_percentage ?? 0) + '%'"></span>
                </div>
                <div class="w-full h-1.5 bg-[#f8f6f3] dark:bg-[#201d1b] rounded-full overflow-hidden border border-[#e8e2d9]/40 dark:border-[#2d2926]">
                    <div class="h-full rounded-full transition-all duration-500"
                         :style="'width: ' + (selectedDeck.progress_percentage ?? 0) + '%; background-color: ' + (selectedDeck.color || '#e07a5f')"></div>
                </div>
                <div class="flex items-center justify-between text-[9px] text-slate-400">
                    <span>
                        <span x-text="selectedDeck.remembered_cards_count ?? 0"></span>/<span x-text="selectedDeck.total_cards_count ?? (selectedDeck.flashcards ? selectedDeck.flashcards.length : 0)"></span> {{ __('đã thuộc') }}
                    </span>
                    <button @click="resetDeckProgress()"
                            class="text-[#e07a5f] hover:underline font-semibold cursor-pointer"
                            :title="'{{ __('Đặt lại tiến độ danh mục này') }}'">
                        {{ __('Học lại') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: 4 Subtabs (Luyện tập 3D, Trắc nghiệm, Nối từ, Danh sách từ) -->
        <div class="inline-flex items-center p-1 bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl text-xs font-bold shadow-2xs shrink-0 flex-wrap sm:flex-nowrap">
            <button @click="switchDeckSubTab('study')"
                    class="px-2.5 sm:px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile cursor-pointer"
                    :class="deckSubTab === 'study'
                        ? 'bg-[#e07a5f] text-white border border-[#e07a5f] shadow-xs'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-transparent'">
                <i class="fa-solid fa-layer-group text-xs"></i>
                <span>{{ __('Luyện tập 3D') }}</span>
            </button>
            <button @click="switchDeckSubTab('quiz')"
                    class="px-2.5 sm:px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile cursor-pointer"
                    :class="deckSubTab === 'quiz'
                        ? 'bg-[#e07a5f] text-white border border-[#e07a5f] shadow-xs'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-transparent'">
                <i class="fa-solid fa-circle-question text-xs"></i>
                <span>{{ __('Trắc nghiệm') }}</span>
            </button>
            <button @click="switchDeckSubTab('match')"
                    class="px-2.5 sm:px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile cursor-pointer"
                    :class="deckSubTab === 'match'
                        ? 'bg-[#e07a5f] text-white border border-[#e07a5f] shadow-xs'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-transparent'">
                <i class="fa-solid fa-puzzle-piece text-xs"></i>
                <span>{{ __('Nối từ') }}</span>
            </button>
            <button @click="switchDeckSubTab('cards')"
                    class="px-2.5 sm:px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile cursor-pointer"
                    :class="deckSubTab === 'cards'
                        ? 'bg-[#e07a5f] text-white border border-[#e07a5f] shadow-xs'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-transparent'">
                <i class="fa-solid fa-list text-xs"></i>
                <span>{{ __('Danh sách từ') }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px]"
                      :class="deckSubTab === 'cards' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400'"
                      x-text="selectedDeck.flashcards ? selectedDeck.flashcards.length : 0"></span>
            </button>
        </div>
    </div>

    <!-- TAB 1: STUDY MODE -->
    <div x-show="deckSubTab === 'study'" class="space-y-4">
        <!-- LOADING SKELETON: Displayed while loading deck details to prevent UI flicker -->
        <template x-if="isLoadingDeckDetails">
            <div class="max-w-2xl mx-auto w-full space-y-3 animate-pulse">
                <!-- Filter bar skeleton -->
                <div class="lms-card p-2 sm:px-3 sm:py-2 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between gap-2.5 shadow-xs">
                    <div class="h-8 w-56 bg-slate-200 dark:bg-slate-800 rounded-xl"></div>
                    <div class="h-8 w-44 bg-slate-200 dark:bg-slate-800 rounded-xl"></div>
                </div>

                <!-- 3D Card skeleton -->
                <div class="w-full rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] p-8 flex flex-col items-center justify-center gap-4 text-center shadow-xs"
                     style="height: 320px; min-height: 320px;">
                    <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-lg shadow-xs">
                        <i class="fa-solid fa-circle-notch fa-spin"></i>
                    </div>
                    <div class="space-y-2">
                        <div class="h-8 w-32 bg-slate-200 dark:bg-slate-800 rounded-xl mx-auto"></div>
                        <div class="h-4 w-20 bg-slate-100 dark:bg-slate-800/60 rounded-md mx-auto"></div>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 pt-2">
                        {{ __('Đang tải dữ liệu từ vựng...') }}
                    </p>
                </div>

                <!-- Action buttons skeleton -->
                <div class="grid grid-cols-3 gap-2.5">
                    <div class="h-10 rounded-xl bg-slate-200 dark:bg-slate-800"></div>
                    <div class="h-10 rounded-xl bg-slate-200 dark:bg-slate-800"></div>
                    <div class="h-10 rounded-xl bg-slate-200 dark:bg-slate-800"></div>
                </div>
            </div>
        </template>

        <!-- EMPTY STATE: Only shown when finished loading AND deck truly has no cards -->
        <template x-if="!isLoadingDeckDetails && (!selectedDeck.flashcards || selectedDeck.flashcards.length === 0)">
            <div class="lms-card p-10 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center flex flex-col items-center justify-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-2xl shadow-xs">
                    <i class="fa-solid fa-plus-circle"></i>
                </div>
                <div class="space-y-1 max-w-sm">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">
                        {{ __('Bộ thẻ này chưa có từ vựng') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Hãy thêm các từ vựng tiếng Trung đầu tiên vào bộ thẻ này để bắt đầu học.') }}
                    </p>
                </div>
                <button @click="openCreateCardModal()"
                        class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span>{{ __('Thêm từ vựng ngay') }}</span>
                </button>
            </div>
        </template>

        <!-- STUDY CONTENT: Shown when finished loading and cards are available -->
        <template x-if="!isLoadingDeckDetails && selectedDeck.flashcards && selectedDeck.flashcards.length > 0">
            <div class="max-w-2xl mx-auto w-full space-y-3">
                <div class="lms-card p-2 sm:px-3 sm:py-2 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-wrap items-center justify-between gap-2.5 shadow-xs">
                    <div class="inline-flex items-center p-1 bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl text-xs font-bold shadow-2xs">
                        <button @click="studyFilter = 'unlearned'; currentIndex = 0"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1 btn-tactile cursor-pointer"
                                :class="studyFilter === 'unlearned'
                                    ? 'bg-[#e07a5f] text-white border border-[#e07a5f] shadow-xs'
                                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-transparent'">
                            <span>{{ __('Chưa thuộc') }}</span>
                        </button>
                        <button @click="studyFilter = 'learned'; currentIndex = 0"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1 btn-tactile cursor-pointer"
                                :class="studyFilter === 'learned'
                                    ? 'bg-[#e07a5f] text-white border border-[#e07a5f] shadow-xs'
                                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-transparent'">
                            <span>{{ __('Đã thuộc') }}</span>
                        </button>
                        <button @click="studyFilter = 'all'; currentIndex = 0"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1 btn-tactile cursor-pointer"
                                :class="studyFilter === 'all'
                                    ? 'bg-[#e07a5f] text-white border border-[#e07a5f] shadow-xs'
                                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-transparent'">
                            <span>{{ __('Tất cả') }}</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold shadow-2xs">
                            <span>{{ __('Thẻ') }}:</span>
                            <strong class="text-[#e07a5f]" x-text="studyCards().length > 0 ? (currentIndex + 1) : 0"></strong>
                            <span>/</span>
                            <span x-text="studyCards().length"></span>
                        </span>

                        <button @click="shuffle()"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold btn-tactile border flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer"
                                :class="isShuffled ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40'">
                            <i class="fa-solid fa-shuffle text-xs"></i>
                            <span x-text="isShuffled ? '{{ __('Bỏ trộn') }}' : '{{ __('Trộn thẻ') }}'"></span>
                        </button>

                        <button @click="autoplayAudio = !autoplayAudio"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold btn-tactile border flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer"
                                :class="autoplayAudio ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40'">
                            <i class="fa-solid text-xs" :class="autoplayAudio ? 'fa-volume-high' : 'fa-volume-xmark'"></i>
                            <span x-text="autoplayAudio ? '{{ __('Tắt tự đọc') }}' : '{{ __('Tự đọc') }}'"></span>
                        </button>
                    </div>
                </div>

                <template x-if="studyCards().length > 0">
                    <div class="space-y-3.5">
                        <div class="w-full perspective-1000 cursor-pointer" style="height: 320px; min-height: 320px;" @click="flipCard()">
                            <div class="relative w-full h-full duration-500 transform-style-3d transition-all"
                                 style="height: 320px; min-height: 320px;"
                                 :class="{
                                     'rotate-y-180': flipped,
                                     'translate-x-full opacity-0 scale-95 pointer-events-none': isLeaving
                                 }">
                                <div class="absolute inset-0 w-full h-full rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] p-5 sm:p-6 flex flex-col justify-between items-center shadow-sm backface-hidden">
                                    <div class="w-full flex justify-between items-center text-xs">
                                        <span class="px-2.5 py-0.5 rounded-full text-white font-bold text-[11px] shadow-xs"
                                              :style="'background-color: ' + (selectedDeck.color || '#e07a5f')"
                                              x-text="selectedDeck.title"></span>

                                        <button @click.stop="toggleRememberCard(currentCard())"
                                                class="px-2.5 py-1 rounded-xl text-xs font-semibold border btn-tactile flex items-center gap-1.5 transition-all shadow-xs cursor-pointer"
                                                :class="currentCard() && currentCard().is_remembered ?
                                                    'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-300 dark:border-emerald-800' :
                                                    'bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 border-[#e8e2d9] dark:border-[#2d2926] hover:border-emerald-300 dark:hover:border-emerald-800/80'"
                                                :title="currentCard() && currentCard().is_remembered ? '{{ __('Bỏ đánh dấu đã thuộc') }}' : '{{ __('Đánh dấu từ này đã thuộc') }}'">
                                            <i class="text-xs text-emerald-600 dark:text-emerald-400"
                                               :class="currentCard() && currentCard().is_remembered ? 'fa-solid fa-circle-check' : 'fa-regular fa-circle-check'"></i>
                                            <span x-text="currentCard() && currentCard().is_remembered ? '{{ __('Đã thuộc') }}' : '{{ __('Đánh dấu đã thuộc') }}'"></span>
                                        </button>
                                    </div>

                                    <div class="text-center space-y-3 my-auto">
                                        <div class="text-5xl sm:text-6xl font-bold zh-text text-slate-900 dark:text-white tracking-wider"
                                             x-text="currentCard() ? currentCard().word : ''"></div>
                                        <button @click.stop="speak()"
                                                class="px-3.5 py-1.5 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs btn-tactile hover:scale-105 inline-flex items-center gap-2 border border-[#fcdccf] dark:border-[#3a2824]">
                                            <i class="fa-solid fa-volume-high text-xs"></i>
                                            <span>{{ __('Nghe phát âm') }}</span>
                                        </button>
                                    </div>

                                    <div class="w-full flex justify-between items-center text-[11px] text-slate-400">
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-arrow-pointer text-[10px]"></i>
                                            <span>{{ __('Bấm vào thẻ để lật xem phiên âm & nghĩa') }}</span>
                                        </span>
                                        <span class="font-semibold text-slate-500 dark:text-slate-400"
                                              x-text="(currentIndex + 1) + ' / ' + studyCards().length"></span>
                                    </div>
                                </div>

                                <div class="absolute inset-0 w-full h-full rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] p-5 sm:p-6 flex flex-col justify-between items-center shadow-sm backface-hidden rotate-y-180 overflow-y-auto no-scrollbar">
                                    <div class="w-full flex justify-between items-center text-xs">
                                        <span class="px-2.5 py-0.5 rounded-full text-white font-bold text-[11px] shadow-xs"
                                              :style="'background-color: ' + (selectedDeck.color || '#e07a5f')"
                                              x-text="selectedDeck.title"></span>

                                        <button @click.stop="toggleRememberCard(currentCard())"
                                                class="px-2.5 py-1 rounded-xl text-xs font-semibold border btn-tactile flex items-center gap-1.5 transition-all shadow-xs cursor-pointer"
                                                :class="currentCard() && currentCard().is_remembered ?
                                                    'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-300 dark:border-emerald-800' :
                                                    'bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 border-[#e8e2d9] dark:border-[#2d2926] hover:border-emerald-300 dark:hover:border-emerald-800/80'"
                                                :title="currentCard() && currentCard().is_remembered ? '{{ __('Bỏ đánh dấu đã thuộc') }}' : '{{ __('Đánh dấu từ này đã thuộc') }}'">
                                            <i class="text-xs text-emerald-600 dark:text-emerald-400"
                                               :class="currentCard() && currentCard().is_remembered ? 'fa-solid fa-circle-check' : 'fa-regular fa-circle-check'"></i>
                                            <span x-text="currentCard() && currentCard().is_remembered ? '{{ __('Đã thuộc') }}' : '{{ __('Đánh dấu đã thuộc') }}'"></span>
                                        </button>
                                    </div>

                                    <div class="text-center space-y-2.5 my-auto max-w-md w-full">
                                        <div class="space-y-0.5">
                                            <div class="text-2xl sm:text-3xl font-bold zh-text text-slate-900 dark:text-white"
                                                 x-text="currentCard() ? currentCard().word : ''"></div>
                                            <div class="text-base sm:text-lg font-bold text-[#e07a5f] tracking-wide"
                                                 x-text="currentCard() ? currentCard().pinyin : ''"></div>
                                        </div>

                                        <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                            <p class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200"
                                               x-text="currentCard() ? currentCard().meaning : ''"></p>
                                        </div>

                                        <template x-if="currentCard() && currentCard().example">
                                            <div class="space-y-1 p-2.5 rounded-xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/40 text-left">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[10px] uppercase font-bold text-amber-600 dark:text-amber-400 tracking-wider flex items-center gap-1">
                                                        <i class="fa-solid fa-quote-left text-[9px]"></i> {{ __('Ví dụ ngữ cảnh') }}
                                                    </span>
                                                    <button @click.stop="speak(currentCard().example)" class="text-amber-600 dark:text-amber-400 hover:scale-110 transition-transform text-xs p-0.5">
                                                        <i class="fa-solid fa-volume-high text-[11px]"></i>
                                                    </button>
                                                </div>
                                                <div class="text-xs sm:text-sm text-slate-800 dark:text-slate-200 font-medium"
                                                     x-html="renderRuby(currentCard().example)"></div>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 italic"
                                                   x-text="currentCard().example_meaning"></p>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="w-full flex justify-between items-center text-[11px] text-slate-400">
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-rotate text-[10px]"></i>
                                            <span>{{ __('Bấm thẻ để quay lại mặt chữ') }}</span>
                                        </span>
                                        <button @click.stop="openEditCardModal(currentCard())"
                                                class="text-slate-500 hover:text-[#e07a5f] font-semibold text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                            <span>{{ __('Sửa từ này') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2.5">
                            <button @click="prevCard()"
                                    class="py-2.5 px-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 hover:border-[#e07a5f] hover:text-[#e07a5f] cursor-pointer">
                                <i class="fa-solid fa-chevron-left text-[11px]"></i>
                                <span>{{ __('Thẻ trước') }}</span>
                                <span class="hidden sm:inline text-[10px] text-slate-400 font-normal">(←)</span>
                            </button>
                            <button @click="flipCard()"
                                    class="py-2.5 px-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 hover:border-[#e07a5f] hover:text-[#e07a5f] cursor-pointer">
                                <i class="fa-solid fa-rotate text-[11px] text-[#e07a5f]"></i>
                                <span>{{ __('Lật mặt thẻ') }}</span>
                                <span class="hidden sm:inline text-[10px] text-slate-400 font-normal">(Space)</span>
                            </button>
                            <button @click="nextCard()"
                                    class="py-2.5 px-3 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                                <span>{{ __('Thẻ tiếp') }}</span>
                                <span class="hidden sm:inline text-[10px] opacity-80 font-normal">(→)</span>
                                <i class="fa-solid fa-chevron-right text-[11px]"></i>
                            </button>
                        </div>

                        <div class="hidden sm:flex items-center justify-center gap-3 text-[11px] text-slate-400">
                            <span><kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold text-[10px]">Space</kbd> {{ __('Lật thẻ') }}</span>
                            <span>•</span>
                            <span><kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold text-[10px]">←</kbd> <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold text-[10px]">→</kbd> {{ __('Chuyển thẻ') }}</span>
                        </div>
                    </div>
                </template>

                <template x-if="studyCards().length === 0">
                    <div class="lms-card p-10 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center flex flex-col items-center justify-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 flex items-center justify-center text-amber-500 text-2xl shadow-xs">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <div class="space-y-1.5 max-w-md">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                                {{ __('Không có thẻ nào trong mục lọc này') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ __('Bạn đã hoàn thành các từ trong mục này hoặc chưa có từ nào phù hợp với bộ lọc hiện tại.') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <button @click="studyFilter = 'all'; currentIndex = 0"
                                    class="px-4 py-2 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 text-xs font-bold btn-tactile">
                                {{ __('Xem tất cả từ') }}
                            </button>
                            <button @click="resetDeckProgress()"
                                    class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs">
                                {{ __('Đặt lại tiến độ học') }}
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <!-- TAB 2: QUIZ MODE (TRẮC NGHIỆM) -->
    <div x-show="deckSubTab === 'quiz'" class="space-y-4" x-cloak>
        @include('portal.student.flashcards.partials.game-quiz')
    </div>

    <!-- TAB 3: MATCH MODE (NỐI TỪ) -->
    <div x-show="deckSubTab === 'match'" class="space-y-4" x-cloak>
        @include('portal.student.flashcards.partials.game-match')
    </div>

    <!-- TAB 4: CARDS LIST -->
    <div x-show="deckSubTab === 'cards'" class="space-y-4" x-cloak>
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                {{ __('Danh sách từ vựng trong bộ thẻ') }} (<span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedDeck.flashcards ? selectedDeck.flashcards.length : 0"></span>):
            </span>
        </div>

        <!-- SKELETON FOR CARDS LIST -->
        <template x-if="isLoadingDeckDetails">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3.5 animate-pulse">
                <template x-for="i in 3" :key="i">
                    <div class="p-4 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3 shadow-xs">
                        <div class="h-6 w-24 bg-slate-200 dark:bg-slate-800 rounded-lg"></div>
                        <div class="h-4 w-36 bg-slate-100 dark:bg-slate-800/60 rounded-md"></div>
                        <div class="h-4 w-48 bg-slate-100 dark:bg-slate-800/40 rounded-md"></div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!isLoadingDeckDetails && (!selectedDeck.flashcards || selectedDeck.flashcards.length === 0)">
            <div class="lms-card p-10 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl text-center flex flex-col items-center justify-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div class="space-y-1 max-w-sm">
                    <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Chưa có từ vựng nào') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Hãy nhấn vào nút bên dưới để thêm các từ vựng đầu tiên kèm phiên âm và câu ví dụ.') }}
                    </p>
                </div>
                <button @click="openCreateCardModal()"
                        class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-2 shadow-xs cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span>{{ __('Thêm từ đầu tiên') }}</span>
                </button>
            </div>
        </template>

        <template x-if="!isLoadingDeckDetails && selectedDeck.flashcards && selectedDeck.flashcards.length > 0">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3.5">
                <template x-for="card in selectedDeck.flashcards" :key="card.id">
                    <div class="p-4 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] flex flex-col justify-between gap-3 shadow-xs hover:border-[#e07a5f]/50 transition-all group">
                        <div class="space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-bold zh-text text-slate-900 dark:text-white" x-text="card.word"></span>
                                    <span class="text-sm font-semibold text-[#e07a5f]" x-text="'[' + card.pinyin + ']'"></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="speak(card.word)" class="text-slate-400 hover:text-[#e07a5f] p-1 text-xs" :title="'{{ __('Nghe phát âm') }}'">
                                        <i class="fa-solid fa-volume-high"></i>
                                    </button>
                                    <button @click="openEditCardModal(card)" class="text-slate-400 hover:text-[#e07a5f] p-1 text-xs" :title="'{{ __('Chỉnh sửa từ') }}'">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button @click="deleteCard(card.id)" class="text-slate-400 hover:text-rose-500 p-1 text-xs" :title="'{{ __('Xóa từ') }}'">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-300 font-medium line-clamp-2" x-text="card.meaning"></p>

                            <template x-if="card.example">
                                <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] text-xs space-y-1">
                                    <p class="font-medium zh-text text-slate-800 dark:text-slate-200" x-text="card.example"></p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 italic" x-text="card.example_meaning"></p>
                                </div>
                            </template>
                        </div>

                        <div class="pt-2 border-t border-[#e8e2d9]/60 dark:border-[#2d2926] flex items-center justify-between text-xs">
                            <span class="text-[11px] font-bold flex items-center gap-1"
                                  :class="card.is_remembered ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400'">
                                <i class="fa-solid" :class="card.is_remembered ? 'fa-circle-check text-emerald-600' : 'fa-circle-notch text-slate-300'"></i>
                                <span x-text="card.is_remembered ? '{{ __('Đã thuộc') }}' : '{{ __('Đang học') }}'"></span>
                            </span>

                            <button @click="toggleRememberCard(card)"
                                    class="text-[11px] font-bold text-slate-400 hover:text-[#e07a5f] btn-tactile flex items-center gap-1 cursor-pointer">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span x-text="card.is_remembered ? '{{ __('Học lại từ này') }}' : '{{ __('Đánh dấu đã thuộc') }}'"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>
