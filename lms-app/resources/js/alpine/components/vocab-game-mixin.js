/**
 * Shared mini-game logic (Quiz & Match) for vocabulary practice.
 * All comments are in English according to project guidelines.
 */

// Synthesize pleasant sound effects using Web Audio API when external audio helpers are unavailable
function playAudioFeedback(type) {
    if (typeof window === 'undefined') return;
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        const now = ctx.currentTime;

        if (type === 'correct') {
            osc.type = 'sine';
            osc.frequency.setValueAtTime(523.25, now); // C5
            osc.frequency.exponentialRampToValueAtTime(783.99, now + 0.12); // G5
            gain.gain.setValueAtTime(0.18, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.28);
            osc.start(now);
            osc.stop(now + 0.28);
        } else if (type === 'wrong') {
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(220, now); // A3
            osc.frequency.linearRampToValueAtTime(146.83, now + 0.18); // D3
            gain.gain.setValueAtTime(0.12, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.22);
            osc.start(now);
            osc.stop(now + 0.22);
        }
    } catch (e) {
        // AudioContext may be restricted before user interaction, safely ignore
    }
}

export function createGameMixin(getVocabListFn, speakFn = null) {
    return {
        // Quiz State
        quizQuestions: [],
        quizCurrentIndex: 0,
        currentQuizQuestion: null,
        quizSelectedOption: null,
        quizIsAnswered: false,
        quizScore: 0,
        quizStreak: 0,
        quizCorrectCount: 0,
        quizIsCompleted: false,

        // Match Game State
        matchCurrentPairs: [],
        matchLeftItems: [],
        matchRightItems: [],
        matchSelectedLeft: null,
        matchSelectedRight: null,
        matchMatchedPairs: [],
        matchScore: 0,
        matchTimer: 0,
        matchTimerInterval: null,
        matchAttempts: 0,
        matchCorrectCount: 0,
        matchAccuracy: 100,
        matchIsCompleted: false,
        isCheckingMatch: false,

        /**
         * Initialize multiple choice quiz session.
         */
        initQuiz() {
            const list = getVocabListFn.call(this) || [];
            if (!list || list.length < 4) {
                this.quizQuestions = [];
                this.currentQuizQuestion = null;
                return;
            }
            this.quizCurrentIndex = 0;
            this.quizSelectedOption = null;
            this.quizIsAnswered = false;
            this.quizScore = 0;
            this.quizStreak = 0;
            this.quizCorrectCount = 0;
            this.quizIsCompleted = false;

            const shuffled = [...list].sort(() => 0.5 - Math.random());
            const selected = shuffled.slice(0, 10);

            this.quizQuestions = selected.map(item => {
                // Find candidates with distinct meanings to avoid ambiguous multiple choice options
                const others = list.filter(v => v.id !== item.id && v.meaning !== item.meaning);
                const shuffledOthers = [...others].sort(() => 0.5 - Math.random());
                const uniqueDistractors = [];
                const seen = new Set();
                for (const o of shuffledOthers) {
                    if (!seen.has(o.meaning)) {
                        seen.add(o.meaning);
                        uniqueDistractors.push(o);
                        if (uniqueDistractors.length === 3) break;
                    }
                }
                // Fallback in case not enough unique meanings exist
                while (uniqueDistractors.length < 3 && others.length >= 3) {
                    const fallback = shuffledOthers[uniqueDistractors.length];
                    if (fallback) uniqueDistractors.push(fallback);
                    else break;
                }

                const options = [
                    { id: item.id, text: item.meaning, isCorrect: true },
                    ...uniqueDistractors.map(o => ({ id: o.id, text: o.meaning, isCorrect: false })),
                ].sort(() => 0.5 - Math.random());

                return {
                    id: item.id,
                    word: item.word,
                    pinyin: item.pinyin,
                    meaning: item.meaning,
                    example: item.example || '',
                    options: options,
                };
            });

            this.currentQuizQuestion = this.quizQuestions[0] || null;
        },

        selectQuizOption(opt) {
            if (this.quizIsAnswered) return;
            this.quizSelectedOption = opt;
            this.quizIsAnswered = true;
            if (opt.isCorrect) {
                this.quizScore += 10;
                this.quizStreak += 1;
                this.quizCorrectCount += 1;
                if (typeof window.playCorrectSound === 'function') {
                    window.playCorrectSound();
                } else {
                    playAudioFeedback('correct');
                }
            } else {
                this.quizStreak = 0;
                if (typeof window.playWrongSound === 'function') {
                    window.playWrongSound();
                } else {
                    playAudioFeedback('wrong');
                }
            }
        },

        nextQuizQuestion() {
            if (this.quizCurrentIndex < this.quizQuestions.length - 1) {
                this.quizCurrentIndex++;
                this.currentQuizQuestion = this.quizQuestions[this.quizCurrentIndex] || null;
                this.quizSelectedOption = null;
                this.quizIsAnswered = false;
            } else {
                this.quizIsCompleted = true;
            }
        },

        /**
         * Initialize matching cards game session.
         */
        initMatchGame() {
            const list = getVocabListFn.call(this) || [];
            if (!list || list.length < 2) {
                this.matchCurrentPairs = [];
                return;
            }

            clearInterval(this.matchTimerInterval);
            this.matchTimer = 0;
            this.matchScore = 0;
            this.matchAttempts = 0;
            this.matchCorrectCount = 0;
            this.matchAccuracy = 100;
            this.matchMatchedPairs = [];
            this.matchSelectedLeft = null;
            this.matchSelectedRight = null;
            this.isCheckingMatch = false;
            this.matchIsCompleted = false;

            const shuffled = [...list].sort(() => 0.5 - Math.random());
            this.matchCurrentPairs = shuffled.slice(0, 6);

            this.matchLeftItems = this.matchCurrentPairs.map(v => ({
                id: v.id,
                word: v.word,
                pinyin: v.pinyin,
                isMatched: false,
                isWrong: false,
            })).sort(() => 0.5 - Math.random());

            this.matchRightItems = this.matchCurrentPairs.map(v => ({
                id: v.id,
                meaning: v.meaning,
                isMatched: false,
                isWrong: false,
            })).sort(() => 0.5 - Math.random());

            this.matchTimerInterval = setInterval(() => {
                this.matchTimer++;
            }, 1000);
        },

        formatMatchTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },

        selectMatchLeft(item) {
            if (this.isCheckingMatch || item.isMatched || item.isWrong) return;
            // Deselect if already selected
            if (this.matchSelectedLeft && this.matchSelectedLeft.id === item.id) {
                this.matchSelectedLeft = null;
                return;
            }
            this.matchSelectedLeft = item;
            if (this.matchSelectedRight) {
                this.checkMatch();
            }
        },

        selectMatchRight(item) {
            if (this.isCheckingMatch || item.isMatched || item.isWrong) return;
            // Deselect if already selected
            if (this.matchSelectedRight && this.matchSelectedRight.id === item.id) {
                this.matchSelectedRight = null;
                return;
            }
            this.matchSelectedRight = item;
            if (this.matchSelectedLeft) {
                this.checkMatch();
            }
        },

        checkMatch() {
            this.matchAttempts++;
            if (this.matchSelectedLeft.id === this.matchSelectedRight.id) {
                this.matchSelectedLeft.isMatched = true;
                this.matchSelectedRight.isMatched = true;
                this.matchMatchedPairs.push(this.matchSelectedLeft.id);
                this.matchCorrectCount++;
                this.matchScore += 20;
                this.matchAccuracy = Math.round((this.matchCorrectCount / this.matchAttempts) * 100);

                if (typeof window.playCorrectSound === 'function') {
                    window.playCorrectSound();
                } else {
                    playAudioFeedback('correct');
                }
                if (typeof speakFn === 'function') {
                    speakFn.call(this, this.matchSelectedLeft.word);
                }

                this.matchSelectedLeft = null;
                this.matchSelectedRight = null;

                if (this.matchMatchedPairs.length === this.matchCurrentPairs.length) {
                    clearInterval(this.matchTimerInterval);
                    setTimeout(() => {
                        this.matchIsCompleted = true;
                    }, 500);
                }
            } else {
                if (typeof window.playWrongSound === 'function') {
                    window.playWrongSound();
                } else {
                    playAudioFeedback('wrong');
                }
                const left = this.matchSelectedLeft;
                const right = this.matchSelectedRight;
                left.isWrong = true;
                right.isWrong = true;
                this.isCheckingMatch = true;
                setTimeout(() => {
                    left.isWrong = false;
                    right.isWrong = false;
                    this.matchSelectedLeft = null;
                    this.matchSelectedRight = null;
                    this.isCheckingMatch = false;
                }, 500);
            }
        },
    };
}
