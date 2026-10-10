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

        <x-portal.page-header
            :title="__('Báo cáo học tập')"
            :description="__('Theo dõi và đánh giá hiệu suất học tập của học sinh.')"
        />

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 overflow-visible">
            <form action="{{ route('teacher.reports.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Tìm kiếm theo tên, email học sinh...') }}"
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400">
                </div>

                <div class="relative sm:w-60">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">class</span>
                    <select name="course_id" class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all cursor-pointer">
                        <option value="">{{ __('Tất cả khóa học') }}</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($search || $courseId)
                    <x-portal.filter-reset :url="route('teacher.reports.index')" />
                @endif
                <x-portal.filter-button />
            </form>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 text-slate-400 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 min-w-[220px]">{{ __('Học sinh') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800">{{ __('Thời gian tham gia') }}</th>
                            <th class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-800 text-center">{{ __('Thao tác') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 dark:text-slate-300 antialiased font-medium text-xs sm:text-sm">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0 group">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($student->avatar)
                                            @php
                                                $avatarUrl = str_starts_with($student->avatar, 'http') ? $student->avatar : asset('storage/' . $student->avatar);
                                            @endphp
                                            <img src="{{ $avatarUrl }}" alt="{{ $student->first_name }}" class="size-9 rounded-xl object-cover ring-2 ring-transparent group-hover:ring-primary/20 transition-all shadow-xs">
                                        @else
                                            <div class="size-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-xs ring-2 ring-transparent group-hover:ring-primary/20 transition-all shadow-xs">
                                                {{ substr($student->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal mt-0.5">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ $student->created_at->format('d/m/Y') }}</span>
                                        <span class="text-[10px] text-slate-400">{{ __('Tham gia từ') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <a href="{{ route('teacher.reports.show', $student->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-slate-100 hover:bg-primary hover:text-white dark:bg-slate-800 dark:hover:bg-primary text-slate-700 dark:text-slate-300 transition-colors rounded-xl font-semibold text-xs gap-1 shadow-xs">
                                        {{ __('Xem tiến độ') }}
                                        <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-16 text-center text-slate-500">
                                    <div class="size-16 bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100 dark:border-slate-800">
                                        <span class="material-symbols-outlined text-3xl text-slate-400">person_off</span>
                                    </div>
                                    <p class="font-bold text-slate-800 dark:text-slate-200 text-base">{{ __('Không tìm thấy học sinh nào.') }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('Có thể bạn chưa có học sinh nào trong lớp hoặc tìm từ khóa chưa đúng.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                {{ $students->appends(request()->query())->links('components.pagination') }}
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
