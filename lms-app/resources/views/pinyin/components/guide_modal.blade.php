<!-- Pronunciation & Mouth Shape Guide Modal -->
<div x-show="showGuideModal" 
     style="display: none;" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    
    <!-- Backdrop -->
    <div x-show="showGuideModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <!-- Modal Panel -->
    <div x-show="showGuideModal"
         @click.away="showGuideModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-2xl w-full p-6 border border-slate-100 dark:border-slate-700 relative max-h-[85vh] flex flex-col z-10">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100 dark:border-slate-700/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200/60 dark:border-indigo-800/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <span class="material-symbols-outlined text-[22px]">record_voice_over</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Hướng Dẫn Khẩu Hình & Mẹo Phát Âm Chuẩn</h3>
                    <p class="text-xs font-medium text-slate-400 dark:text-slate-500">Sơ đồ vị trí bộ máy phát âm chuẩn IPA (Wikimedia Commons)</p>
                </div>
            </div>
            <button @click="showGuideModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-700 rounded-full w-8 h-8 flex items-center justify-center cursor-pointer transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <!-- Body with Custom Tabs & Scrollable Content -->
        <div class="overflow-y-auto pr-1 flex-1 space-y-6 no-scrollbar" x-data="{ activeGroup: 'labial' }">
            
            <!-- Group Tabs Navigation -->
            <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar border-b border-slate-100 dark:border-slate-700/60">
                <button @click="activeGroup = 'labial'" 
                        :class="activeGroup === 'labial' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-200 dark:hover:bg-slate-600'"
                        class="px-3.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    Âm Môi (b, p, m, f)
                </button>
                <button @click="activeGroup = 'alveolar'" 
                        :class="activeGroup === 'alveolar' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-200 dark:hover:bg-slate-600'"
                        class="px-3.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    Đầu Lưỡi (d, t, n, l)
                </button>
                <button @click="activeGroup = 'velar'" 
                        :class="activeGroup === 'velar' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-200 dark:hover:bg-slate-600'"
                        class="px-3.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    Cuống Lưỡi (g, k, h)
                </button>
                <button @click="activeGroup = 'palatal'" 
                        :class="activeGroup === 'palatal' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-200 dark:hover:bg-slate-600'"
                        class="px-3.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    Mặt Lưỡi (j, q, x)
                </button>
                <button @click="activeGroup = 'retroflex'" 
                        :class="activeGroup === 'retroflex' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-200 dark:hover:bg-slate-600'"
                        class="px-3.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    Uốn Lưỡi (zh, ch, sh, r)
                </button>
                <button @click="activeGroup = 'dental'" 
                        :class="activeGroup === 'dental' ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-200 dark:hover:bg-slate-600'"
                        class="px-3.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    Đầu Lưỡi Thẳng (z, c, s)
                </button>
            </div>

            <!-- Tab Content 1: Âm Môi -->
            <div x-show="activeGroup === 'labial'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40">
                    <img src="/images/pinyin/labial.png" alt="Sơ đồ khẩu hình Âm Môi Bilabial (b, p, m)" class="w-48 h-48 object-contain bg-white rounded-xl p-2 shadow-sm border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400">Sơ đồ vị trí khép môi IPA (Bilabial Articulation)</span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-md">b</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Âm b (Không bật hơi)</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Đọc gần giống chữ "b" trong tiếng Việt</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Mím nhẹ hai môi, chặn luồng hơi lại rồi mở nhẹ hai môi cho hơi thoát ra tự nhiên. Không đẩy hơi mạnh.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200/60 dark:border-amber-900/40 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl bg-amber-600 text-white font-black text-lg flex items-center justify-center shadow-md">p</span>
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Âm p (ÂM BẬT HƠI MẠNH 💨)</h4>
                                <p class="text-xs text-amber-700 dark:text-amber-400 font-semibold">Đọc gần giống "p" nhưng phải bật luồng hơi mạnh ra ngoài</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-amber-200 dark:bg-amber-900 text-amber-800 dark:text-amber-200 uppercase">Bật hơi</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Mẹo kiểm tra:</strong> Đặt tờ giấy trước miệng khi phát âm `p`, tờ giấy phải <strong>bay mạnh</strong> ra phía trước. Mím chặt hai môi, nén hơi rồi dồn lực bật mạnh ra.
                    </p>
                </div>
            </div>

            <!-- Tab Content 2: Đầu Lưỡi -->
            <div x-show="activeGroup === 'alveolar'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40">
                    <img src="/images/pinyin/alveolar.png" alt="Sơ đồ khẩu hình Đầu Lưỡi Alveolar (d, t, n, l)" class="w-48 h-48 object-contain bg-white rounded-xl p-2 shadow-sm border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400">Sơ đồ vị trí chạm chân răng trên IPA (Alveolar Articulation)</span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-md">d</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Âm d (Không bật hơi)</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Đọc giống chữ "t" trong tiếng Việt (vd: tôi, đi)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Đầu lưỡi chạm vào chân răng trên, chặn hơi lại rồi bật nhẹ đầu lưỡi xuống. Không đẩy hơi mạnh.
                    </p>
                </div>
            </div>

            <!-- Tab Content 3: Cuống Lưỡi -->
            <div x-show="activeGroup === 'velar'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40">
                    <img src="/images/pinyin/velar.png" alt="Sơ đồ khẩu hình Cuống Lưỡi Velar (g, k, h)" class="w-48 h-48 object-contain bg-white rounded-xl p-2 shadow-sm border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400">Sơ đồ vị trí áp cuống lưỡi IPA (Velar Articulation)</span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-md">g</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Âm g (Không bật hơi)</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Đọc giống chữ "c" / "k" trong tiếng Việt (vd: con, gà)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Cuống lưỡi thụt về sau áp sát ngạc mềm (vòm miệng mềm), luồng hơi bị chặn lại rồi hạ cuống lưỡi xuống nhẹ nhàng.
                    </p>
                </div>
            </div>

            <!-- Tab Content 4: Mặt Lưỡi -->
            <div x-show="activeGroup === 'palatal'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40">
                    <img src="/images/pinyin/palatal.png" alt="Sơ đồ khẩu hình Mặt Lưỡi Palatal (j, q, x)" class="w-48 h-48 object-contain bg-white rounded-xl p-2 shadow-sm border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400">Sơ đồ vị trí nâng mặt lưỡi IPA (Palatal Articulation)</span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-md">j</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Âm j (Không bật hơi)</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Đọc giống chữ "ch" nhẹ trong tiếng Việt (vd: chi, chị)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Mặt trước lưỡi nâng cao áp sát vòm miệng cứng, luồng hơi ngắt lại rồi mở nhẹ ra. Hai khóe miệng hơi kéo sang 2 bên như đang mỉm cười.
                    </p>
                </div>
            </div>

            <!-- Tab Content 5: Uốn Lưỡi -->
            <div x-show="activeGroup === 'retroflex'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40">
                    <img src="/images/pinyin/retroflex.png" alt="Sơ đồ uốn lưỡi Retroflex (zh, ch, sh, r)" class="w-48 h-48 object-contain bg-white rounded-xl p-2 shadow-sm border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400">Sơ đồ vị trí uốn lưỡi IPA (Retroflex Articulation)</span>
                </div>

                <div class="p-4 rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-200/60 dark:border-indigo-900/40 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-12 h-9 rounded-xl bg-indigo-600 text-white font-black text-base flex items-center justify-center shadow-md">zh</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Âm zh (Uốn lưỡi - Không bật hơi)</h4>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300 font-semibold">Đọc giống chữ "tr" trong tiếng Việt (vd: trâu, tre)</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> <strong>Uốn cong đầu lưỡi lên trên</strong> áp sát vòm miệng cứng (ngạc cứng), thả nhẹ đầu lưỡi cho hơi thoát ra. Không đẩy hơi mạnh.
                    </p>
                </div>
            </div>

            <!-- Tab Content 6: Đầu Lưỡi Thẳng -->
            <div x-show="activeGroup === 'dental'" class="space-y-4">
                <div class="flex flex-col items-center p-3 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40">
                    <img src="/images/pinyin/dental.png" alt="Sơ đồ khẩu hình Đầu Lưỡi Thẳng Dental (z, c, s)" class="w-48 h-48 object-contain bg-white rounded-xl p-2 shadow-sm border border-slate-200 dark:border-slate-700 mb-2" />
                    <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400">Sơ đồ vị trí đầu lưỡi thẳng ép sát răng (Dental Articulation)</span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-md">z</span>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Âm z (Đầu lưỡi thẳng - Không bật hơi)</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Đọc giống chữ "ch" / "tr" phảng phất nhẹ</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong>Khẩu hình:</strong> Đầu lưỡi duỗi thẳng áp vào mặt sau răng trên/dưới. Hai răng mím nhẹ sát nhau.
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>
