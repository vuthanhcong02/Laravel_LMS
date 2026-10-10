@props([
    'title' => __('Lọc'),
    'icon' => 'filter_list',
])

<button type="submit" {{ $attributes->merge(['class' => 'px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs sm:text-sm font-semibold flex items-center justify-center gap-1.5 shadow-xs transition-all active:scale-[0.98] shrink-0 cursor-pointer']) }}>
    @if($icon)
        <span class="material-symbols-outlined text-[16px]">{{ $icon }}</span>
    @endif
    <span>{{ $title }}</span>
</button>
