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
    <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400 text-sm mb-3">
        <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
    </div>
    @foreach ($examples as $ex)
        <div class="p-4 bg-white/60 dark:bg-slate-800/60 rounded-2xl border border-amber-200/50 dark:border-slate-700 shadow-sm space-y-4 opacity-80">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                @if($ex->image)
                    <div class="w-28 h-28 rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-2 border border-slate-100 dark:border-slate-800 flex items-center justify-center overflow-hidden">
                        <img src="{{ hsk_storage_url($ex->image) }}" class="max-w-full max-h-full object-contain" alt="Ex">
                    </div>
                @endif
                <div class="flex-1 w-full">
                    <div class="flex items-center justify-center gap-6">
                        @foreach ($ex->options as $option)
                            @php
                                $isTrue = ($option->content === '√');
                                $isCorrect = $option->is_correct;
                                $iconColor = $isTrue ? 'text-emerald-500' : 'text-rose-500';
                                $bgSelected = $isCorrect ? ($isTrue ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/30' : 'border-rose-500 bg-rose-50 dark:bg-rose-950/30') : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900';
                            @endphp
                            <div class="cursor-not-allowed">
                                <div class="w-16 h-12 rounded-xl border-2 flex items-center justify-center {{ $bgSelected }}">
                                    <span class="text-2xl font-black {{ $iconColor }}">{{ $option->content }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
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

            <div class="flex items-start gap-4">
                {{-- Question Number --}}
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                    {{ $currentQNum }}
                </div>

                {{-- Image & Title Container --}}
                <div class="flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4 min-w-0">
                    @if ($question->image)
                        <div class="shrink-0 bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-100 dark:border-slate-700 flex items-center justify-center">
                            <img src="{{ hsk_storage_url($question->image) }}"
                                class="max-h-28 max-w-[160px] object-contain rounded-lg"
                                alt="Question {{ $currentQNum }}">
                        </div>
                    @endif

                    @if ($question->title)
                        <div class="flex-1 text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed text-center sm:text-left min-w-0 flex flex-wrap items-end justify-center sm:justify-start gap-x-2 gap-y-1 py-1">
                            {!! renderHskRubyText($question->title) !!}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Audio & True/False Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                <div class="w-full sm:w-auto">
                    @if ($question->audio_file)
                        <button type="button" 
                                onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                                class="w-full sm:w-auto h-11 px-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-xl">volume_up</span>
                            Nghe Audio
                        </button>
                    @endif
                </div>
                <div class="flex items-center justify-center gap-3 w-full sm:w-auto">
                    @foreach ($question->options as $option)
                        @php
                            $isTrue = ($option->content === '√');
                            $iconColor = $isTrue ? 'text-emerald-500' : 'text-rose-500';
                            $bgHover = $isTrue ? 'hover:bg-emerald-50 dark:hover:bg-emerald-950/30' : 'hover:bg-rose-50 dark:hover:bg-rose-950/30';
                            $peerChecked = $isTrue ? 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-950/30' : 'peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-950/30';
                        @endphp
                        <label class="cursor-pointer group flex-1 sm:flex-none">
                            <input type="radio" name="answers[{{ $question->id }}]"
                                value="{{ $option->id }}"
                                onchange="updateSidebar({{ $currentQNum }})"
                                class="peer hidden">
                            <div class="h-12 px-6 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center transition-all {{ $bgHover }} {{ $peerChecked }} hover:border-slate-300 dark:hover:border-slate-600">
                                <span class="text-2xl font-black {{ $iconColor }}">{{ $option->content }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
