/**
 * Unified Alpine.js component for XiaoMu LMS Flashcard system.
 * Handles both Standard HSK Flashcards and Custom User Decks,
 * including 3D card flipping, Quiz, and Match mini-games.
 * All comments are in English according to project guidelines.
 */
export default function flashcardApp(config = {}) {
    return {
        // ==========================================
        // MAIN NAVIGATION STATE
        // ==========================================
        activeMainTab: config.initialTab || 'tu-vung-hsk', // 'tu-vung-hsk' or 'bo-the-cua-ban'
        isLoggedIn: config.isLoggedIn || false,
        practiceMode: 'flashcard', // 'flashcard', 'quiz', or 'match'

        // ==========================================
        // HSK 3D STUDY STATE
        // ==========================================
        levels: [1, 2, 3, 4, 5, 6, 7, 8, 9],
        activeLevel: 1,
        activeTab: 'study', // 'study' or 'remembered'
        vocabularies: config.vocabularies || window.hskVocabularies || {},
        rememberedIds: (config.rememberedIds || window.hskRememberedIds || []).map(Number),
        currentIndex: 0,
        flipped: false,
        autoplayAudio: false,
        isShuffled: false,
        isShuffling: false,
        shuffledWordsList: [],
        rememberedPage: 1,
        rememberedPerPage: 18,
        isLeaving: false,
        isFilterDrawerOpen: false,

        // ==========================================
        // ADD TO DECK MODAL STATE
        // ==========================================
        showAddToDeckModal: false,
        addToDeckWord: null,
        userDecksForModal: [],
        isLoadingDecksForModal: false,
        isAddingToDeckId: null,
        addedDeckIds: [],
        showQuickCreateDeck: false,
        quickDeckTitle: '',
        quickDeckIcon: 'fa-layer-group',
        quickDeckColor: '#e07a5f',
        isQuickCreating: false,

        // ==========================================
        // CUSTOM DECKS STATE
        // ==========================================
        decks: config.initialDecks || [],
        isLoadingDecks: false,
        selectedDeck: null,
        isLoadingDeckDetails: false,
        deckSubTab: 'study', // 'study' or 'cards'
        customCurrentIndex: 0,
        customFlipped: false,
        customAutoplayAudio: false,
        customIsShuffled: false,
        customShuffledCards: [],
        customIsLeaving: false,
        studyFilter: 'unlearned', // 'all', 'unlearned', 'learned'

        // Deck Modal State
        showDeckModal: false,
        deckModalMode: 'create',
        deckForm: {
            id: null,
            title: '',
            description: '',
            color: '#e07a5f',
            icon: 'fa-layer-group',
        },
        isSubmittingDeck: false,
        deckColorOptions: [
            { label: 'Terracotta', value: '#e07a5f' },
            { label: 'Emerald', value: '#10b981' },
            { label: 'Sky Blue', value: '#0ea5e9' },
            { label: 'Amber', value: '#f59e0b' },
            { label: 'Purple', value: '#8b5cf6' },
            { label: 'Rose', value: '#f43f5e' },
        ],
        deckIconOptions: [
            { icon: 'fa-layer-group', label: 'Layer' },
            { icon: 'fa-book-open', label: 'Book' },
            { icon: 'fa-utensils', label: 'Food' },
            { icon: 'fa-plane', label: 'Travel' },
            { icon: 'fa-briefcase', label: 'Work' },
            { icon: 'fa-heart', label: 'Life' },
            { icon: 'fa-star', label: 'Star' },
            { icon: 'fa-comments', label: 'Chat' },
        ],

        // Card Modal State
        showCardModal: false,
        cardModalMode: 'create',
        cardForm: {
            id: null,
            word: '',
            pinyin: '',
            meaning: '',
            example: '',
            example_meaning: '',
        },
        isSubmittingCard: false,

        // ==========================================
        // SHARED MINI-GAMES STATE (QUIZ & MATCH)
        // ==========================================
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

        // ==========================================
        // INITIALIZATION & TAB SWITCHING
        // ==========================================
        init() {
            // Read tab query parameter from URL
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (['bo-the-cua-ban', 'bo-the', 'my_decks', 'my-decks'].includes(tabParam)) {
                this.activeMainTab = 'bo-the-cua-ban';
            } else {
                this.activeMainTab = 'tu-vung-hsk';
            }

            // Keyboard navigation listener
            window.addEventListener('keydown', (e) => this.handleKeyDown(e));

            // Auto-fetch user decks if authenticated
            if (this.isLoggedIn && this.decks.length === 0) {
                this.fetchDecks();
            }
        },

        switchMainTab(tab) {
            this.activeMainTab = tab;
            this.practiceMode = 'flashcard';
            const url = new URL(window.location);
            if (tab === 'bo-the-cua-ban' || tab === 'bo-the' || tab === 'my_decks') {
                url.searchParams.set('tab', 'bo-the-cua-ban');
                if (this.isLoggedIn && this.decks.length === 0) {
                    this.fetchDecks();
                }
            } else {
                url.searchParams.delete('tab');
            }
            window.history.replaceState({}, '', url);
        },

        switchPracticeMode(mode) {
            this.practiceMode = mode;
            if (mode === 'quiz') {
                this.initQuiz();
            } else if (mode === 'match') {
                this.initMatchGame();
            }
        },

        requireLogin() {
            window.dispatchEvent(new CustomEvent('open-auth-modal', { detail: { tab: 'login' } }));
        },

        getCsrfToken() {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            return tokenMeta ? tokenMeta.getAttribute('content') : '';
        },

        // Helper: retrieve vocabulary list based on active view for games
        getActiveVocabList() {
            if (this.activeMainTab === 'tu-vung-hsk' || this.activeMainTab === 'hsk') {
                return this.vocabularies[this.activeLevel] || [];
            }
            return (this.selectedDeck && this.selectedDeck.flashcards) ? this.selectedDeck.flashcards : [];
        },

        // ==========================================
        // HSK STUDY PLAYER METHODS
        // ==========================================
        currentWords() {
            const allWords = this.vocabularies[this.activeLevel] || [];
            const unremembered = allWords.filter(w => !this.rememberedIds.includes(Number(w.id)));
            if (this.isShuffled) {
                return this.shuffledWordsList.filter(w => !this.rememberedIds.includes(Number(w.id)));
            }
            return unremembered;
        },

        rememberedWords() {
            const allWords = this.vocabularies[this.activeLevel] || [];
            return allWords.filter(w => this.rememberedIds.includes(Number(w.id)));
        },

        rememberedTotalPages() {
            return Math.ceil(this.rememberedWords().length / this.rememberedPerPage) || 1;
        },

        paginatedRememberedWords() {
            const words = this.rememberedWords();
            const total = this.rememberedTotalPages();
            if (this.rememberedPage > total) {
                this.rememberedPage = total;
            }
            const start = (this.rememberedPage - 1) * this.rememberedPerPage;
            return words.slice(start, start + this.rememberedPerPage);
        },

        goToRememberedPage(p) {
            if (p >= 1 && p <= this.rememberedTotalPages()) {
                this.rememberedPage = p;
            }
        },

        currentWord() {
            return this.currentWords()[this.currentIndex] || {};
        },

        totalInScope() {
            return (this.vocabularies[this.activeLevel] || []).length;
        },

        rememberedInScope() {
            const allWords = this.vocabularies[this.activeLevel] || [];
            return allWords.filter(w => this.rememberedIds.includes(Number(w.id))).length;
        },

        getProgressPercentage() {
            const total = this.totalInScope();
            if (total === 0) return 0;
            return Math.round((this.rememberedInScope() / total) * 100);
        },

        flipCard() {
            if (this.activeMainTab === 'tu-vung-hsk' || this.activeMainTab === 'hsk') {
                if (this.currentWords().length === 0) return;
                this.flipped = !this.flipped;
            } else {
                if (this.studyCards().length === 0) return;
                this.flipped = !this.flipped;
            }
        },

        shuffle() {
            this.flipped = false;
            if (this.activeMainTab === 'tu-vung-hsk' || this.activeMainTab === 'hsk') {
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
            } else {
                if (this.isShuffled) {
                    this.isShuffled = false;
                    this.customShuffledCards = [];
                    this.currentIndex = 0;
                } else {
                    const cards = [...(this.selectedDeck?.flashcards || [])];
                    if (cards.length <= 1) return;
                    for (let i = cards.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [cards[i], cards[j]] = [cards[j], cards[i]];
                    }
                    this.customShuffledCards = cards;
                    this.isShuffled = true;
                    this.currentIndex = 0;

                    if (this.autoplayAudio && this.currentCard()) {
                        setTimeout(() => this.speak(), 300);
                    }
                }
            }
        },

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
                setTimeout(() => this.speak(), 350);
            }
        },

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
                            'X-CSRF-TOKEN': this.getCsrfToken(),
                        },
                        body: JSON.stringify({ vocabulary_id: numId }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                if (data.require_login) this.requireLogin();
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
                    'X-CSRF-TOKEN': this.getCsrfToken(),
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

        resetScopeProgress() {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }
            fetch('/flashcards/reset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
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

        // ==========================================
        // CUSTOM DECKS & CARDS METHODS
        // ==========================================
        async fetchDecks() {
            if (!this.isLoggedIn) return;
            this.isLoadingDecks = true;
            try {
                const response = await fetch('/api/custom-flashcards/decks', {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();
                if (data.success) {
                    this.decks = data.decks || [];
                }
            } catch (error) {
                console.error('Failed to fetch custom decks:', error);
            } finally {
                this.isLoadingDecks = false;
            }
        },

        async openDeck(deck) {
            this.isLoadingDeckDetails = true;
            this.currentIndex = 0;
            this.flipped = false;
            this.isShuffled = false;
            this.shuffledWordsList = [];

            try {
                const response = await fetch(`/api/custom-flashcards/decks/${deck.id}`, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();
                if (data.success) {
                    this.selectedDeck = data.deck;
                    if (this.practiceMode === 'quiz') {
                        this.initQuiz();
                    } else if (this.practiceMode === 'match') {
                        this.initMatchGame();
                    }
                } else {
                    alert(data.message || 'Error loading deck');
                }
            } catch (error) {
                console.error('Error fetching deck details:', error);
            } finally {
                this.isLoadingDeckDetails = false;
            }
        },

        closeDeck() {
            this.selectedDeck = null;
            this.fetchDecks();
        },

        studyCards() {
            if (!this.selectedDeck || !this.selectedDeck.flashcards) return [];
            let all = this.selectedDeck.flashcards;

            if (this.studyFilter === 'unlearned') {
                all = all.filter(c => !c.is_remembered);
            } else if (this.studyFilter === 'learned') {
                all = all.filter(c => c.is_remembered);
            }

            if (this.customIsShuffled) {
                return this.customShuffledCards.filter(c => {
                    if (this.studyFilter === 'unlearned') return !c.is_remembered;
                    if (this.studyFilter === 'learned') return c.is_remembered;
                    return true;
                });
            }

            return all;
        },

        currentCard() {
            const list = this.studyCards();
            if (this.currentIndex >= list.length) {
                this.currentIndex = 0;
            }
            return list[this.currentIndex] || null;
        },

        shuffleCustom() {
            this.flipped = false;
            if (this.customIsShuffled) {
                this.customIsShuffled = false;
                this.customShuffledCards = [];
                this.currentIndex = 0;
            } else {
                const cards = [...(this.selectedDeck.flashcards || [])];
                if (cards.length <= 1) return;
                for (let i = cards.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [cards[i], cards[j]] = [cards[j], cards[i]];
                }
                this.customShuffledCards = cards;
                this.customIsShuffled = true;
                this.currentIndex = 0;

                if (this.autoplayAudio && this.currentCard()) {
                    setTimeout(() => this.speak(), 300);
                }
            }
        },

        nextCard() {
            const list = this.studyCards();
            if (list.length === 0) return;
            this.flipped = false;
            setTimeout(() => {
                this.currentIndex = (this.currentIndex + 1) % list.length;
                if (this.autoplayAudio) {
                    setTimeout(() => this.speak(), 300);
                }
            }, 150);
        },

        prevCard() {
            const list = this.studyCards();
            if (list.length === 0) return;
            this.flipped = false;
            setTimeout(() => {
                this.currentIndex = (this.currentIndex - 1 + list.length) % list.length;
                if (this.autoplayAudio) {
                    setTimeout(() => this.speak(), 300);
                }
            }, 150);
        },

        async toggleRememberCard(card) {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }
            if (!card || this.isLeaving) return;

            this.isLeaving = true;

            try {
                const response = await fetch(`/api/custom-flashcards/cards/${card.id}/toggle-remember`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                });

                const res = await response.json();
                if (res.success && res.data) {
                    card.is_remembered = res.data.is_remembered;

                    if (this.selectedDeck) {
                        this.selectedDeck.progress_percentage = res.data.progress_percentage;
                        this.selectedDeck.remembered_cards_count = res.data.remembered_cards;
                        this.selectedDeck.total_cards_count = res.data.total_cards;
                    }

                    const deckInList = this.decks.find(d => d.id === this.selectedDeck.id);
                    if (deckInList) {
                        deckInList.progress_percentage = res.data.progress_percentage;
                    }

                    if (res.data.exp_awarded && res.data.exp_awarded.exp_gained > 0) {
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: {
                                message: `+${res.data.exp_awarded.exp_gained} EXP! Ghi nhớ từ vựng thành công 🎉`,
                                type: 'success'
                            }
                        }));
                    }
                }
            } catch (error) {
                console.error('Toggle remember error:', error);
            } finally {
                this.flipped = false;
                this.isLeaving = false;
            }
        },

        async resetDeckProgress() {
            if (!this.selectedDeck) return;
            if (!confirm('Bạn có muốn đặt lại tiến độ học của toàn bộ thẻ trong danh mục này về 0%?')) {
                return;
            }

            try {
                const response = await fetch(`/api/custom-flashcards/decks/${this.selectedDeck.id}/reset`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                });

                const res = await response.json();
                if (res.success) {
                    if (this.selectedDeck.flashcards) {
                        this.selectedDeck.flashcards.forEach(c => c.is_remembered = false);
                    }
                    this.selectedDeck.progress_percentage = 0;
                    this.selectedDeck.remembered_cards_count = 0;
                    this.currentIndex = 0;
                    this.flipped = false;
                }
            } catch (error) {
                console.error('Reset progress error:', error);
            }
        },

        // CRUD Modals for Decks
        openCreateDeckModal() {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }
            this.deckModalMode = 'create';
            this.deckForm = {
                id: null,
                title: '',
                description: '',
                color: '#e07a5f',
                icon: 'fa-layer-group',
            };
            this.showDeckModal = true;
        },

        openEditDeckModal(deck) {
            this.deckModalMode = 'edit';
            this.deckForm = {
                id: deck.id,
                title: deck.title,
                description: deck.description || '',
                color: deck.color || '#e07a5f',
                icon: deck.icon || 'fa-layer-group',
            };
            this.showDeckModal = true;
        },

        async submitDeckForm() {
            if (!this.deckForm.title.trim()) return;
            this.isSubmittingDeck = true;

            const isEdit = this.deckModalMode === 'edit';
            const url = isEdit
                ? `/api/custom-flashcards/decks/${this.deckForm.id}`
                : '/api/custom-flashcards/decks';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify({
                        title: this.deckForm.title,
                        description: this.deckForm.description,
                        color: this.deckForm.color,
                        icon: this.deckForm.icon,
                    }),
                });

                const result = await response.json();
                if (result.success) {
                    this.showDeckModal = false;
                    if (isEdit && this.selectedDeck && this.selectedDeck.id === this.deckForm.id) {
                        this.selectedDeck.title = this.deckForm.title;
                        this.selectedDeck.description = this.deckForm.description;
                        this.selectedDeck.color = this.deckForm.color;
                        this.selectedDeck.icon = this.deckForm.icon;
                    }
                    await this.fetchDecks();
                } else {
                    alert(result.message || 'Error saving deck');
                }
            } catch (error) {
                console.error('Save deck error:', error);
            } finally {
                this.isSubmittingDeck = false;
            }
        },

        async deleteDeck(deckId) {
            if (!confirm('Bạn có chắc chắn muốn xóa bộ thẻ này? Tất cả các từ vựng bên trong sẽ bị xóa.')) {
                return;
            }

            try {
                const response = await fetch(`/api/custom-flashcards/decks/${deckId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                });

                const result = await response.json();
                if (result.success) {
                    if (this.selectedDeck && this.selectedDeck.id === deckId) {
                        this.selectedDeck = null;
                    }
                    await this.fetchDecks();
                } else {
                    alert(result.message || 'Error deleting deck');
                }
            } catch (error) {
                console.error('Delete deck error:', error);
            }
        },

        // CRUD Modals for Cards
        onWordInput() {
            const input = this.cardForm.word ? this.cardForm.word.trim() : '';
            if (!input) return;

            if (typeof window.pinyinPro !== 'undefined' && typeof window.pinyinPro.pinyin === 'function') {
                try {
                    this.cardForm.pinyin = window.pinyinPro.pinyin(input);
                } catch (e) {
                    console.warn('Pinyin generation fallback:', e);
                }
            }
        },

        openCreateCardModal() {
            this.cardModalMode = 'create';
            this.cardForm = {
                id: null,
                word: '',
                pinyin: '',
                meaning: '',
                example: '',
                example_meaning: '',
            };
            this.showCardModal = true;
        },

        openEditCardModal(card) {
            this.cardModalMode = 'edit';
            this.cardForm = {
                id: card.id,
                word: card.word,
                pinyin: card.pinyin,
                meaning: card.meaning,
                example: card.example || '',
                example_meaning: card.example_meaning || '',
            };
            this.showCardModal = true;
        },

        async submitCardForm() {
            if (!this.selectedDeck) return;
            if (!this.cardForm.word.trim() || !this.cardForm.meaning.trim()) return;

            this.isSubmittingCard = true;
            const isEdit = this.cardModalMode === 'edit';
            const url = isEdit
                ? `/api/custom-flashcards/cards/${this.cardForm.id}`
                : `/api/custom-flashcards/decks/${this.selectedDeck.id}/cards`;
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify({
                        word: this.cardForm.word,
                        pinyin: this.cardForm.pinyin,
                        meaning: this.cardForm.meaning,
                        example: this.cardForm.example,
                        example_meaning: this.cardForm.example_meaning,
                    }),
                });

                const result = await response.json();
                if (result.success) {
                    this.showCardModal = false;
                    await this.openDeck(this.selectedDeck);
                } else {
                    alert(result.message || 'Error saving card');
                }
            } catch (error) {
                console.error('Save card error:', error);
            } finally {
                this.isSubmittingCard = false;
            }
        },

        async deleteCard(cardId) {
            if (!confirm('Bạn có chắc chắn muốn xóa từ vựng này khỏi bộ thẻ?')) {
                return;
            }

            try {
                const response = await fetch(`/api/custom-flashcards/cards/${cardId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                });

                const result = await response.json();
                if (result.success) {
                    await this.openDeck(this.selectedDeck);
                } else {
                    alert(result.message || 'Error deleting card');
                }
            } catch (error) {
                console.error('Delete card error:', error);
            }
        },

        // ==========================================
        // SHARED MINI-GAMES ENGINE (QUIZ & MATCH)
        // ==========================================
        initQuiz() {
            const list = this.getActiveVocabList();
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
                const others = list.filter(v => v.id !== item.id).sort(() => 0.5 - Math.random()).slice(0, 3);
                const options = [
                    { id: item.id, text: item.meaning, isCorrect: true },
                    ...others.map(o => ({ id: o.id, text: o.meaning, isCorrect: false })),
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
                }
            } else {
                this.quizStreak = 0;
                if (typeof window.playWrongSound === 'function') {
                    window.playWrongSound();
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

        initMatchGame() {
            const list = this.getActiveVocabList();
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
            if (item.isMatched || item.isWrong) return;
            this.matchSelectedLeft = item;
            if (this.matchSelectedRight) {
                this.checkMatch();
            }
        },

        selectMatchRight(item) {
            if (item.isMatched || item.isWrong) return;
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
                }
                this.speak(this.matchSelectedLeft.word);

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
                }
                const left = this.matchSelectedLeft;
                const right = this.matchSelectedRight;
                left.isWrong = true;
                right.isWrong = true;
                setTimeout(() => {
                    left.isWrong = false;
                    right.isWrong = false;
                    this.matchSelectedLeft = null;
                    this.matchSelectedRight = null;
                }, 500);
            }
        },

        // ==========================================
        // TEXT-TO-SPEECH & PINYIN HELPERS
        // ==========================================
        speak(customText = null) {
            let text = customText;
            if (!text) {
                if (this.activeMainTab === 'tu-vung-hsk' || this.activeMainTab === 'hsk') {
                    text = this.currentWord().word;
                } else {
                    const card = this.currentCard();
                    text = card ? card.word : '';
                }
            }
            if (!text) return;

            if (this.currentAudio) {
                this.currentAudio.pause();
                this.currentAudio.currentTime = 0;
            }

            try {
                const audioUrl = `/api/tts?text=${encodeURIComponent(text)}&voice=zh-CN-XiaoxiaoNeural`;
                this.currentAudio = new Audio(audioUrl);
                this.currentAudio.play().catch((err) => {
                    console.warn('Edge-TTS playback failed, using fallback:', err);
                    this.fallbackSpeak(text);
                });
            } catch (e) {
                this.fallbackSpeak(text);
            }
        },

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

        // ==========================================
        // KEYBOARD EVENT HANDLER
        // ==========================================
        handleKeyDown(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (this.showDeckModal || this.showCardModal || this.showAddToDeckModal) return;

            if ((this.activeMainTab === 'tu-vung-hsk' || this.activeMainTab === 'hsk') && this.activeTab === 'study' && this.practiceMode === 'flashcard') {
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
            } else if ((this.activeMainTab === 'bo-the-cua-ban' || this.activeMainTab === 'my_decks') && this.selectedDeck && this.deckSubTab === 'study' && this.practiceMode === 'flashcard') {
                if (e.code === 'Space') {
                    e.preventDefault();
                    this.flipCard();
                } else if (e.code === 'ArrowRight') {
                    e.preventDefault();
                    this.nextCard();
                } else if (e.code === 'ArrowLeft') {
                    e.preventDefault();
                    this.prevCard();
                }
            }
        },

        // ==========================================
        // ADD TO DECK METHODS
        // ==========================================
        requireLogin() {
            window.dispatchEvent(new CustomEvent('open-auth-modal', { detail: { tab: 'login' } }));
        },

        async openAddToDeckModal(word) {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }

            if (!word || !word.word) return;

            this.addToDeckWord = word;
            this.showAddToDeckModal = true;
            this.isLoadingDecksForModal = true;
            this.showQuickCreateDeck = false;
            this.quickDeckTitle = '';
            this.quickDeckIcon = 'fa-layer-group';
            this.quickDeckColor = '#e07a5f';
            this.addedDeckIds = [];

            try {
                const response = await fetch(`/api/custom-flashcards/decks/check-word?word=${encodeURIComponent(word.word.trim())}`, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();
                if (data.success && Array.isArray(data.decks)) {
                    this.userDecksForModal = data.decks;
                    this.addedDeckIds = data.decks.filter(d => d.has_word).map(d => d.id);
                } else {
                    this.userDecksForModal = [];
                }
            } catch (err) {
                console.error('Failed to load decks for word check:', err);
                this.userDecksForModal = [];
            } finally {
                this.isLoadingDecksForModal = false;
            }
        },

        async addWordToSpecificDeck(deckId) {
            if (!this.addToDeckWord || this.isAddingToDeckId) return;

            this.isAddingToDeckId = deckId;

            try {
                const payload = {
                    hsk_vocabulary_id: this.addToDeckWord.id || null,
                    word: this.addToDeckWord.word,
                    pinyin: this.addToDeckWord.pinyin || '',
                    meaning: this.addToDeckWord.meaning || '',
                    example: this.addToDeckWord.example || '',
                    example_meaning: this.addToDeckWord.example_meaning || '',
                };

                const response = await fetch(`/api/custom-flashcards/decks/${deckId}/add-hsk-word`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();
                if (data.success) {
                    if (!this.addedDeckIds.includes(deckId)) {
                        this.addedDeckIds.push(deckId);
                    }

                    // Increment card count in modal list
                    const targetDeck = this.userDecksForModal.find(d => d.id === deckId);
                    if (targetDeck && !data.already_exists) {
                        targetDeck.total_cards = (targetDeck.total_cards || 0) + 1;
                    }

                    // Notify custom-flashcard app component
                    window.dispatchEvent(new CustomEvent('deck-cards-updated', { detail: { deckId } }));

                    // Trigger toast notification
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            message: data.message || `Đã thêm từ [${this.addToDeckWord.word}] vào bộ thẻ! 🎉`,
                            type: 'success',
                        }
                    }));
                } else {
                    alert(data.message || 'Không thể thêm từ vựng vào bộ thẻ lúc này.');
                }
            } catch (err) {
                console.error('Failed to add word to deck:', err);
            } finally {
                this.isAddingToDeckId = null;
            }
        },

        async quickCreateDeckAndAddWord() {
            const title = this.quickDeckTitle.trim();
            if (!title || this.isQuickCreating) return;

            this.isQuickCreating = true;

            try {
                const createResponse = await fetch('/api/custom-flashcards/decks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify({
                        title: title,
                        color: this.quickDeckColor || '#e07a5f',
                        icon: this.quickDeckIcon || 'fa-book-open',
                    }),
                });

                const createData = await createResponse.json();
                if (createData.success && createData.deck) {
                    const newDeck = createData.deck;
                    newDeck.total_cards = 0;
                    this.userDecksForModal.unshift(newDeck);
                    this.quickDeckTitle = '';
                    this.quickDeckIcon = 'fa-layer-group';
                    this.quickDeckColor = '#e07a5f';
                    this.showQuickCreateDeck = false;

                    // Immediately add word to the newly created deck
                    await this.addWordToSpecificDeck(newDeck.id);
                } else {
                    alert(createData.message || 'Không thể tạo bộ thẻ mới lúc này.');
                }
            } catch (err) {
                console.error('Failed to quick create deck:', err);
            } finally {
                this.isQuickCreating = false;
            }
        },
    };
}
