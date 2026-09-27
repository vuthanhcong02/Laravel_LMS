@extends('layouts.lms')
@section('title', __('Chiết Tự Chữ Hán Theo Bộ Thủ - Tiếng Trung XiaoMu'))

@section('header-left')
    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#e07a5f] transition-colors">{{ __('Học tập') }}</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
        <a href="{{ route('hanzi.index') }}" class="hover:text-[#e07a5f] transition-colors font-semibold text-slate-700 dark:text-slate-300">{{ __('Chiết tự Chữ Hán') }}</a>
        <template x-if="viewLevel === 'detail' && currentRadical">
            <span class="inline-flex items-center gap-2">
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-[#e07a5f] font-bold" x-text="currentRadical.name"></span>
            </span>
        </template>
    </div>
@endsection

@section('content')
<div x-data="hanziDecompositionApp({ radicalGroups: {{ json_encode($radicalGroups) }}, radicals: {{ json_encode($demoRadicals) }} })" 
     class="max-w-6xl mx-auto space-y-5">

    <!-- ========================================================================= -->
    <!-- HERO HEADER & TOP ACTION BAR -->
    <!-- ========================================================================= -->
    <div class="lms-card p-4 sm:p-5 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden hover:transform-none hover:translate-y-0">
        <!-- Chữ Hán mờ nền nghệ thuật -->
        <div class="absolute right-4 -bottom-6 text-9xl font-bold text-[#e07a5f]/5 pointer-events-none select-none zh-text">
            部
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-xl">
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-sitemap text-[#e07a5f]"></i>
                    <span x-text="viewLevel === 'list' ? '{{ __('Tổng Hợp 214 Bộ Thủ & Chiết Tự') }}' : '{{ __('Học Chi Tiết Theo Bộ Thủ') }}'"></span>
                </div>
                <h1 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                    <template x-if="viewLevel === 'list'">
                        <span>{{ __('Khám Phá Chữ Hán Theo Từng Bộ Thủ Gốc') }}</span>
                    </template>
                    <template x-if="viewLevel === 'detail' && currentRadical">
                        <span>{{ __('Chiết Tự Nhóm:') }} <span class="text-[#e07a5f]" x-text="currentRadical.name + ' (' + currentRadical.radical + ')'"></span></span>
                    </template>
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    <template x-if="viewLevel === 'list'">
                        <span>{{ __('Mỗi bộ thủ là một chiếc chìa khóa mở ra hàng trăm chữ Hán có chung quy luật hình thành và ý nghĩa.') }}</span>
                    </template>
                    <template x-if="viewLevel === 'detail'">
                        <span>{{ __('Bóc tách cấu trúc Lục thư, thơ mẹo nhớ dân gian và hướng dẫn viết nét thuận bút chuẩn mực.') }}</span>
                    </template>
                </p>
            </div>

            <!-- Các nút chuyển Tab & Modal 7 Quy tắc -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <!-- Nút mở Modal 7 Quy tắc viết -->
                <button type="button" @click="showRulesModal = true"
                        class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#23201e] border border-[#e07a5f]/40 dark:border-[#e07a5f]/30 text-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] text-xs font-bold shadow-2xs transition-all flex items-center gap-1.5 btn-tactile cursor-pointer">
                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                    <span>{{ __('7 Quy tắc viết') }}</span>
                </button>

                <!-- Tabs điều hướng chức năng -->
                <div class="flex items-center gap-1 p-1 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <button type="button" @click="backToRadicalList(); activeTab = 'explorer'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all btn-tactile cursor-pointer"
                            :class="activeTab === 'explorer' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-layer-group text-[10px] mr-1"></i>
                        <span>{{ __('Bộ thủ & Chữ') }}</span>
                    </button>
                    <button type="button" @click="activeTab = 'stroke_studio'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all btn-tactile cursor-pointer"
                            :class="activeTab === 'stroke_studio' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-pen-nib text-[10px] mr-1"></i>
                        <span>{{ __('Luyện viết') }}</span>
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

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 1: TỔNG HỢP DANH MỤC CÁC BỘ THỦ (KHI VỪA VÀO TRANG) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && viewLevel === 'list'" class="space-y-4" x-cloak>
        
        <!-- Thanh Tìm kiếm & Lọc Bộ Thủ -->
        <div class="lms-card p-4 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <!-- Input tìm kiếm -->
                <div class="relative flex-1">
                    <input type="text" 
                           x-model="radicalSearchFilter"
                           placeholder="{{ __('Tìm bộ thủ theo tên, ký tự, pinyin hoặc chữ liên quan (VD: Nhân, Thủy, Mộc, rén, 休...)...') }}" 
                           class="w-full pl-9 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-white placeholder-slate-400 focus:outline-hidden focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
                </div>

                <!-- Lọc theo số nét -->
                <div class="flex items-center gap-1.5 shrink-0 overflow-x-auto no-scrollbar">
                    <span class="text-xs font-semibold text-slate-400 mr-1">{{ __('Nét:') }}</span>
                    <button type="button" @click="strokeFilter = 'all'" 
                            class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all btn-tactile cursor-pointer"
                            :class="strokeFilter === 'all' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">
                        {{ __('Tất cả') }}
                    </button>
                    <button type="button" @click="strokeFilter = '2'" 
                            class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all btn-tactile cursor-pointer"
                            :class="strokeFilter === '2' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">
                        {{ __('2 nét') }}
                    </button>
                    <button type="button" @click="strokeFilter = '3'" 
                            class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all btn-tactile cursor-pointer"
                            :class="strokeFilter === '3' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">
                        {{ __('3 nét') }}
                    </button>
                    <button type="button" @click="strokeFilter = '4'" 
                            class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all btn-tactile cursor-pointer"
                            :class="strokeFilter === '4' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">
                        {{ __('4 nét') }}
                    </button>
                    <button type="button" @click="strokeFilter = '5'" 
                            class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-all btn-tactile cursor-pointer"
                            :class="strokeFilter === '5' ? 'bg-[#e07a5f] text-white border-[#e07a5f]' : 'bg-[#f8f6f3] dark:bg-[#23201e] border-[#e8e2d9] text-slate-600'">
                        {{ __('5 nét') }}
                    </button>
                </div>
            </div>

            <!-- Thống kê tổng hợp số lượng bộ & chữ -->
            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-1 border-t border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <div class="flex items-center gap-3">
                    <span>{{ __('Hiển thị:') }} <strong class="text-[#e07a5f]" x-text="filteredRadicalGroups.length"></strong> {{ __('nhóm bộ thủ') }}</span>
                    <span>•</span>
                    <span><strong class="text-slate-800 dark:text-slate-200" x-text="totalCharacterCount"></strong> {{ __('chữ Hán chiết tự đã tổng hợp') }}</span>
                </div>
                <span class="text-[11px] text-slate-400 italic hidden sm:inline">{{ __('Bấm vào từng bộ để xem bóc tách chiết tự & các chữ liên quan') }}</span>
            </div>
        </div>

        <!-- LƯỚI THẺ TỔNG HỢP CÁC BỘ THỦ (RADICAL GRID CARDS) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <template x-for="group in filteredRadicalGroups" :key="group.id">
                <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-4 cursor-pointer group relative overflow-hidden"
                     @click="openRadicalDetail(group.id)">
                    
                    <!-- Faint Watermark Ký tự bộ góc card -->
                    <div class="absolute -right-3 -bottom-4 text-7xl font-bold text-[#e07a5f]/5 pointer-events-none select-none zh-text group-hover:scale-110 transition-transform">
                        <span x-text="group.radical"></span>
                    </div>

                    <div class="space-y-3 relative z-10">
                        <!-- Top Header: Avatar Ký tự Bộ to & Tên bộ -->
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] flex items-center justify-center text-3xl font-bold zh-text group-hover:scale-105 transition-transform shadow-2xs">
                                    <span x-text="group.radical"></span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors" x-text="group.name"></h3>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        <span class="font-semibold text-[#e07a5f]" x-text="group.pinyin"></span>
                                        <span>•</span>
                                        <span x-text="group.stroke_count + ' nét'"></span>
                                    </div>
                                </div>
                            </div>

                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] border border-[#fcdccf] dark:border-[#4a2e26]"
                                  x-text="Object.keys(group.characters || {}).length + ' chữ'"></span>
                        </div>

                        <!-- Ý nghĩa tóm tắt -->
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2 leading-relaxed" x-text="group.meaning"></p>

                        <!-- Khẩu quyết mẹo nhớ dạng chữ nghiêng -->
                        <div class="p-2 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9]/60 dark:border-[#2d2926]">
                            <p class="text-[11px] text-slate-700 dark:text-slate-300 italic font-medium line-clamp-2" x-text="'“' + group.mnemonic + '”'"></p>
                        </div>
                    </div>

                    <!-- Footer: Các chữ Hán tiêu biểu trong bộ & Nút vào xem -->
                    <div class="pt-3 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between relative z-10">
                        <!-- Preview các chữ Hán trong bộ -->
                        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                            <template x-for="(charItem, charKey) in group.characters" :key="charKey">
                                <span class="w-6 h-6 rounded-md bg-[#f8f6f3] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold zh-text text-slate-700 dark:text-slate-200 flex items-center justify-center group-hover:border-[#e07a5f]/40" 
                                      x-text="charItem.character"></span>
                            </template>
                        </div>

                        <!-- CTA Link -->
                        <span class="text-xs font-bold text-[#e07a5f] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1 shrink-0 ml-2">
                            <span>{{ __('Khám phá') }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>

                </div>
            </template>
        </div>

        <!-- Trạng thái trống nếu không tìm thấy bộ thủ -->
        <div x-show="filteredRadicalGroups.length === 0" class="p-8 text-center bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
            <i class="fa-solid fa-filter text-2xl text-slate-300"></i>
            <p class="text-xs text-slate-400">{{ __('Không tìm thấy nhóm bộ thủ nào phù hợp với bộ lọc hiện tại.') }}</p>
            <button type="button" @click="radicalSearchFilter = ''; strokeFilter = 'all'" class="px-3 py-1 rounded-lg bg-[#e07a5f] text-white text-xs font-bold">
                {{ __('Đặt lại bộ lọc') }}
            </button>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MÀN HÌNH CẤP 2: CHI TIẾT BỘ THỦ & CÁC CHỮ HÁN THUỘC BỘ ĐÓ (KHI CLICK VÀO BỘ) -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'explorer' && viewLevel === 'detail'" class="space-y-4" x-cloak>
        
        <!-- Thanh điều hướng cấp 2: Nút Quay lại & Chuyển nhanh bộ thủ khác -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-[#181615] p-3 rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926]">
            <!-- Nút Quay lại danh mục tổng hợp -->
            <button type="button" 
                    @click="backToRadicalList()"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#f8f6f3] dark:bg-[#23201e] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] text-xs font-bold transition-all btn-tactile cursor-pointer shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
                <span>{{ __('← Quay lại danh sách Bộ thủ') }}</span>
            </button>

            <!-- Bộ chọn nhanh các bộ thủ khác -->
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                <span class="text-xs text-slate-400 font-semibold mr-1 shrink-0">{{ __('Bộ khác:') }}</span>
                <template x-for="(gItem, gKey) in radicalGroups" :key="gKey">
                    <button type="button" 
                            @click="openRadicalDetail(gKey)"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold shrink-0 transition-all btn-tactile cursor-pointer border"
                            :class="selectedRadicalId === gKey 
                                ? 'bg-[#e07a5f] text-white border-[#e07a5f]' 
                                : 'bg-[#fcfaf7] dark:bg-[#23201e] border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-300 hover:border-[#e07a5f]'">
                        <span class="zh-text" x-text="gItem.radical"></span>
                        <span class="text-[11px] ml-1" x-text="gItem.sino_vietnamese"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Banner Tiêu Điểm của Bộ Thủ Đang Chọn (Radical Spotlight) -->
        <template x-if="currentRadical">
            <div class="lms-card p-4 bg-gradient-to-r from-[#fcfaf7] via-white to-[#fffaf7] dark:from-[#23201e] dark:via-[#181615] dark:to-[#221c19] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2.5 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] flex items-center justify-center text-3xl font-bold zh-text shadow-2xs">
                            <span x-text="currentRadical.radical"></span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white" x-text="currentRadical.name"></h2>
                                <span class="text-xs font-bold text-[#e07a5f]" x-text="'(Âm: ' + currentRadical.sino_vietnamese + ' • Pinyin: ' + currentRadical.pinyin + ')'"></span>
                                <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold" x-text="currentRadical.stroke_count + ' nét'"></span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5" x-text="currentRadical.meaning"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" @click="speak(currentRadical.radical)" 
                                class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] hover:bg-[#fff2ee] text-xs font-bold flex items-center gap-1.5 btn-tactile cursor-pointer shadow-2xs">
                            <i class="fa-solid fa-volume-high"></i>
                            <span>{{ __('Đọc tên bộ') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Đặc trưng & Khẩu quyết -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#1f1c1a] border border-[#e8e2d9]/60 dark:border-[#2d2926] space-y-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                            <i class="fa-solid fa-circle-info text-[#e07a5f] mr-1"></i>{{ __('Đặc trưng biểu nghĩa') }}
                        </span>
                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed" x-text="currentRadical.description"></p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-[#fff9f6] dark:bg-[#2c221e]/50 border border-[#fcdccf]/60 dark:border-[#4a2e26]/40 space-y-1">
                        <span class="text-[11px] font-bold text-[#e07a5f] uppercase tracking-wider block">
                            <i class="fa-solid fa-scroll text-[#e07a5f] mr-1"></i>{{ __('Khẩu quyết nhận diện') }}
                        </span>
                        <p class="text-slate-800 dark:text-slate-200 italic font-medium leading-relaxed" x-text="currentRadical.mnemonic"></p>
                    </div>
                </div>

                <!-- Dải chọn các chữ Hán thuộc bộ này -->
                <div class="pt-1 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-down-short-wide text-[#e07a5f]"></i>
                            <span>{{ __('Các chữ Hán thuộc bộ này (bấm để xem chiết tự chi tiết):') }}</span>
                        </span>
                        <span class="text-[11px] text-slate-400" x-text="currentRadicalCharacters.length + ' {{ __('chữ Hán') }}'"></span>
                    </div>

                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                        <template x-for="item in currentRadicalCharacters" :key="item.character">
                            <button type="button" 
                                    @click="selectCharacter(item.character)"
                                    class="px-3.5 py-2 rounded-xl text-xs font-semibold shrink-0 transition-all btn-tactile cursor-pointer flex items-center gap-2 border"
                                    :class="selectedCharKey === item.character 
                                        ? 'bg-[#e07a5f] text-white border-[#e07a5f] shadow-xs' 
                                        : 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:border-[#e07a5f] hover:text-[#e07a5f]'">
                                <span class="zh-text font-bold text-base" x-text="item.character"></span>
                                <div class="text-left leading-tight">
                                    <span class="block font-bold text-xs" x-text="item.sino_vietnamese"></span>
                                    <span class="block text-[10px] opacity-80" x-text="item.pinyin"></span>
                                </div>
                                <span class="text-[10px] px-1.5 py-0.2 rounded-md font-bold ml-1"
                                      :class="selectedCharKey === item.character ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
                                      x-text="'HSK ' + item.hsk_level"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <!-- Chi tiết Chiết Tự Chữ Hán Đang Chọn -->
        <template x-if="currentChar">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                
                <!-- CỘT 1 (BÊN TRÁI): THẺ CHỮ HÁN & VIDEO MÔ PHỎNG BÚT THUẬN (5 COLS) -->
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

                        <!-- Ô Mễ Chữ Hán với Bộ Điều Khiển Viết Nét Từng Bước -->
                        <div class="space-y-3">
                            <div class="relative w-48 h-48 sm:w-52 sm:h-52 mx-auto rounded-2xl bg-[#fffaf5] dark:bg-[#1f1c1a] border-2 border-[#e8e2d9] dark:border-[#382823] flex items-center justify-center overflow-hidden shadow-inner group">
                                <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-30 dark:opacity-20" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="0" y1="0" x2="100%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                    <line x1="100%" y1="0" x2="0" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                    <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                    <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#e07a5f" stroke-dasharray="4 4" stroke-width="1.5" />
                                </svg>

                                <div class="absolute inset-0 flex items-center justify-center text-7xl sm:text-8xl font-bold text-slate-900/10 dark:text-white/10 zh-text select-none">
                                    <span x-text="currentChar.character"></span>
                                </div>

                                <div class="relative z-10 text-7xl sm:text-8xl font-bold text-[#e07a5f] zh-text select-none transition-all duration-300"
                                     :class="isPlayingStrokes ? 'scale-105' : ''">
                                    <span x-text="currentChar.character"></span>
                                </div>

                                <div class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-black/60 text-white text-[10px] font-bold">
                                    <span x-text="'Nét ' + currentStrokeStep + ' / ' + (currentChar.stroke_steps?.length || currentChar.stroke_count)"></span>
                                </div>
                            </div>

                            <!-- Nhãn tên nét hiện hành & Cụm nút Play Video Viết nét -->
                            <div class="space-y-2 pt-1">
                                <template x-if="currentChar.stroke_steps && currentChar.stroke_steps[currentStrokeStep - 1]">
                                    <div class="p-2 rounded-lg bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100" x-text="currentChar.stroke_steps[currentStrokeStep - 1].name"></span>
                                        <span class="text-[11px] text-[#e07a5f] ml-1 font-semibold" x-text="'(' + currentChar.stroke_steps[currentStrokeStep - 1].pinyin + ')'"></span>
                                    </div>
                                </template>

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

                        <!-- Tổng quan Phiên âm & Ý nghĩa -->
                        <div class="text-center space-y-1 pt-2 border-t border-[#e8e2d9] dark:border-[#2d2926]">
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-xl font-bold text-slate-900 dark:text-white" x-text="currentChar.pinyin"></span>
                                <span class="text-base font-bold text-[#e07a5f]" x-text="'/ ' + currentChar.sino_vietnamese + ' /'"></span>
                            </div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200" x-text="currentChar.meaning"></p>
                        </div>

                        <!-- Nút chuyển nhanh sang Luyện viết Canvas -->
                        <button type="button" 
                                @click="activeTab = 'stroke_studio'"
                                class="w-full py-2.5 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white text-xs font-bold transition-all shadow-2xs flex items-center justify-center gap-2 btn-tactile cursor-pointer">
                            <i class="fa-solid fa-pen-fancy"></i>
                            <span>{{ __('Luyện viết chữ này trên Canvas') }}</span>
                        </button>
                    </div>
                </div>

                <!-- CỘT 2 (BÊN PHẢI): BÓC TÁCH CHIẾT TỰ, THƠ MẸO NHỚ & BỘ THỦ (7 COLS) -->
                <div class="lg:col-span-7 space-y-4">
                    
                    <!-- Khối 1: Mối quan hệ với Bộ thủ mẹ -->
                    <div class="lms-card p-4 sm:p-5 bg-gradient-to-br from-[#fff7f4] to-white dark:from-[#23201e] dark:to-[#181615] border-l-4 border-l-[#e07a5f] border-y border-r border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#e07a5f] uppercase tracking-wider">
                            <i class="fa-solid fa-link"></i>
                            <span x-text="'{{ __('Ý nghĩa Bộ thủ trong chữ ') }}' + currentChar.character"></span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-800 dark:text-slate-200 font-semibold leading-relaxed" x-text="currentChar.radical_relation"></p>
                        <div class="pt-1 flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <span x-text="'Thuộc nhóm ' + (currentRadical?.name || 'Bộ thủ') + ' • ' + currentChar.stroke_rules"></span>
                        </div>
                    </div>

                    <!-- Khối 2: Thơ Mẹo Nhớ Dân Gian & Khẩu Quyết -->
                    <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-scroll text-[#e07a5f]"></i>
                                {{ __('Thơ Mẹo Nhớ Dân Gian') }}
                            </span>
                            <span class="text-[11px] text-[#e07a5f] font-semibold italic" x-text="currentChar.traditional_quote"></span>
                        </div>
                        
                        <div class="p-3.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-center">
                            <p class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 italic leading-relaxed whitespace-pre-line" 
                               x-text="currentChar.mnemonic_poem"></p>
                        </div>
                    </div>

                    <!-- Khối 3: Bóc tách Thành phần Bộ thủ -->
                    <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-[#e8e2d9] dark:border-[#2d2926]">
                            <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-puzzle-piece text-[#e07a5f]"></i>
                                {{ __('Bóc Tách Thành Phần & Bộ Phận Cấu Thành') }}
                            </span>
                            <span class="text-[11px] text-slate-400" x-text="(currentChar.components?.length || 0) + ' {{ __('thành phần') }}'"></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <template x-for="(comp, cIdx) in currentChar.components" :key="cIdx">
                                <div class="p-3 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/60 transition-all space-y-2">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] flex items-center justify-center text-xl font-bold zh-text shadow-2xs">
                                                <span x-text="comp.character"></span>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white" x-text="comp.name"></h4>
                                                <span class="text-[10px] text-slate-400" x-text="'Vị trí: ' + comp.position + ' • ' + comp.role"></span>
                                            </div>
                                        </div>

                                        <button type="button" @click="speak(comp.character)" class="text-slate-400 hover:text-[#e07a5f] text-xs p-1">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed" x-text="comp.explanation"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Khối 4: Từ vựng mở rộng (Compounds) -->
                    <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:transform-none hover:translate-y-0 space-y-3">
                        <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-book-open text-[#e07a5f]"></i>
                            <span x-text="'{{ __('Từ Vựng Mở Rộng Ghép Với Chữ ') }}' + currentChar.character"></span>
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <template x-for="(wordItem, wIdx) in currentChar.compounds" :key="wIdx">
                                <div class="p-2.5 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between group hover:border-[#e07a5f] transition-all cursor-pointer btn-tactile"
                                     @click="speak(wordItem.word)">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white zh-text" x-text="wordItem.word"></span>
                                            <span class="text-[10px] text-[#e07a5f] font-semibold" x-text="wordItem.pinyin"></span>
                                        </div>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 block truncate" x-text="wordItem.meaning"></span>
                                    </div>
                                    <i class="fa-solid fa-volume-high text-xs text-slate-400 group-hover:text-[#e07a5f] transition-colors"></i>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

            </div>
        </template>
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
             class="relative w-full max-w-2xl bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-5 sm:p-6 space-y-5 z-10 my-8">
            
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
