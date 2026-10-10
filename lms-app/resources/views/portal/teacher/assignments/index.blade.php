@extends('portal.layouts.dashboard')

@section('title', 'Quản lý Bài tập')

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
        <div class="max-w-[1400px] mx-auto space-y-8">
            <x-portal.page-header
                :title="__('Quản lý Bài tập')"
                :description="__('Giao bài tập và chấm điểm cho học viên.')">
                <x-slot:actions>
                    <a href="{{ route('teacher.assignments.create') }}" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-xl font-semibold flex items-center gap-1.5 shadow-sm transition-all text-xs sm:text-sm active:scale-[0.98]">
                        <span class="material-symbols-outlined text-base">add</span>
                        {{ __('Thêm bài tập mới') }}
                    </a>
                </x-slot:actions>
            </x-portal.page-header>

            <x-flash-message />

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/75 dark:bg-slate-800/50 text-slate-400 text-[11px] uppercase tracking-wider font-semibold">
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Tiêu đề') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Khóa / Bài học') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Hạn nộp') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 min-w-[200px]">{{ __('Tiến độ & Tình trạng') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-right">{{ __('Thao tác') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-xs sm:text-sm">
                            @forelse($assignments as $assignment)
                                <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm truncate max-w-[200px]" title="{{ $assignment->title }}">
                                            {{ $assignment->title }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1 font-normal">
                                            @if($assignment->status == \App\Models\Assignment::STATUS_PUBLISHED)
                                                <span class="size-1.5 bg-emerald-500 rounded-full"></span> {{ __('Published') }}
                                            @else
                                                <span class="size-1.5 bg-slate-300 rounded-full"></span> {{ __('Draft') }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-xs sm:text-sm text-slate-900 dark:text-white truncate max-w-[200px]">{{ $assignment->course->title ?? 'N/A' }}</p>
                                        <p class="text-[11px] text-slate-500 truncate max-w-[200px] mt-0.5 font-normal">{{ $assignment->lesson->title ?? __('Tất cả bài học') }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if($assignment->due_date)
                                            <div class="flex items-center gap-1.5 {{ $assignment->due_date < now() ? 'text-red-500' : 'text-slate-600 dark:text-slate-400' }}">
                                                <span class="material-symbols-outlined text-[15px]">calendar_clock</span>
                                                <span class="text-xs font-medium">{{ $assignment->due_date->format('d/m/Y H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-xs font-normal">{{ __('Không có hạn') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @php
                                            $totalStudents = $assignment->course->enrollments_count ?? 0;
                                            $submittedCount = $assignment->submissions->count();
                                            $gradedCount = $assignment->submissions->where('status', \App\Models\AssignmentSubmission::STATUS_GRADED)->count();
                                            $pendingCount = $submittedCount - $gradedCount;
                                            $submissionRate = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0;
                                        @endphp

                                        <div class="space-y-1.5 min-w-[180px]">

                                            <div class="space-y-1">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1 font-medium">
                                                        <span class="material-symbols-outlined text-[14px] text-slate-400">group</span>
                                                        {{ __('Đã nộp:') }}
                                                    </span>
                                                    <span class="font-semibold text-slate-800 dark:text-slate-200 text-xs">
                                                        {{ $submittedCount }}/{{ $totalStudents }} {{ __('học viên') }}
                                                        <span class="text-slate-400 font-normal">({{ $submissionRate }}%)</span>
                                                    </span>
                                                </div>

                                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-primary h-1.5 rounded-full transition-all duration-300" style="width: {{ min(100, $submissionRate) }}%"></div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 pt-0.5">
                                                @if($submittedCount === 0)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-semibold">
                                                        <span class="material-symbols-outlined text-[13px]">hourglass_empty</span>
                                                        {{ __('Chưa có bài nộp') }}
                                                    </span>
                                                @elseif($pendingCount > 0)
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/50 text-[10px] font-semibold">
                                                        <span class="relative flex h-1.5 w-1.5">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                                        </span>
                                                        {{ $pendingCount }} {{ __('bài chờ chấm') }}
                                                        <span class="text-amber-600/80 dark:text-amber-400/80 text-[10px] font-normal">({{ __('Đã chấm') }} {{ $gradedCount }}/{{ $submittedCount }})</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/50 text-[10px] font-semibold">
                                                        <span class="material-symbols-outlined text-[13px] text-emerald-600">check_circle</span>
                                                        {{ __('Đã chấm xong') }} ({{ $gradedCount }}/{{ $submittedCount }})
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-1 max-w-[130px]">
                                        <a href="{{ route('teacher.assignments.edit', $assignment->id) }}" class="inline-flex items-center justify-center size-7.5 text-slate-400 hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors rounded-lg" title="{{ __('Chỉnh sửa') }}">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="inline-flex items-center justify-center size-7.5 text-orange-500 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/40 transition-colors rounded-lg" title="{{ __('Chấm bài') }}">
                                            <span class="material-symbols-outlined text-[18px]">fact_check</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-500">
                                        <div class="size-16 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <span class="material-symbols-outlined text-3xl">inbox</span>
                                        </div>
                                        <p class="font-bold">Bạn chưa tạo bài tập nào</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assignments->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                    {{ $assignments->links('components.pagination') }}
                </div>
                @endif
            </div>

        </div>
    </main>
@endsection
