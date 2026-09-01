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
        <div class="p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-6">
            {{-- Image & Text Group --}}
            <div class="flex flex-col sm:flex-row items-center gap-6">
                {{-- Image Left --}}
                @if($ex->image)
                    <div class="shrink-0 w-28 h-28 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] p-2 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center overflow-hidden">
                        <img src="{{ hsk_storage_url($ex->image) }}" class="max-w-full max-h-full object-contain" alt="Ex">
                    </div>
                @endif
                
                {{-- Text --}}
                @if($ex->title)
                    <div class="text-lg font-bold text-slate-800 dark:text-slate-100 text-center sm:text-left break-words zh-text leading-relaxed">
                        {!! renderHskRubyText($ex->title) !!}
                    </div>
                @endif
            </div>

            {{-- Options Right --}}
            <div class="flex items-center justify-center gap-3 shrink-0">
                @foreach ($ex->options as $idx => $option)
                    @php
                        $isCorrect = $option->is_correct;
                        $iconColor = $isCorrect ? 'text-emerald-500' : 'text-slate-400 dark:text-slate-500';
                        $bgSelected = $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500' : 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926] opacity-60';
                    @endphp
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 {{ $bgSelected }} flex items-center justify-center shadow-xs">
                        <span class="text-2xl font-bold {{ $iconColor }}">{{ $option->content }}</span>
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
        @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-[#181615] p-5 sm:p-6 rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4"
             id="q-{{ $currentQNum }}">
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                {{-- Left: Question Number, Image & Text --}}
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                    <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0">
                        {{ $currentQNum }}
                    </div>
                    @if($question->image)
                        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] p-2.5 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                            <img src="{{ hsk_storage_url($question->image) }}" class="w-full h-full object-contain hover:scale-105 transition-transform duration-200" alt="Q{{ $currentQNum }}">
                        </div>
                    @endif

                    {{-- Text (Ruby) --}}
                    @if($question->title)
                        <div class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 text-center sm:text-left break-words zh-text leading-relaxed">
                            {!! renderHskRubyText($question->title) !!}
                        </div>
                    @endif
                </div>

                {{-- Right: Options --}}
                <div class="flex items-center justify-center gap-3 shrink-0 w-full sm:w-auto">
                    @foreach ($question->options as $option)
                        @php
                            $isTrue = ($option->content === '√');
                            $iconColor = $isTrue ? 'text-emerald-500' : 'text-rose-500';
                        @endphp
                        <label class="cursor-pointer group block shrink-0">
                            <input type="radio"
                                name="answers[{{ $question->id }}]"
                                value="{{ $option->id }}"
                                onchange="updateSidebar({{ $currentQNum }})"
                                class="peer hidden">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff5f2] dark:peer-checked:bg-[#2a201c] flex items-center justify-center transition-all hover:border-[#e07a5f]/50 btn-tactile shadow-xs">
                                <span class="text-2xl font-bold {{ $iconColor }}">{{ $option->content }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
