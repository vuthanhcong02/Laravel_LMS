<div>
    <div class="space-y-4">
        <!-- Text Options Builder -->
        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
            <div class="flex justify-between items-center mb-3">
                <label class="block text-xs font-bold text-slate-500 uppercase">Danh sách Đáp án (A-F)</label>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500">Đáp án Ví dụ:</span>
                    <select wire:model.defer="exampleLetter" class="text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary">
                        <option value="">-- Trống --</option>
                        @foreach($textOptions as $opt)
                            <option value="{{ $opt['letter'] ?? '' }}">{{ $opt['letter'] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="space-y-3">
                @foreach($textOptions as $idx => $opt)
                <div class="flex items-start gap-3 p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg relative group" wire:key="textopt-{{ $idx }}">
                    <div class="w-8 h-8 rounded shrink-0 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black flex items-center justify-center">
                        {{ $opt['letter'] ?? chr(65+$idx) }}
                    </div>
                    <div class="flex-1 space-y-2">
                        <input type="text" wire:model.defer="textOptions.{{ $idx }}.html" placeholder="Nội dung Text hoặc HTML có <ruby>" class="w-full text-sm rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                    </div>
                    <button type="button" wire:click="removeTextOption({{ $idx }})" class="absolute -right-2 -top-2 w-6 h-6 bg-red-500 text-white rounded-full hidden group-hover:flex items-center justify-center hover:bg-red-600 shadow-md">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                @endforeach
            </div>
            <button type="button" wire:click="addTextOption" class="mt-3 flex items-center gap-1 text-xs font-bold text-primary hover:text-primary-dark">
                <span class="material-symbols-outlined text-sm">add_circle</span> Thêm Đáp án
            </button>
        </div>

        <!-- Questions List -->
        <div class="space-y-4 mt-6">
            <h4 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2">
                Danh sách Câu hỏi ({{ $group->questions->count() }})
            </h4>

            @foreach($group->questions as $index => $question)
                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 mb-4" wire:key="q-{{ $question->id }}">
                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-200 dark:border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 shrink-0 {{ $question->is_example ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 border-amber-200' : 'bg-white dark:bg-slate-700 text-slate-500 border-slate-200 dark:border-slate-600' }} rounded-lg border flex items-center justify-center font-black text-sm shadow-sm">
                                {{ $question->is_example ? 'VD' : $index + 1 }}
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Câu hỏi</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:click="toggleExample({{ $question->id }})" {{ $question->is_example ? 'checked' : '' }} class="w-4 h-4 text-amber-500 bg-slate-100 border-slate-300 rounded focus:ring-amber-500">
                                <span class="text-xs font-bold text-slate-500">Là câu Ví dụ</span>
                            </label>
                            <button type="button" wire:click="deleteQuestion({{ $question->id }})" wire:confirm="Xóa câu này?" class="text-red-500 hover:text-red-600 transition-colors p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4 pl-11">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nội dung câu hỏi (sử dụng ___ cho chỗ trống)</label>
                            <textarea wire:model.defer="questionTitles.{{ $index }}" rows="2" placeholder="Ví dụ: 他在 ___ 工作。"
                                class="w-full p-2 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-primary focus:border-primary"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Đáp án Đúng (A-F)</label>
                            <select wire:model.defer="correctAnswers.{{ $question->id }}" class="w-32 p-2 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-bold">
                                <option value="">- Chọn -</option>
                                @foreach($textOptions as $opt)
                                    <option value="{{ $opt['letter'] ?? '' }}">{{ $opt['letter'] ?? '' }}</option>
                                @endforeach
                            </select>
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
        </div>

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
