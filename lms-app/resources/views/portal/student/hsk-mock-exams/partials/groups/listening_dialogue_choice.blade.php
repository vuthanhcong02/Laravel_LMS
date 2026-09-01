@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
@endphp

{{-- Group Header Audio --}}
@if($group->passage_audio)
<div class="flex flex-wrap items-end justify-end gap-3 mb-6 pb-4 border-b border-[#e8e2d9] dark:border-[#2d2926]">
    <div class="flex items-center gap-2">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mr-2">{{ __('Audio Phần thi') }}</span>
        <button type="button"
                onclick="playAudio('{{ hsk_storage_url($group->passage_audio) }}', this)"
                class="w-10 h-10 rounded-2xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white transition-all flex items-center justify-center btn-tactile shadow-xs">
            <i class="fa-solid fa-volume-high text-sm"></i>
        </button>
    </div>
</div>
@endif

{{-- Example Block --}}
@if($examples->count() > 0)
    <div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 space-y-3 shadow-xs">
        <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-200 text-sm">
            <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
        </div>

        @foreach($examples as $ex)
            @php $correctOpt = $ex->options->where('is_correct', true)->first(); @endphp
            <div class="mt-3 p-4 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926]">
                @if($ex->title)
                    <div class="mb-3 zh-text leading-relaxed text-slate-800 dark:text-slate-200">{!! hsk_render_pinyin(trim($ex->title)) !!}</div>
                @endif
                <div class="flex flex-col gap-2">
                    @foreach($ex->options as $optIdx => $opt)
                        @php
                            $optLabel = chr(65 + $optIdx);
                            $isCorrect = $opt->is_correct;
                        @endphp
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-300 dark:border-emerald-800' : 'bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]' }}">
                            <span class="w-7 h-7 shrink-0 rounded-lg {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-[#25211e] text-slate-600 dark:text-slate-300' }} flex items-center justify-center font-bold text-xs">
                                {{ $optLabel }}
                            </span>
                            <span class="text-xs {{ $isCorrect ? 'font-bold text-emerald-700 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300' }} zh-text">
                                {!! hsk_render_pinyin(trim($opt->content)) !!}
                            </span>
                            @if($isCorrect)
                                <span class="ml-auto text-xs font-bold text-emerald-600">✓ {{ __('Đúng') }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Danh sách câu hỏi thực sự --}}
<div class="space-y-4">
    @foreach($realQuestions as $question)
        @php $currentQNum = $qCount++; @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-[#181615] p-5 sm:p-6 rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4"
             id="q-{{ $currentQNum }}">

            {{-- Số câu + câu hỏi audio --}}
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 shrink-0 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center">
                        {{ $currentQNum }}
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ __('Nghe hội thoại và chọn đáp án đúng') }}</p>
                </div>

                @if ($question->audio_file)
                    <button type="button" 
                            onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                            class="h-9 px-3.5 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] font-bold text-xs hover:bg-[#e07a5f] hover:text-white transition-all flex items-center gap-1.5 btn-tactile shadow-xs">
                        <i class="fa-solid fa-volume-high text-xs"></i>
                        <span>{{ __('Nghe') }}</span>
                    </button>
                @endif
            </div>

            {{-- Các đáp án A, B, C --}}
            <div class="flex flex-col gap-2.5">
                @foreach($question->options as $optIdx => $option)
                    @php $optLabel = chr(65 + $optIdx); @endphp
                    <label class="block cursor-pointer">
                        <input type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $option->id }}"
                            onchange="updateSidebar({{ $currentQNum }})"
                            class="peer hidden">
                        <div class="flex items-center gap-3.5 px-4 py-3 rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff5f2] dark:peer-checked:bg-[#2a201c] hover:border-[#e07a5f]/50 transition-all btn-tactile">
                            <div class="w-7 h-7 shrink-0 rounded-lg border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#25211e] flex items-center justify-center font-bold text-xs text-slate-600 dark:text-slate-300 opt-badge transition-all">
                                {{ $optLabel }}
                            </div>
                            <div class="text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-200 leading-relaxed zh-text">
                                {!! hsk_render_pinyin(trim($option->content)) !!}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
