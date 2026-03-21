<header
    class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-primary/20 bg-white dark:bg-slate-900 px-6 py-3 lg:px-10">
    <div class="flex items-center gap-8">
        <div class="flex items-center gap-3 text-primary">
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden flex items-center justify-center p-2 -ml-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined">school</span>
            </div>
            <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">XiaoMu Chinese LMS
            </h2>
        </div>
        <label class="hidden md:flex flex-col min-w-40 !h-10 max-w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full overflow-hidden">
                <div
                    class="text-slate-500 flex border-none bg-slate-100 dark:bg-slate-800 items-center justify-center pl-4">
                    <span class="material-symbols-outlined text-sm">search</span>
                </div>
                <input
                    class="form-input flex w-full min-w-0 flex-1 border-none bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-0 focus:ring-0 h-full placeholder:text-slate-500 px-4 text-sm font-normal"
                    placeholder="Search data..." />
            </div>
        </label>
    </div>
    <div class="flex flex-1 justify-end gap-6 items-center">
        <nav class="hidden lg:flex items-center gap-8">
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="#">Home</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="#">Settings</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="#">Support</a>
        </nav>
        <div class="flex gap-3">
            <button
                class="flex items-center justify-center rounded-lg size-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary/20 transition-colors">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary"
                data-alt="User profile avatar with professional look"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuANa1uMhA3r5EsreKdEqEd8qLsFKsB5L0VOg45J6FG_gVQCaRA5FpHn7jMde_CY1Kq96Eju1aR6Z-nzwJJSRxSZYANLYxjeyk8dMsgk49J5OKmicQhdXLZzvUlHOCROE2hXxdst3Cm-zGzO74rSHye63bIxm2hGqjzkBMh7BbCfhpXJzxi046SHvlBt97T9pAMFpnHFJA1SJ_YKd_grhNBdNHVXtDpK29ssp1QVNoKXnn05M9IZH4e6Ff1zJ6aVxru_vavzp_S_2w");'>
            </div>
        </div>
    </div>
</header>
