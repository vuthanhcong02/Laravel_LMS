@extends('portal.layouts.dashboard')

@section('title', 'Student Dashboard - XiaoMu')

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-10 space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">Chào mừng, Nguyễn Văn A!</h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">Hôm nay là một ngày tuyệt vời để học Hán Ngữ. Bạn đã sẵn
                sàng chưa?</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/10 shadow-sm flex items-center gap-4">
                <div
                    class="size-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-500">
                    <span class="material-symbols-outlined text-3xl">schedule</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Giờ đã học</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">45.5h</p>
                </div>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/10 shadow-sm flex items-center gap-4">
                <div
                    class="size-12 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-500">
                    <span class="material-symbols-outlined text-3xl">play_circle</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Khóa học đang học</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">3</p>
                </div>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/10 shadow-sm flex items-center gap-4">
                <div
                    class="size-12 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-500">
                    <span class="material-symbols-outlined text-3xl">task_alt</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Bài tập hoàn thành</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">12</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Học tiếp tục</h2>
                    <a class="text-primary text-sm font-semibold hover:underline" href="#">Xem tất cả</a>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl border border-primary/10 overflow-hidden shadow-sm flex flex-col md:flex-row">
                    <div class="w-full md:w-64 h-48 bg-slate-200 shrink-0">
                        <img alt="Course Thumbnail" class="w-full h-full object-cover"
                            data-alt="Chinese architecture and calligraphy course background"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcVLFTuZVyva9PZY7A0ZE0DSXILjIqeQe5uxVokz7M5ZYcPeCCugrohl3eNDIv3Nyc_WcpCUC_ycgRBdUscM0V_zhsl40Fq4lfSqip9T9IpFkqOnteD-udm5-0PSk9x2VZ-q-hBRmoU0-2VxDZrc5TJrMqV-33nPTHT1M2aPDris8L7m008C2VXD8-Zchcvp64J4YBdv3jqIr1abQOcDrxiYKcERkNZxTABUMuaXCdBSVX0QpbvXnMEMjWAO8hZR_Di9id3lHt8Q" />
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-1">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="px-2 py-0.5 bg-primary/20 text-primary text-[10px] font-bold rounded uppercase">Trung
                                    cấp 1</span>
                                <span class="text-slate-400 text-xs">• 12 bài giảng</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Hán Ngữ Giao Tiếp Chuyên Sâu
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2">Luyện tập kỹ năng nghe và nói
                                trong các tình huống thực tế tại công sở và cuộc sống hàng ngày.</p>
                        </div>
                        <div class="mt-6">
                            <div class="flex justify-between text-xs font-semibold mb-2">
                                <span class="text-slate-600 dark:text-slate-400">Tiến độ: 65%</span>
                                <span class="text-primary">8/12 bài học</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full mb-4">
                                <div class="bg-primary h-full rounded-full" style="width: 65%"></div>
                            </div>
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-2.5 px-6 rounded-lg transition-all w-full md:w-auto">
                                Học tiếp
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-primary/5 rounded-xl p-6 border border-primary/20 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-primary shadow-sm">
                            <span class="material-symbols-outlined">lightbulb</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white">Mẹo học tập hôm nay</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Hãy dành 15 phút mỗi sáng để ôn tập từ
                                vựng qua Flashcards!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-8">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/10 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-slate-900 dark:text-white">Lịch học sắp tới</h3>
                        <button class="text-primary material-symbols-outlined">calendar_month</button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex gap-4 items-start">
                            <div
                                class="flex flex-col items-center justify-center bg-primary/10 rounded-lg size-12 shrink-0">
                                <span class="text-[10px] font-bold text-primary uppercase">Th2</span>
                                <span class="text-lg font-black text-primary leading-none">24</span>
                            </div>
                            <div class="flex-1 border-b border-slate-100 dark:border-slate-800 pb-3">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Luyện nói 1:1</p>
                                <p class="text-xs text-slate-500">19:30 - 20:15 • Zoom Meeting</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div
                                class="flex flex-col items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-lg size-12 shrink-0">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Th4</span>
                                <span class="text-lg font-black text-slate-400 leading-none">26</span>
                            </div>
                            <div class="flex-1 border-b border-slate-100 dark:border-slate-800 pb-3">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Hán Ngữ Tổng Quát 3</p>
                                <p class="text-xs text-slate-500">09:00 - 11:00 • Phòng 302</p>
                            </div>
                        </div>
                    </div>
                    <button
                        class="w-full mt-4 py-2 border border-primary/30 text-primary text-xs font-bold rounded-lg hover:bg-primary/5 transition-colors">Xem
                        toàn bộ lịch</button>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/10 shadow-sm">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-6">Việc cần làm (To-do)</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 group">
                            <div
                                class="size-5 border-2 border-primary rounded-md flex items-center justify-center cursor-pointer group-hover:bg-primary/10">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Viết bài luận về gia đình
                                </p>
                                <p class="text-[10px] text-red-500 font-bold">Hết hạn: Hôm nay</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 group">
                            <div
                                class="size-5 border-2 border-primary rounded-md flex items-center justify-center cursor-pointer group-hover:bg-primary/10">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Hoàn thành Quiz bài 8</p>
                                <p class="text-[10px] text-slate-400 font-bold">Hạn chót: 2 ngày tới</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 group">
                            <div
                                class="size-5 border-2 border-slate-300 dark:border-slate-700 rounded-md flex items-center justify-center cursor-pointer">
                                <span class="material-symbols-outlined text-primary text-lg font-black">check</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-400 line-through">Luyện viết 20 chữ Hán</p>
                                <p class="text-[10px] text-emerald-500 font-bold">Hoàn thành</p>
                            </div>
                        </div>
                    </div>
                    <button
                        class="w-full mt-6 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">add</span> Thêm nhiệm vụ
                    </button>
                </div>
            </div>
        </div>
    </main>
@endsection
