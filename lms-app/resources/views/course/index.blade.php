@extends('layouts.app')
@section('title', 'Danh Sách Khóa Học - Khóa Học Giá Rẻ')
@section('content')
<!-- Breadcrumb Component -->
<x-breadcrumb />
<!-- Page Content -->
<div class="container mx-auto px-4 py-8">
     <div class="flex flex-col lg:flex-row gap-8">
          <!-- Sidebar Filters -->
          <div class="lg:w-1/4">
               <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h3 class="text-lg font-bold mb-4">Bộ lọc</h3>

                    <!-- Category Filter -->
                    <div class="mb-6">
                         <h4 class="font-semibold mb-3">Danh mục</h4>
                         <div class="space-y-2">
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Công nghệ</span>
                              </label>
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Kinh doanh</span>
                              </label>
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Thiết kế</span>
                              </label>
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Sáng tạo</span>
                              </label>
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Phát triển cá nhân</span>
                              </label>
                         </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-6">
                         <h4 class="font-semibold mb-3">Mức giá</h4>
                         <div class="space-y-2">
                              <button
                                   class="filter-btn w-full text-left py-2 px-3 rounded-md hover:bg-gray-100 transition duration-200 active-filter">Tất
                                   cả</button>
                              <button
                                   class="filter-btn w-full text-left py-2 px-3 rounded-md hover:bg-gray-100 transition duration-200">Dưới
                                   200.000đ</button>
                              <button
                                   class="filter-btn w-full text-left py-2 px-3 rounded-md hover:bg-gray-100 transition duration-200">200.000đ
                                   - 500.000đ</button>
                              <button
                                   class="filter-btn w-full text-left py-2 px-3 rounded-md hover:bg-gray-100 transition duration-200">Trên
                                   500.000đ</button>
                         </div>
                    </div>

                    <!-- Level Filter -->
                    <div class="mb-6">
                         <h4 class="font-semibold mb-3">Cấp độ</h4>
                         <div class="space-y-2">
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Cơ bản</span>
                              </label>
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Trung cấp</span>
                              </label>
                              <label class="flex items-center">
                                   <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                   <span class="ml-2 text-gray-700">Nâng cao</span>
                              </label>
                         </div>
                    </div>

                    <button
                         class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition duration-300">Áp
                         dụng bộ lọc</button>
               </div>
          </div>

          <!-- Course List -->
          <div class="lg:w-3/4">
               <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold mb-4 md:mb-0">Tất cả khóa học</h1>

                    <div class="flex items-center space-x-4">
                         <div class="flex items-center">
                              <span class="mr-2 text-gray-700">Sắp xếp:</span>
                              <select
                                   class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                   <option>Phổ biến nhất</option>
                                   <option>Đánh giá cao nhất</option>
                                   <option>Giá thấp đến cao</option>
                                   <option>Giá cao đến thấp</option>
                                   <option>Mới nhất</option>
                              </select>
                         </div>
                    </div>
               </div>

               <p class="text-gray-600 mb-8">Hiển thị <span class="font-semibold">12</span> khóa học trong tổng số <span
                         class="font-semibold">56</span> khóa học</p>

               <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                    <!-- Course Card 1 -->
                    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden">
                         <div class="relative overflow-hidden">
                              <div
                                   class="h-40 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                   <i class="fas fa-laptop-code text-white text-4xl"></i>
                              </div>
                              <div class="price-tag">Giảm 20%</div>
                         </div>
                         <div class="p-5">
                              <div class="flex justify-between items-start mb-2">
                                   <h3 class="text-lg font-bold">Lập trình Web cơ bản</h3>
                                   <span class="flex items-center text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <span class="ml-1">4.9</span>
                                   </span>
                              </div>
                              <p class="text-gray-600 text-sm mb-4">Học HTML, CSS, JavaScript từ cơ bản đến nâng cao.
                                   Xây dựng website đầu tiên của bạn.</p>
                              <div class="flex justify-between items-center">
                                   <div>
                                        <span class="text-xl font-bold text-indigo-600">299.000đ</span>
                                        <span class="text-gray-400 line-through ml-2">375.000đ</span>
                                   </div>
                                   <a href="{{route('course.detail', ['id' => 1])}}"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 text-sm">Xem
                                        chi tiết</a>
                              </div>
                         </div>
                    </div>

                    <!-- Course Card 2 -->
                    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden">
                         <div class="relative overflow-hidden">
                              <div
                                   class="h-40 bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center">
                                   <i class="fas fa-chart-line text-white text-4xl"></i>
                              </div>
                         </div>
                         <div class="p-5">
                              <div class="flex justify-between items-start mb-2">
                                   <h3 class="text-lg font-bold">Digital Marketing</h3>
                                   <span class="flex items-center text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <span class="ml-1">4.7</span>
                                   </span>
                              </div>
                              <p class="text-gray-600 text-sm mb-4">Chiến lược marketing hiệu quả trên các nền tảng số.
                                   Tăng trưởng doanh thu bền vững.</p>
                              <div class="flex justify-between items-center">
                                   <div>
                                        <span class="text-xl font-bold text-indigo-600">399.000đ</span>
                                   </div>
                                   <a href="course-detail.html"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 text-sm">Xem
                                        chi tiết</a>
                              </div>
                         </div>
                    </div>

                    <!-- Course Card 3 -->
                    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden">
                         <div class="relative overflow-hidden">
                              <div
                                   class="h-40 bg-gradient-to-r from-yellow-500 to-orange-600 flex items-center justify-center">
                                   <i class="fas fa-palette text-white text-4xl"></i>
                              </div>
                              <div class="price-tag">Giảm 15%</div>
                         </div>
                         <div class="p-5">
                              <div class="flex justify-between items-start mb-2">
                                   <h3 class="text-lg font-bold">Thiết kế đồ họa</h3>
                                   <span class="flex items-center text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <span class="ml-1">4.8</span>
                                   </span>
                              </div>
                              <p class="text-gray-600 text-sm mb-4">Làm chủ các công cụ thiết kế như Photoshop,
                                   Illustrator. Tạo ra các sản phẩm sáng tạo.</p>
                              <div class="flex justify-between items-center">
                                   <div>
                                        <span class="text-xl font-bold text-indigo-600">349.000đ</span>
                                        <span class="text-gray-400 line-through ml-2">410.000đ</span>
                                   </div>
                                   <a href="course-detail.html"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 text-sm">Xem
                                        chi tiết</a>
                              </div>
                         </div>
                    </div>

                    <!-- Course Card 4 -->
                    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden">
                         <div class="relative overflow-hidden">
                              <div
                                   class="h-40 bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center">
                                   <i class="fas fa-mobile-alt text-white text-4xl"></i>
                              </div>
                         </div>
                         <div class="p-5">
                              <div class="flex justify-between items-start mb-2">
                                   <h3 class="text-lg font-bold">Lập trình di động</h3>
                                   <span class="flex items-center text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <span class="ml-1">4.6</span>
                                   </span>
                              </div>
                              <p class="text-gray-600 text-sm mb-4">Xây dựng ứng dụng di động với React Native. Phát
                                   triển app cho cả iOS và Android.</p>
                              <div class="flex justify-between items-center">
                                   <div>
                                        <span class="text-xl font-bold text-indigo-600">449.000đ</span>
                                   </div>
                                   <a href="course-detail.html"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 text-sm">Xem
                                        chi tiết</a>
                              </div>
                         </div>
                    </div>

                    <!-- Course Card 5 -->
                    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden">
                         <div class="relative overflow-hidden">
                              <div
                                   class="h-40 bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center">
                                   <i class="fas fa-database text-white text-4xl"></i>
                              </div>
                              <div class="price-tag">Mới</div>
                         </div>
                         <div class="p-5">
                              <div class="flex justify-between items-start mb-2">
                                   <h3 class="text-lg font-bold">Khoa học dữ liệu</h3>
                                   <span class="flex items-center text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <span class="ml-1">4.9</span>
                                   </span>
                              </div>
                              <p class="text-gray-600 text-sm mb-4">Phân tích dữ liệu với Python, Machine Learning cơ
                                   bản. Khám phá insights từ dữ liệu.</p>
                              <div class="flex justify-between items-center">
                                   <div>
                                        <span class="text-xl font-bold text-indigo-600">499.000đ</span>
                                   </div>
                                   <a href="course-detail.html"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 text-sm">Xem
                                        chi tiết</a>
                              </div>
                         </div>
                    </div>

                    <!-- Course Card 6 -->
                    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden">
                         <div class="relative overflow-hidden">
                              <div
                                   class="h-40 bg-gradient-to-r from-red-500 to-pink-600 flex items-center justify-center">
                                   <i class="fas fa-camera text-white text-4xl"></i>
                              </div>
                         </div>
                         <div class="p-5">
                              <div class="flex justify-between items-start mb-2">
                                   <h3 class="text-lg font-bold">Nhiếp ảnh chuyên nghiệp</h3>
                                   <span class="flex items-center text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <span class="ml-1">4.7</span>
                                   </span>
                              </div>
                              <p class="text-gray-600 text-sm mb-4">Kỹ thuật chụp ảnh, chỉnh sửa với Lightroom và
                                   Photoshop. Tạo ra những bức ảnh ấn tượng.</p>
                              <div class="flex justify-between items-center">
                                   <div>
                                        <span class="text-xl font-bold text-indigo-600">329.000đ</span>
                                   </div>
                                   <a href="course-detail.html"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 text-sm">Xem
                                        chi tiết</a>
                              </div>
                         </div>
                    </div>
               </div>

               <!-- Pagination -->
               <div class="flex justify-center mt-12">
                    <nav class="inline-flex rounded-md shadow">
                         <a href="#"
                              class="py-2 px-4 border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 rounded-l-md">
                              <i class="fas fa-chevron-left"></i>
                         </a>
                         <a href="#"
                              class="py-2 px-4 border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">1</a>
                         <a href="#" class="py-2 px-4 border border-gray-300 bg-indigo-600 text-white">2</a>
                         <a href="#"
                              class="py-2 px-4 border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">3</a>
                         <a href="#"
                              class="py-2 px-4 border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 rounded-r-md">
                              <i class="fas fa-chevron-right"></i>
                         </a>
                    </nav>
               </div>
          </div>
     </div>
</div>
@endsection