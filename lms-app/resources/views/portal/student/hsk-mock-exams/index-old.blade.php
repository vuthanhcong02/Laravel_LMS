@extends('layouts.app')
@section('title', __('Thi Thử HSK Miễn Phí'))
@section('breadcrumb', 'Thi Thử HSK Miễn Phí')
@section('breadcrumb_desc',
    'Đánh giá năng lực tiếng Trung của bạn với hệ thống đề thi chuẩn xác, giao diện mô phỏng
    phòng thi thật.')
@section('content')
    <div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 font-sans relative pb-24 pt-8">
        <div class="max-w-7xl mx-auto px-6 space-y-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">category</span>
                            Các cấp độ thi HSK
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @php
                            $hskCounts = [];
                            if (isset($hskLevels)) {
                                foreach($hskLevels as $l) {
                                    $hskCounts[$l->level_code] = $l->mock_exams_count;
                                }
                            }
                        @endphp
                        {{-- HSK 1 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 1]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-yellow-500/10 dark:hover:shadow-yellow-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-slate-700 dark:text-slate-300">1</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Cơ
                                    Bản</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 1</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Kiểm tra
                                khả năng hiểu và sử dụng từ vựng tiếng Trung đơn giản nhất.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> {{ !empty($hskCounts['hsk1']) ? $hskCounts['hsk1'] . ' Đề' : 'Đang cập nhật' }}</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 40 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 40 Câu</span>
                            </div>
                        </a>
                        {{-- HSK 2 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 2]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-teal-500/10 dark:hover:shadow-teal-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-slate-700 dark:text-slate-300">2</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-teal-500/10 text-teal-600 dark:text-teal-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Sơ
                                    Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 2</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Kiểm tra
                                khả năng giao tiếp cơ bản trong các tình huống hàng ngày.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> {{ !empty($hskCounts['hsk2']) ? $hskCounts['hsk2'] . ' Đề' : 'Đang cập nhật' }}</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 55 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 60 Câu</span>
                            </div>
                        </a>
                        {{-- HSK 3 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 3]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-red-500/10 dark:hover:shadow-red-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-slate-700 dark:text-slate-300">3</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-red-500/10 text-red-600 dark:text-red-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Trung
                                    Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 3</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Yêu cầu
                                khả năng giao tiếp cơ bản trong học tập, công việc và đời sống.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> {{ !empty($hskCounts['hsk3']) ? $hskCounts['hsk3'] . ' Đề' : 'Đang cập nhật' }}</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 90 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 80 Câu</span>
                            </div>
                        </a>
                        {{-- HSK 4 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 4]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 dark:hover:shadow-purple-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-slate-700 dark:text-slate-300">4</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-purple-500/10 text-purple-600 dark:text-purple-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Trung
                                    - Cao Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 4</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Thảo
                                luận về nhiều chủ đề đa dạng và giao tiếp lưu loát với người bản xứ.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> {{ !empty($hskCounts['hsk4']) ? $hskCounts['hsk4'] . ' Đề' : 'Đang cập nhật' }}</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 105 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 100 Câu</span>
                            </div>
                        </a>
                        {{-- HSK 5 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 5]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-pink-500/10 dark:hover:shadow-pink-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-slate-700 dark:text-slate-300">5</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-pink-500/10 text-pink-600 dark:text-pink-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Cao
                                    Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 5</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Đọc báo,
                                tạp chí tiếng Trung, xem phim và diễn thuyết trôi chảy.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> {{ !empty($hskCounts['hsk5']) ? $hskCounts['hsk5'] . ' Đề' : 'Đang cập nhật' }}</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 125 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 100 Câu</span>
                            </div>
                        </a>
                        {{-- HSK 6 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 6]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 dark:hover:shadow-blue-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-slate-700 dark:text-slate-300">6</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Thành
                                    Thạo</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 6</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Hiểu
                                những gì nghe và đọc được, biểu đạt quan điểm lưu loát.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> {{ !empty($hskCounts['hsk6']) ? $hskCounts['hsk6'] . ' Đề' : 'Đang cập nhật' }}</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 140 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 101 Câu</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-1 space-y-6">
                    {{-- User Stats --}}
                    @auth
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">analytics</span>
                                Thống kê của bạn
                            </h2>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="bg-slate-50 dark:bg-slate-800/80 rounded-xl p-3 text-center border border-slate-100 dark:border-slate-700/50">
                                    <div class="text-xl font-black text-slate-800 dark:text-white">{{ $completedExamsCount ?? 0 }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Đề đã làm</div>
                                </div>
                                <div
                                    class="bg-slate-50 dark:bg-slate-800/80 rounded-xl p-3 text-center border border-slate-100 dark:border-slate-700/50">
                                    <div class="text-xl font-black text-emerald-500">{{ $highestScore ?? 0 }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Điểm cao nhất</div>
                                </div>
                            </div>
                        </div>
                    @endauth
                    {{-- Spacer for guest to align with left column title --}}
                    @guest
                        <div class="h-[36px]"></div>
                    @endguest
                    {{-- Leaderboard --}}
                    <div
                        class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] shadow-sm overflow-hidden flex flex-col">
                        <div
                            class="p-5 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500 text-[18px]">workspace_premium</span>
                                Bảng Xếp Hạng
                            </h3>
                                <select id="leaderboard_level" name="leaderboard_level" onchange="fetchLeaderboard(this.value)" class="text-xs font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 outline-none cursor-pointer focus:border-primary hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <option value="all" {{ ($leaderboardLevel ?? 'all') == 'all' ? 'selected' : '' }}>Tất cả cấp độ</option>
                                    @if(isset($hskLevels))
                                        @foreach($hskLevels as $l)
                                            <option value="{{ $l->level_code }}" {{ ($leaderboardLevel ?? '') == $l->level_code ? 'selected' : '' }}>{{ strtoupper($l->level_code) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                        </div>
                        <div id="leaderboard-container" class="p-2 space-y-1">
                            @include('portal.student.hsk-mock-exams.leaderboard-list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
function fetchLeaderboard(level) {
    const container = document.getElementById('leaderboard-container');
    container.style.opacity = '0.5';
    fetch(`{{ route('student.hsk-mock-exams.index') }}?leaderboard_level=${level}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        container.innerHTML = html;
        container.style.opacity = '1';
    })
    .catch(error => {
        console.error('Error fetching leaderboard:', error);
        container.style.opacity = '1';
    });
}
</script>
@endpush
@endsection
