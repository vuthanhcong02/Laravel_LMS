<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thi thử HSK {{ $level }} - {{ $result->mockExam->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#131a1f] text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">
    @php
        $levelCode = strtolower($result->mockExam->hskLevel->level_code);
        $maxScore = in_array($levelCode, ['hsk1', 'hsk2']) ? 200 : 300;
        $passScore = in_array($levelCode, ['hsk1', 'hsk2']) ? 120 : 180;
        $isPassed = $result->total_score >= $passScore;
        $durationSeconds = 0;
        if ($result->completed_at && $result->started_at) {
            $durationSeconds = \Carbon\Carbon::parse($result->started_at)->diffInSeconds(\Carbon\Carbon::parse($result->completed_at));
            // Giới hạn thời gian hiển thị tối đa bằng thời gian làm bài trong trường hợp bài thi bị bỏ dở
            $maxDurationSeconds = ($result->mockExam->duration ?? 0) * 60;
            if ($maxDurationSeconds > 0 && $durationSeconds > $maxDurationSeconds) {
                $durationSeconds = $maxDurationSeconds;
            }
        }
        $formattedDuration = sprintf('%02d:%02d', floor($durationSeconds / 60), $durationSeconds % 60);
        // Group user answers by section name
        $userAnswersBySection = $result->userAnswers->groupBy(function($ua) {
            return $ua->question->hskMockExamSection->name;
        });
        $displayOption = function($opt, $q, $ua = null) {
            if (!$opt) {
                if ($ua && !empty($ua->text_answer)) {
                    return trim($ua->text_answer);
                }
                return '<span class="italic text-slate-400">Không trả lời</span>';
            }
            $isTrueFalse = $q->options->count() == 2 && 
                ($q->options[0]->content === '√' || $q->options[0]->content === '×');
            if ($isTrueFalse) {
                return trim($opt->content);
            }
            $letter = chr(65 + ($opt->order_index - 1));
            $content = trim($opt->content ?? '');
            if (empty($content) || strtoupper($content) === $letter) {
                return $letter;
            }
            return $letter . '. ' . $content;
        };
    @endphp
    {{-- ===== HEADER ===== --}}
    <header class="bg-white dark:bg-[#1a2332] border-b border-slate-200 dark:border-slate-800 h-16 flex items-center justify-between px-4 md:px-6 shrink-0 z-20 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.hsk-mock-exams.show', ['level' => $level]) }}" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors shrink-0">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <h1 class="font-bold text-slate-800 dark:text-white text-sm md:text-base truncate">
                Kết quả: HSK {{ $level }} · {{ $result->mockExam->title }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('student.hsk-mock-exams.index') }}" class="px-4 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs transition-colors">
                Bảng xếp hạng
            </a>
        </div>
    </header>
    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1 overflow-y-auto py-8">
        <div class="max-w-4xl mx-auto px-4 md:px-6 space-y-8">
            {{-- 🎉 Overall Pass/Fail Status Banner --}}
            <div class="relative overflow-hidden rounded-3xl border shadow-lg transition-all duration-300 {{ $isPassed ? 'bg-gradient-to-br from-emerald-500 to-teal-600 border-emerald-600/20 text-white' : 'bg-gradient-to-br from-rose-500 to-pink-600 border-rose-600/20 text-white' }}">
                <div class="absolute inset-0 bg-white/10 opacity-30 mix-blend-overlay pointer-events-none"></div>
                <div class="p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-4 text-center md:text-left flex-col md:flex-row">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-inner">
                            <span class="material-symbols-outlined text-[36px]">
                                {{ $isPassed ? 'emoji_events' : 'sentiment_dissatisfied' }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-2xl md:text-3xl font-black uppercase tracking-wide leading-tight">
                                {{ $isPassed ? 'Chúc mừng! Bạn đã đạt' : 'Rất tiếc! Chưa đạt' }}
                            </h2>
                            <p class="text-sm text-white/80 font-medium mt-1">
                                Điểm thi đạt {{ $result->total_score }}/{{ $maxScore }} điểm (Yêu cầu qua môn: {{ $passScore }})
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-8 justify-center shrink-0">
                        <div class="text-center">
                            <p class="text-[10px] uppercase font-bold text-white/70 tracking-wider">Thời gian</p>
                            <p class="text-2xl font-black mt-0.5">{{ $formattedDuration }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] uppercase font-bold text-white/70 tracking-wider">Số câu đúng</p>
                            <p class="text-2xl font-black mt-0.5">
                                {{ $result->userAnswers->where('is_correct', true)->count() }}/{{ $result->userAnswers->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 📻 Skill Details Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Listening --}}
                <div class="bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">volume_up</span>
                            </span>
                            <span class="font-bold text-slate-800 dark:text-white text-sm">Nghe hiểu</span>
                        </div>
                        <span class="font-black text-indigo-600 dark:text-indigo-400 text-lg">{{ $result->listening_score }}/100</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full transition-all duration-500" style="width: {{ $result->listening_score }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 text-center font-medium">Tỷ lệ đúng {{ $result->listening_score }}%</p>
                </div>
                {{-- Reading --}}
                <div class="bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-950/40 text-cyan-500 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">menu_book</span>
                            </span>
                            <span class="font-bold text-slate-800 dark:text-white text-sm">Đọc hiểu</span>
                        </div>
                        <span class="font-black text-cyan-600 dark:text-cyan-400 text-lg">{{ $result->reading_score }}/100</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-cyan-500 rounded-full transition-all duration-500" style="width: {{ $result->reading_score }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 text-center font-medium">Tỷ lệ đúng {{ $result->reading_score }}%</p>
                </div>
                {{-- Writing --}}
                @if(in_array($levelCode, ['hsk3', 'hsk4', 'hsk5', 'hsk6']))
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-500 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">edit_note</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white text-sm">Viết</span>
                            </div>
                            <span class="font-black text-amber-600 dark:text-amber-400 text-lg">{{ $result->writing_score }}/100</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $result->writing_score }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 text-center font-medium">Tỷ lệ đúng {{ $result->writing_score }}%</p>
                    </div>
                @else
                    {{-- Info Card instead of writing --}}
                    <div class="bg-slate-100 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700/80 p-5 flex flex-col items-center justify-center text-center space-y-1.5">
                        <span class="material-symbols-outlined text-slate-400">info</span>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Không có phần Viết</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Đề thi HSK cấp 1 và 2 chỉ kiểm tra 2 kỹ năng nghe và đọc hiểu.</p>
                    </div>
                @endif
            </div>
            {{-- 📝 Detailed Answers Review --}}
            <div class="space-y-4">
                <h3 class="font-black text-slate-800 dark:text-white text-base tracking-wide">Xem lại bài làm</h3>
                @foreach($userAnswersBySection as $sectionName => $userAnswers)
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 overflow-hidden shadow-sm">
                        {{-- Section Header --}}
                        <div class="px-5 py-4 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <h4 class="font-bold text-slate-700 dark:text-slate-300 text-sm uppercase tracking-wider">{{ $sectionName }}</h4>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                                Đúng {{ $userAnswers->where('is_correct', true)->count() }}/{{ $userAnswers->count() }} câu
                            </span>
                        </div>
                        {{-- Section Questions --}}
                        <div class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($userAnswers as $uaIdx => $ua)
                                @php
                                    $question = $ua->question;
                                    $correctOption = $question->options->firstWhere('is_correct', true);
                                    $selectedOption = $ua->option;
                                @endphp
                                <div class="p-5 space-y-3">
                                    <div class="flex items-start gap-3">
                                        {{-- Correct/Incorrect Icon --}}
                                        <div class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center text-white {{ $ua->is_correct ? 'bg-emerald-500' : 'bg-rose-500' }}">
                                            <span class="material-symbols-outlined text-[14px]">
                                                {{ $ua->is_correct ? 'check' : 'close' }}
                                            </span>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                            Câu {{ $uaIdx + 1 }}:
                                            @if($question->title)
                                                <span class="font-medium text-slate-600 dark:text-slate-400 ml-1">
                                                    {!! function_exists('renderHskRubyText') ? renderHskRubyText($question->title) : $question->title !!}
                                                </span>
                                            @else
                                                <span class="italic text-slate-400 font-normal ml-1">Câu hỏi âm thanh</span>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Review Choice --}}
                                    <div class="pl-9 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="p-3 rounded-xl border {{ $ua->is_correct ? 'bg-emerald-50/30 dark:bg-emerald-950/10 border-emerald-200 dark:border-emerald-900/50' : 'bg-rose-50/30 dark:bg-rose-950/10 border-rose-200 dark:border-rose-900/50' }}">
                                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider mb-1">Bạn chọn</span>
                                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                                {!! $displayOption($selectedOption, $question, $ua) !!}
                                            </span>
                                        </div>
                                        @if(!$ua->is_correct && $correctOption)
                                            <div class="p-3 rounded-xl bg-emerald-50/30 dark:bg-emerald-950/10 border border-emerald-200 dark:border-emerald-900/50">
                                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider mb-1">Đáp án đúng</span>
                                                <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                                                    {!! $displayOption($correctOption, $question) !!}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Explanation --}}
                                    @if($question->explanation)
                                        <div class="pl-9 text-xs text-slate-500 dark:text-slate-400">
                                            <span class="font-bold text-slate-600 dark:text-slate-300 block mb-1">Giải thích:</span>
                                            <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-700">
                                                {!! trim($question->explanation) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- ===== BOTTOM ACTIONS ===== --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('student.hsk-mock-exams.start', ['level' => $level, 'id' => $result->hsk_mock_exam_id]) }}" class="flex-1 py-3 px-6 bg-primary text-white font-bold text-sm text-center rounded-2xl shadow-md hover:bg-primary/95 active:scale-95 transition-all duration-150 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">replay</span>
                    Làm lại đề này
                </a>
                <a href="{{ route('student.hsk-mock-exams.show', ['level' => $level]) }}" class="flex-1 py-3 px-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm text-center rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    Đề thi khác
                </a>
            </div>
        </div>
    </main>
    {{-- ===== FOOTER ===== --}}
    <footer class="bg-white dark:bg-[#1a2332] border-t border-slate-200 dark:border-slate-800 py-6 text-center text-xs text-slate-400 dark:text-slate-500 shrink-0">
        <p>© 2026 Laravel LMS. Hệ thống chấm điểm thi thử HSK tự động.</p>
    </footer>
</body>
</html>
