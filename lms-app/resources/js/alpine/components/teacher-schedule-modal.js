/**
 * Alpine.js component for Teacher Schedule Detail Modal.
 *
 * Listens to 'open-schedule-modal' window event and manages modal state.
 *
 * @returns {Object} Alpine data object
 */
export default function teacherScheduleModal() {
    return {
        isOpen: false,
        event: {
            title: '',
            color: '#3b82f6',
            category: '',
            studentsCount: 0,
            dayName: '',
            timeRange: '',
            startDate: '',
            endDate: '',
            courseUrl: '',
        },

        init() {
            window.addEventListener('open-schedule-modal', (e) => {
                this.open(e.detail || {});
            });
        },

        open(detail) {
            this.event = {
                title: detail.title || '',
                color: detail.color || '#3b82f6',
                category: detail.category || '',
                studentsCount: detail.studentsCount || 0,
                dayName: detail.dayName || '',
                timeRange: detail.timeRange || '',
                startDate: detail.startDate || '',
                endDate: detail.endDate || '',
                courseUrl: detail.courseUrl || '',
            };
            this.isOpen = true;
        },

        close() {
            this.isOpen = false;
        }
    };
}
