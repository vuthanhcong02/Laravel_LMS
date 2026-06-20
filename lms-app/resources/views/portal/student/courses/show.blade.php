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
        <div class="max-w-[1400px] mx-auto space-y-8" x-data="{ activeTab: 'curriculum' }">
            
            {{-- Breadcrumb --}}
            <nav class="flex items-center text-sm text-slate-500 font-medium mb-4">
                <a href="{{ route('student.courses.index') }}" class="hover:text-primary transition-colors">Khóa học của tôi</a>
                <span class="material-symbols-outlined text-[18px] mx-1">chevron_right</span>
                <span class="text-slate-800 dark:text-white truncate">{{ $course->title }}</span>
            </nav>



            {{-- Tabs Navigation --}}
            <div class="flex items-center gap-4 border-b border-slate-200 dark:border-slate-800 pb-px overflow-x-auto no-scrollbar">
                <button @click="activeTab = 'curriculum'" 
                        :class="activeTab === 'curriculum' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                        class="px-6 py-4 font-bold border-b-4 transition-all flex items-center gap-2 whitespace-nowrap">
                    <span class="material-symbols-outlined text-xl">menu_book</span>
                    Lộ trình học
                </button>
                <button @click="activeTab = 'assignments'" 
                        :class="activeTab === 'assignments' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                        class="px-6 py-4 font-bold border-b-4 transition-all flex items-center gap-2 whitespace-nowrap">
                    <span class="material-symbols-outlined text-xl">task</span>
                    Bài tập & Kiểm tra
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-xs text-slate-600 dark:text-slate-400">{{ $course->assignments->count() + $course->quizzes->count() }}</span>
                </button>
            </div>

            {{-- Tab Contents --}}
            <div class="pt-6">
                
                {{-- Curriculum Tab --}}
                <div x-show="activeTab === 'curriculum'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="space-y-4">
                        @forelse($course->lessons->sortBy('order') as $index => $lesson)
                            <a href="{{ route('student.courses.learn', ['course' => $course->id, 'lesson' => $lesson->id]) }}" class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-800 cursor-pointer block">
                                <div class="size-12 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                    <span class="material-symbols-outlined">play_lesson</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-base font-bold text-slate-800 dark:text-white truncate group-hover:text-primary transition-colors">
                                        Bài {{ $index + 1 }}: {{ $lesson->title }}
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $lesson->description ?? 'Không có mô tả' }}</p>
                                </div>
                                <div class="shrink-0 pl-4">
                                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-700">lock_open</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-700 mb-4">inventory_2</span>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Chưa có bài học nào</h3>
                                <p class="text-slate-500 max-w-sm mx-auto">Giảng viên đang trong quá trình cập nhật nội dung cho khóa học này. Bạn quay lại sau nhé!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Assignments Tab --}}
                <div x-show="activeTab === 'assignments'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="space-y-6">
                        @if($course->assignments->isEmpty() && $course->quizzes->isEmpty())
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-700 mb-4">task</span>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Không có bài tập</h3>
                                <p class="text-slate-500 max-w-sm mx-auto">Tuyệt vời! Khóa học này hiện chưa có bài tập hay bài kiểm tra nào bạn cần làm.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Danh sách bài tập --}}
                                @if($course->assignments->isNotEmpty())
                                    <div class="space-y-4">
                                        <h3 class="font-bold text-slate-800 dark:text-white text-lg flex items-center gap-2">
                                            <span class="material-symbols-outlined text-primary">assignment</span> 
                                            Bài tập tự luận ({{ $course->assignments->count() }})
                                        </h3>
                                        @foreach($course->assignments as $assignment)
                                            <a href="{{ route('student.assignments.show', $assignment->id) }}" class="block py-4 border-b border-slate-200 dark:border-slate-800 last:border-0 group">
                                                <h4 class="font-bold text-slate-800 dark:text-white line-clamp-1 mb-2 group-hover:text-primary transition-colors">{{ $assignment->title }}</h4>
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-slate-500 flex items-center gap-1 font-medium">
                                                        <span class="material-symbols-outlined text-[14px]">event</span> 
                                                        Hạn nộp: {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y H:i') : 'Không giới hạn' }}
                                                    </span>
                                                    @php
                                                        $submission = $assignment->submissions->first();
                                                    @endphp
                                                    @if($submission)
                                                        @if($submission->status === \App\Models\AssignmentSubmission::STATUS_GRADED)
                                                            <span class="text-emerald-600 dark:text-emerald-500 font-bold">{{ $submission->score }} / 10</span>
                                                        @else
                                                            <span class="text-blue-600 dark:text-blue-500 font-bold">Đã nộp</span>
                                                        @endif
                                                    @else
                                                        <span class="text-amber-600 dark:text-amber-500 font-bold">Chưa nộp</span>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Danh sách Quiz --}}
                                @if($course->quizzes->isNotEmpty())
                                    <div class="space-y-4">
                                        <h3 class="font-bold text-slate-800 dark:text-white text-lg flex items-center gap-2">
                                            <span class="material-symbols-outlined text-purple-500">quiz</span> 
                                            Bài kiểm tra ({{ $course->quizzes->count() }})
                                        </h3>
                                        @foreach($course->quizzes as $quiz)
                                            <div class="py-4 border-b border-slate-200 dark:border-slate-800 last:border-0 group">
                                                <h4 class="font-bold text-slate-800 dark:text-white line-clamp-1 mb-2 group-hover:text-purple-500 transition-colors">{{ $quiz->title }}</h4>
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-slate-500 flex items-center gap-1 font-medium">
                                                        <span class="material-symbols-outlined text-[14px]">timer</span> 
                                                        Thời gian: {{ $quiz->time_limit ?? 'Không giới hạn' }} phút
                                                    </span>
                                                    <span class="text-slate-600 dark:text-slate-400 font-bold">Chưa làm</span>
                                                </div>
                                            </div>
                                        @endforeach
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
