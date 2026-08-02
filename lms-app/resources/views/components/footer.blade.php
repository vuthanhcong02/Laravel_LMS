<footer class="bg-slate-900 py-16 text-slate-400 mt-36">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-12 lg:grid-cols-4">

            {{-- Cột 1: Logo + mô tả + social --}}
            <div class="flex flex-col gap-6 lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 w-fit group">
                    <div
                        class="relative shrink-0 p-[2.5px] rounded-full overflow-hidden bg-gradient-to-br from-primary via-orange-400 to-amber-300 shadow-lg shadow-primary/30">
                        <div class="rounded-full overflow-hidden w-10 h-10">
                            <img src="{{ asset('logo.png') }}" alt="XiaoMu Logo"
                                class="w-full h-full object-cover object-center rounded-full">
                        </div>
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span
                            class="text-base font-extrabold tracking-tight text-white group-hover:text-primary transition-colors duration-300">XiaoMu</span>
                        <span
                            class="text-[9px] font-bold tracking-[0.18em] uppercase text-primary transition-colors duration-300">Tiếng Trung</span>
                    </div>
                </a>
                <p class="text-sm leading-relaxed">
                    Nền tảng học tiếng Trung online theo chuẩn HSK — kết hợp bài khóa, hội thoại, ngữ pháp và luyện tập
                    toàn diện.
                </p>
                {{-- Social media --}}
                <div class="flex items-center gap-3">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/profile.php?id=61589009699142" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-primary text-slate-400 hover:text-white transition-all duration-200"
                        title="Facebook Tiếng Trung XiaoMu" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    {{-- TikTok --}}
                    <a href="https://www.tiktok.com/@chiettuchuhan55" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-primary text-slate-400 hover:text-white transition-all duration-200"
                        title="TikTok Tiếng Trung XiaoMu" aria-label="TikTok">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.22-1.15 4.39-2.92 5.74-1.73 1.3-4.04 1.76-6.14 1.25-2.19-.51-4.02-1.92-4.99-3.95-.97-2.02-.97-4.4.01-6.4 1.01-2.07 3.01-3.61 5.27-3.95.83-.13 1.68-.11 2.5-.02v4.06c-.4-.02-.8-.02-1.2-.02-1.07.03-2.15.42-2.92 1.17-.74.72-1.14 1.75-1.13 2.8.01 1.05.42 2.07 1.15 2.78.75.74 1.83 1.1 2.88 1.04 1.05-.05 2.05-.51 2.75-1.28.69-.76 1.04-1.8 1.03-2.85V.02z" />
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="mb-5 font-bold text-white text-sm uppercase tracking-widest">Khám phá</h4>
                <ul class="flex flex-col gap-3 text-sm">
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('home') }}">
                            <span class="material-symbols-outlined text-[15px] text-primary/60">home</span>
                            Trang chủ
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('courses') }}">
                            <span class="material-symbols-outlined text-[15px] text-primary/60">school</span>
                            Tất cả khóa học
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('flashcards') }}">
                            <span class="material-symbols-outlined text-[15px] text-primary/60">style</span>
                            Thẻ ghi nhớ
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('blog') }}">
                            <span class="material-symbols-outlined text-[15px] text-primary/60">article</span>
                            Góc chia sẻ
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('contact') }}">
                            <span class="material-symbols-outlined text-[15px] text-primary/60">contact_support</span>
                            Liên hệ
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="mb-5 font-bold text-white text-sm uppercase tracking-widest">Lộ trình HSK</h4>
                <ul class="flex flex-col gap-3 text-sm">
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('courses') }}?level=1">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded bg-primary/20 text-primary text-[10px] font-extrabold shrink-0">1</span>
                            HSK 1 — Sơ cấp
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('courses') }}?level=2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded bg-primary/20 text-primary text-[10px] font-extrabold shrink-0">2</span>
                            HSK 2 — Cơ bản
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('courses') }}?level=3">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded bg-primary/20 text-primary text-[10px] font-extrabold shrink-0">3</span>
                            HSK 3 — Trung cấp
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('courses') }}?level=4">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded bg-primary/20 text-primary text-[10px] font-extrabold shrink-0">4</span>
                            HSK 4 — Trung cao
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('courses') }}?level=5">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded bg-primary/20 text-primary text-[10px] font-extrabold shrink-0">5</span>
                            HSK 5 — Nâng cao
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary transition-colors flex items-center gap-2"
                            href="{{ route('courses') }}?level=6">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded bg-primary/20 text-primary text-[10px] font-extrabold shrink-0">6</span>
                            HSK 6 — Thành thạo
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Cột 4: Liên hệ --}}
            <div>
                <h4 class="mb-5 font-bold text-white text-sm uppercase tracking-widest">Liên hệ</h4>
                <ul class="flex flex-col gap-4 text-sm">
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0">call</span>
                        <a href="tel:+84395294730" class="hover:text-primary transition-colors">0395 294 730</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0">mail</span>
                        <a href="mailto:{{ config('mail.support_address', 'xiaomuhsk@gmail.com') }}" class="hover:text-primary transition-colors">{{ config('mail.support_address', 'xiaomuhsk@gmail.com') }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">schedule</span>
                        <span>Thứ 2 – Chủ Nhật<br>8:00 – 24:00</span>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div
            class="mt-14 border-t border-slate-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <span>© {{ date('Y') }} {{ config('app.name', 'Tiếng Trung XiaoMu') }}. All rights reserved.</span>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-primary transition-colors">Chính sách bảo mật</a>
                <span class="text-slate-700">·</span>
                <a href="#" class="hover:text-primary transition-colors">Điều khoản dịch vụ</a>
            </div>
        </div>
    </div>
</footer>
