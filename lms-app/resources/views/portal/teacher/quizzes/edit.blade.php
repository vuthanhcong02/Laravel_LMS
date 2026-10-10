@extends('portal.layouts.dashboard')

@section('title', __('Chỉnh sửa bài kiểm tra'))

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
                    <a href="{{ route('teacher.quizzes.index') }}" class="size-7.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-300 text-base">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Chỉnh sửa Bài thi') }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs">{{ __('Cập nhật thông tin cơ bản cho bài kiểm tra') }}</p>
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

            <form action="{{ route('teacher.quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data"
                  class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 space-y-5 shadow-sm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Khóa học') }} <span class="text-rose-500">*</span></label>
                        <select name="course_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $quiz->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Phân loại bài thi') }} <span class="text-rose-500">*</span></label>
                        <select name="type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" required>
                            @foreach(\App\Enums\QuizType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('type', $quiz->type->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Tiêu đề bài thi') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $quiz->title) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Thời gian làm bài (Phút)') }} <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs sm:text-sm font-medium text-slate-900 dark:text-white shadow-xs focus:border-primary focus:ring-primary" required>
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[11px] font-semibold text-slate-400">{{ __('0 = Không giới hạn') }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-base">headphones</span>
                            <span>{{ __('File Audio nghe chung (Toàn bài thi / Phần Nghe)') }}</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400">MP3 / WAV / M4A ({{ __('Tối đa 50MB') }})</span>
                    </div>

                    @if($quiz->audio_url)
                        <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-emerald-500 text-base">check_circle</span>
                                    {{ __('Đã có file audio cho bài thi') }}
                                </span>
                                <label class="flex items-center gap-2 text-[11px] font-semibold text-rose-600 hover:text-rose-700 cursor-pointer">
                                    <input type="checkbox" name="remove_audio" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    <span>{{ __('Xóa audio hiện tại') }}</span>
                                </label>
                            </div>
                            <audio controls class="w-full h-8" preload="none">
                                <source src="{{ $quiz->audio_url }}" type="audio/mpeg">
                                {{ __('Trình duyệt không hỗ trợ phát audio.') }}
                            </audio>
                        </div>
                    @endif

                    <div class="space-y-1.5">
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            {{ $quiz->audio_url ? __('Tải lên file âm thanh mới để thay thế file hiện tại:') : __('Tải lên file âm thanh cho bài thi:') }}
                        </p>
                        <input type="file"
                               name="audio_file"
                               accept="audio/*,.mp3,.wav,.ogg,.m4a,.aac"
                               class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-1 bg-white dark:bg-slate-800">
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('teacher.quizzes.index') }}" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-xs sm:text-sm">{{ __('Hủy') }}</a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-primary hover:bg-primary/90 transition-all flex items-center gap-1.5 cursor-pointer active:scale-[0.98] text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-base">save</span>
                        {{ __('Cập nhật') }}
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
