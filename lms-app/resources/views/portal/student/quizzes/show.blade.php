@extends('portal.layouts.dashboard')

@section('title', $quiz->title)

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-3xl mx-auto space-y-6">
            
                        <div class="flex items-center">
                <a href="{{ route('student.quizzes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-primary dark:text-slate-400 transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    {{ __('Quay lại danh sách') }}
                </a>
            </div>

                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 shadow-sm relative overflow-hidden">
                <div class="absolute -right-16 -top-16 size-48 rounded-full bg-primary/5 blur-2xl"></div>
                
                <div class="space-y-5 relative z-10">
                    <div class="space-y-1.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-primary/10 text-[11px] font-bold text-primary uppercase tracking-wide">
                            <span class="material-symbols-outlined text-[13px]">school</span>
                            {{ $quiz->course->title }}
                        </span>
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white leading-snug">
                            {{ $quiz->title }}
                        </h1>
                    </div>

                    {{-- Quiz metadata grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 bg-slate-50/80 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800/50">
                        <div class="text-center space-y-0.5">
                            <span class="material-symbols-outlined text-primary text-xl">hourglass_top</span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Thời gian') }}</p>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ $quiz->time_limit > 0 ? __(':time phút', ['time' => $quiz->time_limit]) : __('Không giới hạn') }}
                            </p>
                        </div>
                        <div class="text-center space-y-0.5 border-l border-slate-200 dark:border-slate-800/60">
                            <span class="material-symbols-outlined text-primary text-xl">help_center</span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Số câu hỏi') }}</p>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ $quiz->questions->count() }} {{ __('câu') }}
                            </p>
                        </div>
                        <div class="text-center space-y-0.5 border-l border-slate-200 dark:border-slate-800/60">
                            <span class="material-symbols-outlined text-primary text-xl">grade</span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Thang điểm') }}</p>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ __('10.0 điểm') }}
                            </p>
                        </div>
                        <div class="text-center space-y-0.5 border-l border-slate-200 dark:border-slate-800/60">
                            <span class="material-symbols-outlined text-primary text-xl">event</span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Hạn nộp') }}</p>
                            <p class="text-xs sm:text-sm font-bold {{ $quiz->due_date && $quiz->due_date->isPast() ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-slate-200' }}">
                                {{ $quiz->due_date ? $quiz->due_date->format('d/m/Y H:i') : __('Không giới hạn') }}
                            </p>
                        </div>
                    </div>

                    {{-- Important notice --}}
                    <div class="space-y-3 pt-1">
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-amber-500 text-base">warning</span>
                            {{ __('Lưu ý quan trọng khi làm bài:') }}
                        </h3>
                        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400 pl-1 font-medium leading-relaxed">
                            <li class="flex items-start gap-2">
                                <span class="size-1.5 rounded-full bg-primary mt-1.5 shrink-0"></span>
                                <span><strong>{{ __('Số lần làm bài:') }}</strong> {{ __('Mỗi học viên chỉ được làm bài 01 lần duy nhất. Không thể làm lại sau khi nộp hoặc hết hạn.') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="size-1.5 rounded-full bg-primary mt-1.5 shrink-0"></span>
                                <span>{{ __('Khi bấm bắt đầu, hệ thống sẽ ngay lập tức tính giờ làm bài.') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="size-1.5 rounded-full bg-primary mt-1.5 shrink-0"></span>
                                <span>{{ __('Khi hết giờ làm bài, hệ thống sẽ tự động nộp bài thi của bạn ngay cả khi bạn chưa làm hết các câu hỏi.') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="size-1.5 rounded-full bg-primary mt-1.5 shrink-0"></span>
                                <span>{{ __('Nếu rời khỏi hoặc tắt trình duyệt, bạn chỉ có thể bấm tiếp tục làm bài trước khi đồng hồ đếm ngược hết giờ.') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="size-1.5 rounded-full bg-primary mt-1.5 shrink-0"></span>
                                <span>{{ __('Điểm số sẽ được tự động quy chuẩn về thang điểm 10.') }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Actions based on attempt status --}}
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3">
                        @if($quiz->status === 'completed' || $quiz->status === 'abandoned' || $quiz->status === 'needs_grading')
                            <a href="{{ route('student.quizzes.result', $quiz->student_attempt->id) }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-100 dark:hover:bg-white text-white dark:text-slate-900 font-bold text-xs sm:text-sm rounded-xl shadow-xs transition-all">
                                <span class="material-symbols-outlined text-base">visibility</span>
                                <span>{{ __('Xem kết quả bài thi') }}</span>
                            </a>
                        @elseif($quiz->status === 'expired')
                            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40 text-xs sm:text-sm font-bold">
                                <span class="material-symbols-outlined text-base">event_busy</span>
                                <span>{{ __('Bài kiểm tra đã hết hạn nộp bài') }}</span>
                            </div>
                        @elseif($quiz->status === 'in_progress')
                            <a href="{{ route('student.quizzes.take', $quiz->student_attempt->id) }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition-all">
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                                <span>{{ __('Tiếp tục làm bài (Đang diễn ra)') }}</span>
                            </a>
                        @else
                            <form action="{{ route('student.quizzes.attempt', $quiz->id) }}" method="POST"
                                  x-data="{ isSubmitting: false }"
                                  @submit="if (isSubmitting) { $event.preventDefault(); return false; } isSubmitting = true">
                                @csrf
                                <button type="submit" 
                                        :disabled="isSubmitting"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 bg-primary hover:bg-primary/95 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-md hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
                                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-show="!isSubmitting" class="material-symbols-outlined text-base">play_arrow</span>
                                    <span x-text="isSubmitting ? '{{ __('Đang chuyển hướng...') }}' : '{{ __('Bắt đầu làm bài thi') }}'"></span>
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </main>
@endsection
