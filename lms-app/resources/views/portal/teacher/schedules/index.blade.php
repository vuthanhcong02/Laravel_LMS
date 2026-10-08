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
<main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
    <div class="max-w-[1400px] mx-auto space-y-6">

        <x-portal.page-header
            :title="__('Thời khóa biểu')"
            :description="__('Xem và theo dõi lịch giảng dạy các lớp học của bạn.')"
        />

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div id="calendar" data-events-url="{{ route('teacher.schedules.index') }}"></div>
        </div>

    </div>
</main>
@endsection
