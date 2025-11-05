<header class="bg-white shadow-md sticky top-0 z-50">
     <div class="container mx-auto px-4 py-3 flex justify-between items-center">
          <div class="flex items-center">
               <a href="/" class="flex items-center">
                    <i class="fas fa-graduation-cap text-indigo-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-indigo-600">Khóa Học Giá Rẻ</span>
               </a>
          </div>

          <!-- Desktop Navigation -->
          <nav class="hidden md:flex space-x-8">
               <a href="/" class="text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Trang chủ</a>
               <a href="{{ route('courses') }}"
                    class="text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Khóa học</a>
               <a href="{{ route('about') }}"
                    class="text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Giới
                    thiệu</a>
               <a href="{{ route('contact') }}"
                    class="text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Liên hệ</a>
          </nav>

          <div class="flex items-center space-x-4">
               <!-- Cart Button -->
               <div class="relative">
                    <button id="cart-button" class="text-gray-700 hover:text-indigo-600 transition duration-300">
                         <i class="fas fa-shopping-cart text-xl"></i>
                         <span class="cart-count">0</span>
                    </button>
               </div>

               <!-- User Section -->
               <div class="relative" id="user-section">
                    <!-- Chưa đăng nhập -->
                    <!-- <div id="guest-menu" class="hidden lg:block">
                         <a href="{{ route('login') }}" class="btn-primary text-white px-4 py-2 rounded-md">Đăng
                              nhập</a>
                    </div> -->

                    <!-- Đã đăng nhập -->
                    <div id="user-menu" class="">
                         <button id="user-button"
                              class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition duration-300">
                              <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                   <i class="fas fa-user text-indigo-600"></i>
                              </div>
                              <span class="hidden sm:block font-medium">Nguyễn Văn A</span>
                              <i class="fas fa-chevron-down text-sm"></i>
                         </button>

                         <!-- User Dropdown Menu -->
                         <div id="user-dropdown"
                              class="user-dropdown absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                              <div class="px-4 py-2 border-b border-gray-100">
                                   <p class="text-sm font-semibold text-gray-800">Nguyễn Văn A</p>
                                   <p class="text-xs text-gray-500">user@example.com</p>
                              </div>
                              <a href="profile.html"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-200">
                                   <i class="fas fa-user-circle mr-3 w-4"></i>
                                   Hồ sơ cá nhân
                              </a>
                              <a href="my-courses.html"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-200">
                                   <i class="fas fa-book-open mr-3 w-4"></i>
                                   Khóa học của tôi
                              </a>
                              <a href="purchase-history.html"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-200">
                                   <i class="fas fa-history mr-3 w-4"></i>
                                   Lịch sử mua hàng
                              </a>
                              <div class="border-t border-gray-100 mt-2 pt-2">
                                   <button id="logout-button"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-200">
                                        <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                                        Đăng xuất
                                   </button>
                              </div>
                         </div>
                    </div>
               </div>

               <!-- Mobile Menu Button -->
               <button id="mobile-menu-button" class="lg:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
               </button>
          </div>
     </div>

     <!-- Mobile Menu -->
     <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden">
          <div class="backdrop fixed inset-0 bg-black bg-opacity-50" id="mobile-backdrop"></div>
          <div class="fixed top-0 left-0 bottom-0 w-full md:w-80 bg-white shadow-xl">
               <div class="flex items-center justify-between p-4 border-b">
                    <div class="flex items-center">
                         <i class="fas fa-graduation-cap text-indigo-600 text-2xl mr-2"></i>
                         <span class="text-xl font-bold text-indigo-600">Khóa Học Giá Rẻ</span>
                    </div>
                    <button id="close-mobile-menu" class="text-gray-500 hover:text-gray-700">
                         <i class="fas fa-times text-xl"></i>
                    </button>
               </div>

               <nav class="p-4 space-y-4">
                    <a href="/"
                         class="block py-2 px-4 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">Trang
                         chủ</a>
                    <a href="courses.html"
                         class="block py-2 px-4 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">Khóa
                         học</a>
                    <a href="about.html"
                         class="block py-2 px-4 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">Giới
                         thiệu</a>
                    <a href="contact.html"
                         class="block py-2 px-4 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">Liên
                         hệ</a>

                    <!-- Mobile User Section -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                         <!-- <div id="mobile-guest-menu">
                              <a href="{{ route('login') }}"
                                   class="block w-full text-center btn-primary text-white px-4 py-2 rounded-md">Đăng
                                   nhập</a>
                         </div> -->
                         <div id="mobile-user-menu" class="">
                              <div class="flex items-center space-x-3 mb-4 p-2 bg-gray-50 rounded-lg">
                                   <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-600"></i>
                                   </div>
                                   <div>
                                        <p class="text-sm font-semibold text-gray-800">Nguyễn Văn A</p>
                                        <p class="text-xs text-gray-500">user@example.com</p>
                                   </div>
                              </div>
                              <a href="profile.html"
                                   class="flex items-center py-2 px-4 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">
                                   <i class="fas fa-user-circle mr-3 w-4"></i>
                                   Hồ sơ cá nhân
                              </a>
                              <a href="my-courses.html"
                                   class="flex items-center py-2 px-4 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">
                                   <i class="fas fa-book-open mr-3 w-4"></i>
                                   Khóa học của tôi
                              </a>
                              <a href="purchase-history.html"
                                   class="flex items-center py-2 px-4 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">
                                   <i class="fas fa-history mr-3 w-4"></i>
                                   Lịch sử mua hàng
                              </a>
                              <button
                                   class="flex items-center w-full py-2 px-4 text-red-600 hover:bg-red-50 rounded-lg transition duration-200 mt-2">
                                   <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                                   Đăng xuất
                              </button>
                         </div>
                    </div>
               </nav>
          </div>
     </div>
</header>