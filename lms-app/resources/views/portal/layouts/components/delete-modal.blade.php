<!-- Global Delete Modal -->
<div x-data="{ open: false, url: '', message: '' }" 
     @open-delete-modal.window="open = true; url = $event.detail.url; message = $event.detail.message"
     x-show="open" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0"
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="open = false" 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <!-- Modal Content -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 w-full max-w-lg overflow-hidden z-10">
         
        <div class="p-6 sm:p-8 text-center sm:text-left flex flex-col sm:flex-row gap-4 sm:gap-6 items-center sm:items-start">
            <div class="flex-shrink-0 flex items-center justify-center size-14 sm:size-12 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-full">
                <span class="material-symbols-outlined text-3xl sm:text-2xl">warning</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl sm:text-lg font-bold text-slate-900 dark:text-white mb-2">Xác nhận xóa</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed" x-text="message || 'Bạn có chắc chắn muốn thực hiện hành động này? Dữ liệu bị xóa sẽ không thể khôi phục lại được.'"></p>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-4 border-t border-slate-200 dark:border-slate-800">
            <button @click="open = false" type="button" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors w-full sm:w-auto">
                Hủy bỏ
            </button>
            <form :action="url" method="POST" class="w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold bg-red-600 hover:bg-red-700 text-white transition-all shadow-md shadow-red-600/20 active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                    Chắc chắn xóa
                </button>
            </form>
        </div>
    </div>
</div>
