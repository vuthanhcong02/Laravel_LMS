<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 md:relative md:translate-x-0 gap-3 w-64 shrink-0 border-r border-primary/10 bg-white dark:bg-slate-900 md:flex flex-col justify-between p-6 min-h-[calc(100vh-65px)]">
    <div class="flex flex-col gap-6">

        <nav class="flex flex-col gap-3">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.dashboard') || request()->is('portal/teacher/dashboard') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('teacher.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <p class="text-sm font-semibold">{{ __('Bảng điều khiển') }}</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.classes.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('teacher.classes.index') }}">
                <span class="material-symbols-outlined">groups</span>
                <p class="text-sm font-medium">{{ __('Lớp học') }}</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.schedules.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('teacher.schedules.index') }}">
                <span class="material-symbols-outlined">calendar_month</span>
                <p class="text-sm font-medium">{{ __('Lịch giảng dạy') }}</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.assignments.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('teacher.assignments.index') }}">
                <span class="material-symbols-outlined">assignment</span>
                <p class="text-sm font-medium">{{ __('Bài tập') }}</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.quizzes.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('teacher.quizzes.index') }}">
                <span class="material-symbols-outlined">quiz</span>
                <p class="text-sm font-medium">{{ __('Bài kiểm tra') }}</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.hsk-mock-exams.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('teacher.hsk-mock-exams.index') }}">
                <span class="material-symbols-outlined">description</span>
                <p class="text-sm font-medium">{{ __('Đề thi HSK') }}</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('teacher.reports.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('teacher.reports.index') }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <p class="text-sm font-medium">{{ __('Báo cáo') }}</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('settings.index') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('settings.index') }}">
                <span class="material-symbols-outlined">settings</span>
                <p class="text-sm font-medium">{{ __('Cài đặt') }}</p>
            </a>
        </nav>
    </div>
</aside>
