<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 md:relative md:translate-x-0 gap-3 w-64 shrink-0 border-r border-primary/10 bg-white dark:bg-slate-900 md:flex flex-col justify-between p-6 min-h-[calc(100vh-65px)]">
    <div class="flex flex-col gap-6">
        <div class="flex items-center gap-3 px-2">
            @if(Auth::user()->avatar)
                @php
                    $avatarUrl = str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar);
                @endphp
                <img src="{{ $avatarUrl }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover shadow-sm">
            @else
                <div class="bg-primary/20 p-2 rounded-full flex items-center justify-center w-10 h-10 shadow-sm">
                    <span class="material-symbols-outlined text-primary">person</span>
                </div>
            @endif
            <div class="flex flex-col overflow-hidden">
                <h1 class="text-slate-900 dark:text-white text-sm font-bold truncate">{{ Auth::user()->name ?? 'Teacher' }}</h1>
                <p class="text-slate-500 text-xs truncate">Giáo viên</p>
            </div>
        </div>
        <nav class="flex flex-col gap-3">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.dashboard') || request()->is('portal/teacher/dashboard') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="#">
                <span class="material-symbols-outlined">dashboard</span>
                <p class="text-sm font-semibold">Dashboard</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.classes.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="#">
                <span class="material-symbols-outlined">groups</span>
                <p class="text-sm font-medium">My Classes</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.homework.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="#">
                <span class="material-symbols-outlined">draw</span>
                <p class="text-sm font-medium">Homework Grading</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.exams.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="#">
                <span class="material-symbols-outlined">content_copy</span>
                <p class="text-sm font-medium">Exams Management</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.reports.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="#">
                <span class="material-symbols-outlined">bar_chart</span>
                <p class="text-sm font-medium">Student Reports</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('support.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('support.index') }}">
                <span class="material-symbols-outlined">support_agent</span>
                <p class="text-sm font-medium">Hỗ trợ</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('settings.index') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('settings.index') }}">
                <span class="material-symbols-outlined">settings</span>
                <p class="text-sm font-medium">Cài đặt</p>
            </a>
        </nav>
    </div>
</aside>
