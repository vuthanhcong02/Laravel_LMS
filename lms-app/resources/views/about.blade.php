@extends('layouts.app')

@section('title', 'Giới thiệu')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Về Chúng Tôi</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                LMS - Nền tảng học tập trực tuyến hàng đầu, mang đến cơ hội học tập chất lượng cho mọi người
            </p>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Sứ mệnh của chúng tôi</h2>
                    <p class="text-gray-600 mb-6 text-lg">
                        Chúng tôi cam kết mang đến nền giáo dục chất lượng cao, dễ tiếp cận
                        và phù hợp với mọi đối tượng học viên.
                    </p>
                    <p class="text-gray-600 mb-8 text-lg">
                        Với đội ngũ giảng viên tận tâm và công nghệ hiện đại, chúng tôi tạo ra
                        môi trường học tập lý tưởng cho sự phát triển toàn diện.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-graduation-cap text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Học tập chất lượng</h3>
                            <p class="text-gray-600">Nội dung được kiểm duyệt kỹ lưỡng</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=80"
                        alt="Team collaboration" class="rounded-lg shadow-lg w-full h-80 object-cover">
                    <div class="absolute -bottom-6 -left-6 bg-blue-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="text-3xl font-bold">5+</div>
                        <div class="text-sm">Năm kinh nghiệm</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Giá trị cốt lõi</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Những nguyên tắc định hướng cho mọi hoạt động của chúng tôi</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-lg transition duration-300">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-lightbulb text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Sáng tạo</h3>
                    <p class="text-gray-600">
                        Luôn đổi mới phương pháp giảng dạy và công nghệ để mang đến
                        trải nghiệm học tập tốt nhất
                    </p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-lg transition duration-300">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-handshake text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Tin cậy</h3>
                    <p class="text-gray-600">
                        Cam kết chất lượng đào tạo và bảo mật thông tin cho mọi học viên
                    </p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-lg transition duration-300">
                    <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-purple-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Cộng đồng</h3>
                    <p class="text-gray-600">
                        Xây dựng cộng đồng học tập tích cực, nơi mọi người có thể
                        kết nối và hỗ trợ lẫn nhau
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Đội ngũ của chúng tôi</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Những chuyên gia tâm huyết với sứ mệnh giáo dục</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="text-center group">
                    <div class="relative mb-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=400&q=80"
                            alt="Nguyễn Văn A"
                            class="w-32 h-32 rounded-full mx-auto object-cover group-hover:scale-110 transition duration-300">
                        <div
                            class="absolute inset-0 bg-blue-600 rounded-full opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-1">Nguyễn Văn A</h3>
                    <p class="text-blue-600 mb-2">Giám đốc Đào tạo</p>
                    <p class="text-gray-600 text-sm">
                        10+ năm kinh nghiệm trong lĩnh vực giáo dục trực tuyến
                    </p>
                </div>

                <!-- Team Member 2 -->
                <div class="text-center group">
                    <div class="relative mb-4">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=400&q=80"
                            alt="Trần Thị B"
                            class="w-32 h-32 rounded-full mx-auto object-cover group-hover:scale-110 transition duration-300">
                        <div
                            class="absolute inset-0 bg-blue-600 rounded-full opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-1">Trần Thị B</h3>
                    <p class="text-blue-600 mb-2">Trưởng phòng Nội dung</p>
                    <p class="text-gray-600 text-sm">
                        Chuyên gia phát triển chương trình đào tạo
                    </p>
                </div>

                <!-- Team Member 3 -->
                <div class="text-center group">
                    <div class="relative mb-4">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=400&q=80"
                            alt="Lê Văn C"
                            class="w-32 h-32 rounded-full mx-auto object-cover group-hover:scale-110 transition duration-300">
                        <div
                            class="absolute inset-0 bg-blue-600 rounded-full opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-1">Lê Văn C</h3>
                    <p class="text-blue-600 mb-2">Chuyên gia Công nghệ</p>
                    <p class="text-gray-600 text-sm">
                        Phát triển nền tảng học tập thông minh
                    </p>
                </div>

                <!-- Team Member 4 -->
                <div class="text-center group">
                    <div class="relative mb-4">
                        <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=400&q=80"
                            alt="Phạm Thị D"
                            class="w-32 h-32 rounded-full mx-auto object-cover group-hover:scale-110 transition duration-300">
                        <div
                            class="absolute inset-0 bg-blue-600 rounded-full opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-1">Phạm Thị D</h3>
                    <p class="text-blue-600 mb-2">Quản lý Chất lượng</p>
                    <p class="text-gray-600 text-sm">
                        Đảm bảo chất lượng đào tạo và dịch vụ
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">50,000+</div>
                    <div class="text-blue-200">Học viên</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">1,000+</div>
                    <div class="text-blue-200">Khóa học</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">300+</div>
                    <div class="text-blue-200">Giảng viên</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="text-blue-200">Hài lòng</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Sẵn sàng tham gia cùng chúng tôi?</h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                Hãy cùng chúng tôi xây dựng tương lai giáo dục tốt đẹp hơn
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href=""
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                    Khám phá khóa học
                </a>
                <a href="{{ route('contact') }}"
                    class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white transition duration-300">
                    Liên hệ ngay
                </a>
            </div>
        </div>
    </section>
@endsection
