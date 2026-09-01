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

@if(count($passageImages) > 0)
    {{-- Example Card --}}
    <div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 shadow-xs">
        <div class="flex items-center justify-between font-bold text-slate-800 dark:text-slate-200 text-sm">
            <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-lg">{{ __('Đáp án mẫu') }}: {{ $exLetter }}</span>
        </div>
        @if($examples->count() > 0 && $examples->first()->title)
            @php
                $exTitle = collect(explode("\n", $examples->first()->title))
                    ->map(fn($line) => trim($line))
                    ->filter()
                    ->join("\n");
            @endphp
            <div class="mt-3 text-sm zh-text leading-relaxed text-slate-700 dark:text-slate-300">
                {!! hsk_render_pinyin($exTitle) !!}
            </div>
        @endif
    </div>

    {{-- Images Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-8">
        @foreach($passageImages as $idx => $img)
            @php $label = $imgLabels[$idx] ?? chr(65 + $idx); @endphp
            <div class="relative bg-white dark:bg-[#181615] rounded-3xl p-3 border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f]/50 transition-all shadow-xs group">
                <div class="absolute top-3 left-3 w-8 h-8 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] font-bold text-xs flex items-center justify-center shadow-xs">
                    {{ $label }}
                </div>
                @if($label === $exLetter)
                    <span class="absolute top-3 right-3 text-[10px] bg-emerald-600 text-white px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider flex items-center gap-1 shadow-xs">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        {{ __('Mẫu') }}
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
        <div class="q-card scroll-mt-24 bg-white dark:bg-[#181615] p-5 sm:p-6 rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs flex flex-col md:flex-row gap-4 md:gap-6 md:items-center justify-between"
             id="q-{{ $currentQNum }}">
             
            <div class="flex items-center gap-4 min-w-[200px]">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0">
                    {{ $currentQNum }}
                </div>
                @if($question->audio_file)
                    <button type="button" 
                            onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                            class="flex-1 md:flex-none flex items-center justify-center gap-2 h-10 px-4 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] font-bold text-xs hover:bg-[#e07a5f] hover:text-white transition-all btn-tactile shadow-xs">
                        <i class="fa-solid fa-volume-high text-xs"></i>
                        <span>{{ __('Nghe Audio') }}</span>
                    </button>
                @endif
            </div>
            
            <div class="flex-1 flex flex-wrap items-center gap-2.5 md:justify-end">
                @foreach($imgLabels as $letter)
                    @if($letter === $exLetter) @continue @endif
                    <label class="cursor-pointer group block shrink-0">
                        <input type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $letter }}"
                            onchange="updateSidebar({{ $currentQNum }})"
                            class="peer hidden">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] flex items-center justify-center text-sm font-bold text-slate-700 dark:text-slate-300 transition-all peer-checked:border-[#e07a5f] peer-checked:bg-[#e07a5f] peer-checked:text-white hover:border-[#e07a5f]/50 btn-tactile shadow-xs">
                            {{ $letter }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
