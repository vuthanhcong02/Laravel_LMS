window.questionBuilder = function (data) {
    const config = window.QuizBuilderConfig || {};

    return {
        questions: data.initialQuestions || [],
        activeQuestion: 0,
        validationErrors: data.validationErrors || {},

        addQuestion() {
            const newIndex = this.questions.length;
            this.questions.push({
                id: null,
                type: 'multiple_choice',
                question_text: '',
                marks: 1,
                essay_grading_type: 'auto',
                correct_answer_text: '',
                case_sensitive: false,
                image_url: null,
                audio_url: null,
                audio_name: null,
                delete_image: false,
                delete_audio: false,
                options: [
                    { id: null, option_text: '', is_correct: false },
                    { id: null, option_text: '', is_correct: false },
                ],
            });

            this.activeQuestion = newIndex;

            this.$nextTick(() => {
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            });
        },

        scrollToQuestion(index) {
            this.activeQuestion = index;

            const el = document.getElementById('question-' + index);
            if (!el) return;

            const stickyHeader = document.querySelector('main > div.sticky, [data-sticky-header]');
            const headerHeight = (stickyHeader ? stickyHeader.offsetHeight : 120) + 70;

            el.style.scrollMarginTop = headerHeight + 'px';
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },

        removeQuestion(index) {
            const msg = config.confirmRemoveQuestion || 'Bạn có chắc chắn muốn xóa câu hỏi này?';
            if (confirm(msg)) {
                this.questions.splice(index, 1);
            }
        },

        addOption(qIndex) {
            this.questions[qIndex].options.push({
                id: null,
                option_text: '',
                is_correct: false,
            });
        },

        removeOption(qIndex, oIndex) {
            this.questions[qIndex].options.splice(oIndex, 1);
        },

        clearAllQuestions() {
            if (this.questions.length === 0) return;
            const msg = config.confirmClearAll || 'Bạn có chắc chắn muốn xóa TẤT CẢ câu hỏi? Hành động này không thể hoàn tác!';
            if (confirm(msg)) {
                this.questions = [];
                this.activeQuestion = 0;
                this.$nextTick(() => this.submitForm());
            }
        },

        previewImage(event, index) {
            const file = event.target.files[0];
            if (!file) return;

            this.questions[index].delete_image = false;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.questions[index].image_url = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        previewAudio(event, index) {
            const file = event.target.files[0];
            if (!file) return;

            this.questions[index].audio_name = file.name;
            this.questions[index].audio_url = URL.createObjectURL(file);
            this.questions[index].delete_audio = false;
        },

        removeImage(index) {
            this.questions[index].image_url = null;
            this.questions[index].delete_image = true;
            const fileInput = document.querySelector(`input[name="questions[${index}][image]"]`);
            if (fileInput) fileInput.value = '';
        },

        removeAudio(index) {
            this.questions[index].audio_url = null;
            this.questions[index].audio_name = null;
            this.questions[index].delete_audio = true;
            const fileInput = document.querySelector(`input[name="questions[${index}][audio]"]`);
            if (fileInput) fileInput.value = '';
        },

        toggleCorrect(qIndex, oIndex) {
            this.questions[qIndex].options.forEach((opt, i) => {
                opt.is_correct = i === oIndex;
            });
        },

        submitForm() {
            document.getElementById('questionsForm').submit();
        },
    };
}
