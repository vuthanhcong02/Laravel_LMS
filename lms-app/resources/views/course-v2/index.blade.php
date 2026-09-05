@extends('layouts.lms')
@section('title', __('Khóa học HSK - Tiếng Trung XIAOMU LMS'))
@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Khóa học HSK'), 'url' => null]
    ]" />
@endsection
@section('content')
    <div x-data="{ levelFilter: 'all' }" class="space-y-6">
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-graduation-cap text-[#e07a5f]"></i> {{ __('Lộ trình chuẩn hóa HSK 1 - HSK 6') }}
                </div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                    {{ __('Khóa học Tiếng Trung HSK') }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal leading-relaxed">
                    {{ __('Tổng hợp bài giảng video, từ vựng chữ Hán, mẫu câu giao tiếp và ngữ pháp chuyên sâu. Click vào từng khóa để xem danh sách bài học.') }}
                </p>
            </div>
        </div>
        <!-- Filter Bar Buttons (text-xs font-semibold) -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
            <button @click="levelFilter = 'all'" :class="levelFilter === 'all' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                {{ __('Tất cả khóa học') }}
            </button>
            <button @click="levelFilter = 'hsk12'" :class="levelFilter === 'hsk12' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                {{ __('HSK 1 - HSK 2 (Sơ cấp)') }}
            </button>
            <button @click="levelFilter = 'hsk34'" :class="levelFilter === 'hsk34' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                {{ __('HSK 3 - HSK 4 (Trung cấp)') }}
            </button>
            <button @click="levelFilter = 'hsk56'" :class="levelFilter === 'hsk56' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                {{ __('HSK 5 - HSK 6 (Cao cấp)') }}
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($levels as $level)
                @php
                    $code = strtolower($level->level_code);
                    $group = 'all';
                    if (in_array($code, ['hsk1', 'hsk2', 'hsk-1', 'hsk-2', '1', '2'])) {
                        $group = 'hsk12';
                    } elseif (in_array($code, ['hsk3', 'hsk4', 'hsk-3', 'hsk-4', '3', '4'])) {
                        $group = 'hsk34';
                    } elseif (in_array($code, ['hsk5', 'hsk6', 'hsk-5', 'hsk-6', '5', '6'])) {
                        $group = 'hsk56';
                    }
                    $badgeStyle = 'bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30';
                    $levelSubBadge = __('Sơ cấp');
                    if (in_array($code, ['hsk1', 'hsk-1', '1'])) {
                        $badgeStyle = 'bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30';
                        $levelSubBadge = __('Sơ cấp I');
                    } elseif (in_array($code, ['hsk2', 'hsk-2', '2'])) {
                        $badgeStyle = 'bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30';
                        $levelSubBadge = __('Sơ cấp II');
                    } elseif (in_array($code, ['hsk3', 'hsk-3', '3'])) {
                        $badgeStyle = 'bg-[#0284c7]/15 text-[#0284c7] border border-[#0284c7]/30';
                        $levelSubBadge = __('Trung cấp I');
                    } elseif (in_array($code, ['hsk4', 'hsk-4', '4'])) {
                        $badgeStyle = 'bg-[#0284c7]/15 text-[#0284c7] border border-[#0284c7]/30';
                        $levelSubBadge = __('Trung cấp II');
                    } elseif (in_array($code, ['hsk5', 'hsk-5', '5'])) {
                        $badgeStyle = 'bg-[#6d28d9]/15 text-[#6d28d9] border border-[#6d28d9]/30';
                        $levelSubBadge = __('Cao cấp I');
                    } elseif (in_array($code, ['hsk6', 'hsk-6', '6'])) {
                        $badgeStyle = 'bg-[#be185d]/15 text-[#be185d] border border-[#be185d]/30';
                        $levelSubBadge = __('Cao cấp II');
                    }
                @endphp
                <div x-show="levelFilter === 'all' || levelFilter === '{{ $group }}'" 
                     class="lms-card p-5 flex flex-col justify-between space-y-4 group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeStyle }}">
                                {{ strtoupper($level->level_code) }} • {{ $levelSubBadge }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">
                            {{ $level->title }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed">
                            {{ $level->subtitle ?? __('Lộ trình bài học chính khóa bám sát cấu trúc khung đề thi HSK.') }}
                        </p>
                    </div>
                    <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                            <span><i class="fa-regular fa-clock mr-1"></i>{{ $level->lessons->count() }} {{ __('bài') }}</span>
                            <span><i class="fa-solid fa-language mr-1"></i>{{ $level->vocab_count ?? 150 }} {{ __('từ') }}</span>
                        </div>
                        <a href="{{ route('courses.level', ['levelSlug' => $level->slug]) }}" 
                           class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5 shadow-xs">
                            <span>{{ __('Vào học') }}</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
