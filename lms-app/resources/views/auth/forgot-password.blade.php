@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="text-center">
                    <a href="{{ route('home') }}" class="text-3xl font-bold text-blue-600 flex items-center justify-center">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        LMS
                    </a>
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Quên mật khẩu
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Nhập email của bạn để nhận liên kết đặt lại mật khẩu
                    </p>
                </div>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-medium">Có lỗi xảy ra:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Địa chỉ email
                        </label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                class="appearance-none relative block w-full px-4 py-3 pl-11 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                placeholder="Nhập địa chỉ email của bạn">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-paper-plane text-blue-500 group-hover:text-blue-400"></i>
                        </span>
                        Gửi liên kết đặt lại mật khẩu
                    </button>
                </div>

                <!-- Additional Links -->
                <div class="text-center space-y-3">
                    <p class="text-sm text-gray-600">
                        Nhớ mật khẩu?
                        <a href="{{ route('login') }}"
                            class="font-medium text-blue-600 hover:text-blue-500 transition duration-300">
                            Quay lại đăng nhập
                        </a>
                    </p>
                    <p class="text-sm text-gray-600">
                        Chưa có tài khoản?
                        <a href="{{ route('register') }}"
                            class="font-medium text-blue-600 hover:text-blue-500 transition duration-300">
                            Đăng ký ngay
                        </a>
                    </p>
                </div>
            </form>

            <!-- Help Section -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800 mb-1">Hướng dẫn</h4>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Kiểm tra hộp thư đến và thư rác (spam)</li>
                            <li>• Liên kết đặt lại mật khẩu có hiệu lực trong 60 phút</li>
                            <li>• Nếu không nhận được email, vui lòng thử lại sau ít phút</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
