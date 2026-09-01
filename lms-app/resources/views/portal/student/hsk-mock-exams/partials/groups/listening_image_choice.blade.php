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
    <div class="flex items-center justify-between font-bold text-slate-800 dark:text-slate-200 text-sm">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
    </div>
    @foreach($examples as $ex)
        <div class="p-5 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs">
            <div class="grid grid-cols-3 gap-3 sm:gap-5 max-w-2xl mx-auto w-full">
                @foreach ($ex->options as $idx => $option)
                    @php
                        $optLetter = chr(65 + $idx);
                        $isCorrect = $option->is_correct;
                        $bgClass = $isCorrect ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-500' : 'bg-[#f8f6f3] dark:bg-[#201d1b] border-[#e8e2d9] dark:border-[#2d2926]';
                        $textClass = $isCorrect ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-[#25211e] text-slate-600 dark:text-slate-300';
                    @endphp
                    <div class="flex flex-col items-center p-3 rounded-2xl border-2 {{ $bgClass }} shadow-2xs">
                        <div class="flex items-center justify-between w-full pb-1.5 mb-2 border-b {{ $isCorrect ? 'border-emerald-200' : 'border-[#e8e2d9]/60 dark:border-[#2d2926]' }} text-xs font-bold">
                            <span class="w-6 h-6 rounded-lg {{ $textClass }} text-[11px] font-bold flex items-center justify-center shadow-2xs">{{ $optLetter }}</span>
                            @if($isCorrect)<span class="text-emerald-600 text-xs font-bold">✓</span>@endif
                        </div>
                        <div class="h-24 sm:h-32 w-full flex items-center justify-center overflow-hidden p-1">
                            @if($option->image)
                                <img src="{{ hsk_storage_url(trim($option->image)) }}" class="w-full h-full object-contain rounded-xl" alt="Ex {{ $optLetter }}">
                            @endif
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
        <div class="q-card scroll-mt-24 p-5 sm:p-6 bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-4"
             id="q-{{ $currentQNum }}">
            
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0">
                        {{ $currentQNum }}
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ __('Nghe audio và chọn hình ảnh tương ứng') }}</p>
                </div>

                @if ($question->audio_file)
                    <button type="button" 
                            onclick="playAudio('{{ hsk_storage_url($question->audio_file) }}', this)"
                            class="h-10 px-4 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] border border-[#fcdccf] dark:border-[#42271f] text-[#e07a5f] font-bold text-xs hover:bg-[#e07a5f] hover:text-white transition-all flex items-center gap-2 btn-tactile shadow-xs">
                        <i class="fa-solid fa-volume-high text-xs"></i>
                        <span>{{ __('Nghe Audio') }}</span>
                    </button>
                @endif
            </div>

            <div class="grid grid-cols-3 gap-3 sm:gap-5 max-w-2xl mx-auto w-full pt-2">
                @foreach ($question->options as $idx => $option)
                    @php $label = chr(65 + $idx); @endphp
                    <label class="cursor-pointer group relative block h-full">
                        <input type="radio" name="answers[{{ $question->id }}]"
                            value="{{ $option->id }}"
                            onchange="updateSidebar({{ $currentQNum }})"
                            class="peer hidden">
                        <div class="flex flex-col items-center justify-between h-full p-3 rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] peer-checked:border-[#e07a5f] peer-checked:bg-[#fff5f2] dark:peer-checked:bg-[#2a201c] transition-all hover:border-[#e07a5f]/50 btn-tactile shadow-2xs">
                            <div class="flex items-center justify-between w-full pb-1.5 mb-2 border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                                <span class="w-6 h-6 rounded-lg bg-white dark:bg-[#25211e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 text-xs font-bold flex items-center justify-center opt-badge transition-colors">
                                    {{ $label }}
                                </span>
                            </div>
                            <div class="h-24 sm:h-32 w-full flex items-center justify-center rounded-xl overflow-hidden p-1">
                                @if($option->image)
                                    <img src="{{ hsk_storage_url($option->image) }}"
                                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-200"
                                        alt="Option {{ $label }}">
                                @else
                                    <span class="text-slate-400 text-xs italic">{{ __('Chưa có ảnh') }}</span>
                                @endif
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
