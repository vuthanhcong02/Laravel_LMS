@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
    $passageImages = [];
    if ($group->passage_image) {
        $passageImages = explode(',', $group->passage_image);
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
    @if($group->passage_audio)
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mr-2">Audio Part</span>
            <button type="button" 
                    onclick="playAudio('{{ hsk_storage_url($group->passage_audio) }}', this)"
                    class="w-10 h-10 rounded-full bg-slate-100 hover:bg-primary/10 dark:bg-slate-800 dark:hover:bg-primary/20 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">play_arrow</span>
            </button>
        </div>
    @endif
</div>

{{-- Example Card --}}
@if($examples->count() > 0)
<div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6 space-y-3">
    <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
    </div>
    @foreach($examples as $ex)
        <div class="p-4 bg-white/60 dark:bg-slate-800/60 rounded-2xl border border-amber-200/50 dark:border-slate-700 shadow-sm opacity-80">
            <div class="flex flex-col md:flex-row gap-5 items-center">
                <div class="grid grid-cols-3 gap-3 w-full md:w-auto">
                    @foreach ($ex->options as $idx => $option)
                        @php
                            $optLetter = chr(65 + $idx);
                            $isCorrect = $option->is_correct;
                            $bgClass = $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700';
                            $textClass = $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300';
                        @endphp
                        <div class="flex flex-col items-center p-3 rounded-xl border-2 {{ $bgClass }}">
                            <div class="flex items-center justify-between w-full pb-1 mb-2 border-b {{ $isCorrect ? 'border-emerald-200' : 'border-slate-100' }} text-xs font-black">
                                <span class="px-2 py-0.5 rounded {{ $textClass }} text-[11px]">{{ $optLetter }}</span>
                                @if($isCorrect)<span class="text-emerald-600 text-[11px]">✓</span>@endif
                            </div>
                            @if($option->image)
                                <img src="{{ hsk_storage_url(trim($option->image)) }}" class="h-20 object-contain rounded-lg" alt="Ex {{ $optLetter }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

@endif

{{-- Questions List --}}
<div class="space-y-3">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
        @endphp
        <div class="q-card scroll-mt-24 p-4 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm space-y-4"
            id="q-{{ $currentQNum }}">
            
            <div class="flex items-start gap-4 mb-2">
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                    {{ $currentQNum }}
                </div>
                @if($question->title)
                    <div class="text-base font-medium text-slate-700 dark:text-slate-300 leading-relaxed">{!! renderHskRubyText($question->title) !!}</div>
                @endif
            </div>

            <div class="flex flex-col md:flex-row gap-5">
                @if ($question->audio_file)
                    <div class="md:w-1/4">
                        <button type="button" 
                                onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                                class="w-full h-12 md:h-full min-h-[48px] rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-2xl">play_circle</span>
                            <span class="md:hidden">Nghe Audio</span>
                        </button>
                    </div>
                @endif

                <div class="flex-1 grid grid-cols-3 gap-3">
                    @foreach ($question->options as $idx => $option)
                        @php $label = chr(65 + $idx); @endphp
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="answers[{{ $question->id }}]"
                                value="{{ $option->id }}"
                                onchange="updateSidebar({{ $currentQNum }})"
                                class="peer hidden">
                            <div class="flex flex-col items-center justify-between h-full p-2 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 transition-all peer-checked:border-primary peer-checked:bg-primary/5 hover:border-primary/50 group-hover:shadow-md">
                                <div class="flex items-center justify-between w-full pb-1 mb-2 border-b border-slate-100 dark:border-slate-800">
                                    <span class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-black flex items-center justify-center group-[.peer:checked+&]:bg-primary group-[.peer:checked+&]:text-white transition-colors">
                                        {{ $label }}
                                    </span>
                                </div>
                                <div class="flex-1 flex items-center justify-center w-full bg-white dark:bg-slate-900 rounded-lg overflow-hidden p-1">
                                    @if($option->image)
                                        <img src="{{ hsk_storage_url($option->image) }}"
                                            class="max-h-24 object-contain group-hover:scale-105 transition-transform"
                                            alt="Option {{ $label }}">
                                    @else
                                        <span class="text-slate-400 text-xs italic">Missing image</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
