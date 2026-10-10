<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 md:relative md:translate-x-0 gap-3 w-64 shrink-0 border-r border-primary/10 bg-white dark:bg-slate-900 md:flex flex-col justify-between p-6 min-h-[calc(100vh-65px)]">
    <div class="flex flex-col gap-6">
        <nav class="flex flex-col gap-3">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.dashboard') || request()->is('portal/student/dashboard') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <p class="text-sm font-semibold">{{ __('Bảng điều khiển') }}</p>
            </a>
            
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.courses.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('student.courses.index') }}">
                <span class="material-symbols-outlined">menu_book</span>
                <p class="text-sm font-medium">{{ __('Khóa học của bạn') }}</p>
            </a>
            
            <a class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.assignments.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('student.assignments.index') }}">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">assignment</span>
                    <p class="text-sm font-medium">{{ __('Bài tập về nhà') }}</p>
                </div>
                @if(!empty($pendingAssignmentsCount) && $pendingAssignmentsCount > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[11px] font-bold text-white bg-red-500 rounded-full shadow-xs {{ request()->routeIs('student.assignments.*') ? 'ring-2 ring-white/50' : '' }}">
                        {{ $pendingAssignmentsCount > 99 ? '99+' : $pendingAssignmentsCount }}
                    </span>
                @endif
            </a>
            
            <a class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.quizzes.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('student.quizzes.index') }}">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">quiz</span>
                    <p class="text-sm font-medium">{{ __('Bài kiểm tra') }}</p>
                </div>
                @if(!empty($pendingQuizzesCount) && $pendingQuizzesCount > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[11px] font-bold text-white bg-red-500 rounded-full shadow-xs {{ request()->routeIs('student.quizzes.*') ? 'ring-2 ring-white/50' : '' }}">
                        {{ $pendingQuizzesCount > 99 ? '99+' : $pendingQuizzesCount }}
                    </span>
                @endif
            </a>

            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('settings.index') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                href="{{ route('settings.index') }}">
                <span class="material-symbols-outlined">settings</span>
                <p class="text-sm font-medium">{{ __('Cài đặt') }}</p>
            </a>
        </nav>
    </div>
</aside>


