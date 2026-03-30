<header
    class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-primary/20 bg-white dark:bg-slate-900 px-6 py-3 lg:px-10">
    <div class="flex items-center gap-8">
        <div class="flex items-center gap-3 text-primary">
            <button @click="sidebarOpen = !sidebarOpen"
                class="md:hidden flex items-center justify-center p-2 -ml-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined">school</span>
            </div>
            <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">XiaoMu Chinese LMS
            </h2>
        </div>
    </div>
    <div class="flex flex-1 justify-end gap-6 items-center">
        <nav class="hidden lg:flex items-center gap-8">
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('home') }}">Home</a>
            <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                href="{{ route('admin.support.index') }}">Support</a>
        </nav>
        <div class="flex gap-3">
            {{-- Notifications Dropdown --}}
            @php
                $unreadNotificationsCount = Auth::user()->unreadNotifications->count();
                $latestNotifications = Auth::user()->unreadNotifications()->take(5)->get();
            @endphp
            <div class="relative" x-data="{ 
                notifOpen: false, 
                unreadCount: {{ $unreadNotificationsCount }},
                markAsRead(id, url, event) {
                    if (event) event.preventDefault();
                    
                    let self = this;
                    axios.post('/portal/admin/notifications/' + id + '/mark-as-read')
                        .then(response => {
                            if(response.data.success) {
                                let item = document.getElementById('notif-' + id);
                                if(item) item.classList.remove('bg-blue-50', 'dark:bg-slate-800/80');
                                let dot = document.getElementById('notif-dot-' + id);
                                if(dot) dot.style.display = 'none';
                                
                                self.unreadCount = Math.max(0, self.unreadCount - 1);
                            }
                        })
                        .finally(() => {
                            if (url && url !== '#' && url !== '') {
                                window.location.href = url;
                            }
                        });
                },
                markAllAsRead() {
                    axios.post('{{ route('admin.notifications.markAllAsRead') }}')
                        .then(response => {
                            if(response.data.success) {
                                this.unreadCount = 0;
                                document.querySelectorAll('.notif-item').forEach(el => {
                                    el.classList.remove('bg-blue-50', 'dark:bg-slate-800/80');
                                });
                                document.querySelectorAll('.notif-dot').forEach(el => {
                                    el.style.display = 'none';
                                });
                            }
                        });
                }
            }">
                <button @click="notifOpen = !notifOpen"
                    class="relative flex items-center justify-center rounded-lg size-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span x-cloak x-show="unreadCount > 0" x-text="unreadCount"
                        class="absolute top-1 right-1 flex size-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white border-2 border-white dark:border-slate-800"></span>
                </button>

                {{-- Notification Popup --}}
                <div x-show="notifOpen" @click.outside="notifOpen = false"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute -right-16 md:right-0 mt-2 w-80 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg z-50 overflow-hidden flex flex-col"
                    style="display: none;">
                    <div
                        class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">Thông báo</p>
                        <button type="button" x-cloak x-show="unreadCount > 0" @click="markAllAsRead()" class="text-xs text-primary font-medium hover:underline">Đánh dấu tất cả đã đọc</button>
                    </div>
                    <div class="max-h-[320px] overflow-y-auto">
                        @forelse($latestNotifications as $notification)
                            @php
                                $data = $notification->data;
                                $isUnread = $notification->unread();
                                $iconColor = $data['icon_color'] ?? 'primary';
                                $iconBgClass = match($iconColor) {
                                    'emerald', 'emerald-500' => 'bg-emerald-500/10 text-emerald-500',
                                    'amber', 'amber-500' => 'bg-amber-500/10 text-amber-500',
                                    'red', 'red-500' => 'bg-red-500/10 text-red-500',
                                    'blue', 'blue-500' => 'bg-blue-500/10 text-blue-500',
                                    default => 'bg-primary/10 text-primary',
                                };
                            @endphp
                            <a href="{{ $data['link'] ?? '#' }}" id="notif-{{ $notification->id }}"
                                @click="markAsRead('{{ $notification->id }}', '{{ $data['link'] ?? '#' }}', $event)"
                                class="notif-item flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800/50 relative bg-blue-50 dark:bg-slate-800/80">
                                
                                <div id="notif-dot-{{ $notification->id }}" class="notif-dot size-2 bg-primary rounded-full absolute left-1.5 top-4"></div>
                                
                                <div
                                    class="size-10 rounded-full {{ $iconBgClass }} flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-xl">{{ $data['icon'] ?? 'campaign' }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $data['title'] ?? 'Thông báo' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $data['message'] ?? '' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="py-10 flex flex-col items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2">notifications_paused</span>
                                <p class="text-sm">Không có thông báo mới.</p>
                            </div>
                        @endforelse
                    </div>
                    <a href="{{ route('admin.notifications.index') }}"
                        class="block px-4 py-2.5 bg-slate-50 dark:bg-slate-800 text-center text-sm text-primary font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors border-t border-slate-100 dark:border-slate-800">Xem
                        tất cả thông báo</a>
                </div>
            </div>
            {{-- User Info Dropdown --}}
            <div class="relative" x-data="{ userMenuOpen: false }">
                <button @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center gap-3 rounded-xl px-2 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    {{-- Avatar --}}
                    @php
                        $avatar = Auth::user()->avatar;
                        $avatarUrl = $avatar
                            ? (str_starts_with($avatar, 'http')
                                ? $avatar
                                : asset('storage/' . $avatar))
                            : null;
                    @endphp
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" class="size-9 rounded-full border-2 border-primary object-cover"
                            alt="{{ Auth::user()->first_name }}">
                    @else
                        <div
                            class="size-9 rounded-full border-2 border-primary bg-primary flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                    @endif
                    {{-- Name & Role --}}
                    <div class="hidden lg:flex flex-col items-start leading-tight">
                        <span class="text-sm font-semibold text-slate-800 dark:text-white">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </span>
                        <span class="text-xs text-primary font-medium">
                            @php
                                $roleLabels = \App\Models\User::getAllRole() + [
                                    \App\Models\User::ROLE_ADMIN => 'Admin',
                                ];
                            @endphp
                            {{ $roleLabels[Auth::user()->role] ?? 'Unknown' }}
                        </span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 text-base hidden lg:block"
                        x-text="userMenuOpen ? 'expand_less' : 'expand_more'"></span>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="userMenuOpen" @click.outside="userMenuOpen = false"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg z-50 overflow-hidden"
                    style="display: none;">
                    {{-- User summary at top --}}
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    {{-- Menu items --}}
                    <div class="py-1">
                        <a href="{{ route('admin.profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-base">manage_accounts</span> Profile
                        </a>
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-base">settings</span> Settings
                        </a>
                    </div>
                    {{-- Logout --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 py-1">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
