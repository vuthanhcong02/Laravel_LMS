<header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-10">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 flex items-center">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    LMS
                </a>

                <!-- Navigation -->
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">Trang chủ</a>
                    <a href="" class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">Khóa
                        học</a>
                    <a href="{{ route('about') }}"
                        class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">Giới thiệu</a>
                    <a href="{{ route('contact') }}"
                        class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">Liên hệ</a>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-6">
                <!-- Search Bar -->
                <div class="hidden md:block relative">
                    <input type="text" placeholder="Tìm kiếm khóa học..."
                        class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Cart Icon -->
                <div class="relative mx-2">
                    <a href="" class="text-gray-700 hover:text-blue-600 transition duration-300 relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <!-- Cart Badge -->
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            2
                        </span>
                    </a>
                </div>
                <!-- User Menu -->
                @auth
                    <div class="relative ml-2" x-data="{ open: false }">
                        <!-- User Avatar with Verification Status -->
                        <button @click="open = !open" type="button"
                            class="flex items-center space-x-3 text-gray-700 hover:text-blue-600 transition duration-300 focus:outline-none">
                            <div class="relative">
                                <div
                                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                                </div>
                                <!-- Verification Badge -->
                            </div>
                            <span class="hidden md:block font-medium">{{ Auth::user()->first_name }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300"
                                :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-cloak @click.away="open = false"
                            class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200"
                            style="display: none;" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95">

                            <!-- User Info with Verification Status -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-sm font-semibold text-gray-800 truncate">
                                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                    </p>
                                </div>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @unless (Auth::user()->hasVerifiedEmail())
                                <div class="px-4 py-3 bg-yellow-50 border-b border-yellow-100">
                                    <div class="flex items-start space-x-2">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <i class="fas fa-exclamation-triangle text-yellow-500 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-yellow-800 mb-1">
                                                Email chưa được xác minh
                                            </p>
                                            <p class="text-xs text-yellow-700 mb-2">
                                                Vui lòng xác minh email để sử dụng đầy đủ tính năng.
                                            </p>
                                            <form method="POST" action="{{ route('verification.send') }}">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition duration-200">
                                                    Gửi lại email xác minh
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endunless

                            @if (Auth::user()->hasVerifiedEmail())
                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="" @click="open = false"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
                                        <i class="fas fa-tachometer-alt mr-3 w-4 text-blue-500"></i>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.edit') }}" @click="open = false"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
                                        <i class="fas fa-user-edit mr-3 w-4 text-green-500"></i>
                                        Cập nhật hồ sơ
                                    </a>
                                    <a href="" @click="open = false"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
                                        <i class="fas fa-book-open mr-3 w-4 text-purple-500"></i>
                                        Khóa học của tôi
                                    </a>
                                    <a href="" @click="open = false"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
                                        <i class="fas fa-receipt mr-3 w-4 text-orange-500"></i>
                                        Lịch sử đơn hàng
                                    </a>
                                </div>
                            @endif
                            <!-- Logout -->
                            <div class="border-t border-gray-100 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                                        <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-blue-600 font-medium transition duration-300 hidden md:block">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition duration-300 font-medium">
                            Đăng ký
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden mt-4">
            <div class="flex items-center space-x-4">
                <!-- Mobile Search -->
                <div class="flex-1 relative">
                    <input type="text" placeholder="Tìm kiếm..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Mobile Cart -->
                <a href="" class="text-gray-700 hover:text-blue-600 transition duration-300 relative mx-2">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        2
                    </span>
                </a>
            </div>

            <!-- Mobile Navigation -->
            <div class="grid grid-cols-4 gap-3 mt-4 text-center">
                <a href="{{ route('home') }}"
                    class="text-gray-700 hover:text-blue-600 font-medium py-2 transition duration-300">
                    <i class="fas fa-home block text-lg mb-1"></i>
                    <span class="text-xs">Trang chủ</span>
                </a>
                <a href="" class="text-gray-700 hover:text-blue-600 font-medium py-2 transition duration-300">
                    <i class="fas fa-book block text-lg mb-1"></i>
                    <span class="text-xs">Khóa học</span>
                </a>
                <a href="{{ route('about') }}"
                    class="text-gray-700 hover:text-blue-600 font-medium py-2 transition duration-300">
                    <i class="fas fa-info-circle block text-lg mb-1"></i>
                    <span class="text-xs">Giới thiệu</span>
                </a>
                <a href="{{ route('contact') }}"
                    class="text-gray-700 hover:text-blue-600 font-medium py-2 transition duration-300">
                    <i class="fas fa-envelope block text-lg mb-1"></i>
                    <span class="text-xs">Liên hệ</span>
                </a>
            </div>
        </div>
    </nav>
</header>
