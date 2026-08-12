@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
@endphp



{{-- Example Card --}}
@if($examples->count() > 0)
<div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6 space-y-3">
    <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
    </div>
    @foreach($examples as $ex)
        <div class="p-4 bg-white/60 dark:bg-slate-800/60 rounded-2xl border border-amber-200/50 dark:border-slate-700 shadow-sm opacity-80">
            <div class="space-y-3">
                @foreach ($ex->options as $idx => $option)
                    @php $optLetter = chr(65 + $idx); @endphp
                    <div class="flex items-start gap-3">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-black text-sm flex items-center justify-center shrink-0">
                            {{ $optLetter }}
                        </span>
                        <div class="flex-1 text-base font-bold text-slate-800 dark:text-slate-100 pt-0.5">
                            {!! renderHskRubyText($option->content) !!}
                        </div>
                    </div>
                @endforeach
            </div>
            @if($ex->correct_answer)
            <div class="mt-4 pt-3 border-t border-amber-200/50 dark:border-amber-800/30 flex items-center gap-2">
                <span class="text-xs font-bold text-amber-700 dark:text-amber-500">Đáp án:</span>
                <span class="px-3 py-1 bg-amber-500 text-white rounded-lg text-sm font-black tracking-widest">
                    {{ $ex->correct_answer }}
                </span>
            </div>
            @endif
        </div>
    @endforeach
</div>
@endif

{{-- Questions List --}}
<div class="space-y-6">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
        @endphp
        <div class="q-card scroll-mt-24 p-5 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm space-y-5"
            id="q-{{ $currentQNum }}">

            <div class="flex items-start gap-4">
                {{-- Question Number --}}
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                    {{ $currentQNum }}
                </div>

                <div class="flex-1 space-y-4">
                    {{-- Sentences List --}}
                    <div class="space-y-3">
                        @foreach($question->options as $idx => $option)
                            @php $optLetter = chr(65 + $idx); @endphp
                            <div class="flex items-start gap-3 group">
                                <span class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-black text-sm flex items-center justify-center shrink-0 transition-colors group-hover:border-primary/50 group-hover:text-primary">
                                    {{ $optLetter }}
                                </span>
                                <div class="flex-1 text-base font-bold text-slate-800 dark:text-slate-100 pt-0.5">
                                    {!! renderHskRubyText($option->content) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Answer Input --}}
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50 flex flex-wrap items-center gap-3">
                        <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Thứ tự đúng:</span>
                        <div class="relative">
                            <input type="text" 
                                x-model="answers[{{ $question->id }}]" 
                                @input="
                                    $el.value = $el.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, {{ $question->options->count() }});
                                    answers[{{ $question->id }}] = $el.value;
                                    updateSidebar({{ $currentQNum }})
                                "
                                class="w-32 h-10 px-3 bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl font-black text-lg text-primary tracking-[0.3em] uppercase text-center focus:border-primary focus:ring-0 transition-colors placeholder:text-slate-300 dark:placeholder:text-slate-600 placeholder:font-normal placeholder:tracking-normal"
                                placeholder="VD: BAC">
                            
                            {{-- Validation Checkmark --}}
                            <div class="absolute -right-8 top-1/2 -translate-y-1/2" 
                                x-show="(answers[{{ $question->id }}] || '').length === {{ $question->options->count() }}"
                                x-transition>
                                <span class="material-symbols-outlined text-emerald-500 font-bold">check_circle</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
