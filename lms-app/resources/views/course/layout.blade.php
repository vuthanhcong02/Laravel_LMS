@extends('layouts.app')

@section('title', 'Giáo trình chuẩn HSK')
@section('breadcrumb', 'Giáo trình chuẩn HSK')
@section('breadcrumb_desc', 'Hệ thống bài học tự do và bài giảng chi tiết bám sát bộ sách Giáo trình chuẩn HSK 1 - HSK 6.')
@section('hide_default_breadcrumb', true)

@section('content')
    <div x-data="hskApp()">
        <!-- Dynamic Dark Banner with Built-in Breadcrumb -->
        <section class="w-full py-12 md:py-16 relative overflow-hidden bg-gradient-to-r from-slate-950 via-[#1A2B3C] to-slate-950 border-b border-primary/20 transition-all duration-300">
            <!-- Grid Pattern Overlay (SaaS Coordinates) -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:32px_32px] opacity-80 pointer-events-none"></div>

            <!-- Soft Blur Orbs for background depth -->
            <div class="absolute right-[-10%] top-[-20%] w-[350px] h-[350px] bg-primary/30 rounded-xl blur-[120px] pointer-events-none"></div>
            <div class="absolute left-[30%] bottom-[-50%] w-[250px] h-[250px] bg-indigo-500/20 rounded-xl blur-[100px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10 flex flex-col items-start gap-4">
                <!-- Interactive Status Badge -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-primary/10 border border-primary/30 text-[9px] font-black text-primary uppercase tracking-widest shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-xl bg-primary animate-pulse"></span>
                    <span>Học tập & Rèn luyện</span>
                </div>

                <!-- Glassmorphism Dynamic Breadcrumb Navigation -->
                <nav aria-label="Breadcrumb" class="hidden sm:block">
                    <ol class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950/60 border border-white/20 backdrop-blur-md text-[11px] md:text-xs text-white font-semibold font-sans">
                        <li class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center hover:text-primary transition-colors gap-1 text-slate-350">
                                <span class="material-symbols-outlined text-[15px] font-bold">home</span>
                                <span>Trang chủ</span>
                            </a>
                        </li>
                        <li class="flex items-center">
                            <span class="material-symbols-outlined text-[14px] opacity-40 text-slate-400">chevron_right</span>
                        </li>
                        <li class="flex items-center">
                            <a href="{{ route('courses') }}" class="transition-colors {{ !$currentLevel ? 'text-primary font-bold pointer-events-none' : 'text-slate-350 hover:text-primary' }}">
                                Khóa học
                            </a>
                        </li>
                        @if($currentLevel)
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] opacity-40 text-slate-400">chevron_right</span>
                                <a href="{{ route('courses', ['level' => $currentLevel->id]) }}" class="transition-colors {{ !$currentLesson ? 'text-primary font-bold pointer-events-none' : 'text-slate-350 hover:text-primary' }}">
                                    {{ $currentLevel->title }} - {{ $currentLevel->subtitle }}
                                </a>
                            </li>
                        @endif
                        @if($currentLesson)
                            @php
                                $isDummyData = ($currentLesson->title === 'Bài ' . $currentLesson->lesson_number);
                                $displayTitleBreadcrumb = preg_replace('/^Bài\s+\d+[:\-]?\s*/i', '', $currentLesson->title);
                                $displayTitleBreadcrumb = empty(trim($displayTitleBreadcrumb)) ? '' : ': ' . $displayTitleBreadcrumb;
                                if ($isDummyData) {
                                    $displayTitleBreadcrumb = '';
                                }
                            @endphp
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] opacity-40 text-slate-400">chevron_right</span>
                                <span class="text-primary font-bold pointer-events-none">Bài {{ $currentLesson->lesson_number }}{{ $displayTitleBreadcrumb }}{{ ($currentLesson->translation && !$isDummyData) ? ' - ' . $currentLesson->translation : '' }}</span>
                            </li>
                        @endif
                    </ol>
                </nav>

                <!-- Main Banner Title & Dynamic Subtitle -->
                <div class="flex flex-col gap-2 mt-1">
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight drop-shadow-sm font-poppins leading-none">
                        @yield('breadcrumb')
                    </h1>
                    
                    <p class="text-xs md:text-sm text-slate-300 font-medium max-w-2xl leading-relaxed mt-1 opacity-95">
                        @yield('breadcrumb_desc')
                    </p>
                </div>
            </div>
        </section>

    <!-- Consolidated max-w-5xl viewport container for unified width and zero layout shifts -->
    <main 
        class="max-w-6xl mx-auto px-6 pt-4 pb-16 md:pt-4 flex-1 flex flex-col w-full"
    >

        <!-- State 1: Level Grid View (Unified width context) -->
        @if(!$currentLevel)
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Modern Header Banner -->
            <div class="text-center mb-12 relative">
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-xs font-bold bg-primary/10 text-primary border border-primary/20 uppercase tracking-widest mb-4">
                    <span class="h-1.5 w-1.5 rounded-xl bg-primary animate-ping"></span>
                    Lộ trình tự học tiếng Trung
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white mt-1 mb-3 leading-tight tracking-tight">
                    Chọn cấp độ HSK bắt đầu học
                </h2>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 max-w-lg mx-auto font-medium">
                    Hệ thống giáo trình chuẩn hóa từ HSK 1 đến HSK 6 với lộ trình bài bản, tinh gọn và hiệu quả.
                </p>
            </div>

            <!-- Levels Grid (3 Columns matching user design) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($levels as $level)
                    <a 
                        href="{{ route('courses', ['level' => $level->id]) }}"
                        class="group cursor-pointer bg-white dark:bg-slate-900/60 rounded-2xl border border-slate-105 dark:border-slate-800/80 shadow-sm hover:shadow-xl hover:-translate-y-1.5 active:scale-95 transition-all duration-300 flex flex-col relative overflow-hidden block"
                    >
                        <!-- Top Gradient glow bar -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{
                            $level->color === 'emerald' ? 'from-yellow-400 to-amber-500' :
                            ($level->color === 'cyan' ? 'from-teal-400 to-cyan-550' :
                            ($level->color === 'blue' ? 'from-red-400 to-rose-500' :
                            ($level->color === 'purple' ? 'from-purple-400 to-indigo-500' :
                            ($level->color === 'rose' ? 'from-[#be185d] to-[#db2777]' :
                            'from-blue-400 to-indigo-600'))))
                        }}"></div>

                        <!-- Top Half: Full-width HSK Book Cover Header -->
                        <div class="w-full h-40 relative overflow-hidden border-b border-slate-100 dark:border-slate-800/60 transition-colors duration-300 {{ $level->cover_bg }}">
                            <!-- Left Spine Highlight (Full Height) -->
                            <div class="absolute left-0 top-0 bottom-0 w-3 opacity-90 shadow-sm {{ $level->spine_color }}"></div>
                            
                            <!-- Book Typography content -->
                            <div class="pl-6 pr-4 py-4 flex flex-col justify-between h-full text-left">
                                <div class="flex justify-between items-center">
                                    <span class="text-[8px] font-black tracking-widest text-slate-450 uppercase">STANDARD COURSE</span>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded bg-slate-950/5 dark:bg-white/5 text-slate-400 dark:text-slate-550 uppercase tracking-wider">HSK</span>
                                </div>
                                
                                <div class="my-auto py-1">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-5xl font-black tracking-tighter {{ $level->number_color }}">{{ $level->level_code }}</span>
                                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">LEVEL</span>
                                    </div>
                                </div>
                                
                                <div class="text-[8px] font-black text-slate-400 leading-tight uppercase tracking-wider">
                                    GIÁO TRÌNH CHUẨN HSK
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Half: Content Info -->
                        <div class="p-5 flex flex-col flex-1 text-left">
                            <div class="flex justify-between items-center mb-3">
                                <span 
                                    class="px-2.5 py-1 rounded-xl text-[10px] font-black tracking-wider uppercase border {{
                                        $level->color === 'emerald' ? 'bg-yellow-105 border-yellow-250 text-yellow-600 dark:text-yellow-450' :
                                        ($level->color === 'cyan' ? 'bg-teal-105 border-teal-200 text-teal-650' :
                                        ($level->color === 'blue' ? 'bg-red-105 border-red-200 text-red-600' :
                                        ($level->color === 'purple' ? 'bg-purple-105 border-purple-250 text-purple-655' :
                                        ($level->color === 'rose' ? 'bg-rose-105 border-rose-250 text-rose-600' :
                                        'bg-blue-105 border-blue-250 text-blue-600'))))
                                    }}"
                                >{{ $level->title }}</span>
                                <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Miễn phí</span>
                            </div>

                            <h3 class="text-lg font-extrabold text-slate-800 dark:text-white leading-snug mb-1 group-hover:text-primary transition-colors duration-200">{{ $level->subtitle }}</h3>
                            <p class="text-xs text-slate-555 dark:text-slate-400 leading-relaxed mb-4 line-clamp-2">{{ $level->description }}</p>

                            <!-- Key Metrics Grid -->
                            <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 dark:border-slate-805/60 mt-auto">
                                <div>
                                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-0.5">Bài học</span>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-205">{{ $level->lessons_count }}</span>
                                </div>
                                <div>
                                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-0.5">Từ vựng</span>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-205">{{ $level->vocab_count }}</span>
                                </div>
                            </div>

                            <!-- Learn Now Arrow link -->
                            <div class="mt-4 flex justify-end">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 group-hover:text-primary transition-colors duration-200">
                                    <span>Học ngay</span>
                                    <span class="material-symbols-outlined text-[14px] transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @elseif($currentLevel && !$currentLesson)

        <!-- State 2: Detailed Lesson List View -->
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">


            @php
                $color = $currentLevel->color;
                $accentClass = $color === 'emerald' ? 'bg-[#eab308]' :
                               ($color === 'cyan' ? 'bg-[#0f766e]' :
                               ($color === 'blue' ? 'bg-[#dc2626]' :
                               ($color === 'purple' ? 'bg-[#6d28d9]' :
                               ($color === 'rose' ? 'bg-[#be185d]' : 'bg-[#1d4ed8]'))));
                $buttonClass = $color === 'emerald' ? 'bg-yellow-600 hover:bg-yellow-700' : 
                               ($color === 'cyan' ? 'bg-cyan-600 hover:bg-cyan-700' :
                               ($color === 'blue' ? 'bg-red-600 hover:bg-red-700' :
                               ($color === 'purple' ? 'bg-primary hover:bg-primary/90' :
                               ($color === 'rose' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-amber-600 hover:bg-amber-700'))));
            @endphp

            <!-- Curriclum Banner Hero -->
            <div class="p-6 md:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 text-left">
                <div class="flex items-center gap-4">
                    <div 
                        class="h-14 w-14 rounded-xl flex items-center justify-center font-black text-lg text-white shadow-md shadow-slate-950/10 animate-pulse {{ $accentClass }}"
                    >
                        <span>{{ explode(' ', $currentLevel->title)[0] ?? 'H' }}</span>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-slate-855 dark:text-white">{{ $currentLevel->title }} - {{ $currentLevel->subtitle }}</h2>
                        <p class="text-xs font-bold text-slate-405 dark:text-slate-505 mt-0.5">Lộ trình bài học chính khóa bám sát cấu trúc khung</p>
                    </div>
                </div>

                <!-- Stats Badges -->
                <div class="flex flex-wrap gap-2 md:self-auto self-stretch">
                    <span class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-855 text-xs font-bold text-slate-600 dark:text-slate-300">{{ $currentLevel->lessons_count }} Bài học</span>
                    <span class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-855 text-xs font-bold text-slate-600 dark:text-slate-300">{{ $currentLevel->vocab_count }} Từ vựng</span>
                </div>
            </div>

            <!-- Curriculum Roadmap -->
            <div class="space-y-4 text-left">
                @foreach($currentLevel->lessons as $lesson)
                    <div 
                        class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-805/85 shadow-sm hover:shadow-md hover:border-primary/20 dark:hover:border-primary/20 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div class="flex items-center gap-4">
                            <!-- Premium Big Lesson Number Indicator -->
                            <span 
                                class="text-3xl font-black text-slate-200 dark:text-slate-800 select-none w-12 text-center" 
                            >{{ $lesson->lesson_number < 10 ? '0' . $lesson->lesson_number : $lesson->lesson_number }}</span>
                            
                            <div>
                                <span class="text-[9px] font-black text-primary dark:text-primary-light uppercase tracking-wider block mb-0.5">Mã bài: <span>{{ $lesson->code }}</span></span>
                                <div class="flex items-baseline gap-2">
                                    @php
                                        $isDummyData = ($lesson->title === 'Bài ' . $lesson->lesson_number);
                                        // Xóa "Bài 1: ", "Bài 1 - " hoặc "Bài 1" ra khỏi đầu chuỗi title để hiển thị đẹp hơn với số lớn
                                        $displayTitle = preg_replace('/^Bài\s+\d+[:\-]?\s*/i', '', $lesson->title);
                                        // Nếu sau khi xóa mà chuỗi rỗng (trường hợp title chỉ có "Bài 1"), thì lấy lại title gốc
                                        $displayTitle = empty(trim($displayTitle)) ? $lesson->title : $displayTitle;

                                        if ($isDummyData) {
                                            $displayTitle = 'Đang cập nhật nội dung...';
                                        }
                                    @endphp
                                    <h3 class="text-base font-extrabold text-slate-800 dark:text-white">{{ $displayTitle }}</h3>
                                    @if($lesson->pinyin && !$isDummyData)
                                        <span class="text-xs font-bold text-slate-405 italic">/ {{ $lesson->pinyin }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5 font-semibold">{{ $isDummyData ? 'Vui lòng quay lại sau' : $lesson->translation }}</p>
                            </div>
                        </div>

                        <!-- Start Play Button -->
                        <a 
                            href="{{ route('courses.lesson', ['levelId' => $currentLevel->id, 'lessonId' => $lesson->id]) }}"
                            class="px-5 py-2.5 text-xs font-bold text-white rounded-xl shadow-sm transition-all duration-200 focus:outline-none flex items-center gap-1.5 self-start sm:self-auto cursor-pointer active:scale-95 {{ $buttonClass }}"
                        >
                            <span class="material-symbols-outlined text-[16px]">play_circle</span>
                            <span>Bắt đầu học</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @elseif($currentLevel && $currentLesson)

        <!-- State 3: Lesson View -->
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex-1 flex flex-col">
            
            <div class="space-y-3 flex-1 flex flex-col">

                <!-- Toolbar: Tab Navigation & Action Button -->
                    <div class="sticky top-[60px] md:top-[72px] z-40 -mx-6 sm:mx-0 px-6 sm:px-0 sm:rounded-2xl flex flex-col xl:flex-row xl:items-center w-full transition-all duration-500"
                         x-data="{ isStuck: false }"
                         @scroll.window="isStuck = window.scrollY > 300"
                         :class="isStuck ? 'p-2 sm:p-3 sm:px-3 border-y sm:border bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-slate-200/60 dark:border-slate-700/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] gap-3 xl:gap-0' : 'p-1.5 sm:p-1.5 sm:px-2 border-y sm:border bg-white dark:bg-slate-900 border-slate-200/60 dark:border-slate-700/60 shadow-sm gap-4 xl:gap-0'">
                         
                        <!-- Left Side: Context & Navigation (Expands when scrolled) -->
                        <div class="hidden lg:flex items-center overflow-hidden transition-all duration-500 origin-left" 
                             :class="isStuck ? 'max-w-[600px] pr-4 opacity-100' : 'max-w-0 pr-0 opacity-0 pointer-events-none'">
                             
                             <nav aria-label="Breadcrumb" class="w-max shrink-0 transition-transform duration-500 origin-left"
                                  :class="isStuck ? 'scale-100' : 'scale-95'">
                                 <ol class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 shadow-sm text-[10px] xl:text-[11px] text-slate-600 dark:text-slate-300 font-semibold font-sans whitespace-nowrap">
                                     <li class="flex items-center shrink-0">
                                         <a href="{{ route('home') }}" class="flex items-center hover:text-primary transition-colors gap-1 text-slate-500 dark:text-slate-400">
                                             <span class="material-symbols-outlined text-[13px] font-bold">home</span>
                                             <span class="hidden xl:inline">Trang chủ</span>
                                         </a>
                                     </li>
                                     <li class="flex items-center shrink-0">
                                         <span class="material-symbols-outlined text-[12px] text-slate-400 opacity-60">chevron_right</span>
                                     </li>
                                     <li class="flex items-center shrink-0">
                                         <a href="{{ route('courses') }}" class="transition-colors hover:text-primary">
                                             Khóa học
                                         </a>
                                     </li>
                                     @if($currentLevel)
                                         <li class="flex items-center gap-1.5 shrink-0">
                                             <span class="material-symbols-outlined text-[12px] text-slate-400 opacity-60">chevron_right</span>
                                             <a href="{{ route('courses', ['level' => $currentLevel->id]) }}" class="transition-colors hover:text-primary truncate max-w-[120px] xl:max-w-[200px]">
                                                 {{ $currentLevel->title }}
                                             </a>
                                         </li>
                                     @endif
                                     @if($currentLesson)
                                         @php
                                             $isDummyData = ($currentLesson->title === 'Bài ' . $currentLesson->lesson_number);
                                             $displayTitleBreadcrumb = preg_replace('/^Bài\s+\d+[:\-]?\s*/i', '', $currentLesson->title);
                                             $displayTitleBreadcrumb = empty(trim($displayTitleBreadcrumb)) ? '' : ': ' . $displayTitleBreadcrumb;
                                         @endphp
                                         <li class="flex items-center gap-1.5 shrink-0">
                                             <span class="material-symbols-outlined text-[12px] text-slate-400 opacity-60">chevron_right</span>
                                             <span class="text-primary font-bold pointer-events-none truncate">Bài {{ $currentLesson->lesson_number }}{{ $isDummyData ? '' : $displayTitleBreadcrumb }}</span>
                                         </li>
                                     @endif
                                 </ol>
                             </nav>
                        </div>

                        <!-- Center/Left: Pill Tab Navigation -->
                        <div class="flex-shrink-0 z-10 transition-all duration-300 w-full xl:w-auto overflow-x-auto no-scrollbar">
                            <div class="flex flex-nowrap sm:inline-flex justify-start sm:justify-center gap-1 min-w-max transition-all duration-300">
                                <a 
                                    href="{{ route('courses.lesson', ['levelId' => $currentLevel->id, 'lessonId' => $currentLesson->id, 'tab' => 'vocab']) }}"
                                    class="shrink-0 px-4 md:px-5 py-1.5 text-xs md:text-sm font-bold rounded-xl transition-all duration-300 focus:outline-none cursor-pointer active:scale-95 text-center whitespace-nowrap {{ $activeTab === 'vocab' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                >
                                    Từ vựng
                                </a>
                                <a 
                                    href="{{ route('courses.lesson', ['levelId' => $currentLevel->id, 'lessonId' => $currentLesson->id, 'tab' => 'dialogue']) }}"
                                    class="shrink-0 px-4 md:px-5 py-1.5 text-xs md:text-sm font-bold rounded-xl transition-all duration-300 focus:outline-none cursor-pointer active:scale-95 text-center whitespace-nowrap {{ $activeTab === 'dialogue' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                >
                                    Bài khóa
                                </a>
                                <a 
                                    href="{{ route('courses.lesson', ['levelId' => $currentLevel->id, 'lessonId' => $currentLesson->id, 'tab' => 'grammar']) }}"
                                    class="shrink-0 px-4 md:px-5 py-1.5 text-xs md:text-sm font-bold rounded-xl transition-all duration-300 focus:outline-none cursor-pointer active:scale-95 text-center whitespace-nowrap {{ $activeTab === 'grammar' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                >
                                    Ngữ pháp
                                </a>
                                <a 
                                    href="{{ route('courses.lesson', ['levelId' => $currentLevel->id, 'lessonId' => $currentLesson->id, 'tab' => 'practice']) }}"
                                    class="shrink-0 px-4 md:px-5 py-1.5 text-xs md:text-sm font-bold rounded-xl transition-all duration-300 focus:outline-none cursor-pointer active:scale-95 text-center whitespace-nowrap {{ $activeTab === 'practice' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                >
                                    Luyện tập
                                </a>
                            </div>
                        </div>
                        <!-- Right Side: Action Button -->
                        <div class="flex-shrink-0 transition-all duration-500 flex justify-end w-full xl:w-auto mt-2 xl:mt-0 xl:ml-auto">
                            <button 
                                class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-400 hover:from-emerald-600 hover:to-emerald-500 text-white font-bold text-xs transition-all duration-300 active:scale-95 shadow-md hover:shadow-lg flex items-center justify-center gap-2 cursor-pointer"
                                @click="alert('Chúc mừng bạn đã hoàn thành bài học này!')"
                            >
                                <span class="material-symbols-outlined text-[18px]">done_all</span>
                                <span class="whitespace-nowrap">Hoàn thành bài học</span>
                            </button>
                        </div>
                    </div>

                    <!-- Tabs Content Panels -->
                    <div class="mt-2 flex-1 flex flex-col">
                        @if($activeTab === 'vocab')
                            @include('course.tabs.vocab')
                        @elseif($activeTab === 'dialogue')
                            @include('course.tabs.dialogue')
                        @elseif($activeTab === 'grammar')
                            @include('course.tabs.grammar')
                        @elseif($activeTab === 'practice')
                            @include('course.tabs.practice')
                        @endif
                    </div>
                </div>
        </div>
        @endif
    </main>
    </div>

    
    <script>
        window.alignPinyin = function(hanzi, pinyin, levelCode) {
            if (!hanzi || !pinyin) return null;
            
            const pArr = pinyin.trim().split(/\s+/).filter(Boolean);
            const hArr = hanzi.replace(/\s+/g, '').split('');
            if (pArr.length > 0 && pArr.length === hArr.length) {
                return hArr.map((h, i) => ({ h, p: pArr[i] }));
            }
            return null;
        };
    </script>
    <script>
        function hskApp() {
            return {
                currentLevel: @json($currentLevel ? $currentLevel->id : null),
                currentLevelObj: @json($currentLevel),
                levelTitle: @json($currentLevel ? $currentLevel->title . ' - ' . $currentLevel->subtitle : ''),
                levelStats: {},
                currentLesson: @json($currentLesson),
                activeTab: @json($activeTab ?? 'vocab'),
                lessons: [],
                hskLevels: @json($levels) || [],
                
                isSectionFullyAnswered(questions) {
                    if (!questions || !questions.length) return false;
                    return questions.every(q => {
                        // Skip if no answers required
                        if (!q.correct_answer && (!q.sub_questions || q.sub_questions.length === 0)) return true;
                        
                        if (q.sub_questions && q.sub_questions.length > 0) {
                            return q.sub_questions.every(sq => {
                                if (!sq.correct) return true;
                                if (sq.ques_type === 'fill_blank' || sq.ques_type === 'reorder') return sq.selected_option !== undefined && sq.selected_option !== null;
                                return sq.selected !== undefined && sq.selected !== null;
                            });
                        }
                        
                        if (q.ques_type === 'reorder' || q.ques_type === 'writing') return q.userAnswer && q.userAnswer.trim() !== '';
                        return q.selected !== undefined && q.selected !== null;
                    });
                },

                checkAllSection(questions) {
                    if (!questions) return;
                    questions.forEach(q => {
                        if (q.correct_answer) q.answered = true;
                        if (q.sub_questions) {
                            q.sub_questions.forEach(sq => {
                                if (sq.correct) sq.answered = true;
                            });
                        }
                    });
                },
                
                init() {
                    if (this.currentLesson) {
                        // Map Laravel relationship keys to what Alpine templates expect
                        if (this.currentLesson.vocabList && !this.currentLesson.vocab_list) this.currentLesson.vocab_list = this.currentLesson.vocabList;
                        if (this.currentLesson.grammarList && !this.currentLesson.grammar_list) this.currentLesson.grammar_list = this.currentLesson.grammarList;
                        if (this.currentLesson.dialogueSections && !this.currentLesson.dialogue_sections) this.currentLesson.dialogue_sections = this.currentLesson.dialogueSections;

                        this.startLesson(this.currentLesson);
                    }
                    this.$watch('activeTab', () => {
                        const url = new URL(window.location);
                        if (this.activeTab) {
                            url.searchParams.set('tab', this.activeTab);
                        } else {
                            url.searchParams.delete('tab');
                        }
                        window.history.replaceState({}, '', url);
                    });
                },                
                // Dialogue states
                currentDialogueSectionIdx: 0,
            modePinyin: true,
            modeHanyu: true,
            modeNghia: false,
            modeDich: false,
            modeNghe: false,
            modeGo: false,
            quizIndex: 0,
            quizInput: '',
            quizStatus: 'typing',
            quizCheck() {
                if(!this.currentLesson || !this.currentLesson.dialogue_sections) return;
                const section = this.currentLesson.dialogue_sections[this.currentDialogueSectionIdx];
                if(!section || !section.dialogues) return;
                const currentLine = section.dialogues[this.quizIndex];
                if(!currentLine) return;
                
                const normalizePinyin = (str) => {
                    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/đ/g, 'd').replace(/Đ/g, 'D');
                };

                const cleanInput = normalizePinyin(this.quizInput.replace(/[.,!?，。！？\s]/g, '').toLowerCase());
                
                const targetPinyin = normalizePinyin(currentLine.pinyin.replace(/[.,!?，。！？\s]/g, '').toLowerCase());
                const targetChar = currentLine.character.replace(/[.,!?，。！？\s]/g, '');
                
                if (cleanInput === targetPinyin || cleanInput === targetChar) {
                    this.quizStatus = 'correct';
                } else {
                    this.quizStatus = 'incorrect';
                }
            },
            quizNext() {
                const section = this.currentLesson.dialogue_sections[this.currentDialogueSectionIdx];
                if(this.quizIndex < section.dialogues.length - 1) {
                    this.quizIndex++;
                    this.quizInput = '';
                    this.quizStatus = 'typing';
                }
            },
            quizRetry() {
                this.quizInput = '';
                this.quizStatus = 'typing';
            },
                
                // Practice states
                practiceTab: 'listening',
                practiceSectionIdx: 0,

                
                startLesson(lesson) {
                    if (lesson && lesson.dialogue_sections) {
                        let globalIdx = 1;
                        lesson.dialogue_sections.forEach(sec => {
                            if (sec.dialogues) {
                                sec.dialogues.forEach(line => {
                                    line.audio_url = line.audio_path || null;
                                    globalIdx++;
                                });
                            }
                        });
                    }
                    
                    if (lesson && lesson.practices) {
                        lesson.practices.forEach(practice => {
                            if (practice.sections) {
                                practice.sections.forEach(sec => {
                                    if (sec.questions) {
                                        sec.questions.forEach(q => {
                                            q.selected = null;
                                            q.answered = false;
                                            
                                            if (q.ques_type === 'fill_blank_dropdrag') {
                                                if (q.context && q.context.includes('@{{blank}}')) {
                                                    q.parsed_context = q.context.split('@{{blank}}');
                                                } else {
                                                    q.parsed_context = [q.context];
                                                }
                                                q.available_options = [];
                                                if (q.options) {
                                                    q.options.forEach((opt, idx) => {
                                                        q.available_options.push({ id: idx, text: opt.text || opt, used: false });
                                                    });
                                                }
                                            }
                                            
                                            if (q.sub_questions && Array.isArray(q.sub_questions)) {
                                                q.sub_questions.forEach(sq => {
                                                    sq.selected = null;
                                                    sq.selected_option = null; // For drag and drop
                                                    sq.answered = false;
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }

                    this.currentLesson = lesson;
                    this.currentDialogueSectionIdx = 0;
                },

                // Drag and drop state and methods
                draggedItemText: null,
                draggedSource: null, // 'pool' or sub_question index (number)
                
                startDrag(event, text, source) {
                    this.draggedItemText = text;
                    this.draggedSource = source;
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', text);
                },
                
                onDrop(event, quiz, targetIndex) {
                    event.preventDefault();
                    if (!this.draggedItemText) return;
                    
                    // If dropping into a sub_question dropzone
                    if (targetIndex !== 'pool') {
                        const sq = quiz.sub_questions[targetIndex];
                        if (sq.answered) return; // Cannot modify if already answered
                        
                        // If there's already an option in this dropzone, put it back to pool
                        if (sq.selected_option) {
                            const opt = quiz.available_options.find(o => o.text === sq.selected_option);
                            if (opt) opt.used = false;
                        }
                        
                        // Mark the new option as used
                        const newOpt = quiz.available_options.find(o => o.text === this.draggedItemText);
                        if (newOpt) newOpt.used = true;
                        
                        // If the item came from another dropzone, clear that dropzone
                        if (this.draggedSource !== 'pool' && this.draggedSource !== targetIndex) {
                            quiz.sub_questions[this.draggedSource].selected_option = null;
                        }
                        
                        sq.selected_option = this.draggedItemText;
                        
                    } else {
                        // Dropping back into the pool
                        if (this.draggedSource !== 'pool') {
                            // It came from a dropzone
                            const sq = quiz.sub_questions[this.draggedSource];
                            if (!sq.answered) {
                                const opt = quiz.available_options.find(o => o.text === sq.selected_option);
                                if (opt) opt.used = false;
                                sq.selected_option = null;
                            }
                        }
                    }
                    
                    this.draggedItemText = null;
                    this.draggedSource = null;
                },

                playAudio(audioUrl) {
                    if (!audioUrl) return;
                    let audio = new Audio(audioUrl);
                    audio.play();
                }
            };
        }
    </script>
@endsection
