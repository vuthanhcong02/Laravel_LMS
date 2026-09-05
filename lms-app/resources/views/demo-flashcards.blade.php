@extends('layouts.lms')
@section('title', 'Thẻ ghi nhớ Flashcards HSK - Tiếng Trung XIAOMU LMS')
@section('custom-css')
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
@endsection
@section('alpine-data')
    flipped: false, 
    currentCardIndex: 0, 
    rememberedCount: 14, 
    reviewCount: 3, 
    autoPlay: false, 
    socialDockExpanded: true, 
    cards: [
        { hanzi: '你好', pinyin: 'nǐ hǎo', type: 'Thán từ', meaning: 'Xin chào!', exampleZh: '你好！很高兴认识你。', exampleVi: 'Xin chào! Rất vui được quen biết bạn.' },
        { hanzi: '谢谢', pinyin: 'xièxie', type: 'Động từ', meaning: 'Cảm ơn', exampleZh: '非常谢谢你的帮助。', exampleVi: 'Rất cảm ơn sự giúp đỡ của bạn.' },
        { hanzi: '再见', pinyin: 'zàijiàn', type: 'Thán từ', meaning: 'Tạm biệt', exampleZh: '明天见，再见！', exampleVi: 'Hẹn gặp lại ngày mai, tạm biệt!' },
        { hanzi: '苹果', pinyin: 'píngguǒ', type: 'Danh từ', meaning: 'Quả táo', exampleZh: '我想买三个苹果。', exampleVi: 'Tôi muốn mua 3 quả táo.' },
        { hanzi: '高兴', pinyin: 'gāoxìng', type: 'Tính từ', meaning: 'Vui vẻ', exampleZh: '今天大家很高兴。', exampleVi: 'Hôm nay mọi người rất vui vẻ.' }
    ],
@endsection
@section('header-left')
    <div class="relative w-full">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input type="text" placeholder="Tìm kiếm từ vựng..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
        <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
    </div>
@endsection
@section('content')
    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-[#e07a5f] fa-layer-group"></i> Thẻ Flashcard 3D HSK 1
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Thẻ Ghi Nhớ Từ Vựng Thông Minh
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    Lật mặt thẻ 3D để ghi nhớ nhanh từ vựng, từ loại và câu ví dụ ứng dụng.
                </p>
            </div>
        </div>
    </div>
    <!-- Progress Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                <i class="fa-solid fa-circle-check mr-1"></i>Đã thuộc: <span x-text="rememberedCount">14</span>
            </span>
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                <i class="fa-solid fa-rotate mr-1"></i>Cần ôn: <span x-text="reviewCount">3</span>
            </span>
            <span class="text-xs font-bold text-slate-400 ml-2">
                Thẻ <span x-text="currentCardIndex + 1">1</span> / <span x-text="cards.length">5</span>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button @click="autoPlay = !autoPlay" :class="autoPlay ? 'bg-[#e07a5f] text-white font-bold' : 'bg-white dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-4 py-2 rounded-xl text-xs font-bold btn-tactile flex items-center gap-1.5">
                <i class="fa-solid" :class="autoPlay ? 'fa-pause' : 'fa-play'"></i>
                <span x-text="autoPlay ? 'Dừng phát' : 'Tự động chạy'"></span>
            </button>
        </div>
    </div>
    <!-- ORIGINAL 3D FLASHCARD -->
    <div class="w-full h-[360px] perspective-1000 my-auto cursor-pointer" @click="flipped = !flipped">
        <div class="relative w-full h-full duration-500 transform-style-3d transition-transform" :class="flipped ? 'rotate-y-180' : ''">
            <div class="absolute inset-0 w-full h-full rounded-2xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] p-8 flex flex-col justify-between items-center shadow-md backface-hidden">
                <div class="w-full flex justify-between items-center text-xs text-slate-400">
                    <span class="px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs">HSK 1</span>
                    <span class="text-slate-400 font-medium">Chạm để lật mặt sau</span>
                </div>
                <div class="text-center space-y-4 my-auto">
                    <div class="text-7xl sm:text-8xl font-bold zh-text text-slate-900 dark:text-white tracking-wider" x-text="cards[currentCardIndex].hanzi">你好</div>
                    <div class="text-2xl font-bold text-[#e07a5f]" x-text="cards[currentCardIndex].pinyin">nǐ hǎo</div>
                    <button @click.stop="alert('Phát âm chuẩn giọng Bắc Kinh')" class="px-4 py-2 rounded-xl bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold text-xs btn-tactile mx-auto hover:scale-105 inline-flex items-center gap-2 border border-[#fcdccf] dark:border-[#3a2824]">
                        <i class="fa-solid fa-volume-high"></i>
                        <span>Nghe âm đọc</span>
                    </button>
                </div>
                <div class="text-xs text-slate-400 flex items-center gap-1.5 animate-bounce">
                    <i class="fa-solid fa-hand-pointer"></i> <span>Chạm để lật xem giải nghĩa & ví dụ</span>
                </div>
            </div>
            <div class="absolute inset-0 w-full h-full rounded-2xl bg-gradient-to-br from-white via-[#fffdfc] to-[#fff7f4] dark:from-[#181615] dark:via-[#1c1917] dark:to-[#241d1a] border border-[#e07a5f]/40 p-8 flex flex-col justify-between items-center shadow-xl backface-hidden rotate-y-180" @click.stop="">
                <div class="w-full flex justify-between items-center text-xs text-slate-400 border-b border-[#e8e2d9] dark:border-[#2d2926] pb-3">
                    <span class="px-3 py-1 rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 font-bold" x-text="cards[currentCardIndex].type">Thán từ</span>
                    <button @click="flipped = false" class="text-xs font-bold text-[#e07a5f] hover:text-[#c86349] btn-tactile flex items-center gap-1">
                        <i class="fa-solid fa-rotate-left"></i> <span>Lật lại mặt trước</span>
                    </button>
                </div>
                <div class="text-center space-y-4 my-auto max-w-lg">
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white" x-text="cards[currentCardIndex].meaning">Xin chào!</div>
                    <div class="p-4 rounded-xl bg-[#faf6f2] dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ví dụ mẫu HSK:</div>
                        <div class="text-base sm:text-lg font-bold zh-text text-slate-800 dark:text-slate-100" x-text="cards[currentCardIndex].exampleZh">你好！很高兴认识你。</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="cards[currentCardIndex].exampleVi">Xin chào! Rất vui được quen biết bạn.</div>
                    </div>
                </div>
                <div class="text-xs text-slate-400 text-center">Đánh giá mức độ ghi nhớ từ vựng phía dưới</div>
            </div>
        </div>
    </div>
    <!-- Spaced Repetition Controls -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4">
        <button @click="if(currentCardIndex > 0) { currentCardIndex--; flipped = false; }" :disabled="currentCardIndex === 0" class="p-3.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-200 text-xs font-bold btn-tactile disabled:opacity-40 flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-chevron-left text-xs"></i> Thẻ trước
        </button>
        <button @click="reviewCount++; if(currentCardIndex < cards.length - 1) { currentCardIndex++; flipped = false; }" class="p-3.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 shadow-xs">
            <i class="fa-solid fa-xmark text-xs"></i> Chưa nhớ 🔴
        </button>
        <button @click="rememberedCount++; if(currentCardIndex < cards.length - 1) { currentCardIndex++; flipped = false; }" class="p-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold btn-tactile flex items-center justify-center gap-1.5 shadow-xs">
            <i class="fa-solid fa-check text-xs"></i> Đã nhớ 🟢
        </button>
        <button @click="if(currentCardIndex < cards.length - 1) { currentCardIndex++; flipped = false; }" :disabled="currentCardIndex === cards.length - 1" class="p-3.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile disabled:opacity-40 flex items-center justify-center gap-1.5">
            Thẻ tiếp <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
    </div>
@endsection
