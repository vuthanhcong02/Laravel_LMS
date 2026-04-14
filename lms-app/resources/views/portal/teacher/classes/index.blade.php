@extends('portal.layouts.dashboard')

@section('title', 'Lớp học của tôi')

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1400px] mx-auto space-y-8">
            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">groups</span>
                        Lớp học của tôi
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">Quản lý danh sách các khóa học và học viên bạn đang phụ trách.</p>
                </div>
            </div>

            {{-- Course Grid --}}
            @if($classes->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-12 text-center">
                    <div class="size-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-4xl text-slate-400">school</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Chưa có lớp học nào</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Bạn chưa được phân công giảng dạy khóa học nào. Vui lòng liên hệ Admin để được cấp quyền.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach($classes as $class)
                        <div class="group bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500 overflow-hidden flex flex-col h-full border-b-4 border-b-transparent hover:border-b-primary">
                            {{-- Thumbnail --}}
                            <div class="relative h-48 overflow-hidden">
                                @if($class->thumbnail)
                                    <img src="{{ asset('storage/' . $class->thumbnail) }}" alt="{{ $class->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary/20 to-blue-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-6xl text-primary/30">school</span>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <button @click="$dispatch('notify', { msg: 'Lớp học thuộc danh mục: {{ $class->category?->name ?? 'Chưa phân loại' }}', type: 'info' })" 
                                            class="px-4 py-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-wider text-primary shadow-sm hover:bg-primary hover:text-white transition-all">
                                        {{ $class->category?->name ?? 'Chưa phân loại' }}
                                    </button>
                                </div>
                                @if($class->is_published)
                                    <div class="absolute top-4 right-4">
                                        <button @click="$dispatch('notify', { msg: 'Trạng thái: Đang công khai', type: 'success' })" 
                                                class="px-3 py-1 bg-emerald-500 text-white rounded-full text-[10px] font-bold flex items-center gap-1 shadow-lg hover:bg-emerald-600 transition-all">
                                            <span class="size-1.5 bg-white rounded-full animate-pulse"></span> Công khai
                                        </button>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-8 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                                    {{ $class->title }}
                                </h3>
                                
                                <div class="flex items-center gap-6 mt-auto py-6 border-y border-slate-50 dark:border-slate-800/50">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Học viên</span>
                                        <p class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2">
                                            <span class="material-symbols-outlined text-primary text-xl">group</span>
                                            {{ $class->enrollments_count }}
                                        </p>
                                    </div>
                                    <div class="w-px h-8 bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Bài học</span>
                                        <p class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2">
                                            <span class="material-symbols-outlined text-orange-500 text-xl">menu_book</span>
                                            {{ $class->lessons_count ?? $class->lessons()->count() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center justify-between">
                                    <div class="flex -space-x-3 overflow-hidden">
                                        @foreach($class->enrollments->take(3) as $enrollment)
                                            <button @click="$dispatch('notify', { msg: 'Học viên: {{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}', type: 'info' })" class="focus:outline-none transition-transform hover:scale-110 hover:z-10">
                                                <img class="inline-block size-8 rounded-full ring-2 ring-white dark:ring-slate-900 object-cover" 
                                                     src="{{ $enrollment->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($enrollment->user->first_name) }}" 
                                                     alt="{{ $enrollment->user->first_name }}">
                                            </button>
                                        @endforeach
                                        @if($class->enrollments_count > 3)
                                            <div class="inline-flex items-center justify-center size-8 rounded-full ring-2 ring-white dark:ring-slate-900 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500">
                                                +{{ $class->enrollments_count - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('teacher.classes.show', $class->id) }}" class="inline-flex items-center gap-2 text-sm font-black text-primary hover:text-blue-600 transition-colors group/link">
                                        Chi tiết lớp 
                                        <span class="material-symbols-outlined text-lg group-hover/link:translate-x-1 transition-transform">arrow_right_alt</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $classes->links('components.pagination') }}
                </div>
            @endif
        </div>
    </main>
@endsection
