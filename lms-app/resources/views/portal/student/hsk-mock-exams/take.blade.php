<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Phòng thi') }} · {{ $exam->title }} - XIAOMU LMS</title>
    <meta name="description" content="Phòng thi Luyện thi HSK trực tuyến chuẩn Bộ Giáo Dục Trung Quốc với giao diện chuyên nghiệp.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        :root {
            --brand-primary: #e07a5f;
            --brand-primary-hover: #c86349;
            --brand-bg: #f8f6f3;
            --card-border: #e8e2d9;
        }
        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--brand-bg);
            color: #1e1b18;
            -webkit-font-smoothing: antialiased;
        }
        .zh-text { font-family: 'Inter', 'Noto Sans SC', sans-serif; }

        /* Native Ruby pinyin formatting & high-contrast visibility */
        ruby { font-size: 1.1em; }
        rt {
            font-size: 0.55em;
            color: #64748b;
            font-weight: 600;
            user-select: none;
            text-align: center;
        }
        .dark rt { color: #94a3b8; }

        /* Custom Audio */
        .custom-audio {
            accent-color: #e07a5f;
        }
        .custom-audio::-webkit-media-controls-panel {
            background: transparent;
        }

        /* Card & Container styling */
        .q-card {
            background-color: #ffffff;
            border-radius: 1.5rem;
            border: 1px solid #e8e2d9;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .q-card {
            background-color: #181615;
            border-color: #2d2926;
        }
        .q-card:hover {
            border-color: rgba(224, 122, 95, 0.4);
            box-shadow: 0 8px 24px -4px rgba(224, 122, 95, 0.08);
        }

        /* Modern Radio options active styling */
        input[type="radio"]:checked ~ div,
        input[type="radio"]:checked + div {
            border-color: #e07a5f !important;
            background-color: #fff7f4 !important;
            box-shadow: 0 2px 8px -2px rgba(224, 122, 95, 0.25);
        }
        .dark input[type="radio"]:checked ~ div,
        .dark input[type="radio"]:checked + div {
            background-color: #2a201c !important;
            border-color: #e07a5f !important;
        }

        input[type="radio"]:checked ~ div div:first-child,
        input[type="radio"]:checked + div div:first-child,
        input[type="radio"]:checked ~ div .opt-badge,
        input[type="radio"]:checked + div .opt-badge {
            background-color: #e07a5f !important;
            border-color: #e07a5f !important;
            color: #ffffff !important;
        }

        html { scroll-behavior: smooth; }

        @keyframes timer-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .timer-warning { animation: timer-pulse 1s ease-in-out infinite; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .btn-tactile { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        .btn-tactile:active { transform: scale(0.96); }
    </style>
</head>
<body class="bg-[#f8f6f3] dark:bg-[#0e0c0b] text-slate-900 dark:text-slate-100 h-screen overflow-hidden flex flex-col">
    @php
        $listeningAudio = $exam->audio_file ?? $exam->sections->firstWhere('skill_type', 'listening')?->audio_file;
        $totalQuestions = $exam->total_questions ?? 40;
    @endphp

    <form id="exam-submit-form" action="{{ route('student.hsk-mock-exams.submit', ['uuid' => $result->uuid]) }}" method="POST" class="h-full flex flex-col overflow-hidden w-full" onsubmit="this.querySelectorAll('button[type=submit]').forEach(b => b.disabled = true);">
        @csrf

        <!-- Top Header Navigation Bar -->
        <header class="bg-white dark:bg-[#141211] border-b border-[#e8e2d9] dark:border-[#262220] h-16 flex items-center justify-between px-4 md:px-6 shrink-0 z-30 shadow-xs">
            
            <!-- Left: Back Exit Button & Exam Title -->
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <button type="button" onclick="openExitModal()" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors shrink-0 btn-tactile" title="{{ __('Thoát phòng thi') }}">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <div class="min-w-0">
                    <h1 class="font-bold text-slate-900 dark:text-white text-sm md:text-base truncate leading-tight">
                        {{ __('Luyện thi HSK') }} {{ $level }} · {{ $exam->title }}
                    </h1>
                    @if (!empty($exam->exam_code))
                        <p class="text-[11px] text-slate-400 font-medium truncate">{{ __('Mã đề') }}: {{ $exam->exam_code }}</p>
                    @else
                        <p class="text-[11px] text-[#e07a5f] font-semibold truncate">{{ __('Bộ đề thi chuẩn hóa') }} • {{ $exam->duration }} {{ __('phút') }}</p>
                    @endif
                </div>
            </div>

            <!-- Center: Embedded Audio Player -->
            @if ($listeningAudio)
                <div class="flex-1 flex justify-center px-4 hidden lg:flex">
                    <div class="w-full max-w-sm bg-[#faf6f2] dark:bg-[#201d1b] rounded-xl px-3.5 py-1.5 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center gap-2.5 shadow-xs">
                        <i class="fa-solid fa-volume-high text-[#e07a5f] text-sm shrink-0"></i>
                        <audio controls controlsList="nodownload" class="custom-audio w-full h-8 flex-1">
                            <source src="{{ hsk_storage_url($listeningAudio) }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>
            @endif

            <!-- Right: Countdown Timer, Dark Mode & Palette Toggle -->
            <div class="flex items-center gap-2.5 flex-1 justify-end">
                <!-- Timer Badge -->
                <div id="timer-badge" class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl border bg-[#fff2ee] dark:bg-[#251d1a] border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] dark:text-[#f4978e] transition-colors font-mono font-bold text-xs sm:text-sm shadow-xs">
                    <i class="fa-regular fa-clock text-[#e07a5f] text-sm"></i>
                    <span id="exam-timer-display">{{ str_pad($exam->duration, 2, '0', STR_PAD_LEFT) }}:00</span>
                </div>

                <!-- Dark Mode Switch -->
                <button type="button" onclick="toggleTheme()" class="w-9 h-9 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#201d1b] flex items-center justify-center text-xs transition-colors btn-tactile">
                    <i class="fa-solid fa-moon dark:hidden text-slate-600"></i>
                    <i class="fa-solid fa-sun hidden dark:inline text-amber-400"></i>
                </button>

                <!-- Mobile Palette Toggle Button -->
                <button type="button" onclick="toggleMobileNav(true)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300 md:hidden btn-tactile relative">
                    <i class="fa-solid fa-list-ol text-sm"></i>
                    <span id="mobile-nav-badge" class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#e07a5f] text-white text-[9px] font-bold flex items-center justify-center hidden">0</span>
                </button>
            </div>
        </header>

        <!-- Mobile Audio Sticky Bar -->
        @if ($listeningAudio)
            <div class="lg:hidden bg-slate-900 text-white px-4 py-2 border-b border-slate-800 flex items-center justify-between gap-3 text-xs shrink-0 shadow-md">
                <div class="flex items-center gap-2 text-amber-400 font-bold shrink-0">
                    <i class="fa-solid fa-volume-high"></i>
                    <span>{{ __('Audio Nghe') }}:</span>
                </div>
                <audio controls controlsList="nodownload" class="custom-audio w-full h-8 flex-1">
                    <source src="{{ hsk_storage_url($listeningAudio) }}" type="audio/mpeg">
                </audio>
            </div>
        @endif

        <!-- Main Workspace Container -->
        <div class="flex-1 flex overflow-hidden relative">

            <!-- LEFT: SCROLLABLE QUESTIONS LIST -->
            <main class="flex-1 overflow-y-auto bg-[#f8f6f3] dark:bg-[#0e0c0b] scroll-smooth no-scrollbar" id="question-container">
                <div class="max-w-3xl mx-auto px-4 md:px-6 py-6 pb-28 space-y-8">
                    @php $qCount = 1; @endphp
                    @foreach ($exam->sections as $sectionIndex => $section)
                        @php
                            $sectionRealQCount = $section->questionGroups->sum(
                                fn($g) => $g->questions->where('is_example', false)->count(),
                            );
                            $validGroups = $section->questionGroups->filter(
                                fn($g) => $g->questions->where('is_example', false)->count() > 0,
                            );
                        @endphp

                        @if ($sectionRealQCount > 0)
                            <div class="space-y-6">
                                <!-- Section Title Divider Banner -->
                                <div class="flex items-center justify-between gap-4 p-4 md:p-5 rounded-2xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] text-white shadow-md">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center font-bold text-lg">
                                            {{ $sectionIndex + 1 }}
                                        </span>
                                        <div>
                                            <h2 class="text-base md:text-lg font-bold uppercase tracking-wide">
                                                {{ $section->name }}
                                            </h2>
                                            <p class="text-xs text-white/80 font-medium">
                                                {{ $validGroups->count() }} {{ __('phần bài tập') }} • {{ $sectionRealQCount }} {{ __('câu hỏi') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question Groups in this Section -->
                                @foreach ($section->questionGroups as $gIdx => $group)
                                    @php
                                        $groupRealQCount = $group->questions->where('is_example', false)->count();
                                    @endphp

                                    @if ($groupRealQCount > 0)
                                        @php
                                            $groupType = $group->group_type ?? 'default';
                                            $componentPath = "portal.student.hsk-mock-exams.partials.groups.{$groupType}";
                                        @endphp

                                        <div class="mb-8">
                                            @if (view()->exists($componentPath))
                                                @include($componentPath, [
                                                    'group' => $group,
                                                    'qCount' => $qCount,
                                                    'gIdx' => $gIdx,
                                                    'sectionIndex' => $sectionIndex,
                                                    'navQCount' => $qCount,
                                                ])
                                            @else
                                                <div class="p-4 bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 rounded-2xl border border-amber-200 dark:border-amber-800">
                                                    <h4 class="font-bold">{{ __('Đang tải nội dung phần thi...') }}</h4>
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

            <!-- RIGHT: FIXED QUESTION PALETTE SIDEBAR (Desktop) -->
            <aside id="desktop-palette" class="w-72 bg-white dark:bg-[#141211] border-l border-[#e8e2d9] dark:border-[#262220] flex flex-col shrink-0 hidden md:flex z-20">
                
                <!-- Header & Progress Tracking Bar -->
                <div class="p-4 border-b border-[#e8e2d9] dark:border-[#262220] space-y-2">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Bảng câu hỏi phòng thi') }}</h3>
                        <span class="text-xs font-bold text-[#e07a5f]">
                            <span id="sidebar-progress-text">0</span>/{{ $totalQuestions }} {{ __('câu') }}
                        </span>
                    </div>

                    <div class="h-2 bg-[#f8f6f3] dark:bg-[#201d1b] rounded-full overflow-hidden border border-[#e8e2d9] dark:border-[#2d2926]">
                        <div id="sidebar-progress-bar" class="h-full bg-gradient-to-r from-[#e07a5f] to-[#c86349] rounded-full transition-all duration-300" style="width: 0%;"></div>
                    </div>
                </div>

                <!-- Question Numbers Palette Grid -->
                <div class="flex-1 overflow-y-auto p-4 space-y-5 no-scrollbar">
                    @php $navQCount = 1; @endphp
                    @foreach ($exam->sections as $sectionIndex => $section)
                        @php
                            $sectionRealQCount = $section->questionGroups->sum(
                                fn($g) => $g->questions->where('is_example', false)->count(),
                            );
                        @endphp

                        @if ($sectionRealQCount > 0)
                            <div>
                                <div class="flex items-center gap-2 mb-2.5">
                                    <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        {{ __('Phần') }} {{ $sectionIndex + 1 }}: {{ $section->name }}
                                    </p>
                                </div>

                                @foreach ($section->questionGroups as $gIdx => $group)
                                    @php
                                        $realQCount = $group->questions->where('is_example', false)->count();
                                    @endphp

                                    @if ($realQCount > 0)
                                        <div class="grid grid-cols-5 gap-1.5 mb-2">
                                            @foreach ($group->questions->where('is_example', false) as $question)
                                                @php $currentNavQNum = $navQCount++; @endphp
                                                <button type="button" 
                                                        onclick="scrollToQuestion({{ $currentNavQNum }})" 
                                                        id="nav-btn-{{ $currentNavQNum }}" 
                                                        class="nav-btn-item w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border bg-slate-50 dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#2d2926] hover:border-[#e07a5f] hover:text-[#e07a5f] transition-all btn-tactile">
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

                <!-- Footer: Legend & Action Submit Button -->
                <div class="p-4 border-t border-[#e8e2d9] dark:border-[#262220] space-y-3 bg-[#faf6f2] dark:bg-[#1a1816]">
                    <div class="flex items-center justify-around text-xs text-slate-500 dark:text-slate-400">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-md bg-[#e07a5f]"></div>
                            <span class="font-medium">{{ __('Đã làm') }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-md bg-slate-100 dark:bg-[#201d1b] border border-slate-300 dark:border-[#2d2926]"></div>
                            <span class="font-medium">{{ __('Chưa làm') }}</span>
                        </div>
                    </div>

                    <button type="button" onclick="openSubmitModal()" class="w-full bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold py-2.5 rounded-xl shadow-md transition-all btn-tactile flex items-center justify-center gap-2 text-xs">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>{{ __('Nộp bài ngay') }}</span>
                    </button>
                </div>
            </aside>

        </div>

        <!-- MOBILE SLIDE-OVER DRAWER -->
        <div id="mobile-drawer-overlay" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-xs hidden md:hidden" onclick="toggleMobileNav(false)"></div>
        <div id="mobile-drawer" class="fixed top-0 right-0 bottom-0 w-80 max-w-[85vw] bg-white dark:bg-[#141211] z-50 transform translate-x-full transition-transform duration-300 flex flex-col md:hidden shadow-2xl">
            <div class="p-4 border-b border-[#e8e2d9] dark:border-[#262220] flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Bảng câu hỏi phòng thi') }}</h3>
                    <p class="text-xs text-[#e07a5f] font-semibold"><span id="mobile-progress-text">0</span>/{{ $totalQuestions }} {{ __('câu đã làm') }}</p>
                </div>
                <button type="button" onclick="toggleMobileNav(false)" class="w-8 h-8 rounded-xl border border-[#e8e2d9] dark:border-[#262220] text-slate-400 hover:text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                @php $mobQCount = 1; @endphp
                @foreach ($exam->sections as $sectionIndex => $section)
                    @php
                        $sectionRealQCount = $section->questionGroups->sum(
                            fn($g) => $g->questions->where('is_example', false)->count(),
                        );
                    @endphp

                    @if ($sectionRealQCount > 0)
                        <div>
                            <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">
                                {{ __('Phần') }} {{ $sectionIndex + 1 }}: {{ $section->name }}
                            </p>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach ($section->questionGroups as $group)
                                    @foreach ($group->questions->where('is_example', false) as $question)
                                        @php $currentMobQNum = $mobQCount++; @endphp
                                        <button type="button" 
                                                onclick="scrollToQuestion({{ $currentMobQNum }})" 
                                                id="mob-nav-btn-{{ $currentMobQNum }}" 
                                                class="mob-nav-btn-item w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border bg-slate-50 dark:bg-[#201d1b] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#2d2926]">
                                            {{ $currentMobQNum }}
                                        </button>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="p-4 border-t border-[#e8e2d9] dark:border-[#262220]">
                <button type="button" onclick="toggleMobileNav(false); openSubmitModal();" class="w-full bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 text-xs">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>{{ __('Nộp bài ngay') }}</span>
                </button>
            </div>
        </div>

        <!-- MODAL 1: CONFIRM SUBMIT MODAL -->
        <div id="submitModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
            <div class="bg-white dark:bg-[#181615] rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 border border-[#e8e2d9] dark:border-[#2d2926] space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-circle-question"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Xác nhận nộp bài thi') }}</h3>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ __('Bạn đã làm được') }} <strong class="text-[#e07a5f] font-bold" id="confirm-answered-count">0</strong> / {{ $totalQuestions }} {{ __('câu hỏi') }}.
                </p>

                <p class="text-[11px] text-slate-400">
                    {{ __('Sau khi nộp bài, hệ thống sẽ tính điểm tự động và trả kết quả chi tiết kèm đáp án giải thích.') }}
                </p>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeSubmitModal()" class="flex-1 py-2.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-[#201d1b] transition-colors btn-tactile">
                        {{ __('Làm tiếp') }}
                    </button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs transition-colors btn-tactile shadow-md">
                        {{ __('Nộp ngay') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL 2: CONFIRM EXIT MODAL -->
        <div id="exitModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
            <div class="bg-white dark:bg-[#181615] rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 border border-[#e8e2d9] dark:border-[#2d2926] space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-950/60 text-red-500 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Xác nhận thoát phòng thi') }}</h3>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ __('Bạn có chắc chắn muốn thoát khỏi bài thi lúc này?') }}
                </p>

                <div class="p-3 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/40 text-[11px] text-red-600 dark:text-red-400 leading-relaxed font-medium">
                    ⚠️ {{ __('Lưu ý: Thời gian làm bài vẫn tiếp tục đếm ngược. Nếu hết giờ mà bạn chưa nộp bài, kết quả thi này sẽ bị hủy bỏ hoàn toàn.') }}
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeExitModal()" class="flex-1 py-2.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-[#201d1b] transition-colors btn-tactile">
                        {{ __('Làm tiếp') }}
                    </button>
                    <a href="{{ route('student.hsk-mock-exams.show', ['level' => $level]) }}" class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-xs text-center transition-colors btn-tactile shadow-md">
                        {{ __('Đồng ý thoát') }}
                    </a>
                </div>
            </div>
        </div>

    </form>

    <!-- EXAM JAVASCRIPT LOGIC -->
    <script>
        // Total questions count and answered set
        const _totalQ = {{ $totalQuestions }};
        const _answeredSet = new Set();
        let timeRemaining = {{ isset($timeRemaining) ? $timeRemaining : ($exam->duration * 60) }};

        // Theme toggle
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        // Scroll to question
        function scrollToQuestion(qNum) {
            const el = document.getElementById('q-' + qNum);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                el.style.transition = 'box-shadow 0.3s ease, border-color 0.3s ease';
                el.style.borderColor = '#e07a5f';
                el.style.boxShadow = '0 0 0 3px rgba(224, 122, 95, 0.3)';
                setTimeout(() => { 
                    el.style.boxShadow = ''; 
                    el.style.borderColor = '';
                }, 1500);
            }
            toggleMobileNav(false);
        }

        // Sidebar answer progress update
        function updateSidebar(qNum) {
            // Update desktop button
            const btn = document.getElementById('nav-btn-' + qNum);
            if (btn) {
                btn.className = 'nav-btn-item w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border bg-[#e07a5f] text-white border-[#e07a5f] shadow-xs btn-tactile';
            }

            // Update mobile button
            const mobBtn = document.getElementById('mob-nav-btn-' + qNum);
            if (mobBtn) {
                mobBtn.className = 'mob-nav-btn-item w-full aspect-square rounded-xl text-xs font-bold flex items-center justify-center border bg-[#e07a5f] text-white border-[#e07a5f] shadow-xs';
            }

            _answeredSet.add(qNum);
            const done = _answeredSet.size;

            const textEl = document.getElementById('sidebar-progress-text');
            if (textEl) textEl.innerText = done;

            const mobTextEl = document.getElementById('mobile-progress-text');
            if (mobTextEl) mobTextEl.innerText = done;

            const mobBadge = document.getElementById('mobile-nav-badge');
            if (mobBadge) {
                mobBadge.innerText = done;
                mobBadge.classList.remove('hidden');
            }

            const bar = document.getElementById('sidebar-progress-bar');
            if (bar && _totalQ > 0) bar.style.width = `${(done / _totalQ) * 100}%`;

            const confirmEl = document.getElementById('confirm-answered-count');
            if (confirmEl) confirmEl.innerText = done;
        }

        // Play audio for individual question
        let currentAudio = null;
        let currentBtn = null;
        function playAudio(url, btnElement) {
            if (currentAudio && currentAudio.src.endsWith(url) && !currentAudio.paused) {
                currentAudio.pause();
                resetAudioBtn(btnElement);
                return;
            }

            if (currentAudio) {
                currentAudio.pause();
                if (currentBtn) resetAudioBtn(currentBtn);
            }

            currentAudio = new Audio(url);
            currentBtn = btnElement;

            const icon = btnElement.querySelector('i') || btnElement.querySelector('.material-symbols-outlined');
            if (icon) icon.className = 'fa-solid fa-circle-pause text-lg';

            currentAudio.play().catch(e => console.log(e));
            currentAudio.onended = () => resetAudioBtn(btnElement);
        }

        function resetAudioBtn(btnElement) {
            const icon = btnElement.querySelector('i') || btnElement.querySelector('.material-symbols-outlined');
            if (icon) icon.className = 'fa-solid fa-volume-high text-lg';
        }

        // Modals
        function openSubmitModal() {
            const m = document.getElementById('submitModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function closeSubmitModal() {
            const m = document.getElementById('submitModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function openExitModal() {
            const m = document.getElementById('exitModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function closeExitModal() {
            const m = document.getElementById('exitModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        // Mobile drawer
        function toggleMobileNav(open) {
            const drawer = document.getElementById('mobile-drawer');
            const overlay = document.getElementById('mobile-drawer-overlay');
            if (open) {
                overlay.classList.remove('hidden');
                drawer.classList.remove('translate-x-full');
            } else {
                overlay.classList.add('hidden');
                drawer.classList.add('translate-x-full');
            }
        }

        // Timer Countdown
        function formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        const timerDisplay = document.getElementById('exam-timer-display');
        const timerBadge = document.getElementById('timer-badge');

        const countdownInterval = setInterval(() => {
            if (timeRemaining > 0) {
                timeRemaining--;
                if (timerDisplay) timerDisplay.innerText = formatTime(timeRemaining);

                if (timeRemaining <= 300 && timerBadge) {
                    timerBadge.className = 'flex items-center gap-2 px-3.5 py-1.5 rounded-xl border bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 font-mono font-bold text-xs sm:text-sm timer-warning shadow-xs';
                }
            } else {
                clearInterval(countdownInterval);
                alert('Hết giờ làm bài! Hệ thống đang tự động nộp bài thi của bạn.');
                document.getElementById('exam-submit-form').submit();
            }
        }, 1000);
    </script>
</body>
</html>
