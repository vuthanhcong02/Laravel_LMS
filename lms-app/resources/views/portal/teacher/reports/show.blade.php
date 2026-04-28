@extends('portal.layouts.dashboard')

@section('title', __('Student Report Detail'))

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
    <div class="max-w-[1400px] mx-auto space-y-8">
        
        <!-- Breadcrumb & Auto Navigation -->
        <div class="flex items-center gap-2 text-sm font-bold text-slate-500 mb-2">
            <a href="{{ route('teacher.reports.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                {{ __('Quay lại danh sách') }}
            </a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-slate-800 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div class="flex items-center gap-5">
                @if($student->avatar)
                    @php 
                        $avatarUrl = str_starts_with($student->avatar, 'http') ? $student->avatar : asset('storage/' . $student->avatar); 
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="{{ $student->first_name }}" class="size-20 rounded-3xl object-cover ring-4 ring-primary/10 shadow-lg shadow-primary/10">
                @else
                    <div class="size-20 rounded-3xl bg-gradient-to-br from-primary to-blue-600 text-white flex items-center justify-center font-black text-3xl shadow-lg shadow-primary/30">
                        {{ substr($student->first_name, 0, 1) }}
                    </div>
                @endif
                <div class="space-y-1">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm font-bold text-slate-500">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">mail</span> {{ $student->email }}</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">calendar_today</span> {{ __('Tham gia:') }} {{ $student->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if(!empty($histories))
                    <a href="{{ route('teacher.reports.export-pdf', $student->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black flex items-center gap-2 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                        {{ __('Xuất báo cáo PDF') }}
                    </a>
                @else
                    <button disabled class="px-5 py-2.5 bg-slate-50 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500 rounded-xl font-black flex items-center gap-2 shadow-sm cursor-not-allowed" title="{{ __('Chưa có dữ liệu để xuất') }}">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                        {{ __('Xuất báo cáo PDF') }}
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Stats -->
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 flex flex-col items-center justify-center text-center shadow-sm relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 dark:from-indigo-900/20 transition-opacity"></div>
                <div class="size-16 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 rounded-[1.5rem] flex items-center justify-center mb-4 relative z-10">
                    <span class="material-symbols-outlined text-4xl">class</span>
                </div>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white relative z-10">{{ $stats['total_courses'] }}</h3>
                <p class="text-sm font-bold text-slate-500 mt-2 relative z-10">{{ __('Khóa học đang học') }}</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 flex flex-col items-center justify-center text-center shadow-sm relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-b from-emerald-50/50 to-transparent opacity-0 group-hover:opacity-100 dark:from-emerald-900/20 transition-opacity"></div>
                <div class="size-16 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 rounded-[1.5rem] flex items-center justify-center mb-4 relative z-10">
                    <span class="material-symbols-outlined text-4xl">assignment_turned_in</span>
                </div>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white relative z-10">{{ $stats['avg_assignments'] }}</h3>
                <p class="text-sm font-bold text-slate-500 mt-2 relative z-10">{{ __('Điểm trung bình bài tập') }}</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 flex flex-col items-center justify-center text-center shadow-sm relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-b from-orange-50/50 to-transparent opacity-0 group-hover:opacity-100 dark:from-orange-900/20 transition-opacity"></div>
                <div class="size-16 bg-orange-50 dark:bg-orange-900/30 text-orange-500 rounded-[1.5rem] flex items-center justify-center mb-4 relative z-10">
                    <span class="material-symbols-outlined text-4xl">quiz</span>
                </div>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white relative z-10">{{ $stats['avg_quizzes'] }}</h3>
                <p class="text-sm font-bold text-slate-500 mt-2 relative z-10">{{ __('Điểm trung bình bài thi') }}</p>
            </div>
            
            <!-- Details placeholder to illustrate complete theme -->
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm lg:col-span-3">
                <div class="flex items-center justify-between mb-8 border-b border-slate-100 dark:border-slate-800 pb-6">
                    <h2 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">history</span>
                        {{ __('Lịch sử đánh giá') }}
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-widest font-black">
                                <th class="p-5 border-b border-slate-100 dark:border-slate-800">{{ __('Loại') }}</th>
                                <th class="p-5 border-b border-slate-100 dark:border-slate-800">{{ __('Bài đánh giá') }}</th>
                                <th class="p-5 border-b border-slate-100 dark:border-slate-800">{{ __('Ngày nộp') }}</th>
                                <th class="p-5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Trạng thái') }}</th>
                                <th class="p-5 border-b border-slate-100 dark:border-slate-800 text-right">{{ __('Điểm') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-sm">
                            @foreach($histories as $history)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0 group">
                                <td class="p-5">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-{{ $history['color'] }}-50 dark:bg-{{ $history['color'] }}-900/20 text-{{ $history['color'] }}-600 dark:text-{{ $history['color'] }}-400 text-xs font-black">
                                        <span class="material-symbols-outlined text-[16px]">{{ $history['icon'] }}</span>
                                        {{ $history['type'] }}
                                    </div>
                                </td>
                                <td class="p-5">
                                    <p class="font-black text-slate-900 dark:text-white text-base group-hover:text-primary transition-colors">
                                        {{ $history['title'] }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-bold mt-0.5">{{ $history['course'] }}</p>
                                </td>
                                <td class="p-5 text-sm font-bold text-slate-600 dark:text-slate-400">
                                    {{ $history['date'] }}
                                </td>
                                <td class="p-5 text-center">
                                    @if($history['status'] === 'Đã chấm')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-xs font-black border border-emerald-100 dark:border-emerald-800">
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $history['status'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-xs font-black border border-amber-100 dark:border-amber-800">
                                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            {{ $history['status'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-5 text-right">
                                    @if($history['score'])
                                        <span class="text-lg font-black {{ $history['score'] >= 8 ? 'text-emerald-600 dark:text-emerald-400' : ($history['score'] >= 5 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400') }}">
                                            {{ $history['score'] }}/10
                                        </span>
                                    @else
                                        <span class="text-sm font-bold text-slate-400">--/10</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>

    </div>
</main>
@endsection
