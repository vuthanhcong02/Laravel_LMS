<!DOCTYPE html>
<html lang="vi" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - XiaoMu Chinese</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200 antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Main Container -->
    <div class="w-full max-w-5xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-slate-100 dark:border-slate-800">
        
        <!-- Left Side: Branding & Info (Hidden on small screens) -->
        <div class="hidden md:flex md:w-1/2 bg-primary relative p-12 flex-col justify-between overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/20 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/10 rounded-full blur-3xl -ml-20 -mb-20"></div>

            <!-- Content -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="size-10 bg-white rounded-xl shadow-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-2xl">school</span>
                </div>
                <span class="text-white text-3xl font-bold font-heading tracking-tight">XiaoMu</span>
            </div>

            <div class="relative z-10 my-auto text-white">
                <h2 class="text-4xl font-bold font-heading leading-tight mb-4">Quản lý trung tâm hiệu quả &amp; thông minh</h2>
                <p class="text-white/80 text-lg leading-relaxed mb-8">
                    Chào mừng trở lại Admin Portal. Hệ thống quản lý toàn diện dành cho Quản trị viên và Giáo viên.
                </p>
                <div class="flex items-center gap-5 text-sm font-medium text-white/90">
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span>Học viên</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span>Doanh thu</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span>Khóa học</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-white/60 text-sm">
                &copy; {{ date('Y') }} XiaoMu Chinese. All rights reserved.
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white dark:bg-slate-900 relative">
            
            <div class="mb-10 lg:mb-12">
                <!-- Mobile Logo (visible only on mobile) -->
                <div class="md:hidden flex items-center gap-3 mb-8">
                    <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-2xl">school</span>
                    </div>
                    <span class="text-slate-900 dark:text-white text-2xl font-bold font-heading tracking-tight">XiaoMu</span>
                </div>

                <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-heading mb-2">Đăng nhập Portal</h1>
                <p class="text-slate-500 dark:text-slate-400">Vui lòng nhập thông tin để truy cập hệ thống.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                        <span class="font-semibold text-sm">Đã có lỗi xảy ra!</span>
                    </div>
                    <ul class="list-disc pl-7 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[20px]">mail</span>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary dark:text-white outline-none transition-all placeholder-slate-400 dark:placeholder-slate-500"
                            placeholder="Tài khoản admin/teacher">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Mật khẩu</label>
                        <a href="#" class="text-sm font-medium text-primary hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                            Quên mật khẩu?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[20px]">lock</span>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary dark:text-white outline-none transition-all placeholder-slate-400 dark:placeholder-slate-500"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center text-sm">
                    <label class="flex items-center cursor-pointer group w-max">
                        <div class="relative flex items-center">
                            <input id="remember" type="checkbox" name="remember" class="peer sr-only">
                            <div class="size-5 border-2 border-slate-300 dark:border-slate-600 rounded flex items-center justify-center peer-checked:bg-primary peer-checked:border-primary transition-all">
                                <span class="material-symbols-outlined text-white text-[14px] opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                            </div>
                        </div>
                        <span class="ml-2.5 font-medium text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-primary hover:bg-[#7db0d0] text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-primary/30 transform hover:-translate-y-0.5 transition-all text-base flex justify-center items-center gap-2 mt-4">
                    <span>Đăng nhập</span>
                    <span class="material-symbols-outlined text-[20px]">login</span>
                </button>
            </form>
            
            <div class="mt-8 text-center sm:hidden">
                <p class="text-xs text-slate-500 dark:text-slate-400">&copy; {{ date('Y') }} XiaoMu Chinese.</p>
            </div>
        </div>
    </div>
</body>
</html>
