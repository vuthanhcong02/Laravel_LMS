@props(['type' => 'success', 'message' => null])

@php
    $message ??= session($type);

    $styles = [
        'success' => 'bg-emerald-100 border-emerald-200 text-emerald-800',
        'error'   => 'bg-red-100 border-red-200 text-red-800',
        'warning' => 'bg-yellow-100 border-yellow-200 text-yellow-800',
        'info'    => 'bg-blue-100 border-blue-200 text-blue-800',
    ];

    $icons = [
        'success' => 'check_circle',
        'error'   => 'cancel',
        'warning' => 'warning',
        'info'    => 'info',
    ];

    $class = $styles[$type] ?? $styles['success'];
    $icon  = $icons[$type]  ?? $icons['success'];
@endphp

@if($message)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="flex items-center gap-3 p-4 border rounded-lg shadow-sm {{ $class }}">
        <span class="material-symbols-outlined text-base shrink-0">{{ $icon }}</span>
        <span class="text-sm font-medium flex-1">{{ $message }}</span>
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">
            <span class="material-symbols-outlined text-base">close</span>
        </button>
    </div>
@endif
