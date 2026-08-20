@extends('portal.layouts.dashboard')

@section('title', 'Quản lý Đề thi HSK - XiaoMu Admin')

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
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1200px] mx-auto space-y-6">
            <!-- Header & Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Quản lý Đề thi thử HSK</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Danh sách các bộ đề thi thử HSK toàn hệ thống</p>
                </div>
                <div class="flex gap-3">
                    <button x-data @click="$dispatch('open-create-empty-modal')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Tạo đề trống
                    </button>
                    <a href="{{ route('admin.hsk-mock-exams.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm shadow-md shadow-primary/20 hover:bg-primary/90 transition-all">
                        <span class="material-symbols-outlined text-lg">upload_file</span>
                        Import dữ liệu
                    </a>
                </div>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)" 
                    class="p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-medium flex justify-between items-center">
                    {{ session('success') }}
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><span class="material-symbols-outlined text-sm">close</span></button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 5000)" 
                    class="p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-sm font-medium flex justify-between items-center">
                    {{ session('error') }}
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700"><span class="material-symbols-outlined text-sm">close</span></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-wrap gap-4 items-center justify-between shadow-sm">
                <form action="{{ route('admin.hsk-mock-exams.index') }}" method="GET" class="flex flex-wrap gap-3 items-center flex-1">
                    <div class="relative flex-1 min-w-[200px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm tên đề thi..."
                            class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-primary">
                    </div>
                    <select name="level" class="py-2 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-primary">
                        <option value="">Tất cả Cấp độ HSK</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->level_code }}" {{ request('level') == $level->level_code ? 'selected' : '' }}>
                                {{ $level->title }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white rounded-lg text-sm font-semibold hover:bg-slate-700">Lọc</button>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white font-bold text-xs uppercase border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Tên Đề thi</th>
                                <th class="px-6 py-4">Cấp độ</th>
                                <th class="px-6 py-4 text-center">Thời gian</th>
                                <th class="px-6 py-4 text-center">Số câu hỏi</th>
                                <th class="px-6 py-4 text-center">Trạng thái</th>
                                <th class="px-6 py-4 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($exams as $exam)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ $exam->id }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                        {{ $exam->title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-950/30 dark:border-amber-800">
                                            {{ $exam->hskLevel->title ?? 'HSK' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium">
                                        {{ $exam->duration }} phút
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                        {{ $exam->total_questions }} câu
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.hsk-mock-exams.toggle-publish', $exam->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="transition-all hover:scale-105">
                                                @if($exam->is_published)
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 ring-1 ring-emerald-200 shadow-sm">Xuất bản</span>
                                                @else
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 dark:bg-slate-800 ring-1 ring-slate-200 shadow-sm">Nháp</span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.hsk-mock-exams.edit', $exam->id) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg dark:hover:bg-blue-950/40 transition-colors"
                                                title="Sửa nội dung (Alpine Editor)">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="{{ route('admin.hsk-mock-exams.destroy', $exam->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa đề thi này không?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg dark:hover:bg-rose-950/40 transition-colors" title="Xóa đề">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        Chưa có đề thi thử nào. Hãy bấm "Import / Tạo đề mới" để bắt đầu!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($exams->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $exams->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Create Empty Exam Modal (Alpine JS) -->
    <div x-data="{ open: false }"
         @open-create-empty-modal.window="open = true"
         x-show="open"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
         
        <div @click.away="open = false" x-show="open" x-transition.opacity.scale.95
             class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-lg overflow-hidden border border-slate-100 dark:border-slate-800 shadow-2xl">
            <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-500">add_box</span>
                        Tạo đề thi mới
                    </h3>
                    <p class="text-slate-500 text-sm font-bold mt-1">Đề thi trống theo cấu trúc chuẩn</p>
                </div>
                <button @click="open = false" class="size-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-slate-500 text-lg">close</span>
                </button>
            </div>

            <form action="{{ route('admin.hsk-mock-exams.store-empty') }}" method="POST" class="p-8 space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tên Đề Thi <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="VD: Đề thi HSK 3 - Bộ mới"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm font-semibold text-slate-800 dark:text-white">
                </div>
                
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Cấp độ HSK <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <select name="level_code" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm font-semibold text-slate-800 dark:text-white appearance-none">
                            <option value="">-- Chọn Cấp độ --</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->level_code }}">{{ $level->title }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Thời gian làm bài (Phút) <span class="text-rose-500">*</span></label>
                    <input type="number" name="duration" required value="40" min="1"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm font-semibold text-slate-800 dark:text-white">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="open = false" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors text-sm">Hủy bỏ</button>
                    <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-primary hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Tạo Đề Thi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
