<section class="header-gradient w-full py-16 md:pt-10 md:pb-24 relative overflow-hidden">
    <div class="max-w-[1200px] mx-auto px-4 md:px-10 relative z-10">
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-[15px] text-white font-sans">
                <li class="flex items-center">
                    <a class="flex items-center hover:opacity-80 transition-opacity" href="#">
                        <span class="material-symbols-outlined text-[18px] mr-1.5">home</span>
                        Trang chủ
                    </a>
                </li>
                <li class="flex items-center">
                    <span class="material-symbols-outlined text-[16px] mx-1 opacity-60">chevron_right</span>
                    <span class="font-medium">@yield('breadcrumb')</span>
                </li>
            </ol>
        </nav>
        {{-- <h1 class="text-5xl md:text-6xl font-extrabold text-white tracking-tight font-poppins">
            @yield('breadcrumb')
        </h1> --}}
    </div>
    <div class="absolute right-[-5%] top-1/2 -translate-y-1/2 h-[150%] w-1/2 opacity-5 pointer-events-none">
        <svg class="h-full w-full object-cover" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M44.7,-76.4C58.1,-69.2,69.2,-58.1,77.4,-44.7C85.5,-31.3,90.7,-15.7,91.4,0.4C92.1,16.5,88.3,33.1,79.8,47.2C71.3,61.4,58.1,73.1,43,80.1C27.9,87.1,10.9,89.4,-5.2,88.5C-21.3,87.6,-42.6,83.5,-57.8,73.1C-73,62.7,-82.1,46.1,-87.3,28.8C-92.5,11.5,-93.8,-6.4,-88.7,-22.7C-83.6,-39.1,-72.1,-53.8,-57.8,-62.4C-43.5,-71,-26.4,-73.4,-10.8,-74.6C4.8,-75.8,20.4,-75.8,44.7,-76.4Z"
                fill="#FFFFFF" transform="translate(100 100)"></path>
        </svg>
    </div>
</section>
