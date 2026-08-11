<div>
    <div class="space-y-4">
        <!-- Questions List -->
        <div class="space-y-4 mt-2">
            <h4 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2">
                Danh sách Câu hỏi ({{ $group->questions->count() }})
            </h4>

            @foreach($group->questions as $index => $question)
                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                    <!-- Question Header -->
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
                            <button wire:click="deleteQuestion({{ $question->id }})" wire:confirm="Xóa câu này?" class="text-red-500 hover:text-red-600 transition-colors p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </div>

                    <!-- Question Body -->
                    <div class="space-y-4 pl-11">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Hình ảnh minh họa</label>
                            @if($question->image)
                                <div class="mb-3 relative inline-block group">
                                    <img src="{{ hsk_storage_url($question->image) }}" class="h-20 rounded border border-slate-200">
                                </div>
                            @endif
                            <div class="relative">
                                <input type="file" wire:model="questionImages.{{ $question->id }}" accept="image/*"
                                    class="w-full p-2 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                                
                                <div wire:loading wire:target="questionImages.{{ $question->id }}" class="absolute inset-y-0 right-3 flex items-center">
                                    <div class="w-4 h-4 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Đáp án Đúng</label>
                            <select wire:model="trueFalseAnswers.{{ $question->id }}" class="w-full p-2 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-bold">
                                <option value="1">Đúng (True)</option>
                                <option value="0">Sai (False)</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex items-center gap-3 mt-4">
            <button wire:click="addQuestion" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm font-bold text-sm text-primary hover:border-primary/50 transition-colors">
                <span class="material-symbols-outlined text-lg">add</span>
                Thêm Câu hỏi
            </button>
            <button wire:click="saveGroup" class="flex items-center gap-2 px-6 py-2 bg-emerald-600 text-white rounded-lg shadow-sm font-bold text-sm hover:bg-emerald-700 transition-colors">
                <span class="material-symbols-outlined text-lg">save</span>
                Lưu Part này
            </button>
            <span class="text-xs text-emerald-600 font-bold" wire:loading wire:target="saveGroup">Đang lưu...</span>
        </div>
    </div>
</div>
