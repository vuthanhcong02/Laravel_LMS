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
                    <a href="{{ route('admin.hsk-mock-exams.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm shadow-md shadow-primary/20 hover:bg-primary/90 transition-all">
                        <span class="material-symbols-outlined text-lg">upload_file</span>
                        Import / Tạo đề mới
                    </a>
                </div>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-sm font-medium">
                    {{ session('error') }}
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
                                {{ $level->name }}
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
                                            {{ $exam->hskLevel->name ?? 'HSK' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium">
                                        {{ $exam->duration }} phút
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                        {{ $exam->total_questions }} câu
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($exam->is_published)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30">Xuất bản</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 dark:bg-slate-800">Nháp</span>
                                        @endif
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
@endsection
