@extends('portal.layouts.dashboard')

@section('title', 'Quản lý Liên hệ')

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
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Danh sách Liên hệ</h1>
                    <p class="text-sm text-slate-500">Xem và quản lý các tin nhắn từ người dùng ngoài trang chủ.</p>
                </div>

                <!-- Filters -->
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.contacts.index') }}" method="GET" class="flex gap-2">
                        <select name="status" onchange="this.form.submit()"
                            class="w-[180px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm px-3 py-3 text-slate-600 outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Đã đóng</option>
                        </select>
                    </form>
                </div>
            </div>

            <x-flash-message type="success" />

            <!-- Datatable -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Người gửi</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Chủ đề</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày gửi</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($contacts as $contact)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        #{{ str_pad($contact->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $contact->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $contact->email }}</div>
                                        @if($contact->phone)
                                            <div class="text-xs text-slate-500 mt-0.5">{{ $contact->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @if($contact->topics && is_array($contact->topics))
                                                @foreach($contact->topics as $topic)
                                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded text-xs font-medium border border-slate-200 dark:border-slate-700">
                                                        {{ collect(['tu-van' => 'Tư vấn khóa học', 'ho-tro' => 'Hỗ trợ kỹ thuật', 'thanh-toan' => 'Thanh toán', 'gop-y' => 'Góp ý', 'khac' => 'Khác'])->get($topic, $topic) }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-slate-400 italic text-xs">Không có</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($contact->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-600 mr-1.5"></span> Chờ xử lý
                                            </span>
                                        @elseif($contact->status === 'replied')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5"></span> Đã phản hồi
                                            </span>
                                        @elseif($contact->status === 'closed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-1.5"></span> Đã đóng
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $contact->created_at->format('H:i d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.contacts.show', $contact) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Không có tin nhắn liên hệ nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($contacts->hasPages())
                <div class="mt-6">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
