@extends('portal.layouts.dashboard')

@section('title', __('Bảng điều khiển Học viên') . ' - XiaoMu LMS')

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full min-w-0">
    <div class="max-w-[1400px] mx-auto space-y-6 min-w-0">

        <x-flash-message type="success" />
        <x-flash-message type="error" />

        <div class="animate-fade-in-up stagger-1 bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300">
            <div class="space-y-1">
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Chào mừng quay trở lại, ') }} {{ auth()->user()->first_name ?? __('Học viên') }}!
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-medium">
                    {{ __('Chúc bạn một ngày học tập tràn đầy hứng khởi và tiếp thu thêm nhiều kiến thức Hán Ngữ mới.') }}
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            <a href="{{ route('student.courses.index') }}"
               class="animate-fade-in-up stagger-2 group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-4.5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-primary/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="size-11 rounded-xl bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-500/20 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-primary group-hover:text-white group-hover:border-transparent transition-all duration-300">
                        <span class="material-symbols-outlined text-2xl">menu_book</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white leading-tight">
                            {{ number_format($stats['active_courses'] ?? 0) }}
                        </p>
                        <h3 class="text-xs font-semibold text-slate-600 dark:text-slate-400 truncate mt-0.5">
                            {{ __('Khóa học đang học') }}
                        </h3>
                    </div>
                </div>
                <div class="size-8 rounded-lg bg-slate-50 dark:bg-slate-800/80 text-slate-400 group-hover:text-primary group-hover:bg-primary/10 flex items-center justify-center shrink-0 transition-colors">
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>

            <a href="{{ route('student.assignments.index') }}"
               class="animate-fade-in-up stagger-3 group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-4.5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="size-11 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-emerald-500 group-hover:text-white group-hover:border-transparent transition-all duration-300">
                        <span class="material-symbols-outlined text-2xl">task_alt</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white leading-tight">
                            {{ number_format($stats['completed_assignments'] ?? 0) }}
                        </p>
                        <h3 class="text-xs font-semibold text-slate-600 dark:text-slate-400 truncate mt-0.5">
                            {{ __('Bài tập hoàn thành') }}
                        </h3>
                    </div>
                </div>
                <div class="size-8 rounded-lg bg-slate-50 dark:bg-slate-800/80 text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-500/10 flex items-center justify-center shrink-0 transition-colors">
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>

            <a href="{{ route('student.quizzes.index') }}"
               class="animate-fade-in-up stagger-4 group bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-4.5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-sky-500/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="size-11 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-sky-500 group-hover:text-white group-hover:border-transparent transition-all duration-300">
                        <span class="material-symbols-outlined text-2xl">quiz</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white leading-tight">
                            {{ number_format($stats['completed_quizzes'] ?? 0) }}
                        </p>
                        <h3 class="text-xs font-semibold text-slate-600 dark:text-slate-400 truncate mt-0.5">
                            {{ __('Bài kiểm tra hoàn thành') }}
                        </h3>
                    </div>
                </div>
                <div class="size-8 rounded-lg bg-slate-50 dark:bg-slate-800/80 text-slate-400 group-hover:text-sky-500 group-hover:bg-sky-500/10 flex items-center justify-center shrink-0 transition-colors">
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-6 min-w-0">

                <div x-data="{ activeSlide: 0, slidesCount: {{ count($continuingCourses) }} }" class="animate-fade-in-up stagger-5 space-y-4">
                    <div class="flex items-center justify-between px-1">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">play_lesson</span>
                            <span>{{ __('Khóa học của bạn') }}</span>
                        </h2>
                        <div class="flex items-center gap-3">
                            @if(count($continuingCourses) > 1)
                                <div class="flex items-center gap-1">
                                    <button @click="activeSlide = activeSlide === 0 ? slidesCount - 1 : activeSlide - 1" 
                                        class="size-7 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400 shadow-xs" title="{{ __('Khóa học trước') }}">
                                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                                    </button>
                                    <button @click="activeSlide = activeSlide === slidesCount - 1 ? 0 : activeSlide + 1" 
                                        class="size-7 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400 shadow-xs" title="{{ __('Khóa học tiếp theo') }}">
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </button>
                                </div>
                            @endif
                            @if(count($continuingCourses) > 0)
                                <a href="{{ route('student.courses.index') }}"
                                   class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                    <span>{{ __('Tất cả khóa học') }}</span>
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(count($continuingCourses) > 0)
                        <div class="relative w-full overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <div class="flex w-full transition-transform duration-500 ease-out" 
                                 :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                                
                                @foreach($continuingCourses as $course)
                                    <div class="min-w-full w-full shrink-0 flex flex-col sm:flex-row p-4 sm:p-5 gap-4 sm:gap-5 items-stretch">
                                        <a href="{{ route('student.courses.show', $course['id']) }}" class="w-full sm:w-48 h-32 rounded-xl overflow-hidden shrink-0 relative bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 block group/thumb">
                                            <img alt="{{ $course['title'] }}" class="w-full h-full object-cover group-hover/thumb:scale-105 transition-transform duration-300"
                                                src="{{ $course['thumbnail'] }}"
                                                onerror="this.onerror=null;this.src='{{ asset('images/default-course.jpg') }}';" />
                                        </a>

                                        <div class="flex flex-col justify-between flex-1 min-w-0 py-0.5">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1.5">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-primary/10 text-primary">
                                                        <span class="material-symbols-outlined text-[13px]">school</span>
                                                        <span>{{ $course['lessons_count'] }} {{ __('bài giảng') }}</span>
                                                    </span>
                                                </div>
                                                <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white line-clamp-1 hover:text-primary transition-colors">
                                                    <a href="{{ route('student.courses.show', $course['id']) }}">
                                                        {{ $course['title'] }}
                                                    </a>
                                                </h3>
                                            </div>

                                            <div class="flex items-center justify-between gap-3 pt-2">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    @if($course['teacher_avatar'])
                                                        <img src="{{ $course['teacher_avatar'] }}" class="size-6 rounded-full border border-slate-200 dark:border-slate-700 object-cover shrink-0" alt="{{ $course['teacher_name'] }}">
                                                    @else
                                                        <div class="size-6 rounded-full bg-primary/10 text-primary border border-slate-200 dark:border-slate-700 flex items-center justify-center text-[10px] font-bold shrink-0">
                                                            {{ $course['teacher_name'] ? strtoupper(substr($course['teacher_name'], 0, 1)) : '?' }}
                                                        </div>
                                                    @endif
                                                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400 truncate">{{ $course['teacher_name'] }}</span>
                                                </div>

                                                <a href="{{ route('student.courses.show', $course['id']) }}" class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-all shadow-xs hover:shadow-md">
                                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                                    <span>{{ __('Xem') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-10 text-center shadow-sm space-y-3">
                            <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">menu_book</span>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Bạn chưa đăng ký khóa học nào') }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto font-medium">
                                {{ __('Đăng ký các khóa học tiếng Trung chất lượng cao ngay hôm nay để bắt đầu hành trình học tập của bạn!') }}
                            </p>
                            <div class="pt-2">
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-colors shadow-xs">
                                    <span class="material-symbols-outlined text-sm">explore</span>
                                    <span>{{ __('Khám phá khóa học') }}</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <div x-data="{ showScheduleModal: false, selectedSchedule: {} }" class="animate-fade-in-up stagger-6 space-y-4">
                    <div class="flex items-center justify-between px-1">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">event_upcoming</span>
                            <span>{{ __('Lịch học sắp tới') }}</span>
                        </h2>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 shadow-sm">
                        @forelse($schedules as $schedule)
                            <div @click="selectedSchedule = {{ json_encode($schedule) }}; showScheduleModal = true;" 
                                 class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 mb-3 last:mb-0 hover:border-primary/40 transition-colors cursor-pointer">
                                
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="text-center shrink-0 px-3 py-1.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 min-w-[76px]">
                                        <span class="text-[10px] font-bold text-primary uppercase block">{{ $schedule['day_of_week'] }} ({{ $schedule['day_number'] }})</span>
                                        <span class="text-xs font-bold text-slate-900 dark:text-white block mt-0.5">
                                            {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                        </span>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate">
                                            {{ __('Lớp: ') }} {{ $schedule['course_title'] }}
                                        </h3>
                                        <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mt-1">
                                            <span class="flex items-center gap-1 truncate">
                                                <span class="material-symbols-outlined text-sm text-slate-400">person</span>
                                                <span>{{ $schedule['teacher_name'] }}</span>
                                            </span>
                                            <span class="text-slate-300 dark:text-slate-600">•</span>
                                            <span class="flex items-center gap-1 truncate">
                                                <span class="material-symbols-outlined text-sm text-slate-400">{{ $schedule['meeting_link'] ? 'videocam' : 'room' }}</span>
                                                <span>{{ $schedule['meeting_link'] ? __('Học trực tuyến') : $schedule['room'] }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center justify-end gap-2">
                                    @if($schedule['is_today'])
                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold border border-emerald-200 dark:border-emerald-800">
                                            {{ __('Hôm nay') }}
                                        </span>
                                    @endif
                                    <span class="text-slate-400 hover:text-primary transition-colors material-symbols-outlined text-lg">info</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center space-y-2">
                                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">free_cancellation</span>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Không có lịch học nào sắp tới') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto font-medium">
                                    {{ __('Bạn không có ca học nào trong thời gian tới. Hãy tranh thủ ôn tập và làm bài tập nhé!') }}
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <template x-teleport="body">
                        <div x-show="showScheduleModal" 
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             x-cloak
                             style="display: none;">
                            
                            <div @click.outside="showScheduleModal = false" 
                                 class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-7 max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 transform transition-all space-y-5 relative"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                                
                                <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-2.5">
                                        <div class="size-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                            <span class="material-symbols-outlined text-lg">calendar_month</span>
                                        </div>
                                        <div>
                                            <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">
                                                {{ __('Chi tiết lịch học') }}
                                            </h3>
                                            <span :class="selectedSchedule.is_today ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400'" 
                                                  class="text-[11px] font-semibold"
                                                  x-text="selectedSchedule.is_today ? '{{ __('Lịch học diễn ra hôm nay') }}' : selectedSchedule.day_name_full">
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span :class="selectedSchedule.is_today ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-[#fff2ee] dark:bg-[#2c221e] text-primary border border-[#fcdccf]/60 dark:border-primary/20'" 
                                              class="px-2.5 py-1 rounded-xl text-[11px] font-bold"
                                              x-text="selectedSchedule.is_today ? '{{ __('Hôm nay') }}' : selectedSchedule.day_of_week">
                                        </span>
                                        <button @click="showScheduleModal = false" 
                                                class="size-8 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition-all">
                                            <span class="material-symbols-outlined text-lg">close</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 space-y-1">
                                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider">{{ __('Khóa học') }}</p>
                                        <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white line-clamp-2" x-text="selectedSchedule.course_title"></h4>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60">
                                            <div class="flex items-center gap-1.5 mb-1 text-slate-400">
                                                <span class="material-symbols-outlined text-sm text-primary">schedule</span>
                                                <p class="text-[10px] font-bold uppercase tracking-wider">{{ __('Thời gian') }}</p>
                                            </div>
                                            <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white" x-text="selectedSchedule.start_time + ' - ' + selectedSchedule.end_time"></p>
                                        </div>

                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60">
                                            <div class="flex items-center gap-1.5 mb-1 text-slate-400">
                                                <span class="material-symbols-outlined text-sm text-amber-500">event</span>
                                                <p class="text-[10px] font-bold uppercase tracking-wider">{{ __('Ngày học') }}</p>
                                            </div>
                                            <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white" x-text="selectedSchedule.formatted_date"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60">
                                        <div class="size-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-xl">school</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Giảng viên phụ trách') }}</p>
                                            <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate" x-text="selectedSchedule.teacher_name"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60">
                                        <div class="size-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-xl" x-text="selectedSchedule.meeting_link ? 'videocam' : 'room'"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" x-text="selectedSchedule.meeting_link ? '{{ __('Hình thức học') }}' : '{{ __('Phòng học') }}'"></p>
                                            <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate" x-text="selectedSchedule.meeting_link ? '{{ __('Học Trực Tuyến') }}' : selectedSchedule.room"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button @click="showScheduleModal = false" 
                                            type="button" 
                                            class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition-all btn-tactile">
                                        {{ __('Đóng') }}
                                    </button>
                                </div>

                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="lg:col-span-1 animate-fade-in-up stagger-7 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">task_alt</span>
                        <span>{{ __('Việc cần làm') }}</span>
                    </h2>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($todoTasks as $task)
                        <a href="{{ $task['link'] }}" class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex items-start gap-3 group">
                            <div class="size-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-base">{{ $task['type'] === 'assignment' ? 'assignment' : 'quiz' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                    <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-primary transition-colors">
                                        {{ $task['title'] }}
                                    </p>
                                </div>
                                <span class="text-[10px] font-semibold block {{ $task['is_urgent'] ? 'text-rose-500 dark:text-rose-400 font-bold' : 'text-slate-400' }}">
                                    {{ $task['due_info'] }}
                                </span>
                            </div>
                            <span class="text-slate-300 dark:text-slate-600 group-hover:text-primary transition-colors material-symbols-outlined text-base">chevron_right</span>
                        </a>
                    @empty
                        <div class="py-10 text-center text-slate-400 space-y-1">
                            <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">done_all</span>
                            <p class="text-xs font-medium">{{ __('Tuyệt vời! Đã hoàn thành hết bài tập.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</main>
@endsection
