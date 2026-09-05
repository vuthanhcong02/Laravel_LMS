@extends('layouts.lms')
@section('title')
{{ $currentLesson ? $currentLesson->title : ($currentLevel ? $currentLevel->title : 'Khóa học HSK') }} - XIAOMU LMS
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
@section('alpine-data')
@php
                $lessonData = $currentLesson ? $currentLesson->toArray() : null;
                $shouldShow = $currentLevel ? hsk_should_show_pinyin($currentLevel) : true;
                if ($lessonData && isset($lessonData['practices'])) {
                    foreach ($lessonData['practices'] as &$practice) {
                        if (isset($practice['sections'])) {
                            foreach ($practice['sections'] as &$section) {
                                $section['section_han_html'] = !empty($section['section_han']) ? ($shouldShow ? renderHskRubyText($section['section_han']) : $section['section_han']) : '';
                                // Parse section_vi in PHP so we can apply renderHskRubyText if needed
                                $text = $section['section_vi'] ?? '';
                                $mainText = $text;
                                $exampleHtml = '';
                                $hasExample = false;
                                $headerRx = '/(例如(?:\s*[\(（]?\s*Ví dụ\s*[\)）]?)?\s*[:：]?|Ví dụ\s*[:：]?)/iu';
                                $firstTagRx = '/(男\s*[:：]|女\s*[:：]| 问\s*[:：]|★|\s+[A-D]\s+|[\(（](?:ĐÚNG|SAI|✓|✕|v|x|√|N)[\)）])/iu';
                                if (preg_match($headerRx, $text, $hm, PREG_OFFSET_CAPTURE)) {
                                    $mainText = trim(substr($text, 0, $hm[0][1]));
                                    $exampleRaw = trim(substr($text, $hm[0][1]));
                                    $hasExample = true;
                                } else if (preg_match($firstTagRx, $text, $fm, PREG_OFFSET_CAPTURE)) {
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
                                    // Remove any invalid UTF-8 characters that might render as 
                                    $exHeader = str_replace("ï¿½", '', mb_convert_encoding($exHeader, 'UTF-8', 'UTF-8'));
                                    $exampleRaw = str_replace("ï¿½", '', mb_convert_encoding($exampleRaw, 'UTF-8', 'UTF-8'));
                                    $lines = array_filter(array_map('trim', explode("\n", $exampleRaw)));
                                    $htmlLines = [];
                                    $htmlLinesRaw = [];
                                    $i = 0;
                                    foreach ($lines as $line) {
                                        if (preg_match('/^(A|B|C|D)\s*(.*)/iu', $line, $matches)) {
                                            $contentPinyin = $shouldShow ? renderHskRubyText($matches[2]) : htmlspecialchars($matches[2]);
                                            $contentRaw = htmlspecialchars($matches[2]);
                                            $prefix = '<div class="mt-1.5 flex items-start gap-2"><span class="shrink-0 w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[11px] flex items-center justify-center mt-0.5">' . $matches[1] . '</span><span class="flex-1">';
                                            $suffix = '</span></div>';
                                            $htmlLines[] = $prefix . $contentPinyin . $suffix;
                                            $htmlLinesRaw[] = $prefix . $contentRaw . $suffix;
                                        } else if (preg_match('/^(男\s*[:：]|女\s*[:：])(.*)/su', $line, $matches)) {
                                            $speakerPinyin = $shouldShow ? renderHskRubyText($matches[1]) : htmlspecialchars($matches[1]);
                                            $contentPinyin = $shouldShow ? renderHskRubyText($matches[2]) : htmlspecialchars($matches[2]);
                                            $speakerRaw = htmlspecialchars($matches[1]);
                                            $contentRaw = htmlspecialchars($matches[2]);
                                            $prefix = '<div class="mt-1.5"><span class="font-bold text-slate-700 dark:text-slate-200">';
                                            $mid = '</span>';
                                            $suffix = '</div>';
                                            $htmlLines[] = $prefix . $speakerPinyin . $mid . $contentPinyin . $suffix;
                                            $htmlLinesRaw[] = $prefix . $speakerRaw . $mid . $contentRaw . $suffix;
                                        } else {
                                            $contentPinyin = $shouldShow ? renderHskRubyText($line) : htmlspecialchars($line);
                                            $contentRaw = htmlspecialchars($line);
                                            $prefix = ($i === 0 ? '' : '<div class="mt-1">');
                                            $suffix = ($i === 0 ? '' : '</div>');
                                            $htmlLines[] = $prefix . $contentPinyin . $suffix;
                                            $htmlLinesRaw[] = $prefix . $contentRaw . $suffix;
                                        }
                                        $i++;
                                    }
                                    $exHeaderHtml = $exHeader ? '<span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[11px] mr-1 align-middle">' . ($shouldShow ? renderHskRubyText($exHeader) : htmlspecialchars($exHeader)) . '</span>' : '';
                                    $exHeaderHtmlRaw = $exHeader ? '<span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[11px] mr-1 align-middle">' . htmlspecialchars($exHeader) . '</span>' : '';
                                    $exampleHtml = $exHeaderHtml . implode('', $htmlLines);
                                    $exampleHtmlRaw = $exHeaderHtmlRaw . implode('', $htmlLinesRaw);
                                } else {
                                    $exampleHtmlRaw = '';
                                }
                                $section['parsed_vi'] = [
                                    'mainText' => $mainText,
                                    'hasExample' => $hasExample,
                                    'exampleHtml' => $exampleHtml,
                                    'exampleHtmlRaw' => $exampleHtmlRaw ?? ''
                                ];
                                if ($shouldShow) {
                                    if (isset($section['questions'])) {
                                        foreach ($section['questions'] as &$q) {
                                            $q['question_html'] = !empty($q['question']) ? renderHskRubyText($q['question']) : '';
                                            // Xử lý segment câu hỏi cho dạng điền từ (fill_blank_dropdown / fill_blank)
                                            if (!empty($q['question'])) {
                                                $delim = str_contains($q['question'], '{{blank}}') ? '{{blank}}' : null;
                                                if ($delim !== null) {
                                                    $rawSegs = explode($delim, $q['question']);
                                                    $htmlSegs = [];
                                                    foreach ($rawSegs as $seg) {
                                                        $htmlSegs[] = renderHskRubyText($seg);
                                                    }
                                                    $q['parsed_question_html'] = $htmlSegs;
                                                    $q['parsed_question_raw'] = $rawSegs;
                                                }
                                            }
                                            if (!empty($q['context'])) {
                                                if (is_string($q['context'])) {
                                                    $q['context_html'] = renderHskRubyText($q['context']);
                                                    $delimCtx = str_contains($q['context'], '{{blank}}') ? '{{blank}}' : null;
                                                    if ($delimCtx !== null) {
                                                        $rawCtxSegs = explode($delimCtx, $q['context']);
                                                        $htmlCtxSegs = [];
                                                        foreach ($rawCtxSegs as $seg) {
                                                            $htmlCtxSegs[] = renderHskRubyText($seg);
                                                        }
                                                        $q['parsed_context_html'] = $htmlCtxSegs;
                                                        $q['parsed_context_raw'] = $rawCtxSegs;
                                                    }
                                                } else if (is_array($q['context'])) {
                                                    $q['context_html'] = [];
                                                    foreach ($q['context'] as $c) {
                                                        $q['context_html'][] = renderHskRubyText($c);
                                                    }
                                                }
                                            }
                                            if (!empty($q['options'])) {
                                                $qOpts = is_string($q['options']) ? json_decode($q['options'], true) : $q['options'];
                                                if (is_array($qOpts)) {
                                                    foreach ($qOpts as &$opt) {
                                                        if (is_array($opt) && isset($opt['text'])) {
                                                            if (empty($opt['pinyin'])) {
                                                                try {
                                                                    $opt['pinyin'] = implode(' ', \Overtrue\Pinyin\Pinyin::sentence($opt['text'])->toArray());
                                                                } catch (\Throwable $e) {}
                                                            }
                                                            $opt['html'] = renderHskRubyText($opt['text']);
                                                        } else if (is_string($opt)) {
                                                            $py = '';
                                                            try {
                                                                $py = implode(' ', \Overtrue\Pinyin\Pinyin::sentence($opt)->toArray());
                                                            } catch (\Throwable $e) {}
                                                            $opt = [
                                                                'text' => $opt,
                                                                'pinyin' => $py,
                                                                'html' => renderHskRubyText($opt)
                                                            ];
                                                        }
                                                    }
                                                    $q['options'] = $qOpts;
                                                }
                                            }
                                            if (!empty($q['hints'])) {
                                                $qHints = is_string($q['hints']) ? json_decode($q['hints'], true) : $q['hints'];
                                                if (is_array($qHints)) {
                                                    foreach ($qHints as &$hint) {
                                                        if (is_array($hint) && isset($hint['text'])) {
                                                            if (empty($hint['pinyin'])) {
                                                                try {
                                                                    $hint['pinyin'] = implode(' ', \Overtrue\Pinyin\Pinyin::sentence($hint['text'])->toArray());
                                                                } catch (\Throwable $e) {}
                                                            }
                                                            $hint['html'] = renderHskRubyText($hint['text']);
                                                        } else if (is_string($hint)) {
                                                            $py = '';
                                                            try {
                                                                $py = implode(' ', \Overtrue\Pinyin\Pinyin::sentence($hint)->toArray());
                                                            } catch (\Throwable $e) {}
                                                            $hint = [
                                                                'text' => $hint,
                                                                'pinyin' => $py,
                                                                'html' => renderHskRubyText($hint)
                                                            ];
                                                        }
                                                    }
                                                    $q['hints'] = $qHints;
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
                                                $segments = is_string($q['question_segments']) ? json_decode($q['question_segments'], true) : $q['question_segments'];
                                                $newSegments = [];
                                                if (is_array($segments)) {
                                                    foreach ($segments as $seg) {
                                                    if (is_string($seg)) {
                                                        $newSegments[] = [
                                                            'text' => $seg,
                                                            'html' => renderHskRubyText($seg)
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
                                                    $sq['question_html'] = !empty($sq['question']) ? renderHskRubyText($sq['question']) : '';
                                                    if (!empty($sq['options'])) {
                                                        foreach ($sq['options'] as &$opt) {
                                                            if (is_array($opt) && isset($opt['text'])) {
                                                                $opt['html'] = renderHskRubyText($opt['text']);
                                                            } else if (is_string($opt)) {
                                                                $opt = [
                                                                    'text' => $opt,
                                                                    'html' => renderHskRubyText($opt)
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
    activeTab: '{{ $activeTab }}', 
    vocabSubView: 'table', 
    fcMode: 'flashcard', 
    fcIndex: 0, 
    fcFlipped: false, 
    practiceTab: 'listening',
    practiceSectionIdx: 0,
    socialDockExpanded: true, 
    shouldShowPinyin: @json($shouldShow),
    // Thêm currentLesson data vào Alpine context
    @if(isset($currentLesson) && $currentLesson)
    currentLessonId: {{ $currentLesson->id }},
    vocabularies: {{ Js::from($currentLesson->vocabList ?? []) }},
    @else
    currentLessonId: null,
    vocabularies: [],
    @endif
    // Hỗ trợ component cũ
    currentLesson: {{ Js::from($lessonData) }},
    currentLevelObj: {{ Js::from($currentLevel) }},
    // Các hàm cho tab luyện tập
    isSectionFullyAnswered(questions) {
        if (!questions || !questions.length) return false;
        return questions.every(q => {
            if (!q.correct_answer && (!q.sub_questions || q.sub_questions.length === 0)) return true;
            if (q.sub_questions && q.sub_questions.length > 0) {
                return q.sub_questions.every(sq => {
                    if (!sq.correct) return true;
                    if (sq.ques_type === 'fill_blank' || sq.ques_type === 'reorder') return sq.selected_option !== undefined && sq.selected_option !== null;
                    return sq.selected !== undefined && sq.selected !== null;
                });
            }
            if (q.ques_type === 'reorder' || q.ques_type === 'writing') return q.userAnswer && q.userAnswer.trim() !== '';
            if (q.ques_type === 'fill_blank_dropdown') {
                if (!q.selected_answers) return false;
                return q.selected_answers.every(ans => ans !== '' && ans !== null && ans !== undefined);
            }
            return q.selected !== undefined && q.selected !== null;
        });
    },
    getSectionAnsweredProgress(questions) {
        if (!questions || !questions.length) return { answered: 0, total: 0 };
        let total = 0;
        let answered = 0;
        questions.forEach(q => {
            if (!q.correct_answer && (!q.sub_questions || q.sub_questions.length === 0)) return;
            if (q.sub_questions && q.sub_questions.length > 0) {
                q.sub_questions.forEach(sq => {
                    if (!sq.correct) return;
                    total++;
                    let isDone = false;
                    if (sq.ques_type === 'fill_blank' || sq.ques_type === 'reorder') isDone = sq.selected_option !== undefined && sq.selected_option !== null;
                    else isDone = sq.selected !== undefined && sq.selected !== null;
                    if (isDone) answered++;
                });
            } else {
                total++;
                let isDone = false;
                if (q.ques_type === 'reorder' || q.ques_type === 'writing') isDone = !!(q.userAnswer && q.userAnswer.trim() !== '');
                else if (q.ques_type === 'fill_blank_dropdown') {
                    isDone = !!(q.selected_answers && q.selected_answers.length > 0 && q.selected_answers.every(ans => ans !== '' && ans !== null && ans !== undefined));
                } else isDone = q.selected !== undefined && q.selected !== null;
                if (isDone) answered++;
            }
        });
        return { answered, total };
    },
    checkAllSection(questions) {
        if (!questions) return;
        questions.forEach(q => {
            if (q.correct_answer) q.answered = true;
            if (q.sub_questions) {
                q.sub_questions.forEach(sq => {
                    if (sq.correct) sq.answered = true;
                });
            }
        });
    },
    resetSection(questions) {
        if (!questions) return;
        questions.forEach(q => {
            q.answered = false;
            q.selected = null;
            q.userAnswer = '';
            q.selected_answers = [];
            if (q.sub_questions) {
                q.sub_questions.forEach(sq => {
                    sq.answered = false;
                    sq.selected = null;
                    sq.selected_answers = [];
                });
            }
        });
    },
    initPracticeData() {
        if (this.currentLesson && this.currentLesson.practices) {
            this.currentLesson.practices.forEach(practice => {
                if (practice.sections) {
                    practice.sections.forEach(sec => {
                        if (sec.questions) {
                            sec.questions.forEach(q => {
                                q.selected = null;
                                q.answered = false;
                                if (q.ques_type === 'fill_blank_dropdrag') {
                                    if (q.context && q.context.includes('@{{blank}}')) {
                                        q.parsed_context = q.context.split('@{{blank}}');
                                    } else {
                                        q.parsed_context = [q.context];
                                    }
                                    q.available_options = [];
                                    if (q.options) {
                                        q.options.forEach((opt, idx) => {
                                            q.available_options.push({ id: idx, text: opt.text || opt, used: false });
                                        });
                                    }
                                }
                                if (q.ques_type === 'fill_blank_dropdown') {
                                    if (q.parsed_question_raw && Array.isArray(q.parsed_question_raw)) {
                                        q.parsed_question = q.parsed_question_raw;
                                    } else if (q.question && q.question.includes('@{{blank}}')) {
                                        q.parsed_question = q.question.split('@{{blank}}');
                                    } else {
                                        q.parsed_question = [q.question];
                                    }
                                    q.selected_answers = new Array(Math.max(0, q.parsed_question.length - 1)).fill('');
                                    if (typeof q.hints === 'string') try { q.hints = JSON.parse(q.hints); } catch(e){}
                                    if (typeof q.options === 'string') try { q.options = JSON.parse(q.options); } catch(e){}
                                    if (typeof q.correct === 'string') try { q.correct = JSON.parse(q.correct); } catch(e){}
                                    if (typeof q.correct_answer === 'string' && q.correct_answer.startsWith('[')) try { q.correct = JSON.parse(q.correct_answer); } catch(e){}
                                }
                                if (q.sub_questions && Array.isArray(q.sub_questions)) {
                                    q.sub_questions.forEach(sq => {
                                        sq.selected = null;
                                        sq.selected_option = null;
                                        sq.answered = false;
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    },
    draggedItemText: null,
    draggedSource: null,
    startDrag(event, text, source) {
        this.draggedItemText = text;
        this.draggedSource = source;
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', text);
    },
    onDrop(event, quiz, targetIndex) {
        event.preventDefault();
        if (!this.draggedItemText) return;
        if (targetIndex !== 'pool') {
            const sq = quiz.sub_questions[targetIndex];
            if (sq.answered) return;
            if (sq.selected_option) {
                const opt = quiz.available_options.find(o => o.text === sq.selected_option);
                if (opt) opt.used = false;
            }
            const newOpt = quiz.available_options.find(o => o.text === this.draggedItemText);
            if (newOpt) newOpt.used = true;
            if (this.draggedSource !== 'pool' && this.draggedSource !== targetIndex) {
                quiz.sub_questions[this.draggedSource].selected_option = null;
            }
            sq.selected_option = this.draggedItemText;
        } else {
            if (this.draggedSource !== 'pool') {
                const sq = quiz.sub_questions[this.draggedSource];
                if (!sq.answered) {
                    const opt = quiz.available_options.find(o => o.text === sq.selected_option);
                    if (opt) opt.used = false;
                    sq.selected_option = null;
                }
            }
        }
        this.draggedItemText = null;
        this.draggedSource = null;
    }
@endsection
@section('scripts')
<script>
    window.alignPinyin = function(hanzi, pinyin, levelCode) {
        if (!hanzi || !pinyin) return null;
        const pArr = pinyin.trim().split(/\s+/).filter(Boolean);
        const hArr = hanzi.replace(/\s+/g, '').split('');
        if (pArr.length > 0 && pArr.length === hArr.length) {
            return hArr.map((h, i) => ({ h, p: pArr[i] }));
        }
        return null;
    };
</script>
@endsection
@section('header-left')
    @if(isset($currentLevel) && isset($currentLesson))
        @php
            $isDummyData = ($currentLesson->title === 'Bài ' . $currentLesson->lesson_number);
            $displayTitleBreadcrumb = preg_replace('/^Bài\s+\d+[:\-]?\s*/i', '', $currentLesson->title);
            $displayTitleBreadcrumb = empty(trim($displayTitleBreadcrumb)) ? '' : ': ' . $displayTitleBreadcrumb;
            if ($isDummyData) {
                $displayTitleBreadcrumb = '';
            }
        @endphp
        <!-- Breadcrumbs -->
        <div class="flex items-center text-xs text-slate-500 font-semibold truncate">
            <a href="{{ route('home') }}" class="hover:text-[#e07a5f] transition-colors"><i class="fa-solid fa-house text-xs mr-1"></i>{{ __('Trang chủ') }}</a>
            <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
            <a href="{{ route('courses') }}" class="hover:text-[#e07a5f] transition-colors">{{ __('Khóa học') }}</a>
            <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
            <a href="{{ route('courses.level', ['levelSlug' => $currentLevel->slug]) }}" class="hover:text-[#e07a5f] transition-colors">{{ $currentLevel->title }}</a>
            <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
            <span class="text-slate-900 dark:text-white font-bold truncate">{{ __('Bài') }} {{ $currentLesson->lesson_number }}{{ $isDummyData ? '' : $displayTitleBreadcrumb }}</span>
        </div>
    @endif
@endsection
@section('header-right')
    <!-- Empty to hide language selector in detail page -->
@endsection
@section('sub-header')
    <div class="lms-card p-2 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between gap-3 overflow-x-auto no-scrollbar shadow-xs">
        <div class="flex items-center gap-1.5">
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'tu-vung']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'tu-vung' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-list-ul text-xs"></i>
                <span>{{ __('Từ vựng') }}</span>
            </a>
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'hoi-thoai']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'hoi-thoai' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-comments text-xs"></i>
                <span>{{ __('Bài khóa') }}</span>
            </a>
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'ngu-phap']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'ngu-phap' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-spell-check text-xs"></i>
                <span>{{ __('Ngữ pháp') }}</span>
            </a>
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'luyen-tap']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'luyen-tap' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>{{ __('Luyện tập') }}</span>
            </a>
        </div>
        <div x-show="activeTab === 'tu-vung'" style="display: none;" class="flex items-center gap-1 p-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl shrink-0 overflow-x-auto no-scrollbar max-w-full">
            <button @click="vocabSubView = 'table';" 
                    :class="vocabSubView === 'table' ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-table-cells"></i>
                <span>{{ __('Bảng từ') }}</span>
            </button>
            <button @click="vocabSubView = 'flashcard'; fcIndex = 0; fcFlipped = false;" 
                    :class="vocabSubView === 'flashcard' ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-layer-group"></i>
                <span>{{ __('Flashcard') }}</span>
            </button>
            <button @click="vocabSubView = 'match';" 
                    :class="vocabSubView === 'match' ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-puzzle-piece"></i>
                <span>{{ __('Nối từ') }}</span>
            </button>
            <button @click="vocabSubView = 'quiz';" 
                    :class="vocabSubView === 'quiz' ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-clipboard-question"></i>
                <span>{{ __('Trắc nghiệm') }}</span>
            </button>
            <button @click="vocabSubView = 'typing';" 
                    :class="vocabSubView === 'typing' ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-semibold'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-keyboard"></i>
                <span>{{ __('Gõ phím') }}</span>
            </button>
        </div>
    </div>
@endsection
@section('content')
    <div class="space-y-4">
        <!-- TAB CONTENT -->
        <div>
            @if($activeTab === 'tu-vung')
                @include('course-v2.tabs.vocab')
            @elseif($activeTab === 'hoi-thoai')
                @include('course-v2.tabs.dialogue')
            @elseif($activeTab === 'ngu-phap')
                @include('course-v2.tabs.grammar')
            @elseif($activeTab === 'luyen-tap')
                @include('course-v2.tabs.practice')
            @endif
        </div>
    </div>
@endsection