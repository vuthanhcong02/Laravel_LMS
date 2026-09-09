@extends('layouts.lms')

@section('title', __('Kết quả thi thử') . ' ' . ($result->mockExam->hskLevel->title ?? ('HSK ' . $level)) . ' - ' . ($result->mockExam->title ?? '') . ' - XiaoMu LMS')

@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Luyện thi HSK'), 'url' => route('student.hsk-mock-exams.index')],
        ['label' => $result->mockExam->hskLevel->title ?? ('HSK ' . $level), 'url' => route('student.hsk-mock-exams.show', ['level' => $level])],
        ['label' => __('Kết quả bài thi'), 'url' => null]
    ]" />
@endsection

@section('content')
@php
    $levelCode = strtolower($result->mockExam->hskLevel->level_code ?? 'hsk' . $level);
    $maxScore = in_array($levelCode, ['hsk1', 'hsk2']) ? 200 : 300;
    $passScore = in_array($levelCode, ['hsk1', 'hsk2']) ? 120 : 180;
    $isPassed = $result->total_score >= $passScore;

    $durationSeconds = 0;
    if ($result->completed_at && $result->started_at) {
        $durationSeconds = \Carbon\Carbon::parse($result->started_at)->diffInSeconds(\Carbon\Carbon::parse($result->completed_at));
        $maxDurationSeconds = ($result->mockExam->duration ?? 0) * 60;
        if ($maxDurationSeconds > 0 && $durationSeconds > $maxDurationSeconds) {
            $durationSeconds = $maxDurationSeconds;
        }
    }
    $formattedDuration = sprintf('%02d:%02d', floor($durationSeconds / 60), $durationSeconds % 60);

    $totalAnswers = $result->userAnswers->count();
    $correctAnswers = $result->userAnswers->where('is_correct', true)->count();
    $incorrectAnswers = $totalAnswers - $correctAnswers;
    $accuracyPercent = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100) : 0;

    // Lọc bỏ câu ví dụ (is_example = true) nếu có và sắp xếp câu trả lời chuẩn theo thứ tự phòng thi
    $sortedUserAnswers = $result->userAnswers
        ->filter(function($ua) {
            return $ua->question && !$ua->question->is_example;
        })
        ->sortBy(function($ua) {
            return [
                $ua->question->hskMockExamSection->order_index ?? 0,
                $ua->question->group->order_index ?? 0,
                $ua->question->order_index ?? $ua->id
            ];
        })
        ->values();

    // Gán số thứ tự câu hỏi hiển thị liên tục (1, 2, ..., N) chuẩn như phòng thi
    foreach ($sortedUserAnswers as $idx => $ua) {
        $ua->display_qnum = $idx + 1;
    }

    $totalAnswers = $sortedUserAnswers->count();
    $correctAnswers = $sortedUserAnswers->where('is_correct', true)->count();
    $incorrectAnswers = $totalAnswers - $correctAnswers;
    $accuracyPercent = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100) : 0;

    $sectionNameMap = [
        'listening' => __('Phần thi Nghe hiểu'),
        'reading'   => __('Phần thi Đọc hiểu'),
        'writing'   => __('Phần thi Viết'),
        'speaking'  => __('Phần thi Nói'),
    ];

    $userAnswersBySection = $sortedUserAnswers->groupBy(function($ua) use ($sectionNameMap) {
        $rawName = strtolower(trim($ua->question->hskMockExamSection->name ?? ''));
        return $sectionNameMap[$rawName] ?? ($ua->question->hskMockExamSection->name ?? __('Phần thi'));
    });

    $displayOption = function($opt, $q, $ua = null) {
        if (!$opt) {
            if ($ua && !empty($ua->text_answer)) {
                $textAns = trim($ua->text_answer);
                // Kiểm tra nếu text_answer là ký tự A-F đối chiếu với ngân hàng đáp án của nhóm
                $group = $q->group;
                if ($group && !empty($group->passage_text) && str_starts_with(trim($group->passage_text), '{')) {
                    $parsedEx = json_decode(trim($group->passage_text), true);
                    if (!empty($parsedEx['options']) && is_array($parsedEx['options'])) {
                        foreach ($parsedEx['options'] as $pOpt) {
                            if (isset($pOpt['letter']) && strtoupper($pOpt['letter']) === strtoupper($textAns)) {
                                $optText = $pOpt['html'] ?? $pOpt['hanzi'] ?? '';
                                if (!empty($optText)) {
                                    $formatted = function_exists('renderHskRubyText') ? renderHskRubyText($optText) : e($optText);
                                    return strtoupper($textAns) . '. ' . $formatted;
                                }
                            }
                        }
                    }
                }
                return e($textAns);
            }
            return '<span class="italic text-slate-400 dark:text-slate-500">' . __('Chưa trả lời') . '</span>';
        }

        $isTrueFalse = $q->options->count() == 2 && 
            ($q->options[0]->content === '√' || $q->options[0]->content === '×');
        if ($isTrueFalse) {
            return e(trim($opt->content));
        }

        $letter = chr(65 + ($opt->order_index - 1));
        $content = trim($opt->content ?? '');

        // Kiểm tra nếu nhóm câu hỏi có ngân hàng đáp án dạng JSON trong passage_text
        $group = $q->group;
        if ($group && !empty($group->passage_text) && str_starts_with(trim($group->passage_text), '{')) {
            $parsedEx = json_decode(trim($group->passage_text), true);
            if (!empty($parsedEx['options']) && is_array($parsedEx['options'])) {
                $searchLetter = (!empty($content) && strlen($content) === 1 && ctype_alpha($content)) ? strtoupper($content) : $letter;
                foreach ($parsedEx['options'] as $pOpt) {
                    if (isset($pOpt['letter']) && strtoupper($pOpt['letter']) === $searchLetter) {
                        $matchedText = $pOpt['html'] ?? $pOpt['hanzi'] ?? '';
                        if (!empty($matchedText)) {
                            $content = $matchedText;
                            $letter = $searchLetter;
                            break;
                        }
                    }
                }
            }
        }

        if (empty($content) || strtoupper($content) === $letter) {
            return $letter;
        }

        $formattedContent = function_exists('renderHskRubyText') ? renderHskRubyText($content) : e($content);
        return $letter . '. ' . $formattedContent;
    };
@endphp

<div x-data="hskResultViewer" class="space-y-6 pb-12 relative">

    <!-- Top Action Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('student.hsk-mock-exams.show', ['level' => $level]) }}" 
           class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-[#e07a5f] bg-white dark:bg-[#181615] px-4 py-2.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs btn-tactile w-fit transition-colors">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>{{ __('Danh sách đề') }} {{ strtoupper($levelCode) }}</span>
        </a>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('student.hsk-mock-exams.start', ['level' => $level, 'id' => $result->hsk_mock_exam_id]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs transition-colors">
                <i class="fa-solid fa-rotate-right text-[11px]"></i>
                <span>{{ __('Làm lại đề này') }}</span>
            </a>

            <a href="{{ route('student.hsk-mock-exams.index') }}#leaderboard-section" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:border-[#e07a5f] hover:text-[#e07a5f] text-xs font-bold btn-tactile shadow-xs transition-all">
                <i class="fa-solid fa-trophy text-amber-500 text-[11px]"></i>
                <span>{{ __('Bảng xếp hạng') }}</span>
            </a>
        </div>
    </div>

    @php
        $scorePercent = $maxScore > 0 ? min(100, max(0, round(($result->total_score / $maxScore) * 100))) : 0;
        $circumference = 238.76; // 2 * pi * 38
        $strokeDashoffset = $circumference - ($circumference * ($scorePercent / 100));
        $strokeColor = $isPassed ? '#10b981' : '#e07a5f';
    @endphp

    <!-- Overall Hero Scorecard Card (Clean & Luxury LMS Design) -->
    <div class="lms-card p-6 sm:p-7 relative overflow-hidden bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926]">
        <!-- Subtle background accent aura -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 {{ $isPassed ? 'bg-emerald-500/10' : 'bg-[#e07a5f]/10' }} rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left: Score Donut & Status Info -->
            <div class="flex items-center gap-5 sm:gap-6 flex-col sm:flex-row text-center sm:text-left">
                <!-- SVG Score Donut Ring -->
                <div class="relative w-24 h-24 sm:w-28 sm:h-28 shrink-0 flex items-center justify-center">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 96 96">
                        <!-- Background Circle Track -->
                        <circle cx="48" cy="48" r="38" 
                                fill="transparent" 
                                class="stroke-[#f0eae1] dark:stroke-[#282421]" 
                                stroke-width="8" />
                        <!-- Progress Arc -->
                        <circle cx="48" cy="48" r="38" 
                                fill="transparent" 
                                stroke="{{ $strokeColor }}" 
                                stroke-width="8" 
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}" 
                                stroke-dashoffset="{{ $strokeDashoffset }}"
                                class="transition-all duration-1000 ease-out" />
                    </svg>

                    <!-- Center Score Display -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white leading-none">
                            {{ $result->total_score }}
                        </span>
                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 mt-0.5">
                            / {{ $maxScore }}
                        </span>
                    </div>
                </div>

                <!-- Status Text & Exam Metadata -->
                <div class="space-y-1.5">
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-semibold text-slate-600 dark:text-slate-300">
                        <span class="font-bold text-[#e07a5f]">{{ strtoupper($levelCode) }}</span>
                        <span>•</span>
                        <span class="truncate max-w-[200px] sm:max-w-xs">{{ $result->mockExam->title ?? __('Đề thi chuẩn') }}</span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white flex items-center justify-center sm:justify-start gap-2">
                        @if($isPassed)
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <span class="text-emerald-600 dark:text-emerald-400">{{ __('Chúc mừng! Bạn đã đạt yêu cầu') }}</span>
                        @else
                            <i class="fa-solid fa-circle-exclamation text-[#e07a5f]"></i>
                            <span>{{ __('Chưa đạt yêu cầu') }}</span>
                        @endif
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md">
                        {{ __('Điểm thi đạt') }} <strong class="text-slate-800 dark:text-slate-200 font-bold">{{ $result->total_score }}/{{ $maxScore }}</strong> {{ __('điểm') }}
                        · {{ __('Yêu cầu qua môn') }}: <strong class="text-slate-800 dark:text-slate-200 font-bold">{{ $passScore }}</strong> {{ __('điểm') }}
                    </p>
                </div>
            </div>

            <!-- Right: 3 Inset Stat Cards -->
            <div class="grid grid-cols-3 gap-2.5 sm:gap-3 shrink-0">
                <!-- Duration Inset -->
                <div class="p-3.5 sm:p-4 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center min-w-[100px] sm:min-w-[115px]">
                    <div class="flex items-center justify-center gap-1.5 text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                        <i class="fa-regular fa-clock text-[#0284c7]"></i>
                        <span>{{ __('Thời gian') }}</span>
                    </div>
                    <div class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mt-1">
                        {{ $formattedDuration }}
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 font-medium">
                        / {{ $result->mockExam->duration ?? 40 }} {{ __('phút') }}
                    </div>
                </div>

                <!-- Correct Answers Inset -->
                <div class="p-3.5 sm:p-4 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center min-w-[100px] sm:min-w-[115px]">
                    <div class="flex items-center justify-center gap-1.5 text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-check text-emerald-500"></i>
                        <span>{{ __('Câu đúng') }}</span>
                    </div>
                    <div class="text-lg sm:text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ $correctAnswers }}<span class="text-xs text-slate-400 dark:text-slate-500 font-semibold">/{{ $totalAnswers }}</span>
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 font-medium">
                        {{ $incorrectAnswers }} {{ __('câu sai') }}
                    </div>
                </div>

                <!-- Accuracy Rate Inset -->
                <div class="p-3.5 sm:p-4 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center min-w-[100px] sm:min-w-[115px]">
                    <div class="flex items-center justify-center gap-1.5 text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-bullseye text-[#e07a5f]"></i>
                        <span>{{ __('Chính xác') }}</span>
                    </div>
                    <div class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mt-1">
                        {{ $accuracyPercent }}%
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-[#2d2926] h-1.5 rounded-full mt-1.5 overflow-hidden">
                        <div class="bg-[#e07a5f] h-full rounded-full transition-all duration-500" style="width: {{ $accuracyPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Skill Score Breakdown Grid (Listening, Reading, Writing) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
        <!-- Listening Skill Card -->
        <div class="lms-card p-5 space-y-3.5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-[#0284c7] flex items-center justify-center text-base shrink-0">
                        <i class="fa-solid fa-headphones"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">{{ __('Phần thi Nghe hiểu') }}</h3>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">{{ __('Listening Section') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xl font-bold text-slate-900 dark:text-white">{{ $result->listening_score }}</span>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold">/100</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="h-2 bg-slate-100 dark:bg-[#23201e] rounded-full overflow-hidden">
                    <div class="h-full bg-[#0284c7] rounded-full transition-all duration-700 ease-out" 
                         style="width: {{ min(100, max(0, $result->listening_score)) }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                    <span>{{ __('Tỷ lệ đạt được') }}</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $result->listening_score }}%</span>
                </div>
            </div>
        </div>

        <!-- Reading Skill Card -->
        <div class="lms-card p-5 space-y-3.5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">{{ __('Phần thi Đọc hiểu') }}</h3>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">{{ __('Reading Section') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xl font-bold text-slate-900 dark:text-white">{{ $result->reading_score }}</span>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold">/100</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="h-2 bg-slate-100 dark:bg-[#23201e] rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-700 ease-out" 
                         style="width: {{ min(100, max(0, $result->reading_score)) }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                    <span>{{ __('Tỷ lệ đạt được') }}</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $result->reading_score }}%</span>
                </div>
            </div>
        </div>

        <!-- Writing Skill Card (HSK 3 - 6) OR Info Card (HSK 1 - 2) -->
        @if(in_array($levelCode, ['hsk3', 'hsk4', 'hsk5', 'hsk6']))
            <div class="lms-card p-5 space-y-3.5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/50 text-[#e07a5f] flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-pen-nib"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm">{{ __('Phần thi Viết') }}</h3>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">{{ __('Writing Section') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-slate-900 dark:text-white">{{ $result->writing_score }}</span>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold">/100</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="h-2 bg-slate-100 dark:bg-[#23201e] rounded-full overflow-hidden">
                        <div class="h-full bg-[#e07a5f] rounded-full transition-all duration-700 ease-out" 
                             style="width: {{ min(100, max(0, $result->writing_score)) }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                        <span>{{ __('Tỷ lệ đạt được') }}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $result->writing_score }}%</span>
                    </div>
                </div>
            </div>
        @else
            <div class="lms-card p-5 flex flex-col items-center justify-center text-center space-y-2 bg-[#fcfaf7] dark:bg-[#23201e] border-dashed border-[#e8e2d9] dark:border-[#2d2926]">
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-[#181615] text-slate-400 dark:text-slate-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Không có phần Viết') }}</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 max-w-xs">
                        {{ __('Đề thi HSK 1 và 2 chỉ kiểm tra 2 kỹ năng Nghe hiểu và Đọc hiểu.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Review Section Header & Filter Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
        <div class="flex items-center gap-2.5">
            <div class="w-2.5 h-6 bg-[#e07a5f] rounded-full"></div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                {{ __('Chi tiết đáp án & Giải thích') }}
            </h2>
            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold text-slate-600 dark:text-slate-300">
                {{ $totalAnswers }} {{ __('câu') }}
            </span>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            <!-- Mobile Toggle Palette Button -->
            <button @click="mobilePaletteOpen = !mobilePaletteOpen" 
                    type="button"
                    class="lg:hidden px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] text-xs font-bold btn-tactile shadow-2xs flex items-center gap-1.5">
                <i class="fa-solid fa-list-ol text-xs text-[#e07a5f]"></i>
                <span>{{ __('Bảng câu hỏi') }}</span>
            </button>

            <!-- Filter Buttons: All / Correct / Incorrect -->
            <div class="inline-flex p-1 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xs">
                <button @click="filter = 'all'" 
                        :class="filter === 'all' ? 'bg-[#e07a5f] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'"
                        class="px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                    <span>{{ __('Tất cả') }}</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="filter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-[#25211e] text-slate-500'">{{ $totalAnswers }}</span>
                </button>

                <button @click="filter = 'correct'" 
                        :class="filter === 'correct' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'"
                        class="px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-[10px]"></i>
                    <span>{{ __('Đúng') }}</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="filter === 'correct' ? 'bg-white/20 text-white' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400'">{{ $correctAnswers }}</span>
                </button>

                <button @click="filter = 'incorrect'" 
                        :class="filter === 'incorrect' ? 'bg-rose-600 text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'"
                        class="px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                    <span>{{ __('Sai / Chưa làm') }}</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px]" :class="filter === 'incorrect' ? 'bg-white/20 text-white' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400'">{{ $incorrectAnswers }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area: 2-Column Grid (Question List & Sticky Navigation Palette) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT: QUESTION REVIEW ACCORDION LIST (col-span-8) -->
        <div class="lg:col-span-8 space-y-4">
            @foreach($userAnswersBySection as $sectionName => $userAnswers)
                @php
                    $sectionCorrectCount = $userAnswers->where('is_correct', true)->count();
                    $sectionTotalCount = $userAnswers->count();
                    $sectionKey = 'sec_' . \Illuminate\Support\Str::slug($sectionName);
                @endphp

                <div class="lms-card overflow-hidden bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">
                    <!-- Section Header Accordion -->
                    <div @click="collapsedSections['{{ $sectionKey }}'] = !collapsedSections['{{ $sectionKey }}']" 
                         class="px-5 py-4 bg-[#faf8f5] dark:bg-[#151413] border-b border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between cursor-pointer select-none hover:bg-[#f5f1eb] dark:hover:bg-[#1a1817] transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                               :class="{ '-rotate-90': collapsedSections['{{ $sectionKey }}'] }"></i>
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wide">
                                {{ $sectionName }}
                            </h3>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $sectionCorrectCount === $sectionTotalCount ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40' : 'bg-white dark:bg-[#25211e] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]' }}">
                                {{ __('Đúng') }} {{ $sectionCorrectCount }}/{{ $sectionTotalCount }} {{ __('câu') }}
                            </span>
                        </div>
                    </div>

                    <!-- Section Questions Body -->
                    <div x-show="!collapsedSections['{{ $sectionKey }}']" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="divide-y divide-[#f0eae1] dark:divide-[#262220]">
                        @foreach($userAnswers as $ua)
                            @php
                                $question = $ua->question;
                                $group = $question->group;
                                $correctOption = $question->options->firstWhere('is_correct', true);
                                $selectedOption = $ua->option;
                                $isCorrect = (bool) $ua->is_correct;
                                $displayQNum = $ua->display_qnum;
                                $qAnchorId = 'q-res-' . $displayQNum;
                            @endphp

                            <div id="{{ $qAnchorId }}"
                                 x-show="filter === 'all' || (filter === 'correct' && {{ $isCorrect ? 'true' : 'false' }}) || (filter === 'incorrect' && {{ !$isCorrect ? 'true' : 'false' }})"
                                 :class="highlightedQ === '{{ $qAnchorId }}' ? 'bg-[#fff4ef] dark:bg-[#2c1d18] border-l-4 border-l-[#e07a5f] shadow-inner ring-1 ring-inset ring-[#e07a5f]/40' : 'border-l-4 border-l-transparent'"
                                 class="p-5 sm:p-6 space-y-4 hover:bg-[#fcfaf7]/50 dark:hover:bg-[#1a1817]/40 transition-all duration-300 scroll-mt-24">
                                
                                <!-- Question Meta & Title -->
                                <div class="flex items-start gap-3.5">
                                    <!-- Status Badge Icon -->
                                    <div class="w-7 h-7 shrink-0 rounded-xl flex items-center justify-center text-white text-xs font-bold shadow-xs mt-0.5 {{ $isCorrect ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-rose-500 shadow-rose-500/20' }}">
                                        @if($isCorrect)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid fa-xmark"></i>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0 space-y-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-bold text-slate-900 dark:text-white bg-slate-100 dark:bg-[#25211e] px-2.5 py-0.5 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926]">
                                                {{ __('Câu') }} {{ $displayQNum }}
                                            </span>

                                            @if($question->points)
                                                <span class="text-[11px] font-semibold text-slate-400">
                                                    ({{ $question->points }} {{ __('điểm') }})
                                                </span>
                                            @endif

                                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-md {{ $isCorrect ? 'text-emerald-700 bg-emerald-50 dark:bg-emerald-950/40 dark:text-emerald-400' : 'text-rose-700 bg-rose-50 dark:bg-rose-950/40 dark:text-rose-400' }}">
                                                {{ $isCorrect ? __('Chính xác') : __('Chưa chính xác') }}
                                            </span>
                                        </div>

                                        <!-- Passage / Context Audio if group has it -->
                                        @if($group && !empty($group->passage_audio))
                                            <div class="pt-1 pb-2">
                                                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] max-w-md">
                                                    <i class="fa-solid fa-volume-high text-indigo-500 text-sm pl-1"></i>
                                                    <audio controls class="h-8 flex-1 outline-none">
                                                        <source src="{{ hsk_storage_url($group->passage_audio) }}">
                                                    </audio>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Question Audio if exists -->
                                        @if(!empty($question->audio_file))
                                            <div class="pt-1 pb-2">
                                                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] max-w-md">
                                                    <i class="fa-solid fa-headphones text-indigo-500 text-sm pl-1"></i>
                                                    <audio controls class="h-8 flex-1 outline-none">
                                                        <source src="{{ hsk_storage_url($question->audio_file) }}">
                                                    </audio>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Question Image if exists -->
                                        @if(!empty($question->image))
                                            <div class="py-1">
                                                <img src="{{ hsk_storage_url($question->image) }}" 
                                                     alt="Question Image" 
                                                     class="max-h-48 max-w-full sm:max-w-sm rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] object-contain bg-white dark:bg-[#25211e] p-1.5 shadow-xs">
                                            </div>
                                        @endif

                                        <!-- Group passage text if exists (Only render real reading passages, ignore raw JSON) -->
                                        @if($group && !empty($group->passage_text) && !str_starts_with(trim($group->passage_text), '{'))
                                            <div class="p-3.5 rounded-xl bg-[#faf8f5] dark:bg-[#201d1a] border border-[#e8e2d9] dark:border-[#2d2926] text-xs sm:text-sm text-slate-700 dark:text-slate-200 leading-relaxed zh-text font-medium">
                                                {!! function_exists('renderHskRubyText') ? renderHskRubyText($group->passage_text) : nl2br(e($group->passage_text)) !!}
                                            </div>
                                        @endif

                                        <!-- Question Content / Title with Ruby formatting -->
                                        @if(!empty($question->title))
                                            <div class="text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-100 leading-relaxed zh-text pt-0.5">
                                                {!! function_exists('renderHskRubyText') ? renderHskRubyText($question->title) : e($question->title) !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Choice Comparison: User Selected vs Correct Answer -->
                                <div class="sm:pl-10 grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    <!-- User Answer Card -->
                                    <div class="p-3.5 rounded-2xl border {{ $isCorrect ? 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50' : 'bg-rose-50/50 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/50' }}">
                                        <div class="flex items-center justify-between gap-2 mb-1.5">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                                {{ __('Câu trả lời của bạn') }}
                                            </span>
                                            @if($isCorrect)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                                    <i class="fa-solid fa-check"></i> {{ __('Đúng') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 dark:text-rose-400">
                                                    <i class="fa-solid fa-xmark"></i> {{ __('Sai') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 zh-text">
                                            {!! $displayOption($selectedOption, $question, $ua) !!}
                                        </div>
                                    </div>

                                    <!-- Correct Answer Card (if user answered incorrectly) -->
                                    @if(!$isCorrect && $correctOption)
                                        <div class="p-3.5 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/50">
                                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                                                    {{ __('Đáp án chính xác') }}
                                                </span>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                                    <i class="fa-solid fa-circle-check"></i> {{ __('Chuẩn') }}
                                                </span>
                                            </div>
                                            <div class="text-sm font-bold text-emerald-800 dark:text-emerald-300 zh-text">
                                                {!! $displayOption($correctOption, $question) !!}
                                            </div>
                                        </div>
                                    @elseif(!$isCorrect && !$correctOption && $question->options->isNotEmpty())
                                        <div class="p-3.5 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/50">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 block mb-1.5">
                                                {{ __('Đáp án chính xác') }}
                                            </span>
                                            <div class="text-sm font-bold text-emerald-800 dark:text-emerald-300 zh-text">
                                                @foreach($question->options->where('is_correct', true) as $cOpt)
                                                    {!! $displayOption($cOpt, $question) !!}
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Explanation Box if available -->
                                @if(!empty($question->explanation))
                                    <div class="sm:pl-10 pt-1">
                                        <div class="p-4 rounded-2xl bg-[#faf8f5] dark:bg-[#201d1a] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1.5">
                                            <div class="flex items-center gap-2 text-xs font-bold text-[#e07a5f]">
                                                <i class="fa-solid fa-lightbulb"></i>
                                                <span>{{ __('Giải thích chi tiết') }}</span>
                                            </div>
                                            <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                                                {!! nl2br(trim($question->explanation)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- RIGHT: STICKY QUESTION NAVIGATION PALETTE (Desktop Sidebar col-span-4) -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-0 self-start">
            <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-3.5 max-h-[calc(100vh-2rem)] flex flex-col">
                
                <!-- Palette Header -->
                <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926] shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-list-ol text-[#e07a5f] text-sm"></i>
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">
                            {{ __('Bảng câu hỏi') }}
                        </h3>
                    </div>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">
                        {{ $correctAnswers }}/{{ $totalAnswers }} {{ __('câu đúng') }}
                    </span>
                </div>

                <!-- Legend Indicator -->
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-[#faf8f5] dark:bg-[#201d1a] border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-semibold shrink-0">
                    <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                        <span>{{ __('Đúng') }} ({{ $correctAnswers }})</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-rose-700 dark:text-rose-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                        <span>{{ __('Sai') }} ({{ $incorrectAnswers }})</span>
                    </div>
                </div>

                <!-- Section-wise Question Navigation Buttons with Internal Smooth Scroll -->
                <div class="space-y-3.5 pt-1 overflow-y-auto pr-1 flex-1 no-scrollbar hover:scrollbar-thin" style="max-height: calc(100vh - 12rem);">
                    @foreach($userAnswersBySection as $sectionName => $userAnswers)
                        @php
                            $secKey = 'sec_' . \Illuminate\Support\Str::slug($sectionName);
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-600 dark:text-slate-300">
                                <span class="uppercase tracking-wider text-[10px] text-slate-400">{{ $sectionName }}</span>
                                <span class="text-[10px] px-1.5 py-0.2 rounded-md bg-slate-100 dark:bg-[#25211e] text-slate-500">{{ $userAnswers->count() }} {{ __('câu') }}</span>
                            </div>

                            <div class="grid grid-cols-7 xl:grid-cols-8 gap-1.5 justify-items-center">
                                @foreach($userAnswers as $ua)
                                    @php
                                        $qNumber = $ua->display_qnum;
                                        $qAnchor = 'q-res-' . $qNumber;
                                        $isCorrect = (bool) $ua->is_correct;
                                    @endphp
                                    <button type="button"
                                            @click="scrollToQuestion('{{ $qAnchor }}', '{{ $secKey }}', {{ $isCorrect ? 'true' : 'false' }})"
                                            :class="highlightedQ === '{{ $qAnchor }}' ? 'ring-2 ring-[#e07a5f] ring-offset-2 ring-offset-white dark:ring-offset-[#181615] scale-110' : ''"
                                            class="w-8 h-8 rounded-full font-bold text-[11px] flex items-center justify-center transition-all duration-150 btn-tactile shadow-2xs aspect-square shrink-0
                                                {{ $isCorrect 
                                                    ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' 
                                                    : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800 hover:bg-rose-500 hover:text-white hover:border-rose-500' }}"
                                            title="{{ __('Câu') }} {{ $qNumber }}: {{ $isCorrect ? __('Đúng') : __('Sai / Chưa làm') }}">
                                        {{ $qNumber }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </aside>

    </div>

    <!-- MOBILE QUESTION PALETTE DRAWER / MODAL -->
    <div x-show="mobilePaletteOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4 lg:hidden"
         style="display: none;">
        
        <div @click.away="mobilePaletteOpen = false"
             class="w-full sm:max-w-md bg-white dark:bg-[#181615] rounded-t-3xl sm:rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xl max-h-[85vh] flex flex-col overflow-hidden">
            
            <!-- Mobile Drawer Header -->
            <div class="p-4 border-b border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-[#e07a5f]"></i>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Bảng câu hỏi bài thi') }}</h3>
                </div>
                <button type="button" @click="mobilePaletteOpen = false" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-[#25211e] text-slate-500 hover:text-slate-800 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Mobile Drawer Body -->
            <div class="p-4 overflow-y-auto space-y-4">
                <!-- Legend -->
                <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-[#faf8f5] dark:bg-[#201d1a] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-semibold">
                    <span class="text-emerald-600 dark:text-emerald-400">✓ {{ __('Đúng') }}: {{ $correctAnswers }}</span>
                    <span class="text-rose-600 dark:text-rose-400">✕ {{ __('Sai') }}: {{ $incorrectAnswers }}</span>
                </div>

                @foreach($userAnswersBySection as $sectionName => $userAnswers)
                    @php
                        $secKey = 'sec_' . \Illuminate\Support\Str::slug($sectionName);
                    @endphp
                    <div class="space-y-2">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ $sectionName }}</div>
                        <div class="grid grid-cols-6 gap-2 justify-items-center">
                            @foreach($userAnswers as $ua)
                                @php
                                    $qNumber = $ua->display_qnum;
                                    $qAnchor = 'q-res-' . $qNumber;
                                    $isCorrect = (bool) $ua->is_correct;
                                @endphp
                                <button type="button"
                                        @click="scrollToQuestion('{{ $qAnchor }}', '{{ $secKey }}', {{ $isCorrect ? 'true' : 'false' }})"
                                        class="w-10 h-10 rounded-full font-bold text-xs flex items-center justify-center transition-all btn-tactile aspect-square
                                            {{ $isCorrect 
                                                ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800' 
                                                : 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800' }}">
                                    {{ $qNumber }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Actions Card -->
    <div class="lms-card p-6 sm:p-8 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] border border-[#e8e2d9] dark:border-[#2d2926] text-center space-y-4">
        <div class="space-y-1">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                {{ __('Bạn đã hoàn thành bài thi thử này!') }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                {{ __('Hãy tiếp tục luyện tập các đề thi khác hoặc làm lại đề này để cải thiện điểm số và rèn luyện kỹ năng làm bài.') }}
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2 max-w-lg mx-auto">
            <a href="{{ route('student.hsk-mock-exams.start', ['level' => $level, 'id' => $result->hsk_mock_exam_id]) }}" 
               class="w-full sm:w-auto flex-1 py-3 px-6 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm btn-tactile transition-colors inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-rotate-right text-xs"></i>
                <span>{{ __('Làm lại đề thi này') }}</span>
            </a>

            <a href="{{ route('student.hsk-mock-exams.show', ['level' => $level]) }}" 
               class="w-full sm:w-auto flex-1 py-3 px-6 bg-white dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:border-[#e07a5f] hover:text-[#e07a5f] font-bold text-xs sm:text-sm rounded-xl shadow-xs btn-tactile transition-all inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-list-check text-xs"></i>
                <span>{{ __('Danh sách đề') }} {{ strtoupper($levelCode) }}</span>
            </a>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.data('hskResultViewer')) {
            Alpine.data('hskResultViewer', () => ({
                filter: 'all', 
                collapsedSections: {},
                mobilePaletteOpen: false,
                highlightedQ: null,
                scrollToQuestion(qId, sectionKey, isCorrect) {
                    this.collapsedSections[sectionKey] = false;
                    if (this.filter === 'correct' && !isCorrect) {
                        this.filter = 'all';
                    } else if (this.filter === 'incorrect' && isCorrect) {
                        this.filter = 'all';
                    }
                    this.mobilePaletteOpen = false;
                    
                    this.$nextTick(() => {
                        const el = document.getElementById(qId);
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            this.highlightedQ = qId;
                            setTimeout(() => {
                                if (this.highlightedQ === qId) {
                                    this.highlightedQ = null;
                                }
                            }, 2000);
                        }
                    });
                }
            }));
        }
    });
</script>
@endsection
