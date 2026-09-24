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

        // Main tabs: 'tu-vung-hsk' or 'bo-the-cua-ban'
        activeMainTab: config.initialTab || 'bo-the-cua-ban',
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

        // Bulk Vocabulary Import State
        showImportModal: false,
        importTab: 'paste', // 'paste' or 'file'
        rawImportText: '',
        importDelimiter: 'auto', // 'auto', 'tab', 'dash', 'comma'
        parsedImportCards: [],
        isImportPreviewing: false,
        isSubmittingImport: false,

        /**
         * Initialize the component, bind keyboard navigation and query params.
         */
        init() {
            // Check URL search parameters for initial tab or deck
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (['bo-the-cua-ban', 'bo-the', 'my_decks', 'my-decks'].includes(tabParam)) {
                this.activeMainTab = 'bo-the-cua-ban';
            }

            // Bind keyboard shortcuts for study mode
            window.addEventListener('keydown', (e) => this.handleKeyDown(e));

            // Listen for breadcrumb click to return to deck list
            window.addEventListener('close-selected-deck', () => {
                if (this.selectedDeck) {
                    this.closeDeck();
                }
            });

            // If user is logged in and decks list is empty, fetch fresh decks
            if (this.isLoggedIn && this.decks.length === 0) {
                this.fetchDecks();
            }
        },

        /**
         * Dispatch event to update breadcrumb title.
         */
        notifyDeckChanged(title) {
            window.dispatchEvent(new CustomEvent('deck-changed', { detail: { title } }));
        },

        /**
         * Switch main tab and sync URL without refreshing.
         */
        switchMainTab(tab) {
            this.activeMainTab = tab;
            const url = new URL(window.location);
            if (tab === 'bo-the-cua-ban' || tab === 'bo-the' || tab === 'my_decks') {
                url.searchParams.set('tab', 'bo-the-cua-ban');
                if (this.selectedDeck) {
                    this.notifyDeckChanged(this.selectedDeck.title);
                }
            } else {
                url.searchParams.delete('tab');
                this.notifyDeckChanged(null);
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
            this.notifyDeckChanged(this.selectedDeck.title);
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
                    this.notifyDeckChanged(this.selectedDeck.title);
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
            this.notifyDeckChanged(null);
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
                        this.notifyDeckChanged(this.selectedDeck.title);
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
                        this.notifyDeckChanged(null);
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
                    // Suppress abort errors from rapid clicking
                    if (err.name !== 'AbortError') {
                        console.warn('Edge-TTS playback interrupted:', err);
                    }
                });
            } catch (e) {
                console.warn('Edge-TTS error:', e);
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
            if (this.showDeckModal || this.showCardModal || this.showImportModal) return;

            // Only trigger shortcuts if actively in custom deck study mode
            if ((this.activeMainTab === 'bo-the-cua-ban' || this.activeMainTab === 'my_decks') && this.selectedDeck && this.deckSubTab === 'study') {
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
        // BULK VOCABULARY IMPORT METHODS
        // ==========================================

        /**
         * Open import modal.
         */
        openImportModal() {
            if (!this.isLoggedIn) {
                this.requireLogin();
                return;
            }
            this.showImportModal = true;
            this.importTab = 'paste';
            this.rawImportText = '';
            this.importDelimiter = 'auto';
            this.parsedImportCards = [];
            this.isImportPreviewing = false;
            this.isSubmittingImport = false;
        },

        /**
         * Close import modal and smoothly reset state after transition completes.
         */
        closeImportModal() {
            this.showImportModal = false;
            // Delay resetting preview state until modal transition completes (250ms)
            // to avoid layout contraction and flashing of stage 1 during fade-out
            setTimeout(() => {
                this.isImportPreviewing = false;
                this.isSubmittingImport = false;
                this.parsedImportCards = [];
            }, 300);
        },

        /**
         * Insert helpful sample text for quick testing.
         */
        insertSampleImportText() {
            this.rawImportText =
                "你好\tnǐ hǎo\tXin chào\t你好！很高兴认识你。\tXin chào! Rất vui được gặp bạn.\n" +
                "谢谢\txièxie\tCảm ơn bạn\t非常感谢你的帮助。\tCảm ơn sự giúp đỡ của bạn rất nhiều.\n" +
                "再见\tzàijiàn\tTạm biệt\t明天学校见！\tHẹn gặp lại ở trường vào ngày mai!\n" +
                "苹果\tpíngguǒ\tQuả táo\t我想买三斤红苹果。\tTôi muốn mua 1.5kg táo đỏ.\n" +
                "朋友\tpéngyou\tBạn bè\t他是我的好朋友。\tAnh ấy là người bạn tốt của tôi.";
        },

        /**
         * Generate Pinyin on-the-fly for card in preview table.
         */
        refreshPreviewPinyin(card) {
            if (!card.word || !card.word.trim()) return;
            if (typeof window.pinyinPro !== 'undefined' && typeof window.pinyinPro.pinyin === 'function') {
                try {
                    card.pinyin = window.pinyinPro.pinyin(card.word.trim());
                } catch (e) {
                    console.warn('Pinyin generation error:', e);
                }
            }
        },

        /**
         * Re-evaluate duplicates in parsedImportCards against current deck and within preview list.
         */
        recomputeDuplicates() {
            const existingWords = new Set(
                (this.selectedDeck?.flashcards || []).map(c => (c.word || '').trim().toLowerCase())
            );
            const seen = new Set();

            for (const card of this.parsedImportCards) {
                const wordKey = (card.word || '').trim().toLowerCase();
                if (!wordKey) {
                    card.isDuplicate = false;
                    continue;
                }
                if (existingWords.has(wordKey) || seen.has(wordKey)) {
                    card.isDuplicate = true;
                } else {
                    card.isDuplicate = false;
                    seen.add(wordKey);
                }
            }
        },

        get newImportCardsCount() {
            return (this.parsedImportCards || []).filter(c => !c.isDuplicate && (c.word || '').trim()).length;
        },

        get duplicateImportCardsCount() {
            return (this.parsedImportCards || []).filter(c => c.isDuplicate).length;
        },

        /**
         * Remove single card from preview table.
         */
        removePreviewCard(index) {
            this.parsedImportCards.splice(index, 1);
            if (this.parsedImportCards.length === 0) {
                this.isImportPreviewing = false;
            } else {
                this.recomputeDuplicates();
            }
        },

        /**
         * Process and validate user raw text before opening preview table.
         */
        processAndPreviewImport() {
            const text = (this.rawImportText || '').trim();
            if (!text) {
                this.showAlert({
                    title: 'Chưa có dữ liệu',
                    message: 'Vui lòng dán hoặc nhập danh sách từ vựng trước khi tiếp tục.',
                    type: 'warning'
                });
                return;
            }

            const cards = this.parseRawImportText(text, this.importDelimiter);
            if (cards.length === 0) {
                this.showAlert({
                    title: 'Không thể nhận diện từ vựng',
                    message: 'Không tìm thấy dòng từ vựng hợp lệ nào. Vui lòng kiểm tra lại cấu trúc (Ví dụ: Từ [Tab hoặc -] Nghĩa).',
                    type: 'error'
                });
                return;
            }

            this.parsedImportCards = cards;
            this.recomputeDuplicates();
            this.isImportPreviewing = true;
        },

        /**
         * Parse raw text lines into structured cards array.
         */
        parseRawImportText(text, delimiterMode) {
            const lines = text.split(/\r?\n/).map(l => l.trim()).filter(Boolean);
            const results = [];

            for (const line of lines) {
                // Determine separator for this line
                let parts = [];
                if (delimiterMode === 'tab' || (delimiterMode === 'auto' && line.includes('\t'))) {
                    parts = line.split('\t').map(p => p.trim());
                } else if (delimiterMode === 'dash' || (delimiterMode === 'auto' && (line.includes(' - ') || line.includes(' — ') || line.includes(' – ')))) {
                    parts = line.split(/\s*[-—–]\s*/).map(p => p.trim());
                } else if (delimiterMode === 'comma' || (delimiterMode === 'auto' && line.includes(','))) {
                    parts = line.split(',').map(p => p.trim());
                } else {
                    // Fallback: split by multiple consecutive whitespace or single dash
                    parts = line.split(/\s{2,}|[-—–]/).map(p => p.trim()).filter(Boolean);
                }

                if (parts.length >= 2) {
                    const word = parts[0];
                    let pinyin = '';
                    let meaning = '';
                    let example = '';
                    let example_meaning = '';

                    if (parts.length === 2) {
                        meaning = parts[1];
                    } else if (parts.length === 3) {
                        pinyin = parts[1];
                        meaning = parts[2];
                    } else if (parts.length === 4) {
                        pinyin = parts[1];
                        meaning = parts[2];
                        example = parts[3];
                    } else if (parts.length >= 5) {
                        pinyin = parts[1];
                        meaning = parts[2];
                        example = parts[3];
                        example_meaning = parts[4];
                    }

                    // Auto generate Pinyin if empty
                    if (!pinyin && typeof window.pinyinPro !== 'undefined' && typeof window.pinyinPro.pinyin === 'function') {
                        try {
                            pinyin = window.pinyinPro.pinyin(word);
                        } catch (e) {
                            pinyin = '';
                        }
                    }

                    if (word && meaning) {
                        results.push({
                            word: word,
                            pinyin: pinyin,
                            meaning: meaning,
                            example: example,
                            example_meaning: example_meaning,
                        });
                    }
                }
            }

            return results;
        },

        /**
         * Download ready-to-use CSV template with UTF-8 BOM encoding.
         */
        downloadCsvTemplate() {
            const csvContent = "\uFEFFChữ Hán,Phiên âm Pinyin,Ý nghĩa,Câu ví dụ,Dịch câu ví dụ\n" +
                "你好,nǐ hǎo,Xin chào,你好！很高兴认识你。,Xin chào! Rất vui được biết bạn.\n" +
                "谢谢,xièxie,Cảm ơn bạn,谢谢你的帮助。,Cảm ơn sự giúp đỡ của bạn.\n" +
                "再见,zàijiàn,Tạm biệt,明天见，再见！,Ngày mai gặp lại, tạm biệt!\n" +
                "苹果,píngguǒ,Quả táo,我想买苹果。,Tôi muốn mua táo.\n" +
                "朋友,péngyou,Bạn bè,他是我的好朋友。,Anh ấy là bạn thân của tôi.\n";

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.setAttribute('href', url);
            link.setAttribute('download', 'mau_nhap_tu_vung_xiaomu.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },

        /**
         * Handle CSV file selection and client-side parsing.
         */
        handleCsvFileSelect(event) {
            const file = event.target.files ? event.target.files[0] : null;
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                this.showAlert({
                    title: 'Tệp tin quá lớn',
                    message: 'Vui lòng chọn tệp tin CSV có dung lượng dưới 2MB.',
                    type: 'error'
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const text = e.target.result;
                    const parsedCards = this.parseCsvContent(text);
                    if (parsedCards.length === 0) {
                        this.showAlert({
                            title: 'Không tìm thấy dữ liệu',
                            message: 'Không thể đọc được dòng từ vựng hợp lệ nào trong tệp CSV này. Vui lòng sử dụng tệp mẫu để có kết quả chính xác nhất.',
                            type: 'error'
                        });
                        return;
                    }
                    this.parsedImportCards = parsedCards;
                    this.recomputeDuplicates();
                    this.isImportPreviewing = true;
                } catch (err) {
                    console.error('CSV Parsing Error:', err);
                    this.showAlert({
                        title: 'Lỗi đọc tệp tin',
                        message: 'Đã có lỗi khi phân tích nội dung tệp CSV.',
                        type: 'error'
                    });
                } finally {
                    event.target.value = '';
                }
            };

            reader.readAsText(file, 'UTF-8');
        },

        /**
         * Parse CSV formatted text with quote handling.
         */
        parseCsvContent(csvText) {
            const cleanText = csvText.replace(/^\uFEFF/, ''); // Strip BOM
            const lines = cleanText.split(/\r?\n/).map(l => l.trim()).filter(Boolean);
            const cards = [];

            let startIndex = 0;
            if (lines.length > 0) {
                const firstLower = lines[0].toLowerCase();
                if (firstLower.includes('chữ hán') || firstLower.includes('word') || firstLower.includes('pinyin')) {
                    startIndex = 1; // Skip header line
                }
            }

            for (let i = startIndex; i < lines.length; i++) {
                const row = this.parseCsvRow(lines[i]);
                if (row.length >= 2) {
                    const word = (row[0] || '').trim();
                    let pinyin = (row[1] || '').trim();
                    let meaning = (row[2] || '').trim();
                    let example = (row[3] || '').trim();
                    let example_meaning = (row[4] || '').trim();

                    // If row has only 2 columns: word & meaning
                    if (row.length === 2) {
                        meaning = (row[1] || '').trim();
                        pinyin = '';
                    }

                    if (!pinyin && word && typeof window.pinyinPro !== 'undefined' && typeof window.pinyinPro.pinyin === 'function') {
                        try {
                            pinyin = window.pinyinPro.pinyin(word);
                        } catch (e) {
                            pinyin = '';
                        }
                    }

                    if (word && meaning) {
                        cards.push({
                            word: word,
                            pinyin: pinyin,
                            meaning: meaning,
                            example: example,
                            example_meaning: example_meaning,
                        });
                    }
                }
            }

            return cards;
        },

        /**
         * Parse single CSV line accounting for commas inside quotes.
         */
        parseCsvRow(line) {
            const fields = [];
            let current = '';
            let inQuotes = false;

            for (let i = 0; i < line.length; i++) {
                const char = line[i];
                if (char === '"' || char === "'") {
                    inQuotes = !inQuotes;
                } else if (char === ',' && !inQuotes) {
                    fields.push(current.trim().replace(/^["']|["']$/g, ''));
                    current = '';
                } else {
                    current += char;
                }
            }
            fields.push(current.trim().replace(/^["']|["']$/g, ''));
            return fields;
        },

        /**
         * Submit bulk cards import payload to backend API.
         */
        async submitImportCards() {
            if (!this.selectedDeck || this.parsedImportCards.length === 0) return;

            // Only submit valid cards that are not duplicates
            const cardsToImport = this.parsedImportCards.filter(c => !c.isDuplicate && (c.word || '').trim());
            if (cardsToImport.length === 0) {
                this.showAlert({
                    title: 'Không có từ mới',
                    message: 'Tất cả các từ trong danh sách này đều đã tồn tại trong bộ thẻ.',
                    type: 'info'
                });
                return;
            }

            this.isSubmittingImport = true;

            try {
                const response = await fetch(`/api/custom-flashcards/decks/${this.selectedDeck.id}/import`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify({
                        cards: cardsToImport,
                    }),
                });

                const result = await response.json();
                if (result.success) {
                    this.closeImportModal();
                    if (result.deck) {
                        this.selectedDeck = result.deck;
                    } else {
                        await this.openDeck(this.selectedDeck);
                    }
                    await this.fetchDecks();

                    // Show toast notification
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            message: result.message || `Đã nhập thành công ${result.imported_count} từ vựng! 🎉`,
                            type: result.imported_count > 0 ? 'success' : 'info'
                        }
                    }));
                } else {
                    this.showAlert({
                        title: 'Lỗi nhập dữ liệu',
                        message: result.message || 'Không thể lưu danh sách từ vựng vào bộ thẻ lúc này.',
                        type: 'error'
                    });
                }
            } catch (error) {
                console.error('Import error:', error);
                this.showAlert({
                    title: 'Lỗi kết nối',
                    message: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.',
                    type: 'error'
                });
            } finally {
                this.isSubmittingImport = false;
            }
        },
    };
}
