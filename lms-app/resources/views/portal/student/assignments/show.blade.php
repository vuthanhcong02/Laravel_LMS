@extends('portal.layouts.dashboard')

@section('title', $assignment->title . ' - ' . config('app.name', 'LMS'))

@section('header')
    @include('portal.student.layouts.header')
@endsection

@section('sidebar')
    @include('portal.student.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1000px] mx-auto space-y-8">
            
            <nav class="flex items-center text-sm text-slate-500 font-medium mb-4">
                <a href="{{ route('student.courses.show', $assignment->course_id) }}" class="hover:text-primary transition-colors">Khóa học</a>
                <span class="material-symbols-outlined text-[18px] mx-1">chevron_right</span>
                <span class="text-slate-800 dark:text-white truncate">{{ $assignment->title }}</span>
            </nav>

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm p-6 lg:p-10">
                <div class="flex items-start justify-between gap-6 mb-8">
                    <div>
                        <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-widest mb-4 inline-block">
                            Bài tập tự luận
                        </span>
                        <h1 class="text-2xl lg:text-3xl font-black leading-tight text-slate-800 dark:text-white">{{ $assignment->title }}</h1>
                    </div>
                    
                    <div class="shrink-0 text-right">
                        <p class="text-sm text-slate-500 font-medium">Hạn nộp</p>
                        <p class="text-lg font-bold text-amber-600 dark:text-amber-500">
                            {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y H:i') : 'Không giới hạn' }}
                        </p>
                    </div>
                </div>

                <div class="prose dark:prose-invert max-w-none mb-8 text-slate-600 dark:text-slate-300">
                    {!! nl2br(e($assignment->description)) !!}
                </div>

                @if(!empty($assignment->attachments) && count($assignment->attachments) > 0)
                    <div class="mb-10 p-5 sm:p-6 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                        <x-lms.attachment-list :attachments="$assignment->attachments" variant="cards" :title="__('Tài liệu & Tệp đính kèm từ Giáo viên')" />
                    </div>
                @endif

                <div class="p-6 lg:p-8 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2 text-base lg:text-lg">
                        <span class="material-symbols-outlined text-primary">cloud_upload</span>
                        {{ __('Nộp bài làm của bạn') }}
                    </h3>
                    
                    <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wider">{{ __('Lựa chọn 1: Ghi âm giọng nói') }}</label>
                            <x-audio-recorder name="audio_file" />
                        </div>

                        <div class="relative flex items-center gap-3 py-1">
                            <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Hoặc đính kèm tệp') }}</span>
                            <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
                        </div>

                        <div>
                            <x-lms.file-uploader
                                name="attachments[]"
                                :maxFiles="5"
                                :maxSizeMB="20"
                                :label="__('Lựa chọn 2: Tệp đính kèm (PDF, DOCX, Ảnh, Audio, Zip...)')" />
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="px-8 py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer flex items-center gap-2 btn-tactile">
                                <span class="material-symbols-outlined text-lg">send</span>
                                <span>{{ __('Gửi bài làm ngay') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection
