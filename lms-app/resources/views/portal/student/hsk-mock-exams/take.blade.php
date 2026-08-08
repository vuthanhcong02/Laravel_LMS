<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang Thi: {{ $exam->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        body { font-family: 'Inter', 'Noto Sans SC', sans-serif; }
        
        /* Native Ruby pinyin formatting & high-contrast visibility */
        ruby { font-size: 1em; }
        rt { font-size: 0.55em; color: #334155; font-weight: 600; user-select: none; text-align: center; }
        .dark rt { color: #e2e8f0; }

        /* Custom Audio */
        .custom-audio { accent-color: #E8927A; }
        .custom-audio::-webkit-media-controls-panel { background: transparent; }

        /* Card transition */
        .q-card { transition: border-color 0.2s ease, box-shadow 0.2s ease; }
        .q-card:hover { border-color: rgba(232, 146, 122, 0.4); box-shadow: 0 4px 20px -2px rgba(232, 146, 122, 0.08); }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Timer pulse */
        @keyframes timer-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .timer-warning { animation: timer-pulse 1s ease-in-out infinite; }
    </style>
</head>

<body class="bg-slate-50 dark:bg-[#131a1f] text-slate-900 dark:text-slate-100 h-screen overflow-hidden flex flex-col"
    x-data="examTimer()">

    {{-- ===== HEADER ===== --}}
    <header class="bg-white dark:bg-[#1a2332] border-b border-slate-200 dark:border-slate-800 h-16 flex items-center justify-between px-4 md:px-6 shrink-0 z-20 shadow-sm">
        
        {{-- Left: Back & Exam Title --}}
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <button onclick="window.history.back()"
                class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors shrink-0">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
            <div class="min-w-0">
                <h1 class="font-bold text-slate-800 dark:text-white text-sm md:text-base truncate leading-tight">
                    Đề thi HSK {{ $level }} · {{ $exam->title }}
                </h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="h-1.5 w-28 bg-slate-100 dark:bg-slate-700/60 rounded-full overflow-hidden hidden sm:block">
                        <div id="progress-bar" class="h-full bg-primary rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-medium hidden sm:block">
                        <span x-text="Object.keys(answers).length">0</span>/{{ $exam->total_questions ?? 40 }} câu
                    </span>
                </div>
            </div>
        </div>

        {{-- Center: Audio Player --}}
        @if ($exam->audio_file)
        <div class="flex-1 flex justify-center px-4 hidden lg:flex">
            <div class="w-full max-w-xs bg-slate-100 dark:bg-slate-800/80 rounded-xl px-3 py-1 border border-slate-200 dark:border-slate-700 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px] shrink-0">volume_up</span>
                <audio controls controlsList="nodownload" class="custom-audio w-full h-8">
                    <source src="{{ Storage::url($exam->audio_file) }}" type="audio/mpeg">
                </audio>
            </div>
        </div>
        @endif

        {{-- Right: Timer & Submit Button --}}
        <form id="exam-form"
            action="{{ route('student.hsk-mock-exams.submit', ['level' => $level, 'id' => $exam->id]) }}" method="POST"
            class="flex items-center gap-3 flex-1 justify-end">
            @csrf
            {{-- Timer Badge --}}
            <div :class="timeRemaining <= 300 ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800/50 text-rose-600 dark:text-rose-400 timer-warning' : 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300'"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border transition-colors font-mono font-bold text-sm">
                <span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
                <span x-text="formatTime(timeRemaining)">{{ str_pad($exam->duration, 2, '0', STR_PAD_LEFT) }}:00</span>
            </div>

            {{-- Mobile Nav Toggle --}}
            <button type="button" onclick="toggleNavSidebar()"
                class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors md:hidden relative">
                <span class="material-symbols-outlined text-[20px]">grid_view</span>
                <span id="mobile-nav-badge" class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-primary text-white text-[9px] font-bold flex items-center justify-center hidden"
                    x-text="Object.keys(answers).length" x-show="Object.keys(answers).length > 0"></span>
            </button>

            {{-- Submit button --}}
            <button type="button" onclick="confirmSubmit()"
                class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded-xl shadow-sm transition-all duration-150 active:scale-95 flex items-center justify-center gap-1.5 text-sm whitespace-nowrap">
                <span class="material-symbols-outlined text-[16px]">send</span>
                <span class="hidden sm:inline">Nộp bài</span>
            </button>
        </form>
    </header>

    {{-- Mobile Nav Overlay --}}
    <div id="nav-overlay" class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm hidden md:hidden" onclick="toggleNavSidebar()"></div>

    {{-- ===== MAIN CONTENT CONTAINER ===== --}}
    <div class="flex-1 flex overflow-hidden">

        {{-- ===== LEFT: QUESTIONS LIST ===== --}}
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-[#131a1f] scroll-smooth" id="question-container">
            <div class="max-w-3xl mx-auto px-4 md:px-6 py-8 pb-32 space-y-10">

                @php $qCount = 1; @endphp
                @foreach ($exam->sections as $sectionIndex => $section)
                    <div>
                        {{-- Section Title Divider --}}
                        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-primary/20">
                            <span class="w-3 h-3 rounded-full bg-primary shrink-0"></span>
                            <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-wide">
                                Phần {{ $sectionIndex + 1 }}: {{ $section->name }}
                            </h2>
                        </div>

                        @foreach ($section->questionGroups as $groupIndex => $group)
                            <div class="mb-8">

                                {{-- Passage Text (Instructions / Options reference block) --}}
                                @if ($group->passage_text)
                                    @if (!str_starts_with(trim($group->passage_text), '<div'))
                                        <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
                                            <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
                                                <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如) / Hướng dẫn</span>
                                            </div>
                                            <div class="text-base font-bold text-slate-800 dark:text-slate-200 space-y-2">
                                                {!! nl2br($group->passage_text) !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-6">
                                            {!! nl2br($group->passage_text) !!}
                                        </div>
                                    @endif
                                @endif
                                                             {{-- Passage Images Grid (Answer bank images A-F or Part 1 Examples) --}}
                                @if ($group->passage_image)
                                    @php
                                        $passageImages = array_filter(array_map('trim', explode(',', $group->passage_image)));
                                        $imgLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
                                    @endphp
                                    @if($groupIndex == 0 && count($passageImages) == 2)
                                        {{-- Part 1 True/False Examples Card --}}
                                        <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
                                            <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
                                                <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg mx-auto">
                                                <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-xl border border-amber-100 dark:border-slate-700/80 shadow-sm">
                                                    <img src="{{ Storage::url($passageImages[0]) }}" class="h-20 object-contain rounded-lg" alt="Ex 1">
                                                    <span class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-black flex items-center justify-center text-xl shadow-sm">✓</span>
                                                </div>
                                                <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-xl border border-amber-100 dark:border-slate-700/80 shadow-sm">
                                                    <img src="{{ Storage::url($passageImages[1]) }}" class="h-20 object-contain rounded-lg" alt="Ex 2">
                                                    <span class="w-10 h-10 rounded-xl bg-rose-500 text-white font-black flex items-center justify-center text-xl shadow-sm">✕</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($groupIndex == 1 && count($passageImages) == 3)
                                        {{-- Part 2 Multiple Choice Examples Card --}}
                                        <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
                                            <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
                                                <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
                                                <span class="text-xs font-bold text-emerald-600">Đáp án mẫu: A</span>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 max-w-xl mx-auto">
                                                <div class="flex flex-col items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border-2 border-emerald-500 shadow-sm">
                                                    <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-emerald-200 text-xs font-black">
                                                        <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[11px]">A</span>
                                                        <span class="text-emerald-600 text-[11px]">✓ ĐÚNG</span>
                                                    </div>
                                                    <img src="{{ Storage::url($passageImages[0]) }}" class="h-24 object-contain rounded-lg" alt="Ex A">
                                                </div>
                                                <div class="flex flex-col items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 opacity-60">
                                                    <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-slate-100 text-xs font-black text-slate-500">
                                                        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 text-[11px]">B</span>
                                                    </div>
                                                    <img src="{{ Storage::url($passageImages[1]) }}" class="h-24 object-contain rounded-lg" alt="Ex B">
                                                </div>
                                                <div class="flex flex-col items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 opacity-60">
                                                    <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-slate-100 text-xs font-black text-slate-500">
                                                        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 text-[11px]">C</span>
                                                    </div>
                                                    <img src="{{ Storage::url($passageImages[2]) }}" class="h-24 object-contain rounded-lg" alt="Ex C">
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($groupIndex == 2 && count($passageImages) >= 5)
                                        {{-- Part 3 Matching Example Card --}}
                                        <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
                                            <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
                                                <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
                                                <span class="text-xs font-bold text-emerald-600">Đáp án mẫu: C</span>
                                            </div>
                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                            <div class="p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-amber-200/70 dark:border-slate-700 space-y-1.5 flex-1 w-full text-sm font-bold text-slate-800 dark:text-slate-200">
                                                <div class="flex items-start gap-2">
                                                    <span class="text-slate-400">女:</span>
                                                    <div><ruby>你<rt>Nǐ</rt></ruby> <ruby>好<rt>hǎo</rt></ruby>！</div>
                                                </div>
                                                <div class="flex items-start gap-2">
                                                    <span class="text-slate-400">男:</span>
                                                    <div><ruby>你<rt>Nǐ</rt></ruby> <ruby>好<rt>hǎo</rt></ruby>！<ruby>很<rt>Hěn</rt></ruby> <ruby>高<rt>gāo</rt></ruby><ruby>兴<rt>xìng</rt></ruby> <ruby>认<rt>rèn</rt></ruby><ruby>识<rt>shi</rt></ruby> <ruby>你<rt>nǐ</rt></ruby>。</div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border-2 border-emerald-500 shadow-sm max-w-[200px] w-full">
                                                <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-emerald-200 text-xs font-black">
                                                    <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[11px]">C</span>
                                                    <span class="text-emerald-600 text-[11px]">✓ ĐÚNG</span>
                                                </div>
                                                <img src="{{ Storage::url($passageImages[2]) }}" class="h-24 object-contain rounded-xl p-1 bg-white dark:bg-slate-800" alt="Ex C">
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($sectionIndex == 1 && $groupIndex == 2)
                                        {{-- Reading Part 3 (Q31-35) Matching Example Card --}}
                                        @php
                                            $exData = null;
                                            if ($group->passage_text && str_starts_with($group->passage_text, '{')) {
                                                $exData = json_decode($group->passage_text, true);
                                            }
                                            if (!$exData) {
                                                $exData = [
                                                    'q_html' => '<ruby>你<rt>Nǐ</rt></ruby> <ruby>喝<rt>hē</rt></ruby> <ruby>水<rt>shuǐ</rt></ruby> <ruby>吗<rt>ma</rt></ruby> ？',
                                                    'a_letter' => 'F',
                                                    'a_html' => '<ruby>好<rt>Hǎo</rt></ruby> <ruby>的<rt>de</rt></ruby> ， <ruby>谢<rt>xiè</rt></ruby><ruby>谢<rt>xie</rt></ruby> ！'
                                                ];
                                            }
                                        @endphp
                                        <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
                                            <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
                                                <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
                                                <span class="text-xs font-bold text-emerald-600">Đáp án mẫu: {{ $exData['a_letter'] ?? 'F' }}</span>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-amber-200/70 dark:border-slate-700 text-sm font-bold text-slate-800 dark:text-slate-200 shadow-sm flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                                    {!! $exData['q_html'] ?? '' !!}
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border-2 border-emerald-500 shadow-sm flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <span class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-xs font-black">{{ $exData['a_letter'] ?? 'F' }}</span>
                                                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                                {!! $exData['a_html'] ?? '' !!}
                                                            </div>
                                                        </div>
                                                        <span class="text-emerald-600 text-[11px] font-black">✓ ĐÚNG</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($sectionIndex == 0 && $groupIndex == 3)
                                        {{-- Part 4 Multiple Choice Example Card (Listening) --}}
                                        <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
                                            <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
                                                <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
                                                <span class="text-xs font-bold text-emerald-600">Đáp án mẫu: A</span>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-amber-200/70 dark:border-slate-700 space-y-1.5 text-sm font-bold text-slate-800 dark:text-slate-200">
                                                    <div><ruby>下<rt>Xià</rt></ruby><ruby>午<rt>wǔ</rt></ruby> <ruby>我<rt>wǒ</rt></ruby> <ruby>去<rt>qù</rt></ruby> <ruby>商<rt>shāng</rt></ruby><ruby>店<rt>diàn</rt></ruby> ， <ruby>我<rt>wǒ</rt></ruby> <ruby>想<rt>xiǎng</rt></ruby> <ruby>买<rt>mǎi</rt></ruby> <ruby>一<rt>yì</rt></ruby><ruby>些<rt>xiē</rt></ruby> <ruby>水<rt>shuǐ</rt></ruby><ruby>果<rt>guǒ</rt></ruby> 。</div>
                                                    <div><span class="text-slate-400">问：</span> <ruby>她<rt>Tā</rt></ruby> <ruby>下<rt>xià</rt></ruby><ruby>午<rt>wǔ</rt></ruby> <ruby>去<rt>qù</rt></ruby> <ruby>哪<rt>nǎ</rt></ruby><ruby>里<rt>li</rt></ruby> ？</div>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border-2 border-emerald-500 flex flex-col items-center justify-between text-center shadow-sm">
                                                        <div class="flex items-center justify-between w-full pb-1 mb-1 border-b border-emerald-200 text-xs font-black">
                                                            <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[11px]">A</span>
                                                            <span class="text-emerald-600 text-[11px]">✓ ĐÚNG</span>
                                                        </div>
                                                        <div class="text-base font-bold text-slate-900 dark:text-white py-1">
                                                            <ruby>商<rt>shāng</rt></ruby><ruby>店<rt>diàn</rt></ruby>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col items-center justify-between text-center opacity-60">
                                                        <div class="flex items-center justify-between w-full pb-1 mb-1 border-b border-slate-100 text-xs font-black text-slate-500">
                                                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 text-[11px]">B</span>
                                                        </div>
                                                        <div class="text-base font-bold text-slate-900 dark:text-white py-1">
                                                            <ruby>医<rt>yī</rt></ruby><ruby>院<rt>yuàn</rt></ruby>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col items-center justify-between text-center opacity-60">
                                                        <div class="flex items-center justify-between w-full pb-1 mb-1 border-b border-slate-100 text-xs font-black text-slate-500">
                                                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 text-[11px]">C</span>
                                                        </div>
                                                        <div class="text-base font-bold text-slate-900 dark:text-white py-1">
                                                            <ruby>学<rt>xué</rt></ruby><ruby>校<rt>xiào</rt></ruby>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($sectionIndex == 1 && $groupIndex == 3)
                                        {{-- Reading Part 4 (Q36-40) Text Options Bank & Example Card --}}
                                        @php
                                            $part4Data = null;
                                            if ($group->passage_text && str_starts_with($group->passage_text, '{')) {
                                                $part4Data = json_decode($group->passage_text, true);
                                            }
                                        @endphp
                                        @if($part4Data && isset($part4Data['options']))
                                            {{-- Text Options Bank --}}
                                            <div class="mb-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                                                @foreach($part4Data['options'] as $idx => $opt)
                                                    <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center text-center shadow-sm relative overflow-hidden group">
                                                        <div class="absolute top-0 left-0 bg-primary/10 text-primary px-2 py-0.5 rounded-br-lg text-xs font-black">
                                                            {{ chr(65 + $idx) }}
                                                        </div>
                                                        @if(chr(65 + $idx) === ($part4Data['ex_a_letter'] ?? 'D'))
                                                            <div class="absolute top-0 right-0 bg-amber-500 text-white px-1.5 py-0.5 rounded-bl-lg text-[10px] font-bold">
                                                                Ví dụ
                                                            </div>
                                                        @endif
                                                        <div class="mt-4 flex items-center justify-center h-8">
                                                            {!! $opt['html'] ?? '' !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Reading Part 4 Example Card --}}
                                            <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
                                                <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
                                                    <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
                                                    <span class="text-xs font-bold text-emerald-600">Đáp án mẫu: {{ $part4Data['ex_a_letter'] ?? 'D' }}</span>
                                                </div>
                                                <div class="p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-amber-200/70 dark:border-slate-700 text-sm font-bold text-slate-800 dark:text-slate-200 shadow-sm flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                                    <span class="flex-1">{!! $part4Data['ex_q_html'] ?? '' !!} <span class="text-emerald-600 font-black px-1">( {{ $part4Data['ex_a_letter'] ?? 'D' }} )</span> ?</span>
                                                </div>
                                            </div>
                                        @endif
                                    @elseif(count($passageImages) > 1)
                                        <div class="mb-6 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @foreach($passageImages as $idx => $img)
                                                @php
                                                    $label = $imgLabels[$idx] ?? '';
                                                    $isExampleImg = false;
                                                    if ($sectionIndex == 0 && $groupIndex == 2 && $label === 'C') $isExampleImg = true; // Nghe part 3
                                                    if ($sectionIndex == 1 && $groupIndex == 1 && $label === 'E') $isExampleImg = true; // Đọc part 2
                                                @endphp
                                                <div class="relative rounded-2xl border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 p-3 flex flex-col items-center justify-center hover:border-primary/50 transition-all shadow-sm group {{ $isExampleImg ? 'opacity-60 grayscale hover:grayscale-0' : '' }}">
                                                    <div class="absolute top-2.5 left-2.5 w-7 h-7 rounded-lg {{ $isExampleImg ? 'bg-amber-500 text-white' : 'bg-primary/10 text-primary' }} font-black text-xs flex items-center justify-center">
                                                        {{ $label }}
                                                    </div>
                                                    @if($isExampleImg)
                                                        <span class="absolute top-2.5 right-2.5 text-[10px] bg-amber-500 text-white px-1.5 py-0.5 rounded font-bold">Ví dụ</span>
                                                    @endif
                                                    <img src="{{ Storage::url(trim($img)) }}" 
                                                         class="max-h-28 w-full object-contain p-1 group-hover:scale-105 transition-transform duration-200" 
                                                         alt="Option {{ $label }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="mb-6 flex justify-center">
                                            <img src="{{ Storage::url(trim($passageImages[0])) }}" 
                                                 class="rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm max-w-full max-h-64 object-contain bg-white dark:bg-slate-800 p-2" 
                                                 alt="Passage Image">
                                        </div>
                                    @endif
                                @endif

                                {{-- Questions List --}}
                                <div class="space-y-3">
                                    @foreach ($group->questions as $question)
                                        @php
                                            $currentQNum = $qCount++;

                                            // Type 1: True / False (√ vs ×)
                                            $isTrueFalse =
                                                $question->options->count() == 2 &&
                                                ($question->options[0]->content === '√' ||
                                                 $question->options[0]->content === '×');

                                            // Type 2: Options with Images
                                            $isOptionsWithImages =
                                                !$isTrueFalse &&
                                                $question->options->count() > 0 &&
                                                $question->options->every(fn($opt) => !empty($opt->image));

                                            // Type 3: Matching / Letter Options (A, B, C, D, E, F)
                                            $isLetterOptions =
                                                !$isTrueFalse &&
                                                !$isOptionsWithImages &&
                                                ($question->question_type === 'matching' ||
                                                 $question->options->every(fn($opt) =>
                                                     in_array(trim($opt->content), ['A','B','C','D','E','F']) && empty($opt->image)
                                                 ));
                                        @endphp

                                        {{-- ========== TYPE 1: TRUE / FALSE ========== --}}
                                        @if ($isTrueFalse)
                                            <div class="q-card flex items-center justify-between gap-4 scroll-mt-24 p-4 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm"
                                                id="q-{{ $currentQNum }}">

                                                {{-- Left: Number & Content --}}
                                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                                                        {{ $currentQNum }}
                                                    </div>

                                                    @if ($question->image)
                                                        <div class="shrink-0">
                                                            <img src="{{ Storage::url($question->image) }}"
                                                                class="max-h-24 max-w-[140px] object-contain rounded-lg border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 p-1"
                                                                alt="Q {{ $currentQNum }}">
                                                        </div>
                                                    @endif

                                                    @if ($question->title)
                                                        <div class="flex-1 text-xl font-bold text-slate-800 dark:text-slate-100 leading-loose min-w-0">
                                                            {!! $question->title !!}
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Right: √ / × Buttons --}}
                                                <div class="shrink-0 flex items-center gap-2.5">
                                                    @foreach ($question->options as $option)
                                                        <label class="cursor-pointer group select-none">
                                                            <input type="radio" name="answers[{{ $question->id }}]"
                                                                class="peer hidden" value="{{ $option->id }}"
                                                                @change="selectAnswer({{ $currentQNum }})">
                                                            <div class="w-12 h-12 rounded-xl border-2 flex items-center justify-center transition-all duration-150
                                                                {{ $option->content === '√'
                                                                    ? 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-emerald-500 group-hover:border-emerald-400 group-hover:bg-emerald-50/50 dark:group-hover:bg-emerald-950/20 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-sm'
                                                                    : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-rose-500 group-hover:border-rose-400 group-hover:bg-rose-50/50 dark:group-hover:bg-rose-950/20 peer-checked:border-rose-500 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-sm' }}">
                                                                <span class="material-symbols-outlined text-[24px] font-bold">
                                                                    {{ $option->content === '√' ? 'check' : 'close' }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                        {{-- ========== TYPE 2: OPTIONS WITH IMAGES ========== --}}
                                        @elseif ($isOptionsWithImages)
                                            <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-sm"
                                                id="q-{{ $currentQNum }}">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                                                        {{ $currentQNum }}
                                                    </div>
                                                    @if($question->title)
                                                        <div class="text-base font-medium text-slate-700 dark:text-slate-300 leading-relaxed">{!! $question->title !!}</div>
                                                    @endif
                                                </div>

                                                <div class="grid grid-cols-3 gap-3">
                                                    @foreach ($question->options as $option)
                                                        <label class="cursor-pointer group relative">
                                                            <input type="radio" name="answers[{{ $question->id }}]"
                                                                class="peer hidden" value="{{ $option->id }}"
                                                                @change="selectAnswer({{ $currentQNum }})">
                                                            <div class="flex flex-col items-center justify-between p-3 h-36 rounded-xl border-2 transition-all duration-150
                                                                border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40
                                                                group-hover:border-primary/50 group-hover:bg-primary/5
                                                                peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-sm">
                                                                 <div class="flex-1 flex items-center justify-center w-full">
                                                                    <img src="{{ Storage::url($option->image) }}"
                                                                        class="max-h-24 object-contain group-hover:scale-105 transition-transform"
                                                                        alt="Option {{ $loop->iteration }}">
                                                                </div>
                                                                <div class="w-7 h-7 rounded-lg text-xs font-bold flex items-center justify-center transition-colors
                                                                    border border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400
                                                                    peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white">
                                                                    {{ chr(64 + $loop->iteration) }}
                                                                </div>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                        {{-- ========== TYPE 3: MATCHING / LETTER OPTIONS (A-F) ========== --}}
                                        @elseif ($isLetterOptions)
                                            @php
                                                $excludedLetters = [];
                                                if ($currentQNum >= 11 && $currentQNum <= 15) $excludedLetters = ['C'];
                                                if ($currentQNum >= 26 && $currentQNum <= 30) $excludedLetters = ['E'];
                                                if ($currentQNum >= 31 && $currentQNum <= 35) $excludedLetters = ['F'];
                                                if ($currentQNum >= 36 && $currentQNum <= 40) $excludedLetters = ['D'];
                                            @endphp
                                            <div class="q-card flex items-center justify-between gap-4 scroll-mt-24 px-4 py-3.5 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm"
                                                id="q-{{ $currentQNum }}">

                                                {{-- Number & Title --}}
                                                <div class="flex items-center gap-3.5 flex-1 min-w-0">
                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                                                        {{ $currentQNum }}
                                                    </div>

                                                    @if ($question->title)
                                                        <div class="flex-1 text-lg font-bold text-slate-800 dark:text-slate-100 leading-loose min-w-0">
                                                            {!! $question->title !!}
                                                        </div>
                                                    @else
                                                        <div class="flex-1 text-sm italic text-slate-400">Chọn đáp án nghe được:</div>
                                                    @endif
                                                </div>

                                                {{-- 1 Straight Row of Option Buttons (Excludes example letter) --}}
                                                <div class="shrink-0 flex items-center gap-1.5">
                                                    @foreach ($question->options as $option)
                                                        @php $optContent = trim($option->content); @endphp
                                                        @if (in_array($optContent, $excludedLetters))
                                                            @continue
                                                        @endif
                                                        <label class="cursor-pointer group select-none">
                                                            <input type="radio" name="answers[{{ $question->id }}]"
                                                                class="peer hidden" value="{{ $option->id }}"
                                                                @change="selectAnswer({{ $currentQNum }})">
                                                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border-2 flex items-center justify-center transition-all duration-150 text-sm font-bold
                                                                border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400
                                                                group-hover:border-primary/60 group-hover:text-primary group-hover:bg-primary/5
                                                                peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white peer-checked:shadow-sm">
                                                                {{ $optContent }}
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                        {{-- ========== TYPE 4: DEFAULT MULTI-CHOICE ========== --}}
                                        @else
                                            <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-sm"
                                                id="q-{{ $currentQNum }}">
                                                <div class="flex items-start gap-3 mb-4">
                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                                        {{ $currentQNum }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        @if ($question->title)
                                                            <div class="text-base font-semibold text-slate-800 dark:text-white leading-relaxed">
                                                                {!! $question->title !!}
                                                            </div>
                                                        @endif
                                                        @if ($question->image)
                                                            <div class="mt-2">
                                                                <img src="{{ Storage::url($question->image) }}"
                                                                    class="rounded-xl border border-slate-200 dark:border-slate-700 max-h-36 object-contain"
                                                                    alt="Q {{ $currentQNum }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if ($question->options->count() > 0)
                                                    <div class="grid grid-cols-1 {{ $question->options->count() == 3 ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-3">
                                                        @foreach ($question->options as $option)
                                                            @php
                                                                // Strip duplicated 'A ', 'B ' prefix from content if present
                                                                $cleanContent = preg_replace('/^[A-F][\s\.\,]*/u', '', $option->content ?? '');
                                                            @endphp
                                                            <label class="cursor-pointer group">
                                                                <input type="radio"
                                                                    name="answers[{{ $question->id }}]"
                                                                    class="peer hidden" value="{{ $option->id }}"
                                                                    @change="selectAnswer({{ $currentQNum }})">
                                                                <div class="flex items-center gap-3 p-3 rounded-xl border-2 transition-all duration-150
                                                                    border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40
                                                                    group-hover:border-primary/50 group-hover:bg-primary/5
                                                                    peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-sm">

                                                                    {{-- Option letter badge (A, B, C) --}}
                                                                    <div class="w-7 h-7 rounded-lg border flex items-center justify-center text-xs font-bold shrink-0 transition-colors
                                                                        border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400
                                                                        group-hover:border-primary/60 group-hover:text-primary
                                                                        peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                                                        {{ chr(64 + $option->order_index) }}
                                                                    </div>

                                                                    @if ($option->image)
                                                                        <img src="{{ Storage::url($option->image) }}"
                                                                            class="h-14 w-auto rounded-lg object-contain"
                                                                            alt="Option {{ chr(64 + $option->order_index) }}">
                                                                    @endif

                                                                    @if ($cleanContent)
                                                                        <span class="text-slate-700 dark:text-slate-300 font-medium text-base group-hover:text-primary transition-colors leading-loose">
                                                                            {!! $cleanContent !!}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endforeach

            </div>
        </main>

        {{-- ===== RIGHT: ANSWER PALETTE SIDEBAR ===== --}}
        {{-- Desktop sidebar (always visible md+), mobile: slides in as overlay --}}
        <aside id="nav-sidebar"
            class="w-72 bg-white dark:bg-[#1a2332] border-l border-slate-200 dark:border-slate-800 flex flex-col shrink-0
                   hidden md:flex
                   fixed md:static right-0 top-0 bottom-0 z-40 md:z-auto shadow-2xl md:shadow-none
                   transition-transform duration-300">

            {{-- Header & Stats --}}
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-start gap-2">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-0.5">
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">Bảng câu hỏi</h3>
                        {{-- Mobile close button --}}
                        <button onclick="toggleNavSidebar()" class="md:hidden w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1.5">
                        <span>Tiến độ làm bài</span>
                        <span class="font-bold text-primary">
                            <span x-text="Object.keys(answers).length">0</span>/{{ $exam->total_questions ?? 40 }} câu
                        </span>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-300"
                            :style="`width: ${(Object.keys(answers).length / {{ $exam->total_questions ?? 40 }}) * 100}%`"></div>
                    </div>
                </div>
            </div>

            {{-- Question Palette Grid --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-5">
                @php $navQCount = 1; @endphp
                @foreach ($exam->sections as $sectionIndex => $section)
                    <div>
                        {{-- Section label --}}
                        <div class="flex items-center gap-2 mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
                            <p class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider leading-none">
                                Phần {{ $sectionIndex + 1 }}: {{ $section->name }}
                            </p>
                        </div>

                        @foreach ($section->questionGroups as $gIdx => $group)
                            @if($group->questions->count() > 0)
                                @php
                                    $firstQ = $navQCount;
                                    $lastQ  = $navQCount + $group->questions->count() - 1;
                                @endphp
                                {{-- Group label (Part 1, Part 2...) --}}
                                <p class="text-[10px] font-bold text-slate-400/80 dark:text-slate-600 uppercase mb-1.5 pl-0.5">
                                    Part {{ $gIdx + 1 }} &middot; Câu {{ $firstQ }}–{{ $lastQ }}
                                </p>
                                <div class="grid grid-cols-5 gap-1.5 mb-3">
                                    @foreach ($group->questions as $question)
                                        @php $currentNavQNum = $navQCount++; @endphp
                                        <button type="button"
                                            onclick="scrollToQuestion({{ $currentNavQNum }})"
                                            :class="answers[{{ $currentNavQNum }}]
                                                ? 'bg-primary text-white border-transparent shadow-sm'
                                                : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-primary/50 hover:text-primary'"
                                            class="w-full aspect-square rounded-lg text-xs font-bold flex items-center justify-center border transition-all duration-150 hover:scale-105">
                                            {{ $currentNavQNum }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

            {{-- Legend & Action --}}
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <div class="flex items-center justify-around text-xs text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-primary"></div>
                        <span>Đã làm</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-600"></div>
                        <span>Chưa làm</span>
                    </div>
                </div>
                <button type="button" onclick="confirmSubmit()"
                    class="w-full bg-primary hover:opacity-90 text-white font-bold py-2.5 rounded-xl shadow-sm transition-all duration-150 active:scale-95 flex items-center justify-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Nộp bài ngay
                </button>
            </div>
        </aside>

    </div>

    {{-- ===== CONFIRM SUBMIT MODAL ===== --}}
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-[#1a2332] rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">help</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Xác nhận nộp bài</h3>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                Bạn đã hoàn thành <strong class="text-primary font-bold" id="confirm-answered">0</strong> / {{ $exam->total_questions ?? 40 }} câu.
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Sau khi nộp bài, hệ thống sẽ tính điểm và hiển thị kết quả chi tiết.</p>
            <div class="flex gap-3">
                <button onclick="closeModal()" class="flex-1 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Làm tiếp
                </button>
                <button onclick="document.getElementById('exam-form').submit()" class="flex-1 py-2 rounded-xl bg-primary text-white font-bold text-sm hover:opacity-90 transition-opacity">
                    Nộp ngay
                </button>
            </div>
        </div>
    </div>

    {{-- ===== ALPINE TIMER LOGIC ===== --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('examTimer', () => ({
                timeRemaining: {{ $exam->duration }} * 60,
                answers: {},
                init() {
                    const timer = setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                        } else {
                            clearInterval(timer);
                            this.autoSubmit();
                        }
                    }, 1000);
                },
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                    const s = (seconds % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                },
                selectAnswer(questionOrderIndex) {
                    this.answers[questionOrderIndex] = true;
                    const total = {{ $exam->total_questions ?? 40 }};
                    const done = Object.keys(this.answers).length;
                    const bar = document.getElementById('progress-bar');
                    if (bar) bar.style.width = `${(done / total) * 100}%`;
                },
                autoSubmit() {
                    document.getElementById('exam-form').submit();
                }
            }))
        });

        function confirmSubmit() {
            const answered = document.querySelectorAll('input[type="radio"]:checked').length;
            document.getElementById('confirm-answered').textContent = answered;
            const modal = document.getElementById('confirm-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('confirm-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('confirm-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Scroll to question
        function scrollToQuestion(qNum) {
            const el = document.getElementById('q-' + qNum);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Brief highlight
                el.style.transition = 'box-shadow 0.3s ease';
                el.style.boxShadow = '0 0 0 3px rgba(232, 146, 122, 0.5)';
                setTimeout(() => { el.style.boxShadow = ''; }, 1200);
            }
            // Close mobile sidebar after click
            if (window.innerWidth < 768) toggleNavSidebar(false);
        }

        // Mobile sidebar toggle
        function toggleNavSidebar(forceState) {
            const sidebar = document.getElementById('nav-sidebar');
            const overlay = document.getElementById('nav-overlay');
            const isMobile = window.innerWidth < 768;
            if (!isMobile) return;

            const isOpen = sidebar.classList.contains('flex');
            const shouldOpen = forceState !== undefined ? forceState : !isOpen;

            if (shouldOpen) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    </script>
</body>

</html>
