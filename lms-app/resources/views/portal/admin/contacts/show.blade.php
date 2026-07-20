@extends('portal.layouts.dashboard')

@section('title', 'Chi tiết Liên hệ')

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
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Chi tiết tin nhắn</h1>
                    <p class="text-sm text-slate-500">Xem và cập nhật trạng thái liên hệ.</p>
                </div>
                <a href="{{ route('admin.contacts.index') }}"
                    class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors font-medium text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Quay lại
                </a>
            </div>

            <x-flash-message type="success" />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Nội dung liên hệ -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">Nội dung tin nhắn</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 mb-2">Chủ đề:</h3>
                                <div class="flex flex-wrap gap-2">
                                    @if($contact->topics && is_array($contact->topics))
                                        @foreach($contact->topics as $topic)
                                            <span class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-sm font-bold border border-primary/20">
                                                {{ collect(['tu-van' => 'Tư vấn khóa học', 'ho-tro' => 'Hỗ trợ kỹ thuật', 'thanh-toan' => 'Thanh toán', 'gop-y' => 'Góp ý', 'khac' => 'Khác'])->get($topic, $topic) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-slate-500 italic">Không có chủ đề</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-500 mb-2">Lời nhắn:</h3>
                                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ $contact->message }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin & Trạng thái -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">Thông tin người gửi</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-1">Họ và tên</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $contact->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-1">Email</p>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <a href="mailto:{{ $contact->email }}" class="text-primary hover:underline">{{ $contact->email }}</a>
                                </p>
                            </div>
                            @if($contact->phone)
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-1">Số điện thoại</p>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <a href="tel:{{ $contact->phone }}" class="text-primary hover:underline">{{ $contact->phone }}</a>
                                </p>
                            </div>
                            @endif
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-1">Thời gian gửi</p>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $contact->created_at->format('H:i d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">Cập nhật trạng thái</h2>
                        
                        <form action="{{ route('admin.contacts.updateStatus', $contact) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Trạng thái hiện tại</label>
                                    <select name="status" class="w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all cursor-pointer">
                                        <option value="pending" {{ $contact->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Đã phản hồi (Gửi mail/Gọi điện)</option>
                                        <option value="closed" {{ $contact->status === 'closed' ? 'selected' : '' }}>Đã đóng</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-lg shadow-md hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">save</span> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
