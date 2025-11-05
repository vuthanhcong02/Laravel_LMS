@extends('layouts.app')
@section('title', 'Courses')
@section('content')
<x-breadcrumb />
<!-- Course Detail -->
<div class="container mx-auto px-4 py-8">
     <div class="flex flex-col lg:flex-row gap-8">
          <!-- Main Content -->
          <div class="lg:w-2/3">
               <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                    <div class="h-64 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                         <i class="fas fa-laptop-code text-white text-6xl"></i>
                    </div>
                    <div class="p-6">
                         <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                              <div>
                                   <h1 class="text-2xl font-bold mb-2">Lập trình Web cơ bản</h1>
                                   <p class="text-gray-600 mb-4">Học HTML, CSS, JavaScript từ cơ bản đến nâng cao. Xây
                                        dựng website đầu tiên của bạn chỉ sau 4 tuần.</p>
                              </div>
                              <div class="flex items-center space-x-2 bg-yellow-50 px-3 py-2 rounded-md mb-4 md:mb-0">
                                   <i class="fas fa-star text-yellow-500"></i>
                                   <span class="font-semibold">4.9</span>
                                   <span class="text-gray-600">(1,254 đánh giá)</span>
                              </div>
                         </div>

                         <div class="flex flex-wrap gap-4 mb-6">
                              <div class="flex items-center">
                                   <i class="fas fa-user-graduate text-indigo-600 mr-2"></i>
                                   <span class="text-gray-700">2,548 học viên</span>
                              </div>
                              <div class="flex items-center">
                                   <i class="fas fa-clock text-indigo-600 mr-2"></i>
                                   <span class="text-gray-700">24 giờ học</span>
                              </div>
                              <div class="flex items-center">
                                   <i class="fas fa-signal text-indigo-600 mr-2"></i>
                                   <span class="text-gray-700">Cơ bản</span>
                              </div>
                              <div class="flex items-center">
                                   <i class="fas fa-infinity text-indigo-600 mr-2"></i>
                                   <span class="text-gray-700">Truy cập trọn đời</span>
                              </div>
                         </div>

                         <div class="mb-6">
                              <h3 class="text-lg font-semibold mb-3">Bạn sẽ học được gì?</h3>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                   <div class="flex items-start">
                                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                        <span>Xây dựng website với HTML, CSS</span>
                                   </div>
                                   <div class="flex items-start">
                                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                        <span>Tạo hiệu ứng với JavaScript</span>
                                   </div>
                                   <div class="flex items-start">
                                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                        <span>Thiết kế responsive website</span>
                                   </div>
                                   <div class="flex items-start">
                                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                        <span>Triển khai website lên hosting</span>
                                   </div>
                                   <div class="flex items-start">
                                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                        <span>Tối ưu hóa tốc độ website</span>
                                   </div>
                                   <div class="flex items-start">
                                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                        <span>Làm việc với Git & GitHub</span>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <!-- Course Content -->
               <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-bold mb-4">Nội dung khóa học</h2>
                    <p class="text-gray-600 mb-6">Khóa học bao gồm 8 chương với 48 bài học chi tiết, từ cơ bản đến nâng
                         cao.</p>

                    <div class="space-y-4">
                         <!-- Chapter 1 -->
                         <div class="accordion border rounded-lg overflow-hidden">
                              <button
                                   class="accordion-header w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition duration-200">
                                   <div class="flex items-center">
                                        <i class="fas fa-folder-open text-indigo-600 mr-3"></i>
                                        <span class="font-semibold text-left">Chương 1: Giới thiệu về Web
                                             Development</span>
                                   </div>
                                   <i class="fas fa-chevron-down accordion-icon transition-transform duration-300"></i>
                              </button>
                              <div class="accordion-content">
                                   <div class="p-4 border-t">
                                        <div class="flex items-center py-2 px-3 hover:bg-gray-50 rounded-md">
                                             <i class="fas fa-play-circle text-indigo-600 mr-3"></i>
                                             <span>Bài 1: Web Development là gì?</span>
                                             <span class="ml-auto text-gray-500">15 phút</span>
                                        </div>
                                        <div class="flex items-center py-2 px-3 hover:bg-gray-50 rounded-md">
                                             <i class="fas fa-play-circle text-indigo-600 mr-3"></i>
                                             <span>Bài 2: Các công cụ cần thiết</span>
                                             <span class="ml-auto text-gray-500">20 phút</span>
                                        </div>
                                        <div class="flex items-center py-2 px-3 hover:bg-gray-50 rounded-md">
                                             <i class="fas fa-play-circle text-indigo-600 mr-3"></i>
                                             <span>Bài 3: Cấu trúc một website</span>
                                             <span class="ml-auto text-gray-500">25 phút</span>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Chapter 2 -->
                         <div class="accordion border rounded-lg overflow-hidden">
                              <button
                                   class="accordion-header w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition duration-200">
                                   <div class="flex items-center">
                                        <i class="fas fa-folder-open text-indigo-600 mr-3"></i>
                                        <span class="font-semibold text-left">Chương 2: HTML cơ bản đến nâng cao</span>
                                   </div>
                                   <i class="fas fa-chevron-down accordion-icon transition-transform duration-300"></i>
                              </button>
                              <div class="accordion-content">
                                   <div class="p-4 border-t">
                                        <div class="flex items-center py-2 px-3 hover:bg-gray-50 rounded-md">
                                             <i class="fas fa-play-circle text-indigo-600 mr-3"></i>
                                             <span>Bài 4: Cấu trúc HTML cơ bản</span>
                                             <span class="ml-auto text-gray-500">18 phút</span>
                                        </div>
                                        <div class="flex items-center py-2 px-3 hover:bg-gray-50 rounded-md">
                                             <i class="fas fa-play-circle text-indigo-600 mr-3"></i>
                                             <span>Bài 5: Các thẻ HTML phổ biến</span>
                                             <span class="ml-auto text-gray-500">22 phút</span>
                                        </div>
                                        <div class="flex items-center py-2 px-3 hover:bg-gray-50 rounded-md">
                                             <i class="fas fa-play-circle text-indigo-600 mr-3"></i>
                                             <span>Bài 6: Form và Input</span>
                                             <span class="ml-auto text-gray-500">30 phút</span>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <!-- Instructor -->
               <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-bold mb-4">Giảng viên</h2>
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                         <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center">
                              <i class="fas fa-user text-indigo-600 text-3xl"></i>
                         </div>
                         <div>
                              <h3 class="text-lg font-bold mb-2">Nguyễn Văn A</h3>
                              <p class="text-gray-600 mb-3">Senior Web Developer với 8 năm kinh nghiệm. Đã tham gia phát
                                   triển hơn 50 dự án website cho các công ty trong và ngoài nước.</p>
                              <div class="flex items-center text-gray-600">
                                   <i class="fas fa-users mr-2"></i>
                                   <span class="mr-4">12,548 học viên</span>
                                   <i class="fas fa-play-circle mr-2"></i>
                                   <span>8 khóa học</span>
                              </div>
                         </div>
                    </div>
               </div>

               <!-- Reviews -->
               <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Đánh giá từ học viên</h2>

                    <div class="flex flex-col md:flex-row items-center mb-6 p-4 bg-gray-50 rounded-lg">
                         <div class="text-center md:text-left md:mr-6 mb-4 md:mb-0">
                              <div class="text-4xl font-bold text-indigo-600">4.9</div>
                              <div class="flex text-yellow-500 my-1">
                                   <i class="fas fa-star"></i>
                                   <i class="fas fa-star"></i>
                                   <i class="fas fa-star"></i>
                                   <i class="fas fa-star"></i>
                                   <i class="fas fa-star-half-alt"></i>
                              </div>
                              <div class="text-gray-600">1,254 đánh giá</div>
                         </div>
                         <div class="flex-1">
                              <div class="flex items-center mb-1">
                                   <span class="w-10 text-right mr-2 text-sm">5 <i
                                             class="fas fa-star text-yellow-500"></i></span>
                                   <div class="progress-bar flex-1 mr-2">
                                        <div class="progress-fill" style="width: 85%"></div>
                                   </div>
                                   <span class="text-sm text-gray-600">85%</span>
                              </div>
                              <div class="flex items-center mb-1">
                                   <span class="w-10 text-right mr-2 text-sm">4 <i
                                             class="fas fa-star text-yellow-500"></i></span>
                                   <div class="progress-bar flex-1 mr-2">
                                        <div class="progress-fill" style="width: 12%"></div>
                                   </div>
                                   <span class="text-sm text-gray-600">12%</span>
                              </div>
                              <div class="flex items-center mb-1">
                                   <span class="w-10 text-right mr-2 text-sm">3 <i
                                             class="fas fa-star text-yellow-500"></i></span>
                                   <div class="progress-bar flex-1 mr-2">
                                        <div class="progress-fill" style="width: 2%"></div>
                                   </div>
                                   <span class="text-sm text-gray-600">2%</span>
                              </div>
                              <div class="flex items-center">
                                   <span class="w-10 text-right mr-2 text-sm">2 <i
                                             class="fas fa-star text-yellow-500"></i></span>
                                   <div class="progress-bar flex-1 mr-2">
                                        <div class="progress-fill" style="width: 1%"></div>
                                   </div>
                                   <span class="text-sm text-gray-600">1%</span>
                              </div>
                         </div>
                    </div>

                    <!-- Review List -->
                    <div class="space-y-6">
                         <div class="border-b pb-6">
                              <div class="flex justify-between mb-2">
                                   <div class="font-semibold">Trần Thị B</div>
                                   <div class="flex text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                   </div>
                              </div>
                              <p class="text-gray-600 mb-2">Khóa học rất chi tiết và dễ hiểu. Tôi đã có thể xây dựng
                                   website đầu tiên chỉ sau 2 tuần học. Giảng viên giải thích rõ ràng và có nhiều ví dụ
                                   thực tế.</p>
                              <div class="text-sm text-gray-500">2 tuần trước</div>
                         </div>

                         <div class="border-b pb-6">
                              <div class="flex justify-between mb-2">
                                   <div class="font-semibold">Lê Văn C</div>
                                   <div class="flex text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                   </div>
                              </div>
                              <p class="text-gray-600 mb-2">Nội dung khóa học phong phú, từ cơ bản đến nâng cao. Tôi đặc
                                   biệt thích phần thực hành xây dựng project cuối khóa. Rất đáng đồng tiền!</p>
                              <div class="text-sm text-gray-500">1 tháng trước</div>
                         </div>
                    </div>

                    <button
                         class="w-full mt-6 border border-indigo-600 text-indigo-600 py-2 rounded-md hover:bg-indigo-50 transition duration-300">Xem
                         thêm đánh giá</button>
               </div>
          </div>

          <!-- Sidebar -->
          <div class="lg:w-1/3">
               <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <div class="text-center mb-6">
                         <div class="text-3xl font-bold text-indigo-600 mb-2">299.000đ</div>
                         <div class="text-gray-500 line-through mb-4">375.000đ</div>
                         <div class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-sm font-semibold inline-block">
                              Tiết kiệm 20%</div>
                    </div>

                    <div class="space-y-4 mb-6">
                         <button
                              class="add-to-cart w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition duration-300 font-semibold">
                              Thêm vào giỏ hàng
                         </button>
                         <button
                              class="w-full border border-indigo-600 text-indigo-600 py-3 rounded-md hover:bg-indigo-50 transition duration-300 font-semibold">
                              Mua ngay
                         </button>
                    </div>

                    <div class="text-center text-gray-500 text-sm mb-6">
                         <p>Đảm bảo hoàn tiền trong 30 ngày</p>
                    </div>

                    <div class="space-y-4">
                         <div class="flex justify-between">
                              <span class="text-gray-600">Thời lượng:</span>
                              <span class="font-semibold">24 giờ video</span>
                         </div>
                         <div class="flex justify-between">
                              <span class="text-gray-600">Bài học:</span>
                              <span class="font-semibold">48 bài</span>
                         </div>
                         <div class="flex justify-between">
                              <span class="text-gray-600">Cấp độ:</span>
                              <span class="font-semibold">Cơ bản</span>
                         </div>
                         <div class="flex justify-between">
                              <span class="text-gray-600">Truy cập:</span>
                              <span class="font-semibold">Trọn đời</span>
                         </div>
                         <div class="flex justify-between">
                              <span class="text-gray-600">Hỗ trợ:</span>
                              <span class="font-semibold">Có</span>
                         </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                         <h3 class="font-semibold mb-3">Khóa học này bao gồm:</h3>
                         <div class="space-y-2">
                              <div class="flex items-center">
                                   <i class="fas fa-play-circle text-indigo-600 mr-2"></i>
                                   <span>24 giờ video theo yêu cầu</span>
                              </div>
                              <div class="flex items-center">
                                   <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                   <span>15 tài nguyên tải xuống</span>
                              </div>
                              <div class="flex items-center">
                                   <i class="fas fa-infinity text-indigo-600 mr-2"></i>
                                   <span>Truy cập trọn đời</span>
                              </div>
                              <div class="flex items-center">
                                   <i class="fas fa-mobile-alt text-indigo-600 mr-2"></i>
                                   <span>Truy cập trên di động & TV</span>
                              </div>
                              <div class="flex items-center">
                                   <i class="fas fa-trophy text-indigo-600 mr-2"></i>
                                   <span>Chứng chỉ hoàn thành</span>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
@endsection