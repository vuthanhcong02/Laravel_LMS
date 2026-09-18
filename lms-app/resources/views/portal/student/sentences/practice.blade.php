@extends('layouts.lms')

@php
    $mode = $mode ?? 'scramble';
    $modeTitles = [
        'scramble' => __('Ghép câu'),
        'cloze' => __('Điền từ vào chỗ trống'),
        'dictation' => __('Nghe & Chép chính tả'),
    ];
    $modeTitle = $modeTitles[$mode] ?? __('Luyện tập');
    $pageHeading = ($isRandom ?? false) ? $modeTitle : ($topic['titleVi'] ?? $topic['title'] ?? $modeTitle);
@endphp

@section('title', $pageHeading . ' - XiaoMu LMS')

@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Luyện tập câu'), 'url' => ($isRandom ?? false) ? route('sentences.random', ['mode' => $mode, 'level' => $level]) : route('sentences.index', ['mode' => $mode, 'level' => $level])],
        ['label' => $pageHeading, 'url' => null]
    ]" />
@endsection

@section('content')
<div class="max-w-7xl mx-auto w-full space-y-4 pb-10"
     x-data="sentenceBuilder({ topic: {{ json_encode($topic) }}, level: '{{ $level }}', slug: '{{ $slug }}', mode: '{{ $mode }}', isRandom: {{ ($isRandom ?? false) ? 'true' : 'false' }}, showPinyin: false, completeUrl: '{{ route('sentences.complete') }}', moreUrl: '{{ route('sentences.random.more') }}' })">

    <div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-3.5 sm:p-4 shadow-xs space-y-3">

        <div class="flex items-center justify-between gap-3 pb-2.5 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">

            <div class="flex items-center gap-2.5 min-w-0">
                <a href="{{ ($isRandom ?? false) ? route('sentences.index', ['mode' => 'scramble', 'level' => $level]) : route('sentences.index', ['mode' => $mode, 'level' => $level]) }}"
                   class="size-8 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300 hover:text-[#e07a5f] flex items-center justify-center text-xs transition-all btn-tactile shrink-0"
                   title="{{ __('Về danh sách') }}">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                </a>
                <div class="min-w-0 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2d201a] text-[#e07a5f] text-[11px] font-bold border border-[#fcdccf] dark:border-[#3d271e] shrink-0">
                        {{ $level }}
                    </span>
                    <h1 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                        {{ $pageHeading }}
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200/60 dark:border-amber-800 text-[11px] font-bold text-amber-600 dark:text-amber-400 shadow-xs">
                    <i class="fa-solid fa-star text-[10px]"></i>
                    <span x-text="stats.score"></span> {{ __('Điểm') }}
                </span>
            </div>
        </div>

        @if($isRandom ?? false)

            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mr-1 hidden sm:inline">{{ __('Cấp độ:') }}</span>
                @foreach(($levels ?? ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'HSK7', 'HSK8', 'HSK9']) as $lvl)
                    <a href="{{ route('sentences.random', ['mode' => $mode, 'level' => $lvl]) }}"
                       class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all whitespace-nowrap btn-tactile {{ $level === $lvl ? 'bg-[#e07a5f] text-white shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 hover:bg-[#e8e2d9] dark:hover:bg-[#2d2926]' }}">
                        {{ $lvl }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="flex items-center gap-3 sm:gap-4 pt-0.5">

            <div class="flex-1 bg-[#f8f6f3] dark:bg-[#23201e] h-2 sm:h-2.5 rounded-full overflow-hidden relative border border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <div class="bg-[#e07a5f] h-full rounded-full transition-all duration-300 ease-out"
                     :style="'width: ' + (((currentIndex + 1) / (sentences.length || 1)) * 100) + '%'"></div>
            </div>

            <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 shrink-0 font-mono">
                <span class="text-slate-900 dark:text-white font-bold" x-text="currentIndex + 1"></span>/<span x-text="sentences.length"></span>
            </div>

            @if(hsk_should_show_pinyin($level))
                <button @click="showPinyin = !showPinyin"
                        :class="showPinyin ? 'text-[#e07a5f] font-bold' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-semibold'"
                        class="flex items-center gap-1.5 text-xs transition-colors shrink-0 cursor-pointer"
                        title="{{ __('Bật/Tắt Pinyin') }}">
                    <i x-show="showPinyin" class="fa-regular fa-eye text-xs"></i>
                    <i x-show="!showPinyin" class="fa-regular fa-eye-slash text-xs"></i>
                    <span>{{ __('Pinyin') }}</span>
                </button>
            @endif
        </div>
    </div>

    @include('portal.student.sentences.partials.scramble')

    @include('portal.student.sentences.partials.cloze')

    @include('portal.student.sentences.partials.dictation')

    @include('portal.student.sentences.partials.completed-modal')
</div>
@endsection
