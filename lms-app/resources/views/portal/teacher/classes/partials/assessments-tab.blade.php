<div x-data="{
    subTab: 'assignments',
    showQuizModal: false,
    selectedQuiz: null,
    quizFilter: 'all',
    openQuizModal(quiz) {
        this.selectedQuiz = quiz;
        this.quizFilter = 'all';
        this.showQuizModal = true;
    },
    get filteredQuizStudents() {
        if (!this.selectedQuiz || !this.selectedQuiz.students) return [];
        if (this.quizFilter === 'completed') {
            return this.selectedQuiz.students.filter(s => s.has_attempted);
        }
        if (this.quizFilter === 'uncompleted') {
            return this.selectedQuiz.students.filter(s => !s.has_attempted);
        }
        return this.selectedQuiz.students;
    },
    get completedCount() {
        if (!this.selectedQuiz || !this.selectedQuiz.students) return 0;
        return this.selectedQuiz.students.filter(s => s.has_attempted).length;
    },
    get uncompletedCount() {
        if (!this.selectedQuiz || !this.selectedQuiz.students) return 0;
        return this.selectedQuiz.students.filter(s => !s.has_attempted).length;
    },

    // Modal cho bài tập (Assignments)
    showAssignmentModal: false,
    selectedAssignment: null,
    assignmentFilter: 'all',
    openAssignmentModal(assignment) {
        this.selectedAssignment = assignment;
        this.assignmentFilter = 'all';
        this.showAssignmentModal = true;
    },
    get filteredAssignmentStudents() {
        if (!this.selectedAssignment || !this.selectedAssignment.students) return [];
        if (this.assignmentFilter === 'submitted') {
            return this.selectedAssignment.students.filter(s => s.has_submitted);
        }
        if (this.assignmentFilter === 'unsubmitted') {
            return this.selectedAssignment.students.filter(s => !s.has_submitted);
        }
        return this.selectedAssignment.students;
    },
    get assignmentSubmittedCount() {
        if (!this.selectedAssignment || !this.selectedAssignment.students) return 0;
        return this.selectedAssignment.students.filter(s => s.has_submitted).length;
    },
    get assignmentUnsubmittedCount() {
        if (!this.selectedAssignment || !this.selectedAssignment.students) return 0;
        return this.selectedAssignment.students.filter(s => !s.has_submitted).length;
    },

    // Modal cho kết quả thi thử HSK
    showHskModal: false,
    selectedHskResult: null,
    openHskModal(result) {
        this.selectedHskResult = result;
        this.showHskModal = true;
    }
}" class="space-y-6">


    {{-- Header điều hướng Sub-tab và Action Buttons --}}
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Navigation Sub-tabs --}}
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl w-fit">
            <button type="button" @click="subTab = 'assignments'"
                    :class="subTab === 'assignments' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-sm">assignment</span>
                <span>{{ __('Bài tập') }}</span>
                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold"
                      :class="subTab === 'assignments' ? 'bg-primary/10 text-primary' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400'">
                    {{ $assignments->count() }}
                </span>
            </button>

            <button type="button" @click="subTab = 'quizzes'"
                    :class="subTab === 'quizzes' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-sm">quiz</span>
                <span>{{ __('Bài kiểm tra') }}</span>
                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold"
                      :class="subTab === 'quizzes' ? 'bg-primary/10 text-primary' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400'">
                    {{ $quizzes->count() }}
                </span>
            </button>

            <button type="button" @click="subTab = 'hsk'"
                    :class="subTab === 'hsk' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-sm">school</span>
                <span>{{ __('Đề thi thử HSK') }}</span>
                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold"
                      :class="subTab === 'hsk' ? 'bg-primary/10 text-primary' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400'">
                    {{ $hskResults->count() }}
                </span>
            </button>
        </div>

        {{-- Nút thao tác nhanh --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.assignments.create') }}"
               class="px-3 py-1.5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                <span class="material-symbols-outlined text-base">add</span>
                <span>{{ __('Giao bài tập mới') }}</span>
            </a>
            <a href="{{ route('teacher.quizzes.create') }}"
               class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                <span class="material-symbols-outlined text-base">add</span>
                <span>{{ __('Tạo bài kiểm tra') }}</span>
            </a>
        </div>
    </div>

    {{-- SUB-TAB 1: DANH SÁCH BÀI TẬP (ASSIGNMENTS) --}}
    <div x-show="subTab === 'assignments'" x-transition class="space-y-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 text-slate-400 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Tiêu đề bài tập') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Bài học liên kết') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Hạn nộp bài') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Tiến độ nộp') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Trạng thái chấm') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-right">{{ __('Thao tác') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-xs sm:text-sm">
                        @forelse($assignments as $assignment)
                            @php
                                $totalEnrolled = $class->enrollments_count ?? 1;
                                $submittedCount = $assignment->submissions_count;
                                $gradedCount = $assignment->submissions->where('status', \App\Models\AssignmentSubmission::STATUS_GRADED)->count();
                                $pendingCount = $submittedCount - $gradedCount;
                                $percent = $totalEnrolled > 0 ? min(100, round(($submittedCount / $totalEnrolled) * 100)) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-col">
                                        <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-primary transition-colors text-xs sm:text-sm">
                                            {{ $assignment->title }}
                                        </a>
                                        @if($assignment->description)
                                            <p class="text-[11px] text-slate-400 font-normal line-clamp-1 mt-0.5">
                                                {{ Str::limit(strip_tags($assignment->description), 60) }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-slate-600 dark:text-slate-400 font-normal">
                                    {{ $assignment->lesson->title ?? __('Không gắn bài học') }}
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($assignment->due_date)
                                        <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                            <span class="material-symbols-outlined text-[15px] text-slate-400">event</span>
                                            <span class="text-xs font-medium">{{ $assignment->due_date->format('d/m/Y H:i') }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">{{ __('Không giới hạn') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            @click="openAssignmentModal({
                                                id: {{ $assignment->id }},
                                                title: '{{ addslashes($assignment->title) }}',
                                                url: '{{ route('teacher.assignments.show', $assignment->id) }}',
                                                students: {{ json_encode($assignment->students_submissions_list ?? []) }}
                                            })"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-primary/10 hover:text-primary dark:bg-slate-800 dark:hover:bg-primary/20 text-slate-800 dark:text-slate-200 text-xs font-bold transition-all cursor-pointer shadow-2xs group"
                                            title="{{ __('Bấm để xem danh sách học viên đã nộp và chưa nộp') }}">
                                        <span class="material-symbols-outlined text-[15px] text-primary group-hover:scale-110 transition-transform">groups</span>
                                        <span>{{ $submittedCount }}/{{ $class->enrollments_count }}</span>
                                    </button>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($pendingCount > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 text-[11px] font-bold">
                                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            {{ __(':count chờ chấm', ['count' => $pendingCount]) }}
                                        </span>
                                    @elseif($submittedCount > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[12px]">done_all</span>
                                            {{ __('Đã chấm đủ') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">{{ __('Chưa có bài nộp') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right space-x-1 whitespace-nowrap">
                                    <a href="{{ route('teacher.assignments.show', $assignment->id) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary/10 hover:bg-primary text-primary hover:text-white transition-colors rounded-lg font-bold text-xs"
                                       title="{{ __('Xem bài nộp & Chấm điểm') }}">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        <span>{{ __('Xem bài nộp') }}</span>
                                    </a>
                                    <a href="{{ route('teacher.assignments.edit', $assignment->id) }}"
                                       class="inline-flex items-center justify-center size-7 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                       title="{{ __('Chỉnh sửa bài tập') }}">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-500">
                                    <div class="size-12 bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-2.5 border border-slate-100 dark:border-slate-800">
                                        <span class="material-symbols-outlined text-2xl text-slate-400">assignment_late</span>
                                    </div>
                                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ __('Chưa có bài tập nào cho lớp học này.') }}</p>
                                    <p class="text-xs text-slate-400 mt-1 mb-3">{{ __('Giao bài tập để học viên rèn luyện và nộp bài định kỳ.') }}</p>
                                    <a href="{{ route('teacher.assignments.create') }}" class="inline-flex items-center gap-1 px-3.5 py-1.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary/90 transition-all">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                        <span>{{ __('Giao bài tập đầu tiên') }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SUB-TAB 2: DANH SÁCH BÀI KIỂM TRA (QUIZZES) --}}
    <div x-show="subTab === 'quizzes'" x-transition class="space-y-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 text-slate-400 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Tên bài kiểm tra') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Phân loại / Thời lượng') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Tiến độ làm bài') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Điểm TB') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Hạn làm bài') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-right">{{ __('Thao tác') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-xs sm:text-sm">
                        @forelse($quizzes as $quiz)
                            @php
                                $validAttempts = $quiz->attempts->whereNotNull('score')->where('status', 'completed');
                                $avgScore = $validAttempts->count() > 0 ? round($validAttempts->avg('score'), 1) : null;
                                $quizCompletedCount = $quiz->students_attempts_list ? $quiz->students_attempts_list->where('has_attempted', true)->count() : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-col">
                                        <a href="{{ route('teacher.quizzes.results', $quiz->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-primary transition-colors text-xs sm:text-sm">
                                            {{ $quiz->title }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 font-normal">
                                            {{ __(':count câu hỏi', ['count' => $quiz->questions_count ?? $quiz->questions()->count()]) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold w-fit {{ $quiz->type->value === 'mixed' ? 'bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300' : ($quiz->type->value === 'essay' ? 'bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300') }}">
                                            {{ $quiz->type->label() }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 font-normal">
                                            <span class="material-symbols-outlined text-[13px]">timer</span>
                                            {{ $quiz->time_limit > 0 ? $quiz->time_limit . ' ' . __('phút') : __('Không giới hạn') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            @click="openQuizModal({
                                                id: {{ $quiz->id }},
                                                title: '{{ addslashes($quiz->title) }}',
                                                results_url: '{{ route('teacher.quizzes.results', $quiz->id) }}',
                                                students: {{ json_encode($quiz->students_attempts_list ?? []) }}
                                            })"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-primary/10 hover:text-primary dark:bg-slate-800 dark:hover:bg-primary/20 text-slate-800 dark:text-slate-200 text-xs font-bold transition-all cursor-pointer shadow-2xs group"
                                            title="{{ __('Bấm để xem danh sách học viên đã làm và chưa làm') }}">
                                        <span class="material-symbols-outlined text-[15px] text-primary group-hover:scale-110 transition-transform">groups</span>
                                        <span>{{ $quizCompletedCount }}/{{ $class->enrollments_count }}</span>
                                    </button>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($avgScore !== null)
                                        <span class="font-bold text-xs sm:text-sm {{ $avgScore >= 8 ? 'text-emerald-600 dark:text-emerald-400' : ($avgScore >= 5 ? 'text-blue-600 dark:text-blue-400' : 'text-rose-500') }}">
                                            {{ $avgScore }}/10
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">--</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($quiz->due_date)
                                        <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                            <span class="material-symbols-outlined text-[15px] text-slate-400">event</span>
                                            <span class="text-xs font-medium">{{ $quiz->due_date->format('d/m/Y H:i') }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">{{ __('Không giới hạn') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right space-x-1 whitespace-nowrap">
                                    <a href="{{ route('teacher.quizzes.results', $quiz->id) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary/10 hover:bg-primary text-primary hover:text-white transition-colors rounded-lg font-bold text-xs"
                                       title="{{ __('Xem kết quả làm bài của học sinh') }}">
                                        <span class="material-symbols-outlined text-sm">analytics</span>
                                        <span>{{ __('Xem kết quả') }}</span>
                                    </a>
                                    <a href="{{ route('teacher.quizzes.questions', $quiz->id) }}"
                                       class="inline-flex items-center justify-center size-7 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                       title="{{ __('Quản lý câu hỏi') }}">
                                        <span class="material-symbols-outlined text-[16px]">list_alt</span>
                                    </a>
                                    <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}"
                                       class="inline-flex items-center justify-center size-7 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                       title="{{ __('Chỉnh sửa bài thi') }}">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-500">
                                    <div class="size-12 bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-2.5 border border-slate-100 dark:border-slate-800">
                                        <span class="material-symbols-outlined text-2xl text-slate-400">quiz</span>
                                    </div>
                                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ __('Chưa có bài kiểm tra nào trong khóa học này.') }}</p>
                                    <p class="text-xs text-slate-400 mt-1 mb-3">{{ __('Tạo bài trắc nghiệm hoặc tự luận để kiểm tra năng lực học viên.') }}</p>
                                    <a href="{{ route('teacher.quizzes.create') }}" class="inline-flex items-center gap-1 px-3.5 py-1.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary/90 transition-all">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                        <span>{{ __('Tạo bài kiểm tra đầu tiên') }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SUB-TAB 3: KẾT QUẢ THI THỬ HSK CỦA HỌC SINH TRONG LỚP --}}
    <div x-show="subTab === 'hsk'" x-transition class="space-y-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 text-slate-400 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 min-w-[200px]">{{ __('Học viên') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Đề thi HSK') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Điểm Nghe') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Điểm Đọc') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Điểm Viết') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Tổng điểm') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Kết quả') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Ngày thi') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-right">{{ __('Thao tác') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-xs sm:text-sm">
                        @forelse($hskResults as $result)
                            @php
                                $exam = $result->mockExam;
                                $levelCode = strtolower($exam->hskLevel->level_code ?? 'hsk1');
                                $maxScore = in_array($levelCode, ['hsk1', 'hsk2']) ? 200 : 300;
                                $passScore = in_array($levelCode, ['hsk1', 'hsk2']) ? 120 : 180;
                                $isPassed = ($result->total_score ?? 0) >= $passScore;
                            @endphp
                            <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($result->user->avatar_url)
                                            <img src="{{ $result->user->avatar_url }}" alt="{{ $result->user->full_name }}" class="size-8 rounded-lg object-cover shadow-xs">
                                        @else
                                            <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                                {{ mb_substr($result->user->first_name ?: $result->user->full_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm">
                                                {{ $result->user->full_name }}
                                            </p>
                                            <p class="text-[11px] text-slate-400 font-normal">{{ $result->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm line-clamp-1">
                                            {{ $exam->title ?? __('Đề thi HSK') }}
                                        </p>
                                        @if($exam && $exam->hskLevel)
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-orange-100 dark:bg-orange-950/40 text-orange-700 dark:text-orange-300 w-fit">
                                                {{ $exam->hskLevel->title }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-xs text-slate-700 dark:text-slate-300">
                                    {{ $result->listening_score ?? '--' }}
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-xs text-slate-700 dark:text-slate-300">
                                    {{ $result->reading_score ?? '--' }}
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-xs text-slate-700 dark:text-slate-300">
                                    {{ $result->writing_score !== null ? $result->writing_score : '--' }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="font-bold text-xs sm:text-sm {{ $isPassed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500' }}">
                                        {{ $result->total_score ?? 0 }}/{{ $maxScore }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($isPassed)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                            {{ __('Đạt') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[13px]">cancel</span>
                                            {{ __('Không đạt') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-xs text-slate-500 font-normal whitespace-nowrap">
                                    {{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : '--' }}
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    @if($result->uuid)
                                        <button type="button"
                                                @click="openHskModal({
                                                    id: {{ $result->id }},
                                                    uuid: '{{ $result->uuid }}',
                                                    student_name: '{{ addslashes($result->user->full_name) }}',
                                                    student_email: '{{ addslashes($result->user->email) }}',
                                                    student_avatar: '{{ $result->user->avatar_url }}',
                                                    student_first_char: '{{ mb_substr($result->user->first_name ?: $result->user->full_name, 0, 1) }}',
                                                    exam_title: '{{ addslashes($exam->title ?? __('Đề thi HSK')) }}',
                                                    exam_level: '{{ $exam && $exam->hskLevel ? addslashes($exam->hskLevel->title) : '' }}',
                                                    listening_score: {{ $result->listening_score ?? 0 }},
                                                    reading_score: {{ $result->reading_score ?? 0 }},
                                                    writing_score: {{ $result->writing_score !== null ? $result->writing_score : 'null' }},
                                                    total_score: {{ $result->total_score ?? 0 }},
                                                    pass_score: {{ $passScore }},
                                                    max_score: {{ $exam->total_score ?? 300 }},
                                                    is_passed: {{ $isPassed ? 'true' : 'false' }},
                                                    completed_at: '{{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : '--' }}',
                                                    started_at: '{{ $result->started_at ? $result->started_at->format('d/m/Y H:i') : '--' }}',
                                                    full_result_url: '{{ route('student.hsk-mock-exams.result', $result->uuid) }}'
                                                })"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-primary text-slate-700 hover:text-white dark:bg-slate-800 dark:hover:bg-primary dark:text-slate-300 transition-colors rounded-lg font-bold text-xs cursor-pointer"
                                                title="{{ __('Xem chi tiết bài làm') }}">
                                            <span class="material-symbols-outlined text-[13px]">visibility</span>
                                            <span>{{ __('Chi tiết') }}</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-12 text-center text-slate-500">
                                    <div class="size-12 bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-2.5 border border-slate-100 dark:border-slate-800">
                                        <span class="material-symbols-outlined text-2xl text-slate-400">workspace_premium</span>
                                    </div>
                                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ __('Chưa có học viên nào trong lớp thi thử HSK.') }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ __('Kết quả bài thi thử HSK của học sinh trong lớp sẽ được tự động tổng hợp tại đây.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL: Danh sách học viên đã làm và chưa làm bài kiểm tra --}}
    <div x-show="showQuizModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-cloak>

        <div @click.away="showQuizModal = false"
             x-show="showQuizModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl max-w-lg w-full overflow-hidden flex flex-col max-h-[85vh]">

            {{-- Modal Header --}}
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/40">
                <div class="space-y-0.5">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="selectedQuiz?.title"></h3>
                    <p class="text-xs text-slate-500 font-medium">
                        {{ __('Tiến độ làm bài:') }}
                        <span class="font-bold text-primary" x-text="completedCount"></span>/<span x-text="selectedQuiz?.students?.length || 0"></span> {{ __('học viên') }}
                    </p>
                </div>
                <button type="button" @click="showQuizModal = false"
                        class="size-8 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            {{-- Modal Filter Tabs --}}
            <div class="px-5 pt-3 pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-1.5 bg-slate-50/20 dark:bg-slate-800/20">
                <button type="button" @click="quizFilter = 'all'"
                        :class="quizFilter === 'all' ? 'bg-primary text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                        class="px-3 py-1 rounded-lg text-xs transition-colors cursor-pointer flex items-center gap-1">
                    <span>{{ __('Tất cả') }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                          :class="quizFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                          x-text="selectedQuiz?.students?.length || 0"></span>
                </button>

                <button type="button" @click="quizFilter = 'completed'"
                        :class="quizFilter === 'completed' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                        class="px-3 py-1 rounded-lg text-xs transition-colors cursor-pointer flex items-center gap-1">
                    <span>{{ __('Đã làm') }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                          :class="quizFilter === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                          x-text="completedCount"></span>
                </button>

                <button type="button" @click="quizFilter = 'uncompleted'"
                        :class="quizFilter === 'uncompleted' ? 'bg-rose-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                        class="px-3 py-1 rounded-lg text-xs transition-colors cursor-pointer flex items-center gap-1">
                    <span>{{ __('Chưa làm') }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                          :class="quizFilter === 'uncompleted' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                          x-text="uncompletedCount"></span>
                </button>
            </div>

            {{-- Modal Body: Danh sách học viên --}}
            <div class="p-5 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800 flex-1">
                <template x-for="student in filteredQuizStudents" :key="student.id">
                    <div class="py-3 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <template x-if="student.avatar_url">
                                <img :src="student.avatar_url" :alt="student.name" class="size-9 rounded-xl object-cover shadow-2xs shrink-0">
                            </template>
                            <template x-if="!student.avatar_url">
                                <div class="size-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0" x-text="student.first_char"></div>
                            </template>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate" x-text="student.name"></p>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5" x-text="student.email"></p>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            <template x-if="student.has_attempted">
                                <div class="flex flex-col items-end gap-0.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                        <span x-text="student.score !== null ? student.score + '/10' : '{{ __('Đã nộp') }}'"></span>
                                    </span>
                                    <span x-show="student.completed_at" class="text-[10px] text-slate-400" x-text="student.completed_at"></span>
                                </div>
                            </template>
                            <template x-if="!student.has_attempted">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-xs font-semibold">
                                    <span class="material-symbols-outlined text-[13px]">schedule</span>
                                    {{ __('Chưa làm') }}
                                </span>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="filteredQuizStudents.length === 0" class="py-8 text-center text-slate-400 text-xs">
                    {{ __('Không có học viên nào trong mục này.') }}
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex items-center justify-between">
                <a :href="selectedQuiz?.results_url"
                   class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline">
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                    <span>{{ __('Xem trang thống kê bài thi') }}</span>
                </a>
                <button type="button" @click="showQuizModal = false"
                        class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                    {{ __('Đóng') }}
                </button>
            </div>

        </div>
    </div>

    {{-- MODAL: Danh sách học viên đã nộp và chưa nộp bài tập --}}
    <div x-show="showAssignmentModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-cloak>

        <div @click.away="showAssignmentModal = false"
             x-show="showAssignmentModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl max-w-lg w-full overflow-hidden flex flex-col max-h-[85vh]">

            {{-- Modal Header --}}
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/40">
                <div class="space-y-0.5 min-w-0 pr-4">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white truncate" x-text="selectedAssignment?.title"></h3>
                    <p class="text-xs text-slate-500 font-medium">
                        {{ __('Tiến độ nộp bài:') }}
                        <span class="font-bold text-primary" x-text="assignmentSubmittedCount"></span>/<span x-text="selectedAssignment?.students?.length || 0"></span> {{ __('học viên') }}
                    </p>
                </div>
                <button type="button" @click="showAssignmentModal = false"
                        class="size-8 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer shrink-0">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            {{-- Modal Filter Tabs --}}
            <div class="px-5 pt-3 pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-1.5 bg-slate-50/20 dark:bg-slate-800/20">
                <button type="button" @click="assignmentFilter = 'all'"
                        :class="assignmentFilter === 'all' ? 'bg-primary text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                        class="px-3 py-1 rounded-lg text-xs transition-colors cursor-pointer flex items-center gap-1">
                    <span>{{ __('Tất cả') }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                          :class="assignmentFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                          x-text="selectedAssignment?.students?.length || 0"></span>
                </button>

                <button type="button" @click="assignmentFilter = 'submitted'"
                        :class="assignmentFilter === 'submitted' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                        class="px-3 py-1 rounded-lg text-xs transition-colors cursor-pointer flex items-center gap-1">
                    <span>{{ __('Đã nộp') }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                          :class="assignmentFilter === 'submitted' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                          x-text="assignmentSubmittedCount"></span>
                </button>

                <button type="button" @click="assignmentFilter = 'unsubmitted'"
                        :class="assignmentFilter === 'unsubmitted' ? 'bg-rose-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-medium'"
                        class="px-3 py-1 rounded-lg text-xs transition-colors cursor-pointer flex items-center gap-1">
                    <span>{{ __('Chưa nộp') }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                          :class="assignmentFilter === 'unsubmitted' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                          x-text="assignmentUnsubmittedCount"></span>
                </button>
            </div>

            {{-- Modal Body: Danh sách học viên --}}
            <div class="p-5 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800 flex-1">
                <template x-for="student in filteredAssignmentStudents" :key="student.id">
                    <div class="py-3 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <template x-if="student.avatar_url">
                                <img :src="student.avatar_url" :alt="student.name" class="size-9 rounded-xl object-cover shadow-2xs shrink-0">
                            </template>
                            <template x-if="!student.avatar_url">
                                <div class="size-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0" x-text="student.first_char"></div>
                            </template>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate" x-text="student.name"></p>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5" x-text="student.email"></p>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            <template x-if="student.has_submitted">
                                <div class="flex flex-col items-end gap-0.5">
                                    <template x-if="student.is_graded">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                            <span x-text="student.score !== null ? student.score + '/10' : '{{ __('Đã chấm') }}'"></span>
                                        </span>
                                    </template>
                                    <template x-if="student.is_pending">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 text-xs font-bold">
                                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>{{ __('Chờ chấm') }}</span>
                                        </span>
                                    </template>
                                    <template x-if="!student.is_graded && !student.is_pending">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                                            <span class="material-symbols-outlined text-[13px]">done</span>
                                            <span>{{ __('Đã nộp') }}</span>
                                        </span>
                                    </template>
                                    <span x-show="student.submitted_at" class="text-[10px] text-slate-400" x-text="student.submitted_at"></span>
                                </div>
                            </template>
                            <template x-if="!student.has_submitted">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-xs font-semibold">
                                    <span class="material-symbols-outlined text-[13px]">schedule</span>
                                    {{ __('Chưa nộp') }}
                                </span>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="filteredAssignmentStudents.length === 0" class="py-8 text-center text-slate-400 text-xs">
                    {{ __('Không có học viên nào trong mục này.') }}
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex items-center justify-between">
                <a :href="selectedAssignment?.url"
                   class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline">
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                    <span>{{ __('Xem chi tiết bài tập & chấm điểm') }}</span>
                </a>
                <button type="button" @click="showAssignmentModal = false"
                        class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                    {{ __('Đóng') }}
                </button>
            </div>

        </div>
    </div>

    {{-- MODAL: Chi tiết kết quả bài thi HSK --}}
    <div x-show="showHskModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-cloak>

        <div @click.away="showHskModal = false"
             x-show="showHskModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-6xl w-full overflow-hidden flex flex-col h-[90vh]">

            {{-- Modal Header --}}
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <template x-if="selectedHskResult?.student_avatar">
                        <img :src="selectedHskResult.student_avatar" :alt="selectedHskResult.student_name" class="size-9 rounded-xl object-cover shadow-2xs shrink-0">
                    </template>
                    <template x-if="!selectedHskResult?.student_avatar">
                        <div class="size-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0" x-text="selectedHskResult?.student_first_char"></div>
                    </template>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate" x-text="selectedHskResult?.student_name"></h3>
                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-orange-100 dark:bg-orange-950/40 text-orange-700 dark:text-orange-300 shrink-0" x-text="selectedHskResult?.exam_level"></span>
                        </div>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5" x-text="selectedHskResult?.exam_title"></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a :href="selectedHskResult?.full_result_url"
                       target="_blank"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition-colors cursor-pointer"
                       title="{{ __('Mở trong tab mới') }}">
                        <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                        <span class="hidden sm:inline">{{ __('Mở tab mới') }}</span>
                    </a>
                    <button type="button" @click="showHskModal = false"
                            class="size-8 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
            </div>

            {{-- Modal Body: Nhúng trực tiếp toàn bộ trang chi tiết câu hỏi & câu trả lời --}}
            <div class="flex-1 w-full h-full overflow-hidden relative bg-[#f8f6f3] dark:bg-[#0e0c0b]">
                <template x-if="showHskModal && selectedHskResult?.full_result_url">
                    <iframe :src="selectedHskResult.full_result_url + (selectedHskResult.full_result_url.includes('?') ? '&' : '?') + 'embed=1'"
                            class="w-full h-full border-0"
                            loading="lazy"></iframe>
                </template>
            </div>

        </div>
    </div>

</div>
