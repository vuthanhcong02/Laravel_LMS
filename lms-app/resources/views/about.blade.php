@extends('layouts.app')

@section('title', 'Giới thiệu')

@section('content')
<x-breadcrumb />
<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-16">
     <div class="container mx-auto px-4 text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-6">Về Chúng Tôi</h1>
          <p class="text-xl md:text-2xl max-w-3xl mx-auto">Khóa Học Giá Rẻ - Nền tảng học tập trực tuyến hàng đầu với sứ
               mệnh mang lại kiến thức chất lượng cho mọi người với mức giá phải chăng nhất.</p>
     </div>
</section>

<!-- About Content -->
<section class="py-16 bg-white">
     <div class="container mx-auto px-4">
          <div class="flex flex-col lg:flex-row items-center gap-12">
               <div class="lg:w-1/2">
                    <h2 class="text-3xl font-bold mb-6">Câu chuyện của chúng tôi</h2>
                    <p class="text-gray-600 mb-4">Được thành lập vào năm 2020, Khóa Học Giá Rẻ ra đời với sứ mệnh phá bỏ
                         rào cản về chi phí trong giáo dục trực tuyến.</p>
                    <p class="text-gray-600 mb-6">Chúng tôi tin rằng mọi người đều xứng đáng có cơ hội tiếp cận với kiến
                         thức chất lượng cao, bất kể hoàn cảnh tài chính của họ như thế nào.</p>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                         <div class="text-center">
                              <div class="text-3xl font-bold text-indigo-600 mb-2">50,000+</div>
                              <div class="text-gray-600">Học viên</div>
                         </div>
                         <div class="text-center">
                              <div class="text-3xl font-bold text-indigo-600 mb-2">500+</div>
                              <div class="text-gray-600">Khóa học</div>
                         </div>
                         <div class="text-center">
                              <div class="text-3xl font-bold text-indigo-600 mb-2">100+</div>
                              <div class="text-gray-600">Giảng viên</div>
                         </div>
                         <div class="text-center">
                              <div class="text-3xl font-bold text-indigo-600 mb-2">4.8/5</div>
                              <div class="text-gray-600">Đánh giá</div>
                         </div>
                    </div>

                    <a href="courses.html"
                         class="btn-primary text-white px-8 py-3 rounded-md font-semibold inline-block">Khám phá khóa
                         học</a>
               </div>

               <div class="lg:w-1/2">
                    <div class="bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl p-8">
                         <div class="grid grid-cols-2 gap-4">
                              <div class="bg-white rounded-lg p-4 text-center feature-card">
                                   <div
                                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-dollar-sign text-indigo-600 text-xl"></i>
                                   </div>
                                   <h3 class="font-semibold mb-2">Giá cả phải chăng</h3>
                                   <p class="text-sm text-gray-600">Chỉ bằng 1/3 so với thị trường</p>
                              </div>
                              <div class="bg-white rounded-lg p-4 text-center feature-card">
                                   <div
                                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-award text-indigo-600 text-xl"></i>
                                   </div>
                                   <h3 class="font-semibold mb-2">Chất lượng cao</h3>
                                   <p class="text-sm text-gray-600">Được kiểm duyệt nghiêm ngặt</p>
                              </div>
                              <div class="bg-white rounded-lg p-4 text-center feature-card">
                                   <div
                                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-clock text-indigo-600 text-xl"></i>
                                   </div>
                                   <h3 class="font-semibold mb-2">Học mọi lúc</h3>
                                   <p class="text-sm text-gray-600">Truy cập 24/7 trọn đời</p>
                              </div>
                              <div class="bg-white rounded-lg p-4 text-center feature-card">
                                   <div
                                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-headset text-indigo-600 text-xl"></i>
                                   </div>
                                   <h3 class="font-semibold mb-2">Hỗ trợ 24/7</h3>
                                   <p class="text-sm text-gray-600">Đội ngũ hỗ trợ nhiệt tình</p>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</section>

<!-- Mission & Vision -->
<section class="py-16 bg-gray-50">
     <div class="container mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
               <div class="bg-white rounded-2xl p-8 shadow-md">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                         <i class="fas fa-bullseye text-indigo-600 text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold mb-4">Sứ mệnh</h2>
                    <p class="text-gray-600 mb-4">Chúng tôi cam kết mang đến những khóa học chất lượng cao với mức giá
                         phải chăng nhất, giúp mọi người có cơ hội phát triển kỹ năng và thăng tiến trong sự nghiệp.</p>
                    <p class="text-gray-600">Bằng cách hợp tác với các giảng viên hàng đầu và áp dụng công nghệ tiên
                         tiến, chúng tôi tạo ra môi trường học tập linh hoạt, hiệu quả và dễ tiếp cận.</p>
               </div>

               <div class="bg-white rounded-2xl p-8 shadow-md">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                         <i class="fas fa-eye text-purple-600 text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold mb-4">Tầm nhìn</h2>
                    <p class="text-gray-600 mb-4">Chúng tôi hướng đến trở thành nền tảng học tập trực tuyến hàng đầu tại
                         Việt Nam, nơi mọi người đều có thể tìm thấy khóa học phù hợp với nhu cầu và khả năng tài chính.
                    </p>
                    <p class="text-gray-600">Đến năm 2025, chúng tôi mục tiêu phục vụ 1 triệu học viên và cung cấp hơn
                         5,000 khóa học đa dạng across various fields.</p>
               </div>
          </div>
     </div>
</section>

<!-- Team Section -->
<!-- <section class="py-16 bg-white">
     <div class="container mx-auto px-4">
          <div class="text-center mb-12">
               <h2 class="text-3xl font-bold mb-4">Đội ngũ của chúng tôi</h2>
               <p class="text-gray-600 max-w-2xl mx-auto">Đội ngũ chuyên gia và giảng viên giàu kinh nghiệm, tâm huyết
                    với sứ mệnh mang lại kiến thức chất lượng cho cộng đồng.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
               <div class="team-card bg-white rounded-2xl p-6 text-center shadow-md">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-user text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Nguyễn Văn A</h3>
                    <p class="text-indigo-600 font-semibold mb-3">Founder & CEO</p>
                    <p class="text-gray-600 text-sm">Với 10 năm kinh nghiệm trong lĩnh vực giáo dục trực tuyến và công
                         nghệ.</p>
               </div>

               <div class="team-card bg-white rounded-2xl p-6 text-center shadow-md">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-user text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Trần Thị B</h3>
                    <p class="text-indigo-600 font-semibold mb-3">CTO</p>
                    <p class="text-gray-600 text-sm">Chuyên gia công nghệ với 8 năm kinh nghiệm phát triển nền tảng học
                         tập.</p>
               </div>

               <div class="team-card bg-white rounded-2xl p-6 text-center shadow-md">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-user text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Lê Văn C</h3>
                    <p class="text-indigo-600 font-semibold mb-3">Head of Content</p>
                    <p class="text-gray-600 text-sm">Chịu trách nhiệm kiểm duyệt và phát triển nội dung khóa học chất
                         lượng.</p>
               </div>

               <div class="team-card bg-white rounded-2xl p-6 text-center shadow-md">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-user text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Phạm Thị D</h3>
                    <p class="text-indigo-600 font-semibold mb-3">Student Success Manager</p>
                    <p class="text-gray-600 text-sm">Đảm bảo trải nghiệm học tập tốt nhất cho mọi học viên.</p>
               </div>
          </div>
     </div>
</section> -->

<!-- Values -->
<section class="py-16 bg-gray-50">
     <div class="container mx-auto px-4">
          <div class="text-center mb-12">
               <h2 class="text-3xl font-bold mb-4">Giá trị cốt lõi</h2>
               <p class="text-gray-600 max-w-2xl mx-auto">Những nguyên tắc định hướng cho mọi hoạt động của chúng tôi
               </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
               <div class="bg-white rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-hand-holding-heart text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Vì cộng đồng</h3>
                    <p class="text-gray-600">Chúng tôi tin rằng giáo dục chất lượng nên được tiếp cận rộng rãi, không bị
                         giới hạn bởi khả năng tài chính.</p>
               </div>

               <div class="bg-white rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-gem text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Chất lượng hàng đầu</h3>
                    <p class="text-gray-600">Mọi khóa học đều trải qua quy trình kiểm duyệt nghiêm ngặt để đảm bảo chất
                         lượng nội dung tốt nhất.</p>
               </div>

               <div class="bg-white rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="fas fa-heart text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Tận tâm hỗ trợ</h3>
                    <p class="text-gray-600">Đội ngũ hỗ trợ luôn sẵn sàng giải đáp mọi thắc mắc và đồng hành cùng học
                         viên trên hành trình học tập.</p>
               </div>
          </div>
     </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
     <div class="container mx-auto px-4 text-center">
          <h2 class="text-3xl md:text-4xl font-bold mb-6">Sẵn sàng bắt đầu hành trình học tập?</h2>
          <p class="text-xl mb-8 max-w-2xl mx-auto">Tham gia cộng đồng 50,000+ học viên đang phát triển kỹ năng với các
               khóa học giá rẻ chất lượng cao.</p>
          <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
               <a href="courses.html"
                    class="bg-white text-indigo-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition duration-300">Khám
                    phá khóa học</a>
               <a href="#"
                    class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-md font-semibold hover:bg-white hover:text-indigo-600 transition duration-300">Liên
                    hệ với chúng tôi</a>
          </div>
     </div>
</section>
@endsection