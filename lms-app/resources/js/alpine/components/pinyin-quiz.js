/**
 * Alpine Component cho Bài Luyện Tập Phản Xạ Pinyin (Pinyin Reflex Quiz)
 */

export const pinyinQuizApp = (allTonesInput = []) => ({
    allTones: Array.isArray(allTonesInput) ? allTonesInput : (typeof allTonesInput === 'string' ? JSON.parse(allTonesInput || '[]') : []),
    filteredTones: [],
    screen: 'setup',
    showQuitModal: false,
    selectedCategory: 'all',
    configQuizLength: 10,
    quizLength: 10,
    autoAdvance: true,
    autoPlayAudio: true,
    playbackRate: 1.0,
    questionInRound: 1,
    targetTone: null,
    currentOptions: [],
    selectedOpt: null,
    answered: false,
    isCorrect: false,
    score: 0,
    streak: 0,
    maxStreak: 0,
    correctCount: 0,
    mistakes: [],
    isMistakePracticeMode: false,
    mistakePool: [],
    isPlaying: false,
    _audioUnlocked: false,
    advanceInterval: null,
    advanceProgress: 100,
    advanceTimerActive: false,
    isPausedAdvance: false,
    remainingAdvanceMs: 2500,
    advanceTargetEndTime: 0,

    selectQuizLength(len) {
        this.configQuizLength = len;
        this.quizLength = len;
    },

    formatPinyin(pinyin) {
        if (!pinyin) return '';
        let str = String(pinyin).trim();
        str = str.replace(/uue/gi, 'üe').replace(/uun/gi, 'ün').replace(/uu/gi, 'ü');
        str = str.replace(/v/g, 'ü').replace(/V/g, 'Ü');
        return typeof window.toneToUnicode === 'function' ? window.toneToUnicode(str) : str;
    },

    init() {
        this.filterTones();
    },

    filterTones() {
        if (this.selectedCategory === 'tones') {
            this.filteredTones = this.allTones.filter(t => t.tone_number >= 1 && t.tone_number <= 4);
        } else if (this.selectedCategory === 'aspirated') {
            const aspInitials = ['b', 'p', 'd', 't', 'g', 'k', 'j', 'q', 'z', 'c', 'zh', 'ch'];
            this.filteredTones = this.allTones.filter(t => aspInitials.includes(t.initial));
        } else if (this.selectedCategory === 'retroflex') {
            const retInitials = ['zh', 'ch', 'sh', 'r', 'z', 'c', 's'];
            this.filteredTones = this.allTones.filter(t => retInitials.includes(t.initial));
        } else if (this.selectedCategory === 'nasal') {
            const nasalFinals = ['an', 'ang', 'en', 'eng', 'in', 'ing', 'ian', 'iang', 'uan', 'uang', 'uen', 'ueng', 'ong', 'iong'];
            this.filteredTones = this.allTones.filter(t => nasalFinals.includes(t.final));
        } else if (this.selectedCategory === 'labial') {
            const labInitials = ['b', 'p', 'm', 'f'];
            this.filteredTones = this.allTones.filter(t => labInitials.includes(t.initial));
        } else {
            this.filteredTones = [...this.allTones];
        }
        if (this.filteredTones.length < 4) {
            this.filteredTones = [...this.allTones];
        }
    },

    startQuiz() {
        this.isMistakePracticeMode = false;
        this.quizLength = this.configQuizLength || 10;
        this.score = 0;
        this.streak = 0;
        this.maxStreak = 0;
        this.correctCount = 0;
        this.questionInRound = 1;
        this.mistakes = [];
        this.filterTones();
        this.screen = 'quiz';
        this.nextQuestion(this.autoPlayAudio);
    },

    retryMistakesOnly() {
        if (this.mistakes.length === 0) return;
        this.isMistakePracticeMode = true;
        this.mistakePool = this.mistakes.map(m => m.target);
        this.quizLength = this.mistakePool.length;
        this.questionInRound = 1;
        this.score = 0;
        this.streak = 0;
        this.correctCount = 0;
        this.mistakes = [];
        this.screen = 'quiz';
        this.nextQuestion(this.autoPlayAudio);
    },

    quitQuizPrompt() {
        this.pauseAdvance();
        this.showQuitModal = true;
    },

    confirmQuit() {
        this.clearAdvanceTimers();
        this.showQuitModal = false;
        this.screen = 'setup';
    },

    cancelQuit() {
        this.showQuitModal = false;
        this.resumeAdvance();
    },

    nextQuestion(autoPlay = true) {
        this.clearAdvanceTimers();
        this.answered = false;
        this.selectedOpt = null;
        this.isCorrect = false;
        let pool = this.filteredTones;
        if (this.isMistakePracticeMode && this.mistakePool.length > 0) {
            this.targetTone = this.mistakePool[this.questionInRound - 1] || this.mistakePool[0];
        } else {
            const randIdx = Math.floor(Math.random() * pool.length);
            this.targetTone = pool[randIdx];
        }
        const distractors = this.getSmartDistractors(this.targetTone, pool);
        this.currentOptions = [this.targetTone, ...distractors].sort(() => 0.5 - Math.random());
        if (autoPlay) {
            const player = this.$refs.audioPlayer;
            if (player && this.targetTone && this.targetTone.audio_path) {
                const audioUrl = '/storage/audio/pinyin/' + this.targetTone.audio_path;
                player.src = audioUrl;
                player.load();
            }
            this.$nextTick(() => {
                setTimeout(() => {
                    this.playAudio();
                }, 80);
            });
        }
    },

    getSmartDistractors(target, pool) {
        const distractors = [];
        const addedIds = new Set([target.id]);
        const sameSyllableTones = this.allTones.filter(t => !addedIds.has(t.id) && t.pinyin_id === target.pinyin_id);
        this.shuffleArray(sameSyllableTones);
        for (let t of sameSyllableTones) {
            if (distractors.length >= (this.selectedCategory === 'tones' ? 3 : 2)) break;
            distractors.push(t);
            addedIds.add(t.id);
        }
        if (distractors.length < 3) {
            const SIMILAR_INITIALS = {
                'b': ['p', 'm'], 'p': ['b', 'f'], 'm': ['b', 'n'], 'f': ['p', 'h'],
                'd': ['t', 'n'], 't': ['d', 'l'], 'n': ['l', 'm'], 'l': ['n', 'r'],
                'g': ['k', 'h'], 'k': ['g', 'h'], 'h': ['k', 'f'],
                'j': ['q', 'x'], 'q': ['j', 'x'], 'x': ['j', 'q'],
                'zh': ['ch', 'sh', 'z'], 'ch': ['zh', 'sh', 'c'], 'sh': ['zh', 'ch', 's', 'r'], 'r': ['l', 'sh'],
                'z': ['c', 's', 'zh'], 'c': ['z', 's', 'ch'], 's': ['z', 'c', 'sh']
            };
            const targetInitial = target.initial || '';
            const simInitials = SIMILAR_INITIALS[targetInitial] || [];
            if (simInitials.length > 0) {
                const simInitialTones = pool.filter(t => !addedIds.has(t.id) && simInitials.includes(t.initial));
                if (simInitialTones.length > 0) {
                    this.shuffleArray(simInitialTones);
                    distractors.push(simInitialTones[0]);
                    addedIds.add(simInitialTones[0].id);
                }
            }
        }
        if (distractors.length < 3) {
            const remaining = pool.filter(t => !addedIds.has(t.id));
            this.shuffleArray(remaining);
            for (let t of remaining) {
                if (distractors.length >= 3) break;
                distractors.push(t);
                addedIds.add(t.id);
            }
        }
        return distractors;
    },

    shuffleArray(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
    },

    playAudio(rate = 1.0) {
        if (!this.targetTone || !this.targetTone.audio_path) return;
        const player = this.$refs.audioPlayer;
        if (!player) return;
        const audioUrl = '/storage/audio/pinyin/' + this.targetTone.audio_path;
        if (player.src !== window.location.origin + audioUrl) {
            try { player.pause(); player.currentTime = 0; } catch (e) {}
            player.src = audioUrl;
            player.load();
        } else {
            try { player.pause(); player.currentTime = 0; } catch (e) {}
        }
        player.playbackRate = rate;
        this.isPlaying = true;
        player.onended = () => { this.isPlaying = false; };
        player.onerror = () => { this.isPlaying = false; };
        const playPromise = player.play();
        if (playPromise !== undefined) {
            playPromise.then(() => {
                this.isPlaying = true;
            }).catch(e => {
                console.warn('Audio autoplay prevented or interrupted:', e);
                this.isPlaying = false;
                setTimeout(() => {
                    player.play().then(() => {
                        this.isPlaying = true;
                    }).catch(() => {
                        if (window.playWordAudio && this.targetTone) {
                            window.playWordAudio(this.targetTone.display || this.targetTone.full_pinyin);
                        }
                    });
                }, 200);
            });
        }
    },

    playSpecificToneAudio(tone) {
        if (!tone || !tone.audio_path) return;
        const player = this.$refs.audioPlayer;
        if (!player) return;
        try {
            player.pause();
            player.currentTime = 0;
        } catch (e) {}
        player.src = '/storage/audio/pinyin/' + tone.audio_path;
        player.playbackRate = 1.0;
        player.load();
        player.play().catch(e => {
            console.warn('Audio playback error:', e);
            if (window.playWordAudio) {
                window.playWordAudio(tone.display || tone.full_pinyin);
            }
        });
    },

    selectAnswer(opt) {
        if (this.answered) return;
        this._unlockAudioContext();
        this.selectedOpt = opt;
        this.answered = true;
        if (opt.id === this.targetTone.id) {
            this.isCorrect = true;
            this.score += 10;
            this.correctCount++;
            this.streak += 1;
            if (this.streak > this.maxStreak) {
                this.maxStreak = this.streak;
            }
            this.playSynthSound('correct');
            if (this.autoAdvance) {
                this.startAutoAdvance(2500);
            }
        } else {
            this.isCorrect = false;
            this.streak = 0;
            this.mistakes.push({
                target: this.targetTone,
                chosen: opt
            });
            this.playSynthSound('wrong');
        }
    },

    _unlockAudioContext() {
        if (this._audioUnlocked) return;
        const player = this.$refs.audioPlayer;
        if (!player) return;
        const silentSrc = player.src;
        if (!silentSrc || silentSrc === window.location.href) {
            if (this.targetTone && this.targetTone.audio_path) {
                player.src = '/storage/audio/pinyin/' + this.targetTone.audio_path;
                player.load();
            }
        }
        const unlockPromise = player.play();
        if (unlockPromise !== undefined) {
            unlockPromise.then(() => {
                player.pause();
                player.currentTime = 0;
                this._audioUnlocked = true;
            }).catch(() => {});
        }
    },

    startAutoAdvance(duration = 2500) {
        this.clearAdvanceTimers();
        if (!this.autoAdvance || !this.isCorrect) return;
        this.advanceProgress = 100;
        this.advanceTimerActive = true;
        this.isPausedAdvance = false;
        this.remainingAdvanceMs = duration;
        this.advanceTargetEndTime = Date.now() + duration;
        this.advanceInterval = setInterval(() => {
            if (this.isPausedAdvance) return;
            const remaining = this.advanceTargetEndTime - Date.now();
            this.remainingAdvanceMs = remaining;
            this.advanceProgress = Math.max(0, (remaining / duration) * 100);
            if (remaining <= 0) {
                this.clearAdvanceTimers();
                this.handleNextStep();
            }
        }, 40);
    },

    pauseAdvance() {
        if (this.advanceTimerActive && !this.isPausedAdvance) {
            this.isPausedAdvance = true;
            this.remainingAdvanceMs = Math.max(0, this.advanceTargetEndTime - Date.now());
        }
    },

    resumeAdvance() {
        if (this.advanceTimerActive && this.isPausedAdvance) {
            this.isPausedAdvance = false;
            this.advanceTargetEndTime = Date.now() + Math.max(this.remainingAdvanceMs, 1000);
        }
    },

    clearAdvanceTimers() {
        if (this.advanceInterval) {
            clearInterval(this.advanceInterval);
            this.advanceInterval = null;
        }
        this.advanceTimerActive = false;
        this.isPausedAdvance = false;
        this.advanceProgress = 100;
    },

    handleNextStep() {
        this.clearAdvanceTimers();
        if (this.questionInRound >= this.quizLength) {
            this.screen = 'summary';
        } else {
            this.questionInRound++;
            this.nextQuestion(this.autoPlayAudio);
        }
    },

    getOptionClass(opt) {
        if (!this.answered) {
            return 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-slate-200 hover:border-[#e07a5f] hover:text-[#e07a5f] hover:bg-[#fff2ee]/50';
        }
        if (opt.id === this.targetTone.id) {
            return 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-500/20';
        }
        if (this.selectedOpt && this.selectedOpt.id === opt.id) {
            return 'bg-rose-500 text-white border-rose-500 shadow-md shadow-rose-500/20';
        }
        return 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 opacity-40';
    },

    getBadgeClass(opt) {
        if (!this.answered) {
            return 'bg-[#e8e2d9] text-slate-600 dark:bg-slate-700 dark:text-slate-300';
        }
        if (opt.id === this.targetTone.id) {
            return 'bg-emerald-500 text-white';
        }
        if (this.selectedOpt && this.selectedOpt.id === opt.id) {
            return 'bg-rose-400 text-white';
        }
        return 'bg-slate-200 dark:bg-slate-700 text-slate-400';
    },

    getPitchDescription(toneNumber) {
        const PITCH_MAP = {
            1: '5-5 (Cao bằng phẳng)',
            2: '3-5 (Lên giọng cao)',
            3: '2-1-4 (Xuống rồi lên)',
            4: '5-1 (Rơi mạnh dứt khoát)',
            0: 'Khinh thanh (Nhẹ ngắn)'
        };
        return PITCH_MAP[toneNumber] || 'Chuẩn Quốc tế';
    },

    getEvaluationTitle() {
        const pct = Math.round((this.correctCount / this.quizLength) * 100);
        if (pct === 100) return 'Xuất Sắc! Tuyệt Đối 100% 🏆';
        if (pct >= 80) return 'Rất Tốt! Tai Nghe Chuẩn Xác 🎉';
        if (pct >= 50) return 'Khá Tốt! Tiếp Tục Phát Huy ✨';
        return 'Cần Luyện Tập Thêm 💪';
    },

    handleGlobalKey(e) {
        if (this.screen === 'setup') {
            if (e.key === 'Enter') {
                this.startQuiz();
            }
        } else if (this.screen === 'quiz') {
            if (e.key === ' ' || e.key === 'r' || e.key === 'R') {
                e.preventDefault();
                this.playAudio(1.0);
            } else if (!this.answered) {
                if (['1', 'a', 'A'].includes(e.key) && this.currentOptions[0]) this.selectAnswer(this.currentOptions[0]);
                if (['2', 'b', 'B'].includes(e.key) && this.currentOptions[1]) this.selectAnswer(this.currentOptions[1]);
                if (['3', 'c', 'C'].includes(e.key) && this.currentOptions[2]) this.selectAnswer(this.currentOptions[2]);
                if (['4', 'd', 'D'].includes(e.key) && this.currentOptions[3]) this.selectAnswer(this.currentOptions[3]);
            } else if (this.answered && e.key === 'Enter') {
                this.handleNextStep();
            }
        }
    },

    playSynthSound(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            if (type === 'correct') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(523.25, ctx.currentTime);
                osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.35);
            } else {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(180, ctx.currentTime);
                osc.frequency.setValueAtTime(130, ctx.currentTime + 0.15);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.35);
            }
        } catch (e) {
            // Ignore audio context errors
        }
    }
});

export default pinyinQuizApp;
