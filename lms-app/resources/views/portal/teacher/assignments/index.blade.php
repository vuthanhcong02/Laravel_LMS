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
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">assignment</span>
                        Quản lý Bài tập
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">Giao bài tập và chấm điểm cho học viên.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('teacher.assignments.create') }}" class="px-5 py-2.5 bg-primary hover:bg-blue-600 text-white rounded-xl font-bold flex items-center gap-2 shadow-sm transition-all text-sm">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Thêm bài tập mới
                    </a>
                </div>
            </div>

            <x-flash-message />

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-widest font-black">
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">Tiêu đề</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">Khóa / Bài học</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">Hạn nộp</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">Tình trạng</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-sm">
                            @forelse($assignments as $assignment)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                    <td class="p-6">
                                        <p class="font-bold text-slate-900 dark:text-white text-base truncate max-w-[200px]" title="{{ $assignment->title }}">
                                            {{ $assignment->title }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                            @if($assignment->status == \App\Models\Assignment::STATUS_PUBLISHED)
                                                <span class="size-2 bg-emerald-500 rounded-full"></span> Published
                                            @else
                                                <span class="size-2 bg-slate-300 rounded-full"></span> Draft
                                            @endif
                                        </p>
                                    </td>
                                    <td class="p-6">
                                        <p class="font-bold truncate max-w-[200px]">{{ $assignment->course->title ?? 'N/A' }}</p>
                                        <p class="text-xs text-slate-500 truncate max-w-[200px]">{{ $assignment->lesson->title ?? 'Tất cả bài học' }}</p>
                                    </td>
                                    <td class="p-6">
                                        @if($assignment->due_date)
                                            <div class="flex items-center gap-1.5 {{ $assignment->due_date < now() ? 'text-red-500' : 'text-slate-600 dark:text-slate-400' }}">
                                                <span class="material-symbols-outlined text-[16px]">calendar_clock</span>
                                                <span>{{ $assignment->due_date->format('d/m/Y H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400">Không có hạn</span>
                                        @endif
                                    </td>
                                    <td class="p-6">
                                        @php
                                            $total = $assignment->submissions->count();
                                            $graded = $assignment->submissions->where('status', \App\Models\AssignmentSubmission::STATUS_GRADED)->count();
                                            $pending = $total - $graded;
                                        @endphp
                                        
                                        @if($total === 0)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 text-xs font-bold w-fit">
                                                <span class="material-symbols-outlined text-[16px]">hourglass_empty</span>
                                                Chưa có bài
                                            </span>
                                        @elseif($pending > 0)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 text-xs font-bold w-fit">
                                                <span class="relative flex h-2 w-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                                </span>
                                                {{ $pending }} bài chờ chấm
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 text-xs font-bold w-fit">
                                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                                Đã chấm xong
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-6 text-right space-x-2 max-w-[150px]">
                                        <a href="{{ route('teacher.assignments.edit', $assignment->id) }}" class="inline-flex items-center justify-center size-8 bg-slate-100 hover:bg-primary hover:text-white dark:bg-slate-800 text-slate-600 transition-colors rounded-lg">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="inline-flex items-center justify-center size-8 bg-orange-50 hover:bg-orange-500 hover:text-white dark:bg-orange-900/30 text-orange-600 transition-colors rounded-lg">
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
                <!-- Pagination -->
                @if($assignments->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                    {{ $assignments->links('components.pagination') }}
                </div>
                @endif
            </div>

        </div>
    </main>
@endsection
