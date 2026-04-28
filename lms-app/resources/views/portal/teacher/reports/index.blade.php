@extends('portal.layouts.dashboard')

@section('title', __('Báo cáo'))

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
    <div class="max-w-[1400px] mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
            <div class="space-y-2">
                <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-4xl">bar_chart</span>
                    {{ __('Student Reports') }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 font-bold">{{ __('Theo dõi và đánh giá hiệu suất học tập của học sinh.') }}</p>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm p-6 overflow-visible">
            <form action="{{ route('teacher.reports.index') }}" method="GET" class="flex flex-col lg:flex-row gap-5 items-end">
                <div class="flex-1 w-full space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Tìm kiếm học sinh') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Tên, email...') }}" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400">
                    </div>
                </div>

                <div class="flex-1 w-full space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Lọc theo khóa học') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">class</span>
                        <select name="course_id" class="w-full pl-12 pr-10 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all cursor-pointer">
                            <option value="">{{ __('Tất cả khóa học') }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 w-full lg:w-auto h-[46px]">
                    <a href="{{ route('teacher.reports.index') }}" class="px-5 w-full lg:w-auto bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black transition-all text-sm flex items-center justify-center">{{ __('Xóa lọc') }}</a>
                    <button type="submit" class="px-5 w-full lg:w-auto bg-primary hover:bg-blue-600 text-white rounded-xl font-black transition-all text-sm flex items-center justify-center gap-2 shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        {{ __('Lọc') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Student List -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-widest font-black">
                            <th class="p-6 border-b border-slate-100 dark:border-slate-800 min-w-[250px]">{{ __('Học sinh') }}</th>
                            <th class="p-6 border-b border-slate-100 dark:border-slate-800">{{ __('Nơi truy cập cập nhật') }}</th>
                            <th class="p-6 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Thao tác') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-sm">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0 group">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        @if($student->avatar)
                                            @php 
                                                $avatarUrl = str_starts_with($student->avatar, 'http') ? $student->avatar : asset('storage/' . $student->avatar); 
                                            @endphp
                                            <img src="{{ $avatarUrl }}" alt="{{ $student->first_name }}" class="size-12 rounded-2xl object-cover ring-2 ring-transparent group-hover:ring-primary/20 transition-all shadow-sm">
                                        @else
                                            <div class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-lg ring-2 ring-transparent group-hover:ring-primary/20 transition-all shadow-sm">
                                                {{ substr($student->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-black text-slate-900 dark:text-white text-base">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </p>
                                            <p class="text-xs text-slate-500 font-bold mt-0.5">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-bold">{{ $student->created_at->format('d/m/Y') }}</span>
                                        <span class="text-xs text-slate-500">{{ __('Tham gia từ') }}</span>
                                    </div>
                                </td>
                                <td class="p-6 text-center">
                                    <a href="{{ route('teacher.reports.show', $student->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-primary hover:text-white dark:bg-slate-800 dark:hover:bg-primary/90 text-slate-600 dark:text-slate-300 transition-colors rounded-xl font-black text-xs gap-2">
                                        {{ __('Xem tiến độ') }}
                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-16 text-center text-slate-500">
                                    <div class="size-20 bg-slate-50 dark:bg-slate-800/50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-800">
                                        <span class="material-symbols-outlined text-4xl text-slate-400">person_off</span>
                                    </div>
                                    <p class="font-black text-slate-700 dark:text-slate-300 text-lg">{{ __('Không tìm thấy học sinh nào.') }}</p>
                                    <p class="text-sm mt-1">{{ __('Có thể bạn chưa có học sinh nào trong lớp hoặc tìm từ khóa chưa đúng.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($students->hasPages())
            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                {{ $students->appends(request()->query())->links('components.pagination') }}
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
