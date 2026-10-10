@extends('portal.layouts.dashboard')

@section('title', __('Quản lý Bài kiểm tra'))

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
        <div class="max-w-[1400px] mx-auto space-y-8">
            <x-portal.page-header
                :title="__('Quản lý Bài thi')"
                :description="__('Tạo và quản lý các bài trắc nghiệm, tự luận cho học viên.')">
                <x-slot:actions>
                    <a href="{{ route('teacher.quizzes.create') }}" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-xl font-semibold flex items-center gap-1.5 transition-all text-xs sm:text-sm shadow-sm active:scale-[0.98]">
                        <span class="material-symbols-outlined text-base">add</span>
                        {{ __('Thêm bài thi mới') }}
                    </a>
                </x-slot:actions>
            </x-portal.page-header>

            <x-flash-message />

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/75 dark:bg-slate-800/50 text-slate-400 text-[11px] uppercase tracking-wider font-semibold">
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Tiêu đề') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Khóa học') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Loại / Thời gian') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Số câu hỏi') }}</th>
                                <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-right">{{ __('Thao tác') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-xs sm:text-sm">
                            @forelse($quizzes as $quiz)
                                <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm truncate max-w-[200px]" title="{{ $quiz->title }}">
                                            {{ $quiz->title }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-xs sm:text-sm text-slate-900 dark:text-white truncate max-w-[250px]">{{ $quiz->course->title ?? __('N/A') }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold w-fit {{ $quiz->type->value === 'mixed' ? 'bg-purple-100 text-purple-700' : ($quiz->type->value === 'essay' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                                {{ $quiz->type->label() }}
                                            </span>
                                            <span class="text-[11px] text-slate-500 flex items-center gap-1 font-normal">
                                                <span class="material-symbols-outlined text-[13px]">timer</span>
                                                {{ $quiz->time_limit > 0 ? $quiz->time_limit . ' ' . __('phút') : __('Không giới hạn') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm">{{ $quiz->questions_count ?? $quiz->questions->count() }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-1 max-w-[180px]">

                                        <button @click="$dispatch('open-import-modal', { quiz_id: {{ $quiz->id }}, quiz_title: '{{ $quiz->title }}' })" class="inline-flex items-center justify-center size-7.5 text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors rounded-lg" title="{{ __('Nhập câu hỏi từ CSV') }}">
                                            <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                        </button>

                                        <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}" class="inline-flex items-center justify-center size-7.5 text-slate-400 hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors rounded-lg" title="{{ __('Sửa thông tin') }}">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>

                                        <a href="{{ route('teacher.quizzes.questions', $quiz->id) }}" class="inline-flex items-center justify-center size-7.5 text-slate-400 hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors rounded-lg" title="{{ __('Quản lý câu hỏi') }}">
                                            <span class="material-symbols-outlined text-[18px]">list_alt</span>
                                        </a>

                                        <a href="{{ route('teacher.quizzes.results', $quiz->id) }}" class="inline-flex items-center justify-center size-7.5 text-orange-500 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/40 transition-colors rounded-lg" title="{{ __('Xem kết quả & Thống kê nộp bài') }}">
                                            <span class="material-symbols-outlined text-[18px]">analytics</span>
                                        </a>

                                        <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Bạn có chắc chắn muốn xóa bài thi này?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center size-7.5 text-rose-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors rounded-lg" title="{{ __('Xóa bài thi') }}">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-500">
                                        <div class="size-16 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <span class="material-symbols-outlined text-3xl">inbox</span>
                                        </div>
                                        <p class="font-bold">{{ __('Bạn chưa tạo bài thi nào') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($quizzes->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                    {{ $quizzes->links() }}
                </div>
                @endif
            </div>

        </div>
    </main>

    <div x-data="{
            open: false,
            quizId: null,
            quizTitle: '',
            get actionUrl() { return `/portal/teacher/quizzes/${this.quizId}/import` }
        }"
        @open-import-modal.window="open = true; quizId = $event.detail.quiz_id; quizTitle = $event.detail.quiz_title"
        x-show="open"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        style="display: none;">

        <div @click.away="open = false" class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg overflow-hidden border border-slate-200 dark:border-slate-800 shadow-xl">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-emerald-500 text-xl">upload_file</span>
                        {{ __('Nhập câu hỏi từ CSV') }}
                    </h3>
                    <p class="text-slate-500 text-sm font-medium mt-1" x-text="quizTitle ? '{{ __('Bài thi') }}: ' + quizTitle : ''"></p>
                </div>
                <button @click="open = false" class="size-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-slate-500 text-base">close</span>
                </button>
            </div>

            <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Cấu trúc File CSV') }}</h4>
                        <a href="{{ route('teacher.quizzes.export-template') }}" class="flex items-center gap-1 px-2.5 py-1 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all text-xs font-semibold">
                            <span class="material-symbols-outlined text-sm">download</span>
                            {{ __('Tải tệp mẫu') }}
                        </a>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                        <div class="overflow-x-auto custom-scrollbar-h">
                            <table class="w-full text-left text-xs min-w-[600px]">
                                <thead class="bg-slate-100/70 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-800">
                                    <tr class="font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider text-[11px]">
                                        <th class="px-3 py-2 whitespace-nowrap">Question Text</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Type</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Marks</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Option A → D</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Correct Option</th>
                                    </tr>
                                </thead>
                                <tbody class="font-medium text-xs">
                                    <tr class="bg-white/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
                                        <td class="px-3 py-2 text-slate-600 italic truncate max-w-[150px]">Thủ đô VN là gì?</td>
                                        <td class="px-3 py-2 text-emerald-600 font-semibold">Trắc nghiệm</td>
                                        <td class="px-3 py-2 text-slate-600 text-center">1.0</td>
                                        <td class="px-3 py-2 text-slate-600">Hà Nội | TP.HCM | ...</td>
                                        <td class="px-3 py-2 text-amber-600 font-bold text-center">A</td>
                                    </tr>
                                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                        <td class="px-3 py-2 text-slate-600 italic truncate max-w-[150px]">Hà Nội là thủ đô?</td>
                                        <td class="px-3 py-2 text-blue-600 font-semibold">Đúng/Sai</td>
                                        <td class="px-3 py-2 text-slate-600 text-center">1.0</td>
                                        <td class="px-3 py-2 text-slate-600">Đúng | Sai | |</td>
                                        <td class="px-3 py-2 text-amber-600 font-bold text-center">A</td>
                                    </tr>
                                    <tr class="bg-white/50 dark:bg-slate-900/50">
                                        <td class="px-3 py-2 text-slate-600 italic truncate max-w-[150px]">Cảm nhận về HN...</td>
                                        <td class="px-3 py-2 text-purple-600 font-semibold">Tự luận</td>
                                        <td class="px-3 py-2 text-slate-600 text-center">5.0</td>
                                        <td class="px-3 py-2 text-slate-600">- | - | - | -</td>
                                        <td class="px-3 py-2 text-amber-600 font-bold text-center">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-2 italic px-1 leading-relaxed">
                        * {{ __('Lưu ý: Loại câu hỏi hợp lệ: Trắc nghiệm, Đúng/Sai, Tự luận. Đáp án đúng: A, B, C, hoặc D.') }}
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Chọn file CSV') }}</label>
                    <input type="file" name="csv_file" accept=".csv" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition-all cursor-pointer">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors text-sm">{{ __('Hủy') }}</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all text-sm">{{ __('Bắt đầu nhập') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
