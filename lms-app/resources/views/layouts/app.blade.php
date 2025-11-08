<!DOCTYPE html>
<html lang="vi">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description"
          content="Khóa học giá rẻ, chất lượng cao. Học mọi lúc, mọi nơi với hàng ngàn khóa học đa dạng.">
     <meta name="keywords" content="khóa học, học online, giá rẻ, kỹ năng, kiến thức">
     <title>@yield('title')</title>
     <meta name="csrf-token" content="{{ csrf_token() }}">
     @vite(['resources/css/app.css', 'resources/js/app.js'])
     <link rel="stylesheet" href="{{ asset('css/common.css') }}">
</head>

<body class="bg-gray-50">
     {{-- <div class="loading-bar"></div> --}}

     <!-- Header -->
     <x-header />

     <!-- Main Content -->
     <main>
          @yield('content')
     </main>

     <x-cart-popup />
     <x-payment-popup />
     <x-payment-success-popup />

     <!-- Footer -->
     <x-footer />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
     @vite(['resources/js/home.js', 'resources/js/course.js'])

</body>

</html>