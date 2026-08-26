@props(['links' => []])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400']) }}>
    @foreach ($links as $link)
        @if (!$loop->last)
            @if(isset($link['url']))
                <a href="{{ $link['url'] }}" class="hover:text-[#e07a5f] transition-colors">{{ $link['label'] }}</a>
            @else
                <span class="hover:text-[#e07a5f] transition-colors">{{ $link['label'] }}</span>
            @endif
            <span>/</span>
        @else
            <span class="text-slate-800 dark:text-slate-200">{{ $link['label'] }}</span>
        @endif
    @endforeach
</div>
