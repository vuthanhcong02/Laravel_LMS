@extends('layouts.lms')

@section('title', 'Bảng Phiên âm Pinyin Interactive - Tiếng Trung XIAOMU LMS')

@section('alpine-data')
    selectedInitial: 'b', 
    selectedFinal: 'a', 
    selectedTone: 1, 
    socialDockExpanded: true, 
    initials: ['b', 'p', 'm', 'f', 'd', 't', 'n', 'l', 'g', 'k', 'h', 'j', 'q', 'x', 'zh', 'ch', 'sh', 'r', 'z', 'c', 's'], 
    finals: ['a', 'o', 'e', 'i', 'u', 'ü', 'ai', 'ei', 'ao', 'ou', 'an', 'en', 'ang', 'eng', 'ong'],
@endsection

@section('header-left')
    <div class="relative w-full">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input type="text" placeholder="Tra cứu âm Pinyin..." class="w-full bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl pl-10 pr-10 sm:pr-12 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all">
        <span class="hidden sm:block absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-semibold bg-white dark:bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">⌘K</span>
    </div>
@endsection

@section('content')
    <!-- Banner Tiêu đề Trang (Bám sát Demo-UI) -->
    <div class="lms-card p-5 sm:p-6 bg-gradient-to-r from-[#fff7f4] via-white to-[#fff2ee] dark:from-[#1e1a18] dark:via-[#1c1917] dark:to-[#221c19] relative overflow-hidden group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] dark:text-[#f4978e] text-xs font-bold">
                    <i class="fa-solid fa-table-cells text-[#e07a5f]"></i> Bảng âm ngữ chuẩn giọng Bắc Kinh
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Bảng phiên âm Pinyin Interactive
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    Tra cứu 21 Thanh mẫu (声母), 36 Vận mẫu (韵母) và nghe mẫu phát âm 4 Thanh điệu chuẩn.
                </p>
            </div>
        </div>
    </div>

    <!-- Pronunciation Preview Card -->
    <div class="lms-card p-5 bg-gradient-to-r from-white via-[#fff8f5] to-white dark:from-[#181615] dark:via-[#241d1a] dark:to-[#181615] flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-[#e07a5f] text-white font-bold text-3xl flex items-center justify-center shadow-lg shadow-[#e07a5f]/25 shrink-0">
                <span x-text="selectedInitial + selectedFinal">bā</span>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-[#e07a5f]">Âm ghép:</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="selectedInitial + ' + ' + selectedFinal">b + a</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Thanh 1 (Thanh bằng): <span class="text-[#e07a5f]" x-text="selectedInitial + 'ā'">bā</span></h2>
                <p class="text-xs text-slate-500">Giữ giọng cao bằng phẳng (55) • Hướng dẫn: Mở rộng khẩu hình</p>
            </div>
        </div>

        <button onclick="alert('Đang phát âm Pinyin mẫu chuẩn')" class="px-5 py-3 rounded-xl bg-gradient-to-r from-[#e07a5f] to-[#c86349] text-white font-bold text-xs btn-tactile shadow-md flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-volume-high"></i> <span>Phát âm mẫu</span>
        </button>
    </div>

    <!-- 4 Tones Selection Bar -->
    <div class="lms-card p-5 space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Chọn Thanh điệu (声调)</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <button @click="selectedTone = 1" :class="selectedTone === 1 ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold' : 'border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300'" class="p-3 rounded-xl border text-left btn-tactile">
                <div class="font-bold">Thanh 1 (¯)</div>
                <div class="text-[10px] text-slate-400">Cao bằng (5-5)</div>
            </button>
            <button @click="selectedTone = 2" :class="selectedTone === 2 ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold' : 'border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300'" class="p-3 rounded-xl border text-left btn-tactile">
                <div class="font-bold">Thanh 2 (ˊ)</div>
                <div class="text-[10px] text-slate-400">Sắc lên (3-5)</div>
            </button>
            <button @click="selectedTone = 3" :class="selectedTone === 3 ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold' : 'border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300'" class="p-3 rounded-xl border text-left btn-tactile">
                <div class="font-bold">Thanh 3 (ˇ)</div>
                <div class="text-[10px] text-slate-400">Hỏi xuống lên (2-1-4)</div>
            </button>
            <button @click="selectedTone = 4" :class="selectedTone === 4 ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] font-bold' : 'border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300'" class="p-3 rounded-xl border text-left btn-tactile">
                <div class="font-bold">Thanh 4 (ˋ)</div>
                <div class="text-[10px] text-slate-400">Huyền dứt khoát (5-1)</div>
            </button>
        </div>
    </div>

    <!-- Initials Selector Grid -->
    <div class="lms-card p-5 space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">1. Thanh mẫu (声母 - 21 Phụ âm)</h3>
        <div class="flex flex-wrap gap-2">
            <template x-for="init in initials" :key="init">
                <button @click="selectedInitial = init" :class="selectedInitial === init ? 'bg-[#e07a5f] text-white font-bold' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="w-10 h-10 rounded-xl text-sm font-bold flex items-center justify-center btn-tactile">
                    <span x-text="init"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Finals Selector Grid -->
    <div class="lms-card p-5 space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">2. Vận mẫu (韵母 - Nguyên âm)</h3>
        <div class="flex flex-wrap gap-2">
            <template x-for="fin in finals" :key="fin">
                <button @click="selectedFinal = fin" :class="selectedFinal === fin ? 'bg-[#e07a5f] text-white font-bold' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 border border-[#e8e2d9] dark:border-[#2d2926]'" class="px-3.5 h-10 rounded-xl text-sm font-bold flex items-center justify-center btn-tactile">
                    <span x-text="fin"></span>
                </button>
            </template>
        </div>
    </div>
@endsection
