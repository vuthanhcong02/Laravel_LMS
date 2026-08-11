<div>
    <div class="space-y-4">
        <!-- Options Mode Toggle -->
        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" wire:model.live="useTextOptions" class="w-5 h-5 text-primary bg-slate-100 border-slate-300 rounded focus:ring-primary">
                <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Sử dụng Văn bản (Text) cho các Đáp án A-F (Thay vì Hình ảnh)</span>
            </label>
        </div>

        @if(!$useTextOptions)
        <!-- Passage Images Upload -->
        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Hình ảnh chung cho Part (Passage Images)</label>
            <p class="text-[11px] text-slate-400 mb-2">Upload các bức ảnh A, B, C, D, E, F... tại đây. Bạn có thể kéo thả hoặc chọn nhiều file.</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                @php 
                    $cleanPassageImg = str_replace(['[', ']', '"', '\\'], '', $group->passage_image);
                    $pImgs = $cleanPassageImg ? explode(',', $cleanPassageImg) : []; 
                @endphp
                @if(count($pImgs) > 0)
                    @foreach($pImgs as $idx => $img)
                        <div class="relative group border border-slate-200 dark:border-slate-700 rounded-lg p-1 bg-white dark:bg-slate-900">
                            <span class="absolute top-1 left-1 bg-slate-800 text-white text-[10px] px-1.5 py-0.5 rounded font-bold z-10">{{ chr(65 + $idx) }}</span>
                            <img src="{{ hsk_storage_url($img) }}" class="w-full h-24 object-contain rounded">
                            <button type="button" wire:click="removePassageImage({{ $idx }})" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full hidden group-hover:flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors z-10">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="relative">
                <input type="file" wire:model="newPassageImages" multiple accept="image/*"
                    class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                
                <div wire:loading wire:target="newPassageImages" class="absolute inset-y-0 right-3 flex items-center">
                    <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <span class="ml-2 text-xs text-slate-500 font-bold">Đang tải lên...</span>
                </div>
            </div>
        </div>
        @else
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
        @endif

        <!-- Questions List -->
        <div class="space-y-4 mt-2">
            <h4 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-2">
                Danh sách Câu hỏi ({{ $group->questions->count() }})
            </h4>

            @foreach($group->questions as $index => $question)
                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 mb-4" wire:key="q-{{ $question->id }}">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 shrink-0 {{ $question->is_example ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 border-amber-200' : 'bg-white dark:bg-slate-700 text-slate-500 border-slate-200 dark:border-slate-600' }} rounded-lg border flex items-center justify-center font-black text-sm shadow-sm">
                                {{ $question->is_example ? 'VD' : $index + 1 }}
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Nội dung Câu hỏi</span>
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

                    <div class="mt-4 pl-11 flex flex-col gap-4">
                        <div class="w-full">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Text Câu hỏi (Chữ Hán thô hoặc có {pinyin|Hán})</label>
                            <textarea wire:model="questionTitles.{{ $index }}" rows="2" placeholder="Ví dụ: 你好..." class="w-full text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Đáp án đúng</label>
                            <select wire:model="correctAnswers.{{ $question->id }}" class="text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 font-bold focus:ring-primary focus:border-primary">
                                <option value="">-- Chọn --</option>
                                @foreach(['A','B','C','D','E','F','G','H'] as $letter)
                                    <option value="{{ $letter }}">Đáp án {{ $letter }}</option>
                                @endforeach
                            </select>
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
