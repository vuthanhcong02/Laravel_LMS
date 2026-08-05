@extends('layouts.app')

@section('title', __('HSK Cấp ' . $level . ' - Thi Thử'))

@section('breadcrumb', 'Danh sách đề thi HSK ' . $level)
@section('breadcrumb_desc', 'Chọn đề thi phù hợp và bắt đầu kiểm tra năng lực của bạn. Hệ thống sẽ tự động chấm điểm và đưa ra phân tích sau khi bạn nộp bài.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 font-sans relative pb-24 pt-8">
    
    <div class="max-w-7xl mx-auto px-6 space-y-8 relative z-10">
        
        {{-- Back Button & Filters --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('student.hsk-mock-exams.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition-colors bg-white dark:bg-slate-800/60 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 w-fit shadow-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Quay lại các cấp độ
            </a>

            <div class="flex items-center gap-2">
                <select class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary/20 outline-none shadow-sm cursor-pointer">
                    <option value="all">Tất cả đề thi</option>
                    <option value="completed">Đã hoàn thành</option>
                    <option value="uncompleted">Chưa làm</option>
                </select>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400">search</span>
                    <input type="text" placeholder="Tìm đề thi..." class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold rounded-xl pl-9 pr-4 py-2 w-full sm:w-64 focus:ring-2 focus:ring-primary/20 outline-none shadow-sm placeholder:font-normal">
                </div>
            </div>
        </div>

        {{-- 2-Column Layout: Structure Explanation (Left) & Exam List (Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-12 gap-8">
            
            {{-- LEFT COLUMN: EXAM STRUCTURE --}}
            <div class="lg:col-span-1 xl:col-span-4 space-y-6">
                
                <div class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] p-6 shadow-sm sticky top-24">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">info</span>
                        Cấu trúc đề thi HSK {{ $level }}
                    </h3>
                    
                    @if($level == 1)
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-6 bg-primary/5 p-3 rounded-xl border border-primary/10">
                        Đề thi HSK 1 sẽ có <strong>pinyin</strong> và mỗi câu hỏi phần nghe được phát <strong>2 lần</strong>.
                    </p>

                    <div class="space-y-6">
                        {{-- Listening Section --}}
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 flex items-center gap-2 border-b border-slate-100 dark:border-slate-700/50 pb-2">
                                <span class="material-symbols-outlined text-sky-500 text-[18px]">headphones</span> Nghe hiểu (20 Câu)
                            </h4>
                            <div class="space-y-4">
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 1</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Mỗi câu là 1 hình ảnh, bạn phải nghe cụm từ và căn cứ vào nội dung ảnh để phán đoán đúng sai.</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 2</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Mỗi câu là 3 hình ảnh, bạn phải nghe được miêu tả để chọn ra hình ảnh tương ứng.</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 3</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Mỗi câu là 1 cuộc hội thoại, đề thi cung cấp 1 số hình ảnh, thí sinh nghe và chọn hình ảnh phù hợp.</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 4</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Mỗi câu là 1 đoạn hội thoại. Có 1 câu hỏi và 3 lựa chọn, bạn phải chọn đáp án đúng.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Reading Section --}}
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 flex items-center gap-2 border-b border-slate-100 dark:border-slate-700/50 pb-2">
                                <span class="material-symbols-outlined text-amber-500 text-[18px]">menu_book</span> Đọc hiểu (20 Câu)
                            </h4>
                            <div class="space-y-4">
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 1</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Mỗi câu hỏi là 1 hình ảnh kèm 1 từ, chọn xem chúng có tương ứng nhau không.</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 2</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Cung cấp 1 câu thông tin và hình ảnh minh họa, chọn thông tin phù hợp với bức ảnh.</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 3</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Cung cấp 5 câu hỏi và đáp án tương ứng, ghép nối đáp án đúng với câu hỏi.</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phần 4</span>
                                        <span class="text-[10px] font-bold bg-white dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">5 câu</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Chọn từ thích hợp điền vào chỗ trống trong câu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-slate-500 dark:text-slate-400 italic text-center py-10">Cấu trúc đề thi đang được cập nhật...</p>
                    @endif
                </div>

            </div>

            {{-- RIGHT COLUMN: EXAM LIST --}}
            <div class="lg:col-span-2 xl:col-span-8 space-y-6">
                
                {{-- Exam Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-5">

                    {{-- Exam 1 (Completed) --}}
                    <div class="group bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 flex flex-col">
                        <div class="p-5 flex-1 relative">
                            <div class="flex justify-between items-start mb-3">
                                <div class="px-2.5 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Đã làm
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700/50 flex items-center justify-center text-slate-400 hover:text-amber-500 cursor-pointer transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">bookmark_border</span>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 group-hover:text-primary transition-colors">Đề Thi Thử HSK {{ $level }} - Mã Đề 01</h3>
                            
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-2 mt-4 mb-2">
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">schedule</span> Thời gian</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Phút</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">fact_check</span> Số câu hỏi</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Câu</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-700/50 mt-3">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px] text-emerald-500">military_tech</span> Điểm cao nhất</span>
                                    <span class="font-black text-emerald-500">195/200</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-3 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/20">
                            <a href="{{ route('student.hsk-mock-exams.take', ['level' => $level, 'id' => 1]) }}" class="w-full py-2.5 rounded-xl bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-bold transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">restart_alt</span> Làm lại đề
                            </a>
                        </div>
                    </div>

                    {{-- Exam 2 (Not Started) --}}
                    <div class="group bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 flex flex-col relative">
                        <div class="absolute top-0 left-0 w-full h-1 bg-primary opacity-0 group-hover:opacity-100 transition-all"></div>
                        
                        <div class="p-5 flex-1 relative">
                            <div class="flex justify-between items-start mb-3">
                                <div class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                    Chưa làm
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700/50 flex items-center justify-center text-slate-400 hover:text-amber-500 cursor-pointer transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">bookmark_border</span>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 group-hover:text-primary transition-colors">Đề Thi Thử HSK {{ $level }} - Mã Đề 02</h3>
                            
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-2 mt-4 mb-2">
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">schedule</span> Thời gian</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Phút</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">fact_check</span> Số câu hỏi</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Câu</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-700/50 mt-3">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px] text-slate-400">group</span> Lượt làm</span>
                                    <span class="font-bold text-slate-500">1,245</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-3 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/20">
                            <a href="{{ route('student.hsk-mock-exams.take', ['level' => $level, 'id' => 2]) }}" class="w-full py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white text-sm font-bold shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">play_circle</span> Bắt đầu thi
                            </a>
                        </div>
                    </div>

                    {{-- Exam 3 (Not Started - HOT) --}}
                    <div class="group bg-white dark:bg-slate-800/40 border border-amber-200 dark:border-amber-700/50 rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300 flex flex-col relative">
                        <div class="absolute top-0 right-0 overflow-hidden w-24 h-24 pointer-events-none">
                            <div class="absolute top-4 -right-8 w-32 bg-rose-500 text-white text-[10px] font-bold text-center py-1 uppercase tracking-widest rotate-45 shadow-sm">Mới Nhất</div>
                        </div>
                        
                        <div class="p-5 flex-1 relative">
                            <div class="flex justify-between items-start mb-3">
                                <div class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                    Chưa làm
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-500 transition-colors">Đề Thi Thử HSK {{ $level }} - Mã Đề 03</h3>
                            
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-2 mt-4 mb-2">
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">schedule</span> Thời gian</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Phút</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">fact_check</span> Số câu hỏi</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Câu</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-700/50 mt-3">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px] text-slate-400">group</span> Lượt làm</span>
                                    <span class="font-bold text-slate-500">8,561</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-3 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/20">
                            <a href="{{ route('student.hsk-mock-exams.take', ['level' => $level, 'id' => 3]) }}" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-md shadow-amber-500/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">workspace_premium</span> Bắt đầu thi ngay
                            </a>
                        </div>
                    </div>

                    {{-- Exam 4 (Not Started) --}}
                    <div class="group bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 flex flex-col relative">
                        <div class="absolute top-0 left-0 w-full h-1 bg-primary opacity-0 group-hover:opacity-100 transition-all"></div>
                        
                        <div class="p-5 flex-1 relative">
                            <div class="flex justify-between items-start mb-3">
                                <div class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                    Chưa làm
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700/50 flex items-center justify-center text-slate-400 hover:text-amber-500 cursor-pointer transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">bookmark_border</span>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 group-hover:text-primary transition-colors">Đề Thi Thử HSK {{ $level }} - Mã Đề 04</h3>
                            
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-2 mt-4 mb-2">
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">schedule</span> Thời gian</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Phút</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">fact_check</span> Số câu hỏi</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">40 Câu</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-700/50 mt-3">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px] text-slate-400">group</span> Lượt làm</span>
                                    <span class="font-bold text-slate-500">540</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-3 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/20">
                            <a href="{{ route('student.hsk-mock-exams.take', ['level' => $level, 'id' => 4]) }}" class="w-full py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white text-sm font-bold shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">play_circle</span> Bắt đầu thi
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Pagination (Demo) --}}
                <div class="flex justify-center pt-6">
                    <nav class="flex gap-2">
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-primary hover:border-primary/50 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary text-white font-bold shadow-md shadow-primary/20">1</a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-primary hover:border-primary/50 font-semibold transition-colors">2</a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-primary hover:border-primary/50 font-semibold transition-colors">3</a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-primary hover:border-primary/50 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </a>
                    </nav>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
