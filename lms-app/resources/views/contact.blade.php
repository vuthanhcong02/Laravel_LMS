@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Liên Hệ</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn
            </p>
        </div>
    </section>

    <!-- Contact Info & Form -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Information -->
                <div class="lg:col-span-1">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Thông tin liên hệ</h2>
                    <p class="text-gray-600 mb-8">
                        Chúng tôi luôn sẵn sàng hỗ trợ và giải đáp mọi thắc mắc của bạn.
                        Đừng ngần ngại liên hệ với chúng tôi.
                    </p>

                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-blue-100 p-3 rounded-full mt-1">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Địa chỉ</h3>
                                <p class="text-gray-600">
                                    123 Đường ABC, Quận 1<br>
                                    Thành phố Hồ Chí Minh, Việt Nam
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-100 p-3 rounded-full mt-1">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Điện thoại</h3>
                                <p class="text-gray-600">
                                    +84 28 1234 5678<br>
                                    +84 90 123 4567
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-full mt-1">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                                <p class="text-gray-600">
                                    info@lms.edu.vn<br>
                                    support@lms.edu.vn
                                </p>
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-yellow-100 p-3 rounded-full mt-1">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Giờ làm việc</h3>
                                <p class="text-gray-600">
                                    Thứ 2 - Thứ 6: 8:00 - 18:00<br>
                                    Thứ 7: 8:00 - 12:00<br>
                                    Chủ nhật: Nghỉ
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h3 class="font-semibold text-gray-800 mb-4">Theo dõi chúng tôi</h3>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition duration-300">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#"
                                class="bg-blue-400 text-white p-3 rounded-full hover:bg-blue-500 transition duration-300">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#"
                                class="bg-red-600 text-white p-3 rounded-full hover:bg-red-700 transition duration-300">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#"
                                class="bg-blue-700 text-white p-3 rounded-full hover:bg-blue-800 transition duration-300">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 p-8 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Gửi tin nhắn cho chúng tôi</h2>

                        <form action="" method="POST">
                            @csrf

                            @if (session('success'))
                                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded mb-6">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                                    <ul class="list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên
                                        *</label>
                                    <input type="text" id="name" name="name" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="Nhập họ và tên" value="{{ old('name') }}">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                        *</label>
                                    <input type="email" id="email" name="email" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="Nhập địa chỉ email" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện
                                    thoại</label>
                                <input type="tel" id="phone" name="phone"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                    placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                            </div>

                            <div class="mb-6">
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Chủ đề *</label>
                                <select id="subject" name="subject" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                                    <option value="">Chọn chủ đề</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>Thông tin
                                        chung</option>
                                    <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Hỗ trợ kỹ
                                        thuật</option>
                                    <option value="course" {{ old('subject') == 'course' ? 'selected' : '' }}>Thông tin khóa
                                        học</option>
                                    <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Hợp
                                        tác</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Nội dung tin nhắn
                                    *</label>
                                <textarea id="message" name="message" rows="6" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 resize-none"
                                    placeholder="Nhập nội dung tin nhắn của bạn">{{ old('message') }}</textarea>
                            </div>

                            <div class="flex items-center mb-6">
                                <input type="checkbox" id="newsletter" name="newsletter"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ old('newsletter') ? 'checked' : '' }}>
                                <label for="newsletter" class="ml-2 block text-sm text-gray-700">
                                    Đăng ký nhận bản tin và thông tin khuyến mãi
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Gửi tin nhắn
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Vị trí của chúng tôi</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Google Map Placeholder - Thay bằng embed map thực tế -->
                <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-4xl text-blue-600 mb-4"></i>
                        <p class="text-gray-600">Bản đồ sẽ được hiển thị tại đây</p>
                        <p class="text-sm text-gray-500 mt-2">123 Đường ABC, Quận 1, TP.HCM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Câu hỏi thường gặp</h2>
            <div class="max-w-4xl mx-auto space-y-6">
                <!-- FAQ Item 1 -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <button class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-semibold text-gray-800">Làm thế nào để đăng ký khóa học?</h3>
                        <i class="fas fa-chevron-down text-blue-600 transition-transform duration-300"></i>
                    </button>
                    <div class="mt-4 text-gray-600">
                        <p>Bạn có thể đăng ký khóa học bằng cách:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Truy cập trang "Khóa học" và chọn khóa học phù hợp</li>
                            <li>Nhấn nút "Đăng ký" và làm theo hướng dẫn</li>
                            <li>Thanh toán học phí qua các phương thức có sẵn</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <button class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-semibold text-gray-800">Tôi có được hoàn tiền nếu không hài lòng?</h3>
                        <i class="fas fa-chevron-down text-blue-600 transition-transform duration-300"></i>
                    </button>
                    <div class="mt-4 text-gray-600">
                        <p>Chúng tôi có chính sách hoàn tiền trong vòng 7 ngày nếu bạn không hài lòng với khóa học.
                            Liên hệ với bộ phận hỗ trợ để được hướng dẫn chi tiết.</p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <button class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-semibold text-gray-800">Làm thế nào để trở thành giảng viên?</h3>
                        <i class="fas fa-chevron-down text-blue-600 transition-transform duration-300"></i>
                    </button>
                    <div class="mt-4 text-gray-600">
                        <p>Nếu bạn muốn trở thành giảng viên của chúng tôi, vui lòng:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Gửi CV và đề xuất khóa học đến email: partners@lms.edu.vn</li>
                            <li>Chúng tôi sẽ liên hệ lại trong vòng 3-5 ngày làm việc</li>
                            <li>Tham gia phỏng vấn và demo bài giảng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
