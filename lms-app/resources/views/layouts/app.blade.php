<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - XiaoMu HSK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/common.css'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        /* ===== ANIMATION BASE ===== */
        .reveal {
            opacity: 0;
            visibility: hidden;
            will-change: transform, opacity;
        }

        .reveal.visible {
            opacity: 1;
            visibility: visible;
        }

        /* ===== FADE UP ===== */
        .reveal-fade-up {
            transform: translateY(60px);
            transition: all 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .reveal-fade-up.visible {
            transform: translateY(0);
        }

        /* ===== FADE LEFT ===== */
        .reveal-fade-left {
            transform: translateX(-60px);
            transition: all 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .reveal-fade-left.visible {
            transform: translateX(0);
        }

        /* ===== FADE RIGHT ===== */
        .reveal-fade-right {
            transform: translateX(60px);
            transition: all 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .reveal-fade-right.visible {
            transform: translateX(0);
        }

        /* ===== ZOOM IN ===== */
        .reveal-zoom {
            transform: scale(0.85);
            transition: all 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .reveal-zoom.visible {
            transform: scale(1);
        }

        /* ===== FLIP IN (3D) ===== */
        .reveal-flip {
            transform: perspective(600px) rotateY(15deg);
            transition: all 1s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .reveal-flip.visible {
            transform: perspective(600px) rotateY(0deg);
        }

        /* ===== STAGGER DELAYS ===== */
        .stagger-delay-1 {
            transition-delay: 0.05s;
        }

        .stagger-delay-2 {
            transition-delay: 0.10s;
        }

        .stagger-delay-3 {
            transition-delay: 0.15s;
        }

        .stagger-delay-4 {
            transition-delay: 0.20s;
        }

        .stagger-delay-5 {
            transition-delay: 0.25s;
        }

        .stagger-delay-6 {
            transition-delay: 0.30s;
        }

        .stagger-delay-7 {
            transition-delay: 0.35s;
        }

        .stagger-delay-8 {
            transition-delay: 0.40s;
        }

        /* ===== PARALLAX BACKGROUND ===== */
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
        }

        /* ===== SCROLL INDICATOR ===== */
        .scroll-indicator {
            animation: bounce-down 2s ease-in-out infinite;
        }

        @keyframes bounce-down {

            0%,
            100% {
                transform: translateY(0);
                opacity: 1;
            }

            50% {
                transform: translateY(10px);
                opacity: 0.5;
            }
        }

        /* ===== SMOOTH SCROLL BEHAVIOR ===== */
        html {
            scroll-behavior: smooth;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100">
    <!-- Header -->
    <x-header />

    @if (!request()->routeIs('home') && !View::hasSection('hide_default_breadcrumb'))
        @include('components.breadcrumb')
    @endif
    <!-- Main Content -->
    @yield('content')
    <!-- Footer -->
    <x-footer />
    <!-- Scroll to Top Button -->
    <button id="scroll-to-top" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" aria-label="Cuộn lên đầu trang"
        class="fixed bottom-6 right-6 z-50 flex h-11 w-11 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/30 opacity-0 translate-y-4 pointer-events-none transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary/40">
        <span class="material-symbols-outlined text-[20px]">keyboard_arrow_up</span>
    </button>

    <script>
        (function() {
            const btn = document.getElementById('scroll-to-top');
            if (!btn) return;

            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    btn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                    btn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
                } else {
                    btn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                    btn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                }
            }, {
                passive: true
            });
        })();
    </script>

    @stack('scripts')

</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== REVEAL ON SCROLL (lặp lại mỗi khi cuộn vào) =====
        const options = {
            root: null,
            rootMargin: '0px 0px -80px 0px', // kích hoạt sớm hơn
            threshold: 0.1
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                const el = entry.target;
                if (entry.isIntersecting) {
                    el.classList.add('visible');
                } else {
                    // Nếu element có class 'once' → không xóa visible (chạy 1 lần)
                    // Ngược lại → xóa visible để animation chạy lại khi cuộn vào
                    if (!el.classList.contains('once')) {
                        el.classList.remove('visible');
                    }
                }
            });
        }, options);

        // Quan sát tất cả .reveal
        document.querySelectorAll('.reveal').forEach(function(el) {
            observer.observe(el);
        });

        // ===== COUNTER ANIMATION (chạy 1 lần) =====
        const counters = document.querySelectorAll('.counter');
        let countersAnimated = false;

        function animateCounter(counter) {
            const target = parseInt(counter.dataset.target);
            const duration = 2000;
            const step = Math.ceil(target / 60);
            let current = 0;

            const timer = setInterval(function() {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = current.toLocaleString() + '+';
            }, 30);
        }

        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !countersAnimated) {
                    countersAnimated = true;
                    counters.forEach(function(counter) {
                        animateCounter(counter);
                    });
                }
            });
        }, {
            threshold: 0.5
        });

        if (counters.length > 0) {
            counterObserver.observe(counters[0]);
        }

        // ===== PARALLAX =====
        const parallaxElements = document.querySelectorAll('.parallax-slow');
        window.addEventListener('scroll', function() {
            const scrollY = window.pageYOffset;
            parallaxElements.forEach(function(el) {
                const speed = parseFloat(el.dataset.speed) || 0.3;
                const yPos = -(scrollY * speed);
                el.style.transform = 'translateY(' + yPos + 'px)';
            });
        }, {
            passive: true
        });
    });
</script>

</html>
