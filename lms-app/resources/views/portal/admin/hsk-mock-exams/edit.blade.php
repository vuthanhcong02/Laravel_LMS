@extends('portal.layouts.dashboard')

@section('title', 'Chỉnh sửa Đề thi HSK - XiaoMu Admin')

@section('header')
    @if(request()->is('teacher*') || request()->is('portal/teacher*'))
        @include('portal.teacher.layouts.header')
    @else
        @include('portal.admin.layouts.header')
    @endif
@endsection

@section('sidebar')
    @if(request()->is('teacher*') || request()->is('portal/teacher*'))
        @include('portal.teacher.layouts.sidebar')
    @else
        @include('portal.admin.layouts.sidebar')
    @endif
@endsection

@section('content')
    <style>
        body, html { overflow: hidden !important; }
        /* Prevent dashboard layout from scrolling */
        .flex-1.flex.flex-col { min-height: 0; }
    </style>
    <main class="flex-1 flex flex-col h-[calc(100vh-4.5rem)] max-h-[calc(100vh-4.5rem)] min-h-0 overflow-hidden">
        <livewire:exam-builder.main-manager :exam="$hskMockExam" />
    </main>
@endsection
