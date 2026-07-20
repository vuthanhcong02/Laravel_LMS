@extends('layouts.app')

@section('title', 'Liên hệ')

@section('breadcrumb', 'Liên hệ')

@section('content')
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-12 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Left Column: Info -->
            <div class="flex flex-col gap-8">
                <div class="space-y-6">
                    <h2 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-6">Thông tin liên hệ</h2>
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
                            <p class="text-slate-600 dark:text-slate-400">Thứ 2 - Chủ Nhật: 8:00 - 24:00</p>
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
                        <svg class="w-5 h-5" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.22-1.15 4.39-2.92 5.74-1.73 1.3-4.04 1.76-6.14 1.25-2.19-.51-4.02-1.92-4.99-3.95-.97-2.02-.97-4.4.01-6.4 1.01-2.07 3.01-3.61 5.27-3.95.83-.13 1.68-.11 2.5-.02v4.06c-.4-.02-.8-.02-1.2-.02-1.07.03-2.15.42-2.92 1.17-.74.72-1.14 1.75-1.13 2.8.01 1.05.42 2.07 1.15 2.78.75.74 1.83 1.1 2.88 1.04 1.05-.05 2.05-.51 2.75-1.28.69-.76 1.04-1.8 1.03-2.85V.02z" />
                        </svg>
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
                            <span class="text-sm font-semibold">Hà Nội</span>
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
                <div id="success-message" class="hidden mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <p class="font-medium text-sm"></p>
                </div>
                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Họ và
                            tên</label>
                        <input name="name" id="name"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                            placeholder="Nhập họ tên của bạn" type="text" required />
                        <p id="error-name" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                            <input name="email" id="email"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                placeholder="email@example.com" type="email" required />
                            <p id="error-email" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Số
                                điện thoại</label>
                            <input name="phone" id="phone"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                placeholder="09xx xxx xxx" type="tel" />
                            <p id="error-phone" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Chủ đề liên
                            hệ</label>
                        <div class="flex flex-wrap gap-2.5">
                            <label class="cursor-pointer">
                                <input type="checkbox" name="topics[]" value="tu-van" class="peer sr-only" />
                                <div
                                    class="px-4 py-2 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm font-semibold text-slate-600 dark:text-slate-400 peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:border-primary hover:border-primary/40 transition-all">
                                    Tư vấn khóa học
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="checkbox" name="topics[]" value="ho-tro" class="peer sr-only" />
                                <div
                                    class="px-4 py-2 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm font-semibold text-slate-600 dark:text-slate-400 peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:border-primary hover:border-primary/40 transition-all">
                                    Hỗ trợ kỹ thuật
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="checkbox" name="topics[]" value="gop-y" class="peer sr-only" checked />
                                <div
                                    class="px-4 py-2 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm font-semibold text-slate-600 dark:text-slate-400 peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:border-primary hover:border-primary/40 transition-all">
                                    Góp ý & Phản hồi
                                </div>
                            </label>
                        </div>
                        <p id="error-topics" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lời
                            nhắn</label>
                        <textarea name="message" id="message" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none"
                            placeholder="Bạn có thắc mắc gì cho chúng tôi không?" rows="4"></textarea>
                        <p id="error-message" class="text-red-500 text-xs mt-1 hidden"></p>
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
