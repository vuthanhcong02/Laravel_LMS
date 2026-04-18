@extends('portal.layouts.dashboard')

@section('title', 'Quản lý Hỗ trợ')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <div class="p-6">
        <div class="max-w-[1200px] mx-auto space-y-6">
            <div class="flex items-center justify-between mt-5">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Quản lý Yêu cầu hỗ trợ</h1>
                    <p class="text-sm text-slate-500">Xem và trả lời các yêu cầu hỗ trợ từ Học viên và Giáo viên.</p>
                </div>

                <!-- Filters -->
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.support.index') }}" method="GET" class="flex gap-2">
                        <select name="status" onchange="this.form.submit()"
                            class="w-[180px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm px-3 py-3 text-slate-600 outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="">Tất cả trạng thái</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Đang mở</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Đang xử
                                lý</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Đã giải quyết
                            </option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Đã đóng</option>
                        </select>
                    </form>
                </div>
            </div>

            <x-flash-message type="success" />

            <!-- Datatable -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Ticket</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Người gửi
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tiêu đề -
                                    Mức độ</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Cập nhật
                                    cuối</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                                    Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                                @if ($ticket->user->avatar)
                                                    <img src="{{ asset('storage/' . $ticket->user->avatar) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <span
                                                        class="text-xs font-bold text-slate-500">{{ substr($ticket->user->first_name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                                    {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $ticket->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-slate-900 dark:text-white mb-1">
                                            {{ $ticket->subject }}</div>
                                        @if ($ticket->priority === 'low')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">THẤP</span>
                                        @elseif($ticket->priority === 'normal')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600">BÌNH
                                                THƯỜNG</span>
                                        @elseif($ticket->priority === 'high')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-orange-50 text-orange-600">CAO</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-600">KHẨN
                                                CẤP</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($ticket->status === 'open')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-600 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Đang mở
                                            </span>
                                        @elseif($ticket->status === 'in_progress')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full border border-amber-200 bg-amber-50 text-amber-600 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Đang xử
                                                lý
                                            </span>
                                        @elseif($ticket->status === 'resolved')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span> Đã giải
                                                quyết
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full border border-slate-200 bg-slate-50 text-slate-600 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1.5"></span> Đã đóng
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $ticket->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.support.show', $ticket) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-500 hover:text-primary hover:bg-primary/10 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">chat</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-4xl text-slate-300">inbox</span>
                                            <p>Chưa có yêu cầu hỗ trợ nào.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($tickets->hasPages())
                    <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
