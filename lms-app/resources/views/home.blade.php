@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-primary/20 to-transparent py-20 lg:py-32">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div class="flex flex-col gap-8">
                    <div
                        class="inline-flex w-fit items-center gap-2 rounded-full bg-white/50 dark:bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">
                        <span class="material-symbols-outlined text-sm">auto_awesome</span>
                        Học tiếng Trung thông minh hơn
                    </div>
                    <h1
                        class="text-5xl font-extrabold leading-tight tracking-tight text-slate-900 dark:text-white lg:text-6xl">
                        Học Tiếng Trung hiệu quả cùng <span class="text-primary">XiaoMu</span>
                    </h1>
                    <p class="text-lg text-slate-600 dark:text-slate-400">
                        Nền tảng học tiếng Trung trực tuyến tối ưu giúp bạn chinh phục HSK nhanh chóng và dễ dàng
                        hơn bao giờ hết với phương pháp giảng dạy hiện đại.
                    </p>
                </div>
                <div class="relative">
                    <div class="aspect-square rounded-3xl bg-primary/10 p-8">
                        <img alt="XiaoMu Learning" class="h-full w-full rounded-2xl object-cover shadow-2xl"
                            data-alt="Young student learning Chinese on a laptop"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhmLL44cmgFNEjikD9i_3fd0LdU3ArL_zc6SyL2QsrEGj3NC5OQt7OWm3VDeAzAZGNeAWTS8aUuHrfIJMWirivIn5gylkpPUjSAvx6GiO6ggndED1UxApsHfVcPTCSGit438eJy-kkuNn8FkHfkM5qzpLzafDSSdcfYSHucXHU7mMAh4LyeNUczjZsJ-S815m4DVnf2wRJP6AlEVaoxPrbO6fSs3ZL8YfU8G82wJoCi08a-JoUfFRUcgiq-sXF6gNO4TRXN-zTUQ" />
                    </div>
                    <div
                        class="absolute -bottom-6 -left-6 rounded-2xl bg-white dark:bg-slate-800 p-4 shadow-xl border border-primary/10">
                        <div class="flex items-center gap-3">
                            <div class="rounded-full bg-green-100 p-2 text-green-600">
                                <span class="material-symbols-outlined">verified_user</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase">Trusted by</p>
                                <p class="text-sm font-extrabold">10,000+ Students</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Benefits Section -->
    <section class="py-24 bg-white dark:bg-background-dark/50">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Tại sao chọn XiaoMu?</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">Trải nghiệm học tập vượt trội với các tính năng
                    độc quyền</p>
            </div>
            <div class="grid gap-8 md:grid-cols-3">
                <div
                    class="group rounded-2xl border border-primary/10 bg-background-light dark:bg-slate-800/50 p-8 transition-all hover:border-primary hover:shadow-xl">
                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-primary/20 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-3xl">map</span>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Lộ trình HSK</h3>
                    <p class="text-slate-600 dark:text-slate-400">Lộ trình cá nhân hóa được thiết kế khoa học giúp
                        bạn chinh phục mọi cấp độ HSK từ 1 đến 6.</p>
                </div>
                <div
                    class="group rounded-2xl border border-primary/10 bg-background-light dark:bg-slate-800/50 p-8 transition-all hover:border-primary hover:shadow-xl">
                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-primary/20 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-3xl">edit_note</span>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Bài tập trực tuyến</h3>
                    <p class="text-slate-600 dark:text-slate-400">Hệ thống hàng ngàn bài tập tương tác sinh động,
                        giúp ghi nhớ từ vựng và ngữ pháp nhanh chóng.</p>
                </div>
                <div
                    class="group rounded-2xl border border-primary/10 bg-background-light dark:bg-slate-800/50 p-8 transition-all hover:border-primary hover:shadow-xl">
                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-primary/20 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-3xl">monitoring</span>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Theo dõi tiến độ</h3>
                    <p class="text-slate-600 dark:text-slate-400">Hệ thống AI phân tích và báo cáo chi tiết kết quả
                        học tập hàng tuần để bạn luôn đi đúng hướng.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Courses Section -->
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Khóa học nổi bật</h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">Tìm kiếm lộ trình phù hợp nhất với trình độ
                        của bạn</p>
                </div>
                <a href="{{ route('courses') }}" class="text-sm font-bold text-primary hover:underline">Xem tất cả khóa
                    học</a>
            </div>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Course Card 1 -->
                <div
                    class="overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="aspect-video relative">
                        <img class="h-full w-full object-cover" data-alt="Online Chinese beginners classroom visual"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDci807kZuVH_YOp3PcsVPb_8YuTmRwOIRrF46YH9Hu8NP8aXj_OTBwoDKI4Us_ebop9_orftZ9r40t9ZWRxvOu8IN_9OvPGf9c0bdP8N5fpIuygac9SLTKc3JbIkE_dGg1Cy6gfUWsSdUgb5vKuM5D-mNFydt4hxC-cAcspe5rwlZ4jI5cw7HCTFFNLu7DN_8N1qn8Trz_yiksOUdbe6HeIIUwQ0SKOxDNXuSq0lXdpuf-vlUzPaex3D9-LZa8W9lAwbUx_KIgqg" />
                        <div
                            class="absolute left-3 top-3 rounded-lg bg-primary px-2 py-1 text-[10px] font-bold text-white uppercase">
                            Cơ Bản</div>
                    </div>
                    <div class="p-5">
                        <h3 class="mb-2 text-lg font-bold line-clamp-1">Hán ngữ Sơ cấp 1</h3>
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">stars</span> HSK 1</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">menu_book</span> 24 Bài học</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-primary">1.200.000đ</span>
                            <button
                                class="rounded-lg bg-primary/10 p-2 text-primary hover:bg-primary hover:text-white transition-colors">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 2 -->
                <div
                    class="overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="aspect-video relative">
                        <img class="h-full w-full object-cover" data-alt="Student studying with Chinese textbooks"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBT-0Bc2uuiEdPTZXnXrQKA-U3zIcR3R_rVVjAcJz4TX4t1Taw3EaRrCbniNwJpAk8oLBqdANiHRszV8pBtRB8l1Q2pzJhsu87mx4AyMMQHVqH5MrsJ-4BmPkpKftVDv0oVBRC43PWlPXVTKrql7pWXdWQu26v-Kud2SpjjYVswta2OnIKnjK16spIcrQNpX2ocmZPOzkfWE_3AOt76Ts2b3zI8k9WR7EdhkRGVToLws7x3TJ781Dx3eKTit1fQ3MCuasf3Kjjcmw" />
                        <div
                            class="absolute left-3 top-3 rounded-lg bg-primary px-2 py-1 text-[10px] font-bold text-white uppercase">
                            Trung cấp</div>
                    </div>
                    <div class="p-5">
                        <h3 class="mb-2 text-lg font-bold line-clamp-1">Luyện thi HSK 3 Cấp tốc</h3>
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">stars</span> HSK 3</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">menu_book</span> 40 Bài học</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-primary">2.500.000đ</span>
                            <button
                                class="rounded-lg bg-primary/10 p-2 text-primary hover:bg-primary hover:text-white transition-colors">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 3 -->
                <div
                    class="overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="aspect-video relative">
                        <img class="h-full w-full object-cover" data-alt="Virtual classroom on a tablet screen"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_BmL6wtiZtR_60Z8mlKJ1IxL6W2q3WaEPrGHxj5AA_M6EazMmiMCsfGIauQH0tkfmBfoFhCUaRKfkHm-KwE5RmxShKe-vRmDlrdiJbZ14L5stWAq9LxLgfbqrWVgCWVFiMWDABmGJjNVx5BfQ1HSo5gTFWlR-HHaVKXdHruy_h5S9tVYgepp1fCg9c3trjb-paxpyt12qE5hJJKJE_PDzJt6iXONI0ix_9tVAfHpewUxbApo2rSrvSWFAF2e1tiyemnJMrOo_hA" />
                        <div
                            class="absolute left-3 top-3 rounded-lg bg-primary px-2 py-1 text-[10px] font-bold text-white uppercase">
                            Giao tiếp</div>
                    </div>
                    <div class="p-5">
                        <h3 class="mb-2 text-lg font-bold line-clamp-1">Giao tiếp Công việc</h3>
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">stars</span> HSK 4</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">menu_book</span> 30 Bài học</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-primary">3.200.000đ</span>
                            <button
                                class="rounded-lg bg-primary/10 p-2 text-primary hover:bg-primary hover:text-white transition-colors">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 4 -->
                <div
                    class="overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="aspect-video relative">
                        <img class="h-full w-full object-cover" data-alt="Chinese calligraphy being practiced"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA1HJmNfvBdv1y0d4Bs__cykBUst_q1s0S4L2Eby97TC7E6KdT4ayAMqIfDL-k31u0_29tmG85XILHWj0T38lnkquS4iQP3obnLuiEyEBdie_KBKCSpSXMa-JxHi8q3Qn8YUvsmwRx5hcpO3RypU7wJzQmg3P0XHSFX-3JEnderP1j9zs38mN3XIe5ECULbs_agWFNRqYaMs7W2f6P0_HPRfBHk5xi35pnBBDi9Psl-BoqxRcrU_uOXSxJ-vhwWLT3E_ts5lF94Qg" />
                        <div
                            class="absolute left-3 top-3 rounded-lg bg-primary px-2 py-1 text-[10px] font-bold text-white uppercase">
                            Nâng cao</div>
                    </div>
                    <div class="p-5">
                        <h3 class="mb-2 text-lg font-bold line-clamp-1">HSK 5 Chuyên sâu</h3>
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">stars</span> HSK 5</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">menu_book</span> 60 Bài học</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-primary">4.500.000đ</span>
                            <button
                                class="rounded-lg bg-primary/10 p-2 text-primary hover:bg-primary hover:text-white transition-colors">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Learning Process -->
    <section class="py-24 bg-background-light dark:bg-background-dark/50">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold">Lộ trình 4 bước đơn giản</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">Hành trình từ người mới bắt đầu đến thành thạo
                </p>
            </div>
            <div class="relative flex flex-col gap-12 lg:flex-row lg:justify-between">
                <div class="absolute top-1/2 left-0 hidden h-0.5 w-full bg-primary/20 lg:block"></div>
                <div class="relative z-10 flex flex-col items-center text-center lg:w-1/4">
                    <div
                        class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-white dark:bg-slate-800 text-primary shadow-lg border-4 border-primary/10">
                        <span class="text-xl font-bold">01</span>
                    </div>
                    <h4 class="mb-2 font-bold">Đăng ký tài khoản</h4>
                    <p class="text-sm text-slate-500">Tạo tài khoản miễn phí chỉ trong 30 giây.</p>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center lg:w-1/4">
                    <div
                        class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-white dark:bg-slate-800 text-primary shadow-lg border-4 border-primary/10">
                        <span class="text-xl font-bold">02</span>
                    </div>
                    <h4 class="mb-2 font-bold">Tham gia lớp học</h4>
                    <p class="text-sm text-slate-500">Lựa chọn khóa học và bắt đầu video bài giảng.</p>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center lg:w-1/4">
                    <div
                        class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-white dark:bg-slate-800 text-primary shadow-lg border-4 border-primary/10">
                        <span class="text-xl font-bold">03</span>
                    </div>
                    <h4 class="mb-2 font-bold">Luyện tập mỗi ngày</h4>
                    <p class="text-sm text-slate-500">Hoàn thành bài tập để củng cố kiến thức.</p>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center lg:w-1/4">
                    <div
                        class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-white dark:bg-slate-800 text-primary shadow-lg border-4 border-primary/10">
                        <span class="text-xl font-bold">04</span>
                    </div>
                    <h4 class="mb-2 font-bold">Nhận feedback</h4>
                    <p class="text-sm text-slate-500">Giáo viên hỗ trợ chỉnh sửa lỗi sai kịp thời.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Reviews Section -->
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold">Học viên nói gì về XiaoMu?</h2>
                <div class="mt-4 flex justify-center gap-1 text-yellow-400">
                    <span class="material-symbols-outlined">star</span>
                    <span class="material-symbols-outlined">star</span>
                    <span class="material-symbols-outlined">star</span>
                    <span class="material-symbols-outlined">star</span>
                    <span class="material-symbols-outlined">star</span>
                </div>
            </div>
            <div class="grid gap-8 md:grid-cols-3">
                <div
                    class="rounded-2xl bg-white dark:bg-slate-800 p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="mb-6 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center overflow-hidden">
                            <img class="h-full w-full object-cover" data-alt="Female student testimonial portrait"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDSzDCVvxhSsYplqLStVi_sJVjarNg0MDiUrdlKNR5-NeGZ84mewKi-r1aikITYQDN6tO5ZKxlSCMf86OzE50zHG2atWzYJ8t78_3V2jg4Yki4hrBCzKJEDLlTTq4ywSk-Gncvyn1b9rSX8e0dr7NQ5umPuaV5CubIdRujLEZUGbDLQXNFKTFyEJOynh6Be2PuZ_1s_Q74QqTFsuQol_lOM7Pu1Cx4_L1txpiYrEIQGLLPiEz7_3lNq-_2yFD9NpqsPZQbsElOetA" />
                        </div>
                        <div>
                            <h5 class="font-bold">Minh Anh</h5>
                            <p class="text-xs text-slate-500">Học viên HSK 4</p>
                        </div>
                    </div>
                    <p class="italic text-slate-600 dark:text-slate-400">"Phương pháp giảng dạy rất dễ hiểu, mình
                        đã thi đỗ HSK 4 chỉ sau 4 tháng ôn luyện tại trung tâm. Rất đề xuất cho các bạn!"</p>
                </div>
                <div
                    class="rounded-2xl bg-white dark:bg-slate-800 p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="mb-6 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center overflow-hidden">
                            <img class="h-full w-full object-cover" data-alt="Male student testimonial portrait"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAAuvSGjFvoa5zJhHNKo-fXgLo1kq569Hm7EUwnjmrob7_Q5EzmA15ploK2d3EqI6vhOGPCmHPVr1RtC_VmfTVigakJOdf41jODcyGC-vp43sqgTjaF5uBZHnUpU8G8MULkN3BsUrqL7oQAqH6odFil0k4cTfOffZZHkhVrYL1-COWeHjLKh1P2_tF5vkaXlRjqkbmt_8Axreoe5pIVJsov1UC-d0e_UgtdktS7KFkCsfE5Yj2uZkUGbs_9yagTaQUhAwXg9yXFgQ" />
                        </div>
                        <div>
                            <h5 class="font-bold">Quốc Trung</h5>
                            <p class="text-xs text-slate-500">Người đi làm</p>
                        </div>
                    </div>
                    <p class="italic text-slate-600 dark:text-slate-400">"Nền tảng học trực tuyến rất mượt, mình có
                        thể học bất cứ khi nào có thời gian rảnh. Bài tập tương tác giúp mình nhớ chữ Hán rất lâu."
                    </p>
                </div>
                <div
                    class="rounded-2xl bg-white dark:bg-slate-800 p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="mb-6 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center overflow-hidden">
                            <img class="h-full w-full object-cover" data-alt="Another female student testimonial portrait"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCmteIuqXfG63pI0S5LEeW-FP6ckiQdJfay3g-uVfyCTHYxGXWGK__huCfdXCv8pUtwDD06-DGqp7UPXocHdu5HwwdBKyX3C1jKekPBvYgD24QRmOiQdSQPpQOL3quU-tbbcmweGswUgFMbB1i91sNeODlVn1rXb-7UX8LKsXnAcrHpes40OmLH05UrWhQz2F9TDooFFB-txVruZU4BAzEf9w51ZnNOBMenQOwaGfD-2H3u1SLMWwmE3o-iGoc251YAy7t3k-F9Wg" />
                        </div>
                        <div>
                            <h5 class="font-bold">Thu Hà</h5>
                            <p class="text-xs text-slate-500">Sinh viên ngoại ngữ</p>
                        </div>
                    </div>
                    <p class="italic text-slate-600 dark:text-slate-400">"Các thầy cô ở XiaoMu cực kỳ nhiệt tình,
                        luôn trả lời thắc mắc của mình ngay lập tức. Hệ thống lộ trình rất chuyên nghiệp."</p>
                </div>
            </div>
        </div>
    </section>
@endsection
