@extends('portal.layouts.dashboard')

@section('title', 'System Monitoring - XiaoMu Admin')

@push('styles')
    @livewireStyles
    <style>
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }
    </style>
@endpush

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1400px] mx-auto space-y-6">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Real-time System Pulse</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Theo dõi lượng người truy cập, hiệu năng máy chủ và tác vụ hệ thống theo thời gian thực
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <livewire:pulse.period-selector />
                    <a href="{{ url('/pulse') }}" target="_blank"
                        class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg hover:border-primary hover:text-primary transition shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        Mở Full Dashboard
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Server Load, CPU, RAM, Storage --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <livewire:pulse.servers />
                </div>

                {{-- Application Usage / Top Users --}}
                <div class="col-span-1">
                    <livewire:pulse.usage />
                </div>

                {{-- Slow HTTP Requests --}}
                <div class="col-span-1">
                    <livewire:pulse.slow-requests />
                </div>

                {{-- System Exceptions --}}
                <div class="col-span-1">
                    <livewire:pulse.exceptions />
                </div>

                {{-- Queue Status --}}
                <div class="col-span-1">
                    <livewire:pulse.queues />
                </div>

                {{-- Cache Interactions --}}
                <div class="col-span-1">
                    <livewire:pulse.cache />
                </div>

                {{-- Slow Jobs --}}
                <div class="col-span-1">
                    <livewire:pulse.slow-jobs />
                </div>

                {{-- Slow Outgoing HTTP/API Requests --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <livewire:pulse.slow-outgoing-requests />
                </div>

                {{-- Slow Database Queries --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <livewire:pulse.slow-queries />
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {!! Pulse::js() !!}
    @livewireScripts
@endpush
