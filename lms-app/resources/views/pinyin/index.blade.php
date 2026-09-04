@extends('layouts.lms')

@section('title', __('Bảng Phiên âm Pinyin - Tiếng Trung XIAOMU'))

@section('alpine-data')
    currentPinyin: null,
    selectedTone: null,
    isFullscreen: false,
    showGuideModal: false,
    socialDockExpanded: true,
@endsection

@section('header-left')
    <x-lms.breadcrumb :links="[['label' => __('Bảng phát âm Pinyin'), 'url' => null]]" />
@endsection

@section('content')
    <div class="space-y-6"
        @keyup.window="if(($event.key === 'f' || $event.key === 'F') && !currentPinyin && !showGuideModal) { isFullscreen = !isFullscreen; } if($event.key === 'Escape') { isFullscreen = false; }">

        <!-- Banner Tiêu đề Trang (Design System Card) -->
        <div
            class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
            <!-- Faint Watermark Hanzi -->
            <div
                class="absolute right-4 -bottom-6 text-9xl font-extrabold text-[#e07a5f]/5 pointer-events-none select-none zh-text">
                拼
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="space-y-1.5 max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                        <i class="fa-solid fa-table-cells text-[#e07a5f]"></i> {{ __('Ngữ âm Hán ngữ chuẩn Quốc tế') }}
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                        {{ __('Bảng phiên âm Pinyin') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Tra cứu 21 Thanh mẫu (Thanh mẫu), 36 Vận mẫu (Vận mẫu) và 4 Thanh điệu. Nhấp vào bất kỳ âm tiết nào để nghe phát âm và xem ví dụ minh họa.') }}
                    </p>
                </div>

                <!-- Nút Action mở Modal Mẹo phát âm & Khẩu hình -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <button type="button" @click="showGuideModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs shadow-xs transition-all btn-tactile cursor-pointer select-none">
                        <i class="fa-solid fa-lightbulb text-amber-300 pointer-events-none"></i>
                        <span>{{ __('Mẹo phát âm & Khẩu hình') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Pinyin Table Container -->
        <div class="lms-card p-4 sm:p-5 space-y-3 hover:transform-none hover:translate-y-0"
            :class="isFullscreen ? 'min-h-[500px]' : ''">
            <!-- Toolbar Trên Bảng -->
            <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                    <span>{{ __('Bảng phối âm Thanh mẫu × Vận mẫu') }}</span>
                    <span class="hidden md:inline text-slate-400">• {{ __('Kéo rê chuột để cuộn bảng') }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        class="hidden sm:inline-flex text-[11px] text-slate-400 bg-[#f8f6f3] dark:bg-[#201d1b] px-2 py-0.5 rounded border border-[#e8e2d9] dark:border-[#2d2926]">
                        {{ __('Phím F: Toàn màn hình') }}
                    </span>
                </div>
            </div>

            <!-- Component Bảng Grid Pinyin -->
            @include('pinyin.components.grid')
        </div>

        <!-- Component Modal Hướng dẫn Khẩu hình -->
        @include('pinyin.components.guide_modal')

        <!-- Audio Element ẩn phục vụ phát âm thanh file nội bộ nếu có -->
        <audio x-ref="audioPlayer" class="hidden"></audio>
    </div>
@endsection
