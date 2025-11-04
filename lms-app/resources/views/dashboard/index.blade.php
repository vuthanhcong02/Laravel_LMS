<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LMS Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between px-4 py-3">
            <!-- Left Section -->
            <div class="flex items-center space-x-4">
                <!-- Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-blue-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Logo -->
                <a href="{{ route('student.dashboard') }}" class="text-xl font-bold text-blue-600">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    LMS Dashboard
                </a>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-600 hover:text-blue-600 relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            3
                        </span>
                    </button>
                    <!-- Notification Dropdown -->
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">Thông báo</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <!-- Notification Items -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-800">Bài tập mới</p>
                                <p class="text-xs text-gray-500">Môn Toán - Hạn: 15/12/2024</p>
                            </a>
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-800">Khóa học mới</p>
                                <p class="text-xs text-gray-500">Web Development đã được thêm</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                            </div>
                            <span class="hidden md:block font-medium">{{ auth()->user()->first_name }}</span>
                            <i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->full_name }}</p>
                                <p class="text-xs text-gray-500 capitalize">
                                    @if (auth()->user()->role === 'admin')
                                        Quản trị viên
                                    @elseif(auth()->user()->role === 'teacher')
                                        Giảng viên
                                    @else
                                        Học viên
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-user-edit mr-2"></i>Hồ sơ
                            </a>
                            <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-cog mr-2"></i>Cài đặt
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 border-t border-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <div class="flex" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-cloak
            class="sidebar-transition bg-white w-64 min-h-screen shadow-lg border-r border-gray-200"
            :class="sidebarOpen ? 'block' : 'hidden'">
            <nav class="mt-6">
                @include('dashboard.sidebar')
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6" :class="sidebarOpen ? 'ml-0' : 'ml-0'">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-gray-600">@yield('page-description', 'Tổng quan hệ thống')</p>
            </div>

            <!-- Content -->
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 LMS System. All rights reserved.</p>
            <p class="text-gray-400 text-sm mt-2">Hệ thống quản lý học tập trực tuyến</p>
        </div>
    </footer>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
