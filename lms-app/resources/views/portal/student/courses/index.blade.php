@extends('portal.layouts.dashboard')

@section('title', 'Khóa học của tôi - ' . config('app.name', 'LMS'))

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1400px] mx-auto space-y-8">
            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">menu_book</span>
                        Khóa học của tôi
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">Danh sách tất cả các khóa học bạn đang tham gia.</p>
                </div>
            </div>

            {{-- Course Grid --}}
            @if($enrollments->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-12 text-center">
                    <div class="size-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-4xl text-slate-400">school</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Bạn chưa tham gia khóa học nào</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Hãy khám phá các khóa học hấp dẫn và bắt đầu hành trình học tập của bạn nhé.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $progressPercent = 0; // TODO: Calculate real progress
                        @endphp
                        <div class="group bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500 overflow-hidden flex flex-col h-full border-b-4 border-b-transparent hover:border-b-primary relative">
                            {{-- Thumbnail --}}
                            <div class="relative h-48 overflow-hidden">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary/20 to-blue-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-6xl text-primary/30">school</span>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span class="px-4 py-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-wider text-primary shadow-sm cursor-default">
                                        {{ $course->category?->name ?? 'Chưa phân loại' }}
                                    </span>
                                </div>
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 {{ $enrollment->status === \App\Enums\EnrollmentStatus::COMPLETED ? 'bg-emerald-500' : 'bg-amber-500' }} text-white rounded-full text-[10px] font-bold flex items-center gap-1 shadow-lg">
                                        {{ $enrollment->status === \App\Enums\EnrollmentStatus::COMPLETED ? 'Đã hoàn thành' : 'Đang học' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-8 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                                    {{ $course->title }}
                                </h3>
                                
                                <div class="flex items-center gap-3 mb-6">
                                    @php
                                        $teacherName = $course->teacher->name ?? $course->teacher->first_name;
                                    @endphp
                                    <img src="{{ $course->teacher->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacherName) }}" class="size-8 rounded-full border border-slate-200 dark:border-slate-700">
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ $teacherName }}</span>
                                </div>

                                <div class="mt-auto pt-6 border-t border-slate-50 dark:border-slate-800/50">
                                    <div class="flex justify-between items-end mb-2">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tiến độ học tập</span>
                                            <span class="text-sm font-black text-slate-800 dark:text-white">{{ $progressPercent }}%</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                                        <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('student.courses.show', $course->id) }}" class="flex items-center justify-center gap-2 w-full py-3 bg-slate-50 hover:bg-primary text-slate-700 hover:text-white dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-primary dark:hover:text-white rounded-xl font-bold transition-all group/btn">
                                        Chi tiết khóa học
                                        <span class="material-symbols-outlined text-lg group-hover/btn:translate-x-1 transition-transform">arrow_right_alt</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $enrollments->links('components.pagination') }}
                </div>
            @endif
        </div>
    </main>
@endsection
