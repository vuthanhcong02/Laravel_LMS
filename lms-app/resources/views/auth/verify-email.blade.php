@extends('layouts.app')

@section('title', 'Xác thực Email')

@section('breadcrumb', 'Xác thực Email')

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
                        <span class="material-symbols-outlined text-primary text-4xl">mark_email_read</span>
                    </div>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold font-poppins text-slate-900 dark:text-white mb-3">Xác thực Email</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base leading-relaxed">
                    Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác thực địa chỉ email bằng cách nhấp vào liên kết chúng tôi vừa gửi cho bạn. Nếu bạn không nhận được email, chúng tôi rất sẵn lòng gửi lại cho bạn.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200 text-center">
                    Một liên kết xác thực mới đã được gửi đến địa chỉ email bạn cung cấp khi đăng ký.
                </div>
            @endif

            <div class="flex flex-col gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold h-12 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2">
                        Gửi lại email xác thực
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-slate-500 hover:text-primary transition-colors hover:underline focus:outline-none">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection
