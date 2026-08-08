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
                        File Cấu trúc JSON đề thi <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="json_file" accept=".json,.txt" required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 border border-slate-200 dark:border-slate-800 rounded-xl p-2 bg-slate-50 dark:bg-slate-800">
                    <p class="text-xs text-slate-400">File `exam.json` được tạo ra từ công cụ AI Convert PDF của bạn.</p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-900 dark:text-white">
                        File nén ZIP Phương tiện (Hình ảnh & Audio) <span class="text-slate-400 font-normal">(Tùy chọn)</span>
                    </label>
                    <input type="file" name="zip_file" accept=".zip"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl p-2 bg-slate-50 dark:bg-slate-800">
                    <p class="text-xs text-slate-400">File ZIP chứa 2 thư mục `images/` và `audio/`. Nếu đề thi đã có sẵn hình ảnh trên server thì không cần nén ZIP.</p>
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
@endsection
