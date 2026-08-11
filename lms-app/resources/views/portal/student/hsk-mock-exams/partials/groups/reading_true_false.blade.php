@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
    $exData = null;
    if ($group->passage_text && str_starts_with(trim($group->passage_text), '{')) {
        $exData = json_decode(trim($group->passage_text), true);
    } else {
        // Fallback example for Part 1 Reading HSK 1
        $exData = [
            'q_pinyin' => 'diànshì',
            'q_hanzi'  => '电视',
            'a_letter' => 'F' // False
        ];
    }
@endphp

{{-- Group Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
    <div class="flex items-center gap-3">
        <span class="px-3.5 py-1.5 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary text-xs font-black uppercase tracking-wider">
            {{ $group->title ?? 'Part ' . ($gIdx + 1) }}
        </span>
        @if($realQuestions->count() > 0)
            <span class="text-sm font-black text-slate-800 dark:text-slate-200">
                {{ $navQCount }} – {{ $navQCount + $realQuestions->count() - 1 }}
            </span>
        @endif
    </div>
</div>

{{-- Example Card --}}
{{-- Dynamic Example Card from is_example questions --}}
@if($examples->count() > 0)
<div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6 space-y-3">
    <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
    </div>
    @foreach($examples as $ex)
        <div class="p-4 bg-white/60 dark:bg-slate-800/60 rounded-2xl border border-amber-200/50 dark:border-slate-700 shadow-sm opacity-80 flex flex-col sm:flex-row items-center justify-between gap-6">
            {{-- Image & Text Group --}}
            <div class="flex flex-col sm:flex-row items-center gap-6">
                {{-- Image Left --}}
                @if($ex->image)
                    <div class="shrink-0 w-28 h-28 rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-2 border border-slate-100 dark:border-slate-800 flex items-center justify-center overflow-hidden">
                        <img src="{{ hsk_storage_url($ex->image) }}" class="max-w-full max-h-full object-contain" alt="Ex">
                    </div>
                @endif
                
                {{-- Text --}}
                @if($ex->title)
                    <div class="text-xl font-bold text-slate-800 dark:text-slate-100 text-center sm:text-left break-words">
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
                        $bgHover = $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 opacity-60';
                    @endphp
                    <div class="h-12 w-20 rounded-xl border-2 {{ $bgHover }} flex items-center justify-center shadow-sm">
                        <span class="text-2xl font-black {{ $iconColor }}">{{ $option->content }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@elseif($exData)
<div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 md:p-5 mb-6">
    <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400 text-sm mb-4">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
    </div>
    
    <div class="flex flex-col sm:flex-row items-center justify-between gap-5 bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-amber-100 dark:border-amber-900/30">
        <div class="flex flex-col sm:flex-row items-center gap-5">
            <div class="w-24 h-24 rounded-xl bg-slate-100 dark:bg-slate-800 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-slate-700">
                <img src="https://via.placeholder.com/150?text=TV" class="w-full h-full object-cover" alt="Example">
            </div>
            <div class="text-center sm:text-left">
                <div class="text-sm text-slate-500 mb-1 leading-none">{{ $exData['q_pinyin'] ?? '' }}</div>
                <div class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-widest">{{ $exData['q_hanzi'] ?? '' }}</div>
            </div>
        </div>
        
        <div class="w-12 h-12 rounded-xl flex items-center justify-center border-2 border-rose-500 bg-rose-50 text-rose-500 font-black text-2xl shadow-xs">
            ✕
        </div>
    </div>
</div>
@endif

{{-- Questions List --}}
<div class="space-y-4">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
        @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 p-5 rounded-3xl border border-slate-200 dark:border-slate-700/80 shadow-sm relative overflow-hidden"
             id="q-{{ $currentQNum }}">
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                {{-- Left: Question Number, Image & Text --}}
                <div class="flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-black text-sm flex items-center justify-center shadow-inner">
                            {{ $currentQNum }}
                        </div>
                        @if($question->image)
                            <div class="w-28 h-28 rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-2 border border-slate-100 dark:border-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="{{ hsk_storage_url($question->image) }}" class="max-w-full max-h-full object-contain hover:scale-110 transition-transform duration-300" alt="Q{{ $currentQNum }}">
                            </div>
                        @endif
                    </div>

                    {{-- Text (Ruby) --}}
                    @if($question->title)
                        <div class="text-xl font-bold text-slate-800 dark:text-slate-100 text-center sm:text-left break-words">
                            {!! renderHskRubyText($question->title) !!}
                        </div>
                    @endif
                </div>

                {{-- Right: Options --}}
                <div class="flex items-center justify-center gap-4 shrink-0 w-full sm:w-auto">
                    @foreach ($question->options as $option)
                        @php
                            $isTrue = ($option->content === '√');
                            $iconColor = $isTrue ? 'text-emerald-500' : 'text-rose-500';
                            $bgHover = $isTrue ? 'hover:bg-emerald-50 dark:hover:bg-emerald-950/30' : 'hover:bg-rose-50 dark:hover:bg-rose-950/30';
                            $peerChecked = $isTrue ? 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-950/30 peer-checked:shadow-sm' : 'peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-950/30 peer-checked:shadow-sm';
                        @endphp
                        <label class="cursor-pointer group flex-1 sm:flex-none">
                            <input type="radio"
                                name="answers[{{ $question->id }}]"
                                value="{{ $option->id }}"
                                onchange="updateSidebar({{ $currentQNum }})"
                                class="peer hidden">
                            <div class="h-12 w-full sm:w-20 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center transition-all {{ $bgHover }} {{ $peerChecked }} hover:border-slate-300 dark:hover:border-slate-600">
                                <span class="text-2xl font-black {{ $iconColor }}">{{ $option->content }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
