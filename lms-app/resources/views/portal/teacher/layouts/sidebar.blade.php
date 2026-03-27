<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 md:relative md:translate-x-0 gap-3 w-64 shrink-0 border-r border-primary/10 bg-white dark:bg-slate-900 md:flex flex-col justify-start p-4 min-h-[calc(100vh-65px)]">
    <div class="flex gap-3 items-center pb-4 border-b border-slate-200 dark:border-slate-800">
        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-12"
            data-alt="Portrait of a female teacher smiling"
            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCNGtuwaK4n-ZJZTmNISMRSrGUkJHTzfNjFGxsoemZhvOVSnrDhxJpxyiCMXVV6Ileh_qJaGR7BxSBeAMb9kD_OLAP0HXwoHmcTjOdUoXVO_O5P9F0D5NTI0FZrFJiNITSzYfU3liwE_8tunuXE4YT5PBUm26JGS4t5qXqjssQ6wBql_lnQklof-gwjc48G2pwrr39sgnyHOUYWqFRvNTflrDFHHHgg2o1tE6dD-1HDlsOUSMDT5_vDSwW1F4zkX2lNs7i2IklksA");'>
        </div>
        <div class="flex flex-col">
            <h1 class="text-base font-bold leading-normal">Nguyễn Thị A</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium leading-normal">Giáo viên cao cấp</p>
        </div>
    </div>
    <nav class="flex flex-col gap-2 mt-5">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-primary/20 text-primary dark:text-primary"
            href="#">
            <span class="material-symbols-outlined text-[24px]">dashboard</span>
            <p class="text-sm font-semibold leading-normal">Dashboard</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100"
            href="#">
            <span class="material-symbols-outlined text-[24px]">groups</span>
            <p class="text-sm font-medium leading-normal">My Classes</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100"
            href="#">
            <span class="material-symbols-outlined text-[24px]">draw</span>
            <p class="text-sm font-medium leading-normal">Homework Grading</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100"
            href="#">
            <span class="material-symbols-outlined text-[24px]">content_copy</span>
            <p class="text-sm font-medium leading-normal">Exams Management</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100"
            href="#">
            <span class="material-symbols-outlined text-[24px]">bar_chart</span>
            <p class="text-sm font-medium leading-normal">Student Reports</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('support.*') ? 'bg-primary/20 text-primary dark:text-primary' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100' }}"
            href="{{ route('support.index') }}">
            <span class="material-symbols-outlined text-[24px]">support_agent</span>
            <p class="text-sm font-medium leading-normal">Hỗ trợ</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('settings.index') ? 'bg-primary/20 text-primary dark:text-primary' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100' }}"
            href="{{ route('settings.index') }}">
            <span class="material-symbols-outlined text-[24px]">settings</span>
            <p class="text-sm font-medium leading-normal">Cài đặt</p>
        </a>
    </nav>
</aside>
