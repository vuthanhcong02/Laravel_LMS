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

                                        <div class="grid grid-cols-3 gap-3 p-4 bg-slate-50/80 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800/50">
                        <div class="text-center space-y-0.5">
                            <span class="material-symbols-outlined text-primary text-xl">hourglass_top</span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Thời gian') }}</p>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ $quiz->time_limit > 0 ? __(':time phút', ['time' => $quiz->time_limit]) : __('Không giới hạn') }}
                            </p>
                        </div>
                        <div class="text-center space-y-0.5 border-x border-slate-200 dark:border-slate-800/60">
                            <span class="material-symbols-outlined text-primary text-xl">help_center</span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Số câu hỏi') }}</p>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ $quiz->questions->count() }} {{ __('câu') }}
                            </p>
                        </div>
                        <div class="text-center space-y-0.5">
                            <span class="material-symbols-outlined text-primary text-xl">stars</span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Tổng điểm') }}</p>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ $quiz->questions->sum('marks') }} {{ __('điểm') }}
                            </p>
                        </div>
                    </div>

                                        <div class="space-y-3 pt-1">
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-amber-500 text-base">warning</span>
                            {{ __('Lưu ý quan trọng khi làm bài:') }}
                        </h3>
                        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400 pl-1 font-medium leading-relaxed">
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
                                <span>{{ __('Nếu trình duyệt bị tắt đột ngột, bạn có thể quay lại trang này để bấm tiếp tục làm bài trước khi hết giờ.') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="size-1.5 rounded-full bg-primary mt-1.5 shrink-0"></span>
                                <span>{{ __('Không gian lận, không mở tab khác hoặc tìm kiếm đáp án bên ngoài.') }}</span>
                            </li>
                        </ul>
                    </div>

                                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <form action="{{ route('student.quizzes.attempt', $quiz->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 bg-primary hover:bg-primary/95 text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-md hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
                                <span class="material-symbols-outlined text-base">play_arrow</span>
                                {{ __('Bắt đầu làm bài thi') }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </main>
@endsection
