@extends('portal.layouts.dashboard')

@section('title', 'Trung tâm Hỗ trợ')

@section('header')
    @if (auth()->user()->role === \App\Models\User::ROLE_ADMIN)
        @include('portal.admin.layouts.header')
    @elseif(auth()->user()->role === \App\Models\User::ROLE_TEACHER)
        @include('portal.teacher.layouts.header')
    @else
        @include('portal.student.layouts.header')
    @endif
@endsection

@section('sidebar')
    @if (auth()->user()->role === \App\Models\User::ROLE_ADMIN)
        @include('portal.admin.layouts.sidebar')
    @elseif(auth()->user()->role === \App\Models\User::ROLE_TEACHER)
        @include('portal.teacher.layouts.sidebar')
    @else
        @include('portal.student.layouts.sidebar')
    @endif
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1400px] mx-auto space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">support_agent</span>
                        Trung tâm Hỗ trợ
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">Xem và quản lý các yêu cầu hỗ trợ của bạn.</p>
                </div>
                <a href="{{ route('support.create') }}"
                    class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Tạo yêu cầu mới
                </a>
            </div>

            <x-admin.flash-message type="success" />

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tiêu đề</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mức độ ưu tiên</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Cập nhật cuối</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $ticket->subject }}</div>
                                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">tag</span> #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($ticket->priority === 'low')
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold">Thấp</span>
                                        @elseif($ticket->priority === 'normal')
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold">Bình thường</span>
                                        @elseif($ticket->priority === 'high')
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 text-xs font-bold">Cao</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold">Khẩn cấp</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($ticket->status === 'open')
                                            <span class="inline-flex w-auto items-center px-2 py-1 rounded bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Đang mở
                                            </span>
                                        @elseif($ticket->status === 'in_progress')
                                            <span class="inline-flex w-auto items-center px-2 py-1 rounded bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span> Đang xử lý
                                            </span>
                                        @elseif($ticket->status === 'resolved')
                                            <span class="inline-flex w-auto items-center px-2 py-1 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span> Đã giải quyết
                                            </span>
                                        @else
                                            <span class="inline-flex w-auto items-center px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-2"></span> Đã đóng
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $ticket->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('support.show', $ticket) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-primary hover:bg-primary/10 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">inbox</span>
                                            <p>Bạn không có yêu cầu hỗ trợ nào.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($tickets->hasPages())
                    <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
