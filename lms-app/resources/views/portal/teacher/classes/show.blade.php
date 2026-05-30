@extends('portal.layouts.dashboard')

@section('title', $class->title)

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto" x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'overview', 
        showAnnouncementModal: false 
    }">
        <div class="max-w-[1400px] mx-auto space-y-8">
            {{-- Breadcrumb & Actions --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mt-5">
                <nav class="flex items-center gap-2 text-sm font-bold">
                    <a href="{{ route('teacher.classes.index') }}" class="text-slate-400 hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                        Danh sách lớp
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-800 dark:text-white truncate max-w-[200px]">{{ $class->title }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <button @click="$dispatch('notify', { msg: 'Đang chuyển sang chế độ chỉnh sửa lộ trình...', type: 'info' })" class="px-5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-lg">edit</span> Chỉnh sửa lộ trình
                    </button>
                    <button @click="showAnnouncementModal = true" class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-blue-600 transition-all flex items-center gap-2 shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined text-lg">mail</span> Gửi thông báo lớp
                    </button>
                </div>
            </div>

            {{-- Course Header --}}
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 md:p-10 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row gap-8 items-start md:items-center">
                    <div class="size-32 md:size-40 rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-slate-800 shrink-0">
                        @if($class->thumbnail)
                            <img src="{{ asset('storage/' . $class->thumbnail) }}" alt="{{ $class->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-5xl">school</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="px-4 py-1.5 bg-primary/10 text-primary text-[10px] font-black uppercase rounded-full">
                                {{ $class->category?->name ?? 'Course' }}
                            </span>
                            <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase rounded-full flex items-center gap-1.5">
                                <span class="size-1.5 bg-emerald-500 rounded-full"></span> Đang hoạt động
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white leading-tight">
                            {{ $class->title }}
                        </h1>
                        <div class="flex flex-wrap gap-6 text-slate-500 dark:text-slate-400 font-bold text-sm">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">group</span>
                                {{ $class->enrollments_count }} Học viên
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-orange-500">menu_book</span>
                                {{ $class->lessons->count() }} Bài học
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-500">calendar_today</span>
                                Tạo ngày {{ $class->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                {{-- Decor --}}
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl text-primary"></div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="flex items-center gap-8 border-b border-slate-200 dark:border-slate-800 px-4">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'text-primary border-b-4 border-primary' : 'text-slate-400 hover:text-slate-600'" class="pb-4 text-base font-black transition-all">
                    Tổng quan & Lộ trình
                </button>
                <button @click="activeTab = 'students'" :class="activeTab === 'students' ? 'text-primary border-b-4 border-primary' : 'text-slate-400 hover:text-slate-600'" class="pb-4 text-base font-black transition-all flex items-center gap-2">
                    Danh sách học viên
                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs">{{ $class->enrollments_count }}</span>
                </button>
            </div>

            {{-- Tab Content --}}
            <div class="space-y-8">
                {{-- Overview Tab --}}
                <div x-show="activeTab === 'overview'" x-transition class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                            <h3 class="text-slate-500 text-xs font-black uppercase tracking-widest mb-4">Tỉ lệ hoàn thành trung tâm</h3>
                            <div class="flex items-end gap-3">
                                <p class="text-4xl font-black text-slate-800 dark:text-white">65%</p>
                                <span class="text-emerald-500 text-sm font-bold mb-1.5 flex items-center tracking-tight">
                                    <span class="material-symbols-outlined text-sm">trending_up</span> +5% tuần này
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full mt-4 overflow-hidden">
                                <div class="bg-primary h-full rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                            <h3 class="text-slate-500 text-xs font-black uppercase tracking-widest mb-4">Bài học đã hoàn thành</h3>
                            <p class="text-4xl font-black text-slate-800 dark:text-white">120 <span class="text-lg font-bold text-slate-400">/ 200</span></p>
                            <p class="text-xs text-slate-400 font-bold mt-2 italic">* Tổng số bài học x Số học viên</p>
                        </div>
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                            <h3 class="text-slate-500 text-xs font-black uppercase tracking-widest mb-4">Mức độ tương tác</h3>
                            <p class="text-4xl font-black text-slate-800 dark:text-white">Cao</p>
                            <div class="flex gap-1 mt-4">
                                @for($i=0; $i<5; $i++)
                                    <div class="h-2 flex-1 rounded-full {{ $i < 4 ? 'bg-orange-500' : 'bg-slate-200 dark:bg-slate-800' }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
                            <h2 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                                Lộ trình bài học
                            </h2>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($class->lessons as $lesson)
                                <div class="p-6 hover:bg-slate-50 group transition-all flex items-center gap-6">
                                    <div class="size-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-black group-hover:bg-primary group-hover:text-white transition-all shadow-sm">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-primary transition-all">{{ $lesson->title }}</h4>
                                        <div class="flex items-center gap-4 mt-1 text-xs text-slate-400 font-bold">
                                            <span class="flex items-center gap-1">
                                            </span>
                                        </div>
                                    </div>
                                    <button @click="$dispatch('notify', { msg: 'Đang xem trước nội dung bài học...', type: 'info' })" class="size-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </div>
                            @empty
                                <div class="p-12 text-center text-slate-400 font-bold">Lớp học chưa có bài học nào.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Students Tab --}}
                <div x-show="activeTab === 'students'" x-transition class="space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="relative w-full md:w-96 group">
                            <form action="{{ url()->current() }}" method="GET" class="relative">
                                <input type="hidden" name="tab" value="students">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                                <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm tên, email học viên..." 
                                       class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-sm">
                            </form>
                        </div>
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest">
                            Hiển thị {{ $enrollments->firstItem() ?? 0 }}-{{ $enrollments->lastItem() ?? 0 }} trong tổng số {{ $enrollments->total() }} học viên
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 dark:bg-slate-800/50">
                                <tr>
                                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Học viên</th>
                                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Ngày tham gia</th>
                                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Tiến độ</th>
                                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($enrollments as $enrollment)
                                    <tr class="hover:bg-slate-50/80 transition-all">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $enrollment->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($enrollment->user->first_name) }}" class="size-12 rounded-2xl object-cover shadow-sm ring-1 ring-slate-100">
                                                <div>
                                                    <p class="font-black text-slate-800 dark:text-white">{{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}</p>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-tight">{{ $enrollment->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-sm text-slate-500 font-bold">
                                            {{ $enrollment->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="w-32">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-[10px] font-black text-primary">75%</span>
                                                </div>
                                                <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                                    <div class="bg-primary h-full" style="width: 75%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2 text-primary">
                                                <button @click="$dispatch('notify', { msg: 'Đang tải báo cáo học tập chi tiết...', type: 'info' })" class="p-2 hover:bg-primary/10 rounded-xl transition-all" title="Báo cáo">
                                                    <span class="material-symbols-outlined text-lg">assessment</span>
                                                </button>
                                                <button @click="$dispatch('notify', { msg: 'Đang mở cửa sổ chat với học viên...', type: 'info' })" class="p-2 hover:bg-primary/10 rounded-xl transition-all" title="Nhắn tin">
                                                    <span class="material-symbols-outlined text-lg">chat</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold italic">Chưa có học viên nào tham gia lớp này.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    @if($enrollments->hasPages())
                        <div class="p-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20">
                            {{ $enrollments->appends(['search' => $search, 'tab' => 'students'])->links('components.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Announcement Modal -->
        <div x-show="showAnnouncementModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div @click.away="showAnnouncementModal = false" 
                 class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-800">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        Gửi thông báo lớp
                    </h3>
                    <button @click="showAnnouncementModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Tiêu đề thông báo</label>
                        <input type="text" placeholder="Nhập tiêu đề..." class="w-full px-5 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-2 focus:ring-primary outline-none transition-all font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Nội dung chi tiết</label>
                        <textarea rows="4" placeholder="Nhập nội dung thông báo cho học viên..." class="w-full px-5 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-2 focus:ring-primary outline-none transition-all font-bold"></textarea>
                    </div>
                </div>
                <div class="p-8 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800 flex gap-3">
                    <button @click="showAnnouncementModal = false" class="flex-1 px-6 py-3 rounded-2xl font-black text-slate-500 hover:bg-slate-100 transition-all">Hủy</button>
                    <button @click="showAnnouncementModal = false; $dispatch('notify', { msg: 'Thông báo đã được gửi thành công!', type: 'success' })" 
                            class="flex-1 px-6 py-3 bg-primary text-white rounded-2xl font-black shadow-lg shadow-primary/20 hover:bg-blue-600 transition-all">Gửi ngay</button>
                </div>
            </div>
        </div>
    </main>
@endsection
