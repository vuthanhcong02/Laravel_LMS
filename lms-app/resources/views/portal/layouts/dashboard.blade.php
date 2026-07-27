@php
    $theme = 'light';
    if (Auth::check()) {
        $setting = \App\Models\Setting::where('user_id', Auth::id())->where('key', 'theme')->first();
        if ($setting) {
            $theme = $setting->value;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $theme === 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'XiaoMu Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display"
    x-data="{ sidebarOpen: false }">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
        <!-- Top Header -->
        @yield('header')

        <div class="flex flex-1 relative">
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
                class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden" style="display: none;"></div>

            <!-- Sidebar -->
            @yield('sidebar')

            <div class="flex-1 flex flex-col">
                <!-- Main Content -->
                @yield('content')

                <!-- Footer -->
                @include('portal.layouts.footer')
            </div>
        </div>

        <!-- Global Delete Modal -->
        @include('portal.layouts.components.delete-modal')
    </div>

    <!-- Global Toast Notification -->
    <div x-data="{ 
            messages: [],
            remove(mid) {
                this.messages = this.messages.filter(m => m.id !== mid)
            }
        }"
        @notify.window="
            let id = Date.now();
            messages.push({ id, message: $event.detail.msg, type: $event.detail.type || 'info' });
            setTimeout(() => remove(id), 3000)
        "
        class="fixed bottom-10 right-10 z-[100] flex flex-col gap-3 pointer-events-none">
        <template x-for="m in messages" :key="m.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto flex items-center gap-3 px-6 py-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl min-w-[300px]">
                <div :class="{
                    'bg-primary/10 text-primary': m.type === 'info',
                    'bg-emerald-500/10 text-emerald-500': m.type === 'success',
                    'bg-amber-500/10 text-amber-500': m.type === 'warning'
                }" class="size-10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]" x-text="m.type === 'success' ? 'check_circle' : (m.type === 'warning' ? 'warning' : 'info')"></span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-black text-slate-800 dark:text-white" x-text="m.message"></p>
                </div>
                <button @click="remove(m.id)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </template>
    </div>

    @stack('scripts')
</body>

</html>
