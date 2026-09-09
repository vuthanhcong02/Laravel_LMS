@props([
    'threshold' => 250,
    'target' => 'main > div.overflow-y-auto',
    'title' => __('Cuộn lên đầu trang'),
])

<div x-data="lmsScrollTop({{ (int) $threshold }}, '{{ $target }}')"
    class="pointer-events-none">
    
    <button type="button"
            x-show="show"
            x-cloak
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 scale-75"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-75"
            @click="scrollToTop()"
            class="pointer-events-auto fixed bottom-6 right-5 sm:bottom-8 sm:right-6 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-gradient-to-tr from-[#e07a5f] to-[#e78b72] hover:from-[#c86349] hover:to-[#e07a5f] text-white shadow-xl shadow-[#e07a5f]/40 flex items-center justify-center btn-tactile transition-all duration-300 z-50 border border-white/30 hover:scale-110 cursor-pointer group"
            title="{{ $title }}"
            style="display: none;">
        <i class="fa-solid fa-arrow-up text-sm sm:text-base group-hover:-translate-y-0.5 transition-transform duration-200"></i>
    </button>
</div>
