@php
    $getPosStyle = function($t) {
        $t = mb_strtolower($t);
        if (str_contains($t, 'đại từ')) {
            return 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 border-indigo-500/30';
        }
        if (str_contains($t, 'tính từ')) {
            return 'bg-[#f59e0b]/15 text-[#f59e0b] border-[#f59e0b]/30';
        }
        if (str_contains($t, 'động từ')) {
            return 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/30';
        }
        if (str_contains($t, 'danh từ')) {
            return 'bg-blue-500/15 text-blue-600 dark:text-blue-400 border-blue-500/30';
        }
        if (str_contains($t, 'lượng từ')) {
            return 'bg-teal-500/15 text-teal-600 dark:text-teal-400 border-teal-500/30';
        }
        if (str_contains($t, 'phó từ')) {
            return 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/30';
        }
        if (str_contains($t, 'trợ')) {
            return 'bg-fuchsia-500/15 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-500/30';
        }
        if (str_contains($t, 'thán')) {
            return 'bg-red-500/15 text-red-600 dark:text-red-400 border-red-500/30';
        }
        if (str_contains($t, 'cụm')) {
            return 'bg-[#0284c7]/15 text-[#0284c7] border-[#0284c7]/30';
        }
        if (str_contains($t, 'câu')) {
            return 'bg-[#f59e0b]/15 text-[#f59e0b] border-[#f59e0b]/30';
        }
        return 'bg-slate-500/15 text-slate-600 dark:text-slate-400 border-slate-500/30';
    };
@endphp
<div class="space-y-4">
    <!-- ===================================================================== -->
    <!-- ===================================================================== -->
    <div x-show="vocabSubView === 'table'" class="space-y-4">
        <!-- Header Card -->
        <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f4978e] flex items-center justify-center text-sm font-bold shrink-0">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Từ vựng trọng tâm') }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">{{ __('Ghi nhớ và luyện phát âm các từ vựng mới của bài học.') }}</p>
                </div>
            </div>
            <div class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-[#fcfaf7] dark:bg-[#23201e] px-3 py-1.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926]">
                <span class="text-[#e07a5f]">{{ $currentLesson && $currentLesson->vocabList ? $currentLesson->vocabList->count() : 0 }}</span> {{ __('từ vựng') }}
            </div>
        </div>
        <!-- Table Card -->
        <div class="lms-card bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-[#fcfaf7] dark:bg-[#23201e] border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <th class="p-3.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest w-12 text-center">#</th>
                            <th class="p-3.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest w-36">{{ __('Từ vựng') }}</th>
                            <th class="p-3.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest w-36">{{ __('Pinyin') }}</th>
                            <th class="p-3.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest w-44">{{ __('Từ loại') }}</th>
                            <th class="p-3.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Ý nghĩa & Ví dụ') }}</th>
                            <th class="p-3.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest w-16 text-center">{{ __('Nghe') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e8e2d9] dark:divide-[#2d2926]">
                        @if(isset($currentLesson) && $currentLesson->vocabList)
                            @forelse ($currentLesson->vocabList as $idx => $vocab)
                                @php 
                                    $posClass = $getPosStyle($vocab->type);
                                @endphp
                                <tr class="hover:bg-[#fff2ee]/50 dark:hover:bg-slate-800/30 transition-colors group">
                                    <td class="p-3.5 text-xs font-bold text-slate-400 text-center">{{ $idx + 1 }}</td>
                                    <td class="p-3.5 font-bold text-slate-900 dark:text-white text-base zh-text">{{ $vocab->word }}</td>
                                    <td class="p-3.5 font-mono font-semibold text-[#e07a5f] text-xs">[{{ $vocab->pinyin }}]</td>
                                    <td class="p-3.5">
                                        <span class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wide border {{ $posClass }}">
                                            {{ $vocab->type }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-xs font-medium text-slate-600 dark:text-slate-300">
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $vocab->meaning }}</div>
                                        @if($vocab->example)
                                            <div class="mt-1 text-slate-400 text-[11px] italic flex items-center gap-1.5">
                                                <i class="fa-solid fa-quote-left text-[9px] text-[#e07a5f]/60"></i>
                                                <span>{{ $vocab->example }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <button onclick="window.playAudio('{{ $vocab->audio_url ?: 'https://dict.youdao.com/dictvoice?audio=' . urlencode($vocab->word) . '&type=1' }}')" class="w-8 h-8 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 hover:text-[#e07a5f] hover:border-[#e07a5f] transition-all flex items-center justify-center btn-tactile shadow-2xs" title="{{ __('Phát âm') }}">
                                            <i class="fa-solid fa-volume-high text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-500 text-sm">{{ __('Chưa có từ vựng nào.') }}</td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- ===================================================================== -->
    <!-- ===================================================================== -->
    @include('course-v2.components.games.flashcard')
    <!-- ===================================================================== -->
    <!-- ===================================================================== -->
    @include('course-v2.components.games.match')
    <!-- ===================================================================== -->
    <!-- ===================================================================== -->
    @include('course-v2.components.games.quiz')
    <!-- ===================================================================== -->
    <!-- ===================================================================== -->
    @include('course-v2.components.games.typing')
</div>
@vite('resources/js/vocab-games.js')
