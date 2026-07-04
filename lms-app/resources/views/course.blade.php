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
            <div class="absolute right-[-10%] top-[-20%] w-[350px] h-[350px] bg-primary/30 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute left-[30%] bottom-[-50%] w-[250px] h-[250px] bg-indigo-500/20 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10 flex flex-col items-start gap-4">
                <!-- Interactive Status Badge -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 border border-primary/30 text-[9px] font-black text-primary uppercase tracking-widest shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
                    <span>Học tập & Rèn luyện</span>
                </div>

                <!-- Glassmorphism Dynamic Breadcrumb Navigation -->
                <nav aria-label="Breadcrumb" class="hidden sm:block">
                    <ol class="flex items-center gap-2 px-4 py-2 rounded-full bg-slate-950/60 border border-white/20 backdrop-blur-md text-[11px] md:text-xs text-white font-semibold font-sans">
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
                            <a href="#" @click.prevent="currentLevel = null; currentLesson = null" class="transition-colors" :class="currentLevel === null ? 'text-primary font-bold pointer-events-none' : 'text-slate-350 hover:text-primary'">
                                Khóa học
                            </a>
                        </li>
                        <template x-if="currentLevel !== null">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] opacity-40 text-slate-400">chevron_right</span>
                                <a href="#" @click.prevent="currentLesson = null" class="transition-colors" :class="currentLesson === null ? 'text-primary font-bold pointer-events-none' : 'text-slate-350 hover:text-primary'" x-text="currentLevelObj ? currentLevelObj.title : levelTitle"></a>
                            </li>
                        </template>
                        <template x-if="currentLesson !== null">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] opacity-40 text-slate-400">chevron_right</span>
                                <span class="text-primary font-bold pointer-events-none" x-text="'Bài ' + currentLesson.lesson_number + ': ' + currentLesson.title"></span>
                            </li>
                        </template>
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
        class="max-w-6xl mx-auto px-6 pt-6 pb-16 md:pt-8"
    >

        <!-- State 1: Level Grid View (Unified width context) -->
        <div x-show="currentLevel === null" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Modern Header Banner -->
            <div class="text-center mb-12 relative">
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 uppercase tracking-widest mb-4">
                    <span class="h-1.5 w-1.5 rounded-full bg-primary animate-ping"></span>
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
                <template x-for="level in hskLevels" :key="level.id">
                    <div 
                        @click="selectLevel(level)"
                        class="group cursor-pointer bg-white dark:bg-slate-900/60 rounded-2xl border border-slate-105 dark:border-slate-800/80 shadow-sm hover:shadow-xl hover:-translate-y-1.5 active:scale-95 transition-all duration-300 flex flex-col relative overflow-hidden"
                    >
                        <!-- Top Gradient glow bar -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r" :class="
                            level.color === 'emerald' ? 'from-yellow-400 to-amber-500' :
                            level.color === 'cyan' ? 'from-teal-400 to-cyan-550' :
                            level.color === 'blue' ? 'from-red-400 to-rose-500' :
                            level.color === 'purple' ? 'from-purple-400 to-indigo-500' :
                            level.color === 'rose' ? 'from-[#be185d] to-[#db2777]' :
                            'from-blue-400 to-indigo-600'
                        "></div>

                        <!-- Top Half: Full-width HSK Book Cover Header -->
                        <div class="w-full h-40 relative overflow-hidden border-b border-slate-100 dark:border-slate-800/60 transition-colors duration-300" :class="level.cover_bg">
                            <!-- Left Spine Highlight (Full Height) -->
                            <div class="absolute left-0 top-0 bottom-0 w-3 opacity-90 shadow-sm" :class="level.spine_color"></div>
                            
                            <!-- Book Typography content -->
                            <div class="pl-6 pr-4 py-4 flex flex-col justify-between h-full text-left">
                                <div class="flex justify-between items-center">
                                    <span class="text-[8px] font-black tracking-widest text-slate-450 uppercase">STANDARD COURSE</span>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded bg-slate-950/5 dark:bg-white/5 text-slate-400 dark:text-slate-550 uppercase tracking-wider">HSK</span>
                                </div>
                                
                                <div class="my-auto py-1">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-5xl font-black tracking-tighter" :class="level.number_color" x-text="level.id"></span>
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
                                    class="px-2.5 py-1 rounded-xl text-[10px] font-black tracking-wider uppercase border"
                                    :class="
                                        level.color === 'emerald' ? 'bg-yellow-105 border-yellow-250 text-yellow-600 dark:text-yellow-450' :
                                        level.color === 'cyan' ? 'bg-teal-105 border-teal-200 text-teal-650' :
                                        level.color === 'blue' ? 'bg-red-105 border-red-200 text-red-600' :
                                        level.color === 'purple' ? 'bg-purple-105 border-purple-250 text-purple-655' :
                                        level.color === 'rose' ? 'bg-rose-105 border-rose-250 text-rose-600' :
                                        'bg-blue-105 border-blue-250 text-blue-600'
                                    "
                                    x-text="level.title"
                                ></span>
                                <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Miễn phí</span>
                            </div>

                            <h3 class="text-lg font-extrabold text-slate-800 dark:text-white leading-snug mb-1 group-hover:text-primary transition-colors duration-200" x-text="level.subtitle"></h3>
                            <p class="text-xs text-slate-555 dark:text-slate-400 leading-relaxed mb-4 line-clamp-2" x-text="level.description"></p>

                            <!-- Key Metrics Grid -->
                            <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 dark:border-slate-805/60 mt-auto">
                                <div>
                                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-0.5">Bài học</span>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-205" x-text="level.lessons_count"></span>
                                </div>
                                <div>
                                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-0.5">Từ vựng</span>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-205" x-text="level.vocab_count"></span>
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
                    </div>
                </template>
            </div>
        </div>

        <!-- State 2: Detailed Lesson List View -->
        <div x-show="currentLevel !== null && currentLesson === null" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <!-- Back Arrow Navigation -->
            <button 
                @click="currentLevel = null; if(window.location.search !== '') window.history.pushState({}, '', window.location.pathname);"
                class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-primary transition-all duration-200 cursor-pointer active:scale-95 mb-6 focus:outline-none"
            >
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Quay lại chọn cấp độ</span>
            </button>

            <!-- Curriclum Banner Hero -->
            <div class="p-6 md:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 text-left">
                <div class="flex items-center gap-4">
                    <div 
                        class="h-14 w-14 rounded-xl flex items-center justify-center font-black text-lg text-white shadow-md shadow-slate-950/10 animate-pulse"
                        :class="levelStats.accentClass"
                    >
                        <span x-text="levelTitle ? levelTitle.split(' ')[0] : 'H'"></span>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-slate-855 dark:text-white" x-text="levelTitle"></h2>
                        <p class="text-xs font-bold text-slate-405 dark:text-slate-505 mt-0.5">Lộ trình bài học chính khóa bám sát cấu trúc khung</p>
                    </div>
                </div>

                <!-- Stats Badges -->
                <div class="flex flex-wrap gap-2 md:self-auto self-stretch">
                    <span class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-855 text-xs font-bold text-slate-600 dark:text-slate-300" x-text="levelStats.lessons + ' Bài học'"></span>
                    <span class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-855 text-xs font-bold text-slate-600 dark:text-slate-300" x-text="levelStats.vocab + ' Từ vựng'"></span>
                </div>
            </div>

            <!-- Curriculum Roadmap -->
            <div class="space-y-4 text-left">
                <template x-for="(lesson, index) in lessons" :key="lesson.id">
                    <div 
                        class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-805/85 shadow-sm hover:shadow-md hover:border-primary/20 dark:hover:border-primary/20 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div class="flex items-center gap-4">
                            <!-- Premium Big Lesson Number Indicator -->
                            <span 
                                class="text-3xl font-black text-slate-200 dark:text-slate-800 select-none w-12 text-center" 
                                x-text="(lesson.lesson_number < 10 ? '0' : '') + lesson.lesson_number"
                            ></span>
                            
                            <div>
                                <span class="text-[9px] font-black text-primary dark:text-primary-light uppercase tracking-wider block mb-0.5">Mã bài: <span x-text="lesson.code"></span></span>
                                <div class="flex items-baseline gap-2">
                                    <h3 class="text-base font-extrabold text-slate-800 dark:text-white" x-text="lesson.title"></h3>
                                    <span class="text-xs font-bold text-slate-405 italic" x-text="'/ ' + lesson.pinyin"></span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5 font-semibold" x-text="lesson.translation"></p>
                            </div>
                        </div>

                        <!-- Start Play Button -->
                        <button 
                            @click="startLesson(lesson)"
                            class="px-5 py-2.5 text-xs font-bold text-white rounded-xl shadow-sm transition-all duration-200 focus:outline-none flex items-center gap-1.5 self-start sm:self-auto cursor-pointer active:scale-95"
                            :class="levelStats.color === 'emerald' ? 'bg-yellow-600 hover:bg-yellow-700' : 
                                    levelStats.color === 'cyan' ? 'bg-cyan-600 hover:bg-cyan-700' :
                                    levelStats.color === 'blue' ? 'bg-red-600 hover:bg-red-700' :
                                    levelStats.color === 'purple' ? 'bg-primary hover:hover:bg-primary/90' :
                                    levelStats.color === 'rose' ? 'bg-rose-600 hover:bg-rose-700' :
                                    'bg-amber-600 hover:bg-amber-700'"
                        >
                            <span class="material-symbols-outlined text-[16px]">play_circle</span>
                            <span>Bắt đầu học</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- State 3: Detailed Lesson Detail View -->
        <div x-show="currentLevel !== null && currentLesson !== null" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <template x-if="currentLesson !== null">
                <div class="space-y-6">

                    <!-- Toolbar: Tab Navigation & Action Button -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <!-- Pill Tab Navigation -->
                        <div class="bg-white/80 dark:bg-slate-900/80 p-1 rounded-full border border-slate-200/60 dark:border-slate-700/60 shadow-sm flex inline-flex gap-1 w-full sm:w-auto backdrop-blur-sm">
                            <button 
                                class="flex-1 sm:flex-none px-5 py-1.5 text-sm font-bold rounded-full transition-all duration-300 focus:outline-none cursor-pointer active:scale-95"
                                :class="activeTab === 'vocab' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
                                @click="activeTab = 'vocab'"
                            >
                                Từ vựng
                            </button>
                            <button 
                                class="flex-1 sm:flex-none px-5 py-1.5 text-sm font-bold rounded-full transition-all duration-300 focus:outline-none cursor-pointer active:scale-95"
                                :class="activeTab === 'dialogue' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
                                @click="activeTab = 'dialogue'; currentDialogueSectionIdx = 0;"
                            >
                                Bài khóa
                            </button>
                            <button 
                                class="flex-1 sm:flex-none px-5 py-1.5 text-sm font-bold rounded-full transition-all duration-300 focus:outline-none cursor-pointer active:scale-95"
                                :class="activeTab === 'grammar' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
                                @click="activeTab = 'grammar'"
                            >
                                Ngữ pháp
                            </button>
                            <button 
                                class="flex-1 sm:flex-none px-5 py-1.5 text-sm font-bold rounded-full transition-all duration-300 focus:outline-none cursor-pointer active:scale-95"
                                :class="activeTab === 'practice' ? 'bg-primary text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
                                @click="activeTab = 'practice'; currentPracticeSectionIdx = 0; currentPracticeQuestionIdx = 0;"
                            >
                                Luyện tập
                            </button>
                        </div>
                        
                        <!-- Complete action button -->
                        <button 
                            class="w-full sm:w-auto px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs transition-all duration-300 active:scale-95 shadow-sm hover:shadow-md flex items-center justify-center gap-2 cursor-pointer"
                            @click="alert('Chúc mừng bạn đã hoàn thành bài học này!')"
                        >
                            <span class="material-symbols-outlined text-[16px]">done_all</span>
                            <span>Hoàn thành bài học</span>
                        </button>
                    </div>

                    <!-- Tabs Content Panels -->
                    <div class="mt-6">
                        <!-- Tab 1: Vocabulary Content Panel -->
                        <div x-show="activeTab === 'vocab'" x-transition:enter="transition ease-out duration-200">
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm flex flex-col">
                                <!-- Section Title - Unified -->
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 text-left border-b border-slate-100 dark:border-slate-800/60 pb-5">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2.5">
                                        <div class="h-8 w-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[18px]">list_alt</span>
                                        </div>
                                        <span>Từ vựng trọng tâm</span>
                                    </h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 sm:ml-10">Ghi nhớ và luyện phát âm các từ vựng mới của bài học.</p>
                                </div>

                                <!-- Flashcard link button -->
                                <a 
                                    href="{{ route('flashcards') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-white font-bold text-xs rounded-xl shadow-sm shadow-primary/20 transition-all duration-200 cursor-pointer active:scale-95 shrink-0"
                                >
                                    <span class="material-symbols-outlined text-[16px]">style</span>
                                    <span>Học Flashcard</span>
                                </a>
                            </div>

                            <!-- Vocab Table List -->
                            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800/80 overflow-hidden">
                                <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="sticky top-0 bg-slate-50 dark:bg-slate-800/90 backdrop-blur-sm z-10">
                                            <tr class="border-b border-slate-200 dark:border-slate-700/80">
                                                <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-12 text-center">#</th>
                                                <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-32">Từ vựng</th>
                                                <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-32">Pinyin</th>
                                                <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24">Từ loại</th>
                                                <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ý nghĩa</th>
                                                <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-16 text-center">Nghe</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                            <template x-for="(vocab, idx) in currentLesson.vocab_list" :key="idx">
                                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors group">
                                                    <td class="py-3.5 px-4 text-xs font-black text-slate-300 dark:text-slate-600 text-center" x-text="idx + 1"></td>
                                                    <td class="py-3.5 px-4">
                                                        <span class="text-xl font-black text-slate-800 dark:text-white" x-text="vocab.word"></span>
                                                    </td>
                                                    <td class="py-3.5 px-4">
                                                        <span class="text-[13px] text-slate-500 dark:text-slate-400 font-bold italic" x-text="'[' + vocab.pinyin + ']'"></span>
                                                    </td>
                                                    <td class="py-3.5 px-4">
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 whitespace-nowrap" x-text="vocab.type"></span>
                                                    </td>
                                                    <td class="py-3.5 px-4">
                                                        <span class="text-sm text-slate-600 dark:text-slate-300 font-medium line-clamp-2" x-text="vocab.meaning" :title="vocab.meaning"></span>
                                                    </td>
                                                    <td class="py-3.5 px-4 text-center">
                                                        <button 
                                                            @click="playAudio(vocab.audio_url || 'https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(vocab.word) + '&type=1')"
                                                            class="h-8 w-8 rounded-full bg-primary/10 border border-primary/15 hover:bg-primary hover:text-white transition-all duration-200 inline-flex items-center justify-center text-primary focus:outline-none opacity-60 group-hover:opacity-100 mx-auto"
                                                            title="Nghe phát âm"
                                                        >
                                                            <span class="material-symbols-outlined text-[16px]">volume_up</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- Tab 2: Dialogue Content Panel -->
                        <div x-show="activeTab === 'dialogue'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-left">
                                <!-- Dialogue Chat Box (Left column) -->
                                <div class="lg:col-span-3">
                                    <template x-for="(section, sIdx) in currentLesson.dialogue_sections" :key="section.id">
                                        <div x-show="currentDialogueSectionIdx === sIdx" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm space-y-4 flex flex-col">
                                            <!-- Top Dialogue section header with audio player -->
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800/80">
                                                <div class="flex items-center gap-3 shrink-0">
                                                    <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                        <span class="material-symbols-outlined text-xl">forum</span>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-base font-black text-slate-800 dark:text-white" x-text="section.title"></h4>
                                                        <p class="text-xs text-slate-400 font-bold mt-0.5">Luyện nghe & khẩu ngữ</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Dynamic Audio Player -->
                                                <div class="w-full md:w-1/2 lg:w-[350px]">
                                                    <audio :src="section.audio_url" controls class="w-full h-10 outline-none"></audio>
                                                </div>
                                            </div>

                                            <!-- Control Options bar -->
                                            <div class="flex flex-wrap gap-2 mb-4">
                                                <button 
                                                    @click="modePinyin = !modePinyin; modeNghe = false; modeGo = false; modeDich = false;"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modePinyin ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="font-black text-[16px]">A</span> Pinyin
                                                </button>
                                                
                                                <button 
                                                    @click="modeHanyu = !modeHanyu; modeNghe = false; modeGo = false; modeDich = false;"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modeHanyu ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="font-black text-[16px]">字</span> Chữ Hán
                                                </button>
                                                
                                                <button 
                                                    @click="modeGo = !modeGo; if(modeGo) { modeNghe = false; modeDich = false; quizIndex = 0; quizInput = ''; quizStatus = 'typing'; }"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border ml-0 sm:ml-4"
                                                    :class="modeGo ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">keyboard</span> Luyện gõ
                                                </button>
                                                
                                                <button 
                                                    @click="modeNghe = !modeNghe; if(modeNghe) { modeGo = false; modeDich = false; quizIndex = 0; quizInput = ''; quizStatus = 'typing'; }"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modeNghe ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">volume_up</span> Luyện nghe
                                                </button>
                                                
                                                <button 
                                                    @click="modeDich = !modeDich; if(modeDich) { modeNghe = false; modeGo = false; quizIndex = 0; quizInput = ''; quizStatus = 'typing'; }"
                                                    class="px-4 py-1.5 rounded-full text-[14px] font-bold transition-all duration-200 flex items-center gap-1.5 outline-none border"
                                                    :class="modeDich ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20' : 'bg-white text-primary border-primary/30 hover:border-primary hover:bg-primary/5 dark:bg-slate-800 dark:border-primary/40 dark:text-primary'"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">translate</span> Luyện dịch
                                                </button>
                                            </div>

                                            <!-- Quiz Mode (Luyện Nghe / Luyện Gõ / Luyện Dịch) -->
                                            <div x-show="modeNghe || modeGo || modeDich" class="mt-6 mb-4 w-full">
                                                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 relative">
                                                    <!-- Nghe Mode Header -->
                                                    <div x-show="modeNghe" class="text-center space-y-4 mb-6">
                                                        <h4 class="text-[13px] font-bold text-primary uppercase tracking-widest">Nghe và gõ lại chữ Hán</h4>
                                                        
                                                        <button 
                                                            @click="playAudio(section.dialogues[quizIndex].audio_url || 'https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(section.dialogues[quizIndex].character) + '&type=1')"
                                                            class="w-16 h-16 rounded-full bg-primary text-white shadow-lg shadow-primary/30 flex items-center justify-center hover:hover:bg-primary/90 transition-all mx-auto focus:outline-none active:scale-95"
                                                        >
                                                            <span class="material-symbols-outlined text-3xl">volume_up</span>
                                                        </button>
                                                        
                                                        <p class="text-sm text-slate-400 italic">
                                                            Gợi ý: <span x-text="section.dialogues[quizIndex].role"></span> đang nói...
                                                        </p>
                                                    </div>

                                                    <!-- Gõ Mode Header -->
                                                    <div x-show="modeGo" class="text-left mb-6">
                                                        <p class="text-2xl font-black text-slate-800 tracking-wide font-chinese flex items-center gap-2">
                                                            <span x-text="section.dialogues[quizIndex].role + ':'" class="text-slate-800 font-bold"></span> 
                                                            <span x-text="section.dialogues[quizIndex].character"></span>
                                                        </p>
                                                        <p x-show="modePinyin" class="text-sm text-slate-400 mt-2 font-medium" x-text="section.dialogues[quizIndex].pinyin"></p>
                                                        <p x-show="modeNghia" class="text-sm text-slate-500 mt-1 font-medium" x-text="section.dialogues[quizIndex].translation"></p>
                                                    </div>
                                                    
                                                    <!-- Dịch Mode Header -->
                                                    <div x-show="modeDich" class="text-left mb-4">
                                                        <p class="text-[15px] text-slate-700">
                                                            <span class="font-bold text-slate-800" x-text="section.dialogues[quizIndex].role + ':'"></span>
                                                            <span class="italic" x-text="section.dialogues[quizIndex].translation"></span>
                                                        </p>
                                                    </div>

                                                    <!-- Input Area -->
                                                    <div class="mb-6 relative">
                                                        <input 
                                                            type="text" 
                                                            x-model="quizInput"
                                                            :placeholder="modeNghe ? 'Nghe được gì, gõ nấy...' : (modeGo ? 'Gõ lại câu chữ Hán đầy đủ...' : 'Dịch sang tiếng Trung...')"
                                                            class="w-full px-4 py-4 rounded-lg border-2 text-lg font-chinese focus:outline-none transition-colors pr-12"
                                                            :class="quizStatus === 'correct' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-700' : 
                                                                   (quizStatus === 'incorrect' ? 'border-red-500 bg-red-50/50 text-red-700' : 'border-slate-300 focus:border-primary')"
                                                            @keyup.enter="quizCheck()"
                                                        >
                                                        
                                                        <!-- Result Icons -->
                                                        <div x-show="quizStatus === 'correct'" class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500">
                                                            <span class="material-symbols-outlined text-2xl">check_circle</span>
                                                        </div>
                                                        <div x-show="quizStatus === 'incorrect'" class="absolute right-4 top-1/2 -translate-y-1/2 text-red-500">
                                                            <span class="material-symbols-outlined text-2xl">cancel</span>
                                                        </div>
                                                    </div>

                                                    <!-- Quiz Controls -->
                                                    <div class="flex flex-wrap items-center justify-between gap-4">
                                                        <div class="flex gap-2">
                                                            <button 
                                                                @click="quizCheck()"
                                                                class="px-5 py-2.5 rounded-lg font-bold text-sm transition-all focus:outline-none bg-primary text-white hover:bg-primary/90 shadow-sm active:scale-95"
                                                            >
                                                                Kiểm tra
                                                            </button>
                                                            <button 
                                                                x-show="quizStatus === 'correct'"
                                                                @click="quizNext()"
                                                                :disabled="quizIndex >= section.dialogues.length - 1"
                                                                class="px-5 py-2.5 rounded-lg border border-slate-300 font-bold text-sm transition-all focus:outline-none text-slate-700 hover:bg-slate-50 active:scale-95"
                                                                :class="quizIndex >= section.dialogues.length - 1 ? 'opacity-50 cursor-not-allowed' : ''"
                                                            >
                                                                Câu tiếp
                                                            </button>
                                                            <button 
                                                                @click="quizRetry()"
                                                                class="px-5 py-2.5 rounded-lg border border-slate-300 font-bold text-sm transition-all focus:outline-none text-slate-700 hover:bg-slate-50 active:scale-95"
                                                            >
                                                                Làm lại
                                                            </button>
                                                        </div>
                                                        
                                                        <button 
                                                            @click="modeNghe = false; modeGo = false; modeDich = false;"
                                                            class="px-5 py-2.5 rounded-lg border border-slate-300 font-bold text-sm transition-all focus:outline-none text-slate-700 hover:bg-slate-50 active:scale-95"
                                                        >
                                                            Thoát
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Footer counter -->
                                                    <div class="mt-6 pt-4 border-t border-slate-100 text-left text-sm text-slate-400 font-medium">
                                                        Câu <span x-text="quizIndex + 1"></span>/<span x-text="section.dialogues.length"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Dialogue WeChat Style Bubbles -->
                                            <div class="space-y-4 pt-3 flex flex-col" x-show="!modeNghe && !modeGo && !modeDich">
                                                <template x-for="(line, lineIdx) in section.dialogues" :key="lineIdx">
                                                    <div 
                                                        class="flex gap-2 max-w-md items-end"
                                                        :class="line.role === 'A' ? 'self-start flex-row text-left' : 'self-end flex-row-reverse text-right'"
                                                    >
                                                        <!-- Avatar Icon -->
                                                        <div 
                                                            class="h-7 w-7 rounded-full flex items-center justify-center text-[10px] font-black text-white shrink-0 shadow-sm"
                                                            :class="line.role === 'A' ? 'bg-teal-500 shadow-teal-500/10' : 'bg-indigo-500 shadow-indigo-500/10'"
                                                            x-text="line.role"
                                                        ></div>

                                                        <!-- Text bubble container -->
                                                        <div class="space-y-1 relative">
                                                            <div 
                                                                class="p-3.5 rounded-2xl shadow-sm border text-left"
                                                                :class="line.role === 'A' 
                                                                    ? 'bg-teal-50/40 dark:bg-teal-950/10 border-teal-100 dark:border-teal-900/30 text-slate-800 dark:text-slate-200 rounded-bl-none' 
                                                                    : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-br-none'"
                                                            >
                                                                <!-- Play Individual line sound -->
                                                                <div class="flex justify-between items-center mb-1 gap-4">
                                                                    <span class="text-[9px] font-black uppercase text-slate-400" x-text="line.role === 'A' ? 'Speaker A' : 'Speaker B'"></span>
                                                                    <button 
                                                                        @click="playAudio(line.audio_url || 'https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(line.character) + '&type=1')"
                                                            :data-src="'https://dict.youdao.com/dictvoice?audio=' + encodeURIComponent(line.character) + '&type=1'"
                                                                        class="h-5 w-5 rounded-full bg-primary/10 hover:bg-primary hover:text-white transition-all duration-205 flex items-center justify-center text-primary"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[11px]">volume_up</span>
                                                                    </button>
                                                                </div>

                                                                <!-- Character display & pinyin toggles -->
                                                                <p x-show="modePinyin" class="text-xs text-slate-400 dark:text-slate-550 italic mb-0.5 block" x-text="line.pinyin"></p>
                                                                <p x-show="modeHanyu" class="text-lg font-bold tracking-wide leading-relaxed block" x-text="line.character"></p>
                                                                
                                                                <!-- Empty state placeholder (khi tắt cả pinyin và chữ Hán) -->
                                                                <p x-show="!modePinyin && !modeHanyu" class="text-xs text-slate-400 dark:text-slate-600 italic py-1 opacity-60">Đang ẩn văn bản...</p>
                                                                
                                                                <!-- Luyện dịch toggle text -->
                                                                <p x-show="modeNghia" class="text-xs text-slate-505 dark:text-slate-455 mt-1.5 pt-1.5 border-t border-slate-100 dark:border-slate-800/80 font-semibold" x-text="line.translation"></p>
                                                            </div>

                                                            <!-- Typing field for typing exercise -->
                                                            <div x-show="modeGõ" class="mt-1.5 w-60">
                                                                <input 
                                                                    type="text" 
                                                                    placeholder="Luyện gõ câu hội thoại này..." 
                                                                    class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none bg-slate-50 dark:bg-slate-900"
                                                                    x-model="typeInputs[sIdx + '_' + lineIdx]"
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Playlist Menu navigation (Right column) -->
                                <div class="lg:col-span-1 space-y-4">
                                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-2xl p-4 text-left">
                                        <h5 class="text-xs font-black text-slate-855 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3 flex items-center gap-1.5 uppercase tracking-wider">
                                            <span class="material-symbols-outlined text-[16px]">playlist_play</span>
                                            <span>Chọn bài khóa</span>
                                        </h5>

                                        <div class="flex flex-col gap-1.5">
                                            <template x-for="(section, sIdx) in currentLesson.dialogue_sections" :key="section.id">
                                                <button 
                                                    @click="currentDialogueSectionIdx = sIdx"
                                                    class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-bold transition-all text-left flex items-center gap-2"
                                                    :class="currentDialogueSectionIdx === sIdx 
                                                        ? 'bg-primary/10 text-primary border-primary/20 font-black shadow-sm' 
                                                        : 'bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-805 text-slate-605 dark:text-slate-405 hover:bg-slate-50'"
                                                >
                                                    <span 
                                                        class="h-5 w-5 rounded-full text-[9px] flex items-center justify-center font-black"
                                                        :class="currentDialogueSectionIdx === sIdx ? 'bg-primary text-white' : 'bg-slate-100 text-slate-500 dark:bg-slate-800'"
                                                        x-text="sIdx + 1"
                                                    ></span>
                                                    <span x-text="section.title"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Grammar Content Panel -->
                        <div x-show="activeTab === 'grammar'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                            <template x-if="currentLesson.grammar_list && currentLesson.grammar_list.length > 0">
                                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm flex flex-col">
                                    <!-- Section Title - Unified -->
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 text-left border-b border-slate-100 dark:border-slate-800/60 pb-5">
                                        <div>
                                            <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2.5">
                                                <div class="h-8 w-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                                </div>
                                                <span>Quy tắc ngữ pháp</span>
                                            </h3>
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 sm:ml-10">Nắm vững cấu trúc, ý nghĩa và cách sử dụng qua các ví dụ.</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-6 text-left">
                                        <template x-for="(grammar, gIdx) in currentLesson.grammar_list" :key="grammar.id">
                                            <div class="bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800/80 p-5 space-y-4">
                                                <!-- Title -->
                                                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                                                    <h4 class="text-sm font-extrabold text-primary dark:text-primary-light" x-text="grammar.title"></h4>
                                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500 dark:text-slate-400" x-text="grammar.type"></span>
                                                </div>
                                                
                                                <!-- Formula banner -->
                                                <div x-show="grammar.formula" class="p-4 rounded-xl bg-gradient-to-r from-primary/10 via-primary/5 to-transparent border border-primary/20 text-slate-800 dark:text-white text-base font-black tracking-wide flex items-center text-left">
                                                    <div>
                                                        <span class="text-[8px] font-bold text-primary uppercase tracking-widest block mb-0.5">Cấu trúc công thức</span>
                                                        <span x-text="grammar.formula"></span>
                                                    </div>
                                                </div>

                                                <!-- Detail explanation -->
                                                <div class="space-y-1 bg-slate-50 dark:bg-slate-800/30 p-3.5 rounded-xl border border-slate-100/50 dark:border-slate-800/60">
                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Giải thích ý nghĩa</span>
                                                    <p class="text-xs text-slate-605 dark:text-slate-305 leading-relaxed font-semibold" x-text="grammar.explanation"></p>
                                                </div>

                                                <!-- Examples box mapping -->
                                                <div class="space-y-3 pt-1">
                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Các câu ví dụ mẫu</span>
                                                    <div class="flex flex-col gap-4">
                                                        <template x-for="(ex, eIdx) in grammar.examples" :key="eIdx">
                                                            <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-85 bg-slate-50/50 dark:bg-slate-800/40 relative group/ex">
                                                                <p class="text-xs text-primary font-bold italic mb-0.5" x-text="ex.pinyin"></p>
                                                                <p class="text-xl font-black text-slate-800 dark:text-white leading-normal" x-text="ex.character"></p>
                                                                <p class="text-xs text-slate-455 mt-2 pt-2 border-t border-slate-100 dark:border-slate-800/40 font-semibold" x-text="'Nghĩa Việt: ' + ex.translation"></p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            </template>

                            <!-- Empty State Grammar -->
                            <template x-if="!currentLesson.grammar_list || currentLesson.grammar_list.length === 0">
                                <div class="flex flex-col items-center justify-center py-20 px-4 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl text-center mt-2 max-w-2xl mx-auto">
                                    <div class="w-20 h-20 bg-white dark:bg-slate-800 shadow-sm rounded-full flex items-center justify-center mb-5 border border-slate-100 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-500">menu_book</span>
                                    </div>
                                    <h4 class="text-base font-black text-slate-800 dark:text-white mb-2">Không có ngữ pháp</h4>
                                    <p class="text-xs text-slate-500 max-w-md font-medium leading-relaxed">Bài học này không có trọng điểm ngữ pháp mới.</p>
                                </div>
                            </template>
                        </div>

                        <!-- Tab 4: Practice Content Panel -->
                        <div x-show="activeTab === 'practice'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                            <!-- Empty State Practice -->
                            <template x-if="(!currentLesson?.practices || currentLesson?.practices.length === 0)">
                                <div class="flex flex-col items-center justify-center py-20 px-4 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl text-center mt-2 max-w-2xl mx-auto">
                                    <div class="w-20 h-20 bg-white dark:bg-slate-800 shadow-sm rounded-full flex items-center justify-center mb-5 border border-slate-100 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-500">draw</span>
                                    </div>
                                    <h4 class="text-base font-black text-slate-800 dark:text-white mb-2">Chưa có bài tập thực hành</h4>
                                    <p class="text-xs text-slate-500 max-w-md font-medium leading-relaxed">Nội dung thực hành cho bài học này đang được biên soạn và sẽ sớm ra mắt. Vui lòng quay lại sau.</p>
                                </div>
                            </template>

                            <template x-if="currentLesson?.practices && currentLesson?.practices.length > 0">
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-left">
                                    
                                    <!-- Practice Main Content (Left column) -->
                                    <div class="lg:col-span-3">
                                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm flex flex-col">
                                        <!-- Header & Tabs -->
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 dark:border-slate-800/60 pb-5 mb-5 text-left">
                                            <div class="flex gap-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                                                <button 
                                                    class="px-5 py-2 rounded-lg font-bold text-sm transition-all duration-200 flex items-center gap-2"
                                                    :class="practiceTab === 'listening' ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'listening'; practiceSectionIdx = 0"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">headphones</span> Phần Nghe
                                                </button>
                                                <button 
                                                    class="px-5 py-2 rounded-lg font-bold text-sm transition-all duration-200 flex items-center gap-2"
                                                    :class="practiceTab === 'reading' ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'reading'; practiceSectionIdx = 0"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">menu_book</span> Phần Đọc
                                                </button>
                                            </div>


                                        </div>

                                        <!-- Listening Tab Content -->
                                        <div x-show="practiceTab === 'listening'" x-transition class="space-y-6">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'listening')">
                                                <div>
                                                    <!-- Global Audio for Listening -->
                                                    <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 mb-6 flex flex-col gap-3">
                                                        <h5 class="text-sm font-extrabold text-primary dark:text-primary-light">Tệp Âm Thanh Bài Nghe (Toàn bộ)</h5>
                                                        <template x-if="currentLesson?.practices?.find(p => p.type === 'listening')?.audio_path">
                                                            <audio controls class="w-full h-10 rounded-full" :src="'/storage/hsk_media/' + currentLesson?.practices?.find(p => p.type === 'listening')?.audio_path"></audio>
                                                        </template>
                                                    </div>

                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'listening')?.sections || [])" :key="sIdx">
                                                        <div class="space-y-4" x-show="practiceSectionIdx === sIdx">
                                                            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-805/60 border-l-4 border-l-primary">
                                                                <h4 class="text-sm font-black text-slate-800 dark:text-white font-chinese tracking-wider" x-text="sect.section_han"></h4>
                                                                <p class="text-xs text-primary italic mt-1" x-text="sect.section_vi"></p>
                                                            </div>

                                                    <div class="flex flex-col gap-4">
                                                        <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                            <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-105 dark:border-slate-800 p-5 rounded-2xl space-y-4 text-left">
                                                                <h5 class="text-sm font-extrabold text-slate-800 dark:text-white flex items-start gap-2">
                                                                    <span class="shrink-0 text-slate-400" x-text="'Câu ' + (qIdx + 1) + '.'"></span>
                                                                    <template x-if="quiz.question">
                                                                        <span x-text="quiz.question" class="font-chinese tracking-wide text-base"></span>
                                                                    </template>
                                                                </h5>
                                                                
                                                                <template x-if="quiz.image_path">
                                                                    <div class="my-3 text-center border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50 p-2 flex justify-center">
                                                                        <img :src="'/storage/hsk_media/' + quiz.image_path" class="max-h-64 object-contain rounded-lg">
                                                                    </div>
                                                                </template>

                                                                <div class="flex flex-col sm:flex-row gap-3">
                                                                    <template x-for="(opt, oIdx) in quiz.options" :key="oIdx">
                                                                        <button 
                                                                            class="flex-1 text-left p-3.5 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group"
                                                                            :class="quiz.answered 
                                                                                ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                   (quiz.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-white dark:bg-slate-900 text-slate-400 border-slate-200 opacity-60'))
                                                                                : (quiz.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10' : 'bg-white dark:bg-slate-805 text-slate-700 dark:text-slate-305 border-slate-200 hover:bg-slate-50 hover:border-slate-300')"
                                                                            @click="if(!quiz.answered) quiz.selected = oIdx"
                                                                            :disabled="quiz.answered"
                                                                        >
                                                                            <span class="group-hover:translate-x-1 transition-transform font-chinese text-base" x-text="opt.text || opt"></span>
                                                                            <template x-if="quiz.answered">
                                                                                <div class="flex items-center gap-2">
                                                                                    <template x-if="quiz.selected === oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                        <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Chính xác</span>
                                                                                    </template>
                                                                                    <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                        <span class="text-[10px] font-black uppercase text-red-600 bg-red-500/20 px-2 py-0.5 rounded-md">Bạn chọn</span>
                                                                                    </template>
                                                                                    <template x-if="quiz.selected !== oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                        <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Đáp án</span>
                                                                                    </template>
                                                                                    <template x-if="(opt.text || opt) == quiz.correct_answer">
                                                                                        <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                    </template>
                                                                                    <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                        <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                    </template>
                                                                                </div>
                                                                            </template>
                                                                        </button>
                                                                    </template>
                                                                </div>

                                                                <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-slate-800" x-show="!quiz.answered">
                                                                    <button 
                                                                        class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5"
                                                                        :disabled="quiz.selected === null"
                                                                        :class="quiz.selected !== null ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                        @click="quiz.answered = true"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[16px]">task_alt</span> Xác nhận đáp án
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Reading Tab Content -->
                                        <div x-show="practiceTab === 'reading'" x-transition class="space-y-6 pt-2">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'reading')">
                                                <div>
                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'reading')?.sections || [])" :key="sIdx">
                                                        <div class="space-y-4" x-show="practiceSectionIdx === sIdx">
                                                            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-805/60 border-l-4 border-l-primary">
                                                                <h4 class="text-sm font-black text-slate-800 dark:text-white font-chinese tracking-wider" x-text="sect.section_han"></h4>
                                                                <p class="text-xs text-primary dark:text-primary-light italic mt-1" x-text="sect.section_vi"></p>
                                                            </div>

                                                            <div class="flex flex-col gap-4">
                                                                <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                                    <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-105 dark:border-slate-800 p-5 rounded-2xl space-y-4 text-left">
                                                                        <h5 class="text-sm font-extrabold text-slate-800 dark:text-white flex items-start gap-2">
                                                                            <span class="shrink-0 text-slate-400" x-text="'Câu ' + (qIdx + 1) + '.'"></span>
                                                                            <template x-if="quiz.question">
                                                                                <span x-text="quiz.question" class="font-chinese tracking-wide text-base"></span>
                                                                            </template>
                                                                        </h5>
                                                                        
                                                                        <template x-if="quiz.image_path">
                                                                            <div class="my-3 text-center border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50 p-2 flex justify-center">
                                                                                <img :src="'/storage/hsk_media/' + quiz.image_path" class="max-h-64 object-contain rounded-lg">
                                                                            </div>
                                                                        </template>

                                                                        <div class="flex flex-col sm:flex-row gap-3">
                                                                    <template x-for="(opt, oIdx) in quiz.options" :key="oIdx">
                                                                        <button 
                                                                            class="flex-1 text-left p-3.5 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group"
                                                                            :class="quiz.answered 
                                                                                ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                   (quiz.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-white dark:bg-slate-900 text-slate-400 border-slate-200 opacity-60'))
                                                                                : (quiz.selected === oIdx ? 'bg-primary/10 border-primary text-primary dark:text-primary-light font-extrabold shadow-sm shadow-primary/10' : 'bg-white dark:bg-slate-805 text-slate-700 dark:text-slate-305 border-slate-200 hover:bg-slate-50 hover:border-slate-300')"
                                                                            @click="if(!quiz.answered) quiz.selected = oIdx"
                                                                            :disabled="quiz.answered"
                                                                        >
                                                                            <span class="group-hover:translate-x-1 transition-transform font-chinese text-base" x-text="opt.text || opt"></span>
                                                                            <template x-if="quiz.answered">
                                                                                <div class="flex items-center gap-2">
                                                                                    <template x-if="quiz.selected === oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                        <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Chính xác</span>
                                                                                    </template>
                                                                                    <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                        <span class="text-[10px] font-black uppercase text-red-600 bg-red-500/20 px-2 py-0.5 rounded-md">Bạn chọn</span>
                                                                                    </template>
                                                                                    <template x-if="quiz.selected !== oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                        <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Đáp án</span>
                                                                                    </template>
                                                                                    <template x-if="(opt.text || opt) == quiz.correct_answer">
                                                                                        <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                    </template>
                                                                                    <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                        <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                    </template>
                                                                                </div>
                                                                            </template>
                                                                        </button>
                                                                    </template>
                                                                </div>

                                                                <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-slate-800" x-show="!quiz.answered">
                                                                    <button 
                                                                        class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5"
                                                                        :disabled="quiz.selected === null"
                                                                        :class="quiz.selected !== null ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                        @click="quiz.answered = true"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[16px]">task_alt</span> Xác nhận đáp án
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                                </div>
                                            </template>
                                        </div>
                                        </div>

                                    </div>

                                    <!-- Navigation Sidebar (Right column) -->
                                    <div class="lg:col-span-1 space-y-4">
                                        <!-- Sidebar List -->
                                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-2xl p-4 text-left">
                                            <h5 class="text-sm font-black text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[18px] text-primary">route</span>
                                                <span>Điều hướng bài tập</span>
                                            </h5>

                                            <div class="flex flex-col space-y-1 relative before:content-[''] before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-px before:bg-slate-100 dark:before:bg-slate-800">
                                                <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === practiceTab)?.sections || [])" :key="sIdx">
                                                    <button 
                                                        @click="practiceSectionIdx = sIdx"
                                                        class="group relative flex items-center gap-3 py-2 text-left z-10"
                                                    >
                                                        <div 
                                                            class="h-10 w-10 shrink-0 rounded-full flex items-center justify-center text-xs font-bold transition-all border-4 border-white dark:border-slate-900"
                                                            :class="practiceSectionIdx === sIdx ? 'bg-primary text-white shadow-md' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 group-hover:bg-slate-200 dark:group-hover:bg-slate-700'"
                                                        >
                                                            <span x-text="sIdx + 1"></span>
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <span 
                                                                class="text-sm font-bold transition-colors line-clamp-2"
                                                                :class="practiceSectionIdx === sIdx ? 'text-primary' : 'text-slate-500 dark:text-slate-400 group-hover:text-primary dark:group-hover:text-primary-light'"
                                                                x-text="sect.section_han"
                                                            ></span>
                                                            <span class="text-[10px] text-slate-400 font-medium line-clamp-1" x-show="sect.section_vi" x-text="sect.section_vi"></span>
                                                        </div>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
    </main>
    </div>

    <script>
        function hskApp() {
            return {
                currentLevel: null,
                currentLevelObj: null,
                levelTitle: '',
                levelStats: {},
                currentLesson: null,
                activeTab: 'vocab',
                lessons: [],
                hskLevels: @json($levels) || [],
                
                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const lvlId = urlParams.get('level');
                    const lsnId = urlParams.get('lesson');
                    const tab = urlParams.get('tab');

                    if (lvlId) {
                        const levelObj = this.hskLevels.find(l => l.id == lvlId);
                        if (levelObj) {
                            this.selectLevel(levelObj);
                            if (lsnId) {
                                const lessonObj = this.lessons.find(l => l.id == lsnId);
                                if (lessonObj) {
                                    this.startLesson(lessonObj);
                                    if (tab) this.activeTab = tab;
                                }
                            }
                        }
                    }

                    this.$watch('currentLevel', () => this.updateUrl());
                    this.$watch('currentLesson', () => this.updateUrl());
                    this.$watch('activeTab', () => this.updateUrl());
                },

                updateUrl() {
                    const url = new URL(window.location);
                    if (this.currentLevel) url.searchParams.set('level', this.currentLevel);
                    else url.searchParams.delete('level');
                    
                    if (this.currentLesson) url.searchParams.set('lesson', this.currentLesson.id);
                    else url.searchParams.delete('lesson');

                    if (this.activeTab) url.searchParams.set('tab', this.activeTab);
                    else url.searchParams.delete('tab');
                    
                    window.history.replaceState({}, '', url);
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
                                        });
                                    }
                                });
                            }
                        });
                    }

                    this.currentLesson = lesson;
                    this.activeTab = 'vocab';
                    this.currentDialogueSectionIdx = 0;
                    this.dialogueMode = 'pinyin';

                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                
                stopLesson() {
                    this.currentLesson = null;

                },
                
                playAudio(audioUrl) {
                    if (!audioUrl) return;
                    let audio = new Audio(audioUrl);
                    audio.play();
                },
                
                selectLevel(levelObj) {
                    this.currentLevel = levelObj.id;
                    this.currentLevelObj = levelObj;
                    this.levelTitle = levelObj.title + ' - ' + levelObj.subtitle;
                    this.levelStats = {
                        lessons: levelObj.lessons_count,
                        vocab: levelObj.vocab_count,
                        duration: levelObj.duration,
                        color: levelObj.color,
                        badgeClass: levelObj.color === 'emerald' ? 'bg-yellow-105 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-450 border-yellow-250' : 
                                    levelObj.color === 'cyan' ? 'bg-teal-100 dark:bg-teal-500/10 text-teal-650 dark:text-teal-400 border-teal-200' :
                                    levelObj.color === 'blue' ? 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200' :
                                    levelObj.color === 'purple' ? 'bg-purple-100 dark:bg-purple-500/10 text-purple-650 dark:text-purple-400 border-purple-200' :
                                    levelObj.color === 'rose' ? 'bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200' :
                                    'bg-blue-105 dark:bg-blue-500/10 text-blue-600 dark:text-blue-405 border-blue-200',
                        accentClass: levelObj.color === 'emerald' ? 'bg-[#eab308]' :
                                     levelObj.color === 'cyan' ? 'bg-[#0f766e]' :
                                     levelObj.color === 'blue' ? 'bg-[#dc2626]' :
                                     levelObj.color === 'purple' ? 'bg-[#6d28d9]' :
                                     levelObj.color === 'rose' ? 'bg-[#be185d]' :
                                     'bg-[#1d4ed8]',
                        lightbgClass: levelObj.color === 'emerald' ? 'bg-yellow-500/5 dark:bg-yellow-500/2' :
                                      levelObj.color === 'cyan' ? 'bg-teal-500/5 dark:bg-teal-500/2' :
                                      levelObj.color === 'blue' ? 'bg-red-500/5 dark:bg-red-500/2' :
                                      levelObj.color === 'purple' ? 'bg-purple-500/5 dark:bg-purple-500/2' :
                                      levelObj.color === 'rose' ? 'bg-rose-500/5 dark:bg-rose-500/2' :
                                      'bg-blue-500/5 dark:bg-blue-500/2'
                    };
                    this.lessons = levelObj.lessons || [];
                    this.currentLesson = null;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            };
        }
    </script>
@endsection
