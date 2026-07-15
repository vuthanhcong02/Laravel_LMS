window.quizGameComponent = function() {
    return {
        questions: [],
        currentIndex: 0,
        score: 0,
        isGameOver: false,
        hasAnswered: false,
        
        init() {
            this.initQuiz();
        },
        
        get currentQuestion() {
            return this.questions[this.currentIndex] || {};
        },
        
        initQuiz() {
            this.questions = [];
            let list = [...this.vocabList];
            // Shuffle list
            for (let i = list.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [list[i], list[j]] = [list[j], list[i]];
            }
            
            // Generate questions
            this.questions = list.map(word => {
                let type = Math.random() > 0.5 ? 'meaning' : 'hanzi';
                let options = [{text: type === 'meaning' ? word.meaning : word.word, isCorrect: true}];
                
                // Add 3 distractors
                let distractors = list.filter(w => w.id !== word.id);
                // Shuffle distractors
                for (let i = distractors.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [distractors[i], distractors[j]] = [distractors[j], distractors[i]];
                }
                
                distractors.slice(0, 3).forEach(d => {
                    options.push({text: type === 'meaning' ? d.meaning : d.word, isCorrect: false});
                });
                
                // Shuffle options
                for (let i = options.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [options[i], options[j]] = [options[j], options[i]];
                }
                
                return {
                    word: word,
                    type: type,
                    questionText: type === 'meaning' ? word.word : word.meaning,
                    options: options,
                    selectedIndex: null
                };
            });
            
            this.currentIndex = 0;
            this.score = 0;
            this.isGameOver = false;
            this.hasAnswered = false;
        },
        
        selectAnswer(index) {
            if (this.hasAnswered) return;
            
            this.hasAnswered = true;
            this.currentQuestion.selectedIndex = index;
            
            let isCorrect = this.currentQuestion.options[index].isCorrect;
            
            if (isCorrect) {
                this.score++;
                this.playWordAudio(this.currentQuestion.word.word);
            }
            
            setTimeout(() => {
                this.nextQuestion();
            }, isCorrect ? 1000 : 1500);
        },
        
        nextQuestion() {
            if (this.currentIndex < this.questions.length - 1) {
                this.currentIndex++;
                this.hasAnswered = false;
            } else {
                this.isGameOver = true;
            }
        },
        
        getOptionClass(index) {
            if (!this.hasAnswered) return 'border-slate-100 dark:border-slate-700 hover:border-purple-300 dark:hover:border-purple-700 hover:-translate-y-1 hover:shadow-md';
            
            let opt = this.currentQuestion.options[index];
            if (opt.isCorrect) return 'border-green-500 bg-green-50 dark:bg-green-900/20 shadow-[0_0_15px_rgba(34,197,94,0.2)]';
            if (this.currentQuestion.selectedIndex === index && !opt.isCorrect) return 'border-red-500 bg-red-50 dark:bg-red-900/20';
            
            return 'opacity-50 border-slate-100 dark:border-slate-700';
        },
        
        getOptionBadgeClass(index) {
            if (!this.hasAnswered) return 'group-hover:bg-purple-100 group-hover:text-purple-600';
            
            let opt = this.currentQuestion.options[index];
            if (opt.isCorrect) return 'bg-green-500 text-white';
            if (this.currentQuestion.selectedIndex === index && !opt.isCorrect) return 'bg-red-500 text-white';
            
            return '';
        }
    };
};
