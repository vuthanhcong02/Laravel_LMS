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
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('teacher.assignments.index') }}" class="size-7.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-300 text-base">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Khởi tạo Bài tập') }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs">{{ __('Tạo bài tập mới cho học viên') }}</p>
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

            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data"
                  x-data="{
                      course_id: '{{ old('course_id') }}',
                      courses: {{ \Illuminate\Support\Js::from($courses->map(fn($c) => ['id' => $c->id, 'title' => $c->title, 'lessons' => $c->lessons])) }},
                      isSubmitting: false
                  }"
                  @submit="if (isSubmitting) { $event.preventDefault(); return false; } isSubmitting = true"
                  class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-7 space-y-5">
                @csrf

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
                                        <option :value="lesson.id" x-text="lesson.title" :selected="lesson.id == '{{ old('lesson_id') }}'"></option>
                                    </template>
                                </template>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Tiêu đề bài tập') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="{{ __('Nhập tiêu đề...') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" required>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Mô tả / Hướng dẫn') }}</label>
                    <textarea name="description" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" placeholder="{{ __('Hướng dẫn học viên làm bài...') }}">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-lms.file-uploader
                            name="attachments[]"
                            :maxFiles="5"
                            :maxSizeMB="10"
                            :label="__('Tệp đính kèm')"
                            :helperText="__('Hỗ trợ: PDF, Word, Excel, Ảnh, Audio, Zip... (Tối đa 5 tệp, 10MB/tệp)')" />
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Hạn nộp (Tùy chọn)') }}</label>
                            <input type="datetime-local" name="due_date" value="{{ old('due_date') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Trạng thái phát hành') }}</label>
                            <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary">
                                <option value="{{ \App\Models\Assignment::STATUS_PUBLISHED }}" {{ old('status', (string) \App\Models\Assignment::STATUS_PUBLISHED) == (string) \App\Models\Assignment::STATUS_PUBLISHED ? 'selected' : '' }}>{{ __('Đăng Bài (Công khai)') }}</option>
                                <option value="{{ \App\Models\Assignment::STATUS_DRAFT }}" {{ old('status') === (string) \App\Models\Assignment::STATUS_DRAFT ? 'selected' : '' }}>{{ __('Lưu Nháp (Học sinh chưa thấy)') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('teacher.assignments.index') }}" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-xs sm:text-sm">{{ __('Hủy') }}</a>
                    <button type="submit"
                            :disabled="isSubmitting"
                            class="px-5 py-2.5 rounded-xl font-bold text-white bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition-all text-xs sm:text-sm flex items-center gap-1.5 cursor-pointer active:scale-[0.98]">
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!isSubmitting" class="material-symbols-outlined text-base">save</span>
                        <span x-text="isSubmitting ? '{{ __('Đang lưu...') }}' : '{{ __('Lưu bài tập') }}'"></span>
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
