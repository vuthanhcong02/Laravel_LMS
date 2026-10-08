@props(['type' => 'success', 'message' => null])

@php
    $message ??= session($type);

    $styles = [
        'success' =>
            'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-900 dark:text-emerald-300',
        'error' =>
            'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-950/40 dark:border-rose-900 dark:text-rose-300',
        'warning' =>
            'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-900 dark:text-amber-300',
        'info' =>
            'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-950/40 dark:border-blue-900 dark:text-blue-300',
    ];

    $icons = [
        'success' => 'check_circle',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info',
    ];

    $class = $styles[$type] ?? $styles['success'];
    $icon = $icons[$type] ?? $icons['success'];
@endphp

@if ($message)
    <div x-data="lmsFlashToast(5000)" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="flex items-center gap-3 p-4 border rounded-xl shadow-xs {{ $class }}">
        <span class="material-symbols-outlined text-xl shrink-0">{{ $icon }}</span>
        <span class="text-xs sm:text-sm font-medium flex-1">{{ $message }}</span>
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
            <span class="material-symbols-outlined text-base">close</span>
        </button>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            if (!Alpine.data('lmsFlashToast')) {
                Alpine.data('lmsFlashToast', (duration = 5000) => ({
                    show: true,
                    init() {
                        setTimeout(() => {
                            this.show = false;
                        }, duration);
                    }
                }));
            }
        });
    </script>
@endif
