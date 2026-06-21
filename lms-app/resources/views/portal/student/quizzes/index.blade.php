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
        <div class="max-w-[1400px] mx-auto space-y-8">
            
            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">fact_check</span>
                        {{ __('Bài kiểm tra của bạn') }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold text-sm">
                        {{ __('Nơi tổng hợp tất cả các bài kiểm tra trắc nghiệm, tự luận và hỗn hợp từ các khóa học bạn đang tham gia. Hãy hoàn thành tốt nhé!') }}
                    </p>
                </div>
            </div>

            {{-- Flash Messages --}}
            <x-flash-message type="success" />
            <x-flash-message type="error" />

            {{-- Quizzes Grid --}}
            @if($quizzes->isEmpty())
                <div class="flex flex-col items-center justify-center p-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-center shadow-sm">
                    <div class="size-20 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-6 shadow-inner">
                        <span class="material-symbols-outlined text-4xl">assignment_late</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">{{ __('Không có bài kiểm tra nào') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mb-6">
                        {{ __('Hiện tại bạn chưa được phân công hoặc chưa có bài kiểm tra nào từ các khóa học đã đăng ký.') }}
                    </p>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($quizzes as $quiz)
                        <div class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md hover:border-primary/30 dark:hover:border-primary/30 transition-all duration-300">
                            
                            {{-- Top Metadata --}}
                            <div>
                                <div class="flex items-center justify-between mb-4 gap-2">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 truncate max-w-[150px]">
                                        {{ $quiz->course->title }}
                                    </span>
                                    
                                    {{-- Status Badge --}}
                                    @if($quiz->status === 'in_progress')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-extrabold uppercase tracking-wide">
                                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            {{ __('Đang làm') }}
                                        </span>
                                    @elseif($quiz->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-extrabold uppercase tracking-wide">
                                            <span class="material-symbols-outlined text-[12px]">check_circle</span>
                                            {{ __('Đã hoàn thành') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-extrabold uppercase tracking-wide">
                                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                                            {{ __('Chưa bắt đầu') }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 dark:text-white line-clamp-1 mb-2 group-hover:text-primary transition-colors">
                                    {{ $quiz->title }}
                                </h3>

                                <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-6">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">hourglass_empty</span>
                                        {{ $quiz->time_limit > 0 ? __(':time phút', ['time' => $quiz->time_limit]) : __('Không giới hạn') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">category</span>
                                        {{ $quiz->type->label() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Bottom Info & Actions --}}
                            <div class="border-t border-slate-100 dark:border-slate-800/80 pt-4 flex items-center justify-between gap-4 mt-auto">
                                <div>
                                    @if($quiz->status === 'completed')
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('Điểm cao nhất') }}</p>
                                        <p class="text-lg font-black text-emerald-500">
                                            {{ $quiz->highest_score }}<span class="text-xs font-semibold text-slate-400"> / {{ $quiz->questions->sum('marks') }}</span>
                                        </p>
                                    @else
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('Số câu hỏi') }}</p>
                                        <p class="text-sm font-extrabold text-slate-700 dark:text-slate-300">
                                            {{ $quiz->questions->count() }} {{ __('câu hỏi') }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Action Button --}}
                                @if($quiz->status === 'in_progress')
                                    <a href="{{ route('student.quizzes.take', $quiz->active_attempt_id) }}" 
                                       class="flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-md shadow-amber-500/10 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                        {{ __('Làm tiếp') }}
                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                @elseif($quiz->status === 'completed')
                                    <div class="flex items-center gap-2">
                                        {{-- Xem kết quả thi gần nhất --}}
                                        <a href="{{ route('student.quizzes.result', $quiz->attempts->first()->id) }}" 
                                           class="flex items-center gap-1 px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700/80 rounded-xl transition-all">
                                            {{ __('Kết quả') }}
                                        </a>
                                        {{-- Làm lại bài thi --}}
                                        <a href="{{ route('student.quizzes.show', $quiz->id) }}" 
                                           class="flex items-center justify-center p-2 text-primary bg-primary/10 hover:bg-primary hover:text-white rounded-xl transition-colors"
                                           title="{{ __('Làm lại bài thi') }}">
                                            <span class="material-symbols-outlined text-[18px]">replay</span>
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('student.quizzes.show', $quiz->id) }}" 
                                       class="flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary hover:bg-primary/90 rounded-xl shadow-md shadow-primary/10 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                        {{ __('Làm bài') }}
                                        <span class="material-symbols-outlined text-[16px]">play_arrow</span>
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
