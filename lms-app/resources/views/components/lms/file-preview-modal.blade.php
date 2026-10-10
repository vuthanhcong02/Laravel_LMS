<div x-data="filePreviewModal()"
     @open-file-preview.window="openPreview($event.detail)"
     class="relative">

    <template x-teleport="body">
        <div x-show="isOpen"
             class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6 bg-slate-950/75 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             style="display: none;">

            <div @click.outside="closePreview()"
                 class="bg-white dark:bg-[#181615] rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden border border-slate-200 dark:border-slate-800 transform transition-all"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0 bg-white dark:bg-[#181615]">
                    <div class="flex items-center gap-3 min-w-0 pr-4">
                        <div class="size-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl" x-text="getFileIcon(fileName)"></span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white truncate" x-text="fileName || '{{ __('Xem trước tệp') }}'"></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="fileTypeLabel"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <template x-if="fileUrl">
                            <a :href="fileUrl" target="_blank"
                                class="size-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors"
                                title="{{ __('Mở trong tab mới') }}">
                                <span class="material-symbols-outlined text-lg">open_in_new</span>
                            </a>
                        </template>

                        <button @click="closePreview()"
                                type="button"
                                class="size-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-700 dark:hover:text-white flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6 flex flex-col items-center justify-center bg-slate-50 dark:bg-[#0e0c0b]/60 min-h-[300px]">

                    <template x-if="isImage(fileName)">
                        <div class="flex items-center justify-center max-h-[65vh]">
                            <img :src="fileUrl" :alt="fileName" class="max-w-full max-h-[65vh] rounded-2xl shadow-md object-contain border border-slate-200 dark:border-slate-700">
                        </div>
                    </template>

                    <template x-if="isAudio(fileName)">
                        <div class="p-8 sm:p-12 bg-white dark:bg-slate-900 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-800 flex flex-col items-center gap-6 w-full max-w-md text-center">
                            <div class="size-20 bg-primary/10 rounded-full flex items-center justify-center text-primary animate-pulse shadow-inner">
                                <span class="material-symbols-outlined text-4xl">graphic_eq</span>
                            </div>
                            <div class="space-y-1 w-full">
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate" x-text="fileName"></p>
                                <p class="text-xs text-slate-400">{{ __('Tệp âm thanh / Ghi âm giọng nói') }}</p>
                            </div>
                            <audio x-ref="audioPlayer" :src="fileUrl" controls class="w-full"></audio>
                        </div>
                    </template>

                    <template x-if="isPdf(fileName)">
                        <div class="w-full h-[65vh] rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white">
                            <iframe :src="fileUrl + '#toolbar=1'" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    </template>

                    <template x-if="!isImage(fileName) && !isAudio(fileName) && !isPdf(fileName)">
                        <div class="p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-md text-center max-w-sm w-full space-y-4">
                            <div class="size-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto">
                                <span class="material-symbols-outlined text-3xl" x-text="getFileIcon(fileName)"></span>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-bold text-slate-900 dark:text-white break-all" x-text="fileName"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    {{ __('Định dạng này không hỗ trợ xem trực tiếp. Vui lòng tải về máy để xem.') }}
                                </p>
                            </div>
                            <a :href="fileUrl" download class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-all shadow-md btn-tactile">
                                <span class="material-symbols-outlined text-sm">download</span>
                                <span>{{ __('Tải về thiết bị') }}</span>
                            </a>
                        </div>
                    </template>
                </div>

                <div class="px-6 py-3.5 bg-white dark:bg-[#181615] border-t border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0">
                    <span class="text-xs text-slate-400 font-medium truncate" x-text="fileName"></span>
                    <div class="flex items-center gap-2">
                        <template x-if="fileUrl">
                            <a :href="fileUrl" download class="px-4 py-2 bg-primary/10 hover:bg-primary/20 text-primary rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">download</span>
                                <span>{{ __('Tải xuống') }}</span>
                            </a>
                        </template>
                        <button @click="closePreview()" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition-colors">
                            {{ __('Đóng') }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </template>
</div>

