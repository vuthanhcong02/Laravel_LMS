@extends('layouts.lms')

@section('title', __('Luyện thi HSK Online - Đề thi thử chuẩn cấu trúc'))

@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Luyện thi HSK'), 'url' => null]
    ]" />
@endsection

@section('content')
<div x-data="hskIndex()" class="space-y-6">
    <!-- Banner Tiêu đề Trang -->
    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
        <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-[#e07a5f]/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-[#e07a5f]/10 text-[#e07a5f] text-[11px] font-bold">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>{{ __('HSK 1 - HSK 6') }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
                    {{ __('Luyện thi thử HSK Online') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                    {{ __('Rèn luyện bộ đề thi thử HSK 1 - 6 chính thức, bấm giờ phòng thi thực tế và đọ sức cùng TOP học viên xuất sắc nhất.') }}
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="#leaderboard-section" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-[#e07a5f] hover:text-[#e07a5f] transition-all flex items-center gap-2 shrink-0 btn-tactile shadow-sm">
                    <i class="fa-solid fa-trophy text-amber-500"></i>
                    <span>{{ __('Bảng xếp hạng') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Thẻ Thống Kê Tổng Quan (Combo Chuẩn) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Card 1: Tổng lượt làm đề -->
        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-clipboard-check text-[#e07a5f]"></i>
            </div>
            <div class="space-y-1">
                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ __('Tổng lượt làm đề') }}</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($totalAttempts ?? 0) }} <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 align-text-top">+12%</span></div>
            </div>
        </div>

        <!-- Card 2: Kho đề thi chuẩn -->
        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-book-bookmark text-[#0284c7]"></i>
            </div>
            <div class="space-y-1">
                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ __('Kho đề thi chuẩn') }}</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $totalExamsCount ?? 0 }} <span class="text-xs font-normal text-slate-400">{{ __('Bộ đề') }}</span> <span class="text-[10px] font-bold text-sky-600 dark:text-sky-400 align-text-top">{{ $hskLevels->count() }} {{ __('Cấp') }}</span></div>
            </div>
        </div>

        <!-- Card 3: Tỉ lệ đỗ trung bình (Toàn hệ thống) -->
        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-chart-line text-emerald-500"></i>
            </div>
            <div class="space-y-1">
                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ __('Tỉ lệ đỗ trung bình') }}</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white">
                    {{ $globalPassRate ?? '78.5%' }} <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 align-text-top">{{ __('Chuẩn HSK') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Đã hoàn thành -->
        <div class="lms-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-fire text-rose-500"></i>
            </div>
            <div class="space-y-1">
                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ __('Đã hoàn thành') }}</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white">
                    @auth
                        {{ $completedExamsCount ?? 0 }}/{{ $totalExamsCount ?? 0 }} <span class="text-xs font-normal text-slate-400">{{ __('Đề') }}</span>
                        @if(($totalExamsCount ?? 0) > 0)
                            <span class="text-[10px] font-bold text-rose-500 align-text-top">{{ round((($completedExamsCount ?? 0) / $totalExamsCount) * 100) }}%</span>
                        @endif
                    @else
                        0/{{ $totalExamsCount ?? 0 }} <span class="text-xs font-normal text-slate-400">{{ __('Đề') }}</span>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Cấp Độ HSK (Left 2 cols) & Bảng Xếp Hạng (Right 1 col) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Cột Danh Sách Các Cấp Độ HSK (2/3) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-[#e07a5f]"></i> {{ __('Các cấp độ thi HSK') }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ __('Chọn cấp độ HSK phù hợp để xem danh sách đề thi chi tiết và bắt đầu làm bài.') }}
                    </p>
                </div>
            </div>

            <!-- Grid 6 Cấp Độ HSK (Clean & Minimalist) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @php
                    $levelMeta = [
                        'hsk1' => ['tag' => 'Cơ bản', 'duration' => 40, 'questions' => 40, 'pass_score' => '120/200', 'badgeClass' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-500/20'],
                        'hsk2' => ['tag' => 'Sơ cấp', 'duration' => 55, 'questions' => 60, 'pass_score' => '120/200', 'badgeClass' => 'bg-teal-500/10 text-teal-700 dark:text-teal-300 border-teal-500/20'],
                        'hsk3' => ['tag' => 'Tiền trung cấp', 'duration' => 90, 'questions' => 80, 'pass_score' => '180/300', 'badgeClass' => 'bg-sky-500/10 text-sky-700 dark:text-sky-300 border-sky-500/20'],
                        'hsk4' => ['tag' => 'Trung cấp', 'duration' => 105, 'questions' => 100, 'pass_score' => '180/300', 'badgeClass' => 'bg-purple-500/10 text-purple-700 dark:text-purple-300 border-purple-500/20'],
                        'hsk5' => ['tag' => 'Cao cấp', 'duration' => 125, 'questions' => 100, 'pass_score' => '180/300', 'badgeClass' => 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-500/20'],
                        'hsk6' => ['tag' => 'Thượng thừa', 'duration' => 140, 'questions' => 101, 'pass_score' => '180/300', 'badgeClass' => 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border-indigo-500/20'],
                    ];
                @endphp

                @foreach($hskLevels as $hLevel)
                    @php
                        $code = strtolower($hLevel->level_code);
                        $meta = $levelMeta[$code] ?? ['tag' => 'HSK', 'duration' => 40, 'questions' => 40, 'pass_score' => '120/200', 'badgeClass' => 'bg-slate-100 text-slate-700 border-slate-200'];
                        $examCount = $hLevel->mock_exams_count ?? 0;
                        $hasExams = $examCount > 0;
                        $levelNumber = str_replace('hsk', '', $code);
                        $showUrl = route('student.hsk-mock-exams.show', ['level' => $levelNumber]);
                    @endphp

                    @if($hasExams)
                        <!-- Card Cấp Độ Có Đề Thi -->
                        <a href="{{ $showUrl }}" 
                           class="lms-card p-5 flex flex-col justify-between group hover:border-[#e07a5f] hover:shadow-md transition-all duration-200 relative overflow-hidden btn-tactile">
                            <div class="space-y-3.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors flex items-center gap-2">
                                            <span>{{ $hLevel->title }}</span>
                                        </h3>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                                            {{ $hLevel->subtitle ?? __('Chuẩn quốc tế') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0 flex-wrap justify-end">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $meta['badgeClass'] }}">
                                            {{ __($meta['tag']) }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            {{ $examCount }} {{ __('Đề thi') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div class="p-2 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                                        <div class="text-[10px] text-slate-400 font-medium">{{ __('Thời gian') }}</div>
                                        <div class="font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $meta['duration'] }} {{ __('Phút') }}</div>
                                    </div>
                                    <div class="p-2 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                                        <div class="text-[10px] text-slate-400 font-medium">{{ __('Số câu') }}</div>
                                        <div class="font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $meta['questions'] }} {{ __('Câu') }}</div>
                                    </div>
                                    <div class="p-2 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                                        <div class="text-[10px] text-slate-400 font-medium">{{ __('Điểm đỗ') }}</div>
                                        <div class="font-bold text-[#e07a5f] mt-0.5">{{ $meta['pass_score'] }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3.5 border-t border-[#e8e2d9] dark:border-[#2d2926] mt-4 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-users text-[11px] text-[#0284c7]"></i>
                                    <span>{{ number_format($hLevel->mock_exams_sum_attempt_count ?? 0) }} {{ __('lượt làm') }}</span>
                                </span>
                                <span class="px-3.5 py-1.5 rounded-xl bg-[#e07a5f] group-hover:bg-[#c86349] text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                                    <span>{{ __('Xem chi tiết') }}</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                                </span>
                            </div>
                        </a>
                    @else
                        <!-- Card Cấp Độ Chưa Có Đề Thi (Đang cập nhật) -->
                        <div class="lms-card p-5 flex flex-col justify-between opacity-75 relative overflow-hidden bg-[#faf8f5]/40 dark:bg-[#151413]/40 border-dashed border-[#dfd7cc] dark:border-[#38332f]">
                            <div class="space-y-3.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-600 dark:text-slate-300">
                                            {{ $hLevel->title }}
                                        </h3>
                                        <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 font-medium">
                                            {{ $hLevel->subtitle ?? __('Chuẩn quốc tế') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0 flex-wrap justify-end">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $meta['badgeClass'] }} opacity-70">
                                            {{ __($meta['tag']) }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center gap-1">
                                            <i class="fa-solid fa-clock-rotate-left text-[10px]"></i>
                                            <span>{{ __('Đang cập nhật') }}</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div class="p-2 rounded-xl bg-[#f5f1eb]/50 dark:bg-[#1a1816]/50 border border-[#e8e2d9]/60 dark:border-[#2d2926]/60 text-center">
                                        <div class="text-[10px] text-slate-400 font-medium">{{ __('Thời gian') }}</div>
                                        <div class="font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ $meta['duration'] }} {{ __('Phút') }}</div>
                                    </div>
                                    <div class="p-2 rounded-xl bg-[#f5f1eb]/50 dark:bg-[#1a1816]/50 border border-[#e8e2d9]/60 dark:border-[#2d2926]/60 text-center">
                                        <div class="text-[10px] text-slate-400 font-medium">{{ __('Số câu') }}</div>
                                        <div class="font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ $meta['questions'] }} {{ __('Câu') }}</div>
                                    </div>
                                    <div class="p-2 rounded-xl bg-[#f5f1eb]/50 dark:bg-[#1a1816]/50 border border-[#e8e2d9]/60 dark:border-[#2d2926]/60 text-center">
                                        <div class="text-[10px] text-slate-400 font-medium">{{ __('Điểm đỗ') }}</div>
                                        <div class="font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ $meta['pass_score'] }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3.5 border-t border-[#e8e2d9] dark:border-[#2d2926] mt-4 flex items-center justify-between">
                                <span class="text-xs text-slate-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-users text-[11px] opacity-40"></i>
                                    <span>0 {{ __('lượt làm') }}</span>
                                </span>
                                <span class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-slate-400 dark:text-slate-500 text-xs font-semibold cursor-not-allowed flex items-center gap-1.5 border border-slate-200/60 dark:border-slate-700/60">
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                    <span>{{ __('Chưa mở') }}</span>
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Cột Bảng Xếp Hạng Học Viên (1/3) -->
        <div id="leaderboard-section" class="space-y-4">
            <div class="lms-card p-5 space-y-4 sticky top-6">
                <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-trophy text-amber-500"></i> {{ __('Bảng Xếp Hạng') }} <span class="text-xs font-normal text-slate-400 zh-text">成绩榜</span>
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Top 8 Học viên xuất sắc nhất') }}</p>
                    </div>

                    <!-- Dropdown Lọc Theo Cấp Độ HSK -->
                    <div class="shrink-0">
                        <select x-model="leaderboardLevel" 
                                class="text-[11px] bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl px-2.5 py-1 font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#e07a5f] cursor-pointer">
                            <option value="all">{{ __('Tất cả cấp') }}</option>
                            <option value="hsk1">HSK 1</option>
                            <option value="hsk2">HSK 2</option>
                            <option value="hsk3">HSK 3</option>
                            <option value="hsk4">HSK 4</option>
                            <option value="hsk5">HSK 5</option>
                            <option value="hsk6">HSK 6</option>
                        </select>
                    </div>
                </div>

                <!-- Tabs Filter 1 Row -->
                <div class="flex items-center gap-1 bg-[#f8f6f3] dark:bg-[#201d1b] p-1 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] flex-nowrap overflow-x-auto no-scrollbar">
                    <button @click="leaderboardFilter = 'all_time'" :class="leaderboardFilter === 'all_time' ? 'bg-[#e07a5f] text-white shadow-sm font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700'" class="flex-1 py-1 px-2 rounded-lg text-[11px] whitespace-nowrap transition-all text-center btn-tactile">{{ __('Toàn thời gian') }}</button>
                    <button @click="leaderboardFilter = 'month'" :class="leaderboardFilter === 'month' ? 'bg-[#e07a5f] text-white shadow-sm font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700'" class="flex-1 py-1 px-2 rounded-lg text-[11px] whitespace-nowrap transition-all text-center btn-tactile">{{ __('Tháng này') }}</button>
                    <button @click="leaderboardFilter = 'week'" :class="leaderboardFilter === 'week' ? 'bg-[#e07a5f] text-white shadow-sm font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700'" class="flex-1 py-1 px-2 rounded-lg text-[11px] whitespace-nowrap transition-all text-center btn-tactile">{{ __('Tuần này') }}</button>
                </div>

                <!-- Leaderboard Item List with Loading state -->
                <div class="relative min-h-[160px]">
                    <!-- Loading Spinner Overlay -->
                    <div x-show="loadingLeaderboard" 
                         class="absolute inset-0 bg-white/70 dark:bg-[#181615]/70 backdrop-blur-[1px] flex items-center justify-center z-10 rounded-xl"
                         x-transition>
                        <i class="fa-solid fa-spinner animate-spin text-[#e07a5f] text-lg"></i>
                    </div>

                    <div class="space-y-2 max-h-[480px] overflow-y-auto pr-1 no-scrollbar">
                        <template x-for="item in leaderboard" :key="item.rank">
                            <div class="flex items-center justify-between p-2.5 rounded-xl transition-all btn-tactile"
                                 :class="item.rank === 1 ? 'bg-amber-500/10 border border-amber-500/30' : (item.rank === 2 ? 'bg-slate-200/50 dark:bg-slate-700/30 border border-slate-300 dark:border-slate-600' : (item.rank === 3 ? 'bg-amber-700/10 border border-amber-700/30' : 'bg-[#fcfaf7] dark:bg-[#1d1a18] border border-transparent hover:border-[#e8e2d9] dark:hover:border-[#2d2926]'))">
                                
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0"
                                          :class="item.rank === 1 ? 'bg-amber-500 text-white shadow-sm' : (item.rank === 2 ? 'bg-slate-400 text-white' : (item.rank === 3 ? 'bg-amber-700 text-white' : 'text-slate-400'))"
                                          x-text="'#' + item.rank">
                                    </span>
                                    <img :src="item.avatar" class="w-8 h-8 rounded-full object-cover border border-white dark:border-[#25211e] shrink-0" />
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate" x-text="item.name"></p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[9px] font-bold px-1.5 py-0.2 rounded border" :class="item.badgeBg" x-text="item.level"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-xs font-bold text-slate-900 dark:text-white" x-text="item.score"></div>
                                    <div class="text-[10px] text-slate-400 flex items-center justify-end gap-1">
                                        <i class="fa-solid fa-clock text-[#0284c7] text-[9px]"></i>
                                        <span x-text="item.time"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="leaderboard.length === 0 && !loadingLeaderboard" class="py-8 text-center text-xs text-slate-400">
                            <i class="fa-solid fa-award text-2xl text-slate-300 dark:text-slate-600 mb-2 block"></i>
                            {{ __('Chưa có dữ liệu bảng xếp hạng') }}
                        </div>
                    </div>
                </div>

                <!-- Footer button -->
                <div class="pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926] text-center">
                    <button class="text-xs font-semibold text-[#e07a5f] hover:underline flex items-center justify-center gap-1.5 w-full py-1.5">
                        <span>{{ __('Xem toàn bộ bảng xếp hạng (Top 100)') }}</span>
                        <i class="fa-solid fa-angle-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.hskLeaderboardData = @json($leaderboard ?? []);
</script>
@endsection
