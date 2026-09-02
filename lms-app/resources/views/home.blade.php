@extends('layouts.lms')

@section('title', 'Tiếng Trung XIAOMU - Trang chủ')

@section('alpine-data')
    rankTab: 'week', 
    rankMetric: 'time', 
    socialDockExpanded: true,
@endsection

@section('content')
<div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 max-w-7xl">
                    
                    <!-- Luồng nội dung chính (2 cột) -->
                    <div class="lg:col-span-2 space-y-8 animate-fade-in-up">
                        
                        <!-- Banner Chào mừng (Meiday Inspired) -->
                        <div class="lms-card p-6 sm:p-8 bg-gradient-to-br from-[#1c3848] via-[#1a3342] to-[#152834] text-white relative overflow-hidden group shadow-lg">
                            <!-- Faint Watermark Chinese Character -->
                            <div class="absolute right-4 -bottom-6 text-9xl font-extrabold text-white/5 pointer-events-none select-none zh-text">
                                学
                            </div>
                            <div class="absolute inset-0 shimmer-bg pointer-events-none opacity-20"></div>

                            <div class="relative z-10 space-y-4 max-w-xl">
                                <div class="space-y-2">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs border border-white/15 text-white/90 text-xs font-semibold">
                                        {{ __('Chào mừng, bạn') }} 👋
                                    </div>
                                    <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight leading-snug">
                                        {{ __('Bắt đầu hành trình học tiếng Trung nào!') }}
                                    </h1>
                                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                        {{ __('Chọn một lộ trình bên dưới và hoàn thành bài học đầu tiên để mở khoá chuỗi ngày của bạn.') }}
                                    </p>
                                </div>

                                <!-- CTA Buttons in Banner -->
                                <div class="flex flex-wrap items-center gap-3 pt-1">
                                    @php
                                        $lesson1Url = ($suggestedLesson && $suggestedLesson->slug) 
                                            ? route('courses.lesson', ['levelSlug' => 'hsk-1', 'lessonSlug' => $suggestedLesson->slug]) 
                                            : route('courses.level', ['levelSlug' => 'hsk-1']);
                                    @endphp
                                    <a href="{{ $lesson1Url }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-900 rounded-xl text-xs font-bold shadow-md transition-all btn-tactile">
                                        <i class="fa-solid fa-play text-[10px]"></i> {{ __('Bắt đầu bài học đầu tiên') }}
                                    </a>
                                    <a href="#roadmap" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs font-bold transition-all btn-tactile">
                                        <i class="fa-solid fa-bullseye text-[11px] text-amber-400"></i> {{ __('Chọn lộ trình phù hợp') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Thẻ Thống kê nhanh (Quick Stats / Teaser) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Card 1: Chuỗi ngày học -->
                            <div class="lms-card p-5 flex items-center gap-4 hover:border-[#e07a5f]/50 transition-all">
                                <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xl shrink-0 shadow-xs">
                                    <i class="fa-solid fa-fire animate-flame"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white">0</span>
                                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Ngày liên tục') }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5">
                                        {{ __('Học bài hôm nay để bắt đầu chuỗi!') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Card 2: Tiến độ bài học -->
                            <div class="lms-card p-5 flex items-center gap-4 hover:border-[#e07a5f]/50 transition-all">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 shadow-xs">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white">0</span>
                                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Bài đã hoàn thành') }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5">
                                        {{ __('Hàng trăm bài học đang chờ bạn') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Khối Gợi ý cho bạn (Bài học mở đầu) -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-1 h-5 rounded-full bg-[#e07a5f]"></div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">
                                        {{ __('Gợi ý cho bạn') }}
                                    </h2>
                                </div>
                                <a href="{{ route('courses') }}" class="text-xs font-semibold text-[#e07a5f] hover:underline inline-flex items-center gap-1">
                                    {{ __('Xem tất cả') }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>

                            <!-- Card bài học mở đầu nổi bật -->
                            <div class="lms-card p-5 sm:p-6 hover:shadow-md transition-all group border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                                    <!-- Badge / Thumbnail chữ Hán -->
                                    <div class="w-full sm:w-36 h-28 sm:h-28 rounded-2xl bg-gradient-to-br from-[#e6f0f5] to-[#d6e6f0] dark:from-[#1b3240] dark:to-[#162833] flex flex-col items-center justify-center text-center p-3 shrink-0 border border-slate-200 dark:border-slate-700 shadow-xs">
                                        <span class="text-3xl font-extrabold text-[#1c3848] dark:text-[#a0c4d8] zh-text tracking-wider">汉语</span>
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1">
                                            HSK 3.0 • {{ __('Cấp 1') }} • {{ __('Bài') }} {{ $suggestedLesson->lesson_number ?? 1 }}
                                        </span>
                                    </div>

                                    <!-- Thông tin bài học -->
                                    <div class="flex-1 space-y-2 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] uppercase tracking-wider">
                                                {{ __('Bài học mở đầu') }}
                                            </span>
                                            @if(!empty($suggestedLesson->code))
                                                <span class="text-[11px] text-slate-400">• {{ $suggestedLesson->code }}</span>
                                            @endif
                                        </div>
                                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug zh-text">
                                            {{ $suggestedLesson->title ?? '你好！' }}
                                        </h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                                            {{ $suggestedLesson->translation ?? 'Xin chào!' }}
                                        </p>
                                        
                                        <div class="pt-2 flex items-center justify-between">
                                            <a href="{{ $lesson1Url }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#244255] hover:bg-[#1a3342] dark:bg-[#e07a5f] dark:hover:bg-[#c86349] text-white rounded-xl text-xs font-bold transition-all btn-tactile shadow-xs">
                                                <i class="fa-solid fa-play text-[10px]"></i> {{ __('Bắt đầu học') }}
                                            </a>
                                            <span class="text-xs text-slate-400 font-medium">
                                                {{ $suggestedLesson->vocab_list_count ?? 11 }} {{ __('từ') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Khối Lộ trình của bạn -->
                        <div id="roadmap" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-1 h-5 rounded-full bg-[#e07a5f]"></div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">
                                        {{ __('Lộ trình của bạn') }}
                                    </h2>
                                </div>
                                <a href="{{ route('courses') }}" class="text-xs font-semibold text-[#e07a5f] hover:underline inline-flex items-center gap-1">
                                    {{ __('Tất cả') }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <!-- HSK 1 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-1']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded">HSK 1</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-[#e07a5f] group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Nhập môn căn bản') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">150 {{ __('từ vựng') }} • 15 {{ __('bài') }}</p>
                                </a>

                                <!-- HSK 2 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-2']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded">HSK 2</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-[#e07a5f] group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Giao tiếp đời sống') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">300 {{ __('từ vựng') }} • 15 {{ __('bài') }}</p>
                                </a>

                                <!-- HSK 3 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-3']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded">HSK 3</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-[#e07a5f] group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Sơ trung cấp') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">600 {{ __('từ vựng') }} • 20 {{ __('bài') }}</p>
                                </a>

                                <!-- HSK 4 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-4']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/40 px-2 py-0.5 rounded">HSK 4</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-sky-500 group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Trung cấp vững vàng') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">1200 {{ __('từ vựng') }} • 20 {{ __('bài') }}</p>
                                </a>

                                <!-- HSK 5 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-5']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded">HSK 5</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Cao cấp thành thạo') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">2500 {{ __('từ vựng') }} • 36 {{ __('bài') }}</p>
                                </a>

                                <!-- HSK 6 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-6']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/40 px-2 py-0.5 rounded">HSK 6</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-purple-500 group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Bậc thầy Hán ngữ') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">5000+ {{ __('từ vựng') }} • Chuyên sâu</p>
                                </a>
                            </div>
                        </div>

                        {{-- <!-- Nhiệm vụ hàng ngày -->
                        <div class="lms-card p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-[#e07a5f]"></i> Nhiệm vụ hàng ngày
                                </h2>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">2/3 Hoàn thành</span>
                            </div>

                            <div class="space-y-3">
                                <!-- Nhiệm vụ 1 -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-bold shadow-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-900 dark:text-white line-through opacity-70">Luyện tập 10 câu Pinyin</p>
                                            <p class="text-[10px] text-slate-400">Thưởng +50 điểm</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Đã xong</span>
                                </div>

                                <!-- Nhiệm vụ 2 -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-bold shadow-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-900 dark:text-white line-through opacity-70">Ôn tập 20 thẻ ghi nhớ từ vựng</p>
                                            <p class="text-[10px] text-slate-400">Thưởng +30 điểm</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Đã xong</span>
                                </div>

                                <!-- Nhiệm vụ 3 -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-[#221f1d] border border-[#e8e2d9] dark:border-[#2e2a27] shadow-xs">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-rose-400 flex items-center justify-center text-sm font-bold">
                                            <i class="fa-solid fa-fire animate-flame"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-[#e07a5f]">Làm 1 đề thi thử HSK 5</p>
                                            <p class="text-[10px] text-[#e07a5f]">Nhận thưởng +100 điểm</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.hsk-mock-exams.index') }}" class="px-3.5 py-1.5 bg-[#e07a5f] text-white rounded-lg text-xs font-bold shadow-xs hover:bg-[#c86349] transition-all btn-tactile">
                                        Làm ngay
                                    </a>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <!-- 📅 WIDGET 90 NGÀY GẦN NHẤT -->
                        <div x-data="{
                            selectedDay: {
                                fullDateStr: 'Chủ Nhật, 23 tháng 8, 2026',
                                isToday: true,
                                minutes: 7,
                                summary: 'Thi thử HSK 5 & Luyện tập 10 câu Pinyin',
                                hasActivity: true
                            },
                            selectDay(dateStr, isToday, minutes, summary, hasActivity) {
                                this.selectedDay = { fullDateStr: dateStr, isToday: isToday, minutes: minutes, summary: summary, hasActivity: hasActivity };
                            }
                        }" class="lms-card p-5 sm:p-6 space-y-4">
                            <!-- Title Header -->
                            <div class="space-y-1">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <i class="fa-regular fa-calendar-days text-[#e07a5f]"></i> 90 ngày gần nhất
                                </h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                                    Bấm vào một ngày để xem chi tiết. Ô càng đậm = học càng nhiều.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-stretch">
                                
                                <!-- Heatmap Grid Panel (6 cols in LG) -->
                                <div class="lg:col-span-6 flex flex-col justify-between bg-[#fcfaf7] dark:bg-[#23201e] p-3.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="space-y-2">
                                        
                                        <!-- NHÃN 3 THÁNG GẦN NHẤT CHUẨN ĐỒNG ĐỀU -->
                                        <div class="flex items-center justify-between text-[10px] font-bold text-slate-600 dark:text-slate-400 pl-6 pr-2">
                                            <span>Tháng 6</span>
                                            <span>Tháng 7</span>
                                            <span>Tháng 8</span>
                                        </div>

                                        <!-- LƯỚI HEATMAP 13 TUẦN LIỀN NHAU DI DÍT ĐỀU ĐẶN GAP-1 -->
                                        <div class="flex items-center gap-1.5">
                                            <!-- Trục Thứ trong tuần -->
                                            <div class="flex flex-col justify-between text-[9px] font-medium text-slate-400 dark:text-slate-500 h-24 pr-1 py-0.5 shrink-0">
                                                <span>T3</span>
                                                <span>T5</span>
                                                <span>T7</span>
                                            </div>

                                            <!-- Vùng chứa 13 cột di dít đều đặn không khoảng cách đại diện cho 91 ngày -->
                                            <div class="grid grid-flow-col grid-rows-7 gap-1 flex-1 overflow-x-auto no-scrollbar py-0.5">
                                                
                                                <!-- Cột 1 -->
                                                <button @click="selectDay('Thứ Hai, 25 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]" title="25 thg 5, 2026"></button>
                                                <button @click="selectDay('Thứ Ba, 26 tháng 5, 2026', false, 15, 'Học 20 Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]" title="26 thg 5, 2026: 15p"></button>
                                                <button @click="selectDay('Thứ Tư, 27 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 28 tháng 5, 2026', false, 30, 'Luyện tập Pinyin 15 câu', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 29 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 30 tháng 5, 2026', false, 45, 'Ôn tập 50 Thẻ ghi nhớ', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 31 tháng 5, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 2 -->
                                                <button @click="selectDay('Thứ Hai, 1 tháng 6, 2026', false, 20, 'Học Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 2 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 3 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 4 tháng 6, 2026', false, 35, 'Luyện Nghe HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 5 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 6 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 7 tháng 6, 2026', false, 15, 'Luyện phát âm Pinyin', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 3 -->
                                                <button @click="selectDay('Thứ Hai, 8 tháng 6, 2026', false, 30, 'Thi thử HSK 4 phần Đọc', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 9 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 10 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 11 tháng 6, 2026', false, 50, 'Làm 1 Đề thi HSK 5 hoàn chỉnh', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 12 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 13 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 14 tháng 6, 2026', false, 20, 'Ôn tập bộ Flashcards', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 4 -->
                                                <button @click="selectDay('Thứ Hai, 15 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 16 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 17 tháng 6, 2026', false, 15, 'Đã điểm danh hàng ngày', true)" class="w-3 h-3 rounded-[2px] bg-[#06b6d4] border border-[#a5f3fc] hover:ring-1 hover:ring-[#e07a5f]" title="17 thg 6: Đã điểm danh"></button>
                                                <button @click="selectDay('Thứ Năm, 18 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 19 tháng 6, 2026', false, 30, 'Luyện tập Ngữ pháp HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 20 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 21 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 5 -->
                                                <button @click="selectDay('Thứ Hai, 22 tháng 6, 2026', false, 15, 'Đọc Blog tiếng Trung', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 23 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 24 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 25 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 26 tháng 6, 2026', false, 40, 'Học 30 Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 27 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 28 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 6 -->
                                                <button @click="selectDay('Thứ Hai, 29 tháng 6, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 30 tháng 6, 2026', false, 25, 'Ôn tập Pinyin nâng cao', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 1 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 2 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 3 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 4 tháng 7, 2026', false, 15, 'Học Từ vựng chủ đề Giao tiếp', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 5 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 7 -->
                                                <button @click="selectDay('Thứ Hai, 6 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 7 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 8 tháng 7, 2026', false, 45, 'Thi thử HSK 5 phần Viết', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 9 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 10 tháng 7, 2026', false, 30, 'Luyện dịch văn bản HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 11 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 12 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 8 -->
                                                <button @click="selectDay('Thứ Hai, 13 tháng 7, 2026', false, 25, 'Học 20 Từ vựng HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 14 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 15 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 16 tháng 7, 2026', false, 15, 'Luyện phát âm chuẩn', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 17 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 18 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 19 tháng 7, 2026', false, 50, 'Thi thử Đề HSK 5 Số 02', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 9 -->
                                                <button @click="selectDay('Thứ Hai, 20 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 21 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 22 tháng 7, 2026', false, 30, 'Luyện tập 15 câu Pinyin', true)" class="w-3 h-3 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 23 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 24 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 25 tháng 7, 2026', false, 15, 'Học Thẻ từ vựng', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 26 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 10 -->
                                                <button @click="selectDay('Thứ Hai, 27 tháng 7, 2026', false, 15, 'Ôn tập Ngữ pháp', true)" class="w-3 h-3 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 28 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 29 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 30 tháng 7, 2026', false, 40, 'Thi thử Đọc HSK 5', true)" class="w-3 h-3 rounded-[2px] bg-[#10b981] dark:bg-[#34d399] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 31 tháng 7, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 1 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]" title="1 thg 8, 2026: Chưa học"></button>
                                                <button @click="selectDay('Chủ Nhật, 2 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 11 -->
                                                <button @click="selectDay('Thứ Hai, 3 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 4 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 5 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 6 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 7 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 8 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 9 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 12 -->
                                                <button @click="selectDay('Thứ Hai, 10 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 11 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 12 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 13 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 14 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 15 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 16 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>

                                                <!-- Cột 13 (Hôm nay 23/8/2026 - Tile cuối cùng viền đỏ cờ) -->
                                                <button @click="selectDay('Thứ Hai, 17 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Ba, 18 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Tư, 19 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Năm, 20 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Sáu, 21 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Thứ Bảy, 22 tháng 8, 2026', false, 0, '', false)" class="w-3 h-3 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926] hover:ring-1 hover:ring-[#e07a5f]"></button>
                                                <button @click="selectDay('Chủ Nhật, 23 tháng 8, 2026', true, 7, 'Thi thử HSK 5 & Luyện tập 10 câu Pinyin', true)" class="w-3 h-3 rounded-[2px] bg-white dark:bg-[#23201e] border-2 border-[#e07a5f] hover:ring-2 hover:ring-[#e07a5f]/40" title="Hôm nay 23 thg 8: 7p"></button>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Legend Bar -->
                                    <div class="pt-2 flex flex-wrap items-center justify-between text-[10px] font-normal text-slate-500 dark:text-slate-400 gap-1.5 border-t border-[#e8e2d9] dark:border-[#2d2926] mt-2">
                                        <div class="flex items-center gap-1">
                                            <span>Ít</span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#e8e2d9] dark:bg-[#2d2926]"></span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#a7f3d0] dark:bg-[#1b4d3e]"></span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#34d399] dark:bg-[#267d5f]"></span>
                                            <span class="w-2.5 h-2.5 rounded-[2px] bg-[#10b981] dark:bg-[#34d399]"></span>
                                            <span>Nhiều</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-[2px] border border-[#06b6d4]"></span> Đã điểm danh</span>
                                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-[2px] bg-[#06b6d4]"></span> Freeze</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Middle Panel: Selected Day Activity Card (3 cols in LG) -->
                                <div class="lg:col-span-3 flex flex-col justify-between p-3.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="space-y-2">
                                        <div class="flex items-start justify-between gap-1.5">
                                            <span class="text-xs font-bold text-slate-800 dark:text-white leading-snug" x-text="selectedDay.fullDateStr">Chủ Nhật, 23 tháng 8, 2026</span>
                                            <template x-if="selectedDay.isToday">
                                                <span class="px-2 py-0.5 rounded-full border border-[#e07a5f] text-[#e07a5f] font-semibold text-[10px] shrink-0">Hôm nay</span>
                                            </template>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal leading-relaxed" x-text="selectedDay.hasActivity ? selectedDay.summary : 'Không có hoạt động nào ngày này'">
                                            Không có hoạt động nào ngày này
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1 text-xs text-slate-500 font-medium pt-2 mt-3 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> <span x-text="selectedDay.minutes + 'p'">7p</span>
                                    </div>
                                </div>

                                <!-- Right Panel: TỔNG KẾT 90 NGÀY Card (3 cols in LG) -->
                                <div class="lg:col-span-3 flex flex-col justify-between p-3.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="space-y-2.5">
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TỔNG KẾT 90 NGÀY</div>
                                        
                                        <div class="space-y-2 text-xs">
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-solid fa-bolt-lightning text-emerald-500 text-[11px]"></i> Ngày có học
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">1 / 90</span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-regular fa-clock text-[#0284c7] text-[11px]"></i> Thời gian học
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">2p</span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-solid fa-chart-line text-[#e07a5f] text-[11px]"></i> Chuỗi dài nhất
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">1 ngày</span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1.5">
                                                    <i class="fa-solid fa-feather-pointed text-amber-500 text-[11px]"></i> Hoạt động chính
                                                </span>
                                                <span class="font-bold text-slate-800 dark:text-white text-xs">Chính tả</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> --}}

                    </div>

                    <!-- 🏆 CỘT BẢNG XẾP HẠNG CHI TIẾT (BỘ LỌC CÙNG 1 DÒNG DUY NHẤT KHÔNG XUỐNG DÒNG) -->
                    <div class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                        
                        {{-- <!-- CARD BẢNG XẾP HẠNG -->
                        <div class="lms-card p-5 sm:p-6 space-y-4">
                            
                            <!-- Header Bảng xếp hạng -->
                            <div class="space-y-1">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <i class="fa-solid fa-trophy text-amber-500 text-lg"></i> Bảng xếp hạng
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Xem bạn đang đứng thứ mấy so với các bạn khác</p>
                                <p class="text-[11px] font-semibold text-slate-400">17 thg 8 → 23 thg 8</p>
                            </div>

                            <!-- BỘ LỌC CÙNG 1 DÒNG DUY NHẤT (FLEX-NOWRAP, KÍCH THƯỚC COMPACT PHÙ HỢP 100%) -->
                            <div class="flex items-center justify-between gap-1.5 flex-nowrap overflow-x-auto no-scrollbar border-b border-[#e8e2d9] dark:border-[#2a2624] pb-3">
                                <!-- Filter 1: Thời gian -->
                                <div class="flex bg-[#fcfaf7] dark:bg-[#23201e] p-0.5 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-semibold shrink-0">
                                    <button @click="rankTab = 'week'" :class="rankTab === 'week' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2.5 py-1 rounded-full transition-all">Tuần này</button>
                                    <button @click="rankTab = 'month'" :class="rankTab === 'month' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2.5 py-1 rounded-full transition-all">Tháng này</button>
                                </div>

                                <!-- Filter 2: Metric -->
                                <div class="flex bg-[#fcfaf7] dark:bg-[#23201e] p-0.5 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-semibold shrink-0">
                                    <button @click="rankMetric = 'xp'" :class="rankMetric === 'xp' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2 py-1 rounded-full transition-all flex items-center gap-1">✨ XP</button>
                                    <button @click="rankMetric = 'time'" :class="rankMetric === 'time' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400'" class="px-2 py-1 rounded-full transition-all flex items-center gap-1"><i class="fa-regular fa-clock"></i> Thời gian học</button>
                                </div>
                            </div>

                            <!-- CARD VỊ TRÍ HIỆN TẠI NỔI BẬT CỦA BẠN -->
                            <div class="p-3 rounded-2xl bg-[#fff7f4] dark:bg-[#2e1d1b] border-2 border-[#e07a5f] shadow-xs relative overflow-hidden flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 w-9 text-center shrink-0">#3854</span>
                                    <div class="relative shrink-0">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-9 h-9 rounded-full object-cover border-2 border-[#e07a5f]">
                                        <span class="absolute -bottom-1 -right-1 bg-slate-800 text-white text-[9px] font-semibold px-1 rounded-full border border-white">Lv.1</span>
                                    </div>
                                    <div class="truncate">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-bold text-slate-800 dark:text-white truncate">Vũ Thành Công</span>
                                            <span class="px-1.5 py-0.2 bg-[#e07a5f] text-white text-[9px] font-bold rounded-md uppercase tracking-wider">YOU</span>
                                        </div>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Lv 1</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-[#e07a5f] dark:text-[#f4978e] shrink-0 flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[#0284c7]"></i> 7p
                                </span>
                            </div>

                            <!-- DANH SÁCH BẢNG XẾP HẠNG THÀNH VIÊN (#1 → #8) -->
                            <div class="space-y-2">
                                
                                <!-- Hạng 1 (Thẻ Vàng) -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#fffbeb] dark:bg-[#23201e] border border-[#f59e0b]/80 dark:border-[#2d2926] hover:shadow-xs transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-[#d97706] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">#1</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-9 h-9 rounded-full object-cover border border-[#f59e0b]">
                                            <span class="absolute -bottom-1 -right-1 bg-purple-600 text-white text-[9px] font-bold px-1 rounded-full border border-white">Lv.63</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate zh-text">胡佑芳 🌿</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">Lv 63</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-white shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 25g 24p
                                    </span>
                                </div>

                                <!-- Hạng 2 (Thẻ Bạc) -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#f0f9ff] dark:bg-[#23201e] border border-[#38bdf8]/80 dark:border-[#2d2926] hover:shadow-xs transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-[#0284c7] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">#2</span>
                                        <div class="relative shrink-0">
                                            <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold text-xs">T</div>
                                            <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white text-[9px] font-bold px-1 rounded-full border border-white">Lv.23</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate">Thảo Trần</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">Lv 23</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-white shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 20g 0p
                                    </span>
                                </div>

                                <!-- Hạng 3 (Thẻ Đồng) -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#fff7ed] dark:bg-[#23201e] border border-[#f97316]/80 dark:border-[#2d2926] hover:shadow-xs transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-[#c2410c] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">#3</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-9 h-9 rounded-full object-cover border border-[#ea580c]">
                                            <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white text-[9px] font-bold px-1 rounded-full border border-white">Lv.21</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate">Nhi Trần</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">Lv 21</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-white shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 18g 31p
                                    </span>
                                </div>

                                <!-- Hạng 4 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#4</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-8 h-8 rounded-full object-cover">
                                            <span class="absolute -bottom-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.11</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Vũ Thị oanh</p>
                                            <p class="text-[10px] text-slate-400">Lv 11</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 16g 11p
                                    </span>
                                </div>

                                <!-- Hạng 5 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#5</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-8 h-8 rounded-full object-cover">
                                            <span class="absolute -bottom-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.12</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Xiaoyu</p>
                                            <p class="text-[10px] text-slate-400">Lv 12</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 14g 38p
                                    </span>
                                </div>

                                <!-- Hạng 6 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#6</span>
                                        <div class="relative shrink-0">
                                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&q=80" class="w-8 h-8 rounded-full object-cover">
                                            <span class="absolute -bottom-1 -right-1 bg-purple-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.49</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Cù Dung 🧑‍🎓</p>
                                            <p class="text-[10px] text-slate-400">Lv 49</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 13g 55p
                                    </span>
                                </div>

                                <!-- Hạng 7 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#7</span>
                                        <div class="relative shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold text-xs">Vi</div>
                                            <span class="absolute -bottom-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.11</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Vi Vi</p>
                                            <p class="text-[10px] text-slate-400">Lv 11</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 13g 16p
                                    </span>
                                </div>

                                <!-- Hạng 8 -->
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:bg-[#faf6f2] dark:hover:bg-[#282422] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-[#2a2624] font-semibold text-xs text-slate-500 flex items-center justify-center shrink-0">#8</span>
                                        <div class="relative shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-slate-300 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-user"></i></div>
                                            <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white text-[8px] font-bold px-1 rounded-full">Lv.27</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Hoa Trần</p>
                                            <p class="text-[10px] text-slate-400">Lv 27</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-medium shrink-0 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[#0284c7]"></i> 13g 3p
                                    </span>
                                </div>

                            </div>

                        </div> --}}

                        <!-- Widget Thẻ từ vựng mỗi ngày -->
                        <div x-data="{ 
                            playing: false,
                            timer: null,
                            playAudio() {
                                if (!('speechSynthesis' in window)) {
                                    alert('Trình duyệt không hỗ trợ phát âm.');
                                    return;
                                }

                                const synth = window.speechSynthesis;
                                const word = '{{ addslashes($wordOfDay->word ?? '坚持') }}';
                                if (!word) return;

                                // Xóa timer trước đó nếu người dùng click liên tục
                                if (this.timer) {
                                    clearTimeout(this.timer);
                                    this.timer = null;
                                }

                                // Đánh thức synth nếu bị Chrome pause ngầm
                                if (synth.paused) {
                                    synth.resume();
                                }

                                // Hủy phát âm hiện tại
                                synth.cancel();

                                this.playing = true;

                                // Chờ 60ms để Chrome dọn dẹp hàng đợi audio trước khi speak lượt mới
                                this.timer = setTimeout(() => {
                                    if (synth.paused) {
                                        synth.resume();
                                    }

                                    const utterance = new SpeechSynthesisUtterance(word);
                                    utterance.lang = 'zh-CN';
                                    utterance.rate = 0.85;

                                    // Gán giọng tiếng Trung phù hợp
                                    const voices = synth.getVoices();
                                    const zhVoice = voices.find(v => 
                                        v.lang === 'zh-CN' || v.lang === 'zh_CN' || 
                                        v.lang.startsWith('zh') || v.lang.startsWith('cmn')
                                    );
                                    if (zhVoice) {
                                        utterance.voice = zhVoice;
                                    }

                                    utterance.onend = () => {
                                        this.playing = false;
                                        window._activeUtterance = null;
                                    };

                                    utterance.onerror = () => {
                                        this.playing = false;
                                        window._activeUtterance = null;
                                    };

                                    // Giữ biến toàn cục tránh Garbage Collector giải phóng sớm
                                    window._activeUtterance = utterance;

                                    synth.speak(utterance);
                                }, 60);
                            }
                        }" class="lms-card p-6 space-y-3 relative group">
                            <div class="flex items-center justify-between text-xs text-[#e07a5f] font-bold">
                                <span>Từ vựng hôm nay</span>
                                <span class="px-2 py-0.5 bg-[#fff2ee] dark:bg-slate-800 rounded text-[10px] font-bold">HSK {{ $wordOfDay->level ?? 5 }}</span>
                            </div>

                            <div class="py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div>
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white zh-text">{{ $wordOfDay->word ?? '坚持' }}</span>
                                        <span class="text-sm font-bold text-[#e07a5f]">{{ $wordOfDay->pinyin ?? 'jiān chí' }}</span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-1">{{ $wordOfDay->meaning ?? 'Động từ: Kiên trì, giữ vững mục tiêu' }}</p>
                                </div>
                                <button type="button" 
                                    @click="playAudio()" 
                                    class="w-11 h-11 rounded-full flex items-center justify-center transition-all btn-tactile shadow-xs shrink-0 cursor-pointer focus:outline-none select-none"
                                    :class="playing ? 'bg-[#e07a5f] text-white ring-4 ring-[#e07a5f]/25 scale-105' : 'bg-[#fff2ee] dark:bg-slate-800 text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white'" 
                                    title="{{ __('Nghe phát âm') }}">
                                    <i class="fa-solid fa-volume-high text-sm leading-none pointer-events-none select-none" :class="playing ? 'animate-pulse' : ''"></i>
                                </button>
                            </div>

                            @if(!empty($wordOfDay->example))
                            <div class="p-3.5 bg-[#faf6f2] dark:bg-slate-800/80 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-xs">
                                <p class="text-slate-900 dark:text-white zh-text font-medium leading-relaxed">
                                    例句: {{ $wordOfDay->example }}
                                </p>
                                @if(!empty($wordOfDay->example_meaning))
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-normal">
                                    ({{ $wordOfDay->example_meaning }})
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Widget Kết nối Mạng xã hội XIAOMU (5 Nền tảng: FB, YT, Insta, TikTok, Zalo) -->
                        <div class="lms-card p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <i class="fa-solid fa-globe text-[#e07a5f]"></i> Kết nối cùng XIAOMU
                                </h3>
                                <span class="text-[10px] font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-slate-800 px-2 py-0.5 rounded">Cộng đồng</span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal leading-relaxed">
                                Theo dõi các kênh truyền thông chính thức để nhận mẹo thi HSK và tài liệu tiếng Trung mỗi ngày!
                            </p>

                            <div class="grid grid-cols-2 gap-2.5 pt-1">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/profile.php?id=61589009699142" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-[#1877f2] text-white flex items-center justify-center text-sm shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">Facebook</span>
                                </a>

                                <!-- YouTube -->
                                <a href="https://www.youtube.com/@Chiettuchuhan" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-[#FF0000] text-white flex items-center justify-center text-sm shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-brands fa-youtube"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">YouTube</span>
                                </a>

                                <!-- TikTok -->
                                <a href="https://www.tiktok.com/@chiettuchuhan55" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-black text-white border border-slate-700/30 flex items-center justify-center text-sm shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-brands fa-tiktok"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">TikTok</span>
                                </a>

                                <!-- Zalo Official -->
                                <a href="https://zalo.me/0395294739" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-[#0068ff] text-white font-black text-xs flex items-center justify-center shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        Zalo
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">Zalo</span>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Bottom Bar trên điện thoại -->
            <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white dark:bg-[#141211] border-t border-[#e8e2d9] dark:border-[#262220] flex items-center justify-around py-2.5 px-2 z-30 shadow-md">
                <a href="{{ url('/demo-ui') }}" class="flex flex-col items-center gap-0.5 text-[#e07a5f] btn-tactile">
                    <i class="fa-solid fa-house text-base"></i>
                    <span class="text-[10px] font-bold">Trang chủ</span>
                </a>
                <a href="{{ url('/demo-courses') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-book-open text-base"></i>
                    <span class="text-[10px] font-medium">Khóa học</span>
                </a>
                <a href="{{ url('/demo-exams') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-file-pen text-base"></i>
                    <span class="text-[10px] font-medium">Luyện thi</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-user text-base"></i>
                    <span class="text-[10px] font-medium">Cá nhân</span>
                </a>
            </div>
@endsection
