@extends('layouts.lms')
@section('title')
    {{ $currentLesson ? $currentLesson->title : ($currentLevel ? $currentLevel->title : 'Khóa học HSK') }} - XiaoMu LMS
@endsection
@section('custom-css')
    ruby { font-size: 1.1em; }
    rt { font-size: 0.55em; color: #e07a5f; font-weight: 600; text-align: center; }
    /* 3D Card Flip Animation */
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
@endsection
@php
    $lessonData = $currentLesson ? $currentLesson->toArray() : null;
    $shouldShow = $currentLevel ? hsk_should_show_pinyin($currentLevel) : true;
    if ($lessonData && isset($lessonData['practices'])) {
        foreach ($lessonData['practices'] as &$practice) {
            if (isset($practice['sections'])) {
                foreach ($practice['sections'] as &$section) {
                    $section['section_han_html'] = !empty($section['section_han'])
                        ? ($shouldShow
                            ? renderHskRubyText($section['section_han'])
                            : $section['section_han'])
                        : '';
                    // Parse section_vi in PHP so we can apply renderHskRubyText if needed
                    $text = $section['section_vi'] ?? '';
                    $mainText = $text;
                    $exampleHtml = '';
                    $hasExample = false;
                    $headerRx = '/(例如(?:\s*[\(（]?\s*Ví dụ\s*[\)）]?)?\s*[:：]?|Ví dụ\s*[:：]?)/i';
                    $firstTagRx =
                        '/(男\s*[:：]|女\s*[:：]| 问\s*[:：]|★|\s+[A-D]\s+|[\(（](?:ĐÚNG|SAI|✓|✕|v|x|√|N)[\)）])/i';
                    if (preg_match($headerRx, $text, $hm, PREG_OFFSET_CAPTURE)) {
                        $mainText = trim(substr($text, 0, $hm[0][1]));
                        $exampleRaw = trim(substr($text, $hm[0][1]));
                        $hasExample = true;
                    } elseif (preg_match($firstTagRx, $text, $fm, PREG_OFFSET_CAPTURE)) {
                        $mainText = trim(substr($text, 0, $fm[0][1]));
                        $exampleRaw = trim(substr($text, $fm[0][1]));
                        $hasExample = true;
                    }
                    if ($hasExample) {
                        $exHeader = '';
                        if (preg_match($headerRx, $exampleRaw, $hm, PREG_OFFSET_CAPTURE) && $hm[0][1] == 0) {
                            $exHeader = trim(substr($exampleRaw, 0, strlen($hm[0][0])));
                            $exampleRaw = trim(substr($exampleRaw, strlen($hm[0][0])));
                        }
                        $lines = array_filter(array_map('trim', explode("\n", $exampleRaw)));
                        $htmlLines = [];
                        $i = 0;
                        foreach ($lines as $line) {
                            if (preg_match('/^(A|B|C|D)\s*(.*)/i', $line, $matches)) {
                                $content = $shouldShow ? renderHskRubyText($matches[2]) : htmlspecialchars($matches[2]);
                                $htmlLines[] =
                                    '<div class="mt-1.5 flex items-start gap-2"><span class="shrink-0 w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[11px] flex items-center justify-center mt-0.5">' .
                                    $matches[1] .
                                    '</span><span class="flex-1">' .
                                    $content .
                                    '</span></div>';
                            } elseif (preg_match('/^(男\s*[:：]|女\s*[:：])(.*)/su', $line, $matches)) {
                                $speaker = $shouldShow ? renderHskRubyText($matches[1]) : htmlspecialchars($matches[1]);
                                $content = $shouldShow ? renderHskRubyText($matches[2]) : htmlspecialchars($matches[2]);
                                $htmlLines[] =
                                    '<div class="mt-1.5"><span class="font-bold text-slate-700 dark:text-slate-200">' .
                                    $speaker .
                                    '</span>' .
                                    $content .
                                    '</div>';
                            } else {
                                $content = $shouldShow ? renderHskRubyText($line) : htmlspecialchars($line);
                                $htmlLines[] =
                                    ($i === 0 ? '' : '<div class="mt-1">') . $content . ($i === 0 ? '' : '</div>');
                            }
                            $i++;
                        }
                        $exHeaderHtml = $exHeader
                            ? '<span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[11px] mr-1 align-middle">' .
                                ($shouldShow ? renderHskRubyText($exHeader) : htmlspecialchars($exHeader)) .
                                '</span>'
                            : '';
                        $exampleHtml = $exHeaderHtml . implode('', $htmlLines);
                    }
                    $section['parsed_vi'] = [
                        'mainText' => $mainText,
                        'hasExample' => $hasExample,
                        'exampleHtml' => $exampleHtml,
                    ];
                    if ($shouldShow) {
                        if (isset($section['questions'])) {
                            foreach ($section['questions'] as &$q) {
                                $q['question_html'] = !empty($q['question']) ? renderHskRubyText($q['question']) : '';
                                if (!empty($q['context'])) {
                                    if (is_string($q['context'])) {
                                        $q['context_html'] = renderHskRubyText($q['context']);
                                    } elseif (is_array($q['context'])) {
                                        $q['context_html'] = [];
                                        foreach ($q['context'] as $c) {
                                            $q['context_html'][] = renderHskRubyText($c);
                                        }
                                    }
                                }
                                if (!empty($q['options'])) {
                                    $qOpts = is_string($q['options'])
                                        ? json_decode($q['options'], true)
                                        : $q['options'];
                                    if (is_array($qOpts)) {
                                        foreach ($qOpts as &$opt) {
                                            if (is_array($opt) && isset($opt['text'])) {
                                                $opt['html'] = renderHskRubyText($opt['text']);
                                            } elseif (is_string($opt)) {
                                                $opt = [
                                                    'text' => $opt,
                                                    'html' => renderHskRubyText($opt),
                                                ];
                                            }
                                        }
                                        $q['options'] = $qOpts;
                                    }
                                }
                                if (!empty($q['items'])) {
                                    foreach ($q['items'] as &$item) {
                                        if (is_array($item) && isset($item['text'])) {
                                            $item['html'] = renderHskRubyText($item['text']);
                                        }
                                    }
                                }
                                if (!empty($q['question_segments'])) {
                                    $segments = is_string($q['question_segments'])
                                        ? json_decode($q['question_segments'], true)
                                        : $q['question_segments'];
                                    $newSegments = [];
                                    if (is_array($segments)) {
                                        foreach ($segments as $seg) {
                                            if (is_string($seg)) {
                                                $newSegments[] = [
                                                    'text' => $seg,
                                                    'html' => renderHskRubyText($seg),
                                                ];
                                            } else {
                                                $newSegments[] = $seg;
                                            }
                                        }
                                    }
                                    $q['question_segments'] = $newSegments;
                                }
                                if (!empty($q['sub_questions'])) {
                                    foreach ($q['sub_questions'] as &$sq) {
                                        $sq['question_html'] = !empty($sq['question'])
                                            ? renderHskRubyText($sq['question'])
                                            : '';
                                        if (!empty($sq['options'])) {
                                            foreach ($sq['options'] as &$opt) {
                                                if (is_array($opt) && isset($opt['text'])) {
                                                    $opt['html'] = renderHskRubyText($opt['text']);
                                                } elseif (is_string($opt)) {
                                                    $opt = [
                                                        'text' => $opt,
                                                        'html' => renderHskRubyText($opt),
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
@endphp
@section('header-left')
    @if (isset($currentLevel) && isset($currentLesson))
        @php
            $isDummyData = $currentLesson->title === 'Bài ' . $currentLesson->lesson_number;
            $displayTitleBreadcrumb = preg_replace('/^Bài\s+\d+[:\-]?\s*/i', '', $currentLesson->title);
            $displayTitleBreadcrumb = empty(trim($displayTitleBreadcrumb)) ? '' : ': ' . $displayTitleBreadcrumb;
            if ($isDummyData) {
                $displayTitleBreadcrumb = '';
            }
        @endphp
        <!-- Breadcrumbs -->
        <div class="flex items-center text-xs text-slate-500 font-semibold truncate min-w-0">
            <a href="{{ route('home') }}"
                class="hidden sm:inline-flex items-center hover:text-[#e07a5f] transition-colors shrink-0">
                <i class="fa-solid fa-house text-xs mr-1"></i>{{ __('Trang chủ') }}
            </a>
            <i class="hidden sm:inline-block fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400 shrink-0"></i>
            <a href="{{ route('courses') }}"
                class="hidden md:inline-flex items-center hover:text-[#e07a5f] transition-colors shrink-0">
                {{ __('Khóa học') }}
            </a>
            <i class="hidden md:inline-block fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400 shrink-0"></i>
            <a href="{{ route('courses.level', ['levelSlug' => $currentLevel->slug]) }}"
                class="hover:text-[#e07a5f] transition-colors shrink-0 font-medium">
                {{ $currentLevel->title }}
            </a>
            <i class="fa-solid fa-chevron-right text-[9px] mx-1.5 sm:mx-2 text-slate-400 shrink-0"></i>
            <span class="text-slate-900 dark:text-white font-bold truncate">{{ __('Bài') }}
                {{ $currentLesson->lesson_number }}{{ $isDummyData ? '' : $displayTitleBreadcrumb }}</span>
        </div>
    @endif
@endsection
@section('header-right')
    <!-- Empty to hide language selector in detail page -->
@endsection
@section('sub-header')
    <div x-data="lessonStudyApp({ activeTab: '{{ $activeTab }}' })"
        class="lms-card p-1.5 sm:p-2 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col lg:flex-row lg:items-center justify-between gap-2 shadow-xs">
        <!-- Main Lesson Tabs (Scrollable on small screens) -->
        <div class="flex items-center gap-1 sm:gap-1.5 overflow-x-auto no-scrollbar max-w-full pb-0.5 lg:pb-0 shrink-0">
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'tu-vung']) }}"
                class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-1.5 sm:gap-2 shrink-0 whitespace-nowrap {{ $activeTab === 'tu-vung' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-list-ul text-xs"></i>
                <span>{{ __('Từ vựng') }}</span>
            </a>
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'hoi-thoai']) }}"
                class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-1.5 sm:gap-2 shrink-0 whitespace-nowrap {{ $activeTab === 'hoi-thoai' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-comments text-xs"></i>
                <span>{{ __('Bài khóa') }}</span>
            </a>
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'ngu-phap']) }}"
                class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-1.5 sm:gap-2 shrink-0 whitespace-nowrap {{ $activeTab === 'ngu-phap' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-spell-check text-xs"></i>
                <span>{{ __('Ngữ pháp') }}</span>
            </a>
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'luyen-tap']) }}"
                class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-1.5 sm:gap-2 shrink-0 whitespace-nowrap {{ $activeTab === 'luyen-tap' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>{{ __('Luyện tập') }}</span>
            </a>
        </div>

        <!-- Vocabulary Sub-views Selector -->
        <div x-show="activeTab === 'tu-vung'" style="display: none;"
            class="flex items-center gap-1 p-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl shrink-0 overflow-x-auto no-scrollbar max-w-full">
            <button @click="$store.lesson.vocabSubView = 'table';"
                :class="($store.lesson ? $store.lesson.vocabSubView : 'table') === 'table' ?
                    'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'"
                class="px-2.5 py-1.5 sm:px-3 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap shrink-0">
                <i class="fa-solid fa-table-cells"></i>
                <span>{{ __('Bảng từ') }}</span>
            </button>
            <button
                @click="$store.lesson.vocabSubView = 'flashcard'; if($store.lesson) { $store.lesson.fcIndex = 0; $store.lesson.fcFlipped = false; }"
                :class="($store.lesson ? $store.lesson.vocabSubView : 'table') === 'flashcard' ?
                    'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'"
                class="px-2.5 py-1.5 sm:px-3 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap shrink-0">
                <i class="fa-solid fa-layer-group"></i>
                <span>{{ __('Flashcard') }}</span>
            </button>
            <button @click="$store.lesson.vocabSubView = 'match';"
                :class="($store.lesson ? $store.lesson.vocabSubView : 'table') === 'match' ?
                    'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'"
                class="px-2.5 py-1.5 sm:px-3 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap shrink-0">
                <i class="fa-solid fa-puzzle-piece"></i>
                <span>{{ __('Nối từ') }}</span>
            </button>
            <button @click="$store.lesson.vocabSubView = 'quiz';"
                :class="($store.lesson ? $store.lesson.vocabSubView : 'table') === 'quiz' ?
                    'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'"
                class="px-2.5 py-1.5 sm:px-3 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap shrink-0">
                <i class="fa-solid fa-clipboard-question"></i>
                <span>{{ __('Trắc nghiệm') }}</span>
            </button>
            <button @click="$store.lesson.vocabSubView = 'typing';"
                :class="($store.lesson ? $store.lesson.vocabSubView : 'table') === 'typing' ?
                    'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'"
                class="px-2.5 py-1.5 sm:px-3 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap shrink-0">
                <i class="fa-solid fa-keyboard"></i>
                <span>{{ __('Gõ phím') }}</span>
            </button>
        </div>
    </div>
@endsection
@section('content')
    <div x-data="lessonStudyApp({
        activeTab: '{{ $activeTab }}',
        shouldShowPinyin: @json($shouldShow),
        currentLessonId: {{ $currentLesson ? $currentLesson->id : 'null' }},
        vocabularies: {{ Js::from($currentLesson->vocabList ?? []) }},
        currentLesson: {{ Js::from($lessonData) }},
        currentLevelObj: {{ Js::from($currentLevel) }}
    })" class="space-y-4">
        <!-- TAB CONTENT -->
        <div>
            @if ($activeTab === 'tu-vung')
                @include('course-v2.tabs.vocab')
            @elseif($activeTab === 'hoi-thoai')
                @include('course-v2.tabs.dialogue')
            @elseif($activeTab === 'ngu-phap')
                @include('course-v2.tabs.grammar')
            @elseif($activeTab === 'luyen-tap')
                @include('course-v2.tabs.practice')
            @endif
        </div>

        @php
            $tabSequence = [
                'tu-vung' => [
                    'next' => 'hoi-thoai',
                    'next_label' => __('Tiếp tục sang Bài khóa'),
                    'exp' => 10,
                ],
                'hoi-thoai' => [
                    'next' => 'ngu-phap',
                    'next_label' => __('Tiếp tục sang Ngữ pháp'),
                    'exp' => 15,
                ],
                'ngu-phap' => [
                    'next' => 'luyen-tap',
                    'next_label' => __('Tiếp tục sang Luyện tập'),
                    'exp' => 15,
                ],
                'luyen-tap' => [
                    'next' => null,
                    'next_label' => __('Hoàn thành bài học'),
                    'exp' => 20,
                ],
            ];
            $currentTabInfo = $tabSequence[$activeTab] ?? null;
            $nextUrl = null;
            if ($currentTabInfo && $currentTabInfo['next'] && $currentLevel && $currentLesson) {
                $nextUrl = route('courses.lesson', [
                    'levelSlug' => $currentLevel->slug,
                    'lessonSlug' => $currentLesson->slug,
                    'tab' => $currentTabInfo['next'],
                ]);
            }
        @endphp

        @if ($currentTabInfo)
            <div
                class="pt-4 mt-6 border-t border-[#e8e2d9] dark:border-[#2d2926] flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-medium">
                    <i class="fa-solid fa-sparkles text-[#e07a5f]"></i>
                    <span>{{ __('Hoàn thành phần này để ghi nhận tiến độ học tập và tích lũy') }} <strong
                            class="text-[#e07a5f] font-bold">+{{ $currentTabInfo['exp'] }} EXP</strong></span>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="button" @click="completeAndNextTab('{{ $activeTab }}', '{{ $nextUrl }}')"
                        :disabled="isMarkingTab"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#e07a5f]/20 transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                        <template x-if="isMarkingTab">
                            <i class="fa-solid fa-spinner animate-spin"></i>
                        </template>
                        <span
                            x-text="isMarkingTab ? '{{ __('Đang lưu tiến độ...') }}' : '{{ $currentTabInfo['next_label'] }}'"></span>
                        <i class="fa-solid fa-arrow-right text-xs" x-show="!isMarkingTab"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
@endsection
