@extends('layouts.lms')

@section('title', 'Luyện nghe & Nhận diện Pinyin - Tiếng Trung XIAOMU LMS')

@section('alpine-data')
    score: 80, 
    currentQuestion: 1, 
    selectedAnswer: null, 
    isAnswered: false, 
    socialDockExpanded: true, 
    questions: [
        { pinyin: 'nǐ hǎo', hanzi: '你好', options: ['nǐ hǎo', 'ní hǎo', 'nì hāo', 'nǐ hāo'], correct: 0 },
        { pinyin: 'xièxie', hanzi: '谢谢', options: ['xièxie', 'xiēxie', 'xiěxie', 'xièxiē'], correct: 0 }
    ],
@endsection

@section('header-left')
    <div class="relative w-full">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input type="text" placeholder="Tìm kiếm bài luyện âm Pinyin..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
        <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
    </div>
@endsection

@section('content')
    <!-- Banner Tiêu đề Trang (Bám sát Demo-UI) -->
    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-headset text-[#e07a5f]"></i> Luyện phản xạ âm thanh Pinyin
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Luyện tập Phản xạ Pinyin
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    Nghe phát âm chuẩn giọng Bắc Kinh và chọn đáp án phiên âm có Thanh điệu đúng.
                </p>
            </div>
        </div>
    </div>

    <!-- Progress Bar & Score -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-slate-500">Câu hỏi <span x-text="currentQuestion">1</span>/10</span>
            <div class="w-32 h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                <div class="h-full rounded-full bg-[#e07a5f]" style="width: 10%;"></div>
            </div>
        </div>

        <div class="px-3.5 py-1.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 text-xs font-bold">
            <i class="fa-solid fa-trophy mr-1"></i>Điểm: <span x-text="score">80</span>
        </div>
    </div>

    <!-- Practice Question Card -->
    <div class="lms-card p-6 sm:p-8 space-y-6 text-center">
        <div class="space-y-3">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nghe mẫu phát âm</div>
            
            <button onclick="alert('Đang phát đoạn âm Pinyin bài luyện tập...')" class="w-20 h-20 rounded-full bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white text-2xl flex items-center justify-center btn-tactile shadow-lg shadow-[#e07a5f]/30 mx-auto hover:scale-105">
                <i class="fa-solid fa-volume-high animate-pulse"></i>
            </button>

            <div class="text-2xl font-bold zh-text text-slate-900 dark:text-white pt-2">你好</div>
        </div>

        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto pt-2">
            <template x-for="(opt, idx) in questions[0].options" :key="idx">
                <button @click="selectedAnswer = idx; isAnswered = true" :class="selectedAnswer === idx ? (idx === 0 ? 'bg-emerald-600 text-white font-bold border-emerald-600' : 'bg-red-500 text-white font-bold border-red-500') : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-800 dark:text-slate-200 border-[#e8e2d9] dark:border-[#2d2926]'" class="p-4 rounded-2xl border text-base font-bold btn-tactile shadow-xs flex items-center justify-center gap-2">
                    <span x-text="opt"></span>
                    <i x-show="selectedAnswer === idx && idx === 0" class="fa-solid fa-circle-check text-white"></i>
                </button>
            </template>
        </div>
    </div>
@endsection
