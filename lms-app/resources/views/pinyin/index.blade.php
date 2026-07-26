@extends('layouts.app')

@section('title', 'Bảng Pinyin Tiếng Trung')

@section('breadcrumb', 'Bảng Phát Âm Pinyin')
@section('breadcrumb_desc',
    'Khám phá hệ thống phiên âm tiếng Trung chuẩn xác. Bấm vào từng âm tiết để nghe cách đọc và
    xem ví dụ.')

@section('content')
    <div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 font-sans relative"
        x-data="{ currentPinyin: null, audio: null, isFullscreen: false }"
        @keydown.window="if($event.key === 'f' || $event.key === 'F') isFullscreen = !isFullscreen; if($event.key === 'Escape') isFullscreen = false">

        <!-- Pinyin Grid Section -->
        <div class="max-w-7xl mx-auto px-6 py-8 pb-24">
            @include('pinyin.components.grid')
        </div>

        <!-- Audio Element -->
        <audio x-ref="audioPlayer" class="hidden"></audio>
    </div>
@endsection
