@extends('layouts.app')

@section('title', 'Blog')

@section('breadcrumb', 'Blog')

@section('content')
    <main class="flex-1 px-6 lg:px-40 py-10 max-w-[1440px] mx-auto w-full">
        <section class="mb-16">
            <div
                class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden shadow-sm flex flex-col lg:flex-row min-h-[400px] border border-slate-100 dark:border-slate-800">
                <div class="lg:w-1/2 relative min-h-[300px] lg:min-h-full">
                    <div class="absolute inset-0 bg-cover bg-center"
                        data-alt="Students studying Chinese characters together in class"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBEXHxB1_ktFDPyHlv_vtyROXn0faunvonG5ywBdJ0AbtcalTJVymZFqLPCv4eeEJ-WoAZ-b3WagBG-oa3ShYVul_wGitXfC4hMMo5HsJAOBGzJ8hMnGv40QYd9srAj5Br3TBtPwmzvBcHSFXpj14Lj-SHM0MtwACPnqvbMnmjwF-y-cWE0xcLlqCw7Wcned56JPniIAfseWbXNeDm1VnOkqh_oIthGwo1dxtje1geZjgzbJ1YCOIyUIXyAsQTANwsF1DUO3cin9A");'>
                    </div>
                </div>
                <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                    <span class="text-primary font-bold text-xs tracking-widest uppercase mb-3">BÀI VIẾT NỔI BẬT</span>
                    <h1
                        class="text-3xl lg:text-4xl font-bold font-poppins text-slate-900 dark:text-white leading-tight mb-4">
                        Cách học tiếng Trung hiệu quả cho người mới bắt đầu</h1>
                    <p class="text-slate-600 dark:text-slate-400 text-lg mb-6 leading-relaxed">
                        Khám phá lộ trình học tiếng Trung từ con số 0 với phương pháp học hiện đại từ XiaoMu Chinese giúp
                        bạn ghi nhớ nhanh hơn, giao tiếp tự tin hơn trong thời gian ngắn nhất.
                    </p>
                    <div class="flex items-center gap-3 mb-8">
                        <div class="size-10 rounded-full bg-cover bg-center" data-alt="Author portrait photo"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDV8YhAg-mb6aZUE56TBcUrOb06j0elwSACF5Kk4BJYHaRzU86x0H3irL2uLmhzUZsXG4pJTmCCxKE8BkfYFF4XA6jPuN1_KrJ_4s1wzUplz-EvBsmB9Op1fTtxkMke6Eaz0ED86tZCEcnyGihQyFsbmITTfEBKe2KJPIIdsQwk7Va86Up5qcKF-pXLQZcsxt_tcZb-s_IOYpDstlkmn3sSrx4SARd5RndhYQQAkYI08pZ1JEQJt-ViXODWDm5_WhN4vHIeLCQBOA");'>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Nguyễn Minh Anh</p>
                            <p class="text-xs text-slate-500">Giảng viên ngôn ngữ • 5 phút đọc</p>
                        </div>
                    </div>
                    <button
                        class="flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-8 rounded-lg w-fit hover:shadow-lg transition-all">
                        <span>Đọc ngay</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                </div>
            </div>
        </section>
        <div class="flex flex-col lg:flex-row gap-12">
            <div class="lg:w-2/3">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold font-poppins text-slate-900 dark:text-white">Bài viết mới nhất</h2>
                    <div class="flex gap-2">
                        <button
                            class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-primary transition-colors">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-300">grid_view</span>
                        </button>
                        <button
                            class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-primary transition-colors">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-300">list</span>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div
                        class="group bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all duration-300">
                        <div class="aspect-video bg-cover bg-center relative"
                            data-alt="Chinese street photography for culture article"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBiyqJlq1TxdwBNDX1UTKaPcikQq6rRgTP5u43E68QDQyVNj39EP7xVmk6Z3P_QKPdHbdIWPZSNCD3NGAWUhN9lWljQa_mE5XdwIBD6R1ZeRWLsZnyGCrX8xNgAJe2ACXQlgpJcemArmOrMuUsdXOx7uD7dAvNEEVbw3RQYyoGW7zDI1FeqKApSVKEbZRqVEX56XaBFynTMUmHwRmrZ6Dlc-izyOLvT-a4CM8LDwd01c4dQq9G2bA4iiNbVH1bCaP8vsoW1VXAYmg");'>
                            <span
                                class="absolute top-3 left-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-primary">Văn
                                hóa</span>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-slate-500 mb-2">20/10/2023 • 4 phút đọc</p>
                            <h3
                                class="text-lg font-bold font-poppins text-slate-900 dark:text-white mb-3 group-hover:text-primary transition-colors">
                                Tìm hiểu về ý nghĩa Tết Trung Thu tại Trung Hoa</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-4">Khám phá những phong tục
                                tập quán độc đáo và ý nghĩa tâm linh của ngày lễ đoàn viên này...</p>
                            <a class="inline-flex items-center gap-1 text-primary text-sm font-bold hover:gap-2 transition-all"
                                href="#">
                                Đọc thêm <span class="material-symbols-outlined text-sm">trending_flat</span>
                            </a>
                        </div>
                    </div>
                    <div
                        class="group bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all duration-300">
                        <div class="aspect-video bg-cover bg-center relative"
                            data-alt="Study books and headphones for listening skills"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBjFQ8a6kS1KOGXNRA_ADzrdJaAnywgnkDFhF2iFStP4ZB4ElHt1nj-NqC1Y2oE3AJmAsiBNDIO7iGZ98q7sBUcGSdgqAYsFFxafPNdSRj61d2mUdn29w_h7JCFcDXA5IonlgZDzW8iR22GASwD68vmIJBv928qJEW0bN26m7Zf8t3hD-8Vg2wnqarGtR6SokoCk0Y-P9K5LhUZz59T0JVOX_Tdc7X4SGTotiHZQ4ZBGUadLZ3PhvLxrsB6BAQjzO1jsysox8FYTg");'>
                            <span
                                class="absolute top-3 left-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-primary">Tips
                                &amp; Tricks</span>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-slate-500 mb-2">18/10/2023 • 6 phút đọc</p>
                            <h3
                                class="text-lg font-bold font-poppins text-slate-900 dark:text-white mb-3 group-hover:text-primary transition-colors">
                                Bí quyết luyện nghe HSK 4 đạt điểm tuyệt đối</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-4">Các kỹ thuật phân tích
                                từ khóa và dự đoán đáp án giúp bạn chinh phục phần thi nghe khó nhằn...</p>
                            <a class="inline-flex items-center gap-1 text-primary text-sm font-bold hover:gap-2 transition-all"
                                href="#">
                                Đọc thêm <span class="material-symbols-outlined text-sm">trending_flat</span>
                            </a>
                        </div>
                    </div>
                    <div
                        class="group bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all duration-300">
                        <div class="aspect-video bg-cover bg-center relative"
                            data-alt="Notebook with Chinese grammar rules written"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBF5_u4AoQxhpDkuohhNvx_UQkq6zne9rhlMSe9nnZs3H62vVMVYNbzFgN59cZq3OSAdE9CWfrUOffQWM85esUndOuWnn-7mZpPp8mvh-pHncqRhS7c_OBsqzfipPlPFhWntlWsCjEd6LWOT9hsLuRcz86ZdJEo3a0xZuqOyAeYJwVkMSdrz_jtUY008BwKR_vGAlyCstGsE1P6Nuq5mTAIG8ILJUI7MtPTNz1_KSj-QGlQjUuvDOxWJmNiik7ZY8kUdqsUYCi_YA");'>
                            <span
                                class="absolute top-3 left-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-primary">Tài
                                liệu học tập</span>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-slate-500 mb-2">15/10/2023 • 8 phút đọc</p>
                            <h3
                                class="text-lg font-bold font-poppins text-slate-900 dark:text-white mb-3 group-hover:text-primary transition-colors">
                                Tổng hợp cấu trúc ngữ pháp cơ bản HSK 1-3</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-4">Hệ thống lại toàn bộ các
                                cấu trúc quan trọng nhất dành cho người mới bắt đầu học tiếng Trung...</p>
                            <a class="inline-flex items-center gap-1 text-primary text-sm font-bold hover:gap-2 transition-all"
                                href="#">
                                Đọc thêm <span class="material-symbols-outlined text-sm">trending_flat</span>
                            </a>
                        </div>
                    </div>
                    <div
                        class="group bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all duration-300">
                        <div class="aspect-video bg-cover bg-center relative" data-alt="Office setting with people working"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBgvBy1lSd9L1BCAO0zCrwa1aa0-JOXJuk1lguPlbxyGSgUaYahKgJfUPHO-28_iLPif77qvHXVXfBPrM_325UCduv9NLXCxlq1zGYA1dfMojR1Cx0JQuL-JJVVxJVyjKWlP2n9fVYybkLVC_spX_kFWcthpH-o6gtMR-TD4i_G33r1yMKXH5Om-TBB6qhmnZUodzgfZ4vE7FFwKZ8apYEHeurxy80D0FtyJZBU5nmUyMAVoyq2NU7E6JmWLiPEPDllMGrNEr2bYQ");'>
                            <span
                                class="absolute top-3 left-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-primary">Tài
                                liệu học tập</span>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-slate-500 mb-2">12/10/2023 • 5 phút đọc</p>
                            <h3
                                class="text-lg font-bold font-poppins text-slate-900 dark:text-white mb-3 group-hover:text-primary transition-colors">
                                Bộ từ vựng chủ đề công sở thông dụng nhất</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-4">Lưu ngay bộ từ vựng và
                                các mẫu câu giao tiếp chuyên nghiệp trong môi trường làm việc...</p>
                            <a class="inline-flex items-center gap-1 text-primary text-sm font-bold hover:gap-2 transition-all"
                                href="#">
                                Đọc thêm <span class="material-symbols-outlined text-sm">trending_flat</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-12 flex justify-center">
                    <button
                        class="px-6 py-2 border-2 border-primary text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-all">
                        Xem thêm bài viết
                    </button>
                </div>
            </div>
            <aside class="lg:w-1/3 flex flex-col gap-10">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800">
                    <h4 class="font-poppins font-bold text-slate-900 dark:text-white mb-4">Tìm kiếm bài viết</h4>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary">search</span>
                        <input
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200"
                            placeholder="Nhập từ khóa..." type="text" />
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800">
                    <h4 class="font-poppins font-bold text-slate-900 dark:text-white mb-6">Chủ đề phổ biến</h4>
                    <div class="flex flex-wrap gap-2">
                        <a class="px-4 py-2 bg-primary/10 text-primary text-xs font-bold rounded-full hover:bg-primary hover:text-white transition-all"
                            href="#">HSK 1-6</a>
                        <a class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full hover:bg-primary/20 hover:text-primary transition-all"
                            href="#">Giao tiếp</a>
                        <a class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full hover:bg-primary/20 hover:text-primary transition-all"
                            href="#">Thành ngữ</a>
                        <a class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full hover:bg-primary/20 hover:text-primary transition-all"
                            href="#">Du lịch</a>
                        <a class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full hover:bg-primary/20 hover:text-primary transition-all"
                            href="#">Kinh doanh</a>
                        <a class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full hover:bg-primary/20 hover:text-primary transition-all"
                            href="#">Phim ảnh</a>
                        <a class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full hover:bg-primary/20 hover:text-primary transition-all"
                            href="#">Ẩm thực</a>
                    </div>
                </div>
                <div class="bg-primary/10 p-6 rounded-xl border border-primary/20 relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="font-poppins font-bold text-slate-900 dark:text-white mb-2">Đăng ký nhận tin</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Cập nhật tài liệu và bài viết mới nhất
                            hàng tuần.</p>
                        <input
                            class="w-full bg-white dark:bg-slate-800 border-none rounded-lg px-4 py-3 text-sm mb-3 focus:ring-2 focus:ring-primary/50"
                            placeholder="Email của bạn" type="email" />
                        <button
                            class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:shadow-md transition-all">Đăng
                            ký ngay</button>
                    </div>
                    <span
                        class="material-symbols-outlined absolute -right-6 -bottom-6 text-9xl text-primary/10 rotate-12">mail</span>
                </div>
            </aside>
        </div>
    </main>
@endsection
