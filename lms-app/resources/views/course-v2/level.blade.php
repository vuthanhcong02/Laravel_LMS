@extends('layouts.lms')

@section('title', $currentLevel->title . ' - ' . __('Tiếng Trung XIAOMU LMS'))

@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Khóa học HSK'), 'url' => route('courses')],
        ['label' => $currentLevel->title, 'url' => null]
    ]" />
@endsection

@section('content')
    <div class="space-y-6">
        
        <!-- Nút Quay lại -->
        <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#e07a5f] hover:text-[#c86349] transition-colors btn-tactile">
            <i class="fa-solid fa-arrow-left text-xs"></i> {{ __('Quay lại danh sách khóa học') }}
        </a>

        <!-- Banner Tiêu đề Level -->
        @php
            $badgeColorClass = '';
            if ($currentLevel->color === 'emerald') $badgeColorClass = 'bg-[#f59e0b] text-slate-950';
            else if ($currentLevel->color === 'cyan') $badgeColorClass = 'bg-[#0f766e] text-white';
            else if ($currentLevel->color === 'blue') $badgeColorClass = 'bg-[#0284c7] text-white';
            else if ($currentLevel->color === 'purple') $badgeColorClass = 'bg-[#6d28d9] text-white';
            else if ($currentLevel->color === 'rose') $badgeColorClass = 'bg-[#be185d] text-white';
            else $badgeColorClass = 'bg-[#1d4ed8] text-white';
        @endphp
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <!-- Badge HSK1 chuẩn text-xs font-bold -->
                <div class="w-10 h-10 rounded-xl {{ $badgeColorClass }} font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                    {{ strtoupper($currentLevel->level_code) }}
                </div>
                <div class="space-y-1">
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $currentLevel->title }} - {{ $currentLevel->subtitle }}
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                        {{ __('Lộ trình bài học chính khóa bám sát cấu trúc khung chuẩn') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">
                    {{ $currentLevel->lessons_count }} {{ __('Bài học') }}
                </span>
                <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">
                    {{ $currentLevel->vocab_count }} {{ __('Từ vựng') }}
                </span>
            </div>
        </div>

        <!-- Danh sách các Thẻ Bài học -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($currentLevel->lessons as $lesson)
                @php
                    $isDummyData = ($lesson->title === 'Bài ' . $lesson->lesson_number);
                    $displayTitle = preg_replace('/^Bài\s+\d+[:\-]?\s*/i', '', $lesson->title);
                    $displayTitle = empty(trim($displayTitle)) ? $lesson->title : $displayTitle;

                    if ($isDummyData) {
                        $displayTitle = __('Đang cập nhật nội dung...');
                    }
                @endphp
                <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $lesson->slug]) }}"
                    class="lms-card p-4 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center gap-3 group hover:border-[#e07a5f] transition-all cursor-pointer">
                    
                    <!-- Số thứ tự: w-10 h-10 rounded-full -->
                    <div class="w-10 h-10 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 dark:text-slate-500 font-bold text-sm flex items-center justify-center font-mono shrink-0 group-hover:bg-[#e07a5f] group-hover:text-white group-hover:border-[#e07a5f] transition-colors shadow-sm">
                        {{ str_pad($lesson->lesson_number, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <!-- Nội dung bài học -->
                    <div class="min-w-0 flex-1 flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-[#e07a5f] uppercase tracking-wider mb-0.5">{{ __('Mã bài') }}: {{ $lesson->code }}</span>
                        <h3 class="text-base font-bold zh-text text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors leading-tight">
                            {{ $displayTitle }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate font-normal">
                            {{ $isDummyData ? __('Vui lòng quay lại sau') : $lesson->translation }}
                        </p>
                    </div>

                </a>
            @empty
                <div class="col-span-full lms-card p-8 text-center text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-person-digging text-4xl mb-3 text-slate-300 dark:text-slate-600"></i>
                    <p class="text-sm font-medium">{{ __('Danh sách bài học đang được cập nhật.') }}</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
