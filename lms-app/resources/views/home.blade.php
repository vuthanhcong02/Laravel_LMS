@extends('layouts.app')

@section('title', 'Trang chủ — XiaoMu Chinese')

@push('styles')
    <style>
        /* ==== HERO CARD ANIMATIONS ==== */

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes float-alt {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(8px);
            }
        }

        @keyframes shimmer-glow {

            0%,
            100% {
                text-shadow: 0 0 0px transparent;
            }

            50% {
                text-shadow: 0 0 24px rgba(232, 146, 122, 0.45);
            }
        }

        @keyframes spin-very-slow {
            from {
                transform: rotate(6deg) translateX(3px) translateY(2px);
            }

            to {
                transform: rotate(9deg) translateX(3px) translateY(2px);
            }
        }

        @keyframes spin-very-slow-reverse {
            from {
                transform: rotate(-3deg) translateX(-2px);
            }

            to {
                transform: rotate(-5deg) translateX(-2px);
            }
        }

        @keyframes ping-soft {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            70% {
                transform: scale(1.4);
                opacity: 0;
            }

            100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        .animate-float-alt {
            animation: float-alt 3.5s ease-in-out infinite;
        }

        .animate-float-badge2 {
            animation: float 5s ease-in-out infinite 1s;
        }

        .animate-shimmer-glow {
            animation: shimmer-glow 3s ease-in-out infinite;
        }

        .animate-deco-1 {
            animation: spin-very-slow 6s ease-in-out infinite alternate;
        }

        .animate-deco-2 {
            animation: spin-very-slow-reverse 5s ease-in-out infinite alternate;
        }

        .animate-ping-soft::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            background: #22c55e;
            animation: ping-soft 2s cubic-bezier(0, 0, 0.2, 1) infinite;
            opacity: 0.6;
        }
    </style>
@endpush

@section('content')

    {{-- ===== HERO SECTION ===== --}}
    <section
        class="relative overflow-hidden bg-gradient-to-br from-primary/8 via-white to-rose-50/40 dark:from-primary/10 dark:via-background-dark dark:to-background-dark py-20 lg:py-28">
        {{-- Decorative blobs --}}
        <div class="parallax-slow pointer-events-none absolute -right-40 -top-40 h-[500px] w-[500px] rounded-full bg-primary/15 blur-[80px]"
            data-speed="0.15"></div>
        <div class="parallax-slow pointer-events-none absolute -left-40 bottom-0 h-80 w-80 rounded-full bg-rose-300/15 blur-[60px]"
            data-speed="0.25"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="grid items-center gap-16 lg:grid-cols-2">

                {{-- Left: Text Content --}}
                <div class="flex flex-col gap-7">
                    {{-- Badge --}}
                    <div
                        class="reveal reveal-fade-up inline-flex w-fit items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-primary dark:bg-primary/15">
                        <span>✦</span> Học tiếng Trung cùng XiaoMu
                    </div>

                    {{-- Headline --}}
                    <h1
                        class="reveal reveal-fade-up stagger-delay-1 font-display text-4xl font-black leading-[1.1] tracking-tight text-slate-900 dark:text-white lg:text-5xl xl:text-6xl">
                        Chinh phục<br>
                        tiếng Trung
                        <span class="relative mt-1 block">
                            <span
                                class="relative z-10 bg-gradient-to-r from-primary to-rose-400 bg-clip-text text-transparent">
                                dễ dàng hơn
                            </span>
                            <svg class="absolute -bottom-1 left-0 w-full opacity-30" viewBox="0 0 240 10"
                                preserveAspectRatio="none">
                                <path d="M0 5 Q60 10 120 5 T240 5" stroke="#E8927A" stroke-width="3.5" fill="none"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                    </h1>

                    {{-- Description --}}
                    <p
                        class="reveal reveal-fade-up stagger-delay-2 max-w-lg text-base leading-relaxed text-slate-500 dark:text-slate-400 lg:text-lg">
                        Lộ trình học <strong class="font-semibold text-slate-700 dark:text-slate-200">bài bản từ HSK
                            1→6</strong>, kết hợp flashcard thông minh và bài thi thử chuẩn quốc tế — giúp bạn giao tiếp tự
                        tin trong <strong class="font-semibold text-slate-700 dark:text-slate-200">90 ngày</strong>.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="reveal reveal-fade-up stagger-delay-3 flex flex-wrap items-center gap-4">
                        @guest
                            <a href="{{ route('register') }}"
                                class="group relative inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-primary px-8 py-4 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary/40">
                                <span>Bắt đầu miễn phí</span>
                                <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
                            </a>
                            <a href="{{ route('courses') }}"
                                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-7 py-4 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200">
                                <span class="material-symbols-outlined text-[18px] text-primary">play_circle</span>
                                Xem khóa học
                            </a>
                        @else
                            <a href="{{ route('courses') }}"
                                class="group relative inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-primary px-8 py-4 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary/40">
                                <span>Học ngay</span>
                                <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
                            </a>
                            <a href="{{ route('flashcards') }}"
                                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-7 py-4 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200">
                                <span class="material-symbols-outlined text-[18px] text-primary">style</span>
                                Luyện Flashcard
                            </a>
                        @endguest
                    </div>

                    {{-- Social proof --}}
                    <div
                        class="reveal reveal-fade-up stagger-delay-4 flex flex-wrap items-center gap-6 border-t border-slate-100 pt-6 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <div class="flex -space-x-2.5">
                                <div class="h-9 w-9 rounded-full border-2 border-white bg-primary/70 dark:border-slate-800">
                                </div>
                                <div class="h-9 w-9 rounded-full border-2 border-white bg-rose-300 dark:border-slate-800">
                                </div>
                                <div class="h-9 w-9 rounded-full border-2 border-white bg-amber-300 dark:border-slate-800">
                                </div>
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-slate-100 text-[10px] font-bold text-slate-600 dark:border-slate-800 dark:bg-slate-700 dark:text-slate-300">
                                    +9k</div>
                            </div>
                            <div class="text-sm">
                                <p class="font-bold text-slate-800 dark:text-white"><span class="counter"
                                        data-target="10000">10,000</span> học viên</p>
                                <p class="text-xs text-slate-400">đang học mỗi ngày</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="flex gap-0.5 text-amber-400">★★★★★</div>
                            <span class="text-sm font-bold text-slate-800 dark:text-white">4.9</span>
                            <span class="text-xs text-slate-400">/5</span>
                        </div>

                    </div>
                </div>

                {{-- Right: Visual Card Stack --}}
                <div class="reveal reveal-zoom stagger-delay-2 relative flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-sm">
                        {{-- Decorative rotated cards --}}
                        <div
                            class="animate-deco-1 absolute inset-0 rounded-3xl border-2 border-primary/20 bg-primary/5 rotate-6 translate-x-3 translate-y-2 dark:bg-primary/8">
                        </div>
                        <div
                            class="animate-deco-2 absolute inset-0 rounded-3xl border border-rose-200/50 bg-rose-50/50 -rotate-3 -translate-x-2 dark:bg-rose-900/10">
                        </div>

                        {{-- Main Card --}}
                        <div
                            class="animate-float relative rounded-3xl bg-white p-6 shadow-2xl shadow-primary/15 dark:bg-slate-800/90 dark:shadow-primary/10">
                            {{-- App preview mock --}}
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-primary">Bài học hôm nay
                                    </p>
                                    <h3 class="mt-0.5 text-lg font-black text-slate-800 dark:text-white">HSK 2 — Gia đình
                                    </h3>
                                </div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                                    <span class="material-symbols-outlined text-[20px] text-primary">local_library</span>
                                </div>
                            </div>

                            {{-- Flashcard preview --}}
                            <div
                                class="mb-5 rounded-2xl bg-gradient-to-br from-primary/10 via-rose-50 to-amber-50/50 p-5 text-center dark:from-primary/15 dark:via-slate-700/50 dark:to-slate-700/30">
                                <p class="animate-shimmer-glow text-5xl font-black text-slate-800 dark:text-white">家人</p>
                                <p class="mt-1 text-sm font-medium text-primary">jiā rén</p>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Gia đình / Người thân</p>
                            </div>

                            {{-- Quick actions --}}
                            <div class="mt-4 grid grid-cols-3 gap-2">
                                <div
                                    class="flex flex-col items-center gap-1 rounded-xl bg-slate-50 px-3 py-2.5 dark:bg-slate-700/50">
                                    <span class="material-symbols-outlined text-[18px] text-primary">style</span>
                                    <span
                                        class="text-[10px] font-semibold text-slate-600 dark:text-slate-400">Flashcard</span>
                                </div>
                                <div
                                    class="flex flex-col items-center gap-1 rounded-xl bg-primary px-3 py-2.5 shadow-sm shadow-primary/30">
                                    <span class="material-symbols-outlined text-[18px] text-white">fact_check</span>
                                    <span class="text-[10px] font-bold text-white">Luyện tập</span>
                                </div>
                                <div
                                    class="flex flex-col items-center gap-1 rounded-xl bg-slate-50 px-3 py-2.5 dark:bg-slate-700/50">
                                    <span class="material-symbols-outlined text-[18px] text-primary">auto_stories</span>
                                    <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-400">Giáo
                                        trình</span>
                                </div>
                            </div>
                        </div>

                        {{-- Floating badge 1 --}}
                        <div
                            class="animate-float-alt reveal reveal-fade-up stagger-delay-5 absolute -left-8 -bottom-6 flex items-center gap-2.5 rounded-2xl bg-white px-4 py-3 shadow-xl dark:bg-slate-800">
                            <div class="relative flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                <span
                                    class="animate-ping-soft absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-30"></span>
                                <span class="text-base">🎉</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-white">Vừa qua HSK 3!</p>
                                <p class="text-[10px] text-slate-400">Nguyễn Thị Thủy Tiên. — 2 phút trước</p>
                            </div>
                        </div>

                        {{-- Floating badge 2 --}}
                        <div
                            class="animate-float-badge2 reveal reveal-fade-right stagger-delay-6 absolute -right-6 top-8 rounded-2xl bg-white px-4 py-3 shadow-xl dark:bg-slate-800">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Từ mới hôm nay</p>
                            <p class="text-xl font-black text-slate-800 dark:text-white">+<span class="counter"
                                    data-target="24">24</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== STATS BAR ===== --}}
    <section class="border-y border-primary/10 bg-white py-10 dark:bg-slate-900/50">
        <div class="mx-auto max-w-5xl px-6">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                @foreach ([['target' => 10800, 'label' => 'Từ vựng HSK', 'icon' => 'translate', 'suffix' => '+'], ['target' => 6, 'label' => 'Cấp độ HSK', 'icon' => 'emoji_events', 'suffix' => ''], ['target' => 100, 'label' => 'Miễn phí trải nghiệm', 'icon' => 'verified', 'suffix' => '%'], ['target' => 1000, 'label' => 'Học viên đồng hành', 'icon' => 'group', 'suffix' => '+']] as $stat)
                    <div class="reveal reveal-fade-up flex flex-col items-center gap-2 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 mb-1">
                            <span class="material-symbols-outlined text-[22px] text-primary">{{ $stat['icon'] }}</span>
                        </div>
                        <p class="text-3xl font-black text-slate-900 dark:text-white">
                            <span class="counter"
                                data-target="{{ $stat['target'] }}">{{ $stat['target'] }}</span>{{ $stat['suffix'] }}
                        </p>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== COURSES SECTION ===== --}}
    <section class="py-20 dark:bg-background-dark">
        <div class="mx-auto max-w-7xl px-6">
            {{-- Header --}}
            <div class="mb-14 text-center">
                <span
                    class="reveal reveal-fade-up inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-primary/8 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-primary dark:bg-primary/15">
                    <span class="material-symbols-outlined text-[14px]">menu_book</span> Công cụ học tập
                </span>
                <h2
                    class="reveal reveal-fade-up stagger-delay-1 font-display mt-4 text-3xl font-black tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                    3 công cụ giúp bạn <span class="text-primary">học tiếng Trung hiệu quả</span>
                </h2>
                <p class="reveal reveal-fade-up stagger-delay-2 mx-auto mt-3 max-w-lg text-slate-500 dark:text-slate-400">
                    Toàn bộ hành trình học — từ ghi nhớ từ vựng, theo giáo trình chuẩn HSK, đến thi thử thực chiến — đều có
                    trên XiaoMu.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">

                {{-- Card 1: Flashcard --}}
                <div
                    class="reveal reveal-fade-up stagger-delay-1 group flex flex-col rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-primary/30 hover:shadow-xl hover:shadow-primary/10 dark:border-slate-700/50 dark:bg-slate-800/70">
                    <div
                        class="flex h-44 items-center justify-center rounded-t-2xl bg-gradient-to-br from-primary/15 to-rose-100/60 dark:from-primary/20 dark:to-rose-900/20">
                        <span
                            class="material-symbols-outlined text-[64px] text-primary/70 dark:text-primary/60">style</span>
                    </div>
                    <div class="flex flex-1 flex-col gap-4 p-6">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 dark:text-white">Flashcard</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                                Học từ vựng bằng thẻ ghi nhớ thông minh. Hệ thống tự động ôn lại đúng lúc bạn sắp quên nhờ
                                công nghệ Spaced Repetition.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-primary/10 px-2.5 py-0.5 text-[10px] font-bold text-primary">HSK
                                1–9</span>
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-300">Chữ
                                Hán & Pinyin</span>
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-300">Offline</span>
                        </div>
                        <a href="{{ route('flashcards') }}"
                            class="mt-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-primary px-5 py-3 text-sm font-bold text-white shadow-md shadow-primary/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/30">
                            <span class="material-symbols-outlined text-[18px]">style</span>
                            Luyện Flashcard
                        </a>
                    </div>
                </div>

                {{-- Card 2: Giáo trình HSK --}}
                <div
                    class="reveal reveal-fade-up stagger-delay-2 group flex flex-col rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-primary/30 hover:shadow-xl hover:shadow-primary/10 dark:border-slate-700/50 dark:bg-slate-800/70">
                    <div
                        class="flex h-44 items-center justify-center rounded-t-2xl bg-gradient-to-br from-amber-100/70 to-orange-100/50 dark:from-amber-900/20 dark:to-orange-900/15">
                        <span
                            class="material-symbols-outlined text-[64px] text-amber-500/70 dark:text-amber-400/60">auto_stories</span>
                    </div>
                    <div class="flex flex-1 flex-col gap-4 p-6">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 dark:text-white">Giáo trình chuẩn HSK</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                                Học theo lộ trình bài bản từ HSK 1 đến HSK 6 với bài giảng video, bài tập tương tác và ngữ
                                pháp chi tiết.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">6
                                cấp độ</span>
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-300">Video
                                bài giảng</span>
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-300">Bài
                                tập</span>
                        </div>
                        <a href="{{ route('courses') }}"
                            class="mt-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-primary px-5 py-3 text-sm font-bold text-white shadow-md shadow-primary/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/30">
                            <span class="material-symbols-outlined text-[18px]">auto_stories</span>
                            Xem giáo trình
                        </a>
                    </div>
                </div>

                {{-- Card 3: Thi thử HSK --}}
                <div
                    class="reveal reveal-fade-up stagger-delay-3 group flex flex-col rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-primary/30 hover:shadow-xl hover:shadow-primary/10 dark:border-slate-700/50 dark:bg-slate-800/70">
                    <div
                        class="flex h-44 items-center justify-center rounded-t-2xl bg-gradient-to-br from-green-100/70 to-teal-100/50 dark:from-green-900/20 dark:to-teal-900/15">
                        <span
                            class="material-symbols-outlined text-[64px] text-green-500/70 dark:text-green-400/60">fact_check</span>
                    </div>
                    <div class="flex flex-1 flex-col gap-4 p-6">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 dark:text-white">Thi thử HSK</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                                Bộ đề thi mô phỏng 100% cấu trúc HSK thực tế. Làm xong tự động chấm điểm, phân tích điểm yếu
                                và gợi ý ôn tập.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-bold text-green-700 dark:bg-green-900/30 dark:text-green-400">Chuẩn
                                quốc tế</span>
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-300">Chấm
                                tự động</span>
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-300">Phân
                                tích kết quả</span>
                        </div>
                        <a href="{{ route('student.quizzes.index') }}"
                            class="mt-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-primary px-5 py-3 text-sm font-bold text-white shadow-md shadow-primary/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/30">
                            <span class="material-symbols-outlined text-[18px]">fact_check</span>
                            Thi thử ngay
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ===== FEATURES SECTION ===== --}}
    <section class="py-20 bg-gradient-to-b from-slate-50/80 to-white dark:from-slate-900/50 dark:to-background-dark">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-14 text-center">
                <span
                    class="reveal reveal-fade-up inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-primary/8 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-primary dark:bg-primary/15">
                    <span class="material-symbols-outlined text-[14px]">bolt</span> Tại sao chọn XiaoMu
                </span>
                <h2
                    class="reveal reveal-fade-up stagger-delay-1 font-display mt-4 text-3xl font-black tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                    Học tiếng Trung <span class="text-primary">thông minh hơn</span>
                </h2>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach ([
            ['icon' => 'route', 'title' => 'Lộ trình cá nhân hóa', 'desc' => 'Từ HSK 1 đến HSK 6, hệ thống tự động đề xuất bài học phù hợp với tốc độ tiếp thu của từng người.'],
            ['icon' => 'style', 'title' => 'Flashcard thông minh', 'desc' => 'Công nghệ lặp lại có khoảng cách (Spaced Repetition) giúp bạn ghi nhớ từ vựng lâu dài, hiệu quả gấp 3 lần.'],
            ['icon' => 'fact_check', 'title' => 'Thi thử HSK chuẩn quốc tế', 'desc' => 'Bộ đề thi mô phỏng 100% cấu trúc HSK thực tế, phân tích điểm yếu và gợi ý ôn tập tức thì.'],
            ['icon' => 'translate', 'title' => 'Nhận diện chữ viết tay', 'desc' => 'Luyện viết chữ Hán và nhận phản hồi chính xác về nét bút, cấu trúc chữ theo tiêu chuẩn.'],
            ['icon' => 'record_voice_over', 'title' => 'Luyện phát âm AI', 'desc' => 'Công nghệ nhận dạng giọng nói phân tích và chấm điểm phát âm 4 thanh điệu tiếng Trung.'],
            ['icon' => 'devices', 'title' => 'Học mọi lúc mọi nơi', 'desc' => 'Đồng bộ tiến độ liền mạch giữa điện thoại, máy tính bảng và máy tính — học offline không cần mạng.'],
        ] as $idx => $feat)
                    <div
                        class="reveal reveal-fade-up stagger-delay-{{ ($idx % 3) + 1 }} group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg dark:border-slate-700/50 dark:bg-slate-800/70">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 transition group-hover:bg-primary group-hover:shadow-lg group-hover:shadow-primary/30">
                            <span
                                class="material-symbols-outlined text-[22px] text-primary transition group-hover:text-white">{{ $feat['icon'] }}</span>
                        </div>
                        <h3 class="mb-2 font-bold text-slate-800 dark:text-white">{{ $feat['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $feat['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== TESTIMONIALS SECTION ===== --}}
    {{-- <section class="py-20 dark:bg-background-dark">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-14 text-center">
                <span
                    class="reveal reveal-fade-up inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-primary/8 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-primary dark:bg-primary/15">
                    <span class="material-symbols-outlined text-[14px]">reviews</span> Học viên nói gì
                </span>
                <h2
                    class="reveal reveal-fade-up stagger-delay-1 font-display mt-4 text-3xl font-black tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                    Hàng nghìn học viên <span class="text-primary">tin tưởng</span>
                </h2>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach ([['name' => 'Nguyễn Thu Hà', 'role' => 'Nhân viên xuất nhập khẩu', 'review' => 'Sau 3 tháng học với XiaoMu, mình đã đạt HSK 4 với điểm 285/300. Phương pháp flashcard và thi thử của app cực kỳ hiệu quả!', 'rating' => 5, 'initials' => 'NH'], ['name' => 'Trần Minh Khoa', 'role' => 'Sinh viên Đại học Ngoại thương', 'review' => 'App duy nhất mình thấy có bộ đề thi HSK thực sự giống đề thi thật. Giao diện đẹp, dễ dùng, lộ trình rõ ràng. Highly recommend!', 'rating' => 5, 'initials' => 'MK'], ['name' => 'Lê Bảo Châu', 'role' => 'Dịch thuật tự do', 'review' => 'Tính năng nhận diện phát âm giúp mình sửa được lỗi thanh điệu mà học mãi không sửa được. Giờ giao tiếp tự nhiên hơn rất nhiều!', 'rating' => 5, 'initials' => 'BC']] as $idx => $t)
                    <div
                        class="reveal reveal-zoom stagger-delay-{{ $idx + 1 }} flex flex-col gap-4 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700/50 dark:bg-slate-800/70">
                        <div class="flex gap-0.5 text-amber-400 text-sm">
                            @for ($i = 0; $i < $t['rating']; $i++)
                                ★
                            @endfor
                        </div>
                        <p class="flex-1 text-sm leading-relaxed text-slate-600 dark:text-slate-300">"{{ $t['review'] }}"
                        </p>
                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4 dark:border-slate-700">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/15 text-sm font-bold text-primary">
                                {{ $t['initials'] }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $t['name'] }}</p>
                                <p class="text-xs text-slate-400">{{ $t['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}

    {{-- ===== BLOG PREVIEW ===== --}}
    <section class="py-20 bg-slate-50/80 dark:bg-slate-900/50">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 flex items-end justify-between">
                <div>
                    <span
                        class="reveal reveal-fade-up inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-primary/8 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-primary dark:bg-primary/15">
                        <span class="material-symbols-outlined text-[14px]">rss_feed</span> Blog
                    </span>
                    <h2
                        class="reveal reveal-fade-up stagger-delay-1 font-display mt-3 text-2xl font-black tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                        Bài viết mới nhất</h2>
                </div>
                <a href="{{ route('blog') }}"
                    class="reveal reveal-fade-right hidden items-center gap-1 text-sm font-semibold text-primary transition hover:gap-2 sm:inline-flex">
                    Xem tất cả <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            @if (isset($latestBlogs) && $latestBlogs->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($latestBlogs as $idx => $blog)
                        <article
                            class="reveal reveal-fade-up stagger-delay-{{ $idx + 1 }} group flex flex-col rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg dark:border-slate-700/50 dark:bg-slate-800/70">
                            <div
                                class="h-40 w-full overflow-hidden rounded-t-2xl bg-slate-100 dark:bg-slate-800 relative flex items-center justify-center">
                                @if ($blog->thumbnail)
                                    <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-[48px] text-primary/40 dark:text-primary/30">article</span>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col gap-2 p-5">
                                @if ($blog->category)
                                    <span class="inline-flex w-fit rounded-full bg-primary/10 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-primary">
                                        {{ $blog->category->name }}
                                    </span>
                                @endif
                                <h3 class="font-bold text-slate-800 transition group-hover:text-primary dark:text-white line-clamp-2">
                                    {{ $blog->title }}
                                </h3>
                                <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400 line-clamp-2">
                                    {{ Str::limit(strip_tags($blog->content), 100) }}
                                </p>
                                <div class="mt-auto flex items-center gap-2 pt-3 text-[10px] text-slate-400">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    <span>{{ $blog->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                {{-- Empty state khi chưa có bài viết ở trang chủ --}}
                <div class="reveal reveal-fade-up p-8 rounded-2xl bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-center max-w-lg mx-auto flex flex-col items-center">
                    <div class="size-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-2xl">newspaper</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">Chưa có bài viết mới</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-4">Các nội dung chia sẻ bí kíp học tiếng Trung và mẹo thi HSK sẽ sớm được xuất bản.</p>
                    <a href="{{ route('blog') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                        Xem trang Góc chia sẻ <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- ===== NEWSLETTER / CTA ===== --}}
    <section class="relative overflow-hidden py-24">
        {{-- Gradient background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-rose-400 to-amber-400"></div>
        {{-- Pattern overlay --}}
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;">
        </div>

        <div class="relative mx-auto max-w-3xl px-6 text-center">
            <span
                class="reveal reveal-fade-up inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white backdrop-blur-sm">
                ✉️ Nhận tư vấn
            </span>
            <h2
                class="reveal reveal-fade-up stagger-delay-1 font-display mt-5 text-3xl font-black text-white sm:text-4xl lg:text-5xl">
                Bắt đầu hành trình<br>tiếng Trung của bạn
            </h2>
            <p class="reveal reveal-fade-up stagger-delay-2 mt-4 text-base text-white/80">
                Để lại email — chúng tôi sẽ gửi lộ trình học <strong class="font-bold text-white">hoàn toàn miễn
                    phí</strong> phù hợp với mục tiêu của bạn.
            </p>

            <div x-data="ctaConsultationForm()" class="mt-8 max-w-md mx-auto">
                <form x-show="!submitted" @submit.prevent="submitForm"
                    class="reveal reveal-fade-up stagger-delay-3 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    @csrf
                    <input type="hidden" name="topics[]" value="Tư vấn khóa học">
                    <input type="text" name="website" x-model="website" class="hidden" tabindex="-1"
                        autocomplete="off" aria-hidden="true">
                    <input type="email" name="email" x-model="email" placeholder="Email của bạn" required
                        class="flex-1 rounded-2xl border-2 border-white/30 bg-white/20 px-5 py-4 text-sm font-semibold text-white placeholder-white/70 outline-none backdrop-blur-md transition focus:border-white focus:bg-white/30 sm:max-w-xs shadow-inner">
                    <button type="submit" :disabled="loading"
                        class="rounded-2xl bg-white px-8 py-4 text-sm font-black text-primary shadow-xl shadow-black/10 transition hover:-translate-y-0.5 hover:shadow-2xl active:scale-95 disabled:opacity-75 flex items-center justify-center gap-2 shrink-0">
                        <span x-show="!loading">Nhận tư vấn ngay</span>
                        <span x-show="loading" style="display:none"
                            class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                    </button>
                </form>

                <div x-show="submitted" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100" style="display:none"
                    class="p-5 rounded-2xl bg-white text-slate-900 shadow-2xl border-2 border-emerald-500 flex items-start gap-4 text-left">
                    <div
                        class="size-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-black text-base text-slate-900">Đã đăng ký thành công!</h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Cảm ơn bạn! {{ config('app.name', 'Tiếng Trung XiaoMu') }} sẽ liên hệ tư vấn lộ trình học phù
                            hợp nhất cho bạn trong thời gian sớm nhất.
                        </p>
                    </div>
                </div>

                <div x-show="errorMsg" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display:none"
                    class="mt-3 p-3.5 rounded-xl bg-white text-rose-700 shadow-lg border border-rose-200 text-xs sm:text-sm font-bold flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-rose-500 text-lg">error</span>
                    <span x-text="errorMsg"></span>
                </div>
            </div>
            <p class="reveal reveal-fade-up stagger-delay-4 mt-4 text-xs text-white/60">
                🔒 Cam kết không spam. Hủy bất cứ lúc nào.
            </p>
        </div>
    </section>

    <script>
        function ctaConsultationForm() {
            return {
                email: '',
                website: '',
                loading: false,
                submitted: false,
                errorMsg: '',
                async submitForm() {
                    if (!this.email) return;
                    this.loading = true;
                    this.errorMsg = '';
                    try {
                        const response = await fetch('{{ route('contact.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                email: this.email,
                                website: this.website,
                                topics: ['Tư vấn khóa học']
                            })
                        });
                        const data = await response.json();
                        if (response.ok && data.success) {
                            this.submitted = true;
                            this.email = '';
                        } else {
                            if (response.status === 429) {
                                this.errorMsg = 'Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau 1 phút.';
                            } else if (data.errors && data.errors.email) {
                                this.errorMsg = data.errors.email[0];
                            } else if (data.errors) {
                                const firstKey = Object.keys(data.errors)[0];
                                this.errorMsg = data.errors[firstKey][0];
                            } else {
                                this.errorMsg = data.message || 'Đã có lỗi xảy ra. Vui lòng thử lại sau.';
                            }
                        }
                    } catch (e) {
                        this.errorMsg = 'Đã có lỗi xảy ra. Vui lòng thử lại sau.';
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
@endsection
