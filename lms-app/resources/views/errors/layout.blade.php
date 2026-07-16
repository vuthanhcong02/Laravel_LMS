@extends('layouts.app')

@section('title')
@yield('title')
@endsection

@section('hide_default_breadcrumb', true)

@section('content')
<main class="flex-grow flex items-center justify-center py-20 px-4 min-h-[60vh]">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Graphic -->
        <div class="mb-8 flex justify-center">
            <div class="relative w-32 h-32 flex items-center justify-center bg-primary/10 rounded-full text-primary">
                <span class="material-symbols-outlined text-6xl">
                    @yield('icon', 'error')
                </span>
                <div class="absolute -bottom-2 -right-2 px-4 py-1.5 bg-white dark:bg-slate-800 rounded-full shadow border border-slate-100 dark:border-slate-700">
                    <span class="text-xl font-bold text-slate-800 dark:text-white">
                        @yield('code')
                    </span>
                </div>
            </div>
        </div>

        <h1 class="text-3xl sm:text-4xl font-bold mb-4 text-slate-900 dark:text-white font-heading">
            @yield('title')
        </h1>
        
        <p class="text-lg text-slate-600 dark:text-slate-400 mb-10 max-w-lg mx-auto leading-relaxed">
            @yield('message')
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-primary text-white font-bold rounded-lg shadow-sm hover:bg-primary/90 transition-colors w-full sm:w-auto flex items-center justify-center gap-2">
                <i class="fa-solid fa-house"></i>
                Về trang chủ
            </a>
            <button onclick="window.history.back()" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold rounded-lg shadow-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors w-full sm:w-auto flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại
            </button>
        </div>
    </div>
</main>
@endsection
