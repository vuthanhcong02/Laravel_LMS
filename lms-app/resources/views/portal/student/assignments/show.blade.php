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
            
            {{-- Breadcrumb --}}
            <nav class="flex items-center text-sm text-slate-500 font-medium mb-4">
                <a href="{{ route('student.courses.show', $assignment->course_id) }}" class="hover:text-primary transition-colors">Khóa học</a>
                <span class="material-symbols-outlined text-[18px] mx-1">chevron_right</span>
                <span class="text-slate-800 dark:text-white truncate">{{ $assignment->title }}</span>
            </nav>

            {{-- Assignment Info --}}
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

                <div class="prose dark:prose-invert max-w-none mb-10 text-slate-600 dark:text-slate-300">
                    {!! nl2br(e($assignment->description)) !!}
                </div>

                <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">upload_file</span>
                        Nộp bài làm của bạn
                    </h3>
                    
                    <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tệp đính kèm (PDF, DOCX, ZIP...)</label>
                                <input type="file" name="attachments[]" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900">
                            </div>
                            
                            <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:scale-105 transition-all">
                                Nộp bài ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection
