@extends('portal.layouts.dashboard')

@section('title', __('Làm bài: ') . $attempt->quiz->title)

@section('header')
@include('portal.student.layouts.header')
@endsection

@section('sidebar')
@include('portal.student.layouts.sidebar')
@endsection

@section('content')
@php
    $quiz = $attempt->quiz;
    $timeLimitSeconds = $quiz->time_limit * 60;
    $elapsedSeconds = now()->diffInSeconds($attempt->started_at);
    $secondsRemaining = max(0, $timeLimitSeconds - $elapsedSeconds);
    if ($quiz->time_limit == 0) {
    $secondsRemaining = -1;
    }
    $initialAnswersJson = json_encode($attempt->answers->mapWithKeys(function($ans) {
    if ($ans->option_id) {
    return [$ans->question_id => ['option_id' => (int)$ans->option_id]];
    }
    return [$ans->question_id => ['text_answer' => $ans->text_answer]];
    }));
@endphp

<main class="flex-1 p-6 lg:p-8 overflow-y-auto"
    x-data="quizAttemptData({{ $secondsRemaining }}, '{{ route('student.quizzes.submit', $attempt->id) }}')">

    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        {{-- Left Side: Questions Container --}}
        <div class="flex-1 space-y-6">

            {{-- Quiz Header Info --}}
            <div style="height: 110px;" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 px-6 py-4 shadow-sm flex flex-col justify-center">
                <div>
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">{{ $quiz->course->title }}</span>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white mt-1 line-clamp-1">{{ $quiz->title }}</h1>
                </div>
            </div>

            {{-- Questions Form --}}
            <form id="quiz-form" action="{{ route('student.quizzes.submit', $attempt->id) }}" method="POST" class="space-y-6">
                @csrf

                @foreach($quiz->questions as $index => $question)
                <div id="question-card-{{ $question->id }}"
                    class="question-card bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6 scroll-mt-24">

                    {{-- Question Header --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-extrabold text-slate-600 dark:text-slate-400 uppercase">
                                {{ __('Câu hỏi :num', ['num' => $index + 1]) }}
                            </span>
                            <span class="text-xs text-slate-400 font-semibold block sm:inline sm:ml-2">({{ __(':marks điểm', ['marks' => $question->marks]) }})</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-primary/10 text-primary text-[11px] font-bold uppercase tracking-wider">
                            {{ $question->type->label() }}
                        </span>
                    </div>

                    {{-- Question Content --}}
                    <div class="space-y-4">
                        <div class="text-base font-bold text-slate-950 dark:text-slate-50 leading-relaxed">
                            {!! nl2br(e($question->question_text)) !!}
                        </div>

                        {{-- Image if exists --}}
                        @if($question->image_path)
                        <div class="max-w-lg overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm mt-3">
                            <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question visual" class="max-h-80 w-auto object-contain mx-auto">
                        </div>
                        @endif

                        {{-- Audio if exists --}}
                        @if($question->audio_path)
                        <div class="w-full max-w-md bg-slate-50 dark:bg-slate-800/40 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/30 mt-3">
                            <audio controls class="w-full">
                                <source src="{{ asset('storage/' . $question->audio_path) }}" type="audio/mpeg">
                                {{ __('Trình duyệt không hỗ trợ phát âm thanh.') }}
                            </audio>
                        </div>
                        @endif
                    </div>

                    {{-- Answers Options --}}
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-6">

                        {{-- Multiple Choice or True/False --}}
                        @if($question->type === \App\Enums\QuestionType::MULTIPLE_CHOICE || $question->type === \App\Enums\QuestionType::TRUE_FALSE)
                        <div class="grid gap-3">
                            @foreach($question->options as $option)
                            <label class="group relative flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-100 dark:border-slate-800 hover:border-primary/20 dark:hover:border-primary/20 bg-slate-50/50 dark:bg-slate-800/20 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-all duration-200"
                                :class="{ 'border-primary dark:border-primary bg-primary/5 dark:bg-primary/5': isSelected({{ $question->id }}, {{ $option->id }}) }">
                                <input type="radio"
                                    name="answers[{{ $question->id }}][option_id]"
                                    value="{{ $option->id }}"
                                    class="peer sr-only"
                                    @change="answerSelected({{ $question->id }}, {{ $option->id }})"
                                    {{ $attempt->answers->firstWhere('question_id', $question->id)?->option_id == $option->id ? 'checked' : '' }}>

                                {{-- Styled Custom Radio --}}
                                <div class="size-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 peer-checked:border-primary transition-colors group-hover:border-primary/40"
                                    :class="{ 'border-primary': isSelected({{ $question->id }}, {{ $option->id }}) }">
                                    <div class="size-2.5 rounded-full bg-primary opacity-0 scale-50 transition-all duration-200"
                                        :class="{ 'opacity-100 scale-100': isSelected({{ $question->id }}, {{ $option->id }}) }"></div>
                                </div>

                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 select-none peer-checked:text-primary dark:peer-checked:text-primary-light">
                                    {{ $option->option_text }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        {{-- Essay --}}
                        @elseif($question->type === \App\Enums\QuestionType::ESSAY)
                        <div class="space-y-2">
                            <textarea name="answers[{{ $question->id }}][text_answer]"
                                rows="6"
                                class="w-full px-4 py-3 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 focus:bg-white dark:focus:bg-slate-900 focus:border-primary focus:ring-0 outline-none text-sm text-slate-800 dark:text-slate-200 transition-all duration-200"
                                placeholder="{{ __('Nhập câu trả lời tự luận của bạn tại đây...') }}"
                                @input="textAnswered({{ $question->id }}, $event.target.value)">{{ $attempt->answers->firstWhere('question_id', $question->id)?->text_answer }}</textarea>
                        </div>
                        @endif

                    </div>
                </div>
                @endforeach

                {{-- Submit Button Area --}}
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-xs text-slate-500">
                        {{ __('Hãy kiểm tra kỹ tất cả các câu trả lời của bạn trước khi bấm nộp bài.') }}
                    </div>
                    <button type="button"
                        @click="confirmSubmit()"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl shadow-lg shadow-primary/10 transition-all">
                        <span class="material-symbols-outlined text-base">publish</span>
                        {{ __('Nộp bài thi') }}
                    </button>
                </div>
            </form>

        </div>

        {{-- Right Side: Countdown Timer & Navigation --}}
        <div class="w-full lg:w-80 shrink-0 space-y-6">

            {{-- Sticky Container --}}
            <div class="lg:sticky space-y-6">

                {{-- Timer Box --}}
                <div style="height: 110px;" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 px-6 py-4 shadow-sm flex flex-col justify-center text-center">
                    <div class="flex items-center justify-center gap-2 text-slate-400 mb-1.5">
                        <span class="material-symbols-outlined text-lg">alarm</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Thời gian còn lại') }}</span>
                    </div>

                    <template x-if="timerSeconds >= 0">
                        <div class="text-3xl font-black font-display tracking-tight text-slate-900 dark:text-white"
                            :class="{ 'text-red-500 animate-pulse': timerSeconds <= 60 }">
                            <span x-text="formatTime()"></span>
                        </div>
                    </template>

                    <template x-if="timerSeconds < 0">
                        <div class="text-sm font-bold text-emerald-500 bg-emerald-500/10 py-1.5 px-3 rounded-full inline-block">
                            {{ __('Không giới hạn thời gian') }}
                        </div>
                    </template>
                </div>

                {{-- Question Navigation --}}
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">
                        {{ __('Tiến trình làm bài') }}
                    </h3>

                    {{-- Question Grid --}}
                    <div class="grid grid-cols-5 gap-2.5">
                        @foreach($quiz->questions as $index => $question)
                        <button type="button"
                            @click="scrollToQuestion({{ $question->id }})"
                            class="aspect-square flex items-center justify-center rounded-xl border-2 text-xs font-bold transition-all duration-200"
                            :class="hasAnswer({{ $question->id }}) 
                                            ? 'bg-primary border-primary text-white shadow-sm shadow-primary/20' 
                                            : 'border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 hover:border-slate-300 dark:hover:border-slate-700 bg-slate-50/50 dark:bg-slate-800/20'">
                            {{ $index + 1 }}
                        </button>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-800 pt-4 flex items-center justify-between text-[11px] text-slate-400">
                        <div class="flex items-center gap-1.5">
                            <div class="size-2.5 rounded bg-primary"></div>
                            <span>{{ __('Đã trả lời') }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="size-2.5 rounded border border-slate-200 dark:border-slate-700"></div>
                            <span>{{ __('Chưa trả lời') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Alpine-controlled Confirm Modal --}}
    <div x-show="showConfirmModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        style="display: none;"
        x-transition>

        <div @click.outside="showConfirmModal = false"
            class="w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl border border-slate-200 dark:border-slate-800 text-center space-y-6">

            <div class="size-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-3xl">question_mark</span>
            </div>

            <div class="space-y-2">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Nộp bài kiểm tra?') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    <span x-text="countAnswered()"></span> / {{ $quiz->questions->count() }} {{ __('câu đã làm.') }}
                    <br>
                    {{ __('Hành động này không thể hoàn tác, bạn có chắc chắn muốn nộp bài thi ngay bây giờ?') }}
                </p>
            </div>

            <div class="flex gap-3 justify-center">
                <button type="button"
                    @click="showConfirmModal = false"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-sm transition-colors">
                    {{ __('Quay lại') }}
                </button>
                <button type="button"
                    @click="forceSubmit()"
                    class="px-6 py-2.5 bg-primary hover:bg-primary/95 text-white font-bold rounded-xl text-sm shadow-md shadow-primary/20 transition-colors">
                    {{ __('Xác nhận nộp') }}
                </button>
            </div>
        </div>
    </div>
</main>

<script>
    function quizAttemptData(remainingSeconds, submitUrl) {
        // Restore answers from request/backend if pre-saved
        const initialAnswers = {!! $initialAnswersJson !!};

        return {
            timerSeconds: remainingSeconds,
            answers: initialAnswers,
            showConfirmModal: false,
            submitted: false,
            submitUrl: submitUrl,

            init() {
                if (this.timerSeconds > 0) {
                    const interval = setInterval(() => {
                        if (this.timerSeconds <= 0) {
                            clearInterval(interval);
                            this.forceSubmit();
                        } else {
                            this.timerSeconds--;
                        }
                    }, 1000);
                }

                window.addEventListener('beforeunload', (e) => {
                    if (!this.submitted) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            },

            formatTime() {
                const hours = Math.floor(this.timerSeconds / 3600);
                const minutes = Math.floor((this.timerSeconds % 3600) / 60);
                const seconds = this.timerSeconds % 60;

                let result = '';
                if (hours > 0) {
                    result += String(hours).padStart(2, '0') + ':';
                }
                result += String(minutes).padStart(2, '0') + ':';
                result += String(seconds).padStart(2, '0');
                return result;
            },

            answerSelected(questionId, optionId) {
                this.answers[questionId] = {
                    option_id: optionId
                };
            },

            textAnswered(questionId, text) {
                if (text.trim() === '') {
                    delete this.answers[questionId];
                } else {
                    this.answers[questionId] = {
                        text_answer: text
                    };
                }
            },

            isSelected(questionId, optionId) {
                return this.answers[questionId] && this.answers[questionId].option_id === optionId;
            },

            hasAnswer(questionId) {
                return this.answers[questionId] !== undefined;
            },

            countAnswered() {
                return Object.keys(this.answers).length;
            },

            scrollToQuestion(questionId) {
                const element = document.getElementById('question-card-' + questionId);
                if (element) {
                    element.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            },

            confirmSubmit() {
                this.showConfirmModal = true;
            },

            forceSubmit() {
                this.submitted = true;
                document.getElementById('quiz-form').submit();
            }
        };
    }
</script>
@endsection