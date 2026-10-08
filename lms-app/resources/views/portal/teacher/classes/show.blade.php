@extends('portal.layouts.dashboard')

@section('title', $class->title . ' - ' . __('Chi tiết lớp học'))

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@php
    $initialConfig = [
        'classId' => $class->id,
        'activeTab' => $errors->hasAny(['title', 'record_url', 'video_url', 'pdf_file', 'note_file', 'note_content', 'description']) ? 'lessons' : (request('tab') ?? 'overview'),
        'showLessonModal' => $errors->hasAny(['title', 'record_url', 'video_url', 'pdf_file', 'note_file', 'note_content', 'description']),
        'showAddStudentModal' => $errors->hasAny(['user_ids', 'user_id']),
        'lessonModalMode' => old('_method') === 'PUT' ? 'edit' : 'create',
        'initialLessonForm' => [
            'id' => old('id'),
            'title' => old('title', ''),
            'description' => old('description', ''),
            'record_url' => old('record_url', ''),
            'note_content' => old('note_content', ''),
            'action_url' => old('action_url', route('teacher.classes.lessons.store', $class->id)),
        ],
        'routes' => [
            'availableStudents' => route('teacher.classes.available-students', $class->id),
            'unenrollStudent' => route('teacher.classes.unenroll', ['course' => $class->id, 'enrollment' => '__ID__']),
            'storeLesson' => route('teacher.classes.lessons.store', $class->id),
            'updateLesson' => route('teacher.classes.lessons.update', ['course' => $class->id, 'lesson' => '__ID__']),
            'destroyLesson' => route('teacher.classes.lessons.destroy', ['course' => $class->id, 'lesson' => '__ID__']),
            'storageAssetBase' => asset('storage'),
        ],
    ];
@endphp

@section('content')
    <script>
        window.__teacherClassConfig = {!! json_encode($initialConfig, JSON_UNESCAPED_UNICODE) !!};
    </script>

    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full" x-data="teacherClassDetail(window.__teacherClassConfig || {})">
        <div class="max-w-[1400px] mx-auto space-y-6">

            <x-flash-message type="success" />
            <x-flash-message type="error" />

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <nav class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400">
                    <a href="{{ route('teacher.classes.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        {{ __('Danh sách lớp') }}
                    </a>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    <span class="text-slate-900 dark:text-white font-bold truncate max-w-[280px]">{{ $class->title }}</span>
                </nav>

                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <button type="button" @click="openCreateLessonModal()"
                            class="px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 shadow-sm cursor-pointer active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        <span>{{ __('Thêm bài học') }}</span>
                    </button>

                    <button type="button" @click="openAddStudentModal()"
                            class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 shadow-sm cursor-pointer active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg">person_add</span>
                        <span>{{ __('Thêm học viên') }}</span>
                    </button>

                    <button type="button" @click="showAnnouncementModal = true"
                            class="px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 hover:text-primary hover:border-primary/40 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 shadow-xs cursor-pointer active:scale-[0.98]">
                        <span class="material-symbols-outlined text-lg text-primary">mail</span>
                        <span>{{ __('Gửi thông báo') }}</span>
                    </button>
                </div>
            </div>

            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 sm:p-7 shadow-sm">
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">

                    <div class="size-28 sm:size-36 rounded-2xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-800 shrink-0 bg-slate-100 dark:bg-slate-800">
                        @if($class->thumbnail)
                            <img src="{{ asset('storage/' . $class->thumbnail) }}" alt="{{ $class->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-primary bg-primary/10">
                                <span class="material-symbols-outlined text-5xl">school</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg shadow-xs">
                                {{ $class->category?->name ?? __('Khóa học') }}
                            </span>
                            @if($class->is_published)
                                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-lg flex items-center gap-1.5 shadow-xs">
                                    <span class="size-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    {{ __('Đang hoạt động') }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white leading-tight">
                            {{ $class->title }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-slate-600 dark:text-slate-300 font-medium text-xs sm:text-sm">
                            <span class="flex items-center gap-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                <span class="material-symbols-outlined text-primary text-lg">group</span>
                                <span>{{ $class->enrollments_count }} {{ __('Học viên') }}</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-amber-500 text-lg">menu_book</span>
                                <span>{{ $class->lessons->count() }} {{ __('Bài học') }}</span>
                            </span>
                            <span class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                <span class="material-symbols-outlined text-slate-400 text-lg">calendar_today</span>
                                <span>{{ __('Tạo ngày') }} {{ $class->created_at->format('d/m/Y') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6 border-b border-slate-200 dark:border-slate-800 px-2 overflow-x-auto">
                <button type="button" @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'text-primary border-b-2 border-primary font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                        class="pb-3 text-sm transition-all flex items-center gap-2 cursor-pointer whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    <span>{{ __('Tổng quan') }}</span>
                </button>

                <button type="button" @click="activeTab = 'lessons'"
                        :class="activeTab === 'lessons' ? 'text-primary border-b-2 border-primary font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                        class="pb-3 text-sm transition-all flex items-center gap-2 cursor-pointer whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">menu_book</span>
                    <span>{{ __('Bài học & Tài nguyên') }}</span>
                    <span class="px-2 py-0.5 rounded-md text-xs font-bold"
                          :class="activeTab === 'lessons' ? 'bg-primary/10 text-primary' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'">
                        {{ $class->lessons->count() }}
                    </span>
                </button>

                <button type="button" @click="activeTab = 'students'"
                        :class="activeTab === 'students' ? 'text-primary border-b-2 border-primary font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                        class="pb-3 text-sm transition-all flex items-center gap-2 cursor-pointer whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">groups</span>
                    <span>{{ __('Danh sách học viên') }}</span>
                    <span class="px-2 py-0.5 rounded-md text-xs font-bold"
                          :class="activeTab === 'students' ? 'bg-primary/10 text-primary' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'">
                        {{ $class->enrollments_count }}
                    </span>
                </button>
            </div>

            <div class="space-y-6">

                <div x-show="activeTab === 'overview'" x-transition class="space-y-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">{{ __('Tỉ lệ hoàn thành trung bình') }}</span>
                            <div class="flex items-baseline gap-2">
                                <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['completion_rate'] ?? 0 }}%</p>
                                @if(($stats['completed_students'] ?? 0) > 0)
                                    <span class="text-emerald-600 dark:text-emerald-400 text-xs font-semibold flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-sm">check_circle</span> {{ $stats['completed_students'] }}/{{ $stats['total_students'] }} {{ __('hoàn thành') }}
                                    </span>
                                @elseif(($stats['total_students'] ?? 0) > 0)
                                    <span class="text-slate-400 text-xs font-medium">
                                        0/{{ $stats['total_students'] }} {{ __('học viên hoàn thành') }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs font-medium">
                                        {{ __('Chưa có học viên') }}
                                    </span>
                                @endif
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden mt-3">
                                <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $stats['completion_rate'] ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">{{ __('Tổng số bài học') }}</span>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $class->lessons->count() }} <span class="text-sm font-medium text-slate-400">{{ __('bài giảng') }}</span></p>
                            <button type="button" @click="activeTab = 'lessons'" class="text-xs text-primary hover:underline font-semibold cursor-pointer">
                                {{ __('+ Quản lý bài học & tài nguyên') }}
                            </button>
                        </div>

                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">{{ __('Sĩ số lớp học') }}</span>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $class->enrollments_count }} <span class="text-sm font-medium text-slate-400">{{ __('học viên') }}</span></p>
                            <button type="button" @click="openAddStudentModal()" class="text-xs text-primary hover:underline font-semibold cursor-pointer">
                                {{ __('+ Thêm học viên mới') }}
                            </button>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/60 dark:bg-slate-800/40">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                                <span>{{ __('Lộ trình bài học') }}</span>
                            </h2>
                            <button type="button" @click="openCreateLessonModal()"
                                    class="px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold rounded-lg transition-colors flex items-center gap-1 cursor-pointer">
                                <span class="material-symbols-outlined text-sm">add</span>
                                <span>{{ __('Thêm bài học') }}</span>
                            </button>
                        </div>

                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($class->lessons as $lesson)
                                <div class="p-4 sm:p-5 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-all flex items-center justify-between gap-4 group">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="size-10 rounded-xl bg-primary/10 text-primary font-bold text-sm flex items-center justify-center shrink-0 border border-primary/20">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white group-hover:text-primary transition-colors truncate">
                                                {{ $lesson->title }}
                                            </h4>
                                            @if($lesson->description)
                                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $lesson->description }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">

                                        @if($lesson->effective_record_url)
                                            <a href="{{ $lesson->effective_record_url }}" target="_blank" rel="noopener noreferrer"
                                               class="px-2.5 py-1 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-rose-600 dark:text-rose-400 text-xs font-semibold flex items-center gap-1 hover:underline"
                                               title="{{ __('Xem video record buổi học') }}">
                                                <span class="material-symbols-outlined text-sm">play_circle</span>
                                                <span class="hidden sm:inline">{{ __('Record') }}</span>
                                            </a>
                                        @endif

                                        @if($lesson->pdf_path)
                                            <a href="{{ asset('storage/' . $lesson->pdf_path) }}" target="_blank" rel="noopener noreferrer"
                                               class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 text-blue-600 dark:text-blue-400 text-xs font-semibold flex items-center gap-1 hover:underline"
                                               title="{{ __('Tài liệu PDF bài học') }}">
                                                <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                                                <span class="hidden sm:inline">{{ __('PDF') }}</span>
                                            </a>
                                        @endif

                                        @if($lesson->note_file_path)
                                            <a href="{{ asset('storage/' . $lesson->note_file_path) }}" target="_blank" rel="noopener noreferrer"
                                               class="px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 text-amber-600 dark:text-amber-400 text-xs font-semibold flex items-center gap-1 hover:underline"
                                               title="{{ __('File ghi chú đính kèm') }}">
                                                <span class="material-symbols-outlined text-sm">attach_file</span>
                                                <span class="hidden sm:inline">{{ __('Note') }}</span>
                                            </a>
                                        @endif

                                        @if($lesson->note_content)
                                            <button type="button"
                                                    @click="viewNoteContent('{{ addslashes($lesson->title) }}', '{{ addslashes($lesson->note_content) }}')"
                                                    class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold flex items-center gap-1 hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer"
                                                    title="{{ __('Xem ghi chú bài học') }}">
                                                <span class="material-symbols-outlined text-sm">notes</span>
                                                <span class="hidden sm:inline">{{ __('Ghi chú') }}</span>
                                            </button>
                                        @endif

                                        <button type="button" @click="openEditLessonModal({{ json_encode($lesson) }})"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                                                title="{{ __('Sửa bài học') }}">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center text-slate-400 font-medium space-y-3">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">menu_book</span>
                                    <p>{{ __('Lớp học chưa có bài học nào.') }}</p>
                                    <button type="button" @click="openCreateLessonModal()" class="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary/90 transition-all cursor-pointer">
                                        {{ __('+ Thêm bài học đầu tiên') }}
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'lessons'" x-transition class="space-y-5">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">menu_book</span>
                                <span>{{ __('Danh sách bài học & Tài nguyên giảng dạy') }}</span>
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ __('Quản lý link record buổi học, file PDF slide/giáo trình, file tài liệu đính kèm và ghi chú bài giảng.') }}
                            </p>
                        </div>

                        <button type="button" @click="openCreateLessonModal()"
                                class="px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 shadow-sm cursor-pointer active:scale-[0.98] self-start sm:self-auto">
                            <span class="material-symbols-outlined text-lg">add_circle</span>
                            <span>{{ __('Tạo bài học mới') }}</span>
                        </button>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50/60 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-20 text-center">{{ __('Thứ tự') }}</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Tên bài học') }}</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Tài nguyên đính kèm') }}</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">{{ __('Thao tác') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse($class->lessons as $lesson)
                                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">

                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <span class="size-8 rounded-lg bg-primary/10 text-primary font-bold text-xs flex items-center justify-center border border-primary/20">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                    <div class="flex flex-col">
                                                        @if(!$loop->first)
                                                            <form action="{{ route('teacher.classes.lessons.move-up', ['course' => $class->id, 'lesson' => $lesson->id]) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="p-0.5 text-slate-400 hover:text-primary transition-colors cursor-pointer" title="{{ __('Di chuyển lên') }}">
                                                                    <span class="material-symbols-outlined text-sm">arrow_upward</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if(!$loop->last)
                                                            <form action="{{ route('teacher.classes.lessons.move-down', ['course' => $class->id, 'lesson' => $lesson->id]) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="p-0.5 text-slate-400 hover:text-primary transition-colors cursor-pointer" title="{{ __('Di chuyển xuống') }}">
                                                                    <span class="material-symbols-outlined text-sm">arrow_downward</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    <p class="font-bold text-sm text-slate-900 dark:text-white">
                                                        {{ $lesson->title }}
                                                    </p>
                                                    @if($lesson->description)
                                                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                                            {{ $lesson->description }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-xs font-medium">
                                                <div class="flex flex-wrap items-center gap-2">

                                                    @if($lesson->effective_record_url)
                                                        <a href="{{ $lesson->effective_record_url }}" target="_blank" rel="noopener noreferrer"
                                                           class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-semibold bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 px-2.5 py-1 rounded-lg hover:underline"
                                                           title="{{ __('Link Record buổi học') }}: {{ $lesson->effective_record_url }}">
                                                            <span class="material-symbols-outlined text-sm">play_circle</span>
                                                            <span>{{ __('Record') }}</span>
                                                            <span class="material-symbols-outlined text-[10px]">open_in_new</span>
                                                        </a>
                                                    @endif

                                                    @if($lesson->pdf_path)
                                                        <a href="{{ asset('storage/' . $lesson->pdf_path) }}" target="_blank" rel="noopener noreferrer"
                                                           class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 font-semibold bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 px-2.5 py-1 rounded-lg hover:underline"
                                                           title="{{ __('Xem file PDF bài học') }}">
                                                            <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                                                            <span>{{ __('PDF') }}</span>
                                                            <span class="material-symbols-outlined text-[10px]">download</span>
                                                        </a>
                                                    @endif

                                                    @if($lesson->note_file_path)
                                                        <a href="{{ asset('storage/' . $lesson->note_file_path) }}" target="_blank" rel="noopener noreferrer"
                                                           class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-semibold bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 px-2.5 py-1 rounded-lg hover:underline"
                                                           title="{{ __('Tải file ghi chú đính kèm') }}">
                                                            <span class="material-symbols-outlined text-sm">attach_file</span>
                                                            <span>{{ __('File Note') }}</span>
                                                            <span class="material-symbols-outlined text-[10px]">download</span>
                                                        </a>
                                                    @endif

                                                    @if($lesson->note_content)
                                                        <button type="button"
                                                                @click="viewNoteContent('{{ addslashes($lesson->title) }}', '{{ addslashes($lesson->note_content) }}')"
                                                                class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-300 font-semibold bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2.5 py-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                                                                title="{{ __('Xem nội dung ghi chú') }}">
                                                            <span class="material-symbols-outlined text-sm">notes</span>
                                                            <span>{{ __('Ghi chú') }}</span>
                                                        </button>
                                                    @endif

                                                    @if(!$lesson->effective_record_url && !$lesson->pdf_path && !$lesson->note_file_path && !$lesson->note_content)
                                                        <span class="text-slate-400 italic text-[11px]">{{ __('Chưa có tài nguyên đính kèm') }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-1.5">

                                                    <button type="button"
                                                            @click="openEditLessonModal({{ json_encode($lesson) }})"
                                                            class="size-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-primary hover:bg-primary/10 dark:text-slate-400 dark:hover:text-primary dark:hover:bg-primary/10 transition-colors cursor-pointer"
                                                            title="{{ __('Chỉnh sửa bài học & tài nguyên') }}">
                                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                                    </button>

                                                    <button type="button"
                                                            @click="confirmDeleteLesson({{ $lesson->id }}, '{{ addslashes($lesson->title) }}')"
                                                            class="size-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-rose-50 dark:text-slate-400 dark:hover:text-rose-400 dark:hover:bg-rose-950/50 transition-colors cursor-pointer"
                                                            title="{{ __('Xóa bài học') }}">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium space-y-2">
                                                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">menu_book</span>
                                                <p>{{ __('Lớp học chưa có bài học nào.') }}</p>
                                                <button type="button" @click="openCreateLessonModal()" class="mt-3 text-xs text-primary hover:underline font-bold">
                                                    {{ __('+ Tạo bài học đầu tiên ngay bây giờ') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'students'" x-transition class="space-y-5">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                        <div class="relative w-full sm:w-80">
                            <form action="{{ url()->current() }}" method="GET" class="relative">
                                <input type="hidden" name="tab" value="students">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                                <input type="text" name="search" value="{{ $search }}"
                                       placeholder="{{ __('Tìm kiếm tên, email học viên...') }}"
                                       class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-xs text-slate-900 dark:text-white">
                            </form>
                        </div>

                        <div class="flex items-center gap-3 self-end sm:self-auto">
                            <button type="button" @click="openAddStudentModal()"
                                    class="px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 shadow-sm cursor-pointer active:scale-[0.98]">
                                <span class="material-symbols-outlined text-lg">person_add</span>
                                <span>{{ __('Thêm học viên vào lớp') }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50/60 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Học viên') }}</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Cấp độ') }}</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Ngày tham gia') }}</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">{{ __('Thao tác') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse($enrollments as $enrollment)
                                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">

                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $enrollment->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($enrollment->user->full_name) }}"
                                                         alt="{{ $enrollment->user->full_name }}"
                                                         class="size-10 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 bg-slate-100">
                                                    <div class="min-w-0">
                                                        <p class="font-bold text-sm text-slate-900 dark:text-white truncate">
                                                            {{ $enrollment->user->full_name }}
                                                        </p>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                                            {{ $enrollment->user->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <span class="px-2.5 py-1 rounded-lg bg-primary/10 border border-primary/20 text-primary text-xs font-bold">
                                                    {{ $enrollment->user->level_badge ?? 'Lv.1' }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                                {{ $enrollment->created_at ? $enrollment->created_at->format('d/m/Y H:i') : __('N/A') }}
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-1.5">

                                                    <button type="button"
                                                            @click="confirmDeleteStudent({{ $enrollment->id }}, '{{ addslashes($enrollment->user->full_name) }}')"
                                                            class="size-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-rose-50 dark:text-slate-400 dark:hover:text-rose-400 dark:hover:bg-rose-950/50 transition-colors cursor-pointer"
                                                            title="{{ __('Xóa học viên khỏi lớp') }}">
                                                        <span class="material-symbols-outlined text-[18px]">person_remove</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">
                                                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">group_off</span>
                                                <p>{{ __('Lớp học chưa có học viên nào.') }}</p>
                                                <button type="button" @click="openAddStudentModal()" class="mt-3 text-xs text-primary hover:underline font-bold">
                                                    {{ __('+ Thêm học viên đầu tiên') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($enrollments->hasPages())
                            <div class="p-5 border-t border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40">
                                {{ $enrollments->appends(['search' => $search, 'tab' => 'students'])->links('components.pagination') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <div x-show="showAddStudentModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-cloak>

            <div @click.away="showAddStudentModal = false"
                 class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 space-y-0">

                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/60 dark:bg-slate-800/40">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                            <span class="material-symbols-outlined text-xl">person_add</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                {{ __('Thêm học viên vào lớp') }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $class->title }}
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="showAddStudentModal = false"
                            class="size-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <form action="{{ route('teacher.classes.enroll', $class->id) }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <template x-for="id in selectedStudentIds" :key="id">
                        <input type="hidden" name="user_ids[]" :value="id">
                    </template>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            {{ __('Tìm kiếm học viên') }}
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                            <input type="text"
                                   x-model="studentSearch"
                                   @input="onStudentSearchInput()"
                                   placeholder="{{ __('Nhập tên hoặc email học viên...') }}"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-slate-900 dark:text-white">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <span>{{ __('Chọn học viên khả dụng') }}</span>
                                <span x-show="availableStudents.length > 0" class="text-[11px] font-semibold text-slate-400" x-text="'(' + availableStudents.length + ')'"></span>
                            </label>

                            <div class="flex items-center gap-3">
                                <span x-show="isLoadingStudents" class="text-primary text-[11px] font-normal flex items-center gap-1">
                                    <span class="animate-spin size-3 border-2 border-primary border-t-transparent rounded-full"></span>
                                    {{ __('Đang tải...') }}
                                </span>

                                <button type="button"
                                        x-show="availableStudents.length > 0"
                                        @click="toggleSelectAll()"
                                        class="text-xs font-semibold text-primary hover:underline cursor-pointer"
                                        x-text="isAllSelected() ? '{{ __('Bỏ chọn tất cả') }}' : '{{ __('Chọn tất cả') }}'">
                                </button>
                            </div>
                        </div>

                        <div class="max-h-60 overflow-y-auto rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 divide-y divide-slate-200 dark:divide-slate-700">
                            <template x-for="student in availableStudents" :key="student.id">
                                <div @click="toggleStudent(student.id)"
                                     class="p-3.5 flex items-center justify-between gap-3 cursor-pointer transition-colors"
                                     :class="isStudentSelected(student.id) ? 'bg-primary/10 border-l-4 border-l-primary' : 'hover:bg-white dark:hover:bg-slate-800'">

                                    <div class="flex items-center gap-3 min-w-0">

                                        <div class="size-5 rounded-md border flex items-center justify-center transition-colors shrink-0"
                                             :class="isStudentSelected(student.id) ? 'bg-primary border-primary text-white' : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800'">
                                            <span class="material-symbols-outlined text-sm font-bold" x-show="isStudentSelected(student.id)">check</span>
                                        </div>

                                        <img :src="student.avatar_url" class="size-9 rounded-xl object-cover bg-slate-100 shrink-0">

                                        <div class="min-w-0">
                                            <p class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate" x-text="student.name"></p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate" x-text="student.email"></p>
                                        </div>
                                    </div>

                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-primary text-[10px] font-bold" x-text="student.level"></span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!isLoadingStudents && availableStudents.length === 0">
                                <div class="p-6 text-center text-xs text-slate-400 font-medium">
                                    {{ __('Không tìm thấy học viên khả dụng hoặc tất cả đã tham gia lớp.') }}
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="selectedStudentIds.length > 0"
                         x-transition
                         class="p-3 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-between text-xs text-primary font-bold">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">checklist</span>
                            <span>{{ __('Đã chọn:') }} <strong class="text-slate-900 dark:text-white" x-text="selectedStudentIds.length"></strong> {{ __('học viên') }}</span>
                        </div>
                        <button type="button" @click="selectedStudentIds = []" class="text-xs font-semibold text-slate-500 hover:text-rose-500 transition-colors cursor-pointer">
                            {{ __('Xóa chọn') }}
                        </button>
                    </div>

                    @error('user_ids')
                        <p class="text-xs text-rose-500 font-semibold flex items-center gap-1.5 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900">
                            <span class="material-symbols-outlined text-base">error</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror

                    @error('user_id')
                        <p class="text-xs text-rose-500 font-semibold flex items-center gap-1.5 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900">
                            <span class="material-symbols-outlined text-base">error</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror

                    <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddStudentModal = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                            {{ __('Hủy') }}
                        </button>
                        <button type="submit"
                                :disabled="selectedStudentIds.length === 0"
                                class="px-5 py-2.5 bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-1.5 active:scale-[0.98]">
                            <span class="material-symbols-outlined text-base">person_add</span>
                            <span x-text="selectedStudentIds.length > 1 ? `{{ __('Xác nhận thêm') }} (${selectedStudentIds.length}) {{ __('học viên') }}` : `{{ __('Xác nhận thêm vào lớp') }}`"></span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <div x-show="showLessonModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-cloak>

            <div @click.away="showLessonModal = false"
                 class="bg-white dark:bg-slate-900 w-full max-w-xl max-h-[90vh] rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col">

                <div class="p-5 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/80 dark:bg-slate-800/40 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                            <span class="material-symbols-outlined text-xl" x-text="lessonModalMode === 'create' ? 'add_circle' : 'edit_note'"></span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white"
                                x-text="lessonModalMode === 'create' ? '{{ __('Thêm bài học mới') }}' : '{{ __('Chỉnh sửa bài học & Tài nguyên') }}'">
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $class->title }}
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="showLessonModal = false"
                            class="size-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <form :action="lessonForm.action_url" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <input type="hidden" name="_method" :value="lessonModalMode === 'edit' ? 'PUT' : 'POST'">
                    <input type="hidden" name="action_url" :value="lessonForm.action_url">
                    <input type="hidden" name="id" :value="lessonForm.id">

                    <div class="p-5 sm:p-6 space-y-4 overflow-y-auto flex-1">

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center justify-between">
                                <span class="flex items-center gap-1">
                                    <span>{{ __('Tiêu đề bài học') }}</span>
                                    <span class="text-rose-500">*</span>
                                </span>
                            </label>
                            <input type="text"
                                   name="title"
                                   x-model="lessonForm.title"
                                   required
                                   placeholder="{{ __('Ví dụ: Bài 1 - Chào hỏi và làm quen...') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('title') ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200 dark:border-slate-700' }} bg-slate-50 dark:bg-slate-800/80 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-xs sm:text-sm font-medium text-slate-900 dark:text-white transition-all">
                            @error('title')
                                <p class="text-xs text-rose-500 font-medium flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-rose-500">videocam</span>
                                    <span>{{ __('Link Record buổi học (Video URL)') }}</span>
                                </span>
                                <span class="text-[11px] font-normal text-slate-400">{{ __('Zoom / Drive / Youtube') }}</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">link</span>
                                <input type="url"
                                       name="record_url"
                                       x-model="lessonForm.record_url"
                                       placeholder="https://drive.google.com/... hoặc https://youtube.com/..."
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('record_url') ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200 dark:border-slate-700' }} bg-slate-50 dark:bg-slate-800/80 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-xs sm:text-sm font-medium text-slate-900 dark:text-white transition-all">
                            </div>
                            @error('record_url')
                                <p class="text-xs text-rose-500 font-medium flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-blue-500">picture_as_pdf</span>
                                    <span>{{ __('Tài liệu PDF bài giảng (Slide / Giáo trình)') }}</span>
                                </span>
                                <span class="text-[11px] font-normal text-slate-400">PDF (tối đa 20MB)</span>
                            </label>

                            <template x-if="lessonForm.pdf_path">
                                <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 flex items-center justify-between text-xs text-blue-600 dark:text-blue-400 font-medium">
                                    <a :href="lessonForm.pdf_url" target="_blank" class="flex items-center gap-1.5 hover:underline truncate max-w-[280px]">
                                        <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                                        <span>{{ __('Xem file PDF hiện tại') }}</span>
                                    </a>
                                    <label class="flex items-center gap-1 text-[11px] text-rose-500 font-semibold cursor-pointer">
                                        <input type="checkbox" name="remove_pdf" value="1" class="rounded text-rose-500 focus:ring-rose-400 size-3.5">
                                        <span>{{ __('Xóa file') }}</span>
                                    </label>
                                </div>
                            </template>

                            <input type="file"
                                   name="pdf_file"
                                   accept=".pdf"
                                   @change="handlePdfChange($event)"
                                   class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer border {{ $errors->has('pdf_file') ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200 dark:border-slate-700' }} rounded-xl p-1 bg-slate-50 dark:bg-slate-800/80">

                            <p x-show="clientErrors.pdf_file" x-text="clientErrors.pdf_file" class="text-xs text-rose-500 font-semibold flex items-center gap-1 mt-1" x-cloak></p>

                            @error('pdf_file')
                                <p class="text-xs text-rose-500 font-medium flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-amber-500">attach_file</span>
                                    <span>{{ __('File Note / Tài liệu bổ trợ đính kèm') }}</span>
                                </span>
                                <span class="text-[11px] font-normal text-slate-400">Doc / Txt / Zip (tối đa 20MB)</span>
                            </label>

                            <template x-if="lessonForm.note_file_path">
                                <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 flex items-center justify-between text-xs text-amber-600 dark:text-amber-400 font-medium">
                                    <a :href="lessonForm.note_file_url" target="_blank" class="flex items-center gap-1.5 hover:underline truncate max-w-[280px]">
                                        <span class="material-symbols-outlined text-base">attach_file</span>
                                        <span>{{ __('Tải file Note hiện tại') }}</span>
                                    </a>
                                    <label class="flex items-center gap-1 text-[11px] text-rose-500 font-semibold cursor-pointer">
                                        <input type="checkbox" name="remove_note_file" value="1" class="rounded text-rose-500 focus:ring-rose-400 size-3.5">
                                        <span>{{ __('Xóa file') }}</span>
                                    </label>
                                </div>
                            </template>

                            <input type="file"
                                   name="note_file"
                                   accept=".pdf,.doc,.docx,.txt,.zip,.rar,.ppt,.pptx,.xlsx,.xls"
                                   @change="handleNoteFileChange($event)"
                                   class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 dark:file:bg-amber-950/40 file:text-amber-600 dark:file:text-amber-400 hover:file:bg-amber-100 file:cursor-pointer border {{ $errors->has('note_file') ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200 dark:border-slate-700' }} rounded-xl p-1 bg-slate-50 dark:bg-slate-800/80">

                            <p x-show="clientErrors.note_file" x-text="clientErrors.note_file" class="text-xs text-rose-500 font-semibold flex items-center gap-1 mt-1" x-cloak></p>

                            @error('note_file')
                                <p class="text-xs text-rose-500 font-medium flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-slate-500">notes</span>
                                    <span>{{ __('Ghi chú / Tóm tắt kiến thức bài học (Text Note)') }}</span>
                                </span>
                                <span class="text-[11px] font-normal text-slate-400">{{ __('Tùy chọn') }}</span>
                            </label>
                            <textarea name="note_content"
                                      rows="3"
                                      x-model="lessonForm.note_content"
                                      placeholder="{{ __('Nhập ghi chú quan trọng, dặn dò học viên hoặc các điểm ngữ pháp cần lưu ý...') }}"
                                      class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('note_content') ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200 dark:border-slate-700' }} bg-slate-50 dark:bg-slate-800/80 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-xs sm:text-sm font-medium text-slate-900 dark:text-white transition-all"></textarea>
                            @error('note_content')
                                <p class="text-xs text-rose-500 font-medium flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center justify-between">
                                <span>{{ __('Mô tả tóm tắt') }}</span>
                                <span class="text-[11px] font-normal text-slate-400">{{ __('Tùy chọn') }}</span>
                            </label>
                            <textarea name="description"
                                      rows="2"
                                      x-model="lessonForm.description"
                                      placeholder="{{ __('Mô tả ngắn gọn về mục tiêu của bài học...') }}"
                                      class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('description') ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200 dark:border-slate-700' }} bg-slate-50 dark:bg-slate-800/80 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-xs sm:text-sm font-medium text-slate-900 dark:text-white transition-all"></textarea>
                            @error('description')
                                <p class="text-xs text-rose-500 font-medium flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/60 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" @click="showLessonModal = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                            {{ __('Hủy') }}
                        </button>
                        <button type="submit"
                                :disabled="hasFileErrors()"
                                class="px-5 py-2.5 bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-1.5 active:scale-[0.98]">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <span x-text="lessonModalMode === 'create' ? '{{ __('Lưu bài học') }}' : '{{ __('Cập nhật') }}'"></span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <div x-show="showNoteModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-cloak>

            <div @click.away="showNoteModal = false"
                 class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 p-6 space-y-4">

                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">notes</span>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white truncate max-w-[340px]" x-text="currentNoteView.title"></h3>
                    </div>
                    <button type="button" @click="showNoteModal = false" class="size-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>

                <div class="max-h-72 overflow-y-auto p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs sm:text-sm text-slate-800 dark:text-slate-200 whitespace-pre-line leading-relaxed"
                     x-text="currentNoteView.content">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" @click="showNoteModal = false"
                            class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors cursor-pointer">
                        {{ __('Đóng') }}
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showDeleteLessonModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-cloak>

            <div @click.away="showDeleteLessonModal = false"
                 class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 p-6 space-y-4">

                <div class="size-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-500 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-900">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>

                <div class="text-center space-y-1.5">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">
                        {{ __('Xác nhận xóa bài học') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Bạn có chắc chắn muốn xóa bài học') }} <strong class="text-slate-900 dark:text-white" x-text="lessonToDelete.title"></strong>?
                    </p>
                    <p class="text-[11px] text-rose-500 font-medium">
                        {{ __('Tất cả tài nguyên PDF, file Note đính kèm của bài học này cũng sẽ bị xóa.') }}
                    </p>
                </div>

                <form :action="lessonToDelete.action_url" method="POST" class="pt-2 flex items-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="showDeleteLessonModal = false"
                            class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        {{ __('Hủy bỏ') }}
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer active:scale-[0.98]">
                        {{ __('Xác nhận xóa') }}
                    </button>
                </form>

            </div>
        </div>

        <div x-show="showDeleteConfirmModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-cloak>

            <div @click.away="showDeleteConfirmModal = false"
                 class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 p-6 space-y-4">

                <div class="size-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-500 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-900">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>

                <div class="text-center space-y-1.5">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">
                        {{ __('Xác nhận xóa học viên') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Bạn có chắc chắn muốn xóa học viên') }} <strong class="text-slate-900 dark:text-white" x-text="studentToDelete"></strong> {{ __('khỏi lớp học này?') }}
                    </p>
                </div>

                <form :action="deleteActionUrl" method="POST" class="pt-2 flex items-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="showDeleteConfirmModal = false"
                            class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        {{ __('Hủy bỏ') }}
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer active:scale-[0.98]">
                        {{ __('Xác nhận xóa') }}
                    </button>
                </form>

            </div>
        </div>

        <div x-show="showAnnouncementModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-cloak>
            <div @click.away="showAnnouncementModal = false"
                 class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/60 dark:bg-slate-800/40">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        <span>{{ __('Gửi thông báo cho cả lớp') }}</span>
                    </h3>
                    <button type="button" @click="showAnnouncementModal = false" class="size-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Tiêu đề thông báo') }}</label>
                        <input type="text" placeholder="{{ __('Nhập tiêu đề thông báo...') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-xs sm:text-sm font-medium text-slate-900 dark:text-white">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Nội dung thông báo') }}</label>
                        <textarea rows="4" placeholder="{{ __('Nhập nội dung chi tiết gửi tới toàn bộ học viên...') }}"
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-xs sm:text-sm font-medium text-slate-900 dark:text-white"></textarea>
                    </div>
                </div>
                <div class="p-6 bg-slate-50/60 dark:bg-slate-800/40 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                    <button type="button" @click="showAnnouncementModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        {{ __('Hủy') }}
                    </button>
                    <button type="button" @click="showAnnouncementModal = false; $dispatch('notify', { msg: '{{ __('Thông báo đã được gửi thành công!') }}', type: 'success' })"
                            class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer active:scale-[0.98]">
                        {{ __('Gửi ngay') }}
                    </button>
                </div>
            </div>
        </div>

    </main>
@endsection
