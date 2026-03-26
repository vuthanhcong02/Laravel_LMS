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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
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
</body>

</html>
