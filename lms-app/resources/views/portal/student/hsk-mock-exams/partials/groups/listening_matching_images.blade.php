@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
    $passageImages = [];
    if ($group->passage_image) {
        $passageImages = explode(',', $group->passage_image);
    }
    $imgLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
    
    // Attempt to parse passage_text for example letter, fallback to C
    $exLetter = 'C';
    if ($group->passage_text && str_starts_with(trim($group->passage_text), '{')) {
        $parsedEx = json_decode(trim($group->passage_text), true);
        $exLetter = $parsedEx['a_letter'] ?? $parsedEx['ex_a_letter'] ?? 'C';
    }
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

@if(count($passageImages) > 0)
    {{-- Example Card --}}
    <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-800/40 rounded-2xl p-4 mb-6">
        <div class="flex items-center justify-between font-bold text-amber-700 dark:text-amber-400 text-sm">
            <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-xs font-black">Ví dụ (例如)</span>
            <span class="text-xs font-bold text-emerald-600">Đáp án mẫu: {{ $exLetter }}</span>
        </div>
        {{-- Hiển thị nội dung text của câu ví dụ nếu có --}}
        @if($examples->count() > 0 && $examples->first()->title)
            @php
                // Trim từng dòng để tránh khoảng trắng thừa đầu dòng từ textarea
                $exTitle = collect(explode("\n", $examples->first()->title))
                    ->map(fn($line) => trim($line))
                    ->filter()  // Bỏ dòng trống
                    ->join("\n");
            @endphp
            <div class="mt-3">
                {!! hsk_render_pinyin($exTitle) !!}
            </div>
        @endif
    </div>

    {{-- Images Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-8">
        @foreach($passageImages as $idx => $img)
            @php $label = $imgLabels[$idx] ?? chr(65 + $idx); @endphp
            <div class="relative bg-white dark:bg-slate-800/80 rounded-2xl p-3 border border-slate-200 dark:border-slate-700/80 hover:border-primary/50 transition-all shadow-xs group">
                <div class="absolute top-2.5 left-2.5 w-7 h-7 rounded-lg bg-primary/10 text-primary font-black text-xs flex items-center justify-center shadow-xs">
                    {{ $label }}
                </div>
                @if($label === $exLetter)
                    <span class="absolute top-2.5 right-2.5 text-[10px] bg-emerald-600 text-white px-2 py-0.5 rounded-md font-black tracking-wider uppercase flex items-center gap-1 shadow-xs">
                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                        Mẫu
                    </span>
                @endif
                <img src="{{ hsk_storage_url(trim($img)) }}" 
                     class="h-32 w-full object-contain p-2 group-hover:scale-105 transition-transform duration-200" 
                     alt="Option {{ $label }}">
            </div>
        @endforeach
    </div>
@endif

{{-- Questions List for Part 3 --}}
<div class="space-y-4">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
        @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 p-4 md:p-5 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm flex flex-col md:flex-row gap-4 md:gap-6 md:items-center"
             id="q-{{ $currentQNum }}">
             
            <div class="flex items-center gap-4 min-w-[200px]">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-sm flex items-center justify-center shrink-0">
                    {{ $currentQNum }}
                </div>
                @if($question->audio_file)
                    <button type="button" 
                            onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                            class="flex-1 md:flex-none flex items-center justify-center gap-2 h-10 px-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                        <span class="material-symbols-outlined text-lg">volume_up</span>
                        Nghe Audio
                    </button>
                @endif
            </div>
            
            <div class="flex-1 flex flex-wrap items-center gap-2 md:justify-end">
                @foreach($imgLabels as $letter)
                    @if($letter === $exLetter) @continue @endif
                    <label class="cursor-pointer group flex-1 md:flex-none">
                        <input type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $letter }}"
                            onchange="updateSidebar({{ $currentQNum }})"
                            class="peer hidden">
                        <div class="h-12 md:w-14 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center text-lg font-black text-slate-400 dark:text-slate-500 transition-all peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary hover:border-primary/50 hover:bg-slate-50 dark:hover:bg-slate-800">
                            {{ $letter }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
