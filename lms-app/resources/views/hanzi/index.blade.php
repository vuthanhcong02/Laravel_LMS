@extends('layouts.lms')
@section('title', __('Chiết Tự Chữ Hán Theo Bộ Thủ - Tiếng Trung XiaoMu'))

@section('header-left')
    <div x-data="{ step: 1, radical: '', radicalName: '', char: '' }"
         @hanzi-nav-changed.window="step = $event.detail.step; radical = $event.detail.radical; radicalName = $event.detail.radicalName; char = $event.detail.char"
         class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-[#e07a5f] transition-colors shrink-0">{{ __('Học tập') }}</a>
        <span class="shrink-0 text-slate-400">/</span>
        
        <button type="button" 
                @click="window.dispatchEvent(new CustomEvent('hanzi-go-step', { detail: { step: 1 } }))"
                class="hover:text-[#e07a5f] transition-colors cursor-pointer shrink-0"
                :class="step === 1 ? 'text-slate-800 dark:text-slate-200 font-bold' : ''">
            {{ __('214 Bộ thủ') }}
        </button>

        <template x-if="step >= 2 && radical">
            <div class="flex items-center gap-2">
                <span class="shrink-0 text-slate-400">/</span>
                <button type="button" 
                        @click="window.dispatchEvent(new CustomEvent('hanzi-go-step', { detail: { step: 2 } }))"
                        class="hover:text-[#e07a5f] transition-colors cursor-pointer"
                        :class="step === 2 ? 'text-[#e07a5f] font-bold' : ''">
                    <span>{{ __('Bộ ') }}</span><span class="zh-text text-sm font-bold" x-text="radical"></span>
                </button>
            </div>
        </template>

        <template x-if="step === 3 && char">
            <div class="flex items-center gap-2">
                <span class="shrink-0 text-slate-400">/</span>
                <span class="text-[#e07a5f] font-bold zh-text text-base" x-text="char"></span>
            </div>
        </template>
    </div>
@endsection

@section('content')
<div x-data="hanziDecompositionApp({ radicalGroups: {{ json_encode($radicalGroups) }}, radicals: {{ json_encode($demoRadicals) }}, radicals214: {{ json_encode($radicals214) }} })" 
     class="max-w-6xl mx-auto space-y-5">

    <!-- ========================================================================= -->
    <!-- TOP ACTION BAR: CÁC NÚT TIỆN ÍCH BỔ TRỢ -->
    <!-- ========================================================================= -->
    <div class="flex items-center justify-end gap-2">
        <button type="button" @click="showRulesModal = true"
                class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] hover:bg-[#fff7f4] dark:hover:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs btn-tactile">
            <i class="fa-solid fa-graduation-cap text-[#e07a5f]"></i>
            <span class="hidden sm:inline">{{ __('7 Quy tắc viết') }}</span>
        </button>
        <button type="button" @click="activeTab = 'stroke_studio'"
                class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] hover:bg-[#fff7f4] dark:hover:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs btn-tactile"
                :class="activeTab === 'stroke_studio' ? 'border-[#e07a5f] text-[#e07a5f] dark:text-[#e07a5f]' : ''">
            <i class="fa-solid fa-pen-nib text-[#e07a5f]"></i>
            <span class="hidden sm:inline">{{ __('Luyện viết') }}</span>
        </button>
        <button type="button" @click="activeTab = 'quiz'"
                class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] hover:bg-[#fff7f4] dark:hover:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs btn-tactile"
                :class="activeTab === 'quiz' ? 'border-[#e07a5f] text-[#e07a5f] dark:text-[#e07a5f]' : ''">
            <i class="fa-solid fa-puzzle-piece text-[#e07a5f]"></i>
            <span class="hidden sm:inline">{{ __('Luyện tập') }}</span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 1: THEO BỘ THỦ (214) (BIẾN TẤU CHUẨN XIAOMU LMS) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && step === 1" class="space-y-5" x-cloak>
        
        <!-- HERO BANNER TỔNG QUAN PHÂN HỆ CHIẾT TỰ (CHUẨN THEME XIAOMU LMS) -->
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] border border-[#fcdccf] dark:border-[#3d2822] rounded-3xl relative overflow-hidden group shadow-xs">
            <!-- Faint Watermark Hanzi -->
            <div class="absolute right-4 -bottom-6 text-9xl font-extrabold text-[#e07a5f]/5 pointer-events-none select-none zh-text">
                部
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative z-10">
                <!-- Cụm thông tin giới thiệu -->
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-xs font-bold shadow-2xs">
                        <i class="fa-solid fa-shapes text-[#e07a5f]"></i>
                        <span>{{ __('Chiết tự & Tự nguyên Hán ngữ') }}</span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                        {{ __('Tra Cứu Chiết Tự & 214 Bộ Thủ Khang Hy') }}
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Giải mã cấu tạo chữ Hán theo từng bộ thủ, hiểu sâu bản chất tự nguyên, thứ tự thuận bút chuẩn và mẹo ghi nhớ nhớ lâu.') }}
                    </p>
                </div>

                <!-- Thống kê nhanh & CTA Pills -->
                <div class="flex items-center gap-3 flex-wrap shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="px-3.5 py-2 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] text-center shadow-2xs min-w-[76px]">
                            <span class="text-base font-bold text-[#e07a5f] block">214</span>
                            <span class="text-[10px] text-slate-400 font-medium uppercase">{{ __('Bộ thủ') }}</span>
                        </div>
                        <div class="px-3.5 py-2 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] text-center shadow-2xs min-w-[76px]">
                            <span class="text-base font-bold text-slate-800 dark:text-slate-100 block">1 - 17</span>
                            <span class="text-[10px] text-slate-400 font-medium uppercase">{{ __('Số nét') }}</span>
                        </div>
                        <div class="px-3.5 py-2 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] text-center shadow-2xs min-w-[76px]">
                            <span class="text-base font-bold text-slate-800 dark:text-slate-100 block">7</span>
                            <span class="text-[10px] text-slate-400 font-medium uppercase">{{ __('Quy tắc') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header: Tiêu đề "Theo bộ thủ (214)" & Số lượng -->
        <div class="flex items-center justify-between pt-1">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <span>{{ __('Danh mục 214 Bộ Thủ Khang Hy') }}</span>
            </h2>
            <span class="px-2.5 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-xs font-bold shadow-2xs" 
                  x-text="total214Count + ' {{ __('bộ thủ') }}'"></span>
        </div>

        <!-- Thanh Tìm kiếm Bộ thủ: "Tìm bộ thủ - glyph, Hán - Việt, nghĩa..." -->
        <div class="relative">
            <input type="text" 
                   x-model="radicalSearchFilter"
                   placeholder="{{ __('Tìm bộ thủ - glyph, Hán - Việt, nghĩa...') }}" 
                   class="w-full px-4 py-2.5 rounded-2xl text-xs sm:text-sm bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/15 shadow-2xs transition-all">
            <i class="fa-solid fa-magnifying-glass absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
        </div>

        <!-- DANH SÁCH BỘ THỦ PHÂN THEO SỐ NÉT (1 nét (6), 2 nét (23), 3 nét (31)...) -->
        <div class="space-y-6">
            <template x-for="(group, strokeKey) in filtered214Grouped" :key="strokeKey">
                <div class="space-y-2.5">
                    <!-- Tên nhóm số nét: "1 nét (6)" -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400" 
                              x-text="group.name + ' (' + group.items.length + ')'"></span>
                        <div class="flex-1 h-px bg-[#e8e2d9] dark:bg-[#2d2926]"></div>
                    </div>

                    <!-- Lưới các ô card bộ thủ -->
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2.5 sm:gap-3">
                        <template x-for="item in group.items" :key="item.id">
                            <div class="p-3 rounded-2xl bg-white dark:bg-[#181615] hover:bg-[#fff9f7] dark:hover:bg-[#201a18] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:shadow-md flex flex-col items-center justify-between text-center cursor-pointer group transition-all duration-200 min-h-[96px] shadow-2xs btn-tactile"
                                 @click="chooseRadical214(item)">
                                
                                <!-- Ký tự bộ thủ to ở trên -->
                                <span class="text-2xl sm:text-3xl font-bold zh-text text-slate-800 dark:text-slate-100 group-hover:text-[#e07a5f] group-hover:scale-110 transition-all py-0.5" 
                                      x-text="item.radical"></span>

                                <!-- Tên âm Hán-Việt ở giữa -->
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 block truncate w-full" 
                                      x-text="item.sino_vietnamese"></span>

                                <!-- Số chữ thuộc bộ ở dưới (VD: 8 chữ, 20 chữ) -->
                                <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block truncate" 
                                      x-text="(item.characters?.length || item.char_count || 0) + ' {{ __('chữ') }}'"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Trạng thái trống nếu không tìm thấy bộ thủ -->
        <div x-show="Object.keys(filtered214Grouped).length === 0" class="p-8 text-center bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 shadow-xs">
            <i class="fa-solid fa-filter text-2xl text-slate-400"></i>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Không tìm thấy bộ thủ nào phù hợp.') }}</p>
            <button type="button" @click="radicalSearchFilter = ''" class="px-3.5 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold cursor-pointer transition-all shadow-xs btn-tactile">
                {{ __('Đặt lại tìm kiếm') }}
            </button>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 2: CHỮ HÁN BỘ [X] (BIẾN TẤU CHUẨN XIAOMU LMS) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && step === 2" class="space-y-5" x-cloak>
        
        <!-- Tiêu đề & Nút quay lại -->
        <div class="space-y-1.5">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 pt-1">
                <div class="flex items-baseline gap-3">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>{{ __('Chữ Hán bộ') }}</span>
                        <span class="zh-text text-2xl sm:text-3xl text-[#e07a5f]" x-text="currentRadicalData ? currentRadicalData.radical : ''"></span>
                    </h1>
                </div>
                <!-- Nút quay lại danh sách bộ -->
                <button type="button" @click="goToStep1()" 
                        class="text-xs font-semibold text-slate-500 hover:text-[#e07a5f] dark:text-slate-400 dark:hover:text-[#e07a5f] transition-colors cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>{{ __('Xem tất cả 214 Bộ thủ') }}</span>
                </button>
            </div>
            
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="(sortedRadicalCharacters.length) + ' {{ __('chữ Hán liên quan') }}'"></p>
        </div>

        <!-- Thanh Công cụ: [Sắp xếp] & [Ô Tìm kiếm chữ trong bộ] -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
            <!-- Thanh Sắp xếp: "Sắp xếp: [Số nét] [Độ phổ biến]" -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold mr-1">{{ __('Sắp xếp:') }}</span>
                
                <button type="button" @click="sortType = 'stroke'" 
                        class="px-3.5 py-1 rounded-full text-xs transition-all cursor-pointer btn-tactile"
                        :class="sortType === 'stroke' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#23201e] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    {{ __('Số nét') }}
                </button>

                <button type="button" @click="sortType = 'frequency'" 
                        class="px-3.5 py-1 rounded-full text-xs transition-all cursor-pointer btn-tactile"
                        :class="sortType === 'frequency' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#23201e] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    {{ __('Độ phổ biến') }}
                </button>
            </div>

            <!-- Ô Tìm kiếm Chữ Hán trong Bộ này -->
            <div class="relative w-full sm:w-64">
                <input type="text" 
                       x-model="charSearchFilter"
                       placeholder="{{ __('Tìm chữ, pinyin, nghĩa...') }}" 
                       class="w-full px-3.5 py-1.5 pr-8 rounded-xl text-xs bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/15 shadow-2xs transition-all">
                <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-[11px] text-slate-400 pointer-events-none"></i>
            </div>
        </div>

        <!-- LƯỚI CARD CHỮ HÁN DẠNG NẰM NGANG (THEME XIAOMU LMS) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            <template x-for="charItem in sortedRadicalCharacters" :key="charItem.char">
                <div class="p-3.5 rounded-2xl bg-white dark:bg-[#181615] hover:bg-[#fff9f7] dark:hover:bg-[#201a18] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:shadow-md flex items-center gap-3.5 cursor-pointer group transition-all duration-200 shadow-2xs btn-tactile"
                     @click="chooseCharacter214(charItem)">
                    
                    <!-- Ký tự chữ to bên trái -->
                    <span class="text-3xl sm:text-4xl font-bold zh-text text-slate-800 dark:text-slate-100 group-hover:text-[#e07a5f] group-hover:scale-105 transition-all shrink-0" 
                          x-text="charItem.char"></span>

                    <!-- Cột thông tin bên phải: Pinyin ở trên, Nghĩa ở dưới -->
                    <div class="min-w-0 flex-1">
                        <span class="text-xs font-bold text-[#e07a5f] block" x-text="charItem.pinyin"></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 block truncate mt-0.5" x-text="charItem.meaning"></span>
                    </div>
                </div>
            </template>

            <!-- Trạng thái trống nếu không tìm thấy chữ -->
            <div x-show="sortedRadicalCharacters.length === 0" class="col-span-full p-8 text-center bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 shadow-xs">
                <i class="fa-solid fa-filter text-2xl text-slate-400"></i>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Không tìm thấy chữ Hán nào phù hợp trong bộ này.') }}</p>
                <button type="button" @click="charSearchFilter = ''" class="px-3.5 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold cursor-pointer transition-all shadow-xs btn-tactile">
                    {{ __('Đặt lại tìm kiếm') }}
                </button>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 3: CHI TIẾT CHỮ HÁN (HERO BANNER & CỘT NỘI DUNG XIAOMU LMS) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && step === 3" class="space-y-4" x-cloak>
        
        <!-- Nút quay lại danh sách chữ -->
        <div class="flex items-center justify-end text-xs text-slate-500 dark:text-slate-400">
            <button type="button" @click="goToStep2()" class="hover:text-[#e07a5f] transition-colors cursor-pointer flex items-center gap-1.5 font-semibold">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>{{ __('Danh sách chữ bộ ') }}<strong x-text="currentRadicalData ? currentRadicalData.radical : ''"></strong></span>
            </button>
        </div>

        <!-- HERO BANNER XIAOMU LMS (WARM CREAM / TERRACOTTA / CHARCOAL) -->
        <div class="relative rounded-3xl bg-gradient-to-br from-[#fff7f4] via-[#ffffff] to-[#fff2ee] dark:from-[#201a18] dark:via-[#181615] dark:to-[#241c19] border border-[#fcdccf] dark:border-[#3d2822] p-6 sm:p-7 overflow-hidden shadow-xs">
            <!-- Họa tiết ô mễ mờ trang nhã -->
            <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-15 dark:opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="xiaomu-grid" width="48" height="48" patternUnits="userSpaceOnUse">
                        <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#e07a5f" stroke-width="0.8"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#xiaomu-grid)" />
            </svg>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- Cụm bên trái: Chữ to + 2 Ô Thông tin + Dải tags -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                    <!-- Ký tự chữ Hán siêu to màu Terracotta -->
                    <div class="text-7xl sm:text-8xl font-bold zh-text text-[#e07a5f] select-none shrink-0 drop-shadow-xs"
                         x-text="currentCharacterData ? currentCharacterData.char : (currentChar ? currentChar.character : '')">
                    </div>

                    <!-- 2 Box thông tin: Nghĩa tiếng Việt & Âm Hán Việt -->
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Box Nghĩa tiếng Việt -->
                            <div class="px-4 py-2.5 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] shadow-xs backdrop-blur-xs min-w-[160px]">
                                <span class="text-[10px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-400 block">{{ __('NGHĨA TIẾNG VIỆT') }}</span>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mt-0.5" 
                                    x-text="currentCharacterData ? currentCharacterData.meaning : (currentChar ? currentChar.meaning : '')"></h3>
                            </div>

                            <!-- Box Âm Hán Việt -->
                            <div class="px-4 py-2.5 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] shadow-xs backdrop-blur-xs min-w-[140px]">
                                <span class="text-[10px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-400 block">{{ __('ÂM HÁN VIỆT') }}</span>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mt-0.5" 
                                    x-text="currentCharacterData ? currentCharacterData.sino_vietnamese : (currentChar ? currentChar.sino_vietnamese : '')"></h3>
                            </div>
                        </div>

                        <!-- Dải metadata tags: [1 chữ] [# 1 nét] [Thanh điệu: 3 V] -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 rounded-full bg-white dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs text-[#e07a5f] font-semibold shadow-2xs"
                                  x-text="'1 chữ'"></span>
                            <span class="px-3 py-1 rounded-full bg-white dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs text-[#e07a5f] font-semibold shadow-2xs"
                                  x-text="'# ' + (currentCharacterData?.stroke_count || currentChar?.stroke_count || 1) + ' nét'"></span>
                            <span class="px-3 py-1 rounded-full bg-white dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs text-[#e07a5f] font-semibold shadow-2xs"
                                  x-text="'Thanh điệu: ' + (currentCharacterData?.tone || '1')"></span>
                        </div>
                    </div>
                </div>

                <!-- Cụm bên phải: Nút Yêu thích, Chia sẻ, Phát âm -->
                <div class="flex items-center gap-2 self-start md:self-center shrink-0">
                    <!-- Nút Trái tim Favorite -->
                    <button type="button" @click="toggleFavorite()" 
                            class="w-9 h-9 rounded-xl bg-white dark:bg-[#23201e] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center transition-all cursor-pointer shadow-2xs btn-tactile"
                            :title="isFavorite ? 'Bỏ thích' : 'Yêu thích'">
                        <i class="fa-solid fa-heart text-xs" :class="isFavorite ? 'text-rose-500' : 'text-slate-400'"></i>
                    </button>

                    <!-- Nút Chia sẻ -->
                    <button type="button" @click="if (navigator.share) { navigator.share({ title: 'Chiết tự chữ Hán', url: window.location.href }); }" 
                            class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#23201e] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer shadow-2xs btn-tactile">
                        <i class="fa-solid fa-share-nodes text-[11px] text-[#e07a5f]"></i>
                        <span>{{ __('Chia sẻ') }}</span>
                    </button>

                    <!-- Nút Phát âm (Terracotta Button) -->
                    <button type="button" @click="speak(currentCharacterData ? currentCharacterData.char : '')"
                            class="w-9 h-9 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold flex items-center justify-center shadow-xs transition-all cursor-pointer btn-tactile"
                            title="{{ __('Phát âm') }}">
                        <i class="fa-solid fa-volume-high text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- DÒNG THÔNG BÁO HOÀN THIỆN -->
        <div class="p-3 rounded-2xl bg-[#fffbf0] dark:bg-[#241e14] border border-[#fde68a] dark:border-[#533f1c] text-xs text-amber-800 dark:text-amber-300 flex items-center gap-2.5 shadow-2xs">
            <i class="fa-solid fa-circle-info text-amber-600 dark:text-amber-400 text-xs"></i>
            <span>{{ __('Trang chữ này đang được hoàn thiện. Một số phần (tự nguyên, mẹo nhớ Hán-Việt, từ ghép...) sẽ xuất hiện sớm.') }}</span>
        </div>

        <!-- THANH TABS TÍNH NĂNG (THEME XIAOMU LMS) -->
        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar py-1">
            <button type="button" @click="detailTab = 'stroke_order'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer shrink-0 btn-tactile"
                    :class="detailTab === 'stroke_order' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#1e1c1a] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                {{ __('Bút thuận') }}
            </button>
            <button type="button" @click="detailTab = 'structure'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer shrink-0 btn-tactile"
                    :class="detailTab === 'structure' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#1e1c1a] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                {{ __('Cấu tạo') }}
            </button>
            <button type="button" @click="detailTab = 'meaning_usage'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer shrink-0 btn-tactile"
                    :class="detailTab === 'meaning_usage' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#1e1c1a] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                {{ __('Nghĩa & cách dùng') }}
            </button>
            <button type="button" @click="detailTab = 'map'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer shrink-0 btn-tactile"
                    :class="detailTab === 'map' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#1e1c1a] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                {{ __('Bản đồ') }}
            </button>
            <button type="button" @click="detailTab = 'sino_viet'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer shrink-0 btn-tactile"
                    :class="detailTab === 'sino_viet' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#1e1c1a] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                {{ __('Hán - Việt') }}
            </button>
            <button type="button" @click="detailTab = 'mnemonic'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer shrink-0 btn-tactile"
                    :class="detailTab === 'mnemonic' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#1e1c1a] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                {{ __('Mẹo cộng đồng') }}
            </button>
            <button type="button" @click="detailTab = 'naming'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer shrink-0 btn-tactile"
                    :class="detailTab === 'naming' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#1e1c1a] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                {{ __('Đặt tên') }}
            </button>
        </div>

        <!-- 2 CỘT NỘI DUNG CHÍNH (THEME XIAOMU LMS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- CỘT 1 (BÊN TRÁI): THỨ TỰ VIẾT NÉT CHỮ & KHUNG LUYỆN TẬP (5 COLS) -->
            <div class="lg:col-span-5 space-y-4">
                <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-4 shadow-xs">
                    
                    <!-- Header: Thứ tự viết nét chữ & Tuỳ chỉnh nâng cao -->
                    <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Thứ tự viết nét chữ') }}</h4>
                        <button type="button" @click="showRulesModal = true" 
                                class="text-xs text-slate-500 hover:text-[#e07a5f] dark:text-slate-400 dark:hover:text-[#e07a5f] transition-colors flex items-center gap-1 cursor-pointer">
                            <i class="fa-solid fa-gear text-[11px]"></i>
                            <span>{{ __('Tuỳ chỉnh nâng cao') }}</span>
                        </button>
                    </div>

                    <!-- Khung mô phỏng nét viết chữ Hán -->
                    <div class="relative w-48 h-48 sm:w-56 sm:h-56 mx-auto rounded-2xl bg-[#fffaf5] dark:bg-[#1c1816] border-2 border-[#e8e2d9] dark:border-[#382823] flex items-center justify-center overflow-hidden shadow-inner group select-none">
                        <!-- Đường kẻ ô mễ mờ sắc terracotta -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-25 dark:opacity-20" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0" y1="0" x2="100%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="100%" y1="0" x2="0" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                        </svg>

                        <!-- Chữ mờ nền -->
                        <div class="absolute inset-0 flex items-center justify-center text-7xl sm:text-8xl font-bold text-slate-900/10 dark:text-white/10 zh-text select-none">
                            <span x-text="currentCharacterData ? currentCharacterData.char : (currentChar ? currentChar.character : '')"></span>
                        </div>

                        <!-- Chữ hiển thị nét viết -->
                        <div class="relative z-10 text-7xl sm:text-8xl font-bold text-slate-800 dark:text-slate-100 zh-text select-none transition-all duration-300"
                             :class="isPlayingStrokes ? 'scale-105 text-[#e07a5f] dark:text-[#e07a5f]' : ''">
                            <span x-text="currentCharacterData ? currentCharacterData.char : (currentChar ? currentChar.character : '')"></span>
                        </div>
                    </div>

                    <!-- 2 Nút hành động: [▶ Xem viết] & [✎ Luyện tập] -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <!-- Nút Xem viết -->
                        <button type="button" @click="toggleStrokeAutoPlay()" 
                                class="py-2.5 px-4 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] hover:bg-[#f1ede7] dark:hover:bg-[#2d2926] border border-[#e8e2d9] dark:border-[#38332f] text-slate-700 dark:text-slate-200 hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer shadow-2xs btn-tactile">
                            <i class="fa-solid text-[11px] text-[#e07a5f]" :class="isPlayingStrokes ? 'fa-pause' : 'fa-play'"></i>
                            <span x-text="isPlayingStrokes ? '{{ __('Tạm dừng') }}' : '{{ __('Xem viết') }}'"></span>
                        </button>

                        <!-- Nút Luyện tập (Terracotta Nổi bật) -->
                        <button type="button" @click="activeTab = 'stroke_studio'" 
                                class="py-2.5 px-4 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer btn-tactile">
                            <i class="fa-solid fa-pencil text-[11px]"></i>
                            <span>{{ __('Luyện tập') }}</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- CỘT 2 (BÊN PHẢI): BỘ THỦ, THÀNH PHẦN & LOẠI TỪ CÁCH DÙNG (7 COLS) -->
            <div class="lg:col-span-7 space-y-4">
                
                <!-- Card 1: Bộ thủ & thành phần -->
                <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3 shadow-xs">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Bộ thủ & thành phần') }}</h4>
                    
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f]">
                            {{ __('BỘ THỦ:') }} <span class="zh-text text-sm ml-1" x-text="currentRadicalData ? currentRadicalData.radical : ''"></span>
                        </span>
                        <span class="px-3 py-1 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#38332f] text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ __('SỐ NÉT:') }} <span class="text-[#e07a5f] ml-1" x-text="(currentRadicalData?.stroke_count || 1) + ' nét'"></span>
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-normal pt-1" 
                       x-text="currentRadicalData ? (currentRadicalData.description || currentRadicalData.meaning) : ''"></p>
                </div>

                <!-- Card 2: Loại từ & cách dùng -->
                <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3 shadow-xs">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Loại từ & cách dùng') }}</h4>
                        <span class="px-2.5 py-0.5 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-[11px] font-bold"
                              x-text="currentRadicalData ? (currentRadicalData.word_type || 'Danh từ 名词') : 'Danh từ 名词'"></span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <p class="leading-relaxed font-normal" 
                           x-text="currentRadicalData ? (currentRadicalData.word_type_desc || currentRadicalData.description) : 'Tên gọi của một nét viết; dùng làm bộ thủ chứ không đứng riêng thành từ.'"></p>
                    </div>

                    <div class="pt-2 text-xs space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block" x-text="currentRadicalData ? (currentRadicalData.word_type || 'Danh từ 名词') : 'Danh từ 名词'"></span>
                        <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-[11px]">
                            {{ __('Chỉ sự vật, nơi chốn hoặc đặc trưng cấu tạo chữ trong Hán ngữ.') }}
                        </p>
                    </div>
                </div>

                <!-- Card 3: Thơ Mẹo Nhớ Dân Gian (nếu có) -->
                <template x-if="currentChar && currentChar.mnemonic_poem">
                    <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 shadow-xs">
                        <h4 class="text-sm font-bold text-[#e07a5f] flex items-center gap-2">
                            <i class="fa-solid fa-scroll text-xs"></i>
                            <span>{{ __('Thơ Mẹo Nhớ Dân Gian') }}</span>
                        </h4>
                        <div class="p-3.5 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 italic leading-relaxed whitespace-pre-line" 
                               x-text="currentChar.mnemonic_poem"></p>
                        </div>
                    </div>
                </template>

            </div>

        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- TAB LUYỆN VIẾT CANVAS TƯƠNG TÁC (HANDWRITING STUDIO) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'stroke_studio'" class="space-y-4" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <div class="lg:col-span-7 space-y-3">
                <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-4">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Bảng Luyện Viết Ô Mễ') }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold" x-text="'Đang viết: ' + (currentChar?.character || '') + ' (' + (currentChar?.sino_vietnamese || '') + ')'"></span>
                        </div>

                        <button type="button" @click="showTraceGuide = !showTraceGuide"
                                class="px-2.5 py-1 rounded-lg text-xs font-semibold border transition-all btn-tactile cursor-pointer"
                                :class="showTraceGuide ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] text-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-500'">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1"></i>
                            <span x-text="showTraceGuide ? '{{ __('Tắt nét mờ') }}' : '{{ __('Bật nét mờ') }}'"></span>
                        </button>
                    </div>

                    <div class="relative w-full aspect-square max-w-md mx-auto rounded-2xl bg-[#fffaf5] dark:bg-[#1f1c1a] border-2 border-[#e8e2d9] dark:border-[#382823] flex items-center justify-center overflow-hidden shadow-inner select-none touch-none">
                        <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-25 dark:opacity-20" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0" y1="0" x2="100%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="100%" y1="0" x2="0" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                        </svg>

                        <div x-show="showTraceGuide && currentChar" 
                             class="absolute inset-0 flex items-center justify-center text-8xl sm:text-9xl font-bold text-slate-900/10 dark:text-white/10 zh-text pointer-events-none select-none">
                            <span x-text="currentChar.character"></span>
                        </div>

                        <canvas id="hanziWritingCanvas" 
                                class="relative z-10 w-full h-full cursor-crosshair"
                                @mousedown="startDrawing($event)"
                                @mousemove="draw($event)"
                                @mouseup="stopDrawing()"
                                @mouseleave="stopDrawing()"
                                @touchstart.prevent="startDrawing($event)"
                                @touchmove.prevent="draw($event)"
                                @touchend.prevent="stopDrawing()"></canvas>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400 font-semibold">{{ __('Mực:') }}</span>
                            <div class="flex items-center gap-1.5">
                                <template x-for="c in brushColors" :key="c.hex">
                                    <button type="button" 
                                            @click="setBrushColor(c.hex)" 
                                            :title="c.name"
                                            class="w-6 h-6 rounded-full transition-all btn-tactile cursor-pointer border-2"
                                            :class="brushColor === c.hex && !isEraser ? 'ring-2 ring-offset-2 ring-[#e07a5f] scale-110' : 'border-transparent'"
                                            :style="'background-color: ' + c.hex"></button>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-slate-400 font-semibold">{{ __('Nét:') }}</span>
                            <button type="button" @click="setBrushSize(4)" class="px-2 py-1 rounded-md text-xs font-bold border btn-tactile" :class="brushSize === 4 ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">{{ __('Mảnh') }}</button>
                            <button type="button" @click="setBrushSize(8)" class="px-2 py-1 rounded-md text-xs font-bold border btn-tactile" :class="brushSize === 8 ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">{{ __('Vừa') }}</button>
                            <button type="button" @click="setBrushSize(14)" class="px-2 py-1 rounded-md text-xs font-bold border btn-tactile" :class="brushSize === 14 ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">{{ __('Đậm') }}</button>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="toggleEraser()" :class="isEraser ? 'bg-[#e07a5f] text-white' : 'bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300'" class="px-2.5 py-1.5 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold btn-tactile" title="{{ __('Tẩy nét') }}">
                                <i class="fa-solid fa-eraser"></i>
                            </button>
                            <button type="button" @click="undoCanvas()" class="px-2.5 py-1.5 rounded-lg bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold btn-tactile" title="{{ __('Hoàn tác nét vừa vẽ') }}">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                            <button type="button" @click="clearCanvas()" class="px-2.5 py-1.5 rounded-lg bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold btn-tactile" title="{{ __('Xóa toàn bộ để viết lại') }}">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2">
                        <button type="button" @click="checkHandwriting()" 
                                class="w-full py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2 btn-tactile cursor-pointer">
                            <i class="fa-solid fa-stamp"></i>
                            <span>{{ __('Chấm bài viết & Nhận xét thần thái nét') }}</span>
                        </button>

                        <div x-show="feedbackMessage" x-cloak 
                             class="p-3 rounded-xl border text-xs font-semibold text-center transition-all"
                             :class="feedbackType === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300'">
                            <span x-text="feedbackMessage"></span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-5 space-y-4">
                <template x-if="currentChar">
                    <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-list-ol text-[#e07a5f]"></i>
                                {{ __('Trình tự thuận bút chữ') }} <span class="zh-text font-bold text-sm text-[#e07a5f]" x-text="currentChar.character"></span>
                            </span>
                            <span class="text-[11px] text-slate-400" x-text="currentChar.stroke_count + ' nét'"></span>
                        </div>

                        <div class="space-y-1.5 max-h-[420px] overflow-y-auto no-scrollbar pr-1">
                            <template x-for="(st, sIdx) in currentChar.stroke_steps" :key="sIdx">
                                <div class="p-2.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-6 h-6 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold flex items-center justify-center text-xs" x-text="st.step"></span>
                                        <div>
                                            <span class="font-bold text-slate-800 dark:text-slate-100" x-text="st.name"></span>
                                            <span class="text-[11px] text-slate-400 block" x-text="st.highlight"></span>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-[#e07a5f]" x-text="st.pinyin"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB LUYỆN TẬP MINI QUIZ (QUIZ) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'quiz'" class="space-y-4" x-cloak>
        <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-5">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">{{ __('Thử Thách Giải Mã Chiết Tự & Bộ Thủ') }}</h3>
                    <p class="text-xs text-slate-400">{{ __('Áp dụng quy tắc bộ thủ để phân biệt các cặp chữ Hán dễ nhầm lẫn nhất') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-bold border border-[#fcdccf] dark:border-[#4a2e26]" x-text="'Điểm: ' + totalScore"></span>
                </div>
            </div>

            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                <button type="button" @click="quizMode = 'confusing_chars'" class="px-3 py-1.5 rounded-xl text-xs font-bold border transition-all btn-tactile cursor-pointer" :class="quizMode === 'confusing_chars' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600 dark:text-slate-400'">{{ __('1. Phân biệt chữ dễ lú') }}</button>
                <button type="button" @click="quizMode = 'predict_meaning'" class="px-3 py-1.5 rounded-xl text-xs font-bold border transition-all btn-tactile cursor-pointer" :class="quizMode === 'predict_meaning' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600 dark:text-slate-400'">{{ __('2. Đoán nghĩa qua bộ thủ') }}</button>
                <button type="button" @click="quizMode = 'find_typo'" class="px-3 py-1.5 rounded-xl text-xs font-bold border transition-all btn-tactile cursor-pointer" :class="quizMode === 'find_typo' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600 dark:text-slate-400'">{{ __('3. Bắt lỗi sai nét') }}</button>
            </div>

            <!-- DẠNG 1: PHÂN BIỆT CHỮ DỄ LÚ -->
            <div x-show="quizMode === 'confusing_chars'" class="space-y-4">
                <div class="p-4 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3">
                    <span class="text-xs font-bold text-[#e07a5f] uppercase tracking-wider block" x-text="confusingQuestions[confusingIndex].context"></span>
                    <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white" x-text="confusingQuestions[confusingIndex].prompt"></h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <template x-for="opt in confusingQuestions[confusingIndex].options" :key="opt.char">
                            <button type="button" 
                                    @click="answerConfusing(opt.char)"
                                    class="p-4 rounded-xl border text-center transition-all btn-tactile cursor-pointer flex items-center justify-between"
                                    :class="confusingAnswered === null 
                                        ? 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]' 
                                        : (opt.char === confusingQuestions[confusingIndex].correct 
                                            ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-400 text-emerald-700 dark:text-emerald-300 font-bold' 
                                            : (confusingAnswered === opt.char ? 'bg-red-50 dark:bg-red-950/40 border-red-400 text-red-700' : 'bg-white opacity-50'))">
                                <span class="text-3xl font-bold zh-text text-[#e07a5f]" x-text="opt.char"></span>
                                <div class="text-right">
                                    <span class="text-xs font-bold block" x-text="opt.label"></span>
                                    <span class="text-[11px] text-slate-400" x-text="opt.pinyin"></span>
                                </div>
                            </button>
                        </template>
                    </div>

                    <div x-show="confusingAnswered !== null" class="p-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 pt-2" x-cloak>
                        <div class="flex items-center gap-2 text-xs font-bold" :class="confusingAnswered === confusingQuestions[confusingIndex].correct ? 'text-emerald-600' : 'text-red-500'">
                            <i class="fa-solid" :class="confusingAnswered === confusingQuestions[confusingIndex].correct ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                            <span x-text="confusingAnswered === confusingQuestions[confusingIndex].correct ? '{{ __('Chính xác! Bạn ghi nhớ rất tốt.') }}' : '{{ __('Chưa chuẩn rồi! Hãy xem mẹo ghi nhớ bên dưới.') }}'"></span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed" x-text="confusingQuestions[confusingIndex].mnemonic"></p>
                        
                        <div class="pt-2 text-right">
                            <button type="button" @click="nextConfusing()" class="px-4 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs btn-tactile cursor-pointer">
                                {{ __('Câu kế tiếp') }} <i class="fa-solid fa-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DẠNG 2: ĐOÁN NGHĨA QUA BỘ THỦ -->
            <div x-show="quizMode === 'predict_meaning'" class="space-y-4" x-cloak>
                <div class="p-4 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#e07a5f] uppercase tracking-wider" x-text="predictQuestions[predictIndex].components"></span>
                        <span class="text-2xl font-bold zh-text text-[#e07a5f]" x-text="predictQuestions[predictIndex].unknownChar + ' (' + predictQuestions[predictIndex].pinyin + ')'"></span>
                    </div>

                    <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white" x-text="predictQuestions[predictIndex].question"></h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <template x-for="opt in predictQuestions[predictIndex].options" :key="opt">
                            <button type="button" 
                                    @click="answerPredict(opt)"
                                    class="p-3 rounded-xl border text-left text-xs font-semibold transition-all btn-tactile cursor-pointer"
                                    :class="predictAnswered === null 
                                        ? 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]' 
                                        : (opt === predictQuestions[predictIndex].correct 
                                            ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-400 text-emerald-700 font-bold' 
                                            : (predictAnswered === opt ? 'bg-red-50 border-red-400 text-red-700' : 'bg-white opacity-50'))"
                                    x-text="opt">
                            </button>
                        </template>
                    </div>

                    <div x-show="predictAnswered !== null" class="p-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 pt-2" x-cloak>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed" x-text="predictQuestions[predictIndex].explanation"></p>
                        <div class="pt-2 text-right">
                            <button type="button" @click="nextPredict()" class="px-4 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs btn-tactile cursor-pointer">
                                {{ __('Câu kế tiếp') }} <i class="fa-solid fa-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DẠNG 3: BẮT LỖI SAI NÉT -->
            <div x-show="quizMode === 'find_typo'" class="space-y-4" x-cloak>
                <div class="p-4 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3">
                    <span class="text-xs font-bold text-[#e07a5f] uppercase tracking-wider block" x-text="'Từ cần điền: ' + typoQuestions[typoIndex].wordNeeded"></span>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white" x-text="typoQuestions[typoIndex].sentence"></h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 zh-text" x-text="typoQuestions[typoIndex].sentenceZh"></p>

                    <div class="grid grid-cols-3 gap-3 pt-2">
                        <template x-for="(ch, cIdx) in typoQuestions[typoIndex].options" :key="cIdx">
                            <button type="button" 
                                    @click="answerTypo(cIdx)"
                                    class="p-4 rounded-xl border text-center transition-all btn-tactile cursor-pointer"
                                    :class="typoAnswered === null 
                                        ? 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]' 
                                        : (cIdx === typoQuestions[typoIndex].correctIndex 
                                            ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-400 text-emerald-700 font-bold' 
                                            : (typoAnswered === cIdx ? 'bg-red-50 border-red-400 text-red-700' : 'bg-white opacity-50'))">
                                <span class="text-3xl font-bold zh-text text-[#e07a5f]" x-text="ch"></span>
                            </button>
                        </template>
                    </div>

                    <div x-show="typoAnswered !== null" class="p-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 pt-2" x-cloak>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed" x-text="typoQuestions[typoIndex].explanation"></p>
                        <div class="pt-2 text-right">
                            <button type="button" @click="nextTypo()" class="px-4 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs btn-tactile cursor-pointer">
                                {{ __('Câu kế tiếp') }} <i class="fa-solid fa-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB BẢNG 214 BỘ THỦ KHANG HY (RADICALS REFERENCE) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'radicals'" class="space-y-4" x-cloak>
        <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">{{ __('Bảng 214 Bộ Thủ Khang Hy') }}</h3>
                    <p class="text-xs text-slate-400">{{ __('Bấm vào bộ thủ để nhảy sang khám phá các chữ Hán thuộc bộ đó') }}</p>
                </div>
                <div class="w-full sm:w-60">
                    <input type="text" x-model="radicalSearch" placeholder="{{ __('Tìm tên bộ, pinyin, nghĩa...') }}" 
                           class="w-full px-3 py-2 text-xs rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-white focus:outline-hidden focus:border-[#e07a5f]">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                <template x-for="(r, rIdx) in filteredRadicals" :key="rIdx">
                    <div class="p-2.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] transition-all flex items-center justify-between cursor-pointer btn-tactile group"
                         @click="jumpToRadicalFrom214(r)">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="text-2xl font-bold zh-text text-[#e07a5f] group-hover:scale-110 transition-transform shrink-0" x-text="r.radical"></span>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-900 dark:text-white block truncate" x-text="r.sino_vietnamese"></span>
                                <span class="text-[10px] text-slate-400 block truncate" x-text="r.meaning"></span>
                            </div>
                        </div>
                        <template x-if="r.has_group">
                            <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold shrink-0">
                                {{ __('Chiết tự') }}
                            </span>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL POPUP: 7 QUY TẮC VÀNG VIẾT CHỮ HÁN -->
    <!-- ========================================================================= -->
    <div x-show="showRulesModal" 
         x-cloak
         @keydown.escape.window="showRulesModal = false"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
         role="dialog" 
         aria-modal="true">
        
        <div x-show="showRulesModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showRulesModal = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>

        <div x-show="showRulesModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="relative w-full max-w-2xl bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-5 sm:p-6 space-y-5 z-10 my-8">
            
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

                <button type="button" @click="showRulesModal = false" 
                        class="w-8 h-8 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] hover:bg-[#e8e2d9] dark:hover:bg-[#2d2926] text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center text-xs transition-colors btn-tactile cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[60vh] overflow-y-auto no-scrollbar pr-1">
                <template x-for="rule in strokeRulesGuide" :key="rule.id">
                    <div class="p-3.5 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] transition-all space-y-1.5 flex flex-col justify-between">
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

