@extends('portal.layouts.dashboard')

@section('title', 'Tạo Yêu cầu Hỗ trợ')

@section('header')
    @if (auth()->user()->role === \App\Models\User::ROLE_ADMIN)
        @include('portal.admin.layouts.header')
    @elseif(auth()->user()->role === \App\Models\User::ROLE_TEACHER)
        @include('portal.teacher.layouts.header')
    @else
        @include('portal.student.layouts.header')
    @endif
@endsection

@section('sidebar')
    @if (auth()->user()->role === \App\Models\User::ROLE_ADMIN)
        @include('portal.admin.layouts.sidebar')
    @elseif(auth()->user()->role === \App\Models\User::ROLE_TEACHER)
        @include('portal.teacher.layouts.sidebar')
    @else
        @include('portal.student.layouts.sidebar')
    @endif
@endsection

@section('content')
    <div class="p-6">
        <div class="max-w-[800px] mx-auto space-y-6">
            <div class="flex items-center gap-4 mb-6 mt-5">
                <a href="{{ route('support.index') }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Gửi yêu cầu hỗ trợ mới</h1>
                    <p class="text-sm text-slate-500">Mô tả chi tiết vấn đề bạn đang gặp phải để chúng tôi hỗ trợ tốt nhất.</p>
                </div>
            </div>

            <x-admin.flash-message type="error" />

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm">
                <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tiêu đề (Tóm tắt vấn đề) <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-4 py-2.5 outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                            placeholder="Ví dụ: Lỗi khi nộp bài tập môn Tiếng Trung cơ bản 1">
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Mức độ ưu tiên</label>
                        <select name="priority" id="priority" class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-4 py-2.5 outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Thấp (Không gấp)</option>
                            <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Bình thường</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Cao (Cần xử lý sớm)</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Khẩn cấp (Lỗi nghiêm trọng)</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nội dung chi tiết <span class="text-red-500">*</span></label>
                        <textarea name="message" id="message" rows="6" required
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-4 py-2.5 outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                            placeholder="Mô tả cụ thể vấn đề hoặc câu hỏi của bạn...">{{ old('message') }}</textarea>
                    </div>

                    <div>
                        <label for="attachment" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tệp đính kèm (Tùy chọn)</label>
                        <div class="flex items-center gap-3">
                            <label class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-sm font-medium text-slate-600 dark:text-slate-300 transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">attach_file</span> Chọn file
                                <input type="file" name="attachment" id="attachment" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                    onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Chưa chọn file nào'">
                            </label>
                            <span id="file-name" class="text-sm text-slate-500">Chưa chọn file nào</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Hỗ trợ: JPG, PNG, PDF, DOC, DOCX. Tối đa 2MB.</p>
                    </div>

                    <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2 shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">send</span> Gửi yêu cầu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
