@extends('layouts.lms')
@section('title', 'Tiếng Trung XIAOMU - Trang chủ')
@section('alpine-data')
    rankTab: 'week', 
    rankMetric: 'time', 
    socialDockExpanded: true,
@endsection
@section('content')
<div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 max-w-7xl">
                    <div class="lg:col-span-2 space-y-8 animate-fade-in-up">
                        <div class="lms-card p-6 sm:p-8 bg-gradient-to-br from-[#1c3848] via-[#1a3342] to-[#152834] text-white relative overflow-hidden group shadow-lg">
                            <!-- Faint Watermark Chinese Character -->
                            <div class="absolute right-4 -bottom-6 text-9xl font-extrabold text-white/5 pointer-events-none select-none zh-text">
                                学
                            </div>
                            <div class="absolute inset-0 shimmer-bg pointer-events-none opacity-20"></div>
                            <div class="relative z-10 space-y-4 max-w-xl">
                                <div class="space-y-2">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs border border-white/15 text-white/90 text-xs font-semibold">
                                        {{ __('Chào mừng, bạn') }} 👋
                                    </div>
                                    <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight leading-snug">
                                        {{ __('Bắt đầu hành trình học tiếng Trung nào!') }}
                                    </h1>
                                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                        {{ __('Chọn một lộ trình bên dưới và hoàn thành bài học đầu tiên để mở khoá chuỗi ngày của bạn.') }}
                                    </p>
                                </div>
                                <!-- CTA Buttons in Banner -->
                                <div class="flex flex-wrap items-center gap-3 pt-1">
                                    @php
                                        $lesson1Url = ($suggestedLesson && $suggestedLesson->slug) 
                                            ? route('courses.lesson', ['levelSlug' => 'hsk-1', 'lessonSlug' => $suggestedLesson->slug]) 
                                            : route('courses.level', ['levelSlug' => 'hsk-1']);
                                    @endphp
                                    <a href="{{ $lesson1Url }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-900 rounded-xl text-xs font-bold shadow-md transition-all btn-tactile">
                                        <i class="fa-solid fa-play text-[10px]"></i> {{ __('Bắt đầu bài học đầu tiên') }}
                                    </a>
                                    <a href="#roadmap" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs font-bold transition-all btn-tactile">
                                        <i class="fa-solid fa-bullseye text-[11px] text-amber-400"></i> {{ __('Chọn lộ trình phù hợp') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="lms-card p-5 flex items-center gap-4 hover:border-[#e07a5f]/50 transition-all">
                                <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xl shrink-0 shadow-xs">
                                    <i class="fa-solid fa-fire animate-flame"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white">0</span>
                                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Ngày liên tục') }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5">
                                        {{ __('Học bài hôm nay để bắt đầu chuỗi!') }}
                                    </p>
                                </div>
                            </div>
                            <div class="lms-card p-5 flex items-center gap-4 hover:border-[#e07a5f]/50 transition-all">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 shadow-xs">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white">0</span>
                                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Bài đã hoàn thành') }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5">
                                        {{ __('Hàng trăm bài học đang chờ bạn') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-1 h-5 rounded-full bg-[#e07a5f]"></div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">
                                        {{ __('Gợi ý cho bạn') }}
                                    </h2>
                                </div>
                                <a href="{{ route('courses') }}" class="text-xs font-semibold text-[#e07a5f] hover:underline inline-flex items-center gap-1">
                                    {{ __('Xem tất cả') }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                            <div class="lms-card p-5 sm:p-6 hover:shadow-md transition-all group border border-[#e8e2d9] dark:border-[#2d2926]">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                                    <div class="w-full sm:w-36 h-28 sm:h-28 rounded-2xl bg-gradient-to-br from-[#e6f0f5] to-[#d6e6f0] dark:from-[#1b3240] dark:to-[#162833] flex flex-col items-center justify-center text-center p-3 shrink-0 border border-slate-200 dark:border-slate-700 shadow-xs">
                                        <span class="text-3xl font-extrabold text-[#1c3848] dark:text-[#a0c4d8] zh-text tracking-wider">汉语</span>
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1">
                                            HSK 3.0 • {{ __('Cấp 1') }} • {{ __('Bài') }} {{ $suggestedLesson->lesson_number ?? 1 }}
                                        </span>
                                    </div>
                                    <div class="flex-1 space-y-2 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] uppercase tracking-wider">
                                                {{ __('Bài học mở đầu') }}
                                            </span>
                                            @if(!empty($suggestedLesson->code))
                                                <span class="text-[11px] text-slate-400">• {{ $suggestedLesson->code }}</span>
                                            @endif
                                        </div>
                                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white group-hover:text-[#e07a5f] transition-colors leading-snug zh-text">
                                            {{ $suggestedLesson->title ?? '你好！' }}
                                        </h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                                            {{ $suggestedLesson->translation ?? 'Xin chào!' }}
                                        </p>
                                        <div class="pt-2 flex items-center justify-between">
                                            <a href="{{ $lesson1Url }}" 
                                               @if(!auth()->check())
                                                   @click.prevent="$dispatch('open-auth-modal', { redirect: '{{ $lesson1Url }}' })"
                                               @endif
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-[#244255] hover:bg-[#1a3342] dark:bg-[#e07a5f] dark:hover:bg-[#c86349] text-white rounded-xl text-xs font-bold transition-all btn-tactile shadow-xs cursor-pointer">
                                                <i class="fa-solid fa-play text-[10px]"></i> {{ __('Bắt đầu học') }}
                                            </a>
                                            <span class="text-xs text-slate-400 font-medium">
                                                {{ $suggestedLesson->vocab_list_count ?? 11 }} {{ __('từ') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="roadmap" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-1 h-5 rounded-full bg-[#e07a5f]"></div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">
                                        {{ __('Lộ trình của bạn') }}
                                    </h2>
                                </div>
                                <a href="{{ route('courses') }}" class="text-xs font-semibold text-[#e07a5f] hover:underline inline-flex items-center gap-1">
                                    {{ __('Tất cả') }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <!-- HSK 1 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-1']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded">HSK 1</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-[#e07a5f] group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Nhập môn căn bản') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">150 {{ __('từ vựng') }} • 15 {{ __('bài') }}</p>
                                </a>
                                <!-- HSK 2 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-2']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded">HSK 2</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-[#e07a5f] group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Giao tiếp đời sống') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">300 {{ __('từ vựng') }} • 15 {{ __('bài') }}</p>
                                </a>
                                <!-- HSK 3 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-3']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded">HSK 3</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-[#e07a5f] group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Sơ trung cấp') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">600 {{ __('từ vựng') }} • 20 {{ __('bài') }}</p>
                                </a>
                                <!-- HSK 4 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-4']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/40 px-2 py-0.5 rounded">HSK 4</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-sky-500 group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Trung cấp vững vàng') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">1200 {{ __('từ vựng') }} • 20 {{ __('bài') }}</p>
                                </a>
                                <!-- HSK 5 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-5']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded">HSK 5</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Cao cấp thành thạo') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">2500 {{ __('từ vựng') }} • 36 {{ __('bài') }}</p>
                                </a>
                                <!-- HSK 6 -->
                                <a href="{{ route('courses.level', ['levelSlug' => 'hsk-6']) }}" class="lms-card p-4 hover:border-[#e07a5f] transition-all group block">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/40 px-2 py-0.5 rounded">HSK 6</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-purple-500 group-hover:translate-x-0.5 transition-all"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ __('Bậc thầy Hán ngữ') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">5000+ {{ __('từ vựng') }} • Chuyên sâu</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <div x-data="{ 
                            playing: false,
                            timer: null,
                            playAudio() {
                                if (!('speechSynthesis' in window)) {
                                    alert('Trình duyệt không hỗ trợ phát âm.');
                                    return;
                                }
                                const synth = window.speechSynthesis;
                                const word = '{{ addslashes($wordOfDay->word ?? '坚持') }}';
                                if (!word) return;
                                // Xóa timer trước đó nếu người dùng click liên tục
                                if (this.timer) {
                                    clearTimeout(this.timer);
                                    this.timer = null;
                                }
                                // Đánh thức synth nếu bị Chrome pause ngầm
                                if (synth.paused) {
                                    synth.resume();
                                }
                                // Hủy phát âm hiện tại
                                synth.cancel();
                                this.playing = true;
                                // Chờ 60ms để Chrome dọn dẹp hàng đợi audio trước khi speak lượt mới
                                this.timer = setTimeout(() => {
                                    if (synth.paused) {
                                        synth.resume();
                                    }
                                    const utterance = new SpeechSynthesisUtterance(word);
                                    utterance.lang = 'zh-CN';
                                    utterance.rate = 0.85;
                                    // Gán giọng tiếng Trung phù hợp
                                    const voices = synth.getVoices();
                                    const zhVoice = voices.find(v => 
                                        v.lang === 'zh-CN' || v.lang === 'zh_CN' || 
                                        v.lang.startsWith('zh') || v.lang.startsWith('cmn')
                                    );
                                    if (zhVoice) {
                                        utterance.voice = zhVoice;
                                    }
                                    utterance.onend = () => {
                                        this.playing = false;
                                        window._activeUtterance = null;
                                    };
                                    utterance.onerror = () => {
                                        this.playing = false;
                                        window._activeUtterance = null;
                                    };
                                    // Giữ biến toàn cục tránh Garbage Collector giải phóng sớm
                                    window._activeUtterance = utterance;
                                    synth.speak(utterance);
                                }, 60);
                            }
                        }" class="lms-card p-6 space-y-3 relative group">
                            <div class="flex items-center justify-between text-xs text-[#e07a5f] font-bold">
                                <span>Từ vựng hôm nay</span>
                                <span class="px-2 py-0.5 bg-[#fff2ee] dark:bg-slate-800 rounded text-[10px] font-bold">HSK {{ $wordOfDay->level ?? 5 }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div>
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white zh-text">{{ $wordOfDay->word ?? '坚持' }}</span>
                                        <span class="text-sm font-bold text-[#e07a5f]">{{ $wordOfDay->pinyin ?? 'jiān chí' }}</span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-1">{{ $wordOfDay->meaning ?? 'Động từ: Kiên trì, giữ vững mục tiêu' }}</p>
                                </div>
                                <button type="button" 
                                    @click="playAudio()" 
                                    class="w-11 h-11 rounded-full flex items-center justify-center transition-all btn-tactile shadow-xs shrink-0 cursor-pointer focus:outline-none select-none"
                                    :class="playing ? 'bg-[#e07a5f] text-white ring-4 ring-[#e07a5f]/25 scale-105' : 'bg-[#fff2ee] dark:bg-slate-800 text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white'" 
                                    title="{{ __('Nghe phát âm') }}">
                                    <i class="fa-solid fa-volume-high text-sm leading-none pointer-events-none select-none" :class="playing ? 'animate-pulse' : ''"></i>
                                </button>
                            </div>
                            @if(!empty($wordOfDay->example))
                            <div class="p-3.5 bg-[#faf6f2] dark:bg-slate-800/80 rounded-xl border border-[#e8e2d9] dark:border-slate-700 text-xs">
                                <p class="text-slate-900 dark:text-white zh-text font-medium leading-relaxed">
                                    例句: {{ $wordOfDay->example }}
                                </p>
                                @if(!empty($wordOfDay->example_meaning))
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-normal">
                                    ({{ $wordOfDay->example_meaning }})
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="lms-card p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 tracking-tight">
                                    <i class="fa-solid fa-globe text-[#e07a5f]"></i> Kết nối cùng XIAOMU
                                </h3>
                                <span class="text-[10px] font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-slate-800 px-2 py-0.5 rounded">Cộng đồng</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal leading-relaxed">
                                Theo dõi các kênh truyền thông chính thức để nhận mẹo thi HSK và tài liệu tiếng Trung mỗi ngày!
                            </p>
                            <div class="grid grid-cols-2 gap-2.5 pt-1">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/profile.php?id=61589009699142" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-[#1877f2] text-white flex items-center justify-center text-sm shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">Facebook</span>
                                </a>
                                <!-- YouTube -->
                                <a href="https://www.youtube.com/@Chiettuchuhan" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-[#FF0000] text-white flex items-center justify-center text-sm shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-brands fa-youtube"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">YouTube</span>
                                </a>
                                <!-- TikTok -->
                                <a href="https://www.tiktok.com/@chiettuchuhan55" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-black text-white border border-slate-700/30 flex items-center justify-center text-sm shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-brands fa-tiktok"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">TikTok</span>
                                </a>
                                <!-- Zalo Official -->
                                <a href="https://zalo.me/0395294739" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2a2624] hover:bg-[#fff2ee] dark:hover:bg-[#28201d] hover:border-[#fcdccf] transition-all btn-tactile group">
                                    <div class="w-8 h-8 rounded-lg bg-[#0068ff] text-white font-black text-xs flex items-center justify-center shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                                        Zalo
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">Zalo</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white dark:bg-[#141211] border-t border-[#e8e2d9] dark:border-[#262220] flex items-center justify-around py-2.5 px-2 z-30 shadow-md">
                <a href="{{ url('/demo-ui') }}" class="flex flex-col items-center gap-0.5 text-[#e07a5f] btn-tactile">
                    <i class="fa-solid fa-house text-base"></i>
                    <span class="text-[10px] font-bold">Trang chủ</span>
                </a>
                <a href="{{ url('/demo-courses') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-book-open text-base"></i>
                    <span class="text-[10px] font-medium">Khóa học</span>
                </a>
                <a href="{{ url('/demo-exams') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-file-pen text-base"></i>
                    <span class="text-[10px] font-medium">Luyện thi</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-700 dark:hover:text-white btn-tactile">
                    <i class="fa-solid fa-user text-base"></i>
                    <span class="text-[10px] font-medium">Cá nhân</span>
                </a>
            </div>
@endsection
