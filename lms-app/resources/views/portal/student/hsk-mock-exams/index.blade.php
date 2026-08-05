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

                        {{-- HSK 1 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 1]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 dark:hover:shadow-emerald-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-emerald-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-emerald-500">1</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Cơ
                                    Bản</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 1</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Kiểm tra
                                khả năng hiểu và sử dụng từ vựng tiếng Trung đơn giản nhất.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5 text-emerald-500"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> 15 Đề</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 40 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 40 Câu</span>
                            </div>
                        </a>

                        {{-- HSK 2 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 2]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-sky-500/10 dark:hover:shadow-sky-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-sky-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(14,165,233,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-sky-500">2</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-sky-500/10 text-sky-600 dark:text-sky-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Sơ
                                    Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 2</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Kiểm tra
                                khả năng giao tiếp cơ bản trong các tình huống hàng ngày.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5 text-sky-500"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> 12 Đề</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 55 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 60 Câu</span>
                            </div>
                        </a>

                        {{-- HSK 3 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 3]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 dark:hover:shadow-amber-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-amber-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(245,158,11,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-amber-500">3</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Trung
                                    Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 3</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Yêu cầu
                                khả năng giao tiếp cơ bản trong học tập, công việc và đời sống.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5 text-amber-500"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> 18 Đề</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 90 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 80 Câu</span>
                            </div>
                        </a>

                        {{-- HSK 4 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 4]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-red-500/10 dark:hover:shadow-red-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-red-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(239,68,68,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-red-500">4</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-red-500/10 text-red-600 dark:text-red-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Trung
                                    - Cao Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 4</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Thảo
                                luận về nhiều chủ đề đa dạng và giao tiếp lưu loát với người bản xứ.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5 text-red-500"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> 20 Đề</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 105 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 100 Câu</span>
                            </div>
                        </a>

                        {{-- HSK 5 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 5]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-violet-500/10 dark:hover:shadow-violet-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-violet-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(139,92,246,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-violet-500">5</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-violet-500/10 text-violet-600 dark:text-violet-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Cao
                                    Cấp</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 5</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Đọc báo,
                                tạp chí tiếng Trung, xem phim và diễn thuyết trôi chảy.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5 text-violet-500"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> 10 Đề</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">schedule</span> 125 Phút</span>
                                <span class="flex items-center gap-1.5"><span
                                        class="material-symbols-outlined text-[14px]">fact_check</span> 100 Câu</span>
                            </div>
                        </a>

                        {{-- HSK 6 Card --}}
                        <a href="{{ route('student.hsk-mock-exams.show', ['level' => 6]) }}"
                            class="group block bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm hover:shadow-xl hover:shadow-rose-500/10 dark:hover:shadow-rose-500/5 hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-rose-500 opacity-80 group-hover:h-1.5 group-hover:opacity-100 transition-all shadow-[0_0_10px_rgba(244,63,94,0.5)]">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/50 shadow-inner border border-slate-100 dark:border-slate-700">
                                    <span class="text-xl font-black text-rose-500">6</span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[10px] font-bold uppercase tracking-wider rounded-full">Thành
                                    Thạo</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">HSK Cấp 6</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 leading-relaxed line-clamp-2">Hiểu
                                những gì nghe và đọc được, biểu đạt quan điểm lưu loát.</p>
                            <div
                                class="flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <span class="flex items-center gap-1.5 text-rose-500"><span
                                        class="material-symbols-outlined text-[14px]">library_books</span> 8 Đề</span>
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
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">analytics</span>
                            Thống kê của bạn
                        </h2>
                    </div>

                    <div
                        class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="bg-slate-50 dark:bg-slate-800/80 rounded-xl p-3 text-center border border-slate-100 dark:border-slate-700/50">
                                <div class="text-xl font-black text-slate-800 dark:text-white">12</div>
                                <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Đề đã làm</div>
                            </div>
                            <div
                                class="bg-slate-50 dark:bg-slate-800/80 rounded-xl p-3 text-center border border-slate-100 dark:border-slate-700/50">
                                <div class="text-xl font-black text-emerald-500">280</div>
                                <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Điểm cao nhất</div>
                            </div>
                        </div>
                    </div>

                    {{-- Leaderboard --}}
                    <div
                        class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] shadow-sm overflow-hidden flex flex-col">
                        <div
                            class="p-5 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500 text-[18px]">workspace_premium</span>
                                Bảng Xếp Hạng
                            </h3>
                            <span class="text-xs font-semibold text-primary cursor-pointer hover:underline">Tuần này</span>
                        </div>

                        <div class="p-2 space-y-1">
                            {{-- Top 1 --}}
                            <div
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="w-6 text-center font-black text-amber-500 text-sm">1</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex-shrink-0 border border-slate-300 dark:border-slate-600">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800 dark:text-white truncate">Nguyễn Văn A</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">HSK 5</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-emerald-500">295</p>
                                </div>
                            </div>

                            {{-- Top 2 --}}
                            <div
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="w-6 text-center font-black text-slate-400 text-sm">2</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex-shrink-0 border border-slate-300 dark:border-slate-600">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800 dark:text-white truncate">Trần Thị B</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">HSK 4</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-emerald-500">290</p>
                                </div>
                            </div>

                            {{-- Top 3 --}}
                            <div
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="w-6 text-center font-black text-amber-700 text-sm">3</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex-shrink-0 border border-slate-300 dark:border-slate-600">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800 dark:text-white truncate">Lê Văn C</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">HSK 3</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-emerald-500">285</p>
                                </div>
                            </div>

                            {{-- Current User (Example ranking) --}}
                            <div class="mt-2 border-t border-slate-100 dark:border-slate-700/50 pt-2">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-primary/5 border border-primary/10">
                                    <div class="w-6 text-center font-bold text-slate-500 text-xs">42</div>
                                    <div
                                        class="w-8 h-8 rounded-full bg-primary/20 flex-shrink-0 border border-primary/30 flex items-center justify-center text-primary text-xs font-bold">
                                        You</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-primary truncate">Bạn</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-primary">150</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
