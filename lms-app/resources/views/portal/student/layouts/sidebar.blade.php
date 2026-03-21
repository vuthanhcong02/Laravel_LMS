<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 md:relative md:translate-x-0 gap-3 w-64 shrink-0 border-r border-primary/10 bg-white dark:bg-slate-900 md:flex flex-col justify-between p-4 min-h-[calc(100vh-65px)]">
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary text-white font-semibold" href="#">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-sm">Dashboard</span>
    </a>
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
        href="#">
        <span class="material-symbols-outlined">menu_book</span>
        <span class="text-sm">Khóa học của tôi</span>
    </a>
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
        href="#">
        <span class="material-symbols-outlined">assignment</span>
        <span class="text-sm">Bài tập về nhà</span>
    </a>
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
        href="#">
        <span class="material-symbols-outlined">quiz</span>
        <span class="text-sm">Kỳ thi</span>
    </a>
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
        href="#">
        <span class="material-symbols-outlined">workspace_premium</span>
        <span class="text-sm">Chứng chỉ</span>
    </a>
    <div class="pt-10 mt-auto">
        <div class="bg-primary/10 rounded-xl p-4 border border-primary/20">
            <p class="text-xs font-bold text-primary mb-1">PRO PLAN</p>
            <p class="text-[11px] text-slate-600 dark:text-slate-400 mb-3">Mở khóa tất cả bài giảng cao cấp.</p>
            <button
                class="w-full py-2 bg-primary text-white text-xs font-bold rounded-lg shadow-sm shadow-primary/40">Nâng
                cấp ngay</button>
        </div>
    </div>
</aside>
