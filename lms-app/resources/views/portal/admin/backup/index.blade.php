@extends('portal.layouts.dashboard')

@section('title', 'Database Backup - XiaoMu Admin')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1100px] mx-auto space-y-8">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Database Backup</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Quản lý và lên lịch backup database tự động
                    </p>
                </div>
                <form action="{{ route('admin.backup.run-now') }}" method="POST" id="form-run-now">
                    @csrf
                    <button type="submit"
                        id="btn-run-now"
                        onclick="return confirmRunNow()"
                        class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 active:scale-95 transition-all shadow-md shadow-primary/30">
                        <span class="material-symbols-outlined text-[18px]">backup</span>
                        Backup Ngay (Queue)
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="flex items-center gap-3 px-5 py-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 px-5 py-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                <div class="xl:col-span-1">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">Lịch Backup Tự Động</h2>
                                <p class="text-xs text-slate-500">Cấu hình thời gian chạy</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.backup.settings') }}" method="POST" class="space-y-5" id="form-settings">
                            @csrf

                            <div class="flex items-center justify-between p-4 rounded-lg bg-slate-50 dark:bg-slate-800">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">Backup tự động</p>
                                    <p class="text-xs text-slate-500">Bật để chạy theo lịch cài đặt</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="enabled" value="0">
                                    <input type="checkbox" name="enabled" value="1" class="sr-only peer"
                                        {{ $settings->enabled ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                                    Tần suất
                                </label>
                                <div class="grid grid-cols-3 gap-2" x-data="{ freq: '{{ $settings->frequency }}' }">
                                    @foreach(['hourly' => 'Mỗi giờ', 'daily' => 'Mỗi ngày', 'weekly' => 'Mỗi tuần'] as $val => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="frequency" value="{{ $val }}" class="sr-only peer"
                                                x-model="freq"
                                                {{ $settings->frequency === $val ? 'checked' : '' }}>
                                            <div class="text-center py-2 px-1 rounded-lg border-2 text-xs font-semibold transition-all
                                                peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary
                                                border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400
                                                hover:border-primary/50">
                                                {{ $label }}
                                            </div>
                                        </label>
                                    @endforeach

                                    <div class="col-span-3 space-y-3" x-show="freq !== 'hourly'" x-transition>
                                        <div>
                                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                                                Giờ chạy
                                            </label>
                                            <input type="time" name="run_at"
                                                value="{{ substr($settings->run_at, 0, 5) }}"
                                                class="mt-1 w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition">
                                        </div>

                                        <div x-show="freq === 'weekly'" x-transition>
                                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                                                Ngày trong tuần
                                            </label>
                                            <select name="day_of_week"
                                                class="mt-1 w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition">
                                                @foreach(['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'] as $i => $day)
                                                    <option value="{{ $i }}" {{ $settings->day_of_week == $i ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                                    Giữ tối đa
                                </label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="max_backups"
                                        value="{{ $settings->max_backups }}"
                                        min="1" max="30"
                                        class="w-24 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-3 py-2 text-sm text-center font-bold focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition">
                                    <span class="text-sm text-slate-500">bản backup gần nhất</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 active:scale-95 transition-all">
                                Lưu cài đặt
                            </button>
                        </form>

                        <div class="mt-5 pt-5 border-t border-slate-200 dark:border-slate-800 space-y-2">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Trạng thái hiện tại</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-block size-2 rounded-full {{ $settings->enabled ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $settings->enabled ? 'Đang hoạt động' : 'Đã tắt' }}
                                </span>
                            </div>
                            @if($settings->enabled)
                                <p class="text-xs text-slate-500">
                                    {{ $settings->frequency_label }}
                                    @if($settings->frequency !== 'hourly')
                                        lúc {{ substr($settings->run_at, 0, 5) }}
                                    @endif
                                    @if($settings->frequency === 'weekly')
                                        ({{ $settings->day_of_week_label }})
                                    @endif
                                    — giữ {{ $settings->max_backups }} bản
                                </p>
                            @endif
                            @if($settings->last_run_at)
                                <p class="text-xs text-slate-400 pt-1">
                                    Backup tự động gần nhất: {{ $settings->last_run_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-2 space-y-6">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-sm text-center">
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ count($allFiles) }}</p>
                            <p class="text-xs text-slate-500 mt-1">File backup</p>
                        </div>
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-sm text-center">
                            @php
                                $totalSize = collect($allFiles)->sum('size');
                                $totalSizeHuman = $totalSize >= 1048576
                                    ? round($totalSize / 1048576, 1) . ' MB'
                                    : ($totalSize >= 1024 ? round($totalSize / 1024, 1) . ' KB' : $totalSize . ' B');
                            @endphp
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalSizeHuman ?: '0 B' }}</p>
                            <p class="text-xs text-slate-500 mt-1">Dung lượng</p>
                        </div>
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-sm text-center">
                            @php
                                $lastFile = $allFiles[0] ?? null;
                            @endphp
                            <p class="text-2xl font-bold {{ $lastFile ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $lastFile ? \Carbon\Carbon::parse($lastFile['created_at'])->diffForHumans() : 'Chưa có' }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">Backup cuối</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Danh sách Backup</h2>
                            <span class="text-xs text-slate-500">Tổng {{ $backupFiles->total() }} file</span>
                        </div>

                        @if($backupFiles->isEmpty())
                            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                                <span class="material-symbols-outlined !text-5xl mb-3">cloud_off</span>
                                <p class="text-sm font-medium">Chưa có file backup nào</p>
                                <p class="text-xs mt-1">Nhấn "Backup Ngay" để tạo bản backup đầu tiên</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($backupFiles as $file)
                                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <div class="size-10 shrink-0 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-blue-600 text-[18px]">folder_zip</span>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                                                {{ $file['filename'] }}
                                            </p>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                {{ $file['size_human'] }} &bull; {{ $file['created_at'] }}
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <a href="{{ route('admin.backup.download', $file['filename']) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold hover:border-primary hover:text-primary transition-colors">
                                                <span class="material-symbols-outlined text-[14px]">download</span>
                                                Tải xuống
                                            </a>

                                            <form action="{{ route('admin.backup.destroy', $file['filename']) }}" method="POST"
                                                onsubmit="return confirm('Xóa file backup này không thể hoàn tác. Tiếp tục?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-rose-900 text-rose-500 text-xs font-semibold hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($backupFiles->hasPages())
                                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                                    {{ $backupFiles->links() }}
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
    function confirmRunNow() {
        return confirm('Gửi yêu cầu backup database vào hàng chờ xử lý (Queue)?');
    }
</script>
@endpush
