@php
    $parsedEx = $group->passage_text && str_starts_with(trim($group->passage_text), '{') 
                ? json_decode(trim($group->passage_text), true) : null;
    $textOptions = [];
    $exLetter = '';
    if ($parsedEx && isset($parsedEx['options'])) {
        $textOptions = $parsedEx['options'];
        $exLetter = $parsedEx['ex_a_letter'] ?? $parsedEx['a_letter'] ?? '';
    }
    $optLabels = [];
    foreach ($textOptions as $idx => $opt) {
        $optLabels[] = $opt['letter'] ?? chr(65 + $idx);
    }
    $examples = $group->questions->where('is_example', true)->sortBy('order_index');
    $realQuestions = $group->questions->where('is_example', false)->sortBy('order_index');
@endphp
@if(count($textOptions) > 0)
    {{-- Text Options Bank (A-F) --}}
    <div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 md:p-6 mb-6 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">{{ __('Danh sách các từ để chọn') }}</span>
            </div>
            @if($exLetter)
            <span class="text-[11px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 px-2.5 py-0.5 rounded-lg font-bold uppercase tracking-wider flex items-center gap-1">
                {{ __('Ví dụ') }}: {{ $exLetter }}
            </span>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($textOptions as $idx => $opt)
                @php
                    $optLetter = $opt['letter'] ?? chr(65 + $idx);
                    $isExAns = ($optLetter === $exLetter);
                    $optText = function_exists('renderHskRubyText') ? renderHskRubyText($opt['html'] ?? '', $opt['pinyin'] ?? '', $opt['hanzi'] ?? '') : ($opt['html'] ?? '');
                @endphp
                <div class="p-3.5 rounded-2xl border flex items-center justify-between transition-all relative
                    {{ $isExAns 
                        ? 'bg-amber-500/10 dark:bg-amber-950/40 border-amber-400 dark:border-amber-500/80' 
                        : 'bg-white dark:bg-[#181615] border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/50 shadow-xs' }}">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <span class="w-8 h-8 rounded-xl text-xs font-bold flex items-center justify-center shrink-0 shadow-xs transition-colors
                            {{ $isExAns 
                                ? 'bg-amber-500 text-white' 
                                : 'bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] border border-[#fcdccf] dark:border-[#42271f]' }}">
                            {{ $optLetter }}
                        </span>
                        <div class="flex-1 flex flex-wrap items-end gap-x-2 gap-y-1 text-sm sm:text-base font-bold text-slate-800 dark:text-slate-100 zh-text leading-relaxed">
                            {!! $optText !!}
                        </div>
                    </div>
                    @if($isExAns)
                        <span class="px-2 py-0.5 rounded-lg bg-amber-500 text-white text-[10px] font-bold uppercase tracking-wider shrink-0 ml-2 shadow-xs">
                            {{ __('Ví dụ') }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
{{-- Example Card --}}
@if($examples->count() > 0)
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 shadow-xs">
    <div class="flex items-center justify-between font-bold text-slate-800 dark:text-slate-200 text-sm">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
        @if($exLetter)
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-lg">{{ __('Đáp án mẫu') }}: {{ $exLetter }}</span>
        @endif
    </div>
    @if($examples->first()->title)
        @php
            $exTitle = collect(explode("\n", $examples->first()->title))
                ->map(fn($line) => trim($line))
                ->filter()
                ->join("\n");
        @endphp
        <div class="mt-4 flex justify-center lg:justify-start">
            <div class="text-lg font-bold text-slate-800 dark:text-slate-100 zh-text leading-relaxed">
                {!! function_exists('renderHskRubyText') ? renderHskRubyText($exTitle) : $exTitle !!}
            </div>
        </div>
    @endif
</div>
@endif
{{-- Questions List --}}
<div class="space-y-4">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
        @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-[#181615] p-5 sm:p-6 rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs flex flex-col lg:flex-row gap-5 lg:gap-8 lg:items-center justify-between"
             id="q-{{ $currentQNum }}">
            <div class="flex-1 flex items-center gap-4 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0">
                    {{ $currentQNum }}
                </div>
                <div class="flex-1 text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed min-w-0 zh-text">
                    @if ($question->title)
                        <div class="flex flex-wrap items-end gap-x-2 gap-y-1">
                            {!! function_exists('renderHskRubyText') ? renderHskRubyText($question->title) : $question->title !!}
                        </div>
                    @else
                        <span class="text-xs font-semibold italic text-slate-400">{{ __('Chọn đáp án điền vào chỗ trống') }}:</span>
                    @endif
                </div>
            </div>
            <div class="flex-1 lg:flex-none flex flex-wrap items-center gap-2.5 pt-2 lg:pt-0 lg:justify-end">
                @foreach($optLabels as $letter)
                    @if($letter === $exLetter) @continue @endif
                    <label class="cursor-pointer group block shrink-0">
                        <input type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $letter }}"
                            onchange="updateSidebar({{ $currentQNum }})"
                            class="peer hidden">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] flex items-center justify-center text-sm font-bold text-slate-700 dark:text-slate-300 transition-all peer-checked:border-[#e07a5f] peer-checked:bg-[#e07a5f] peer-checked:text-white hover:border-[#e07a5f]/50 btn-tactile shadow-xs">
                            {{ $letter }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
