@php
    use App\Models\User;
@endphp
<aside class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-[#141211] border-r border-[#e8e2d9] dark:border-[#262220] flex flex-col transition-all duration-300 ease-out lg:static shrink-0 h-screen overflow-hidden w-64"
               :class="{ 
                   'translate-x-0': sidebarOpen, 
                   '-translate-x-full lg:translate-x-0': !sidebarOpen, 
                   'w-64': !sidebarCollapsed, 
                   'w-20': sidebarCollapsed 
               }">
            
            <div class="h-20 flex items-center justify-between px-4 border-b border-[#e8e2d9] dark:border-[#262220] shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                    <img src="{{ asset('logo.png') }}" alt="XIAOMU Logo" class="w-10 h-10 rounded-full object-cover shrink-0 group-hover:scale-105 transition-transform duration-200">

                    <div x-show="!sidebarCollapsed" class="flex flex-col min-w-0 transition-opacity duration-200">
                        <span class="font-bold text-lg tracking-tight text-slate-900 dark:text-white leading-none">XIAOMU</span>
                        <span class="text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">
                            {{ __('Tiếng Trung') }}
                        </span>
                    </div>
                </a>

                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5 no-scrollbar">
                
                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">{{ __('Học tập') }}</div>
                    <div class="space-y-1">
                        @php
                            $isHomeActive = request()->routeIs('home');
                        @endphp
                        <a href="{{ route('home') }}" class="group flex items-center gap-3 rounded-xl text-sm {{ $isHomeActive ? 'nav-item-active' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }} px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Trang chủ') }}' : ''">
                            <i class="fa-solid fa-house text-base w-5 text-center shrink-0 transition-colors {{ $isHomeActive ? '' : 'text-slate-400 dark:text-slate-500 group-hover:text-[#e07a5f]' }}"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">{{ __('Trang chủ') }}</span>
                        </a>

                        @php
                            $isPinyinActive = request()->routeIs('pinyin.index') || request()->is('bang-phien-am-pinyin*') || request()->is('demo-pinyin-chart*');
                        @endphp
                        <a href="{{ route('pinyin.index') }}" class="group flex items-center gap-3 rounded-xl text-sm font-medium transition-all btn-tactile {{ $isPinyinActive ? 'nav-item-active' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Bảng Pinyin') }}' : ''">
                            <i class="fa-solid fa-table-cells text-base w-5 text-center transition-colors {{ $isPinyinActive ? '' : 'text-slate-400 dark:text-slate-500 group-hover:text-[#e07a5f]' }} shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">{{ __('Bảng Pinyin') }}</span>
                        </a>

                        @php
                            $isPinyinPracticeActive = request()->routeIs('pinyin.quiz');
                        @endphp
                        <a href="{{ route('pinyin.quiz') }}" class="group flex items-center gap-3 rounded-xl text-sm font-medium transition-all btn-tactile {{ $isPinyinPracticeActive ? 'nav-item-active' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Luyện tập Pinyin') }}' : ''">
                            <i class="fa-solid fa-headset text-base w-5 text-center transition-colors {{ $isPinyinPracticeActive ? '' : 'text-slate-400 dark:text-slate-500 group-hover:text-[#e07a5f]' }} shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">{{ __('Luyện tập Pinyin') }}</span>
                        </a>

                        @php
                            $isCourseActive = request()->routeIs('courses*') || request()->is('demo-courses');
                        @endphp
                        <a href="{{ route('courses') }}" class="group flex items-center gap-3 rounded-xl text-sm font-medium transition-all btn-tactile {{ $isCourseActive ? 'nav-item-active' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Khóa học HSK') }}' : ''">
                            <i class="fa-solid fa-book-open text-base w-5 text-center shrink-0 transition-colors {{ $isCourseActive ? '' : 'text-slate-400 dark:text-slate-500 group-hover:text-[#e07a5f]' }}"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">{{ __('Khóa học HSK') }}</span>
                        </a>

                        @php
                            $isFlashcardsActive = request()->routeIs('flashcards*') || request()->is('the-ghi-nho*') || request()->is('demo-flashcards');
                        @endphp
                        <a href="{{ route('flashcards') }}" class="group flex items-center gap-3 rounded-xl text-sm font-medium transition-all btn-tactile {{ $isFlashcardsActive ? 'nav-item-active' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Thẻ ghi nhớ') }}' : ''">
                            <i class="fa-solid fa-layer-group text-base w-5 text-center shrink-0 transition-colors {{ $isFlashcardsActive ? '' : 'text-slate-400 dark:text-slate-500 group-hover:text-[#e07a5f]' }}"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">{{ __('Thẻ ghi nhớ') }}</span>
                        </a>

                        @php
                            $isHskActive = request()->routeIs('student.hsk-mock-exams.*');
                        @endphp
                        <a href="{{ route('student.hsk-mock-exams.index') }}" class="group flex items-center gap-3 rounded-xl text-sm font-medium transition-all btn-tactile {{ $isHskActive ? 'nav-item-active' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Luyện thi HSK') }}' : ''">
                            <i class="fa-solid fa-file-pen text-base w-5 text-center shrink-0 transition-colors {{ $isHskActive ? '' : 'text-slate-400 dark:text-slate-500 group-hover:text-[#e07a5f]' }}"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">{{ __('Luyện thi HSK') }}</span>
                        </a>

                    </div>
                </div>

                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">{{ __('Hệ thống') }}</div>
                    <div class="space-y-1">
                        @if(auth()->check() && auth()->user()->role !== User::ROLE_GUEST)
                            @php
                                $dashboardRoute = '#';
                                $dashboardLabel = __('Bảng điều khiển');
                                $userRole = auth()->user()->role;
                                
                                if ($userRole === User::ROLE_ADMIN) {
                                    $dashboardRoute = route('admin.dashboard');
                                    $dashboardLabel = __('Quản trị hệ thống');
                                } elseif ($userRole === User::ROLE_TEACHER) {
                                    $dashboardRoute = route('teacher.dashboard');
                                    $dashboardLabel = __('Bảng điều khiển');
                                } else {
                                    $dashboardRoute = route('student.dashboard');
                                    $dashboardLabel = __('Tiến độ học tập');
                                }
                                $isDashboardActive = request()->url() === $dashboardRoute;
                            @endphp
                            <a href="{{ $dashboardRoute }}" class="group flex items-center gap-3 rounded-xl text-sm {{ $isDashboardActive ? 'nav-item-active' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }} px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? $dashboardLabel : ''">
                                <i class="fa-solid fa-chart-pie text-base w-5 text-center shrink-0 transition-colors {{ $isDashboardActive ? '' : 'text-slate-400 dark:text-slate-500 group-hover:text-[#e07a5f]' }}"></i>
                                <span x-show="!sidebarCollapsed" class="truncate">{{ $dashboardLabel }}</span>
                            </a>
                        @endif

                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-[#e07a5f] dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Liên hệ hỗ trợ') }}' : ''">
                            <i class="fa-solid fa-envelope text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">{{ __('Liên hệ hỗ trợ') }}</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <button x-show="isLoggedIn" @click.prevent="document.getElementById('logout-form').submit()" class="group flex items-center gap-3 w-full rounded-xl text-sm text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 font-medium px-3.5 py-2.5 transition-all btn-tactile cursor-pointer" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? '{{ __('Đăng xuất') }}' : ''">
                            <i class="fa-solid fa-arrow-right-from-bracket text-red-500 text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate font-semibold">{{ __('Đăng xuất') }}</span>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Banner Quảng cáo Nâng cấp -->
            {{-- <div x-show="!sidebarCollapsed" class="p-3.5 m-3 rounded-2xl bg-gradient-to-br from-[#fff2ee] to-[#fdeae3] dark:from-slate-800 dark:to-slate-900 border border-[#fcdccf] dark:border-slate-700 shadow-xs relative overflow-hidden shrink-0 transition-all">
                <div class="flex items-center gap-2 text-[#e07a5f] font-bold text-xs mb-1">
                    <i class="fa-solid fa-crown text-amber-500"></i> NÂNG CẤP TÀI KHOẢN
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 mb-2 leading-relaxed">Mở khóa toàn bộ các bộ đề thi HSK 1 - HSK 6 nâng cao.</p>
                <button class="w-full py-1.5 bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs shadow-md transition-all btn-tactile hover:shadow-lg">
                    Nâng cấp ngay
                </button>
            </div> --}}

            <!-- Chân trang Hồ sơ cá nhân (Ghim cố định shrink-0 ở đáy Sidebar) -->
            <div class="border-t border-[#e8e2d9] dark:border-[#262220] bg-[#faf6f2] dark:bg-slate-900/60 shrink-0 transition-all">
                
                <!-- TRẠNG THÁI 1: KHI ĐÃ ĐĂNG NHẬP -->
                <div x-show="isLoggedIn" class="p-3"
                     x-data="{ 
                         userName: '{{ auth()->check() ? (auth()->user()->first_name . ' ' . auth()->user()->last_name) : 'Vũ Thành Công' }}',
                         userAvatar: '{{ auth()->check() ? auth()->user()->avatar_url : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80' }}'
                     }"
                     @profile-updated.window="userName = $event.detail.name; userAvatar = $event.detail.avatar">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 min-w-0 group" :class="sidebarCollapsed ? 'justify-center' : ''">
                        <!-- Avatar tròn có huy hiệu Lv.1 ở góc dưới -->
                        <div class="relative shrink-0">
                            <img :src="userAvatar" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 group-hover:border-[#e07a5f] transition-colors">
                            <span class="absolute -bottom-1 -right-1 bg-slate-800 dark:bg-slate-900 text-slate-200 text-[9px] font-bold px-1 py-0.2 rounded-full border border-slate-600 leading-none">Lv.1</span>
                        </div>

                        <div x-show="!sidebarCollapsed" class="truncate min-w-0">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors" x-text="userName"></p>
                            <p class="text-xs text-slate-400 font-medium truncate">Hồ sơ cá nhân</p>
                        </div>
                    </a>
                </div>

                <!-- TRẠNG THÁI 2: KHI CHƯA ĐĂNG NHẬP (GUEST USER) -->
                <div x-show="!isLoggedIn" class="p-3 w-full">
                    <button @click="authModalOpen = true; authModalTab = 'login'" class="w-full py-2.5 bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold rounded-xl text-center shadow-md shadow-[#e07a5f]/20 transition-all btn-tactile flex items-center justify-center gap-2 cursor-pointer" :title="sidebarCollapsed ? 'Đăng nhập' : ''">
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        <span x-show="!sidebarCollapsed">Đăng nhập</span>
                    </button>
                </div>

                <!-- Nút Thu gọn Sidebar dưới cùng (BÁM SÁT HÌNH 1: « Thu gọn) -->
                <div class="border-t border-[#e8e2d9] dark:border-[#262220] py-2 px-3 bg-white/50 dark:bg-slate-900/40">
                    <button @click="sidebarCollapsed = !sidebarCollapsed" 
                            class="w-full py-1.5 flex items-center justify-center gap-2 text-xs font-bold text-slate-500 hover:text-[#e07a5f] dark:text-slate-400 dark:hover:text-white transition-colors btn-tactile" 
                            :title="sidebarCollapsed ? 'Mở rộng Sidebar' : 'Thu gọn Sidebar'">
                        <i class="fa-solid text-xs transition-transform duration-300" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
                        <span x-show="!sidebarCollapsed" class="tracking-wide">Thu gọn</span>
                    </button>
                </div>

            </div>
        </aside>