@extends('layouts.lms')

@section('title', 'Luyện thi HSK Online - Tiếng Trung XIAOMU LMS')

@section('custom-css')
    .lms-card:hover { border-color: #d8cebf; transform: translateY(-2px); box-shadow: 0 10px 24px -6px rgba(224, 122, 95, 0.1); }
    .dark .lms-card:hover { border-color: #383330; box-shadow: 0 10px 24px -6px rgba(0, 0, 0, 0.7); }
@endsection

@section('alpine-data')
    levelTab: 'all', 
    leaderboardFilter: 'all_time', 
    socialDockExpanded: true, 
    leaderboard: [
        { rank: 1, name: 'Trần Hoàng Nam', avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 6', score: '300/300', time: '28:15', isTop: true, badgeBg: 'bg-amber-500/10 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 border-amber-500/30' },
        { rank: 2, name: 'Lê Minh Anh', avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 5', score: '295/300', time: '31:40', isTop: true, badgeBg: 'bg-slate-300/20 text-slate-700 dark:bg-slate-400/20 dark:text-slate-300 border-slate-400/30' },
        { rank: 3, name: 'Nguyễn Văn Đức', avatar: 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 5', score: '290/300', time: '33:10', isTop: true, badgeBg: 'bg-amber-700/10 text-amber-800 dark:bg-amber-700/20 dark:text-amber-300 border-amber-700/30' },
        { rank: 4, name: 'Phạm Thu Hà', avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 4', score: '288/300', time: '34:05', isTop: false, badgeBg: 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700' },
        { rank: 5, name: 'Vũ Thành Công', avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 4', score: '285/300', time: '35:20', isTop: false, badgeBg: 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700' },
        { rank: 6, name: 'Đặng Hoàng Yến', avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 3', score: '280/300', time: '36:12', isTop: false, badgeBg: 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700' },
        { rank: 7, name: 'Ngô Tiến Dũng', avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 3', score: '278/300', time: '37:45', isTop: false, badgeBg: 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700' },
        { rank: 8, name: 'Bùi Mai Phương', avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80', level: 'HSK 2', score: '275/300', time: '38:00', isTop: false, badgeBg: 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700' }
    ],
@endsection

@section('content')
    <!-- Banner Tiêu đề Trang -->
    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-award text-[#e07a5f]"></i> Hệ thống Luyện thi HSK Online Chuẩn Quốc Tế
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Luyện Thi HSK & Bảng Xếp Hạng 成績榜
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    Rèn luyện bộ đề thi thử HSK 1 - 6 chính thức, bấm giờ phòng thi thực tế và đọ sức cùng TOP học viên xuất sắc nhất.
                </p>
            </div>
        </div>
    </div>

    <!-- THỐNG KÊ TỔNG QUAN LUYỆN THI (STATS BAR) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-[#0284c7] fa-clipboard-check"></i>
            </div>
            <div>
                <div class="text-[11px] font-medium text-slate-400">Tổng lượt làm đề</div>
                <div class="text-base font-bold text-slate-900 dark:text-white">24,850 <span class="text-xs text-emerald-500 font-semibold">+12%</span></div>
            </div>
        </div>

        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div>
                <div class="text-[11px] font-medium text-slate-400">Tỷ lệ đỗ HSK</div>
                <div class="text-base font-bold text-slate-900 dark:text-white">88.5% <span class="text-xs text-emerald-500 font-semibold">Cao</span></div>
            </div>
        </div>

        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 dark:bg-sky-950/60 text-[#0284c7] flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="text-[11px] font-medium text-slate-400">Thời gian TB làm bài</div>
                <div class="text-base font-bold text-slate-900 dark:text-white">38 Phút <span class="text-xs text-[#0284c7] font-semibold">Chuẩn</span></div>
            </div>
        </div>

        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-fire"></i>
            </div>
            <div>
                <div class="text-[11px] font-medium text-slate-400">Cấp độ sôi nổi nhất</div>
                <div class="text-base font-bold text-slate-900 dark:text-white">HSK 4 & 5</div>
            </div>
        </div>
    </div>

    <!-- BỐ CỤC CHÍNH: BẢNG ĐỀ THI (BÊN TRÁI 2 CỘT) & BẢNG XẾP HẠNG LEADERBOARD (BÊN PHẢI 1 CỘT) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- CỘT 1 & 2: DANH SÁCH BỘ ĐỀ LUYỆN THI HSK -->
        <div class="lg:col-span-2 space-y-5">
            
            <!-- HSK Level Filter Tabs -->
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-book-bookmark text-[#e07a5f]"></i> Bộ đề Luyện thi HSK
                </h2>

                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1">
                    <button @click="levelTab = 'all'" :class="levelTab === 'all' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap btn-tactile">Tất cả</button>
                    <button @click="levelTab = 'hsk1'" :class="levelTab === 'hsk1' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap btn-tactile">HSK 1</button>
                    <button @click="levelTab = 'hsk2'" :class="levelTab === 'hsk2' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap btn-tactile">HSK 2</button>
                    <button @click="levelTab = 'hsk3'" :class="levelTab === 'hsk3' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap btn-tactile">HSK 3</button>
                    <button @click="levelTab = 'hsk4'" :class="levelTab === 'hsk4' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap btn-tactile">HSK 4</button>
                    <button @click="levelTab = 'hsk5'" :class="levelTab === 'hsk5' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap btn-tactile">HSK 5</button>
                    <button @click="levelTab = 'hsk6'" :class="levelTab === 'hsk6' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap btn-tactile">HSK 6</button>
                </div>
            </div>

            <!-- Grid các đề thi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Đề 1 -->
                <div class="lms-card p-5 space-y-4 flex flex-col justify-between group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">HSK 1 • Đề 2026</span>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400"><i class="fa-solid fa-award mr-1"></i>Đạt: 195/200</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">Đề Luyện thi HSK 1 - Mã H1001</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">Cấu trúc 2 phần: Nghe hiểu (20 câu, 15p) & Đọc hiểu (20 câu, 17p).</p>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Thời gian</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">35 Phút</div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Số câu hỏi</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">40 Câu</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-users mr-1"></i>3.4k lượt làm</span>
                        <a href="{{ url('/demo-exam-take') }}" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5">
                            <span>Vào thi ngay</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Đề 2 -->
                <div class="lms-card p-5 space-y-4 flex flex-col justify-between group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300">HSK 2 • Đề 2026</span>
                            <span class="text-xs font-medium text-slate-400">Chưa làm</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">Đề Luyện thi HSK 2 - Mã H2002</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">Bộ đề thi cập nhật tháng 4/2026 với 300 từ vựng cốt lõi kèm đoạn hội thoại audio thực tế.</p>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Thời gian</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">45 Phút</div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Số câu hỏi</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">60 Câu</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-users mr-1"></i>2.8k lượt làm</span>
                        <a href="{{ url('/demo-exam-take') }}" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5">
                            <span>Vào thi ngay</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Đề 3 -->
                <div class="lms-card p-5 space-y-4 flex flex-col justify-between group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300">HSK 3 • Đề 2026</span>
                            <span class="text-xs font-medium text-slate-400">Chưa làm</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">Đề Luyện thi HSK 3 - Mã H3003</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">Kèm phần thi Đọc hiểu chữ Hán không có Pinyin và bài tập điền từ chuẩn.</p>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Thời gian</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">60 Phút</div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Số câu hỏi</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">80 Câu</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-users mr-1"></i>1.9k lượt làm</span>
                        <a href="{{ url('/demo-exam-take') }}" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5">
                            <span>Vào thi ngay</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Đề 4 -->
                <div class="lms-card p-5 space-y-4 flex flex-col justify-between group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300">HSK 4 • Cao cấp I</span>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400"><i class="fa-solid fa-award mr-1"></i>Đạt: 285/300</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">Đề Luyện thi HSK 4 - Mã H4004</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">Đề thi đầy đủ 3 phần Nghe, Đọc, Viết với 1,200 từ vựng và câu ghép ngữ pháp phức tạp.</p>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Thời gian</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">105 Phút</div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="text-[10px] text-slate-400">Số câu hỏi</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">100 Câu</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-users mr-1"></i>4.2k lượt làm</span>
                        <a href="{{ url('/demo-exam-take') }}" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5">
                            <span>Thi lại</span> <i class="fa-solid fa-rotate-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- CỘT 3: WIDGET BẢNG XẾP HẠNG LEADERBOARD (#1 -> #8 STRIKT MANDATE) -->
        <div class="space-y-4">
            <div class="lms-card p-5 space-y-4">
                
                <!-- Leaderboard Header -->
                <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-trophy text-amber-500"></i> Bảng Xếp Hạng 成績榜
                        </h3>
                        <p class="text-[11px] text-slate-400 font-medium">Top 8 Học viên xuất sắc nhất</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                </div>

                <!-- FILTER BAR ALWAYS ON 1 SINGLE ROW (flex-nowrap, text-[11px] STRICT MANDATE) -->
                <div class="flex items-center gap-1 overflow-x-auto flex-nowrap pb-1 no-scrollbar text-[11px] border-y border-[#e8e2d9] dark:border-[#2d2926] py-2">
                    <button @click="leaderboardFilter = 'all_time'" :class="leaderboardFilter === 'all_time' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-lg shrink-0 whitespace-nowrap btn-tactile">Toàn thời gian</button>
                    <button @click="leaderboardFilter = 'month'" :class="leaderboardFilter === 'month' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-lg shrink-0 whitespace-nowrap btn-tactile">Tháng này</button>
                    <button @click="leaderboardFilter = 'week'" :class="leaderboardFilter === 'week' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-lg shrink-0 whitespace-nowrap btn-tactile">Tuần này</button>
                    <button @click="leaderboardFilter = 'hsk6'" :class="leaderboardFilter === 'hsk6' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-lg shrink-0 whitespace-nowrap btn-tactile">HSK 6</button>
                    <button @click="leaderboardFilter = 'hsk5'" :class="leaderboardFilter === 'hsk5' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-lg shrink-0 whitespace-nowrap btn-tactile">HSK 5</button>
                    <button @click="leaderboardFilter = 'hsk4'" :class="leaderboardFilter === 'hsk4' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-lg shrink-0 whitespace-nowrap btn-tactile">HSK 4</button>
                </div>

                <!-- LEADERBOARD LIST (RANKS #1 to #8 MANDATE) -->
                <div class="space-y-2 pt-1">
                    <template x-for="item in leaderboard" :key="item.rank">
                        <div class="flex items-center justify-between p-2.5 rounded-xl transition-all btn-tactile"
                             :class="item.rank === 1 ? 'bg-amber-500/10 dark:bg-amber-500/15 border border-amber-500/30' : (item.rank === 2 ? 'bg-slate-200/40 dark:bg-slate-800/40 border border-slate-300 dark:border-slate-700' : (item.rank === 3 ? 'bg-amber-800/10 dark:bg-amber-900/20 border border-amber-700/25' : 'hover:bg-slate-50 dark:hover:bg-slate-800/60 border border-transparent'))">
                            
                            <!-- Rank Number & Avatar & Name -->
                            <div class="flex items-center gap-2.5 min-w-0">
                                <!-- Rank Number Badge -->
                                <div class="w-6 h-6 rounded-lg font-extrabold text-xs flex items-center justify-center shrink-0"
                                     :class="item.rank === 1 ? 'bg-amber-500 text-white shadow-xs' : (item.rank === 2 ? 'bg-slate-400 text-white' : (item.rank === 3 ? 'bg-amber-700 text-white' : 'text-slate-400 bg-slate-100 dark:bg-slate-800'))">
                                    <span x-text="'#' + item.rank"></span>
                                </div>

                                <!-- Avatar -->
                                <img :src="item.avatar" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0">

                                <!-- Name & Level Badge -->
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate" x-text="item.name"></p>
                                    </div>
                                    <span class="inline-block px-1.5 py-0.2 text-[9px] font-bold rounded border" :class="item.badgeBg" x-text="item.level"></span>
                                </div>
                            </div>

                            <!-- Score & Blue Clock Icon (#0284c7 STRICT MANDATE) -->
                            <div class="text-right shrink-0">
                                <div class="text-xs font-extrabold text-slate-900 dark:text-white" x-text="item.score"></div>
                                <div class="text-[10px] font-semibold text-[#0284c7] flex items-center justify-end gap-1">
                                    <i class="fa-solid fa-clock text-[#0284c7]"></i>
                                    <span x-text="item.time"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Bottom Action Link -->
                <div class="pt-2 text-center border-t border-[#e8e2d9] dark:border-[#2d2926]">
                    <button class="text-xs font-bold text-[#e07a5f] hover:text-[#c86349] transition-colors btn-tactile">
                        Xem toàn bộ bảng xếp hạng (Top 100) <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection
