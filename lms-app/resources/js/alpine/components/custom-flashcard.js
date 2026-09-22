import { createGameMixin } from './vocab-game-mixin.js';

/**
 * Alpine.js component for Custom Flashcard Decks management and 3D study interface.
 * All comments are in English according to project guidelines.
 */
export default function customFlashcardApp(config = {}) {
    return {
        ...createGameMixin(
            function() { return (this.selectedDeck && this.selectedDeck.flashcards) ? this.selectedDeck.flashcards : []; },
            function(text) { this.speak(text); }
        ),

        // Main tabs: 'hsk' or 'my_decks'
        activeMainTab: config.initialTab || 'my_decks',
        isLoggedIn: config.isLoggedIn || false,

        // User decks list
        decks: config.initialDecks || [],
        isLoadingDecks: false,

        // Currently opened deck (null when viewing deck list)
        selectedDeck: null,
        isLoadingDeckDetails: false,
        currentAudio: null,

        // Dialog Modal State (Replaces native browser alert/confirm)
        showDialogModal: false,
        dialogMode: 'confirm', // 'confirm' or 'alert'
        dialogType: 'danger', // 'danger', 'warning', 'success', 'info', 'error'
        dialogTitle: '',
        dialogMessage: '',
        dialogConfirmText: 'Xác nhận',
        dialogCancelText: 'Hủy bỏ',
        dialogOnConfirmCallback: null,

        // Sub-view inside a deck: 'study' (interactive practice), 'quiz', 'match', or 'cards' (word list)
        deckSubTab: 'study',
        practiceMode: 'flashcard',

        // 3D Study State
        currentIndex: 0,
        flipped: false,
        autoplayAudio: false,
        isShuffled: false,
        shuffledCards: [],
        isLeaving: false,
        studyFilter: 'unlearned', // 'all', 'unlearned', 'learned'

        /**
         * Switch between sub-tabs within deck detail view.
         */
        switchDeckSubTab(tab) {
            this.deckSubTab = tab;
            if (tab === 'quiz') {
                this.practiceMode = 'quiz';
                this.initQuiz();
            } else if (tab === 'match') {
                this.practiceMode = 'match';
                this.initMatchGame();
            } else if (tab === 'study') {
                this.practiceMode = 'flashcard';
            }
        },

        /**
         * Switch practice modes (flashcard, quiz, match).
         */
        switchPracticeMode(mode) {
            this.practiceMode = mode;
            if (mode === 'quiz') {
                this.deckSubTab = 'quiz';
                this.initQuiz();
            } else if (mode === 'match') {
                this.deckSubTab = 'match';
                this.initMatchGame();
            } else if (mode === 'flashcard') {
                this.deckSubTab = 'study';
            }
        },

        /**
         * Get flashcards list of currently opened deck.
         */
        getCurrentDeckCards() {
            return (this.selectedDeck && this.selectedDeck.flashcards) ? this.selectedDeck.flashcards : [];
        },

        // Deck Modal State
        showDeckModal: false,
        deckModalMode: 'create', // 'create' or 'edit'
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
        cardModalMode: 'create', // 'create' or 'edit'
        cardForm: {
            id: null,
            word: '',
            pinyin: '',
            meaning: '',
            example: '',
            example_meaning: '',
        },
        isSubmittingCard: false,

        /**
         * Initialize the component, bind keyboard navigation and query params.
         */
        init() {
            // Check URL search parameters for initial tab or deck
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (tabParam === 'my_decks' || tabParam === 'my-decks') {
                this.activeMainTab = 'my_decks';
            }

            // Bind keyboard shortcuts for study mode
            window.addEventListener('keydown', (e) => this.handleKeyDown(e));

            // If user is logged in and decks list is empty, fetch fresh decks
            if (this.isLoggedIn && this.decks.length === 0) {
                this.fetchDecks();
            }
        },

        /**
         * Switch main tab and sync URL without refreshing.
         */
        switchMainTab(tab) {
            this.activeMainTab = tab;
            const url = new URL(window.location);
            if (tab === 'my_decks') {
                url.searchParams.set('tab', 'my_decks');
            } else {
                url.searchParams.delete('tab');
            }
            window.history.replaceState({}, '', url);
        },

        /**
         * Trigger user login modal when authentication is required.
         */
        requireLogin() {
            window.dispatchEvent(new CustomEvent('open-auth-modal', { detail: { tab: 'login' } }));
        },

        /**
         * Helper to retrieve CSRF token.
         */
        getCsrfToken() {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            return tokenMeta ? tokenMeta.getAttribute('content') : '';
        },

        // ==========================================
        // DECK MANAGEMENT (CRUD & NAVIGATION)
        // ==========================================

        /**
         * Fetch all decks belonging to current user from API.
         */
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

        /**
         * Open custom confirm dialog.
         */
        showConfirm({ title, message, confirmText = 'Xác nhận', cancelText = 'Hủy bỏ', type = 'danger', onConfirm = null }) {
            this.dialogMode = 'confirm';
            this.dialogType = type;
            this.dialogTitle = title;
            this.dialogMessage = message;
            this.dialogConfirmText = confirmText;
            this.dialogCancelText = cancelText;
            this.dialogOnConfirmCallback = onConfirm;
            this.showDialogModal = true;
        },

        /**
         * Open custom alert dialog.
         */
        showAlert({ title = 'Thông báo', message, type = 'error', confirmText = 'Đã hiểu' }) {
            this.dialogMode = 'alert';
            this.dialogType = type;
            this.dialogTitle = title;
            this.dialogMessage = message;
            this.dialogConfirmText = confirmText;
            this.dialogOnConfirmCallback = null;
            this.showDialogModal = true;
        },

        /**
         * Close dialog modal.
         */
        closeDialog() {
            this.showDialogModal = false;
            this.dialogOnConfirmCallback = null;
        },

        /**
         * Execute confirm callback and close dialog.
         */
        async confirmDialog() {
            const callback = this.dialogOnConfirmCallback;
            this.closeDialog();
            if (typeof callback === 'function') {
                await callback();
            }
        },

        /**
         * Open a deck and load its flashcards.
         */
        async openDeck(deck) {
            // Immediately populate selectedDeck with deck metadata so DOM initializes with valid icon and title
            this.selectedDeck = {
                ...deck,
                color: deck.color || '#e07a5f',
                icon: deck.icon || 'fa-book-open',
                flashcards: deck.flashcards || [],
            };
            this.isLoadingDeckDetails = true;
            this.currentIndex = 0;
            this.flipped = false;
            this.isShuffled = false;
            this.shuffledCards = [];

            this.deckSubTab = 'study';
            this.practiceMode = 'flashcard';
            clearInterval(this.matchTimerInterval);

            try {
                const response = await fetch(`/api/custom-flashcards/decks/${deck.id}`, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();
                if (data.success && data.deck) {
                    this.selectedDeck = {
                        ...this.selectedDeck,
                        ...data.deck,
                        color: data.deck.color || this.selectedDeck.color,
                        icon: data.deck.icon || this.selectedDeck.icon,
                    };
                } else {
                    this.showAlert({
                        title: 'Lỗi tải bộ thẻ',
                        message: data.message || 'Không thể tải chi tiết bộ thẻ lúc này.',
                        type: 'error'
                    });
                }
            } catch (error) {
                console.error('Error fetching deck details:', error);
                this.showAlert({
                    title: 'Lỗi kết nối',
                    message: 'Không thể kết nối đến máy chủ. Vui lòng kiểm tra lại đường truyền mạng.',
                    type: 'error'
                });
            } finally {
                this.isLoadingDeckDetails = false;
            }
        },

        /**
         * Exit deck view back to decks list.
         */
        closeDeck() {
            clearInterval(this.matchTimerInterval);
            this.selectedDeck = null;
            this.deckSubTab = 'study';
            this.practiceMode = 'flashcard';
            this.fetchDecks();
        },

        /**
         * Open create deck modal with reset form.
         */
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

        /**
         * Open edit deck modal prefilled with deck attributes.
         */
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

        /**
         * Submit create or update deck form.
         */
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
                    this.showAlert({
                        title: 'Lỗi lưu bộ thẻ',
                        message: result.message || 'Không thể lưu bộ thẻ lúc này.',
                        type: 'error'
                    });
                }
            } catch (error) {
                console.error('Save deck error:', error);
                this.showAlert({
                    title: 'Lỗi kết nối',
                    message: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.',
                    type: 'error'
                });
            } finally {
                this.isSubmittingDeck = false;
            }
        },

        /**
         * Delete a deck with confirmation modal.
         */
        deleteDeck(deckId) {
            this.showConfirm({
                title: 'Xóa bộ thẻ',
                message: 'Bạn có chắc chắn muốn xóa bộ thẻ này? Tất cả các từ vựng và tiến độ học tập bên trong sẽ bị xóa vĩnh viễn và không thể khôi phục.',
                confirmText: 'Xóa vĩnh viễn',
                cancelText: 'Giữ lại',
                type: 'danger',
                onConfirm: async () => {
                    await this.performDeleteDeck(deckId);
                }
            });
        },

        /**
         * Perform backend deck deletion.
         */
        async performDeleteDeck(deckId) {
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
                    this.showAlert({
                        title: 'Không thể xóa bộ thẻ',
                        message: result.message || 'Đã có lỗi xảy ra khi xóa bộ thẻ này.',
                        type: 'error'
                    });
                }
            } catch (error) {
                console.error('Delete deck error:', error);
                this.showAlert({
                    title: 'Lỗi kết nối',
                    message: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.',
                    type: 'error'
                });
            }
        },

        // ==========================================
        // CARD MANAGEMENT (CRUD & AUTO PINYIN)
        // ==========================================

        /**
         * Automatically generate accented Pinyin from Chinese characters using pinyin-pro.
         */
        onWordInput() {
            const input = this.cardForm.word ? this.cardForm.word.trim() : '';
            if (!input) return;

            // Use pinyinPro global if available
            if (typeof window.pinyinPro !== 'undefined' && typeof window.pinyinPro.pinyin === 'function') {
                try {
                    this.cardForm.pinyin = window.pinyinPro.pinyin(input);
                } catch (e) {
                    console.warn('Pinyin generation fallback:', e);
                }
            }
        },

        /**
         * Open create card modal.
         */
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

        /**
         * Open edit card modal.
         */
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

        /**
         * Submit create or edit card form.
         */
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
                    // Reload deck details to refresh cards and stats
                    await this.openDeck(this.selectedDeck);
                } else {
                    this.showAlert({
                        title: 'Lỗi lưu từ vựng',
                        message: result.message || 'Không thể lưu thông tin từ vựng này.',
                        type: 'error'
                    });
                }
            } catch (error) {
                console.error('Save card error:', error);
                this.showAlert({
                    title: 'Lỗi kết nối',
                    message: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.',
                    type: 'error'
                });
            } finally {
                this.isSubmittingCard = false;
            }
        },

        /**
         * Delete a card from deck with modern confirm modal.
         */
        deleteCard(cardId) {
            this.showConfirm({
                title: 'Xóa từ vựng',
                message: 'Bạn có chắc chắn muốn xóa từ vựng này khỏi bộ thẻ?',
                confirmText: 'Xóa từ này',
                cancelText: 'Hủy bỏ',
                type: 'danger',
                onConfirm: async () => {
                    await this.performDeleteCard(cardId);
                }
            });
        },

        /**
         * Perform backend card deletion.
         */
        async performDeleteCard(cardId) {
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
                    this.showAlert({
                        title: 'Không thể xóa từ vựng',
                        message: result.message || 'Đã có lỗi xảy ra khi xóa từ vựng này.',
                        type: 'error'
                    });
                }
            } catch (error) {
                console.error('Delete card error:', error);
                this.showAlert({
                    title: 'Lỗi kết nối',
                    message: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.',
                    type: 'error'
                });
            }
        },

        // ==========================================
        // 3D STUDY PLAYER LOGIC
        // ==========================================

        /**
         * Get cards available for study based on shuffle state and learning filter.
         */
        studyCards() {
            if (!this.selectedDeck || !this.selectedDeck.flashcards) return [];
            let all = this.selectedDeck.flashcards;

            if (this.studyFilter === 'unlearned') {
                all = all.filter(c => !c.is_remembered);
            } else if (this.studyFilter === 'learned') {
                all = all.filter(c => c.is_remembered);
            }

            if (this.isShuffled) {
                return this.shuffledCards.filter(c => {
                    if (this.studyFilter === 'unlearned') return !c.is_remembered;
                    if (this.studyFilter === 'learned') return c.is_remembered;
                    return true;
                });
            }

            return all;
        },

        /**
         * Get current active card in the study session.
         */
        currentCard() {
            const list = this.studyCards();
            if (this.currentIndex >= list.length) {
                this.currentIndex = 0;
            }
            return list[this.currentIndex] || null;
        },

        /**
         * Flip the 3D flashcard.
         */
        flipCard() {
            if (this.studyCards().length === 0) return;
            this.flipped = !this.flipped;
        },

        /**
         * Shuffle the cards order.
         */
        shuffle() {
            this.flipped = false;
            if (this.isShuffled) {
                this.isShuffled = false;
                this.shuffledCards = [];
                this.currentIndex = 0;
            } else {
                const cards = [...(this.selectedDeck.flashcards || [])];
                if (cards.length <= 1) return;
                for (let i = cards.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [cards[i], cards[j]] = [cards[j], cards[i]];
                }
                this.shuffledCards = cards;
                this.isShuffled = true;
                this.currentIndex = 0;

                if (this.autoplayAudio && this.currentCard()) {
                    setTimeout(() => this.speak(), 300);
                }
            }
        },

        /**
         * Move to next card.
         */
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

        /**
         * Move to previous card.
         */
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

        /**
         * Toggle remembered status of current or specified card.
         */
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

                    // Update deck progress stats
                    if (this.selectedDeck) {
                        this.selectedDeck.progress_percentage = res.data.progress_percentage;
                        this.selectedDeck.remembered_cards_count = res.data.remembered_cards;
                        this.selectedDeck.total_cards_count = res.data.total_cards;
                    }

                    // Also update in decks list
                    const deckInList = this.decks.find(d => d.id === this.selectedDeck.id);
                    if (deckInList) {
                        deckInList.progress_percentage = res.data.progress_percentage;
                    }

                    // Trigger EXP celebration toast if awarded
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

        /**
         * Reset progress of current deck to 0% with modern confirm modal.
         */
        resetDeckProgress() {
            if (!this.selectedDeck) return;
            this.showConfirm({
                title: 'Đặt lại tiến độ học',
                message: 'Bạn có muốn đặt lại toàn bộ thẻ trong bộ thẻ này về 0% để bắt đầu ôn tập lại từ đầu?',
                confirmText: 'Đặt lại 0%',
                cancelText: 'Hủy bỏ',
                type: 'warning',
                onConfirm: async () => {
                    await this.performResetDeckProgress();
                }
            });
        },

        async performResetDeckProgress() {
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
                } else {
                    this.showAlert({
                        title: 'Lỗi đặt lại tiến độ',
                        message: res.message || 'Không thể đặt lại tiến độ lúc này.',
                        type: 'error'
                    });
                }
            } catch (error) {
                console.error('Reset progress error:', error);
                this.showAlert({
                    title: 'Lỗi kết nối',
                    message: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.',
                    type: 'error'
                });
            }
        },

        /**
         * Text-to-speech pronunciation using Microsoft Edge-TTS (Azure Neural AI)
         * with graceful fallback to browser Web Speech API.
         */
        speak(customText = null) {
            const card = this.currentCard();
            const text = customText || (card ? card.word : '');
            if (!text) return;

            // Stop any currently playing audio instance
            if (this.currentAudio) {
                this.currentAudio.pause();
                this.currentAudio.currentTime = 0;
            }

            try {
                // Fetch Edge-TTS audio stream from backend
                const audioUrl = `/api/tts?text=${encodeURIComponent(text)}&voice=zh-CN-XiaoxiaoNeural`;
                this.currentAudio = new Audio(audioUrl);
                this.currentAudio.play().catch((err) => {
                    console.warn('Edge-TTS playback interrupted or failed, using browser fallback:', err);
                    this.fallbackSpeak(text);
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
         * Render Chinese text with ruby annotation (Pinyin above Hanzi).
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
         * Keyboard event handler for study actions.
         */
        handleKeyDown(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (this.showDeckModal || this.showCardModal) return;

            // Only trigger shortcuts if actively in custom deck study mode
            if (this.activeMainTab === 'my_decks' && this.selectedDeck && this.deckSubTab === 'study') {
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
    };
}
