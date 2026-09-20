@php
    $examples = $group->questions->where('is_example', true);
    $realQuestions = $group->questions->where('is_example', false)->sortBy('order_index');
@endphp

@if ($group->passage_text && !str_starts_with(trim($group->passage_text), '{'))
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] px-5 py-4 mb-5 text-sm font-semibold text-slate-600 dark:text-slate-300 zh-text leading-relaxed shadow-xs">
    {!! renderHskRubyText($group->passage_text) !!}
</div>
@endif

@if ($examples->count() > 0)
<div class="bg-[#fcfaf7] dark:bg-[#1f1c1a] border border-[#e8e2d9] dark:border-[#2d2926] rounded-3xl p-5 mb-6 space-y-3 shadow-xs">
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-bold">{{ __('Ví dụ (例如)') }}</span>
    </div>
    @foreach ($examples as $ex)
        @php
            $exWords = array_filter(array_map('trim', explode('/', $ex->title ?? '')));
            $exCorrect = $ex->options->where('is_correct', true)->first()->content ?? '河上有一条小船。';
        @endphp
        <div class="p-4 bg-white dark:bg-[#181615] rounded-2xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs space-y-3">
            @if (count($exWords) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach ($exWords as $w)
                        <span class="px-3.5 py-1.5 bg-slate-100 dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl zh-text">
                            {!! renderHskRubyText($w) !!}
                        </span>
                    @endforeach
                </div>
            @endif
            <div class="flex items-center gap-2 pt-1">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">{{ __('Đáp án mẫu:') }}</span>
                <span class="px-3.5 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-400 dark:border-emerald-500/80 text-emerald-700 dark:text-emerald-400 font-bold text-base rounded-xl zh-text">
                    {!! renderHskRubyText($exCorrect) !!}
                </span>
            </div>
        </div>
    @endforeach
</div>
@endif

<div class="space-y-5">
    @foreach ($realQuestions as $question)
        @php
            $currentQNum = $qCount++;
            $rawTitle = $question->title ?? '';
            $words = array_values(array_filter(array_map('trim', explode('/', $rawTitle))));
        @endphp
        <div class="q-card scroll-mt-24 bg-white dark:bg-[#181615] p-5 sm:p-6 rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-xs"
             id="q-{{ $currentQNum }}"
             data-arrange-card="{{ $question->id }}"
             data-qnum="{{ $currentQNum }}">
            
            <div class="flex items-start gap-4 mb-4">
                <div class="w-9 h-9 rounded-xl bg-[#fff2ee] dark:bg-[#251d1a] text-[#e07a5f] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                    {{ $currentQNum }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                        {{ __('Nhấp vào các từ để ghép thành câu hoàn chỉnh:') }}
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">
                        {{ __('(Chọn lần lượt từng từ theo thứ tự, có thể nhấp lại vào từ đã ghép để gỡ bỏ)') }}
                    </p>

                    @if (count($words) > 0)
                        <div class="flex flex-wrap gap-2.5 mb-5 p-3 rounded-2xl bg-[#fcfaf7] dark:bg-[#1a1716] border border-[#e8e2d9] dark:border-[#2d2926]"
                             id="arrange-bank-{{ $question->id }}">
                            @foreach ($words as $wIdx => $word)
                                <button type="button"
                                        class="arrange-chip-btn px-4 py-2 bg-white dark:bg-[#23201e] border-2 border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-slate-100 font-bold text-base rounded-2xl zh-text shadow-xs hover:border-[#e07a5f] hover:text-[#e07a5f] active:scale-95 transition-all cursor-pointer select-none"
                                        id="bank-chip-{{ $question->id }}-{{ $wIdx }}"
                                        onclick="window.hskArrangeWord({{ $question->id }}, {{ $wIdx }}, {{ json_encode($word) }}, {{ $currentQNum }})">
                                    {!! renderHskRubyText($word) !!}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                {{ __('Câu của bạn:') }}
                            </label>
                            <button type="button"
                                    id="btn-reset-{{ $question->id }}"
                                    onclick="window.hskResetArrange({{ $question->id }}, {{ $currentQNum }})"
                                    class="hidden text-xs font-bold text-slate-400 hover:text-[#e07a5f] dark:text-slate-500 dark:hover:text-[#e07a5f] items-center gap-1.5 transition-colors cursor-pointer px-2.5 py-1 rounded-lg hover:bg-[#fff2ee] dark:hover:bg-[#251d1a]">
                                <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                <span>{{ __('Xếp lại') }}</span>
                            </button>
                        </div>

                        <div class="min-h-[58px] p-3 sm:p-4 rounded-2xl border-2 border-dashed border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3] dark:bg-[#201d1b] flex flex-wrap items-center gap-2 transition-all"
                             id="arrange-dropzone-{{ $question->id }}">
                            <div class="arrange-empty-hint text-xs sm:text-sm text-slate-400 dark:text-slate-500 italic flex items-center gap-2 select-none py-1">
                                <i class="fa-regular fa-hand-pointer text-[#e07a5f]"></i>
                                <span>{{ __('Chạm vào các từ ở trên theo thứ tự để ghép câu...') }}</span>
                            </div>
                        </div>

                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 pt-1 px-1">
                            <span class="text-slate-400 dark:text-slate-500">{{ __('Xem trước:') }}</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 zh-text italic" id="arrange-preview-{{ $question->id }}">
                                —
                            </span>
                        </div>
                    </div>

                    <input type="hidden"
                           name="answers[{{ $question->id }}]"
                           id="arrange-input-{{ $question->id }}"
                           value="">
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
(function() {
    window._arrangeState = window._arrangeState || {};

    // Initialize or retrieve per-question arrangement state
    function getQuestionState(qId) {
        if (!window._arrangeState[qId]) {
            window._arrangeState[qId] = {
                tokens: [] // Array of { index: number, word: string }
            };
        }
        return window._arrangeState[qId];
    }

    // Select token from word bank and append to sentence
    window.hskArrangeWord = function(qId, wordIdx, wordText, qNum) {
        const state = getQuestionState(qId);
        
        // Prevent duplicate selection of same token
        if (state.tokens.some(t => t.index === wordIdx)) {
            return;
        }

        // Append to selected token list
        state.tokens.push({ index: wordIdx, word: wordText });

        // Mark corresponding bank chip as disabled/used
        const bankBtn = document.getElementById('bank-chip-' + qId + '-' + wordIdx);
        if (bankBtn) {
            bankBtn.classList.add('opacity-25', 'pointer-events-none', 'scale-90', 'border-dashed');
        }

        renderSentence(qId, qNum);
    };

    // Remove token from sentence dropzone
    window.hskRemoveArrangeWord = function(qId, wordIdx, qNum) {
        const state = getQuestionState(qId);
        state.tokens = state.tokens.filter(t => t.index !== wordIdx);

        // Restore bank chip
        const bankBtn = document.getElementById('bank-chip-' + qId + '-' + wordIdx);
        if (bankBtn) {
            bankBtn.classList.remove('opacity-25', 'pointer-events-none', 'scale-90', 'border-dashed');
        }

        renderSentence(qId, qNum);
    };

    // Reset whole sentence back to word bank
    window.hskResetArrange = function(qId, qNum) {
        const state = getQuestionState(qId);
        
        // Restore all chips in word bank
        state.tokens.forEach(t => {
            const bankBtn = document.getElementById('bank-chip-' + qId + '-' + t.index);
            if (bankBtn) {
                bankBtn.classList.remove('opacity-25', 'pointer-events-none', 'scale-90', 'border-dashed');
            }
        });

        state.tokens = [];
        renderSentence(qId, qNum);
    };

    // Render dropzone UI and update hidden input for submission
    function renderSentence(qId, qNum) {
        const state = getQuestionState(qId);
        const dropzone = document.getElementById('arrange-dropzone-' + qId);
        const inputEl = document.getElementById('arrange-input-' + qId);
        const previewEl = document.getElementById('arrange-preview-' + qId);
        const resetBtn = document.getElementById('btn-reset-' + qId);

        if (!dropzone || !inputEl) return;

        // Clear dropzone container
        dropzone.innerHTML = '';

        if (state.tokens.length === 0) {
            // Re-render empty placeholder
            dropzone.innerHTML = `
                <div class="arrange-empty-hint text-xs sm:text-sm text-slate-400 dark:text-slate-500 italic flex items-center gap-2 select-none py-1">
                    <i class="fa-regular fa-hand-pointer text-[#e07a5f]"></i>
                    <span>{{ __('Chạm vào các từ ở trên theo thứ tự để ghép câu...') }}</span>
                </div>
            `;
            inputEl.value = '';
            if (previewEl) previewEl.innerText = '—';
            if (resetBtn) {
                resetBtn.classList.add('hidden');
                resetBtn.classList.remove('inline-flex');
            }
            if (typeof unmarkSidebar === 'function') {
                unmarkSidebar(qNum);
            }
            return;
        }

        // Show reset button
        if (resetBtn) {
            resetBtn.classList.remove('hidden');
            resetBtn.classList.add('inline-flex');
        }

        // Render selected tokens as interactive chips
        state.tokens.forEach(token => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'group inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#e07a5f] text-white font-bold text-base zh-text shadow-xs hover:bg-[#c86349] active:scale-95 transition-all cursor-pointer select-none';
            chip.title = '{{ __('Nhấp để gỡ từ này') }}';
            chip.innerHTML = `
                <span>${escapeHtml(token.word)}</span>
                <i class="fa-solid fa-xmark text-xs opacity-70 group-hover:opacity-100"></i>
            `;
            chip.onclick = function() {
                window.hskRemoveArrangeWord(qId, token.index, qNum);
            };
            dropzone.appendChild(chip);
        });

        // Concatenate tokens into full sentence
        const fullSentence = state.tokens.map(t => t.word).join('');
        inputEl.value = fullSentence;

        if (previewEl) {
            previewEl.innerText = fullSentence;
        }

        // Update sidebar status
        if (typeof updateSidebar === 'function') {
            updateSidebar(qNum);
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();
</script>
