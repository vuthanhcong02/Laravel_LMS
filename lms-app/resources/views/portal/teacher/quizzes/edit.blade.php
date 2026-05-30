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
        <div class="max-w-4xl mx-auto space-y-8">
            <div class="flex items-center justify-between pb-2 mt-5 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <a href="{{ route('teacher.quizzes.index') }}" class="size-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 dark:text-white">{{ __('Chỉnh sửa Bài thi') }}</h1>
                        <p class="text-slate-500 font-medium text-sm">{{ __('Cập nhật thông tin cơ bản cho bài kiểm tra') }}</p>
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

            <form action="{{ route('teacher.quizzes.update', $quiz->id) }}" method="POST" 
                  class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Khóa học') }} <span class="text-red-500">*</span></label>
                        <select name="course_id" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $quiz->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Phân loại bài thi') }} <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                            @foreach(\App\Enums\QuizType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('type', $quiz->type->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Tiêu đề bài thi') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $quiz->title) }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Thời gian làm bài (Phút)') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" min="0" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 focus:border-primary focus:ring-primary" required>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">{{ __('0 = Không giới hạn') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('teacher.quizzes.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">{{ __('Hủy') }}</a>
                    <button type="submit" class="px-8 py-3 rounded-xl font-bold text-white bg-primary hover:bg-blue-600 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span> 
                        {{ __('Cập nhật') }}
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
