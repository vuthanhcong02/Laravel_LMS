@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('breadcrumb', 'Quên mật khẩu')

@section('content')
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div
            class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-lg shadow-xl shadow-slate-200/50 dark:shadow-none p-8 md:p-12">
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-bold font-poppins text-slate-900 dark:text-white mb-3">Quên mật khẩu?
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base leading-relaxed">
                    Nhập email của bạn để nhận hướng dẫn khôi phục mật khẩu.
                </p>
            </div>
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200 text-center">
                    {{ session('status') }}
                </div>
            @endif
            <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                        <input
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border @error('email') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-white"
                            placeholder="Nhập email của bạn" required type="email" autofocus />
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    class="w-full bg-primary hover:bg-[#7eafcf] text-white font-bold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all transform active:scale-[0.98]"
                    type="submit">
                    Gửi yêu cầu
                </button>
            </form>
            <div class="mt-8 text-center">
                <a class="text-sm font-bold text-primary hover:underline flex items-center justify-center gap-2 group"
                    href="{{ route('login') }}">
                    Quay lại Đăng nhập
                </a>
            </div>
        </div>
    </main>
@endsection
