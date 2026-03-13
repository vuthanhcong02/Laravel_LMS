@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('breadcrumb', 'Đặt lại mật khẩu')

@section('content')
    <main class="flex-grow flex items-center justify-center p-4">
        <div
            class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-xl shadow-xl shadow-primary/5 border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-8 pb-4">
                <div class="flex justify-center mb-6">
                    <div class="bg-primary/10 p-4 rounded-full">
                        <span class="material-symbols-outlined text-primary text-4xl">lock_reset</span>
                    </div>
                </div>
                <div class="text-center">
                    <h1 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-2">Đặt lại mật khẩu</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Vui lòng nhập mật khẩu mới của bạn để bảo mật tài
                        khoản.</p>
                </div>
            </div>
            <form class="p-8 pt-4 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mật khẩu mới</label>
                    <div class="relative">
                        <input
                            class="w-full h-12 px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all pr-12 text-slate-900 dark:text-white placeholder:text-slate-400"
                            placeholder="Nhập mật khẩu mới" type="password" />
                        <button
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center"
                            type="button">
                            <span class="material-symbols-outlined" data-icon="visibility">visibility</span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Xác nhận mật khẩu
                        mới</label>
                    <div class="relative">
                        <input
                            class="w-full h-12 px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all pr-12 text-slate-900 dark:text-white placeholder:text-slate-400"
                            placeholder="Nhập lại mật khẩu mới" type="password" />
                        <button
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center"
                            type="button">
                            <span class="material-symbols-outlined" data-icon="visibility">visibility</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-4 pt-2">
                    <button
                        class="w-full bg-primary hover:bg-primary/90 text-slate-900 font-bold h-12 rounded-lg transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                        type="submit">
                        Cập nhật mật khẩu
                    </button>
                    <a class="block text-center text-sm font-medium text-slate-500 hover:text-primary transition-colors"
                        href="#">
                        Quay lại trang Đăng nhập
                    </a>
                </div>
            </form>
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-xl">info</span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Mật khẩu nên chứa ít nhất 8 ký tự, bao gồm chữ cái in hoa, chữ thường và ít nhất một chữ số.
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection
