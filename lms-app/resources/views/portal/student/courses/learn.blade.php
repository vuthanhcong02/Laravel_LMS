@extends('portal.layouts.blank')

@section('title', 'Đang học: ' . $course->title)

@section('content')
<div class="h-screen flex flex-col bg-slate-900 text-slate-300">
    {{-- Top Navbar --}}
    <header class="h-16 bg-slate-950 flex items-center justify-between px-6 shrink-0 border-b border-slate-800">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.courses.show', $course->id) }}" class="text-slate-400 hover:text-white transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="text-sm font-medium hidden md:inline">Trở về khóa học</span>
            </a>
            <div class="w-px h-6 bg-slate-800 mx-2"></div>
            <h1 class="font-bold text-white line-clamp-1">{{ $course->title }}</h1>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-sm font-medium">
                <span class="text-slate-400">Tiến độ:</span>
                <span class="text-white">0%</span>
            </div>
            {{-- Dark mode toggle or user menu could go here --}}
        </div>
    </header>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">
        
        {{-- Video Player Area (Left/Main) --}}
        <main class="flex-1 flex flex-col bg-black overflow-y-auto relative">
            @if($currentLesson)
                {{-- Mock Video Player --}}
                <div class="w-full aspect-video bg-slate-900 relative flex items-center justify-center group border-b border-slate-800">
                    <img src="https://images.unsplash.com/photo-1610484826967-09c5720778c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Video thumbnail" class="absolute inset-0 w-full h-full object-cover opacity-50">
                    
                    <button class="z-10 size-20 rounded-full bg-primary/90 text-white flex items-center justify-center shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform backdrop-blur-md">
                        <span class="material-symbols-outlined text-4xl ml-1">play_arrow</span>
                    </button>
                    
                    {{-- Mock Video Controls --}}
                    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-black/80 to-transparent flex items-end px-4 pb-3 gap-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-white cursor-pointer hover:text-primary">play_arrow</span>
                        <div class="h-1 flex-1 bg-white/30 rounded-full cursor-pointer relative">
                            <div class="absolute inset-y-0 left-0 w-1/3 bg-primary rounded-full"></div>
                        </div>
                        <span class="text-xs text-white/90">05:24 / 15:00</span>
                        <span class="material-symbols-outlined text-white cursor-pointer hover:text-primary">volume_up</span>
                        <span class="material-symbols-outlined text-white cursor-pointer hover:text-primary">fullscreen</span>
                    </div>
                </div>

                {{-- Lesson Details & Multiple Videos Switcher --}}
                <div class="p-6 lg:p-10 max-w-5xl mx-auto w-full">
                    <h2 class="text-2xl font-bold text-white mb-2">{{ $currentLesson->title }}</h2>
                    
                    {{-- Demo: Multiple videos in one lesson --}}
                    <div class="mb-6">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Các phần của bài học</p>
                        <div class="flex flex-wrap gap-2">
                            <button class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-bold flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">play_circle</span>
                                Phần 1: Lý thuyết
                            </button>
                            <button class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors text-sm font-medium flex items-center gap-2 border border-slate-700">
                                <span class="material-symbols-outlined text-[18px]">smart_display</span>
                                Phần 2: Thực hành (08:45)
                            </button>
                            <button class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors text-sm font-medium flex items-center gap-2 border border-slate-700">
                                <span class="material-symbols-outlined text-[18px]">smart_display</span>
                                Phần 3: Chữa bài tập (12:30)
                            </button>
                        </div>
                    </div>

                    <div class="w-full h-px bg-slate-800 my-6"></div>

                    <div class="prose prose-invert max-w-none text-slate-300">
                        <p>{{ $currentLesson->description ?? 'Không có mô tả cho bài học này.' }}</p>
                        {{-- Mock Content --}}
                        <p class="mt-4">Nội dung bài học sẽ được hiển thị ở đây. Bạn có thể xem video và tham khảo các tài liệu đính kèm bên dưới.</p>
                        
                        <div class="mt-8 flex gap-4">
                            <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg flex items-center gap-2 transition-colors border border-slate-700">
                                <span class="material-symbols-outlined text-[18px]">download</span>
                                Tài liệu đính kèm (PDF)
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
                    <span class="material-symbols-outlined text-6xl text-slate-700 mb-4">video_library</span>
                    <h2 class="text-xl font-bold text-white mb-2">Chưa có bài học nào</h2>
                    <p class="text-slate-400">Khóa học này hiện chưa có nội dung bài học.</p>
                </div>
            @endif
        </main>

        {{-- Curriculum Sidebar (Right) --}}
        <aside class="w-full lg:w-[400px] shrink-0 bg-slate-900 border-l border-slate-800 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-800 shrink-0 bg-slate-950/50">
                <h3 class="font-bold text-white">Nội dung khóa học</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $course->lessons->count() }} bài học</p>
            </div>
            
            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="flex flex-col">
                    @forelse($course->lessons->sortBy('order') as $index => $lesson)
                        @php
                            $isActive = $currentLesson && $currentLesson->id === $lesson->id;
                        @endphp
                        <a href="{{ route('student.courses.learn', ['course' => $course->id, 'lesson' => $lesson->id]) }}" 
                           class="flex gap-4 p-4 border-b border-slate-800/50 hover:bg-slate-800/50 transition-colors {{ $isActive ? 'bg-slate-800/80 border-l-4 border-l-primary' : 'border-l-4 border-l-transparent' }}">
                            
                            <div class="shrink-0 flex flex-col items-center gap-1 mt-1">
                                @if($isActive)
                                    <span class="material-symbols-outlined text-primary">play_circle</span>
                                @else
                                    <span class="material-symbols-outlined text-slate-500 text-[20px]">check_circle</span>
                                @endif
                                <span class="text-[10px] text-slate-500">{{ sprintf('%02d', $index + 1) }}</span>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium {{ $isActive ? 'text-white' : 'text-slate-300' }} line-clamp-2 leading-snug">
                                    {{ $lesson->title }}
                                </h4>
                                <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">smart_display</span>
                                        15:00
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-slate-500">
                            Trống
                        </div>
                    @endforelse
                </div>
            </div>
        </aside>

    </div>
</div>
@endsection
