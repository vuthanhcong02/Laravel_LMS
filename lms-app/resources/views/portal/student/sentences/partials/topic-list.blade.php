<div>
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
            <span>{{ __('Danh sách Chủ đề') }} - <span class="text-[#e07a5f]">{{ $selectedLevel }}</span></span>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2d201a] text-[#e07a5f] border border-[#fcdccf] dark:border-[#3d271e]">
                {{ count($topics) }} {{ __('chủ đề') }}
            </span>
        </h2>
    </div>

    @if(count($topics) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($topics as $index => $t)
                <a href="{{ route('sentences.practice', ['level' => $t['level'], 'slug' => $t['id'], 'mode' => $mode]) }}"
                   @if(!auth()->check())
                       @click.prevent="$dispatch('open-auth-modal', { redirect: '{{ route('sentences.practice', ['level' => $t['level'], 'slug' => $t['id'], 'mode' => $mode]) }}' })"
                   @endif
                   class="bg-white dark:bg-[#181615] rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] p-3.5 shadow-xs hover:border-[#e07a5f] hover:-translate-y-0.5 hover:shadow-xs transition-all duration-200 block group btn-tactile cursor-pointer">
                    
                    <div class="flex items-center justify-between gap-2 min-w-0">
                        <h3 class="text-xs sm:text-[13px] font-semibold text-slate-800 dark:text-slate-200 group-hover:text-[#e07a5f] transition-colors truncate">
                            {{ $t['titleVi'] }}
                        </h3>
                        
                        <span class="text-[10px] font-medium px-2 py-0.5 rounded-md bg-[#f8f6f3] dark:bg-[#23201e] text-slate-500 dark:text-slate-400 border border-[#e8e2d9]/60 dark:border-[#2d2926] shrink-0">
                            {{ $t['totalSentences'] }} {{ __('câu') }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-6 space-y-3">
            <div class="size-12 rounded-full bg-[#fff2ee] dark:bg-[#2a201c] flex items-center justify-center text-[#e07a5f] mx-auto">
                <i class="fa-regular fa-face-frown text-2xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                    {{ __('Không tìm thấy bài học nào phù hợp') }}
                </h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    {{ __('Vui lòng thử chọn cấp độ HSK khác hoặc xóa bớt từ khóa tìm kiếm để khám phá thêm nhiều bài học thú vị.') }}
                </p>
            </div>
            <a href="{{ route('sentences.index', ['mode' => $mode, 'level' => 'HSK1']) }}"
               class="inline-flex items-center gap-1.5 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs px-3.5 py-2 transition-all btn-tactile">
                <i class="fa-solid fa-rotate-right text-xs"></i>
                <span>{{ __('Về HSK 1') }}</span>
            </a>
        </div>
    @endif
</div>
