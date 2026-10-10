@extends('portal.layouts.dashboard')

@section('title', __('Lịch giảng dạy'))

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/teacher-schedules.css') }}">
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src="{{ asset('js/teacher-schedules.js') }}"></script>
@endpush

@section('content')
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full" x-data="teacherScheduleModal()">
    <div class="max-w-[1400px] mx-auto space-y-6">

        <x-portal.page-header
            :title="__('Thời khóa biểu')"
            :description="__('Xem và theo dõi lịch giảng dạy các lớp học của bạn.')"
        />

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div id="calendar" data-events-url="{{ route('teacher.schedules.index') }}"></div>
        </div>

    </div>

    {{-- Modal Chi tiết Lịch giảng dạy --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-cloak>
        <div @click.away="close()"
             class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col">
            
            {{-- Modal Header --}}
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/60 dark:bg-slate-800/40">
                <div class="flex items-center gap-3">
                    <span class="size-3.5 rounded-full ring-4 ring-opacity-20 shrink-0"
                          :style="'background-color: ' + event.color + '; box-shadow: 0 0 0 4px ' + event.color + '33'"></span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="event.title"></h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="event.category || '{{ __('Khóa học') }}'"></p>
                    </div>
                </div>
                <button type="button" @click="close()"
                        class="size-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors flex items-center justify-center cursor-pointer">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    {{-- Thời gian --}}
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 space-y-1">
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-base text-primary">schedule</span>
                            <span>{{ __('Khung giờ') }}</span>
                        </div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="event.timeRange || '{{ __('Cả ngày') }}'"></p>
                    </div>

                    {{-- Thứ trong tuần --}}
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 space-y-1">
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-base text-sky-500">calendar_today</span>
                            <span>{{ __('Lịch học') }}</span>
                        </div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="event.dayName || '{{ __('Hàng tuần') }}'"></p>
                    </div>

                    {{-- Sĩ số lớp --}}
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 space-y-1">
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-base text-emerald-500">groups</span>
                            <span>{{ __('Sĩ số học viên') }}</span>
                        </div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">
                            <span x-text="event.studentsCount"></span> {{ __('học viên') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-6 bg-slate-50/60 dark:bg-slate-800/40 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end">
                <button type="button" @click="close()"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    {{ __('Đóng') }}
                </button>
            </div>
        </div>
    </div>
</main>
@endsection
