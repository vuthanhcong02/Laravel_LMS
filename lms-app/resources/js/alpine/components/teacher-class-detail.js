/**
 * Alpine.js Component for Teacher Class Details (Student Enrollment, Lessons, Resources).
 *
 * @param {Object} config - Initial configuration passed from Blade view
 * @returns {Object} Alpine data object
 */
export default function teacherClassDetail(config = {}) {
    return {
        // Tab & Modal Visibility States
        activeTab: config.activeTab || 'overview',
        showAnnouncementModal: false,
        showAddStudentModal: config.showAddStudentModal || false,
        showDeleteConfirmModal: false,
        showLessonModal: config.showLessonModal || false,
        showDeleteLessonModal: false,
        showNoteModal: false,

        // Student Management State
        studentToDelete: null,
        deleteActionUrl: '',
        studentSearch: '',
        availableStudents: [],
        selectedStudentIds: [],
        isLoadingStudents: false,
        searchTimeout: null,

        // Client-side File Validation Errors
        clientErrors: {
            pdf_file: null,
            note_file: null
        },

        // Lesson Management State
        lessonModalMode: config.lessonModalMode || 'create',
        currentNoteView: {
            title: '',
            content: ''
        },
        lessonForm: {
            id: null,
            title: '',
            description: '',
            record_url: '',
            pdf_path: null,
            pdf_url: null,
            note_file_path: null,
            note_file_url: null,
            note_content: '',
            action_url: '',
            ...(config.initialLessonForm || {})
        },
        lessonToDelete: {
            id: null,
            title: '',
            action_url: ''
        },

        init() {
            if (!this.lessonForm.action_url && config.routes && config.routes.storeLesson) {
                this.lessonForm.action_url = config.routes.storeLesson;
            }
        },

        switchTab(tab) {
            this.activeTab = tab;
            if (window.history && window.history.replaceState) {
                const url = new URL(window.location);
                url.searchParams.set('tab', tab);
                window.history.replaceState({}, '', url);
            }
        },

        // Client-side File Validation
        handlePdfChange(event) {
            const file = event.target.files?.[0];
            this.clientErrors.pdf_file = null;

            if (!file) return;

            const maxSize = 20 * 1024 * 1024; // 20MB
            const isPdf = file.name.toLowerCase().endsWith('.pdf') || file.type === 'application/pdf';

            if (!isPdf) {
                this.clientErrors.pdf_file = 'Tệp đã chọn không đúng định dạng PDF (.pdf).';
                return;
            }

            if (file.size > maxSize) {
                const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
                this.clientErrors.pdf_file = `Dung lượng file PDF vượt quá 20MB (File hiện tại: ${sizeMb}MB). Vui lòng nén hoặc chọn file nhỏ hơn.`;
            }
        },

        handleNoteFileChange(event) {
            const file = event.target.files?.[0];
            this.clientErrors.note_file = null;

            if (!file) return;

            const maxSize = 20 * 1024 * 1024; // 20MB
            const allowedExtensions = ['.pdf', '.doc', '.docx', '.txt', '.zip', '.rar', '.ppt', '.pptx', '.xlsx', '.xls'];
            const fileName = file.name.toLowerCase();
            const isAllowed = allowedExtensions.some(ext => fileName.endsWith(ext));

            if (!isAllowed) {
                this.clientErrors.note_file = 'File ghi chú đính kèm chỉ chấp nhận các định dạng: pdf, doc, docx, txt, zip, rar, ppt, pptx, xlsx, xls.';
                return;
            }

            if (file.size > maxSize) {
                const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
                this.clientErrors.note_file = `Dung lượng file ghi chú vượt quá 20MB (File hiện tại: ${sizeMb}MB). Vui lòng chọn file nhỏ hơn.`;
            }
        },

        hasFileErrors() {
            return Boolean(this.clientErrors.pdf_file || this.clientErrors.note_file);
        },

        openAddStudentModal() {
            this.showAddStudentModal = true;
            this.studentSearch = '';
            this.selectedStudentIds = [];
            this.fetchAvailableStudents('');
        },

        onStudentSearchInput() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.fetchAvailableStudents(this.studentSearch);
            }, 300);
        },

        fetchAvailableStudents(query) {
            this.isLoadingStudents = true;
            const baseUrl = config.routes?.availableStudents || '';
            const url = `${baseUrl}?q=${encodeURIComponent(query || '')}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.availableStudents = data.students || [];
                    this.isLoadingStudents = false;
                })
                .catch(() => {
                    this.isLoadingStudents = false;
                });
        },

        toggleStudent(id) {
            if (this.selectedStudentIds.includes(id)) {
                this.selectedStudentIds = this.selectedStudentIds.filter(item => item !== id);
            } else {
                this.selectedStudentIds.push(id);
            }
        },

        isStudentSelected(id) {
            return this.selectedStudentIds.includes(id);
        },

        isAllSelected() {
            return this.availableStudents.length > 0 && 
                this.availableStudents.every(s => this.selectedStudentIds.includes(s.id));
        },

        toggleSelectAll() {
            if (this.isAllSelected()) {
                const currentIds = this.availableStudents.map(s => s.id);
                this.selectedStudentIds = this.selectedStudentIds.filter(id => !currentIds.includes(id));
            } else {
                const currentIds = this.availableStudents.map(s => s.id);
                this.selectedStudentIds = Array.from(new Set([...this.selectedStudentIds, ...currentIds]));
            }
        },

        confirmDeleteStudent(enrollmentId, studentName) {
            this.studentToDelete = studentName;
            const routeTemplate = config.routes?.unenrollStudent || '';
            this.deleteActionUrl = routeTemplate.replace('__ID__', enrollmentId);
            this.showDeleteConfirmModal = true;
        },

        openCreateLessonModal() {
            this.lessonModalMode = 'create';
            this.clientErrors.pdf_file = null;
            this.clientErrors.note_file = null;
            this.lessonForm = {
                id: null,
                title: '',
                description: '',
                record_url: '',
                pdf_path: null,
                pdf_url: null,
                note_file_path: null,
                note_file_url: null,
                note_content: '',
                action_url: config.routes?.storeLesson || ''
            };
            this.showLessonModal = true;
        },

        openEditLessonModal(lesson) {
            this.lessonModalMode = 'edit';
            this.clientErrors.pdf_file = null;
            this.clientErrors.note_file = null;
            const storageBase = config.routes?.storageAssetBase || '';
            const updateRouteTemplate = config.routes?.updateLesson || '';

            this.lessonForm = {
                id: lesson.id,
                title: lesson.title,
                description: lesson.description || '',
                record_url: lesson.record_url || lesson.video_url || '',
                pdf_path: lesson.pdf_path || null,
                pdf_url: lesson.pdf_url || (lesson.pdf_path ? `${storageBase}/${lesson.pdf_path}` : null),
                note_file_path: lesson.note_file_path || null,
                note_file_url: lesson.note_file_url || (lesson.note_file_path ? `${storageBase}/${lesson.note_file_path}` : null),
                note_content: lesson.note_content || '',
                action_url: updateRouteTemplate.replace('__ID__', lesson.id)
            };
            this.showLessonModal = true;
        },

        viewNoteContent(title, content) {
            this.currentNoteView = {
                title: title,
                content: content
            };
            this.showNoteModal = true;
        },

        confirmDeleteLesson(id, title) {
            const destroyRouteTemplate = config.routes?.destroyLesson || '';
            this.lessonToDelete = {
                id: id,
                title: title,
                action_url: destroyRouteTemplate.replace('__ID__', id)
            };
            this.showDeleteLessonModal = true;
        }
    };
}
