@extends('portal.layouts.dashboard')

@section('title', 'Cập nhật Bài tập')

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
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Cập nhật Bài tập') }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">{{ $assignment->title }}</p>
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

            <form action="{{ route('teacher.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data"
                  x-data="{
                      course_id: '{{ old('course_id', $assignment->course_id) }}',
                      courses: {{ \Illuminate\Support\Js::from($courses->map(fn($c) => ['id' => $c->id, 'title' => $c->title, 'lessons' => $c->lessons])) }}
                  }"
                  class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm p-8 space-y-6">
                @csrf
                @method('PUT')

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
                                        <option :value="lesson.id" x-text="lesson.title" :selected="lesson.id == '{{ old('lesson_id', $assignment->lesson_id) }}'"></option>
                                    </template>
                                </template>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Tiêu đề bài tập <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $assignment->title) }}" placeholder="Nhập tiêu đề..." class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary" required>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Mô tả / Hướng dẫn</label>
                    <textarea name="description" rows="5" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="Hướng dẫn học viên làm bài...">{{ old('description', $assignment->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <x-lms.file-uploader
                            name="attachments[]"
                            :maxFiles="5"
                            :maxSizeMB="10"
                            :label="__('Tải lên tệp đính kèm mới')"
                            :helperText="__('Hỗ trợ: PDF, Word, Excel, Ảnh, Audio, Zip... (Tối đa 5 tệp, 10MB/tệp)')" />

                                                @if(!empty($assignment->attachments))
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">{{ __('Tệp đã đính kèm hiện tại:') }}</label>
                                <div class="space-y-2">
                                    @foreach($assignment->attachments as $i => $file)
                                        @php
                                            $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
                                            $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                                            $isAudio = in_array($ext, ['mp3', 'wav', 'ogg', 'webm', 'm4a']);
                                            $isPdf = ($ext === 'pdf');
                                            $fileViewerUrl = route('file.viewer', ['path' => $file['path']]);
                                        @endphp
                                        <div class="flex items-center justify-between gap-3 p-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl hover:border-slate-300 transition-colors">
                                            <label class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer">
                                                <input type="checkbox" name="keep_attachments[]" value="{{ $file['path'] }}" checked class="rounded text-primary focus:ring-primary size-4 shrink-0">
                                                <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-lg">
                                                        @if($isImg) image @elseif($isAudio) audio_file @elseif($isPdf) picture_as_pdf @else description @endif
                                                    </span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $file['name'] }}</p>
                                                    <span class="text-[10px] text-slate-400">{{ __('Tích để giữ lại tệp này') }}</span>
                                                </div>
                                            </label>

                                            <div class="flex items-center gap-1 shrink-0">
                                                <button type="button"
                                                        @click="$dispatch('open-file-preview', { url: '{{ $fileViewerUrl }}', name: '{{ addslashes($file['name']) }}' })"
                                                        class="size-8 rounded-lg text-slate-500 hover:text-primary hover:bg-primary/10 flex items-center justify-center transition-colors"
                                                        title="{{ __('Xem trước') }}">
                                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                </button>
                                                <a href="{{ $fileViewerUrl }}" target="_blank"
                                                   class="size-8 rounded-lg text-slate-500 hover:text-primary hover:bg-primary/10 flex items-center justify-center transition-colors"
                                                   title="{{ __('Mở tệp') }}">
                                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    <p class="text-[11px] text-slate-400 italic mt-1">{{ __('Bỏ tích chọn nếu muốn xóa tệp khỏi bài tập.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Hạn nộp</label>
                            <input type="datetime-local" name="due_date" value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Trạng thái phát hành</label>
                            <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="{{ \App\Models\Assignment::STATUS_DRAFT }}" {{ old('status', $assignment->status) == \App\Models\Assignment::STATUS_DRAFT ? 'selected' : '' }}>Lưu Nháp (Học sinh chưa thấy)</option>
                                <option value="{{ \App\Models\Assignment::STATUS_PUBLISHED }}" {{ old('status', $assignment->status) == \App\Models\Assignment::STATUS_PUBLISHED ? 'selected' : '' }}>Đăng Bài (Công khai)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-between gap-3">
                    <button type="button" class="px-6 py-3 rounded-xl font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors flex items-center gap-2" onclick="if(confirm('Bạn có chắc chắn muốn xóa bài tập này?')) document.getElementById('delete-form').submit();">
                        <span class="material-symbols-outlined text-[18px]">delete</span> Xóa
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('teacher.assignments.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Hủy</a>
                        <button type="submit" class="px-8 py-3 rounded-xl font-bold text-white bg-primary hover:bg-blue-600 shadow-md transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span> Cập nhật
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-form" action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </main>
@endsection
