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
<div x-data="sentenceTopicSearch({
    initialTopics: {{ \Illuminate\Support\Js::from($topics) }},
    level: '{{ $selectedLevel }}',
    mode: '{{ $mode }}',
    search: '{{ $search }}',
    searchUrl: '{{ route('sentences.index') }}',
    practiceBaseUrl: '{{ url('/luyen-ghep-cau') }}',
    isAuthenticated: {{ auth()->check() ? 'true' : 'false' }}
})" class="space-y-5 max-w-7xl mx-auto w-full">
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
                <button type="button"
                        @click="selectLevel('{{ $lvl }}')"
                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all whitespace-nowrap btn-tactile cursor-pointer"
                        :class="selectedLevel === '{{ $lvl }}' ? 'bg-[#e07a5f] text-white shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 hover:bg-[#e8e2d9] dark:hover:bg-[#2d2926]'">
                    {{ $lvl }}
                </button>
            @endforeach
        </div>

        @if($mode === 'scramble')
            <div class="flex items-center gap-2 w-full lg:w-72">
                <div class="relative w-full">
                    <button type="button"
                            @click="submitSearch()"
                            class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#e07a5f] text-xs p-1 transition-colors cursor-pointer"
                            title="{{ __('Tìm kiếm (Enter)') }}">
                        <i x-show="!isLoading" class="fa-solid fa-magnifying-glass"></i>
                        <i x-show="isLoading" class="fa-solid fa-spinner fa-spin text-[#e07a5f]" style="display: none;"></i>
                    </button>
                    <input type="text"
                           x-model="searchQuery"
                           @keydown.enter.prevent="submitSearch()"
                           placeholder="{{ __('Tìm chủ đề (nhấn Enter)...') }}"
                           class="w-full pl-8 pr-8 py-1.5 text-xs bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-lg text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-1 focus:ring-[#e07a5f]/20 transition-all">
                    <button type="button"
                            x-show="searchQuery.length > 0"
                            @click="clearSearch()"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs cursor-pointer p-0.5"
                            title="{{ __('Xóa tìm kiếm') }}"
                            style="display: none;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>

    @if($mode === 'scramble')
        @include('portal.student.sentences.partials.topic-list')
    @else
        @include('portal.student.sentences.partials.random-card')
    @endif
</div>
@endsection
