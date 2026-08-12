@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
    $exData = null;
    $p3Options = [];
    if ($group->passage_text && str_starts_with(trim($group->passage_text), '{')) {
        $parsedEx = json_decode(trim($group->passage_text), true);
        if (isset($parsedEx['options'])) {
            $p3Options = $parsedEx['options'];
            $exData = $parsedEx;
        } else {
            $exData = $parsedEx;
        }
    }
    $exLetter = $exData['ex_a_letter'] ?? $exData['a_letter'] ?? 'D';
@endphp



{{-- Options Bank (A-F) --}}
@if(count($p3Options) > 0)
<div class="bg-gradient-to-b from-slate-50 via-white to-slate-50/80 dark:from-slate-900/90 dark:via-slate-900/70 dark:to-slate-900/90 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 p-4 md:p-6 mb-6 shadow-sm relative overflow-hidden">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
            <span class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-200">Danh sách đáp án</span>
        </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($p3Options as $idx => $opt)
            @php
                $optLetter = $opt['letter'] ?? chr(65 + $idx);
                $isExAns = ($optLetter === $exLetter);
                $optText = renderHskRubyText($opt['html'] ?? '', $opt['pinyin'] ?? '', $opt['hanzi'] ?? '');
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

{{-- Dynamic Example Card from is_example questions --}}
@if($examples->count() > 0)
<div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6 space-y-3">
    <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
    </div>
    @foreach($examples as $ex)
        <div class="p-4 bg-white/60 dark:bg-slate-800/60 rounded-2xl border border-amber-200/50 dark:border-slate-700 shadow-sm opacity-80">
            @if($ex->image)
                <div class="mb-4 flex justify-center">
                    <img src="{{ hsk_storage_url($ex->image) }}" class="max-h-32 object-contain rounded-lg" alt="Ex">
                </div>
            @endif
            @if($ex->title)
                <div class="text-lg font-bold text-slate-800 dark:text-slate-100 text-center mb-4">
                    {!! renderHskRubyText($ex->title) !!}
                </div>
            @endif
            <div class="flex flex-wrap items-center justify-center gap-3">
                @foreach ($ex->options as $idx => $option)
                    @php
                        $optLetter = $option->content === '√' || $option->content === '✕' ? '' : chr(65 + $idx);
                        $isCorrect = $option->is_correct;
                        $bgClass = $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 opacity-60';
                    @endphp
                    <div class="px-4 py-2 rounded-xl border-2 {{ $bgClass }} flex items-center gap-2">
                        @if($optLetter)
                            <span class="font-black {{ $isCorrect ? 'text-emerald-600' : 'text-slate-500' }}">{{ $optLetter }}</span>
                        @endif
                        <span class="font-bold {{ $isCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300' }}">{!! renderHskRubyText($option->content) !!}</span>
                        @if($isCorrect)<span class="text-emerald-600 text-sm font-black ml-1">✓</span>@endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@endif
{{-- Questions List --}}
<div class="space-y-4">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
            $excludedLetters = [$exLetter]; // Usually the example letter is excluded
        @endphp
        <div class="q-card scroll-mt-24 p-5 bg-white dark:bg-slate-800/80 rounded-3xl border border-slate-200 dark:border-slate-700/80 shadow-sm relative overflow-hidden"
             id="q-{{ $currentQNum }}">
            
            <div class="flex flex-col lg:flex-row gap-5">
                {{-- Left: Question Text --}}
                <div class="flex-1 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        {{ $currentQNum }}
                    </div>

                    <div class="flex-1 text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed min-w-0">
                        @if ($question->title)
                            <div class="flex flex-wrap items-end gap-x-2 gap-y-1">
                                {!! renderHskRubyText($question->title) !!}
                            </div>
                        @else
                            <span class="text-sm font-semibold italic text-slate-400">Chọn đáp án:</span>
                        @endif
                    </div>
                </div>

                {{-- Right: Options (A-F inline) --}}
                <div class="flex flex-wrap lg:justify-end items-center gap-2 pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-100 dark:border-slate-700/50">
                    <span class="text-xs font-semibold text-slate-400 mr-2 select-none w-full lg:w-auto mb-2 lg:mb-0">Đáp án:</span>
                    @foreach ($question->options as $option)
                        @php $optContent = trim($option->content ?? ''); @endphp
                        @if (in_array($optContent, $excludedLetters))
                            <div class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 flex items-center justify-center opacity-40 cursor-not-allowed">
                                <span class="text-slate-400 dark:text-slate-500 font-black text-sm">{{ $optContent }}</span>
                            </div>
                        @else
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="answers[{{ $question->id }}]"
                                    class="peer hidden" value="{{ $option->id }}"
                                    onchange="updateSidebar({{ $currentQNum }})">
                                <div class="w-10 h-10 rounded-xl border-2 border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-900 flex items-center justify-center transition-all duration-150
                                    hover:border-primary/50 hover:bg-slate-50 dark:hover:bg-slate-800
                                    peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-sm">
                                    <div class="text-slate-600 dark:text-slate-300 font-black text-sm transition-colors group-hover:text-primary peer-checked:text-primary">
                                        {!! renderHskRubyText($optContent) !!}
                                    </div>
                                </div>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
