@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false);
    $passageImages = [];
    if ($group->passage_image) {
        $passageImages = explode(',', $group->passage_image);
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

{{-- Example Card --}}
@if($examples->count() > 0)
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 space-y-4 shadow-xs">
    <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-200 text-sm">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
    </div>
    @foreach ($examples as $ex)
        <div class="p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                @if($ex->image)
                    <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] p-2.5 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                        <img src="{{ hsk_storage_url($ex->image) }}" class="w-full h-full object-contain rounded-xl" alt="Ex">
                    </div>
                @endif
            </div>
            <div class="flex items-center justify-center gap-3 shrink-0">
                @foreach ($ex->options as $option)
                    @php
                        $isTrue = ($option->content === '√');
                        $isCorrect = $option->is_correct;
                        $iconColor = $isTrue ? 'text-emerald-500' : 'text-rose-500';
                        $bgSelected = $isCorrect ? ($isTrue ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/30' : 'border-rose-500 bg-rose-50 dark:bg-rose-950/30') : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] opacity-60';
                    @endphp
                    <div class="cursor-not-allowed">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 flex items-center justify-center {{ $bgSelected }} shadow-xs">
                            <span class="text-2xl font-bold {{ $iconColor }}">{{ $option->content }}</span>
                        </div>
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
        <div class="q-card scroll-mt-24 p-5 sm:p-6 bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-5"
             id="q-{{ $currentQNum }}">

            {{-- Left: Question Number & Uniform Fixed Image --}}
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0">
                    {{ $currentQNum }}
                </div>

                @if ($question->image)
                    <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] p-2.5 border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                        <img src="{{ hsk_storage_url($question->image) }}"
                            class="w-full h-full object-contain rounded-xl"
                            alt="Question {{ $currentQNum }}">
                    </div>
                @endif
            </div>

            {{-- Right: Audio (if any) & True/False Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 w-full sm:w-auto shrink-0">
                @if ($question->audio_file)
                    <button type="button" 
                            onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                            class="w-full sm:w-auto h-12 px-5 rounded-2xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] font-bold text-xs hover:bg-[#e07a5f] hover:text-white transition-all flex items-center justify-center gap-2 btn-tactile shadow-xs">
                        <i class="fa-solid fa-volume-high text-sm"></i>
                        <span>{{ __('Nghe Audio') }}</span>
                    </button>
                @endif

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @foreach ($question->options as $option)
                        @php
                            $isTrue = ($option->content === '√');
                            $iconColor = $isTrue ? 'text-emerald-500' : 'text-rose-500';
                        @endphp
                        <label class="cursor-pointer group block shrink-0">
                            <input type="radio" name="answers[{{ $question->id }}]"
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
