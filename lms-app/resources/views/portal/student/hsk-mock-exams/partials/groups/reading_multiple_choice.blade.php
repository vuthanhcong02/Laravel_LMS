@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false)->sortBy('order_index');
@endphp

@if ($examples->count() > 0)
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 space-y-4 shadow-xs">
    <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-200 text-sm">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
    </div>
    @foreach ($examples as $ex)
        <div class="p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4">
            @if ($ex->title)
                <div class="text-base font-bold text-slate-700 dark:text-slate-200 leading-relaxed zh-text whitespace-pre-line">
                    {!! renderHskRubyText($ex->title) !!}
                </div>
            @endif
            <div class="flex flex-wrap items-center gap-2.5">
                @foreach ($ex->options as $idx => $option)
                    @php
                        $isCorrect = $option->is_correct;
                        $label = chr(65 + $idx);
                        $cleanOptText = trim(preg_replace('/^[A-F][\.\s]+/', '', $option->content));
                    @endphp
                    <div class="px-4 py-2.5 rounded-2xl border flex items-center gap-2
                        {{ $isCorrect
                            ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500'
                            : 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] opacity-60' }}">
                        <span class="font-bold text-xs {{ $isCorrect ? 'text-emerald-600' : 'text-slate-500' }}">{{ $label }}</span>
                        <span class="font-bold text-xs sm:text-sm {{ $isCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300' }} zh-text">
                            {!! renderHskRubyText($cleanOptText ?: $option->content) !!}
                        </span>
                        @if ($isCorrect)<span class="text-emerald-600 text-xs font-bold ml-1">✓</span>@endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endif

<div class="space-y-4">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
            $rawTitle = $question->title ?? '';
            $rawTitle = preg_replace('/^\s*\d+[\.\、\．\:\：]\s*/u', '', $rawTitle);
            $parts = explode("\n", $rawTitle);
            $questionLine = '';
            $passageLines = [];
            foreach ($parts as $line) {
                if (str_starts_with(trim($line), '★')) {
                    $questionLine = trim($line);
                } else {
                    $passageLines[] = $line;
                }
            }
            $passageText = implode("\n", $passageLines);
        @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-[#181615] p-5 sm:p-6 rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4"
             id="q-{{ $currentQNum }}">
            <div class="flex items-start gap-4">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                    {{ $currentQNum }}
                </div>
                <div class="flex-1 min-w-0">
                    @if (trim($passageText))
                        <div class="text-base font-semibold text-slate-700 dark:text-slate-200 leading-relaxed zh-text mb-3 whitespace-pre-line p-4 bg-[#fcfaf7] dark:bg-[#1f1c1a] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926]">
                            {!! renderHskRubyText(trim($passageText)) !!}
                        </div>
                    @endif
                    @if ($questionLine)
                        <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed zh-text">
                            {!! renderHskRubyText($questionLine) !!}
                        </div>
                    @elseif (!trim($passageText) && $rawTitle)
                        <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed zh-text">
                            {!! renderHskRubyText($rawTitle) !!}
                        </div>
                    @endif
                </div>
            </div>
            @if ($question->image)
                <div class="flex justify-center">
                    <img src="{{ hsk_storage_url($question->image) }}" class="max-h-48 object-contain rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926]" alt="Question {{ $currentQNum }}">
                </div>
            @endif
            @if ($question->audio_file)
                <button type="button"
                        onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                        class="h-10 px-4 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] font-bold text-xs hover:bg-[#e07a5f] hover:text-white transition-all flex items-center gap-2 btn-tactile shadow-xs">
                    <i class="fa-solid fa-volume-high text-xs"></i>
                    <span>{{ __('Nghe Audio') }}</span>
                </button>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-3 pt-1">
                @foreach ($question->options as $idx => $option)
                    @php
                        $label = chr(65 + $idx);
                        $optText = trim(preg_replace('/^[A-F][\.\s]+/', '', $option->content));
                    @endphp
                    <label class="cursor-pointer group block select-none h-full">
                        <input type="radio"
                               name="answers[{{ $question->id }}]"
                               value="{{ $option->id }}"
                               onchange="updateSidebar({{ $currentQNum }})"
                               class="peer hidden">
                        <div class="h-full flex items-center gap-3 p-3.5 rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] transition-all peer-checked:border-[#e07a5f] peer-checked:bg-[#fff7f4] dark:peer-checked:bg-[#2a201c] hover:border-[#e07a5f]/50 btn-tactile shadow-xs">
                            <span class="opt-badge w-8 h-8 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] font-bold text-xs flex items-center justify-center shrink-0 text-slate-600 dark:text-slate-300 transition-colors peer-checked:bg-[#e07a5f] peer-checked:text-white peer-checked:border-[#e07a5f]">
                                {{ $label }}
                            </span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 zh-text leading-snug">
                                {!! renderHskRubyText($optText ?: $option->content) !!}
                            </span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
