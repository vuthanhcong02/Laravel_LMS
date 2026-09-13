export default function hskResultViewer() {
    return {
        filter: 'all', 
        collapsedSections: {},
        mobilePaletteOpen: false,
        highlightedQ: null,
        scrollToQuestion(qId, sectionKey, isCorrect) {
            this.collapsedSections[sectionKey] = false;
            if (this.filter === 'correct' && !isCorrect) {
                this.filter = 'all';
            } else if (this.filter === 'incorrect' && isCorrect) {
                this.filter = 'all';
            }
            this.mobilePaletteOpen = false;
            
            this.$nextTick(() => {
                const el = document.getElementById(qId);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    this.highlightedQ = qId;
                    setTimeout(() => {
                        if (this.highlightedQ === qId) {
                            this.highlightedQ = null;
                        }
                    }, 2000);
                }
            });
        }
    };
}
