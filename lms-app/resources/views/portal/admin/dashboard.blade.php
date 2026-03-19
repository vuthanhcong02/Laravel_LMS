@extends('portal.layouts.dashboard')

@section('title', 'Admin Dashboard - XiaoMu')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1200px] mx-auto space-y-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tổng học viên</p>
                        <span class="material-symbols-outlined text-primary">groups</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">12,840</p>
                    <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">trending_up</span>
                        <span>+12% so với tháng trước</span>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tổng doanh thu</p>
                        <span class="material-symbols-outlined text-primary">currency_exchange</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">₫2.4B</p>
                    <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">trending_up</span>
                        <span>+8.5% so với tháng trước</span>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Khóa học hoạt động</p>
                        <span class="material-symbols-outlined text-primary">local_library</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">156</p>
                    <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">trending_up</span>
                        <span>+4% mới đăng ký</span>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Yêu cầu hỗ trợ</p>
                        <span class="material-symbols-outlined text-primary">support_agent</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">42</p>
                    <div class="flex items-center gap-1 text-orange-600 dark:text-orange-400 text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">error</span>
                        <span>12 yêu cầu chưa xử lý</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Chart Area -->
                <div
                    class="lg:col-span-2 flex flex-col gap-6 p-6 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-slate-900 dark:text-white text-lg font-bold">Biểu đồ doanh thu &amp; tăng trưởng
                            </h2>
                            <p class="text-slate-500 text-sm">Hiệu suất tài chính 7 tháng qua</p>
                        </div>
                        <select
                            class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-xs font-medium px-2 py-1 focus:ring-primary">
                            <option>Năm 2024</option>
                            <option>Năm 2023</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <p class="text-slate-900 dark:text-white text-4xl font-bold tracking-tight">₫2,400,000,000</p>
                        <p class="text-emerald-600 font-semibold text-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">north_east</span> +15.2% vs Last Period
                        </p>
                    </div>
                    <div class="h-64 w-full relative pt-4">
                        <!-- Abstract SVG Chart Representation -->
                        <svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 500 150">
                            <defs>
                                <lineargradient id="chartGradient" x1="0" x2="0" y1="0"
                                    y2="1">
                                    <stop offset="0%" stop-color="#8fc0e0" stop-opacity="0.5"></stop>
                                    <stop offset="100%" stop-color="#8fc0e0" stop-opacity="0"></stop>
                                </lineargradient>
                            </defs>
                            <path
                                d="M0,130 C50,120 80,40 120,60 S180,10 240,50 S320,100 380,40 S450,20 500,10 L500,150 L0,150 Z"
                                fill="url(#chartGradient)"></path>
                            <path d="M0,130 C50,120 80,40 120,60 S180,10 240,50 S320,100 380,40 S450,20 500,10"
                                fill="none" stroke="#8fc0e0" stroke-linecap="round" stroke-width="3"></path>
                        </svg>
                        <div class="flex justify-between mt-4 px-2">
                            <span class="text-slate-400 text-xs font-bold uppercase">Jan</span>
                            <span class="text-slate-400 text-xs font-bold uppercase">Feb</span>
                            <span class="text-slate-400 text-xs font-bold uppercase">Mar</span>
                            <span class="text-slate-400 text-xs font-bold uppercase">Apr</span>
                            <span class="text-slate-400 text-xs font-bold uppercase">May</span>
                            <span class="text-slate-400 text-xs font-bold uppercase">Jun</span>
                            <span class="text-slate-400 text-xs font-bold uppercase">Jul</span>
                        </div>
                    </div>
                </div>
                <!-- Recent Activity -->
                <div
                    class="flex flex-col gap-6 p-6 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h2 class="text-slate-900 dark:text-white text-lg font-bold">Hoạt động gần đây</h2>
                    <div class="flex flex-col gap-6">
                        <!-- Activity Item 1 -->
                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <span class="material-symbols-outlined text-sm">person_add</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Nguyễn Văn A vừa đăng ký
                                    khóa học HSK 1</p>
                                <p class="text-xs text-slate-500">2 phút trước</p>
                            </div>
                        </div>
                        <!-- Activity Item 2 -->
                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                <span class="material-symbols-outlined text-sm">draw</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Giáo viên Trần Thị B vừa
                                    cập nhật bài giảng</p>
                                <p class="text-xs text-slate-500">15 phút trước</p>
                            </div>
                        </div>
                        <!-- Activity Item 3 -->
                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <span class="material-symbols-outlined text-sm">payments</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Thanh toán thành công:
                                    ₫500.000 từ Lê C</p>
                                <p class="text-xs text-slate-500">1 giờ trước</p>
                            </div>
                        </div>
                        <!-- Activity Item 4 -->
                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                <span class="material-symbols-outlined text-sm">emergency</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Yêu cầu hỗ trợ mới từ
                                    học viên HSK 4</p>
                                <p class="text-xs text-slate-500">3 giờ trước</p>
                            </div>
                        </div>
                        <!-- Activity Item 5 -->
                        <div class="flex gap-4">
                            <div
                                class="size-10 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                                <span class="material-symbols-outlined text-sm">person_add</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Đặng Văn D đăng ký làm
                                    giáo viên mới</p>
                                <p class="text-xs text-slate-500">5 giờ trước</p>
                            </div>
                        </div>
                    </div>
                    <button
                        class="w-full py-2.5 rounded-lg border border-primary text-primary text-sm font-bold hover:bg-primary hover:text-white transition-all">
                        Xem tất cả hoạt động
                    </button>
                </div>
            </div>
            <!-- Featured Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-primary/10 rounded-lg p-6 flex items-center justify-between border border-primary/20">
                    <div class="space-y-2">
                        <h3 class="text-primary font-bold">Khóa học phổ biến nhất</h3>
                        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">HSK 3 - Giao tiếp</p>
                        <p class="text-sm text-slate-500">Với hơn 1,200 học viên đang theo học</p>
                    </div>
                    <div
                        class="size-16 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center shadow-lg text-primary">
                        <span class="material-symbols-outlined !text-4xl">workspace_premium</span>
                    </div>
                </div>
                <div class="bg-slate-900 rounded-lg p-6 flex items-center justify-between border border-slate-700">
                    <div class="space-y-2">
                        <h3 class="text-primary font-bold">Tỷ lệ hài lòng</h3>
                        <p class="text-2xl font-bold text-white">4.8 / 5.0</p>
                        <p class="text-sm text-slate-400">Dựa trên 850 đánh giá tháng này</p>
                    </div>
                    <div class="flex -space-x-3">
                        <div class="size-10 rounded-full border-2 border-slate-900 bg-cover bg-center"
                            data-alt="Student avatar placeholder"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCsQ7DXQWb5AtV3vFOXpWZe9FwxGlyol-vRpuSsKhd_lBlKq7nLiX9KVrMnYvBDKi_ZpO5NnqqAurWEhaf8qR-u4XEA_LKNIQui5i2vV_Z3M9jAgUhhEpw2_0mJkLigTQl32Gt3Y3ufTnO2xpfQ4UqirPl4ZZGDovsPKOF-eQxslJGxIREApCNl9Me7iUJYkGWkAjxIracv1EijQ9qT-kOpHpKAMyPbwzI2Uqqr_wEsuY1UMF-_YixnFkXj9yIUSP9I5woPrnigZQ");'>
                        </div>
                        <div class="size-10 rounded-full border-2 border-slate-900 bg-cover bg-center"
                            data-alt="Student avatar placeholder"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDZcq3QfUSmML0o_drtpWeba9LXvvUGi4xmnr_pdWGy6E139YbGPGwtSvFjYQQ1ReZ7U4OsgNcKs764EGyww5GNFpOR3aLMP8drJohTKPQqtTbeySACbvnLjGZnAh8_beDRBtZDNoufk141F1tjfMw1le3vHmHoOk44YHpyeveOrFc79LC0Jm9tdGK6mzP0lR3xpIn_-8-pBZrbVD26pScdkH8tp1Nwn4nIZmefCtTdwrn_JBxHFW4tM_qMliRPYybopSO15Wmymg");'>
                        </div>
                        <div class="size-10 rounded-full border-2 border-slate-900 bg-cover bg-center"
                            data-alt="Student avatar placeholder"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAAYXz-1-uxHQYHYgwPq0iI_OaaORBs04WTLc3ryXm0FOP0slwgqTA0YZ8FjM1BiUO4PE0t9KxnfaydGzoSdFGELLUsnmiBW8bcuJELnotUDYaUBJ1BjFhYnKkSIg--Xqbj3T-JrP1Y_HyszRXhuTLiyHx7_CSsTdxGqYtmvwF3ju36XqFedYhBeiCzF9hZjElcEvJMKuiVTX3XE2XBBDaJ2ihwZK9AdswViV0_GylSs0AYMqw5hdA02HQLGfEsTSTZEEmMpsw6mA");'>
                        </div>
                        <div
                            class="size-10 rounded-full border-2 border-slate-900 bg-primary flex items-center justify-center text-[10px] font-bold text-white">
                            +82</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
