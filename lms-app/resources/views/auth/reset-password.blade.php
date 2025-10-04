@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

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
                        Đặt lại mật khẩu
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Tạo mật khẩu mới cho tài khoản của bạn
                    </p>
                </div>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('password.store') }}" method="POST">
                @csrf
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                            <input id="email" name="email" type="email" autocomplete="email" required readonly
                                value="{{ old('email', $request->email) }}"
                                class="appearance-none relative block w-full px-4 py-3 pl-11 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                placeholder="Nhập địa chỉ email của bạn">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mật khẩu mới
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="appearance-none relative block w-full px-4 py-3 pl-11 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                placeholder="Nhập mật khẩu mới">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Mật khẩu phải có ít nhất 8 ký tự
                        </p>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Xác nhận mật khẩu
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                autocomplete="new-password" required
                                class="appearance-none relative block w-full px-4 py-3 pl-11 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                placeholder="Nhập lại mật khẩu mới">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-key text-blue-500 group-hover:text-blue-400"></i>
                        </span>
                        Đặt lại mật khẩu
                    </button>
                </div>

                <!-- Additional Links -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Quay lại
                        <a href="{{ route('login') }}"
                            class="font-medium text-blue-600 hover:text-blue-500 transition duration-300">
                            trang đăng nhập
                        </a>
                    </p>
                </div>
            </form>

            <!-- Security Tips -->
            <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shield-alt text-green-500 mt-0.5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-green-800 mb-1">Mẹo bảo mật</h4>
                        <ul class="text-xs text-green-700 space-y-1">
                            <li>• Sử dụng mật khẩu mạnh với chữ hoa, chữ thường, số và ký tự đặc biệt</li>
                            <li>• Không sử dụng mật khẩu cũ đã từng sử dụng</li>
                            <li>• Đảm bảo mật khẩu khác với các tài khoản khác</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
