@extends('portal.layouts.dashboard')

@section('title', __('Kết quả bài thi') . ' - ' . $quiz->title)

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
@php
    $initialData = [
        'students'   => $student_results,
        'totalMarks' => (float) $total_marks,
        'quizTitle'  => $quiz->title,
    ];
@endphp
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full"
      x-data="teacherQuizResults({!! htmlspecialchars(json_encode($initialData, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') !!})">
    <div class="max-w-[1400px] mx-auto space-y-6">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    <a href="{{ route('teacher.quizzes.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">assignment</span>
                        <span>{{ __('Quản lý bài thi') }}</span>
                    </a>
                    <span>/</span>
                    <span class="text-slate-900 dark:text-white truncate max-w-[200px] sm:max-w-[300px]">{{ $quiz->title }}</span>
                    <span>/</span>
                    <span class="text-primary">{{ __('Thống kê & Kết quả') }}</span>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('teacher.quizzes.index') }}"
                       class="size-9 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-primary hover:border-primary/40 flex items-center justify-center transition-all shadow-xs shrink-0">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>{{ $quiz->title }}</span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 flex items-center gap-2 mt-0.5">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $quiz->course->title ?? __('N/A') }}</span>
                            <span>•</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $quiz->type->value === 'mixed' ? 'bg-purple-100 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300' : ($quiz->type->value === 'essay' ? 'bg-orange-100 dark:bg-orange-950/50 text-orange-700 dark:text-orange-300' : 'bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300') }}">
                                {{ $quiz->type->label() }}
                            </span>
                            <span>•</span>
                            <span>{{ $quiz->time_limit > 0 ? $quiz->time_limit . ' ' . __('phút') : __('Không giới hạn') }}</span>
                            <span>•</span>
                            <span class="font-semibold text-primary">{{ $total_marks }} {{ __('điểm tối đa') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2.5 self-end md:self-auto">
                <a href="{{ route('teacher.quizzes.questions', $quiz->id) }}"
                   class="px-4 py-2.5 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                    <span class="material-symbols-outlined text-base">list_alt</span>
                    <span>{{ __('Quản lý câu hỏi') }}</span>
                </a>
                <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}"
                   class="px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm active:scale-[0.98]">
                    <span class="material-symbols-outlined text-base">edit</span>
                    <span>{{ __('Sửa bài thi') }}</span>
                </a>
            </div>
        </div>

        @if($quiz->audio_url)
        <div class="p-4 rounded-2xl bg-amber-500/10 dark:bg-amber-900/20 border border-amber-500/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="size-8 rounded-xl bg-primary text-white flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-base">headphones</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span>{{ __('File Audio Toàn Bài Thi') }}</span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-primary/20 text-primary font-extrabold">{{ __('MP3') }}</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('File nghe chung phát xuyên suốt bài kiểm tra.') }}</p>
                </div>
            </div>
            <audio controls class="h-8 max-w-sm w-full" preload="none">
                <source src="{{ $quiz->audio_url }}" type="audio/mpeg">
                {{ __('Trình duyệt không hỗ trợ phát audio.') }}
            </audio>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Tiến độ nộp bài') }}</span>
                    <span class="size-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">fact_check</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['submitted_count'] }}</span>
                        <span class="text-xs font-semibold text-slate-400">/ {{ $stats['total_students'] }} {{ __('học viên') }}</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full mt-3 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $stats['submission_rate'] >= 80 ? 'bg-emerald-500' : ($stats['submission_rate'] >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}"
                             style="width: {{ $stats['submission_rate'] }}%"></div>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-2 flex items-center justify-between">
                        <span>{{ __('Tỉ lệ nộp bài:') }}</span>
                        <strong class="text-slate-900 dark:text-white">{{ $stats['submission_rate'] }}%</strong>
                    </p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Điểm trung bình') }}</span>
                    <span class="size-9 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">analytics</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['average_score'] }}</span>
                        <span class="text-xs font-semibold text-slate-400">/ {{ $total_marks }} {{ __('điểm') }}</span>
                    </div>
                    <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 mt-3 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">equalizer</span>
                        <span>{{ __('Thang điểm bài thi:') }} {{ $total_marks }}</span>
                    </p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Cao nhất / Thấp nhất') }}</span>
                    <span class="size-9 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">emoji_events</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-600 uppercase">{{ __('Cao nhất') }}</span>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['submitted_count'] > 0 ? $stats['highest_score'] : '-' }}</p>
                        </div>
                        <div class="h-8 w-px bg-slate-200 dark:bg-slate-800"></div>
                        <div>
                            <span class="text-[11px] font-bold text-rose-500 uppercase">{{ __('Thấp nhất') }}</span>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['submitted_count'] > 0 ? $stats['lowest_score'] : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Tỉ lệ đạt (≥ 50%)') }}</span>
                    <span class="size-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">verified</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['pass_rate'] }}%</span>
                        <span class="text-xs font-semibold text-slate-400">({{ $stats['passed_count'] }}/{{ $stats['submitted_count'] }} {{ __('đạt') }})</span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-3 flex items-center justify-between">
                        <span>{{ __('Chưa nộp bài:') }}</span>
                        <strong class="text-amber-500">{{ $stats['not_started_count'] + $stats['in_progress_count'] }} {{ __('học viên') }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

            <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div class="relative w-full sm:w-80">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="{{ __('Tìm tên, email học viên...') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-slate-900 dark:text-white">
                </div>

                <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-800/60 rounded-xl">
                    <button type="button"
                            @click="statusFilter = 'all'"
                            :class="statusFilter === 'all' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 font-medium'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer">
                        {{ __('Tất cả') }} (<span x-text="students.length"></span>)
                    </button>
                    <button type="button"
                            @click="statusFilter = 'completed'"
                            :class="statusFilter === 'completed' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-emerald-600 font-medium'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer">
                        {{ __('Đã nộp bài') }} (<span x-text="students.filter(s => s.status === 'completed').length"></span>)
                    </button>
                    <button type="button"
                            @click="statusFilter = 'not_started'"
                            :class="statusFilter === 'not_started' ? 'bg-white dark:bg-slate-700 text-rose-500 shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-rose-500 font-medium'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer">
                        {{ __('Chưa nộp bài') }} (<span x-text="students.filter(s => s.status === 'not_started').length"></span>)
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Học viên') }}</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Trạng thái') }}</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Điểm số') }}</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Thời gian nộp') }}</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Số lượt làm') }}</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">{{ __('Thao tác') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs sm:text-sm">
                        <template x-for="student in filteredStudents" :key="student.student_id">
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img :src="student.avatar_url"
                                             :alt="student.name"
                                             class="size-10 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 bg-slate-100 shrink-0">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-slate-900 dark:text-white truncate" x-text="student.name"></p>
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-primary/10 text-primary border border-primary/20" x-text="student.level_badge"></span>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="student.email"></p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <template x-if="student.status === 'completed'">
                                        <div>
                                            <template x-if="student.grading_status === 'needs_grading'">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                    </span>
                                                    <span>{{ __('Cần chấm tự luận') }}</span>
                                                </span>
                                            </template>
                                            <template x-if="student.grading_status !== 'needs_grading'">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">
                                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                                    <span>{{ __('Đã hoàn thành') }}</span>
                                                </span>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="student.status === 'in_progress'">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-900">
                                            <span class="material-symbols-outlined text-sm animate-spin">sync</span>
                                            <span>{{ __('Đang làm bài') }}</span>
                                        </span>
                                    </template>
                                    <template x-if="student.status === 'not_started'">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            <span class="material-symbols-outlined text-sm">cancel</span>
                                            <span>{{ __('Chưa nộp bài') }}</span>
                                        </span>
                                    </template>
                                </td>

                                <td class="px-6 py-4">
                                    <template x-if="student.status === 'completed'">
                                        <div class="space-y-1">
                                            <div class="flex items-baseline gap-1.5">
                                                <span class="font-bold text-base"
                                                      :class="student.percentage >= 50 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500'"
                                                      x-text="student.score"></span>
                                                <span class="text-xs text-slate-400" x-text="'/ ' + student.total_marks"></span>
                                                <span class="text-[11px] font-bold px-1.5 py-0.2 rounded"
                                                      :class="student.percentage >= 50 ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600' : 'bg-rose-50 dark:bg-rose-950/50 text-rose-500'"
                                                      x-text="student.percentage + '%'"></span>
                                            </div>
                                            <div class="w-24 bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full"
                                                     :class="student.percentage >= 50 ? 'bg-emerald-500' : 'bg-rose-500'"
                                                     :style="'width: ' + student.percentage + '%'"></div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="student.status !== 'completed'">
                                        <span class="text-slate-400 italic text-xs">{{ __('Chưa có điểm') }}</span>
                                    </template>
                                </td>

                                <td class="px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    <template x-if="student.completed_at">
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white" x-text="new Date(student.completed_at).toLocaleString('vi-VN')"></p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400" x-text="'Thời gian: ' + student.duration_minutes + ' phút'"></p>
                                        </div>
                                    </template>
                                    <template x-if="!student.completed_at">
                                        <span class="text-slate-400 italic">{{ __('Chưa nộp') }}</span>
                                    </template>
                                </td>

                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                    <span x-text="student.attempts_count + ' ' + '{{ __('lượt') }}'"></span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <template x-if="student.attempt_id">
                                        <button type="button"
                                                @click="openAttemptDetail(student)"
                                                class="px-3 py-1.5 rounded-lg bg-primary/10 hover:bg-primary text-primary hover:text-white transition-all text-xs font-bold inline-flex items-center gap-1 cursor-pointer">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                            <span>{{ __('Xem bài làm') }}</span>
                                        </button>
                                    </template>
                                    <template x-if="!student.attempt_id">
                                        <span class="text-slate-400 text-xs italic">{{ __('Chưa có bài') }}</span>
                                    </template>
                                </td>
                            </tr>
                        </template>

                        <template x-if="filteredStudents.length === 0">
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">person_search</span>
                                    <p>{{ __('Không tìm thấy học viên nào phù hợp.') }}</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div x-show="showDetailModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-cloak>

        <div @click.away="closeDetailModal()"
             class="bg-white dark:bg-slate-900 w-full max-w-3xl max-h-[90vh] rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col">

            <div class="p-5 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/80 dark:bg-slate-800/40 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                        <span class="material-symbols-outlined text-xl">assignment_turned_in</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>{{ __('Chi tiết bài làm:') }}</span>
                            <span class="text-primary" x-text="currentAttemptDetail?.student?.full_name"></span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="quizTitle"></p>
                    </div>
                </div>
                <button type="button" @click="closeDetailModal()"
                        class="size-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <div class="p-5 sm:p-6 space-y-6 overflow-y-auto flex-1">

                <div x-show="isLoadingDetail" class="py-16 text-center space-y-3">
                    <div class="animate-spin size-8 border-3 border-primary border-t-transparent rounded-full mx-auto"></div>
                    <p class="text-xs font-semibold text-slate-500">{{ __('Đang tải nội dung bài làm...') }}</p>
                </div>

                <div x-show="detailError" class="p-4 rounded-xl bg-rose-50 text-rose-600 text-xs font-bold" x-text="detailError"></div>

                <div x-show="!isLoadingDetail && currentAttemptDetail" class="space-y-6">

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">{{ __('Điểm số') }}</span>
                            <p class="text-xl font-bold text-primary mt-0.5" x-text="currentAttemptDetail?.score + ' / ' + currentAttemptDetail?.total_marks"></p>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">{{ __('Tỉ lệ đạt') }}</span>
                            <p class="text-xl font-bold text-emerald-600 mt-0.5" x-text="currentAttemptDetail?.percentage + '%'"></p>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">{{ __('Thời lượng') }}</span>
                            <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5" x-text="currentAttemptDetail?.duration_minutes + ' phút'"></p>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">{{ __('Nộp lúc') }}</span>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-2" x-text="currentAttemptDetail?.completed_at ? new Date(currentAttemptDetail.completed_at).toLocaleString('vi-VN') : '-'"></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">{{ __('Danh sách câu trả lời') }}</h4>

                        <template x-for="(q, idx) in currentAttemptDetail?.questions" :key="q.id">
                            <div class="p-4 rounded-xl border transition-all"
                                 :class="q.type === 'essay' ? 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30' : (q.is_correct ? 'border-emerald-200 dark:border-emerald-900 bg-emerald-50/30 dark:bg-emerald-950/20' : 'border-rose-200 dark:border-rose-900 bg-rose-50/30 dark:bg-rose-950/20')">

                                <div class="flex items-start justify-between gap-3 mb-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md font-bold text-xs"
                                              :class="q.type === 'essay' ? 'bg-purple-100 text-purple-700' : (q.is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700')"
                                              x-text="'Câu ' + (idx + 1)"></span>
                                        <span class="text-xs font-bold text-slate-500" x-text="'(' + q.type_label + ' - ' + q.marks + ' điểm)'"></span>
                                    </div>
                                    <template x-if="q.type !== 'essay'">
                                        <span class="text-xs font-bold flex items-center gap-1"
                                              :class="q.is_correct ? 'text-emerald-600' : 'text-rose-500'">
                                            <span class="material-symbols-outlined text-base" x-text="q.is_correct ? 'check_circle' : 'cancel'"></span>
                                            <span x-text="q.is_correct ? '{{ __('Đúng') }}' : '{{ __('Sai') }}'"></span>
                                        </span>
                                    </template>
                                </div>

                                <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mb-3 leading-relaxed" x-text="q.question_text"></p>

                                <template x-if="q.image_url">
                                    <img :src="q.image_url" class="max-h-48 rounded-lg mb-3 object-contain border border-slate-200 dark:border-slate-700">
                                </template>
                                <template x-if="q.audio_url">
                                    <audio controls :src="q.audio_url" class="w-full mb-3"></audio>
                                </template>

                                <template x-if="q.type === 'multiple_choice' || q.type === 'true_false'">
                                    <div class="space-y-2 mt-2">
                                        <template x-for="opt in q.options" :key="opt.id">
                                            <div class="p-2.5 rounded-lg border text-xs flex items-center justify-between gap-2"
                                                 :class="{
                                                     'bg-emerald-100/70 border-emerald-400 text-emerald-900 font-bold': opt.is_correct && opt.is_selected,
                                                     'bg-emerald-50 border-emerald-300 text-emerald-800 font-semibold': opt.is_correct && !opt.is_selected,
                                                     'bg-rose-100/70 border-rose-400 text-rose-900 font-bold': !opt.is_correct && opt.is_selected,
                                                     'bg-white dark:bg-slate-800/80 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300': !opt.is_correct && !opt.is_selected
                                                 }">
                                                <div class="flex items-center gap-2">
                                                    <span class="size-4 rounded-full border flex items-center justify-center text-[10px]"
                                                          :class="opt.is_selected ? 'bg-primary border-primary text-white' : 'border-slate-300'">
                                                        <span x-show="opt.is_selected">✓</span>
                                                    </span>
                                                    <span x-text="opt.option_text"></span>
                                                </div>
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <span x-show="opt.is_selected" class="text-[10px] font-bold px-1.5 py-0.2 rounded bg-slate-900/10 text-slate-700">
                                                        {{ __('Học viên chọn') }}
                                                    </span>
                                                    <span x-show="opt.is_correct" class="text-[10px] font-bold px-1.5 py-0.2 rounded bg-emerald-600 text-white">
                                                        {{ __('Đáp án đúng') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="q.type === 'essay'">
                                    <div class="mt-3 space-y-3">

                                        <div class="space-y-1">
                                            <span class="text-[11px] font-bold text-slate-500 uppercase flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">article</span>
                                                {{ __('Bài làm của học viên:') }}
                                            </span>
                                            <div class="p-3.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs sm:text-sm text-slate-900 dark:text-white whitespace-pre-line leading-relaxed font-medium"
                                                 x-text="q.text_answer || '{{ __('(Học viên không nhập nội dung trả lời)') }}'"></div>
                                        </div>

                                        <template x-if="q.essay_grading_type === 'auto'">
                                            <div class="p-3 rounded-xl bg-blue-50/50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/50 text-xs space-y-1.5">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-blue-900 dark:text-blue-300 flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-sm">smart_toy</span>
                                                        {{ __('Chế độ: Tự động chấm theo đáp án mẫu') }}
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold"
                                                          :class="q.is_correct ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                                          x-text="q.is_correct ? '{{ __('Khớp đáp án (+') }}' + q.marks + ' {{ __('điểm)') }}' : '{{ __('Không khớp (0 điểm)') }}'"></span>
                                                </div>
                                                <p class="text-slate-600 dark:text-slate-400">
                                                    <span class="font-semibold">{{ __('Đáp án chuẩn:') }}</span> <span class="font-bold text-slate-900 dark:text-white" x-text="q.correct_answer_text || '{{ __('(Chưa thiết lập)') }}'"></span>
                                                </p>
                                            </div>
                                        </template>

                                        <template x-if="q.essay_grading_type === 'manual'">
                                            <div class="p-4 rounded-xl bg-amber-50/40 dark:bg-amber-950/20 border border-amber-200/70 dark:border-amber-900/50 space-y-3">

                                                <template x-if="q.correct_answer_text">
                                                    <div class="text-xs text-amber-900 dark:text-amber-300 bg-amber-100/60 dark:bg-amber-900/30 p-2.5 rounded-lg">
                                                        <span class="font-bold">{{ __('Gợi ý / Tiêu chí chấm:') }}</span>
                                                        <span x-text="q.correct_answer_text"></span>
                                                    </div>
                                                </template>

                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                                                    <label class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-sm text-primary">edit_note</span>
                                                        {{ __('Điểm chấm cho câu này:') }}
                                                    </label>
                                                    <div class="flex items-center gap-2">
                                                        <input type="number"
                                                               x-model.number="q.marks_obtained"
                                                               min="0"
                                                               :max="q.marks"
                                                               step="0.25"
                                                               class="w-24 px-3 py-1.5 text-center font-bold text-sm rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-primary focus:ring-2 focus:ring-primary focus:border-primary shadow-xs">
                                                        <span class="text-xs font-bold text-slate-500" x-text="'/ ' + q.marks + ' {{ __('điểm') }}'"></span>
                                                    </div>
                                                </div>

                                                <div class="space-y-1">
                                                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('Nhận xét / Lời phê của giáo viên:') }}</label>
                                                    <textarea x-model="q.teacher_feedback"
                                                              rows="2"
                                                              placeholder="{{ __('Nhập lời phê, góp ý về câu từ, ngữ pháp cho học viên...') }}"
                                                              class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary font-medium"></textarea>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/60 flex items-center justify-between shrink-0">
                <div>
                    <template x-if="gradeSuccessMessage">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 animate-pulse">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <span x-text="gradeSuccessMessage"></span>
                        </span>
                    </template>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="closeDetailModal()"
                            class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold transition-colors cursor-pointer">
                        {{ __('Đóng') }}
                    </button>
                    <template x-if="currentAttemptDetail?.questions?.some(q => q.type === 'essay' && q.essay_grading_type === 'manual')">
                        <button type="button"
                                @click="submitEssayGrades()"
                                :disabled="isSubmittingGrade"
                                class="px-5 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
                            <span x-show="isSubmittingGrade" class="material-symbols-outlined text-sm animate-spin">sync</span>
                            <span x-show="!isSubmittingGrade" class="material-symbols-outlined text-sm">save</span>
                            <span x-text="isSubmittingGrade ? '{{ __('Đang lưu...') }}' : '{{ __('Lưu kết quả chấm') }}'"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
