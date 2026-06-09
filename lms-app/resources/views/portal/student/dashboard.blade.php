@extends('portal.layouts.dashboard')

@section('title', 'Student Dashboard - XiaoMu')

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-10 space-y-8">
        <!-- Welcome Banner -->
        <div x-data="{ showBanner: true }" x-show="showBanner" x-transition.opacity.duration.300ms class="bg-gradient-to-r from-primary to-blue-500 rounded-3xl py-6 pl-8 pr-12 lg:pr-16 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <!-- Nút đóng -->
            <button @click="showBanner = false" class="absolute top-1/2 -translate-y-1/2 right-4 lg:right-5 z-20 size-8 flex items-center justify-center rounded-full bg-black/10 hover:bg-black/20 text-white/90 hover:text-white transition-all backdrop-blur-md" title="{{ __('Đóng banner') }}">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold mb-1.5 tracking-tight">
                        {{ Auth::user()->name ? __('Chào mừng quay trở lại, :name!', ['name' => Auth::user()->name]) : __('Chào mừng quay trở lại!') }}
                    </h1>
                    <p class="text-white/90 text-sm font-medium">
                        {{ __('Hôm nay là một ngày tuyệt vời để tiếp thu thêm kiến thức Hán Ngữ mới. Hãy cùng cố gắng nhé!') }}
                    </p>
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

        <!-- 3 Thẻ thống kê động cao cấp -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stat 1: Khóa học đang học -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="size-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-500 shrink-0">
                    <span class="material-symbols-outlined text-3xl">play_circle</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Khóa học đang học') }}</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['active_courses'] }}</p>
                </div>
            </div>

            <!-- Stat 2: Bài tập hoàn thành -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="size-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-500 shrink-0">
                    <span class="material-symbols-outlined text-3xl">task_alt</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Bài tập hoàn thành') }}</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['completed_assignments'] }}</p>
                </div>
            </div>

            <!-- Stat 3: Bài kiểm tra hoàn thành -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="size-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-500 shrink-0">
                    <span class="material-symbols-outlined text-3xl">quiz</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Bài kiểm tra hoàn thành') }}</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['completed_quizzes'] }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Cột trái: Carousel học tiếp tục & Mẹo học tập -->
            <div class="xl:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">play_lesson</span>
                        {{ __('Học tiếp tục') }}
                    </h2>
                </div>

                @if(count($continuingCourses) > 0)
                    <!-- Carousel trượt các khóa học sử dụng AlpineJS -->
                    <div x-data="{ activeSlide: 0, slidesCount: {{ count($continuingCourses) }} }" class="relative w-full">
                        
                        <!-- Nút điều hướng Carousel nếu có trên 1 khóa học -->
                        @if(count($continuingCourses) > 1)
                            <div class="absolute -top-12 right-0 flex items-center gap-1.5 z-10">
                                <button @click="activeSlide = activeSlide === 0 ? slidesCount - 1 : activeSlide - 1" 
                                    class="size-8 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400">
                                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                                </button>
                                <button @click="activeSlide = activeSlide === slidesCount - 1 ? 0 : activeSlide + 1" 
                                    class="size-8 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400">
                                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                                </button>
                            </div>
                        @endif

                        <!-- Viewport của Carousel -->
                        <div class="overflow-hidden rounded-3xl border border-primary/10 bg-white dark:bg-slate-900 shadow-sm">
                            <div class="flex transition-transform duration-500 ease-out" 
                                 :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                                
                                @foreach($continuingCourses as $course)
                                    <div class="w-full shrink-0 flex flex-col md:flex-row overflow-hidden">
                                        <div class="w-full md:w-64 h-48 bg-slate-200 dark:bg-slate-800 shrink-0 relative">
                                            <img alt="{{ $course['title'] }}" class="w-full h-full object-cover"
                                                src="{{ $course['thumbnail'] }}" />
                                        </div>
                                        <div class="p-6 flex flex-col justify-between flex-1">
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="px-2 py-0.5 bg-primary/20 text-primary text-[10px] font-bold rounded uppercase">
                                                        {{ $course['category_name'] }}
                                                    </span>
                                                    <span class="text-slate-400 text-xs">• {{ $course['lessons_count'] }} {{ __('bài giảng') }}</span>
                                                </div>
                                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 line-clamp-1">
                                                    {{ $course['title'] }}
                                                </h3>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mt-2">
                                                    <span class="material-symbols-outlined text-base text-slate-400">arrow_right_alt</span>
                                                    {{ __('Tiếp theo:') }} <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $course['next_lesson_title'] }}</span>
                                                </p>
                                            </div>
                                            <div class="mt-4">
                                                <div class="flex justify-between text-xs font-semibold mb-2">
                                                    <span class="text-slate-600 dark:text-slate-400">{{ __('Tiến độ:') }} {{ $course['progress_percentage'] }}%</span>
                                                    <span class="text-primary">{{ $course['completed_lessons'] }}/{{ $course['lessons_count'] }} {{ __('bài học') }}</span>
                                                </div>
                                                <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full mb-4">
                                                    <div class="bg-primary h-full rounded-full transition-all duration-500" :style="'width: {{ $course['progress_percentage'] }}%'"></div>
                                                </div>
                                                <button class="bg-primary hover:bg-primary/95 text-white font-bold py-2 px-6 rounded-lg transition-all w-full md:w-auto shadow-sm shadow-primary/20">
                                                    {{ __('Học tiếp') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @else
                    <!-- Trạng thái trống của Khóa học -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-primary/10 p-12 text-center shadow-sm">
                        <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-700 mb-4 animate-pulse">menu_book</span>
                        <h3 class="font-extrabold text-lg text-slate-800 dark:text-white mb-2">{{ __('Bạn chưa đăng ký khóa học nào') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-6">{{ __('Đăng ký các khóa học tiếng Trung chất lượng cao ngay hôm nay để bắt đầu hành trình học tập của bạn!') }}</p>
                        <button class="bg-primary text-white py-2 px-6 rounded-xl font-bold hover:bg-primary/90 transition-colors shadow-md shadow-primary/20">
                            {{ __('Khám phá khóa học') }}
                        </button>
                    </div>
                @endif


            </div>

            <!-- Cột phải: Việc cần làm (To-do) & Lịch học sắp tới -->
            <div class="flex flex-col-reverse gap-8">
                <!-- Lịch học sắp tới tích hợp Popup Modal -->
                <div x-data="{ showScheduleModal: false, selectedSchedule: {} }" class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-primary/10 shadow-sm flex flex-col justify-between h-fit relative">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">calendar_month</span>
                                {{ __('Lịch học sắp tới') }}
                            </h3>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($schedules as $schedule)
                                <div @click="selectedSchedule = {{ json_encode($schedule) }}; showScheduleModal = true;" 
                                     class="flex gap-4 items-start cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/40 p-2 -m-2 rounded-xl transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center rounded-xl size-12 shrink-0 {{ $schedule['is_today'] ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                                        <span class="text-[9px] font-bold uppercase">{{ $schedule['day_of_week'] }}</span>
                                        <span class="text-lg font-black leading-none mt-0.5">{{ $schedule['day_number'] }}</span>
                                    </div>
                                    <div class="flex-1 border-b border-slate-100 dark:border-slate-800 pb-3">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{{ $schedule['course_title'] }}</p>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm shrink-0">schedule</span>
                                            {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                            <span class="text-slate-300 dark:text-slate-700">•</span>
                                            <span class="line-clamp-1">{{ $schedule['room'] }}</span>
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400 dark:text-slate-600">
                                    <span class="material-symbols-outlined text-4xl mb-2">calendar_today</span>
                                    <p class="text-xs font-semibold">{{ __('Không có lịch học nào sắp tới') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Schedule Detail Popup Modal -->
                    <div x-show="showScheduleModal" 
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         style="display: none;">
                        
                        <!-- Modal Container -->
                        <div @click.outside="showScheduleModal = false" 
                             class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-slate-100 dark:border-slate-800 transform transition-all"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-6">
                                <span :class="selectedSchedule.is_today ? 'bg-primary/20 text-primary' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'" 
                                      class="px-3 py-1 rounded-xl text-xs font-bold"
                                      x-text="selectedSchedule.is_today ? '{{ __('Hôm nay') }}' : selectedSchedule.day_name_full">
                                </span>
                                <button @click="showScheduleModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>

                            <!-- Content -->
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ __('Khóa học') }}</h4>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white" x-text="selectedSchedule.course_title"></h3>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-2xl border border-slate-100 dark:border-slate-800">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ __('Thời gian') }}</p>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white" x-text="selectedSchedule.start_time + ' - ' + selectedSchedule.end_time"></p>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-2xl border border-slate-100 dark:border-slate-800">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ __('Ngày học') }}</p>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white" x-text="selectedSchedule.formatted_date"></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                                    <div class="size-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Giảng viên') }}</p>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate" x-text="selectedSchedule.teacher_name"></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                                    <div class="size-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined" x-text="selectedSchedule.meeting_link ? 'videocam' : 'room'"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" x-text="selectedSchedule.meeting_link ? '{{ __('Hình thức') }}' : '{{ __('Phòng học') }}'"></p>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate" x-text="selectedSchedule.meeting_link ? '{{ __('Học Trực Tuyến') }}' : selectedSchedule.room"></p>
                                    </div>
                                </div>

                                <!-- Action Button (Zoom link or Close) -->
                                <div class="pt-2">
                                    <template x-if="selectedSchedule.meeting_link">
                                        <a :href="selectedSchedule.meeting_link" target="_blank" 
                                           class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition-all">
                                            <span class="material-symbols-outlined text-lg">videocam</span>
                                            {{ __('Tham gia lớp Zoom') }}
                                        </a>
                                    </template>
                                    <template x-if="!selectedSchedule.meeting_link">
                                        <button @click="showScheduleModal = false" 
                                                class="w-full py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-2xl transition-colors">
                                            {{ __('Đóng cửa sổ') }}
                                        </button>
                                    </template>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Việc cần làm (To-do List) -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-primary/10 shadow-sm">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-500">task_alt</span>
                        {{ __('Việc cần làm (To-do)') }}
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($todoTasks as $task)
                            <a href="{{ $task['link'] }}" class="flex items-start gap-3 group hover:bg-slate-50 dark:hover:bg-slate-800/40 p-2 -m-2 rounded-xl transition-colors">
                                <div class="mt-0.5 size-5 border-2 rounded-md flex items-center justify-center shrink-0 border-primary group-hover:bg-primary/10">
                                    <span class="material-symbols-outlined text-primary text-sm font-black scale-0 group-hover:scale-100 transition-transform">check</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-primary transition-colors truncate">
                                        {{ $task['title'] }}
                                    </p>
                                    <p class="text-[10px] font-bold mt-0.5 {{ $task['is_urgent'] ? 'text-red-500' : 'text-slate-400' }}">
                                        {{ $task['due_info'] }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8 text-slate-400 dark:text-slate-600">
                                <span class="material-symbols-outlined text-4xl mb-2">done_all</span>
                                <p class="text-xs font-semibold">{{ __('Tuyệt vời! Đã hoàn thành hết bài tập') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
    </main>
@endsection
