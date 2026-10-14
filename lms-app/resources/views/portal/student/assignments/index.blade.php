@extends('portal.layouts.dashboard')

@section('title', __('Bài tập của tôi'))

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
        <div class="max-w-[1400px] mx-auto space-y-8">
            <x-portal.page-header
                :title="__('Bài tập của bạn')"
                :description="__('Quản lý bài thực hành theo từng bài học, nộp bài ghi âm/tệp tin và nhận xét từ giáo viên.')"
            />

            <x-flash-message type="success" />
            <x-flash-message type="error" />

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 space-y-1">
                    <div class="flex items-center gap-2 font-bold text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-lg">error</span>
                        <span>{{ __('Đã có lỗi xảy ra khi gửi bài làm:') }}</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 ml-1 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($courses->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-sm space-y-3">
                    <div class="size-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto">
                        <span class="material-symbols-outlined text-3xl">assignment_late</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">{{ __('Chưa có bài tập nào!') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                        {{ __('Hiện tại các khóa học bạn tham gia chưa có bài tập nào được giao hoặc chưa được mở.') }}
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($courses as $course)
                        @foreach($course->lessons as $lesson)
                                    @php
                                        $lessonAssignments = $lesson->assignments;
                                        if ($lessonAssignments->isEmpty()) {
                                            continue;
                                        }

                                        $lessonTotal = $lessonAssignments->count();
                                        $lessonSubmitted = $lessonAssignments->filter(fn($a) => $a->submissions->isNotEmpty())->count();
                                        $lessonPending = $lessonTotal - $lessonSubmitted;
                                        $isLessonCompleted = ($lessonTotal > 0 && $lessonSubmitted === $lessonTotal);

                                        $shouldOpenLesson = ($openLessonId === $lesson->id || $lessonAssignments->contains('id', $openAssignmentId));
                                    @endphp

                                    <div x-data="{ lessonOpen: {{ $shouldOpenLesson ? 'true' : 'false' }} }"
                                         id="lesson-{{ $lesson->id }}"
                                         class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-200 hover:border-primary/40">
                                        
                                                                                <button @click="lessonOpen = !lessonOpen"
                                                type="button"
                                                class="w-full text-left p-4 sm:p-5 flex items-center justify-between gap-4 bg-transparent hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer select-none">
                                            
                                            <div class="flex items-center gap-3.5 min-w-0">
                                                <div class="size-10 rounded-xl flex items-center justify-center shrink-0 border {{ $isLessonCompleted ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 border-emerald-200 dark:border-emerald-800' : 'bg-primary/10 text-primary border-primary/20' }}">
                                                    <span class="material-symbols-outlined text-xl">
                                                        {{ $isLessonCompleted ? 'check_circle' : 'menu_book' }}
                                                    </span>
                                                </div>

                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-primary">
                                                            {{ __('Bài :order', ['order' => $lesson->order ?: $loop->iteration]) }}
                                                        </span>
                                                        <span class="text-slate-300 dark:text-slate-600">•</span>
                                                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">
                                                            {{ __(':count bài tập', ['count' => $lessonTotal]) }}
                                                        </span>
                                                    </div>
                                                    <h3 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate mt-0.5">
                                                        {{ $lesson->title }}
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3 shrink-0">
                                                @if($isLessonCompleted)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-[11px] font-bold">
                                                        <span class="material-symbols-outlined text-[13px]">verified</span>
                                                        <span>{{ __('Đã hoàn thành') }}</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 text-[11px] font-bold">
                                                        <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                        <span>{{ __(':count chưa nộp', ['count' => $lessonPending]) }}</span>
                                                    </span>
                                                @endif

                                                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200 text-lg"
                                                      :class="lessonOpen ? 'rotate-180 text-primary' : ''">
                                                    expand_more
                                                </span>
                                            </div>
                                        </button>

                                                                                <div x-show="lessonOpen" x-collapse class="border-t border-slate-100 dark:border-slate-800/80 p-4 sm:p-5 space-y-4 bg-slate-50/60 dark:bg-slate-950/30">
                                            @foreach($lessonAssignments as $assignment)
                                                @php
                                                    $submission  = $assignment->submissions->first();
                                                    $isGraded    = $submission && $submission->status === \App\Models\AssignmentSubmission::STATUS_GRADED;
                                                    $isSubmitted = $submission && $submission->status === \App\Models\AssignmentSubmission::STATUS_SUBMITTED;
                                                    $isPastDue   = $assignment->due_date && $assignment->due_date->isPast();
                                                    $isOverdue   = $isPastDue && !$isGraded && !$isSubmitted;
                                                    $shouldOpenAssignment = ($openAssignmentId === $assignment->id);
                                                @endphp

                                                <div x-data="{ open: {{ $shouldOpenAssignment ? 'true' : 'false' }} }"
                                                     id="assignment-{{ $assignment->id }}"
                                                     class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden transition-all duration-200 hover:border-primary/40">
                                                    
                                                    <button @click="open = !open"
                                                            type="button"
                                                            class="w-full text-left p-3.5 sm:p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-transparent hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer select-none">
                                                        <div class="flex items-center gap-3 min-w-0">
                                                            <div class="size-8 rounded-lg flex items-center justify-center shrink-0 border
                                                                {{ $isGraded ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 border-emerald-200 dark:border-emerald-800' : ($isSubmitted ? 'bg-blue-50 dark:bg-blue-950/30 text-primary border-blue-200 dark:border-blue-900/50' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700') }}">
                                                                <span class="material-symbols-outlined text-base">
                                                                    {{ $isGraded ? 'verified' : ($isSubmitted ? 'cloud_done' : 'assignment') }}
                                                                </span>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-1">{{ $assignment->title }}</h4>
                                                                <div class="flex items-center gap-2 mt-0.5">
                                                                    @if($assignment->due_date)
                                                                        <span class="text-[11px] font-medium flex items-center gap-1 {{ $isOverdue ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-slate-400 dark:text-slate-500' }}">
                                                                            <span class="material-symbols-outlined text-[13px]">calendar_month</span>
                                                                            {{ __('Hạn nộp: :date', ['date' => $assignment->due_date->format('d/m/Y H:i')]) }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-[11px] text-slate-400 font-medium">{{ __('Không giới hạn thời gian') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2.5 shrink-0 self-end md:self-center">
                                                            <div class="flex flex-col items-end">
                                                                @if($isGraded)
                                                                    <div class="text-right flex items-baseline gap-0.5">
                                                                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ (float) $submission->score }}</span>
                                                                        <span class="text-[11px] font-semibold text-slate-400">/10</span>
                                                                    </div>
                                                                @elseif($isSubmitted)
                                                                    <span class="px-2 py-0.5 text-[11px] font-semibold text-primary bg-primary/10 rounded-md border border-primary/20">{{ __('Đã nộp') }}</span>
                                                                @else
                                                                    <span class="px-2 py-0.5 text-[11px] font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30 rounded-md border border-amber-200 dark:border-amber-900/50">{{ __('Chưa nộp') }}</span>
                                                                @endif
                                                            </div>
                                                            <span class="material-symbols-outlined text-slate-400 transition-transform duration-200 text-base" :class="open ? 'rotate-180 text-primary' : '' ">expand_more</span>
                                                        </div>
                                                    </button>

                                                                                                        <div x-show="open" x-collapse class="border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                                                        <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

                                                            <div class="space-y-4">
                                                                <div>
                                                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 block">{{ __('Hướng dẫn từ giáo viên') }}</label>
                                                                    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed shadow-2xs">
                                                                        {!! nl2br(e($assignment->description)) !!}

                                                                        @if(!empty($assignment->attachments))
                                                                            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                                                                <x-lms.attachment-list :attachments="$assignment->attachments" variant="badges" :title="__('Tài liệu đính kèm:')" />
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                @if($isGraded)
                                                                    <div class="space-y-2">
                                                                        <label class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1 block">{{ __('Nhận xét & Góp ý của giáo viên') }}</label>
                                                                        <div class="bg-emerald-50/70 dark:bg-emerald-950/20 p-4 rounded-xl border border-emerald-200 dark:border-emerald-800/60 space-y-3">
                                                                            @if($submission->teacher_audio_path)
                                                                                <div class="bg-white dark:bg-slate-900 p-3 rounded-lg flex items-center gap-3 border border-emerald-200 dark:border-emerald-800 shadow-2xs">
                                                                                    <div class="size-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                                                                        <span class="material-symbols-outlined text-base">graphic_eq</span>
                                                                                    </div>
                                                                                    <audio src="{{ assignmentFileUrl($submission->teacher_audio_path) }}" controls class="h-7 flex-1"></audio>
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($submission->teacher_attachments))
                                                                                <div class="bg-white dark:bg-slate-900 p-3 rounded-lg border border-emerald-200 dark:border-emerald-800 shadow-2xs">
                                                                                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2">{{ __('Tệp đính kèm từ giáo viên:') }}</p>
                                                                                    <x-lms.attachment-list :attachments="$submission->teacher_attachments" variant="badges" />
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($submission->teacher_feedback))
                                                                                <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                                                                                    {{ $submission->teacher_feedback }}
                                                                                </p>
                                                                            @elseif(!$submission->teacher_audio_path && empty($submission->teacher_attachments))
                                                                                <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                                                                                    {{ __('Giáo viên không để lại nhận xét văn bản.') }}
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                             </div>

                                                             <div class="space-y-4">
                                                                 @if($isGraded)
                                                                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col items-center justify-center text-center">
                                                                        <div class="size-14 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl flex items-center justify-center text-emerald-500 mb-3 border border-emerald-200 dark:border-emerald-800">
                                                                            <span class="material-symbols-outlined text-3xl">verified</span>
                                                                        </div>
                                                                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ (float) $submission->score }}<span class="text-xs text-slate-400">/10</span></h3>
                                                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __('Bài làm đã hoàn thành tuyệt vời!') }}</p>
                                                                    </div>

                                                                    @if(!empty($submission->attachments))
                                                                        <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                                                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                                                                                <span class="material-symbols-outlined text-primary text-base">task_alt</span>
                                                                                <span>{{ __('Bài làm bạn đã nộp') }}</span>
                                                                                <span class="text-slate-400 text-[11px] font-normal">({{ count($submission->attachments) }})</span>
                                                                            </h4>

                                                                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700/60 space-y-2">
                                                                                <div class="flex flex-wrap gap-2">
                                                                                    @foreach($submission->attachments as $att)
                                                                                        @php
                                                                                            $isAudio = preg_match('/\.(webm|mp3|wav|ogg|m4a|weba|aac)$/i', $att['name'] ?? '') || preg_match('/\.(webm|mp3|wav|ogg|m4a|weba|aac)$/i', $att['path'] ?? '');
                                                                                            $attViewerUrl = assignmentFileUrl($att['path']);
                                                                                        @endphp
                                                                                        @if($isAudio)
                                                                                            <div class="w-full flex items-center gap-2.5 p-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                                                                                <span class="material-symbols-outlined text-primary text-base">mic</span>
                                                                                                <audio src="{{ $attViewerUrl }}" controls class="h-7 flex-1"></audio>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white dark:bg-slate-900 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-primary/50 transition-colors">
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="flex items-center gap-1 text-slate-800 dark:text-white hover:text-primary text-left cursor-pointer font-bold">
                                                                                                    <span class="material-symbols-outlined text-sm text-primary">description</span>
                                                                                                    <span class="truncate max-w-[130px] sm:max-w-[180px]">{{ $att['name'] }}</span>
                                                                                                </button>
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="size-6 rounded hover:bg-primary/10 text-slate-400 hover:text-primary flex items-center justify-center cursor-pointer transition-colors"
                                                                                                        title="{{ __('Xem trước') }}">
                                                                                                    <span class="material-symbols-outlined text-[13px]">visibility</span>
                                                                                                </button>
                                                                                                <a href="{{ $attViewerUrl }}" target="_blank" download class="size-6 rounded hover:bg-primary/10 text-slate-400 hover:text-primary flex items-center justify-center transition-colors" title="{{ __('Tải về') }}">
                                                                                                    <span class="material-symbols-outlined text-[13px]">download</span>
                                                                                                </a>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @elseif($isPastDue && !$isSubmitted)
                                                                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col items-center justify-center text-center">
                                                                        <div class="size-14 bg-rose-50 dark:bg-rose-950/40 rounded-xl flex items-center justify-center text-rose-500 mb-3 border border-rose-200 dark:border-rose-800">
                                                                            <span class="material-symbols-outlined text-3xl">running_with_errors</span>
                                                                        </div>
                                                                        <h3 class="text-base font-bold text-rose-600">{{ __('Hết hạn nộp bài') }}</h3>
                                                                        <p class="text-xs text-slate-500 mt-1">{{ __('Vui lòng liên hệ giáo viên để được hỗ trợ') }}</p>
                                                                    </div>
                                                                @elseif($isPastDue && $isSubmitted)
                                                                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                                                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                                                                            <span class="material-symbols-outlined text-emerald-500 text-base">check_circle</span>
                                                                            <span>{{ __('Bài làm đã nộp (Đã hết hạn chỉnh sửa)') }}</span>
                                                                        </h4>

                                                                        @if(!empty($submission->attachments))
                                                                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700/60 mb-3 space-y-2">
                                                                                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Tệp/Ghi âm đã nộp:') }}</p>
                                                                                <div class="flex flex-wrap gap-2">
                                                                                    @foreach($submission->attachments as $att)
                                                                                        @php
                                                                                            $isAudio = preg_match('/\.(webm|mp3|wav|ogg|m4a|weba)$/i', $att['name'] ?? '') || preg_match('/\.(webm|mp3|wav|ogg|m4a|weba)$/i', $att['path'] ?? '');
                                                                                            $attViewerUrl = assignmentFileUrl($att['path']);
                                                                                        @endphp
                                                                                        @if($isAudio)
                                                                                            <div class="w-full flex items-center gap-2 p-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                                                                                <span class="material-symbols-outlined text-primary text-base">mic</span>
                                                                                                <audio src="{{ $attViewerUrl }}" controls class="h-7 flex-1"></audio>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-white dark:bg-slate-900 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-primary">
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="flex items-center gap-1 text-slate-800 dark:text-white hover:text-primary text-left">
                                                                                                    <span class="material-symbols-outlined text-sm text-primary">description</span>
                                                                                                    <span class="truncate max-w-[140px]">{{ $att['name'] }}</span>
                                                                                                </button>
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="size-5 rounded hover:bg-primary/10 text-slate-400 hover:text-primary flex items-center justify-center ml-1"
                                                                                                        title="{{ __('Xem trước') }}">
                                                                                                    <span class="material-symbols-outlined text-[13px]">visibility</span>
                                                                                                </button>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                                                            {{ __('Bài làm của bạn đang chờ giáo viên chấm điểm.') }}
                                                                        </p>
                                                                    </div>
                                                                @else
                                                                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                                                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                                                                            <span class="material-symbols-outlined text-primary text-base">cloud_upload</span>
                                                                            <span>{{ $isSubmitted ? __('Cập nhật bài làm') : __('Nộp bài ngay') }}</span>
                                                                        </h4>

                                                                        <form action="{{ route('student.assignments.submit', $assignment->id) }}"
                                                                              method="POST"
                                                                              enctype="multipart/form-data"
                                                                              x-data="{ 
                                                                                  isSubmitting: false,
                                                                                  existingFiles: {{ json_encode(array_values($submission->attachments ?? [])) }}
                                                                              }"
                                                                              @submit="if (isSubmitting) { $event.preventDefault(); return false; } isSubmitting = true"
                                                                              class="space-y-4">
                                                                            @csrf

                                                                            @if($isSubmitted && !empty($submission->attachments))
                                                                                <div class="p-3 bg-blue-50/70 dark:bg-blue-950/20 rounded-xl border border-blue-200 dark:border-blue-900/50 mb-3 space-y-2">
                                                                                    <div class="flex items-center justify-between">
                                                                                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider">{{ __('Tệp / Ghi âm đã nộp:') }}</p>
                                                                                        <span class="text-[10px] text-slate-400 font-medium">{{ __('(Bấm X đỏ để gỡ file)') }}</span>
                                                                                    </div>
                                                                                    <div class="flex flex-wrap gap-2">
                                                                                        <template x-for="(att, aIdx) in existingFiles" :key="att.path">
                                                                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-slate-900 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                                                                <input type="hidden" name="existing_attachments[]" :value="att.path">
                                                                                                <template x-if="att.name && att.name.match(/\.(webm|mp3|wav|ogg|m4a|weba)$/i)">
                                                                                                    <span class="material-symbols-outlined text-primary text-sm">mic</span>
                                                                                                </template>
                                                                                                <template x-if="!att.name || !att.name.match(/\.(webm|mp3|wav|ogg|m4a|weba)$/i)">
                                                                                                    <span class="material-symbols-outlined text-primary text-sm">description</span>
                                                                                                </template>
                                                                                                <span class="truncate max-w-[130px]" x-text="att.name"></span>
                                                                                                <button type="button" @click="existingFiles.splice(aIdx, 1)"
                                                                                                        class="size-4.5 rounded-full bg-red-50 hover:bg-red-500 hover:text-white text-red-500 flex items-center justify-center transition-colors ml-1 cursor-pointer"
                                                                                                        title="{{ __('Gỡ bỏ tệp này') }}">
                                                                                                    <span class="material-symbols-outlined text-[12px]">close</span>
                                                                                                </button>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                    <p x-show="existingFiles.length === 0" class="text-[11px] text-amber-600 dark:text-amber-400 italic">
                                                                                        {{ __('Bạn đã gỡ hết các file cũ. Vui lòng ghi âm hoặc đính kèm tệp mới bên dưới.') }}
                                                                                    </p>
                                                                                </div>
                                                                            @endif

                                                                            <div class="space-y-3">
                                                                                <div>
                                                                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1 mb-1.5">
                                                                                        <span class="material-symbols-outlined text-primary text-base">mic</span>
                                                                                        <span>{{ __('1. Ghi âm giọng nói (nếu có)') }}</span>
                                                                                    </label>
                                                                                    <x-audio-recorder name="audio_file" />
                                                                                </div>

                                                                                <div class="pt-1">
                                                                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1 mb-1.5">
                                                                                        <span class="material-symbols-outlined text-primary text-base">attach_file</span>
                                                                                        <span>{{ __('2. Đính kèm tệp tin / hình ảnh bài làm (nếu có)') }}</span>
                                                                                    </label>
                                                                                    <x-lms.file-uploader
                                                                                        name="attachments[]"
                                                                                        :compact="true"
                                                                                        :maxFiles="5"
                                                                                        :maxSizeMB="20"
                                                                                        :label="null"
                                                                                        :helperText="__('Hỗ trợ: PDF, Word, Excel, Ảnh, Audio, Zip... (Có thể gửi kèm cả ghi âm và tệp tin cùng lúc)')" />
                                                                                </div>
                                                                            </div>

                                                                            <button type="submit"
                                                                                    :disabled="isSubmitting"
                                                                                    class="w-full py-2.5 bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                                                                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                                </svg>
                                                                                <span x-show="!isSubmitting" class="material-symbols-outlined text-sm">send</span>
                                                                                <span x-text="isSubmitting ? '{{ __('Đang gửi...') }}' : '{{ $isSubmitted ? __('Cập nhật bài làm') : __('Gửi bài làm') }}'"></span>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                             </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                                                @if($course->assignments->isNotEmpty())
                                    @php
                                        $generalAssignments = $course->assignments;
                                        $genTotal = $generalAssignments->count();
                                        $genSubmitted = $generalAssignments->filter(fn($a) => $a->submissions->isNotEmpty())->count();
                                        $genPending = $genTotal - $genSubmitted;
                                        $isGenCompleted = ($genTotal > 0 && $genSubmitted === $genTotal);
                                        $shouldOpenGen = ($generalAssignments->contains('id', $openAssignmentId));
                                    @endphp

                                    <div x-data="{ genOpen: {{ $shouldOpenGen ? 'true' : 'false' }} }"
                                         class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-200 hover:border-primary/40">
                                        
                                        <button @click="genOpen = !genOpen"
                                                type="button"
                                                class="w-full text-left p-4 sm:p-5 flex items-center justify-between gap-4 bg-transparent hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer select-none">
                                            <div class="flex items-center gap-3.5 min-w-0">
                                                <div class="size-10 rounded-xl flex items-center justify-center shrink-0 border {{ $isGenCompleted ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 border-emerald-200 dark:border-emerald-800' : 'bg-primary/10 text-primary border-primary/20' }}">
                                                    <span class="material-symbols-outlined text-xl">
                                                        {{ $isGenCompleted ? 'check_circle' : 'folder_open' }}
                                                    </span>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-primary">{{ __('Tổng hợp') }}</span>
                                                        <span class="text-slate-300 dark:text-slate-600">•</span>
                                                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">{{ __(':count bài tập', ['count' => $genTotal]) }}</span>
                                                    </div>
                                                    <h3 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate mt-0.5">{{ __('Bài tập chung & Cuối khóa') }}</h3>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3 shrink-0">
                                                @if($isGenCompleted)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-[11px] font-bold">
                                                        <span class="material-symbols-outlined text-[13px]">verified</span>
                                                        <span>{{ __('Đã hoàn thành') }}</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 text-[11px] font-bold">
                                                        <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                        <span>{{ __(':count chưa nộp', ['count' => $genPending]) }}</span>
                                                    </span>
                                                @endif

                                                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200 text-lg"
                                                      :class="genOpen ? 'rotate-180 text-primary' : ''">
                                                    expand_more
                                                </span>
                                            </div>
                                        </button>

                                        <div x-show="genOpen" x-collapse class="border-t border-slate-100 dark:border-slate-800/80 p-4 sm:p-5 space-y-4 bg-slate-50/60 dark:bg-slate-950/30">
                                            @foreach($generalAssignments as $assignment)
                                                @php
                                                    $submission  = $assignment->submissions->first();
                                                    $isGraded    = $submission && $submission->status === \App\Models\AssignmentSubmission::STATUS_GRADED;
                                                    $isSubmitted = $submission && $submission->status === \App\Models\AssignmentSubmission::STATUS_SUBMITTED;
                                                    $isPastDue   = $assignment->due_date && $assignment->due_date->isPast();
                                                    $isOverdue   = $isPastDue && !$isGraded && !$isSubmitted;
                                                    $shouldOpenAssignment = ($openAssignmentId === $assignment->id);
                                                @endphp

                                                <div x-data="{ open: {{ $shouldOpenAssignment ? 'true' : 'false' }} }"
                                                     id="assignment-{{ $assignment->id }}"
                                                     class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden transition-all duration-200 hover:border-primary/40">
                                                    
                                                    <button @click="open = !open"
                                                            type="button"
                                                            class="w-full text-left p-3.5 sm:p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-transparent hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer select-none">
                                                        <div class="flex items-center gap-3 min-w-0">
                                                            <div class="size-8 rounded-lg flex items-center justify-center shrink-0 border
                                                                {{ $isGraded ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 border-emerald-200 dark:border-emerald-800' : ($isSubmitted ? 'bg-blue-50 dark:bg-blue-950/30 text-primary border-blue-200 dark:border-blue-900/50' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700') }}">
                                                                <span class="material-symbols-outlined text-base">
                                                                    {{ $isGraded ? 'verified' : ($isSubmitted ? 'cloud_done' : 'assignment') }}
                                                                </span>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-1">{{ $assignment->title }}</h4>
                                                                <div class="flex items-center gap-2 mt-0.5">
                                                                    @if($assignment->due_date)
                                                                        <span class="text-[11px] font-medium flex items-center gap-1 {{ $isOverdue ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-slate-400 dark:text-slate-500' }}">
                                                                            <span class="material-symbols-outlined text-[13px]">calendar_month</span>
                                                                            {{ __('Hạn nộp: :date', ['date' => $assignment->due_date->format('d/m/Y H:i')]) }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-[11px] text-slate-400 font-medium">{{ __('Không giới hạn thời gian') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2.5 shrink-0 self-end md:self-center">
                                                            <div class="flex flex-col items-end">
                                                                @if($isGraded)
                                                                    <div class="text-right flex items-baseline gap-0.5">
                                                                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ (float) $submission->score }}</span>
                                                                        <span class="text-[11px] font-semibold text-slate-400">/10</span>
                                                                    </div>
                                                                @elseif($isSubmitted)
                                                                    <span class="px-2 py-0.5 text-[11px] font-semibold text-primary bg-primary/10 rounded-md border border-primary/20">{{ __('Đã nộp') }}</span>
                                                                @else
                                                                    <span class="px-2 py-0.5 text-[11px] font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30 rounded-md border border-amber-200 dark:border-amber-900/50">{{ __('Chưa nộp') }}</span>
                                                                @endif
                                                            </div>
                                                            <span class="material-symbols-outlined text-slate-400 transition-transform duration-200 text-base" :class="open ? 'rotate-180 text-primary' : '' ">expand_more</span>
                                                        </div>
                                                    </button>

                                                    <div x-show="open" x-collapse class="border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                                                        <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

                                                            <div class="space-y-4">
                                                                <div>
                                                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 block">{{ __('Hướng dẫn từ giáo viên') }}</label>
                                                                    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed shadow-2xs">
                                                                        {!! nl2br(e($assignment->description)) !!}

                                                                        @if(!empty($assignment->attachments))
                                                                            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                                                                <x-lms.attachment-list :attachments="$assignment->attachments" variant="badges" :title="__('Tài liệu đính kèm:')" />
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                @if($isGraded)
                                                                    <div class="space-y-2">
                                                                        <label class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1 block">{{ __('Nhận xét & Góp ý của giáo viên') }}</label>
                                                                        <div class="bg-emerald-50/70 dark:bg-emerald-950/20 p-4 rounded-xl border border-emerald-200 dark:border-emerald-800/60 space-y-3">
                                                                            @if($submission->teacher_audio_path)
                                                                                <div class="bg-white dark:bg-slate-900 p-3 rounded-lg flex items-center gap-3 border border-emerald-200 dark:border-emerald-800 shadow-2xs">
                                                                                    <div class="size-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                                                                        <span class="material-symbols-outlined text-base">graphic_eq</span>
                                                                                    </div>
                                                                                    <audio src="{{ assignmentFileUrl($submission->teacher_audio_path) }}" controls class="h-7 flex-1"></audio>
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($submission->teacher_attachments))
                                                                                <div class="bg-white dark:bg-slate-900 p-3 rounded-lg border border-emerald-200 dark:border-emerald-800 shadow-2xs">
                                                                                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2">{{ __('Tệp đính kèm từ giáo viên:') }}</p>
                                                                                    <x-lms.attachment-list :attachments="$submission->teacher_attachments" variant="badges" />
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($submission->teacher_feedback))
                                                                                <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                                                                                    {{ $submission->teacher_feedback }}
                                                                                </p>
                                                                            @elseif(!$submission->teacher_audio_path && empty($submission->teacher_attachments))
                                                                                <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                                                                                    {{ __('Giáo viên không để lại nhận xét văn bản.') }}
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                             </div>

                                                             <div class="space-y-4">
                                                                @if($isGraded)
                                                                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col items-center justify-center text-center">
                                                                        <div class="size-14 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl flex items-center justify-center text-emerald-500 mb-3 border border-emerald-200 dark:border-emerald-800">
                                                                            <span class="material-symbols-outlined text-3xl">verified</span>
                                                                        </div>
                                                                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ (float) $submission->score }}<span class="text-xs text-slate-400">/10</span></h3>
                                                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __('Bài làm đã hoàn thành tuyệt vời!') }}</p>
                                                                    </div>

                                                                    @if(!empty($submission->attachments))
                                                                        <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                                                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                                                                                <span class="material-symbols-outlined text-primary text-base">task_alt</span>
                                                                                <span>{{ __('Bài làm bạn đã nộp') }}</span>
                                                                                <span class="text-slate-400 text-[11px] font-normal">({{ count($submission->attachments) }})</span>
                                                                            </h4>

                                                                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700/60 space-y-2">
                                                                                <div class="flex flex-wrap gap-2">
                                                                                    @foreach($submission->attachments as $att)
                                                                                        @php
                                                                                            $isAudio = preg_match('/\.(webm|mp3|wav|ogg|m4a|weba|aac)$/i', $att['name'] ?? '') || preg_match('/\.(webm|mp3|wav|ogg|m4a|weba|aac)$/i', $att['path'] ?? '');
                                                                                            $attViewerUrl = assignmentFileUrl($att['path']);
                                                                                        @endphp
                                                                                        @if($isAudio)
                                                                                            <div class="w-full flex items-center gap-2.5 p-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                                                                                <span class="material-symbols-outlined text-primary text-base">mic</span>
                                                                                                <audio src="{{ $attViewerUrl }}" controls class="h-7 flex-1"></audio>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white dark:bg-slate-900 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-primary/50 transition-colors">
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="flex items-center gap-1 text-slate-800 dark:text-white hover:text-primary text-left cursor-pointer font-bold">
                                                                                                    <span class="material-symbols-outlined text-sm text-primary">description</span>
                                                                                                    <span class="truncate max-w-[130px] sm:max-w-[180px]">{{ $att['name'] }}</span>
                                                                                                </button>
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="size-6 rounded hover:bg-primary/10 text-slate-400 hover:text-primary flex items-center justify-center cursor-pointer transition-colors"
                                                                                                        title="{{ __('Xem trước') }}">
                                                                                                    <span class="material-symbols-outlined text-[13px]">visibility</span>
                                                                                                </button>
                                                                                                <a href="{{ $attViewerUrl }}" target="_blank" download class="size-6 rounded hover:bg-primary/10 text-slate-400 hover:text-primary flex items-center justify-center transition-colors" title="{{ __('Tải về') }}">
                                                                                                    <span class="material-symbols-outlined text-[13px]">download</span>
                                                                                                </a>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @elseif($isPastDue && !$isSubmitted)
                                                                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col items-center justify-center text-center">
                                                                        <div class="size-14 bg-rose-50 dark:bg-rose-950/40 rounded-xl flex items-center justify-center text-rose-500 mb-3 border border-rose-200 dark:border-rose-800">
                                                                            <span class="material-symbols-outlined text-3xl">running_with_errors</span>
                                                                        </div>
                                                                        <h3 class="text-base font-bold text-rose-600">{{ __('Hết hạn nộp bài') }}</h3>
                                                                        <p class="text-xs text-slate-500 mt-1">{{ __('Vui lòng liên hệ giáo viên để được hỗ trợ') }}</p>
                                                                    </div>
                                                                @elseif($isPastDue && $isSubmitted)
                                                                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                                                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                                                                            <span class="material-symbols-outlined text-emerald-500 text-base">check_circle</span>
                                                                            <span>{{ __('Bài làm đã nộp (Đã hết hạn chỉnh sửa)') }}</span>
                                                                        </h4>

                                                                        @if(!empty($submission->attachments))
                                                                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700/60 mb-3 space-y-2">
                                                                                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Tệp/Ghi âm đã nộp:') }}</p>
                                                                                <div class="flex flex-wrap gap-2">
                                                                                    @foreach($submission->attachments as $att)
                                                                                        @php
                                                                                            $isAudio = preg_match('/\.(webm|mp3|wav|ogg|m4a|weba)$/i', $att['name'] ?? '') || preg_match('/\.(webm|mp3|wav|ogg|m4a|weba)$/i', $att['path'] ?? '');
                                                                                            $attViewerUrl = assignmentFileUrl($att['path']);
                                                                                        @endphp
                                                                                        @if($isAudio)
                                                                                            <div class="w-full flex items-center gap-2 p-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                                                                                <span class="material-symbols-outlined text-primary text-base">mic</span>
                                                                                                <audio src="{{ $attViewerUrl }}" controls class="h-7 flex-1"></audio>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-white dark:bg-slate-900 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-primary">
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="flex items-center gap-1 text-slate-800 dark:text-white hover:text-primary text-left">
                                                                                                    <span class="material-symbols-outlined text-sm text-primary">description</span>
                                                                                                    <span class="truncate max-w-[140px]">{{ $att['name'] }}</span>
                                                                                                </button>
                                                                                                <button type="button"
                                                                                                        @click="$dispatch('open-file-preview', { url: '{{ $attViewerUrl }}', name: '{{ addslashes($att['name']) }}' })"
                                                                                                        class="size-5 rounded hover:bg-primary/10 text-slate-400 hover:text-primary flex items-center justify-center ml-1"
                                                                                                        title="{{ __('Xem trước') }}">
                                                                                                    <span class="material-symbols-outlined text-[13px]">visibility</span>
                                                                                                </button>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                                                            {{ __('Bài làm của bạn đang chờ giáo viên chấm điểm.') }}
                                                                        </p>
                                                                    </div>
                                                                @else
                                                                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                                                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                                                                            <span class="material-symbols-outlined text-primary text-base">cloud_upload</span>
                                                                            <span>{{ $isSubmitted ? __('Cập nhật bài làm') : __('Nộp bài ngay') }}</span>
                                                                        </h4>

                                                                        <form action="{{ route('student.assignments.submit', $assignment->id) }}"
                                                                              method="POST"
                                                                              enctype="multipart/form-data"
                                                                              x-data="{ 
                                                                                  isSubmitting: false,
                                                                                  existingFiles: {{ json_encode(array_values($submission->attachments ?? [])) }}
                                                                              }"
                                                                              @submit="if (isSubmitting) { $event.preventDefault(); return false; } isSubmitting = true"
                                                                              class="space-y-4">
                                                                            @csrf

                                                                            @if($isSubmitted && !empty($submission->attachments))
                                                                                <div class="p-3 bg-blue-50/70 dark:bg-blue-950/20 rounded-xl border border-blue-200 dark:border-blue-900/50 mb-3 space-y-2">
                                                                                    <div class="flex items-center justify-between">
                                                                                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider">{{ __('Tệp / Ghi âm đã nộp:') }}</p>
                                                                                        <span class="text-[10px] text-slate-400 font-medium">{{ __('(Bấm X đỏ để gỡ file)') }}</span>
                                                                                    </div>
                                                                                    <div class="flex flex-wrap gap-2">
                                                                                        <template x-for="(att, aIdx) in existingFiles" :key="att.path">
                                                                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-slate-900 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                                                                <input type="hidden" name="existing_attachments[]" :value="att.path">
                                                                                                <template x-if="att.name && att.name.match(/\.(webm|mp3|wav|ogg|m4a|weba)$/i)">
                                                                                                    <span class="material-symbols-outlined text-primary text-sm">mic</span>
                                                                                                </template>
                                                                                                <template x-if="!att.name || !att.name.match(/\.(webm|mp3|wav|ogg|m4a|weba)$/i)">
                                                                                                    <span class="material-symbols-outlined text-primary text-sm">description</span>
                                                                                                </template>
                                                                                                <span class="truncate max-w-[130px]" x-text="att.name"></span>
                                                                                                <button type="button" @click="existingFiles.splice(aIdx, 1)"
                                                                                                        class="size-4.5 rounded-full bg-red-50 hover:bg-red-500 hover:text-white text-red-500 flex items-center justify-center transition-colors ml-1 cursor-pointer"
                                                                                                        title="{{ __('Gỡ bỏ tệp này') }}">
                                                                                                    <span class="material-symbols-outlined text-[12px]">close</span>
                                                                                                </button>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                    <p x-show="existingFiles.length === 0" class="text-[11px] text-amber-600 dark:text-amber-400 italic">
                                                                                        {{ __('Bạn đã gỡ hết các file cũ. Vui lòng ghi âm hoặc đính kèm tệp mới bên dưới.') }}
                                                                                    </p>
                                                                                </div>
                                                                            @endif

                                                                            <div class="space-y-3">
                                                                                <div>
                                                                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1 mb-1.5">
                                                                                        <span class="material-symbols-outlined text-primary text-base">mic</span>
                                                                                        <span>{{ __('1. Ghi âm giọng nói (nếu có)') }}</span>
                                                                                    </label>
                                                                                    <x-audio-recorder name="audio_file" />
                                                                                </div>

                                                                                <div class="pt-1">
                                                                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1 mb-1.5">
                                                                                        <span class="material-symbols-outlined text-primary text-base">attach_file</span>
                                                                                        <span>{{ __('2. Đính kèm tệp tin / hình ảnh bài làm (nếu có)') }}</span>
                                                                                    </label>
                                                                                    <x-lms.file-uploader
                                                                                        name="attachments[]"
                                                                                        :compact="true"
                                                                                        :maxFiles="5"
                                                                                        :maxSizeMB="20"
                                                                                        :label="null"
                                                                                        :helperText="__('Hỗ trợ: PDF, Word, Excel, Ảnh, Audio, Zip... (Có thể gửi kèm cả ghi âm và tệp tin cùng lúc)')" />
                                                                                </div>
                                                                            </div>

                                                                            <button type="submit"
                                                                                    :disabled="isSubmitting"
                                                                                    class="w-full py-2.5 bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                                                                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                                </svg>
                                                                                <span x-show="!isSubmitting" class="material-symbols-outlined text-sm">send</span>
                                                                                <span x-text="isSubmitting ? '{{ __('Đang gửi...') }}' : '{{ $isSubmitted ? __('Cập nhật bài làm') : __('Gửi bài làm') }}'"></span>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                             </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                </div>
            @endif
        </div>
    </main>
@endsection
