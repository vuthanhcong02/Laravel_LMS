@extends('layouts.lms')

@section('title', 'Bài 1: 你好！ - HSK 1 - XIAOMU LMS')

@section('custom-css')
    ruby { font-size: 1.1em; }
    rt { font-size: 0.55em; color: #e07a5f; font-weight: 600; text-align: center; }

    /* 3D Card Flip Animation */
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
@endsection

@section('alpine-data')
    activeTab: 'vocab', 
    vocabSubView: 'table', 
    fcMode: 'flashcard', 
    fcIndex: 0, 
    fcFlipped: false, 
    socialDockExpanded: true, 
    vocabularies: [
        { id: 1, hanzi: '你', pinyin: '[nǐ]', pos: 'Đại từ', posClass: 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 border-indigo-500/30', meaning: '(số ít) anh, chị, bạn...', example: '你好！ (Xin chào!)' },
        { id: 2, hanzi: '好', pinyin: '[hǎo]', pos: 'Tính từ', posClass: 'bg-[#f59e0b]/15 text-[#f59e0b] border-[#f59e0b]/30', meaning: 'khỏe, tốt', example: '很好！ (Rất tốt!)' },
        { id: 3, hanzi: '您', pinyin: '[nín]', pos: 'Đại từ', posClass: 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 border-indigo-500/30', meaning: '(lịch sự) ông, bà, ngài...', example: '您好！ (Cháu chào ông/bà!)' },
        { id: 4, hanzi: '你们', pinyin: '[nǐmen]', pos: 'Đại từ', posClass: 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 border-indigo-500/30', meaning: '(số nhiều) các anh, các chị, các bạn...', example: '你们好！ (Chào các bạn!)' },
        { id: 5, hanzi: '对不起', pinyin: '[duìbuqǐ]', pos: 'Động từ', posClass: 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/30', meaning: 'xin lỗi', example: '对不起，我迟到了。 (Xin lỗi, tôi đến muộn.)' },
        { id: 6, hanzi: '没关系', pinyin: '[méi guānxi]', pos: 'Cụm từ', posClass: 'bg-[#0284c7]/15 text-[#0284c7] border-[#0284c7]/30', meaning: 'không sao đâu, không có vấn đề gì', example: '没关系！ (Không sao đâu!)' },
        { id: 7, hanzi: '上课!', pinyin: '[Shàng kè!]', pos: 'Câu giao tiếp', posClass: 'bg-[#f59e0b]/15 text-[#f59e0b] border-[#f59e0b]/30', meaning: 'Vào học đi!', example: '同学们，上课！ (Các em học sinh, vào học!)' }
    ],
@endsection

@section('header-left')
    <!-- Breadcrumbs -->
    <div class="flex items-center text-xs text-slate-500 font-semibold truncate">
        <a href="{{ url('/demo-courses') }}" class="hover:text-[#e07a5f] transition-colors"><i class="fa-solid fa-house text-xs mr-1"></i>Trang chủ</a>
        <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
        <a href="{{ url('/demo-courses') }}" class="hover:text-[#e07a5f] transition-colors">Khóa học</a>
        <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
        <span>HSK 1</span>
        <i class="fa-solid fa-chevron-right text-[9px] mx-2 text-slate-400"></i>
        <span class="text-slate-900 dark:text-white font-bold truncate">Bài 1: 你好！</span>
    </div>
@endsection

@section('header-right')
    <!-- Empty to hide language selector in detail page -->
@endsection

@section('content')
    <!-- THANH 4 TAB CHÍNH KHÓA HỌC -->
    <div class="lms-card p-2 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between gap-3 overflow-x-auto no-scrollbar">
        <div class="flex items-center gap-1.5">
            <!-- Tab 1: Từ vựng -->
            <button @click="activeTab = 'vocab'" 
                    :class="activeTab === 'vocab' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold'"
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2">
                <i class="fa-solid fa-list-ul text-xs"></i>
                <span>Từ vựng</span>
            </button>

            <!-- Tab 2: Bài khóa -->
            <button @click="activeTab = 'dialogue'" 
                    :class="activeTab === 'dialogue' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold'"
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2">
                <i class="fa-solid fa-comments text-xs"></i>
                <span>Bài khóa</span>
            </button>

            <!-- Tab 3: Ngữ pháp -->
            <button @click="activeTab = 'grammar'" 
                    :class="activeTab === 'grammar' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-[#e07a5f]'"
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2">
                <i class="fa-solid fa-spell-check text-xs"></i>
                <span>Ngữ pháp</span>
            </button>

            <!-- Tab 4: Luyện tập -->
            <button @click="activeTab = 'practice'" 
                    :class="activeTab === 'practice' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-semibold'"
                    class="px-4 py-2 rounded-xl text-xs transition-all btn-tactile flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>Luyện tập</span>
            </button>
        </div>

        <!-- Sub-Toggle Switcher trong Tab Từ vựng: Đổi view trực tiếp giữa "Bảng từ vựng" & "Học Flashcard" -->
        <div class="flex items-center gap-1.5 p-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl shrink-0">
            <button @click="activeTab = 'vocab'; vocabSubView = 'table';" 
                    :class="vocabSubView === 'table' ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 font-semibold'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5">
                <i class="fa-solid fa-[#e07a5f] fa-[#e07a5f] fa-table-cells"></i>
                <span>Bảng từ vựng</span>
            </button>
            <button @click="activeTab = 'vocab'; vocabSubView = 'flashcard'; fcIndex = 0; fcFlipped = false;" 
                    :class="vocabSubView === 'flashcard' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'text-slate-500 font-semibold'" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5">
                <i class="fa-solid fa-layer-group"></i>
                <span>Học Flashcard</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: TỪ VỰNG TRỌNG TÂM -->
    <div x-show="activeTab === 'vocab'" class="space-y-4">
        
        <!-- ===================================================================== -->
        <!-- VIEW 1: BẢNG TỪ VỰNG (DEFAULT TABLE VIEW) -->
        <!-- ===================================================================== -->
        <div x-show="vocabSubView === 'table'" class="space-y-4">
            <!-- Header Card -->
            <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f4978e] flex items-center justify-center text-sm font-bold shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Từ vựng trọng tâm</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Ghi nhớ và luyện phát âm các từ vựng mới của bài học.</p>
                    </div>
                </div>

                <button @click="vocabSubView = 'flashcard'; fcIndex = 0; fcFlipped = false;" class="px-3.5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>Chuyển sang Flashcard</span>
                </button>
            </div>

            <!-- VOCABULARY TABLE -->
            <div class="lms-card bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#e8e2d9] dark:border-[#2d2926] bg-[#fcfaf7] dark:bg-[#23201e] text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="p-3.5 w-12 text-center">#</th>
                                <th class="p-3.5">TỪ VỰNG</th>
                                <th class="p-3.5">PINYIN</th>
                                <th class="p-3.5">TỪ LOẠI</th>
                                <th class="p-3.5">Ý NGHĨA</th>
                                <th class="p-3.5 text-center w-20">NGHE</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e8e2d9] dark:divide-[#2d2926] text-xs font-medium">
                            <template x-for="item in vocabularies" :key="item.id">
                                <tr class="hover:bg-[#fcfaf7] dark:hover:bg-[#23201e] transition-colors">
                                    <td class="p-3.5 text-center text-slate-400 font-semibold text-xs" x-text="item.id">1</td>
                                    <td class="p-3.5 font-bold text-slate-900 dark:text-white text-base zh-text" x-text="item.hanzi">你</td>
                                    <td class="p-3.5 font-mono font-semibold text-[#e07a5f] text-xs" x-text="item.pinyin">[nǐ]</td>
                                    <td class="p-3.5">
                                        <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold border" :class="item.posClass" x-text="item.pos">Đại từ</span>
                                    </td>
                                    <td class="p-3.5 font-normal text-slate-700 dark:text-slate-300 text-xs" x-text="item.meaning">(số ít) anh, chị, bạn...</td>
                                    <td class="p-3.5 text-center">
                                        <button @click="alert('Đang phát âm thanh từ vựng: ' + item.hanzi)" class="w-7 h-7 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] hover:bg-[#e07a5f] hover:text-white text-slate-500 dark:text-slate-400 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center mx-auto transition-colors btn-tactile">
                                            <i class="fa-solid fa-volume-high text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ===================================================================== -->
        <!-- VIEW 2: KHUNG HỌC FLASHCARD THAY THẾ TRỰC TIẾP TRONG MAIN CONTENT -->
        <!-- ===================================================================== -->
        <div x-show="vocabSubView === 'flashcard'" class="space-y-6" style="display: none;">
            
            <!-- Header Bar Flashcard trong Main Content -->
            <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#e07a5f] text-white flex items-center justify-center text-sm font-bold shrink-0">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Luyện tập Flashcard từ vựng Bài 1</h2>
                        <p class="text-xs text-slate-500 font-normal">Chạm vào thẻ để lật hoặc chọn các chế độ học bên dưới.</p>
                    </div>
                </div>

                <button @click="vocabSubView = 'table'" class="px-3.5 py-2 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 text-xs font-bold btn-tactile flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-table-cells text-xs"></i>
                    <span>Quay lại Bảng từ vựng</span>
                </button>
            </div>

            <!-- 4 Chế độ Luyện tập Flashcard (Segmented Control) -->
            <div class="flex items-center justify-center gap-1.5 p-1.5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl max-w-xl mx-auto overflow-x-auto no-scrollbar shadow-xs">
                <button @click="fcMode = 'flashcard'" :class="fcMode === 'flashcard' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs whitespace-nowrap btn-tactile flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>Flashcard</span>
                </button>
                <button @click="fcMode = 'match'" :class="fcMode === 'match' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs whitespace-nowrap btn-tactile flex items-center gap-2">
                    <i class="fa-solid fa-puzzle-piece text-xs"></i>
                    <span>Nối từ</span>
                </button>
                <button @click="fcMode = 'quiz'" :class="fcMode === 'quiz' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs whitespace-nowrap btn-tactile flex items-center gap-2">
                    <i class="fa-solid fa-circle-question text-xs"></i>
                    <span>Trắc nghiệm</span>
                </button>
                <button @click="fcMode = 'type'" :class="fcMode === 'type' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs whitespace-nowrap btn-tactile flex items-center gap-2">
                    <i class="fa-solid fa-keyboard text-xs"></i>
                    <span>Luyện gõ</span>
                </button>
            </div>

            <!-- CHẾ ĐỘ 1: FLASHCARD LẬT THẺ 3D (HIỂN THỊ CHUẨN TRONG MAIN CONTENT) -->
            <div x-show="fcMode === 'flashcard'" class="space-y-6">
                <div class="perspective-1000 w-full min-h-[300px] sm:min-h-[340px] cursor-pointer max-w-2xl mx-auto" @click="fcFlipped = !fcFlipped">
                    <div class="relative w-full h-full min-h-[300px] sm:min-h-[340px] transition-transform duration-500 transform-style-3d shadow-md rounded-2xl" :class="{ 'rotate-y-180': fcFlipped }">
                        
                        <!-- MẶT TRƯỚC (CHỮ HÁN) -->
                        <div class="absolute inset-0 w-full h-full bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl p-6 flex flex-col justify-between items-center backface-hidden">
                            <div class="w-full flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold text-slate-500 dark:text-slate-400" x-text="(fcIndex + 1) + ' / ' + vocabularies.length">1 / 7</span>
                                <button @click.stop="alert('Đang phát âm thanh: ' + vocabularies[fcIndex].hanzi)" class="w-9 h-9 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white flex items-center justify-center text-xs transition-colors btn-tactile">
                                    <i class="fa-solid fa-volume-high"></i>
                                </button>
                            </div>

                            <div class="my-auto text-center space-y-2">
                                <div class="text-5xl sm:text-6xl font-bold zh-text text-slate-900 dark:text-white tracking-wide" x-text="vocabularies[fcIndex].hanzi">你</div>
                            </div>

                            <div class="text-xs font-medium text-slate-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-hand-pointer text-[#e07a5f]"></i>
                                <span>Chạm để xem mặt sau</span>
                            </div>
                        </div>

                        <!-- MẶT SAU (PINYIN & NGHĨA) -->
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#2a221f] dark:via-[#1c1917] dark:to-[#221c19] border border-[#fcdccf] dark:border-[#4a2e26] rounded-2xl p-6 flex flex-col justify-between items-center backface-hidden rotate-y-180">
                            <div class="w-full flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold border" :class="vocabularies[fcIndex].posClass" x-text="vocabularies[fcIndex].pos">Đại từ</span>
                                <button @click.stop="alert('Đang phát âm thanh: ' + vocabularies[fcIndex].hanzi)" class="w-9 h-9 rounded-full bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white flex items-center justify-center text-xs transition-colors btn-tactile">
                                    <i class="fa-solid fa-volume-high"></i>
                                </button>
                            </div>

                            <div class="my-auto text-center space-y-3">
                                <div class="text-4xl font-bold zh-text text-slate-900 dark:text-white" x-text="vocabularies[fcIndex].hanzi">你</div>
                                <div class="text-base font-mono font-bold text-[#e07a5f]" x-text="vocabularies[fcIndex].pinyin">[nǐ]</div>
                                <div class="text-sm font-bold text-slate-800 dark:text-slate-200" x-text="vocabularies[fcIndex].meaning">(số ít) anh, chị, bạn...</div>
                                <div class="p-2.5 rounded-xl bg-white/80 dark:bg-slate-900/60 border border-[#e8e2d9] dark:border-[#2d2926] text-xs text-slate-600 dark:text-slate-300 font-medium zh-text" x-text="vocabularies[fcIndex].example">你好！ (Xin chào!)</div>
                            </div>

                            <div class="text-xs font-medium text-slate-400">
                                <span>Chạm để quay lại mặt trước</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Điều khiển Thẻ (Next, Prev, Shuffle) -->
                <div class="flex items-center justify-center gap-4 pt-2">
                    <button @click="fcIndex = fcIndex > 0 ? fcIndex - 1 : vocabularies.length - 1; fcFlipped = false;" 
                            class="w-10 h-10 rounded-full bg-white dark:bg-[#181615] hover:bg-[#e07a5f] hover:text-white text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center text-xs font-bold transition-all btn-tactile shadow-xs" 
                            title="Thẻ trước">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <button @click="vocabularies = vocabularies.sort(() => Math.random() - 0.5); fcIndex = 0; fcFlipped = false;" 
                            class="px-4 py-2 rounded-full bg-white dark:bg-[#181615] hover:bg-[#e07a5f] hover:text-white text-slate-700 dark:text-slate-200 border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-bold flex items-center gap-2 transition-all btn-tactile shadow-xs">
                        <i class="fa-solid fa-shuffle text-xs"></i>
                        <span>Trộn thẻ</span>
                    </button>

                    <button @click="fcIndex = fcIndex < vocabularies.length - 1 ? fcIndex + 1 : 0; fcFlipped = false;" 
                            class="w-10 h-10 rounded-full bg-white dark:bg-[#181615] hover:bg-[#e07a5f] hover:text-white text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center text-xs font-bold transition-all btn-tactile shadow-xs" 
                            title="Thẻ tiếp">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- CHẾ ĐỘ 2: NỐI TỪ -->
            <div x-show="fcMode === 'match'" class="p-6 text-center space-y-4 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926]" style="display: none;">
                <i class="fa-solid fa-puzzle-piece text-4xl text-[#e07a5f]"></i>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Chế độ Nối từ HSK 1</h3>
                <p class="text-xs text-slate-500">Nối chữ Hán với nghĩa tiếng Việt tương ứng nhanh nhất có thể!</p>
                <button @click="alert('Bắt đầu trò chơi Nối từ!')" class="px-5 py-2.5 rounded-xl bg-[#e07a5f] text-white text-xs font-bold btn-tactile">Bắt đầu trò chơi</button>
            </div>

            <!-- CHẾ ĐỘ 3: TRẮC NGHIỆM -->
            <div x-show="fcMode === 'quiz'" class="p-6 text-center space-y-4 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926]" style="display: none;">
                <i class="fa-solid fa-circle-question text-4xl text-[#e07a5f]"></i>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Chế độ Kiểm tra Trắc nghiệm</h3>
                <p class="text-xs text-slate-500">Kiểm tra mức độ phản xạ ghi nhớ từ vựng qua 10 câu trắc nghiệm nhanh.</p>
                <button @click="alert('Bắt đầu làm bài trắc nghiệm!')" class="px-5 py-2.5 rounded-xl bg-[#e07a5f] text-white text-xs font-bold btn-tactile">Bắt đầu làm bài</button>
            </div>

            <!-- CHẾ ĐỘ 4: LUYỆN GÕ -->
            <div x-show="fcMode === 'type'" class="p-6 text-center space-y-4 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926]" style="display: none;">
                <i class="fa-solid fa-keyboard text-4xl text-[#e07a5f]"></i>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Chế độ Luyện gõ Pinyin</h3>
                <p class="text-xs text-slate-500">Gõ đúng Pinyin kèm thanh điệu của từ vựng vừa học để phản xạ tự nhiên.</p>
                <button @click="alert('Bắt đầu bài Luyện gõ!')" class="px-5 py-2.5 rounded-xl bg-[#e07a5f] text-white text-xs font-bold btn-tactile">Bắt đầu gõ</button>
            </div>

        </div>

    </div>

    <!-- TAB 2: BÀI KHÓA (DIALOGUE) -->
    <div x-show="activeTab === 'dialogue'" class="space-y-4" style="display: none;">
        <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-4">
                <div class="space-y-0.5">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Bài khóa HSK 1 - Bài 1: 你好！</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Nghe và luyện tập hội thoại chào hỏi căn bản</p>
                </div>
                <button @click="alert('Đang phát bài khóa âm thanh giọng chuẩn...')" class="px-4 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-2">
                    <i class="fa-solid fa-play text-xs"></i> Nghe Bài khóa
                </button>
            </div>

            <div class="space-y-3">
                <div class="p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1">
                    <span class="text-xs font-bold text-[#e07a5f]">A:</span>
                    <div class="text-base font-bold zh-text text-slate-900 dark:text-white">
                        <ruby>你<rt>nǐ</rt></ruby><ruby>好<rt>hǎo</rt></ruby>！
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Xin chào!</p>
                </div>

                <div class="p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1">
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">B:</span>
                    <div class="text-base font-bold zh-text text-slate-900 dark:text-white">
                        <ruby>你<rt>nǐ</rt></ruby><ruby>好<rt>hǎo</rt></ruby>！<ruby>很<rt>hěn</rt></ruby><ruby>高<rt>gāo</rt></ruby><ruby>兴<rt>xìng</rt></ruby><ruby>认<rt>rèn</rt></ruby><ruby>识<rt>shi</rt></ruby><ruby>你<rt>nǐ</rt></ruby>。
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Xin chào! Rất vui được quen biết bạn.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: NGỮ PHÁP (GRAMMAR) -->
    <div x-show="activeTab === 'grammar'" class="space-y-4" style="display: none;">
        <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl space-y-5">
            <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">Điểm ngữ pháp trọng tâm Bài 1</h3>
            
            <div class="space-y-3.5">
                <div class="p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1.5">
                    <h4 class="font-bold text-[#e07a5f] text-xs">1. Đại từ xưng hô Kính ngữ 你 vs 您</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-normal leading-relaxed">Dùng <strong class="text-slate-900 dark:text-white zh-text">您 (nín)</strong> khi muốn bày tỏ sự kính trọng lịch sự đối với người lớn tuổi, thầy cô giáo hoặc cấp trên.</p>
                </div>

                <div class="p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-1.5">
                    <h4 class="font-bold text-[#e07a5f] text-xs">2. Hậu tố số nhiều 们 (men)</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-normal leading-relaxed">Thêm <strong class="text-slate-900 dark:text-white zh-text">们</strong> đằng sau đại từ xưng hô để tạo thành số nhiều (VD: <span class="zh-text text-[#f59e0b] font-bold">你们</span> = các bạn, <span class="zh-text text-[#f59e0b] font-bold">我们</span> = chúng tôi).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 4: LUYỆN TẬP (PRACTICE) -->
    <div x-show="activeTab === 'practice'" class="space-y-4" style="display: none;">
        <div class="lms-card p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl space-y-4">
            <div class="border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3 space-y-0.5">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Luyện tập củng cố Bài 1</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Chọn đáp án đúng nhất cho câu hỏi dưới đây:</p>
            </div>

            <div class="p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] space-y-3">
                <p class="text-xs font-bold text-slate-900 dark:text-white">Câu 1: "Cảm ơn" trong tiếng Trung đọc là gì?</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <button @click="alert('Chính xác!')" class="p-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold btn-tactile">A. 谢谢 (xièxie)</button>
                    <button @click="alert('Chưa chính xác')" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-slate-700 dark:text-slate-200 font-bold btn-tactile">B. 再见 (zàijiàn)</button>
                    <button @click="alert('Chưa chính xác')" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-slate-700 dark:text-slate-200 font-bold btn-tactile">C. 對不起 (duìbuqǐ)</button>
                </div>
            </div>
        </div>
    </div>
@endsection
