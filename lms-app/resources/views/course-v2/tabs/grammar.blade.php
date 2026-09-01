<!-- TAB 3: NGỮ PHÁP (GRAMMAR) -->
<style>
    .hide-pinyin-rt rt {
        display: none !important;
    }
    .grammar-ruby rt:not(.text-transparent) {
        color: #e07a5f !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        line-height: 1 !important;
        user-select: none;
    }
    .dark .grammar-ruby rt:not(.text-transparent) {
        color: #f4978e !important;
    }
</style>
<div x-show="activeTab === 'ngu-phap'" 
     x-data="{ 
         grammarFilter: 'all', 
         showExamplePinyin: {{ hsk_should_show_pinyin($currentLesson->level ?? null) ? 'true' : 'false' }}
     }" 
     class="space-y-5">

    @if(isset($currentLesson) && $currentLesson->grammarList && $currentLesson->grammarList->count() > 0)
        <!-- Thanh điều khiển & Bộ lọc trọng điểm ngữ pháp -->
        <div class="lms-card p-4 sm:p-5 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f4978e] flex items-center justify-center text-base font-bold shrink-0">
                    <i class="fa-solid fa-spell-check"></i>
                </div>
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Quy tắc ngữ pháp') }}</h2>
                        <span class="px-2 py-0.5 rounded-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-bold text-[#e07a5f]">
                            {{ $currentLesson->grammarList->count() }} {{ __('trọng điểm') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                        {{ __('Nắm vững công thức, cách dùng và ngữ cảnh qua các ví dụ thực tế.') }}
                    </p>
                </div>
            </div>

            <!-- Toggles hiển thị Pinyin / Bản dịch cho ví dụ -->
            <div class="flex items-center gap-1.5 p-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl self-start md:self-auto overflow-x-auto no-scrollbar">
                <button @click="showExamplePinyin = !showExamplePinyin" 
                        :class="showExamplePinyin ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-700 dark:hover:text-slate-300'" 
                        class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 shrink-0"
                        title="{{ __('Bật/tắt hiển thị phiên âm Pinyin') }}">
                    <i class="fa-solid fa-eye" x-show="showExamplePinyin"></i>
                    <i class="fa-solid fa-eye-slash opacity-50" x-show="!showExamplePinyin"></i>
                    <span>Pinyin</span>
                </button>
            </div>
        </div>

        @php
            // Đếm số lần xuất hiện của từng tiêu đề để tự động đánh số (1), (2)... nếu trùng lặp
            $titleCounts = [];
            $titleCurrentIndex = [];
            foreach ($currentLesson->grammarList as $g) {
                $t = trim($g->title);
                $titleCounts[$t] = ($titleCounts[$t] ?? 0) + 1;
            }
        @endphp

        <!-- Quick Jump Pills (Thanh nhảy nhanh giữa các điểm ngữ pháp - Ưu tiên tiếng Việt cho HSK 1-2) -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
            <button @click="grammarFilter = 'all'" 
                    :class="grammarFilter === 'all' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all btn-tactile flex items-center gap-1.5">
                <i class="fa-solid fa-layer-group text-[10px]"></i>
                <span>{{ __('Tất cả') }} ({{ $currentLesson->grammarList->count() }})</span>
            </button>

            @foreach ($currentLesson->grammarList as $idx => $grammar)
                @php
                    $rawTitle = trim($grammar->title);
                    $titleParts = preg_split('/\s*[\(（]\s*/u', $rawTitle);
                    $zhTitle = trim($titleParts[0] ?? $rawTitle);
                    $vnTitle = isset($titleParts[1]) ? trim(rtrim($titleParts[1], ')）')) : '';

                    $isDuplicate = ($titleCounts[$rawTitle] ?? 1) > 1;
                    $titleCurrentIndex[$rawTitle] = ($titleCurrentIndex[$rawTitle] ?? 0) + 1;
                    $partSuffix = $isDuplicate ? ' (P.' . $titleCurrentIndex[$rawTitle] . ')' : '';

                    // Tiêu đề nút tab: Tiếng Việt rõ ràng cho người mới bắt đầu HSK 1-2
                    $navTitle = $vnTitle ? ($vnTitle . $partSuffix) : ($zhTitle . $partSuffix);
                @endphp
                <button @click="grammarFilter = 'rule-{{ $grammar->id }}'" 
                        :class="grammarFilter === 'rule-{{ $grammar->id }}' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" 
                        class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all btn-tactile flex items-center gap-1.5"
                        title="{{ $rawTitle }}">
                    <span class="w-4 h-4 rounded-md bg-black/10 dark:bg-white/10 flex items-center justify-center text-[10px] font-bold">{{ $idx + 1 }}</span>
                    <span>{{ $navTitle }}</span>
                </button>
            @endforeach
        </div>

        <!-- Danh sách thẻ ngữ pháp chi tiết -->
        <div class="space-y-6">
            @php
                // Reset bộ đếm số phần cho các thẻ chi tiết bên dưới
                $cardTitleCurrentIndex = [];
            @endphp
            @foreach ($currentLesson->grammarList as $idx => $grammar)
                @php
                    // Xử lý tiêu đề Hán tự & Tiếng Việt
                    $rawTitle = trim($grammar->title);
                    $titleParts = preg_split('/\s*[\(（]\s*/u', $rawTitle);
                    $zhTitle = trim($titleParts[0] ?? $rawTitle);
                    $vnTitle = isset($titleParts[1]) ? trim(rtrim($titleParts[1], ')）')) : '';

                    $isDuplicate = ($titleCounts[$rawTitle] ?? 1) > 1;
                    $cardTitleCurrentIndex[$rawTitle] = ($cardTitleCurrentIndex[$rawTitle] ?? 0) + 1;
                    $partSuffix = $isDuplicate ? ' (' . __('Phần') . ' ' . $cardTitleCurrentIndex[$rawTitle] . ')' : '';

                    // Hỗ trợ cả formula và structure
                    $structureFormula = $grammar->formula ?: $grammar->structure;

                    // Parse examples an toàn từ nhiều định dạng dữ liệu
                    $rawExamples = $grammar->examples;
                    $examples = [];
                    if (is_array($rawExamples)) {
                        $examples = $rawExamples;
                    } elseif (is_object($rawExamples)) {
                        $examples = (array)$rawExamples;
                    } elseif (is_string($rawExamples)) {
                        $trimmed = trim($rawExamples);
                        if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
                            $decoded = json_decode($trimmed, true);
                            $examples = is_array($decoded) ? $decoded : [];
                        } else {
                            $examples = array_values(array_filter(explode("\n", $trimmed)));
                        }
                    }
                @endphp

                <div x-show="grammarFilter === 'all' || grammarFilter === 'rule-{{ $grammar->id }}'" 
                     class="lms-card p-5 sm:p-7 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl space-y-5 transition-all shadow-xs"
                     id="grammar-rule-{{ $grammar->id }}">
                    
                    <!-- Header Thẻ: Số thứ tự + Tên điểm ngữ pháp Tiếng Việt (chính) & Hán tự (phụ) -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#e8e2d9] dark:border-[#2d2926] pb-4 gap-3">
                        <div class="flex items-start sm:items-center gap-3.5">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#fff2ee] to-[#fcdccf] dark:from-[#2c221e] dark:to-[#382620] text-[#e07a5f] border border-[#fcdccf] dark:border-[#4a2e26] flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs">
                                {{ $idx + 1 }}
                            </div>
                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Tiêu đề Tiếng Việt rõ ràng cho người học dễ hiểu -->
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-wide">
                                        {{ $vnTitle ? ($vnTitle . $partSuffix) : $zhTitle }}
                                    </h3>
                                    
                                    <!-- Badge Chữ Hán -->
                                    @if($vnTitle && $zhTitle)
                                        <span class="px-2.5 py-0.5 rounded-lg bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] text-[#e07a5f] text-xs font-bold zh-text">
                                            {{ $zhTitle }}
                                        </span>
                                    @endif

                                    @if($grammar->type)
                                        <span class="px-2 py-0.5 rounded-md bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            {{ $grammar->type }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 1. CÔNG THỨC / CẤU TRÚC (FORMULA BANNER) -->
                    @if($structureFormula)
                        <div class="p-4 rounded-xl bg-gradient-to-r from-[#fff7f4] via-[#fcfaf7] to-transparent dark:from-[#23201e] dark:via-[#1e1b19] dark:to-transparent border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-[10px] font-bold text-[#e07a5f] uppercase tracking-widest">
                                    <i class="fa-solid fa-shapes"></i>
                                    <span>{{ __('Cấu trúc công thức') }}</span>
                                </div>
                                <button onclick="navigator.clipboard.writeText('{{ addslashes($structureFormula) }}'); alert('{{ __('Đã sao chép công thức!') }}');" 
                                        class="text-[11px] font-semibold text-slate-400 hover:text-[#e07a5f] transition-colors flex items-center gap-1 btn-tactile" 
                                        title="{{ __('Sao chép công thức') }}">
                                    <i class="fa-regular fa-copy"></i>
                                    <span>{{ __('Sao chép') }}</span>
                                </button>
                            </div>
                            <div class="text-base sm:text-lg font-mono font-bold text-slate-900 dark:text-white tracking-wide break-words grammar-ruby flex flex-wrap items-end gap-x-1 gap-y-2 pt-1">
                                {!! function_exists('hsk_render_pinyin') ? hsk_render_pinyin($structureFormula) : e($structureFormula) !!}
                            </div>
                        </div>
                    @endif

                    <!-- 2. GIẢI THÍCH Ý NGHĨA VÀ CÁCH DÙNG -->
                    @if($grammar->explanation)
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                <i class="fa-solid fa-circle-info text-[#e07a5f]"></i>
                                <span>{{ __('Giải thích chi tiết') }}</span>
                            </div>
                            <div class="p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-xs sm:text-sm text-slate-700 dark:text-slate-300 font-medium leading-relaxed prose dark:prose-invert max-w-none">
                                {!! nl2br(e($grammar->explanation)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- 3. CÁC CÂU VÍ DỤ MINH HỌA -->
                    @if(!empty($examples))
                        <div class="space-y-3 pt-1">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    <i class="fa-solid fa-quote-left text-[#e07a5f]"></i>
                                    <span>{{ __('Câu ví dụ mẫu') }}</span>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400">
                                    {{ count($examples) }} {{ __('câu') }}
                                </span>
                            </div>

                            <div class="space-y-2.5">
                                @foreach($examples as $exIdx => $example)
                                    @php
                                        // Chuẩn hóa dữ liệu câu ví dụ
                                        if (is_string($example)) {
                                            $char = trim($example);
                                            $pinyin = '';
                                            $trans = '';
                                        } else {
                                            $char = is_array($example) 
                                                ? ($example['character'] ?? $example['zh'] ?? $example['cn'] ?? '') 
                                                : ($example->character ?? $example->zh ?? $example->cn ?? '');
                                            $pinyin = is_array($example) 
                                                ? ($example['pinyin'] ?? '') 
                                                : ($example->pinyin ?? '');
                                            $trans = is_array($example) 
                                                ? ($example['translation'] ?? $example['vn'] ?? $example['vi'] ?? $example['meaning'] ?? '') 
                                                : ($example->translation ?? $example->vn ?? $example->vi ?? $example->meaning ?? '');
                                        }
                                        $audioText = $char ?: (is_string($example) ? $example : '');
                                    @endphp

                                    <div class="p-3.5 sm:p-4 rounded-xl bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] flex items-start justify-between gap-3 group/ex hover:border-[#e07a5f]/40 dark:hover:border-[#e07a5f]/40 transition-all">
                                        
                                        <div class="flex flex-col gap-2 flex-1 min-w-0">
                                            
                                            <!-- Row 1: Index Icon + Chinese Text -->
                                            <div class="flex items-center gap-3">
                                                <!-- Index icon / arrow -->
                                                <div class="w-6 h-6 rounded-lg bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] text-[11px] font-bold flex items-center justify-center shrink-0 group-hover/ex:bg-[#e07a5f] group-hover/ex:text-white transition-colors">
                                                    {{ $exIdx + 1 }}
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    @php
                                                        // Tạo chuỗi ruby có pinyin trên đầu chữ Hán
                                                        $rubyHtml = '';
                                                        if (!empty($char)) {
                                                            if (!empty($pinyin) && function_exists('renderHskRubyText')) {
                                                                $rubyHtml = renderHskRubyText($char, $pinyin, $char);
                                                            } elseif (function_exists('hsk_render_pinyin')) {
                                                                $rubyHtml = hsk_render_pinyin($char);
                                                            }
                                                        }
                                                    @endphp

                                                    <!-- Chữ Hán kèm Pinyin trên đầu (Ruby Text) -->
                                                    @if($char)
                                                        <div class="grammar-ruby zh-text text-base sm:text-lg font-bold text-slate-900 dark:text-white leading-relaxed tracking-wide break-words select-text flex flex-wrap items-end gap-x-1 gap-y-2"
                                                             :class="{ 'hide-pinyin-rt': !showExamplePinyin }">
                                                            @if(!empty($rubyHtml))
                                                                {!! $rubyHtml !!}
                                                            @else
                                                                {{ $char }}
                                                            @endif
                                                        </div>
                                                    @elseif(is_string($example))
                                                        <div class="zh-text text-base font-medium text-slate-800 dark:text-slate-200">
                                                            {{ trim($example) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Row 2: Nghĩa tiếng Việt -->
                                            @if($trans)
                                                <div>
                                                    <div class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-400 pt-2 border-t border-[#e8e2d9]/60 dark:border-[#2d2926] flex items-start gap-1.5 ml-9">
                                                        <span class="text-[#e07a5f] font-bold shrink-0">{{ __('Nghĩa:') }}</span>
                                                        <span class="leading-relaxed">{{ $trans }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Nút phát âm thanh mẫu câu -->
                                        @if($audioText)
                                            <button onclick="window.playAudio('{{ addslashes($audioText) }}')" 
                                                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-400 hover:text-[#e07a5f] hover:border-[#e07a5f] flex items-center justify-center shrink-0 transition-all btn-tactile shadow-2xs mt-1"
                                                    title="{{ __('Nghe phát âm câu ví dụ') }}">
                                                <i class="fa-solid fa-volume-high text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        <!-- Banner Chuyển tiếp sang Luyện tập ngữ pháp -->
        <div class="lms-card p-5 bg-gradient-to-r from-[#fff7f4] via-[#fcfaf7] to-white dark:from-[#23201e] dark:via-[#1e1b19] dark:to-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5 text-left">
                <div class="w-10 h-10 rounded-xl bg-[#e07a5f] text-white flex items-center justify-center text-lg font-bold shrink-0 shadow-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Đã nắm vững các điểm ngữ pháp?') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Thực hành làm bài tập củng cố để ghi nhớ quy tắc ngữ pháp lâu hơn.') }}</p>
                </div>
            </div>
            <a href="{{ route('courses.lesson', ['levelSlug' => $currentLevel->slug, 'lessonSlug' => $currentLesson->slug, 'tab' => 'luyen-tap']) }}" 
               class="px-5 py-2.5 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile flex items-center gap-2 shrink-0 shadow-xs">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>{{ __('Chuyển sang Luyện tập') }}</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

    @else
        <x-lms.empty-state 
            icon="fa-solid fa-spell-check"
            :title="__('Chưa có nội dung ngữ pháp')"
            :description="__('Nội dung ngữ pháp cho bài học này đang được biên soạn và sẽ sớm ra mắt. Vui lòng quay lại sau.')"
        />
    @endif
</div>