<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-4 px-6 lg:px-10 mt-auto">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-sm text-slate-500 dark:text-slate-400">
            &copy; {{ date('Y') }} <span class="font-semibold text-primary">{{ config('app.name', 'Tiếng Trung XiaoMu') }}</span>. All rights
            reserved.
        </div>
        <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
            <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
        </div>
    </div>
</footer>
