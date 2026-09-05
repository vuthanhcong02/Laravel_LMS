@extends('layouts.lms')
@section('title', 'Chiết Tự Chữ Hán & Bộ Thủ - Tiếng Trung XIAOMU LMS')
@section('alpine-data')
    selectedRadical: 'all', 
    selectedLevel: 'all', 
    searchQuery: '', 
    activeTabMap: {}, 
    socialDockExpanded: true, 
    characters: [
        { 
            id: 1,
            hanzi: '好', 
            pinyin: 'hǎo', 
            hanviet: 'HẢO', 
            type: 'Tính từ', 
            meaning: 'Tốt, hay, đẹp, giỏi, an lành', 
            level: 'HSK 1',
            radicalPrimary: '女',
            exampleZh: '你好！很高兴认识你。', 
            exampleVi: 'Xin chào! Rất vui được quen biết bạn.',
            etymology: {
                structure: 'Trái - Phải (左右结构)',
                category: 'Chữ Hội ý (會意字)',
                radicals: [
                    { radical: '女', pinyin: 'nǚ', meaning: 'Bộ Nữ (Người phụ nữ / Mẹ)' },
                    { radical: '子', pinyin: 'zǐ', meaning: 'Bộ Tử (Đứa con trẻ)' }
                ],
                story: 'Hình ảnh người mẹ tay ẵm đứa con nhỏ là biểu tượng ấm áp nhất của tình mẫu tử, đại diện cho những điều TỐT ĐẸP, AN LÀNH (好).'
            },
            strokes: {
                count: 6,
                rules: 'Viết bên Trái (Bộ Nữ) trước ➔ Bên Phải (Bộ Tử) sau. Viết từ trên xuống dưới.',
                steps: ['𡦹 (Phẩy chấm)', 'ノ (Phẩy)', '一 (Ngang)', '㇇ (Ngang gấp)', '亅 (Sổ móc)', '一 (Ngang)']
            }
        },
        { 
            id: 2,
            hanzi: '谢', 
            pinyin: 'xiè', 
            hanviet: 'TẠ', 
            type: 'Động từ', 
            meaning: 'Cảm ơn, tạ ơn, từ chối', 
            level: 'HSK 1',
            radicalPrimary: '讠',
            exampleZh: '非常谢谢你的帮助。', 
            exampleVi: 'Rất cảm ơn sự giúp đỡ của bạn.',
            etymology: {
                structure: 'Trái - Giữa - Phải (左中右结构)',
                category: 'Chữ Hình thanh (形聲字)',
                radicals: [
                    { radical: '讠', pinyin: 'yán', meaning: 'Bộ Ngôn (Lời nói)' },
                    { radical: '身', pinyin: 'shēn', meaning: 'Bộ Thân (Thân thể)' },
                    { radical: '寸', pinyin: 'cùn', meaning: 'Bộ Thốn (Lễ nghi / Quy tắc)' }
                ],
                story: 'Dùng Lời nói (讠) cúi gập Thân thể (身) đúng Lễ nghi (寸) để bày tỏ lòng CẢM ƠN (谢).'
            },
            strokes: {
                count: 12,
                rules: 'Viết từ trái sang phải: Bộ Ngôn ➔ Bộ Thân ➔ Bộ Thốn.',
                steps: ['丶 (Chấm)', '㇊ (Ngang gập)', 'ノ (Phẩy)', '丨 (Sổ)', '𠃍 (Ngang gập)', '一 (Ngang)', '一 (Ngang)', 'ノ (Phẩy)', '一 (Ngang)', '亅 (Sổ móc)', '丶 (Chấm)']
            }
        },
        { 
            id: 3,
            hanzi: '学', 
            pinyin: 'xué', 
            hanviet: 'HỌC', 
            type: 'Động từ', 
            meaning: 'Học tập, tiếp thu, bắt chước', 
            level: 'HSK 1',
            radicalPrimary: '冖',
            exampleZh: '我在中国学习汉语。', 
            exampleVi: 'Tôi đang học tiếng Trung ở Trung Quốc.',
            etymology: {
                structure: 'Trên - Dưới (上下结构)',
                category: 'Chữ Hội ý (會意字)',
                radicals: [
                    { radical: '⺌', pinyin: 'xiǎo', meaning: 'Bộ Mái nhà / Tri thức' },
                    { radical: '冖', pinyin: 'mì', meaning: 'Bộ Mịch (Trùm chăn / Mái trường)' },
                    { radical: '子', pinyin: 'zǐ', meaning: 'Bộ Tử (Đứa trẻ)' }
                ],
                story: 'Đứa trẻ (子) ngồi dưới mái trường (冖) rèn luyện trí tuệ để gặt hái tri thức ➔ Ý nghĩa của việc HỌC (学).'
            },
            strokes: {
                count: 8,
                rules: 'Viết phần Trên (Mái trường) trước ➔ Phần Dưới (Đứa trẻ) sau.',
                steps: ['丶', 'ノ', '丶', '冖', '㇇', '亅', '一']
            }
        },
        { 
            id: 4,
            hanzi: '水', 
            pinyin: 'shuǐ', 
            hanviet: 'THỦY', 
            type: 'Danh từ', 
            meaning: 'Nước, chất lỏng', 
            level: 'HSK 1',
            radicalPrimary: '水',
            exampleZh: '请喝一杯水。', 
            exampleVi: 'Xin hãy uống một cốc nước.',
            etymology: {
                structure: 'Độc lập (独体字)',
                category: 'Chữ Tượng hình (象形字)',
                radicals: [
                    { radical: '水', pinyin: 'shuǐ', meaning: 'Bộ Thủy (Dòng nước chảy)' }
                ],
                story: 'Nét giữa tượng trưng cho dòng sông chính, các nét hai bên tượng trưng cho những giọt nước bọt tung tóe ➔ NƯỚC (水).'
            },
            strokes: {
                count: 4,
                rules: 'Viết nét Sổ móc ở Giữa trước ➔ Viết các nét hai Bên sau.',
                steps: ['亅 (Sổ móc)', '㇇ (Ngang gập phẩy)', 'ノ (Phẩy)', '㇏ (Mác)']
            }
        }
    ],
@endsection
@section('header-left')
    <div class="relative w-full">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input type="text" x-model="searchQuery" placeholder="Tìm kiếm bộ thủ, chữ Hán, pinyin..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
        <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
    </div>
@endsection
@section('content')
    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                <i class="fa-solid fa-puzzle-piece text-[#e07a5f]"></i> Tra Cứu Chiết Tự Chữ Hán (漢字拆字)
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                Chiết Tự Chữ Hán & Quy Tắc Nét Bút Thuận
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Phân tích hình thái chữ Hán qua 214 Bộ thủ, quy tắc viết nét bút thuận chuẩn và mẹo ghi nhớ nguồn gốc chữ.
            </p>
        </div>
    </div>
    <!-- RADICAL & HSK FILTERS BAR -->
    <div class="lms-card p-4 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                <span class="text-slate-400 font-bold shrink-0">Bộ thủ:</span>
                <button @click="selectedRadical = 'all'" :class="selectedRadical === 'all' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0">
                    Tất cả Bộ thủ
                </button>
                <button @click="selectedRadical = '女'" :class="selectedRadical === '女' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                    女 (Bộ Nữ)
                </button>
                <button @click="selectedRadical = '讠'" :class="selectedRadical === '讠' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                    讠 (Bộ Ngôn)
                </button>
                <button @click="selectedRadical = '冖'" :class="selectedRadical === '冖' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                    冖 (Bộ Mịch)
                </button>
                <button @click="selectedRadical = '水'" :class="selectedRadical === '水' ? 'bg-[#e07a5f] text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-xl btn-tactile shrink-0 zh-text">
                    水 (Bộ Thủy)
                </button>
            </div>
            <!-- Level Filter -->
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-slate-400 font-bold">Cấp độ:</span>
                <select x-model="selectedLevel" class="bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-800 dark:text-white focus:outline-none focus:border-[#e07a5f]">
                    <option value="all">Tất cả HSK</option>
                    <option value="HSK 1">HSK 1</option>
                    <option value="HSK 2">HSK 2</option>
                    <option value="HSK 3">HSK 3</option>
                </select>
            </div>
        </div>
    </div>
    <!-- CHARACTER ETYMOLOGY CARDS LIST (1/2 LEFT vs UPPER/LOWER RIGHT LAYOUT) -->
    <div class="space-y-6">
        <template x-for="item in characters" :key="item.id">
            <div x-show="(selectedRadical === 'all' || item.radicalPrimary === selectedRadical) && (selectedLevel === 'all' || item.level === selectedLevel) && (searchQuery === '' || item.hanzi.includes(searchQuery) || item.pinyin.includes(searchQuery) || item.meaning.includes(searchQuery))"
                 class="lms-card p-5 sm:p-6 space-y-4 hover:border-[#e07a5f]/50 transition-all shadow-sm">
                <!-- Card Header Bar -->
                <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl font-bold zh-text text-[#e07a5f]" x-text="item.hanzi">好</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="item.pinyin">hǎo</span>
                                <span class="text-xs font-semibold text-slate-400" x-text="'• Hán-Việt: ' + item.hanviet"></span>
                            </div>
                            <span class="text-[11px] font-bold text-amber-700 dark:text-amber-400" x-text="item.type">Tính từ</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] text-xs font-bold" x-text="item.level">HSK 1</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-[#faf6f2] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2 mb-3">
                                <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-black">
                                    1/2 Bên Trái: Phát âm & Dịch nghĩa
                                </span>
                                <button @click="alert('Phát âm chuẩn giọng Bắc Kinh')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 text-[#e07a5f] text-[11px] font-bold btn-tactile border border-[#e8e2d9] dark:border-slate-700">
                                    <i class="fa-solid fa-volume-high mr-1"></i>Nghe phát âm
                                </button>
                            </div>
                            <div class="space-y-1 mb-3">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Giải nghĩa tiếng Việt:</span>
                                <div class="text-base sm:text-lg font-bold text-slate-900 dark:text-white" x-text="item.meaning">Tốt, hay, đẹp, giỏi</div>
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1">
                            <div class="font-bold text-slate-400 text-[10px]"><i class="fa-solid fa-quote-left text-[#e07a5f] mr-1"></i>Ví dụ mẫu HSK:</div>
                            <div class="text-sm font-bold zh-text text-slate-800 dark:text-slate-100" x-text="item.exampleZh">你好！很高兴认识你。</div>
                            <div class="text-xs text-slate-500" x-text="item.exampleVi">Xin chào! Rất vui được quen biết bạn.</div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="p-4 rounded-2xl bg-white dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2.5 shadow-xs">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                                <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-black">
                                    ✍️ Nửa trên: Khung cách viết (Bút thuận)
                                </span>
                                <span class="text-xs font-bold text-slate-400" x-text="item.strokes.count + ' Nét vẽ'">6 Nét vẽ</span>
                            </div>
                            <div class="text-xs text-slate-600 dark:text-slate-300">
                                Quy tắc nét: <strong class="text-slate-800 dark:text-white" x-text="item.strokes.rules"></strong>
                            </div>
                            <div class="flex items-center justify-between pt-1">
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="(st, idx) in item.strokes.steps" :key="idx">
                                        <span class="px-2 py-0.5 rounded bg-[#f8f6f3] dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-slate-700" x-text="st"></span>
                                    </template>
                                </div>
                                <button @click="alert('Đang phát hoạt ảnh tập viết nét chữ Hán từng bước chuẩn Bút thuận!')" class="px-3 py-1.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold text-xs btn-tactile shrink-0">
                                    <i class="fa-solid fa-pen-line mr-1"></i>Xem nét viết
                                </button>
                            </div>
                        </div>
                        <div class="p-4 rounded-2xl bg-white dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2.5 shadow-xs">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                                <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-xs font-black">
                                    🧩 Nửa dưới: Chiết tự Chữ Hán
                                </span>
                                <span class="text-xs font-bold text-slate-400" x-text="item.etymology.category">Chữ Hội ý</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-slate-400 font-bold shrink-0">Bộ thủ:</span>
                                <template x-for="rad in item.etymology.radicals" :key="rad.radical">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] text-xs font-bold border border-[#fcdccf] dark:border-[#42271f]" x-text="rad.radical + ' (' + rad.meaning + ')'"></span>
                                </template>
                            </div>
                            <div class="p-3 rounded-xl bg-[#fff2ee]/60 dark:bg-[#2a221f]/60 text-slate-700 dark:text-slate-300 text-xs leading-relaxed">
                                <strong class="text-[#e07a5f] font-bold block mb-1"><i class="fa-solid fa-brain mr-1"></i>Chiết tự ghi nhớ:</strong>
                                <span x-text="item.etymology.story"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection
