<div class="bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 sm:p-6 text-center max-w-md mx-auto shadow-xs space-y-4 my-2">
    <div class="size-11 rounded-xl bg-[#fff2ee] dark:bg-[#2a201c] flex items-center justify-center text-[#e07a5f] mx-auto shadow-xs">
        <i class="{{ $currentModeInfo['icon'] }} text-base"></i>
    </div>

    <div class="space-y-1.5">
        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2d201a] text-[#e07a5f] border border-[#fcdccf] dark:border-[#3d271e] text-[10px] font-bold">
            <span>{{ $selectedLevel }}</span>
            <span>•</span>
            <span>15 {{ __('câu hỏi') }}</span>
        </div>
        <h2 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">
            {{ $currentModeInfo['name'] }} - {{ $selectedLevel }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
            {{ __('Hệ thống sẽ chọn ngẫu nhiên 15 câu chuẩn cấp độ') }} <strong class="text-slate-700 dark:text-slate-200">{{ $selectedLevel }}</strong> {{ __('để bạn rèn luyện phản xạ.') }}
        </p>
    </div>

    <div class="grid grid-cols-3 gap-2 max-w-sm mx-auto pt-1">
        <div class="p-2 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926] space-y-0.5">
            <i class="fa-solid fa-shuffle text-[#e07a5f] text-xs"></i>
            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ __('15 câu') }}</p>
            <p class="text-[9px] text-slate-400">{{ __('Ngẫu nhiên') }}</p>
        </div>
        <div class="p-2 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926] space-y-0.5">
            <i class="fa-solid fa-bolt text-amber-500 text-xs"></i>
            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ __('+ EXP') }}</p>
            <p class="text-[9px] text-slate-400">{{ __('Tích lũy') }}</p>
        </div>
        <div class="p-2 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926] space-y-0.5">
            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ __('Chấm điểm') }}</p>
            <p class="text-[9px] text-slate-400">{{ __('Tức thì') }}</p>
        </div>
    </div>

    <div class="pt-2">
        <a href="{{ route('sentences.random', ['mode' => $mode, 'level' => $selectedLevel]) }}"
           @if(!auth()->check())
               @click.prevent="$dispatch('open-auth-modal', { redirect: '{{ route('sentences.random', ['mode' => $mode, 'level' => $selectedLevel]) }}' })"
           @endif
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs hover:shadow-sm transition-all btn-tactile cursor-pointer">
            <i class="fa-solid fa-play text-xs"></i>
            <span>{{ __('Luyện ngẫu nhiên 15 câu') }}</span>
        </a>
    </div>
</div>
