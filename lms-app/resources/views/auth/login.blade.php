@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('breadcrumb', 'Đăng nhập')

@section('content')
    <main class="flex-1 flex items-center justify-center p-6 relative overflow-hidden mb-24">
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
            <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
        </div>
        <div
            class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-xl shadow-xl shadow-primary/5 border border-primary/5 dark:border-slate-700/50 p-5 sm:p-8 md:p-10 z-10">
            <div class="flex flex-col items-center text-center mb-6 sm:mb-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-poppins font-bold text-slate-900 dark:text-white tracking-tight">Chào mừng quay trở lại!</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 sm:mt-2">Vui lòng đăng nhập để tiếp tục học tập.</p>
            </div>
            @if (session('status'))
                <div class="mb-4 font-medium text-xs sm:text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 font-medium text-xs sm:text-sm text-red-600">
                    {{ session('error') }}
                </div>
            @endif
            <form class="space-y-4 sm:space-y-5" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="flex flex-col gap-1.5 sm:gap-2">
                    <label class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm font-semibold px-1">Email</label>
                    <input name="email" value="{{ old('email') }}"
                        class="w-full h-11 sm:h-12 px-3.5 sm:px-4 rounded-lg border @error('email') border-red-500 @else border-slate-200 dark:border-slate-600 @enderror bg-white dark:bg-slate-700 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none text-xs sm:text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500"
                        placeholder="example@gmail.com" type="email" required autofocus />
                    @error('email')
                        <p class="mt-1 text-xs sm:text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-1.5 sm:gap-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm font-semibold">Mật khẩu</label>
                        <a class="text-primary text-xs font-bold hover:underline"
                            href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    </div>
                    <div class="relative flex items-center">
                        <input name="password" id="password_input"
                            class="w-full h-11 sm:h-12 px-3.5 sm:px-4 pr-11 rounded-lg border @error('password') border-red-500 @else border-slate-200 dark:border-slate-600 @enderror bg-white dark:bg-slate-700 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none text-xs sm:text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500"
                            placeholder="••••••••" type="password" required />
                        <button class="absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-primary transition-colors focus:outline-none"
                            onclick="togglePasswordVisibility('password_input', this)"
                            type="button" aria-label="Hiện/ẩn mật khẩu">
                            <span class="material-symbols-outlined text-[18px] sm:text-[20px] select-none">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs sm:text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center gap-2.5 px-1 py-1">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="size-4 shrink-0 text-primary focus:ring-primary border-slate-300 rounded cursor-pointer">
                    <label for="remember_me" class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 font-medium cursor-pointer select-none">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                <button
                    class="w-full h-11 sm:h-12 bg-primary hover:bg-primary/90 text-white text-xs sm:text-sm font-bold rounded-2xl transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2 mt-2"
                    type="submit">
                    <span>Đăng nhập</span>
                </button>
            </form>
            <div class="relative my-6 sm:my-8">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t border-slate-200 dark:border-slate-600"></span>
                </div>
                <div class="relative flex justify-center text-[11px] sm:text-xs uppercase">
                    <span class="bg-white dark:bg-slate-900 px-3 text-slate-400 dark:text-slate-500 font-medium">Hoặc đăng nhập bằng</span>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('socialite.redirect', ['provider' => 'google']) }}"
                    class="flex items-center justify-center gap-2 h-10 sm:h-11 border border-slate-200 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors font-semibold text-xs sm:text-sm">
                    <svg class="size-4 sm:size-5" viewbox="0 0 24 24">
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
                    <span class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm font-semibold">Google</span>
                </a>
            </div>
            <div class="mt-6 sm:mt-8 text-center text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Bạn chưa có tài khoản?
                <a class="text-primary font-bold hover:underline" href="{{ route('register') }}">Đăng ký ngay</a>
            </div>
        </div>
    </main>
@endsection
