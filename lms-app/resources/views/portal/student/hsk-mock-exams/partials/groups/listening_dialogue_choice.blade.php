@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
@endphp

{{-- Group Header --}}
@if($group->passage_audio)
<div class="flex flex-wrap items-end justify-end gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
    <div class="flex items-center gap-2">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mr-2">Audio Part</span>
        <button type="button"
                onclick="playAudio('{{ hsk_storage_url($group->passage_audio) }}', this)"
                class="w-10 h-10 rounded-full bg-slate-100 hover:bg-primary/10 dark:bg-slate-800 dark:hover:bg-primary/20 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-xl">play_arrow</span>
        </button>
    </div>
</div>
@endif

{{-- Example Block --}}
@if($examples->count() > 0)
    <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-5 mb-6 space-y-3">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>

        @foreach($examples as $ex)
            @php $correctOpt = $ex->options->where('is_correct', true)->first(); @endphp
            <div class="mt-3 p-4 bg-white/60 dark:bg-slate-800/60 rounded-xl border border-amber-200/50 dark:border-slate-700 opacity-80">
                @if($ex->title)
                    <div class="mb-3">{!! hsk_render_pinyin(trim($ex->title)) !!}</div>
                @endif
                <div class="flex flex-col gap-2">
                    @foreach($ex->options as $optIdx => $opt)
                        @php
                            $optLabel = chr(65 + $optIdx);
                            $isCorrect = $opt->is_correct;
                        @endphp
                        <div class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-300' : 'bg-slate-50 dark:bg-slate-800' }}">
                            <span class="w-7 h-7 shrink-0 rounded-lg {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center font-black text-sm">
                                {{ $optLabel }}
                            </span>
                            <span class="text-sm {{ $isCorrect ? 'font-bold text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400' }}">
                                {!! hsk_render_pinyin(trim($opt->content)) !!}
                            </span>
                            @if($isCorrect)
                                <span class="ml-auto text-xs font-black text-emerald-600">✓ Đúng</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Danh sách câu hỏi thực sự --}}
<div class="space-y-5">
    @foreach($realQuestions as $question)
        @php $currentQNum = $qCount++; @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 p-5 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm"
             id="q-{{ $currentQNum }}">

            {{-- Số câu + câu hỏi text --}}
            <div class="flex items-start gap-3 mb-4">
                <div class="w-9 h-9 shrink-0 rounded-xl bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-sm flex items-center justify-center">
                    {{ $currentQNum }}
                </div>
                @if($question->title)
                    <div class="mt-1.5 mb-2">
                        {!! hsk_render_pinyin(trim($question->title)) !!}
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic mt-1.5">Câu hỏi âm thanh</p>
                @endif
            </div>

            {{-- Các đáp án A, B, C --}}
            <div class="flex flex-col gap-2 pl-12">
                @foreach($question->options as $optIdx => $option)
                    @php $optLabel = chr(65 + $optIdx); @endphp
                    <label class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 cursor-pointer hover:border-primary/50 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all peer-checked:border-primary group">
                        <input type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $option->id }}"
                            onchange="updateSidebar({{ $currentQNum }})"
                            class="peer hidden">
                        <div class="w-8 h-8 shrink-0 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-center font-black text-sm text-slate-500 group-hover:border-primary group-hover:text-primary transition-all peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white">
                            {{ $optLabel }}
                        </div>
                        <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{!! hsk_render_pinyin(trim($option->content)) !!}</div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
