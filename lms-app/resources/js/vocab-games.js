// ==========================================
// AUDIO HELPER: Tạo âm thanh "ding" bằng code (rất nhẹ)
// ==========================================
window.playCorrectSound = function() {
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;
        const ctx = new AudioContext();
        
        // 2 notes for a happy "ding ding" (C5 -> E5)
        const playNote = (freq, startTime, duration) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, startTime);
            
            gain.gain.setValueAtTime(0, startTime);
            gain.gain.linearRampToValueAtTime(0.15, startTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(startTime);
            osc.stop(startTime + duration);
        };

        const now = ctx.currentTime;
        playNote(523.25, now, 0.15); // C5
        playNote(659.25, now + 0.1, 0.3); // E5
    } catch (e) {
        console.log('Audio not supported', e);
    }
};

window.playWrongSound = function() {
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;
        const ctx = new AudioContext();
        
        // "Uh-oh" sound: Hai nốt trầm nhẹ nhàng (sine wave)
        const playNote = (freq, startTime, duration) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine'; // Dùng sóng sine cho âm thanh tròn và êm
            osc.frequency.setValueAtTime(freq, startTime);
            
            gain.gain.setValueAtTime(0, startTime);
            gain.gain.linearRampToValueAtTime(0.4, startTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(startTime);
            osc.stop(startTime + duration);
        };

        const now = ctx.currentTime;
        playNote(300, now, 0.15);        // Nốt đầu (cao hơn 1 chút)
        playNote(220, now + 0.15, 0.25); // Nốt sau (trầm hơn) tạo hiệu ứng "uh-oh"
    } catch (e) {
        console.log('Audio not supported', e);
    }
};

// ==========================================
// 1. ENGINE GAME NỐI TỪ (MATCH WORDS)
// ==========================================
window.vocabMatchEngine = function() {
    return {
        currentPairs: [],
        leftItems: [],
        rightItems: [],
        selectedLeft: null,
        selectedRight: null,
        matchedPairs: [],
        score: 0,
        timer: 0,
        timerInterval: null,
        isCompleted: false,
        showPinyinHint: false,
        attempts: 0,
        correctMatches: 0,

        get accuracy() {
            if (this.attempts === 0) return 100;
            return Math.round((this.correctMatches / this.attempts) * 100);
        },

        formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },

        initMatchGame(vocabList) {
            if (!vocabList || vocabList.length < 2) return;
            
            clearInterval(this.timerInterval);
            this.timer = 0;
            this.score = 0;
            this.attempts = 0;
            this.correctMatches = 0;
            this.matchedPairs = [];
            this.selectedLeft = null;
            this.selectedRight = null;
            this.isCompleted = false;

            // Pick up to 6 random pairs
            let shuffled = [...vocabList].sort(() => 0.5 - Math.random());
            this.currentPairs = shuffled.slice(0, 6);

            // Build Left (Chinese) & Right (Vietnamese) items
            this.leftItems = this.currentPairs.map(v => ({
                id: v.id,
                word: v.word,
                pinyin: v.pinyin,
                audio_url: v.audio_url,
                isMatched: false,
                isWrong: false
            })).sort(() => 0.5 - Math.random());

            this.rightItems = this.currentPairs.map(v => ({
                id: v.id,
                meaning: v.meaning,
                isMatched: false,
                isWrong: false
            })).sort(() => 0.5 - Math.random());

            // Start Timer
            this.timerInterval = setInterval(() => {
                this.timer++;
            }, 1000);
        },

        selectLeft(item) {
            if (item.isMatched) return;
            this.selectedLeft = item;
            if (this.selectedRight) {
                this.checkMatch();
            }
        },

        selectRight(item) {
            if (item.isMatched) return;
            this.selectedRight = item;
            if (this.selectedLeft) {
                this.checkMatch();
            }
        },

        checkMatch() {
            this.attempts++;
            if (this.selectedLeft.id === this.selectedRight.id) {
                // Correct Match
                window.playCorrectSound();
                this.correctMatches++;
                this.score += 10;
                this.selectedLeft.isMatched = true;
                this.selectedRight.isMatched = true;
                this.matchedPairs.push(this.selectedLeft.id);

                // Play audio
                if (this.selectedLeft.word) {
                    window.playAudio(this.selectedLeft.audio_url || ('https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(this.selectedLeft.word) + '&type=1'));
                }

                this.selectedLeft = null;
                this.selectedRight = null;

                // Check Win Condition
                if (this.matchedPairs.length === this.currentPairs.length) {
                    clearInterval(this.timerInterval);
                    setTimeout(() => {
                        this.isCompleted = true;
                    }, 500);
                }
            } else {
                // Wrong Match
                window.playWrongSound();
                const left = this.selectedLeft;
                const right = this.selectedRight;
                left.isWrong = true;
                right.isWrong = true;

                setTimeout(() => {
                    left.isWrong = false;
                    right.isWrong = false;
                    this.selectedLeft = null;
                    this.selectedRight = null;
                }, 600);
            }
        }
    };
};

// ==========================================
// 2. ENGINE TRẮC NGHIỆM TỪ VỰNG (VOCAB QUIZ)
// ==========================================
window.vocabQuizEngine = function() {
    return {
        questions: [],
        currentIndex: 0,
        selectedOption: null,
        isAnswered: false,
        score: 0,
        streak: 0,
        correctCount: 0,
        isCompleted: false,

        get currentQuestion() {
            return this.questions[this.currentIndex] || null;
        },

        initQuiz(vocabList) {
            if (!vocabList || vocabList.length < 4) return;

            this.currentIndex = 0;
            this.selectedOption = null;
            this.isAnswered = false;
            this.score = 0;
            this.streak = 0;
            this.correctCount = 0;
            this.isCompleted = false;

            // Pick up to 10 questions
            let shuffledVocab = [...vocabList].sort(() => 0.5 - Math.random());
            let quizList = shuffledVocab.slice(0, 10);

            this.questions = quizList.map(item => {
                // Create 3 random distractors
                let otherMeanings = vocabList
                    .filter(v => v.id !== item.id)
                    .map(v => v.meaning)
                    .sort(() => 0.5 - Math.random())
                    .slice(0, 3);

                // If not enough unique distractors, fill with placeholder
                while (otherMeanings.length < 3) {
                    otherMeanings.push('Đáp án ngẫu nhiên ' + (otherMeanings.length + 1));
                }

                // Shuffle options
                let allOptions = [item.meaning, ...otherMeanings].sort(() => 0.5 - Math.random());
                let correctIdx = allOptions.indexOf(item.meaning);

                return {
                    id: item.id,
                    word: item.word,
                    pinyin: item.pinyin,
                    type: item.type,
                    meaning: item.meaning,
                    example: item.example,
                    audio_url: item.audio_url,
                    options: allOptions,
                    correctOptionIdx: correctIdx
                };
            });
        },

        selectAnswer(optionIdx) {
            if (this.isAnswered) return;

            this.selectedOption = optionIdx;
            this.isAnswered = true;

            if (optionIdx === this.currentQuestion.correctOptionIdx) {
                window.playCorrectSound();
                this.correctCount++;
                this.streak++;
                this.score += 10 + (this.streak > 1 ? 5 : 0);
            } else {
                window.playWrongSound();
                this.streak = 0;
            }
        },

        nextQuestion() {
            if (this.currentIndex < this.questions.length - 1) {
                this.currentIndex++;
                this.selectedOption = null;
                this.isAnswered = false;
            } else {
                this.isCompleted = true;
            }
        }
    };
};

// ==========================================
// 3. ENGINE LUYỆN GÕ PHÍM (VOCAB TYPING)
// ==========================================
window.vocabTypingEngine = function() {
    return {
        words: [],
        currentIndex: 0,
        inputVal: '',
        status: 'idle', // 'idle', 'correct', 'wrong'
        showHint: false,
        correctCount: 0,
        isCompleted: false,

        get currentWord() {
            return this.words[this.currentIndex] || null;
        },

        initTyping(vocabList) {
            if (!vocabList || vocabList.length === 0) return;

            this.currentIndex = 0;
            this.inputVal = '';
            this.status = 'idle';
            this.showHint = false;
            this.correctCount = 0;
            this.isCompleted = false;
            this._timeout = null;
            this.words = [...vocabList].sort(() => 0.5 - Math.random());
        },

        cleanStr(str) {
            if (!str) return '';
            return str
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^\p{L}\p{N}]/gu, '');
        },

        checkInput() {
            if (this.status === 'correct') {
                this.nextWord();
                return;
            }

            if (!this.inputVal.trim() || !this.currentWord) return;

            let userClean = this.cleanStr(this.inputVal);
            let wordClean = this.cleanStr(this.currentWord.word);
            let pinyinClean = this.cleanStr(this.currentWord.pinyin);

            // Matches exact Hanzi or Pinyin (with or without tones)
            if (userClean === wordClean || userClean === pinyinClean || this.inputVal.trim() === this.currentWord.word) {
                this.status = 'correct';
                window.playCorrectSound();
                if (!this.showHint) {
                    this.correctCount++;
                }
                window.playAudio(this.currentWord.audio_url || ('https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(this.currentWord.word) + '&type=1'));
            } else {
                window.playWrongSound();
                this.status = 'wrong';
                if (this._timeout) clearTimeout(this._timeout);
                this._timeout = setTimeout(() => {
                    if (this.status === 'wrong') {
                        this.status = 'idle';
                    }
                }, 1200);
            }
        },

        nextWord() {
            if (this.currentIndex < this.words.length - 1) {
                this.currentIndex++;
                this.inputVal = '';
                this.status = 'idle';
                this.showHint = false;
            } else {
                this.isCompleted = true;
            }
        }
    };
};
