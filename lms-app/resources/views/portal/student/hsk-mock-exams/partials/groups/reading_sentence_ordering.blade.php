@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
@endphp
{{-- Example Card --}}
@if($examples->count() > 0)
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 space-y-4 shadow-xs">
    <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-200 text-sm">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
    </div>
    @foreach($examples as $ex)
        <div class="p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-3">
            <div class="space-y-3">
                @foreach ($ex->options as $idx => $option)
                    @php $optLetter = chr(65 + $idx); @endphp
                    <div class="flex items-start gap-3">
                        <span class="w-7 h-7 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0">
                            {{ $optLetter }}
                        </span>
                        <div class="flex-1 text-sm sm:text-base font-bold text-slate-800 dark:text-slate-100 zh-text leading-relaxed pt-0.5">
                            {!! renderHskRubyText($option->content) !!}
                        </div>
                    </div>
                @endforeach
            </div>
            @if($ex->correct_answer)
            <div class="mt-4 pt-3 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500">{{ __('Đáp án mẫu') }}:</span>
                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-xl text-xs font-bold tracking-widest">
                    {{ $ex->correct_answer }}
                </span>
            </div>
            @endif
        </div>
    @endforeach
</div>
@endif
{{-- Questions List --}}
<div class="space-y-4">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
        @endphp
        <div class="q-card scroll-mt-24 p-5 sm:p-6 bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-5"
            id="q-{{ $currentQNum }}">
            <div class="flex items-start gap-4">
                {{-- Question Number --}}
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                    {{ $currentQNum }}
                </div>
                <div class="flex-1 space-y-4">
                    {{-- Sentences List --}}
                    <div class="space-y-3">
                        @foreach($question->options as $idx => $option)
                            @php $optLetter = chr(65 + $idx); @endphp
                            <div class="flex items-start gap-3.5 p-3 rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b]">
                                <span class="w-7 h-7 rounded-xl bg-white dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ $optLetter }}
                                </span>
                                <div class="flex-1 text-sm sm:text-base font-bold text-slate-800 dark:text-slate-100 zh-text leading-relaxed pt-0.5">
                                    {!! renderHskRubyText($option->content) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Answer Input --}}
                    <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex flex-wrap items-center gap-3">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Thứ tự đúng') }}:</span>
                        <div class="relative">
                            <input type="text" 
                                name="answers[{{ $question->id }}]"
                                oninput="
                                    this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, {{ $question->options->count() }});
                                    if(this.value.length === {{ $question->options->count() }}) { updateSidebar({{ $currentQNum }}); }
                                "
                                class="w-36 h-11 px-3 bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl font-bold text-base text-[#e07a5f] tracking-[0.3em] uppercase text-center focus:border-[#e07a5f] focus:outline-hidden transition-all placeholder:text-slate-400 placeholder:font-normal placeholder:tracking-normal"
                                placeholder="{{ __('VD: BAC') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
