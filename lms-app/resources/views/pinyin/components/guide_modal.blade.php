<!-- Pronunciation & Mouth Shape Guide Modal -->
<template x-teleport="body">
<div x-show="showGuideModal" 
     x-cloak
     style="display: none;" 
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
    
    <!-- Backdrop -->
    <div x-show="showGuideModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <!-- Modal Panel -->
    <div x-show="showGuideModal"
         @click.away="showGuideModal = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="lms-card max-w-2xl w-full p-5 sm:p-6 border border-[#e8e2d9] dark:border-[#2d2926] relative max-h-[85vh] flex flex-col z-10 shadow-2xl space-y-4 hover:transform-none">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-[#e8e2d9] dark:border-[#2d2926] shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#fff2ee] dark:bg-[#2c221e] border border-[#fcdccf] dark:border-[#4a2e26] flex items-center justify-center text-[#e07a5f] shrink-0">
                    <i class="fa-solid fa-headset text-base pointer-events-none"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white leading-tight">
                        {{ __('Hướng Dẫn Khẩu Hình & Mẹo Phát Âm') }}
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                        {{ __('Sơ đồ cấu âm vị trí bộ máy phát âm chuẩn Quốc tế (IPA)') }}
                    </p>
                </div>
            </div>
            <button type="button" 
                    @click="showGuideModal = false" 
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 bg-[#f8f6f3] dark:bg-[#201d1b] rounded-full w-8 h-8 flex items-center justify-center cursor-pointer transition-colors btn-tactile shrink-0">
                <i class="fa-solid fa-xmark text-sm pointer-events-none"></i>
            </button>
        </div>

        <!-- Body with Custom Tabs & Scrollable Content -->
        <div class="overflow-y-auto pr-1 flex-1 space-y-5 no-scrollbar" x-data="{ activeGroup: 'labial' }">
            
            <!-- Group Tabs Navigation (1 Single Row with horizontal scroll) -->
            <div class="flex flex-nowrap gap-1.5 overflow-x-auto pb-1 no-scrollbar border-b border-[#e8e2d9]/60 dark:border-[#2d2926]">
                <button type="button" @click="activeGroup = 'labial'" 
                        :class="activeGroup === 'labial' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 font-semibold hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926]'"
                        class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer select-none shrink-0">
                    {{ __('Âm Môi (b, p, m, f)') }}
                </button>
                <button type="button" @click="activeGroup = 'alveolar'" 
                        :class="activeGroup === 'alveolar' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 font-semibold hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926]'"
                        class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer select-none shrink-0">
                    {{ __('Đầu Lưỡi (d, t, n, l)') }}
                </button>
                <button type="button" @click="activeGroup = 'velar'" 
                        :class="activeGroup === 'velar' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 font-semibold hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926]'"
                        class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer select-none shrink-0">
                    {{ __('Cuống Lưỡi (g, k, h)') }}
                </button>
                <button type="button" @click="activeGroup = 'palatal'" 
                        :class="activeGroup === 'palatal' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 font-semibold hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926]'"
                        class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer select-none shrink-0">
                    {{ __('Mặt Lưỡi (j, q, x)') }}
                </button>
                <button type="button" @click="activeGroup = 'retroflex'" 
                        :class="activeGroup === 'retroflex' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 font-semibold hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926]'"
                        class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer select-none shrink-0">
                    {{ __('Uốn Lưỡi (zh, ch, sh, r)') }}
                </button>
                <button type="button" @click="activeGroup = 'dental'" 
                        :class="activeGroup === 'dental' ? 'bg-[#e07a5f] text-white font-bold shadow-xs' : 'bg-[#f8f6f3] dark:bg-[#201d1b] text-slate-700 dark:text-slate-300 font-semibold hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] border border-[#e8e2d9] dark:border-[#2d2926]'"
                        class="px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all btn-tactile cursor-pointer select-none shrink-0">
                    {{ __('Đầu Lưỡi Thẳng (z, c, s)') }}
                </button>
            </div>

            <!-- Tab Content 1: Âm Môi (b, p, m, f) -->
            <div x-show="activeGroup === 'labial'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <img src="/images/pinyin/labial.png" alt="Sơ đồ khẩu hình Âm Môi Bilabial (b, p, m)" class="w-44 h-44 object-contain bg-white rounded-xl p-2 shadow-xs border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-[#e07a5f]">Sơ đồ vị trí khép môi IPA (Bilabial Articulation)</span>
                </div>

                <div class="p-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#e07a5f] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">b</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-xs sm:text-sm">Âm b (Không bật hơi)</h4>
                            <p class="text-[11px] text-slate-400">Đọc gần giống chữ "b" trong tiếng Việt</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Mím nhẹ hai môi, chặn luồng hơi lại rồi mở nhẹ hai môi cho hơi thoát ra tự nhiên. Không đẩy luồng hơi mạnh.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#fff7f4] dark:bg-[#241d1a] border border-[#fcdccf] dark:border-[#4a2e26] space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">p</span>
                            <div>
                                <h4 class="font-bold text-[#e07a5f] dark:text-[#f4978e] text-xs sm:text-sm">Âm p (ÂM BẬT HƠI MẠNH 💨)</h4>
                                <p class="text-[11px] text-slate-400">Đọc gần giống "p" nhưng phải bật luồng hơi mạnh ra ngoài</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-[#e07a5f]/15 text-[#e07a5f] uppercase tracking-wider">Bật hơi</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Mẹo kiểm tra:</strong> Đặt tờ giấy trước miệng khi phát âm `p`, tờ giấy phải <strong>bay mạnh</strong> ra phía trước. Mím chặt hai môi, nén hơi rồi dồn lực bật mạnh ra.
                    </p>
                </div>
            </div>

            <!-- Tab Content 2: Đầu Lưỡi (d, t, n, l) -->
            <div x-show="activeGroup === 'alveolar'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <img src="/images/pinyin/alveolar.png" alt="Sơ đồ khẩu hình Đầu Lưỡi Alveolar (d, t, n, l)" class="w-44 h-44 object-contain bg-white rounded-xl p-2 shadow-xs border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-[#e07a5f]">Sơ đồ vị trí chạm chân răng trên IPA (Alveolar Articulation)</span>
                </div>

                <div class="p-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#e07a5f] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">d</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-xs sm:text-sm">Âm d (Không bật hơi)</h4>
                            <p class="text-[11px] text-slate-400">Đọc giống chữ "t" trong tiếng Việt (vd: tôi, đi)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Đầu lưỡi chạm vào chân răng trên, chặn hơi lại rồi bật nhẹ đầu lưỡi xuống. Không đẩy hơi mạnh.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#fff7f4] dark:bg-[#241d1a] border border-[#fcdccf] dark:border-[#4a2e26] space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">t</span>
                            <div>
                                <h4 class="font-bold text-[#e07a5f] dark:text-[#f4978e] text-xs sm:text-sm">Âm t (Bật hơi mạnh 💨)</h4>
                                <p class="text-[11px] text-slate-400">Đọc giống chữ "th" trong tiếng Việt (vd: thỏ, thơ)</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-[#e07a5f]/15 text-[#e07a5f] uppercase tracking-wider">Bật hơi</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Đầu lưỡi chạm chân răng trên, nén hơi rồi bật mạnh đầu lưỡi xuống cho hơi thoát ra dứt khoát.
                    </p>
                </div>
            </div>

            <!-- Tab Content 3: Cuống Lưỡi (g, k, h) -->
            <div x-show="activeGroup === 'velar'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <img src="/images/pinyin/velar.png" alt="Sơ đồ khẩu hình Cuống Lưỡi Velar (g, k, h)" class="w-44 h-44 object-contain bg-white rounded-xl p-2 shadow-xs border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-[#e07a5f]">Sơ đồ vị trí áp cuống lưỡi IPA (Velar Articulation)</span>
                </div>

                <div class="p-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#e07a5f] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">g</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-xs sm:text-sm">Âm g (Không bật hơi)</h4>
                            <p class="text-[11px] text-slate-400">Đọc giống chữ "c" / "k" trong tiếng Việt (vd: con, gà)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Cuống lưỡi thụt về sau áp sát ngạc mềm (vòm miệng mềm), luồng hơi bị chặn lại rồi hạ cuống lưỡi xuống nhẹ nhàng.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#fff7f4] dark:bg-[#241d1a] border border-[#fcdccf] dark:border-[#4a2e26] space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">k</span>
                            <div>
                                <h4 class="font-bold text-[#e07a5f] dark:text-[#f4978e] text-xs sm:text-sm">Âm k (Bật hơi mạnh 💨)</h4>
                                <p class="text-[11px] text-slate-400">Đọc giống chữ "kh" nhưng khạc nhẹ hơi từ cổ họng</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-[#e07a5f]/15 text-[#e07a5f] uppercase tracking-wider">Bật hơi</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Cuống lưỡi nâng lên ngạc mềm, dồn hơi rồi bật mạnh cuống lưỡi xuống tạo luồng hơi bật mạnh.
                    </p>
                </div>
            </div>

            <!-- Tab Content 4: Mặt Lưỡi (j, q, x) -->
            <div x-show="activeGroup === 'palatal'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <img src="/images/pinyin/palatal.png" alt="Sơ đồ khẩu hình Mặt Lưỡi Palatal (j, q, x)" class="w-44 h-44 object-contain bg-white rounded-xl p-2 shadow-xs border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-[#e07a5f]">Sơ đồ vị trí nâng mặt lưỡi IPA (Palatal Articulation)</span>
                </div>

                <div class="p-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#e07a5f] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">j</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-xs sm:text-sm">Âm j (Không bật hơi)</h4>
                            <p class="text-[11px] text-slate-400">Đọc giống chữ "ch" nhẹ trong tiếng Việt (vd: chi, chị)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Mặt trước lưỡi nâng cao áp sát vòm miệng cứng, luồng hơi ngắt lại rồi mở nhẹ ra. Hai khóe miệng hơi kéo sang 2 bên như đang mỉm cười.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#fff7f4] dark:bg-[#241d1a] border border-[#fcdccf] dark:border-[#4a2e26] space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">q</span>
                            <div>
                                <h4 class="font-bold text-[#e07a5f] dark:text-[#f4978e] text-xs sm:text-sm">Âm q (Bật hơi mạnh 💨)</h4>
                                <p class="text-[11px] text-slate-400">Giống âm "ch" nhưng bật hơi cực mạnh</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-[#e07a5f]/15 text-[#e07a5f] uppercase tracking-wider">Bật hơi</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Giữ nguyên vị trí của âm `j`, nhưng nén luồng hơi mạnh trong miệng rồi bật mạnh ra giữa mặt lưỡi và ngạc cứng.
                    </p>
                </div>
            </div>

            <!-- Tab Content 5: Uốn Lưỡi (zh, ch, sh, r) -->
            <div x-show="activeGroup === 'retroflex'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <img src="/images/pinyin/retroflex.png" alt="Sơ đồ uốn lưỡi Retroflex (zh, ch, sh, r)" class="w-44 h-44 object-contain bg-white rounded-xl p-2 shadow-xs border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-[#e07a5f]">Sơ đồ vị trí uốn lưỡi IPA (Retroflex Articulation)</span>
                </div>

                <div class="p-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-8 rounded-lg bg-[#e07a5f] text-white font-bold text-sm flex items-center justify-center shadow-xs shrink-0">zh</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-xs sm:text-sm">Âm zh (Uốn lưỡi - Không bật hơi)</h4>
                            <p class="text-[11px] text-slate-400">Đọc giống chữ "tr" trong tiếng Việt (vd: trâu, tre)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> <strong>Uốn cong đầu lưỡi lên trên</strong> áp sát vòm miệng cứng (ngạc cứng), thả nhẹ đầu lưỡi cho hơi thoát ra. Không đẩy luồng hơi mạnh.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#fff7f4] dark:bg-[#241d1a] border border-[#fcdccf] dark:border-[#4a2e26] space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-8 rounded-lg bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white font-bold text-sm flex items-center justify-center shadow-xs shrink-0">ch</span>
                            <div>
                                <h4 class="font-bold text-[#e07a5f] dark:text-[#f4978e] text-xs sm:text-sm">Âm ch (Uốn lưỡi + Bật hơi mạnh 💨)</h4>
                                <p class="text-[11px] text-slate-400">Uốn lưỡi giống `zh` nhưng đẩy luồng hơi bật mạnh ra</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-[#e07a5f]/15 text-[#e07a5f] uppercase tracking-wider">Bật hơi</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Mẹo:</strong> Đầu lưỡi uốn cong lên ngạc cứng, nén hơi rồi bật đầu lưỡi xuống đẩy luồng hơi mạnh ra ngoài.
                    </p>
                </div>
            </div>

            <!-- Tab Content 6: Đầu Lưỡi Thẳng (z, c, s) -->
            <div x-show="activeGroup === 'dental'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926]">
                    <img src="/images/pinyin/dental.png" alt="Sơ đồ khẩu hình Đầu Lưỡi Thẳng Dental (z, c, s)" class="w-44 h-44 object-contain bg-white rounded-xl p-2 shadow-xs border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-[#e07a5f]">Sơ đồ vị trí đầu lưỡi thẳng ép sát răng (Dental Articulation)</span>
                </div>

                <div class="p-3.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#e07a5f] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">z</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-xs sm:text-sm">Âm z (Đầu lưỡi thẳng - Không bật hơi)</h4>
                            <p class="text-[11px] text-slate-400">Đọc giống chữ "ch" / "tr" phảng phất nhẹ</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Đầu lưỡi duỗi thẳng áp vào mặt sau răng trên/dưới. Hai răng mím nhẹ sát nhau.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#fff7f4] dark:bg-[#241d1a] border border-[#fcdccf] dark:border-[#4a2e26] space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white font-bold text-base flex items-center justify-center shadow-xs shrink-0">c</span>
                            <div>
                                <h4 class="font-bold text-[#e07a5f] dark:text-[#f4978e] text-xs sm:text-sm">Âm c (Đầu lưỡi thẳng + Bật hơi mạnh 💨)</h4>
                                <p class="text-[11px] text-slate-400">Đầu lưỡi thẳng, ép sát răng và đẩy hơi bật mạnh</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-[#e07a5f]/15 text-[#e07a5f] uppercase tracking-wider">Bật hơi</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Giữ nguyên vị trí đầu lưỡi thẳng của âm `z`, nén hơi qua khe hở của 2 hàm răng rồi bật mạnh luồng hơi ra ngoài.
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>
</template>
