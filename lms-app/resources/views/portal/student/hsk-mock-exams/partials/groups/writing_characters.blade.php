@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false)->sortBy('order_index');
@endphp

@if ($group->passage_text && !str_starts_with(trim($group->passage_text), '{'))
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] px-5 py-4 mb-5 text-sm font-semibold text-slate-600 dark:text-slate-300 zh-text leading-relaxed shadow-xs">
    {!! renderHskRubyText($group->passage_text) !!}
</div>
@endif

@if ($examples->count() > 0)
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 space-y-3 shadow-xs">
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
    </div>
    @foreach ($examples as $ex)
        @php
            $exTitle = $ex->title ?? '';
            $exCorrect = $ex->options->where('is_correct', true)->first()->content ?? '关';
        @endphp
        <div class="p-4 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-3">
            @if ($exTitle)
                <div class="text-base sm:text-lg font-semibold text-slate-700 dark:text-slate-200 leading-relaxed zh-text">
                    {!! renderHskRubyText($exTitle) !!}
                </div>
            @endif
            <div class="flex items-center gap-2 pt-1">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">{{ __('Đáp án mẫu:') }}</span>
                <span class="px-3.5 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-400 dark:border-emerald-500/80 text-emerald-700 dark:text-emerald-400 font-bold text-lg rounded-xl zh-text">
                    {!! renderHskRubyText($exCorrect) !!}
                </span>
            </div>
        </div>
    @endforeach
</div>
@endif

<div class="space-y-5">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
            $rawTitle = $question->title ?? '';
            preg_match('/[（\(]\s*([a-zA-Zāáǎàēéěèīíǐìōóǒòūúǔùǖǘǚǜ\s]+?)\s*[）\)]/u', $rawTitle, $pinyinMatch);
            $pinyinHint = isset($pinyinMatch[1]) ? trim($pinyinMatch[1]) : null;
        @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-[#181615] p-5 sm:p-6 rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs"
             id="q-{{ $currentQNum }}">
            
            <div class="flex items-start gap-4 mb-4">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                    {{ $currentQNum }}
                </div>
                <div class="flex-1 min-w-0">
                    @if ($rawTitle)
                        @php
                            $cleanTitle = preg_replace('/^\s*\d+[\.\、\．\:\：]\s*/u', '', $rawTitle);
                        @endphp
                        <div class="text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100 leading-relaxed zh-text mb-4">
                            {!! renderHskRubyText($cleanTitle) !!}
                        </div>
                    @endif

                    <div class="p-3.5 sm:p-4 rounded-2xl bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] inline-flex flex-wrap items-center gap-3 sm:gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                {{ __('Điền chữ Hán:') }}
                            </span>
                            @if ($pinyinHint)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/40 text-xs font-bold">
                                    {{ $pinyinHint }}
                                </span>
                            @endif
                        </div>

                        <div class="relative flex items-center justify-center">
                            <input type="text"
                                   name="answers[{{ $question->id }}]"
                                   placeholder=" "
                                   maxlength="8"
                                   autocomplete="off"
                                   oninput="this.value.trim() ? updateSidebar({{ $currentQNum }}) : unmarkSidebar({{ $currentQNum }})"
                                   class="peer w-32 sm:w-36 h-12 py-0 px-2 rounded-2xl border-2 border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#181615] text-[#e07a5f] dark:text-[#f2957e] font-bold text-2xl zh-text text-center leading-[44px] focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all shadow-xs">

                            <div class="absolute inset-0 pointer-events-none flex items-center justify-center peer-focus:opacity-0 peer-[&:not(:placeholder-shown)]:opacity-0 transition-opacity text-slate-400 dark:text-slate-500 font-bold text-xl select-none leading-none">
                                ?
                            </div>
                        </div>

                        <span class="text-xs text-slate-400 dark:text-slate-500 italic hidden md:inline">
                            {{ __('(Nhập chữ Hán tương ứng với phiên âm)') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
