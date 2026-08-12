@extends('portal.layouts.dashboard')

@section('title', 'Import Đề thi HSK - XiaoMu Admin')

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
        <div class="max-w-[800px] mx-auto space-y-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.hsk-mock-exams.index') }}" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Import / Tạo Đề thi HSK mới</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Tải lên file JSON chứa cấu trúc đề thi và file nén ZIP chứa phương tiện</p>
                </div>
            </div>

            @if(session('error'))
                <div class="p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.hsk-mock-exams.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-900 dark:text-white">
                        File Cấu trúc Đề thi <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex gap-4 items-start">
                        <input type="file" name="data_file" accept=".json,.csv" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 border border-slate-200 dark:border-slate-800 rounded-xl p-2 bg-slate-50 dark:bg-slate-800">
                        <a href="{{ route('admin.hsk-mock-exams.download-template') }}" class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition-all whitespace-nowrap">
                            <span class="material-symbols-outlined text-lg">download</span>
                            Tải file CSV Mẫu
                        </a>
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Hỗ trợ file `exam.json` từ công cụ AI, hoặc file `template.csv` điền tay.</p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-900 dark:text-white">
                        Kéo thả toàn bộ Phương tiện (Hình ảnh & Audio) <span class="text-slate-400 font-normal">(Tùy chọn)</span>
                    </label>
                    <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-8 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group text-center cursor-pointer overflow-hidden">
                        <input type="file" name="media_files[]" accept="image/*,audio/*" multiple
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="mediaInput">
                        
                        <div class="flex flex-col items-center justify-center space-y-3 pointer-events-none">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Click hoặc Kéo thả nhiều file vào đây</p>
                                <p class="text-xs text-slate-400 mt-1">Hỗ trợ các định dạng .mp3, .png, .jpg (Không cần nén ZIP)</p>
                            </div>
                            <p id="mediaCount" class="text-xs font-bold text-primary hidden bg-primary/10 px-3 py-1 rounded-full"></p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('admin.hsk-mock-exams.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 font-semibold text-sm text-slate-600 dark:text-slate-300">
                        Hủy
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-bold text-sm shadow-md shadow-primary/20 hover:bg-primary/90">
                        Bắt đầu Import
                    </button>
                </div>
            </form>
        </div>
    </main>
    <script>
        document.getElementById('mediaInput').addEventListener('change', function(e) {
            const count = e.target.files.length;
            const textElement = document.getElementById('mediaCount');
            if (count > 0) {
                textElement.textContent = `Đã chọn ${count} file`;
                textElement.classList.remove('hidden');
            } else {
                textElement.classList.add('hidden');
            }
        });
    </script>
@endsection
