<div id="global-preloader"
    class="fixed inset-0 z-[9999] bg-[#f8f6f3] dark:bg-[#0e0c0b] flex flex-col items-center justify-center transition-opacity duration-300 pointer-events-none">
    <div class="relative flex items-center justify-center w-20 h-20">
        <div class="absolute inset-0 border-4 border-[#fcdccf] dark:border-[#42271f] rounded-full"></div>
        <div class="absolute inset-0 border-4 border-[#e07a5f] rounded-full border-t-transparent animate-spin"></div>
        <img src="{{ asset('logo.png') }}" class="w-12 h-12 rounded-full p-1 object-contain" alt="{{ __('Đang tải') }}"
             onerror="this.onerror=null;this.src='{{ asset('favicon.png') }}';">
    </div>
    <p class="mt-4 text-xs font-bold text-[#e07a5f] animate-pulse tracking-widest uppercase">{{ __('Đang tải dữ liệu...') }}</p>
</div>

<script>
    (function () {
        function hidePreloader() {
            var preloader = document.getElementById('global-preloader');
            if (preloader) {
                setTimeout(function () {
                    preloader.style.opacity = '0';
                    setTimeout(function () {
                        if (preloader && preloader.parentNode) {
                            preloader.parentNode.removeChild(preloader);
                        }
                    }, 300);
                }, 150);
            }
        }

        if (document.readyState === 'complete') {
            hidePreloader();
        } else {
            window.addEventListener('load', hidePreloader);
            // Fallback timeout để tránh bị treo nếu tài nguyên ngoài chậm
            setTimeout(hidePreloader, 1500);
        }
    })();
</script>
