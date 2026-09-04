<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<div x-data="{
        isDown: false, 
        isDragging: false,
        startX: 0, 
        scrollLeft: 0, 
        startY: 0, 
        scrollTop: 0,
        initDrag(e) {
            this.isDown = true;
            this.isDragging = false;
            this.$el.classList.add('!cursor-grabbing', 'select-none');
            this.startX = e.pageX - this.$el.offsetLeft;
            this.scrollLeft = this.$el.scrollLeft;
            this.startY = e.pageY - this.$el.offsetTop;
            this.scrollTop = this.$el.scrollTop;
        },
        endDrag(e) {
            this.isDown = false;
            this.$el.classList.remove('!cursor-grabbing', 'select-none');
            // Reset dragging state slightly after so click events can be blocked
            setTimeout(() => { this.isDragging = false; }, 50);
        },
        doDrag(e) {
            if(!this.isDown) return;
            
            const x = e.pageX - this.$el.offsetLeft;
            const y = e.pageY - this.$el.offsetTop;
            const walkX = (x - this.startX) * 1.5;
            const walkY = (y - this.startY) * 1.5;
            
            if (Math.abs(walkX) > 5 || Math.abs(walkY) > 5) {
                this.isDragging = true;
            }
            
            if (this.isDragging) {
                e.preventDefault();
                this.$el.scrollLeft = this.scrollLeft - walkX;
                this.$el.scrollTop = this.scrollTop - walkY;
            }
        },
        handleClick(e) {
            if (this.isDragging) {
                e.stopPropagation();
                e.preventDefault();
            }
        }
     }"
     @mousedown="initDrag($event)"
     @mouseleave="endDrag($event)"
     @mouseup="endDrag($event)"
     @mousemove="doDrag($event)"
     @click.capture="handleClick($event)"
     :class="isFullscreen ? '!fixed !inset-0 !z-[99999] !w-screen !h-screen overflow-auto bg-slate-50 dark:bg-[#0b1120] rounded-none m-0 p-2 sm:p-4 no-scrollbar cursor-grab' : 'relative overflow-auto w-full no-scrollbar cursor-grab'"
     style="max-height: 85vh;"
     x-ref="gridContainer">
    <table class="w-full text-center border-collapse bg-white">
        <!-- Table Header (Finals) -->
        <thead>
            <tr>
                <th rowspan="2" class="sticky left-0 top-0 z-30 min-w-[28px] w-[28px] p-0.5 bg-blue-300 border border-slate-300 shadow-[2px_2px_5px_-2px_rgba(0,0,0,0.05)] text-blue-900">
                    <button @click="
                            isFullscreen = !isFullscreen; 
                            if (isFullscreen && $refs.gridContainer.requestFullscreen) { $refs.gridContainer.requestFullscreen(); }
                            else if (!isFullscreen && document.fullscreenElement) { document.exitFullscreen(); }
                        " 
                        class="p-0.5 rounded hover:bg-blue-400/50 text-blue-900 transition-colors w-full flex justify-center" title="Phóng to / Thu nhỏ (Phím F)">
                        <span class="material-symbols-outlined text-[14px]" x-text="isFullscreen ? 'fullscreen_exit' : 'fullscreen'"></span>
                    </button>
                </th>
                <th colspan="5" class="p-0.5 bg-blue-300 border border-slate-300 text-blue-900 font-bold text-center text-xs">a</th>
                <th colspan="3" class="p-0.5 bg-blue-300 border border-slate-300 text-blue-900 font-bold text-center text-xs">o</th>
                <th colspan="5" class="p-0.5 bg-blue-300 border border-slate-300 text-blue-900 font-bold text-center text-xs">e</th>
                <th colspan="10" class="p-0.5 bg-blue-300 border border-slate-300 text-blue-900 font-bold text-center text-xs">i</th>
                <th colspan="9" class="p-0.5 bg-blue-300 border border-slate-300 text-blue-900 font-bold text-center text-xs">u</th>
                <th colspan="4" class="p-0.5 bg-blue-300 border border-slate-300 text-blue-900 font-bold text-center text-xs">ü</th>
            </tr>
            <tr>
                @foreach($finals as $final)
                <th class="min-w-[26px] p-0.5 bg-orange-200 border border-slate-300 text-orange-900 font-bold text-center text-[11px]">
                    {{ $final->name }}
                </th>
                @endforeach
            </tr>
        </thead>
        
        <!-- Table Body (Initials x Finals) -->
        <tbody>
            @foreach($initials as $initial)
            <tr class="group hover:bg-indigo-50/50 transition-colors">
                <!-- Initial Column -->
                <td class="sticky left-0 z-10 w-[28px] text-center p-0.5 bg-blue-300 border border-slate-300 font-bold text-[11px] text-blue-900 group-hover:bg-blue-400 transition-colors">
                    {{ $initial->name === '' ? '-' : $initial->name }}
                </td>
                
                <!-- Pinyin Cells -->
                @foreach($finals as $final)
                    @php
                        $key = $initial->id . '_' . $final->id;
                        $pinyin = $pinyins->get($key);
                    @endphp
                    
                    <td class="p-0 border border-slate-300 {{ $pinyin ? 'bg-indigo-100 hover:bg-indigo-200' : 'bg-white' }}">
                        @if($pinyin)
                        <button 
                            @click='currentPinyin = @json($pinyin); selectedTone = (currentPinyin.tones && currentPinyin.tones.length > 0) ? currentPinyin.tones[0] : null'
                            class="w-full h-6 px-0.5 flex items-center justify-center font-medium text-[11px] text-slate-800 transition-colors active:scale-95 cursor-pointer">
                            {{ $pinyin->full }}
                        </button>
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Popup Modal for Tone Details & Vocabulary Examples -->
<div x-show="currentPinyin" 
     style="display: none;" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    
    <!-- Backdrop -->
    <div x-show="currentPinyin"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <!-- Modal Panel -->
    <div x-show="currentPinyin"
         @click.away="currentPinyin = null; selectedTone = null"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-md w-full p-6 border border-slate-100 dark:border-slate-700 relative z-10">
        
        <button @click="currentPinyin = null; selectedTone = null" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-700 rounded-full w-8 h-8 flex items-center justify-center cursor-pointer transition-colors">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
        
        <div class="text-center mb-5">
            <h2 class="text-4xl font-black text-indigo-600 dark:text-indigo-400 mb-1" x-text="currentPinyin ? currentPinyin.full : ''"></h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs">Chọn thanh điệu để nghe phát âm & xem từ vựng</p>
        </div>
        
        <!-- Tones Selection Grid -->
        <div class="grid grid-cols-2 gap-2.5" x-show="currentPinyin && currentPinyin.tones && currentPinyin.tones.length > 0">
            <template x-for="tone in (currentPinyin ? currentPinyin.tones : [])" :key="tone.id">
                <button 
                    @click="selectedTone = tone; $refs.audioPlayer.src = '/storage/audio/pinyin/' + tone.audio; $refs.audioPlayer.play()"
                    :class="selectedTone && selectedTone.id === tone.id ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/40 ring-2 ring-indigo-500/20' : 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/50 hover:bg-indigo-50/50 dark:hover:bg-indigo-500/20 hover:border-indigo-300 dark:hover:border-indigo-500'"
                    class="p-3 rounded-2xl border-2 transition-all active:scale-95 flex flex-col items-center justify-center gap-1 group cursor-pointer">
                    <span class="text-2xl font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors" x-text="tone.display"></span>
                    <span class="text-[10px] uppercase font-bold text-indigo-500/80 tracking-wider">Thanh <span x-text="tone.tone"></span></span>
                </button>
            </template>
        </div>

        <!-- Vocabulary Examples Section -->
        <div x-show="selectedTone && selectedTone.examples && selectedTone.examples.length > 0" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/80">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[15px] text-emerald-500">menu_book</span>
                    Từ vựng minh họa
                </span>
                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/40" x-text="selectedTone ? selectedTone.display : ''"></span>
            </div>
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1 no-scrollbar">
                <template x-for="ex in (selectedTone ? selectedTone.examples : [])" :key="ex.id">
                    <div class="p-3 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/40 flex items-center justify-between transition-all hover:border-emerald-300 dark:hover:border-emerald-700 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="min-w-[2.5rem] h-10 px-2 rounded-xl bg-indigo-600 dark:bg-indigo-500 text-white flex items-center justify-center text-base font-black shrink-0 shadow-md shadow-indigo-500/20 whitespace-nowrap" x-text="ex.hanzi"></div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-100" x-text="ex.pinyin"></span>
                                    <template x-if="ex.level">
                                        <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 uppercase tracking-wider" x-text="ex.level"></span>
                                    </template>
                                </div>
                                <p class="text-xs font-medium text-slate-600 dark:text-slate-300 mt-0.5" x-text="ex.meaning"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        
        <div x-show="currentPinyin && (!currentPinyin.tones || currentPinyin.tones.length === 0)" class="text-center py-6 text-slate-400 text-sm">
            Chưa có dữ liệu thanh điệu cho âm này.
        </div>
    </div>
</div>
