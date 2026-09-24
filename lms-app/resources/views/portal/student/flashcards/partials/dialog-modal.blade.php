<!-- CONFIRMATION & ALERT DIALOG MODAL (Clean Inline SVG to prevent FontAwesome JS DOM collision) -->
<div x-show="showDialogModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4" x-cloak>
    <!-- Backdrop overlay -->
    <div x-show="showDialogModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDialog()"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <!-- Modal Box Container -->
    <div x-show="showDialogModal"
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-md bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-6 overflow-hidden z-10 text-left">

        <!-- Header Modal (XiaoMu LMS Standard: Squircle Icon + Title + Close Button) -->
        <div class="flex items-center justify-between pb-4 border-b border-[#e8e2d9] dark:border-[#2d2926]">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Squircle Icon Container with clean SVGs (Guarantees exactly ONE icon) -->
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-xs"
                     :class="{
                         'bg-rose-50 dark:bg-rose-950/40 text-rose-500 border border-rose-200 dark:border-rose-900/60': dialogType === 'danger' || dialogType === 'error',
                         'bg-amber-50 dark:bg-amber-950/40 text-amber-500 border border-amber-200 dark:border-amber-900/60': dialogType === 'warning',
                         'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 border border-emerald-200 dark:border-emerald-900/60': dialogType === 'success',
                         'bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] border border-[#fcdccf] dark:border-[#e07a5f]/30': dialogType === 'info'
                     }">
                    <!-- Danger / Delete Icon -->
                    <template x-if="dialogType === 'danger'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </template>

                    <!-- Warning / Revert Progress Icon -->
                    <template x-if="dialogType === 'warning'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </template>

                    <!-- Error Icon -->
                    <template x-if="dialogType === 'error'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>

                    <!-- Success Icon -->
                    <template x-if="dialogType === 'success'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>

                    <!-- Info Icon -->
                    <template x-if="dialogType === 'info'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                </div>

                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight truncate"
                    x-text="dialogTitle"></h3>
            </div>

            <!-- Close Modal Button -->
            <button @click="closeDialog()"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0 cursor-pointer"
                    :title="'{{ __('Đóng') }}'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Message -->
        <div class="py-4">
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed"
               x-text="dialogMessage"></p>
        </div>

        <!-- Footer Actions (Right aligned buttons according to XiaoMu LMS guidelines) -->
        <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926]">
            <!-- Confirm Mode Buttons -->
            <template x-if="dialogMode === 'confirm'">
                <div class="flex items-center justify-end gap-2.5 w-full sm:w-auto">
                    <button type="button"
                            @click="closeDialog()"
                            class="px-4 py-2 rounded-xl bg-white dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold btn-tactile transition-all cursor-pointer">
                        <span x-text="dialogCancelText || '{{ __('Hủy bỏ') }}'"></span>
                    </button>
                    <button type="button"
                            @click="confirmDialog()"
                            class="px-4 py-2 rounded-xl text-white text-xs font-bold btn-tactile shadow-xs transition-all cursor-pointer flex items-center gap-1.5"
                            :class="{
                                'bg-rose-600 hover:bg-rose-700': dialogType === 'danger',
                                'bg-amber-600 hover:bg-amber-700': dialogType === 'warning',
                                'bg-[#e07a5f] hover:bg-[#c86349]': dialogType !== 'danger' && dialogType !== 'warning'
                            }">
                        <span x-text="dialogConfirmText || '{{ __('Xác nhận') }}'"></span>
                    </button>
                </div>
            </template>

            <!-- Alert Mode Button -->
            <template x-if="dialogMode === 'alert'">
                <button type="button"
                        @click="closeDialog()"
                        class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold btn-tactile shadow-xs transition-all cursor-pointer">
                    <span x-text="dialogConfirmText || '{{ __('Đã hiểu') }}'"></span>
                </button>
            </template>
        </div>
    </div>
</div>
