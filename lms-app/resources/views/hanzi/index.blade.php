@extends('layouts.lms')
@section('title', __('Chiết Tự Chữ Hán Theo Bộ Thủ & Cấp Độ HSK - Tiếng Trung XiaoMu'))

@section('header-left')
    <div x-data="{ step: 1, viewMode: 'radicals', fromViewMode: 'radicals', hskLevel: 1, radical: '', radicalName: '', char: '' }"
         @hanzi-nav-changed.window="step = $event.detail.step; viewMode = $event.detail.viewMode || 'radicals'; fromViewMode = $event.detail.fromViewMode || 'radicals'; hskLevel = $event.detail.hskLevel || 1; radical = $event.detail.radical; radicalName = $event.detail.radicalName; char = $event.detail.char"
         class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
        
        <!-- Cấp gốc: Chiết tự Chữ Hán -->
        <a href="{{ route('hanzi.index') }}" 
           @click.prevent="window.dispatchEvent(new CustomEvent('hanzi-go-step', { detail: { step: 1, reset: true } }))"
           class="hover:text-[#e07a5f] transition-colors shrink-0"
           :class="step === 1 && fromViewMode !== 'hsk' ? 'text-slate-800 dark:text-slate-200 font-bold' : ''">
            {{ __('Chiết tự Chữ Hán') }}
        </a>

        <!-- Phân cấp Cấp độ HSK -->
        <template x-if="fromViewMode === 'hsk'">
            <div class="flex items-center gap-2">
                <span class="shrink-0 text-slate-400">/</span>
                <button type="button" 
                        @click="window.dispatchEvent(new CustomEvent('hanzi-go-step', { detail: { step: 1 } }))"
                        class="hover:text-[#e07a5f] transition-colors cursor-pointer shrink-0"
                        :class="step === 1 ? 'text-slate-800 dark:text-slate-200 font-bold' : ''">
                    <span x-text="'HSK ' + hskLevel"></span>
                </button>
            </div>
        </template>

        <!-- Phân cấp 214 Bộ thủ khi vào Step 2 hoặc Step 3 -->
        <template x-if="fromViewMode !== 'hsk' && step >= 2">
            <div class="flex items-center gap-2">
                <span class="shrink-0 text-slate-400">/</span>
                <button type="button" 
                        @click="window.dispatchEvent(new CustomEvent('hanzi-go-step', { detail: { step: 1, reset: true } }))"
                        class="hover:text-[#e07a5f] transition-colors cursor-pointer shrink-0">
                    <span>{{ __('214 Bộ thủ') }}</span>
                </button>
            </div>
        </template>

        <!-- Chi tiết Bộ thủ (Step 2) -->
        <template x-if="step === 2 && radical && fromViewMode !== 'hsk'">
            <div class="flex items-center gap-2">
                <span class="shrink-0 text-slate-400">/</span>
                <span class="text-[#e07a5f] font-bold">
                    <span>{{ __('Bộ ') }}</span><span class="zh-text text-sm font-bold" x-text="radical"></span>
                </span>
            </div>
        </template>

        <!-- Chi tiết Bộ thủ khi ở Step 3 (có thể click để quay lại Step 2) -->
        <template x-if="step === 3 && radical && fromViewMode !== 'hsk'">
            <div class="flex items-center gap-2">
                <span class="shrink-0 text-slate-400">/</span>
                <button type="button" 
                        @click="window.dispatchEvent(new CustomEvent('hanzi-go-step', { detail: { step: 2 } }))"
                        class="hover:text-[#e07a5f] transition-colors cursor-pointer shrink-0">
                    <span>{{ __('Bộ ') }}</span><span class="zh-text text-sm font-bold" x-text="radical"></span>
                </button>
            </div>
        </template>

        <!-- Chi tiết Chữ Hán (Step 3) -->
        <template x-if="step === 3 && char">
            <div class="flex items-center gap-2">
                <span class="shrink-0 text-slate-400">/</span>
                <span class="text-[#e07a5f] font-bold zh-text text-base" x-text="char"></span>
            </div>
        </template>
    </div>
@endsection

@section('content')
<div x-data="hanziDecompositionApp({ radicalGroups: {{ json_encode($radicalGroups) }}, radicals: {{ json_encode($demoRadicals) }}, radicals214: {{ json_encode($radicals214) }}, hskGroups: {{ json_encode($hskGroups) }} })" 
     class="max-w-6xl mx-auto space-y-5">

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 1: THEO BỘ THỦ & THEO CẤP ĐỘ HSK (BIẾN TẤU CHUẨN XIAOMU LMS) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && step === 1" class="space-y-5" x-cloak>
        
        <!-- HERO BANNER TỔNG QUAN PHÂN HỆ CHIẾT TỰ (CHUẨN THEME XIAOMU LMS) -->
        <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] border border-[#fcdccf] dark:border-[#3d2822] rounded-3xl relative overflow-hidden group shadow-xs">
            <!-- Faint Watermark Hanzi -->
            <div class="absolute right-4 -bottom-6 text-9xl font-extrabold text-[#e07a5f]/5 pointer-events-none select-none zh-text">
                字
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative z-10">
                <!-- Cụm thông tin giới thiệu -->
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-xs font-bold shadow-2xs">
                        <i class="fa-solid fa-shapes text-[#e07a5f]"></i>
                        <span>{{ __('Chiết tự, 214 Bộ thủ & 6 Cấp độ HSK') }}</span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                        {{ __('Tra Cứu Chiết Tự & Luyện Viết Hán Tự') }}
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Giải mã cấu tạo 2.662+ chữ Hán theo 214 bộ thủ Khang Hy và phân chia theo cấp độ HSK 1 - HSK 6, hiểu sâu bản chất tự nguyên và thuận bút chuẩn.') }}
                    </p>
                </div>

                <!-- Thống kê nhanh & CTA Pills -->
                <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                    <div class="px-3.5 py-2 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] text-center shadow-2xs min-w-[76px]">
                        <span class="text-base font-bold text-[#e07a5f] block">214</span>
                        <span class="text-[10px] text-slate-400 font-medium uppercase">{{ __('Bộ thủ') }}</span>
                    </div>
                    <div class="px-3.5 py-2 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] text-center shadow-2xs min-w-[76px]">
                        <span class="text-base font-bold text-slate-800 dark:text-slate-100 block" x-text="totalHskCharactersCount"></span>
                        <span class="text-[10px] text-slate-400 font-medium uppercase">{{ __('Hán tự') }}</span>
                    </div>
                    <div class="px-3.5 py-2 rounded-2xl bg-white/90 dark:bg-[#231e1a]/90 border border-[#fcdccf] dark:border-[#3d2822] text-center shadow-2xs min-w-[76px]">
                        <span class="text-base font-bold text-slate-800 dark:text-slate-100 block">7</span>
                        <span class="text-[10px] text-slate-400 font-medium uppercase">{{ __('Quy tắc') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KHỐI 6 CARD CẤP ĐỘ HSK TRÊN CÙNG (THU NHỎ GỌN GÀNG, TINH TẾ) -->
        <div class="space-y-1.5">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-2.5">
                <template x-for="stat in hskStats" :key="stat.level">
                    <div @click="hskFilter === stat.level ? setHskFilter('all') : setHskFilter(stat.level)"
                         class="py-2 px-2.5 sm:py-2 sm:px-3 rounded-xl border text-center cursor-pointer transition-all duration-200 shadow-2xs btn-tactile flex flex-col justify-center items-center gap-0.5 group"
                         :class="hskFilter === stat.level 
                             ? 'bg-[#e07a5f] border-[#e07a5f] text-white shadow-xs scale-[1.02]' 
                             : 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:bg-[#fff9f7] dark:hover:bg-[#201a18]'">
                        <span class="text-xs sm:text-sm font-bold tracking-tight" 
                              :class="hskFilter === stat.level ? 'text-white' : 'text-slate-800 dark:text-slate-100 group-hover:text-[#e07a5f]'" 
                              x-text="stat.name"></span>
                        <span class="text-[10px] sm:text-[11px] font-medium opacity-80" 
                              :class="hskFilter === stat.level ? 'text-white/90' : 'text-slate-500 dark:text-slate-400'"
                              x-text="stat.count + ' {{ __('chữ') }}'"></span>
                    </div>
                </template>
            </div>
            <div class="flex items-center justify-between text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 font-medium px-0.5">
                <span>{{ __('Hiện hỗ trợ HSK 1–6. HSK 7–9 đang được bổ sung.') }}</span>
                <template x-if="hskFilter !== 'all'">
                    <button type="button" @click="setHskFilter('all')" class="text-[#e07a5f] hover:underline font-semibold flex items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i>
                        <span x-text="activeMainTab === 'radicals' ? '{{ __('Xem tất cả 214 bộ thủ') }}' : '{{ __('Xem tất cả cấp độ HSK') }}'"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- TAB SWITCHER: [THEO BỘ THỦ] [TỪ ĐIỂN HSK] & ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
            <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xs w-fit">
                <button type="button" 
                        @click="activeMainTab = 'radicals'"
                        class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer btn-tactile flex items-center gap-1.5"
                        :class="activeMainTab === 'radicals' 
                            ? 'bg-[#23201e] dark:bg-white text-white dark:text-slate-900 shadow-xs' 
                            : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    <i class="fa-solid fa-shapes text-xs"></i>
                    <span>{{ __('Theo bộ thủ') }}</span>
                </button>
                <button type="button" 
                        @click="activeMainTab = 'dictionary'"
                        class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer btn-tactile flex items-center gap-1.5"
                        :class="activeMainTab === 'dictionary' 
                            ? 'bg-[#23201e] dark:bg-white text-white dark:text-slate-900 shadow-xs' 
                            : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    <i class="fa-solid fa-book-bookmark text-xs"></i>
                    <span>{{ __('Từ điển HSK') }}</span>
                </button>
            </div>

            <!-- CÁC NÚT TIỆN ÍCH BỔ TRỢ DƯỚI BANNER -->
            <div class="flex items-center gap-2">
                <button type="button" @click="showRulesModal = true"
                        class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] hover:bg-[#fff7f4] dark:hover:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs btn-tactile">
                    <i class="fa-solid fa-graduation-cap text-[#e07a5f]"></i>
                    <span>{{ __('7 Quy tắc viết') }}</span>
                </button>
                <button type="button" @click="activeTab = 'stroke_studio'"
                        class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] hover:bg-[#fff7f4] dark:hover:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs btn-tactile"
                        :class="activeTab === 'stroke_studio' ? 'border-[#e07a5f] text-[#e07a5f] dark:text-[#e07a5f]' : ''">
                    <i class="fa-solid fa-pen-nib text-[#e07a5f]"></i>
                    <span>{{ __('Luyện viết') }}</span>
                </button>
                <button type="button" @click="activeTab = 'quiz'"
                        class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] hover:bg-[#fff7f4] dark:hover:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs btn-tactile"
                        :class="activeTab === 'quiz' ? 'border-[#e07a5f] text-[#e07a5f] dark:text-[#e07a5f]' : ''">
                    <i class="fa-solid fa-puzzle-piece text-[#e07a5f]"></i>
                    <span>{{ __('Luyện tập') }}</span>
                </button>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- VIEW 1: TAB THEO BỘ THỦ (214 BỘ THỦ KHANG HY / LỌC THEO HSK NẾU CÓ CHỌN) -->
        <!-- ========================================================================= -->
        <template x-if="activeMainTab === 'radicals'">
            <div class="space-y-4">
                <!-- Tiêu đề & Ô Tìm kiếm Bộ thủ -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                            <template x-if="hskFilter === 'all'">
                                <span>{{ __('Theo bộ thủ (214)') }}</span>
                            </template>
                            <template x-if="hskFilter !== 'all'">
                                <span class="flex items-center gap-1.5">
                                    <span>{{ __('Bộ thủ trong') }}</span>
                                    <span class="text-[#e07a5f]" x-text="'HSK ' + hskFilter"></span>
                                </span>
                            </template>
                        </h2>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500"
                                  x-text="hskFilter === 'all' ? '214 {{ __('bộ') }}' : (currentFilteredRadicalsCount + ' {{ __('bộ thủ') }} · ' + currentFilteredCharsCount + ' {{ __('chữ') }}')">
                            </span>
                            <template x-if="hskFilter !== 'all'">
                                <button type="button" @click="setHskFilter('all')" 
                                        class="px-2.5 py-1 text-[11px] rounded-xl bg-slate-100 dark:bg-[#23201e] hover:bg-[#e07a5f]/10 text-slate-600 dark:text-slate-400 hover:text-[#e07a5f] transition-all font-semibold cursor-pointer">
                                    <i class="fa-solid fa-xmark mr-1"></i>{{ __('Bỏ lọc HSK') }}
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="text" 
                               x-model.debounce.150ms="radicalSearchFilter"
                               :placeholder="hskFilter === 'all' ? '{{ __('Tìm bộ thủ, Hán - Việt, nghĩa...') }}' : '{{ __('Tìm bộ thủ hoặc chữ trong HSK...') }}'" 
                               class="w-full px-4 py-2.5 rounded-2xl text-xs sm:text-sm bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/15 shadow-2xs transition-all">
                        <i class="fa-solid fa-magnifying-glass absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- DANH SÁCH BỘ THỦ PHÂN THEO SỐ NÉT (1 nét (6), 2 nét (23), 3 nét (31)...) -->
                <div class="space-y-6 transition-opacity duration-150" :class="isFiltering ? 'opacity-40 scale-[0.99]' : 'opacity-100 scale-100'">
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

                                        <!-- Số chữ thuộc bộ ở dưới (VD: 8 chữ, 20 chữ hoặc 3 chữ) -->
                                        <span class="text-[10px] font-medium block truncate" 
                                              :class="hskFilter !== 'all' ? 'text-[#e07a5f] font-bold' : 'text-slate-400 dark:text-slate-500'"
                                              x-text="(hskFilter === 'all' ? (item.char_count || item.characters?.length || 0) : item.displayCharCount) + ' {{ __('chữ') }}'"></span>
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
                    <button type="button" @click="radicalSearchFilter = ''; setHskFilter('all');" class="px-3.5 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold cursor-pointer transition-all shadow-xs btn-tactile">
                        {{ __('Đặt lại bộ lọc') }}
                    </button>
                </div>
            </div>
        </template>

        <!-- ========================================================================= -->
        <!-- VIEW 2: TAB TỪ ĐIỂN HSK (DANH BẠ CHỮ HÁN THEO CẤP ĐỘ HSK 1 - 6) -->
        <!-- ========================================================================= -->
        <template x-if="activeMainTab === 'dictionary'">
            <div class="space-y-4">
                
                <!-- Breadcrumb điều hướng -->
                <nav class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                    <button type="button" @click="setHskFilter('all'); activeMainTab = 'radicals';" class="hover:text-[#e07a5f] transition-colors cursor-pointer">
                        {{ __('Theo bộ thủ') }}
                    </button>
                    <span>/</span>
                    <span class="text-slate-800 dark:text-slate-200 font-semibold" 
                          x-text="hskFilter === 'all' ? '{{ __('Tất cả cấp độ HSK (1 – 6)') }}' : ('HSK ' + hskFilter)"></span>
                </nav>

                <!-- Tiêu đề lớn & Số lượng chữ -->
                <div class="space-y-0.5">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight" 
                        x-text="hskFilter === 'all' ? '{{ __('Từ Điển Hán Tự HSK (1 – 6)') }}' : ('{{ __('Chữ Hán HSK ') }}' + hskFilter)"></h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium" 
                       x-text="currentHskGroup.count + ' {{ __('chữ') }}'"></p>
                </div>

                <!-- Thanh Sắp xếp & Ô Tìm kiếm chữ Hán trong HSK -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3.5 sm:p-4 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">
                    <!-- Sắp xếp -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold mr-1">{{ __('Sắp xếp:') }}</span>
                        
                        <button type="button" @click="hskSortType = 'stroke'" 
                                class="px-3.5 py-1 rounded-full text-xs font-semibold transition-all cursor-pointer btn-tactile"
                                :class="hskSortType === 'stroke' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#23201e] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            {{ __('Số nét') }}
                        </button>

                        <button type="button" @click="hskSortType = 'pinyin'" 
                                class="px-3.5 py-1 rounded-full text-xs font-semibold transition-all cursor-pointer btn-tactile"
                                :class="hskSortType === 'pinyin' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#23201e] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            {{ __('A → Z') }}
                        </button>

                        <button type="button" @click="hskSortType = 'frequency'" 
                                class="px-3.5 py-1 rounded-full text-xs font-semibold transition-all cursor-pointer btn-tactile"
                                :class="hskSortType === 'frequency' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#23201e] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            {{ __('Tần suất') }}
                        </button>
                    </div>

                    <!-- Ô Tìm kiếm Chữ Hán trong HSK này -->
                    <div class="relative w-full sm:w-72">
                        <input type="text" 
                               x-model.debounce.150ms="hskSearchFilter"
                               placeholder="{{ __('Tìm chữ, pinyin, Hán-Việt, nghĩa...') }}" 
                               class="w-full px-3.5 py-1.5 pr-8 rounded-xl text-xs bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/15 shadow-2xs transition-all">
                        <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-[11px] text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- LƯỚI CARD CHỮ HÁN THEO CẤP ĐỘ HSK (DÙNG PAGINATED ĐỂ TỐI ƯU HIỆU NĂNG) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    <template x-for="charItem in paginatedHskCharacters" :key="charItem.char">
                        <div class="p-3.5 rounded-2xl bg-white dark:bg-[#181615] hover:bg-[#fff9f7] dark:hover:bg-[#201a18] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:shadow-md flex items-center justify-between gap-3.5 cursor-pointer group transition-all duration-200 shadow-2xs btn-tactile"
                             @click="chooseCharacterFromHsk(charItem)">
                            
                            <!-- Ký tự chữ to bên trái -->
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="text-3xl sm:text-4xl font-bold zh-text text-slate-800 dark:text-slate-100 group-hover:text-[#e07a5f] group-hover:scale-105 transition-all shrink-0" 
                                      x-text="charItem.char"></span>

                                <!-- Cột thông tin ở giữa: Pinyin + Âm Hán-Việt + Nghĩa -->
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-bold text-[#e07a5f] block truncate" x-text="charItem.pinyin"></span>
                                    <span class="text-xs text-slate-600 dark:text-slate-300 block truncate mt-0.5" x-text="charItem.meaning"></span>
                                </div>
                            </div>

                            <!-- Cột bên phải: Số nét nhỏ gọn -->
                            <div class="text-right shrink-0">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium" 
                                      x-text="(charItem.stroke_count || 1) + ' {{ __('nét') }}'"></span>
                            </div>
                        </div>
                    </template>

                    <!-- Trạng thái trống nếu không tìm thấy chữ trong HSK -->
                    <div x-show="filteredHskCharacters.length === 0" class="col-span-full p-8 text-center bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 shadow-xs">
                        <i class="fa-solid fa-filter text-2xl text-slate-400"></i>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Không tìm thấy chữ Hán nào phù hợp.') }}</p>
                        <button type="button" @click="hskSearchFilter = ''" class="px-3.5 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold cursor-pointer transition-all shadow-xs btn-tactile">
                            {{ __('Đặt lại tìm kiếm') }}
                        </button>
                    </div>
                </div>

                <!-- Nút Tải thêm khi danh sách dài (HSK 4, 5, 6) -->
                <template x-if="filteredHskCharacters.length > paginatedHskCharacters.length">
                    <div class="text-center pt-2">
                        <button type="button" @click="loadMoreHskChars()"
                                class="px-5 py-2.5 rounded-2xl bg-white dark:bg-[#181615] hover:bg-[#fff7f4] dark:hover:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] dark:hover:text-[#e07a5f] text-xs font-bold transition-all shadow-2xs btn-tactile">
                            <span>{{ __('Xem thêm 48 chữ') }}</span>
                            <span class="text-slate-400 font-normal ml-1" x-text="'(' + paginatedHskCharacters.length + '/' + filteredHskCharacters.length + ')'"></span>
                        </button>
                    </div>
                </template>
            </div>
        </template>

    </div>

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 2: CHỮ HÁN BỘ [X] (BIẾN TẤU CHUẨN XIAOMU LMS) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && step === 2" class="space-y-5" x-cloak>
        
        <!-- Tiêu đề & Nút quay lại -->
        <div class="space-y-1.5">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 pt-1">
                <div class="flex items-baseline gap-3 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>{{ __('Chữ Hán bộ') }}</span>
                        <span class="zh-text text-2xl sm:text-3xl text-[#e07a5f]" x-text="currentRadicalData ? currentRadicalData.radical : ''"></span>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400" x-text="currentRadicalData ? ('(' + currentRadicalData.sino_vietnamese + ' - ' + currentRadicalData.meaning + ')') : ''"></span>
                    </h1>
                    <template x-if="selectedHskFilterInRadical !== 'all'">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f]"
                              x-text="'HSK ' + selectedHskFilterInRadical"></span>
                    </template>
                </div>
                <!-- Nút quay lại danh sách bộ -->
                <button type="button" @click="goToStep1()" 
                        class="text-xs font-semibold text-slate-500 hover:text-[#e07a5f] dark:text-slate-400 dark:hover:text-[#e07a5f] transition-colors cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>{{ __('Quay lại danh sách bộ thủ') }}</span>
                </button>
            </div>
            
            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium">
                <span x-text="(sortedRadicalCharacters.length) + ' {{ __('chữ Hán') }}' + (selectedHskFilterInRadical !== 'all' ? (' HSK ' + selectedHskFilterInRadical) : ' {{ __('liên quan') }}')"></span>
            </div>
        </div>

        <!-- Thanh Công cụ GỌN GÀNG TRÊN 1 HÀNG DUY NHẤT: [Sắp xếp] & [Ô Tìm kiếm] -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3.5 sm:p-4 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">
            <!-- Sắp xếp -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold mr-1">{{ __('Sắp xếp:') }}</span>
                
                <button type="button" @click="sortType = 'stroke'" 
                        class="px-3.5 py-1 rounded-full text-xs transition-all cursor-pointer btn-tactile font-semibold"
                        :class="sortType === 'stroke' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#23201e] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    {{ __('Số nét') }}
                </button>

                <button type="button" @click="sortType = 'frequency'" 
                        class="px-3.5 py-1 rounded-full text-xs transition-all cursor-pointer btn-tactile font-semibold"
                        :class="sortType === 'frequency' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f1ede7] dark:bg-[#23201e] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    {{ __('Độ phổ biến') }}
                </button>
            </div>

            <!-- Ô Tìm kiếm Chữ Hán trong Bộ này -->
            <div class="relative w-full sm:w-64">
                <input type="text" 
                       x-model.debounce.150ms="charSearchFilter"
                       placeholder="{{ __('Tìm chữ, pinyin, nghĩa...') }}" 
                       class="w-full px-3.5 py-1.5 pr-8 rounded-xl text-xs bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/15 shadow-2xs transition-all">
                <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-[11px] text-slate-400 pointer-events-none"></i>
            </div>
        </div>

        <!-- LƯỚI CARD CHỮ HÁN DẠNG NẰM NGANG (THEME XIAOMU LMS) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            <template x-for="charItem in sortedRadicalCharacters" :key="charItem.char">
                <div class="p-3.5 rounded-2xl bg-white dark:bg-[#181615] hover:bg-[#fff9f7] dark:hover:bg-[#201a18] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:shadow-md flex items-center justify-between gap-3.5 cursor-pointer group transition-all duration-200 shadow-2xs btn-tactile"
                     @click="chooseCharacter214(charItem)">
                    
                    <!-- Cụm ký tự chữ to bên trái -->
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-3xl sm:text-4xl font-bold zh-text text-slate-800 dark:text-slate-100 group-hover:text-[#e07a5f] group-hover:scale-105 transition-all shrink-0" 
                              x-text="charItem.char"></span>

                        <!-- Cột thông tin bên phải: Pinyin ở trên, Nghĩa ở dưới -->
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-[#e07a5f] block" x-text="charItem.pinyin"></span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 block truncate mt-0.5" x-text="charItem.meaning"></span>
                        </div>
                    </div>

                    <!-- Badge HSK bên phải: Chỉ hiện khi đang ở chế độ xem 'Tất cả' để tránh lặp UI -->
                    <template x-if="selectedHskFilterInRadical === 'all' && charItem.hsk">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] shrink-0"
                              x-text="'HSK ' + charItem.hsk"></span>
                    </template>
                </div>
            </template>

            <!-- Trạng thái trống nếu không tìm thấy chữ -->
            <div x-show="sortedRadicalCharacters.length === 0" class="col-span-full p-8 text-center bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] space-y-2 shadow-xs">
                <i class="fa-solid fa-filter text-2xl text-slate-400"></i>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Không tìm thấy chữ Hán nào phù hợp trong bộ này.') }}</p>
                <button type="button" @click="charSearchFilter = ''; selectedHskFilterInRadical = 'all'" class="px-3.5 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold cursor-pointer transition-all shadow-xs btn-tactile">
                    {{ __('Đặt lại bộ lọc') }}
                </button>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 3: CHI TIẾT CHỮ HÁN (HERO BANNER & CỘT NỘI DUNG XIAOMU LMS) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && step === 3" class="space-y-4" x-cloak>
        
        <!-- Nút quay lại danh sách chữ hoặc HSK (Căn bên trái) -->
        <div class="flex items-center justify-start text-xs text-slate-500 dark:text-slate-400">
            <button type="button" @click="goToStep2()" class="hover:text-[#e07a5f] transition-colors cursor-pointer flex items-center gap-1.5 font-semibold">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <template x-if="fromViewMode === 'hsk'">
                    <span>{{ __('Quay lại danh sách HSK ') }}<strong x-text="selectedHskLevel"></strong></span>
                </template>
                <template x-if="fromViewMode !== 'hsk'">
                    <span>{{ __('Danh sách chữ bộ ') }}<strong x-text="currentRadicalData ? currentRadicalData.radical : ''"></strong></span>
                </template>
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
                <!-- Cụm bên trái: Chữ to + Nút Loa Edge-TTS + 2 Ô Thông tin + Dải tags -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                    <!-- Ký tự chữ Hán siêu to màu Terracotta kèm Nút phát âm trực tiếp bên cạnh -->
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="text-7xl sm:text-8xl font-bold zh-text text-[#e07a5f] select-none drop-shadow-xs"
                             x-text="currentCharacterData ? currentCharacterData.char : (currentChar ? currentChar.character : '')">
                        </div>

                        <!-- Nút Phát âm Edge-TTS ngay cạnh Chữ Hán -->
                        <button type="button" @click="speak(currentCharacterData ? currentCharacterData.char : (currentChar ? currentChar.character : ''))"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] hover:bg-[#e07a5f] hover:text-white border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] font-bold flex items-center justify-center shadow-2xs transition-all cursor-pointer btn-tactile"
                                :class="{ '!bg-[#e07a5f] !text-white ring-2 ring-[#e07a5f]/50 scale-95': isPlayingAudio }"
                                title="{{ __('Phát âm AI Edge-TTS') }}">
                            <i class="fa-solid fa-volume-high text-xs" :class="{ 'animate-pulse': isPlayingAudio }"></i>
                        </button>
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

                        <!-- Dải metadata tags: [HSK X] [1 chữ] [# X nét] [Thanh điệu: X] -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs text-[#e07a5f] font-bold shadow-2xs"
                                  x-text="'HSK ' + (currentCharacterData?.hsk || currentChar?.hsk_level || 1)"></span>
                            <span class="px-3 py-1 rounded-full bg-white dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs text-slate-700 dark:text-slate-200 font-semibold shadow-2xs"
                                  x-text="'# ' + (currentCharacterData?.stroke_count || currentChar?.stroke_count || 1) + ' nét'"></span>
                            <span class="px-3 py-1 rounded-full bg-white dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs text-slate-700 dark:text-slate-200 font-semibold shadow-2xs"
                                  x-text="'Thanh điệu: ' + (currentCharacterData?.tone || '1')"></span>
                        </div>
                    </div>
                </div>

                <!-- Cụm bên phải: Nút Yêu thích & Chia sẻ -->
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
                </div>
            </div>
        </div>

        <!-- 2 CỘT NỘI DUNG CHÍNH (THEME XIAOMU LMS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- CỘT 1 (BÊN TRÁI): THỨ TỰ VIẾT NÉT CHỮ & KHUNG LUYỆN TẬP (5 COLS) -->
            <div class="lg:col-span-5 space-y-4">
                <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-4 shadow-xs">
                    
                    <!-- Header: Thứ tự viết nét chữ & Quy tắc viết -->
                    <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Thứ tự viết nét chữ') }}</h4>
                        <button type="button" @click="showRulesModal = true" 
                                class="text-xs text-slate-500 hover:text-[#e07a5f] dark:text-slate-400 dark:hover:text-[#e07a5f] transition-colors flex items-center gap-1.5 cursor-pointer font-medium">
                            <i class="fa-solid fa-book-open text-[11px] text-[#e07a5f]"></i>
                            <span>{{ __('Quy tắc viết') }}</span>
                        </button>
                    </div>

                    <!-- Khung mô phỏng nét viết chữ Hán -->
                    <div class="relative w-48 h-48 sm:w-56 sm:h-56 mx-auto rounded-2xl bg-[#fffaf5] dark:bg-[#201c1a] border-2 border-[#e8e2d9] dark:border-[#3d2e29] flex items-center justify-center overflow-hidden shadow-inner group select-none">
                        <!-- Đường kẻ ô mễ mờ sắc terracotta -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-40 dark:opacity-50" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0" y1="0" x2="100%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="100%" y1="0" x2="0" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                        </svg>

                        <!-- Khung chứa Animation Vector Nét Viết HanziWriter -->
                        <div id="hanziStrokeAnimator" class="relative z-10 w-full h-full flex items-center justify-center select-none"></div>
                    </div>

                    <!-- 2 Nút hành động: [▶ Xem viết] & [✎ Luyện tập] -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <!-- Nút Xem viết -->
                        <button type="button" @click="toggleStrokeAutoPlay()" 
                                class="py-2.5 px-4 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] hover:bg-[#f1ede7] dark:hover:bg-[#2d2926] border border-[#e8e2d9] dark:border-[#38332f] text-slate-700 dark:text-slate-200 hover:text-[#e07a5f] text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer shadow-2xs btn-tactile">
                            <i x-show="isPlayingStrokes" class="fa-solid fa-pause text-[11px] text-[#e07a5f]"></i>
                            <i x-show="!isPlayingStrokes" class="fa-solid fa-play text-[11px] text-[#e07a5f]"></i>
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
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Bộ thủ & thành phần') }}</h4>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-xs font-bold text-[#e07a5f]">
                                {{ __('Bộ') }} <span class="zh-text text-sm ml-0.5" x-text="(currentChar?.radical?.radical || currentRadicalData?.radical || '')"></span>
                                <span class="capitalize ml-1" x-text="'(' + (currentChar?.radical?.sino_vietnamese || currentRadicalData?.sino_vietnamese || '') + ')'"></span>
                            </span>
                            <span class="px-2 py-1 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#38332f] text-xs font-semibold text-slate-600 dark:text-slate-300"
                                  x-text="(currentChar?.stroke_count || currentRadicalData?.stroke_count || 1) + ' {{ __('nét') }}'"></span>
                        </div>
                    </div>

                    <!-- Danh sách các thành phần chiết tự cấu tạo nên chữ (nếu có) -->
                    <template x-if="currentChar && currentChar.components && currentChar.components.length > 0">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                            <template x-for="(comp, cIdx) in currentChar.components" :key="cIdx">
                                <div class="p-3 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center gap-3">
                                    <span class="font-hanziSans text-2xl font-bold text-[#e07a5f] shrink-0 w-8 text-center" x-text="comp.character"></span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 capitalize" x-text="comp.name || comp.character"></span>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-semibold" 
                                                  x-show="comp.role" x-text="comp.role"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5" x-show="comp.meaning" x-text="comp.meaning"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Diễn giải quan hệ cấu tạo chữ nếu chưa có components chi tiết -->
                    <template x-if="!currentChar || !currentChar.components || currentChar.components.length === 0">
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-normal pt-1" 
                           x-text="currentChar?.radical_relation || currentChar?.formation_desc || currentRadicalData?.description || currentRadicalData?.meaning || ''"></p>
                    </template>
                </div>

                <!-- Card 2: Loại từ & cách dùng -->
                <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3 shadow-xs">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Loại từ & cách dùng') }}</h4>
                        
                        <!-- Danh sách các badge loại từ của chính chữ Hán -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <template x-if="currentChar && currentChar.word_types && currentChar.word_types.length > 0">
                                <template x-for="(wt, wtIdx) in currentChar.word_types" :key="wtIdx">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-[11px] font-bold"
                                          x-text="wt"></span>
                                </template>
                            </template>
                            <template x-if="!currentChar || !currentChar.word_types || currentChar.word_types.length === 0">
                                <span class="px-2.5 py-0.5 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-[11px] font-bold"
                                      x-text="currentRadicalData?.word_type || '{{ __('Đang cập nhật') }}'"></span>
                            </template>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs text-slate-600 dark:text-slate-300 space-y-2">
                        <p class="leading-relaxed font-normal" 
                           x-text="currentChar?.formation_desc || (currentRadicalData ? (currentRadicalData.word_type_desc || currentRadicalData.description) : '{{ __('Được sử dụng làm từ đơn hoặc ghép với các chữ Hán khác để tạo thành từ vựng.') }}')"></p>
                    </div>
                </div>

                <!-- Card 3: Mẹo nhớ Chữ Hán & Gương Hán - Việt -->
                <div class="p-5 rounded-3xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3 shadow-xs"
                     x-show="currentChar && (currentChar.mnemonic_tip || currentChar.sino_vietnamese_mirror || currentChar.mnemonic_poem)">
                    
                    <h4 class="text-sm font-bold text-[#e07a5f] flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs"></i>
                        <span>{{ __('Mẹo Nhớ Chữ Hán') }}</span>
                    </h4>

                    <!-- Mẹo nhớ thực tế theo chiết tự -->
                    <template x-if="currentChar && currentChar.mnemonic_tip">
                        <div class="p-3.5 rounded-2xl bg-[#fffcf7] dark:bg-[#23201e] border border-[#f3e8d9] dark:border-[#38332f]">
                            <p class="text-xs font-medium text-slate-800 dark:text-slate-200 leading-relaxed" 
                               x-text="currentChar.mnemonic_tip"></p>
                        </div>
                    </template>

                    <!-- Gương Hán - Việt (Từ ngữ tiếng Việt thân thuộc chứa chữ này) -->
                    <template x-if="currentChar && currentChar.sino_vietnamese_mirror">
                        <div class="p-3 rounded-2xl bg-[#fcfaf7] dark:bg-[#1e1c1b] border border-[#e8e2d9] dark:border-[#2d2926] flex items-start gap-2.5">
                            <span class="px-2 py-0.5 rounded-md bg-[#e07a5f]/10 text-[#e07a5f] text-[10px] font-bold shrink-0 mt-0.5">
                                {{ __('Gương Hán-Việt') }}
                            </span>
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium" 
                               x-text="currentChar.sino_vietnamese_mirror"></p>
                        </div>
                    </template>

                    <!-- Thơ lục bát (nếu có thơ chuẩn) -->
                    <template x-if="currentChar && currentChar.mnemonic_poem">
                        <div class="p-3 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 italic leading-relaxed whitespace-pre-line" 
                               x-text="currentChar.mnemonic_poem"></p>
                        </div>
                    </template>
                </div>

            </div>

        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- TAB LUYỆN VIẾT CANVAS TƯƠNG TÁC (HANDWRITING STUDIO) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'stroke_studio'" class="space-y-4" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- CỘT 1: KHUNG LUYỆN VIẾT Ô MỄ (THU GỌN VỪA VẶN) -->
            <div class="lg:col-span-6 space-y-3">
                <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl hover:transform-none hover:translate-y-0 space-y-3.5 shadow-xs">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-2.5 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                        <div class="flex items-center gap-2">
                            <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">{{ __('Bảng Luyện Viết Ô Mễ') }}</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold" 
                                  x-text="'Đang viết: ' + (currentCharacterData?.char || currentChar?.character || '') + ' (' + (currentCharacterData?.sino_vietnamese || currentChar?.sino_vietnamese || '') + ')'"></span>
                        </div>

                        <button type="button" @click="showTraceGuide = !showTraceGuide"
                                class="px-2.5 py-1 rounded-lg text-xs font-semibold border transition-all btn-tactile cursor-pointer"
                                :class="showTraceGuide ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-[#e07a5f] text-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-500'">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1 text-[10px]"></i>
                            <span x-text="showTraceGuide ? '{{ __('Tắt nét mờ') }}' : '{{ __('Bật nét mờ') }}'"></span>
                        </button>
                    </div>

                    <!-- Khung Canvas Ô Mễ (Kích thước tinh gọn, trang nhã: 208px - 240px) -->
                    <div class="relative w-52 h-52 sm:w-60 sm:h-60 mx-auto rounded-2xl bg-[#fffaf5] dark:bg-[#201c1a] border-2 border-[#e8e2d9] dark:border-[#3d2e29] flex items-center justify-center overflow-hidden shadow-inner select-none touch-none">
                        <!-- Đường kẻ ô mễ mờ -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-40 dark:opacity-50" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0" y1="0" x2="100%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="100%" y1="0" x2="0" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                            <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                        </svg>

                        <!-- Chữ mờ mẫu bên trong (Rõ nét ở cả Dark & Light mode) -->
                        <div x-show="showTraceGuide" 
                             class="absolute inset-0 flex items-center justify-center text-6xl sm:text-7xl font-bold text-slate-900/25 dark:text-stone-300/40 zh-text pointer-events-none select-none">
                            <span x-text="currentCharacterData?.char || currentChar?.character || ''"></span>
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

                    <!-- Thanh Công Cụ Chọn Màu & Độ Dày Nét -->
                    <div class="flex flex-wrap items-center justify-between gap-2.5 pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                        <!-- Cọ Màu -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] text-slate-400 font-semibold">{{ __('Mực:') }}</span>
                            <div class="flex items-center gap-1.5">
                                <template x-for="c in brushColors" :key="c.hex">
                                    <button type="button" 
                                            @click="setBrushColor(c.hex)" 
                                            :title="c.name"
                                            class="w-5 h-5 rounded-full transition-all btn-tactile cursor-pointer border-2"
                                            :class="brushColor === c.hex && !isEraser ? 'ring-2 ring-offset-1 ring-[#e07a5f] scale-110' : 'border-transparent'"
                                            :style="'background-color: ' + c.hex"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Cỡ nét -->
                        <div class="flex items-center gap-1">
                            <span class="text-[11px] text-slate-400 font-semibold mr-0.5">{{ __('Nét:') }}</span>
                            <button type="button" @click="setBrushSize(4)" class="px-2 py-0.5 rounded-md text-[11px] font-bold border btn-tactile" :class="brushSize === 4 ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">{{ __('Mảnh') }}</button>
                            <button type="button" @click="setBrushSize(8)" class="px-2 py-0.5 rounded-md text-[11px] font-bold border btn-tactile" :class="brushSize === 8 ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">{{ __('Vừa') }}</button>
                            <button type="button" @click="setBrushSize(14)" class="px-2 py-0.5 rounded-md text-[11px] font-bold border btn-tactile" :class="brushSize === 14 ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">{{ __('Đậm') }}</button>
                        </div>

                        <!-- Tẩy & Hoàn tác -->
                        <div class="flex items-center gap-1">
                            <button type="button" @click="toggleEraser()" :class="isEraser ? 'bg-[#e07a5f] text-white' : 'bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300'" class="px-2 py-1 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold btn-tactile" title="{{ __('Tẩy nét') }}">
                                <i class="fa-solid fa-eraser text-[11px]"></i>
                            </button>
                            <button type="button" @click="undoCanvas()" class="px-2 py-1 rounded-lg bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold btn-tactile" title="{{ __('Hoàn tác nét vừa vẽ') }}">
                                <i class="fa-solid fa-rotate-left text-[11px]"></i>
                            </button>
                            <button type="button" @click="clearCanvas()" class="px-2 py-1 rounded-lg bg-[#f8f6f3] dark:bg-[#23201e] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold btn-tactile" title="{{ __('Xóa toàn bộ để viết lại') }}">
                                <i class="fa-solid fa-trash-can text-[11px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Nút Chấm Điểm Nét Viết -->
                    <div class="space-y-2 pt-1">
                        <button type="button" @click="checkHandwriting()" 
                                class="w-full py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2 btn-tactile cursor-pointer">
                            <i class="fa-solid fa-stamp text-[11px]"></i>
                            <span>{{ __('Chấm bài viết & Nhận xét thần thái nét') }}</span>
                        </button>

                        <div x-show="feedbackMessage" x-cloak 
                             class="p-2.5 rounded-xl border text-xs font-semibold text-center transition-all"
                             :class="{
                                 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300': feedbackType === 'success',
                                 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300': feedbackType === 'error',
                                 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300': feedbackType === 'warning' || feedbackType === 'info'
                             }">
                            <span x-text="feedbackMessage"></span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- CỘT 2: TRÌNH TỰ THUẬN BÚT & MÔ PHẢNG NÉT VIẾT -->
            <div class="lg:col-span-6 space-y-4">
                <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl hover:transform-none hover:translate-y-0 space-y-4 shadow-xs">
                    <div class="flex items-center justify-between pb-2.5 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                        <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-list-ol text-[#e07a5f]"></i>
                            <span>{{ __('Trình tự thuận bút chữ') }}</span>
                            <span class="zh-text font-bold text-sm text-[#e07a5f]" x-text="currentCharacterData?.char || currentChar?.character || ''"></span>
                        </span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-500 dark:text-slate-400 font-semibold" 
                              x-text="(currentChar?.stroke_count || currentCharacterData?.stroke_count || 1) + ' nét'"></span>
                    </div>

                    <div class="space-y-1.5 max-h-[380px] overflow-y-auto no-scrollbar pr-1">
                        <template x-for="(st, sIdx) in (currentChar?.stroke_steps || [{step: 1, name: 'Nét 1', pinyin: 'shù', highlight: 'Thân chữ'}])" :key="sIdx">
                            <div class="p-2.5 rounded-2xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between text-xs transition-all hover:border-[#e07a5f]">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-6 h-6 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold flex items-center justify-center text-xs shadow-2xs" x-text="st.step"></span>
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

