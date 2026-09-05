@extends('layouts.lms')
@section('title', __('Luyện nghe & Nhận diện Phản xạ Pinyin - Tiếng Trung XIAOMU LMS'))
@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Bảng Pinyin'), 'url' => route('pinyin.index')],
        ['label' => __('Luyện tập Phản xạ Pinyin'), 'url' => null]
    ]" />
@endsection
@section('custom-css')
@keyframes audioEqualizer {
    0%, 100% { height: 6px; }
    50% { height: 24px; }
}
.audio-bar-1 { height: 10px; animation: audioEqualizer 0.65s ease-in-out infinite 0.1s; }
.audio-bar-2 { height: 18px; animation: audioEqualizer 0.75s ease-in-out infinite 0.25s; }
.audio-bar-3 { height: 24px; animation: audioEqualizer 0.6s ease-in-out infinite 0.4s; }
.audio-bar-4 { height: 16px; animation: audioEqualizer 0.8s ease-in-out infinite 0.15s; }
.audio-bar-5 { height: 8px; animation: audioEqualizer 0.7s ease-in-out infinite 0.3s; }
@endsection
@section('content')
    <div x-data="pinyinQuizApp()" x-init="initApp()" class="space-y-6 pb-12"
         @keydown.window="handleGlobalKey($event)">
        {{-- ========================================================================= --}}
        {{-- ========================================================================= --}}
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
            <div class="absolute right-4 -bottom-6 text-9xl font-extrabold text-[#e07a5f]/5 pointer-events-none select-none zh-text">
                练
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="space-y-1.5 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                        <i class="fa-solid fa-headset text-[#e07a5f]"></i> {{ __('Luyện phản xạ âm thanh chuẩn Bắc Kinh') }}
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                        {{ __('Luyện tập Phản xạ Phiên âm Pinyin') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Rèn luyện đôi tai phân biệt 4 Thanh điệu, các cặp âm bật hơi, âm uốn lưỡi và vận mẫu mũi dễ nhầm lẫn.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('pinyin.index') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-[#201d1b] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] text-slate-700 dark:text-slate-200 border border-[#e8e2d9] dark:border-[#2d2926] font-bold text-xs shadow-xs transition-all btn-tactile">
                        <i class="fa-solid fa-table-cells text-[#e07a5f]"></i>
                        <span>{{ __('Bảng Pinyin') }}</span>
                    </a>
                </div>
            </div>
        </div>
        {{-- ========================================================================= --}}
        {{-- ========================================================================= --}}
        <div x-show="screen === 'setup'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-6">
            <div class="lms-card p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                            {{ __('Bước 1: Chọn Chuyên Đề Luyện Tập') }}
                        </h2>
                    </div>
                    <span class="text-[11px] font-medium text-slate-400" x-text="`Có ${filteredTones.length} âm sẵn sàng`"></span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <button type="button" @click="selectedCategory = 'all'; filterTones()"
                            :class="selectedCategory === 'all' ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] ring-2 ring-[#e07a5f]/30' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 hover:bg-[#fff2ee]/40 dark:hover:bg-[#2c221e]/40'"
                            class="p-3.5 rounded-2xl border text-left transition-all btn-tactile flex items-start gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] flex items-center justify-center font-bold text-base shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            🎯
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-[#e07a5f] transition-colors truncate">
                                {{ __('Tổng Hợp Toàn Diện') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug">
                                {{ __('Luyện phản xạ ngẫu nhiên toàn bộ hơn 1,500 âm tiết Pinyin.') }}
                            </p>
                        </div>
                    </button>
                    <button type="button" @click="selectedCategory = 'tones'; filterTones()"
                            :class="selectedCategory === 'tones' ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] ring-2 ring-[#e07a5f]/30' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 hover:bg-[#fff2ee]/40 dark:hover:bg-[#2c221e]/40'"
                            class="p-3.5 rounded-2xl border text-left transition-all btn-tactile flex items-start gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] flex items-center justify-center font-bold text-base shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            🎵
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-[#e07a5f] transition-colors truncate">
                                {{ __('Phân Biệt 4 Thanh Điệu') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug">
                                {{ __('Cùng 1 âm tiết, luyện phân biệt Thanh 1, 2, 3, 4.') }}
                            </p>
                        </div>
                    </button>
                    <button type="button" @click="selectedCategory = 'aspirated'; filterTones()"
                            :class="selectedCategory === 'aspirated' ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] ring-2 ring-[#e07a5f]/30' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 hover:bg-[#fff2ee]/40 dark:hover:bg-[#2c221e]/40'"
                            class="p-3.5 rounded-2xl border text-left transition-all btn-tactile flex items-start gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] flex items-center justify-center font-bold text-base shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            💨
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-[#e07a5f] transition-colors truncate">
                                {{ __('Cặp Âm Bật Hơi') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug">
                                {{ __('Phân biệt b/p, d/t, g/k, j/q, z/c, zh/ch.') }}
                            </p>
                        </div>
                    </button>
                    <button type="button" @click="selectedCategory = 'retroflex'; filterTones()"
                            :class="selectedCategory === 'retroflex' ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] ring-2 ring-[#e07a5f]/30' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 hover:bg-[#fff2ee]/40 dark:hover:bg-[#2c221e]/40'"
                            class="p-3.5 rounded-2xl border text-left transition-all btn-tactile flex items-start gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] flex items-center justify-center font-bold text-base shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            🌀
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-[#e07a5f] transition-colors truncate">
                                {{ __('Âm Uốn Lưỡi & Đầu Lưỡi') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug">
                                {{ __('Luyện phản xạ phân biệt z/zh, c/ch, s/sh, r.') }}
                            </p>
                        </div>
                    </button>
                    <button type="button" @click="selectedCategory = 'nasal'; filterTones()"
                            :class="selectedCategory === 'nasal' ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] ring-2 ring-[#e07a5f]/30' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 hover:bg-[#fff2ee]/40 dark:hover:bg-[#2c221e]/40'"
                            class="p-3.5 rounded-2xl border text-left transition-all btn-tactile flex items-start gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] flex items-center justify-center font-bold text-base shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            👃
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-[#e07a5f] transition-colors truncate">
                                {{ __('Vận Mẫu Mũi Trước / Sau') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug">
                                {{ __('Phân biệt âm đuôi mũi: an/ang, en/eng, in/ing.') }}
                            </p>
                        </div>
                    </button>
                    <button type="button" @click="selectedCategory = 'labial'; filterTones()"
                            :class="selectedCategory === 'labial' ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] ring-2 ring-[#e07a5f]/30' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 hover:bg-[#fff2ee]/40 dark:hover:bg-[#2c221e]/40'"
                            class="p-3.5 rounded-2xl border text-left transition-all btn-tactile flex items-start gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] flex items-center justify-center font-bold text-base shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            👄
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-[#e07a5f] transition-colors truncate">
                                {{ __('Nhóm Âm Môi & Răng Môi') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug">
                                {{ __('Luyện nhóm thanh mẫu nền tảng b, p, m, f.') }}
                            </p>
                        </div>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="lms-card p-5 space-y-3.5">
                    <div class="flex items-center gap-2 border-b border-[#e8e2d9] dark:border-[#2d2926] pb-2.5">
                        <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                            {{ __('Bước 2: Số Lượng Câu Hỏi') }}
                        </h2>
                    </div>
                    <div class="grid grid-cols-3 gap-2.5">
                        <button type="button" @click="quizLength = 10"
                                :class="quizLength === 10 ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 text-slate-700 dark:text-slate-300'"
                                class="p-3 rounded-xl border text-center transition-all btn-tactile cursor-pointer">
                            <span class="block text-lg font-bold">10</span>
                            <span class="text-[10px] text-slate-400 block">{{ __('Nhanh (~2p)') }}</span>
                        </button>
                        <button type="button" @click="quizLength = 20"
                                :class="quizLength === 20 ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 text-slate-700 dark:text-slate-300'"
                                class="p-3 rounded-xl border text-center transition-all btn-tactile cursor-pointer">
                            <span class="block text-lg font-bold">20</span>
                            <span class="text-[10px] text-slate-400 block">{{ __('Chuẩn (~5p)') }}</span>
                        </button>
                        <button type="button" @click="quizLength = 50"
                                :class="quizLength === 50 ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold' : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 text-slate-700 dark:text-slate-300'"
                                class="p-3 rounded-xl border text-center transition-all btn-tactile cursor-pointer">
                            <span class="block text-lg font-bold">50</span>
                            <span class="text-[10px] text-slate-400 block">{{ __('Thử thách') }}</span>
                        </button>
                    </div>
                </div>
                <div class="lms-card p-5 space-y-3.5">
                    <div class="flex items-center gap-2 border-b border-[#e8e2d9] dark:border-[#2d2926] pb-2.5">
                        <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                            {{ __('Bước 3: Tùy Chọn & Phím Tắt') }}
                        </h2>
                    </div>
                    <div class="space-y-2.5">
                        <label class="flex items-center justify-between p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] cursor-pointer">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-bolt text-[#e07a5f] text-xs"></i>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ __('Tự động chuyển câu khi trả lời đúng') }}</span>
                            </div>
                            <input type="checkbox" x-model="autoAdvance" class="rounded text-[#e07a5f] focus:ring-[#e07a5f] w-4 h-4 cursor-pointer">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] cursor-pointer">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-volume-high text-[#e07a5f] text-xs"></i>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ __('Tự động phát âm thanh khi vào câu mới') }}</span>
                            </div>
                            <input type="checkbox" x-model="autoPlayAudio" class="rounded text-[#e07a5f] focus:ring-[#e07a5f] w-4 h-4 cursor-pointer">
                        </label>
                    </div>
                </div>
            </div>
            <div class="text-center pt-2">
                <button type="button" @click="startQuiz()"
                        class="inline-flex items-center gap-2.5 px-6 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs sm:text-sm shadow-xs transition-all btn-tactile cursor-pointer select-none">
                    <span>{{ __('Bắt Đầu Luyện Tập Ngay') }}</span>
                    <i class="fa-solid fa-play text-xs pointer-events-none"></i>
                </button>
                <p class="text-[11px] text-slate-400 mt-1.5 font-medium">
                    {{ __('Nhấn phím Enter để bắt đầu nhanh') }}
                </p>
            </div>
        </div>
        {{-- ========================================================================= --}}
        {{-- ========================================================================= --}}
        <div x-show="screen === 'quiz'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-98"
             x-transition:enter-end="opacity-100 scale-100"
             class="space-y-5 max-w-2xl mx-auto"
             style="display: none;">
            <div class="flex flex-wrap items-center justify-between gap-3 p-3.5 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">
                <button type="button" @click="quitQuizPrompt()"
                        class="px-3 py-1.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 text-slate-600 dark:text-slate-300 text-xs font-bold border border-[#e8e2d9] dark:border-[#2d2926] flex items-center gap-1.5 transition-all btn-tactile cursor-pointer"
                        title="{{ __('Thoát về màn hình chọn bài') }}">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span class="hidden sm:inline">{{ __('Thoát') }}</span>
                </button>
                {{-- Progress Bar --}}
                <div class="flex items-center gap-2.5 flex-1 max-w-xs mx-auto">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                        <span x-text="questionInRound"></span>/<span x-text="quizLength"></span>
                    </span>
                    <div class="flex-1 h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full rounded-full bg-[#e07a5f] transition-all duration-300" 
                             :style="`width: ${(questionInRound / quizLength) * 100}%`"></div>
                    </div>
                </div>
                {{-- Streak & Score Badges --}}
                <div class="flex items-center gap-2">
                    <div class="px-3 py-1 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-700 dark:text-amber-300 text-xs font-bold flex items-center gap-1.5"
                         title="{{ __('Chuỗi câu trả lời đúng liên tiếp') }}">
                        <i class="fa-solid fa-fire text-amber-500 animate-pulse text-xs"></i>
                        <span>x<span x-text="streak"></span></span>
                    </div>
                    <div class="px-3 py-1 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-trophy text-[#e07a5f] text-xs"></i>
                        <span><span x-text="score"></span> {{ __('Điểm') }}</span>
                    </div>
                </div>
            </div>
            <div class="lms-card p-5 sm:p-6 space-y-5 text-center relative overflow-hidden">
                <div x-show="!answered" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="flex flex-col items-center justify-center space-y-3.5 pt-1 pb-1">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold transition-all duration-300"
                         :class="isPlaying 
                            ? 'bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f4978e] border border-[#fcdccf] dark:border-[#4a2e26] shadow-xs' 
                            : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-500 dark:text-slate-400 border border-[#e8e2d9] dark:border-[#2d2926]'">
                        <span class="relative flex h-2 w-2">
                            <span x-show="isPlaying" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#e07a5f] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2" :class="isPlaying ? 'bg-[#e07a5f]' : 'bg-slate-400 dark:bg-slate-500'"></span>
                        </span>
                        <span class="tracking-wider uppercase text-[11px] font-bold" x-text="isPlaying ? '{{ __('Đang phát âm thanh...') }}' : '{{ __('Nghe phát âm và chọn đáp án') }}'"></span>
                    </div>
                    <div class="relative flex items-center justify-center my-1">
                        <div x-show="isPlaying" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute w-28 h-28 sm:w-32 sm:h-32 rounded-full border-2 border-dashed border-[#e07a5f]/30 dark:border-[#e07a5f]/25 animate-[spin_12s_linear_infinite] pointer-events-none">
                        </div>
                        <div class="w-22 h-22 sm:w-24 sm:h-24 rounded-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center p-2 transition-all duration-300 shadow-inner"
                             :class="isPlaying ? 'ring-4 ring-[#e07a5f]/20 dark:ring-[#e07a5f]/25 scale-105' : 'hover:border-[#e07a5f]/40'">
                            <button type="button" @click="playAudio(1.0)" 
                                    class="w-full h-full rounded-full bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white flex items-center justify-center shadow-md shadow-[#e07a5f]/25 hover:shadow-lg hover:shadow-[#e07a5f]/40 hover:scale-105 active:scale-95 transition-all duration-200 cursor-pointer group relative overflow-hidden btn-tactile"
                                    title="{{ __('Bấm hoặc nhấn Space để nghe lại') }}">
                                <div x-show="isPlaying" class="flex items-center justify-center gap-1 h-6" style="display: none;">
                                    <span class="w-1 bg-white rounded-full audio-bar-1"></span>
                                    <span class="w-1 bg-white rounded-full audio-bar-2"></span>
                                    <span class="w-1 bg-white rounded-full audio-bar-3"></span>
                                    <span class="w-1 bg-white rounded-full audio-bar-4"></span>
                                    <span class="w-1 bg-white rounded-full audio-bar-5"></span>
                                </div>
                                <div x-show="!isPlaying" class="flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current drop-shadow-xs" viewBox="0 0 24 24">
                                        <path d="M14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77zm-2.5-1.73L6.83 6H3c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h3.83l4.67 4.5c.67.65 1.77.18 1.77-.75V2.25c0-.93-1.1-1.4-1.77-.75zM14 8.83v6.34c1.24-.58 2-1.82 2-3.17s-.76-2.59-2-3.17z"/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-center pt-0.5">
                        <button type="button" @click="playAudio(1.0)" 
                                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#f4978e] font-semibold text-xs border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/40 transition-all btn-tactile shadow-2xs cursor-pointer select-none">
                            <i class="fa-solid fa-rotate-right text-[11px] text-[#e07a5f]"></i>
                            <span>{{ __('Nghe lại') }}</span>
                            <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[10px] font-mono text-slate-500 dark:text-slate-400 font-bold">Space</kbd>
                        </button>
                    </div>
                </div>
                <div x-show="answered" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @mouseenter="pauseAdvance()"
                     @mouseleave="resumeAdvance()"
                     class="space-y-3 pt-1 text-left"
                     style="display: none;">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 p-3 sm:p-3.5 rounded-2xl border relative overflow-hidden transition-all shadow-xs"
                         :class="isCorrect 
                            ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/60 text-emerald-900 dark:text-emerald-200' 
                            : 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800/60 text-rose-900 dark:text-rose-200'">
                        <div x-show="autoAdvance && isCorrect && advanceTimerActive" 
                             class="absolute top-0 left-0 right-0 h-1 bg-emerald-200/50 dark:bg-emerald-900/50 overflow-hidden">
                            <div class="h-full bg-emerald-500 transition-all duration-75"
                                 :style="`width: ${advanceProgress}%`"></div>
                        </div>
                        <div class="flex items-center gap-2.5 w-full sm:w-auto">
                            <div class="shrink-0 w-8 h-8 rounded-xl flex items-center justify-center shadow-2xs"
                                 :class="isCorrect ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400'">
                                <template x-if="isCorrect">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                    </svg>
                                </template>
                                <template x-if="!isCorrect">
                                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
                                    </svg>
                                </template>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-xs sm:text-sm block" x-text="isCorrect ? '{{ __('Chính xác! +10 Điểm 🎉') }}' : '{{ __('Chưa chính xác! ❌') }}'"></span>
                                    <template x-if="isCorrect && isPausedAdvance">
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-200/70 dark:bg-emerald-800/60 text-emerald-900 dark:text-emerald-200 font-semibold animate-pulse">
                                            {{ __('Đã tạm dừng tự chuyển câu') }}
                                        </span>
                                    </template>
                                </div>
                                <span class="text-[11px] opacity-90 truncate block" 
                                      x-text="isCorrect ? '{{ __('Bạn đã nhận diện đúng chuẩn thanh điệu.') }}' : `{{ __('Đáp án đúng là:') }} ${targetTone ? formatPinyin(targetTone.display) : ''}`"></span>
                            </div>
                        </div>
                        <button type="button" @click="handleNextStep()" 
                                class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all btn-tactile cursor-pointer shrink-0 shadow-xs">
                            <span>{{ __('Câu tiếp theo') }}</span>
                            <span class="text-[10px] opacity-75 font-mono">(Enter)</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-atom text-[#e07a5f]"></i>
                                {{ __('Cấu tạo âm') }}: <span class="text-slate-900 dark:text-white font-bold zh-text text-sm normal-case" x-text="targetTone ? formatPinyin(targetTone.display) : ''"></span>
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-[#e07a5f]" x-text="`Thanh ${targetTone ? targetTone.tone_number : ''}`"></span>
                                <button type="button" @click="playAudio(1.0)" 
                                        class="px-2 py-0.5 rounded-lg bg-white dark:bg-[#181615] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] text-xs font-semibold flex items-center gap-1 transition-colors btn-tactile cursor-pointer"
                                        title="{{ __('Nghe lại âm thanh chuẩn') }}">
                                    <i class="fa-solid fa-volume-high text-[10px]"></i>
                                    <span class="text-[10px]">{{ __('Nghe lại') }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="p-2 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <span class="text-[10px] font-medium text-slate-400 block">{{ __('Thanh mẫu') }}</span>
                                <span class="font-bold text-[#e07a5f] text-sm sm:text-base zh-text" x-text="targetTone && targetTone.initial ? targetTone.initial : '—'"></span>
                            </div>
                            <div class="p-2 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <span class="text-[10px] font-medium text-slate-400 block">{{ __('Vận mẫu') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200 text-sm sm:text-base zh-text" x-text="targetTone && targetTone.final ? targetTone.final : '—'"></span>
                            </div>
                            <div class="p-2 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <span class="text-[10px] font-medium text-slate-400 block">{{ __('Cao độ') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200 text-xs sm:text-sm truncate block" x-text="getPitchDescription(targetTone ? targetTone.tone_number : 0)"></span>
                            </div>
                        </div>
                        <template x-if="!isCorrect && selectedOpt">
                            <div class="pt-1.5 border-t border-[#e8e2d9] dark:border-[#2d2926] flex flex-wrap items-center justify-between gap-2">
                                <span class="text-[11px] font-semibold text-slate-600 dark:text-slate-400">{{ __('So sánh phát âm:') }}</span>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="playSpecificToneAudio(targetTone)" 
                                            class="px-2.5 py-1 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[11px] font-bold flex items-center gap-1 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-volume-high text-[10px]"></i>
                                        <span x-text="`{{ __('Âm đúng:') }} ${targetTone ? formatPinyin(targetTone.display) : ''}`"></span>
                                    </button>
                                    <button type="button" @click="playSpecificToneAudio(selectedOpt)" 
                                            class="px-2.5 py-1 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 text-[11px] font-bold flex items-center gap-1 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-volume-high text-[10px]"></i>
                                        <span x-text="`{{ __('Bạn chọn:') }} ${selectedOpt ? formatPinyin(selectedOpt.display) : ''}`"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 max-w-md mx-auto pt-1">
                    <template x-for="(opt, idx) in currentOptions" :key="opt.id + '_' + idx">
                        <button type="button" @click="selectAnswer(opt)" 
                                :disabled="answered"
                                :class="getOptionClass(opt)" 
                                class="p-3.5 sm:p-4 rounded-2xl border-2 btn-tactile flex flex-col items-center justify-center min-h-[85px] sm:min-h-[95px] relative transition-all cursor-pointer select-none">
                            {{-- Option Letter Badge (A, B, C, D) --}}
                            <span class="absolute top-2.5 left-2.5 text-[10px] w-5 h-5 rounded-md flex items-center justify-center font-bold"
                                  :class="getBadgeClass(opt)"
                                  x-text="['A', 'B', 'C', 'D'][idx]"></span>
                            {{-- Pinyin Display --}}
                            <span x-text="formatPinyin(opt.display)" class="text-3xl font-bold tracking-normal zh-text"></span>
                            <div class="absolute top-2.5 right-2.5">
                                <template x-if="answered && opt.id === targetTone.id">
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg animate-bounce"></i>
                                </template>
                                <template x-if="answered && selectedOpt && selectedOpt.id === opt.id && opt.id !== targetTone.id">
                                    <i class="fa-solid fa-circle-xmark text-rose-500 text-lg"></i>
                                </template>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3 text-[11px] text-slate-400">
                <span><kbd class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono text-[10px]">1-4</kbd> / <kbd class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono text-[10px]">A-D</kbd> Chọn đáp án</span>
                <span>•</span>
                <span><kbd class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono text-[10px]">Space</kbd> Nghe lại</span>
                <span>•</span>
                <span><kbd class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono text-[10px]">Enter</kbd> Tiếp tục</span>
            </div>
        </div>
        {{-- ========================================================================= --}}
        {{-- ========================================================================= --}}
        <div x-show="screen === 'summary'" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="space-y-5 max-w-xl mx-auto"
             style="display: none;">
            <div class="lms-card p-5 sm:p-6 text-center space-y-5">
                {{-- Trophy Icon --}}
                <div class="w-16 h-16 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center mx-auto border-4 border-white dark:border-[#181615] shadow-lg">
                    <i class="fa-solid fa-trophy text-3xl animate-bounce"></i>
                </div>
                <div class="space-y-1">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight"
                        x-text="getEvaluationTitle()">
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400"
                       x-text="`Bạn đã hoàn thành bài luyện ${quizLength} câu phản xạ Pinyin.`">
                    </p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Độ chính xác') }}</span>
                        <span class="text-xl font-bold text-[#e07a5f] mt-0.5" x-text="`${Math.round((correctCount / quizLength) * 100)}%`"></span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Tổng Điểm') }}</span>
                        <span class="text-xl font-bold text-slate-800 dark:text-white mt-0.5" x-text="score"></span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Streak Dài Nhất') }}</span>
                        <span class="text-xl font-bold text-amber-500 mt-0.5" x-text="maxStreak"></span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Đúng / Tổng') }}</span>
                        <span class="text-xl font-bold text-emerald-600 mt-0.5" x-text="`${correctCount}/${quizLength}`"></span>
                    </div>
                </div>
                <template x-if="mistakes.length > 0">
                    <div class="space-y-2.5 text-left pt-1">
                        <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-2">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-rose-500 text-xs"></i>
                                <span>{{ __('Các câu cần ôn tập lại') }} (<span x-text="mistakes.length"></span>)</span>
                            </span>
                            <span class="text-[11px] text-slate-400 font-medium">{{ __('Nhấp loa để nghe lại') }}</span>
                        </div>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1 no-scrollbar">
                            <template x-for="(m, idx) in mistakes" :key="idx">
                                <div class="p-3 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <button type="button" @click="playSpecificToneAudio(m.target)"
                                                class="w-8 h-8 rounded-full bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white flex items-center justify-center transition-all cursor-pointer shadow-xs shrink-0">
                                            <i class="fa-solid fa-volume-high text-xs pointer-events-none"></i>
                                        </button>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-emerald-600 text-sm zh-text" x-text="formatPinyin(m.target.display)"></span>
                                                <span class="text-[11px] text-slate-400 font-medium">(Thanh <span x-text="m.target.tone_number"></span>)</span>
                                            </div>
                                            <span class="text-[11px] text-rose-500 font-medium" x-text="`{{ __('Bạn đã chọn:') }} ${m.chosen ? formatPinyin(m.chosen.display) : '{{ __('Chưa chọn') }}'}`"></span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300" x-text="`#${idx + 1}`"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                {{-- Action Buttons --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                    <template x-if="mistakes.length > 0">
                        <button type="button" @click="retryMistakesOnly()"
                                class="py-2.5 px-3.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-xs transition-all btn-tactile flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-arrow-rotate-left text-xs"></i>
                            <span>{{ __('Luyện lại các câu sai') }} (<span x-text="mistakes.length"></span>)</span>
                        </button>
                    </template>
                    <button type="button" @click="startQuiz()"
                            :class="mistakes.length === 0 ? 'sm:col-span-2' : ''"
                            class="py-2.5 px-3.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs transition-all btn-tactile flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-play text-xs"></i>
                        <span>{{ __('Luyện lượt mới (Cùng chủ đề)') }}</span>
                    </button>
                    <button type="button" @click="screen = 'setup'"
                            :class="mistakes.length === 0 ? 'sm:col-span-2' : 'sm:col-span-2'"
                            class="py-2 px-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] text-slate-700 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] font-bold text-xs transition-all btn-tactile flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-sliders text-xs"></i>
                        <span>{{ __('Đổi chủ đề / cài đặt khác') }}</span>
                    </button>
                </div>
            </div>
        </div>
        <div x-show="showQuitModal"
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center px-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancelQuit()"></div>
            <div class="lms-card p-6 bg-white dark:bg-[#181615] w-full max-w-sm relative z-10 shadow-2xl"
                 x-transition:enter="transition ease-out duration-300 delay-75"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 @click.stop>
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="w-14 h-14 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-500 flex items-center justify-center border-4 border-white dark:border-[#23201e] shadow-sm">
                        <i class="fa-solid fa-person-walking-arrow-right text-2xl"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ __('Tạm dừng luyện tập?') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Tiến trình hiện tại của bạn sẽ bị hủy bỏ. Bạn có chắc chắn muốn thoát về màn hình chọn bài?') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3 w-full pt-2">
                        <button type="button" @click="cancelQuit()"
                                class="flex-1 py-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs border border-[#e8e2d9] dark:border-[#2d2926] transition-colors btn-tactile">
                            {{ __('Tiếp tục làm bài') }}
                        </button>
                        <button type="button" @click="confirmQuit()"
                                class="flex-1 py-2.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs shadow-xs shadow-rose-500/20 transition-colors btn-tactile">
                            {{ __('Đồng ý thoát') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <audio x-ref="audioPlayer" class="hidden"></audio>
    </div>
<script>
function pinyinQuizApp() {
    return {
        // Dữ liệu gốc từ Database
        allTones: {!! $quizTonesJson !!},
        filteredTones: [],
        // Trạng thái màn hình: 'setup' | 'quiz' | 'summary'
        screen: 'setup',
        showQuitModal: false,
        // Cấu hình bài quiz
        selectedCategory: 'all',
        quizLength: 10,
        autoAdvance: true,
        autoPlayAudio: true,
        playbackRate: 1.0,
        // Tiến trình làm bài
        questionInRound: 1,
        targetTone: null,
        currentOptions: [],
        selectedOpt: null,
        answered: false,
        isCorrect: false,
        // Điểm số & Thành tích
        score: 0,
        streak: 0,
        maxStreak: 0,
        correctCount: 0,
        mistakes: [],
        isMistakePracticeMode: false,
        mistakePool: [],
        // Trạng thái audio
        isPlaying: false,
        _audioUnlocked: false,  // Theo dõi xem AudioContext đã được unlock chưa
        // Quản lý đếm lùi tự động chuyển câu (Auto Advance Countdown & Pause on Hover)
        advanceInterval: null,
        advanceProgress: 100,
        advanceTimerActive: false,
        isPausedAdvance: false,
        remainingAdvanceMs: 2500,
        advanceTargetEndTime: 0,
        formatPinyin(pinyin) {
            if (!pinyin) return '';
            let str = String(pinyin).trim();
            str = str.replace(/uue/gi, 'üe').replace(/uun/gi, 'ün').replace(/uu/gi, 'ü');
            str = str.replace(/v/g, 'ü').replace(/V/g, 'Ü');
            return typeof window.toneToUnicode === 'function' ? window.toneToUnicode(str) : str;
        },
        initApp() {
            this.filterTones();
        },
        filterTones() {
            if (this.selectedCategory === 'tones') {
                this.filteredTones = this.allTones.filter(t => t.tone_number >= 1 && t.tone_number <= 4);
            } else if (this.selectedCategory === 'aspirated') {
                const aspInitials = ['b', 'p', 'd', 't', 'g', 'k', 'j', 'q', 'z', 'c', 'zh', 'ch'];
                this.filteredTones = this.allTones.filter(t => aspInitials.includes(t.initial));
            } else if (this.selectedCategory === 'retroflex') {
                const retInitials = ['zh', 'ch', 'sh', 'r', 'z', 'c', 's'];
                this.filteredTones = this.allTones.filter(t => retInitials.includes(t.initial));
            } else if (this.selectedCategory === 'nasal') {
                const nasalFinals = ['an', 'ang', 'en', 'eng', 'in', 'ing', 'ian', 'iang', 'uan', 'uang', 'uen', 'ueng', 'ong', 'iong'];
                this.filteredTones = this.allTones.filter(t => nasalFinals.includes(t.final));
            } else if (this.selectedCategory === 'labial') {
                const labInitials = ['b', 'p', 'm', 'f'];
                this.filteredTones = this.allTones.filter(t => labInitials.includes(t.initial));
            } else {
                this.filteredTones = [...this.allTones];
            }
            if (this.filteredTones.length < 4) {
                this.filteredTones = [...this.allTones];
            }
        },
        startQuiz() {
            this.isMistakePracticeMode = false;
            this.score = 0;
            this.streak = 0;
            this.maxStreak = 0;
            this.correctCount = 0;
            this.questionInRound = 1;
            this.mistakes = [];
            this.filterTones();
            this.screen = 'quiz';
            this.nextQuestion(this.autoPlayAudio);
        },
        retryMistakesOnly() {
            if (this.mistakes.length === 0) return;
            this.isMistakePracticeMode = true;
            this.mistakePool = this.mistakes.map(m => m.target);
            this.quizLength = this.mistakePool.length;
            this.questionInRound = 1;
            this.score = 0;
            this.streak = 0;
            this.correctCount = 0;
            this.mistakes = [];
            this.screen = 'quiz';
            this.nextQuestion(this.autoPlayAudio);
        },
        quitQuizPrompt() {
            this.pauseAdvance();
            this.showQuitModal = true;
        },
        confirmQuit() {
            this.clearAdvanceTimers();
            this.showQuitModal = false;
            this.screen = 'setup';
        },
        cancelQuit() {
            this.showQuitModal = false;
            this.resumeAdvance();
        },
        nextQuestion(autoPlay = true) {
            this.clearAdvanceTimers();
            this.answered = false;
            this.selectedOpt = null;
            this.isCorrect = false;
            let pool = this.filteredTones;
            if (this.isMistakePracticeMode && this.mistakePool.length > 0) {
                this.targetTone = this.mistakePool[this.questionInRound - 1] || this.mistakePool[0];
            } else {
                const randIdx = Math.floor(Math.random() * pool.length);
                this.targetTone = pool[randIdx];
            }
            // Sinh đáp án gây nhiễu thông minh (Smart Distractors)
            const distractors = this.getSmartDistractors(this.targetTone, pool);
            this.currentOptions = [this.targetTone, ...distractors].sort(() => 0.5 - Math.random());
            if (autoPlay) {
                const player = this.$refs.audioPlayer;
                if (player && this.targetTone && this.targetTone.audio_path) {
                    const audioUrl = '/storage/audio/pinyin/' + this.targetTone.audio_path;
                    player.src = audioUrl;
                    player.load();
                }
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.playAudio();
                    }, 80);
                });
            }
        },
        getSmartDistractors(target, pool) {
            const distractors = [];
            const addedIds = new Set([target.id]);
            // Ưu tiên 1: Lấy các thanh điệu khác của cùng 1 âm tiết (ví dụ mā -> má, mǎ, mà)
            const sameSyllableTones = this.allTones.filter(t => !addedIds.has(t.id) && t.pinyin_id === target.pinyin_id);
            this.shuffleArray(sameSyllableTones);
            for (let t of sameSyllableTones) {
                if (distractors.length >= (this.selectedCategory === 'tones' ? 3 : 2)) break;
                distractors.push(t);
                addedIds.add(t.id);
            }
            // Ưu tiên 2: Cặp âm tương đồng (Confusing Pairs)
            if (distractors.length < 3) {
                const SIMILAR_INITIALS = {
                    'b': ['p', 'm'], 'p': ['b', 'f'], 'm': ['b', 'n'], 'f': ['p', 'h'],
                    'd': ['t', 'n'], 't': ['d', 'l'], 'n': ['l', 'm'], 'l': ['n', 'r'],
                    'g': ['k', 'h'], 'k': ['g', 'h'], 'h': ['k', 'f'],
                    'j': ['q', 'x'], 'q': ['j', 'x'], 'x': ['j', 'q'],
                    'zh': ['ch', 'sh', 'z'], 'ch': ['zh', 'sh', 'c'], 'sh': ['zh', 'ch', 's', 'r'], 'r': ['l', 'sh'],
                    'z': ['c', 's', 'zh'], 'c': ['z', 's', 'ch'], 's': ['z', 'c', 'sh']
                };
                const targetInitial = target.initial || '';
                const simInitials = SIMILAR_INITIALS[targetInitial] || [];
                if (simInitials.length > 0) {
                    const simInitialTones = pool.filter(t => !addedIds.has(t.id) && simInitials.includes(t.initial));
                    if (simInitialTones.length > 0) {
                        this.shuffleArray(simInitialTones);
                        distractors.push(simInitialTones[0]);
                        addedIds.add(simInitialTones[0].id);
                    }
                }
            }
            // Ưu tiên 3: Điền ngẫu nhiên nếu còn thiếu
            if (distractors.length < 3) {
                const remaining = pool.filter(t => !addedIds.has(t.id));
                this.shuffleArray(remaining);
                for (let t of remaining) {
                    if (distractors.length >= 3) break;
                    distractors.push(t);
                    addedIds.add(t.id);
                }
            }
            return distractors;
        },
        shuffleArray(arr) {
            for (let i = arr.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
        },
        playAudio(rate = 1.0) {
            if (!this.targetTone || !this.targetTone.audio_path) return;
            const player = this.$refs.audioPlayer;
            if (!player) return;
            const audioUrl = '/storage/audio/pinyin/' + this.targetTone.audio_path;
            if (player.src !== window.location.origin + audioUrl) {
                try { player.pause(); player.currentTime = 0; } catch (e) {}
                player.src = audioUrl;
                player.load();
            } else {
                try { player.pause(); player.currentTime = 0; } catch (e) {}
            }
            player.playbackRate = rate;
            this.isPlaying = true;
            player.onended = () => { this.isPlaying = false; };
            player.onerror = () => { this.isPlaying = false; };
            const playPromise = player.play();
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    this.isPlaying = true;
                }).catch(e => {
                    console.warn('Audio autoplay prevented or interrupted:', e);
                    this.isPlaying = false;
                    setTimeout(() => {
                        player.play().then(() => {
                            this.isPlaying = true;
                        }).catch(() => {
                            if (window.playWordAudio && this.targetTone) {
                                window.playWordAudio(this.targetTone.display || this.targetTone.full_pinyin);
                            }
                        });
                    }, 200);
                });
            }
        },
        playSpecificToneAudio(tone) {
            if (!tone || !tone.audio_path) return;
            const player = this.$refs.audioPlayer;
            if (!player) return;
            try {
                player.pause();
                player.currentTime = 0;
            } catch (e) {}
            player.src = '/storage/audio/pinyin/' + tone.audio_path;
            player.playbackRate = 1.0;
            player.load();
            player.play().catch(e => {
                console.warn('Audio playback error:', e);
                if (window.playWordAudio) {
                    window.playWordAudio(tone.display || tone.full_pinyin);
                }
            });
        },
        selectAnswer(opt) {
            if (this.answered) return;
            this._unlockAudioContext();
            this.selectedOpt = opt;
            this.answered = true;
            if (opt.id === this.targetTone.id) {
                this.isCorrect = true;
                this.score += 10;
                this.correctCount++;
                this.streak += 1;
                if (this.streak > this.maxStreak) {
                    this.maxStreak = this.streak;
                }
                this.playSynthSound('correct');
                if (this.autoAdvance) {
                    // Tự động chuyển câu sau 2.5s (2500ms), có thanh đếm lùi trực quan và tạm dừng khi hover đọc giải thích
                    this.startAutoAdvance(2500);
                }
            } else {
                this.isCorrect = false;
                this.streak = 0;
                this.mistakes.push({
                    target: this.targetTone,
                    chosen: opt
                });
                this.playSynthSound('wrong');
            }
        },
        _unlockAudioContext() {
            if (this._audioUnlocked) return;
            const player = this.$refs.audioPlayer;
            if (!player) return;
            const silentSrc = player.src;
            if (!silentSrc || silentSrc === window.location.href) {
                if (this.targetTone && this.targetTone.audio_path) {
                    player.src = '/storage/audio/pinyin/' + this.targetTone.audio_path;
                    player.load();
                }
            }
            const unlockPromise = player.play();
            if (unlockPromise !== undefined) {
                unlockPromise.then(() => {
                    player.pause();
                    player.currentTime = 0;
                    this._audioUnlocked = true;
                }).catch(() => {
                });
            }
        },
        startAutoAdvance(duration = 2500) {
            this.clearAdvanceTimers();
            if (!this.autoAdvance || !this.isCorrect) return;
            this.advanceProgress = 100;
            this.advanceTimerActive = true;
            this.isPausedAdvance = false;
            this.remainingAdvanceMs = duration;
            this.advanceTargetEndTime = Date.now() + duration;
            this.advanceInterval = setInterval(() => {
                if (this.isPausedAdvance) return;
                const remaining = this.advanceTargetEndTime - Date.now();
                this.remainingAdvanceMs = remaining;
                this.advanceProgress = Math.max(0, (remaining / duration) * 100);
                if (remaining <= 0) {
                    this.clearAdvanceTimers();
                    this.handleNextStep();
                }
            }, 40);
        },
        pauseAdvance() {
            if (this.advanceTimerActive && !this.isPausedAdvance) {
                this.isPausedAdvance = true;
                this.remainingAdvanceMs = Math.max(0, this.advanceTargetEndTime - Date.now());
            }
        },
        resumeAdvance() {
            if (this.advanceTimerActive && this.isPausedAdvance) {
                this.isPausedAdvance = false;
                // Khôi phục thời gian kết thúc dựa trên số mili giây còn lại (tối thiểu 1s để kịp phản ứng)
                this.advanceTargetEndTime = Date.now() + Math.max(this.remainingAdvanceMs, 1000);
            }
        },
        clearAdvanceTimers() {
            if (this.advanceInterval) {
                clearInterval(this.advanceInterval);
                this.advanceInterval = null;
            }
            this.advanceTimerActive = false;
            this.isPausedAdvance = false;
            this.advanceProgress = 100;
        },
        handleNextStep() {
            this.clearAdvanceTimers();
            if (this.questionInRound >= this.quizLength) {
                this.screen = 'summary';
            } else {
                this.questionInRound++;
                this.nextQuestion(this.autoPlayAudio);
            }
        },
        getOptionClass(opt) {
            if (!this.answered) {
                return 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-slate-200 hover:border-[#e07a5f] hover:text-[#e07a5f] hover:bg-[#fff2ee]/50';
            }
            if (opt.id === this.targetTone.id) {
                return 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-500/20';
            }
            if (this.selectedOpt && this.selectedOpt.id === opt.id) {
                return 'bg-rose-500 text-white border-rose-500 shadow-md shadow-rose-500/20';
            }
            return 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 opacity-40';
        },
        getBadgeClass(opt) {
            if (!this.answered) {
                return 'bg-[#e8e2d9] text-slate-600 dark:bg-slate-700 dark:text-slate-300';
            }
            if (opt.id === this.targetTone.id) {
                return 'bg-emerald-500 text-white';
            }
            if (this.selectedOpt && this.selectedOpt.id === opt.id) {
                return 'bg-rose-400 text-white';
            }
            return 'bg-slate-200 dark:bg-slate-700 text-slate-400';
        },
        getPitchDescription(toneNumber) {
            const PITCH_MAP = {
                1: '5-5 (Cao bằng phẳng)',
                2: '3-5 (Lên giọng cao)',
                3: '2-1-4 (Xuống rồi lên)',
                4: '5-1 (Rơi mạnh dứt khoát)',
                0: 'Khinh thanh (Nhẹ ngắn)'
            };
            return PITCH_MAP[toneNumber] || 'Chuẩn Quốc tế';
        },
        getEvaluationTitle() {
            const pct = Math.round((this.correctCount / this.quizLength) * 100);
            if (pct === 100) return 'Xuất Sắc! Tuyệt Đối 100% 🏆';
            if (pct >= 80) return 'Rất Tốt! Tai Nghe Chuẩn Xác 🎉';
            if (pct >= 50) return 'Khá Tốt! Tiếp Tục Phát Huy ✨';
            return 'Cần Luyện Tập Thêm 💪';
        },
        handleGlobalKey(e) {
            if (this.screen === 'setup') {
                if (e.key === 'Enter') {
                    this.startQuiz();
                }
            } else if (this.screen === 'quiz') {
                if (e.key === ' ' || e.key === 'r' || e.key === 'R') {
                    e.preventDefault();
                    this.playAudio(1.0);
                } else if (!this.answered) {
                    if (['1', 'a', 'A'].includes(e.key) && this.currentOptions[0]) this.selectAnswer(this.currentOptions[0]);
                    if (['2', 'b', 'B'].includes(e.key) && this.currentOptions[1]) this.selectAnswer(this.currentOptions[1]);
                    if (['3', 'c', 'C'].includes(e.key) && this.currentOptions[2]) this.selectAnswer(this.currentOptions[2]);
                    if (['4', 'd', 'D'].includes(e.key) && this.currentOptions[3]) this.selectAnswer(this.currentOptions[3]);
                } else if (this.answered && e.key === 'Enter') {
                    this.handleNextStep();
                }
            }
        },
        playSynthSound(type) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                if (type === 'correct') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(523.25, ctx.currentTime); 
                    osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1); 
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.35);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(180, ctx.currentTime);
                    osc.frequency.setValueAtTime(130, ctx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.35);
                }
            } catch(e) {
                // Ignore audio context errors
            }
        }
    }
}
</script>
@endsection
