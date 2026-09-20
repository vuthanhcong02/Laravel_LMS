<div class="space-y-6">
    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Hướng dẫn phần thi (Instructions / Passage Text)</label>
        <textarea wire:model.defer="group.passage_text" rows="3"
            placeholder="Ví dụ: 第二部分，一共5个题。请根据拼音写出汉字。"
            class="w-full p-3 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary font-medium zh-text"></textarea>
    </div>

    <div>
        <h4 class="text-sm font-black text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-3">
            Danh sách Câu hỏi Viết chữ Hán ({{ $group->questions->count() }})
        </h4>

        @foreach($group->questions as $index => $question)
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 mb-4" wire:key="q-{{ $question->id }}">
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
                            <span class="text-xs font-bold text-slate-500">Là câu Ví dụ (例如)</span>
                        </label>
                        <button type="button" wire:click="deleteQuestion({{ $question->id }})" wire:confirm="Xóa câu này?" class="text-red-500 hover:text-red-600 transition-colors p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>

                <div class="pl-11 space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">
                            Câu hỏi chứa phiên âm trong ngoặc đơn <code class="text-primary font-bold">（ pinyin ）</code>
                        </label>
                        <input type="text"
                            wire:model.defer="questionTitles.{{ $index }}"
                            placeholder="Ví dụ: 今天的云很多，看不见（  tài  ）阳。"
                            class="w-full text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary px-3 py-2 font-bold zh-text text-base">
                    </div>

                    <div class="w-full sm:w-64">
                        <label class="block text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase mb-1">
                            Chữ Hán cần điền (Đáp án đúng)
                        </label>
                        <input type="text"
                            wire:model.defer="correctAnswers.{{ $question->id }}"
                            placeholder="Ví dụ: 太"
                            maxlength="10"
                            class="w-full text-sm rounded-lg border border-emerald-300 dark:border-emerald-700 bg-emerald-50/40 dark:bg-emerald-950/20 text-emerald-900 dark:text-emerald-200 focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2 font-black zh-text text-xl text-center">
                    </div>

                    <x-lms.exam-builder.explanation-input :index="$index" />
                </div>
            </div>
        @endforeach

        <div class="flex items-center gap-3 mt-4">
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
