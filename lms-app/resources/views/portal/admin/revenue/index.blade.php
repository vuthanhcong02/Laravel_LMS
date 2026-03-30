@extends('portal.layouts.dashboard')

@section('title', 'Quản lý Doanh thu')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1200px] mx-auto space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Thống kê Doanh thu</h1>
                    <p class="text-slate-500 text-sm mt-1">Tổng quan về tình hình tài chính của hệ thống.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.revenue.transactions') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-200 hover:border-primary hover:text-primary transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">history</span>
                        Lịch sử giao dịch
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tổng doanh thu</p>
                        <span class="material-symbols-outlined text-emerald-500">payments</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-2xl font-bold leading-tight">
                        ₫{{ number_format($stats['total_revenue']) }}</p>
                    <div class="text-slate-500 text-xs">Từ {{ $stats['total_transactions'] }} giao dịch</div>
                </div>

                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tháng này</p>
                        <span class="material-symbols-outlined text-blue-500">calendar_month</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-2xl font-bold leading-tight">
                        ₫{{ number_format($stats['this_month_revenue']) }}</p>
                    <div
                        class="flex items-center gap-1 {{ $stats['growth_percentage'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} text-xs font-bold">
                        <span
                            class="material-symbols-outlined text-xs">{{ $stats['growth_percentage'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                        <span>{{ $stats['growth_percentage'] >= 0 ? '+' : '' }}{{ $stats['growth_percentage'] }}% so với
                            tháng trước</span>
                    </div>
                </div>

                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Giá trị TB/Đơn</p>
                        <span class="material-symbols-outlined text-amber-500">barcode_scanner</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-2xl font-bold leading-tight">
                        ₫{{ number_format($stats['avg_order_value']) }}</p>
                    <div class="text-slate-500 text-xs">Trung bình mỗi thanh toán</div>
                </div>

                <div
                    class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tỷ lệ tăng trưởng</p>
                        <span class="material-symbols-outlined text-purple-500">auto_graph</span>
                    </div>
                    <p class="text-slate-900 dark:text-white text-2xl font-bold leading-tight">
                        {{ $stats['growth_percentage'] }}%</p>
                    <div class="text-slate-500 text-xs">Biến động doanh thu tháng</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Chart Area -->
                <div
                    class="lg:col-span-2 flex flex-col gap-6 p-6 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-slate-900 dark:text-white text-lg font-bold">Xu hướng Doanh thu</h2>
                            <p class="text-slate-500 text-sm">Biểu đồ biến động 30 ngày gần nhất</p>
                        </div>
                    </div>
                    <div class="w-full relative min-h-[300px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Top Courses -->
                <div
                    class="flex flex-col gap-6 p-6 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
                        <h2 class="text-slate-900 dark:text-white text-lg font-bold">Khóa học doanh thu cao</h2>
                    </div>
                    <div class="flex flex-col gap-5">
                        @foreach ($topCourses as $course)
                            <div class="flex items-center justify-between group">
                                <div class="flex flex-col gap-0.5 max-w-[70%]">
                                    <p
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate group-hover:text-primary transition-colors">
                                        {{ $course->course->title }}</p>
                                    <p class="text-xs text-slate-500">Phổ biến</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">
                                        ₫{{ number_format($course->total) }}</p>
                                    <p class="text-[10px] text-emerald-500 font-bold uppercase">Success</p>
                                </div>
                            </div>
                        @endforeach

                        @if ($topCourses->isEmpty())
                            <div class="text-center py-8">
                                <span class="material-symbols-outlined text-slate-300 text-4xl">inventory_2</span>
                                <p class="text-slate-500 text-sm mt-2">Chưa có dữ liệu khóa học</p>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('admin.courses.index') }}"
                        class="w-full py-2.5 rounded-lg border border-primary text-primary text-xs font-bold hover:bg-primary hover:text-white transition-all text-center">
                        Xem tất cả khóa học
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            const labels = {!! json_encode($chartData['labels']) !!};
            const data = {!! json_encode($chartData['values']) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu (₫)',
                        data: data,
                        borderWidth: 3,
                        borderColor: '#8fc0e0',
                        backgroundColor: 'rgba(143, 192, 224, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#8fc0e0',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            padding: 12,
                            backgroundColor: '#1e293b',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(226, 232, 240, 0.5)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + 'M';
                                    if (value >= 1000) return (value / 1000) + 'K';
                                    return value;
                                },
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
