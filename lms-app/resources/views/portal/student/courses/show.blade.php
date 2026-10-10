@extends('portal.layouts.dashboard')

@section('title', $course->title . ' - ' . config('app.name', 'LMS'))

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1400px] mx-auto space-y-6" x-data="{ activeTab: 'curriculum' }">
            
                        <div class="space-y-2">
                <nav class="flex items-center text-xs text-slate-500 font-medium">
                    <a href="{{ route('student.courses.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        <span>{{ __('Khóa học của bạn') }}</span>
                    </a>
                    <span class="material-symbols-outlined text-sm mx-1.5 text-slate-400">chevron_right</span>
                    <span class="text-slate-700 dark:text-slate-300 truncate font-semibold">{{ $course->title }}</span>
                </nav>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                            {{ $course->title }}
                        </h1>
                        @if($course->teacher)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-slate-400">person</span>
                                <span>{{ __('Giảng viên:') }} {{ $course->teacher->first_name }} {{ $course->teacher->last_name }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

                        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-px overflow-x-auto no-scrollbar">
                <button @click="activeTab = 'curriculum'" 
                        :class="activeTab === 'curriculum' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                        class="px-4 py-2.5 font-bold text-xs sm:text-sm border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">menu_book</span>
                    <span>{{ __('Lộ trình học') }}</span>
                </button>
                <button @click="activeTab = 'assignments'" 
                        :class="activeTab === 'assignments' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                        class="px-4 py-2.5 font-bold text-xs sm:text-sm border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">task</span>
                    <span>{{ __('Bài tập & Kiểm tra') }}</span>
                    <span class="px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] text-slate-600 dark:text-slate-400 font-bold">{{ $course->assignments->count() + $course->quizzes->count() }}</span>
                </button>
            </div>

                        <div>
                
                                <div x-show="activeTab === 'curriculum'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden">
                        @forelse($course->lessons->sortBy('order') as $index => $lesson)
                            <a href="{{ route('student.courses.learn', ['course' => $course->id, 'lesson' => $lesson->id]) }}" class="group flex items-center gap-3 p-3 sm:p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors cursor-pointer select-none">
                                <div class="size-8 rounded-lg flex items-center justify-center shrink-0 bg-primary/10 text-primary border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors cursor-pointer">
                                    <span class="material-symbols-outlined text-base cursor-pointer">play_lesson</span>
                                </div>
                                <div class="flex-1 min-w-0 cursor-pointer">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-primary transition-colors cursor-pointer">
                                        {{ $lesson->title }}
                                    </h4>
                                    @if($lesson->description)
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 truncate cursor-pointer">{{ $lesson->description }}</p>
                                    @endif
                                </div>
                                <div class="shrink-0 pl-2 cursor-pointer flex items-center justify-center">
                                    <span class="material-symbols-outlined text-slate-400 group-hover:text-primary group-hover:scale-115 transition-all text-xl cursor-pointer" title="{{ __('Học bài này') }}">play_circle</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-12 p-6 space-y-2">
                                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">inventory_2</span>
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Chưa có bài học nào') }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">{{ __('Giảng viên đang trong quá trình cập nhật nội dung cho khóa học này. Bạn quay lại sau nhé!') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                                <div x-show="activeTab === 'assignments'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="space-y-6">
                        @if($course->assignments->isEmpty() && $course->quizzes->isEmpty())
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-sm space-y-2">
                                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">task</span>
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Không có bài tập') }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">{{ __('Tuyệt vời! Khóa học này hiện chưa có bài tập hay bài kiểm tra nào bạn cần làm.') }}</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                                                @if($course->assignments->isNotEmpty())
                                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-3">
                                        <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="material-symbols-outlined text-primary text-lg">assignment</span> 
                                            <span>{{ __('Bài tập về nhà (:count)', ['count' => $course->assignments->count()]) }}</span>
                                        </h3>
                                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                            @foreach($course->assignments as $assignment)
                                                <a href="{{ route('student.assignments.index', ['open' => $assignment->id, 'lesson_id' => $assignment->lesson_id]) }}" class="block py-3 group">
                                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-1 mb-1 group-hover:text-primary transition-colors">{{ $assignment->title }}</h4>
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1 font-medium">
                                                            <span class="material-symbols-outlined text-sm text-slate-400">event</span> 
                                                            {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y H:i') : __('Không có hạn') }}
                                                        </span>
                                                        @php
                                                            $submission = $assignment->submissions->first();
                                                        @endphp
                                                        @if($submission)
                                                            @if($submission->status === \App\Models\AssignmentSubmission::STATUS_GRADED)
                                                                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs">{{ $submission->score }} / 10</span>
                                                            @else
                                                                <span class="text-primary font-bold text-xs">{{ __('Đã nộp') }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-amber-600 dark:text-amber-400 font-bold text-xs">{{ __('Chưa nộp') }}</span>
                                                        @endif
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                                                @if($course->quizzes->isNotEmpty())
                                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-3">
                                        <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="material-symbols-outlined text-primary text-lg">quiz</span> 
                                            <span>{{ __('Bài kiểm tra (:count)', ['count' => $course->quizzes->count()]) }}</span>
                                        </h3>
                                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                            @foreach($course->quizzes as $quiz)
                                                @php
                                                    $completedAttempts = $quiz->attempts->filter(fn($a) => !is_null($a->completed_at));
                                                    $activeAttempt = $quiz->attempts->first(fn($a) => is_null($a->completed_at));
                                                    $highestScore = $completedAttempts->max('score');
                                                    $totalMarks = $quiz->questions->sum('marks');
                                                @endphp
                                                <div class="py-3 group">
                                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-1 mb-1 group-hover:text-primary transition-colors">
                                                        @if($activeAttempt)
                                                            <a href="{{ route('student.quizzes.take', $activeAttempt->id) }}">{{ $quiz->title }}</a>
                                                        @elseif($completedAttempts->isNotEmpty())
                                                            <a href="{{ route('student.quizzes.result', $completedAttempts->first()->id) }}">{{ $quiz->title }}</a>
                                                        @else
                                                            <a href="{{ route('student.quizzes.show', $quiz->id) }}">{{ $quiz->title }}</a>
                                                        @endif
                                                    </h4>
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1 font-medium">
                                                            <span class="material-symbols-outlined text-sm text-slate-400">timer</span> 
                                                            {{ $quiz->time_limit > 0 ? __(':time phút', ['time' => $quiz->time_limit]) : __('Không giới hạn') }}
                                                        </span>
                                                        
                                                        @if($activeAttempt)
                                                            <span class="text-amber-600 dark:text-amber-400 font-bold flex items-center gap-1 text-xs">
                                                                <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                                {{ __('Đang làm') }}
                                                            </span>
                                                        @elseif($completedAttempts->isNotEmpty())
                                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs">
                                                                {{ $highestScore }} / {{ $totalMarks }} {{ __('điểm') }}
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400 font-semibold text-xs">{{ __('Chưa làm') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
