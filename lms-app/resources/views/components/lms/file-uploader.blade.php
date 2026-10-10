@props([
    'name' => 'attachments[]',
    'maxFiles' => 5,
    'maxSizeMB' => 20,
    'accept' => '.jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.webm,.mp3,.wav,.ogg,.m4a',
    'compact' => false,
    'label' => null,
    'helperText' => null,
])

<div x-data="fileUploadPreview({ maxFiles: {{ $maxFiles }}, maxSizeMB: {{ $maxSizeMB }} })" class="space-y-2.5">
    @if($label)
        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center justify-between">
            <span>{{ $label }}</span>
            <span class="text-[11px] font-semibold text-primary" x-show="files.length > 0" x-text="files.length + '/{{ $maxFiles }} tệp'"></span>
        </label>
    @endif

    <div class="relative flex flex-col items-center justify-center {{ $compact ? 'min-h-[80px] p-3' : 'p-4 sm:p-5' }} border-2 border-dashed rounded-xl cursor-pointer transition-all text-center group"
         :class="isDragging ? 'border-primary bg-primary/5' : 'border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-slate-100/80 dark:hover:bg-slate-800 hover:border-slate-400'"
         @click="$refs.fileInput.click()"
         @dragover.prevent="isDragging = true"
         @dragleave.prevent="isDragging = false"
         @drop.prevent="isDragging = false; handleFiles($event.dataTransfer.files)">
        
        <input x-ref="fileInput" type="file" name="{{ $name }}" multiple class="hidden" accept="{{ $accept }}"
               @click.stop
               @change="handleFiles($event.target.files)">

        <div class="{{ $compact ? 'size-8 mb-1' : 'size-10 mb-2' }} rounded-xl bg-primary/10 text-primary flex items-center justify-center group-hover:scale-105 transition-transform">
            <span class="material-symbols-outlined {{ $compact ? 'text-lg' : 'text-xl' }}">cloud_upload</span>
        </div>
        
        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
            {{ __('Kéo thả tệp vào đây hoặc') }} <span class="text-primary underline">{{ __('Chọn từ máy') }}</span>
        </p>
        
        <p class="text-[10px] text-slate-400 mt-0.5">
            {{ $helperText ?? __('Hỗ trợ: PDF, Word, Excel, Ảnh, Audio, Zip... (Tối đa :max tệp, :sizeMB MB/tệp)', ['max' => $maxFiles, 'sizeMB' => $maxSizeMB]) }}
        </p>
    </div>

    <div x-show="files.length > 0" class="space-y-1.5 mt-2" style="display: none;">
        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Danh sách tệp đã chọn:') }}</p>
        <div class="{{ $compact ? 'space-y-1.5 max-h-48' : 'grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60' }} overflow-y-auto pr-1">
            <template x-for="(item, idx) in files" :key="idx">
                <div class="flex items-center gap-2.5 p-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs hover:border-primary/40 transition-colors">
                    <div class="size-8 sm:size-9 rounded-lg overflow-hidden shrink-0 flex items-center justify-center bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600">
                        <template x-if="item.isImg">
                            <img :src="item.previewUrl" :alt="item.name" class="size-full object-cover">
                        </template>
                        <template x-if="!item.isImg">
                            <span class="material-symbols-outlined text-base sm:text-lg text-primary" x-text="item.icon"></span>
                        </template>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate" x-text="item.name"></p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-semibold text-slate-400" x-text="item.sizeFormatted"></span>
                            <span class="text-[9px] font-bold px-1.5 py-0.2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded" x-text="item.ext"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" @click="previewSelectedFile(item)"
                                class="size-7 rounded-lg text-slate-400 hover:text-primary hover:bg-primary/10 flex items-center justify-center transition-colors"
                                title="{{ __('Xem trước') }}">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                        </button>
                        <button type="button" @click="removeFile(idx)"
                                class="size-7 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/40 flex items-center justify-center transition-colors"
                                title="{{ __('Gỡ bỏ') }}">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
