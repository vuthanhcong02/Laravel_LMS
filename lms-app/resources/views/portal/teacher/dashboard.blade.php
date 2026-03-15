@extends('portal.layouts.dashboard')

@section('title', 'Teacher Dashboard - XiaoMu')

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
        <div class="max-w-[1400px] mx-auto space-y-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-primary to-blue-500 rounded-3xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-extrabold mb-3 tracking-tight">Xin chào, Nguyễn Thị A! 👋</h1>
                        <p class="text-white/90 text-lg font-medium">Chúc bạn một ngày làm việc hiệu quả. Hôm nay bạn có <span class="font-bold underline decoration-2 underline-offset-4">2 lớp học</span> và <span class="font-bold underline decoration-2 underline-offset-4">25 bài tập</span> chờ chấm.</p>
                    </div>
                    <div class="hidden lg:flex items-center gap-3 bg-white/20 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/30">
                        <span class="material-symbols-outlined text-3xl">calendar_month</span>
                        <div class="text-right">
                            <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Hôm nay</p>
                            <p class="text-xl font-bold">16 Tháng 3, 2026</p>
                        </div>
                    </div>
                </div>
                <!-- Decor -->
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/20 rounded-full blur-3xl mix-blend-overlay"></div>
                <div class="absolute right-40 -bottom-20 w-60 h-60 bg-blue-300/30 rounded-full blur-3xl mix-blend-overlay"></div>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div class="group bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 group-hover:-rotate-12 duration-300">
                        <span class="material-symbols-outlined text-8xl text-blue-500">group</span>
                    </div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="size-12 rounded-2xl bg-blue-50 dark:bg-blue-900/40 text-blue-500 flex items-center justify-center border border-blue-100 dark:border-blue-800/50">
                            <span class="material-symbols-outlined shrink-0 text-2xl">group</span>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 rounded-full border border-emerald-100 dark:border-emerald-800/50">+12%</span>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">Tổng học viên</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white">150</p>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="group bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 group-hover:-rotate-12 duration-300">
                        <span class="material-symbols-outlined text-8xl text-purple-500">class</span>
                    </div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="size-12 rounded-2xl bg-purple-50 dark:bg-purple-900/40 text-purple-500 flex items-center justify-center border border-purple-100 dark:border-purple-800/50">
                            <span class="material-symbols-outlined shrink-0 text-2xl">class</span>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">Lớp đang dạy</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white">5</p>
                        <p class="text-xs text-slate-400 mt-2 font-medium">3 lớp HSK 4, 2 lớp Giao tiếp</p>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="group bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-orange-500/10 hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 group-hover:-rotate-12 duration-300">
                        <span class="material-symbols-outlined text-8xl text-orange-500">history_edu</span>
                    </div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="size-12 rounded-2xl bg-orange-50 dark:bg-orange-900/40 text-orange-500 flex items-center justify-center border border-orange-100 dark:border-orange-800/50">
                            <span class="material-symbols-outlined shrink-0 text-2xl">history_edu</span>
                        </div>
                        <span class="flex size-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">Bài tập chờ chấm</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white">25</p>
                        <p class="text-xs text-orange-600 mt-2 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">warning</span> Cần xong trước 18:00
                        </p>
                    </div>
                </div>

                <!-- Stat 4 -->
                <div class="group bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 group-hover:-rotate-12 duration-300">
                        <span class="material-symbols-outlined text-8xl text-emerald-500">payments</span>
                    </div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="size-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-500 flex items-center justify-center border border-emerald-100 dark:border-emerald-800/50">
                            <span class="material-symbols-outlined shrink-0 text-2xl">payments</span>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">Thu nhập ước tính</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white mt-1">15.0M <span class="text-base font-bold text-slate-400">VNĐ</span></p>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-3 overflow-hidden">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Timeline Section -->
                <div class="lg:col-span-2 flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-3xl">event_upcoming</span>
                            Lịch dạy hôm nay
                        </h2>
                        <a href="#" class="text-primary text-sm font-bold hover:underline underline-offset-4 flex items-center gap-1 group">
                            Xem lịch đầy đủ <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm relative h-full">
                        <!-- Left timeline line -->
                        <div class="absolute left-[59px] top-8 bottom-8 w-1 bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                        
                        <div class="flex flex-col gap-8 relative z-10">
                            <!-- Class 1 -->
                            <div class="flex gap-6 relative group">
                                <div class="w-20 text-right pt-2 relative shrink-0">
                                    <p class="font-extrabold text-slate-800 dark:text-white text-lg leading-tight">08:00</p>
                                    <p class="text-xs text-slate-400 font-bold tracking-wider">09:30</p>
                                </div>
                                <div class="absolute left-[56px] top-4 size-3.5 rounded-full bg-primary border-[3px] border-white dark:border-slate-900 z-10 shadow-[0_0_0_5px_rgba(143,192,224,0.15)] group-hover:scale-150 transition-all duration-300"></div>
                                <div class="flex-1 bg-gradient-to-br from-primary/10 to-transparent rounded-2xl p-6 border border-primary/20 hover:border-primary/50 transition-colors shadow-sm">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-extrabold text-xl text-slate-800 dark:text-white group-hover:text-primary transition-colors">Lớp HSK 4 - Căn bản</h3>
                                        <span class="px-3 py-1.5 bg-white/80 dark:bg-slate-800 backdrop-blur-sm rounded-xl text-xs font-bold text-primary flex items-center gap-1.5 shadow-sm border border-primary/10">
                                            <span class="material-symbols-outlined text-[16px]">videocam</span> Online
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-5 font-medium flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">menu_book</span> Bài 5: Mua sắm tại siêu thị
                                    </p>
                                    <div class="flex items-center justify-between mt-auto">
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-bold bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">group</span> 15 học viên
                                        </div>
                                        <button class="bg-primary hover:bg-blue-400 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                            Vào phòng học <span class="material-symbols-outlined text-[18px]">login</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Class 2 -->
                            <div class="flex gap-6 relative group">
                                <div class="w-20 text-right pt-2 relative shrink-0">
                                    <p class="font-extrabold text-slate-800 dark:text-white text-lg leading-tight">14:00</p>
                                    <p class="text-xs text-slate-400 font-bold tracking-wider">15:30</p>
                                </div>
                                <div class="absolute left-[56px] top-4 size-3.5 rounded-full bg-slate-300 dark:bg-slate-600 border-[3px] border-white dark:border-slate-900 z-10 group-hover:bg-primary group-hover:scale-150 transition-all duration-300"></div>
                                <div class="flex-1 bg-slate-50 dark:bg-slate-800/40 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-extrabold text-xl text-slate-800 dark:text-white group-hover:text-primary transition-colors">Lớp Giao tiếp nâng cao</h3>
                                        <span class="px-3 py-1.5 bg-white dark:bg-slate-800 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 flex items-center gap-1.5 shadow-sm border border-slate-200 dark:border-slate-700">
                                            <span class="material-symbols-outlined text-[16px]">storefront</span> Offline
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-5 font-medium flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">forum</span> Phỏng vấn xin việc
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-bold bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">group</span> 10 học viên
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-bold bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">location_on</span> Phòng 302
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Class 3 -->
                            <div class="flex gap-6 relative group opacity-50 hover:opacity-100 transition-opacity">
                                <div class="w-20 text-right pt-2 relative shrink-0">
                                    <p class="font-extrabold text-slate-800 dark:text-white text-lg leading-tight">19:00</p>
                                    <p class="text-xs text-slate-400 font-bold tracking-wider">20:30</p>
                                </div>
                                <div class="absolute left-[56px] top-4 size-3.5 rounded-full bg-slate-300 dark:bg-slate-600 border-[3px] border-white dark:border-slate-900 z-10 group-hover:bg-primary transition-colors duration-300"></div>
                                <div class="flex-1 bg-slate-50 dark:bg-slate-800/20 rounded-2xl p-6 border border-slate-100 dark:border-slate-800">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-extrabold text-xl text-slate-800 dark:text-white">Lớp HSK 5 - Cấp tốc</h3>
                                        <span class="px-3 py-1.5 bg-white dark:bg-slate-800 rounded-xl text-xs font-bold text-slate-500 flex items-center gap-1.5 shadow-sm border border-slate-200 dark:border-slate-700">
                                            <span class="material-symbols-outlined text-[16px]">videocam</span> Online
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-5 font-medium flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">school</span> Luyện đề Đọc hiểu số 2
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-bold bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">group</span> 20 học viên
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications & Quick Actions Section -->
                <div class="flex flex-col gap-6">
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-500 text-3xl">notifications_active</span>
                        Thông báo mới
                    </h2>
                    
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col h-full relative">
                        <!-- Notice 1 -->
                        <a href="#" class="group p-6 hover:bg-orange-50/50 dark:hover:bg-orange-900/10 transition-colors border-b border-slate-100 dark:border-slate-800 flex gap-5 relative overflow-hidden">
                            <div class="mt-1 size-12 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/40 dark:to-orange-900/20 text-orange-600 shadow-sm flex items-center justify-center shrink-0 border border-orange-200/50 dark:border-orange-800/50 group-hover:scale-110 group-hover:rotate-6 transition-all">
                                <span class="material-symbols-outlined text-2xl">assignment</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1.5">
                                    <p class="font-extrabold text-slate-800 dark:text-slate-200 text-base group-hover:text-orange-600 transition-colors">Yêu cầu chấm điểm</p>
                                    <span class="text-[10px] font-black uppercase text-white bg-orange-500 px-2 py-0.5 rounded shadow-sm">Mới</span>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 font-medium leading-relaxed">Có 15 bài tập viết của lớp HSK 4 chờ chấm trước ngày mai.</p>
                                <p class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5 uppercase letter-spacing"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 giờ trước</p>
                            </div>
                        </a>
                        
                        <!-- Notice 2 -->
                        <a href="#" class="group p-6 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors border-b border-slate-100 dark:border-slate-800 flex gap-5 relative overflow-hidden">
                            <div class="mt-1 size-12 rounded-2xl bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/40 dark:to-blue-900/20 text-blue-600 shadow-sm flex items-center justify-center shrink-0 border border-blue-200/50 dark:border-blue-800/50 group-hover:scale-110 group-hover:rotate-6 transition-all">
                                <span class="material-symbols-outlined text-2xl">campaign</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1.5">
                                    <p class="font-extrabold text-slate-800 dark:text-slate-200 text-base group-hover:text-blue-600 transition-colors">Họp chuyên môn</p>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 font-medium leading-relaxed">Lịch họp định kỳ sáng Thứ 7 lúc 09:00 tại Phòng Hội đồng.</p>
                                <p class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5 uppercase letter-spacing"><span class="material-symbols-outlined text-[14px]">schedule</span> Hôm qua</p>
                            </div>
                        </a>

                        <!-- Notice 3 -->
                        <a href="#" class="group p-6 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors flex gap-5 relative overflow-hidden">
                            <div class="mt-1 size-12 rounded-2xl bg-gradient-to-br from-emerald-100 to-emerald-50 dark:from-emerald-900/40 dark:to-emerald-900/20 text-emerald-600 shadow-sm flex items-center justify-center shrink-0 border border-emerald-200/50 dark:border-emerald-800/50 group-hover:scale-110 group-hover:-rotate-6 transition-all">
                                <span class="material-symbols-outlined text-2xl">workspace_premium</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-extrabold text-slate-800 dark:text-slate-200 text-base mb-1.5 group-hover:text-emerald-600 transition-colors">Học viên xuất sắc</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 font-medium leading-relaxed">Học viên Trần Văn B vừa báo điểm thi 280đ. Vinh danh ngay!</p>
                                <p class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5 uppercase letter-spacing"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 ngày trước</p>
                            </div>
                        </a>
                        
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-5 mt-auto border-t border-slate-100 dark:border-slate-800 text-center">
                            <button class="w-full inline-flex justify-center items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors group">
                                Xem tất cả 12 thông báo <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
@endsection
