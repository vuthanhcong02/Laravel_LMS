<aside class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-[#141211] border-r border-[#e8e2d9] dark:border-[#262220] flex flex-col transition-all duration-300 ease-out lg:static shrink-0 h-screen overflow-hidden w-64"
               :class="{ 
                   'translate-x-0': sidebarOpen, 
                   '-translate-x-full lg:translate-x-0': !sidebarOpen, 
                   'w-64': !sidebarCollapsed, 
                   'w-20': sidebarCollapsed 
               }">
            
            <!-- KHU VỰC LOGO THƯƠNG HIỆU -->
            <div class="h-20 flex items-center justify-between px-4 border-b border-[#e8e2d9] dark:border-[#262220] shrink-0">
                <a href="{{ url('/demo-ui') }}" class="flex items-center gap-3 group min-w-0" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                    <img src="{{ asset('logo.png') }}" alt="XIAOMU Logo" class="w-10 h-10 rounded-full object-cover shrink-0 group-hover:scale-105 transition-transform duration-200">

                    <div x-show="!sidebarCollapsed" class="flex flex-col min-w-0 transition-opacity duration-200">
                        <span class="font-bold text-lg tracking-tight text-slate-900 dark:text-white leading-none">XIAOMU</span>
                        <span class="text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] tracking-wide mt-1 leading-none">
                            Tiếng Trung LMS
                        </span>
                    </div>
                </a>

                <!-- Nút đóng trên Mobile -->
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Menu chính (Tự co giãn scroll nội dung) -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5 no-scrollbar">
                
                <!-- Nhóm Học tập -->
                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Học tập</div>
                    <div class="space-y-1">
                        <!-- Trang chủ -->
                        <a href="{{ url('/demo-ui') }}" class="flex items-center gap-3 rounded-xl text-sm nav-item-active px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Trang chủ' : ''">
                            <i class="fa-solid fa-house text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Trang chủ</span>
                        </a>

                        <!-- Khóa học -->
                        <a href="{{ url('/demo-courses') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Khóa học HSK' : ''">
                            <i class="fa-solid fa-book-open text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Khóa học HSK</span>
                        </a>

                        <!-- Luyện thi HSK -->
                        <a href="{{ url('/demo-exams') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện thi HSK' : ''">
                            <i class="fa-solid fa-file-pen text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện thi HSK</span>
                        </a>

                        <!-- Thẻ ghi nhớ -->
                        <a href="{{ url('/demo-flashcards') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Thẻ ghi nhớ' : ''">
                            <i class="fa-solid fa-layer-group text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Thẻ ghi nhớ</span>
                        </a>
                        <a href="{{ url('/demo-etymology') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Chiết tự chữ Hán' : ''">
                            <i class="fa-solid fa-puzzle-piece text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Chiết tự chữ Hán</span>
                        </a>

                        <!-- Bảng phiên âm Pinyin -->
                        <a href="{{ url('/demo-pinyin-chart') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Bảng Pinyin' : ''">
                            <i class="fa-solid fa-table-cells text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Bảng Pinyin</span>
                        </a>

                        <!-- Luyện tập Pinyin -->
                        <a href="{{ url('/demo-pinyin-practice') }}" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Luyện tập Pinyin' : ''">
                            <i class="fa-solid fa-headset text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Luyện tập Pinyin</span>
                        </a>

                        <!-- Góc chia sẻ (Blog) -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Góc chia sẻ' : ''">
                            <i class="fa-solid fa-newspaper text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Góc chia sẻ</span>
                        </a>
                    </div>
                </div>

                <!-- Nhóm Cá nhân & Hệ thống (Bám sát thiết kế hình ảnh) -->
                <div>
                    <div x-show="!sidebarCollapsed" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Hệ thống</div>
                    <div class="space-y-1">
                        <!-- Từ vựng của tôi (Bám sát hình 1) -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Từ vựng của tôi' : ''">
                            <i class="fa-solid fa-font text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Từ vựng của tôi</span>
                        </a>

                        <!-- Liên hệ -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-[#e07a5f] dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Liên hệ hỗ trợ' : ''">
                            <i class="fa-solid fa-envelope text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Liên hệ hỗ trợ</span>
                        </a>

                        <!-- Cài đặt -->
                        <a href="#" class="group flex items-center gap-3 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Cài đặt tài khoản' : ''">
                            <i class="fa-solid fa-gear text-slate-400 dark:text-slate-500 text-base w-5 text-center group-hover:text-[#e07a5f] transition-colors shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate">Cài đặt tài khoản</span>
                        </a>

                        <!-- Đăng xuất (Bám sát hình 1 - Chữ đỏ & Icon Đăng xuất) -->
                        <button x-show="isLoggedIn" @click="isLoggedIn = false" class="group flex items-center gap-3 w-full rounded-xl text-sm text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 font-medium px-3.5 py-2.5 transition-all btn-tactile" :class="sidebarCollapsed ? 'justify-center p-2.5' : 'px-3.5 py-2.5'" :title="sidebarCollapsed ? 'Đăng xuất' : ''">
                            <i class="fa-solid fa-arrow-right-from-bracket text-red-500 text-base w-5 text-center shrink-0"></i>
                            <span x-show="!sidebarCollapsed" class="truncate font-semibold">Đăng xuất</span>
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
                
                <!-- TRẠNG THÁI 1: KHI ĐÃ ĐĂNG NHẬP (BÁM SÁT HÌNH 1) -->
                <div x-show="isLoggedIn" class="p-3">
                    <a href="#" class="flex items-center gap-3 min-w-0 group" :class="sidebarCollapsed ? 'justify-center' : ''">
                        <!-- Avatar tròn có huy hiệu Lv.1 ở góc dưới -->
                        <div class="relative shrink-0">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 group-hover:border-[#e07a5f] transition-colors">
                            <span class="absolute -bottom-1 -right-1 bg-slate-800 dark:bg-slate-900 text-slate-200 text-[9px] font-bold px-1 py-0.2 rounded-full border border-slate-600 leading-none">Lv.1</span>
                        </div>

                        <div x-show="!sidebarCollapsed" class="truncate min-w-0">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-[#e07a5f] transition-colors">Vũ Thành Công</p>
                            <p class="text-xs text-slate-400 font-medium truncate">Hồ sơ</p>
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