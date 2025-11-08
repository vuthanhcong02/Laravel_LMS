@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<!-- Main Content -->
<main class="flex-1 flex items-center justify-center py-12 px-4">
     <div class="container max-w-md mx-auto">
          <div class="auth-container">
               <div class="p-8 md:p-12">
                    <div class="text-center mb-8">
                         <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                              <i class="fas fa-lock text-green-600 text-2xl"></i>
                         </div>
                         <h1 class="text-3xl font-bold text-gray-800 mb-2">Đặt lại mật khẩu</h1>
                         <p class="text-gray-600">Tạo mật khẩu mới cho tài khoản của bạn</p>
                    </div>

                    <!-- Reset Password Form -->
                    <form class="space-y-6" action="{{ route('password.store') }}" method="POST">
                         @csrf
                         <!-- Password Reset Token -->
                         <input type="hidden" name="token" value="{{ $request->route('token') }}">
                         <div>
                              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                   Địa chỉ email
                              </label>
                              <div class="relative">
                                   <input id="email" name="email" type="email" autocomplete="email" required readonly
                                        value="{{ old('email', $request->email) }}"
                                        class="appearance-none relative block w-full px-4 py-3 pl-11 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                        placeholder="Nhập địa chỉ email của bạn">
                                   <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                   </div>
                              </div>
                              @error('email')
                              <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                              @enderror
                         </div>

                         <div>
                              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới
                                   <span class="text-red-500">*</span>
                              </label>
                              <div class="input-group">
                                   <i class="input-icon fas fa-lock"></i>
                                   <input type="password" id="password" name="password" required
                                        class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Nhập mật khẩu mới">
                                   <i class="password-toggle fas fa-eye" id="toggle-password"></i>
                              </div>
                              <p class="text-xs text-gray-500 mt-1">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa,
                                   chữ thường và số</p>
                              @error('password')
                              <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                              @enderror
                         </div>
                         <div>
                              <label for="password_confirmation"
                                   class="block text-sm font-medium text-gray-700 mb-2">Xác
                                   nhận
                                   mật khẩu <span class="text-red-500">*</span>
                              </label>
                              <div class="input-group">
                                   <i class="input-icon fas fa-lock"></i>
                                   <input type="password" name="password_confirmation" required
                                        class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Nhập lại mật khẩu mới">
                                   <i class="password-toggle fas fa-eye" id="toggle-confirm-password"></i>
                              </div>
                              @error('password_confirmation')
                              <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                              @enderror
                         </div>

                         <button type="submit"
                              class="w-full btn-primary text-white py-4 rounded-lg font-semibold text-lg transition duration-300">
                              <i class="fas fa-save mr-2"></i>
                              Đặt lại mật khẩu
                         </button>
                    </form>

                    <div class="text-center mt-8">
                         <p class="text-gray-600">
                              Quay lại
                              <a href="{{ route('login') }}"
                                   class="text-indigo-600 font-semibold hover:text-indigo-500 transition duration-200">đăng
                                   nhập</a>
                         </p>
                    </div>
               </div>
          </div>
     </div>
</main>
@endsection