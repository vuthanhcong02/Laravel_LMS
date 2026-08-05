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
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-yellow-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(234,179,8,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-yellow-500">1</span>
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
                                <span class="flex items-center gap-1.5 text-yellow-500"><span
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
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-teal-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(20,184,166,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-teal-500">2</span>
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
                                <span class="flex items-center gap-1.5 text-teal-500"><span
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
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-red-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(239,68,68,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-red-500">3</span>
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
                                <span class="flex items-center gap-1.5 text-red-500"><span
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
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-purple-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(168,85,247,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-purple-500">4</span>
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
                                <span class="flex items-center gap-1.5 text-purple-500"><span
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
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-pink-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(236,72,153,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-pink-500">5</span>
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
                                <span class="flex items-center gap-1.5 text-pink-500"><span
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
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-blue-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(59,130,246,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-blue-500">6</span>
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
                                <span class="flex items-center gap-1.5 text-blue-500"><span
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
                            <form action="{{ route('student.hsk-mock-exams.index') }}" method="GET" class="m-0">
                                <select name="leaderboard_level" onchange="this.form.submit()" class="text-xs font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 outline-none cursor-pointer focus:border-primary hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <option value="all" {{ ($leaderboardLevel ?? 'all') == 'all' ? 'selected' : '' }}>Tất cả cấp độ</option>
                                    @if(isset($hskLevels))
                                        @foreach($hskLevels as $l)
                                            <option value="{{ $l->level_code }}" {{ ($leaderboardLevel ?? '') == $l->level_code ? 'selected' : '' }}>{{ strtoupper($l->level_code) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </form>
                        </div>

                        <div class="p-2 space-y-1">
                            @if(isset($leaderboard) && $leaderboard->isNotEmpty())
                                @foreach($leaderboard as $index => $result)
                                    <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ auth()->check() && auth()->id() == $result->user_id ? 'bg-primary/5 border border-primary/10' : '' }}">
                                        @if($index == 0)
                                            <div class="w-6 text-center font-black text-amber-500 text-sm">1</div>
                                        @elseif($index == 1)
                                            <div class="w-6 text-center font-black text-slate-400 text-sm">2</div>
                                        @elseif($index == 2)
                                            <div class="w-6 text-center font-black text-amber-700 text-sm">3</div>
                                        @else
                                            <div class="w-6 text-center font-bold text-slate-500 text-xs">{{ $index + 1 }}</div>
                                        @endif
                                        
                                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex-shrink-0 border border-slate-300 dark:border-slate-600 flex items-center justify-center overflow-hidden">
                                            @if($result->user && $result->user->avatar)
                                                <img src="{{ Storage::url($result->user->avatar) }}" alt="{{ $result->user->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs font-bold text-slate-500">{{ substr($result->user->name ?? '?', 0, 1) }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold {{ auth()->check() && auth()->id() == $result->user_id ? 'text-primary' : 'text-slate-800 dark:text-white' }} truncate">
                                                {{ auth()->check() && auth()->id() == $result->user_id ? 'Bạn' : ($result->user->name ?? 'Người dùng') }}
                                            </p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                                {{ $result->mockExam && $result->mockExam->hskLevel ? strtoupper($result->mockExam->hskLevel->level_code) : 'HSK' }}
                                            </p>
                                        </div>
                                        
                                        <div class="text-right">
                                            <p class="text-sm font-black {{ auth()->check() && auth()->id() == $result->user_id ? 'text-primary' : 'text-emerald-500' }}">
                                                {{ $result->total_score }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-8 text-center">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 italic">Chưa có ai hoàn thành đề thi nào.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
