<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
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
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('support.*') ? 'bg-primary/20 text-primary dark:text-primary' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100' }}"
        href="{{ route('support.index') }}">
        <span class="material-symbols-outlined">support_agent</span>
        <span class="text-sm">Hỗ trợ</span>
    </a>
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('settings.index') ? 'bg-primary/20 text-primary dark:text-primary' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100' }}"
        href="{{ route('settings.index') }}">
        <span class="material-symbols-outlined">settings</span>
        <span class="text-sm">Cài đặt</span>
    </a>
</aside>
