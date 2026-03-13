@extends('layouts.app')

@section('title', 'Lộ Trình Học HSK')

@section('breadcrumb', 'Lộ Trình Học HSK')

@section('content')
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-12">
            <div class="relative grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-12 items-start">
                <div class="relative space-y-12 pb-20">
                    <div
                        class="absolute left-8 -translate-x-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-primary via-primary/50 to-transparent rounded-full hidden md:block">
                    </div>
                    <!-- HSK 1 -->
                    <div class="relative flex flex-col md:flex-row gap-8 items-start group md:pl-24">
                        <div
                            class="hidden md:flex absolute left-8 -translate-x-1/2 w-8 h-8 rounded-full bg-white dark:bg-background-dark border-4 border-primary z-10 items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                        </div>
                        <div
                            class="flex-1 bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 group-hover:bg-primary/10 transition-colors">
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div class="space-y-1">
                                    <span class="text-primary font-bold text-sm tracking-widest uppercase">Cấp độ
                                        01</span>
                                    <h3 class="font-heading text-2xl font-bold">HSK 1: Nhập môn</h3>
                                </div>
                                <div class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold">
                                    1-2 Tháng
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">translate</span>
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Từ vựng</p>
                                        <p class="font-semibold">150 từ vựng cốt lõi</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">psychology</span>
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Kỹ năng</p>
                                        <p class="font-semibold">Phiên âm &amp; Chào hỏi</p>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="w-full bg-primary/10 hover:bg-primary text-primary hover:text-white py-3 rounded-lg font-bold transition-all flex items-center justify-center gap-2">
                                Xem khóa học
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- HSK 2 -->
                    <div class="relative flex flex-col md:flex-row gap-8 items-start group md:pl-24">
                        <div
                            class="hidden md:flex absolute left-8 -translate-x-1/2 w-8 h-8 rounded-full bg-white dark:bg-background-dark border-4 border-primary z-10 items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                        </div>
                        <div
                            class="flex-1 bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300 relative overflow-hidden">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div class="space-y-1">
                                    <span class="text-primary font-bold text-sm tracking-widest uppercase">Cấp độ
                                        02</span>
                                    <h3 class="font-heading text-2xl font-bold">HSK 2: Giao tiếp cơ bản</h3>
                                </div>
                                <div class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold">
                                    2-3 Tháng
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">forum</span>
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Từ vựng</p>
                                        <p class="font-semibold">300 từ vựng</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">local_library</span>
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Kỹ năng</p>
                                        <p class="font-semibold">Hội thoại sinh hoạt</p>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="w-full bg-primary text-white py-3 rounded-lg font-bold shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2">
                                Xem khóa học
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- HSK 3 -->
                    <div class="relative flex flex-col md:flex-row gap-8 items-start group md:pl-24">
                        <div
                            class="hidden md:flex absolute left-8 -translate-x-1/2 w-8 h-8 rounded-full bg-white dark:bg-background-dark border-4 border-primary z-10 items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                        </div>
                        <div
                            class="flex-1 bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300 relative overflow-hidden">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div class="space-y-1">
                                    <span class="text-primary font-bold text-sm tracking-widest uppercase">Cấp độ
                                        03</span>
                                    <h3 class="font-heading text-2xl font-bold">HSK 3: Trung cấp thấp</h3>
                                </div>
                                <div class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold">
                                    3-4 Tháng
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">edit_note</span>
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Từ vựng</p>
                                        <p class="font-semibold">600 từ vựng</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">menu_book</span>
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Kỹ năng
                                        </p>
                                        <p class="font-semibold">Đọc hiểu đoạn văn</p>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="w-full bg-primary/10 hover:bg-primary text-primary hover:text-white py-3 rounded-lg font-bold transition-all flex items-center justify-center gap-2">
                                Xem khóa học
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- HSK 4-6 Simplified Cards for Roadmap display -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:pl-24">
                        <div
                            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 text-center hover:border-primary transition-colors">
                            <p class="text-primary font-black text-xl mb-1">HSK 4</p>
                            <p class="text-xs font-bold text-slate-500 mb-4">1200 Từ</p>
                            <button class="text-xs font-bold bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-lg">Chi
                                tiết</button>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 text-center hover:border-primary transition-colors">
                            <p class="text-primary font-black text-xl mb-1">HSK 5</p>
                            <p class="text-xs font-bold text-slate-500 mb-4">2500 Từ</p>
                            <button class="text-xs font-bold bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-lg">Chi
                                tiết</button>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 text-center hover:border-primary transition-colors">
                            <p class="text-primary font-black text-xl mb-1">HSK 6</p>
                            <p class="text-xs font-bold text-slate-500 mb-4">5000+ Từ</p>
                            <button class="text-xs font-bold bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-lg">Chi
                                tiết</button>
                        </div>
                    </div>
                </div>
                <aside class="sticky top-32 space-y-8">
                    <div class="relative bg-primary/10 rounded-xl p-8 overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="font-heading text-2xl font-bold mb-4">Trình độ của bạn?</h4>
                            <p class="text-slate-600 dark:text-slate-400 mb-6">Bạn không biết mình đang ở đâu trên lộ
                                trình này? Làm bài kiểm tra nhanh 5 phút.</p>
                            <button
                                class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 py-3 rounded-lg font-bold hover:scale-[1.02] transition-transform">
                                Kiểm tra ngay
                            </button>
                        </div>
                        <div class="absolute -bottom-4 -right-4 text-primary/20">
                            <span class="material-symbols-outlined text-[100px]">assignment</span>
                        </div>
                    </div>
                    <div class="rounded-xl overflow-hidden shadow-xl border border-primary/20">
                        <img class="w-full h-48 object-cover"
                            data-alt="Student studying with Chinese calligraphy background"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuARGlfUl_muDFey40kJohKUmTOIE7ZrrKhzDnQbsnQh-R4LqFr1Hk_PHwDa62vR5qzVHvRn4ksRaLu2amWxc4gmrEzqsyC445K4BEXdtYUHBeEIyL5fGZoC9-X6KqYvxI2BJXvFVa1nDJ5NAF9-iIs0heQINyN26h8qjYWCNxFlWT4J_guLyQKH7yts5eevhfs0XCNIu4A3-QjzXnmDfxU4geobTvYguYm6Bosalj5Nwz9_S1gqa2djawBSLgwxN-1u-vDTNcPaqA" />
                        <div class="bg-white dark:bg-slate-900 p-6">
                            <h4 class="font-bold mb-2">Tư vấn lộ trình 1-1</h4>
                            <p class="text-sm text-slate-500 mb-4">Đội ngũ giảng viên XiaoMu luôn sẵn sàng hỗ trợ bạn
                                thiết kế lộ trình riêng biệt.</p>
                            <a class="text-primary font-bold text-sm flex items-center gap-2 hover:underline"
                                href="#">
                                Liên hệ tư vấn
                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <div
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-4 py-2 rounded-full flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs font-bold">1.2k Đang học</span>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-4 py-2 rounded-full flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-primary">verified</span>
                            <span class="text-xs font-bold">Chứng chỉ HSK</span>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
@endsection
