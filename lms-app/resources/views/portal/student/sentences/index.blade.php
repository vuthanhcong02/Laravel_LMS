@extends('layouts.lms')

@php
    $mode = $mode ?? 'scramble';
    $modeLabels = [
        'scramble' => [
            'name' => __('Ghép câu'),
            'icon' => 'fa-solid fa-puzzle-piece',
            'desc' => __('Rèn luyện tư duy ngữ pháp tự nhiên, phản xạ sắp xếp trật tự từ Hán ngữ kết hợp phát âm bản xứ.'),
            'badge' => __('Luyện ghép câu'),
        ],
        'cloze' => [
            'name' => __('Điền từ vào chỗ trống'),
            'icon' => 'fa-solid fa-pen-to-square',
            'desc' => __('Thử thách phán đoán từ vựng & ngữ pháp phù hợp nhất để hoàn thành câu hoàn chỉnh.'),
            'badge' => __('Luyện điền từ'),
        ],
        'dictation' => [
            'name' => __('Nghe & Chép chính tả'),
            'icon' => 'fa-solid fa-headphones',
            'desc' => __('Luyện tai nghe phát âm chuẩn bản xứ và rèn luyện kỹ năng gõ chữ Hán/Pinyin chính xác 100%.'),
            'badge' => __('Luyện nghe chép'),
        ],
    ];
    $currentModeInfo = $modeLabels[$mode] ?? $modeLabels['scramble'];
@endphp

@section('title', $currentModeInfo['name'] . ' - XiaoMu LMS')

@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Luyện tập câu'), 'url' => route('sentences.index', ['mode' => $mode])],
        ['label' => $currentModeInfo['name'], 'url' => null]
    ]" />
@endsection

@section('content')
<div class="space-y-5 max-w-7xl mx-auto w-full">
    <div class="lms-card p-4 sm:p-5 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
        <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-[#e07a5f]/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-[#e07a5f]/10 text-[#e07a5f] text-[10px] font-bold">
                    <i class="{{ $currentModeInfo['icon'] }}"></i>
                    <span>{{ $currentModeInfo['badge'] }} • {{ __('HSK 1 - HSK 9') }}</span>
                </div>
                <h1 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                    {{ $currentModeInfo['name'] }} {{ __('Tiếng Trung') }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                    {{ $currentModeInfo['desc'] }}
                </p>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 bg-white dark:bg-[#181615] p-3 sm:p-3.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5 flex-nowrap shrink-0">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 mr-1 hidden sm:inline">{{ __('Cấp độ:') }}</span>
            @foreach($levels as $lvl)
                <a href="{{ route('sentences.index', ['mode' => $mode, 'level' => $lvl, 'q' => $search]) }}"
                   class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all whitespace-nowrap btn-tactile {{ $selectedLevel === $lvl ? 'bg-[#e07a5f] text-white shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 hover:bg-[#e8e2d9] dark:hover:bg-[#2d2926]' }}">
                    {{ $lvl }}
                </a>
            @endforeach
        </div>

        @if($mode === 'scramble')
            <form method="GET" action="{{ route('sentences.index') }}" class="flex items-center gap-2 w-full lg:w-64">
                <input type="hidden" name="mode" value="{{ $mode }}">
                <input type="hidden" name="level" value="{{ $selectedLevel }}">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="{{ __('Tìm chủ đề') }}"
                           class="w-full pl-8 pr-3 py-1.5 text-xs bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-lg text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-1 focus:ring-[#e07a5f]/20 transition-all">
                </div>
                @if($search)
                    <a href="{{ route('sentences.index', ['mode' => $mode, 'level' => $selectedLevel]) }}"
                       class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 text-xs font-medium" title="{{ __('Xóa tìm kiếm') }}">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </a>
                @endif
            </form>
        @endif
    </div>

    @if($mode === 'scramble')
        @include('portal.student.sentences.partials.topic-list')
    @else
        @include('portal.student.sentences.partials.random-card')
    @endif
</div>
@endsection
