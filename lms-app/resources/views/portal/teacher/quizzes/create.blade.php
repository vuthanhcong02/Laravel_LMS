@extends('portal.layouts.dashboard')

@section('title', __('Thêm bài kiểm tra mới'))

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
                    <a href="{{ route('teacher.quizzes.index') }}" class="size-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Khởi tạo Bài thi') }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">{{ __('Thiết lập thông tin cơ bản cho bài kiểm tra') }}</p>
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

            <form action="{{ route('teacher.quizzes.store') }}" method="POST" enctype="multipart/form-data"
                  class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Khóa học') }} <span class="text-red-500">*</span></label>
                        <select name="course_id" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                            <option value="">-- {{ __('Chọn khóa học') }} --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Phân loại bài thi') }} <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                            @foreach(\App\Enums\QuizType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Tiêu đề bài thi') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="{{ __('Ví dụ: Kiểm tra cuối khóa HSK1') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Thời gian làm bài (Phút)') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="time_limit" value="{{ old('time_limit', 0) }}" min="0" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">{{ __('0 = Không giới hạn') }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">headphones</span>
                            <span>{{ __('File Audio nghe chung (Toàn bài thi / Phần Nghe)') }}</span>
                        </label>
                        <span class="text-[11px] font-semibold text-slate-400">MP3 / WAV / M4A ({{ __('Tối đa 50MB') }})</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Dành cho các đề thi nghe HSK hoặc bài thi có 1 file âm thanh phát liên tục từ đầu đến cuối bài thi.') }}
                    </p>
                    <input type="file"
                           name="audio_file"
                           accept="audio/*,.mp3,.wav,.ogg,.m4a,.aac"
                           class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-1 bg-white dark:bg-slate-800">
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('teacher.quizzes.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">{{ __('Hủy') }}</a>
                    <button type="submit" class="px-8 py-3 rounded-xl font-bold text-white bg-primary hover:bg-blue-600 transition-colors flex items-center gap-2 cursor-pointer shadow-sm active:scale-[0.98]">
                        <span class="material-symbols-outlined text-[18px]">save_as</span>
                        {{ __('Lưu và Tiếp tục') }}
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
