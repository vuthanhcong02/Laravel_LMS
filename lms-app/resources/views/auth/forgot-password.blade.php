@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('breadcrumb', 'Quên mật khẩu')

@section('content')
    <main class="flex-1 flex items-center justify-center p-6 relative overflow-hidden mb-24">
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
            <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
        </div>
        <div
            class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-xl shadow-xl shadow-primary/5 border border-primary/5 dark:border-slate-700/50 p-5 sm:p-8 md:p-12 z-10">
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold font-poppins text-slate-900 dark:text-white mb-2">Quên mật khẩu?
                </h1>
                <p class="text-xs sm:text-sm md:text-base text-slate-500 dark:text-slate-400 leading-relaxed">
                    Nhập email của bạn để nhận hướng dẫn khôi phục mật khẩu.
                </p>
            </div>
            @if (session('status'))
                <div
                    class="mb-4 font-medium text-xs sm:text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200 text-center">
                    {{ session('status') }}
                </div>
            @endif
            <form class="space-y-4 sm:space-y-6" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Email</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-3.5 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg sm:text-xl">mail</span>
                        <input name="email" value="{{ old('email') }}"
                            class="w-full pl-10 sm:pl-12 pr-3.5 sm:pr-4 py-2.5 sm:py-3.5 bg-slate-50 dark:bg-slate-800 border @error('email') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-xs sm:text-sm text-slate-900 dark:text-white"
                            placeholder="Nhập email của bạn" required type="email" autofocus />
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs sm:text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 sm:py-4 text-xs sm:text-sm rounded-xl sm:rounded-2xl shadow-lg shadow-primary/20 transition-all transform active:scale-[0.98]"
                    type="submit">
                    Gửi yêu cầu
                </button>
            </form>
            <div class="mt-6 sm:mt-8 text-center">
                <a class="text-xs sm:text-sm font-bold text-primary hover:underline flex items-center justify-center gap-2 group"
                    href="{{ route('login') }}">
                    Quay lại Đăng nhập
                </a>
            </div>
        </div>
    </main>
@endsection
