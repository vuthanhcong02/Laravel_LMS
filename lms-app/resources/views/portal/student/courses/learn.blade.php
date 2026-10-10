@extends('portal.layouts.dashboard')

@section('title', ($currentLesson ? $currentLesson->title . ' - ' : '') . $course->title . ' - ' . config('app.name', 'LMS'))

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
@php
    $videoList = $currentLesson?->video_list ?? [];
    $hasMultipleVideos = count($videoList) > 1;
    $embedUrl = $currentLesson?->embed_video_url;

    $sortedLessons = $course->lessons->sortBy('order')->values();
    $currentIndex = $currentLesson ? $sortedLessons->search(fn($l) => $l->id === $currentLesson->id) : false;
    $prevLesson = ($currentIndex !== false && $currentIndex > 0) ? $sortedLessons->get($currentIndex - 1) : null;
    $nextLesson = ($currentIndex !== false && $currentIndex < $sortedLessons->count() - 1) ? $sortedLessons->get($currentIndex + 1) : null;
@endphp

<main class="flex-1 p-6 lg:p-8 overflow-y-auto">
    <div class="max-w-[1400px] mx-auto space-y-6">

                <div class="space-y-2">
            <nav class="flex items-center text-xs text-slate-500 font-medium">
                <a href="{{ route('student.courses.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                    <span>{{ __('Khóa học của bạn') }}</span>
                </a>
                <span class="material-symbols-outlined text-sm mx-1.5 text-slate-400">chevron_right</span>
                <a href="{{ route('student.courses.show', $course->id) }}" class="hover:text-primary transition-colors truncate max-w-[200px]">
                    {{ $course->title }}
                </a>
                @if($currentLesson)
                    <span class="material-symbols-outlined text-sm mx-1.5 text-slate-400">chevron_right</span>
                    <span class="text-slate-700 dark:text-slate-300 truncate font-semibold">{{ $currentLesson->title }}</span>
                @endif
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                        {{ $currentLesson ? $currentLesson->title : $course->title }}
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-slate-400">school</span>
                        <span>{{ $course->title }}</span>
                        @if($course->teacher)
                            <span class="text-slate-300 dark:text-slate-600">•</span>
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm text-slate-400">person</span>
                                <span>{{ $course->teacher->first_name }} {{ $course->teacher->last_name }}</span>
                            </span>
                        @endif
                    </p>
                </div>

                <a href="{{ route('student.courses.show', $course->id) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold transition-colors shadow-xs w-fit">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    <span>{{ __('Quay lại lộ trình') }}</span>
                </a>
            </div>
        </div>

        @if($currentLesson)
                        <div x-data="{ 
                    activeVideoIndex: 0, 
                    videos: {{ json_encode($videoList) }},
                    previewModal: { open: false, url: '', title: '' },
                    openPreview(url, title) {
                        this.previewModal = { open: true, url: url, title: title };
                    },
                    closePreview() {
                        this.previewModal.open = false;
                        this.previewModal.url = '';
                    }
                 }" 
                 class="space-y-6">

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                                <div class="lg:col-span-2 space-y-4">

                                        @if($hasMultipleVideos)
                        <div class="flex items-center justify-between gap-3 bg-white dark:bg-slate-900 p-2 sm:p-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
                            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
                                <template x-for="(vid, idx) in videos" :key="idx">
                                    <button @click="activeVideoIndex = idx" 
                                            :class="activeVideoIndex === idx 
                                                ? 'bg-primary text-white shadow-xs font-bold border-primary' 
                                                : 'bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border-slate-200/80 dark:border-slate-700/60 hover:bg-slate-100 dark:hover:bg-slate-700 font-semibold'"
                                            class="px-3.5 py-2 rounded-xl border text-xs transition-all flex items-center gap-2 shrink-0 cursor-pointer">
                                        <span class="material-symbols-outlined text-sm" :class="activeVideoIndex === idx ? 'text-white' : 'text-primary'">play_circle</span>
                                        <span x-text="vid.title"></span>
                                    </button>
                                </template>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400 shrink-0 px-2 hidden sm:inline" x-text="(activeVideoIndex + 1) + '/' + videos.length + ' {{ __('phần') }}'"></span>
                        </div>
                    @endif

                                        <div class="w-full aspect-video rounded-2xl overflow-hidden bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-sm relative flex items-center justify-center">
                        <template x-if="videos.length > 0 && videos[activeVideoIndex] && videos[activeVideoIndex].embed_url">
                            <iframe :src="videos[activeVideoIndex].embed_url" 
                                    class="w-full h-full" 
                                    frameborder="0" 
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                            </iframe>
                        </template>

                        <template x-if="videos.length === 0 && '{{ $embedUrl }}'">
                            <iframe src="{{ $embedUrl }}" 
                                    class="w-full h-full" 
                                    frameborder="0" 
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                            </iframe>
                        </template>

                        <template x-if="videos.length === 0 && !'{{ $embedUrl }}'">
                            <div class="text-center p-8 space-y-3">
                                <div class="size-14 rounded-2xl bg-slate-800/80 text-primary border border-slate-700 flex items-center justify-center mx-auto shadow-md">
                                    <span class="material-symbols-outlined text-3xl">smart_display</span>
                                </div>
                                <h3 class="text-sm font-bold text-white">{{ __('Chưa có video bài giảng') }}</h3>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto font-medium">
                                    {{ __('Nội dung video bài học này đang được chuẩn bị hoặc cập nhật.') }}
                                </p>
                            </div>
                        </template>
                    </div>

                </div>

                                <div class="space-y-5">

                                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-4">
                        
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-primary/10 text-primary text-xs font-bold tracking-wide shrink-0 border border-primary/20">
                                    {{ __('Bài :order', ['order' => $currentLesson->order ?? ($currentIndex + 1)]) }}
                                </span>
                            </div>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-snug">
                                {{ $currentLesson->title }}
                            </h2>
                        </div>

                                                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
                            @if($prevLesson)
                                <a href="{{ route('student.courses.learn', ['course' => $course->id, 'lesson' => $prevLesson->id]) }}"
                                   class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold transition-colors shadow-xs"
                                   title="{{ $prevLesson->title }}">
                                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                                    <span>{{ __('Bài trước') }}</span>
                                </a>
                            @else
                                <div class="opacity-40 cursor-not-allowed inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-400 text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                                    <span>{{ __('Bài trước') }}</span>
                                </div>
                            @endif

                            @if($nextLesson)
                                <a href="{{ route('student.courses.learn', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}"
                                   class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-all shadow-xs"
                                   title="{{ $nextLesson->title }}">
                                    <span>{{ __('Bài tiếp theo') }}</span>
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            @else
                                <div class="opacity-40 cursor-not-allowed inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 text-xs font-bold">
                                    <span>{{ __('Hết khóa') }}</span>
                                </div>
                            @endif
                        </div>

                                                @if($currentLesson->description)
                            <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm">subject</span>
                                    <span>{{ __('Nội dung bài học') }}</span>
                                </h3>
                                <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50/60 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                    {!! nl2br(e($currentLesson->description)) !!}
                                </div>
                            </div>
                        @endif

                                                @if($currentLesson->note_content)
                            <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm">tips_and_updates</span>
                                    <span>{{ __('Ghi chú từ giáo viên') }}</span>
                                </h3>
                                <div class="bg-amber-50/70 dark:bg-amber-950/20 p-3 rounded-xl border border-amber-200/80 dark:border-amber-800/40 text-xs text-amber-900 dark:text-amber-200 leading-relaxed shadow-xs">
                                    {!! nl2br(e($currentLesson->note_content)) !!}
                                </div>
                            </div>
                        @endif

                    </div>

                                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-3">
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-base">folder_open</span>
                                <span>{{ __('Tài liệu học tập đính kèm') }}</span>
                            </h3>
                        </div>

                        <div class="space-y-2">
                            @php
                                $hasMaterials = false;
                            @endphp

                                                        @if($currentLesson->pdf_path)
                                @php $hasMaterials = true; @endphp
                                <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100/80 dark:hover:bg-slate-800 transition-colors border border-slate-200 dark:border-slate-700/60 group">
                                    <div class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer"
                                         @click="openPreview('{{ $currentLesson->pdf_url }}', '{{ __('Tài liệu bài giảng (PDF)') }}')">
                                        <div class="size-8 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-500 border border-rose-200 dark:border-rose-900/50 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-primary transition-colors">
                                                {{ __('Tài liệu bài giảng (PDF)') }}
                                            </p>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ __('Tài liệu PDF') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" 
                                                @click="openPreview('{{ $currentLesson->pdf_url }}', '{{ __('Tài liệu bài giảng (PDF)') }}')"
                                                class="size-8 inline-flex items-center justify-center rounded-xl text-slate-500 hover:text-primary hover:bg-white dark:hover:bg-slate-700/80 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 hover:shadow-xs cursor-pointer"
                                                title="{{ __('Xem trước') }}">
                                            <span class="material-symbols-outlined text-[18px] leading-none">visibility</span>
                                        </button>
                                        <a href="{{ $currentLesson->pdf_url }}" download target="_blank"
                                           class="size-8 inline-flex items-center justify-center rounded-xl text-slate-500 hover:text-primary hover:bg-white dark:hover:bg-slate-700/80 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 hover:shadow-xs"
                                           title="{{ __('Tải xuống') }}">
                                            <span class="material-symbols-outlined text-[18px] leading-none">download</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                                                        @if($currentLesson->note_file_path)
                                @php $hasMaterials = true; @endphp
                                <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100/80 dark:hover:bg-slate-800 transition-colors border border-slate-200 dark:border-slate-700/60 group">
                                    <div class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer"
                                         @click="openPreview('{{ $currentLesson->note_file_url }}', '{{ __('File bài tập / Ghi chú') }}')">
                                        <div class="size-8 rounded-lg bg-blue-50 dark:bg-blue-950/30 text-primary border border-blue-200 dark:border-blue-900/50 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-base">description</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-primary transition-colors">
                                                {{ __('File bài tập / Ghi chú') }}
                                            </p>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ __('Tài liệu đính kèm') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" 
                                                @click="openPreview('{{ $currentLesson->note_file_url }}', '{{ __('File bài tập / Ghi chú') }}')"
                                                class="size-8 inline-flex items-center justify-center rounded-xl text-slate-500 hover:text-primary hover:bg-white dark:hover:bg-slate-700/80 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 hover:shadow-xs cursor-pointer"
                                                title="{{ __('Xem trước') }}">
                                            <span class="material-symbols-outlined text-[18px] leading-none">visibility</span>
                                        </button>
                                        <a href="{{ $currentLesson->note_file_url }}" download target="_blank"
                                           class="size-8 inline-flex items-center justify-center rounded-xl text-slate-500 hover:text-primary hover:bg-white dark:hover:bg-slate-700/80 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 hover:shadow-xs"
                                           title="{{ __('Tải xuống') }}">
                                            <span class="material-symbols-outlined text-[18px] leading-none">download</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                                                        @if($currentLesson->assignments && $currentLesson->assignments->isNotEmpty())
                                @php $hasMaterials = true; @endphp
                                @foreach($currentLesson->assignments as $assignment)
                                    <a href="{{ route('student.assignments.index', ['open' => $assignment->id, 'lesson_id' => $assignment->lesson_id]) }}" 
                                       class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border border-slate-200 dark:border-slate-700/60 group">
                                        <div class="size-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 border border-amber-200 dark:border-amber-900/50 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-base">assignment</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-primary transition-colors">
                                                {{ $assignment->title }}
                                            </p>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ __('Bài tập thực hành') }}</span>
                                        </div>
                                        <span class="material-symbols-outlined text-sm text-slate-400 group-hover:text-primary transition-colors">arrow_forward</span>
                                    </a>
                                @endforeach
                            @endif

                            @if(!$hasMaterials)
                                <div class="py-6 text-center text-slate-400 space-y-1">
                                    <span class="material-symbols-outlined text-2xl text-slate-300 dark:text-slate-600">inventory_2</span>
                                    <p class="text-xs font-medium">{{ __('Chưa có tài liệu đính kèm cho bài này') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                </div>

                                <div x-show="previewModal.open" 
                     x-cloak 
                     @keydown.escape.window="closePreview()"
                     class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-3 sm:p-6 bg-slate-950/75 backdrop-blur-xs transition-opacity">
                    <div @click.away="closePreview()" 
                         class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                        
                                                <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-850">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="material-symbols-outlined text-primary text-xl">visibility</span>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate" x-text="previewModal.title"></h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <a :href="previewModal.url" target="_blank" 
                                   class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors"
                                   title="{{ __('Mở trong tab mới') }}">
                                    <span class="material-symbols-outlined text-base">open_in_new</span>
                                </a>
                                <a :href="previewModal.url" download 
                                   class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors"
                                   title="{{ __('Tải xuống') }}">
                                    <span class="material-symbols-outlined text-base">download</span>
                                </a>
                                <button type="button" @click="closePreview()" 
                                        class="p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 text-slate-400 hover:text-rose-500 transition-colors cursor-pointer"
                                        title="{{ __('Đóng') }}">
                                    <span class="material-symbols-outlined text-lg">close</span>
                                </button>
                            </div>
                        </div>

                                                <div class="flex-1 bg-slate-100 dark:bg-slate-950 relative overflow-hidden flex flex-col">
                            <template x-if="previewModal.open && previewModal.url">
                                <object :data="previewModal.url" type="application/pdf" class="w-full h-full flex-1">
                                    <iframe :src="previewModal.url" 
                                            class="w-full h-full border-0 flex-1" 
                                            frameborder="0">
                                        <div class="flex flex-col items-center justify-center h-full p-8 text-center space-y-3">
                                            <span class="material-symbols-outlined text-4xl text-slate-400">picture_as_pdf</span>
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                                {{ __('Trình duyệt không hỗ trợ xem trực tiếp trong khung này.') }}
                                            </p>
                                            <a :href="previewModal.url" target="_blank" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold shadow-xs flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                                                <span>{{ __('Mở tài liệu trong tab mới') }}</span>
                                            </a>
                                        </div>
                                    </iframe>
                                </object>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-sm space-y-2">
                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">inventory_2</span>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Chưa có bài học nào') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">{{ __('Khóa học này hiện chưa có bài học nào được đăng tải.') }}</p>
            </div>
        @endif

    </div>
</main>
@endsection
