@extends('layouts.app')

@section('title', 'Bảng Pinyin Tiếng Trung')

@section('breadcrumb', 'Bảng Phát Âm Pinyin')
@section('breadcrumb_desc',
    'Khám phá hệ thống phiên âm tiếng Trung chuẩn xác. Bấm vào từng âm tiết để nghe cách đọc và
    xem ví dụ.')

@section('content')
    <div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 font-sans relative"
        x-data="{ currentPinyin: null, selectedTone: null, audio: null, isFullscreen: false, showGuideModal: false }"
        @keydown.window="if($event.key === 'f' || $event.key === 'F') isFullscreen = !isFullscreen; if($event.key === 'Escape') isFullscreen = false">

        <!-- Pinyin Grid Section -->
        <div class="max-w-7xl mx-auto px-6 py-8 pb-24">
            <!-- Header Toolbar -->
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Bảng Phiên Âm Pinyin Chuẩn</span>
                <button @click="showGuideModal = true" 
                        class="px-4 py-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs flex items-center gap-2 shadow-md shadow-indigo-500/20 transition-all active:scale-95 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">record_voice_over</span>
                    <span>💡 Mẹo phát âm & Khẩu hình</span>
                </button>
            </div>

            @include('pinyin.components.grid')
        </div>

        <!-- Pronunciation & Mouth Shape Modal -->
        @include('pinyin.components.guide_modal')

        <!-- Audio Element -->
        <audio x-ref="audioPlayer" class="hidden"></audio>
    </div>
@endsection
