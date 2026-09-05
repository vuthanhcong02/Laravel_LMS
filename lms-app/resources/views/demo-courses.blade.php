@extends('layouts.lms')
@section('title', 'Khóa học HSK - Tiếng Trung XIAOMU LMS')
@section('alpine-data')
    levelFilter: 'all', 
    selectedCourse: null, 
    lessons: [
        { id: 1, code: 'H1L01', hanzi: '你好！', vi: 'Xin chào', countVocab: 7, isUnlocked: true },
        { id: 2, code: 'H1L02', hanzi: '谢谢你！', vi: 'Cảm ơn anh!', countVocab: 9, isUnlocked: true },
        { id: 3, code: 'H1L03', hanzi: '你叫什么名字？', vi: 'Cô tên gì?', countVocab: 10, isUnlocked: true },
        { id: 4, code: 'H1L04', hanzi: '她是我的汉语老师。', vi: 'Cô ấy là giáo viên tiếng Trung của tôi.', countVocab: 12, isUnlocked: true },
        { id: 5, code: 'H1L05', hanzi: '她女儿今年二十岁。', vi: 'Con gái cô ấy năm nay 20 tuổi.', countVocab: 11, isUnlocked: true },
        { id: 6, code: 'H1L06', hanzi: '我会说汉语。', vi: 'Tôi biết nói tiếng Trung.', countVocab: 10, isUnlocked: true },
        { id: 7, code: 'H1L07', hanzi: '今天几号？', vi: 'Hôm nay ngày mấy?', countVocab: 8, isUnlocked: true }
    ],
@endsection
@section('content')
    <!-- ========================================================================= -->
    <!-- ========================================================================= -->
    <div x-show="selectedCourse === null" class="space-y-6">
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-graduation-cap text-[#e07a5f]"></i> Lộ trình chuẩn hóa HSK 1 - HSK 6
                </div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Khóa học Tiếng Trung HSK
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal leading-relaxed">
                    Tổng hợp bài giảng video, từ vựng chữ Hán, mẫu câu giao tiếp và ngữ pháp chuyên sâu. Click vào từng khóa để xem danh sách bài học.
                </p>
            </div>
        </div>
        <!-- Filter Bar Buttons (text-xs font-semibold) -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
            <button @click="levelFilter = 'all'" :class="levelFilter === 'all' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                Tất cả khóa học
            </button>
            <button @click="levelFilter = 'hsk12'" :class="levelFilter === 'hsk12' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                HSK 1 - HSK 2 (Sơ cấp)
            </button>
            <button @click="levelFilter = 'hsk34'" :class="levelFilter === 'hsk34' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                HSK 3 - HSK 4 (Trung cấp)
            </button>
            <button @click="levelFilter = 'hsk56'" :class="levelFilter === 'hsk56' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap btn-tactile">
                HSK 5 - HSK 6 (Cao cấp)
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <div class="lms-card p-5 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">HSK 1 • Sơ cấp I</span>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400"><i class="fa-solid fa-circle-check mr-1"></i>Đã đăng ký</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">HSK 1: Khởi đầu Hán ngữ</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed">Lộ trình bài học chính khóa bám sát cấu trúc khung đề thi HSK. Nắm vững 150 từ vựng cốt lõi & Pinyin giọng chuẩn.</p>
                    <div class="space-y-1.5 pt-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-500">
                            <span>Tiến độ học</span>
                            <span class="font-bold text-[#e07a5f]">12/15 bài (80%)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#e07a5f] to-[#c86349]" style="width: 80%;"></div>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                        <span><i class="fa-regular fa-clock mr-1"></i>15 bài</span>
                        <span><i class="fa-solid fa-user-group mr-1"></i>1.2k học viên</span>
                    </div>
                    <button @click="selectedCourse = 'hsk1'" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5 shadow-xs">
                        <span>Vào học</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
            <div class="lms-card p-5 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">HSK 2 • Sơ cấp II</span>
                        <span class="text-xs font-semibold text-[#e07a5f]"><i class="fa-solid fa-play mr-1"></i>Đang học</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">HSK 2: Giao tiếp Đời sống</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed">Mở rộng 300 từ vựng, cấu trúc câu hỏi phức hợp và kỹ năng hỏi đường, mua sắm, đặt phòng khách sạn.</p>
                    <div class="space-y-1.5 pt-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-500">
                            <span>Tiến độ học</span>
                            <span class="font-bold text-[#e07a5f]">6/15 bài (40%)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#e07a5f] to-[#c86349]" style="width: 40%;"></div>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                        <span><i class="fa-regular fa-clock mr-1"></i>15 bài</span>
                        <span><i class="fa-solid fa-user-group mr-1"></i>980</span>
                    </div>
                    <button @click="selectedCourse = 'hsk1'" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5 shadow-xs">
                        <span>Học tiếp</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
            <div class="lms-card p-5 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#0284c7]/15 text-[#0284c7] border border-[#0284c7]/30">HSK 3 • Trung cấp</span>
                        <span class="text-xs font-medium text-slate-400">Chưa đăng ký</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">HSK 3: Đọc hiểu Văn bản</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed">Tích lũy 600 từ vựng, tự tin đọc đoạn văn ngắn không cần Pinyin và viết đúng bộ thủ chữ Hán chuẩn nét.</p>
                    <div class="space-y-1.5 pt-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-500">
                            <span>Tiến độ học</span>
                            <span class="font-semibold text-slate-400">0/20 bài</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] overflow-hidden">
                            <div class="h-full rounded-full bg-slate-300 dark:bg-slate-700" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                        <span><i class="fa-regular fa-clock mr-1"></i>20 bài</span>
                        <span><i class="fa-solid fa-user-group mr-1"></i>750</span>
                    </div>
                    <button @click="selectedCourse = 'hsk1'" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-slate-300 text-xs font-bold btn-tactile flex items-center gap-1.5">
                        <span>Xem bài học</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ========================================================================= -->
    <!-- ========================================================================= -->
    <div x-show="selectedCourse !== null" class="space-y-6" style="display: none;">
        <button @click="selectedCourse = null" class="inline-flex items-center gap-2 text-xs font-bold text-[#e07a5f] hover:text-[#c86349] transition-colors btn-tactile">
            <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại danh sách khóa học
        </button>
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#f59e0b] text-slate-950 font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                    HSK 1
                </div>
                <div class="space-y-1">
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                        HSK 1 - Khởi đầu Hán ngữ
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                        Lộ trình bài học chính khóa bám sát cấu trúc khung chuẩn
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">
                    15 Bài học
                </span>
                <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f] dark:text-[#f4978e]">
                    150 Từ vựng
                </span>
            </div>
        </div>
        <div class="space-y-3">
            <template x-for="(item, index) in lessons" :key="item.id">
                <a :href="'{{ url('/demo-course-detail') }}?lesson=' + item.id"
                    class="lms-card p-4 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between gap-4 group hover:border-[#e07a5f] transition-all cursor-pointer block">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 dark:text-slate-500 font-bold text-xs flex items-center justify-center font-mono shrink-0 group-hover:text-[#e07a5f] group-hover:border-[#e07a5f]/40 transition-colors" x-text="index < 9 ? '0' + (index + 1) : (index + 1)">01</div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-xs font-semibold text-[#e07a5f] uppercase tracking-wider" x-text="'MÃ BÀI: ' + item.code">MÃ BÀI: H1L01</span>
                            </div>
                            <h3 class="text-base font-bold zh-text text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors leading-tight" x-text="item.hanzi">你好！</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate font-normal" x-text="item.vi">Xin chào</p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <div class="w-8 h-8 rounded-full bg-[#f59e0b] hover:bg-[#d97706] text-slate-950 flex items-center justify-center shadow-xs transition-transform group-hover:scale-105 btn-tactile">
                            <i class="fa-solid fa-play ml-0.5 text-[11px]"></i>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
@endsection
