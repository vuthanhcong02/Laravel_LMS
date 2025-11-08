@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<!-- Main Content -->
<main class="flex-1 flex items-center justify-center py-12 px-4">
     <div class="container max-w-6xl mx-auto">
          <div class="login-container">
               <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- Left Side - Login Form -->
                    <div class="p-8 md:p-12 lg:p-16">
                         <div class="max-w-md mx-auto">
                              <div class="text-center mb-8">
                                   <h1 class="text-3xl font-bold text-gray-800 mb-2">Chào mừng trở lại!</h1>
                                   <p class="text-gray-600">Đăng nhập để tiếp tục học tập</p>
                              </div>

                              <!-- Social Login Buttons -->
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
                                        <span class="px-2 bg-white text-gray-500">hoặc đăng nhập với email</span>
                                   </div>
                              </div>

                              <!-- Login Form -->
                              <form id="login-form" class="space-y-6" method="POST">
                                   @csrf
                                   <div>
                                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Tên
                                             đăng nhập hoặc Email
                                             <span class="text-red-500">*</span>
                                        </label>
                                        <div class="input-group">
                                             <i class="input-icon fas fa-user"></i>
                                             <input type="text" id="username" name="username" required
                                                  class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                  placeholder="Nhập tên đăng nhập hoặc email">
                                        </div>
                                   </div>

                                   <div>
                                        <div class="flex justify-between items-center mb-2">
                                             <label for="password" class="block text-sm font-medium text-gray-700">Mật
                                                  khẩu
                                                  <span class="text-red-500">*</span>
                                             </label>
                                             <a href="{{  route('password.request') }}"
                                                  class="text-sm text-indigo-600 hover:text-indigo-500 transition duration-200">Quên
                                                  mật khẩu?</a>
                                        </div>
                                        <div class="input-group">
                                             <i class="input-icon fas fa-lock"></i>
                                             <input type="password" id="password" name="password" required
                                                  class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                  placeholder="Nhập mật khẩu">
                                             <i class="password-toggle fas fa-eye" id="toggle-password"></i>
                                        </div>
                                   </div>

                                   <div class="flex items-center">
                                        <input type="checkbox" id="remember" name="remember"
                                             class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ghi nhớ đăng
                                             nhập</label>
                                   </div>

                                   <button type="submit"
                                        class="w-full btn-primary text-white py-4 rounded-lg font-semibold text-lg transition duration-300">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Đăng nhập
                                   </button>
                                   <!-- Success Message -->
                                   <div id="success-message"
                                        class="hidden bg-green-50 border border-green-200 rounded-lg p-4 text-green-700 text-center">
                                        <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
                                        <p class="font-semibold">Đăng nhập thành công!</p>
                                        <p class="text-sm mt-1">Chào mừng bạn đến với Khóa Học Giá Rẻ</p>
                                   </div>

                                   <!-- Error Message -->
                                   <div id="error-message"
                                        class="hidden bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 text-center">
                                        <i class="fas fa-exclamation-circle text-red-500 text-xl mb-2"></i>
                                        <p class="font-semibold" id="error-text">Tên đăng nhập hoặc mật khẩu không đúng
                                        </p>
                                   </div>
                              </form>

                              <div class="text-center mt-8">
                                   <p class="text-gray-600">
                                        Chưa có tài khoản?
                                        <a href="{{ route('register') }}"
                                             class="text-indigo-600 font-semibold hover:text-indigo-500 transition duration-200">Đăng
                                             ký ngay</a>
                                   </p>
                              </div>
                         </div>
                    </div>

                    <!-- Right Side - Banner -->
                    <div class="login-sidebar p-8 md:p-12 lg:p-16 flex items-center justify-center">
                         <div class="text-center relative z-10">
                              <div class="floating mb-8">
                                   <i class="fas fa-graduation-cap text-white text-6xl opacity-20"></i>
                              </div>
                              <h2 class="text-3xl font-bold mb-6">Bắt đầu hành trình học tập của bạn</h2>
                              <p class="text-lg opacity-90 mb-8 max-w-md mx-auto">
                                   Truy cập vào hàng trăm khóa học chất lượng cao với mức giá phải chăng. Học mọi lúc,
                                   mọi nơi.
                              </p>
                              <div class="space-y-4 text-left max-w-xs mx-auto">
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">500+ khóa học đa dạng</span>
                                   </div>
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">Học trọn đời không giới hạn</span>
                                   </div>
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">Hỗ trợ 24/7 từ giảng viên</span>
                                   </div>
                                   <div class="flex items-center">
                                        <i class="fas fa-check-circle text-white mr-3 opacity-90"></i>
                                        <span class="opacity-90">Giá cả chỉ bằng 1/3 thị trường</span>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</main>
@endsection

@vite(['resources/js/login.js'])