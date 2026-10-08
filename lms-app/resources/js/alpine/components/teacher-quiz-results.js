/**
 * Alpine.js Component for Teacher Quiz Results & Submissions
 */
export default function teacherQuizResults(config = {}) {
    return {
        // Full students results list
        students: config.students || [],
        totalMarks: config.totalMarks || 10,
        quizTitle: config.quizTitle || '',
        
        // Search & filter state
        searchQuery: '',
        statusFilter: 'all', // 'all', 'completed', 'in_progress', 'not_started'
        
        // Detail & grading modal state
        showDetailModal: false,
        isLoadingDetail: false,
        currentAttemptDetail: null,
        detailError: null,
        isSubmittingGrade: false,
        gradeSuccessMessage: null,

        /**
         * Filtered students list based on search query and status filter
         */
        get filteredStudents() {
            return this.students.filter(student => {
                // Filter by status
                if (this.statusFilter !== 'all' && student.status !== this.statusFilter) {
                    return false;
                }

                // Filter by search query
                if (this.searchQuery.trim() !== '') {
                    const query = this.searchQuery.toLowerCase().trim();
                    const nameMatch = (student.name || '').toLowerCase().includes(query);
                    const emailMatch = (student.email || '').toLowerCase().includes(query);
                    return nameMatch || emailMatch;
                }

                return true;
            });
        },

        /**
         * Open modal to view student attempt details
         */
        async openAttemptDetail(student) {
            if (!student.attempt_id) {
                return;
            }

            this.showDetailModal = true;
            this.isLoadingDetail = true;
            this.currentAttemptDetail = null;
            this.detailError = null;
            this.gradeSuccessMessage = null;

            try {
                const response = await fetch(`/portal/teacher/quizzes/attempts/${student.attempt_id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load attempt detail.');
                }

                const data = await response.json();
                this.currentAttemptDetail = data;
            } catch (err) {
                console.error(err);
                this.detailError = err.message || 'An error occurred while loading attempt details.';
            } finally {
                this.isLoadingDetail = false;
            }
        },

        /**
         * Submit essay grades and teacher feedback
         */
        async submitEssayGrades() {
            if (!this.currentAttemptDetail || !this.currentAttemptDetail.attempt) {
                return;
            }

            const attemptId = this.currentAttemptDetail.attempt.id;
            const essayQuestions = this.currentAttemptDetail.questions.filter(q => q.type === 'essay' && q.essay_grading_type === 'manual');

            const grades = essayQuestions.map(q => ({
                question_id: q.id,
                marks_obtained: parseFloat(q.marks_obtained || 0),
                feedback: q.teacher_feedback || ''
            }));

            this.isSubmittingGrade = true;
            this.detailError = null;
            this.gradeSuccessMessage = null;

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch(`/portal/teacher/quizzes/attempts/${attemptId}/grade`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ grades })
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Failed to save grades.');
                }

                this.currentAttemptDetail = result.data;
                this.gradeSuccessMessage = result.message || 'Grades saved successfully!';

                // Synchronize updated score in the table list
                const st = this.students.find(s => s.attempt_id === attemptId);
                if (st) {
                    st.score = result.data.score;
                    st.percentage = result.data.percentage;
                    st.grading_status = result.data.grading_status;
                }

                setTimeout(() => {
                    this.gradeSuccessMessage = null;
                }, 4000);
            } catch (err) {
                console.error(err);
                this.detailError = err.message || 'An error occurred while saving grades.';
            } finally {
                this.isSubmittingGrade = false;
            }
        },

        /**
         * Close attempt detail modal
         */
        closeDetailModal() {
            this.showDetailModal = false;
            this.currentAttemptDetail = null;
            this.detailError = null;
            this.gradeSuccessMessage = null;
        }
    };
}
