@php
    use App\Services\GamificationService;
    use Carbon\Carbon;

    $user = $user ?? auth()->user();
    $gamificationService = app(GamificationService::class);
    $heatmapDays = $gamificationService->getHeatmapData($user ? $user->id : null);

    $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
    $now = Carbon::now($timezone);

    $todayItem =
        collect($heatmapDays)->firstWhere('is_today', true) ?? (count($heatmapDays) > 0 ? end($heatmapDays) : null);

    $m4 = $now->translatedFormat('\T\h\á\n\g n');
    $m3 = $now->copy()->subMonth()->translatedFormat('\T\h\á\n\g n');
    $m2 = $now->copy()->subMonths(2)->translatedFormat('\T\h\á\n\g n');
    $m1 = $now->copy()->subMonths(3)->translatedFormat('\T\h\á\n\g n');

    $columns = array_chunk($heatmapDays, 7);
    $activeDaysCount = collect($heatmapDays)->filter(fn($d) => $d['exp'] > 0)->count();
@endphp

<div x-data="streakHeatmapWidget({ today: @js($todayItem) })"
    class="lms-card p-5 sm:p-6 bg-[#fcfaf7] dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl shadow-xs space-y-4">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-[15px] font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                <i class="fa-regular fa-calendar text-[#e07a5f] text-base"></i> {{ __('90 ngày gần nhất') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">
                {{ __('Bấm vào một ô ngày để xem chi tiết hoạt động.') }}
            </p>
        </div>

        <div
            class="flex flex-wrap items-center gap-4 text-[11px] text-slate-400 dark:text-neutral-400 font-medium select-none">
            <div class="flex items-center gap-1.5">
                <span>{{ __('Ít') }}</span>
                <span class="w-3 h-3 rounded-[3px] bg-[#eae5dd] dark:bg-[#262220]" title="0 EXP"></span>
                <span class="w-3 h-3 rounded-[3px] bg-[#9be9a8] dark:bg-[#0e4429]" title="1 - 20 EXP"></span>
                <span class="w-3 h-3 rounded-[3px] bg-[#40c463] dark:bg-[#006d32]" title="21 - 50 EXP"></span>
                <span class="w-3 h-3 rounded-[3px] bg-[#30a14e] dark:bg-[#26a641]" title="51 - 100 EXP"></span>
                <span class="w-3 h-3 rounded-[3px] bg-[#216e39] dark:bg-[#39d353]" title="> 100 EXP"></span>
                <span>{{ __('Nhiều') }}</span>
            </div>
            @php
                $isTodayCheckedIn = $todayItem && !empty($todayItem['is_checked_in']);
            @endphp
            <div class="flex items-center gap-1.5">
                @if ($isTodayCheckedIn)
                    <span
                        class="w-3.5 h-3.5 rounded-[3px] border border-[#38bdf8] bg-[#0284c7]/20 text-[#38bdf8] flex items-center justify-center text-[8px]">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <span
                        class="text-[10px] text-sky-600 dark:text-sky-400 font-semibold">{{ __('Đã học hôm nay') }}</span>
                @else
                    <span
                        class="w-3.5 h-3.5 rounded-[3px] border border-slate-300 dark:border-neutral-600 bg-transparent flex items-center justify-center text-[8px]"></span>
                    <span
                        class="text-[10px] text-slate-400 dark:text-neutral-400 font-medium">{{ __('Chưa học hôm nay') }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-5 items-center lg:items-stretch">

        <div class="overflow-x-auto no-scrollbar py-1 w-full lg:w-auto shrink-0 flex justify-center lg:justify-start">
            <div class="w-fit">
                <div class="flex items-start gap-2">
                    <div
                        class="flex flex-col gap-[4px] text-[10px] text-slate-400 dark:text-neutral-500 font-medium select-none shrink-0 w-4.5 pt-[19px]">
                        <div class="h-[13px] sm:h-[14px] flex items-center justify-end pr-1 invisible">CN</div>
                        <div class="h-[13px] sm:h-[14px] flex items-center justify-end pr-1">T2</div>
                        <div class="h-[13px] sm:h-[14px] flex items-center justify-end pr-1 invisible">T3</div>
                        <div class="h-[13px] sm:h-[14px] flex items-center justify-end pr-1">T4</div>
                        <div class="h-[13px] sm:h-[14px] flex items-center justify-end pr-1 invisible">T5</div>
                        <div class="h-[13px] sm:h-[14px] flex items-center justify-end pr-1">T6</div>
                        <div class="h-[13px] sm:h-[14px] flex items-center justify-end pr-1 invisible">T7</div>
                    </div>

                    <div>
                        <div
                            class="flex items-center justify-between text-[11px] font-medium text-slate-400 dark:text-neutral-500 px-0.5 mb-1.5 tracking-wide select-none">
                            <span>{{ $m1 }}</span>
                            <span>{{ $m2 }}</span>
                            <span>{{ $m3 }}</span>
                            <span>{{ $m4 }}</span>
                        </div>

                        <div class="flex items-start gap-[4px]" @mouseleave="resetPreview()">
                            @foreach ($columns as $colIndex => $colDays)
                                <div class="flex flex-col gap-[4px]">
                                    @foreach ($colDays as $day)
                                        @php
                                            $isFuture = $day['is_future'] ?? false;
                                            $isCheckedIn = $day['is_checked_in'] ?? false;
                                            $level = $day['level'];
                                            $colorClass = match ($level) {
                                                1 => 'bg-[#9be9a8] dark:bg-[#0e4429]',
                                                2 => 'bg-[#40c463] dark:bg-[#006d32]',
                                                3 => 'bg-[#30a14e] dark:bg-[#26a641]',
                                                4 => 'bg-[#216e39] dark:bg-[#39d353]',
                                                default => $isFuture
                                                    ? 'bg-[#f0ebe3]/40 dark:bg-[#1c1917]/40 opacity-40 cursor-default'
                                                    : 'bg-[#eae5dd] dark:bg-[#262220] hover:bg-[#ded7cb] dark:hover:bg-[#34302d]',
                                            };
                                            if ($isCheckedIn) {
                                                $colorClass .= ' border border-[#38bdf8]';
                                            }
                                        @endphp
                                        <button type="button"
                                            @if (!$isFuture) @mouseenter="previewDay(@js($day))"
                                                    @click="selectDay(@js($day))" @endif
                                            :class="{
                                                'ring-2 ring-[#e07a5f] ring-offset-1 ring-offset-[#fcfaf7] dark:ring-offset-[#181615] z-10': selectedDay &&
                                                    selectedDay.date === '{{ $day['date'] }}'
                                            }"
                                            class="w-[13px] h-[13px] sm:w-[14px] sm:h-[14px] rounded-[2.5px] transition-all duration-150 shrink-0 {{ $colorClass }} {{ $isFuture ? '' : 'cursor-pointer' }}"
                                            title="{{ $day['full_formatted_date'] ?? $day['formatted_date'] }}: {{ $day['exp'] }} EXP ({{ $isCheckedIn ? __('Đã điểm danh') : __('Chưa học') }})">
                                        </button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex-1 w-full min-w-0 p-4 sm:p-5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f5f0]/60 dark:bg-[#1e1b19]/60 flex flex-col justify-center min-h-[116px]">
            <template x-if="selectedDay">
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check text-[#e07a5f] text-sm"></i>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white capitalize"
                                x-text="selectedDay.full_formatted_date || selectedDay.formatted_date"></h4>
                        </div>
                        <span
                            class="px-2 py-0.5 rounded-full border border-[#e07a5f] bg-[#e07a5f]/10 text-[#e07a5f] text-[10px] font-semibold"
                            x-show="selectedDay.is_today">{{ __('Hôm nay') }}</span>
                    </div>

                    <div>
                        <template x-if="selectedDay.activities && selectedDay.activities.length > 0">
                            <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                                <template x-for="(act, idx) in selectedDay.activities" :key="idx">
                                    <div
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#e07a5f]/10 border border-[#e07a5f]/25 text-[#e07a5f] text-xs font-semibold">
                                        <i class="fa-solid fa-book-open text-[10px]"></i>
                                        <span x-text="act"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!selectedDay.activities || selectedDay.activities.length === 0">
                            <div class="text-xs text-slate-500 dark:text-neutral-400 flex items-center gap-1.5 pt-0.5">
                                <i
                                    class="fa-regular fa-circle-dot text-[10px] text-slate-400 dark:text-neutral-500"></i>
                                <span>{{ __('Không có hoạt động nào trong ngày này') }}</span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <template x-if="!selectedDay">
                <div class="text-xs text-slate-400 dark:text-neutral-500 italic flex items-center gap-2">
                    <i class="fa-regular fa-hand-pointer"></i>
                    <span>{{ __('Di chuột hoặc bấm vào ô để xem chi tiết') }}</span>
                </div>
            </template>
        </div>

    </div>

    <div
        class="pt-3.5 border-t border-[#e8e2d9] dark:border-[#2d2926] flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
        <div
            class="text-[11px] font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-wider shrink-0 flex items-center gap-1.5 select-none">
            <i class="fa-solid fa-chart-pie text-[#e07a5f]"></i>
            <span>{{ __('TỔNG KẾT 90 NGÀY') }}</span>
        </div>

        <div class="flex-1 flex items-center justify-between sm:justify-end gap-4 sm:gap-8 text-xs select-none">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-[#10b981] text-xs"></i>
                <span class="text-slate-500 dark:text-neutral-400">{{ __('Ngày học:') }}</span>
                <strong class="text-slate-900 dark:text-white font-bold">{{ $activeDaysCount }}/90</strong>
            </div>

            @php
                $totalExp90Days = collect($heatmapDays)->sum('exp');
            @endphp
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-bolt text-[#38bdf8] text-xs"></i>
                <span class="text-slate-500 dark:text-neutral-400">{{ __('Tổng EXP:') }}</span>
                <strong class="text-slate-900 dark:text-white font-bold">{{ number_format($totalExp90Days) }}
                    EXP</strong>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-arrow-trend-up text-[#f97316] text-xs"></i>
                <span class="text-slate-500 dark:text-neutral-400">{{ __('Chuỗi dài:') }}</span>
                <strong class="text-slate-900 dark:text-white font-bold">{{ $user?->longest_streak ?? 0 }}
                    {{ __('ngày') }}</strong>
            </div>
        </div>
    </div>

</div>
