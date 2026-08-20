@php
                                            // Type 1: True / False (√ vs ×)
                                            $isTrueFalse =
                                                $question->options->count() == 2 &&
                                                ($question->options[0]->content === '√' ||
                                                 $question->options[0]->content === '×');

                                            // Type 2: Options with Images
                                            $isOptionsWithImages =
                                                !$isTrueFalse &&
                                                $question->options->count() > 0 &&
                                                $question->options->every(fn($opt) => !empty($opt->image));

                                            // Type 3: Matching / Letter Options (A, B, C, D, E, F)
                                            $isLetterOptions =
                                                !$isTrueFalse &&
                                                !$isOptionsWithImages &&
                                                ($question->question_type === 'matching' ||
                                                 ($question->options->count() > 0 && $question->options->every(function($opt) {
                                                     $c = trim($opt->content ?? '');
                                                     return empty($opt->image) && (empty($c) || in_array($c, ['A','B','C','D','E','F']));
                                                 })));
                                        @endphp

                                        {{-- ========== TYPE 1: TRUE / FALSE ========== --}}
                                        @if ($isTrueFalse)
                                            <div class="q-card scroll-mt-24 p-4 {{ $isExample ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-200/80 dark:border-amber-800/40' : 'bg-white dark:bg-slate-800/80 border-slate-200 dark:border-slate-700/80' }} rounded-2xl border shadow-sm space-y-4"
                                                id="q-{{ $currentQNum }}">

                                                <div class="flex items-start gap-4">
                                                    {{-- Question Number --}}
                                                    <div class="w-8 h-8 rounded-lg {{ $isExample ? 'bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300' }} font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                                        {{ $currentQNum }}
                                                    </div>

                                                    {{-- Image & Title Container --}}
                                                    <div class="flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4 min-w-0">
                                                        @if ($question->image)
                                                            <div class="shrink-0 bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-100 dark:border-slate-700 flex items-center justify-center">
                                                                <img src="{{ asset($question->image) }}"
                                                                    class="max-h-28 max-w-[160px] object-contain rounded-lg"
                                                                    alt="Q {{ $currentQNum }}">
                                                            </div>
                                                        @endif

                                                        @if ($question->title)
                                                            <div class="flex-1 text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed text-center sm:text-left min-w-0 flex flex-wrap items-end justify-center sm:justify-start gap-x-2 gap-y-1 py-1">
                                                                {!! renderHskRubyText($question->title) !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Bottom Row: √ / × Buttons --}}
                                                <div class="flex items-center justify-center sm:justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                                    <span class="text-xs font-semibold text-slate-400 mr-1 select-none">Đúng / Sai:</span>
                                                    @foreach ($question->options as $option)
                                                        <label class="{{ $isExample ? '' : 'cursor-pointer group' }} select-none">
                                                            @if(!$isExample)
                                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                                    class="peer hidden" value="{{ $option->id }}"
                                                                    @change="selectAnswer({{ $currentQNum }})">
                                                            @endif
                                                            <div class="w-12 h-12 rounded-2xl border-2 flex items-center justify-center transition-all duration-150
                                                                {{ $option->content === '√'
                                                                    ? ($isExample && $option->is_correct ? 'border-emerald-500 bg-emerald-500 text-white shadow-sm' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-emerald-500 group-hover:border-emerald-400 group-hover:bg-emerald-50/50 dark:group-hover:bg-emerald-950/20 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-sm')
                                                                    : ($isExample && $option->is_correct ? 'border-rose-500 bg-rose-500 text-white shadow-sm' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-rose-500 group-hover:border-rose-400 group-hover:bg-rose-50/50 dark:group-hover:bg-rose-950/20 peer-checked:border-rose-500 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-sm') }}">
                                                                <span class="material-symbols-outlined text-[26px] font-black">
                                                                    {{ $option->content === '√' ? 'check' : 'close' }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                        {{-- ========== TYPE 2: OPTIONS WITH IMAGES ========== --}}
                                        @elseif ($isOptionsWithImages)
                                            <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-sm"
                                                id="q-{{ $currentQNum }}">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                                                        {{ $currentQNum }}
                                                    </div>
                                                    @if($question->title)
                                                        <div class="text-base font-medium text-slate-700 dark:text-slate-300 leading-relaxed">{!! renderHskRubyText($question->title) !!}</div>
                                                    @endif
                                                </div>

                                                <div class="grid grid-cols-3 gap-3">
                                                    @foreach ($question->options as $option)
                                                        <label class="{{ $isExample ? '' : 'cursor-pointer group' }} relative">
                                                            @if(!$isExample)
                                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                                    class="peer hidden" value="{{ $option->id }}"
                                                                    @change="selectAnswer({{ $currentQNum }})">
                                                            @endif
                                                            <div class="flex flex-col items-center justify-between p-3 h-36 rounded-xl border-2 transition-all duration-150
                                                                {{ $isExample && $option->is_correct ? 'border-primary bg-primary/10 shadow-sm' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40' }}
                                                                group-hover:border-primary/50 group-hover:bg-primary/5
                                                                peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-sm">
                                                                 <div class="flex-1 flex items-center justify-center w-full">
                                                                    <img src="{{ asset($option->image) }}"
                                                                        class="max-h-24 object-contain group-hover:scale-105 transition-transform"
                                                                        alt="Option {{ $loop->iteration }}">
                                                                </div>
                                                                <div class="w-7 h-7 rounded-lg text-xs font-bold flex items-center justify-center transition-colors
                                                                    {{ $isExample && $option->is_correct ? 'bg-primary border-primary text-white' : 'border border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400' }}
                                                                    peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white">
                                                                    {{ chr(64 + $loop->iteration) }}
                                                                </div>
                                                                @if($isExample && $option->is_correct)
                                                                    <div class="absolute top-2 right-2 flex items-center gap-1 bg-primary text-white text-[10px] font-black px-1.5 py-0.5 rounded shadow-sm">
                                                                        <span class="material-symbols-outlined text-[12px]">check</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                        {{-- ========== TYPE 3: MATCHING / LETTER OPTIONS (A-F) ========== --}}
                                        @elseif ($isLetterOptions)
                                            @php
                                                $excludedLetters = [];
                                                if ($currentQNum >= 11 && $currentQNum <= 15) $excludedLetters = ['C'];
                                                if ($currentQNum >= 26 && $currentQNum <= 30) $excludedLetters = ['E'];
                                                if ($currentQNum >= 31 && $currentQNum <= 35) $excludedLetters = ['F'];
                                                if ($currentQNum >= 36 && $currentQNum <= 40) $excludedLetters = ['D'];
                                            @endphp
                                            <div class="q-card scroll-mt-24 p-4 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm space-y-3"
                                                id="q-{{ $currentQNum }}">

                                                {{-- Number & Title --}}
                                                <div class="flex items-start gap-3.5">
                                                    <div class="w-8 h-8 rounded-lg {{ $isExample ? 'bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300' }} font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                                        {{ $currentQNum }}
                                                    </div>

                                                    <div class="flex-1 text-base md:text-lg font-bold text-slate-800 dark:text-slate-100 leading-relaxed min-w-0 flex flex-wrap items-end gap-x-2 gap-y-1">
                                                        @if ($question->title)
                                                            {!! renderHskRubyText($question->title) !!}
                                                        @else
                                                            <span class="text-sm font-semibold italic text-slate-400">Chọn đáp án nghe được:</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Option Buttons Row --}}
                                                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-700/50">
                                                    <span class="text-xs font-semibold text-slate-400 mr-1 select-none">Đáp án:</span>
                                                    @foreach ($question->options as $option)
                                                        @php $optContent = trim($option->content ?? ''); @endphp
                                                        @if (in_array($optContent, $excludedLetters))
                                                            @continue
                                                        @endif
                                                        <label class="{{ $isExample ? '' : 'cursor-pointer group' }} select-none">
                                                            @if(!$isExample)
                                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                                    class="peer hidden" value="{{ $option->id }}"
                                                                    @change="selectAnswer({{ $currentQNum }})">
                                                            @endif
                                                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border-2 flex items-center justify-center transition-all duration-150 text-sm font-bold
                                                                {{ $isExample && $option->is_correct ? 'border-primary bg-primary text-white shadow-sm' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}
                                                                group-hover:border-primary/60 group-hover:text-primary group-hover:bg-primary/5
                                                                peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white peer-checked:shadow-sm">
                                                                {{ $optContent }}
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                        {{-- ========== TYPE 4: DEFAULT MULTI-CHOICE ========== --}}
                                        @else
                                            <div class="q-card scroll-mt-24 bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-sm"
                                                id="q-{{ $currentQNum }}">
                                                <div class="flex items-start gap-3 mb-4">
                                                    <div class="w-8 h-8 rounded-lg {{ $isExample ? 'bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300' }} font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                                        {{ $currentQNum }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        @if ($question->title)
                                                            <div class="text-base font-semibold text-slate-800 dark:text-white leading-relaxed">
                                                                {!! renderHskRubyText($question->title) !!}
                                                            </div>
                                                        @endif
                                                        @if ($question->image)
                                                            <div class="mt-2">
                                                                <img src="{{ asset($question->image) }}"
                                                                    class="rounded-xl border border-slate-200 dark:border-slate-700 max-h-36 object-contain"
                                                                    alt="Q {{ $currentQNum }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if ($question->options->count() > 0)
                                                    <div class="grid grid-cols-1 {{ $question->options->count() == 3 ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-3">
                                                        @foreach ($question->options as $option)
                                                            @php
                                                                $rawOptContent = trim($option->content ?? '');
                                                                if (preg_match('/^[A-F][\.\:\,\s]+\s*(.+)$/u', $rawOptContent, $mClean)) {
                                                                    $cleanContent = $mClean[1];
                                                                } else {
                                                                    $cleanContent = $rawOptContent;
                                                                }
                                                            @endphp
                                                            <label class="{{ $isExample ? '' : 'cursor-pointer group' }}">
                                                                <input type="radio"
                                                                    name="answers[{{ $question->id }}]"
                                                                    class="peer hidden" value="{{ $option->id }}"
                                                                    @change="selectAnswer({{ $currentQNum }})">
                                                                <div class="flex items-center gap-3 p-3 rounded-xl border-2 transition-all duration-150
                                                                    border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40
                                                                    group-hover:border-primary/50 group-hover:bg-primary/5
                                                                    peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-sm">

                                                                    {{-- Option letter badge (A, B, C) --}}
                                                                    <div class="w-7 h-7 rounded-lg border flex items-center justify-center text-xs font-bold shrink-0 transition-colors
                                                                        {{ $isExample && $option->is_correct ? 'border-primary bg-primary text-white' : 'border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400' }}
                                                                        group-hover:border-primary/60 group-hover:text-primary
                                                                        peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                                                        {{ chr(64 + $option->order_index) }}
                                                                    </div>

                                                                    @if ($option->image)
                                                                        <img src="{{ asset($option->image) }}"
                                                                            class="h-14 w-auto rounded-lg object-contain"
                                                                            alt="Option {{ chr(64 + $option->order_index) }}">
                                                                    @endif

                                                                    @if ($cleanContent)
                                                                        <span class="text-slate-700 dark:text-slate-300 font-medium text-base group-hover:text-primary transition-colors leading-loose">
                                                                            {!! renderHskRubyText($cleanContent) !!}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endif