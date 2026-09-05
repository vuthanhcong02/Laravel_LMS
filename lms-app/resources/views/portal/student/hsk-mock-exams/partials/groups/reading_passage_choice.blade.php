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
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] p-5 md:p-6 mb-6 shadow-xs relative overflow-hidden">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926]">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">{{ __('Danh sách đáp án') }}</span>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($p3Options as $idx => $opt)
            @php
                $optLetter = $opt['letter'] ?? chr(65 + $idx);
                $isExAns = ($optLetter === $exLetter);
                $optText = renderHskRubyText($opt['html'] ?? '', $opt['pinyin'] ?? '', $opt['hanzi'] ?? '');
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
{{-- Dynamic Example Card from is_example questions --}}
@if($examples->count() > 0)
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 space-y-4 shadow-xs">
    <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-200 text-sm">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
    </div>
    @foreach($examples as $ex)
        <div class="p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">
            @if($ex->image)
                <div class="mb-4 flex justify-center">
                    <img src="{{ hsk_storage_url($ex->image) }}" class="max-h-32 object-contain rounded-xl" alt="Ex">
                </div>
            @endif
            @if($ex->title)
                <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 text-center mb-4 zh-text leading-relaxed">
                    {!! renderHskRubyText($ex->title) !!}
                </div>
            @endif
            <div class="flex flex-wrap items-center justify-center gap-2.5">
                @foreach ($ex->options as $idx => $option)
                    @php
                        $optLetter = $option->content === '√' || $option->content === '✕' ? '' : chr(65 + $idx);
                        $isCorrect = $option->is_correct;
                        $bgClass = $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500' : 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] opacity-60';
                    @endphp
                    <div class="px-4 py-2.5 rounded-2xl border {{ $bgClass }} flex items-center gap-2">
                        @if($optLetter)
                            <span class="font-bold text-xs {{ $isCorrect ? 'text-emerald-600' : 'text-slate-500' }}">{{ $optLetter }}</span>
                        @endif
                        <span class="font-bold text-xs sm:text-sm {{ $isCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300' }} zh-text">{!! renderHskRubyText($option->content) !!}</span>
                        @if($isCorrect)<span class="text-emerald-600 text-xs font-bold ml-1">✓</span>@endif
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
            $excludedLetters = [$exLetter];
        @endphp
        <div class="q-card scroll-mt-24 p-5 sm:p-6 bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs"
             id="q-{{ $currentQNum }}">
            <div class="flex flex-col lg:flex-row gap-5 lg:items-center justify-between">
                {{-- Left: Question Text --}}
                <div class="flex-1 flex items-start gap-4">
                    <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        {{ $currentQNum }}
                    </div>
                    <div class="flex-1 text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed min-w-0 zh-text">
                        @if ($question->title)
                            <div class="flex flex-wrap items-end gap-x-2 gap-y-1">
                                {!! renderHskRubyText($question->title) !!}
                            </div>
                        @else
                            <span class="text-xs font-semibold italic text-slate-400">{{ __('Chọn đáp án') }}:</span>
                        @endif
                    </div>
                </div>
                {{-- Right: Options (A-F inline) --}}
                <div class="flex flex-wrap lg:justify-end items-center gap-2.5 pt-3 lg:pt-0 border-t lg:border-t-0 border-[#e8e2d9] dark:border-[#2d2926]">
                    @foreach ($question->options as $option)
                        @php $optContent = trim($option->content ?? ''); @endphp
                        @if (in_array($optContent, $excludedLetters))
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] bg-slate-100 dark:bg-[#201d1b] flex items-center justify-center opacity-40 cursor-not-allowed shadow-2xs">
                                <span class="text-slate-400 dark:text-slate-500 font-bold text-xs">{{ $optContent }}</span>
                            </div>
                        @else
                            <label class="cursor-pointer group relative block shrink-0">
                                <input type="radio" name="answers[{{ $question->id }}]"
                                    class="peer hidden" value="{{ $option->id }}"
                                    onchange="updateSidebar({{ $currentQNum }})">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] flex items-center justify-center transition-all peer-checked:border-[#e07a5f] peer-checked:bg-[#e07a5f] peer-checked:text-white hover:border-[#e07a5f]/50 btn-tactile shadow-xs">
                                    <div class="text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors group-hover:text-[#e07a5f] peer-checked:text-white zh-text">
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
