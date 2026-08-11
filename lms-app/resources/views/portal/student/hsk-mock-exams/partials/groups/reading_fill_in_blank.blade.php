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

<div class="mb-4">
    <div class="inline-flex items-center gap-2 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-300">
        <span class="material-symbols-outlined text-[18px]">edit_note</span>
        <span class="tracking-wide uppercase">{{ $group->title ?? 'Part' }}</span>
        @if($group->questions->count() > 0)
            <span class="opacity-50 mx-1">|</span>
            <span class="text-xs font-semibold">
                {{ $group->questions->min('order_index') }} - {{ $group->questions->max('order_index') }}
            </span>
        @endif
    </div>
</div>

@if(count($textOptions) > 0)
    {{-- Text Options Bank (A-F) --}}
    <div class="bg-gradient-to-b from-slate-50 via-white to-slate-50/80 dark:from-slate-900/90 dark:via-slate-900/70 dark:to-slate-900/90 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 p-4 md:p-6 mb-6 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-200">Danh sách các từ để chọn</span>
            </div>
            @if($exLetter)
            <span class="text-[10px] bg-emerald-600/10 text-emerald-600 dark:text-emerald-400 px-2.5 py-1 rounded-md font-black tracking-wider uppercase flex items-center gap-1">
                Ví dụ: {{ $exLetter }}
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
                <div class="p-3.5 rounded-2xl border flex items-center justify-between transition-all duration-150 relative shadow-2xs group/optcard
                    {{ $isExAns 
                        ? 'bg-amber-500/10 dark:bg-amber-950/40 border-amber-400 dark:border-amber-500/80 ring-1 ring-amber-400/30' 
                        : 'bg-white dark:bg-slate-800/90 border-slate-200 dark:border-slate-700/80 hover:border-primary/50 hover:shadow-xs' }}">
                    
                    <div class="flex items-center gap-4 min-w-0">
                        <span class="w-8 h-8 rounded-xl text-sm font-black flex items-center justify-center shrink-0 shadow-2xs transition-colors
                            {{ $isExAns 
                                ? 'bg-amber-500 text-white' 
                                : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 group-hover/optcard:bg-primary group-hover/optcard:text-white' }}">
                            {{ $optLetter }}
                        </span>
                        <div class="flex-1 flex flex-wrap items-end gap-x-2 gap-y-1 text-base font-bold text-slate-800 dark:text-slate-100">
                            {!! $optText !!}
                        </div>
                    </div>

                    @if($isExAns)
                        <span class="px-2 py-1 rounded-lg bg-amber-500 text-white text-[10px] font-black uppercase tracking-wider shrink-0 ml-2 shadow-2xs">
                            Ví dụ
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Example Card --}}
@if($examples->count() > 0)
<div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
    <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
        @if($exLetter)
            <span class="text-xs font-bold text-emerald-600">Đáp án mẫu: {{ $exLetter }}</span>
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
            <div class="text-xl font-bold text-slate-800 dark:text-slate-100">
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
        <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 p-4 md:p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm flex flex-col lg:flex-row gap-5 lg:gap-8 lg:items-center"
             id="q-{{ $currentQNum }}">
             
            <div class="flex-1 flex items-center gap-4 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-sm flex items-center justify-center shrink-0">
                    {{ $currentQNum }}
                </div>
                <div class="flex-1 text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed min-w-0">
                    @if ($question->title)
                        <div class="flex flex-wrap items-end gap-x-2 gap-y-1">
                            {!! function_exists('renderHskRubyText') ? renderHskRubyText($question->title) : $question->title !!}
                        </div>
                    @else
                        <span class="text-sm font-semibold italic text-slate-400">Chọn đáp án điền vào chỗ trống:</span>
                    @endif
                </div>
            </div>
            
            <div class="flex-1 lg:flex-none flex flex-wrap items-center gap-2 pt-2 lg:pt-0 lg:justify-end">
                @foreach($optLabels as $letter)
                    @if($letter === $exLetter) @continue @endif
                    <label class="cursor-pointer group flex-1 md:flex-none">
                        <input type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $letter }}"
                            onchange="updateSidebar({{ $currentQNum }})"
                            class="peer hidden">
                        <div class="h-12 w-full lg:w-12 xl:w-14 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center text-lg font-black text-slate-400 dark:text-slate-500 transition-all peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary hover:border-primary/50 hover:bg-slate-50 dark:hover:bg-slate-800">
                            {{ $letter }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
