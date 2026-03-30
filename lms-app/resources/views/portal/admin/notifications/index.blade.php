@extends('portal.layouts.dashboard')

@section('title', 'Thông báo')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
        <div class="max-w-[1200px] mx-auto space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Tất cả thông báo</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Quản lý và theo dõi các thông báo hệ thống.
                    </p>
                </div>

                <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-primary hover:bg-primary/90 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">done_all</span>
                        Đánh dấu tất cả đã đọc
                    </button>
                </form>
            </div>

            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="flex flex-col">
                    @forelse($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = $notification->unread();
                            $iconColor = $data['icon_color'] ?? 'primary';
                            $iconBgClass = match ($iconColor) {
                                'emerald', 'emerald-500' => 'bg-emerald-500/10 text-emerald-500',
                                'amber', 'amber-500' => 'bg-amber-500/10 text-amber-500',
                                'red', 'red-500' => 'bg-red-500/10 text-red-500',
                                'blue', 'blue-500' => 'bg-blue-500/10 text-blue-500',
                                default => 'bg-primary/10 text-primary',
                            };
                        @endphp
                        <div
                            class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800 last:border-0 {{ $isUnread ? 'bg-blue-50/50 dark:bg-slate-800/80' : '' }}">
                            <div class="size-12 rounded-full {{ $iconBgClass }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">{{ $data['icon'] ?? 'campaign' }}</span>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col sm:flex-row justify-between gap-4">
                                <div>
                                    <h3
                                        class="text-base font-semibold {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ $data['title'] ?? 'Tiêu đề thông báo' }}
                                        @if ($isUnread)
                                            <span
                                                class="inline-flex items-center rounded-md bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/10 ml-2">Mới</span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 line-clamp-2">
                                        {{ $data['message'] ?? 'Nội dung chi tiết thông báo' }}</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <p class="text-xs text-slate-500 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                        @if (isset($data['link']) && $data['link'] !== '#')
                                            <a href="{{ $data['link'] }}"
                                                class="text-xs text-primary hover:underline font-medium">Xem chi tiết</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 flex flex-col items-center justify-center text-center">
                            <div
                                class="size-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                <span class="material-symbols-outlined text-3xl">notifications_off</span>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Không có thông báo</h3>
                            <p class="text-sm text-slate-500 max-w-sm mt-1">Hiện tại bạn chưa có thông báo mới nào từ hệ
                                thống.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </main>
@endsection
