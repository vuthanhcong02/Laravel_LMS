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
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">
                        {{ number_format($stats['total_students']) }}</p>
                    <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">trending_up</span>
                        <span>Dữ liệu thực tế</span>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tổng doanh thu</p>
                        <span class="material-symbols-outlined text-primary">currency_exchange</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">
                        ₫{{ number_format($stats['total_revenue'] / 1000000, 1) }}M</p>
                    <div
                        class="flex items-center gap-1 {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} text-xs font-bold">
                        <span
                            class="material-symbols-outlined text-xs">{{ $stats['revenue_growth'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                        <span>{{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}% so với tháng
                            trước</span>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Khóa học hiện có</p>
                        <span class="material-symbols-outlined text-primary">local_library</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">
                        {{ $stats['active_courses'] }}</p>
                    <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">check_circle</span>
                        <span>Đã xuất bản</span>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Yêu cầu hỗ trợ</p>
                        <span class="material-symbols-outlined text-primary">support_agent</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">
                        {{ $stats['pending_tickets'] }}</p>
                    <div
                        class="flex items-center gap-1 {{ $stats['pending_tickets'] > 0 ? 'text-orange-600' : 'text-emerald-600' }} text-xs font-bold">
                        <span
                            class="material-symbols-outlined text-xs">{{ $stats['pending_tickets'] > 0 ? 'warning' : 'check_circle' }}</span>
                        <span>{{ $stats['pending_tickets'] }} yêu cầu đang xử lý</span>
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
                        <p class="text-slate-900 dark:text-white text-4xl font-bold tracking-tight">
                            ₫{{ number_format($stats['total_revenue']) }}</p>
                        <p
                            class="{{ $stats['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-semibold text-sm flex items-center gap-1">
                            <span
                                class="material-symbols-outlined text-sm">{{ $stats['revenue_growth'] >= 0 ? 'north_east' : 'south_east' }}</span>
                            {{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}% so với tháng
                            trước
                        </p>
                    </div>
                    <div class="h-64 w-full relative pt-4">
                        <canvas id="dashboardRevenueChart"></canvas>
                    </div>
                </div>
                <!-- Recent Activity -->
                <div
                    class="flex flex-col gap-6 p-6 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h2 class="text-slate-900 dark:text-white text-lg font-bold">Hoạt động gần đây</h2>
                    <div class="flex flex-col gap-6">
                        @foreach ($activities as $activity)
                            <div class="flex gap-4">
                                <div
                                    class="size-10 shrink-0 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center text-{{ $activity['color'] }}-600">
                                    <span class="material-symbols-outlined text-sm">{{ $activity['icon'] }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $activity['title'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $activity['date']->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach

                        @if ($activities->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-slate-500 text-sm">Chưa có hoạt động nào.</p>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('admin.revenue.transactions') }}"
                        class="w-full py-2.5 rounded-lg border border-primary text-primary text-sm font-bold hover:bg-primary hover:text-white transition-all text-center">
                        Xem tất cả lịch sử GD
                    </a>
                </div>
            </div>
            <!-- Featured Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-primary/10 rounded-lg p-6 flex items-center justify-between border border-primary/20">
                    <div class="space-y-2">
                        <h3 class="text-primary font-bold">Khóa học phổ biến nhất</h3>
                        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 truncate max-w-[250px]">
                            {{ $featured['top_course'] }}</p>
                        <p class="text-sm text-slate-500">Với {{ number_format($featured['top_course_students']) }} học
                            viên đang theo học</p>
                    </div>
                    <div
                        class="size-16 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center shadow-lg text-primary">
                        <span class="material-symbols-outlined !text-4xl">workspace_premium</span>
                    </div>
                </div>
                <div class="bg-slate-900 rounded-lg p-6 flex items-center justify-between border border-slate-700">
                    <div class="space-y-2">
                        <h3 class="text-primary font-bold">Tỷ lệ hài lòng</h3>
                        <p class="text-2xl font-bold text-white">{{ number_format($featured['satisfaction_rate'], 1) }} /
                            5.0</p>
                        <p class="text-sm text-slate-400">Dữ liệu từ đánh giá hệ thống</p>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.chartData = {!! json_encode($chartData) !!};
    </script>
    @vite('resources/js/dashboard.js')
@endpush
