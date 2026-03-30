<header
    class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-primary/20 bg-white dark:bg-slate-900 px-6 py-3 lg:px-10">
    <div class="flex items-center gap-8">
        <div class="flex items-center gap-3 text-primary">
            <button @click="sidebarOpen = !sidebarOpen"
                class="md:hidden flex items-center justify-center p-2 -ml-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined">school</span>
            </div>
            <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">XiaoMu Chinese LMS
            </h2>
        </div>
    </div>
    <div class="flex flex-1 justify-end gap-6 items-center">
        <nav class="hidden lg:flex items-center gap-8">
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="#">Home</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="#">Support</a>
        </nav>
        <div class="flex gap-3">
            {{-- Notifications Dropdown --}}
            <div class="relative" x-data="{ notifOpen: false }">
                <button @click="notifOpen = !notifOpen"
                    class="relative flex items-center justify-center rounded-lg size-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span
                        class="absolute top-2.5 right-2.5 size-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-800"></span>
                </button>

                {{-- Notification Popup --}}
                <div x-show="notifOpen" @click.outside="notifOpen = false"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute -right-16 md:right-0 mt-2 w-80 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg z-50 overflow-hidden flex flex-col"
                    style="display: none;">
                    <div
                        class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">Thông báo</p>
                        <a href="#" class="text-xs text-primary font-medium hover:underline">Đánh dấu đã đọc</a>
                    </div>
                    <div class="max-h-[320px] overflow-y-auto">
                        {{-- Dummy Notification Item 1 --}}
                        <a href="#"
                            class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800/50 relative">
                            <div class="size-2 bg-primary rounded-full absolute left-1.5 top-4"></div>
                            <div
                                class="size-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-xl">campaign</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Nhắc nhở học tập</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">Bạn có một bài
                                    kiểm tra cho khóa HSK 2 sắp diễn ra vào ngày mai.</p>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Vừa xong</p>
                            </div>
                        </a>
                        {{-- Dummy Notification Item 2 --}}
                        <a href="#"
                            class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800/50">
                            <div
                                class="size-10 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-xl">workspace_premium</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Chứng nhận mới</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">Bạn vừa nhận
                                    được chứng chỉ hoàn thành khóa HSK 1.</p>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">3 giờ trước</p>
                            </div>
                        </a>
                        {{-- Dummy Notification Item 3 --}}
                        <a href="#"
                            class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800/50">
                            <div
                                class="size-10 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-xl">forum</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Phản hồi từ giáo viên
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">Giáo viên đã
                                    trả lời câu hỏi của bạn trong bài 3.</p>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">2 ngày trước</p>
                            </div>
                        </a>
                    </div>
                    <a href="#"
                        class="block px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-center text-sm text-primary font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors border-t border-slate-100 dark:border-slate-800">Xem
                        tất cả thông báo</a>
                </div>
            </div>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary"
                data-alt="User profile avatar with professional look"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuANa1uMhA3r5EsreKdEqEd8qLsFKsB5L0VOg45J6FG_gVQCaRA5FpHn7jMde_CY1Kq96Eju1aR6Z-nzwJJSRxSZYANLYxjeyk8dMsgk49J5OKmicQhdXLZzvUlHOCROE2hXxdst3Cm-zGzO74rSHye63bIxm2hGqjzkBMh7BbCfhpXJzxi046SHvlBt97T9pAMFpnHFJA1SJ_YKd_grhNBdNHVXtDpK29ssp1QVNoKXnn05M9IZH4e6Ff1zJ6aVxru_vavzp_S_2w");'>
            </div>
        </div>
    </div>
</header>
