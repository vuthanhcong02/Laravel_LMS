@extends('layouts.lms')
@section('title', __('Chiết Tự & Thuận Bút Chữ Hán - Tiếng Trung XiaoMu'))

@section('header-left')
    <x-lms.breadcrumb :links="[
        ['label' => __('Học tập'), 'url' => route('home')],
        ['label' => __('Chiết tự Chữ Hán'), 'url' => null]
    ]" />
@endsection

@section('content')
<div x-data="hanziDecompositionApp({ characters: {{ json_encode($demoCharacters) }}, radicals: {{ json_encode($demoRadicals) }} })" 
     class="max-w-6xl mx-auto space-y-5">

    <!-- HERO HEADER & ACTION BAR -->
    <div class="lms-card p-4 sm:p-5 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden hover:transform-none hover:translate-y-0">
        <!-- Faint Watermark Hanzi -->
        <div class="absolute right-4 -bottom-6 text-9xl font-bold text-[#e07a5f]/5 pointer-events-none select-none zh-text">
            拆
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-xl">
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-shapes text-[#e07a5f]"></i>
                    <span>{{ __('Nghệ thuật Ghi nhớ Chữ Hán') }}</span>
                </div>
                <h1 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                    {{ __('Chiết Tự & Thuận Bút Chữ Hán') }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Bóc tách Lục thư, thơ mẹo nhớ dân gian kết hợp mô phỏng hoạt ảnh viết nét chuẩn mực.') }}
                </p>
            </div>

            <!-- Header Buttons: Tab switch & 7 Rules Modal Button -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <!-- 7 Rules Modal Trigger Button (Nổi bật phía trên) -->
                <button type="button" @click="showRulesModal = true"
                        class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#23201e] border border-[#e07a5f]/40 dark:border-[#e07a5f]/30 text-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] text-xs font-bold shadow-2xs transition-all flex items-center gap-1.5 btn-tactile cursor-pointer">
                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                    <span>{{ __('7 Quy tắc viết') }}</span>
                </button>

                <!-- Navigation Tabs -->
                <div class="flex items-center gap-1 p-1 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <button type="button" @click="activeTab = 'explorer'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all btn-tactile cursor-pointer"
                            :class="activeTab === 'explorer' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-table-columns text-[10px] mr-1"></i>
                        <span>{{ __('Chiết tự') }}</span>
                    </button>
                    <button type="button" @click="activeTab = 'stroke_studio'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all btn-tactile cursor-pointer"
                            :class="activeTab === 'stroke_studio' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-pen-nib text-[10px] mr-1"></i>
                        <span>{{ __('Luyện viết Canvas') }}</span>
                    </button>
                    <button type="button" @click="activeTab = 'quiz'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all btn-tactile cursor-pointer"
                            :class="activeTab === 'quiz' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-puzzle-piece text-[10px] mr-1"></i>
                        <span>{{ __('Luyện tập') }}</span>
                    </button>
                    <button type="button" @click="activeTab = 'radicals'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all btn-tactile cursor-pointer"
                            :class="activeTab === 'radicals' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-table-cells text-[10px] mr-1"></i>
                        <span>{{ __('214 Bộ thủ') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Quick Chips Bar -->
    <div x-show="activeTab === 'explorer' || activeTab === 'stroke_studio'" class="lms-card p-3.5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-2.5">
        <div class="relative">
            <input type="text" 
                   x-model="searchQuery"
                   placeholder="{{ __('Tìm chữ Hán, Pinyin hoặc âm Hán-Việt (VD: 休, ming, Đức, an...)') }}" 
                   class="w-full pl-9 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-white placeholder-slate-400 focus:outline-hidden focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
        </div>

        <!-- Single Row Quick Chips -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pt-0.5">
            <span class="text-[11px] font-semibold text-slate-400 shrink-0 mr-1">{{ __('Gợi ý:') }}</span>
            <template x-for="(item, key) in characters" :key="key">
                <button type="button" 
                        @click="selectCharacter(key)"
                        class="px-2.5 py-1 rounded-lg text-xs font-semibold shrink-0 transition-all btn-tactile cursor-pointer"
                        :class="selectedKey === key 
                            ? 'bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] font-bold shadow-2xs' 
                            : 'bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300 hover:border-[#e07a5f] hover:text-[#e07a5f]'">
                    <span class="zh-text font-bold" x-text="item.character"></span>
                    <span class="text-[11px] opacity-75 ml-0.5" x-text="item.sino_vietnamese"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: BỐ CỤC 2 CỘT (CỘT 1: CHỮ HÁN & VIẾT MẪU | CỘT 2: CHIẾT TỰ & MẸO NHỚ) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer'" class="space-y-5" x-cloak>
        <template x-if="currentChar">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                
                <!-- ============================================================= -->
                <!-- CỘT 1 (BÊN TRÁI): THẺ CHỮ HÁN & VIDEO/HOẠT ẢNH VIẾT MẪU (5 COLS) -->
                <!-- ============================================================= -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-5">
                        
                        <!-- Header with HSK Badge & Audio -->
                        <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900" x-text="'HSK ' + currentChar.hsk_level"></span>
                                <span class="text-xs font-semibold text-slate-400" x-text="currentChar.formation_type"></span>
                            </div>

                            <button type="button" 
                                    @click="speak(currentChar.character)"
                                    class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white transition-all text-xs font-bold flex items-center gap-1.5 btn-tactile cursor-pointer">
                                <i class="fa-solid fa-volume-high"></i>
                                <span>{{ __('Phát âm') }}</span>
                            </button>
                        </div>

                        <!-- Rice Grid Character Box with Animated Stroke Player -->
                        <div class="space-y-3">
                            <div class="relative w-48 h-48 sm:w-52 sm:h-52 mx-auto rounded-2xl bg-[#fffaf5] dark:bg-[#1f1c1a] border-2 border-[#e8e2d9] dark:border-[#382823] flex items-center justify-center overflow-hidden shadow-inner group">
                                <!-- Background Rice Grid Pattern Lines -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-30 dark:opacity-20" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="0" y1="0" x2="100%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                    <line x1="100%" y1="0" x2="0" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                    <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                    <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                </svg>

                                <!-- Faint Full Character -->
                                <div class="absolute inset-0 flex items-center justify-center text-7xl sm:text-8xl font-bold text-slate-900/10 dark:text-white/10 zh-text select-none">
                                    <span x-text="currentChar.character"></span>
                                </div>

                                <!-- Dynamic Character -->
                                <div class="relative z-10 text-7xl sm:text-8xl font-bold text-[#e07a5f] zh-text select-none transition-all duration-300"
                                     :class="isPlayingStrokes ? 'scale-105' : ''">
                                    <span x-text="currentChar.character"></span>
                                </div>

                                <!-- Watermark Step Label -->
                                <div class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-black/60 text-white text-[10px] font-bold">
                                    <span x-text="'Nét ' + currentStrokeStep + ' / ' + (currentChar.stroke_steps?.length || currentChar.stroke_count)"></span>
                                </div>
                            </div>

                            <!-- Step Name & Control Toolbar (Video Viết Lại Chữ) -->
                            <div class="space-y-2 pt-1">
                                <template x-if="currentChar.stroke_steps && currentChar.stroke_steps[currentStrokeStep - 1]">
                                    <div class="p-2 rounded-lg bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100" x-text="currentChar.stroke_steps[currentStrokeStep - 1].name"></span>
                                        <span class="text-[11px] text-[#e07a5f] ml-1 font-semibold" x-text="'(' + currentChar.stroke_steps[currentStrokeStep - 1].pinyin + ')'"></span>
                                    </div>
                                </template>

                                <!-- Mini Player Controls -->
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" @click="prevStroke()" title="{{ __('Nét trước') }}"
                                            class="w-8 h-8 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center btn-tactile hover:border-[#e07a5f]">
                                        <i class="fa-solid fa-backward-step"></i>
                                    </button>
                                    
                                    <button type="button" @click="toggleStrokeAutoPlay()" 
                                            class="px-3.5 h-8 rounded-lg text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 btn-tactile cursor-pointer"
                                            :class="isPlayingStrokes ? 'bg-amber-600 hover:bg-amber-700' : 'bg-[#e07a5f] hover:bg-[#c86349]'">
                                        <i class="fa-solid text-[10px]" :class="isPlayingStrokes ? 'fa-pause' : 'fa-play'"></i>
                                        <span x-text="isPlayingStrokes ? '{{ __('Tạm dừng') }}' : '{{ __('Viết mẫu') }}'"></span>
                                    </button>

                                    <button type="button" @click="nextStroke()" title="{{ __('Nét kế tiếp') }}"
                                            class="w-8 h-8 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center btn-tactile hover:border-[#e07a5f]">
                                        <i class="fa-solid fa-forward-step"></i>
                                    </button>

                                    <button type="button" @click="resetStrokePlayer()" title="{{ __('Viết lại') }}"
                                            class="w-8 h-8 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center btn-tactile hover:border-[#e07a5f]">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Phonetic & Meaning Overview -->
                        <div class="text-center space-y-1 pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-xl font-bold text-slate-900 dark:text-white" x-text="currentChar.pinyin"></span>
                                <span class="text-base font-bold text-[#e07a5f]" x-text="'/ ' + currentChar.sino_vietnamese + ' /'"></span>
                            </div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200" x-text="currentChar.meaning"></p>
                            <span class="text-xs text-slate-400 block" x-text="currentChar.stroke_count + ' nét • Bút thuận: ' + (currentChar.stroke_rules || 'Trái trước phải sau')"></span>
                        </div>

                        <!-- Switch to Canvas Practice CTA -->
                        <div class="pt-1">
                            <button type="button" @click="activeTab = 'stroke_studio'"
                                    class="w-full py-2 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-2 btn-tactile cursor-pointer">
                                <i class="fa-solid fa-pen-nib"></i>
                                <span>{{ __('Mở khung vẽ luyện viết tay Canvas') }}</span>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- ============================================================= -->
                <!-- CỘT 2 (BÊN PHẢI): CHIẾT TỰ, THƠ MẸO NHỚ & BÚT THUẬN (7 COLS) -->
                <!-- ============================================================= -->
                <div class="lg:col-span-7 space-y-4">
                    
                    <!-- 1. Cấu Tạo Chiết Tự (Phương trình Lục Thư) -->
                    <div class="lms-card p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-3.5">
                        <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs font-bold">
                                    <i class="fa-solid fa-shapes"></i>
                                </div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">{{ __('Bóc Tách Cấu Tạo Chiết Tự') }}</h3>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium" x-text="currentChar.formation_type"></span>
                        </div>

                        <!-- Formula Row -->
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 p-2.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                            <!-- Main Character -->
                            <div class="px-3 py-1.5 rounded-lg bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-center shadow-2xs">
                                <span class="text-xl font-bold text-slate-900 dark:text-white zh-text" x-text="currentChar.character"></span>
                                <span class="text-[10px] text-slate-500 font-medium block" x-text="currentChar.sino_vietnamese"></span>
                            </div>

                            <span class="text-base font-bold text-slate-400">=</span>

                            <!-- Child Components -->
                            <template x-for="(comp, cIdx) in currentChar.components" :key="cIdx">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <div class="px-3 py-1.5 rounded-lg bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-center shadow-2xs">
                                        <div class="flex items-center justify-center gap-1">
                                            <span class="text-xl font-bold text-[#e07a5f] zh-text" x-text="comp.character"></span>
                                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200" x-text="'(' + comp.sino_vietnamese + ')'"></span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-normal block" x-text="comp.meaning"></span>
                                    </div>
                                    <template x-if="cIdx < currentChar.components.length - 1">
                                        <span class="text-base font-bold text-slate-400">+</span>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Components Explanation Breakdown -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <template x-for="(comp, cIdx) in currentChar.components" :key="cIdx">
                                <div class="p-2.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-[#e07a5f]" x-text="comp.name"></span>
                                        <span class="text-[10px] px-1.5 py-0.2 rounded-sm bg-[#fff2ee] dark:bg-[#2c221e] text-slate-600 dark:text-slate-300" x-text="comp.position"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed" x-text="comp.explanation"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Storyline Narrative -->
                        <p class="text-xs text-slate-700 dark:text-slate-300 italic pt-1 leading-relaxed" x-text="currentChar.formation_desc"></p>
                    </div>

                    <!-- 2. Thơ Chiết Tự & Mẹo Nhớ Dân Gian -->
                    <div class="lms-card p-5 bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] hover:transform-none hover:translate-y-0 space-y-2.5 relative">
                        <div class="flex items-center justify-between text-xs font-bold text-[#e07a5f]">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-feather-pointed"></i>
                                <span>{{ __('Thơ Chiết Tự & Khẩu Quyết Ghi Nhớ') }}</span>
                            </span>
                            <button type="button" 
                                    @click="navigator.clipboard.writeText(currentChar.mnemonic_poem); $dispatch('toast', { message: 'Đã sao chép câu thơ chiết tự!', type: 'success' })"
                                    class="text-[11px] text-[#e07a5f] hover:text-[#c86349] font-bold flex items-center gap-1 cursor-pointer transition-colors">
                                <i class="fa-regular fa-copy"></i>
                                <span>{{ __('Sao chép') }}</span>
                            </button>
                        </div>

                        <p class="text-sm sm:text-base font-semibold text-slate-900 dark:text-white whitespace-pre-line leading-relaxed italic" x-text="currentChar.mnemonic_poem"></p>
                        
                        <div class="pt-2 border-t border-[#fcdccf]/60 dark:border-[#4a2e26]/60 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                            <span x-text="currentChar.traditional_quote"></span>
                            <span class="text-[#e07a5f] font-semibold">{{ __('Dân gian truyền khẩu') }}</span>
                        </div>
                    </div>



                    <!-- 4. Từ Ghép HSK Mở Rộng -->
                    <template x-if="currentChar.compounds && currentChar.compounds.length > 0">
                        <div class="lms-card p-4.5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-book-bookmark"></i>
                                    </div>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">{{ __('Từ Ghép HSK Thông Dụng') }}</h3>
                                </div>
                                <span class="text-[11px] text-slate-400">{{ __('Nhấp để nghe') }}</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <template x-for="(compound, cIndex) in currentChar.compounds" :key="cIndex">
                                    <div class="p-2.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between cursor-pointer hover:border-[#e07a5f] transition-all btn-tactile"
                                         @click="speak(compound.word)">
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="zh-text font-bold text-sm text-slate-900 dark:text-white" x-text="compound.word"></span>
                                                <span class="text-[10px] text-slate-400" x-text="compound.pinyin"></span>
                                            </div>
                                            <span class="text-[11px] text-slate-600 dark:text-slate-300 font-medium block truncate" x-text="compound.meaning"></span>
                                        </div>
                                        <i class="fa-solid fa-volume-low text-xs text-slate-400"></i>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>

            </div>
        </template>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: STUDIO LUYỆN VIẾT CANVAS TƯƠNG TÁC (HANDWRITING STUDIO) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'stroke_studio'" class="space-y-5" x-cloak>
        <template x-if="currentChar">
            <div class="space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    
                    <!-- Left: Canvas Box -->
                    <div class="lg:col-span-6 lms-card p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs font-bold">
                                    <i class="fa-solid fa-pen-fancy"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Khung Vẽ Luyện Viết') }}</h2>
                                    <p class="text-[11px] text-slate-400" x-text="'Tập viết chữ: ' + currentChar.character + ' (' + currentChar.sino_vietnamese + ')'"></p>
                                </div>
                            </div>

                            <button type="button" @click="toggleTraceGuide()"
                                    class="px-2.5 py-1 rounded-lg text-xs font-semibold border transition-all btn-tactile cursor-pointer flex items-center gap-1.5"
                                    :class="showTraceGuide 
                                        ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-300 dark:border-emerald-800' 
                                        : 'bg-[#f8f6f3] dark:bg-[#23201e] text-slate-500 border-[#e8e2d9] dark:border-[#2d2926]'">
                                <i class="fa-regular" :class="showTraceGuide ? 'fa-eye' : 'fa-eye-slash'"></i>
                                <span x-text="showTraceGuide ? '{{ __('Đồ nét: BẬT') }}' : '{{ __('Đồ nét: TẮT') }}'"></span>
                            </button>
                        </div>

                        <!-- Canvas Box (Lưới Mễ) -->
                        <div class="relative w-56 h-56 sm:w-64 sm:h-64 mx-auto rounded-2xl bg-[#fffdfa] dark:bg-[#1f1c1a] border-2 border-[#e8e2d9] dark:border-[#382823] flex items-center justify-center overflow-hidden shadow-inner touch-none select-none">
                            <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-35 dark:opacity-25" xmlns="http://www.w3.org/2000/svg">
                                <line x1="0" y1="0" x2="100%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                <line x1="100%" y1="0" x2="0" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            </svg>

                            <div x-show="showTraceGuide" 
                                 class="absolute inset-0 flex items-center justify-center text-8xl font-bold text-slate-300 dark:text-neutral-700 pointer-events-none zh-text select-none">
                                <span x-text="currentChar.character"></span>
                            </div>

                            <canvas id="hanziWritingCanvas"
                                    @mousedown="startDrawing($event)"
                                    @mousemove="draw($event)"
                                    @mouseup="stopDrawing()"
                                    @mouseleave="stopDrawing()"
                                    @touchstart="startDrawing($event)"
                                    @touchmove="draw($event)"
                                    @touchend="stopDrawing()"
                                    class="absolute inset-0 w-full h-full cursor-crosshair z-10"></canvas>
                        </div>

                        <!-- Canvas Toolbar -->
                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                            <div class="flex items-center gap-1.5 p-1 rounded-lg bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <template x-for="color in brushColors" :key="color.hex">
                                    <button type="button" 
                                            @click="selectBrushColor(color.hex)"
                                            :title="color.name"
                                            class="w-5 h-5 rounded-full transition-transform btn-tactile cursor-pointer"
                                            :style="'background-color: ' + color.hex"
                                            :class="brushColor === color.hex && !isEraser ? 'ring-2 ring-offset-1 ring-[#e07a5f] scale-110' : 'opacity-80 hover:opacity-100'"></button>
                                </template>
                            </div>

                            <div class="flex items-center gap-1 p-1 rounded-lg bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                                <button type="button" @click="setBrushSize(4)" title="{{ __('Mảnh') }}"
                                        class="px-2 py-0.5 rounded text-[10px] font-bold btn-tactile cursor-pointer"
                                        :class="brushSize === 4 ? 'bg-[#e07a5f] text-white' : 'text-slate-500'">4px</button>
                                <button type="button" @click="setBrushSize(8)" title="{{ __('Vừa') }}"
                                        class="px-2 py-0.5 rounded text-[10px] font-bold btn-tactile cursor-pointer"
                                        :class="brushSize === 8 ? 'bg-[#e07a5f] text-white' : 'text-slate-500'">8px</button>
                                <button type="button" @click="setBrushSize(14)" title="{{ __('Dày') }}"
                                        class="px-2 py-0.5 rounded text-[10px] font-bold btn-tactile cursor-pointer"
                                        :class="brushSize === 14 ? 'bg-[#e07a5f] text-white' : 'text-slate-500'">14px</button>
                            </div>

                            <div class="flex items-center gap-1">
                                <button type="button" @click="toggleEraser()" title="{{ __('Tẩy') }}"
                                        class="p-1.5 rounded-lg border text-xs font-bold btn-tactile cursor-pointer"
                                        :class="isEraser ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300'">
                                    <i class="fa-solid fa-eraser"></i>
                                </button>
                                <button type="button" @click="undoStroke()" title="{{ __('Hoàn tác') }}"
                                        class="p-1.5 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 text-xs font-bold btn-tactile cursor-pointer">
                                    <i class="fa-solid fa-arrow-rotate-left"></i>
                                </button>
                                <button type="button" @click="clearCanvas()" title="{{ __('Xóa sạch') }}"
                                        class="p-1.5 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#23201e] text-rose-500 hover:text-rose-600 text-xs font-bold btn-tactile cursor-pointer">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Feedback and Submit -->
                        <div class="space-y-2 pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                            <template x-if="feedbackMessage">
                                <div class="p-2 rounded-lg text-xs font-semibold text-center"
                                     :class="feedbackType === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800'"
                                     x-text="feedbackMessage"></div>
                            </template>

                            <button type="button" @click="finishPractice()"
                                    class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 btn-tactile cursor-pointer">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('Đã Viết Xong • Kiểm Tra') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Right: Stroke Steps Guide for Canvas -->
                    <div class="lg:col-span-6 lms-card p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xs font-bold">
                                    <i class="fa-solid fa-list-ol"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Thứ Tự Thuận Bút') }}</h3>
                            </div>
                            <span class="text-xs font-bold text-[#e07a5f]" x-text="currentChar.stroke_count + ' nét'"></span>
                        </div>

                        <div class="space-y-2 max-h-96 overflow-y-auto no-scrollbar pr-1">
                            <template x-for="(step, sIdx) in currentChar.stroke_steps" :key="sIdx">
                                <div class="p-3 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-6 h-6 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold text-xs flex items-center justify-center border border-[#fcdccf] dark:border-[#4a2e26]" x-text="step.step"></span>
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 dark:text-white" x-text="step.name"></span>
                                            <span class="text-[11px] text-slate-400 block" x-text="step.highlight"></span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-[#e07a5f]" x-text="step.pinyin"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: PHÒNG LUYỆN TẬP THỰC CHIẾN (HIGH-VALUE DRILLS) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'quiz'" class="space-y-4" x-cloak>
        <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 max-w-xl mx-auto space-y-5">
            
            <!-- Quiz Mode Selector Pills (1 Single Row) -->
            <div class="space-y-3 pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        {{ __('Luyện tập Thực chiến:') }}
                    </span>
                    <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-bold">
                        <i class="fa-solid fa-star text-amber-500"></i>
                        <span x-text="totalScore + ' {{ __('điểm') }}'"></span>
                    </div>
                </div>

                <!-- 3 Drill Buttons in 1 Row -->
                <div class="grid grid-cols-3 gap-1.5 p-1 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <button type="button" @click="quizMode = 'confusing_chars'"
                            class="px-2 py-1.5 rounded-lg text-[11px] font-bold text-center transition-all btn-tactile cursor-pointer truncate"
                            :class="quizMode === 'confusing_chars' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        {{ __('1. Chữ Dễ Lú') }}
                    </button>
                    <button type="button" @click="quizMode = 'predict_meaning'"
                            class="px-2 py-1.5 rounded-lg text-[11px] font-bold text-center transition-all btn-tactile cursor-pointer truncate"
                            :class="quizMode === 'predict_meaning' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        {{ __('2. Đoán Chữ Lạ') }}
                    </button>
                    <button type="button" @click="quizMode = 'find_typo'"
                            class="px-2 py-1.5 rounded-lg text-[11px] font-bold text-center transition-all btn-tactile cursor-pointer truncate"
                            :class="quizMode === 'find_typo' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        {{ __('3. Bắt Lỗi Sai') }}
                    </button>
                </div>
            </div>

            <!-- DRILL 1: PHÂN BIỆT CHỮ TƯƠNG ĐỒNG (形近字) -->
            <div x-show="quizMode === 'confusing_chars'" class="space-y-4">
                <template x-if="confusingQuestions[confusingIndex]">
                    <div class="space-y-4">
                        <div class="text-center space-y-1">
                            <span class="text-[11px] font-bold text-[#e07a5f] uppercase tracking-wider" x-text="'Câu hỏi ' + (confusingIndex + 1) + ' / ' + confusingQuestions.length"></span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white" x-text="confusingQuestions[confusingIndex].prompt"></h3>
                            <span class="text-xs text-slate-400 block" x-text="confusingQuestions[confusingIndex].context"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <template x-for="(opt, optIdx) in confusingQuestions[confusingIndex].options" :key="optIdx">
                                <button type="button" 
                                        @click="selectConfusingAnswer(opt.char)"
                                        :disabled="confusingAnswered !== null"
                                        class="p-4 rounded-xl border text-center transition-all btn-tactile cursor-pointer flex flex-col items-center justify-center space-y-2"
                                        :class="confusingAnswered === null 
                                            ? 'bg-[#fcfaf7] dark:bg-[#23201e] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e]' 
                                            : (opt.char === confusingQuestions[confusingIndex].correct 
                                                ? 'bg-emerald-50 dark:bg-emerald-950/50 border-emerald-500 text-emerald-700 dark:text-emerald-300' 
                                                : (confusingAnswered === opt.char 
                                                    ? 'bg-rose-50 dark:bg-rose-950/50 border-rose-500 text-rose-700 dark:text-rose-300' 
                                                    : 'opacity-50 border-[#e8e2d9] dark:border-[#2d2926]'))">
                                    <span class="text-5xl font-bold zh-text" x-text="opt.char"></span>
                                    <span class="text-xs font-semibold" x-text="opt.label"></span>
                                </button>
                            </template>
                        </div>

                        <template x-if="confusingAnswered !== null">
                            <div class="p-3.5 rounded-xl border space-y-2 animate-fade-in"
                                 :class="confusingAnswered === confusingQuestions[confusingIndex].correct 
                                     ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200' 
                                     : 'bg-amber-50 dark:bg-amber-950/40 border-amber-300 dark:border-amber-800 text-amber-900 dark:text-amber-200'">
                                <div class="flex items-center gap-2 text-xs font-bold">
                                    <i class="fa-solid" :class="confusingAnswered === confusingQuestions[confusingIndex].correct ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-amber-600'"></i>
                                    <span x-text="confusingAnswered === confusingQuestions[confusingIndex].correct ? '{{ __('Chính xác! Tuyệt vời!') }}' : '{{ __('Chưa chính xác!') }}'"></span>
                                </div>
                                <p class="text-xs leading-relaxed" x-text="confusingQuestions[confusingIndex].mnemonic"></p>
                                <div class="text-right pt-1">
                                    <button type="button" @click="nextConfusing()" 
                                            class="px-4 py-1.5 rounded-lg bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile cursor-pointer">
                                        {{ __('Tiếp tục') }} <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- DRILL 2: ĐOÁN NGHĨA CHỮ LẠ (偏旁推测) -->
            <div x-show="quizMode === 'predict_meaning'" class="space-y-4">
                <template x-if="predictQuestions[predictIndex]">
                    <div class="space-y-4">
                        <div class="text-center space-y-2">
                            <span class="text-[11px] font-bold text-[#e07a5f] uppercase tracking-wider" x-text="'Câu hỏi ' + (predictIndex + 1) + ' / ' + predictQuestions.length"></span>
                            
                            <div class="inline-flex flex-col items-center justify-center p-3 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26]">
                                <span class="text-5xl font-bold text-[#e07a5f] zh-text" x-text="predictQuestions[predictIndex].unknownChar"></span>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 mt-1" x-text="predictQuestions[predictIndex].pinyin"></span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400" x-text="predictQuestions[predictIndex].components"></p>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white" x-text="predictQuestions[predictIndex].question"></h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                            <template x-for="(opt, optIdx) in predictQuestions[predictIndex].options" :key="optIdx">
                                <button type="button" 
                                        @click="selectPredictAnswer(opt)"
                                        :disabled="predictAnswered !== null"
                                        class="p-3 rounded-xl border text-left text-xs font-semibold transition-all btn-tactile cursor-pointer"
                                        :class="predictAnswered === null 
                                            ? 'bg-[#fcfaf7] dark:bg-[#23201e] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e]' 
                                            : (opt === predictQuestions[predictIndex].correct 
                                                ? 'bg-emerald-50 dark:bg-emerald-950/50 border-emerald-500 text-emerald-700 dark:text-emerald-300 font-bold' 
                                                : (predictAnswered === opt 
                                                    ? 'bg-rose-50 dark:bg-rose-950/50 border-rose-500 text-rose-700 dark:text-rose-300' 
                                                    : 'opacity-50 border-[#e8e2d9] dark:border-[#2d2926]'))"
                                        x-text="(optIdx + 1) + '. ' + opt"></button>
                            </template>
                        </div>

                        <template x-if="predictAnswered !== null">
                            <div class="p-3.5 rounded-xl border space-y-2 animate-fade-in"
                                 :class="predictAnswered === predictQuestions[predictIndex].correct 
                                     ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200' 
                                     : 'bg-amber-50 dark:bg-amber-950/40 border-amber-300 dark:border-amber-800 text-amber-900 dark:text-amber-200'">
                                <div class="flex items-center gap-2 text-xs font-bold">
                                    <i class="fa-solid" :class="predictAnswered === predictQuestions[predictIndex].correct ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-amber-600'"></i>
                                    <span x-text="predictAnswered === predictQuestions[predictIndex].correct ? '{{ __('Đoán rất chuẩn!') }}' : '{{ __('Chưa chính xác!') }}'"></span>
                                </div>
                                <p class="text-xs leading-relaxed" x-text="predictQuestions[predictIndex].explanation"></p>
                                <div class="text-right pt-1">
                                    <button type="button" @click="nextPredict()" 
                                            class="px-4 py-1.5 rounded-lg bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile cursor-pointer">
                                        {{ __('Tiếp tục') }} <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- DRILL 3: BẮT LỖI SAI NÉT & BỘ THỦ (错别字) -->
            <div x-show="quizMode === 'find_typo'" class="space-y-4">
                <template x-if="typoQuestions[typoIndex]">
                    <div class="space-y-4">
                        <div class="text-center space-y-2">
                            <span class="text-[11px] font-bold text-[#e07a5f] uppercase tracking-wider" x-text="'Câu hỏi ' + (typoIndex + 1) + ' / ' + typoQuestions.length"></span>
                            
                            <div class="p-3.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1">
                                <p class="text-xs text-slate-500 dark:text-slate-400" x-text="typoQuestions[typoIndex].sentence"></p>
                                <p class="text-base sm:text-lg font-bold text-slate-900 dark:text-white zh-text" x-text="typoQuestions[typoIndex].sentenceZh"></p>
                            </div>

                            <span class="text-xs font-semibold text-[#e07a5f] block" x-text="'Cần điền chữ: ' + typoQuestions[typoIndex].wordNeeded"></span>
                        </div>

                        <div class="grid grid-cols-3 gap-2.5 pt-1">
                            <template x-for="(char, cIdx) in typoQuestions[typoIndex].options" :key="cIdx">
                                <button type="button" 
                                        @click="selectTypoAnswer(cIdx)"
                                        :disabled="typoAnswered !== null"
                                        class="p-4 rounded-xl border text-center transition-all btn-tactile cursor-pointer"
                                        :class="typoAnswered === null 
                                            ? 'bg-[#fcfaf7] dark:bg-[#23201e] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e]' 
                                            : (cIdx === typoQuestions[typoIndex].correctIndex 
                                                ? 'bg-emerald-50 dark:bg-emerald-950/50 border-emerald-500 text-emerald-700 dark:text-emerald-300 font-bold' 
                                                : (typoAnswered === cIdx 
                                                    ? 'bg-rose-50 dark:bg-rose-950/50 border-rose-500 text-rose-700 dark:text-rose-300' 
                                                    : 'opacity-50 border-[#e8e2d9] dark:border-[#2d2926]'))">
                                    <span class="text-4xl font-bold zh-text" x-text="char"></span>
                                </button>
                            </template>
                        </div>

                        <template x-if="typoAnswered !== null">
                            <div class="p-3.5 rounded-xl border space-y-2 animate-fade-in"
                                 :class="typoAnswered === typoQuestions[typoIndex].correctIndex 
                                     ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200' 
                                     : 'bg-amber-50 dark:bg-amber-950/40 border-amber-300 dark:border-amber-800 text-amber-900 dark:text-amber-200'">
                                <div class="flex items-center gap-2 text-xs font-bold">
                                    <i class="fa-solid" :class="typoAnswered === typoQuestions[typoIndex].correctIndex ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-amber-600'"></i>
                                    <span x-text="typoAnswered === typoQuestions[typoIndex].correctIndex ? '{{ __('Chọn đúng chữ chuẩn xác!') }}' : '{{ __('Sai rồi! Đã chọn nhầm chữ tương tự.') }}'"></span>
                                </div>
                                <p class="text-xs leading-relaxed" x-text="typoQuestions[typoIndex].explanation"></p>
                                <div class="text-right pt-1">
                                    <button type="button" @click="nextTypo()" 
                                            class="px-4 py-1.5 rounded-lg bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile cursor-pointer">
                                        {{ __('Tiếp tục') }} <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 4: BẢNG 214 BỘ THỦ (RADICAL MATRIX) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'radicals'" class="space-y-4" x-cloak>
        <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">{{ __('Bảng 214 Bộ Thủ Khang Hy') }}</h3>
                    <p class="text-xs text-slate-400">{{ __('Thành phần gốc tạo nên toàn bộ kho chữ Hán') }}</p>
                </div>
                <div class="w-full sm:w-60">
                    <input type="text" x-model="radicalSearch" placeholder="{{ __('Tìm tên bộ, pinyin, nghĩa...') }}" 
                           class="w-full px-3 py-2 text-xs rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-white focus:outline-hidden focus:border-[#e07a5f]">
                </div>
            </div>

            <!-- Radicals Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                <template x-for="(r, rIdx) in filteredRadicals" :key="rIdx">
                    <div class="p-2.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] transition-all flex items-center gap-2.5 cursor-pointer btn-tactile"
                         @click="speak(r.radical)">
                        <span class="text-2xl font-bold zh-text text-[#e07a5f]" x-text="r.radical"></span>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-900 dark:text-white block truncate" x-text="r.sino_vietnamese"></span>
                            <span class="text-[10px] text-slate-400 block truncate" x-text="r.meaning"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL POPUP: 7 QUY TẮC VÀNG VIẾT CHỮ HÁN (GOLDEN STROKE RULES MODAL) -->
    <!-- ========================================================================= -->
    <div x-show="showRulesModal" 
         x-cloak
         @keydown.escape.window="showRulesModal = false"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop Blur -->
        <div x-show="showRulesModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showRulesModal = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>

        <!-- Modal Dialog Box -->
        <div x-show="showRulesModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="relative w-full max-w-2xl bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-5 sm:p-6 space-y-5 z-10 my-8">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3.5 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-sm font-bold shadow-2xs">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('7 Quy Tắc Vàng Viết Chữ Hán') }}</h3>
                        <p class="text-xs text-slate-400">{{ __('Quy chuẩn thuận bút (笔顺规则) nền tảng trong Hán ngữ') }}</p>
                    </div>
                </div>

                <!-- Close Button -->
                <button type="button" @click="showRulesModal = false" 
                        class="w-8 h-8 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] hover:bg-[#e8e2d9] dark:hover:bg-[#2d2926] text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center text-xs transition-colors btn-tactile cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body: 7 Rules Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[60vh] overflow-y-auto no-scrollbar pr-1">
                <template x-for="rule in strokeRulesGuide" :key="rule.id">
                    <div class="p-3.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] transition-all space-y-1.5 flex flex-col justify-between">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-[#e07a5f] uppercase tracking-wider" x-text="'Quy tắc ' + rule.id"></span>
                                <span class="px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-sm font-bold zh-text" x-text="rule.example"></span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span x-text="rule.name"></span>
                                <span class="text-[10px] text-slate-400 font-normal" x-text="'(' + rule.zh + ')'"></span>
                            </h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed" x-text="rule.desc"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between pt-3 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                <span class="text-[11px] text-slate-400 italic">{{ __('Ghi nhớ quy tắc giúp viết nhanh, chuẩn nét và đẹp chữ.') }}</span>
                <button type="button" @click="showRulesModal = false"
                        class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs btn-tactile cursor-pointer">
                    {{ __('Đã hiểu • Đóng') }}
                </button>
            </div>

        </div>
    </div>

</div>
@endsection
