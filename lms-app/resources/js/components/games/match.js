window.matchGameComponent = function() {
    return {
        WORDS_PER_PAGE: 6,
        rightHotkeys: ['Q', 'W', 'E', 'R', 'T', 'Y'],
        currentPage: 0,
        matchedPairs: 0,
        currentLeftWords: [],
        currentRightWords: [],
        selectedLeft: null,
        selectedRight: null,
        isProcessing: false,
        
        init() {
            this.loadPage();
            window.addEventListener('keydown', this.handleKeydown.bind(this));
        },
        destroy() {
            window.removeEventListener('keydown', this.handleKeydown.bind(this));
        },
        get isPageComplete() {
            return this.currentLeftWords.length > 0 && this.currentLeftWords.every(w => w.matched);
        },
        loadPage() {
            let start = this.currentPage * this.WORDS_PER_PAGE;
            let pageWords = this.vocabList.slice(start, start + this.WORDS_PER_PAGE);
            let leftItems = pageWords.map(w => ({ ...w, matched: false }));
            let rightItems = pageWords.map(w => ({ ...w, matched: false }));
            this.currentLeftWords = this.shuffleArray(leftItems);
            this.currentRightWords = this.shuffleArray(rightItems);
            this.selectedLeft = null;
            this.selectedRight = null;
        },
        nextPage() {
            if (this.matchedPairs >= this.vocabList.length) {
                this.currentPage = 0;
                this.matchedPairs = 0;
            } else {
                this.currentPage++;
            }
            this.loadPage();
        },
        shuffleArray(array) {
            let list = [...array];
            for (let i = list.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [list[i], list[j]] = [list[j], list[i]];
            }
            return list;
        },
        selectLeft(item) {
            if (this.isProcessing || item.matched) return;
            this.selectedLeft = item;
            this.checkMatch();
        },
        selectRight(item) {
            if (this.isProcessing || item.matched) return;
            this.selectedRight = item;
            this.checkMatch();
        },
        checkMatch() {
            if (this.selectedLeft && this.selectedRight) {
                this.isProcessing = true;
                if (this.selectedLeft.id === this.selectedRight.id) {
                    this.playWordAudio(this.selectedLeft.word);
                    setTimeout(() => {
                        let leftTarget = this.currentLeftWords.find(w => w.id === this.selectedLeft.id);
                        let rightTarget = this.currentRightWords.find(w => w.id === this.selectedRight.id);
                        if (leftTarget) leftTarget.matched = true;
                        if (rightTarget) rightTarget.matched = true;
                        this.matchedPairs++;
                        this.selectedLeft = null;
                        this.selectedRight = null;
                        this.isProcessing = false;
                    }, 300);
                } else {
                    setTimeout(() => {
                        this.selectedLeft = null;
                        this.selectedRight = null;
                        this.isProcessing = false;
                    }, 500);
                }
            }
        },
        handleKeydown(e) {
            if (this.isProcessing || this.viewMode !== 'match') return;
            let key = e.key.toUpperCase();
            let numIndex = parseInt(key) - 1;
            if (numIndex >= 0 && numIndex < this.currentLeftWords.length) {
                this.selectLeft(this.currentLeftWords[numIndex]);
                return;
            }
            let rightIndex = this.rightHotkeys.indexOf(key);
            if (rightIndex !== -1 && rightIndex < this.currentRightWords.length) {
                this.selectRight(this.currentRightWords[rightIndex]);
                return;
            }
        }
    };
};
