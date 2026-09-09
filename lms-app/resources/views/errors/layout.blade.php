@extends('layouts.lms')

@section('title')
    @yield('title') - {{ __('Tiếng Trung XiaoMu LMS') }}
@endsection

@section('hide_sidebar', true)

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center py-8 px-2 relative">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-[#e07a5f]/10 dark:bg-[#e07a5f]/5 rounded-full blur-3xl pointer-events-none -z-10">
        </div>

        <div class="max-w-xl w-full text-center">
            <div
                class="bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-8 sm:p-12 shadow-sm relative overflow-hidden">
                <div
                    class="absolute -right-8 -bottom-8 w-32 h-32 bg-[#fff2ee] dark:bg-[#23201e] rounded-full opacity-40 pointer-events-none">
                </div>

                <div class="relative mb-5 flex flex-col items-center justify-center">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#fff2ee] dark:bg-[#23201e] border border-[#fcdccf] dark:border-[#2d2926] flex items-center justify-center text-[#e07a5f] shadow-inner mb-3">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl">
                            @yield('icon', 'error')
                        </span>
                    </div>

                    <span class="text-2xl sm:text-3xl font-bold text-[#e07a5f] tracking-wide select-none">
                        @yield('code', '404')
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
                    @yield('title')
                </h1>

                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-md mx-auto leading-relaxed mb-8">
                    @yield('message')
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    @if (!View::hasSection('hide_home_btn'))
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs sm:text-sm font-bold shadow-xs hover:-translate-y-0.5 transition-all btn-tactile w-full sm:w-auto">
                            <i class="fa-solid fa-house text-xs"></i>
                            <span>{{ __('Về trang chủ') }}</span>
                        </a>
                    @endif

                    @hasSection('action_reload')
                        <button onclick="window.location.reload()"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl @if (View::hasSection('hide_home_btn')) bg-[#e07a5f] hover:bg-[#c86349] text-white @else bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600 @endif text-xs sm:text-sm font-bold shadow-xs hover:-translate-y-0.5 transition-all btn-tactile w-full sm:w-auto cursor-pointer">
                            <i class="fa-solid fa-rotate-right text-xs"></i>
                            <span>{{ __('Tải lại trang') }}</span>
                        </button>
                    @else
                        <button onclick="window.history.back()"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600 text-xs sm:text-sm font-bold shadow-xs hover:-translate-y-0.5 transition-all btn-tactile w-full sm:w-auto cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            <span>{{ __('Quay lại') }}</span>
                        </button>
                    @endif
                </div>

                <div
                    class="mt-8 pt-6 border-t border-[#e8e2d9] dark:border-[#2d2926] text-xs text-slate-500 dark:text-slate-400 flex items-center justify-center gap-2">
                    <span>{{ __('Cần trợ giúp?') }}</span>
                    <button @click="$dispatch('open-contact-modal')" type="button"
                        class="text-[#e07a5f] hover:underline font-semibold flex items-center gap-1 cursor-pointer">
                        <span>{{ __('Liên hệ hỗ trợ XiaoMu') }}</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
