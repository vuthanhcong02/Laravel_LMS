@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Học tập mọi lúc, mọi nơi</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Khám phá hàng ngàn khóa học chất lượng cao từ các chuyên gia hàng đầu
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href=""
                    class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    Khám phá khóa học
                </a>
                <a href="{{ route('register') }}"
                    class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                    Đăng ký ngay
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Tại sao chọn chúng tôi?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Các tính năng nổi bật giúp bạn học tập hiệu quả hơn</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-laptop-code text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Học mọi lúc mọi nơi</h3>
                    <p class="text-gray-600">Truy cập khóa học trên mọi thiết bị, học bất cứ khi nào bạn muốn</p>
                </div>

                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Giảng viên chất lượng</h3>
                    <p class="text-gray-600">Học từ các chuyên gia hàng đầu trong lĩnh vực</p>
                </div>

                <div class="text-center p-6">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-certificate text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Chứng chỉ công nhận</h3>
                    <p class="text-gray-600">Nhận chứng chỉ sau khi hoàn thành khóa học</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Courses -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Khóa học nổi bật</h2>
                    <p class="text-gray-600">Các khóa học được yêu thích nhất</p>
                </div>
                <a href="" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Course Card 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="h-48 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Bestseller</span>
                            <span class="text-yellow-500 ml-2"><i class="fas fa-star"></i> 4.9</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Web Development Bootcamp</h3>
                        <p class="text-gray-600 mb-4">Học phát triển web từ cơ bản đến nâng cao</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-800">599.000đ</span>
                            <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Đăng ký
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Course Card 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="h-48 bg-gradient-to-r from-green-400 to-green-600"></div>
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Mới</span>
                            <span class="text-yellow-500 ml-2"><i class="fas fa-star"></i> 4.8</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Data Science Fundamentals</h3>
                        <p class="text-gray-600 mb-4">Khóa học nền tảng về khoa học dữ liệu</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-800">799.000đ</span>
                            <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Đăng ký
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Course Card 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="h-48 bg-gradient-to-r from-purple-400 to-purple-600"></div>
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Bestseller</span>
                            <span class="text-yellow-500 ml-2"><i class="fas fa-star"></i> 4.7</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Mobile App Development</h3>
                        <p class="text-gray-600 mb-4">Phát triển ứng dụng di động đa nền tảng</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-800">699.000đ</span>
                            <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Đăng ký
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">10,000+</div>
                    <div class="text-blue-200">Học viên</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-blue-200">Khóa học</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">200+</div>
                    <div class="text-blue-200">Giảng viên</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">95%</div>
                    <div class="text-blue-200">Hài lòng</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Sẵn sàng bắt đầu hành trình học tập?</h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                Tham gia cộng đồng học tập của chúng tôi ngay hôm nay và khám phá tiềm năng của bạn
            </p>
            <a href=""
                class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                Đăng ký tài khoản miễn phí
            </a>
        </div>
    </section>
@endsection
