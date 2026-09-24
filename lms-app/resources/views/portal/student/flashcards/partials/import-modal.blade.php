<div x-show="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4" x-cloak>
    <!-- Backdrop overlay -->
    <div x-show="showImportModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
          @click="!isSubmittingImport && closeImportModal()"
          class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <!-- Modal Content Box -->
    <div x-show="showImportModal"
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         :class="isImportPreviewing ? 'max-w-4xl' : 'max-w-2xl'"
         class="relative w-full bg-white dark:bg-[#181615] rounded-3xl border border-[#e8e2d9] dark:border-[#2d2926] shadow-2xl p-5 sm:p-6 overflow-hidden z-10 max-h-[92vh] flex flex-col transition-all duration-200">

        <!-- Header -->
        <div class="flex items-center justify-between pb-3.5 border-b border-[#e8e2d9] dark:border-[#2d2926] shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-base shadow-xs shrink-0">
                    <i class="fa-solid fa-file-import"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                        {{ __('Nhập Từ Vựng Hàng Loạt') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Thêm nhanh danh sách từ vựng từ Quizlet, Excel hoặc văn bản thô') }}
                    </p>
                </div>
            </div>
            <button @click="!isSubmittingImport && closeImportModal()"
                    :disabled="isSubmittingImport"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors disabled:opacity-50 cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- STAGE 1: INPUT FORM (Shown when NOT previewing) -->
        <div x-show="!isImportPreviewing" class="space-y-4 pt-4 overflow-y-auto pr-1 flex-1">
            <!-- Tabs: Quick Paste vs CSV File -->
            <div class="flex items-center justify-between gap-3">
                <div class="inline-flex items-center p-1 bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl text-xs font-bold shadow-2xs">
                    <button type="button"
                            @click="importTab = 'paste'"
                            class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile cursor-pointer"
                            :class="importTab === 'paste' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-paste text-xs"></i>
                        <span>{{ __('Dán văn bản') }}</span>
                    </button>
                    <button type="button"
                            @click="importTab = 'file'"
                            class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 btn-tactile cursor-pointer"
                            :class="importTab === 'file' ? 'bg-[#e07a5f] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                        <i class="fa-solid fa-file-csv text-xs"></i>
                        <span>{{ __('Tải file CSV') }}</span>
                    </button>
                </div>

                <!-- Action Button for Sample Data -->
                <button type="button"
                        x-show="importTab === 'paste'"
                        @click="insertSampleImportText()"
                        class="px-2.5 py-1 rounded-lg bg-[#e07a5f]/10 hover:bg-[#e07a5f]/20 text-[#e07a5f] text-xs font-semibold flex items-center gap-1.5 transition-all btn-tactile cursor-pointer">
                    <i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i>
                    <span>{{ __('Dán dữ liệu mẫu') }}</span>
                </button>
            </div>

            <!-- TAB 1: PASTE TEXT -->
            <div x-show="importTab === 'paste'" class="space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                    <label class="font-bold text-slate-700 dark:text-slate-300">
                        {{ __('Dán dữ liệu từ vựng (Mỗi từ 1 dòng)') }}
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 text-[11px]">{{ __('Dấu phân cách:') }}</span>
                        <select x-model="importDelimiter"
                                class="px-2 py-1 rounded-lg bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-[11px] font-semibold text-slate-700 dark:text-slate-300 focus:outline-hidden focus:border-[#e07a5f]">
                            <option value="auto">{{ __('Tự động nhận diện') }}</option>
                            <option value="dash">{{ __('Dấu gạch ngang (-)') }}</option>
                            <option value="tab">{{ __('Phím Tab (Từ Excel/Sheets)') }}</option>
                            <option value="comma">{{ __('Dấu phẩy (,)') }}</option>
                        </select>
                    </div>
                </div>

                <textarea x-model="rawImportText"
                          rows="6"
                          placeholder="{{ __('Dán hoặc gõ danh sách từ vựng vào đây (hoặc bấm \'Dán dữ liệu mẫu\' ở trên để xem thử)...') }}"
                          class="w-full p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] text-xs font-mono text-slate-900 dark:text-white focus:outline-hidden focus:border-[#e07a5f] dark:focus:border-[#e07a5f] transition-all resize-none leading-relaxed"></textarea>

                <!-- FORMAT GUIDELINES FOR END-USERS -->
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-list-check text-[#e07a5f]"></i>
                            <span>{{ __('Cấu trúc các cột thông tin đầy đủ:') }}</span>
                        </span>
                        <span class="text-slate-400 text-[10px]">{{ __('Hỗ trợ tối đa 500 từ/lần') }}</span>
                    </div>

                    <!-- Visual Column Sequence -->
                    <div class="p-3 rounded-2xl bg-[#fcfaf7] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2.5">
                        <div class="flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-slate-700 dark:text-slate-300">
                            <span class="px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] border border-[#e07a5f]/20">1. {{ __('Chữ Hán') }} *</span>
                            <span class="text-slate-300 dark:text-slate-600">→</span>
                            <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200/50">2. {{ __('Pinyin') }}</span>
                            <span class="text-slate-300 dark:text-slate-600">→</span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50">3. {{ __('Ý nghĩa') }} *</span>
                            <span class="text-slate-300 dark:text-slate-600">→</span>
                            <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-200/50">4. {{ __('Ví dụ') }}</span>
                            <span class="text-slate-300 dark:text-slate-600">→</span>
                            <span class="px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-200/50">5. {{ __('Dịch ví dụ') }}</span>
                        </div>

                        <!-- 2 Format Examples -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <!-- Format 1: Dash -->
                            <div class="p-2.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9]/70 dark:border-[#2d2926] space-y-1">
                                <div class="text-[11px] font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-pencil text-[#e07a5f] text-[10px]"></i>
                                        <span>{{ __('Cách 1: Gõ dấu gạch (-)') }}</span>
                                    </span>
                                </div>
                                <div class="text-[11px] font-mono text-slate-600 dark:text-slate-300 leading-relaxed bg-[#f8f6f3] dark:bg-[#201d1b] p-2 rounded-lg border border-[#e8e2d9]/40 dark:border-[#2d2926] space-y-0.5">
                                    <div><span class="zh-text font-bold text-slate-900 dark:text-white">你好</span> - nǐ hǎo - Xin chào - 你好！ - Xin chào!</div>
                                    <div><span class="zh-text font-bold text-slate-900 dark:text-white">谢谢</span> - xièxie - Cảm ơn - 非常感谢！ - Rất cảm ơn!</div>
                                </div>
                            </div>

                            <!-- Format 2: Excel / Sheets -->
                            <div class="p-2.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9]/70 dark:border-[#2d2926] space-y-1">
                                <div class="text-[11px] font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-table-cells text-emerald-500 text-[10px]"></i>
                                        <span>{{ __('Cách 2: Từ Excel / Sheets') }}</span>
                                    </span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 font-medium">{{ __('Copy & Paste') }}</span>
                                </div>
                                <div class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed bg-[#f8f6f3] dark:bg-[#201d1b] p-2 rounded-lg border border-[#e8e2d9]/40 dark:border-[#2d2926]">
                                    <span>{{ __('Tạo bảng gồm các cột theo thứ tự trên trong Excel hoặc Google Sheets, bôi đen danh sách rồi dán thẳng vào ô văn bản.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Smart Tips -->
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-start gap-1.5 pt-0.5">
                            <i class="fa-solid fa-wand-magic-sparkles text-amber-500 text-xs shrink-0 mt-0.5"></i>
                            <span>{{ __('Hệ thống linh hoạt: Tự động tạo Pinyin nếu bạn bỏ trống cột Pinyin. Các cột Ví dụ & Dịch ví dụ không bắt buộc.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: UPLOAD CSV FILE -->
            <div x-show="importTab === 'file'" class="space-y-4">
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <div class="space-y-0.5">
                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ __('Tệp tin CSV mẫu') }}</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Định dạng chuẩn UTF-8 bao gồm các cột: Chữ Hán, Pinyin, Nghĩa, Ví dụ, Dịch ví dụ') }}</div>
                    </div>
                    <button type="button"
                            @click="downloadCsvTemplate()"
                            class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] text-slate-700 dark:text-slate-300 hover:text-[#e07a5f] text-xs font-bold flex items-center gap-1.5 shadow-2xs btn-tactile cursor-pointer shrink-0">
                        <i class="fa-solid fa-download text-xs"></i>
                        <span>{{ __('Tải tệp mẫu') }}</span>
                    </button>
                </div>

                <div class="border-2 border-dashed border-[#e8e2d9] dark:border-[#2d2926] hover:border-[#e07a5f] rounded-2xl p-6 sm:p-8 text-center transition-colors cursor-pointer"
                     @click="$refs.csvFileInput.click()">
                    <input type="file"
                           x-ref="csvFileInput"
                           accept=".csv,text/csv"
                           @change="handleCsvFileSelect($event)"
                           class="hidden">
                    <div class="w-12 h-12 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] flex items-center justify-center text-xl mx-auto mb-3 shadow-xs">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-800 dark:text-white mb-1">
                        {{ __('Chọn tệp tin CSV từ máy tính của bạn') }}
                    </p>
                    <p class="text-[11px] text-slate-400">
                        {{ __('Kéo thả hoặc nhấn vào để duyệt file (.csv, tối đa 2MB)') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- STAGE 2: PREVIEW TABLE (Shown when previewing parsed results) -->
        <div x-show="isImportPreviewing" class="space-y-3 pt-4 overflow-y-auto pr-1 flex-1">
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-800 dark:text-white">{{ __('Xem trước danh sách từ:') }}</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] font-bold text-[11px]"
                          x-text="parsedImportCards.length + ' {{ __('từ hợp lệ') }}'"></span>
                </div>
                <button type="button"
                        @click="isImportPreviewing = false"
                        class="text-slate-500 hover:text-[#e07a5f] font-semibold text-xs flex items-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span x-text="importTab === 'file' ? '{{ __('Quay lại tải tệp') }}' : '{{ __('Quay lại sửa văn bản') }}'"></span>
                </button>
            </div>

            <div class="border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl overflow-hidden shadow-2xs">
                <div class="overflow-x-auto max-h-[45vh]">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-[#f8f6f3] dark:bg-[#201d1b] border-b border-[#e8e2d9] dark:border-[#2d2926] sticky top-0 z-10">
                            <tr>
                                <th class="py-2.5 px-3 font-bold text-slate-500 dark:text-slate-400 text-[10px] uppercase w-10 text-center">#</th>
                                <th class="py-2.5 px-3 font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ __('Chữ Hán') }} *</th>
                                <th class="py-2.5 px-3 font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ __('Pinyin') }}</th>
                                <th class="py-2.5 px-3 font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ __('Ý nghĩa') }} *</th>
                                <th class="py-2.5 px-3 font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ __('Câu ví dụ') }}</th>
                                <th class="py-2.5 px-3 font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ __('Dịch câu ví dụ') }}</th>
                                <th class="py-2.5 px-2 font-bold text-slate-500 dark:text-slate-400 text-center w-8"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e8e2d9]/60 dark:divide-[#2d2926]">
                            <template x-for="(card, index) in parsedImportCards" :key="index">
                                <tr class="transition-colors"
                                    :class="card.isDuplicate ? 'bg-amber-500/10 dark:bg-amber-950/20' : 'hover:bg-slate-50/80 dark:hover:bg-slate-800/40'">
                                    <td class="py-2 px-3 text-center text-slate-400 font-mono text-[11px]" x-text="index + 1"></td>
                                    <td class="py-2 px-3">
                                        <div class="flex items-center gap-1.5">
                                            <input type="text"
                                                   x-model="card.word"
                                                   @input="refreshPreviewPinyin(card); recomputeDuplicates()"
                                                   class="w-full px-2 py-1 rounded-lg bg-transparent border border-transparent hover:border-[#e8e2d9] dark:hover:border-[#2d2926] focus:bg-white dark:focus:bg-[#201d1b] focus:border-[#e07a5f] font-bold zh-text text-sm text-slate-900 dark:text-white">
                                            <template x-if="card.isDuplicate">
                                                <span class="px-1.5 py-0.5 rounded-md bg-amber-100 dark:bg-amber-900/60 border border-amber-200 dark:border-amber-800/80 text-amber-700 dark:text-amber-300 text-[10px] font-bold shrink-0 shadow-2xs"
                                                      :title="'{{ __('Từ này đã có trong bộ thẻ, hệ thống sẽ tự động bỏ qua khi nhập') }}'">
                                                    {{ __('Trùng lặp') }}
                                                </span>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="text"
                                               x-model="card.pinyin"
                                               class="w-full px-2 py-1 rounded-lg bg-transparent border border-transparent hover:border-[#e8e2d9] dark:hover:border-[#2d2926] focus:bg-white dark:focus:bg-[#201d1b] focus:border-[#e07a5f] text-xs font-semibold text-[#e07a5f]">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="text"
                                               x-model="card.meaning"
                                               class="w-full px-2 py-1 rounded-lg bg-transparent border border-transparent hover:border-[#e8e2d9] dark:hover:border-[#2d2926] focus:bg-white dark:focus:bg-[#201d1b] focus:border-[#e07a5f] text-xs text-slate-800 dark:text-slate-200">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="text"
                                               x-model="card.example"
                                               placeholder="{{ __('(Không bắt buộc)') }}"
                                               class="w-full px-2 py-1 rounded-lg bg-transparent border border-transparent hover:border-[#e8e2d9] dark:hover:border-[#2d2926] focus:bg-white dark:focus:bg-[#201d1b] focus:border-[#e07a5f] text-xs text-slate-600 dark:text-slate-400 italic">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input type="text"
                                               x-model="card.example_meaning"
                                               placeholder="{{ __('(Không bắt buộc)') }}"
                                               class="w-full px-2 py-1 rounded-lg bg-transparent border border-transparent hover:border-[#e8e2d9] dark:hover:border-[#2d2926] focus:bg-white dark:focus:bg-[#201d1b] focus:border-[#e07a5f] text-xs text-slate-600 dark:text-slate-400 italic">
                                    </td>
                                    <td class="py-2 px-2 text-center">
                                        <button type="button"
                                                @click="removePreviewCard(index)"
                                                class="w-6 h-6 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 flex items-center justify-center transition-colors cursor-pointer"
                                                :title="'{{ __('Xóa dòng này') }}'">
                                            <i class="fa-solid fa-trash text-[10px]"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="pt-4 border-t border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between gap-3 shrink-0">
            <div>
                <template x-if="isImportPreviewing">
                    <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                        <span>
                            {{ __('Tổng cộng:') }} <strong class="text-slate-800 dark:text-white" x-text="parsedImportCards.length"></strong> {{ __('từ') }}
                        </span>
                        <template x-if="duplicateImportCardsCount > 0">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-600 dark:text-amber-400 text-[11px] font-medium">
                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                <span x-text="'{{ __('Tự động bỏ qua') }} ' + duplicateImportCardsCount + ' {{ __('từ trùng') }}'"></span>
                            </span>
                        </template>
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button"
                        x-show="isImportPreviewing"
                        @click="isImportPreviewing = false"
                        class="px-4 py-2 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span x-text="importTab === 'file' ? '{{ __('Chọn lại tệp') }}' : '{{ __('Sửa lại văn bản') }}'"></span>
                </button>

                <button type="button"
                        :disabled="isSubmittingImport"
                        @click="!isSubmittingImport && closeImportModal()"
                        class="px-4 py-2 rounded-xl border border-[#e8e2d9] dark:border-[#2d2926] bg-white dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all disabled:opacity-50 cursor-pointer">
                    {{ __('Hủy bỏ') }}
                </button>

                <!-- Button when in Text Paste Mode -->
                <button type="button"
                        x-show="!isImportPreviewing && importTab === 'paste'"
                        @click="processAndPreviewImport()"
                        class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 btn-tactile cursor-pointer">
                    <i class="fa-solid fa-eye text-xs"></i>
                    <span>{{ __('Kiểm tra & Xem trước') }}</span>
                </button>

                <!-- Button when in CSV File Mode -->
                <button type="button"
                        x-show="!isImportPreviewing && importTab === 'file'"
                        @click="$refs.csvFileInput.click()"
                        class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 btn-tactile cursor-pointer">
                    <i class="fa-solid fa-folder-open text-xs"></i>
                    <span>{{ __('Chọn tệp CSV') }}</span>
                </button>

                <!-- Button when in Preview Mode -->
                <button type="button"
                        x-show="isImportPreviewing"
                        @click="submitImportCards()"
                        :disabled="isSubmittingImport || newImportCardsCount === 0"
                        class="px-5 py-2 rounded-xl bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 btn-tactile disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                    <i x-show="isSubmittingImport" class="fa-solid fa-spinner fa-spin text-xs"></i>
                    <i x-show="!isSubmittingImport" class="fa-solid fa-check text-xs"></i>
                    <span x-show="newImportCardsCount > 0" x-text="'{{ __('Nhập') }} ' + newImportCardsCount + ' {{ __('từ mới vào bộ thẻ') }}'"></span>
                    <span x-show="newImportCardsCount === 0">{{ __('Không có từ mới để nhập') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
