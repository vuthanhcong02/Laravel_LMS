@props([
    'url',
    'title' => __('Xóa lọc'),
    'icon' => null,
])

<a href="{{ $url }}" {{ $attributes->merge(['class' => 'px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center justify-center gap-1.5 shrink-0']) }}>
    @if($icon)
        <span class="material-symbols-outlined text-[16px]">{{ $icon }}</span>
    @endif
    <span>{{ $title }}</span>
</a>
