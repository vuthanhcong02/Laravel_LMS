@extends('portal.layouts.dashboard')

@section('title', __('Danh sách bài kiểm tra'))

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
                :title="__('Bài kiểm tra của bạn')"
                :description="__('Nơi tổng hợp tất cả các bài kiểm tra trắc nghiệm, tự luận và hỗn hợp từ các khóa học bạn đang tham gia.')"
            />

            <x-flash-message type="success" />
            <x-flash-message type="error" />

            @if($quizzes->isEmpty())
                <div class="flex flex-col items-center justify-center p-12 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 text-center shadow-sm space-y-2">
                    <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">assignment_late</span>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Không có bài kiểm tra nào') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">
                        {{ __('Hiện tại bạn chưa được phân công hoặc chưa có bài kiểm tra nào từ các khóa học đã đăng ký.') }}
                    </p>
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($quizzes as $quiz)
                        <div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-200">

                            <div>
                                <div class="flex items-center justify-between mb-3 gap-2">
                                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 truncate max-w-[150px]">
                                        {{ $quiz->course->title }}
                                    </span>

                                    @if($quiz->status === 'in_progress')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 text-[10px] font-bold">
                                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            {{ __('Đang làm') }}
                                        </span>
                                    @elseif($quiz->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-[10px] font-bold">
                                            <span class="material-symbols-outlined text-[12px]">check_circle</span>
                                            {{ __('Đã hoàn thành') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 text-[10px] font-bold">
                                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                                            {{ __('Chưa bắt đầu') }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white line-clamp-1 mb-2 group-hover:text-primary transition-colors">
                                    {{ $quiz->title }}
                                </h3>

                                <div class="flex items-center gap-3 text-xs font-medium text-slate-500 dark:text-slate-400 mb-4">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px] text-slate-400">hourglass_empty</span>
                                        {{ $quiz->time_limit > 0 ? __(':time phút', ['time' => $quiz->time_limit]) : __('Không giới hạn') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px] text-slate-400">category</span>
                                        {{ $quiz->type->label() }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-800 pt-3.5 flex items-center justify-between gap-3 mt-auto">
                                <div>
                                    @if($quiz->status === 'completed')
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('Điểm cao nhất') }}</p>
                                        <p class="text-base font-bold text-emerald-600 dark:text-emerald-400">
                                            {{ $quiz->highest_score }}<span class="text-xs font-normal text-slate-400"> / {{ $quiz->questions->sum('marks') }}</span>
                                        </p>
                                    @else
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('Số câu hỏi') }}</p>
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                            {{ $quiz->questions->count() }} {{ __('câu hỏi') }}
                                        </p>
                                    @endif
                                </div>

                                @if($quiz->status === 'in_progress')
                                    <a href="{{ route('student.quizzes.take', $quiz->active_attempt_id) }}"
                                       class="flex items-center gap-1 px-3.5 py-1.5 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs transition-colors">
                                        <span>{{ __('Làm tiếp') }}</span>
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </a>
                                @elseif($quiz->status === 'completed')
                                    <a href="{{ route('student.quizzes.result', $quiz->attempts->first()->id) }}"
                                       class="flex items-center gap-1 px-3.5 py-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        <span>{{ __('Kết quả') }}</span>
                                    </a>
                                @else
                                    <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                                       class="flex items-center gap-1 px-3.5 py-1.5 text-xs font-bold text-white bg-primary hover:bg-primary/90 rounded-xl shadow-xs transition-colors">
                                        <span>{{ __('Làm bài') }}</span>
                                        <span class="material-symbols-outlined text-sm">play_arrow</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
@endsection
