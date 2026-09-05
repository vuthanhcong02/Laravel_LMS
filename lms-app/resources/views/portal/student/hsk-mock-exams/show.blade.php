@extends('layouts.lms')
@section('title', __('Danh sách đề thi') . ' ' . $hskLevel->title . ' - ' . __('Luyện thi HSK'))
@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Luyện thi HSK'), 'url' => route('student.hsk-mock-exams.index')],
        ['label' => $hskLevel->title, 'url' => null]
    ]" />
@endsection
@section('content')
<div x-data="{ showStructureModal: false }" class="space-y-6">
    <!-- Header & Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('student.hsk-mock-exams.index') }}" 
           class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-[#e07a5f] bg-white dark:bg-[#181615] px-4 py-2.5 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-sm btn-tactile w-fit transition-colors">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>{{ __('Quay lại các cấp độ HSK') }}</span>
        </a>
        <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-semibold text-slate-500 shadow-sm">
            <i class="fa-solid fa-book-open text-[#e07a5f]"></i>
            <span>{{ __('Tổng cộng') }}: <strong class="text-slate-800 dark:text-white">{{ $hskLevel->mockExams->count() }}</strong> {{ __('Đề thi') }}</span>
        </div>
    </div>
    <!-- Level Banner Card -->
    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-[#e07a5f]/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-[#e07a5f]/10 text-[#e07a5f] text-[11px] font-bold">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>{{ strtoupper($hskLevel->level_code) }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
                    {{ $hskLevel->title }} - {{ $hskLevel->subtitle ?? __('Luyện thi tiêu chuẩn') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                    {{ $hskLevel->description ?? __('Hệ thống đề thi thử HSK bám sát cấu trúc đề thi chính thức của Hanban, tự động chấm điểm và đánh giá chi tiết từng kỹ năng.') }}
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                <div class="px-4 py-2 rounded-xl bg-white dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                    <div class="text-[10px] text-slate-400 font-medium">{{ __('Thời gian làm bài') }}</div>
                    <div class="text-sm font-bold text-slate-800 dark:text-slate-200">
                        @php
                            $lvl = strtolower($hskLevel->level_code);
                            $durations = ['hsk1' => 40, 'hsk2' => 55, 'hsk3' => 90, 'hsk4' => 105, 'hsk5' => 125, 'hsk6' => 140];
                            $testDuration = $durations[$lvl] ?? 40;
                        @endphp
                        {{ $testDuration }} {{ __('Phút') }}
                    </div>
                </div>
                <div class="px-4 py-2 rounded-xl bg-white dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                    <div class="text-[10px] text-slate-400 font-medium">{{ __('Điểm tối đa') }}</div>
                    <div class="text-sm font-bold text-[#e07a5f]">
                        {{ in_array(strtolower($hskLevel->level_code), ['hsk1', 'hsk2']) ? '200' : '300' }} {{ __('Điểm') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-[#e07a5f]"></i>
                    <span>{{ __('Danh sách đề thi') }} ({{ $hskLevel->mockExams->count() }})</span>
                </h2>
                <button @click="showStructureModal = true" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-[#e07a5f] hover:text-[#e07a5f] shadow-xs btn-tactile transition-all">
                    <i class="fa-solid fa-circle-info text-[#e07a5f]"></i>
                    <span>{{ __('Cấu trúc đề thi') }}</span>
                </button>
            </div>
        </div>
        @if($hskLevel->mockExams->isEmpty())
            <div class="lms-card p-12 text-center space-y-3 bg-[#faf8f5]/60 dark:bg-[#151413]/60 border-dashed border-[#dfd7cc] dark:border-[#38332f]">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white">
                    {{ __('Chưa có đề thi nào') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                    {{ __('Hệ thống đang trong quá trình chuẩn bị và cập nhật bộ đề thi chuẩn cho cấp độ này. Vui lòng quay lại sau!') }}
                </p>
                <div class="pt-2">
                    <a href="{{ route('student.hsk-mock-exams.index') }}" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile inline-flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>{{ __('Xem cấp độ khác') }}</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($hskLevel->mockExams as $exam)
                    @php
                        $userResults = $exam->results ?? collect();
                        $statusBadge = '';
                        $actionBtn = '<span>' . __('Vào thi') . '</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>';
                        $highestResult = $userResults->where('status', 'completed')->sortByDesc('total_score')->first();
                        $inProgressResult = $userResults->where('status', 'in_progress')->first();
                        if ($highestResult) {
                            $maxPt = in_array(strtolower($hskLevel->level_code), ['hsk1', 'hsk2']) ? 200 : 300;
                            $statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center gap-1"><i class="fa-solid fa-medal text-[10px]"></i> ' . __('Đạt') . ': ' . $highestResult->total_score . '/' . $maxPt . '</span>';
                            $actionBtn = '<span>' . __('Thi lại') . '</span> <i class="fa-solid fa-rotate-right text-[10px]"></i>';
                        } elseif ($inProgressResult) {
                            $statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center gap-1"><i class="fa-solid fa-spinner animate-spin text-[10px]"></i> ' . __('Đang thi') . '</span>';
                            $actionBtn = '<span>' . __('Tiếp tục thi') . '</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>';
                        }
                        $levelNumber = str_replace('hsk', '', strtolower($hskLevel->level_code));
                        $actionUrl = route('student.hsk-mock-exams.start', ['level' => $levelNumber, 'id' => $exam->id]);
                    @endphp
                    <div class="lms-card p-5 space-y-4 flex flex-col justify-between group hover:border-[#e07a5f] hover:shadow-md transition-all duration-300">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/20">
                                    {{ strtoupper($hskLevel->level_code) }} • {{ __('Đề') }} {{ $exam->year ?? date('Y') }}
                                </span>
                                {!! $statusBadge !!}
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug">
                                {{ $exam->title }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $exam->description ?? __('Đề thi chuẩn cấu trúc HSK mới nhất.') }}
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="text-[10px] text-slate-400 font-medium">{{ __('Thời gian') }}</div>
                                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $exam->duration }} {{ __('Phút') }}</div>
                                </div>
                                <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                    <div class="text-[10px] text-slate-400 font-medium">{{ __('Số câu hỏi') }}</div>
                                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $exam->total_questions ?? 0 }} {{ __('Câu') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                            <span class="text-xs text-slate-400">
                                <i class="fa-solid fa-users mr-1"></i>{{ number_format($exam->attempt_count ?? 0) }} {{ __('lượt làm') }}
                            </span>
                            @auth
                                <a href="{{ $actionUrl }}" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5 shadow-sm">
                                    {!! $actionBtn !!}
                                </a>
                            @else
                                <button type="button" @click.prevent="authModalOpen = true; authModalTab = 'login'; authRedirectUrl = '{{ $actionUrl }}'" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-1.5 shadow-sm">
                                    {!! $actionBtn !!}
                                </button>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div x-show="showStructureModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showStructureModal = false">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showStructureModal = false"></div>
        <!-- Modal Box -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl overflow-hidden pointer-events-auto"
                 @click.outside="showStructureModal = false">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between bg-[#fcfaf7] dark:bg-[#201d1b]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#e07a5f]/10 text-[#e07a5f] flex items-center justify-center text-sm">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                {{ __('Cấu trúc đề thi') }} {{ $hskLevel->title }}
                            </h3>
                            <p class="text-[11px] text-slate-400">
                                {{ __('Tiêu chuẩn đánh giá năng lực Hán ngữ Hanban') }}
                            </p>
                        </div>
                    </div>
                    <button @click="showStructureModal = false" class="w-8 h-8 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    @if(!empty($hskLevel->exam_structure))
                        @php $structure = $hskLevel->exam_structure; @endphp
                        @if(!empty($structure['note']))
                            <div class="text-xs text-slate-600 dark:text-slate-300 bg-[#fff7f4] dark:bg-[#221c19] p-4 rounded-2xl border border-[#fcdccf] dark:border-[#382620] leading-relaxed">
                                <div class="font-bold text-[#e07a5f] mb-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-lightbulb"></i>
                                    <span>{{ __('Lưu ý quan trọng') }}:</span>
                                </div>
                                {!! nl2br(e($structure['note'])) !!}
                            </div>
                        @endif
                        <div class="space-y-4">
                            @foreach($structure['sections'] ?? [] as $section)
                                @php
                                    $secTitle = $section['title'] ?? '';
                                    $isListen = stripos($secTitle, 'Nghe') !== false;
                                    $isRead = stripos($secTitle, 'Đọc') !== false;
                                    $isWrite = stripos($secTitle, 'Viết') !== false;
                                    $secIcon = $isListen ? 'fa-headphones text-sky-500' : ($isRead ? 'fa-book-open text-amber-500' : ($isWrite ? 'fa-pen-to-square text-emerald-500' : 'fa-list-check text-[#e07a5f]'));
                                @endphp
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-[#e8e2d9] dark:border-[#2d2926] pb-1.5">
                                        <span class="flex items-center gap-2 text-sm">
                                            <i class="fa-solid {{ $secIcon }}"></i>
                                            <span>{{ $secTitle }}</span>
                                        </span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold px-2.5 py-0.5 rounded-full bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                                            {{ $section['total_questions'] ?? 0 }} {{ __('Câu') }} • {{ $section['total_score'] ?? 100 }} {{ __('Điểm') }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        @foreach($section['parts'] ?? [] as $part)
                                            <div class="p-3 rounded-2xl bg-[#fcfaf7] dark:bg-[#1d1a18] border border-[#e8e2d9]/60 dark:border-[#2d2926]/60 text-xs space-y-1">
                                                <div class="flex items-center justify-between font-bold text-slate-800 dark:text-slate-200">
                                                    <span>{{ $part['name'] }}</span>
                                                    <span class="text-[10px] font-bold text-slate-500 bg-white dark:bg-[#25211e] px-2 py-0.5 rounded border border-[#e8e2d9] dark:border-[#2d2926]">
                                                        {{ $part['questions'] }} {{ __('câu') }}
                                                    </span>
                                                </div>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                                    {{ $part['description'] }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-xs text-slate-400">
                            <i class="fa-solid fa-clock text-2xl mb-2 text-slate-300 dark:text-slate-600 block"></i>
                            {{ __('Cấu trúc đề thi đang được cập nhật...') }}
                        </div>
                    @endif
                </div>
                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-[#e8e2d9] dark:border-[#2d2926] bg-[#fcfaf7] dark:bg-[#201d1b] flex justify-end">
                    <button @click="showStructureModal = false" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-sm">
                        {{ __('Đã hiểu') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
