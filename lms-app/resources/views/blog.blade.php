@extends('layouts.app')

@section('title', 'Góc chia sẻ — XiaoMu Chinese')

@section('breadcrumb', 'Góc chia sẻ')

@section('content')
    <main class="flex-1 px-4 sm:px-6 lg:px-20 xl:px-32 py-8 sm:py-12 md:py-16 max-w-[1440px] mx-auto w-full">
        @if (!isset($blogs) || $blogs->isEmpty())
            <div
                class="my-8 sm:my-16 px-6 sm:px-12 py-12 sm:py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl dark:shadow-none text-center max-w-2xl mx-auto flex flex-col items-center">

                <div class="mb-6 flex justify-center">
                    <div
                        class="size-20 sm:size-24 rounded-2xl bg-primary/10 dark:bg-primary/20 text-primary border border-primary/20 dark:border-primary/30 flex items-center justify-center shadow-lg shadow-primary/10">
                        <span class="material-symbols-outlined text-4xl sm:text-5xl text-primary">newspaper</span>
                    </div>
                </div>

                <div class="mb-4">
                    <span
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-primary/10 dark:bg-primary/20 border border-primary/20 text-primary text-xs font-bold tracking-wide">
                        <span class="material-symbols-outlined text-[15px]">auto_awesome</span> Góc chia sẻ
                    </span>
                </div>

                <h1
                    class="text-2xl sm:text-3xl md:text-4xl font-black font-display text-slate-900 dark:text-white tracking-tight mb-3">
                    Chưa có bài viết mới
                </h1>

                <p class="text-xs sm:text-sm md:text-base text-slate-500 dark:text-slate-400 max-w-lg leading-relaxed mb-8">
                    Đội ngũ giảng viên <strong class="font-bold text-slate-700 dark:text-slate-200">Tiếng Trung
                        XiaoMu</strong> đang chuẩn bị những bài viết chất lượng về bí kíp luyện thi HSK, mẹo ghi nhớ chữ Hán
                    và phương pháp giao tiếp. Vui lòng quay lại sau!
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
                    <a href="{{ route('home') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200">
                        <span class="material-symbols-outlined text-[18px] text-primary">home</span>
                        Về Trang Chủ
                    </a>
                    <a href="{{ route('courses') }}"
                        class="w-full sm:w-auto group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-primary px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary/40">
                        <span class="material-symbols-outlined text-[18px]">school</span>
                        <span>Khám Phá Khóa Học</span>
                    </a>
                </div>
            </div>
        @else
            @if (isset($featuredBlog) && $featuredBlog)
                <section class="mb-12 sm:mb-16">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none flex flex-col lg:flex-row min-h-[380px] border border-slate-100 dark:border-slate-800">
                        <div
                            class="lg:w-1/2 relative min-h-[260px] lg:min-h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                            @if ($featuredBlog->thumbnail)
                                <img src="{{ asset('storage/' . $featuredBlog->thumbnail) }}"
                                    alt="{{ $featuredBlog->title }}" class="w-full h-full object-cover">
                            @else
                                <span
                                    class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600">article</span>
                            @endif
                        </div>
                        <div class="lg:w-1/2 p-6 sm:p-8 lg:p-12 flex flex-col justify-center">
                            <span class="text-primary font-bold text-xs tracking-widest uppercase mb-3">BÀI VIẾT NỔI
                                BẬT</span>
                            <h1
                                class="text-xl sm:text-2xl lg:text-3xl font-bold font-display text-slate-900 dark:text-white leading-snug mb-4">
                                {{ $featuredBlog->title }}
                            </h1>
                            <p
                                class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm line-clamp-3 mb-6 leading-relaxed">
                                {{ Str::limit(strip_tags($featuredBlog->content), 180) }}
                            </p>
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="size-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                    {{ substr($featuredBlog->author->first_name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $featuredBlog->author->full_name ?? 'Tác giả' }}
                                    </p>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $featuredBlog->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <a href="#"
                                class="flex items-center justify-center gap-2 bg-primary text-white font-bold py-2.5 sm:py-3 px-6 rounded-2xl w-fit hover:shadow-lg transition-all text-xs sm:text-sm">
                                <span>Đọc ngay</span>
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </section>
            @endif

            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                <div class="lg:w-2/3">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold font-display text-slate-900 dark:text-white">Bài viết mới
                            nhất</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($blogs as $blog)
                            <article
                                class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all duration-300 flex flex-col">
                                <div
                                    class="aspect-video bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                    @if ($blog->thumbnail)
                                        <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <span
                                            class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">article</span>
                                    @endif
                                    @if ($blog->category)
                                        <span
                                            class="absolute top-3 left-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-2.5 py-1 rounded-lg text-[10px] font-bold text-primary">
                                            {{ $blog->category->name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <p class="text-[11px] text-slate-400 mb-2">
                                            {{ $blog->created_at->format('d/m/Y') }}
                                        </p>
                                        <h3
                                            class="text-base font-bold font-display text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                            {{ $blog->title }}
                                        </h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 mb-4">
                                            {{ Str::limit(strip_tags($blog->content), 120) }}
                                        </p>
                                    </div>
                                    <a class="inline-flex items-center gap-1 text-primary text-xs font-bold hover:gap-2 transition-all"
                                        href="#">
                                        Đọc thêm <span class="material-symbols-outlined text-sm">trending_flat</span>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $blogs->links() }}
                    </div>
                </div>

                <aside class="lg:w-1/3 flex flex-col gap-6">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <h4 class="font-display font-bold text-slate-900 dark:text-white text-base mb-4">Tìm kiếm bài viết
                        </h4>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-lg">search</span>
                            <input
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200"
                                placeholder="Nhập từ khóa..." type="text" />
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </main>
@endsection
