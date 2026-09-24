@php
    $levelMeta = [
        'hsk1' => ['tag' => 'HSK 1', 'badgeClass' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-500/20'],
        'hsk2' => ['tag' => 'HSK 2', 'badgeClass' => 'bg-teal-500/10 text-teal-700 dark:text-teal-300 border-teal-500/20'],
        'hsk3' => ['tag' => 'HSK 3', 'badgeClass' => 'bg-sky-500/10 text-sky-700 dark:text-sky-300 border-sky-500/20'],
        'hsk4' => ['tag' => 'HSK 4', 'badgeClass' => 'bg-purple-500/10 text-purple-700 dark:text-purple-300 border-purple-500/20'],
        'hsk5' => ['tag' => 'HSK 5', 'badgeClass' => 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-500/20'],
        'hsk6' => ['tag' => 'HSK 6', 'badgeClass' => 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border-indigo-500/20'],
    ];
@endphp

<div class="space-y-4">
    @guest
        <div class="lms-card p-8 text-center space-y-3">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-[#e07a5f]/10 text-[#e07a5f] flex items-center justify-center text-2xl">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800 dark:text-white">
                {{ __('Đăng nhập để theo dõi lịch sử làm bài') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                {{ __('Hệ thống tự động lưu trữ mọi lần làm bài thi thử HSK, bảng điểm chi tiết từng kỹ năng và đáp án giải thích để bạn tiện ôn tập.') }}
            </p>
            <div class="pt-2">
                <button type="button"
                        @click="$dispatch('open-auth-modal', { tab: 'login' })"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>{{ __('Đăng nhập ngay') }}</span>
                </button>
            </div>
        </div>
    @else
        @if ($userHistory->isEmpty())
            <div class="lms-card p-10 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-3xl">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">
                        {{ __('Chưa có lượt thi thử nào') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                        {{ __('Bạn chưa hoàn thành bài thi HSK nào. Hãy chọn một đề thi phù hợp trong kho đề để rèn luyện kỹ năng và bấm giờ thực tế!') }}
                    </p>
                </div>
                <div class="pt-2">
                    <button type="button" @click="switchMainTab('exams')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>{{ __('Khám phá Kho đề thi ngay') }}</span>
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($userHistory as $attempt)
                    @php
                        $mockExam = $attempt->mockExam;
                        $levelCode = strtolower($mockExam->hskLevel->level_code ?? 'hsk1');
                        $meta = $levelMeta[$levelCode] ?? ['tag' => 'HSK', 'badgeClass' => 'bg-slate-100 text-slate-700 border-slate-200'];
                        $passScore = $mockExam->pass_score ?? ($levelCode === 'hsk1' || $levelCode === 'hsk2' ? 120 : 180);
                        $isPassed = $attempt->total_score >= $passScore;
                        $levelNumber = str_replace('hsk', '', $levelCode);
                        $durationMinutes = 0;
                        if ($attempt->started_at && $attempt->completed_at) {
                            $durationMinutes = \Carbon\Carbon::parse($attempt->started_at)->diffInMinutes(\Carbon\Carbon::parse($attempt->completed_at));
                        }
                    @endphp

                    <div class="lms-card p-4 sm:p-5 hover:border-[#e07a5f]/60 transition-all duration-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 border {{ $isPassed ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border-rose-500/20' }}">
                                <i class="fa-solid {{ $isPassed ? 'fa-circle-check text-xl' : 'fa-circle-xmark text-xl' }}"></i>
                            </div>

                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $meta['badgeClass'] }}">
                                        {{ $meta['tag'] }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $isPassed ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20' }}">
                                        {{ $isPassed ? __('Đạt chuẩn HSK') : __('Chưa đạt') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[10px]"></i>
                                        <span>{{ $attempt->completed_at ? \Carbon\Carbon::parse($attempt->completed_at)->format('H:i - d/m/Y') : '' }}</span>
                                    </span>
                                </div>

                                <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate">
                                    {{ $mockExam->title ?? __('Đề thi HSK') }}
                                </h4>

                                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid fa-stopwatch text-[#0284c7] text-[11px]"></i>
                                        <span>{{ $durationMinutes > 0 ? $durationMinutes . ' ' . __('phút') : __('Dưới 1 phút') }}</span>
                                    </span>
                                    <span>•</span>
                                    <span>{{ __('Nghe') }}: <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $attempt->listening_score ?? 0 }}</strong></span>
                                    <span>•</span>
                                    <span>{{ __('Đọc') }}: <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $attempt->reading_score ?? 0 }}</strong></span>
                                    @if($attempt->writing_score !== null)
                                        <span>•</span>
                                        <span>{{ __('Viết') }}: <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $attempt->writing_score ?? 0 }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-[#e8e2d9] dark:border-[#2d2926]">
                            <div class="text-left md:text-right pr-2">
                                <div class="text-[10px] text-slate-400 uppercase font-bold">{{ __('Tổng điểm') }}</div>
                                <div class="text-lg font-bold {{ $isPassed ? 'text-emerald-600 dark:text-emerald-400' : 'text-[#e07a5f]' }}">
                                    {{ $attempt->total_score }} <span class="text-xs text-slate-400 font-normal">/ {{ $mockExam->total_score ?? 300 }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('student.hsk-mock-exams.result', $attempt->uuid) }}" 
                                   class="px-3.5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 shrink-0 btn-tactile">
                                    <i class="fa-solid fa-file-lines text-xs"></i>
                                    <span>{{ __('Xem giải thích') }}</span>
                                </a>

                                @if($mockExam)
                                    <a href="{{ route('student.hsk-mock-exams.start', ['level' => $levelNumber, 'id' => $mockExam->id]) }}" 
                                       title="{{ __('Thi lại đề này') }}"
                                       class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs transition-all shrink-0">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($userHistory->hasPages())
                    <div class="pt-2">
                        {{ $userHistory->withQueryString()->appends(['tab' => 'history'])->links() }}
                    </div>
                @endif
            </div>
        @endif
    @endguest
</div>
