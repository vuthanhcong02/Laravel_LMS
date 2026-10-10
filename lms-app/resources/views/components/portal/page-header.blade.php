@props([
    'title' => '',
    'subtitle' => null,
    'description' => null,
])

@php
    $desc = $subtitle ?? $description;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-1']) }}>
    <div class="space-y-0.5">
        <h1 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white tracking-tight">
            {{ $title ?: $slot }}
        </h1>
        @if($desc)
            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                {{ $desc }}
            </p>
        @endif
    </div>

    @if(isset($actions))
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
