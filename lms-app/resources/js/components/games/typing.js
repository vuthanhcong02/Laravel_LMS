window.typingGameComponent = function() {
    return {
        shuffledList: [],
        currentIndex: 0,
        score: 0,
        isGameOver: false,
        hasAnswered: false,
        userInput: '',
        showError: false,
        showSuccess: false,
        
        init() {
            this.initTyping();
        },
        
        get currentWord() {
            return this.shuffledList[this.currentIndex] || {};
        },
        
        initTyping() {
            this.shuffledList = [...this.vocabList];
            for (let i = this.shuffledList.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [this.shuffledList[i], this.shuffledList[j]] = [this.shuffledList[j], this.shuffledList[i]];
            }
            
            this.currentIndex = 0;
            this.score = 0;
            this.isGameOver = false;
            this.resetTurn();
            
            // Focus input
            setTimeout(() => {
                if (this.$refs.typingInput) this.$refs.typingInput.focus();
            }, 100);
        },
        
        resetTurn() {
            this.userInput = '';
            this.hasAnswered = false;
            this.showError = false;
            this.showSuccess = false;
            setTimeout(() => {
                if (this.$refs.typingInput) this.$refs.typingInput.focus();
            }, 50);
        },
        
        checkAnswer() {
            if (this.hasAnswered || !this.userInput.trim()) return;
            
            this.hasAnswered = true;
            let isCorrect = this.userInput.trim() === this.currentWord.word;
            
            if (isCorrect) {
                this.score++;
                this.showSuccess = true;
                this.playWordAudio(this.currentWord.word);
                setTimeout(() => {
                    this.nextWord();
                }, 1000);
            } else {
                this.showError = true;
                setTimeout(() => {
                    this.showError = false;
                    this.hasAnswered = false;
                    if (this.$refs.typingInput) {
                        this.$refs.typingInput.select();
                        this.$refs.typingInput.focus();
                    }
                }, 800);
            }
        },
        
        skipWord() {
            this.nextWord();
        },
        
        nextWord() {
            if (this.currentIndex < this.shuffledList.length - 1) {
                this.currentIndex++;
                this.resetTurn();
            } else {
                this.isGameOver = true;
            }
        },
        
        getInputClass() {
            if (this.showError) return 'border-red-500 focus:ring-red-500/20 text-red-500';
            if (this.showSuccess) return 'border-green-500 focus:ring-green-500/20 text-green-600';
            return 'border-slate-200 dark:border-slate-700 focus:border-orange-500 focus:ring-orange-500/20';
        }
    };
};
