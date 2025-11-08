@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<x-breadcrumb />
<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-16">
     <div class="container mx-auto px-4 text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-6">Liên Hệ Với Chúng Tôi</h1>
          <p class="text-xl md:text-2xl max-w-3xl mx-auto">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Đừng ngần
               ngại liên hệ khi cần tư vấn hoặc giải đáp thắc mắc.</p>
     </div>
</section>

<!-- Contact Info -->
<section class="py-16 bg-white">
     <div class="container mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
               <!-- Contact Card 1 -->
               <div class="contact-card bg-white rounded-2xl p-8 text-center shadow-md border border-gray-100">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                         <i class="fas fa-map-marker-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Địa chỉ</h3>
                    <p class="text-gray-600 mb-2">123 Đường ABC</p>
                    <p class="text-gray-600">Quận XYZ, TP. Hồ Chí Minh</p>
               </div>

               <!-- Contact Card 2 -->
               <div class="contact-card bg-white rounded-2xl p-8 text-center shadow-md border border-gray-100">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                         <i class="fas fa-phone-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Điện thoại</h3>
                    <p class="text-gray-600 mb-2">Hotline: 1900 1234</p>
                    <p class="text-gray-600">Support: 028 3456 7890</p>
               </div>

               <!-- Contact Card 3 -->
               <div class="contact-card bg-white rounded-2xl p-8 text-center shadow-md border border-gray-100">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                         <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Email</h3>
                    <p class="text-gray-600 mb-2">support@khoahocgiare.com</p>
                    <p class="text-gray-600">info@khoahocgiare.com</p>
               </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
               <!-- Contact Form -->
               <div>
                    <h2 class="text-3xl font-bold mb-6">Gửi tin nhắn cho chúng tôi</h2>
                    <p class="text-gray-600 mb-8">Điền thông tin bên dưới và chúng tôi sẽ phản hồi trong thời gian sớm
                         nhất.</p>

                    <form id="contact-form" class="space-y-6">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                              <div>
                                   <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên
                                        *</label>
                                   <input type="text" id="name" name="name" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500">
                              </div>
                              <div>
                                   <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                        *</label>
                                   <input type="email" id="email" name="email" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500">
                              </div>
                         </div>

                         <div>
                              <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện
                                   thoại</label>
                              <input type="tel" id="phone" name="phone"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500">
                         </div>

                         <div>
                              <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Chủ đề *</label>
                              <select id="subject" name="subject" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                   <option value="">Chọn chủ đề</option>
                                   <option value="support">Hỗ trợ kỹ thuật</option>
                                   <option value="course">Tư vấn khóa học</option>
                                   <option value="payment">Vấn đề thanh toán</option>
                                   <option value="partnership">Hợp tác đối tác</option>
                                   <option value="other">Khác</option>
                              </select>
                         </div>

                         <div>
                              <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Nội dung
                                   *</label>
                              <textarea id="message" name="message" rows="6" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Hãy mô tả chi tiết vấn đề của bạn..."></textarea>
                         </div>

                         <button type="submit"
                              class="w-full btn-primary text-white py-4 rounded-lg font-semibold text-lg">
                              <i class="fas fa-paper-plane mr-2"></i>
                              Gửi tin nhắn
                         </button>

                         <!-- Success Message -->
                         <div id="success-message"
                              class="success-message bg-green-50 border border-green-200 rounded-lg p-4 text-green-700 text-center">
                              <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
                              <p class="font-semibold">Cảm ơn bạn! Tin nhắn đã được gửi thành công.</p>
                              <p class="text-sm mt-1">Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
                         </div>
                    </form>
               </div>

               <!-- Map & Additional Info -->
               <div>
                    <!-- Map -->
                    <div class="map-container mb-8">
                         <div
                              class="bg-gradient-to-br from-indigo-100 to-purple-100 h-80 flex items-center justify-center">
                              <div class="text-center">
                                   <i class="fas fa-map-marked-alt text-indigo-600 text-4xl mb-4"></i>
                                   <p class="text-gray-600 font-semibold">Bản đồ vị trí</p>
                                   <p class="text-gray-500 text-sm mt-2">123 Đường ABC, Quận XYZ, TP.HCM</p>
                              </div>
                         </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 mb-8">
                         <h3 class="text-xl font-bold mb-4 flex items-center">
                              <i class="fas fa-clock text-indigo-600 mr-3"></i>
                              Giờ làm việc
                         </h3>
                         <div class="space-y-3">
                              <div class="flex justify-between">
                                   <span class="text-gray-600">Thứ 2 - Thứ 6</span>
                                   <span class="font-semibold">8:00 - 18:00</span>
                              </div>
                              <div class="flex justify-between">
                                   <span class="text-gray-600">Thứ 7</span>
                                   <span class="font-semibold">8:00 - 12:00</span>
                              </div>
                              <div class="flex justify-between">
                                   <span class="text-gray-600">Chủ nhật</span>
                                   <span class="font-semibold text-red-500">Nghỉ</span>
                              </div>
                         </div>
                    </div>

                    <!-- FAQ Quick Links -->
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                         <h3 class="text-xl font-bold mb-4 flex items-center">
                              <i class="fas fa-question-circle text-indigo-600 mr-3"></i>
                              Câu hỏi thường gặp
                         </h3>
                         <div class="space-y-3">
                              <a href="#"
                                   class="block p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition duration-200">
                                   <div class="font-semibold text-gray-800">Làm thế nào để đăng ký khóa học?</div>
                              </a>
                              <a href="#"
                                   class="block p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition duration-200">
                                   <div class="font-semibold text-gray-800">Phương thức thanh toán nào được chấp nhận?
                                   </div>
                              </a>
                              <a href="#"
                                   class="block p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition duration-200">
                                   <div class="font-semibold text-gray-800">Tôi có thể học trên thiết bị di động không?
                                   </div>
                              </a>
                              <a href="#"
                                   class="block p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition duration-200">
                                   <div class="font-semibold text-gray-800">Chính sách hoàn tiền như thế nào?</div>
                              </a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
     <div class="container mx-auto px-4 text-center">
          <h2 class="text-3xl md:text-4xl font-bold mb-6">Cần hỗ trợ ngay lập tức?</h2>
          <p class="text-xl mb-8 max-w-2xl mx-auto">Đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giải đáp mọi thắc mắc
               24/7</p>
          <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
               <a href="tel:19001234"
                    class="bg-white text-indigo-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition duration-300">
                    <i class="fas fa-phone-alt mr-2"></i>
                    Gọi ngay: 1900 1234
               </a>
               <a href="mailto:support@khoahocgiare.com"
                    class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-md font-semibold hover:bg-white hover:text-indigo-600 transition duration-300">
                    <i class="fas fa-envelope mr-2"></i>
                    support@khoahocgiare.com
               </a>
          </div>
     </div>
</section>
@endsection
@vite(['resources/js/contact.js'])