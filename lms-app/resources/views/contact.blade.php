@extends('layouts.app')

@section('title', 'Liên hệ')

@section('breadcrumb', 'Liên hệ')

@section('content')
    <main class="flex-1 max-w-[1200px] mx-auto w-full px-6 py-12 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Left Column: Info -->
            <div class="flex flex-col gap-8">
                <div class="space-y-6">
                    <h2 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-6">Thông tin liên hệ</h2>
                    <!-- Contact Item -->
                    <div
                        class="flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="bg-primary/20 text-primary p-3 rounded-lg">
                            <span class="material-symbols-outlined text-2xl">location_on</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white">Địa chỉ</p>
                            <p class="text-slate-600 dark:text-slate-400">Số 15, Ngõ 10, Giao thông vận tải, Hà Nội</p>
                        </div>
                    </div>
                    <!-- Contact Item -->
                    <div
                        class="flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="bg-primary/20 text-primary p-3 rounded-lg">
                            <span class="material-symbols-outlined text-2xl">call</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white">Số điện thoại</p>
                            <p class="text-slate-600 dark:text-slate-400">+84 123 456 789</p>
                        </div>
                    </div>
                    <!-- Contact Item -->
                    <div
                        class="flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="bg-primary/20 text-primary p-3 rounded-lg">
                            <span class="material-symbols-outlined text-2xl">mail</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white">Email</p>
                            <p class="text-slate-600 dark:text-slate-400">info@xiaomu.vn</p>
                        </div>
                    </div>
                    <!-- Contact Item -->
                    <div
                        class="flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="bg-primary/20 text-primary p-3 rounded-lg">
                            <span class="material-symbols-outlined text-2xl">schedule</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white">Thời gian làm việc</p>
                            <p class="text-slate-600 dark:text-slate-400">Thứ 2 - Thứ 7: 8:00 - 21:00</p>
                        </div>
                    </div>
                </div>
                <!-- Social Links -->
                <div class="flex gap-4">
                    <a class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-primary hover:text-white transition-all"
                        href="#">
                        <svg class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z">
                            </path>
                        </svg>
                    </a>
                    <a class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-primary hover:text-white transition-all"
                        href="#">
                        <span class="material-symbols-outlined">chat</span>
                    </a>
                    <a class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-primary hover:text-white transition-all"
                        href="#">
                        <svg class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z">
                            </path>
                        </svg>
                    </a>
                </div>
                <!-- Map Placeholder -->
                <div
                    class="rounded-xl overflow-hidden shadow-md border border-slate-200 dark:border-slate-700 h-64 relative bg-slate-200 dark:bg-slate-800">
                    <div class="absolute inset-0 bg-cover bg-center"
                        data-alt="Bản đồ vị trí trung tâm XiaoMu Chinese tại Hà Nội" data-location="Hanoi, Vietnam"
                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAerVm8IvFEnHlrorc8Fd5FXdV9x_BhouRV9TaD-Yexe0usm7ueKdZ6AxYFWQrjlGwa8yZ3D22FFp1WXSz0nT6vwUjJl_hoPHcTJNpnVJKtjawZJ02Yqw0rEBSjldTQQtiNrCQ5AbGDoAXoRBwlDtbAtYCWOkqBtx6IdZygnK9I4qH81zYf9F6QGGKvrY3v4kVrSZZcTPgcn52jFQqVfBTXE84N9iewzW8-cx3sUOgpWhDPGjBj5o2gKRxCo3fBlxcK34xB7pCKxQ');">
                    </div>
                    <div class="absolute inset-0 bg-primary/10 flex items-center justify-center pointer-events-none">
                        <div
                            class="bg-white/90 dark:bg-slate-900/90 px-4 py-2 rounded-full shadow-lg border border-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-500">location_on</span>
                            <span class="text-sm font-semibold">Giao thông vận tải, Hà Nội</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Column: Form Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 p-8">
                <h2 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-2">Gửi tin nhắn cho chúng tôi
                </h2>
                <p class="text-slate-600 dark:text-slate-400 mb-8">Điền thông tin bên dưới, chuyên viên tư vấn
                    sẽ liên hệ lại ngay.</p>
                <form class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Họ và
                            tên</label>
                        <input
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                            placeholder="Nhập họ tên của bạn" type="text" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                            <input
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                placeholder="email@example.com" type="email" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Số
                                điện thoại</label>
                            <input
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                placeholder="09xx xxx xxx" type="tel" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Khóa
                            học quan tâm</label>
                        <select
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                            <option disabled="" selected="" value="">Chọn trình độ HSK</option>
                            <option value="hsk1">HSK 1 - Sơ cấp</option>
                            <option value="hsk2">HSK 2 - Sơ cấp</option>
                            <option value="hsk3">HSK 3 - Trung cấp</option>
                            <option value="hsk4">HSK 4 - Trung cấp</option>
                            <option value="hsk5">HSK 5 - Cao cấp</option>
                            <option value="hsk6">HSK 6 - Cao cấp</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lời
                            nhắn</label>
                        <textarea
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none"
                            placeholder="Bạn có thắc mắc gì cho chúng tôi không?" rows="4"></textarea>
                    </div>
                    <button
                        class="w-full py-4 bg-primary text-white font-bold rounded-lg text-lg hover:opacity-90 shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2"
                        type="submit">
                        <span class="material-symbols-outlined">send</span>
                        Gửi tin nhắn
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection
@vite(['resources/js/contact.js'])
