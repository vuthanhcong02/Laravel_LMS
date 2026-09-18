<div>
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
            <span>{{ __('Danh sách Chủ đề') }} - <span class="text-[#e07a5f]" x-text="selectedLevel"></span></span>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2d201a] text-[#e07a5f] border border-[#fcdccf] dark:border-[#3d271e]">
                <span x-text="topics.length"></span> {{ __('chủ đề') }}
            </span>
        </h2>
    </div>

    {{-- Loading Skeleton Indicator --}}
    <div x-show="isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 animate-pulse" style="display: none;">
        @for($i = 0; $i < 6; $i++)
            <div class="bg-white dark:bg-[#181615] rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] p-3.5 shadow-xs h-14 flex items-center justify-between">
                <div class="h-3.5 bg-slate-200 dark:bg-slate-800 rounded w-2/3"></div>
                <div class="h-3.5 bg-slate-100 dark:bg-slate-800/60 rounded w-12"></div>
            </div>
        @endfor
    </div>

    {{-- Dynamic Topic Grid --}}
    <div x-show="!isLoading && topics.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        <template x-for="t in topics" :key="t.id">
            <a :href="getPracticeUrl(t)"
               @click="handleTopicClick(t, $event)"
               class="bg-white dark:bg-[#181615] rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] p-3.5 shadow-xs hover:border-[#e07a5f] hover:-translate-y-0.5 hover:shadow-xs transition-all duration-200 block group btn-tactile cursor-pointer">
                <div class="flex items-center justify-between gap-2 min-w-0">
                    <h3 class="text-xs sm:text-[13px] font-semibold text-slate-800 dark:text-slate-200 group-hover:text-[#e07a5f] transition-colors truncate"
                        x-text="t.titleVi || t.title">
                    </h3>
                    <span class="text-[10px] font-medium px-2 py-0.5 rounded-md bg-[#f8f6f3] dark:bg-[#23201e] text-slate-500 dark:text-slate-400 border border-[#e8e2d9]/60 dark:border-[#2d2926] shrink-0">
                        <span x-text="t.totalSentences"></span> {{ __('câu') }}
                    </span>
                </div>
            </a>
        </template>
    </div>

    {{-- Empty State --}}
    <div x-show="!isLoading && topics.length === 0" class="text-center py-12 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] p-6 space-y-3" style="display: none;">
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
        <button type="button"
                @click="clearSearch(); selectLevel('HSK1')"
                class="inline-flex items-center gap-1.5 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs px-3.5 py-2 transition-all btn-tactile cursor-pointer">
            <i class="fa-solid fa-rotate-right text-xs"></i>
            <span>{{ __('Về HSK 1') }}</span>
        </button>
    </div>
</div>
