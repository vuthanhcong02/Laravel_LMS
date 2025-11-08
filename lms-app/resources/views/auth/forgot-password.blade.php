@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<!-- Main Content -->
<main class="flex-1 flex items-center justify-center py-12 px-4">
     <div class="container max-w-md mx-auto">
          <div class="auth-container">
               <div class="p-8 md:p-12">
                    <div class="text-center mb-8">
                         <div
                              class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                              <i class="fas fa-key text-indigo-600 text-2xl"></i>
                         </div>
                         <h1 class="text-3xl font-bold text-gray-800 mb-2">Quên mật khẩu?</h1>
                         <p class="text-gray-600">Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
                    </div>

                    <!-- Forgot Password Form -->
                    <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                         @csrf
                         <div>
                              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                   Email đăng ký <span class="text-red-500">*</span>
                              </label>

                              <div class="input-group">
                                   <i class="input-icon fas fa-envelope"></i>
                                   <input type="email" id="email" name="email" required
                                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Nhập email của bạn">
                              </div>

                              @error('email')
                              <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                              @enderror
                         </div>

                         <button type="submit"
                              class="w-full btn-primary text-white py-4 rounded-lg font-semibold text-lg transition duration-300">
                              <i class="fas fa-paper-plane mr-2"></i>
                              Gửi liên kết đặt lại
                         </button>

                         <!-- Success Message -->
                         @if (session('status'))
                         <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-700 text-center">
                              <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
                              <p class="font-semibold">Liên kết đặt lại đã được gửi!</p>
                              <p class="text-sm mt-1">Vui lòng kiểm tra email của bạn</p>
                         </div>
                         @endif
                    </form>

                    <div class="text-center mt-8">
                         <p class="text-gray-600">
                              Nhớ mật khẩu?
                              <a href="{{ route('login') }}"
                                   class="text-indigo-600 font-semibold hover:text-indigo-500 transition duration-200">Quay
                                   lại đăng nhập</a>
                         </p>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                         <div class="flex items-start">
                              <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                              <div class="text-sm text-blue-700">
                                   <p class="font-semibold">Không nhận được email?</p>
                                   <p class="mt-1">• Kiểm tra thư mục spam</p>
                                   <p>• Đảm bảo bạn nhập đúng email đăng ký</p>
                                   <p>• Liên hệ <a href="{{ route('contact') }}" class="underline">hỗ trợ</a> nếu cần
                                        trợ giúp</p>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</main>
@endsection