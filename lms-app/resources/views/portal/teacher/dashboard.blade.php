@extends('portal.layouts.dashboard')

@section('title', __('Bảng điều khiển Giảng viên') . ' - XiaoMu LMS')

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
    <div class="max-w-[1400px] mx-auto space-y-6">

        <x-flash-message type="success" />
        <x-flash-message type="error" />

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Chào mừng quay trở lại, ') }} {{ auth()->user()->first_name ?? __('Giảng viên') }}!
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-normal">
                    {{ __('Chúc bạn một ngày giảng dạy tràn đầy năng lượng và hiệu quả cùng học viên.') }}
                </p>
            </div>

            <div class="self-start sm:self-auto flex items-center gap-2.5 bg-slate-50 dark:bg-slate-800/80 px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined text-lg text-primary">calendar_month</span>
                <div class="text-right">
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Hôm nay') }}</p>
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::now()->translatedFormat('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            <a href="{{ route('teacher.reports.index') }}"
               class="group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-200 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <div class="size-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl">group</span>
                    </div>
                    <span class="text-slate-400 group-hover:text-primary transition-colors material-symbols-outlined text-base">arrow_forward</span>
                </div>
                <div>
                    <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">{{ __('Tổng học viên') }}</h3>
                    <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_students'] ?? 0) }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ __('Học viên đã tham gia lớp học') }}</p>
                </div>
            </a>

            <a href="{{ route('teacher.classes.index') }}"
               class="group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-200 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <div class="size-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl">school</span>
                    </div>
                    <span class="text-slate-400 group-hover:text-primary transition-colors material-symbols-outlined text-base">arrow_forward</span>
                </div>
                <div>
                    <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">{{ __('Lớp đang phụ trách') }}</h3>
                    <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_courses'] ?? 0) }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ __('Khóa học tiếng Trung của bạn') }}</p>
                </div>
            </a>

            <a href="{{ route('teacher.assignments.index') }}"
               class="group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-200 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <div class="size-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl">assignment</span>
                    </div>
                    @if (($stats['pending_assignments_count'] ?? 0) > 0)
                        <span class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-bold animate-pulse">
                            {{ $stats['pending_assignments_count'] }} {{ __('chờ chấm') }}
                        </span>
                    @else
                        <span class="text-slate-400 group-hover:text-primary transition-colors material-symbols-outlined text-base">arrow_forward</span>
                    @endif
                </div>
                <div>
                    <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">{{ __('Bài tập chờ chấm') }}</h3>
                    <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['pending_assignments_count'] ?? 0) }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ __('Bài nộp của học viên cần nhận xét') }}</p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">event_upcoming</span>
                        <span>{{ __('Lịch dạy hôm nay') }}</span>
                    </h2>
                    <a href="{{ route('teacher.schedules.index') }}"
                       class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                        <span>{{ __('Xem lịch đầy đủ') }}</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 shadow-sm">
                    @forelse ($schedules as $schedule)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 mb-3 last:mb-0">

                            <div class="text-center shrink-0 px-3 py-1.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white block">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </span>
                            </div>

                            <div class="flex-1 min-w-0 flex items-center">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                    {{ __('Lớp: ') }} {{ $schedule->course->title }}
                                </h3>
                            </div>

                            <div class="shrink-0 flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-sm text-slate-400">group</span>
                                <span>{{ $schedule->students_count }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center space-y-2">
                            <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">free_cancellation</span>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Hôm nay không có lịch dạy') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto font-medium">
                                {{ __('Bạn không có ca dạy nào trong ngày. Hãy dành thời gian nghiên cứu giáo án và chuẩn bị bài giảng nhé!') }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">notifications_active</span>
                        <span>{{ __('Thông báo mới') }}</span>
                    </h2>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($notifications as $notification)
                        @php
                            $link = isset($notification->data['link']) ? url($notification->data['link']) : '#';
                            $title = $notification->data['title'] ?? __('Thông báo');
                            $message = $notification->data['message'] ?? '';
                            $isUnread = is_null($notification->read_at);
                        @endphp
                        <a href="{{ $link }}" class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex items-start gap-3 group">
                            <div class="size-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-base">mail</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-primary transition-colors">
                                        {{ $title }}
                                    </p>
                                    @if ($isUnread)
                                        <span class="size-2 rounded-full bg-primary shrink-0"></span>
                                    @endif
                                </div>
                                @if ($message)
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                        {{ $message }}
                                    </p>
                                @endif
                                <span class="text-[10px] text-slate-400 mt-1 block">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="py-10 text-center text-slate-400 space-y-1">
                            <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">notifications_off</span>
                            <p class="text-xs font-medium">{{ __('Bạn không có thông báo mới nào.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</main>
@endsection
