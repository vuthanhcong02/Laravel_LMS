<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LMS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-8">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">LMS</a>

                    <!-- Navigation -->
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium">Trang
                            chủ</a>
                        <a href="" class="text-gray-700 hover:text-blue-600 font-medium">Khóa học</a>
                        <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 font-medium">Giới
                            thiệu</a>
                        <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 font-medium">Liên
                            hệ</a>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Đăng nhập</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Đăng ký
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">LMS System</h3>
                    <p class="text-gray-400">Nền tảng học tập trực tuyến hàng đầu</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Liên kết</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Về chúng tôi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Khóa học</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Trợ giúp</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Liên hệ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Theo dõi chúng tôi</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 LMS System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
