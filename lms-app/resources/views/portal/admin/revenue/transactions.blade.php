@extends('portal.layouts.dashboard')

@section('title', 'Lịch sử Giao dịch')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1200px] mx-auto space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.revenue.index') }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary transition-colors shadow-sm">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Lịch sử Giao dịch</h1>
                        <p class="text-slate-500 text-sm">Danh sách các giao dịch thanh toán trên hệ thống.</p>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg p-4 shadow-sm">
                <form action="{{ route('admin.revenue.transactions') }}" method="GET"
                    class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Tìm kiếm</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Tìm theo người dùng, khóa học hoặc mã GD..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        </div>
                    </div>
                    <div class="w-full md:w-48 space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Trạng thái</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            <option value="">Tất cả</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Thành công
                            </option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất bại</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-2.5 rounded-lg bg-primary text-white font-bold text-sm hover:bg-primary/90 transition-all shadow-sm shadow-primary/20">
                        Lọc dữ liệu
                    </button>
                    @if (request()->anyFilled(['search', 'status']))
                        <a href="{{ route('admin.revenue.transactions') }}"
                            class="w-full md:w-auto px-6 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-center">
                            Xóa lọc
                        </a>
                    @endif
                </form>
            </div>

            <!-- Transactions Table -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Giao dịch
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Học viên
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Khóa học
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Số tiền</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Phương thức
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày GD</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">
                                    Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs font-mono font-bold text-slate-400">#{{ $tx->transaction_id ?? str_pad($tx->id, 8, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="size-8 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                                @if ($tx->user->avatar_url)
                                                    <img src="{{ $tx->user->avatar_url }}" class="size-full object-cover">
                                                @else
                                                    <span
                                                        class="text-[10px] font-bold text-slate-500">{{ substr($tx->user->first_name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $tx->user->first_name }}
                                                    {{ $tx->user->last_name }}</span>
                                                <span class="text-[10px] text-slate-500">{{ $tx->user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate max-w-[200px] block">{{ $tx->course->title }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-bold text-slate-900 dark:text-white">₫{{ number_format($tx->amount) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div
                                            class="flex items-center gap-1.5 capitalize text-xs font-medium text-slate-600 dark:text-slate-400">
                                            <span class="material-symbols-outlined text-[16px]">
                                                {{ $tx->payment_method === 'bank' ? 'account_balance' : ($tx->payment_method === 'wallet' ? 'account_balance_wallet' : 'credit_card') }}
                                            </span>
                                            {{ $tx->payment_method }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $tx->created_at->format('H:i d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center">
                                            @if ($tx->status === 'completed')
                                                <span
                                                    class="px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider">Thành
                                                    công</span>
                                            @elseif($tx->status === 'pending')
                                                <span
                                                    class="px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider">Chờ
                                                    xử lý</span>
                                            @else
                                                <span
                                                    class="px-2.5 py-1 rounded-full bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[10px] font-bold uppercase tracking-wider">Thất
                                                    bại</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-4xl text-slate-300">payments</span>
                                            <p class="text-sm">Không tìm thấy giao dịch nào phù hợp.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
