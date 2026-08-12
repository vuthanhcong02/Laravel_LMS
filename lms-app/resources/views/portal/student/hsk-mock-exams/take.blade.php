<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang Thi: {{ $exam->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mock-exam.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+SC:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        body {
            font-family: 'Inter', 'Noto Sans SC', sans-serif;
        }

        /* Native Ruby pinyin formatting & high-contrast visibility */
        ruby {
            font-size: 1em;
        }

        rt {
            font-size: 0.55em;
            color: #334155;
            font-weight: 600;
            user-select: none;
            text-align: center;
        }

        .dark rt {
            color: #e2e8f0;
        }

        /* Custom Audio */
        .custom-audio {
            accent-color: #E8927A;
        }

        .custom-audio::-webkit-media-controls-panel {
            background: transparent;
        }

        /* Card transition */
        .q-card {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .q-card:hover {
            border-color: rgba(232, 146, 122, 0.4);
            box-shadow: 0 4px 20px -2px rgba(232, 146, 122, 0.08);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Timer pulse */
        @keyframes timer-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .timer-warning {
            animation: timer-pulse 1s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-[#131a1f] text-slate-900 dark:text-slate-100 h-screen overflow-hidden flex flex-col" x-data="examTimer">

    @php
        $listeningAudio = $exam->sections->firstWhere('skill_type', 'listening')?->audio_file;
    @endphp

    {{-- ===== HEADER ===== --}}
    <header
        class="bg-white dark:bg-[#1a2332] border-b border-slate-200 dark:border-slate-800 h-16 flex items-center justify-between px-4 md:px-6 shrink-0 z-20 shadow-sm">

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
                    <div
                        class="h-1.5 w-28 bg-slate-100 dark:bg-slate-700/60 rounded-full overflow-hidden hidden sm:block">
                        <div id="progress-bar" class="h-full bg-primary rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-medium hidden sm:block">
                        <span x-text="Object.keys(answers).length">0</span>/{{ $exam->total_questions ?? 40 }} câu
                    </span>
                </div>
            </div>
        </div>

        {{-- Center: Audio Player --}}
        @if ($listeningAudio)
            <div class="flex-1 flex justify-center px-4 hidden lg:flex">
                <div
                    class="w-full max-w-xs bg-slate-100 dark:bg-slate-800/80 rounded-xl px-3 py-1 border border-slate-200 dark:border-slate-700 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px] shrink-0">volume_up</span>
                    <audio controls controlsList="nodownload" class="custom-audio w-full h-8">
                        <source src="{{ Storage::url($listeningAudio) }}" type="audio/mpeg">
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
            <div :class="timeRemaining <= 300 ?
                'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800/50 text-rose-600 dark:text-rose-400 timer-warning' :
                'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300'"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border transition-colors font-mono font-bold text-sm">
                <span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
                <span x-text="formatTime(timeRemaining)">{{ str_pad($exam->duration, 2, '0', STR_PAD_LEFT) }}:00</span>
            </div>

            {{-- Mobile Nav Toggle --}}
            <button type="button" onclick="toggleNavSidebar()"
                class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors md:hidden relative">
                <span class="material-symbols-outlined text-[20px]">grid_view</span>
                <span id="mobile-nav-badge"
                    class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-primary text-white text-[9px] font-bold flex items-center justify-center hidden"
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

    {{-- Mobile / Tablet Sticky Audio Player Bar --}}
    @if ($listeningAudio)
        <div
            class="lg:hidden sticky top-16 z-20 bg-slate-900/95 dark:bg-slate-950/95 backdrop-blur-md text-white px-3.5 py-2 border-b border-slate-800 flex items-center justify-between gap-2.5 shadow-md">
            <div class="flex items-center gap-1.5 text-xs font-black text-amber-400 shrink-0">
                <span class="material-symbols-outlined text-[18px]">volume_up</span>
                <span class="hidden sm:inline">File nghe:</span>
            </div>
            <audio controls controlsList="nodownload" class="custom-audio w-full h-8 flex-1">
                <source src="{{ Storage::url($listeningAudio) }}" type="audio/mpeg">
            </audio>
        </div>
    @endif

    {{-- Mobile Nav Overlay --}}
    <div id="nav-overlay" class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm hidden md:hidden"
        onclick="toggleNavSidebar()"></div>

    {{-- ===== MAIN CONTENT CONTAINER ===== --}}
    <div class="flex-1 flex overflow-hidden">

        {{-- ===== LEFT: QUESTIONS LIST ===== --}}
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-[#131a1f] scroll-smooth" id="question-container">
            <div class="max-w-3xl mx-auto px-4 md:px-6 py-8 pb-32 space-y-10">

                @php $qCount = 1; @endphp
                @foreach ($exam->sections as $sectionIndex => $section)
                    @php
                        $sectionRealQCount = $section->questionGroups->sum(fn($g) => $g->questions->where('is_example', false)->count());
                        $validGroups = $section->questionGroups->filter(fn($g) => $g->questions->where('is_example', false)->count() > 0);
                    @endphp
                    
                    @if($sectionRealQCount > 0)
                    <div>
                        {{-- Section Title Divider Banner --}}
                        <div
                            class="flex items-center justify-between gap-4 mb-8 p-4 md:p-5 rounded-2xl bg-gradient-to-r from-primary to-primary/80 text-white shadow-md">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center font-black text-lg">
                                    {{ $sectionIndex + 1 }}
                                </span>
                                <div>
                                    <h2 class="text-lg md:text-xl font-black uppercase tracking-wide">
                                        {{ $section->name }}
                                    </h2>
                                    <p class="text-xs text-white/80 font-medium">
                                        {{ $validGroups->count() }} phần bài tập •
                                        {{ $sectionRealQCount }}
                                        câu hỏi
                                    </p>
                                </div>
                            </div>
                        </div>

                        @foreach ($section->questionGroups as $gIdx => $group)
                            @php
                                $groupRealQCount = $group->questions->where('is_example', false)->count();
                            @endphp
                            
                            @if($groupRealQCount > 0)
                            @php
                                $groupType = $group->group_type ?? 'default';

                                // Logic to determine component path based on group type or context
                                $componentPath = "portal.student.hsk-mock-exams.partials.groups.{$groupType}";
                            @endphp

                            <div class="mb-10">
                                @if (view()->exists($componentPath))
                                    @include($componentPath, [
                                        'group' => $group,
                                        'qCount' => $qCount,
                                        'gIdx' => $gIdx,
                                        'sectionIndex' => $sectionIndex,
                                        'navQCount' => $qCount,
                                    ])
                                @else
                                    <div class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-200">
                                        <h4 class="font-bold">Missing component for {{ $groupType }}</h4>
                                        <p class="text-sm mt-1">Please create
                                            <code>{{ str_replace('.', '/', $componentPath) }}.blade.php</code>.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @php
                                $qCount += $groupRealQCount;
                            @endphp
                            @endif
                        @endforeach
                    </div>
                    @endif
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
                        <button onclick="toggleNavSidebar()"
                            class="md:hidden w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
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
                            :style="`width: ${(Object.keys(answers).length / {{ $exam->total_questions ?? 40 }}) * 100}%`">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Question Palette Grid --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-5">
                @php $navQCount = 1; @endphp
                @foreach ($exam->sections as $sectionIndex => $section)
                    @php
                        $sectionRealQCount = $section->questionGroups->sum(fn($g) => $g->questions->where('is_example', false)->count());
                    @endphp
                    
                    @if($sectionRealQCount > 0)
                    <div>
                        <div class="flex items-center gap-2 mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
                            <p
                                class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider leading-none">
                                Phần {{ $sectionIndex + 1 }}: {{ $section->name }}
                            </p>
                        </div>

                        @foreach ($section->questionGroups as $gIdx => $group)
                            @php
                                $realQCount = $group->questions->where('is_example', false)->count();
                            @endphp
                            
                            @if ($realQCount > 0)
                                @php
                                    $firstQ = $navQCount;
                                    $lastQ = $navQCount + $realQCount - 1;
                                @endphp
                                <p
                                    class="text-[10px] font-bold text-slate-400/80 dark:text-slate-600 uppercase mb-1.5 pl-0.5">
                                    {{ $group->title ?? 'Part ' . ($gIdx + 1) }} &middot; Câu
                                    {{ $firstQ }}–{{ $lastQ }}
                                </p>
                                <div class="grid grid-cols-5 gap-1.5 mb-3">
                                    @foreach ($group->questions->where('is_example', false) as $question)
                                        @php $currentNavQNum = $navQCount++; @endphp
                                        <button type="button" onclick="scrollToQuestion({{ $currentNavQNum }})"
                                            id="nav-btn-{{ $currentNavQNum }}"
                                            class="nav-btn-item w-full aspect-square rounded-lg text-xs font-bold flex items-center justify-center border transition-all duration-150 hover:scale-105 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-primary/50 hover:text-primary">
                                            {{ $currentNavQNum }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @endif
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
                        <div
                            class="w-3 h-3 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-600">
                        </div>
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
    <div id="confirm-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-[#1a2332] rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">help</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Xác nhận nộp bài</h3>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                Bạn đã hoàn thành <strong class="text-primary font-bold" id="confirm-answered">0</strong> /
                {{ $exam->total_questions ?? 40 }} câu.
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Sau khi nộp bài, hệ thống sẽ tính điểm và hiển
                thị kết quả chi tiết.</p>
            <div class="flex gap-3">
                <button onclick="closeModal()"
                    class="flex-1 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Làm tiếp
                </button>
                <button onclick="document.getElementById('exam-form').submit()"
                    class="flex-1 py-2 rounded-xl bg-primary text-white font-bold text-sm hover:opacity-90 transition-opacity">
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.initTimer) {
                window.initTimer({{ $exam->duration }});
            }
        });
    </script>
</body>

</html>
