export default () => ({
    showStructureModal: false,
    copiedId: null,

    init() {
        this.checkAndHighlightTargetExam();
    },

    checkAndHighlightTargetExam() {
        const params = new URLSearchParams(window.location.search);
        let targetId = params.get('exam');

        if (!targetId && window.location.hash.startsWith('#exam-')) {
            targetId = window.location.hash.replace('#exam-', '');
        }

        if (targetId) {
            this.$nextTick(() => {
                setTimeout(() => {
                    const el = document.getElementById(`exam-${targetId}`);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        el.classList.add('exam-card-highlight');

                        setTimeout(() => {
                            el.classList.remove('exam-card-highlight');
                        }, 3000);
                    }
                }, 200);
            });
        }
    },

    async copyExamLink(examId) {
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('exam', examId);
            url.hash = `exam-${examId}`;

            await navigator.clipboard.writeText(url.toString());
            this.copiedId = examId;

            setTimeout(() => {
                if (this.copiedId === examId) {
                    this.copiedId = null;
                }
            }, 2000);
        } catch (err) {
            console.error('Không thể sao chép liên kết đề thi:', err);
        }
    }
});
