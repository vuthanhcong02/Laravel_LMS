/**
 * Component widget Từ vựng mỗi ngày & Audio phát âm
 */
export const homeDailyVocab = (wordToSpeak = '') => ({
    playing: false,
    timer: null,
    playAudio() {
        if (!('speechSynthesis' in window)) {
            alert('Trình duyệt không hỗ trợ phát âm.');
            return;
        }
        const synth = window.speechSynthesis;
        const word = wordToSpeak;
        if (!word) return;
        if (this.timer) {
            clearTimeout(this.timer);
            this.timer = null;
        }
        if (synth.paused) {
            synth.resume();
        }
        synth.cancel();
        this.playing = true;
        this.timer = setTimeout(() => {
            if (synth.paused) {
                synth.resume();
            }
            const utterance = new SpeechSynthesisUtterance(word);
            utterance.lang = 'zh-CN';
            utterance.rate = 0.85;
            const voices = synth.getVoices();
            const zhVoice = voices.find(v => 
                v.lang === 'zh-CN' || v.lang === 'zh_CN' || 
                v.lang.startsWith('zh') || v.lang.startsWith('cmn')
            );
            if (zhVoice) {
                utterance.voice = zhVoice;
            }
            utterance.onend = () => {
                this.playing = false;
                window._activeUtterance = null;
            };
            utterance.onerror = () => {
                this.playing = false;
                window._activeUtterance = null;
            };
            window._activeUtterance = utterance;
            synth.speak(utterance);
        }, 60);
    }
});

export default homeDailyVocab;
