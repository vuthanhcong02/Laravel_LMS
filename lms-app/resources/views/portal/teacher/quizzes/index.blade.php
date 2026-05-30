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
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">quiz</span>
                        {{ __('Quản lý Bài thi') }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">{{ __('Tạo và quản lý các bài trắc nghiệm, tự luận cho học viên.') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('teacher.quizzes.create') }}" class="px-5 py-2.5 bg-primary hover:bg-blue-600 text-white rounded-xl font-bold flex items-center gap-2 transition-all text-sm">
                        <span class="material-symbols-outlined text-lg">add</span>
                        {{ __('Thêm bài thi mới') }}
                    </a>
                </div>
            </div>

            <x-flash-message />

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-widest font-black">
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">{{ __('Tiêu đề') }}</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">{{ __('Khóa học') }}</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">{{ __('Loại / Thời gian') }}</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800">{{ __('Số câu hỏi') }}</th>
                                <th class="p-6 border-b border-slate-100 dark:border-slate-800 text-right">{{ __('Thao tác') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-sm">
                            @forelse($quizzes as $quiz)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0">
                                    <td class="p-6">
                                        <p class="font-bold text-slate-900 dark:text-white text-base truncate max-w-[200px]" title="{{ $quiz->title }}">
                                            {{ $quiz->title }}
                                        </p>
                                    </td>
                                    <td class="p-6">
                                        <p class="font-bold truncate max-w-[250px]">{{ $quiz->course->title ?? __('N/A') }}</p>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold w-fit {{ $quiz->type->value === 'mixed' ? 'bg-purple-100 text-purple-700' : ($quiz->type->value === 'essay' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                                {{ $quiz->type->label() }}
                                            </span>
                                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">timer</span>
                                                {{ $quiz->time_limit > 0 ? $quiz->time_limit . ' ' . __('phút') : __('Không giới hạn') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-6 text-center">
                                        <span class="font-black text-slate-900 dark:text-white">{{ $quiz->questions_count ?? $quiz->questions->count() }}</span>
                                    </td>
                                    <td class="p-6 text-right space-x-1 max-w-[200px]">
                                        <!-- Import Questions CSV -->
                                        <button @click="$dispatch('open-import-modal', { quiz_id: {{ $quiz->id }}, quiz_title: '{{ $quiz->title }}' })" class="inline-flex items-center justify-center size-8 bg-emerald-50 hover:bg-emerald-600 hover:text-white dark:bg-emerald-900/30 text-emerald-600 transition-colors rounded-lg" title="{{ __('Nhập câu hỏi từ CSV') }}">
                                            <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                        </button>
                                        <!-- Edit Basic Info -->
                                        <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}" class="inline-flex items-center justify-center size-8 bg-slate-100 hover:bg-primary hover:text-white dark:bg-slate-800 text-slate-600 transition-colors rounded-lg" title="{{ __('Sửa thông tin') }}">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <!-- Manage Questions -->
                                        <a href="{{ route('teacher.quizzes.questions', $quiz->id) }}" class="inline-flex items-center justify-center size-8 bg-blue-50 hover:bg-blue-600 hover:text-white dark:bg-blue-900/30 text-blue-600 transition-colors rounded-lg" title="{{ __('Quản lý câu hỏi') }}">
                                            <span class="material-symbols-outlined text-[18px]">list_alt</span>
                                        </a>
                                        <!-- View Submissions/Results (Placeholder for now) -->
                                        <a href="#" class="inline-flex items-center justify-center size-8 bg-orange-50 hover:bg-orange-500 hover:text-white dark:bg-orange-900/30 text-orange-600 transition-colors rounded-lg" title="{{ __('Xem kết quả') }}">
                                            <span class="material-symbols-outlined text-[18px]">analytics</span>
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Bạn có chắc chắn muốn xóa bài thi này?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center size-8 bg-red-50 hover:bg-red-600 hover:text-white dark:bg-red-900/30 text-red-600 transition-colors rounded-lg">
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
                <!-- Pagination -->
                @if($quizzes->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                    {{ $quizzes->links() }}
                </div>
                @endif
            </div>

        </div>
    </main>

    {{-- Import CSV Modal --}}
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

        <div @click.away="open = false" class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-lg overflow-hidden border border-slate-100 dark:border-slate-800">
            <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-500">upload_file</span>
                        {{ __('Nhập câu hỏi từ CSV') }}
                    </h3>
                    <p class="text-slate-500 text-sm font-bold mt-1" x-text="quizTitle ? '{{ __('Bài thi') }}: ' + quizTitle : ''"></p>
                </div>
                <button @click="open = false" class="size-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-slate-500 text-lg">close</span>
                </button>
            </div>

            <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                @csrf

                {{-- Cấu trúc CSV --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('Cấu trúc File CSV') }}</h4>
                        <a href="{{ route('teacher.quizzes.export-template') }}" class="flex items-center gap-1 px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all text-[10px] font-black uppercase">
                            <span class="material-symbols-outlined text-sm">download</span>
                            {{ __('Tải tệp mẫu') }}
                        </a>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
                        <div class="overflow-x-auto custom-scrollbar-h">
                            <table class="w-full text-left text-[10px] min-w-[600px]">
                                <thead class="bg-slate-100/50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800">
                                    <tr class="font-black text-slate-500 uppercase tracking-tighter">
                                        <th class="px-3 py-2 whitespace-nowrap">Question Text</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Type</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Marks</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Option A → D</th>
                                        <th class="px-3 py-2 whitespace-nowrap">Correct Option</th>
                                    </tr>
                                </thead>
                                <tbody class="font-bold">
                                    <tr class="bg-white/50 dark:bg-slate-900/50">
                                        <td class="px-3 py-2 text-slate-500 italic truncate max-w-[150px]">Thủ đô VN là gì?</td>
                                        <td class="px-3 py-2 text-emerald-500">Trắc nghiệm</td>
                                        <td class="px-3 py-2 text-slate-500 text-center">1.0</td>
                                        <td class="px-3 py-2 text-slate-500">Hà Nội | TP.HCM | ...</td>
                                        <td class="px-3 py-2 text-amber-500 text-center">A</td>
                                    </tr>
                                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                                        <td class="px-3 py-2 text-slate-500 italic truncate max-w-[150px]">Hà Nội là thủ đô?</td>
                                        <td class="px-3 py-2 text-blue-500">Đúng/Sai</td>
                                        <td class="px-3 py-2 text-slate-500 text-center">1.0</td>
                                        <td class="px-3 py-2 text-slate-500">Đúng | Sai | |</td>
                                        <td class="px-3 py-2 text-amber-500 text-center">A</td>
                                    </tr>
                                    <tr class="bg-white/50 dark:bg-slate-900/50">
                                        <td class="px-3 py-2 text-slate-500 italic truncate max-w-[150px]">Cảm nhận về HN...</td>
                                        <td class="px-3 py-2 text-purple-500">Tự luận</td>
                                        <td class="px-3 py-2 text-slate-500 text-center">5.0</td>
                                        <td class="px-3 py-2 text-slate-500">- | - | - | -</td>
                                        <td class="px-3 py-2 text-amber-500 text-center">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <p class="text-[9px] text-slate-400 mt-2 italic px-1 leading-relaxed">
                        * {{ __('Lưu ý: Loại câu hỏi hợp lệ: Trắc nghiệm, Đúng/Sai, Tự luận. Đáp án đúng: A, B, C, hoặc D.') }}
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Chọn file CSV') }}</label>
                    <input type="file" name="csv_file" accept=".csv" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-primary file:text-white hover:file:bg-blue-600 transition-all cursor-pointer">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors">{{ __('Hủy') }}</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-black transition-all">{{ __('Bắt đầu nhập') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
