@extends('layouts.app')

@section('title', 'Xác nhận mật khẩu')

@section('breadcrumb', 'Xác nhận mật khẩu')

@section('content')
    <main class="flex-1 flex items-center justify-center p-6 relative overflow-hidden mb-24">
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
            <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
        </div>
        <div class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-xl shadow-xl shadow-primary/5 border border-primary/5 dark:border-slate-700/50 p-8 md:p-12 z-10">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="bg-primary/10 p-4 rounded-full">
                        <span class="material-symbols-outlined text-primary text-4xl">admin_panel_settings</span>
                    </div>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold font-poppins text-slate-900 dark:text-white mb-3">Xác nhận mật khẩu</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base leading-relaxed">
                    Đây là khu vực bảo mật của hệ thống. Vui lòng xác nhận mật khẩu của bạn trước khi tiếp tục.
                </p>
            </div>

            <form class="space-y-6" method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Mật khẩu</label>
                    <div class="relative">
                        <input
                            name="password"
                            id="password_input"
                            class="w-full h-12 px-4 rounded-lg border @error('password') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all pr-12 text-slate-900 dark:text-white placeholder:text-slate-400"
                            placeholder="Nhập mật khẩu của bạn" type="password" required autocomplete="current-password" />
                        <button
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center"
                            onclick="document.getElementById('password_input').type = document.getElementById('password_input').type === 'password' ? 'text' : 'password'"
                            type="button">
                            <span class="material-symbols-outlined" data-icon="visibility">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold h-12 rounded-xl transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                    type="submit">
                    Xác nhận
                </button>
            </form>
        </div>
    </main>
@endsection
