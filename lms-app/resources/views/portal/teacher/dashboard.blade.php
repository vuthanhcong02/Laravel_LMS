@extends('portal.layouts.dashboard')

@section('title', __('Teacher Dashboard - XiaoMu'))

@section('header')
@include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
@include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
    <div class="max-w-[1400px] mx-auto space-y-8">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-primary to-blue-500 rounded-3xl py-6 px-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold mb-1.5 tracking-tight">{{ __('Chào mừng quay trở lại!') }}</h1>
                    <p class="text-white/90 text-sm font-medium">{{ __('Chúc bạn một ngày giảng dạy tràn đầy năng lượng và hiệu quả.') }}</p>
                </div>
                <div class="hidden lg:flex items-center gap-3 bg-white/20 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/30">
                    <span class="material-symbols-outlined text-2xl">calendar_month</span>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-white/80 uppercase tracking-wider">{{ __('Hôm nay') }}</p>
                        <p class="text-lg font-extrabold">{{ \Carbon\Carbon::now()->translatedFormat('d \T\h\á\n\g m, Y') }}</p>
                    </div>
                </div>
            </div>
            <!-- Decor -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/20 rounded-full blur-3xl mix-blend-overlay"></div>
            <div class="absolute right-40 -bottom-20 w-60 h-60 bg-blue-300/30 rounded-full blur-3xl mix-blend-overlay"></div>
        </div>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stat 1: Tổng học viên -->
            <a href="{{ route('teacher.reports.index') }}" class="block group bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 group-hover:-rotate-12 duration-300">
                    <span class="material-symbols-outlined text-8xl text-blue-500">group</span>
                </div>
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="size-12 rounded-2xl bg-blue-50 dark:bg-blue-900/40 text-blue-500 flex items-center justify-center border border-blue-100 dark:border-blue-800/50">
                        <span class="material-symbols-outlined shrink-0 text-2xl">group</span>
                    </div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">{{ __('Tổng học viên') }}</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($stats['total_students'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">{{ __('Học viên đã tham gia lớp học') }}</p>
                </div>
            </a>

            <!-- Stat 2: Lớp đang dạy -->
            <a href="{{ route('teacher.classes.index') }}" class="block group bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 group-hover:-rotate-12 duration-300">
                    <span class="material-symbols-outlined text-8xl text-purple-500">class</span>
                </div>
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="size-12 rounded-2xl bg-purple-50 dark:bg-purple-900/40 text-purple-500 flex items-center justify-center border border-purple-100 dark:border-purple-800/50">
                        <span class="material-symbols-outlined shrink-0 text-2xl">class</span>
                    </div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">{{ __('Lớp đang dạy') }}</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($stats['total_courses'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">{{ __('Các khóa học tiếng Trung của bạn') }}</p>
                </div>
            </a>

            <!-- Stat 3: Bài tập chờ chấm -->
            <a href="{{ route('teacher.assignments.index') }}" class="block group bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-orange-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 group-hover:-rotate-12 duration-300">
                    <span class="material-symbols-outlined text-8xl text-orange-500">history_edu</span>
                </div>
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="size-12 rounded-2xl bg-orange-50 dark:bg-orange-900/40 text-orange-500 flex items-center justify-center border border-orange-100 dark:border-orange-800/50">
                        <span class="material-symbols-outlined shrink-0 text-2xl">history_edu</span>
                    </div>
                    @if (($stats['pending_assignments_count'] ?? 0) > 0)
                    <span class="flex size-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                    @endif
                </div>
                <div class="relative z-10">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">{{ __('Bài tập chờ chấm') }}</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($stats['pending_assignments_count'] ?? 0) }}</p>
                    <p class="text-xs text-orange-600 mt-2 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        {{ __('Cần chấm điểm cho học viên') }}
                    </p>
                </div>
            </a>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Timeline Section -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl">event_upcoming</span>
                        {{ __('Lịch dạy hôm nay') }}
                    </h2>
                    <a href="{{ route('teacher.schedules.index') }}" class="text-primary text-sm font-bold hover:underline underline-offset-4 flex items-center gap-1 group">
                        {{ __('Xem lịch đầy đủ') }} <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm relative h-full">
                    @forelse ($schedules as $index => $schedule)
                    @if ($index === 0)
                    <!-- Left timeline line (only visible if we have data) -->
                    <div class="absolute left-[122px] top-8 bottom-8 w-1 bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                    @endif
                    @empty
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center text-center py-16 px-4">
                        <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-700 mb-4 animate-bounce">coffee</span>
                        <h3 class="font-extrabold text-xl text-slate-800 dark:text-white mb-2">{{ __('Hôm nay không có lịch dạy') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm">{{ __('Bạn không có ca dạy học nào trong hôm nay. Hãy tận hưởng ngày nghỉ hoặc chuẩn bị kỹ lưỡng giáo án nhé!') }}</p>
                    </div>
                    @endforelse

                    @if ($schedules->isNotEmpty())
                    <div class="flex flex-col gap-8 relative z-10">
                        @foreach ($schedules as $schedule)
                        <div class="flex gap-6 relative group">
                            <div class="w-20 text-right pt-2 shrink-0">
                                <p class="font-extrabold text-slate-800 dark:text-white text-lg leading-tight">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                </p>
                                <p class="text-xs text-slate-400 font-bold tracking-wider">
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </p>
                            </div>
                            <div class="absolute left-[85px] top-4 size-3.5 rounded-full bg-primary border-[3px] border-white dark:border-slate-900 z-10 shadow-[0_0_0_5px_rgba(143,192,224,0.15)] group-hover:scale-150 transition-all duration-300"></div>

                            <div class="flex-1 bg-gradient-to-br from-primary/10 to-transparent rounded-2xl p-6 border border-primary/20 hover:border-primary/50 transition-colors shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="font-extrabold text-xl text-slate-800 dark:text-white group-hover:text-primary transition-colors">
                                        {{ $schedule->course->title }}
                                    </h3>
                                    <span class="px-3 py-1.5 bg-white/80 dark:bg-slate-800 backdrop-blur-sm rounded-xl text-xs font-bold text-primary flex items-center gap-1.5 shadow-sm border border-primary/10">
                                        <span class="material-symbols-outlined text-[16px]">videocam</span> {{ __('Online') }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-5 font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400">menu_book</span>
                                    {{ $schedule->current_lesson_title }}
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500 font-bold bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">group</span>
                                        {{ trans_choice('{0} 0 học viên|{1} 1 học viên|[2,*] :count học viên', $schedule->students_count, ['count' => $schedule->students_count]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="flex flex-col gap-6">
                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-orange-500 text-3xl">notifications_active</span>
                    {{ __('Thông báo mới') }}
                </h2>

                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col h-fit relative">
                    <div class="flex flex-col relative z-10">
                        @forelse ($notifications as $notification)
                        @php
                        $link = isset($notification->data['link']) ? url($notification->data['link']) : '#';
                        $title = $notification->data['title'] ?? __('Thông báo');
                        $message = $notification->data['message'] ?? '';
                        $isUnread = is_null($notification->read_at);
                        @endphp
                        <a href="{{ $link }}" class="group p-6 hover:bg-orange-50/50 dark:hover:bg-orange-900/10 transition-colors border-b border-slate-100 dark:border-slate-800 flex gap-5 relative overflow-hidden">
                            <div class="mt-1 size-12 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/40 dark:to-orange-900/20 text-orange-600 shadow-sm flex items-center justify-center shrink-0 border border-orange-200/50 dark:border-orange-800/50 group-hover:scale-110 group-hover:rotate-6 transition-all">
                                <span class="material-symbols-outlined text-2xl">mail</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1.5">
                                    <p class="font-extrabold text-slate-800 dark:text-slate-200 text-base group-hover:text-orange-600 transition-colors">
                                        {{ $title }}
                                    </p>
                                    @if ($isUnread)
                                    <span class="text-[10px] font-black uppercase text-white bg-orange-500 px-2 py-0.5 rounded shadow-sm">
                                        {{ __('Mới') }}
                                    </span>
                                    @endif
                                </div>
                                @if ($message)
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 font-medium leading-relaxed">
                                    {{ $message }}
                                </p>
                                @endif
                                <p class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5 uppercase letter-spacing">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                        @empty
                        <!-- Empty Notifications -->
                        <div class="flex flex-col items-center justify-center text-center py-16 px-4">
                            <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-700 mb-3">notifications_off</span>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">{{ __('Bạn không có thông báo nào') }}</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 mt-auto border-t border-slate-100 dark:border-slate-800 text-center">
                        <a href="#" class="w-full inline-flex justify-center items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors group">
                            {{ __('Xem tất cả thông báo') }} <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection