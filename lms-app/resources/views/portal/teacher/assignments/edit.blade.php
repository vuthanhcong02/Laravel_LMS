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
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('teacher.assignments.index') }}" class="size-7.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-300 text-base">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Cập nhật Bài tập') }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs">{{ $assignment->title }}</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-rose-50 text-rose-600 p-4 rounded-xl border border-rose-100 mb-6 text-xs">
                    <ul class="list-disc pl-5 font-semibold space-y-1">
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
                  class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-7 space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Khóa học') }} <span class="text-rose-500">*</span></label>
                        <select name="course_id" x-model="course_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" required>
                            <option value="">-- {{ __('Chọn khóa học') }} --</option>
                            <template x-for="course in courses" :key="course.id">
                                <option :value="course.id" x-text="course.title" :selected="course.id == course_id"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Bài học (Tùy chọn)') }}</label>
                        <select name="lesson_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary">
                            <option value="">-- {{ __('Bài tập cấp khóa học') }} --</option>
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

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Tiêu đề bài tập') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $assignment->title) }}" placeholder="{{ __('Nhập tiêu đề...') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" required>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Mô tả / Hướng dẫn') }}</label>
                    <textarea name="description" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" placeholder="{{ __('Hướng dẫn học viên làm bài...') }}">{{ old('description', $assignment->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-3">
                        <x-lms.file-uploader
                            name="attachments[]"
                            :maxFiles="5"
                            :maxSizeMB="10"
                            :label="__('Tải lên tệp đính kèm mới')"
                            :helperText="__('Hỗ trợ: PDF, Word, Excel, Ảnh, Audio, Zip... (Tối đa 5 tệp, 10MB/tệp)')" />

                        @if(!empty($assignment->attachments))
                            <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-2">{{ __('Tệp đã đính kèm hiện tại:') }}</label>
                                <div class="space-y-1.5">
                                    @foreach($assignment->attachments as $i => $file)
                                        @php
                                            $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
                                            $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                                            $isAudio = in_array($ext, ['mp3', 'wav', 'ogg', 'webm', 'm4a']);
                                            $isPdf = ($ext === 'pdf');
                                            $fileViewerUrl = route('file.viewer', ['path' => $file['path']]);
                                        @endphp
                                        <div class="flex items-center justify-between gap-3 p-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl hover:border-slate-300 transition-colors">
                                            <label class="flex items-center gap-2.5 min-w-0 flex-1 cursor-pointer">
                                                <input type="checkbox" name="keep_attachments[]" value="{{ $file['path'] }}" checked class="rounded text-primary focus:ring-primary size-3.5 shrink-0">
                                                <div class="size-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-base">
                                                        @if($isImg) image @elseif($isAudio) audio_file @elseif($isPdf) picture_as_pdf @else description @endif
                                                    </span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ $file['name'] }}</p>
                                                    <span class="text-[10px] text-slate-400">{{ __('Tích để giữ lại tệp này') }}</span>
                                                </div>
                                            </label>

                                            <div class="flex items-center gap-1 shrink-0">
                                                <button type="button"
                                                        @click="$dispatch('open-file-preview', { url: '{{ $fileViewerUrl }}', name: '{{ addslashes($file['name']) }}' })"
                                                        class="size-7 rounded-lg text-slate-500 hover:text-primary hover:bg-primary/10 flex items-center justify-center transition-colors cursor-pointer"
                                                        title="{{ __('Xem trước') }}">
                                                    <span class="material-symbols-outlined text-base">visibility</span>
                                                </button>
                                                <a href="{{ $fileViewerUrl }}" target="_blank"
                                                   class="size-7 rounded-lg text-slate-500 hover:text-primary hover:bg-primary/10 flex items-center justify-center transition-colors"
                                                   title="{{ __('Mở tệp') }}">
                                                    <span class="material-symbols-outlined text-base">open_in_new</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    <p class="text-[10px] text-slate-400 italic mt-1">{{ __('Bỏ tích chọn nếu muốn xóa tệp khỏi bài tập.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Hạn nộp (Tùy chọn)') }}</label>
                            <input type="datetime-local" name="due_date" value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Trạng thái phát hành') }}</label>
                            <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary">
                                <option value="{{ \App\Models\Assignment::STATUS_DRAFT }}" {{ old('status', $assignment->status) == \App\Models\Assignment::STATUS_DRAFT ? 'selected' : '' }}>{{ __('Lưu Nháp (Học sinh chưa thấy)') }}</option>
                                <option value="{{ \App\Models\Assignment::STATUS_PUBLISHED }}" {{ old('status', $assignment->status) == \App\Models\Assignment::STATUS_PUBLISHED ? 'selected' : '' }}>{{ __('Đăng Bài (Công khai)') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-between gap-3">
                    <button type="button" class="px-5 py-2.5 rounded-xl font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 transition-colors flex items-center gap-1.5 cursor-pointer text-xs sm:text-sm" onclick="if(confirm('{{ __('Bạn có chắc chắn muốn xóa bài tập này?') }}')) document.getElementById('delete-form').submit();">
                        <span class="material-symbols-outlined text-base">delete</span> {{ __('Xóa') }}
                    </button>

                    <div class="flex gap-2.5">
                        <a href="{{ route('teacher.assignments.index') }}" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-xs sm:text-sm">{{ __('Hủy') }}</a>
                        <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-primary hover:bg-primary/90 shadow-sm transition-all flex items-center gap-1.5 cursor-pointer active:scale-[0.98] text-xs sm:text-sm">
                            <span class="material-symbols-outlined text-base">save</span> {{ __('Cập nhật') }}
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
