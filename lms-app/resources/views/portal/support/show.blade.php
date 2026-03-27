@extends('portal.layouts.dashboard')

@section('title', 'Chi Tiết Yêu Cầu Hỗ Trợ')

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
    <div class="p-6 flex-1 flex flex-col h-[calc(100vh-4rem)]">
        <div class="max-w-[1000px] mx-auto w-full flex flex-col h-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900 z-10">
                <div class="flex items-center gap-4">
                    <a href="{{ route('support.index') }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 dark:text-white">{{ $ticket->subject }}</h1>
                        <div class="text-xs text-slate-500 flex items-center gap-3 mt-1">
                            <span>Ticket #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span>&bull;</span>
                            
                            @if($ticket->status === 'open')
                                <span class="text-emerald-500 font-medium">Đang mở</span>
                            @elseif($ticket->status === 'in_progress')
                                <span class="text-amber-500 font-medium">Đang xử lý</span>
                            @elseif($ticket->status === 'resolved')
                                <span class="text-blue-500 font-medium">Đã giải quyết</span>
                            @else
                                <span class="text-slate-500 font-medium">Đã đóng</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <x-admin.flash-message type="success" />
            <x-admin.flash-message type="error" />

            <!-- Chat Area -->
            <div class="flex-1 p-6 overflow-y-auto bg-slate-50/50 dark:bg-slate-900/50 space-y-6" id="chat-container">
                @foreach($ticket->messages as $msg)
                    @php
                        $isMe = $msg->user_id === auth()->id();
                        $isAdmin = $msg->user->role === \App\Models\User::ROLE_ADMIN;
                    @endphp

                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="flex {{ $isMe ? 'flex-row-reverse' : 'flex-row' }} items-end gap-3 max-w-[80%]">
                            <!-- Avatar -->
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                @if($msg->user->avatar_url)
                                    <img src="{{ $msg->user->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs font-bold text-slate-500">{{ substr($msg->user->first_name, 0, 1) }}</span>
                                @endif
                            </div>

                            <!-- Bubble -->
                            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                                <span class="text-xs text-slate-500 mb-1 px-1">
                                    {{ $isAdmin && !$isMe ? 'Hỗ trợ viên' : $msg->user->first_name . ' ' . $msg->user->last_name }} 
                                    &bull; {{ $msg->created_at->format('H:i d/m/Y') }}
                                </span>
                                <div class="flex items-end gap-2">
                                    <div class="flex items-center gap-1 text-[10px] text-emerald-500 font-bold mb-1 {{ $isMe ? 'order-1' : 'order-2' }}">
                                        <span class="material-symbols-outlined text-[14px]">done_all</span>
                                        Đã gửi
                                    </div>
                                    <div class="px-4 py-3 rounded-2xl {{ $isMe ? 'bg-primary text-white rounded-br-none order-2' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-bl-none order-1' }} shadow-sm">
                                        <p class="text-sm whitespace-pre-wrap leading-relaxed">{{ $msg->message }}</p>
                                        @if($msg->attachment_path)
                                            <div class="mt-3">
                                                <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded bg-black/10 hover:bg-black/20 dark:bg-white/10 dark:hover:bg-white/20 transition-colors text-sm font-medium">
                                                    <span class="material-symbols-outlined text-[18px]">attachment</span> Xem tệp đính kèm
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Box -->
            @if(in_array($ticket->status, ['open', 'in_progress', 'resolved']))
                <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <form action="{{ route('support.reply', $ticket) }}" method="POST" enctype="multipart/form-data" id="reply-form">
                        @csrf
                        <div class="flex items-end gap-3">
                            <label class="w-12 h-12 shrink-0 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-pointer text-slate-500 hover:text-primary transition-colors" title="Đính kèm tệp">
                                <span class="material-symbols-outlined text-[20px]">attach_file</span>
                                <input type="file" name="attachment" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                    onchange="document.getElementById('attach-name').textContent = this.files[0] ? this.files[0].name : ''; document.getElementById('attach-name').classList.remove('hidden')">
                            </label>
                            <div class="flex-1 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all p-2 relative flex flex-col">
                                <div id="attach-name" class="hidden text-xs text-primary font-medium px-2 pb-1 border-b border-slate-200 dark:border-slate-700/50 mb-1"></div>
                                <textarea name="message" rows="2" class="w-full bg-transparent border-none focus:ring-0 text-sm p-2 resize-none outline-none dark:text-white" placeholder="Nhập tin nhắn phản hồi..." required></textarea>
                            </div>
                            <button type="submit" class="w-12 h-12 shrink-0 flex items-center justify-center rounded-xl bg-primary text-white hover:bg-primary/90 transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-6 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 text-center">
                    <p class="text-sm text-slate-500">Yêu cầu hỗ trợ này đã được đóng lại. Bạn không thể gửi thêm phản hồi.</p>
                </div>
            @endif
        </div>
    </div>
    <script>
        // Scroll to bottom of chat automatically
        const chatContainer = document.getElementById('chat-container');
        const scrollToBottom = () => {
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        };
        
        scrollToBottom();

        // Handle AJAX Form Submission
        const replyForm = document.getElementById('reply-form');
        if (replyForm) {
            replyForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(replyForm);
                const submitBtn = replyForm.querySelector('button[type="submit"]');
                const textarea = replyForm.querySelector('textarea[name="message"]');
                const attachName = document.getElementById('attach-name');
                
                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[20px]">refresh</span>';
                
                try {
                    const response = await fetch(replyForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Append new message to chat
                        const msg = result.data;
                        const messageHtml = `
                            <div class="flex justify-end">
                                <div class="flex flex-row-reverse items-end gap-3 max-w-[80%]">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        ${msg.user.avatar ? `<img src="${msg.user.avatar}" alt="Avatar" class="w-full h-full object-cover">` : `<span class="text-xs font-bold text-slate-500">${msg.user.first_name.charAt(0)}</span>`}
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-xs text-slate-500 mb-1 px-1">
                                            Bạn &bull; ${msg.created_at}
                                        </span>
                                        <div class="flex items-end gap-2">
                                            <div class="flex items-center gap-1 text-[10px] text-emerald-500 font-bold mb-1 order-1">
                                                <span class="material-symbols-outlined text-[14px]">done_all</span>
                                                Đã gửi
                                            </div>
                                            <div class="px-4 py-3 rounded-2xl bg-primary text-white rounded-br-none shadow-sm order-2">
                                                <p class="text-sm whitespace-pre-wrap leading-relaxed">${msg.message}</p>
                                                ${msg.attachment_path ? `
                                                    <div class="mt-3">
                                                        <a href="${msg.attachment_path}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded bg-black/10 hover:bg-black/20 dark:bg-white/10 dark:hover:bg-white/20 transition-colors text-sm font-medium">
                                                            <span class="material-symbols-outlined text-[18px]">attachment</span> Xem tệp đính kèm
                                                        </a>
                                                    </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        chatContainer.insertAdjacentHTML('beforeend', messageHtml);
                        
                        // Clear inputs
                        textarea.value = '';
                        replyForm.querySelector('input[type="file"]').value = '';
                        attachName.textContent = '';
                        attachName.classList.add('hidden');
                        
                        // Scroll to bottom
                        scrollToBottom();
                    } else {
                        alert(result.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Không thể kết nối đến máy chủ.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span class="material-symbols-outlined text-[20px]">send</span>';
                }
            });
        }
    </script>
@endsection
