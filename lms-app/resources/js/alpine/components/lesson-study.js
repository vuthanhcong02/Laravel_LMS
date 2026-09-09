/**
 * Hàm align Pinyin theo từng ký tự Hán tự
 */
export const alignPinyin = function (hanzi, pinyin, levelCode) {
    if (!hanzi || !pinyin) return null;
    const pArr = pinyin.trim().split(/\s+/).filter(Boolean);
    const hArr = hanzi.replace(/\s+/g, '').split('');
    if (pArr.length > 0 && pArr.length === hArr.length) {
        return hArr.map((h, i) => ({ h, p: pArr[i] }));
    }
    return null;
};

// Đăng ký toàn cục để các helper blade có thể gọi
if (typeof window !== 'undefined') {
    window.alignPinyin = alignPinyin;
}

/**
 * Component quản lý toàn bộ trang học bài HSK (Từ vựng, Bài khóa, Ngữ pháp, Luyện tập)
 */
export const lessonStudyApp = (config = {}) => ({
    activeTab: config.activeTab || 'tu-vung',

    get vocabSubView() {
        return window.Alpine?.store('lesson')?.vocabSubView || 'table';
    },
    set vocabSubView(v) {
        if (window.Alpine?.store('lesson')) window.Alpine.store('lesson').vocabSubView = v;
    },

    get fcMode() {
        return window.Alpine?.store('lesson')?.fcMode || 'flashcard';
    },
    set fcMode(v) {
        if (window.Alpine?.store('lesson')) window.Alpine.store('lesson').fcMode = v;
    },

    get fcIndex() {
        return window.Alpine?.store('lesson')?.fcIndex || 0;
    },
    set fcIndex(v) {
        if (window.Alpine?.store('lesson')) window.Alpine.store('lesson').fcIndex = v;
    },

    get fcFlipped() {
        return window.Alpine?.store('lesson')?.fcFlipped || false;
    },
    set fcFlipped(v) {
        if (window.Alpine?.store('lesson')) window.Alpine.store('lesson').fcFlipped = v;
    },

    get practiceTab() {
        return window.Alpine?.store('lesson')?.practiceTab || 'listening';
    },
    set practiceTab(v) {
        if (window.Alpine?.store('lesson')) window.Alpine.store('lesson').practiceTab = v;
    },

    get practiceSectionIdx() {
        return window.Alpine?.store('lesson')?.practiceSectionIdx || 0;
    },
    set practiceSectionIdx(v) {
        if (window.Alpine?.store('lesson')) window.Alpine.store('lesson').practiceSectionIdx = v;
    },

    shouldShowPinyin: config.shouldShowPinyin ?? true,
    currentLessonId: config.currentLessonId || null,
    vocabularies: config.vocabularies || [],
    currentLesson: config.currentLesson || null,
    currentLevelObj: config.currentLevelObj || null,

    init() {
        if (typeof this.initPracticeData === 'function') {
            this.initPracticeData();
        }
    },

    isSectionFullyAnswered(questions) {
        if (!questions || !questions.length) return false;
        return questions.every(q => {
            if (!q.correct_answer && (!q.sub_questions || q.sub_questions.length === 0)) return true;
            if (q.sub_questions && q.sub_questions.length > 0) {
                return q.sub_questions.every(sq => {
                    if (!sq.correct) return true;
                    if (sq.ques_type === 'fill_blank' || sq.ques_type === 'reorder') {
                        return sq.selected_option !== undefined && sq.selected_option !== null;
                    }
                    return sq.selected !== undefined && sq.selected !== null;
                });
            }
            if (q.ques_type === 'reorder' || q.ques_type === 'writing') {
                return q.userAnswer && q.userAnswer.trim() !== '';
            }
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
                    if (sq.ques_type === 'fill_blank' || sq.ques_type === 'reorder') {
                        isDone = sq.selected_option !== undefined && sq.selected_option !== null;
                    } else {
                        isDone = sq.selected !== undefined && sq.selected !== null;
                    }
                    if (isDone) answered++;
                });
            } else {
                total++;
                let isDone = false;
                if (q.ques_type === 'reorder' || q.ques_type === 'writing') {
                    isDone = !!(q.userAnswer && q.userAnswer.trim() !== '');
                } else if (q.ques_type === 'fill_blank_dropdown') {
                    isDone = !!(q.selected_answers && q.selected_answers.length > 0 && q.selected_answers.every(ans => ans !== '' && ans !== null && ans !== undefined));
                } else {
                    isDone = q.selected !== undefined && q.selected !== null;
                }
                if (isDone) answered++;
            }
        });
        return { answered, total };
    },

    checkAllSection(questions) {
        if (!questions) return;
        questions.forEach(q => {
            if (!q.correct_answer && (!q.sub_questions || q.sub_questions.length === 0)) return;
            if (q.sub_questions && q.sub_questions.length > 0) {
                q.sub_questions.forEach(sq => {
                    sq.answered = true;
                });
            } else {
                q.answered = true;
            }
        });
    },

    resetAllSection(questions) {
        if (!questions) return;
        questions.forEach(q => {
            q.answered = false;
            q.selected = null;
            q.userAnswer = '';
            if (q.ques_type === 'fill_blank_dropdown' && q.parsed_question) {
                q.selected_answers = new Array(Math.max(0, q.parsed_question.length - 1)).fill('');
            }
            if (q.sub_questions && q.sub_questions.length > 0) {
                q.sub_questions.forEach(sq => {
                    sq.answered = false;
                    sq.selected = null;
                    sq.selected_option = null;
                });
                if (q.available_options && Array.isArray(q.available_options)) {
                    q.available_options.forEach(opt => opt.used = false);
                }
            }
        });
    },

    initPracticeData() {
        if (this.currentLesson && this.currentLesson.practices) {
            this.currentLesson.practices.forEach(p => {
                if (p.sections) {
                    p.sections.forEach(s => {
                        if (s.questions) {
                            s.questions.forEach(q => {
                                q.selected = null;
                                q.answered = false;
                                q.userAnswer = '';
                                if (q.ques_type === 'match_text' && q.items) {
                                    const opts = q.items.map(item => ({
                                        id: item.id || item.tag,
                                        text: item.text,
                                        html: item.html || item.text,
                                        used: false
                                    }));
                                    q.available_options = opts;
                                }
                                if (q.ques_type === 'fill_blank_dropdown') {
                                    if (q.parsed_question && Array.isArray(q.parsed_question)) {
                                        // already parsed
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
});

export default lessonStudyApp;
