<div class="space-y-6">
    {{-- Audio chung cho group --}}
    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Audio chung cho Part này</label>
        @if($group->passage_audio)
            <div class="mb-3">
                <audio controls class="w-full max-w-md h-10">
                    <source src="{{ hsk_storage_url($group->passage_audio) }}" type="audio/mpeg">
                </audio>
            </div>
        @endif
        <input type="file" wire:model="groupAudio" accept="audio/*"
            class="w-full p-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
        <div wire:loading wire:target="groupAudio" class="mt-1 text-xs text-slate-500 font-bold">Đang tải lên...</div>
    </div>

    {{-- Danh sách câu hỏi --}}
    <div>
        <h4 class="text-sm font-black text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-3">
            Danh sách Câu hỏi ({{ $group->questions->count() }})
        </h4>

        @foreach($group->questions as $index => $question)
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 mb-4">
                {{-- Header câu hỏi --}}
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 shrink-0 {{ $question->is_example ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 border-amber-200' : 'bg-white dark:bg-slate-700 text-slate-500 border-slate-200 dark:border-slate-600' }} rounded-lg border flex items-center justify-center font-black text-sm shadow-sm">
                            {{ $question->is_example ? 'VD' : $index + 1 }}
                        </div>
                        <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Câu hỏi {{ $index + 1 }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:click="toggleExample({{ $question->id }})" {{ $question->is_example ? 'checked' : '' }} class="w-4 h-4 text-amber-500 bg-slate-100 border-slate-300 rounded focus:ring-amber-500">
                            <span class="text-xs font-bold text-slate-500">Là câu Ví dụ</span>
                        </label>
                        <button type="button" wire:click="deleteQuestion({{ $question->id }})" wire:confirm="Xóa câu này?" class="text-red-500 hover:text-red-600 transition-colors p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>

                {{-- Nội dung câu hỏi --}}
                <div class="pl-11 space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nội dung câu hỏi (Tuỳ chọn)</label>
                        <textarea wire:model="questionTitles.{{ $index }}" rows="2"
                            placeholder="Ví dụ: 他们在谈论什么？"
                            class="w-full text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary"></textarea>
                    </div>

                    {{-- Các đáp án A, B, C --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Đáp án (A, B, C)</label>
                        <div class="space-y-2">
                            @foreach($question->options as $optIdx => $option)
                                @php $optLabel = chr(65 + $optIdx); @endphp
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 shrink-0 rounded-lg {{ $correctAnswers[$question->id] ?? null == $option->id ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }} flex items-center justify-center font-black text-sm">
                                        {{ $optLabel }}
                                    </span>
                                    <input type="text"
                                        wire:model="optionContents.{{ $option->id }}"
                                        placeholder="Nội dung đáp án {{ $optLabel }}..."
                                        class="flex-1 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary px-3 py-1.5">
                                    <label class="flex items-center gap-1.5 cursor-pointer shrink-0">
                                        <input type="radio"
                                            wire:model="correctAnswers.{{ $question->id }}"
                                            value="{{ $option->id }}"
                                            class="w-4 h-4 text-emerald-500 border-slate-300 focus:ring-emerald-500">
                                        <span class="text-xs text-slate-500 font-bold">Đúng</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Giải thích đáp án (Tuỳ chọn)</label>
                        <textarea wire:model.defer="questionExplanations.{{ $index }}" rows="2"
                            placeholder="Nhập giải thích cho câu hỏi này..."
                            class="w-full text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary px-3 py-2"></textarea>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Nút thêm câu + lưu --}}
        <div class="flex items-center gap-3 mt-2">
            <button type="button" wire:click="addQuestion" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm font-bold text-sm text-primary hover:border-primary/50 transition-colors">
                <span class="material-symbols-outlined text-lg">add</span>
                Thêm Câu hỏi
            </button>
            <button type="button" wire:click="saveGroup" class="flex items-center gap-2 px-6 py-2 bg-emerald-600 text-white rounded-lg shadow-sm font-bold text-sm hover:bg-emerald-700 transition-colors">
                <span class="material-symbols-outlined text-lg">save</span>
                Lưu Part này
            </button>
            <span class="text-xs text-emerald-600 font-bold" wire:loading wire:target="saveGroup">Đang lưu...</span>
        </div>
    </div>
</div>
