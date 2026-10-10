@extends('portal.layouts.dashboard')

@section('title', __('Khóa học của bạn') . ' - ' . config('app.name', 'LMS'))

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1400px] mx-auto space-y-6">

            <x-portal.page-header
                :title="__('Khóa học của bạn')"
                :description="__('Danh sách tất cả các khóa học bạn đang tham gia.')"
            />

            @if($enrollments->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-sm space-y-2">
                    <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">school</span>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Bạn chưa tham gia khóa học nào') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">{{ __('Hãy khám phá các khóa học hấp dẫn và bắt đầu hành trình học tập của bạn nhé.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                        @endphp
                        <a href="{{ route('student.courses.show', $course->id) }}" class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-200 overflow-hidden flex flex-col h-full cursor-pointer">

                            <div class="relative h-40 overflow-hidden bg-slate-100 dark:bg-slate-800">
                                <img src="{{ $course->thumbnail_url }}" 
                                     alt="{{ $course->title }}" 
                                     onerror="this.onerror=null;this.src='{{ asset('images/default-course.jpg') }}';"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 {{ $enrollment->status === \App\Enums\EnrollmentStatus::COMPLETED ? 'bg-emerald-500' : 'bg-amber-500' }} text-white rounded-lg text-[10px] font-bold shadow-xs">
                                        {{ $enrollment->status === \App\Enums\EnrollmentStatus::COMPLETED ? __('Đã hoàn thành') : __('Đang học') }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">
                                        {{ $course->title }}
                                    </h3>

                                    <div class="flex items-center gap-2.5 mb-4">
                                        @php
                                            $teacher = $course->teacher;
                                            $teacherName = $teacher ? ($teacher->first_name . ' ' . $teacher->last_name) : __('Chưa phân công');
                                            $teacherAvatar = $teacher?->avatar_url;
                                        @endphp
                                        @if($teacherAvatar)
                                            <img src="{{ $teacherAvatar }}" class="size-6 rounded-full border border-slate-200 dark:border-slate-700 object-cover" alt="{{ $teacherName }}">
                                        @else
                                            <div class="size-6 rounded-full bg-primary/10 text-primary border border-slate-200 dark:border-slate-700 flex items-center justify-center text-[10px] font-bold shrink-0">
                                                {{ $teacher ? strtoupper(substr($teacher->first_name, 0, 1)) : '?' }}
                                            </div>
                                        @endif
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 truncate">{{ $teacherName }}</span>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center justify-center gap-1.5 w-full py-2 bg-slate-50 group-hover:bg-primary text-slate-700 group-hover:text-white dark:bg-slate-800 dark:text-slate-300 dark:group-hover:bg-primary dark:group-hover:text-white rounded-xl text-xs font-bold transition-all border border-slate-200 dark:border-slate-700">
                                        <span>{{ __('Chi tiết khóa học') }}</span>
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $enrollments->links('components.pagination') }}
                </div>
            @endif
        </div>
    </main>
@endsection
