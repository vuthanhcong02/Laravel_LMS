@extends('layouts.app')

@section('title', 'Đăng ký')

@section('breadcrumb', 'Đăng ký')

@section('content')
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-[520px] bg-white dark:bg-slate-900 rounded-lg shadow-xl shadow-primary/5 p-8 md:p-12">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold font-poppins text-slate-900 dark:text-white mb-2">Tạo tài khoản mới</h1>
                <p class="text-slate-500 dark:text-slate-400">Tham gia cộng đồng học tiếng Trung cùng XiaoMu</p>
            </div>
            <form class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Họ và tên</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">person</span>
                        <input
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-white"
                            placeholder="Nhập họ và tên của bạn" type="text" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                        <input
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-white"
                            placeholder="example@email.com" type="email" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Số điện thoại</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">call</span>
                        <input
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-white"
                            placeholder="0123 456 789" type="tel" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Mật khẩu</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
                        <input
                            class="w-full pl-12 pr-12 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-white"
                            placeholder="••••••••" type="password" />
                        <button
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                            type="button">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                </div>
                <div class="flex items-start gap-3 py-2">
                    <input
                        class="mt-1 size-4 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary"
                        id="terms" type="checkbox" />
                    <label class="text-sm text-slate-600 dark:text-slate-400 leading-tight" for="terms">
                        Tôi đồng ý với <a class="text-primary font-medium hover:underline" href="#">Điều khoản</a> và
                        <a class="text-primary font-medium hover:underline" href="#">Chính sách bảo mật</a>
                    </label>
                </div>
                <button
                    class="w-full bg-primary hover:bg-primary/90 text-slate-900 font-bold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all transform active:scale-[0.98]"
                    type="submit">
                    Đăng ký
                </button>
            </form>
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-slate-900 text-slate-500">Hoặc đăng ký bằng</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <button
                    class="flex items-center justify-center gap-2 py-3 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    <svg class="size-5" viewbox="0 0 24 24">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4"></path>
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853"></path>
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05"></path>
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335"></path>
                    </svg>
                    <span class="text-sm font-semibold">Google</span>
                </button>
                <button
                    class="flex items-center justify-center gap-2 py-3 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    <svg class="size-5 text-[#1877F2]" fill="currentColor" viewbox="0 0 24 24">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z">
                        </path>
                    </svg>
                    <span class="text-sm font-semibold">Facebook</span>
                </button>
            </div>
            <div class="mt-10 text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Bạn đã có tài khoản?
                    <a class="text-primary font-bold hover:underline ml-1" href="{{ route('login') }}">Đăng nhập</a>
                </p>
            </div>
        </div>
    </main>
@endsection

@vite(['resources/js/register.js'])
