<div>
    <div class="space-y-4">
        <!-- Passage Text Input -->
        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                Nội dung hội thoại Ví dụ (Passage Text) <span class="text-xs font-normal text-slate-500">(Không bắt buộc)</span>
            </label>
            <textarea wire:model.defer="group.passage_text" rows="3"
                class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:border-primary focus:ring-primary shadow-sm text-sm"
                placeholder="Nhập nội dung hội thoại ví dụ (nếu có). Ví dụ: 女：你好！ 男：你好！很高兴认识你。"></textarea>
            <p class="text-xs text-slate-500 mt-2">Nội dung này sẽ hiển thị ở khối Ví dụ ngoài giao diện thi. Bỏ trống nếu không muốn hiển thị.</p>
        </div>
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
                            <button type="button" wire:click="deleteQuestion({{ $question->id }})" wire:confirm="Xóa câu này?" class="text-red-500 hover:text-red-600 transition-colors p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </div>

                    <!-- Question Body -->
                    <div class="space-y-4 pl-11">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Các lựa chọn hình ảnh</label>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($question->options as $optIndex => $option)
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-2 bg-white dark:bg-slate-800 relative">
                                        <div class="absolute top-2 right-2 flex items-center gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-md shadow-sm border border-slate-200 dark:border-slate-700">
                                            <input type="radio" wire:model.live="correctAnswers.{{ $question->id }}" name="correct_{{ $question->id }}" value="{{ $option->id }}" class="w-5 h-5 text-emerald-500 bg-slate-100 border-slate-300 focus:ring-emerald-500 cursor-pointer">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide px-1">Đáp án</span>
                                        </div>
                                        <span class="font-bold text-slate-500 mb-2 block">Lựa chọn {{ chr(65 + $optIndex) }}</span>
                                        
                                        @if($option->image)
                                            <div class="mb-2 relative inline-block group w-full">
                                                <img src="{{ hsk_storage_url($option->image) }}" class="h-16 w-full object-contain rounded bg-slate-50 border border-slate-200">
                                            </div>
                                        @endif
                                        
                                        <div class="relative">
                                            <input type="file" wire:model="optionImages.{{ $option->id }}" accept="image/*"
                                                class="w-full text-[10px] rounded border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                                            
                                            <div wire:loading wire:target="optionImages.{{ $option->id }}" class="absolute inset-y-0 right-2 flex items-center">
                                                <div class="w-3 h-3 border border-primary border-t-transparent rounded-full animate-spin"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
