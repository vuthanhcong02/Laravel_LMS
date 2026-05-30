@extends('portal.layouts.dashboard')

@section('title', 'Bài tập của tôi')

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full" x-data="studentAssignments()">
        <div class="max-w-[1200px] mx-auto space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">assignment</span>
                        Bài tập của tôi
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold text-sm">Quản lý các bài thực hành và nhận xét từ giáo viên.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-300 font-bold flex items-center gap-2" role="alert">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 text-sm text-red-700 rounded-2xl bg-red-50 dark:bg-red-900/30 dark:text-red-300 font-bold flex items-center gap-2" role="alert">
                    <span class="material-symbols-outlined">error</span>
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6">
                @forelse($assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->first();
                        $isGraded   = $submission && $submission->status === \App\Models\AssignmentSubmission::STATUS_GRADED;
                        $isSubmitted = $submission && $submission->status === \App\Models\AssignmentSubmission::STATUS_SUBMITTED;
                        $overdue    = $assignment->due_date && $assignment->due_date->isPast();
                    @endphp

                    <div x-data="{ open: false }"
                         class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">

                        {{-- Card Header --}}
                        <button @click="open = !open" class="w-full text-left p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="flex items-start gap-5">
                                {{-- Status icon --}}
                                <div class="mt-1 size-14 rounded-2xl flex items-center justify-center shrink-0
                                    {{ $isGraded ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600' : ($isSubmitted ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-500' : 'bg-orange-50 dark:bg-orange-900/30 text-orange-500') }}">
                                    <span class="material-symbols-outlined text-3xl">
                                        {{ $isGraded ? 'verified' : ($isSubmitted ? 'cloud_done' : 'history_edu') }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="font-black text-xl text-slate-800 dark:text-white line-clamp-1">{{ $assignment->title }}</h2>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500">{{ $assignment->course->title ?? '' }}</span>
                                        @if($assignment->lesson)
                                            <span class="text-slate-300">·</span>
                                            <span class="text-xs font-bold text-slate-400">{{ $assignment->lesson->title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 shrink-0 self-end md:self-center">
                                @if($assignment->due_date)
                                    <div class="text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 px-3 py-2 rounded-xl {{ $overdue ? 'bg-red-50 text-red-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                                        <span class="material-symbols-outlined text-[14px]">event_busy</span>
                                        {{ $assignment->due_date->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                                
                                <div class="flex flex-col items-end">
                                    @if($isGraded)
                                        <div class="text-right">
                                            <span class="text-2xl font-black text-emerald-600">{{ (float) $submission->score }}</span>
                                            <span class="text-xs font-bold text-emerald-400">/10</span>
                                        </div>
                                    @elseif($isSubmitted)
                                        <span class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-blue-600 bg-blue-50 dark:bg-blue-900/30 rounded-full">Đã nộp</span>
                                    @else
                                        <span class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-orange-600 bg-orange-50 dark:bg-orange-900/30 rounded-full">Chưa nộp</span>
                                    @endif
                                </div>
                                <span class="material-symbols-outlined text-slate-300 transition-transform duration-300" :class="open ? 'rotate-180 text-primary' : '' ">expand_more</span>
                            </div>
                        </button>

                        {{-- Expanded details --}}
                        <div x-show="open" x-collapse class="border-t border-slate-50 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/30">
                            <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-12">

                                {{-- LEFT: Instruction & Materials --}}
                                <div class="space-y-8">
                                    <div>
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 block">Hướng dẫn từ giáo viên</label>
                                        <div class="bg-white dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-300 leading-relaxed shadow-sm">
                                            {!! nl2br(e($assignment->description)) !!}
                                            
                                            @if(!empty($assignment->attachments))
                                                <div class="mt-6 pt-6 border-t border-slate-50 dark:border-slate-800">
                                                    <p class="text-xs font-black mb-3">Tài liệu tham khảo:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($assignment->attachments as $file)
                                                            <a href="#" class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800 rounded-xl text-xs font-bold text-primary hover:bg-primary hover:text-white transition-all border border-slate-100 dark:border-slate-700">
                                                                <span class="material-symbols-outlined text-[16px]">attach_file</span>
                                                                {{ $file['name'] }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($isGraded)
                                        <div class="space-y-4">
                                            <label class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-4 block">Nhận xét & Góp ý của giáo viên</label>
                                            <div class="bg-emerald-50 dark:bg-emerald-900/20 p-6 rounded-3xl border border-emerald-100 dark:border-emerald-800/50">
                                                @if($submission->teacher_audio_path)
                                                    <div class="mb-4 bg-white dark:bg-slate-800 p-4 rounded-2xl flex items-center gap-4 shadow-sm">
                                                        <div class="size-10 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0">
                                                            <span class="material-symbols-outlined">graphic_eq</span>
                                                        </div>
                                                        <audio src="{{ route('file.viewer', ['path' => $submission->teacher_audio_path]) }}" controls class="h-8 flex-1"></audio>
                                                    </div>
                                                @endif
                                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300 italic">
                                                    "{{ $submission->teacher_feedback ?? 'Giáo viên không để lại nhận xét văn bản.' }}"
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- RIGHT: Submission Form --}}
                                <div class="space-y-6">
                                    @if($isGraded)
                                        <div class="bg-white dark:bg-slate-800 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col items-center justify-center text-center">
                                            <div class="size-24 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-500 mb-6 scale-125">
                                                <span class="material-symbols-outlined text-5xl">verified</span>
                                            </div>
                                            <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ (float) $submission->score }}<span class="text-sm text-slate-400">/10</span></h3>
                                            <p class="text-sm font-bold text-slate-500 mt-2">Bài làm đã hoàn thành tuyệt vời!</p>
                                        </div>
                                    @elseif($overdue && !$isSubmitted)
                                        <div class="bg-white dark:bg-slate-800 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col items-center justify-center text-center">
                                            <div class="size-20 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-400 mb-4">
                                                <span class="material-symbols-outlined text-4xl">running_with_errors</span>
                                            </div>
                                            <h3 class="text-xl font-black text-red-600">Hết hạn nộp bài</h3>
                                            <p class="text-xs font-bold text-slate-400 mt-2 uppercase">Vui lòng liên hệ giáo viên</p>
                                        </div>
                                    @else
                                        <div class="bg-white dark:bg-slate-800 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
                                            <h4 class="text-sm font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-primary">cloud_upload</span>
                                                {{ $isSubmitted ? 'Cập nhật bài làm' : 'Nộp bài ngay' }}
                                            </h4>
                                            
                                            <form action="{{ route('student.assignments.submit', $assignment->id) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data"
                                                  class="space-y-8">
                                                @csrf
                                                
                                                <!-- Audio Recorder Option -->
                                                <div>
                                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Lựa chọn 1: Ghi âm giọng nói</p>
                                                    <x-audio-recorder name="attachments[]" />
                                                </div>

                                                <div class="relative flex items-center gap-4 py-2">
                                                    <div class="flex-1 h-px bg-slate-100 dark:bg-slate-800"></div>
                                                    <span class="text-[10px] font-black text-slate-300 uppercase">Hoặc</span>
                                                    <div class="flex-1 h-px bg-slate-100 dark:bg-slate-800"></div>
                                                </div>

                                                <!-- File Upload Option -->
                                                <div>
                                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Lựa chọn 2: Tải lên tệp tin</p>
                                                    <label class="group relative flex flex-col items-center justify-center w-full h-32 border-2 border-slate-100 dark:border-slate-700 border-dashed rounded-[2rem] cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all active:scale-[0.98]">
                                                        <input type="file" name="attachments[]" multiple class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                                                        <span class="material-symbols-outlined text-3xl text-slate-300 group-hover:text-primary transition-colors">upload_file</span>
                                                        <span class="text-xs font-bold text-slate-400 mt-2">Kéo thả hoặc nhấn để chọn tệp</span>
                                                    </label>
                                                </div>

                                                <button type="submit"
                                                        class="w-full py-4 bg-primary hover:bg-blue-600 text-white rounded-3xl font-black text-sm shadow-xl shadow-primary/25 transition-all flex items-center justify-center gap-2 group">
                                                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform">send</span>
                                                    Gửi bài làm
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-dashed border-slate-300 dark:border-slate-700 p-20 text-center">
                        <span class="material-symbols-outlined text-7xl text-slate-200 dark:text-slate-800 mb-6">inbox</span>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Thảnh thơi quá!</h3>
                        <p class="text-slate-500 font-bold">Hiện tại bạn không có bài tập nào cần hoàn thành.</p>
                    </div>
                @endforelse
            </div>

            @if($assignments->hasPages())
                <div class="mt-8">{{ $assignments->links('components.pagination') }}</div>
            @endif
        </div>
    </main>
@endsection

@section('scripts')
<script>
function studentAssignments() {
    return {
        // Shared logic if needed
    }
}
</script>
@endsection
