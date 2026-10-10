@extends('portal.layouts.dashboard')

@section('title', __('Lớp học của tôi') . ' - XiaoMu LMS')

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

            <x-portal.page-header
                :title="__('Lớp học của tôi')"
                :description="__('Quản lý danh sách các khóa học và học viên bạn đang phụ trách giảng dạy.')"
            />

            @if($classes->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-12 text-center space-y-3">
                    <div class="size-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mx-auto border border-primary/20">
                        <span class="material-symbols-outlined text-3xl">school</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Chưa có lớp học nào') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto font-medium">
                        {{ __('Bạn chưa được phân công giảng dạy khóa học nào. Vui lòng liên hệ Quản trị viên để được cấp quyền.') }}
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($classes as $class)
                        <a href="{{ route('teacher.classes.show', $class->id) }}"
                           class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-primary/50 transition-all duration-300 overflow-hidden flex flex-col h-full cursor-pointer">

                            <div class="relative h-40 overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @if($class->thumbnail)
                                    <img src="{{ asset('storage/' . $class->thumbnail) }}" alt="{{ $class->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-primary/30">
                                        <span class="material-symbols-outlined text-5xl">school</span>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-0.5 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs rounded-lg text-[10px] font-bold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 shadow-xs">
                                        {{ $class->category?->name ?? __('Khóa học') }}
                                    </span>
                                </div>

                                @if($class->is_published)
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2 py-0.5 bg-emerald-500 text-white rounded-lg text-[10px] font-bold flex items-center gap-1 shadow-xs">
                                            <span class="size-1.5 bg-white rounded-full animate-pulse"></span>
                                            {{ __('Đang mở') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between space-y-3.5">
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors line-clamp-2 leading-snug">
                                        {{ $class->title }}
                                    </h3>
                                </div>

                                <div class="grid grid-cols-2 gap-3 py-3 border-y border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 rounded-xl px-3">
                                    <div class="flex items-center gap-2">
                                        <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                                            <span class="material-symbols-outlined text-base">group</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-slate-400 block">{{ __('Học viên') }}</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-white">{{ $class->enrollments_count }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="size-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 flex items-center justify-center border border-amber-200 dark:border-amber-900">
                                            <span class="material-symbols-outlined text-base">menu_book</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-slate-400 block">{{ __('Bài học') }}</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-white">{{ $class->lessons_count ?? $class->lessons()->count() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex -space-x-2 overflow-hidden">
                                        @foreach($class->enrollments->take(3) as $enrollment)
                                            <img class="inline-block size-7 rounded-lg ring-2 ring-white dark:ring-slate-900 object-cover bg-slate-100"
                                                 src="{{ $enrollment->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($enrollment->user->first_name) }}"
                                                 alt="{{ $enrollment->user->first_name }}"
                                                 title="{{ $enrollment->user->full_name }}">
                                        @endforeach
                                        @if($class->enrollments_count > 3)
                                            <div class="inline-flex items-center justify-center size-7 rounded-lg ring-2 ring-white dark:ring-slate-900 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500">
                                                +{{ $class->enrollments_count - 3 }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:text-primary/80 transition-colors">
                                        <span>{{ __('Vào lớp học') }}</span>
                                        <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($classes->hasPages())
                    <div class="pt-4">
                        {{ $classes->links('components.pagination') }}
                    </div>
                @endif
            @endif

        </div>
    </main>
@endsection
