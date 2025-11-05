@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<!-- Main Content -->
<main class="flex-1 flex items-center justify-center py-12 px-4">
     <div class="container max-w-6xl mx-auto">
          <div class="register-container">
               <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- Left Side - Register Form -->
                    <div class="p-8 md:p-12 lg:p-16">
                         <div class="max-w-md mx-auto">
                              <div class="text-center mb-8">
                                   <h1 class="text-3xl font-bold text-gray-800 mb-2">Bắt đầu hành trình</h1>
                                   <p class="text-gray-600">Tạo tài khoản để khám phá thế giới tri thức</p>
                              </div>

                              <!-- Step Indicator -->
                              <div class="step-indicator">
                                   <div class="step active" data-step="1"></div>
                                   <div class="step" data-step="2"></div>
                                   <div class="step" data-step="3"></div>
                              </div>

                              <!-- Social Register Buttons -->
                              <div class="grid grid-cols-2 gap-4 mb-8">
                                   <button
                                        class="social-btn google-btn bg-white py-3 rounded-lg font-semibold flex items-center justify-center">
                                        <i class="fab fa-google text-red-500 mr-2"></i>
                                        Google
                                   </button>
                                   <button
                                        class="social-btn facebook-btn bg-white py-3 rounded-lg font-semibold flex items-center justify-center">
                                        <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                        Facebook
                                   </button>
                              </div>

                              <div class="relative mb-8">
                                   <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                   </div>
                                   <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white text-gray-500">hoặc đăng ký với email</span>
                                   </div>
                              </div>

                              <!-- Register Form -->
                              <form id="register-form" class="space-y-6">
                                   <!-- Step 1: Basic Info -->
                                   <div class="form-step active" data-step="1">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                             <div>
                                                  <label for="firstName"
                                                       class="block text-sm font-medium text-gray-700 mb-2">Họ *</label>
                                                  <div class="input-group">
                                                       <i class="input-icon fas fa-user"></i>
                                                       <input type="text" id="firstName" name="firstName" required
                                                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                            placeholder="Nhập họ của bạn">
                                                  </div>
                                             </div>
                                             <div>
                                                  <label for="lastName"
                                                       class="block text-sm font-medium text-gray-700 mb-2">Tên
                                                       *</label>
                                                  <div class="input-group">
                                                       <i class="input-icon fas fa-user"></i>
                                                       <input type="text" id="lastName" name="lastName" required
                                                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                            placeholder="Nhập tên của bạn">
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="mt-6">
                                             <label for="email"
                                                  class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                             <div class="input-group">
                                                  <i class="input-icon fas fa-envelope"></i>
                                                  <input type="email" id="email" name="email" required
                                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                       placeholder="Nhập email của bạn">
                                             </div>
                                        </div>

                                        <div class="mt-6">
                                             <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số
                                                  điện thoại</label>
                                             <div class="input-group">
                                                  <i class="input-icon fas fa-phone"></i>
                                                  <input type="tel" id="phone" name="phone"
                                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                       placeholder="Nhập số điện thoại">
                                             </div>
                                        </div>

                                        <button type="button"
                                             class="w-full mt-6 btn-primary text-white py-4 rounded-lg font-semibold text-lg transition duration-300 next-step">
                                             Tiếp theo
                                        </button>
                                   </div>

                                   <!-- Step 2: Account Details -->
                                   <div class="form-step hidden" data-step="2">
                                        <div>
                                             <label for="username"
                                                  class="block text-sm font-medium text-gray-700 mb-2">Tên đăng nhập
                                                  *</label>
                                             <div class="input-group">
                                                  <i class="input-icon fas fa-user-circle"></i>
                                                  <input type="text" id="username" name="username" required
                                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                       placeholder="Chọn tên đăng nhập">
                                             </div>
                                             <p class="text-xs text-gray-500 mt-1">Tên đăng nhập phải có ít nhất 4 ký tự
                                             </p>
                                        </div>

                                        <div class="mt-6">
                                             <label for="password"
                                                  class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu
                                                  *</label>
                                             <div class="input-group">
                                                  <i class="input-icon fas fa-lock"></i>
                                                  <input type="password" id="password" name="password" required
                                                       class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                       placeholder="Tạo mật khẩu">
                                                  <i class="password-toggle fas fa-eye" id="toggle-password"></i>
                                             </div>
                                             <div id="password-strength" class="password-strength strength-weak"></div>
                                             <p class="text-xs text-gray-500 mt-1">Mật khẩu phải có ít nhất 8 ký tự, bao
                                                  gồm chữ hoa, chữ thường và số</p>
                                        </div>

                                        <div class="mt-6">
                                             <label for="confirmPassword"
                                                  class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu
                                                  *</label>
                                             <div class="input-group">
                                                  <i class="input-icon fas fa-lock"></i>
                                                  <input type="password" id="confirmPassword" name="confirmPassword"
                                                       required
                                                       class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                       placeholder="Nhập lại mật khẩu">
                                                  <i class="password-toggle fas fa-eye"
                                                       id="toggle-confirm-password"></i>
                                             </div>
                                        </div>

                                        <div class="flex space-x-4 mt-6">
                                             <button type="button"
                                                  class="flex-1 border border-gray-300 text-gray-700 py-4 rounded-lg font-semibold hover:bg-gray-50 transition duration-300 prev-step">
                                                  Quay lại
                                             </button>
                                             <button type="button"
                                                  class="flex-1 btn-primary text-white py-4 rounded-lg font-semibold text-lg transition duration-300 next-step">
                                                  Tiếp theo
                                             </button>
                                        </div>
                                   </div>

                                   <!-- Step 3: Preferences & Terms -->
                                   <div class="form-step hidden" data-step="3">
                                        <div>
                                             <label class="block text-sm font-medium text-gray-700 mb-4">Sở thích học
                                                  tập (tùy chọn)</label>
                                             <div class="grid grid-cols-2 gap-3">
                                                  <label
                                                       class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                                       <input type="checkbox" name="interests" value="technology"
                                                            class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                                       <span>Công nghệ</span>
                                                  </label>
                                                  <label
                                                       class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                                       <input type="checkbox" name="interests" value="business"
                                                            class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                                       <span>Kinh doanh</span>
                                                  </label>
                                                  <label
                                                       class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                                       <input type="checkbox" name="interests" value="design"
                                                            class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                                       <span>Thiết kế</span>
                                                  </label>
                                                  <label
                                                       class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                                       <input type="checkbox" name="interests" value="creative"
                                                            class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                                       <span>Sáng tạo</span>
                                                  </label>
                                             </div>
                                        </div>

                                        <div class="mt-6">
                                             <label class="flex items-start">
                                                  <input type="checkbox" id="newsletter" name="newsletter"
                                                       class="mt-1 mr-3 text-indigo-600 focus:ring-indigo-500">
                                                  <span class="text-sm text-gray-700">Tôi muốn nhận thông tin về khóa
                                                       học mới và chương trình khuyến mãi qua email</span>
                                             </label>
                                        </div>

                                        <div class="mt-6">
                                             <label class="flex items-start">
                                                  <input type="checkbox" id="terms" name="terms" required
                                                       class="mt-1 mr-3 text-indigo-600 focus:ring-indigo-500">
                                                  <span class="text-sm text-gray-700">
                                                       Tôi đồng ý với
                                                       <a href="#" class="text-indigo-600 hover:text-indigo-500">Điều
                                                            khoản dịch vụ</a>
                                                       và
                                                       <a href="#" class="text-indigo-600 hover:text-indigo-500">Chính
                                                            sách bảo mật</a>
                                                  </span>
                                             </label>
                                        </div>

                                        <div class="flex space-x-4 mt-6">
                                             <button type="button"
                                                  class="flex-1 border border-gray-300 text-gray-700 py-4 rounded-lg font-semibold hover:bg-gray-50 transition duration-300 prev-step">
                                                  Quay lại
                                             </button>
                                             <button type="submit"
                                                  class="flex-1 btn-primary text-white py-4 rounded-lg font-semibold text-lg transition duration-300">
                                                  <i class="fas fa-user-plus mr-2"></i>
                                                  Đăng ký
                                             </button>
                                        </div>
                                   </div>

                                   <!-- Success Message -->
                                   <div id="success-message"
                                        class="hidden bg-green-50 border border-green-200 rounded-lg p-4 text-green-700 text-center">
                                        <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
                                        <p class="font-semibold">Đăng ký thành công!</p>
                                        <p class="text-sm mt-1">Chào mừng bạn đến với Khóa Học Giá Rẻ</p>
                                   </div>

                                   <!-- Error Message -->
                                   <div id="error-message"
                                        class="hidden bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 text-center">
                                        <i class="fas fa-exclamation-circle text-red-500 text-xl mb-2"></i>
                                        <p class="font-semibold" id="error-text">Có lỗi xảy ra khi đăng ký</p>
                                   </div>
                              </form>

                              <div class="text-center mt-8">
                                   <p class="text-gray-600">
                                        Đã có tài khoản?
                                        <a href="login.html"
                                             class="text-indigo-600 font-semibold hover:text-indigo-500 transition duration-200">Đăng
                                             nhập ngay</a>
                                   </p>
                              </div>
                         </div>
                    </div>

                    <!-- Right Side - Banner -->
                    <div class="register-sidebar p-8 md:p-12 lg:p-16 flex items-center justify-center">
                         <div class="text-center relative z-10">
                              <div class="floating mb-8">
                                   <i class="fas fa-rocket text-white text-6xl opacity-20"></i>
                              </div>
                              <h2 class="text-3xl font-bold mb-6">Tham gia cộng đồng học tập</h2>
                              <p class="text-lg opacity-90 mb-8 max-w-md mx-auto">
                                   Kết nối với hàng ngàn học viên, khám phá tri thức mới và phát triển kỹ năng cùng
                                   chúng tôi.
                              </p>
                              <div class="space-y-4 text-left max-w-xs mx-auto">
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">Truy cập không giới hạn</span>
                                   </div>
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">Học theo tốc độ của riêng bạn</span>
                                   </div>
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">Cộng đồng hỗ trợ nhiệt tình</span>
                                   </div>
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">Cập nhật khóa học mới liên tục</span>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</main>
@endsection

@vite(['resources/js/register.js'])