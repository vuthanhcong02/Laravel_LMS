<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 md:relative md:translate-x-0 gap-3 w-64 shrink-0 border-r border-primary/10 bg-white dark:bg-slate-900 md:flex flex-col justify-between p-6 min-h-[calc(100vh-65px)]">
    <div class="flex flex-col gap-6">
        <div class="flex items-center gap-3 px-2">
            <div class="bg-primary/20 p-2 rounded-lg">
                <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
            </div>
            <div class="flex flex-col">
                <h1 class="text-slate-900 dark:text-white text-sm font-bold">XiaoMu Admin</h1>
                <p class="text-slate-500 text-xs">System Control</p>
            </div>
        </div>
        <nav class="flex flex-col gap-3">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <p class="text-sm font-semibold">Dashboard</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.users.index') }}">
                <span class="material-symbols-outlined">group</span>
                <p class="text-sm font-medium">User Management</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.courses.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.courses.index') }}">
                <span class="material-symbols-outlined">school</span>
                <p class="text-sm font-medium">Course Management</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.hsk-mock-exams.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.hsk-mock-exams.index') }}">
                <span class="material-symbols-outlined">quiz</span>
                <p class="text-sm font-medium">HSK Mock Exams</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.blogs.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.blogs.index') }}">
                <span class="material-symbols-outlined">article</span>
                <p class="text-sm font-medium">Blog Management</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.revenue.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.revenue.index') }}">
                <span class="material-symbols-outlined">payments</span>
                <p class="text-sm font-medium">Revenue</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.support.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.support.index') }}">
                <span class="material-symbols-outlined">support_agent</span>
                <p class="text-sm font-medium">Support</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.contacts.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.contacts.index') }}">
                <span class="material-symbols-outlined">contact_mail</span>
                <p class="text-sm font-medium">Contacts</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.backup.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.backup.index') }}">
                <span class="material-symbols-outlined">database</span>
                <p class="text-sm font-medium">Database Backup</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.monitoring.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('admin.monitoring.index') }}">
                <span class="material-symbols-outlined">monitor_heart</span>
                <p class="text-sm font-medium">Real-time Pulse</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('settings.index') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('settings.index') }}">
                <span class="material-symbols-outlined">settings</span>
                <p class="text-sm font-medium">Settings</p>
            </a>
        </nav>
    </div>
</aside>
