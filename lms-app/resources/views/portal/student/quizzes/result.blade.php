@extends('portal.layouts.dashboard')

@section('title', __('Kết quả bài thi: ') . $attempt->quiz->title)

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    @php
        $quiz = $attempt->quiz;
        $totalQuestions = $quiz->questions->count();
        $totalMarks = $quiz->questions->sum('marks');

        // Calculate duration
        $durationSeconds = $attempt->completed_at->diffInSeconds($attempt->started_at);
        $durationMinutes = floor($durationSeconds / 60);
        $durationRemainingSeconds = $durationSeconds % 60;

        // Count correct answers (only for MC and TF questions)
        $correctCount = 0;
        $mcAndTfCount = 0;
        foreach($quiz->questions as $q) {
            if ($q->type !== \App\Enums\QuestionType::ESSAY) {
                $mcAndTfCount++;
                $studentAns = $attempt->answers->firstWhere('question_id', $q->id);
                $correctOpt = $q->options->firstWhere('is_correct', true);
                if ($studentAns && $correctOpt && $studentAns->option_id === $correctOpt->id) {
                    $correctCount++;
                }
            }
        }

        $percentage = $totalMarks > 0 ? round(($attempt->score / $totalMarks) * 100) : 0;
    @endphp

    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-4xl mx-auto space-y-8">

            <div class="flex items-center">
                <a href="{{ route('student.quizzes.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary dark:text-slate-400 transition-colors">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    {{ __('Quay lại danh sách bài kiểm tra') }}
                </a>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm text-center relative overflow-hidden space-y-6">
                <div class="absolute -right-20 -top-20 size-56 rounded-full bg-emerald-500/5 blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 size-56 rounded-full bg-primary/5 blur-3xl"></div>

                <div class="space-y-2 relative z-10">
                    <div class="size-16 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto mb-4 animate-bounce">
                        <span class="material-symbols-outlined text-3xl">emoji_events</span>
                    </div>
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ $quiz->course->title }}</span>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white leading-tight">
                        {{ __('Kết quả: :title', ['title' => $quiz->title]) }}
                    </h1>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-slate-50 dark:bg-slate-850/30 rounded-2xl border border-slate-100 dark:border-slate-800/30 relative z-10">
                    <div class="text-center space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Điểm số') }}</p>
                        <p class="text-2xl font-black text-emerald-500">
                            {{ $attempt->score }}<span class="text-xs font-semibold text-slate-400"> / {{ $totalMarks }}</span>
                        </p>
                    </div>
                    <div class="text-center space-y-1 border-l border-slate-200 dark:border-slate-800/50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Tỉ lệ đúng') }}</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">
                            {{ $percentage }}%
                        </p>
                    </div>
                    <div class="text-center space-y-1 border-l border-slate-200 dark:border-slate-850/50 md:border-l-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Số câu trắc nghiệm đúng') }}</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">
                            {{ $correctCount }}<span class="text-xs font-semibold text-slate-400"> / {{ $mcAndTfCount }}</span>
                        </p>
                    </div>
                    <div class="text-center space-y-1 border-l border-slate-200 dark:border-slate-800/50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Thời gian làm') }}</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white text-sm py-1">
                            {{ __(':min phút :sec giây', ['min' => $durationMinutes, 'sec' => $durationRemainingSeconds]) }}
                        </p>
                    </div>
                </div>
            </div>

            @if($quiz->audio_url)
            <div class="bg-gradient-to-r from-amber-500/10 via-primary/10 to-orange-500/10 dark:from-amber-900/20 dark:via-primary/20 dark:to-orange-900/20 rounded-3xl border border-primary/20 p-5 shadow-sm space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="size-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-lg">headphones</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            {{ __('File nghe toàn bài thi') }}
                            <span class="px-2 py-0.5 rounded-full bg-primary/20 text-primary text-[10px] font-extrabold uppercase tracking-wide">{{ __('Phần nghe') }}</span>
                        </h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Bạn có thể nghe lại file âm thanh toàn bài thi khi xem lại lời giải chi tiết.') }}</p>
                    </div>
                </div>
                <div class="bg-white/80 dark:bg-slate-900/80 rounded-2xl p-2 border border-primary/20">
                    <audio controls class="w-full h-10" preload="none">
                        <source src="{{ $quiz->audio_url }}" type="audio/mpeg">
                        <source src="{{ $quiz->audio_url }}" type="audio/wav">
                        <source src="{{ $quiz->audio_url }}" type="audio/mp4">
                        {{ __('Trình duyệt không hỗ trợ phát âm thanh.') }}
                    </audio>
                </div>
            </div>
            @endif

            <div class="space-y-6">
                <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ __('Chi tiết bài thi') }}</h2>

                @foreach($quiz->questions as $index => $question)
                    @php
                        $studentAns = $attempt->answers->firstWhere('question_id', $question->id);
                        $isCorrect = false;

                        if ($question->type !== \App\Enums\QuestionType::ESSAY) {
                            $correctOpt = $question->options->firstWhere('is_correct', true);
                            $isCorrect = $studentAns && $correctOpt && $studentAns->option_id === $correctOpt->id;
                        }
                    @endphp

                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-5">

                        <div class="flex justify-between items-start gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
                            <div class="space-y-0.5">
                                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">{{ __('Câu hỏi :num', ['num' => $index + 1]) }}</span>
                                <span class="text-xs text-slate-400 font-semibold ml-2">({{ __(':marks điểm', ['marks' => $question->marks]) }})</span>
                            </div>

                            @if($question->type === \App\Enums\QuestionType::ESSAY)
                                @if($attempt->grading_status === 'needs_grading' && ($studentAns?->marks_obtained === null))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase">
                                        <span class="material-symbols-outlined text-[14px]">pending</span>
                                        {{ __('Chờ giáo viên chấm') }}
                                    </span>
                                @elseif($studentAns?->marks_obtained !== null)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase">
                                        <span class="material-symbols-outlined text-[14px]">grade</span>
                                        {{ __(':marks / :max điểm', ['marks' => $studentAns->marks_obtained, 'max' => $question->marks]) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-xs font-bold uppercase">
                                        <span class="material-symbols-outlined text-[14px]">edit_note</span>
                                        {{ __('Tự luận') }}
                                    </span>
                                @endif
                            @elseif($isCorrect)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-xs font-bold uppercase">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    {{ __('Đúng') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-xs font-bold uppercase">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    {{ __('Sai') }}
                                </span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-relaxed">
                                {!! nl2br(e($question->question_text)) !!}
                            </div>

                            @if($question->image_path)
                                <div class="max-w-md overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 mt-2">
                                    <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question visual" class="max-h-60 w-auto object-contain">
                                </div>
                            @endif

                            @if($question->audio_path)
                                <div class="w-full max-w-sm bg-slate-50 dark:bg-slate-800/40 p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/30 mt-2">
                                    <audio controls class="w-full">
                                        <source src="{{ asset('storage/' . $question->audio_path) }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3 pt-2">

                            @if($question->type === \App\Enums\QuestionType::MULTIPLE_CHOICE || $question->type === \App\Enums\QuestionType::TRUE_FALSE)
                                <div class="grid gap-2">
                                    @foreach($question->options as $option)
                                        @php
                                            $isStudentChoice = $studentAns && $studentAns->option_id === $option->id;
                                            $isCorrectChoice = $option->is_correct;
                                        @endphp

                                        <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl border text-sm font-semibold transition-all
                                            @if($isCorrectChoice)
                                                bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-400
                                            @elseif($isStudentChoice && !$isCorrectChoice)
                                                bg-red-500/10 border-red-500/30 text-red-600 dark:text-red-400
                                            @else
                                                bg-slate-50/50 border-slate-100 dark:bg-slate-800/20 dark:border-slate-800 text-slate-600 dark:text-slate-400
                                            @endif">

                                            <div class="flex items-center gap-3">
                                                <div class="size-4 rounded-full border-2 flex items-center justify-center shrink-0
                                                    @if($isCorrectChoice) border-emerald-500 @elseif($isStudentChoice) border-red-500 @else border-slate-300 dark:border-slate-600 @endif">
                                                    @if($isCorrectChoice || $isStudentChoice)
                                                        <div class="size-2 rounded-full @if($isCorrectChoice) bg-emerald-500 @else bg-red-500 @endif"></div>
                                                    @endif
                                                </div>
                                                <span>{{ $option->option_text }}</span>
                                            </div>

                                            <div class="flex items-center shrink-0">
                                                @if($isCorrectChoice)
                                                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-500 text-white tracking-wider">{{ __('Đáp án đúng') }}</span>
                                                @elseif($isStudentChoice)
                                                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-red-500 text-white tracking-wider">{{ __('Lựa chọn của bạn') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @elseif($question->type === \App\Enums\QuestionType::ESSAY)
                                <div class="space-y-3">
                                    <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/30">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Bài làm của bạn:') }}</p>
                                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300 whitespace-pre-wrap">
                                            {{ $studentAns->text_answer ?? __('(Không có câu trả lời)') }}
                                        </div>
                                    </div>

                                    @if($question->correct_answer_text)
                                        <div class="bg-emerald-500/5 dark:bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/20">
                                            <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">{{ __('Đáp án mẫu / Gợi ý:') }}</p>
                                            <div class="text-sm font-semibold text-emerald-700 dark:text-emerald-300 whitespace-pre-wrap">
                                                {{ $question->correct_answer_text }}
                                            </div>
                                        </div>
                                    @endif

                                    @if($studentAns && $studentAns->teacher_feedback)
                                        <div class="bg-amber-500/5 dark:bg-amber-500/10 p-4 rounded-2xl border border-amber-500/20">
                                            <p class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">rate_review</span>
                                                {{ __('Nhận xét của giáo viên:') }}
                                            </p>
                                            <div class="text-sm font-semibold text-amber-800 dark:text-amber-200 whitespace-pre-wrap">
                                                {{ $studentAns->teacher_feedback }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('student.quizzes.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-[1.01] active:scale-[0.99] transition-all">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    {{ __('Quay lại danh sách') }}
                </a>
            </div>

        </div>
    </main>
@endsection
