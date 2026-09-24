import { createGameMixin } from './vocab-game-mixin.js';

/**
 * Alpine.js component for Standard HSK 3D Flashcards.
 * All comments are in English according to project guidelines.
 */
export default function hskFlashcardApp(config = {}) {
    return {
        ...createGameMixin(
            function() { return this.vocabularies[this.activeLevel] || []; },
            function(text) { this.speak(text); }
        ),

        isLoggedIn: config.isLoggedIn || false,
        vocabularies: config.vocabularies || window.hskVocabularies || {},
        activeTab: 'study', // 'study' or 'remembered'
        practiceMode: 'flashcard', // 'flashcard', 'quiz', or 'match'
        activeLevel: 1,
        currentIndex: 0,
        flipped: false,
        autoplayAudio: false,
        isShuffled: false,
        isShuffling: false,
        shuffledWordsList: [],
        levels: [1, 2, 3, 4, 5, 6, 7, 8, 9],
        rememberedIds: (config.rememberedIds || window.hskRememberedIds || []).map(Number),
        rememberedPage: 1,
        rememberedPerPage: 18,
        isLeaving: false,
        isFilterDrawerOpen: false,
        currentAudio: null,

        /**
         * Switch between study modes: flashcard, quiz, or match.
         */
        switchPracticeMode(mode) {
            this.practiceMode = mode;
            if (mode === 'quiz') {
                this.initQuiz();
            } else if (mode === 'match') {
                this.initMatchGame();
            }
        },

        /**
         * Trigger user login modal when authentication is required.
         */
        requireLogin() {
            window.dispatchEvent(new CustomEvent('open-auth-modal', { detail: { tab: 'login' } }));
        },

        /**
         * Get unlearned words in current HSK level.
         */
        currentWords() {
            const allWords = this.vocabularies[this.activeLevel] || [];
            const unremembered = allWords.filter(w => !this.rememberedIds.includes(Number(w.id)));
            if (this.isShuffled) {
                return this.shuffledWordsList.filter(w => !this.rememberedIds.includes(Number(w.id)));
            }
            return unremembered;
        },

        /**
         * Get learned words in current HSK level.
         */
        rememberedWords() {
            const allWords = this.vocabularies[this.activeLevel] || [];
            return allWords.filter(w => this.rememberedIds.includes(Number(w.id)));
        },

        /**
         * Calculate total pagination pages for remembered words.
         */
        rememberedTotalPages() {
            return Math.ceil(this.rememberedWords().length / this.rememberedPerPage) || 1;
        },

        /**
         * Get current page slice of remembered words.
         */
        paginatedRememberedWords() {
            const words = this.rememberedWords();
            const total = this.rememberedTotalPages();
            if (this.rememberedPage > total) {
                this.rememberedPage = total;
            }
            const start = (this.rememberedPage - 1) * this.rememberedPerPage;
            return words.slice(start, start + this.rememberedPerPage);
        },

        /**
         * Navigate to specific page in remembered words.
         */
        goToRememberedPage(p) {
            if (p >= 1 && p <= this.rememberedTotalPages()) {
                this.rememberedPage = p;
            }
        },

        /**
         * Get currently displayed word in flashcard player.
         */
        currentWord() {
            return this.currentWords()[this.currentIndex] || {};
        },

        /**
         * Total words in active HSK level.
         */
        totalInScope() {
            return (this.vocabularies[this.activeLevel] || []).length;
        },

        /**
         * Learned words in active HSK level.
         */
        rememberedInScope() {
            const allWords = this.vocabularies[this.activeLevel] || [];
            return allWords.filter(w => this.rememberedIds.includes(Number(w.id))).length;
        },

        /**
         * Learning progress percentage in active level.
         */
        getProgressPercentage() {
            const total = this.totalInScope();
            if (total === 0) return 0;
            return Math.round((this.rememberedInScope() / total) * 100);
        },

        /**
         * Flip the flashcard.
         */
        flipCard() {
            if (this.currentWords().length === 0) return;
            this.flipped = !this.flipped;
        },

        /**
         * Shuffle words order.
         */
        shuffle() {
            this.flipped = false;
            if (this.isShuffled) {
                this.isShuffled = false;
                this.shuffledWordsList = [];
                this.currentIndex = 0;
            } else {
                const words = (this.vocabularies[this.activeLevel] || []).filter(w => !this.rememberedIds.includes(Number(w.id)));
                if (words.length <= 1) return;
                this.isShuffling = true;
                setTimeout(() => {
                    for (let i = words.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [words[i], words[j]] = [words[j], words[i]];
                    }
                    this.shuffledWordsList = words;
                    this.isShuffled = true;
                    this.currentIndex = 0;
                    this.isShuffling = false;
                    if (this.autoplayAudio && this.currentWords().length > 0) {
                        setTimeout(() => this.speak(), 300);
                    }
                }, 200);
            }
        },

        /**
         * Move to next word.
         */
        nextWord() {
            if (this.currentWords().length === 0) return;
            this.flipped = false;
            setTimeout(() => {
                this.currentIndex = (this.currentIndex + 1) % this.currentWords().length;
                if (this.autoplayAudio) {
                    setTimeout(() => this.speak(), 300);
                }
            }, 150);
        },

        /**
         * Move to previous word.
         */
        prevWord() {
            if (this.currentWords().length === 0) return;
            this.flipped = false;
            setTimeout(() => {
                this.currentIndex = (this.currentIndex - 1 + this.currentWords().length) % this.currentWords().length;
                if (this.autoplayAudio) {
                    setTimeout(() => this.speak(), 300);
                }
            }, 150);
        },

        /**
         * Switch HSK level.
         */
        changeLevel(level) {
            this.activeLevel = level;
            this.currentIndex = 0;
            this.flipped = false;
            this.isShuffled = false;
            this.shuffledWordsList = [];
            this.rememberedPage = 1;
            if (this.practiceMode === 'quiz') {
                this.initQuiz();
            } else if (this.practiceMode === 'match') {
                this.initMatchGame();
            }
            if (this.autoplayAudio && this.currentWords().length > 0) {
                setTimeout(() => {
                    this.speak();
                }, 350);
            }
        },

        /**
         * Mark vocabulary as remembered in database.
         */
        markAsRemembered(word, id) {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }
            if (this.isLeaving || !id) return;
            const numId = Number(id);
            if (!this.rememberedIds.includes(numId)) {
                this.isLeaving = true;
                setTimeout(() => {
                    this.rememberedIds.push(numId);
                    fetch('/flashcards/remember', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ vocabulary_id: numId }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                if (data.require_login) {
                                    this.requireLogin();
                                }
                                console.error('API Error:', data.message);
                            }
                        })
                        .catch(error => console.error('Connection Error:', error));

                    if (this.currentIndex >= this.currentWords().length) {
                        this.currentIndex = 0;
                    }
                    this.flipped = false;
                    this.isLeaving = false;
                    setTimeout(() => {
                        if (this.autoplayAudio && this.currentWords().length > 0) {
                            this.speak();
                        }
                    }, 150);
                }, 200);
            }
        },

        /**
         * Remove word from remembered list.
         */
        unrememberWord(id) {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }
            const numId = Number(id);
            fetch('/flashcards/unremember', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ vocabulary_id: numId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.rememberedIds = this.rememberedIds.filter(itemId => itemId !== numId);
                        if (this.currentIndex >= this.currentWords().length) {
                            this.currentIndex = 0;
                        }
                    } else if (data.require_login) {
                        this.requireLogin();
                    }
                })
                .catch(error => console.error('Connection Error:', error));
        },

        /**
         * Reset progress of active HSK level.
         */
        resetScopeProgress() {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }
            fetch('/flashcards/reset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ level: this.activeLevel }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const levelIds = (this.vocabularies[this.activeLevel] || []).map(w => Number(w.id));
                        this.rememberedIds = this.rememberedIds.filter(id => !levelIds.includes(id));
                        this.currentIndex = 0;
                        this.flipped = false;
                    } else if (data.require_login) {
                        this.requireLogin();
                    }
                })
                .catch(error => console.error('Connection Error:', error));
        },

        /**
         * Chinese text-to-speech pronunciation using Edge-TTS with fallback.
         */
        speak(customText = null) {
            const text = customText || (this.currentWord().word || '');
            if (!text) return;

            // Stop any currently playing audio instance
            if (this.currentAudio) {
                this.currentAudio.pause();
                this.currentAudio.currentTime = 0;
            }

            try {
                const audioUrl = `/api/tts?text=${encodeURIComponent(text)}&voice=zh-CN-XiaoxiaoNeural`;
                this.currentAudio = new Audio(audioUrl);
                this.currentAudio.play().catch((err) => {
                    if (err.name !== 'AbortError') {
                        console.warn('Edge-TTS playback interrupted or failed, using browser fallback:', err);
                        this.fallbackSpeak(text);
                    }
                });
            } catch (e) {
                this.fallbackSpeak(text);
            }
        },

        /**
         * Fallback speech synthesis using browser native Web Speech API.
         */
        fallbackSpeak(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'zh-CN';
                const voices = window.speechSynthesis.getVoices();
                const zhVoice = voices.find(v => v.lang && (v.lang.includes('zh') || v.lang.includes('ZH')));
                if (zhVoice) {
                    utterance.voice = zhVoice;
                }
                utterance.rate = 0.85;
                window.speechSynthesis.speak(utterance);
            }
        },

        /**
         * Render ruby annotation for Chinese characters.
         */
        renderRuby(text) {
            if (!text || typeof text !== 'string') return '';
            if (typeof window.pinyinPro === 'undefined' || !window.pinyinPro.pinyin) {
                return `<span class="text-sm sm:text-base font-bold zh-text text-slate-800 dark:text-slate-100">${text}</span>`;
            }
            try {
                const tokens = window.pinyinPro.pinyin(text, { type: 'all' });
                let html = '<div class="inline-flex flex-wrap items-end gap-x-[1.5px] gap-y-1.5 align-bottom leading-normal">';
                for (const token of tokens) {
                    if (token.isZh) {
                        html += `<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-[1.5px]"><span class="text-sm sm:text-base font-bold zh-text text-slate-800 dark:text-slate-100">${token.origin}</span><rt class="text-[10px] sm:text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] mb-1 select-none tracking-normal">${token.pinyin}</rt></ruby>`;
                    } else if (token.origin === ' ') {
                        html += '<span class="mx-1"> </span>';
                    } else {
                        html += `<span class="text-sm sm:text-base font-bold text-slate-700 dark:text-slate-300 mt-auto self-end mb-[2px]">${token.origin}</span>`;
                    }
                }
                html += '</div>';
                return html;
            } catch (e) {
                return `<span class="text-sm sm:text-base font-bold zh-text text-slate-800 dark:text-slate-100">${text}</span>`;
            }
        },

        /**
         * Handle keyboard events for HSK study session.
         */
        handleKey(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (this.activeTab === 'study') {
                if (e.code === 'Space') {
                    e.preventDefault();
                    this.flipCard();
                } else if (e.code === 'ArrowRight') {
                    e.preventDefault();
                    this.nextWord();
                } else if (e.code === 'ArrowLeft') {
                    e.preventDefault();
                    this.prevWord();
                }
            }
        },

        init() {
            window.addEventListener('keydown', (e) => this.handleKey(e));
        },
    };
}
