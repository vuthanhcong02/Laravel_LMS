export default () => ({
        timeRemaining: window.examTimeRemaining || 2100, // Default 35 minutes if not passed
        isPlaying: false,
        mobileNavOpen: false,
        showSubmitModal: false,
        showExitModal: false,
        answers: {},
        darkMode: false,
        
        init() {
            const timer = setInterval(() => {
                if (this.timeRemaining > 0) {
                    this.timeRemaining--;
                } else {
                    clearInterval(timer);
                    alert('Hết giờ làm bài! Bài thi đã được tự động nộp.');
                    const form = document.getElementById('exam-submit-form');
                    if (form) form.submit();
                }
            }, 1000);
        },
        
        formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },
        
        toggleAudio() {
            this.isPlaying = !this.isPlaying;
        },
        
        playAudio() {
            this.isPlaying = true;
            alert('Đang phát file nghe âm thanh của câu hỏi này...');
        },
        
        scrollToQuestion(qNum) {
            const el = document.getElementById('q-' + qNum);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
});
