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
            
            {{-- Breadcrumb / Back --}}
            <div class="flex items-center">
                <a href="{{ route('student.quizzes.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary dark:text-slate-400 transition-colors">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    {{ __('Quay lại danh sách') }}
                </a>
            </div>

            {{-- Main Intro Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm relative overflow-hidden">
                <div class="absolute -right-16 -top-16 size-48 rounded-full bg-primary/5 blur-2xl"></div>
                
                <div class="space-y-6 relative z-10">
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-xs font-bold text-primary uppercase">
                            <span class="material-symbols-outlined text-[14px]">school</span>
                            {{ $quiz->course->title }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-tight">
                            {{ $quiz->title }}
                        </h1>
                    </div>

                    {{-- Info Cards Grid --}}
                    <div class="grid grid-cols-3 gap-4 p-5 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800/30">
                        <div class="text-center space-y-1">
                            <span class="material-symbols-outlined text-primary text-2xl">hourglass_top</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Thời gian') }}</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">
                                {{ $quiz->time_limit > 0 ? __(':time phút', ['time' => $quiz->time_limit]) : __('Không giới hạn') }}
                            </p>
                        </div>
                        <div class="text-center space-y-1 border-x border-slate-200 dark:border-slate-800/50">
                            <span class="material-symbols-outlined text-primary text-2xl">help_center</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Số câu hỏi') }}</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">
                                {{ $quiz->questions->count() }} {{ __('câu') }}
                            </p>
                        </div>
                        <div class="text-center space-y-1">
                            <span class="material-symbols-outlined text-primary text-2xl">stars</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Tổng điểm') }}</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">
                                {{ $quiz->questions->sum('marks') }} {{ __('điểm') }}
                            </p>
                        </div>
                    </div>

                    {{-- Instructions --}}
                    <div class="space-y-4">
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-500">warning</span>
                            {{ __('Lưu ý quan trọng khi làm bài:') }}
                        </h3>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400 pl-2">
                            <li class="flex items-start gap-2.5">
                                <span class="size-1.5 rounded-full bg-primary mt-2 shrink-0"></span>
                                <span>{{ __('Khi bấm bắt đầu, hệ thống sẽ ngay lập tức tính giờ làm bài.') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="size-1.5 rounded-full bg-primary mt-2 shrink-0"></span>
                                <span>{{ __('Khi hết giờ làm bài, hệ thống sẽ tự động nộp bài thi của bạn ngay cả khi bạn chưa làm hết các câu hỏi.') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="size-1.5 rounded-full bg-primary mt-2 shrink-0"></span>
                                <span>{{ __('Nếu trình duyệt bị tắt đột ngột, bạn có thể quay lại trang này để bấm tiếp tục làm bài trước khi hết giờ.') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="size-1.5 rounded-full bg-primary mt-2 shrink-0"></span>
                                <span>{{ __('Không gian lận, không mở tab khác hoặc tìm kiếm đáp án bên ngoài.') }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Start Button / Form --}}
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <form action="{{ route('student.quizzes.attempt', $quiz->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary hover:bg-primary/95 text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-[1.01] active:scale-[0.99] transition-all">
                                <span class="material-symbols-outlined">play_arrow</span>
                                {{ __('Bắt đầu làm bài thi') }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </main>
@endsection
