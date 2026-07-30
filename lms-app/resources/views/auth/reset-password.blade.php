@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('breadcrumb', 'Đặt lại mật khẩu')

@section('content')
    <main class="flex-1 flex items-center justify-center p-6 relative overflow-hidden mb-24">
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
            <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
        </div>
        <div
            class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-xl shadow-xl shadow-primary/5 border border-primary/5 dark:border-slate-700/50 overflow-hidden z-10">
            <div class="p-5 sm:p-8 pb-3 sm:pb-4">
                <div class="flex justify-center mb-4 sm:mb-6">
                    <div class="bg-primary/10 p-3 sm:p-4 rounded-full">
                        <span class="material-symbols-outlined text-primary text-3xl sm:text-4xl">lock_reset</span>
                    </div>
                </div>
                <div class="text-center">
                    <h1 class="font-heading text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-1.5 sm:mb-2">Đặt lại mật khẩu</h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Vui lòng nhập mật khẩu mới của bạn để bảo mật tài khoản.</p>
                </div>
            </div>
            <form class="p-5 sm:p-8 pt-2 sm:pt-4 space-y-4 sm:space-y-6" method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Mật khẩu mới</label>
                    <div class="relative flex items-center">
                        <input name="password" id="password_input"
                            class="w-full h-11 sm:h-12 px-3.5 sm:px-4 pr-11 rounded-lg border @error('password') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm text-slate-900 dark:text-white placeholder:text-slate-400"
                            placeholder="Nhập mật khẩu mới" type="password" required />
                        <button
                            class="absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-primary transition-colors focus:outline-none"
                            onclick="togglePasswordVisibility('password_input', this)"
                            type="button" aria-label="Hiện/ẩn mật khẩu">
                            <span class="material-symbols-outlined text-[18px] sm:text-[20px] select-none">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs sm:text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Xác nhận mật khẩu mới</label>
                    <div class="relative flex items-center">
                        <input name="password_confirmation" id="password_confirmation_input"
                            class="w-full h-11 sm:h-12 px-3.5 sm:px-4 pr-11 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm text-slate-900 dark:text-white placeholder:text-slate-400"
                            placeholder="Nhập lại mật khẩu mới" type="password" required />
                        <button
                            class="absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-primary transition-colors focus:outline-none"
                            onclick="togglePasswordVisibility('password_confirmation_input', this)"
                            type="button" aria-label="Hiện/ẩn mật khẩu">
                            <span class="material-symbols-outlined text-[18px] sm:text-[20px] select-none">visibility</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-4 pt-2">
                    <button
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold h-11 sm:h-12 text-xs sm:text-sm rounded-xl sm:rounded-2xl transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                        type="submit">
                        Cập nhật mật khẩu
                    </button>
                    <a class="block text-center text-xs sm:text-sm font-medium text-slate-500 hover:text-primary transition-colors"
                        href="{{ route('login') }}">
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
