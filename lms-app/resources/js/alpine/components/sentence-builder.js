/**
 * XiaoMu LMS - Sentence Practice Component
 * Supports 3 practice modes:
 * 1. 'scramble' - Interactive sentence builder
 * 2. 'cloze' - Fill in the blank
 * 3. 'dictation' - Listening & transcription
 */

export default function sentenceBuilder(config = {}) {
    return {
        topic: config.topic || {},
        level: config.level || 'HSK1',
        slug: config.slug || '',
        mode: config.mode || 'scramble', // 'scramble' | 'cloze' | 'dictation'
        isRandom: Boolean(config.isRandom),
        completeUrl: config.completeUrl || '',
        moreUrl: config.moreUrl || '',

        // Gamification & Completion state
        hasSubmittedResult: false,
        isSubmittingExp: false,
        expResponse: null,
        isLoadingNextRound: false,
        
        sentences: [],
        currentIndex: 0,
        currentSentence: null,
        
        // 🧩 Scramble state
        availableChips: [],
        selectedChips: [],
        
        // ✏️ Cloze question state
        clozeData: {
            prefix: '',
            suffix: '',
            targetWord: '',
            options: [],
            userChoice: null,
            isAnswered: false,
        },
        retryQueue: [],
        isReviewingWrong: false,
        
        // 🎧 Dictation state
        dictationInput: '',
        dictationResult: null, // { accuracy: 100, chars: [{ char, expected, correct }] }
        dictationChecked: false,
        
        // Audio playback & karaoke state
        isPlayingAudio: false,
        activeWordIdx: -1,
        activeTokenIdx: -1,
        karaokeTokens: [],
        audioElement: null,
        playbackProgress: 0,
        audioProgressInterval: null,
        
        // Exercise status
        status: 'idle', // 'idle' | 'correct' | 'wrong' | 'completed'
        
        // Display options
        showPinyin: config.showPinyin !== undefined ? Boolean(config.showPinyin) : true,
        playbackSpeed: 1.0,
        
        // Score & progress
        stats: {
            score: 0,
            completedCount: 0,
        },

        // 💡 Hints (limited to 3 hints per topic attempt)
        maxHints: 3,
        hintsLeft: 3,

        init() {
            this.sentences = this.topic.sentences || [];
            if (!this.sentences || this.sentences.length === 0) return;

            this.audioElement = new Audio();
            this.loadSentence(0);

            // Listen for keyboard shortcuts
            window.addEventListener('keydown', (e) => {
                if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;
                
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    this.nextSentence();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    this.prevSentence();
                } else if (e.key === ' ' && (this.status === 'correct' || this.mode === 'dictation')) {
                    e.preventDefault();
                    this.playSentenceAudio();
                }
            });
        },

        loadSentence(index) {
            if (index < 0 || index >= this.sentences.length) {
                if (this.mode === 'cloze' && this.retryQueue && this.retryQueue.length > 0) {
                    this.startReviewWrongSentences();
                    return;
                }
                this.handlePracticeCompleted();
                return;
            }

            this.stopAudio();
            this.currentIndex = index;
            this.currentSentence = this.sentences[index];
            this.status = 'idle';
            this.activeWordIdx = -1;
            this.activeTokenIdx = -1;
            this.playbackProgress = 0;
            this.buildKaraokeTokens();

            // Initialize state depending on mode
            if (this.mode === 'scramble') {
                this.initScrambleChips();
            } else if (this.mode === 'cloze') {
                this.initClozeQuestion();
            } else if (this.mode === 'dictation') {
                this.initDictation();
            }
        },

        /* ═══════════════════════════════════════════════════════════
         * 🧩 1. SCRAMBLE MODE LOGIC
         * ═══════════════════════════════════════════════════════════ */
        initScrambleChips() {
            this.selectedChips = [];
            const punctuationRegex = /[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)]/;

            let validTokens = [];
            if (this.currentSentence.tokens && this.currentSentence.tokens.length > 0) {
                validTokens = this.currentSentence.tokens
                    .filter(t => !punctuationRegex.test(t.trim()))
                    .map((token, idx) => ({
                        id: `t_${idx}_${Date.now()}_${Math.random()}`,
                        hanzi: token,
                    }));
            } else {
                const words = this.currentSentence.words || [];
                validTokens = words
                    .filter(w => !punctuationRegex.test(w.hanzi.trim()))
                    .map((w, idx) => ({
                        id: `w_${idx}_${Date.now()}_${Math.random()}`,
                        hanzi: w.hanzi,
                        start: w.start,
                        end: w.end,
                    }));
            }

            this.availableChips = this.shuffleArray([...validTokens]);
        },

        selectChip(chip) {
            if (this.status === 'correct') return;
            const idx = this.availableChips.findIndex(c => c.id === chip.id);
            if (idx !== -1) {
                const [item] = this.availableChips.splice(idx, 1);
                this.selectedChips.push(item);
                this.playTone('click');
                if (this.status === 'wrong') this.status = 'idle';
            }
        },

        unselectChip(chip) {
            if (this.status === 'correct') return;
            const idx = this.selectedChips.findIndex(c => c.id === chip.id);
            if (idx !== -1) {
                const [item] = this.selectedChips.splice(idx, 1);
                this.availableChips.push(item);
                this.playTone('unclick');
                if (this.status === 'wrong') this.status = 'idle';
            }
        },

        resetChips() {
            if (this.status === 'correct') return;
            this.availableChips.push(...this.selectedChips);
            this.selectedChips = [];
            this.status = 'idle';
            this.playTone('unclick');
        },

        checkAnswer() {
            if (this.selectedChips.length === 0) return;

            const userText = this.selectedChips.map(c => c.hanzi).join('');
            const targetText = (this.currentSentence.hanzi || '').replace(/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)\s]/g, '');

            if (userText === targetText) {
                this.status = 'correct';
                this.stats.score += 10;
                this.stats.completedCount++;
                this.playTone('correct');
                setTimeout(() => this.playSentenceAudio(), 250);
            } else {
                this.status = 'wrong';
                this.playTone('wrong');
            }
        },

        giveHint() {
            if (this.status === 'correct') return;
            if (this.hintsLeft <= 0) return;

            const targetText = (this.currentSentence.hanzi || '').replace(/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)\s]/g, '');
            const currentSelected = this.selectedChips.map(c => c.hanzi).join('');

            // If user previously selected wrong tokens, revert the last wrong chip back to pool
            if (!targetText.startsWith(currentSelected)) {
                if (this.selectedChips.length > 0) {
                    const lastChip = this.selectedChips.pop();
                    this.availableChips.push(lastChip);
                    this.status = 'idle';
                    return;
                }
            }

            const remainingText = targetText.substring(currentSelected.length);

            if (remainingText) {
                const foundIdx = this.availableChips.findIndex(c => remainingText.startsWith(c.hanzi));
                if (foundIdx !== -1) {
                    this.selectChip(this.availableChips[foundIdx]);
                    this.hintsLeft--;
                }
            }
        },

        /* ═══════════════════════════════════════════════════════════
         * ✏️ 2. CLOZE TEST LOGIC
         * ═══════════════════════════════════════════════════════════ */
        initClozeQuestion() {
            const rawSentence = this.currentSentence.hanzi || '';
            
            // Common HSK confusable / grammar word sets
            const confusableSets = [
                ['还是', '或者', '而且', '但是'],
                ['刚', '刚才', '已经', '经常'],
                ['常常', '往往', '总共', '一直'],
                ['会', '能', '可以', '想'],
                ['以为', '认为', '觉得', '希望'],
                ['张', '条', '件', '本', '只', '个'],
                ['在', '从', '离', '往'],
                ['因为', '所以', '虽然', '如果'],
                ['餐厅', '厨房', '超市', '医院'],
                ['做饭', '吃饭', '买菜', '洗碗'],
            ];

            let targetWord = '';
            let distractors = [];

            // Priority 1: Check if sentence contains any grammar confusable word
            for (const set of confusableSets) {
                for (const word of set) {
                    if (rawSentence.includes(word)) {
                        targetWord = word;
                        distractors = set.filter(w => w !== word).slice(0, 3);
                        break;
                    }
                }
                if (targetWord) break;
            }

            // Priority 2: Extract a meaningful word from sentence tokens (prefer compound words)
            if (!targetWord) {
                const candidates = (this.currentSentence.tokens && this.currentSentence.tokens.length > 0)
                    ? this.currentSentence.tokens.filter(t => !/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)]/.test(t.trim()) && !['的', '了', '吗', '呢', '吧'].includes(t.trim()))
                    : (this.currentSentence.words || []).map(w => w.hanzi.trim()).filter(w => !/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)]/.test(w) && !['的', '了', '吗', '呢', '吧'].includes(w));
                
                const compoundWords = candidates.filter(w => w.length >= 2);
                targetWord = (compoundWords.length > 0 ? compoundWords[Math.floor(Math.random() * compoundWords.length)] : candidates[Math.floor(Math.random() * candidates.length)]) || '什么';

                // Get 3 other words of same length from other sentences in topic
                const allWordsInTopic = [];
                this.sentences.forEach(s => {
                    const pool = (s.tokens && s.tokens.length > 0) ? s.tokens : (s.words || []).map(w => w.hanzi);
                    pool.forEach(w => {
                        const clean = (typeof w === 'string' ? w : w.hanzi || '').trim();
                        if (clean.length === targetWord.length && clean !== targetWord && !allWordsInTopic.includes(clean)) {
                            allWordsInTopic.push(clean);
                        }
                    });
                });

                distractors = this.shuffleArray(allWordsInTopic).slice(0, 3);
                while (distractors.length < 3) {
                    distractors.push(['自己', '朋友', '今天', '地方', '学习'][distractors.length]);
                }
            }

            // Split sentence into prefix and suffix around target word
            const splitIdx = rawSentence.indexOf(targetWord);
            const prefix = splitIdx !== -1 ? rawSentence.substring(0, splitIdx) : '';
            const suffix = splitIdx !== -1 ? rawSentence.substring(splitIdx + targetWord.length) : '';

            // Generate 4 options A, B, C, D
            const options = this.shuffleArray([
                { text: targetWord, correct: true },
                ...distractors.map(d => ({ text: d, correct: false }))
            ]).map((opt, idx) => ({
                id: idx,
                label: ['A', 'B', 'C', 'D'][idx],
                text: opt.text,
                correct: opt.correct,
            }));

            this.clozeData = {
                prefix: prefix,
                suffix: suffix,
                targetWord: targetWord,
                options: options,
                userChoice: null,
                isAnswered: false,
            };
        },

        selectClozeOption(option) {
            if (this.clozeData.isAnswered) return;

            this.clozeData.userChoice = option.text;
            this.clozeData.isAnswered = true;

            if (option.correct) {
                this.status = 'correct';
                this.stats.score += 10;
                this.stats.completedCount++;
                this.playTone('correct');
                setTimeout(() => this.playSentenceAudio(), 300);
            } else {
                this.status = 'wrong';
                this.playTone('wrong');

                const alreadyQueued = this.retryQueue.some(s => s.id === this.currentSentence.id);
                if (!alreadyQueued) {
                    this.retryQueue.push(this.currentSentence);
                }

                // Auto transition to next sentence after showing wrong indicator
                setTimeout(() => {
                    this.nextSentence();
                }, 700);
            }
        },

        /* ═══════════════════════════════════════════════════════════
         * 🎧 3. DICTATION MODE LOGIC
         * ═══════════════════════════════════════════════════════════ */
        initDictation() {
            this.dictationInput = '';
            this.dictationResult = null;
            this.dictationChecked = false;
            // Auto play audio when entering sentence
            setTimeout(() => this.playSentenceAudio(), 350);
        },

        checkDictation() {
            if (!this.dictationInput.trim()) return;

            const targetHanzi = (this.currentSentence.hanzi || '').replace(/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)\s]/g, '');
            const inputClean = this.dictationInput.replace(/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)\s]/g, '');

            const chars = [];
            let correctCount = 0;
            const maxLen = Math.max(targetHanzi.length, inputClean.length);

            for (let i = 0; i < maxLen; i++) {
                const targetChar = targetHanzi[i] || '';
                const inputChar = inputClean[i] || '';
                const isMatch = targetChar === inputChar;
                if (isMatch) correctCount++;

                chars.push({
                    char: inputChar || '␣',
                    expected: targetChar,
                    correct: isMatch,
                });
            }

            const accuracy = Math.round((correctCount / (targetHanzi.length || 1)) * 100);
            this.dictationResult = {
                accuracy: accuracy,
                chars: chars,
                targetText: targetHanzi,
            };
            this.dictationChecked = true;

            if (accuracy === 100) {
                this.status = 'correct';
                this.stats.score += 10;
                this.stats.completedCount++;
                this.playTone('correct');
            } else {
                this.status = 'wrong';
                this.playTone('wrong');
            }
        },

        giveDictationHint() {
            if (this.hintsLeft <= 0) return;
            const targetHanzi = (this.currentSentence.hanzi || '').replace(/[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)\s]/g, '');
            const currentLen = this.dictationInput.length;
            if (currentLen < targetHanzi.length) {
                this.dictationInput += targetHanzi[currentLen];
                this.hintsLeft--;
                this.playTone('click');
            }
        },

        buildKaraokeTokens() {
            if (!this.currentSentence) {
                this.karaokeTokens = [];
                return;
            }

            const words = this.currentSentence.words || [];
            const tokens = this.currentSentence.tokens || [];

            if (!tokens || tokens.length === 0) {
                this.karaokeTokens = words.map((w, idx) => ({
                    hanzi: w.hanzi,
                    start: w.start,
                    end: w.end,
                    isPunctuation: /[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)]/.test((w.hanzi || '').trim()),
                }));
                return;
            }

            const result = [];
            let wIdx = 0;
            const isPunct = (str) => /[，。！？、；：“”‘’（）《》…,\.!\?;:"'\(\)]/.test((str || '').trim());

            for (let tIdx = 0; tIdx < tokens.length; tIdx++) {
                const tokenText = tokens[tIdx];
                const tokenLen = tokenText.length;

                // Append preceding punctuation marks from words if any
                while (wIdx < words.length && isPunct(words[wIdx].hanzi) && !isPunct(tokenText)) {
                    result.push({
                        hanzi: words[wIdx].hanzi,
                        start: words[wIdx].start,
                        end: words[wIdx].end,
                        isPunctuation: true,
                    });
                    wIdx++;
                }

                if (wIdx < words.length) {
                    const startMs = words[wIdx].start;
                    let endMs = words[wIdx].end;

                    // Advance word index according to token length
                    for (let k = 0; k < tokenLen && wIdx < words.length; k++) {
                        endMs = words[wIdx].end;
                        wIdx++;
                    }

                    result.push({
                        hanzi: tokenText,
                        start: startMs,
                        end: endMs,
                        isPunctuation: isPunct(tokenText),
                    });
                } else {
                    result.push({
                        hanzi: tokenText,
                        start: 0,
                        end: 0,
                        isPunctuation: isPunct(tokenText),
                    });
                }
            }

            // Append trailing punctuation marks if any
            while (wIdx < words.length) {
                result.push({
                    hanzi: words[wIdx].hanzi,
                    start: words[wIdx].start,
                    end: words[wIdx].end,
                    isPunctuation: true,
                });
                wIdx++;
            }

            this.karaokeTokens = result;
        },

        /* ═══════════════════════════════════════════════════════════
         * 🔊 AUDIO PLAYBACK & KARAOKE HIGHLIGHT
         * ═══════════════════════════════════════════════════════════ */
        playSentenceAudio() {
            if (!this.currentSentence) return;
            this.stopAudio();
            this.isPlayingAudio = true;
            this.activeWordIdx = -1;
            this.activeTokenIdx = -1;

            const words = this.currentSentence.words || [];
            const duration = this.currentSentence.duration || 3000;
            const audioUrl = this.currentSentence.audioUrl;
            const textToSpeak = this.currentSentence.hanzi || '';
            let fullPath = null;
            if (audioUrl) {
                fullPath = (audioUrl.startsWith('http') || audioUrl.startsWith('/storage/'))
                    ? audioUrl
                    : `/storage/${audioUrl.replace(/^\//, '')}`;
            }

            if (fullPath && this.audioElement) {
                this.audioElement.src = fullPath;
                this.audioElement.playbackRate = this.playbackSpeed;

                this.audioElement.ontimeupdate = () => {
                    const currentMs = this.audioElement.currentTime * 1000;
                    this.playbackProgress = Math.min((currentMs / (duration || 1)) * 100, 100);

                    const matchIdx = words.findIndex(w => currentMs >= w.start && currentMs <= w.end);
                    if (matchIdx !== -1) {
                        this.activeWordIdx = matchIdx;
                    }

                    const matchTokenIdx = this.karaokeTokens.findIndex(t => !t.isPunctuation && currentMs >= t.start && currentMs <= t.end);
                    if (matchTokenIdx !== -1) {
                        this.activeTokenIdx = matchTokenIdx;
                    }
                };

                this.audioElement.onended = () => {
                    this.isPlayingAudio = false;
                    this.activeWordIdx = -1;
                    this.activeTokenIdx = -1;
                    this.playbackProgress = 100;
                };

                this.audioElement.onerror = () => {
                    this.playWithTTS(textToSpeak, words, duration);
                };

                this.audioElement.play().catch(() => {
                    this.playWithTTS(textToSpeak, words, duration);
                });
            } else {
                this.playWithTTS(textToSpeak, words, duration);
            }
        },

        playWithTTS(text, words, duration) {
            if (!('speechSynthesis' in window)) {
                this.isPlayingAudio = false;
                return;
            }

            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'zh-CN';
            utterance.rate = this.playbackSpeed * 0.9;

            const startTime = Date.now();
            
            if (this.audioProgressInterval) clearInterval(this.audioProgressInterval);
            this.audioProgressInterval = setInterval(() => {
                const elapsedMs = (Date.now() - startTime) * (this.playbackSpeed * 0.9);
                this.playbackProgress = Math.min((elapsedMs / (duration || 1)) * 100, 100);

                const matchIdx = words.findIndex(w => elapsedMs >= w.start && elapsedMs <= w.end);
                if (matchIdx !== -1) {
                    this.activeWordIdx = matchIdx;
                }
            }, 50);

            utterance.onend = () => {
                clearInterval(this.audioProgressInterval);
                this.isPlayingAudio = false;
                this.activeWordIdx = -1;
                this.playbackProgress = 100;
            };

            utterance.onerror = () => {
                clearInterval(this.audioProgressInterval);
                this.isPlayingAudio = false;
                this.activeWordIdx = -1;
            };

            window.speechSynthesis.speak(utterance);
        },

        stopAudio() {
            this.isPlayingAudio = false;
            this.activeWordIdx = -1;
            this.activeTokenIdx = -1;
            this.playbackProgress = 0;
            if (this.audioElement) {
                this.audioElement.pause();
                this.audioElement.currentTime = 0;
            }
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }
            if (this.audioProgressInterval) {
                clearInterval(this.audioProgressInterval);
            }
        },

        nextSentence() {
            if (this.currentIndex + 1 < this.sentences.length) {
                this.loadSentence(this.currentIndex + 1);
            } else {
                if (this.mode === 'cloze' && this.retryQueue && this.retryQueue.length > 0) {
                    this.startReviewWrongSentences();
                } else {
                    this.handlePracticeCompleted();
                }
            }
        },

        startReviewWrongSentences() {
            this.isReviewingWrong = true;
            this.sentences = [...this.retryQueue];
            this.retryQueue = [];
            this.currentIndex = 0;
            this.status = 'idle';
            this.loadSentence(0);
        },

        prevSentence() {
            if (this.currentIndex > 0) {
                this.loadSentence(this.currentIndex - 1);
            }
        },

        restartPractice() {
            this.hasSubmittedResult = false;
            this.expResponse = null;
            this.retryQueue = [];
            this.isReviewingWrong = false;
            this.sentences = this.topic.sentences || [];
            this.stats = { score: 0, completedCount: 0 };
            this.hintsLeft = this.maxHints;
            this.loadSentence(0);
        },

        async handlePracticeCompleted() {
            this.status = 'completed';
            this.stopAudio();

            if (this.hasSubmittedResult) return;
            this.hasSubmittedResult = true;
            this.isSubmittingExp = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(this.completeUrl || '/luyen-ghep-cau/complete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                    },
                    body: JSON.stringify({
                        topic_id: this.topic?.id,
                        level: this.level,
                        mode: this.mode,
                        total_sentences: this.sentences.length,
                        correct_count: this.stats.completedCount,
                        hints_used: this.maxHints - this.hintsLeft,
                        score: this.stats.score,
                    }),
                });

                const data = await res.json();
                this.expResponse = data;

                if (data.success && data.user) {
                    window.dispatchEvent(new CustomEvent('exp-updated', { detail: data.user }));
                }
            } catch (err) {
                console.error('Failed to submit practice result:', err);
            } finally {
                this.isSubmittingExp = false;
            }
        },

        async loadNextRandomRound() {
            this.isLoadingNextRound = true;

            try {
                const url = `${this.moreUrl || '/luyen-tap-ngau-nhien/more'}?level=${encodeURIComponent(this.level)}&limit=15`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();

                if (data.success && Array.isArray(data.sentences) && data.sentences.length > 0) {
                    this.sentences = data.sentences;
                    this.currentIndex = 0;
                    this.hasSubmittedResult = false;
                    this.expResponse = null;
                    this.retryQueue = [];
                    this.isReviewingWrong = false;
                    this.hintsLeft = this.maxHints;
                    this.stats = { score: 0, completedCount: 0 };
                    this.status = 'idle';
                    this.loadSentence(0);
                } else {
                    window.location.reload();
                }
            } catch (err) {
                console.error('Failed to load next random round:', err);
                window.location.reload();
            } finally {
                this.isLoadingNextRound = false;
            }
        },

        shuffleArray(arr) {
            const copy = [...arr];
            for (let i = copy.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [copy[i], copy[j]] = [copy[j], copy[i]];
            }
            return copy;
        },

        playTone(type) {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();

                if (type === 'click') {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(600, ctx.currentTime);
                    gain.gain.setValueAtTime(0.12, ctx.currentTime);
                    gain.gain.linearRampToValueAtTime(0.01, ctx.currentTime + 0.04);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.04);
                } else if (type === 'unclick') {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(320, ctx.currentTime);
                    gain.gain.setValueAtTime(0.1, ctx.currentTime);
                    gain.gain.linearRampToValueAtTime(0.01, ctx.currentTime + 0.04);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.04);
                } else if (type === 'correct') {
                    const now = ctx.currentTime;
                    [523.25, 659.25, 783.99].forEach((freq, i) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.value = freq;
                        gain.gain.setValueAtTime(0.18, now + i * 0.07);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.07 + 0.2);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start(now + i * 0.07);
                        osc.stop(now + i * 0.07 + 0.2);
                    });
                } else if (type === 'wrong') {
                    const now = ctx.currentTime;
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(160, now);
                    gain.gain.setValueAtTime(0.15, now);
                    gain.gain.linearRampToValueAtTime(0.01, now + 0.18);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.18);
                }
            } catch (e) {}
        }
    };
}
