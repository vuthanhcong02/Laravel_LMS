@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Section -->
<section class="hero-bg text-white py-16 md:py-24 relative overflow-hidden">
     <div class="absolute top-0 left-0 w-full h-full opacity-10">
          <div class="floating absolute top-10 left-10 text-6xl"><i class="fas fa-book"></i></div>
          <div class="floating absolute top-1/4 right-20 text-5xl" style="animation-delay: 0.5s"><i
                    class="fas fa-laptop-code"></i></div>
          <div class="floating absolute bottom-20 left-1/4 text-4xl" style="animation-delay: 1s"><i
                    class="fas fa-chart-bar"></i></div>
          <div class="floating absolute bottom-10 right-1/3 text-6xl" style="animation-delay: 1.5s"><i
                    class="fas fa-palette"></i></div>
     </div>

     <div class="container mx-auto px-4 text-center relative z-10">
          <h1 class="text-4xl md:text-6xl font-bold mb-6" data-aos="fade-up">Học tập không giới hạn</h1>
          <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">Hàng ngàn khóa
               học chất lượng cao với mức giá cực kỳ phải chăng. Nâng cao kỹ năng và kiến thức ngay hôm nay!</p>
          <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4" data-aos="fade-up"
               data-aos-delay="400">
               <a href="#courses"
                    class="bg-white text-indigo-600 px-8 py-4 rounded-md font-bold text-lg hover:bg-gray-100 transition duration-300 pulse">Khám
                    phá khóa học</a>
               <a href="#"
                    class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-md font-bold text-lg hover:bg-white hover:text-indigo-600 transition duration-300">Tìm
                    hiểu thêm</a>
          </div>
     </div>
</section>

<!-- Categories -->
<section class="py-12 bg-white">
     <div class="container mx-auto px-4">
          <h2 class="text-3xl font-bold text-center mb-2" data-aos="fade-up">Danh mục khóa học</h2>
          <p class="text-gray-600 text-center mb-12" data-aos="fade-up" data-aos-delay="200">Chọn lĩnh vực bạn quan
               tâm</p>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
               <div class="category-card bg-indigo-50 p-6 rounded-lg text-center" data-aos="zoom-in" data-aos-delay="0">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-laptop-code text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Công nghệ</h3>
                    <p class="text-gray-600 text-sm mt-2">Lập trình, AI, Blockchain</p>
               </div>

               <div class="category-card bg-green-50 p-6 rounded-lg text-center" data-aos="zoom-in"
                    data-aos-delay="100">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Kinh doanh</h3>
                    <p class="text-gray-600 text-sm mt-2">Marketing, Quản lý, Khởi nghiệp</p>
               </div>

               <div class="category-card bg-yellow-50 p-6 rounded-lg text-center" data-aos="zoom-in"
                    data-aos-delay="200">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-palette text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Thiết kế</h3>
                    <p class="text-gray-600 text-sm mt-2">UI/UX, Đồ họa, 3D</p>
               </div>

               <div class="category-card bg-red-50 p-6 rounded-lg text-center" data-aos="zoom-in" data-aos-delay="300">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-camera text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Sáng tạo</h3>
                    <p class="text-gray-600 text-sm mt-2">Nhiếp ảnh, Âm nhạc, Viết lách</p>
               </div>
          </div>
     </div>
</section>

<!-- Featured Courses -->
<section id="courses" class="py-12 bg-gray-50">
     <div class="container mx-auto px-4">
          <h2 class="text-3xl font-bold text-center mb-2" data-aos="fade-up">Khóa học nổi bật</h2>
          <p class="text-gray-600 text-center mb-12" data-aos="fade-up" data-aos-delay="200">Các khóa học được yêu
               thích nhất</p>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
               <!-- Course Card 1 -->
               <div class="course-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up"
                    data-aos-delay="0">
                    <div class="relative overflow-hidden">
                         <div
                              class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center course-image">
                              <i class="fas fa-laptop-code text-white text-5xl"></i>
                         </div>
                         <div class="price-tag">Giảm 20%</div>
                    </div>
                    <div class="p-6">
                         <div class="flex justify-between items-start mb-2">
                              <h3 class="text-xl font-bold">Lập trình Web cơ bản</h3>
                              <span class="flex items-center text-yellow-500">
                                   <i class="fas fa-star"></i>
                                   <span class="ml-1">4.9</span>
                              </span>
                         </div>
                         <p class="text-gray-600 mb-4">Học HTML, CSS, JavaScript từ cơ bản đến nâng cao. Xây dựng
                              website đầu tiên của bạn.</p>
                         <div class="flex justify-between items-center">
                              <div>
                                   <span class="text-2xl font-bold text-indigo-600">299.000đ</span>
                                   <span class="text-gray-400 line-through ml-2">375.000đ</span>
                              </div>
                              <button
                                   class="add-to-cart bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Thêm
                                   vào giỏ</button>
                         </div>
                    </div>
               </div>

               <!-- Course Card 2 -->
               <div class="course-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up"
                    data-aos-delay="100">
                    <div class="relative overflow-hidden">
                         <div
                              class="h-48 bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center course-image">
                              <i class="fas fa-chart-line text-white text-5xl"></i>
                         </div>
                    </div>
                    <div class="p-6">
                         <div class="flex justify-between items-start mb-2">
                              <h3 class="text-xl font-bold">Digital Marketing</h3>
                              <span class="flex items-center text-yellow-500">
                                   <i class="fas fa-star"></i>
                                   <span class="ml-1">4.7</span>
                              </span>
                         </div>
                         <p class="text-gray-600 mb-4">Chiến lược marketing hiệu quả trên các nền tảng số. Tăng trưởng
                              doanh thu bền vững.</p>
                         <div class="flex justify-between items-center">
                              <div>
                                   <span class="text-2xl font-bold text-indigo-600">399.000đ</span>
                              </div>
                              <button
                                   class="add-to-cart bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Thêm
                                   vào giỏ</button>
                         </div>
                    </div>
               </div>

               <!-- Course Card 3 -->
               <div class="course-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up"
                    data-aos-delay="200">
                    <div class="relative overflow-hidden">
                         <div
                              class="h-48 bg-gradient-to-r from-yellow-500 to-orange-600 flex items-center justify-center course-image">
                              <i class="fas fa-palette text-white text-5xl"></i>
                         </div>
                         <div class="price-tag">Giảm 15%</div>
                    </div>
                    <div class="p-6">
                         <div class="flex justify-between items-start mb-2">
                              <h3 class="text-xl font-bold">Thiết kế đồ họa</h3>
                              <span class="flex items-center text-yellow-500">
                                   <i class="fas fa-star"></i>
                                   <span class="ml-1">4.8</span>
                              </span>
                         </div>
                         <p class="text-gray-600 mb-4">Làm chủ các công cụ thiết kế như Photoshop, Illustrator. Tạo ra
                              các sản phẩm sáng tạo.</p>
                         <div class="flex justify-between items-center">
                              <div>
                                   <span class="text-2xl font-bold text-indigo-600">349.000đ</span>
                                   <span class="text-gray-400 line-through ml-2">410.000đ</span>
                              </div>
                              <button
                                   class="add-to-cart bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Thêm
                                   vào giỏ</button>
                         </div>
                    </div>
               </div>

               <!-- Course Card 4 -->
               <div class="course-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up"
                    data-aos-delay="0">
                    <div class="relative overflow-hidden">
                         <div
                              class="h-48 bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center course-image">
                              <i class="fas fa-mobile-alt text-white text-5xl"></i>
                         </div>
                    </div>
                    <div class="p-6">
                         <div class="flex justify-between items-start mb-2">
                              <h3 class="text-xl font-bold">Lập trình di động</h3>
                              <span class="flex items-center text-yellow-500">
                                   <i class="fas fa-star"></i>
                                   <span class="ml-1">4.6</span>
                              </span>
                         </div>
                         <p class="text-gray-600 mb-4">Xây dựng ứng dụng di động với React Native. Phát triển app cho cả
                              iOS và Android.</p>
                         <div class="flex justify-between items-center">
                              <div>
                                   <span class="text-2xl font-bold text-indigo-600">449.000đ</span>
                              </div>
                              <button
                                   class="add-to-cart bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Thêm
                                   vào giỏ</button>
                         </div>
                    </div>
               </div>

               <!-- Course Card 5 -->
               <div class="course-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up"
                    data-aos-delay="100">
                    <div class="relative overflow-hidden">
                         <div
                              class="h-48 bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center course-image">
                              <i class="fas fa-database text-white text-5xl"></i>
                         </div>
                         <div class="price-tag">Mới</div>
                    </div>
                    <div class="p-6">
                         <div class="flex justify-between items-start mb-2">
                              <h3 class="text-xl font-bold">Khoa học dữ liệu</h3>
                              <span class="flex items-center text-yellow-500">
                                   <i class="fas fa-star"></i>
                                   <span class="ml-1">4.9</span>
                              </span>
                         </div>
                         <p class="text-gray-600 mb-4">Phân tích dữ liệu với Python, Machine Learning cơ bản. Khám phá
                              insights từ dữ liệu.</p>
                         <div class="flex justify-between items-center">
                              <div>
                                   <span class="text-2xl font-bold text-indigo-600">499.000đ</span>
                              </div>
                              <button
                                   class="add-to-cart bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Thêm
                                   vào giỏ</button>
                         </div>
                    </div>
               </div>

               <!-- Course Card 6 -->
               <div class="course-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up"
                    data-aos-delay="200">
                    <div class="relative overflow-hidden">
                         <div
                              class="h-48 bg-gradient-to-r from-red-500 to-pink-600 flex items-center justify-center course-image">
                              <i class="fas fa-camera text-white text-5xl"></i>
                         </div>
                    </div>
                    <div class="p-6">
                         <div class="flex justify-between items-start mb-2">
                              <h3 class="text-xl font-bold">Nhiếp ảnh chuyên nghiệp</h3>
                              <span class="flex items-center text-yellow-500">
                                   <i class="fas fa-star"></i>
                                   <span class="ml-1">4.7</span>
                              </span>
                         </div>
                         <p class="text-gray-600 mb-4">Kỹ thuật chụp ảnh, chỉnh sửa với Lightroom và Photoshop. Tạo ra
                              những bức ảnh ấn tượng.</p>
                         <div class="flex justify-between items-center">
                              <div>
                                   <span class="text-2xl font-bold text-indigo-600">329.000đ</span>
                              </div>
                              <button
                                   class="add-to-cart bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Thêm
                                   vào giỏ</button>
                         </div>
                    </div>
               </div>
          </div>

          <div class="text-center mt-12" data-aos="fade-up">
               <a href="courses.html"
                    class="btn-primary text-white px-8 py-3 rounded-md font-bold text-lg inline-block">Xem tất cả khóa
                    học</a>
          </div>
     </div>
</section>

<!-- Why Choose Us -->
<section class="py-12 bg-white">
     <div class="container mx-auto px-4">
          <h2 class="text-3xl font-bold text-center mb-2" data-aos="fade-up">Tại sao chọn chúng tôi?</h2>
          <p class="text-gray-600 text-center mb-12" data-aos="fade-up" data-aos-delay="200">Điểm khác biệt của nền
               tảng khóa học giá rẻ</p>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
               <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-dollar-sign text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Giá cực kỳ phải chăng</h3>
                    <p class="text-gray-600">Chất lượng cao với mức giá chỉ bằng 1/3 so với thị trường. Học không giới
                         hạn với chi phí tối ưu.</p>
               </div>

               <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-clock text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Học mọi lúc, mọi nơi</h3>
                    <p class="text-gray-600">Truy cập khóa học 24/7 từ bất kỳ thiết bị nào. Học theo tốc độ của riêng
                         bạn mà không bị gián đoạn.</p>
               </div>

               <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-users text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Cộng đồng học tập</h3>
                    <p class="text-gray-600">Kết nối với giảng viên và học viên khác. Thảo luận, chia sẻ kiến thức và
                         hỗ trợ lẫn nhau.</p>
               </div>
          </div>
     </div>
</section>
@endsection