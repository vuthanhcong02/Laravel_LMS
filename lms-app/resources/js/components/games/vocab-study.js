window.vocabStudyComponent = function(initialVocabList) {
    return {
        viewMode: 'list', // list, flashcard, match, quiz, typing
        vocabList: initialVocabList,
        shuffledList: [],
        isShuffled: false,
        currentIndex: 0,
        flipped: false,
        
        init() {
            this.shuffledList = [...this.vocabList];
        },
        
        currentWord() {
            return this.shuffledList[this.currentIndex] || this.vocabList[0];
        },
        
        flipCard() {
            this.flipped = !this.flipped;
        },
        
        nextWord() {
            this.flipped = false;
            setTimeout(() => {
                this.currentIndex = (this.currentIndex + 1) % this.shuffledList.length;
            }, 150);
        },
        
        prevWord() {
            this.flipped = false;
            setTimeout(() => {
                this.currentIndex = (this.currentIndex - 1 + this.shuffledList.length) % this.shuffledList.length;
            }, 150);
        },
        
        shuffleList() {
            this.flipped = false;
            setTimeout(() => {
                if (this.isShuffled) {
                    this.isShuffled = false;
                    this.shuffledList = [...this.vocabList];
                } else {
                    this.isShuffled = true;
                    let list = [...this.vocabList];
                    for (let i = list.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [list[i], list[j]] = [list[j], list[i]];
                    }
                    this.shuffledList = list;
                }
                this.currentIndex = 0;
            }, 150);
        },
        
        playWordAudio(wordText) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                let utterance = new SpeechSynthesisUtterance(wordText);
                utterance.lang = 'zh-CN';
                let voices = window.speechSynthesis.getVoices();
                let zhVoice = voices.find(v => v.lang.includes('zh') || v.lang.includes('ZH'));
                if (zhVoice) utterance.voice = zhVoice;
                window.speechSynthesis.speak(utterance);
            }
        }
    };
};
