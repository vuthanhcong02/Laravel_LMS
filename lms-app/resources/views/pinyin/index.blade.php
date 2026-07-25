@extends('layouts.app')

@section('title', 'Bảng Pinyin Tiếng Trung')

@section('breadcrumb', 'Bảng Phát Âm Pinyin')
@section('breadcrumb_desc', 'Khám phá hệ thống phiên âm tiếng Trung chuẩn xác. Bấm vào từng âm tiết để nghe cách đọc và xem ví dụ.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 font-sans relative" x-data="{ currentPinyin: null, audio: null, isFullscreen: false }" @keydown.window="if($event.key === 'f' || $event.key === 'F') isFullscreen = !isFullscreen; if($event.key === 'Escape') isFullscreen = false">
    
    <!-- Filters Section -->
    <div class="relative pt-8 pb-6 z-10">
        <div class="container mx-auto px-4 flex flex-col items-center">
            <!-- Filters -->
            <div class="flex flex-wrap justify-center gap-2">
                <button class="px-6 py-2 rounded-xl bg-slate-900 dark:bg-slate-800 border border-slate-700/50 text-indigo-400 font-medium transition-all shadow-lg shadow-indigo-500/5">Tất cả</button>
                <button class="px-6 py-2 rounded-xl bg-transparent text-slate-500 dark:text-slate-400 font-medium transition-colors hover:text-indigo-400 hover:bg-slate-800/50">Đã lưu</button>
                <button class="px-6 py-2 rounded-xl bg-transparent text-slate-500 dark:text-slate-400 font-medium transition-colors hover:text-indigo-400 hover:bg-slate-800/50">Lịch sử</button>
            </div>
            
            <!-- User Hint -->
            <div class="mt-5 flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm font-medium animate-pulse">
                <span class="material-symbols-outlined text-[16px]">swipe</span>
                <span>Mẹo: Nhấn giữ và kéo chuột để cuộn bảng dễ dàng hơn</span>
            </div>
        </div>
    </div>

    <!-- Pinyin Grid Section -->
    <div class="container mx-auto px-4 pb-24">
        @include('pinyin.components.grid')
    </div>
    
    <!-- Audio Element -->
    <audio x-ref="audioPlayer" class="hidden"></audio>
</div>
@endsection
