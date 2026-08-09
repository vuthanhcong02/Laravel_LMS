@extends('portal.layouts.dashboard')

@section('title', 'Chỉnh sửa Đề thi HSK - XiaoMu Admin')

@section('header')
    @if(request()->is('teacher*') || request()->is('portal/teacher*'))
        @include('portal.teacher.layouts.header')
    @else
        @include('portal.admin.layouts.header')
    @endif
@endsection

@section('sidebar')
    @if(request()->is('teacher*') || request()->is('portal/teacher*'))
        @include('portal.teacher.layouts.sidebar')
    @else
        @include('portal.admin.layouts.sidebar')
    @endif
@endsection

@section('content')
    <style>
        body, html { overflow: hidden !important; }
        /* Prevent dashboard layout from scrolling */
        .flex-1.flex.flex-col { min-height: 0; }
    </style>
    <main class="flex-1 flex flex-col h-[calc(100vh-4.5rem)] max-h-[calc(100vh-4.5rem)] min-h-0 overflow-hidden" x-data="hskExamEditor({{ $hskMockExam->id }})">
        
        <!-- Fixed Header -->
        <div class="shrink-0 px-6 lg:px-8 py-5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 z-10 shadow-sm">
            <div class="max-w-[1400px] mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ request()->is('teacher*') ? route('teacher.hsk-mock-exams.index') : route('admin.hsk-mock-exams.index') }}"
                        class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition-colors">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight" x-text="exam.title || 'Đang tải...'"></h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Trình chỉnh sửa trực quan nội dung câu hỏi, hình ảnh và đáp án</p>
                    </div>
                </div>
                <button @click="saveExam()" :disabled="saving"
                    class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 flex items-center gap-2 disabled:opacity-50 transition-all active:scale-95 shrink-0">
                    <span class="material-symbols-outlined text-lg" x-text="saving ? 'sync' : 'save'"></span>
                    <span x-text="saving ? 'Đang lưu...' : 'Lưu Thay Đổi'"></span>
                </button>
            </div>
        </div>

        <!-- Two Columns Scrollable Area -->
        <div class="flex-1 overflow-hidden p-6 lg:px-8 lg:py-6 bg-slate-50/50 dark:bg-[#131a1f]">
            <div class="max-w-[1400px] mx-auto h-full flex flex-col xl:flex-row gap-6 items-start">
                
                <!-- Main Content Area (Scrolls independently) -->
                <div class="flex-1 overflow-y-auto h-full min-w-0 w-full space-y-8 pb-32 pr-2" style="scrollbar-width: thin;" id="main-editor-scroll">
                    <!-- Loading Spinner -->
            <template x-if="loading">
                <div class="p-12 text-center text-slate-500 space-y-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
                    <span class="material-symbols-outlined text-4xl animate-spin text-primary">sync</span>
                    <p class="font-medium text-sm">Đang tải cấu trúc toàn bộ đề thi...</p>
                </div>
            </template>

            <!-- Main Editor Form -->
            <template x-if="!loading && exam">
                <div class="space-y-8">
                    <!-- General Information -->
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">info</span>
                            Thông tin chung
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tên Đề thi</label>
                                <input type="text" x-model="exam.title"
                                    class="w-full p-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 font-bold">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Thời gian (Phút)</label>
                                <input type="number" x-model.number="exam.duration"
                                    class="w-full p-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 font-bold">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Trạng thái</label>
                                <select x-model="exam.is_published"
                                    class="w-full p-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 font-bold">
                                    <option :value="1">Xuất bản (Hiển thị cho Học sinh)</option>
                                    <option :value="0">Nháp (Ẩn)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sections Editor -->
                    <template x-for="(section, sIndex) in exam.sections" :key="section.id">
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-xs rounded-lg uppercase font-black" x-text="section.skill_type"></span>
                                    <span x-text="section.name"></span>
                                </h2>
                            </div>

                            <!-- Groups -->
                            <div class="space-y-6">
                                <template x-for="(group, gIndex) in (section.question_groups || section.questionGroups || [])" :key="group.id">
                                    <div class="p-6 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-200/80 dark:border-slate-800 space-y-5">
                                        <div class="font-bold text-base text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-lg bg-primary text-white flex items-center justify-center text-xs font-black shadow-sm" x-text="gIndex + 1"></span>
                                            <span>Nhóm câu hỏi / Part <span x-text="gIndex + 1"></span></span>
                                        </div>

                                        <!-- Passage Text / Instructions (Only for Reading comprehension passages) -->
                                        <template x-if="group.passage_text && section.skill_type === 'reading' && gIndex >= 2">
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-500 uppercase">Đoạn văn đọc hiểu / Hướng dẫn Part</label>
                                                <textarea x-model="group.passage_text" rows="3" placeholder="Nhập đoạn văn đọc hiểu..."
                                                    class="w-full p-3 text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200"></textarea>
                                            </div>
                                        </template>

                                        <!-- Passage Images (A-F Bank for Part 3 & 4) -->
                                        <template x-if="group.passage_image && getPassageImageList(group.passage_image).length > 3">
                                            <div class="space-y-2 pt-2 border-t border-slate-200/60 dark:border-slate-700/50">
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Bộ ảnh lựa chọn A-F (Passage Images Bank)</label>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                                                    <template x-for="(imgSrc, imgIdx) in getPassageImageList(group.passage_image)" :key="imgIdx">
                                                        <div class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex flex-col items-center gap-1 shadow-sm">
                                                            <div class="flex items-center gap-1">
                                                                <span class="px-2 py-0.5 rounded text-xs font-black"
                                                                    :class="(gIndex === 2 && imgIdx === 2) ? 'bg-amber-500 text-white' : 'bg-primary/10 text-primary'"
                                                                    x-text="'Ảnh ' + String.fromCharCode(65 + imgIdx)"></span>
                                                                <template x-if="gIndex === 2 && imgIdx === 2">
                                                                    <span class="text-[9px] bg-amber-500 text-white px-1 rounded font-bold">Ví dụ</span>
                                                                </template>
                                                            </div>
                                                            <img :src="getImageUrl(imgSrc)" class="h-32 object-contain rounded-lg my-1 bg-white dark:bg-slate-900" alt="Passage Image">
                                                            
                                                            <input type="file" accept="image/*" class="hidden" :id="'passage-img-' + group.id + '-' + imgIdx" @change="uploadPassageImage($event, group, imgIdx)">
                                                            <button type="button" @click="triggerFileInput('passage-img-' + group.id + '-' + imgIdx)"
                                                                class="w-full py-1 bg-primary text-white text-[10px] font-bold rounded-lg hover:bg-primary/90 transition-all flex items-center justify-center gap-1 cursor-pointer">
                                                                <span class="material-symbols-outlined text-xs font-bold">cloud_upload</span>
                                                                Tải ảnh từ máy
                                                            </button>
                                                            <span class="text-[10px] text-slate-400 font-mono truncate w-full text-center" x-text="imgSrc.split('/').pop()"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Text Options Bank (A-F for Reading Part 3 & Part 4) -->
                                        <template x-if="section.skill_type === 'reading' && (gIndex === 2 || gIndex === 3)">
                                            <div class="space-y-4 pt-2 border-t border-slate-200/60 dark:border-slate-700/50">
                                                <div class="flex items-center justify-between">
                                                    <label class="block text-xs font-bold text-slate-500 uppercase">Bộ từ lựa chọn A-F (Điền vào chỗ trống)</label>
                                                </div>
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                                                    <template x-for="(opt, optIdx) in group._text_options" :key="optIdx">
                                                        <div class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex flex-col gap-2 shadow-sm relative group/card">
                                                            <div class="flex items-center justify-between">
                                                                <span class="px-2 py-0.5 rounded text-xs font-black bg-primary/10 text-primary" x-text="String.fromCharCode(65 + optIdx)"></span>
                                                                <template x-if="String.fromCharCode(65 + optIdx) === (group._ex_a_letter || 'D')">
                                                                    <span class="text-[9px] bg-amber-500 text-white px-1.5 py-0.5 rounded font-bold uppercase shadow-sm">Ví dụ</span>
                                                                </template>
                                                            </div>
                                                            <div class="space-y-2">
                                                                <div class="relative group/pinyin">
                                                                    <textarea rows="2" x-model="opt.pinyin" :id="'text-opt-pinyin-' + group.id + '-' + optIdx"
                                                                        @input="opt.pinyin = convertPinyinToneNumbers(opt.pinyin); updatePart4Text(group)"
                                                                        placeholder="Pinyin"
                                                                        class="w-full text-xs font-medium p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white resize-y"></textarea>
                                                                    <div class="absolute right-0 top-full mt-1 z-10 flex flex-col gap-0.5 opacity-0 group-hover/pinyin:opacity-100 transition-opacity bg-white dark:bg-slate-900 p-1 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm hidden pointer-events-none group-focus-within/pinyin:flex group-focus-within/pinyin:opacity-100 group-focus-within/pinyin:pointer-events-auto">
                                                                        <template x-for="tone in ['ā','á','ǎ','à','a']" :key="tone">
                                                                            <button type="button" @mousedown.prevent="insertCharAtCursor(tone, opt, 'text-opt-pinyin-' + group.id + '-' + optIdx, 'pinyin'); updatePart4Text(group)"
                                                                                class="w-5 h-5 rounded flex items-center justify-center text-[10px] font-bold text-slate-600 hover:bg-slate-100 hover:text-primary transition-colors" x-text="tone"></button>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                                <textarea rows="2" x-model="opt.hanzi" @input="updatePart4Text(group)"
                                                                    placeholder="Chữ Hán"
                                                                    class="w-full text-sm font-bold p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-center resize-y"></textarea>
                                                            </div>
                                                            <!-- Preview -->
                                                            <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-800 flex justify-center items-center h-10" x-html="opt.html || ''">
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Questions & Examples List inside Group -->
                                        <div class="space-y-4 pt-3">
                                            <div class="text-xs font-black uppercase tracking-wider text-slate-400">Danh sách các câu ví dụ & câu hỏi chính thức</div>
                                            
                                            <!-- EXAMPLE 1 & 2 CARDS (For Part 1) -->
                                            <template x-if="group.passage_image && getPassageImageList(group.passage_image).length === 2 && gIndex === 0">
                                                <div class="space-y-4">
                                                    <!-- EXAMPLE 1 -->
                                                    <div class="p-5 bg-amber-50/40 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-900/50 space-y-4 shadow-sm">
                                                        <div class="flex items-center justify-between border-b border-amber-200/60 dark:border-amber-900/50 pb-3">
                                                            <div class="flex items-center gap-3">
                                                                <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                    Ví dụ 1 (例如 1)
                                                                </span>
                                                                <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Câu Ví dụ mẫu của Part (Đúng ✓)</span>
                                                            </div>
                                                            <span class="text-xs text-amber-600 font-bold uppercase">Mẫu Đề</span>
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                                            <div class="md:col-span-3 p-3 bg-white dark:bg-slate-900 rounded-xl border border-amber-200/80 flex flex-col items-center text-center space-y-2">
                                                                <img :src="getImageUrl(getPassageImageList(group.passage_image)[0])" class="h-28 object-contain rounded-lg bg-white dark:bg-slate-900" alt="Ex 1">
                                                                <input type="file" accept="image/*" class="hidden" :id="'ex1-img-' + group.id" @change="uploadPassageImage($event, group, 0)">
                                                                <button type="button" @click="triggerFileInput('ex1-img-' + group.id)" class="px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded-lg hover:bg-primary/90 flex items-center gap-1 cursor-pointer">
                                                                    <span class="material-symbols-outlined text-xs font-bold">cloud_upload</span> Đổi ảnh từ máy
                                                                </button>
                                                            </div>

                                                            <div class="md:col-span-9 space-y-2">
                                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Đáp án mẫu Ví dụ 1</label>
                                                                <div class="grid grid-cols-2 gap-4 max-w-md">
                                                                    <div class="p-3.5 rounded-2xl border-2 border-emerald-500 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold text-xs flex items-center justify-between shadow-lg shadow-emerald-500/20">
                                                                        <div class="flex items-center gap-3">
                                                                            <div class="w-8 h-8 rounded-xl bg-white/20 text-white flex items-center justify-center font-black text-base">✓</div>
                                                                            <div class="flex flex-col text-left">
                                                                                <span class="text-xs font-black tracking-wider uppercase">ĐÚNG</span>
                                                                                <span class="text-[10px] font-semibold text-emerald-100">TRUE (√)</span>
                                                                            </div>
                                                                        </div>
                                                                        <span class="px-2.5 py-1 rounded-full bg-white/20 text-[10px] font-black uppercase">✓ ĐÃ CHỌN</span>
                                                                    </div>
                                                                    <div class="p-3.5 rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 font-medium text-xs text-slate-400 flex items-center opacity-50">
                                                                        <div class="flex items-center gap-3">
                                                                            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-black text-base">✕</div>
                                                                            <span class="text-xs font-bold">SAI (FALSE)</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- EXAMPLE 2 -->
                                                    <div class="p-5 bg-amber-50/40 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-900/50 space-y-4 shadow-sm">
                                                        <div class="flex items-center justify-between border-b border-amber-200/60 dark:border-amber-900/50 pb-3">
                                                            <div class="flex items-center gap-3">
                                                                <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                    Ví dụ 2 (例如 2)
                                                                </span>
                                                                <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Câu Ví dụ mẫu của Part (Sai ✕)</span>
                                                            </div>
                                                            <span class="text-xs text-amber-600 font-bold uppercase">Mẫu Đề</span>
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                                            <div class="md:col-span-3 p-3 bg-white dark:bg-slate-900 rounded-xl border border-amber-200/80 flex flex-col items-center text-center space-y-2">
                                                                <img :src="getImageUrl(getPassageImageList(group.passage_image)[1])" class="h-28 object-contain rounded-lg bg-white dark:bg-slate-900" alt="Ex 2">
                                                                <input type="file" accept="image/*" class="hidden" :id="'ex2-img-' + group.id" @change="uploadPassageImage($event, group, 1)">
                                                                <button type="button" @click="triggerFileInput('ex2-img-' + group.id)" class="px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded-lg hover:bg-primary/90 flex items-center gap-1 cursor-pointer">
                                                                    <span class="material-symbols-outlined text-xs font-bold">cloud_upload</span> Đổi ảnh từ máy
                                                                </button>
                                                            </div>

                                                            <div class="md:col-span-9 space-y-2">
                                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Đáp án mẫu Ví dụ 2</label>
                                                                <div class="grid grid-cols-2 gap-4 max-w-md">
                                                                    <div class="p-3.5 rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 font-medium text-xs text-slate-400 flex items-center opacity-50">
                                                                        <div class="flex items-center gap-3">
                                                                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-base">✓</div>
                                                                            <span class="text-xs font-bold">ĐÚNG (TRUE)</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="p-3.5 rounded-2xl border-2 border-rose-500 bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs flex items-center justify-between shadow-lg shadow-rose-500/20">
                                                                        <div class="flex items-center gap-3">
                                                                            <div class="w-8 h-8 rounded-xl bg-white/20 text-white flex items-center justify-center font-black text-base">✕</div>
                                                                            <div class="flex flex-col text-left">
                                                                                <span class="text-xs font-black tracking-wider uppercase">SAI</span>
                                                                                <span class="text-[10px] font-semibold text-rose-100">FALSE (×)</span>
                                                                            </div>
                                                                        </div>
                                                                        <span class="px-2.5 py-1 rounded-full bg-white/20 text-[10px] font-black uppercase">✓ ĐÃ CHỌN</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- EXAMPLE CARD FOR PART 2 -->
                                            <template x-if="group.passage_image && getPassageImageList(group.passage_image).length === 3 && gIndex === 1">
                                                <div class="p-5 bg-amber-50/40 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-900/50 space-y-4 shadow-sm">
                                                    <div class="flex items-center justify-between border-b border-amber-200/60 dark:border-amber-900/50 pb-3">
                                                        <div class="flex items-center gap-3">
                                                            <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                Ví dụ (例如)
                                                            </span>
                                                            <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Câu Ví dụ mẫu của Part 2 (Đáp án mẫu: A)</span>
                                                        </div>
                                                        <span class="text-xs text-amber-600 font-bold uppercase">Mẫu Đề</span>
                                                    </div>

                                                    <div class="space-y-2">
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Hình ảnh các đáp án ví dụ mẫu A, B, C</label>
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                            <div class="p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50/80 dark:bg-emerald-950/40 flex flex-col items-center text-center shadow-sm">
                                                                <div class="flex items-center justify-between w-full pb-2 border-b border-emerald-200">
                                                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-600 text-white">Đáp án A</span>
                                                                    <span class="text-xs font-black text-emerald-600">✓ ĐÁP ÁN ĐÚNG (MẪU)</span>
                                                                </div>
                                                                <img :src="getImageUrl(getPassageImageList(group.passage_image)[0])" class="h-32 object-contain my-3 rounded-xl border bg-white dark:bg-slate-900 p-1" alt="Ex A">
                                                                <input type="file" accept="image/*" class="hidden" :id="'exA-img-' + group.id" @change="uploadPassageImage($event, group, 0)">
                                                                <button type="button" @click="triggerFileInput('exA-img-' + group.id)" class="px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded-lg hover:bg-primary/90 flex items-center gap-1 cursor-pointer">
                                                                    <span class="material-symbols-outlined text-xs font-bold">cloud_upload</span> Đổi ảnh từ máy
                                                                </button>
                                                            </div>

                                                            <div class="p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col items-center text-center opacity-60">
                                                                <div class="flex items-center justify-between w-full pb-2 border-b border-slate-100 dark:border-slate-800">
                                                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-slate-100 dark:bg-slate-800 text-slate-700">Đáp án B</span>
                                                                </div>
                                                                <img :src="getImageUrl(getPassageImageList(group.passage_image)[1])" class="h-32 object-contain my-3 rounded-xl border bg-white dark:bg-slate-900 p-1" alt="Ex B">
                                                                <input type="file" accept="image/*" class="hidden" :id="'exB-img-' + group.id" @change="uploadPassageImage($event, group, 1)">
                                                                <button type="button" @click="triggerFileInput('exB-img-' + group.id)" class="px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded-lg hover:bg-primary/90 flex items-center gap-1 cursor-pointer">
                                                                    <span class="material-symbols-outlined text-xs font-bold">cloud_upload</span> Đổi ảnh từ máy
                                                                </button>
                                                            </div>

                                                            <div class="p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col items-center text-center opacity-60">
                                                                <div class="flex items-center justify-between w-full pb-2 border-b border-slate-100 dark:border-slate-800">
                                                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-slate-100 dark:bg-slate-800 text-slate-700">Đáp án C</span>
                                                                </div>
                                                                <img :src="getImageUrl(getPassageImageList(group.passage_image)[2])" class="h-32 object-contain my-3 rounded-xl border bg-white dark:bg-slate-900 p-1" alt="Ex C">
                                                                <input type="file" accept="image/*" class="hidden" :id="'exC-img-' + group.id" @change="uploadPassageImage($event, group, 2)">
                                                                <button type="button" @click="triggerFileInput('exC-img-' + group.id)" class="px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded-lg hover:bg-primary/90 flex items-center gap-1 cursor-pointer">
                                                                    <span class="material-symbols-outlined text-xs font-bold">cloud_upload</span> Đổi ảnh từ máy
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- EXAMPLE CARD FOR PART 3 -->
                                            <template x-if="group.passage_image && getPassageImageList(group.passage_image).length >= 5 && gIndex === 2">
                                                <div class="p-5 bg-amber-50/40 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-900/50 space-y-4 shadow-sm">
                                                    <div class="flex items-center justify-between border-b border-amber-200/60 dark:border-amber-900/50 pb-3">
                                                        <div class="flex items-center gap-3">
                                                            <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                Ví dụ (例如)
                                                            </span>
                                                            <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Câu Ví dụ mẫu của Part 3 (Ghép với Ảnh C)</span>
                                                        </div>
                                                        <span class="text-xs text-amber-600 font-bold uppercase">Mẫu Đề</span>
                                                    </div>

                                                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                                                        <!-- Dialogue Text with Pinyin Ruby -->
                                                        <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-amber-200/80 dark:border-slate-800 space-y-2 flex-1 w-full shadow-sm">
                                                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-relaxed space-y-1.5">
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-slate-500 font-bold">女:</span>
                                                                    <div><ruby>你<rt>Nǐ</rt></ruby> <ruby>好<rt>hǎo</rt></ruby>！</div>
                                                                </div>
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-slate-500 font-bold">男:</span>
                                                                    <div><ruby>你<rt>Nǐ</rt></ruby> <ruby>好<rt>hǎo</rt></ruby>！<ruby>很<rt>Hěn</rt></ruby> <ruby>高<rt>gāo</rt></ruby><ruby>兴<rt>xìng</rt></ruby> <ruby>认<rt>rèn</rt></ruby><ruby>识<rt>shi</rt></ruby> <ruby>你<rt>nǐ</rt></ruby>。</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Example Answer Image (C) -->
                                                        <div class="p-3 bg-white dark:bg-slate-900 rounded-2xl border-2 border-emerald-500 flex flex-col items-center shadow-sm">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <span class="px-2.5 py-0.5 rounded-lg bg-emerald-600 text-white text-xs font-black">Đáp án mẫu: C</span>
                                                                <span class="text-xs font-black text-emerald-600">✓ ĐÁP ÁN ĐÚNG (MẪU)</span>
                                                            </div>
                                                            <img :src="getImageUrl(getPassageImageList(group.passage_image)[2])" class="h-32 object-contain rounded-xl border p-1 bg-white dark:bg-slate-900" alt="Example Image C">
                                                            <input type="file" accept="image/*" class="hidden" :id="'exC3-img-' + group.id" @change="uploadPassageImage($event, group, 2)">
                                                            <button type="button" @click="triggerFileInput('exC3-img-' + group.id)" class="mt-2 px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded-lg hover:bg-primary/90 flex items-center gap-1 cursor-pointer">
                                                                <span class="material-symbols-outlined text-xs font-bold">cloud_upload</span> Đổi ảnh từ máy
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- EXAMPLE CARD FOR READING PART 3 & LISTENING PART 3 -->
                                            <template x-if="(section.skill_type === 'reading' && gIndex === 2) || (section.skill_type === 'listening' && gIndex === 2)">
                                                <div class="p-5 bg-amber-50/40 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-900/50 space-y-4 shadow-sm mb-4">
                                                    <div class="flex items-center justify-between border-b border-amber-200/60 dark:border-amber-900/50 pb-3">
                                                        <div class="flex items-center gap-3">
                                                            <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                Ví dụ (例如)
                                                            </span>
                                                            <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Chỉnh sửa Câu Ví dụ mẫu của Part 3 (Ghép Câu Q&A)</span>
                                                        </div>
                                                        <span class="text-xs text-amber-600 font-bold uppercase">Mẫu Đề</span>
                                                    </div>

                                                    <div class="space-y-4">
                                                        <!-- Question Input -->
                                                        <div class="p-4 bg-white dark:bg-slate-900 rounded-xl border border-amber-200/70 dark:border-slate-800 shadow-sm space-y-3">
                                                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                                                Nội dung câu hỏi mẫu
                                                            </h4>
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Phiên âm Pinyin</label>
                                                                    <div class="relative flex items-center group/pinyin">
                                                                        <textarea rows="2" x-model="group._ex_q_pinyin" :id="'ex-q-pinyin-' + group.id"
                                                                            @input="group._ex_q_pinyin = convertPinyinToneNumbers(group._ex_q_pinyin); updateExampleText(group)"
                                                                            placeholder="Ví dụ: Nǐ hē shuǐ ma ?"
                                                                            class="w-full text-sm font-medium p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white resize-y"></textarea>
                                                                        <div class="absolute right-1.5 flex items-center gap-0.5 opacity-0 group-hover/pinyin:opacity-100 transition-opacity bg-white dark:bg-slate-900 px-1 py-1 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                                                                            <template x-for="tone in ['ā','á','ǎ','à','a']" :key="tone">
                                                                                <button type="button" @mousedown.prevent="insertCharAtCursor(tone, group, 'ex-q-pinyin-' + group.id, '_ex_q_pinyin')"
                                                                                    class="w-5 h-5 rounded flex items-center justify-center text-[10px] font-bold text-slate-600 hover:bg-slate-100 hover:text-primary transition-colors" x-text="tone"></button>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Chữ Hán</label>
                                                                    <textarea rows="2" x-model="group._ex_q_hanzi" @input="updateExampleText(group)"
                                                                        placeholder="Ví dụ: 你喝水吗？"
                                                                        class="w-full text-sm font-bold p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white resize-y"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Answer Input -->
                                                        <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 rounded-xl border border-emerald-200/70 dark:border-emerald-800/50 shadow-sm space-y-3">
                                                            <div class="flex items-center gap-3">
                                                                <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-200 flex items-center gap-2">
                                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                                    Đáp án mẫu
                                                                </h4>
                                                                <input type="text" x-model="group._ex_a_letter" @input="updateExampleText(group)"
                                                                    class="w-12 text-center text-xs font-black p-1 rounded-lg border border-emerald-300 dark:border-emerald-700 bg-white dark:bg-slate-800 text-emerald-700 dark:text-emerald-300 uppercase"
                                                                    maxlength="1" placeholder="F">
                                                            </div>
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-emerald-600/70 uppercase mb-1">Phiên âm Pinyin</label>
                                                                    <div class="relative flex items-center group/pinyin">
                                                                        <textarea rows="2" x-model="group._ex_a_pinyin" :id="'ex-a-pinyin-' + group.id"
                                                                            @input="group._ex_a_pinyin = convertPinyinToneNumbers(group._ex_a_pinyin); updateExampleText(group)"
                                                                            placeholder="Ví dụ: Hǎo de, xièxie!"
                                                                            class="w-full text-sm font-medium p-2 rounded-lg border border-emerald-200 dark:border-emerald-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white resize-y"></textarea>
                                                                        <div class="absolute right-1.5 flex items-center gap-0.5 opacity-0 group-hover/pinyin:opacity-100 transition-opacity bg-white dark:bg-slate-900 px-1 py-1 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                                                                            <template x-for="tone in ['ā','á','ǎ','à','a']" :key="tone">
                                                                                <button type="button" @mousedown.prevent="insertCharAtCursor(tone, group, 'ex-a-pinyin-' + group.id, '_ex_a_pinyin')"
                                                                                    class="w-5 h-5 rounded flex items-center justify-center text-[10px] font-bold text-slate-600 hover:bg-slate-100 hover:text-primary transition-colors" x-text="tone"></button>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-emerald-600/70 uppercase mb-1">Chữ Hán</label>
                                                                    <textarea rows="2" x-model="group._ex_a_hanzi" @input="updateExampleText(group)"
                                                                        placeholder="Ví dụ: 好的，谢谢！"
                                                                        class="w-full text-sm font-bold p-2 rounded-lg border border-emerald-200 dark:border-emerald-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white resize-y"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Preview -->
                                                        <div class="pt-2 border-t border-amber-200/50 dark:border-amber-900/50">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-2">Xem trước hiển thị</div>
                                                            <div class="space-y-3">
                                                                <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-bold text-slate-800 dark:text-slate-200 shadow-sm flex items-center gap-2" x-html="group._ex_q_html || ''"></div>
                                                                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border-2 border-emerald-500 shadow-sm flex items-center justify-between">
                                                                    <div class="flex items-center gap-3">
                                                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-xs font-black" x-text="group._ex_a_letter || 'F'"></span>
                                                                        <div class="text-sm font-bold text-slate-900 dark:text-white" x-html="group._ex_a_html || ''"></div>
                                                                    </div>
                                                                    <span class="text-emerald-600 text-[11px] font-black">✓ ĐÁP ÁN ĐÚNG</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- EXAMPLE CARD FOR LISTENING PART 4 -->
                                            <template x-if="section.skill_type === 'listening' && gIndex === 3">
                                                <div class="p-5 bg-amber-50/40 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-900/50 space-y-4 shadow-sm">
                                                    <div class="flex items-center justify-between border-b border-amber-200/60 dark:border-amber-900/50 pb-3">
                                                        <div class="flex items-center gap-3">
                                                            <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                Ví dụ (例如)
                                                            </span>
                                                            <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Câu Ví dụ mẫu của Part 4 (Nghe hiểu)</span>
                                                        </div>
                                                        <span class="text-xs text-amber-600 font-bold uppercase">Mẫu Đề</span>
                                                    </div>

                                                    <div class="space-y-3">
                                                        <!-- Example Dialogue Text -->
                                                        <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-amber-200/80 dark:border-slate-800 space-y-2 shadow-sm">
                                                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-relaxed space-y-1.5">
                                                                <div><ruby>下<rt>Xià</rt></ruby><ruby>午<rt>wǔ</rt></ruby> <ruby>我<rt>wǒ</rt></ruby> <ruby>去<rt>qù</rt></ruby> <ruby>商<rt>shāng</rt></ruby><ruby>店<rt>diàn</rt></ruby> ， <ruby>我<rt>wǒ</rt></ruby> <ruby>想<rt>xiǎng</rt></ruby> <ruby>买<rt>mǎi</rt></ruby> <ruby>一<rt>yì</rt></ruby><ruby>些<rt>xiē</rt></ruby> <ruby>水<rt>shuǐ</rt></ruby><ruby>果<rt>guǒ</rt></ruby> 。</div>
                                                                <div><span class="text-slate-500 font-bold">问：</span> <ruby>她<rt>Tā</rt></ruby> <ruby>下<rt>xià</rt></ruby><ruby>午<rt>wǔ</rt></ruby> <ruby>去<rt>qù</rt></ruby> <ruby>哪<rt>nǎ</rt></ruby><ruby>里<rt>li</rt></ruby> ？</div>
                                                            </div>
                                                        </div>

                                                        <!-- Example Options A, B, C -->
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                            <!-- Option A (Correct) -->
                                                            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border-2 border-emerald-500 flex flex-col items-center justify-between text-center shadow-sm">
                                                                <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-emerald-200">
                                                                    <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[11px] font-black">A</span>
                                                                    <span class="text-xs font-black text-emerald-600">✓ ĐÁP ÁN ĐÚNG (MẪU)</span>
                                                                </div>
                                                                <div class="text-base font-bold text-slate-900 dark:text-white py-2">
                                                                    <ruby>商<rt>shāng</rt></ruby><ruby>店<rt>diàn</rt></ruby>
                                                                </div>
                                                            </div>
                                                            <!-- Option B -->
                                                            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-between text-center opacity-60">
                                                                <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-slate-100 dark:border-slate-800">
                                                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 text-[11px] font-black">B</span>
                                                                </div>
                                                                <div class="text-base font-bold text-slate-900 dark:text-white py-2">
                                                                    <ruby>医<rt>yī</rt></ruby><ruby>院<rt>yuàn</rt></ruby>
                                                                </div>
                                                            </div>
                                                            <!-- Option C -->
                                                            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-between text-center opacity-60">
                                                                <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-slate-100 dark:border-slate-800">
                                                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 text-[11px] font-black">C</span>
                                                                </div>
                                                                <div class="text-base font-bold text-slate-900 dark:text-white py-2">
                                                                    <ruby>学<rt>xué</rt></ruby><ruby>校<rt>xiào</rt></ruby>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- EXAMPLE CARD FOR READING PART 4 -->
                                            <template x-if="section.skill_type === 'reading' && gIndex === 3">
                                                <div class="p-5 bg-amber-50/40 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-900/50 space-y-4 shadow-sm mb-4">
                                                    <div class="flex items-center justify-between border-b border-amber-200/60 dark:border-amber-900/50 pb-3">
                                                        <div class="flex items-center gap-3">
                                                            <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                Ví dụ (例如)
                                                            </span>
                                                            <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Chỉnh sửa Câu Ví dụ mẫu của Part 4 (Điền vào chỗ trống)</span>
                                                        </div>
                                                        <span class="text-xs text-amber-600 font-bold uppercase">Mẫu Đề</span>
                                                    </div>

                                                    <div class="space-y-4">
                                                        <div class="p-4 bg-white dark:bg-slate-900 rounded-xl border border-amber-200/70 dark:border-slate-800 shadow-sm space-y-3">
                                                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                                                    Nội dung câu hỏi mẫu
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-xs font-bold text-emerald-600">Đáp án:</span>
                                                                    <input type="text" x-model="group._ex_a_letter" @input="updatePart4Text(group)"
                                                                        class="w-8 h-6 text-center text-[10px] font-black rounded border border-emerald-300 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 uppercase"
                                                                        maxlength="1" placeholder="D">
                                                                </div>
                                                            </h4>
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Phiên âm Pinyin</label>
                                                                    <div class="relative flex items-center group/pinyin">
                                                                        <textarea rows="2" x-model="group._ex_q_pinyin" :id="'ex4-q-pinyin-' + group.id"
                                                                            @input="group._ex_q_pinyin = convertPinyinToneNumbers(group._ex_q_pinyin); updatePart4Text(group)"
                                                                            placeholder="Ví dụ: Nǐ jiào shénme"
                                                                            class="w-full text-sm font-medium p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white resize-y"></textarea>
                                                                        <div class="absolute right-1.5 flex items-center gap-0.5 opacity-0 group-hover/pinyin:opacity-100 transition-opacity bg-white dark:bg-slate-900 px-1 py-1 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                                                                            <template x-for="tone in ['ā','á','ǎ','à','a']" :key="tone">
                                                                                <button type="button" @mousedown.prevent="insertCharAtCursor(tone, group, 'ex4-q-pinyin-' + group.id, '_ex_q_pinyin')"
                                                                                    class="w-5 h-5 rounded flex items-center justify-center text-[10px] font-bold text-slate-600 hover:bg-slate-100 hover:text-primary transition-colors" x-text="tone"></button>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Chữ Hán (Dùng (   ) cho chỗ trống)</label>
                                                                    <textarea rows="2" x-model="group._ex_q_hanzi" @input="updatePart4Text(group)"
                                                                        placeholder="Ví dụ: 你叫什么 (   ) ？"
                                                                        class="w-full text-sm font-bold p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white resize-y"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Preview -->
                                                        <div class="pt-2 border-t border-amber-200/50 dark:border-amber-900/50">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-2">Xem trước hiển thị</div>
                                                            <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-bold text-slate-800 dark:text-slate-200 shadow-sm flex items-center gap-2">
                                                                <span x-html="group._ex_q_html || ''"></span>
                                                                <span class="text-emerald-600 px-1 font-black">( <span x-text="group._ex_a_letter || 'D'"></span> )</span>
                                                                <span>?</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Questions inside Group -->
                                            <template x-for="(question, qIndex) in group.questions" :key="question.id">
                                                <div :id="'question-edit-' + question.order_index" class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4 shadow-sm hover:border-slate-300 transition-colors">
                                                    
                                                    <!-- Question Header -->
                                                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                                                        <div class="flex items-center gap-3">
                                                            <span class="px-3 py-1 rounded-lg bg-amber-500 text-white text-xs font-black shadow-sm">
                                                                Câu <span x-text="question.order_index"></span>
                                                            </span>
                                                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400"
                                                                x-text="question.question_type === 'true_false' ? 'Dạng Đúng / Sai (True / False)' : 'Dạng Trắc nghiệm chọn 1 đáp án'"></span>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 text-xs font-bold text-emerald-700 dark:text-emerald-300 shadow-sm">
                                                                <span>Đáp án đúng hiện tại:</span>
                                                                <span class="font-black text-emerald-600 dark:text-emerald-400" x-text="getCorrectOptionLabel(question)"></span>
                                                            </div>
                                                            <span class="text-xs text-slate-400 font-mono">ID: #<span x-text="question.id"></span></span>
                                                        </div>
                                                    </div>

                                                    <!-- Question Layout: Left Image + Right Inputs -->
                                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start pt-1">
                                                        
                                                        <!-- Question Image Thumbnail & Controls (Shows if question has image OR in Reading section (Parts 1-2) / True-False, EXCEPT for Simple Letter Options) -->
                                                        <template x-if="!isSimpleLetterOptions(question) && (question.image || question.question_type === 'true_false' || (section.skill_type === 'reading' && gIndex < 2))">
                                                            <div class="md:col-span-4 p-3 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700 flex flex-col items-center text-center space-y-2 shadow-sm">
                                                                <template x-if="question.image">
                                                                    <div class="w-full flex flex-col items-center space-y-1.5">
                                                                        <img :src="getImageUrl(question.image)" class="h-36 object-contain rounded-xl border bg-white dark:bg-slate-900 p-1 shadow-sm" alt="Question Image">
                                                                        <span class="text-[10px] text-slate-400 font-mono truncate w-full" x-text="question.image.split('/').pop()"></span>
                                                                    </div>
                                                                </template>
                                                                
                                                                <template x-if="!question.image">
                                                                    <div class="h-32 w-full flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 text-slate-400 p-4">
                                                                        <span class="material-symbols-outlined text-3xl mb-1">image_not_supported</span>
                                                                        <span class="text-xs font-semibold">Chưa có hình ảnh câu hỏi</span>
                                                                    </div>
                                                                </template>

                                                                <!-- Hidden File Input for Question Image -->
                                                                <input type="file" accept="image/*" class="hidden" :id="'q-img-input-' + question.id" @change="uploadLocalImage($event, question)">

                                                                <!-- Action buttons: Direct Upload from Local Computer -->
                                                                <div class="flex items-center gap-2 pt-1 w-full justify-center">
                                                                    <button type="button" @click="triggerFileInput('q-img-input-' + question.id)"
                                                                        class="px-3 py-1.5 bg-primary text-white hover:bg-primary/90 text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-1.5 cursor-pointer">
                                                                        <span class="material-symbols-outlined text-sm font-bold">cloud_upload</span>
                                                                        <span x-text="question.image ? 'Đổi ảnh từ máy tính' : 'Tải ảnh từ máy tính'"></span>
                                                                    </button>

                                                                    <template x-if="question.image">
                                                                        <button type="button" @click="question.image = null"
                                                                            class="px-2.5 py-1.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-xl hover:bg-rose-100 transition-colors flex items-center gap-1">
                                                                            <span class="material-symbols-outlined text-sm font-bold">delete</span>
                                                                            Xóa ảnh
                                                                        </button>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <!-- Question Content Inputs -->
                                                        <div :class="(!isSimpleLetterOptions(question) && (question.image || question.question_type === 'true_false' || (section.skill_type === 'reading' && gIndex < 2))) ? 'md:col-span-8 space-y-3' : 'md:col-span-12 space-y-3'">
                                                            
                                                            <!-- Question Title / Text (Always editable for all question types) -->
                                                            <template x-if="true">
                                                                <div class="space-y-3 p-3.5 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-200 dark:border-slate-700/60">
                                                                    <div class="flex items-center gap-2 mb-1">
                                                                        <span class="w-1.5 h-4 bg-primary rounded-full"></span>
                                                                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Nội dung câu hỏi (Text)</h4>
                                                                    </div>
                                                                    
                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phiên âm Pinyin (Nhấn Enter để xuống dòng)</label>
                                                                            <div class="relative flex items-start group/pinyin">
                                                                                <textarea rows="2" x-model="question._pinyin" :id="'q-pinyin-input-' + question.id"
                                                                                    @input="question._pinyin = convertPinyinToneNumbers(question._pinyin); updateQuestionTitleFromInputs(question)"
                                                                                    placeholder="Ví dụ: Nǚ: Nǐ hē shuǐ ma ?&#10;Nán: Hǎo de"
                                                                                    class="w-full text-sm font-medium p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-inner focus:ring-2 focus:ring-primary/20 transition-all resize-y"></textarea>
                                                                                <!-- Pinyin Tone Shortcuts -->
                                                                                <div class="absolute right-1.5 top-1.5 flex items-center gap-0.5 opacity-0 group-hover/pinyin:opacity-100 transition-opacity bg-white dark:bg-slate-900 px-1 py-1 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                                                                                    <template x-for="tone in ['ā','á','ǎ','à','a']" :key="tone">
                                                                                        <button type="button" @mousedown.prevent="insertCharAtCursor(tone, question, 'q-pinyin-input-' + question.id)"
                                                                                            class="w-6 h-6 rounded flex items-center justify-center text-[10px] font-bold text-slate-600 hover:bg-slate-100 hover:text-primary transition-colors" x-text="tone"></button>
                                                                                    </template>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div>
                                                                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Chữ Hán (Nhấn Enter để xuống dòng)</label>
                                                                            <textarea rows="2" x-model="question._hanzi" 
                                                                                @input="updateQuestionTitleFromInputs(question)"
                                                                                placeholder="Ví dụ: 女：你喝水吗？&#10;男：好的"
                                                                                class="w-full text-sm font-bold p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-inner focus:ring-2 focus:ring-primary/20 transition-all resize-y"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <!-- TRUE / FALSE OPTIONS (For true_false questions) -->
                                                            <template x-if="question.question_type === 'true_false'">
                                                                <div class="space-y-2">
                                                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                                                        Chọn đáp án ĐÚNG cho câu hỏi này
                                                                    </label>
                                                                    <div class="grid grid-cols-2 gap-4 max-w-md">
                                                                        <!-- TRUE Button -->
                                                                        <button type="button" @click="setTrueFalseOption(question, true)"
                                                                            class="relative p-3.5 rounded-2xl border-2 transition-all duration-200 flex items-center justify-between font-bold text-xs overflow-hidden group shadow-sm"
                                                                            :class="isTrueCorrect(question) 
                                                                                ? 'border-emerald-500 bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 scale-[1.02]' 
                                                                                : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 hover:border-emerald-400 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20'">
                                                                            <div class="flex items-center gap-3">
                                                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-base transition-transform group-hover:scale-110"
                                                                                    :class="isTrueCorrect(question) ? 'bg-white/20 text-white' : 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400'">
                                                                                    ✓
                                                                                </div>
                                                                                <div class="flex flex-col text-left">
                                                                                    <span class="text-xs font-black tracking-wider uppercase" :class="isTrueCorrect(question) ? 'text-white' : 'text-slate-800 dark:text-slate-200'">ĐÚNG</span>
                                                                                    <span class="text-[10px] font-semibold" :class="isTrueCorrect(question) ? 'text-emerald-100' : 'text-slate-400'">TRUE (√)</span>
                                                                                </div>
                                                                            </div>
                                                                            <template x-if="isTrueCorrect(question)">
                                                                                <span class="px-2.5 py-1 rounded-full bg-white/20 text-[10px] font-black uppercase tracking-wider backdrop-blur-sm">✓ ĐÃ CHỌN</span>
                                                                            </template>
                                                                        </button>

                                                                        <!-- FALSE Button -->
                                                                        <button type="button" @click="setTrueFalseOption(question, false)"
                                                                            class="relative p-3.5 rounded-2xl border-2 transition-all duration-200 flex items-center justify-between font-bold text-xs overflow-hidden group shadow-sm"
                                                                            :class="!isTrueCorrect(question) 
                                                                                ? 'border-emerald-500 bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 scale-[1.02]' 
                                                                                : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 hover:border-emerald-400 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20'">
                                                                            <div class="flex items-center gap-3">
                                                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-base transition-transform group-hover:scale-110"
                                                                                    :class="!isTrueCorrect(question) ? 'bg-white/20 text-white' : 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400'">
                                                                                    ✕
                                                                                </div>
                                                                                <div class="flex flex-col text-left">
                                                                                    <span class="text-xs font-black tracking-wider uppercase" :class="!isTrueCorrect(question) ? 'text-white' : 'text-slate-800 dark:text-slate-200'">SAI</span>
                                                                                    <span class="text-[10px] font-semibold" :class="!isTrueCorrect(question) ? 'text-emerald-100' : 'text-slate-400'">FALSE (×)</span>
                                                                                </div>
                                                                            </div>
                                                                            <template x-if="!isTrueCorrect(question)">
                                                                                <span class="px-2.5 py-1 rounded-full bg-white/20 text-[10px] font-black uppercase tracking-wider backdrop-blur-sm">✓ ĐÃ CHỌN</span>
                                                                            </template>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <!-- OPTION IMAGES (For questions where choices A, B, C are images, like Part 2) -->
                                                            <template x-if="question.question_type !== 'true_false' && hasOptionImages(question)">
                                                                <div class="space-y-2 pt-1">
                                                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                                                        Hình ảnh các lựa chọn A, B, C <span class="text-emerald-600 font-bold">(Click chọn ô Đáp án ĐÚNG)</span>
                                                                    </label>
                                                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                                        <template x-for="(option, oIndex) in question.options" :key="option.id">
                                                                            <div @click="setCorrectOption(question, option)"
                                                                                class="p-4 rounded-2xl border-2 transition-all duration-200 flex flex-col items-center justify-between text-center cursor-pointer shadow-sm group"
                                                                                :class="option.is_correct 
                                                                                    ? 'border-emerald-500 bg-emerald-50/80 dark:bg-emerald-950/40 ring-2 ring-emerald-500/30 scale-[1.02]' 
                                                                                    : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300'">
                                                                                
                                                                                <div class="flex items-center justify-between w-full pb-2 border-b border-slate-100 dark:border-slate-800">
                                                                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black"
                                                                                        :class="option.is_correct ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300'"
                                                                                        x-text="'Đáp án ' + (option.content || String.fromCharCode(65 + oIndex))"></span>
                                                                                    
                                                                                    <span class="text-xs font-bold"
                                                                                        :class="option.is_correct ? 'text-emerald-600 dark:text-emerald-400 font-black' : 'text-slate-400'">
                                                                                        <span x-text="option.is_correct ? '✓ ĐÁP ÁN ĐÚNG' : 'Chọn ô này'"></span>
                                                                                    </span>
                                                                                </div>

                                                                                <img :src="getImageUrl(option.image || option.image_url)" class="h-40 object-contain my-3 rounded-xl border bg-white dark:bg-slate-900 p-1" alt="Option Image">

                                                                                <input type="file" accept="image/*" class="hidden" :id="'opt-img-' + option.id" @change="uploadLocalImage($event, option)">
                                                                                <button type="button" @click.stop="triggerFileInput('opt-img-' + option.id)"
                                                                                    class="mt-1 px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 transition-all flex items-center gap-1.5 cursor-pointer shadow-sm">
                                                                                    <span class="material-symbols-outlined text-sm font-bold">cloud_upload</span>
                                                                                    Đổi ảnh từ máy tính
                                                                                </button>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <!-- SIMPLE LETTER OPTIONS (For A, B, C, D, E, F text-only choices without images) -->
                                                            <template x-if="question.question_type !== 'true_false' && !hasOptionImages(question) && isSimpleLetterOptions(question)">
                                                                <div class="space-y-2 pt-1">
                                                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                                                        Chọn đáp án ĐÚNG cho câu hỏi này
                                                                    </label>
                                                                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                                                        <template x-for="(option, oIndex) in question.options" :key="option.id">
                                                                            <button type="button" @click="setCorrectOption(question, option)"
                                                                                class="p-3 rounded-2xl border-2 transition-all duration-200 flex flex-col items-center justify-center font-bold shadow-sm cursor-pointer min-h-[4rem]"
                                                                                :class="option.is_correct 
                                                                                    ? 'border-emerald-500 bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 scale-[1.02]' 
                                                                                    : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 hover:border-emerald-400'">
                                                                                <div class="flex items-center gap-2">
                                                                                    <span class="text-xl font-black" x-text="option.content"></span>
                                                                                    <template x-if="option.is_correct">
                                                                                        <span class="material-symbols-outlined text-base font-bold">check_circle</span>
                                                                                    </template>
                                                                                </div>
                                                                            </button>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <!-- MULTIPLE CHOICE OPTIONS WITH TEXT (For questions with text content options) -->
                                                            <template x-if="question.question_type !== 'true_false' && !hasOptionImages(question) && !isSimpleLetterOptions(question)">
                                                                <div class="space-y-2 pt-1">
                                                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                                                        Nội dung các đáp án lựa chọn <span class="text-emerald-600 font-bold">(Click chọn ô Đáp án ĐÚNG)</span>
                                                                    </label>
                                                                    
                                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                                        <template x-for="(option, oIndex) in question.options" :key="option.id">
                                                                            <div class="p-3.5 rounded-2xl border-2 transition-all cursor-pointer space-y-3"
                                                                                @click="setCorrectOption(question, option)"
                                                                                :class="option.is_correct ? 'border-emerald-500 bg-emerald-50/60 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20 shadow-sm' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300'">
                                                                                
                                                                                <!-- Header: Badge A/B/C + Correct Status -->
                                                                                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60 dark:border-slate-700/60">
                                                                                    <div class="flex items-center gap-2">
                                                                                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-black"
                                                                                            :class="option.is_correct ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'"
                                                                                            x-text="'Đáp án ' + String.fromCharCode(65 + oIndex)"></span>
                                                                                    </div>
                                                                                    <span class="text-xs font-bold" :class="option.is_correct ? 'text-emerald-600 dark:text-emerald-400 font-black' : 'text-slate-400'">
                                                                                        <span x-text="option.is_correct ? '✓ ĐÁP ÁN ĐÚNG' : 'Chọn ô này'"></span>
                                                                                    </span>
                                                                                </div>

                                                                                <!-- Clean Text Input -->
                                                                                <div>
                                                                                    <label class="block text-[11px] font-semibold text-slate-400 mb-1">Phiên âm Pinyin:</label>
                                                                                    <div class="relative flex items-center group/pinyin mb-2">
                                                                                        <textarea rows="2" x-model="option._pinyin" @click.stop :id="'opt-pinyin-input-' + option.id"
                                                                                            @input="option._pinyin = convertPinyinToneNumbers(option._pinyin); updateOptionContentFromInputs(option)"
                                                                                            placeholder="Ví dụ: xie3 hoặc hen3 hao3"
                                                                                            class="w-full text-sm font-medium p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-inner focus:ring-2 focus:ring-primary/20 resize-y"></textarea>
                                                                                        <!-- Pinyin Tone Shortcuts -->
                                                                                        <div class="absolute right-1.5 flex items-center gap-0.5 opacity-0 group-hover/pinyin:opacity-100 transition-opacity bg-white dark:bg-slate-900 px-1 py-1 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                                                                                            <template x-for="tone in ['ā','á','ǎ','à','a']" :key="tone">
                                                                                                <button type="button" @mousedown.prevent="insertCharAtCursor(tone, option, 'opt-pinyin-input-' + option.id, '_pinyin')"
                                                                                                    class="w-6 h-6 rounded flex items-center justify-center text-[10px] font-bold text-slate-600 hover:bg-slate-100 hover:text-primary transition-colors" x-text="tone"></button>
                                                                                            </template>
                                                                                        </div>
                                                                                    </div>

                                                                                    <label class="block text-[11px] font-semibold text-slate-400 mb-1">Chữ Hán / Nội dung:</label>
                                                                                    <textarea rows="2" x-model="option._hanzi" @click.stop @input="updateOptionContentFromInputs(option)"
                                                                                        placeholder="Ví dụ: 写 hoặc 很好"
                                                                                        class="w-full text-sm font-bold p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-inner resize-y"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            </div> <!-- End Main Content Area -->

            <!-- Fixed Sidebar Navigator -->
            <template x-if="!loading && exam">
                <aside class="hidden xl:flex w-72 shrink-0 flex-col h-full pb-12">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-full overflow-hidden">
                        <div class="p-4 border-b border-slate-200 dark:border-slate-800">
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm">Điều hướng câu hỏi</h3>
                            <p class="text-[11px] text-slate-500 mt-1">Click để cuộn nhanh đến câu hỏi</p>
                        </div>
                        <div class="flex-1 overflow-y-auto p-4 space-y-5 custom-scrollbar">
                            <template x-for="(section, sIdx) in exam.sections" :key="section.id">
                                <div>
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <span class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
                                        <p class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider leading-none" x-text="'Phần ' + (sIdx + 1) + ': ' + section.name"></p>
                                    </div>
                                    <template x-for="(group, gIdx) in (section.question_groups || section.questionGroups || [])" :key="group.id">
                                        <div class="mb-3" x-show="group.questions && group.questions.length > 0">
                                            <p class="text-[10px] font-bold text-slate-400/80 dark:text-slate-600 uppercase mb-1.5 pl-0.5" x-text="'Part ' + (gIdx + 1)"></p>
                                            <div class="grid grid-cols-5 gap-1.5">
                                                <template x-for="q in group.questions" :key="q.id">
                                                    <button type="button" @click="scrollToQuestion(q.order_index)"
                                                        class="w-full aspect-square rounded-lg text-xs font-bold flex items-center justify-center border transition-all duration-150 hover:scale-105 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-primary/50 hover:text-primary"
                                                        x-text="q.order_index">
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </aside>
            </template>
            </div> <!-- End max-w container -->
        </div> <!-- End scrollable area wrapper -->

    </main>

    <script>
        function hskExamEditor(examId) {
            return {
                examId: examId,
                exam: null,
                loading: true,
                saving: false,
                rubyModal: {
                    show: false,
                    targetObj: null,
                    targetField: '',
                    hanzi: '',
                    pinyin: ''
                },
                init() {
                    this.loadData();
                },
                scrollToQuestion(index) {
                    const el = document.getElementById('question-edit-' + index);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        el.style.transition = 'box-shadow 0.3s ease';
                        el.style.boxShadow = '0 0 0 3px rgba(232, 146, 122, 0.5)';
                        setTimeout(() => { el.style.boxShadow = ''; }, 1200);
                    }
                },
                loadData() {
                    this.loading = true;
                    const url = "{{ request()->is('teacher*') ? route('teacher.hsk-mock-exams.editor-data', $hskMockExam->id) : route('admin.hsk-mock-exams.editor-data', $hskMockExam->id) }}";
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                this.cleanExamData(data.exam);
                                this.exam = data.exam;
                            }
                            this.loading = false;
                        })
                        .catch(err => {
                            alert('Không thể tải dữ liệu đề thi!');
                            this.loading = false;
                        });
                },
                stripHtmlTags(str) {
                    if (!str || typeof str !== 'string') return str || '';
                    
                    let text = str;
                    
                    // 1. Decode common HTML entities FIRST so tags become real < >
                    text = text.replace(/&lt;/g, '<')
                               .replace(/&gt;/g, '>')
                               .replace(/&nbsp;/g, ' ')
                               .replace(/&amp;/g, '&');
                               
                    // 2. Remove all <rt>...</rt> tags and their contents (removes Pinyin)
                    text = text.replace(/<rt[^>]*>.*?<\/rt>/gi, '');
                    
                    // 3. Remove all remaining HTML tags (like <ruby>, </ruby>)
                    text = text.replace(/<[^>]+>/g, '');
                    
                    // 4. Remove leading A, B, C, D... prefix with optional space or dot
                    return text.replace(/^[A-F][\s\.]*/i, '').trim();
                },
                convertPinyinToneNumbers(str) {
                    if (!str || typeof str !== 'string') return str || '';

                    const toneMap = {
                        'a': ['ā', 'á', 'ǎ', 'à', 'a'],
                        'e': ['ē', 'é', 'ě', 'è', 'e'],
                        'i': ['ī', 'í', 'ǐ', 'ì', 'i'],
                        'o': ['ō', 'ó', 'ǒ', 'ò', 'o'],
                        'u': ['ū', 'ú', 'ǔ', 'ù', 'u'],
                        'v': ['ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü'],
                        'ü': ['ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü']
                    };

                    let result = str.replace(/u:/gi, 'v');

                    result = result.replace(/([a-zA-ZvüÜ]+)([1-5])/gi, (match, syllable, toneNumStr) => {
                        const tone = parseInt(toneNumStr, 10);
                        if (tone === 5 || tone === 0) return syllable;

                        let lower = syllable.toLowerCase();
                        let toneIdx = tone - 1;

                        let targetVowel = '';
                        if (lower.includes('a')) targetVowel = 'a';
                        else if (lower.includes('e')) targetVowel = 'e';
                        else if (lower.includes('ou')) targetVowel = 'o';
                        else {
                            const vowels = lower.match(/[aeiouvü]/g);
                            if (vowels && vowels.length > 0) {
                                targetVowel = vowels[vowels.length - 1];
                            }
                        }

                        if (!targetVowel || !toneMap[targetVowel]) return match;

                        const markedVowel = toneMap[targetVowel][toneIdx];
                        
                        let replaced = false;
                        let finalSyllable = '';
                        for (let i = 0; i < syllable.length; i++) {
                            const char = syllable[i];
                            if (!replaced && char.toLowerCase() === targetVowel) {
                                const isUpper = char === char.toUpperCase() && char !== char.toLowerCase();
                                finalSyllable += isUpper ? markedVowel.toUpperCase() : markedVowel;
                                replaced = true;
                            } else {
                                finalSyllable += char;
                            }
                        }
                        return finalSyllable;
                    });

                    return result;
                },
                parseContentToPinyinAndHanzi(obj, fieldName = 'title') {
                    if (!obj) return;
                    if (!obj[fieldName]) return;

                    const str = obj[fieldName];
                    
                    // If the string is EXACTLY a single letter like 'A', 'B', 'C' (with or without a dot), preserve it!
                    const letterMatch = str.trim().match(/^([A-F])\.?$/i);
                    if (letterMatch) {
                        obj._hanzi = letterMatch[1].toUpperCase(); // Normalize to just 'A', 'B' etc.
                        return;
                    }
                    
                    // Pre-clean: strip leading A, B, C, D... prefix with optional space or dot
                    let cleanStr = str.replace(/^[A-F][\s\.]*/i, '').trim();

                    // Decode HTML entities FIRST
                    let decoded = cleanStr.replace(/&lt;/g, '<')
                                          .replace(/&gt;/g, '>')
                                          .replace(/&nbsp;/g, ' ')
                                          .replace(/&amp;/g, '&');

                    if (/<ruby[\s>]/i.test(decoded) || /<div class="text-xs/i.test(decoded)) {
                        const lineHtmls = decoded.split(/<div class="w-full h-0 basis-full my-1"><\/div>/i);
                        let pyLines = [];
                        let hzLines = [];
                        
                        lineHtmls.forEach(lineHtml => {
                            const lineTmp = document.createElement('DIV');
                            lineTmp.innerHTML = lineHtml;
                            
                            if (lineTmp.querySelector('ruby')) {
                                let pyLine = [];
                                let hzLine = [];
                                lineTmp.querySelectorAll('ruby').forEach(r => {
                                    const rt = r.querySelector('rt');
                                    if (rt) {
                                        pyLine.push(rt.textContent.trim());
                                        rt.remove();
                                    }
                                    const hanzi = (r.textContent || r.innerText || '').trim();
                                    if (hanzi) hzLine.push(hanzi);
                                });
                                pyLines.push(pyLine.join(' '));
                                hzLines.push(hzLine.join(''));
                            } else if (lineTmp.querySelector('.text-xs') && lineTmp.querySelector('.text-base')) {
                                pyLines.push(lineTmp.querySelector('.text-xs').textContent.trim());
                                hzLines.push(lineTmp.querySelector('.text-base').textContent.trim());
                            } else {
                                pyLines.push('');
                                hzLines.push(lineTmp.textContent.trim());
                            }
                        });
                        
                        if (pyLines.length > 0) obj._pinyin = pyLines.join('\n');
                        if (hzLines.length > 0) obj._hanzi = hzLines.join('\n');
                    } else {
                        const match = cleanStr.trim().match(/^([a-zA-ZāáǎàēéěèīíǐìōóǒòūúǔùǖǘǚǜĀÁǍÀĒÉĚÈĪÍǏÌŌÓǑÒŪÚǓÙǕǗǙǛ’'\s]+)\s+([\u4e00-\u9fa5\s]+)$/);
                        if (match) {
                            obj._pinyin = match[1].trim();
                            obj._hanzi = match[2].trim().replace(/\s+/g, '');
                        } else if (/[\u4e00-\u9fa5]/.test(cleanStr)) {
                            obj._hanzi = cleanStr.trim();
                        } else {
                            if (!obj._pinyin && !obj._hanzi) {
                                obj._pinyin = cleanStr.trim();
                            }
                        }
                    }
                },
                updateOptionContentFromInputs(opt) {
                    if (!opt) return;
                    opt.content = this.buildRubyText(opt._pinyin, opt._hanzi);
                },
                buildRubyText(pinyinStr, hanziStr) {
                    pinyinStr = (pinyinStr || '').trim();
                    hanziStr = (hanziStr || '').trim();
                    if (!pinyinStr && !hanziStr) return '';
                    if (!pinyinStr) return hanziStr.replace(/\n/g, '<br>');
                    if (!hanziStr) return pinyinStr.replace(/\n/g, '<br>');

                    if (pinyinStr.includes('\n') || hanziStr.includes('\n')) {
                        const pyLines = pinyinStr.split('\n');
                        const hzLines = hanziStr.split('\n');
                        const maxLines = Math.max(pyLines.length, hzLines.length);
                        const lines = [];
                        for (let l = 0; l < maxLines; l++) {
                            lines.push(this.buildRubyText(pyLines[l], hzLines[l]));
                        }
                        return lines.join('<div class="w-full h-0 basis-full my-1"></div>');
                    }

                    const validPinyins = pinyinStr.match(/(?:[a-zA-Z]{1,3})?[aeiouüāáǎàēéěèīíǐìōóǒòūúǔùǖǘǚǜAEIOUÜĀÁǍÀĒÉĚÈĪÍǏÌŌÓǑÒŪÚǓÙǕǗǙǛ]+(?:ng|n|r)?/gi) || [];
                    const chars = Array.from(hanziStr.replace(/[ \t]+/g, ''));
                    
                    const chineseCharCount = chars.filter(char => /[\u4e00-\u9fa5]/u.test(char)).length;

                    if (validPinyins.length === chineseCharCount && chineseCharCount > 0) {
                        let out = '';
                        let pIdx = 0;
                        chars.forEach((char) => {
                            if (char === "\n") {
                                out += '<div class="w-full h-0 basis-full"></div>';
                            } else if (/[\u4e00-\u9fa5]/.test(char)) {
                                out += '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-0.5"><span class="text-base font-black text-slate-900 dark:text-white">' + char + '</span><rt class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-0.5 select-none">' + validPinyins[pIdx++] + '</rt></ruby>';
                            } else if (/[a-zA-Z0-9]/.test(char)) {
                                out += '<span class="mx-1 text-base font-black text-slate-900 dark:text-white">' + char + '</span>';
                            } else {
                                out += '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-0.5"><span class="text-base font-black text-slate-900 dark:text-white">' + char + '</span><rt class="text-[11px] font-bold text-transparent mb-0.5 select-none">.</rt></ruby>';
                            }
                        });
                        return out;
                    }

                    // Fallback
                    return '<div><div class="text-xs text-slate-500 mb-1 leading-none">' + pinyinStr + '</div><div class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-widest">' + hanziStr + '</div></div>';
                },
                updateExampleText(group) {
                    if (!group) return;
                    
                    const qHtml = this.buildRubyText(group._ex_q_pinyin, group._ex_q_hanzi);
                    const aHtml = this.buildRubyText(group._ex_a_pinyin, group._ex_a_hanzi);
                    
                    group._ex_q_html = qHtml;
                    group._ex_a_html = aHtml;
                    
                    group.passage_text = JSON.stringify({
                        q_pinyin: group._ex_q_pinyin || '',
                        q_hanzi: group._ex_q_hanzi || '',
                        a_letter: group._ex_a_letter || 'F',
                        a_pinyin: group._ex_a_pinyin || '',
                        a_hanzi: group._ex_a_hanzi || '',
                        q_html: qHtml,
                        a_html: aHtml,
                        options: group._text_options || []
                    });
                },
                updatePart4Text(group) {
                    if (!group) return;

                    (group._text_options || []).forEach(opt => {
                        opt.html = this.buildRubyText(opt.pinyin, opt.hanzi);
                    });
                    
                    const qHtml = this.buildRubyText(group._ex_q_pinyin, group._ex_q_hanzi);
                    group._ex_q_html = qHtml;
                    
                    group.passage_text = JSON.stringify({
                        ex_q_pinyin: group._ex_q_pinyin || '',
                        ex_q_hanzi: group._ex_q_hanzi || '',
                        ex_q_html: qHtml,
                        ex_a_letter: group._ex_a_letter || 'D',
                        options: group._text_options
                    });
                },
                updateQuestionTitleFromInputs(q) {
                    if (!q) return;
                    q.title = this.buildRubyText(q._pinyin, q._hanzi);
                },
                insertCharAtCursor(char, question, inputId, fieldName = '_pinyin') {
                    const el = document.getElementById(inputId);
                    const currentVal = question[fieldName] || '';
                    if (!el) {
                        question[fieldName] = currentVal + char;
                        this.updateQuestionTitleFromInputs(question);
                        return;
                    }

                    const start = el.selectionStart !== null ? el.selectionStart : currentVal.length;
                    const end = el.selectionEnd !== null ? el.selectionEnd : start;

                    const newVal = currentVal.substring(0, start) + char + currentVal.substring(end);
                    question[fieldName] = this.convertPinyinToneNumbers(newVal);
                    this.updateQuestionTitleFromInputs(question);

                    this.$nextTick(() => {
                        el.focus();
                        const newPos = start + char.length;
                        el.setSelectionRange(newPos, newPos);
                    });
                },
                cleanExamData(exam) {
                    if (!exam || !exam.sections) return;
                    exam.sections.forEach(sec => {
                        const groups = sec.question_groups || sec.questionGroups || sec.groups || [];
                        groups.forEach((group, gIndex) => {
                            if ((sec.skill_type === 'reading' && gIndex === 2) || (sec.skill_type === 'listening' && gIndex === 2)) {
                                if (group.passage_text && group.passage_text.startsWith('{')) {
                                    try {
                                        let parsed = JSON.parse(group.passage_text);
                                        group._ex_q_pinyin = parsed.q_pinyin || '';
                                        group._ex_q_hanzi = parsed.q_hanzi || '';
                                        group._ex_a_letter = parsed.a_letter || (sec.skill_type === 'listening' ? 'C' : 'F');
                                        group._ex_a_pinyin = parsed.a_pinyin || '';
                                        group._ex_a_hanzi = parsed.a_hanzi || '';
                                        group._ex_q_html = parsed.q_html || '';
                                        group._ex_a_html = parsed.a_html || '';
                                        group._text_options = parsed.options || [];
                                    } catch(e) {}
                                }
                                if (!group._ex_q_pinyin && !group._ex_q_hanzi) {
                                    if (sec.skill_type === 'listening') {
                                        group._ex_q_pinyin = 'Nǐ hǎo ! qǐng wèn Zhāng lǎoshī zài ma ?';
                                        group._ex_q_hanzi = '你好！请问张老师在吗？';
                                        group._ex_a_letter = 'C';
                                        group._ex_a_pinyin = 'Tā zài , qǐng jìn .';
                                        group._ex_a_hanzi = ' coast 他在，请进。';
                                    } else {
                                        group._ex_q_pinyin = 'Nǐ hē shuǐ ma ?';
                                        group._ex_q_hanzi = '你喝水吗？';
                                        group._ex_a_letter = 'F';
                                        group._ex_a_pinyin = 'Hǎo de , xiè xie !';
                                        group._ex_a_hanzi = '好的，谢谢！';
                                    }
                                }
                                if (sec.skill_type === 'reading' && (!group._text_options || group._text_options.length === 0)) {
                                    group._text_options = [
                                        {pinyin: 'Zhōngguó rén', hanzi: '中国人'},
                                        {pinyin: '7 diǎn', hanzi: '7点'},
                                        {pinyin: 'Píngguǒ', hanzi: '苹果'},
                                        {pinyin: '20 kuài', hanzi: '20块'},
                                        {pinyin: 'Zuò chūzūchē', hanzi: '坐出租车'},
                                        {pinyin: 'Hǎo de', hanzi: '好的'}
                                    ];
                                }
                                this.updateExampleText(group);
                            }
                            if (sec.skill_type === 'reading' && gIndex === 3) {
                                if (group.passage_text && group.passage_text.startsWith('{')) {
                                    try {
                                        let parsed = JSON.parse(group.passage_text);
                                        group._ex_q_pinyin = parsed.ex_q_pinyin || '';
                                        group._ex_q_hanzi = parsed.ex_q_hanzi || '';
                                        group._ex_a_letter = parsed.ex_a_letter || 'D';
                                        group._ex_q_html = parsed.ex_q_html || '';
                                        group._text_options = parsed.options || [];
                                    } catch(e) {}
                                }
                                if (!group._text_options || group._text_options.length === 0) {
                                    group._ex_q_pinyin = 'Nǐ jiào shénme';
                                    group._ex_q_hanzi = '你叫什么 (   ) ？';
                                    group._ex_a_letter = 'D';
                                    group._text_options = [
                                        {pinyin: 'zuò', hanzi: '坐'},
                                        {pinyin: 'qiánmiàn', hanzi: '前面'},
                                        {pinyin: 'méi guānxi', hanzi: '没关系'},
                                        {pinyin: 'míngzi', hanzi: '名字'},
                                        {pinyin: 'Hànyǔ', hanzi: '汉语'},
                                        {pinyin: 'yuè', hanzi: '月'}
                                    ];
                                }
                                this.updatePart4Text(group);
                            }
                            if (group.questions) {
                                group.questions.forEach(q => {
                                    this.parseContentToPinyinAndHanzi(q, 'title');
                                    this.updateQuestionTitleFromInputs(q);
                                    
                                    if (q.options) {
                                        q.options.forEach((opt, oIdx) => {
                                            // If imported content is completely empty, default it to A, B, C based on index
                                            // ONLY for Reading Part 2 (gIndex 1, Q26-30) where options are strictly image matching
                                            if (!opt.content && sec.skill_type === 'reading' && gIndex === 1) {
                                                opt.content = String.fromCharCode(65 + oIdx);
                                            }
                                            this.parseContentToPinyinAndHanzi(opt, 'content');
                                            this.updateOptionContentFromInputs(opt);
                                        });
                                    }
                                });
                            }
                        });
                    });
                },
                getCorrectOptionLabel(question) {
                    if (!question || !question.options) return '';
                    const correctOpt = question.options.find(o => o.is_correct);
                    if (!correctOpt) return 'Chưa chọn';
                    let raw = correctOpt.content || '';
                    if (raw.includes('<ruby')) {
                        const tmp = document.createElement('DIV');
                        tmp.innerHTML = raw;
                        raw = tmp.textContent || tmp.innerText || raw;
                    }
                    return raw.trim() || 'Đã chọn';
                },
                getImageUrl(path) {
                    if (!path) return '';
                    if (path.startsWith('http')) return path;
                    if (path.startsWith('/storage/')) return path;
                    if (path.startsWith('storage/')) return '/' + path;
                    return '/storage/' + path;
                },
                triggerFileInput(elementId) {
                    const el = document.getElementById(elementId);
                    if (el) el.click();
                },
                uploadLocalImage(event, targetObj) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    const uploadUrl = "{{ request()->is('teacher*') ? route('teacher.hsk-mock-exams.upload-image') : route('admin.hsk-mock-exams.upload-image') }}";

                    this.saving = true;
                    fetch(uploadUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.saving = false;
                        if (data.status === 'success') {
                            targetObj.image = data.path;
                        } else {
                            alert('Lỗi tải ảnh: ' + (data.message || 'Không thể tải ảnh lên.'));
                        }
                    })
                    .catch(err => {
                        this.saving = false;
                        alert('Lỗi kết nối khi tải ảnh lên!');
                    });
                },
                uploadPassageImage(event, group, imgIdx) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    const uploadUrl = "{{ request()->is('teacher*') ? route('teacher.hsk-mock-exams.upload-image') : route('admin.hsk-mock-exams.upload-image') }}";

                    this.saving = true;
                    fetch(uploadUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.saving = false;
                        if (data.status === 'success') {
                            const list = this.getPassageImageList(group.passage_image);
                            list[imgIdx] = data.path;
                            group.passage_image = list.join(', ');
                        } else {
                            alert('Lỗi tải ảnh: ' + (data.message || 'Không thể tải ảnh lên.'));
                        }
                    })
                    .catch(err => {
                        this.saving = false;
                        alert('Lỗi kết nối khi tải ảnh lên!');
                    });
                },
                editQuestionImage(question) {
                    const current = question.image || '';
                    const newPath = prompt('Nhập đường dẫn hình ảnh cho câu hỏi (Ví dụ: hsk_mock_exams/H10901/images/page7_img3.jpeg):', current);
                    if (newPath !== null) {
                        question.image = newPath.trim() || null;
                    }
                },
                getPassageImageList(passageImage) {
                    if (!passageImage) return [];
                    return passageImage.split(',').map(s => s.trim()).filter(Boolean);
                },
                isTrueCorrect(question) {
                    if (!question.options || question.options.length === 0) return true;
                    const opt1 = question.options[0];
                    return (opt1 && opt1.is_correct);
                },
                setTrueFalseOption(question, isTrue) {
                    if (!question.options || question.options.length < 2) return;
                    question.options[0].is_correct = isTrue;
                    question.options[1].is_correct = !isTrue;
                },
                hasOptionImages(question) {
                    if (!question.options || question.options.length === 0) return false;
                    return question.options.some(opt => opt.image || opt.image_url);
                },
                getOptionPassageImage(group, optionContent) {
                    if (!group || !group.passage_image || !optionContent) return null;
                    const images = this.getPassageImageList(group.passage_image);
                    if (images.length < 5) return null;
                    const code = optionContent.trim().toUpperCase().charCodeAt(0);
                    const index = code - 65;
                    return images[index] || null;
                },
                isSimpleLetterOptions(question) {
                    if (!question.options || question.options.length === 0) return false;
                    if (this.hasOptionImages(question)) return false;
                    return question.question_type === 'matching' && question.options.every(opt => opt.content && /^[A-F]$/i.test(opt.content.trim()));
                },
                setCorrectOption(question, selectedOption) {
                    question.options.forEach(opt => {
                        opt.is_correct = (opt.id === selectedOption.id);
                    });
                },
                openRubyModal(targetObj, targetField) {
                    this.rubyModal.targetObj = targetObj;
                    this.rubyModal.targetField = targetField;
                    this.rubyModal.hanzi = '';
                    this.rubyModal.pinyin = '';
                    this.rubyModal.show = true;
                },
                generateRubyTags(hanzi, pinyinStr) {
                    if (!hanzi) return '';
                    const chars = hanzi.trim().split('');
                    const pinyins = pinyinStr ? pinyinStr.trim().split(/\s+/) : [];
                    
                    let result = '';
                    for (let i = 0; i < chars.length; i++) {
                        const char = chars[i];
                        const py = pinyins[i] || '';
                        if (py && char.match(/[\u4e00-\u9fa5]/)) {
                            result += `<ruby>${char}<rt>${py}</rt></ruby>`;
                        } else {
                            result += char;
                        }
                    }
                    return result;
                },
                applyRubyTags() {
                    if (this.rubyModal.targetObj && this.rubyModal.targetField) {
                        const generated = this.generateRubyTags(this.rubyModal.hanzi, this.rubyModal.pinyin);
                        this.rubyModal.targetObj[this.rubyModal.targetField] = generated;
                    }
                    this.rubyModal.show = false;
                },
                saveExam() {
                    this.saving = true;
                    const saveUrl = "{{ request()->is('teacher*') ? route('teacher.hsk-mock-exams.save-editor-data', $hskMockExam->id) : route('admin.hsk-mock-exams.save-editor-data', $hskMockExam->id) }}";
                    fetch(saveUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.exam)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.saving = false;
                        if (data.status === 'success') {
                            alert('Lưu thay đổi thành công!');
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(err => {
                        this.saving = false;
                        alert('Lỗi kết nối khi lưu dữ liệu!');
                    });
                }
            }
        }
    </script>
@endsection
