@extends('layouts.lms')
@section('title', __('Thẻ ghi nhớ Flashcards HSK - Tiếng Trung XiaoMu LMS'))
@section('header-left')
    <div x-data="{ deckTitle: '' }"
         @deck-changed.window="deckTitle = $event.detail?.title || ''"
         class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-[#e07a5f] transition-colors shrink-0">
            {{ __('Trang chủ') }}
        </a>
        <span class="shrink-0">/</span>
        <template x-if="deckTitle">
            <div class="flex items-center gap-2 min-w-0">
                <button type="button"
                        @click="window.dispatchEvent(new CustomEvent('close-selected-deck'))"
                        class="hover:text-[#e07a5f] transition-colors cursor-pointer shrink-0">
                    {{ __('Thẻ ghi nhớ') }}
                </button>
                <span class="shrink-0">/</span>
                <span class="text-slate-800 dark:text-slate-200 truncate max-w-[150px] sm:max-w-[250px] md:max-w-xs font-bold"
                      :title="deckTitle"
                      x-text="deckTitle"></span>
            </div>
        </template>
        <template x-if="!deckTitle">
            <span class="text-slate-800 dark:text-slate-200">{{ __('Thẻ ghi nhớ') }}</span>
        </template>
    </div>
@endsection
@section('custom-css')
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
@endsection
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/pinyin-pro@3.19.6/dist/index.js"></script>
    <script>
        window.hskVocabularies = @json($vocabularies);
        window.hskRememberedIds = @json($rememberedIds ?? []);
        window.myDecksData = @json($myDecks ?? []);
    </script>

    <div x-data="{
        activeMainTab: '{{ in_array(request('tab'), ['bo-the-cua-ban', 'bo-the', 'my_decks', 'my-decks']) ? 'bo-the-cua-ban' : 'tu-vung-hsk' }}',
        switchMainTab(tab) {
            this.activeMainTab = tab;
            const url = new URL(window.location);
            if (tab === 'bo-the-cua-ban') {
                url.searchParams.set('tab', 'bo-the-cua-ban');
            } else {
                url.searchParams.delete('tab');
                window.dispatchEvent(new CustomEvent('deck-changed', { detail: { title: null } }));
            }
            window.history.replaceState({}, '', url);
        }
    }" class="space-y-6 pb-12">

        <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
            <div class="inline-flex items-center gap-1.5 p-1 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl shadow-xs">
                <button type="button"
                        @click="switchMainTab('tu-vung-hsk')"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 btn-tactile cursor-pointer"
                        :class="activeMainTab === 'tu-vung-hsk' ?
                            'bg-[#e07a5f] text-white shadow-xs' :
                            'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>{{ __('Từ vựng HSK (1-9)') }}</span>
                </button>

                <button type="button"
                        @click="switchMainTab('bo-the-cua-ban')"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 btn-tactile cursor-pointer"
                        :class="activeMainTab === 'bo-the-cua-ban' ?
                            'bg-[#e07a5f] text-white shadow-xs' :
                            'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    <i class="fa-solid fa-folder-plus text-xs"></i>
                    <span>{{ __('Bộ thẻ của bạn') }}</span>
                </button>
            </div>
        </div>

        <div x-show="activeMainTab === 'tu-vung-hsk'">
            <div x-data="flashcardApp({ isLoggedIn: {{ auth()->check() ? 'true' : 'false' }} })" class="space-y-6">
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
            <div class="absolute right-4 -bottom-6 text-9xl font-extrabold text-[#e07a5f]/5 pointer-events-none select-none zh-text">
                记
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="space-y-1.5 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                        <i class="fa-solid fa-layer-group text-[#e07a5f]"></i>
                        <span x-text="'{{ __('Thẻ Flashcard 3D HSK') }} ' + activeLevel"></span>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                        {{ __('Thẻ Ghi Nhớ Từ Vựng HSK Thông Minh') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Luyện nhớ mặt chữ Hán, phiên âm Pinyin, định nghĩa và ngữ cảnh câu ví dụ thực tế thông qua phương pháp lật thẻ tương tác 3D.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2.5 shrink-0">
                    <button @click="isFilterDrawerOpen = true"
                            class="lg:hidden inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-[#201d1b] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] text-slate-700 dark:text-slate-200 border border-[#e8e2d9] dark:border-[#2d2926] font-bold text-xs shadow-xs transition-all btn-tactile">
                        <i class="fa-solid fa-sliders text-[#e07a5f]"></i>
                        <span>{{ __('Bộ lọc HSK') }}</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            <div class="hidden lg:flex lg:col-span-1 flex-col gap-5 sticky top-4">
                <div class="lms-card p-5 space-y-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-[#e07a5f]"></i>
                                <span>{{ __('Cấp Độ HSK') }}</span>
                            </h3>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="lvl in levels" :key="lvl">
                                <button @click="changeLevel(lvl)"
                                        class="h-11 rounded-xl text-xs font-bold transition-all border flex flex-col items-center justify-center btn-tactile"
                                        :class="activeLevel === lvl ?
                                            'bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white border-[#e07a5f] shadow-sm shadow-[#e07a5f]/30' :
                                            'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f]'">
                                    <span class="text-sm font-bold" x-text="lvl"></span>
                                    <span class="text-[9px] font-medium opacity-80" x-text="'HSK ' + lvl"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="h-[1px] bg-[#e8e2d9] dark:bg-[#2d2926]"></div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-500 dark:text-slate-400">{{ __('Tiến độ ghi nhớ') }}</span>
                            <span class="font-bold text-[#e07a5f]" x-text="getProgressPercentage() + '%'"></span>
                        </div>
                        <div class="w-full h-2 bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-[#e07a5f] to-[#c86349] rounded-full transition-all duration-500"
                                 :style="'width: ' + getProgressPercentage() + '%'"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                            <span><span x-text="rememberedInScope()"></span> / <span x-text="totalInScope()"></span> {{ __('đã thuộc') }}</span>
                            <button @click="resetScopeProgress()" class="text-[#e07a5f] hover:underline" :title="'{{ __('Đặt lại tiến độ danh mục này') }}'">
                                {{ __('Học lại') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-3 flex flex-col gap-4">
                <div class="lms-card p-3.5 sm:p-4 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-1.5 p-1 bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl text-xs font-bold">
                        <button @click="activeTab = 'study'"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile"
                                :class="activeTab === 'study' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            <i class="fa-solid fa-layer-group text-xs"></i>
                            <span>{{ __('Thẻ đang học') }}</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="activeTab === 'study' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" x-text="currentWords().length"></span>
                        </button>
                        <button @click="activeTab = 'remembered'"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile"
                                :class="activeTab === 'remembered' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            <span>{{ __('Từ đã thuộc') }}</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="activeTab === 'remembered' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" x-text="rememberedInScope()"></span>
                        </button>
                    </div>
                    <div x-show="activeTab === 'study'" class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold">
                            <span>{{ __('Thẻ') }}:</span>
                            <strong class="text-[#e07a5f]" x-text="currentWords().length > 0 ? (currentIndex + 1) : 0"></strong>
                            <span>/</span>
                            <span x-text="currentWords().length">0</span>
                        </span>
                        <button @click="shuffle()"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold btn-tactile border flex items-center gap-1.5 transition-all"
                                :class="isShuffled ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40'">
                            <i class="fa-solid fa-shuffle"></i>
                            <span x-text="isShuffled ? '{{ __('Bỏ trộn') }}' : '{{ __('Trộn thẻ') }}'"></span>
                        </button>
                        <button @click="autoplayAudio = !autoplayAudio"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold btn-tactile border flex items-center gap-1.5 transition-all"
                                :class="autoplayAudio ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40'">
                            <i class="fa-solid" :class="autoplayAudio ? 'fa-volume-high' : 'fa-volume-xmark'"></i>
                            <span x-text="autoplayAudio ? '{{ __('Tắt tự đọc') }}' : '{{ __('Tự động đọc') }}'"></span>
                        </button>
                    </div>
                </div>
                <div x-show="activeTab === 'study'" class="space-y-4">
                    <template x-if="currentWords().length > 0">
                        <div class="space-y-4">
                            <div class="w-full perspective-1000 cursor-pointer" style="height: 320px; min-height: 320px;" @click="flipCard()">
                                <div class="relative w-full h-full duration-500 transform-style-3d transition-all"
                                     style="height: 320px; min-height: 320px;"
                                     :class="{
                                         'rotate-y-180': flipped,
                                         'translate-x-full opacity-0 scale-95 pointer-events-none': isLeaving
                                     }">
                                    <div class="absolute inset-0 w-full h-full rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] p-5 sm:p-6 flex flex-col justify-between items-center shadow-sm backface-hidden">
                                        <div class="w-full flex justify-between items-center text-xs">
                                            <span class="px-2.5 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-[11px]"
                                                  x-text="'HSK ' + activeLevel"></span>
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                        @click.stop="openAddToDeckModal(currentWord())"
                                                        class="px-2.5 py-1 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-amber-50 dark:hover:bg-amber-950/40 text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 text-xs font-semibold border border-[#e8e2d9] dark:border-[#2d2926] hover:border-amber-300 dark:hover:border-amber-800/80 btn-tactile flex items-center gap-1.5 transition-all shadow-xs cursor-pointer"
                                                        :title="'{{ __('Lưu từ này vào bộ thẻ của bạn') }}'">
                                                    <i class="fa-solid fa-folder-plus text-amber-500 text-xs"></i>
                                                    <span class="hidden sm:inline">{{ __('Thêm vào bộ thẻ') }}</span>
                                                </button>
                                                <button @click.stop="markAsRemembered(currentWord().word, currentWord().id)"
                                                        class="px-2.5 py-1 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 text-xs font-semibold border border-[#e8e2d9] dark:border-[#2d2926] hover:border-emerald-300 dark:hover:border-emerald-800/80 btn-tactile flex items-center gap-1.5 transition-all shadow-xs"
                                                        :title="'{{ __('Đánh dấu từ này đã thuộc') }}'">
                                                    <i class="fa-regular fa-circle-check text-emerald-600 dark:text-emerald-400 text-xs"></i>
                                                    <span>{{ __('Đánh dấu đã thuộc') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-center space-y-3 my-auto">
                                            <div class="text-5xl sm:text-6xl font-bold zh-text text-slate-900 dark:text-white tracking-wider"
                                                 x-text="currentWord().word"></div>
                                            <button @click.stop="speak()"
                                                    class="px-3.5 py-1.5 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs btn-tactile hover:scale-105 inline-flex items-center gap-2 border border-[#fcdccf] dark:border-[#3a2824]">
                                                <i class="fa-solid fa-volume-high text-xs"></i>
                                                <span>{{ __('Nghe phát âm') }}</span>
                                            </button>
                                        </div>
                                        <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                                            <i class="fa-solid fa-hand-pointer text-[#e07a5f] animate-bounce text-xs"></i>
                                            <span>{{ __('Chạm hoặc nhấn Space để lật xem nghĩa & ví dụ') }}</span>
                                        </div>
                                    </div>
                                    <div class="absolute inset-0 w-full h-full rounded-2xl bg-gradient-to-br from-white via-[#fffdfc] to-[#fff7f4] dark:from-[#181615] dark:via-[#1c1917] dark:to-[#241d1a] border border-[#e07a5f]/40 p-5 sm:p-6 flex flex-col justify-between items-center shadow-lg backface-hidden rotate-y-180">
                                        <div class="w-full flex justify-between items-center text-xs border-b border-[#e8e2d9] dark:border-[#2d2926] pb-2.5">
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg sm:text-xl font-bold text-[#e07a5f]" x-text="'[' + (currentWord().pinyin || '') + ']'"></span>
                                                <button @click.stop="speak()" class="text-slate-400 hover:text-[#e07a5f] text-sm p-0.5" title="{{ __('Nghe phát âm từ') }}">
                                                    <i class="fa-solid fa-volume-high text-xs"></i>
                                                </button>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                        @click.stop="openAddToDeckModal(currentWord())"
                                                        class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-700 btn-tactile flex items-center gap-1 px-2.5 py-1 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 cursor-pointer shadow-2xs"
                                                        :title="'{{ __('Lưu từ này vào bộ thẻ của bạn') }}'">
                                                    <i class="fa-solid fa-folder-plus text-[11px]"></i>
                                                    <span>{{ __('Thêm vào bộ thẻ') }}</span>
                                                </button>
                                                <button @click.stop="flipped = false" class="text-xs font-bold text-[#e07a5f] hover:text-[#c86349] btn-tactile flex items-center gap-1">
                                                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                                    <span>{{ __('Lật lại mặt trước') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="w-full my-auto space-y-3 max-h-[190px] sm:max-h-[210px] overflow-y-auto no-scrollbar pr-1">
                                            <div class="p-2.5 rounded-xl bg-white/80 dark:bg-[#181615]/80 border border-[#e8e2d9] dark:border-[#2d2926]">
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">{{ __('Định nghĩa & Ý nghĩa') }}</div>
                                                <div class="text-base sm:text-lg font-bold text-slate-900 dark:text-white" x-text="currentWord().meaning"></div>
                                            </div>
                                            <template x-if="currentWord().example">
                                                <div class="p-2.5 rounded-xl bg-[#faf6f2] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                                        <span>{{ __('Ví dụ mẫu HSK') }}</span>
                                                        <button @click.stop="speak(currentWord().example)" class="text-[#e07a5f] hover:text-[#c86349] text-xs flex items-center gap-1" title="{{ __('Nghe toàn bộ ví dụ') }}">
                                                            <i class="fa-solid fa-volume-high text-[11px]"></i> {{ __('Nghe ví dụ') }}
                                                        </button>
                                                    </div>
                                                    <template x-for="(ex, idx) in (currentWord().example || '').split('\n')" :key="idx">
                                                        <div class="space-y-1 border-b last:border-b-0 border-[#e8e2d9]/60 dark:border-[#2d2926] pb-2 last:pb-0">
                                                            <div class="leading-normal py-0.5" x-html="renderRuby(ex)"></div>
                                                            <div class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed"
                                                                 x-text="(currentWord().example_meaning || '').split('\n')[idx] || ''"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="text-[11px] text-slate-400 text-center">
                                            {{ __('Chạm vào thẻ để lật lại mặt trước hoặc chuyển từ tiếp theo') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2.5">
                                <button @click="prevWord()"
                                        class="py-2.5 px-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f]">
                                    <i class="fa-solid fa-chevron-left text-[11px]"></i>
                                    <span>{{ __('Thẻ trước') }}</span>
                                </button>
                                <button @click="flipCard()"
                                        class="py-2.5 px-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 hover:bg-[#fff2ee] dark:hover:bg-[#2a221f]">
                                    <i class="fa-solid fa-rotate text-[#e07a5f] text-[11px]"></i>
                                    <span>{{ __('Lật thẻ') }}</span>
                                    <span class="text-[10px] text-slate-400 font-normal hidden sm:inline">(Space)</span>
                                </button>
                                <button @click="nextWord()"
                                        class="py-2.5 px-3 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 shadow-xs">
                                    <span>{{ __('Thẻ tiếp') }}</span>
                                    <i class="fa-solid fa-chevron-right text-[11px]"></i>
                                </button>
                            </div>
                            <div class="hidden sm:flex items-center justify-center gap-3 text-[11px] text-slate-400 pt-1">
                                <span><kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold text-[10px]">Space</kbd> {{ __('Lật thẻ') }}</span>
                                <span>•</span>
                                <span><kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold text-[10px]">←</kbd> <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold text-[10px]">→</kbd> {{ __('Chuyển từ') }}</span>
                            </div>
                        </div>
                    </template>
                    <template x-if="currentWords().length === 0">
                        <div class="lms-card p-10 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center flex flex-col items-center justify-center gap-5 my-4">
                            <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 flex items-center justify-center text-amber-500 text-2xl shadow-xs">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <div class="space-y-1.5 max-w-md">
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white"
                                    x-text="'{{ __('Chúc mừng! Bạn đã thuộc hết từ vựng HSK') }} ' + activeLevel"></h3>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Tuyệt vời! Bạn đã ghi nhớ toàn bộ từ vựng trong cấp độ này. Bạn có thể xem lại danh sách từ đã thuộc hoặc tiếp tục sang cấp độ tiếp theo.') }}
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center justify-center gap-3">
                                <button @click="resetScopeProgress()" class="px-4 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:border-[#e07a5f] text-xs font-bold btn-tactile flex items-center gap-2">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    <span>{{ __('Ôn tập lại từ đầu') }}</span>
                                </button>
                                <button @click="activeTab = 'remembered'" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:border-[#e07a5f] text-xs font-bold btn-tactile flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-emerald-600"></i>
                                    <span>{{ __('Xem từ đã thuộc') }}</span>
                                </button>
                                <button @click="activeLevel < 9 ? changeLevel(activeLevel + 1) : null"
                                        x-show="activeLevel < 9"
                                        class="px-4 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-2 shadow-xs">
                                    <span>{{ __('Sang HSK tiếp theo') }}</span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <div x-show="activeTab === 'remembered'" class="space-y-4" x-cloak>
                    <template x-if="rememberedWords().length > 0">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-medium px-1">
                                <span>{{ __('Danh sách các từ vựng bạn đã đánh dấu thuộc trong cấp độ này:') }}</span>
                                <span class="font-bold text-[#e07a5f]" x-text="rememberedWords().length + ' {{ __('từ') }}'"></span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3.5">
                                <template x-for="item in paginatedRememberedWords()" :key="item.id">
                                    <div class="p-4 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] flex flex-col justify-between gap-3 shadow-xs hover:border-[#e07a5f]/50 transition-all group">
                                        <div class="space-y-1.5">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex items-baseline gap-2">
                                                    <span class="text-2xl font-bold zh-text text-slate-900 dark:text-white" x-text="item.word"></span>
                                                    <span class="text-sm font-semibold text-[#e07a5f]" x-text="'[' + (item.pinyin || '') + ']'"></span>
                                                </div>
                                                <button @click="speak(item.word)" class="text-slate-400 hover:text-[#e07a5f] p-1 text-xs" :title="'{{ __('Nghe phát âm') }}'">
                                                    <i class="fa-solid fa-volume-high"></i>
                                                </button>
                                            </div>
                                            <p class="text-xs text-slate-600 dark:text-slate-300 font-medium line-clamp-2" x-text="item.meaning"></p>
                                        </div>
                                        <div class="pt-2 border-t border-[#e8e2d9]/60 dark:border-[#2d2926] flex items-center justify-between text-xs">
                                            <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check text-xs"></i> {{ __('Đã thuộc') }}
                                            </span>
                                            <div class="flex items-center gap-2.5">
                                                <button type="button"
                                                        @click="openAddToDeckModal(item)"
                                                        class="text-[11px] font-bold text-slate-400 hover:text-amber-500 btn-tactile flex items-center gap-1 cursor-pointer"
                                                        :title="'{{ __('Lưu từ này vào bộ thẻ của bạn') }}'">
                                                    <i class="fa-solid fa-folder-plus text-[10px]"></i>
                                                    <span>{{ __('Lưu thẻ') }}</span>
                                                </button>
                                                <button @click="unrememberWord(item.id)"
                                                        class="text-[11px] font-bold text-slate-400 hover:text-[#e07a5f] btn-tactile flex items-center gap-1"
                                                        :title="'{{ __('Bỏ đánh dấu và chuyển về danh sách học') }}'">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                    <span>{{ __('Học lại từ này') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <template x-if="rememberedTotalPages() > 1">
                                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        <span>{{ __('Hiển thị') }}</span>
                                        <strong class="text-slate-800 dark:text-slate-200" x-text="((rememberedPage - 1) * rememberedPerPage + 1) + ' - ' + Math.min(rememberedPage * rememberedPerPage, rememberedWords().length)"></strong>
                                        <span>/</span>
                                        <span x-text="rememberedWords().length"></span>
                                        <span>{{ __('từ') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button @click="goToRememberedPage(rememberedPage - 1)"
                                                :disabled="rememberedPage <= 1"
                                                class="h-8 px-2.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-xs font-bold text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] transition-all flex items-center gap-1 btn-tactile">
                                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                            <span class="hidden sm:inline">{{ __('Trước') }}</span>
                                        </button>
                                        <template x-for="p in rememberedTotalPages()" :key="p">
                                            <button @click="goToRememberedPage(p)"
                                                    class="w-8 h-8 rounded-xl text-xs font-bold transition-all border flex items-center justify-center btn-tactile"
                                                    :class="rememberedPage === p ?
                                                        'bg-[#e07a5f] text-white border-[#e07a5f] shadow-xs' :
                                                        'bg-white dark:bg-[#181615] text-slate-700 dark:text-slate-300 border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40'"
                                                    x-text="p">
                                            </button>
                                        </template>
                                        <button @click="goToRememberedPage(rememberedPage + 1)"
                                                :disabled="rememberedPage >= rememberedTotalPages()"
                                                class="h-8 px-2.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-xs font-bold text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#fff2ee] dark:hover:bg-[#2a221f] transition-all flex items-center gap-1 btn-tactile">
                                            <span class="hidden sm:inline">{{ __('Sau') }}</span>
                                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="rememberedWords().length === 0">
                        <div class="lms-card p-12 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl text-center flex flex-col items-center justify-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-xl shadow-xs">
                                <i class="fa-solid fa-book-bookmark"></i>
                            </div>
                            <div class="space-y-1 max-w-sm">
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Chưa có từ nào đã thuộc') }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('Hãy bắt đầu học flashcard và nhấn "Đánh dấu đã thuộc" ở góc thẻ để lưu vào danh sách này.') }}
                                </p>
                            </div>
                            <button @click="activeTab = 'study'" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-layer-group"></i>
                                <span>{{ __('Bắt đầu học thẻ ngay') }}</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div x-show="isFilterDrawerOpen" class="fixed inset-0 z-50 lg:hidden" x-cloak>
            <div x-show="isFilterDrawerOpen"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="isFilterDrawerOpen = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div x-show="isFilterDrawerOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-250 transform"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 class="fixed inset-x-0 bottom-0 max-h-[85vh] bg-white dark:bg-[#181615] rounded-t-3xl border-t border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl flex flex-col p-6 overflow-y-auto no-scrollbar z-50">
                <div class="flex items-center justify-between pb-4 border-b border-[#e8e2d9] dark:border-[#2d2926] mb-5">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-[#e07a5f]"></i>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Bộ lọc cấp độ HSK') }}</h3>
                    </div>
                    <button @click="isFilterDrawerOpen = false"
                            class="h-8 w-8 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="space-y-4 pb-6">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-[#e07a5f]"></i>
                            <span>{{ __('Cấp Độ HSK') }}</span>
                        </h4>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="lvl in levels" :key="lvl">
                                <button @click="changeLevel(lvl); isFilterDrawerOpen = false"
                                        class="h-11 rounded-xl text-xs font-bold transition-all border flex flex-col items-center justify-center"
                                        :class="activeLevel === lvl ?
                                            'bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white border-[#e07a5f] shadow-sm' :
                                            'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 border-[#e8e2d9] dark:border-[#2d2926]'">
                                    <span class="text-sm font-bold" x-text="lvl"></span>
                                    <span class="text-[9px] font-medium opacity-80" x-text="'HSK ' + lvl"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('portal.student.flashcards.partials.add-to-deck-modal')
    </div>
</div>

    <!-- MY DECKS TAB -->
    <div x-show="activeMainTab === 'bo-the-cua-ban'">
        <div x-data="customFlashcardApp({ isLoggedIn: {{ auth()->check() ? 'true' : 'false' }}, initialDecks: window.myDecksData || [] })" class="space-y-6">
            <div x-show="!selectedDeck"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('portal.student.flashcards.partials.decks-list')
            </div>

            <template x-if="selectedDeck">
                <div x-show="selectedDeck"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    @include('portal.student.flashcards.partials.deck-detail')
                </div>
            </template>

            @include('portal.student.flashcards.partials.deck-modal')
            @include('portal.student.flashcards.partials.card-modal')
            @include('portal.student.flashcards.partials.import-modal')
            @include('portal.student.flashcards.partials.dialog-modal')
        </div>
    </div>
</div>
@endsection
