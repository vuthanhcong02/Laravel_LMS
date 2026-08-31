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
    activeTab: '{{ $activeTab }}', 
    vocabSubView: 'table', 
    fcMode: 'flashcard', 
    fcIndex: 0, 
    fcFlipped: false, 
    practiceTab: 'listening',
    practiceSectionIdx: 0,
    socialDockExpanded: true, 
    shouldShowPinyin: true,
    
    // Thêm currentLesson data vào Alpine context
    @if(isset($currentLesson) && $currentLesson)
    currentLessonId: {{ $currentLesson->id }},
    vocabularies: {{ Js::from($currentLesson->vocabList ?? []) }},
    @else
    currentLessonId: null,
    vocabularies: [],
    @endif

    // Hỗ trợ component cũ
    currentLesson: {{ Js::from($currentLesson) }},
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
                                    if (q.question && q.question.includes('@{{blank}}')) {
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
            <a href="{{ route('courses.v2') }}" class="hover:text-[#e07a5f] transition-colors">{{ __('Khóa học') }}</a>
            <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
            <a href="{{ route('courses.v2.level', ['levelSlug' => $currentLevel->slug]) }}" class="hover:text-[#e07a5f] transition-colors">{{ $currentLevel->title }}</a>
            <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
            <span class="text-slate-900 dark:text-white font-bold truncate">{{ __('Bài') }} {{ $currentLesson->lesson_number }}{{ $isDummyData ? '' : $displayTitleBreadcrumb }}</span>
        </div>
    @endif
@endsection

@section('header-right')
    <!-- Empty to hide language selector in detail page -->
@endsection

@section('sub-header')
    <!-- THANH 4 TAB CHÍNH KHÓA HỌC -->
    <div class="lms-card p-2 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between gap-3 overflow-x-auto no-scrollbar shadow-xs">
        <div class="flex items-center gap-1.5">
            <!-- Tab 1: Từ vựng -->
            <a href="{{ route('courses.v2.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'tu-vung']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'tu-vung' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-list-ul text-xs"></i>
                <span>{{ __('Từ vựng') }}</span>
            </a>

            <!-- Tab 2: Bài khóa -->
            <a href="{{ route('courses.v2.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'hoi-thoai']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'hoi-thoai' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-comments text-xs"></i>
                <span>{{ __('Bài khóa') }}</span>
            </a>

            <!-- Tab 3: Ngữ pháp -->
            <a href="{{ route('courses.v2.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'ngu-phap']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'ngu-phap' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-spell-check text-xs"></i>
                <span>{{ __('Ngữ pháp') }}</span>
            </a>

            <!-- Tab 4: Luyện tập -->
            <a href="{{ route('courses.v2.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'luyen-tap']) }}" 
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2 {{ $activeTab === 'luyen-tap' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold' }}">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>{{ __('Luyện tập') }}</span>
            </a>
        </div>

        <!-- Sub-Toggle Switcher trong Tab Từ vựng -->
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