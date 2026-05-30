@extends('portal.layouts.dashboard')

@section('title', 'Thêm bài tập mới')

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
        <div class="max-w-4xl mx-auto space-y-8">
            <div class="flex items-center justify-between pb-2 mt-5 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <a href="{{ route('teacher.assignments.index') }}" class="size-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Khởi tạo Bài tập</h1>
                        <p class="text-slate-500 font-medium text-sm">Tạo bài tập mới cho học viên</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 mb-6">
                    <ul class="list-disc pl-5 font-bold text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data" 
                  x-data="{
                      course_id: '{{ old('course_id') }}',
                      courses: {{ \Illuminate\Support\Js::from($courses->map(fn($c) => ['id' => $c->id, 'title' => $c->title, 'lessons' => $c->lessons])) }}
                  }"
                  class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Khóa học <span class="text-red-500">*</span></label>
                        <select name="course_id" x-model="course_id" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary" required>
                            <option value="">-- Chọn khóa học --</option>
                            <template x-for="course in courses" :key="course.id">
                                <option :value="course.id" x-text="course.title" :selected="course.id == course_id"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Bài học (Tùy chọn)</label>
                        <select name="lesson_id" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">-- Bài tập cấp khóa học --</option>
                            <template x-for="course in courses" :key="'lesson-group-'+course.id">
                                <template x-if="course_id == course.id">
                                    <template x-for="lesson in course.lessons" :key="lesson.id">
                                        <option :value="lesson.id" x-text="lesson.title" :selected="lesson.id == '{{ old('lesson_id') }}'"></option>
                                    </template>
                                </template>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Tiêu đề bài tập <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Nhập tiêu đề..." class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary" required>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Mô tả / Hướng dẫn</label>
                    <textarea name="description" rows="5" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="Hướng dẫn học viên làm bài...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2" x-data="{ files: [] }">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Tệp đính kèm (Tối đa 5 files, 10MB/file)</label>
                        <div class="relative flex items-center justify-center w-full h-32 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-xl cursor-pointer bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                            <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip" 
                                   @change="files = Array.from($event.target.files)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="text-center" x-show="files.length === 0">
                                <span class="material-symbols-outlined text-4xl text-slate-400">upload_file</span>
                                <p class="text-sm text-slate-500 font-bold mt-2">Kéo thả hoặc Click để Upload</p>
                            </div>
                            <div class="text-center px-4" x-show="files.length > 0" style="display: none;">
                                <span class="material-symbols-outlined text-4xl text-emerald-500">task</span>
                                <p class="text-sm text-emerald-600 font-bold mt-2" x-text="files.length + ' file(s) selected'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Hạn nộp (Tùy chọn)</label>
                            <input type="datetime-local" name="due_date" value="{{ old('due_date') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Trạng thái phát hành</label>
                            <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="{{ \App\Models\Assignment::STATUS_DRAFT }}">Lưu Nháp (Học sinh chưa thấy)</option>
                                <option value="{{ \App\Models\Assignment::STATUS_PUBLISHED }}">Đăng Bài (Công khai)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('teacher.assignments.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Hủy</a>
                    <button type="submit" class="px-8 py-3 rounded-xl font-bold text-white bg-primary hover:bg-blue-600 shadow-md transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span> Lưu Bài Tập
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
