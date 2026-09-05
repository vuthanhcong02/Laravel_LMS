@extends('layouts.app')
@section('title', __('HSK Cấp ' . $level . ' - Thi Thử'))
@section('breadcrumb', 'Danh sách đề thi HSK ' . $level)
@section('breadcrumb_desc', 'Chọn đề thi phù hợp và bắt đầu kiểm tra năng lực của bạn. Hệ thống sẽ tự động chấm điểm và đưa ra phân tích sau khi bạn nộp bài.')
@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 font-sans relative pb-24 pt-8">
    <div class="max-w-7xl mx-auto px-6 space-y-8 relative z-10">
        {{-- Back Button & Filters --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('student.hsk-mock-exams.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition-colors bg-white dark:bg-slate-800/60 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 w-fit shadow-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Quay lại các cấp độ
            </a>
            <div class="flex items-center gap-2">
                <select id="exam-status-filter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary/20 outline-none shadow-sm cursor-pointer">
                    <option value="all">Tất cả đề thi</option>
                    <option value="completed">Đã hoàn thành</option>
                    <option value="uncompleted">Chưa làm</option>
                </select>
            </div>
        </div>
        {{-- 2-Column Layout: Structure Explanation (Left) & Exam List (Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-12 gap-8">
            {{-- LEFT COLUMN: EXAM STRUCTURE --}}
            <div class="lg:col-span-1 xl:col-span-4 space-y-6">
                <div class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm sticky top-24">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">info</span>
                        Cấu trúc đề thi HSK {{ $level }}
                    </h3>
                    @if($hskLevel->exam_structure)
                        @php $structure = $hskLevel->exam_structure; @endphp
                        @if(isset($structure['note']))
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-6 bg-primary/5 p-3 rounded-xl border border-primary/10">
                            {!! nl2br(e($structure['note'])) !!}
                        </p>
                        @endif
                        <div class="space-y-6">
                            @foreach($structure['sections'] ?? [] as $section)
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 flex items-center gap-2 border-b border-slate-100 dark:border-slate-700/50 pb-2">
                                        @if(stripos($section['title'], 'Nghe') !== false)
                                            <span class="material-symbols-outlined text-sky-500 text-[18px]">headphones</span>
                                        @elseif(stripos($section['title'], 'Đọc') !== false)
                                            <span class="material-symbols-outlined text-amber-500 text-[18px]">menu_book</span>
                                        @elseif(stripos($section['title'], 'Viết') !== false)
                                            <span class="material-symbols-outlined text-emerald-500 text-[18px]">edit_document</span>
                                        @else
                                            <span class="material-symbols-outlined text-primary text-[18px]">quiz</span>
                                        @endif
                                        {{ $section['title'] }} ({{ $section['total_questions'] }} Câu)
                                    </h4>
                                    <div class="space-y-4">
                                        @foreach($section['parts'] ?? [] as $part)
                                            <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $part['name'] }}</span>
                                                    <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">{{ $part['questions'] }} câu</span>
                                                </div>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $part['description'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400 italic text-center py-10">Cấu trúc đề thi đang được cập nhật...</p>
                    @endif
                </div>
            </div>
            {{-- RIGHT COLUMN: EXAM LIST --}}
            <div class="lg:col-span-2 xl:col-span-8 space-y-6">
                @if($hskLevel->mockExams->isEmpty())
                    <div class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-10 flex flex-col items-center justify-center text-center shadow-sm h-[400px]">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/80 rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700">
                            <span class="material-symbols-outlined text-slate-300 dark:text-slate-500 text-4xl">inventory_2</span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Chưa có đề thi nào</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm">Hệ thống đang cập nhật đề thi cho cấp độ này. Vui lòng quay lại sau nhé!</p>
                    </div>
                @else
                    {{-- Exam Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-5">
                        @foreach($hskLevel->mockExams as $exam)
                            @php
                                $statusText = 'Chưa làm';
                                $statusClass = 'bg-slate-100 dark:bg-slate-700 text-slate-500';
                                $highestScore = null;
                                $userResults = $exam->results;
                                if ($userResults->isNotEmpty()) {
                                    $completedResults = $userResults->where('status', 'completed');
                                    if ($completedResults->isNotEmpty()) {
                                        $statusText = 'Đã làm';
                                        $statusClass = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600';
                                        $highestScore = $completedResults->max('total_score');
                                    } elseif ($userResults->where('status', 'in_progress')->isNotEmpty()) {
                                        $statusText = 'Đang làm';
                                        $statusClass = 'bg-amber-100 dark:bg-amber-900/30 text-amber-600';
                                    }
                                }
                            @endphp
                            {{-- Exam Card (Dynamic) --}}
                            <div class="exam-card group bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 flex flex-col relative" data-status="{{ $statusText === 'Đã làm' ? 'completed' : 'uncompleted' }}" data-title="{{ strtolower($exam->title) }}">
                                <div class="p-5 flex-1 relative">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="px-2.5 py-1 {{ $statusClass }} text-[10px] font-bold uppercase tracking-wider rounded-full">
                                            {{ $statusText }}
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 group-hover:text-primary transition-colors">{{ $exam->title }}</h3>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 space-y-2 mt-4 mb-2">
                                        <div class="flex justify-between items-center">
                                            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">schedule</span> Thời gian</span>
                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $exam->duration }} Phút</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            @php
                                                $lblCode = strtolower($level);
                                                $maxPt = in_array($lblCode, ['1', '2']) ? 200 : 300;
                                            @endphp
                                            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">fact_check</span> Điểm {{ $highestScore !== null ? 'cao nhất' : 'tối đa' }}</span>
                                            <span class="font-bold text-slate-700 dark:text-slate-300">
                                                @if($highestScore !== null)
                                                    <span class="text-emerald-500">{{ $highestScore }}</span> / {{ $maxPt }}
                                                @else
                                                    {{ $maxPt }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-700/50 mt-3">
                                            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px] text-slate-400">group</span> Lượt làm</span>
                                            <span class="font-bold text-slate-500">{{ $exam->results_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/20">
                                    @auth
                                        <a href="{{ route('student.hsk-mock-exams.start', ['level' => $level, 'id' => $exam->id]) }}" class="w-full py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white text-sm font-bold shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">play_circle</span> {{ $statusText === 'Đang làm' ? 'Làm tiếp' : ($statusText === 'Đã làm' ? 'Làm lại' : 'Bắt đầu thi') }}
                                        </a>
                                    @else
                                        <button type="button" onclick="openLoginModal('{{ route('student.hsk-mock-exams.start', ['level' => $level, 'id' => $exam->id]) }}')" class="w-full py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white text-sm font-bold shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">play_circle</span> Bắt đầu thi
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Login Required Modal (Vanilla JS) --}}
    <div id="loginModal" class="hidden relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('loginModal').classList.add('hidden')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 pointer-events-none">
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md pointer-events-auto border border-slate-200 dark:border-slate-700">
                    <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400">lock</span>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white" id="modal-title">Đăng nhập để tiếp tục</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        Bạn cần đăng nhập để làm bài thi và lưu lại kết quả. Quá trình này rất nhanh chóng.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <a id="loginModalBtn" href="{{ route('login') }}" class="inline-flex w-full justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600 sm:ml-3 sm:w-auto">
                            Đăng nhập
                        </a>
                        <button type="button" onclick="document.getElementById('loginModal').classList.add('hidden')" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-900 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 sm:mt-0 sm:w-auto">
                            Để sau
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function openLoginModal(intendedUrl) {
        // Point the login button to the intended protected route
        // This leverages Laravel's auth middleware to redirect back after login
        document.getElementById('loginModalBtn').href = intendedUrl;
        document.getElementById('loginModal').classList.remove('hidden');
    }
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('exam-status-filter');
        const cards = document.querySelectorAll('.exam-card');
        function filterExams() {
            const status = statusSelect.value;
            cards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                const matchStatus = status === 'all' || 
                                    (status === 'completed' && cardStatus === 'completed') ||
                                    (status === 'uncompleted' && cardStatus !== 'completed');
                if (matchStatus) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        if(statusSelect) {
            statusSelect.addEventListener('change', filterExams);
        }
    });
</script>
@endsection
