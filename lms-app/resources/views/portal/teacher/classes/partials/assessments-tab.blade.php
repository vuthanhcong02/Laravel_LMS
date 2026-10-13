{{-- resources/views/portal/teacher/classes/partials/assessments-tab.blade.php --}}
<div x-data="{ subTab: 'assignments' }" class="space-y-6">

    {{-- Thống kê tổng quan đánh giá của lớp --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Bài tập --}}
        <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {{ __('Bài tập đã giao') }}
                </span>
                <div class="size-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">assignment</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $assessmentStats['total_assignments'] ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">{{ __('bài tập') }}</span>
            </div>
            @if(($assessmentStats['pending_submissions_count'] ?? 0) > 0)
                <p class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px]">pending</span>
                    <span>{{ __(':count bài chờ chấm điểm', ['count' => $assessmentStats['pending_submissions_count']]) }}</span>
                </p>
            @else
                <p class="text-[11px] text-slate-400 font-medium">{{ __('Đã chấm toàn bộ bài nộp') }}</p>
            @endif
        </div>

        {{-- Card 2: Bài kiểm tra --}}
        <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {{ __('Bài kiểm tra') }}
                </span>
                <div class="size-8 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">quiz</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $assessmentStats['total_quizzes'] ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">{{ __('bài thi') }}</span>
            </div>
            <p class="text-[11px] text-slate-400 font-medium">
                {{ __('Gắn liền theo chương trình học') }}
            </p>
        </div>

        {{-- Card 3: Điểm TB Quiz --}}
        <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {{ __('Điểm TB Kiểm tra') }}
                </span>
                <div class="size-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">trending_up</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900 dark:text-white">
                    {{ $assessmentStats['avg_quiz_score'] !== null ? $assessmentStats['avg_quiz_score'] . '/10' : __('Chưa có') }}
                </span>
            </div>
            <p class="text-[11px] text-slate-400 font-medium">
                {{ __('Điểm trung bình các lượt thi') }}
            </p>
        </div>

        {{-- Card 4: Đề thi HSK --}}
        <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {{ __('Lượt thi thử HSK') }}
                </span>
                <div class="size-8 rounded-lg bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">workspace_premium</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $assessmentStats['total_hsk_attempts'] ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">{{ __('lượt hoàn thành') }}</span>
            </div>
            <p class="text-[11px] text-slate-400 font-medium">
                {{ __('Học viên lớp thi thử HSK') }}
            </p>
        </div>
    </div>

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
                                        <div class="flex flex-col gap-0.5">
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $assignment->due_date->isPast() ? 'text-rose-500' : 'text-slate-700 dark:text-slate-300' }}">
                                                <span class="material-symbols-outlined text-[13px]">event</span>
                                                {{ $assignment->due_date->format('d/m/Y H:i') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">
                                                {{ $assignment->due_date->isPast() ? __('Đã hết hạn') : $assignment->due_date->diffForHumans() }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">{{ __('Không giới hạn') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="inline-flex flex-col items-center gap-1 min-w-[90px]">
                                        <span class="text-xs font-bold text-slate-900 dark:text-white">
                                            {{ $submittedCount }}/{{ $class->enrollments_count }}
                                        </span>
                                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-primary h-full rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
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
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Lượt làm bài') }}</th>
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
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-xs font-bold">
                                        <span class="material-symbols-outlined text-[14px] text-primary">groups</span>
                                        {{ $quiz->attempts_count }} {{ __('lượt') }}
                                    </span>
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
                                        <div class="flex flex-col gap-0.5">
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $quiz->due_date->isPast() ? 'text-rose-500' : 'text-slate-700 dark:text-slate-300' }}">
                                                <span class="material-symbols-outlined text-[13px]">event</span>
                                                {{ $quiz->due_date->format('d/m/Y H:i') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">
                                                {{ $quiz->due_date->isPast() ? __('Đã kết thúc') : $quiz->due_date->diffForHumans() }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">{{ __('Không giới hạn') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right space-x-1 whitespace-nowrap">
                                    <a href="{{ route('teacher.quizzes.results', $quiz->id) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 hover:bg-orange-500 text-orange-600 hover:text-white dark:bg-orange-950/40 dark:hover:bg-orange-600 transition-colors rounded-lg font-bold text-xs"
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
                                $passScore = $exam->pass_score ?? 180;
                                $isPassed = ($result->total_score ?? 0) >= $passScore;
                            @endphp
                            <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($result->user->avatar)
                                            <img src="{{ $result->user->avatar_url }}" alt="{{ $result->user->first_name }}" class="size-8 rounded-lg object-cover shadow-xs">
                                        @else
                                            <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                                {{ substr($result->user->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm">
                                                {{ $result->user->first_name }} {{ $result->user->last_name }}
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
                                                {{ $exam->hskLevel->name }}
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
                                        {{ $result->total_score ?? 0 }}/{{ $exam->total_score ?? 300 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($isPassed)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                            {{ __('Đạt chuẩn') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[13px]">cancel</span>
                                            {{ __('Chưa đạt') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-xs text-slate-500 font-normal whitespace-nowrap">
                                    {{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : '--' }}
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    @if($result->uuid)
                                        <a href="{{ route('student.hsk-mock-exams.result', $result->uuid) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-primary text-slate-700 hover:text-white dark:bg-slate-800 dark:hover:bg-primary dark:text-slate-300 transition-colors rounded-lg font-bold text-xs"
                                           title="{{ __('Xem chi tiết bài làm') }}">
                                            <span>{{ __('Chi tiết') }}</span>
                                            <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                        </a>
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

</div>
