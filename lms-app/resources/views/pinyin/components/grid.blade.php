<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    /* Fullscreen overlay positioning - đặt trong CSS class để :style bìnding không ghi đè */
    .pinyin-fs-overlay {
        position: fixed !important;
        inset: 0 !important;
        z-index: 9990 !important;
        overflow: auto !important;
        padding: 12px !important;
    }
</style>
<div class="relative overflow-auto w-full no-scrollbar cursor-grab"
     style="max-height: 85vh;"
     x-data="{
        isDown: false, isDragging: false, startX: 0, scrollLeft: 0, startY: 0, scrollTop: 0,
        initDrag(e) { this.isDown=true; this.isDragging=false; this.$el.classList.add('!cursor-grabbing','select-none'); this.startX=e.pageX-this.$el.offsetLeft; this.scrollLeft=this.$el.scrollLeft; this.startY=e.pageY-this.$el.offsetTop; this.scrollTop=this.$el.scrollTop; },
        endDrag(e)   { this.isDown=false; this.$el.classList.remove('!cursor-grabbing','select-none'); setTimeout(()=>{this.isDragging=false;},50); },
        doDrag(e)    { if(!this.isDown) return; const wX=(e.pageX-this.$el.offsetLeft-this.startX)*1.5; const wY=(e.pageY-this.$el.offsetTop-this.startY)*1.5; if(Math.abs(wX)>5||Math.abs(wY)>5){this.isDragging=true;} if(this.isDragging){e.preventDefault();this.$el.scrollLeft=this.scrollLeft-wX;this.$el.scrollTop=this.scrollTop-wY;} },
        handleClick(e){ if(this.isDragging){e.stopPropagation();e.preventDefault();} }
     }"
     @mousedown="initDrag($event)"
     @mouseleave="endDrag($event)"
     @mouseup="endDrag($event)"
     @mousemove="doDrag($event)"
     @click.capture="handleClick($event)">
    @include('pinyin.components.grid_table')
</div>
<template x-teleport="body">
    <div x-show="isFullscreen"
         x-cloak
         style="display:none;"
         :style="darkMode ? 'background:#0e0c0b;' : 'background:#f8f6f6;'"
         x-data="{
            isDown: false, isDragging: false, startX: 0, scrollLeft: 0, startY: 0, scrollTop: 0,
            initDrag(e) { this.isDown=true; this.isDragging=false; this.$el.classList.add('!cursor-grabbing','select-none'); this.startX=e.pageX-this.$el.offsetLeft; this.scrollLeft=this.$el.scrollLeft; this.startY=e.pageY-this.$el.offsetTop; this.scrollTop=this.$el.scrollTop; },
            endDrag(e)   { this.isDown=false; this.$el.classList.remove('!cursor-grabbing','select-none'); setTimeout(()=>{this.isDragging=false;},50); },
            doDrag(e)    { if(!this.isDown) return; const wX=(e.pageX-this.$el.offsetLeft-this.startX)*1.5; const wY=(e.pageY-this.$el.offsetTop-this.startY)*1.5; if(Math.abs(wX)>5||Math.abs(wY)>5){this.isDragging=true;} if(this.isDragging){e.preventDefault();this.$el.scrollLeft=this.scrollLeft-wX;this.$el.scrollTop=this.scrollTop-wY;} },
            handleClick(e){ if(this.isDragging){e.stopPropagation();e.preventDefault();} }
         }"
         @mousedown="initDrag($event)"
         @mouseleave="endDrag($event)"
         @mouseup="endDrag($event)"
         @mousemove="doDrag($event)"
         @click.capture="handleClick($event)"
         class="pinyin-fs-overlay no-scrollbar cursor-grab">
        <div class="sticky top-0 left-0 right-0 z-40 mb-2 flex items-center justify-between px-3 py-2 rounded-xl border shadow-sm"
             :class="darkMode ? 'bg-slate-900/95 border-slate-700' : 'bg-white/95 border-slate-200'">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#e07a5f]"></span>
                <span class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-slate-800'">{{ __('Bảng Phiên Âm Pinyin — Chế độ toàn màn hình') }}</span>
                <span class="hidden sm:inline text-xs text-slate-400">• {{ __('Kéo chuột để cuộn • Bấm ô để nghe') }}</span>
            </div>
            <button type="button" @click="isFullscreen = false"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#e07a5f] hover:bg-[#c86349] text-white text-xs font-bold transition-colors cursor-pointer select-none">
                <i class="fa-solid fa-compress text-xs pointer-events-none"></i>
                <span>{{ __('Thu nhỏ (F / ESC)') }}</span>
            </button>
        </div>
        @include('pinyin.components.grid_table')
    </div>
</template>
<template x-teleport="body">
    <div x-show="currentPinyin"
         x-cloak
         style="display:none;"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div x-show="currentPinyin"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
        {{-- Panel --}}
        <div x-show="currentPinyin"
             @click.away="currentPinyin = null; selectedTone = null"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="lms-card max-w-md w-full p-6 border border-[#e8e2d9] dark:border-[#2d2926] relative z-10 space-y-4 shadow-2xl hover:transform-none">
            <button type="button"
                    @click="currentPinyin = null; selectedTone = null"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 bg-[#f8f6f3] dark:bg-[#201d1b] rounded-full w-8 h-8 flex items-center justify-center cursor-pointer transition-colors btn-tactile">
                <i class="fa-solid fa-xmark text-sm pointer-events-none"></i>
            </button>
            <div class="text-center pt-2">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] text-[10px] font-bold uppercase tracking-wider mb-2">
                    {{ __('Âm tiết cơ bản') }}
                </div>
                <h2 class="text-3xl font-bold text-[#e07a5f] dark:text-[#f4978e] tracking-wide zh-text" x-text="currentPinyin ? currentPinyin.full : ''"></h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">{{ __('Bấm vào từng thanh điệu để nghe phát âm và xem từ ví dụ') }}</p>
            </div>
            <div class="space-y-2" x-show="currentPinyin && currentPinyin.tones && currentPinyin.tones.length > 0">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-waveform-lines text-[#e07a5f]"></i>
                    {{ __('4 Thanh điệu (声调)') }}
                </span>
                <div class="grid grid-cols-2 gap-2.5">
                    <template x-for="tone in (currentPinyin ? currentPinyin.tones : [])" :key="tone.id">
                        <button type="button"
                            @click="selectedTone = tone; let u = tone.audio ? ('/storage/audio/pinyin/' + tone.audio) : null; if(u){window.playAudio(u);}else{window.playWordAudio(tone.display);}"
                            :class="selectedTone && selectedTone.id === tone.id
                                ? 'border-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] ring-2 ring-[#e07a5f]/20 font-bold'
                                : 'border-[#e8e2d9] dark:border-[#2d2926] bg-[#f8f6f3]/60 dark:bg-[#201d1b]/60 hover:bg-[#fff2ee] dark:hover:bg-[#2c221e] hover:border-[#e07a5f]/50 text-slate-700 dark:text-slate-300'"
                            class="p-3 rounded-xl border transition-all btn-tactile flex flex-col items-center justify-center gap-0.5 cursor-pointer select-none">
                            <span class="text-xl font-bold zh-text" x-text="window.toneToUnicode(tone.display)"></span>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ __('Thanh') }} <span x-text="tone.tone"></span></span>
                        </button>
                    </template>
                </div>
            </div>
            <div x-show="selectedTone && selectedTone.examples && selectedTone.examples.length > 0"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="pt-3 border-t border-[#e8e2d9] dark:border-[#2d2926] space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-book-bookmark text-[#e07a5f]"></i>
                        {{ __('Từ vựng minh họa') }}
                    </span>
                    <span class="text-[10px] font-bold text-[#e07a5f] bg-[#fff2ee] dark:bg-[#2c221e] px-2 py-0.5 rounded-md" x-text="selectedTone ? window.toneToUnicode(selectedTone.display) : ''"></span>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1 no-scrollbar">
                    <template x-for="ex in (selectedTone ? selectedTone.examples : [])" :key="ex.id">
                        <div class="p-2.5 rounded-xl bg-[#f8f6f3] dark:bg-[#201d1b] border border-[#e8e2d9] dark:border-[#2d2926] flex items-center justify-between hover:border-[#e07a5f]/40 transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#e07a5f] to-[#c86349] text-white flex items-center justify-center text-base font-bold zh-text shrink-0 shadow-xs" x-text="ex.hanzi"></div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-sm font-bold text-slate-800 dark:text-white zh-text" x-text="ex.pinyin"></span>
                                        <template x-if="ex.level">
                                            <span class="text-[9px] font-bold px-1.5 rounded bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] uppercase tracking-wider" x-text="ex.level"></span>
                                        </template>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5" x-text="ex.meaning"></p>
                                </div>
                            </div>
                            <button type="button"
                                    @click="window.playWordAudio(ex.hanzi || ex.pinyin)"
                                    class="w-8 h-8 rounded-full bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] text-[#e07a5f] hover:bg-[#e07a5f] hover:text-white flex items-center justify-center transition-all btn-tactile shadow-xs shrink-0 cursor-pointer"
                                    title="{{ __('Nghe phát âm') }}">
                                <i class="fa-solid fa-volume-high text-xs pointer-events-none"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
            <div x-show="currentPinyin && (!currentPinyin.tones || currentPinyin.tones.length === 0)" class="text-center py-6 text-slate-400 text-xs">
                {{ __('Chưa có dữ liệu thanh điệu cho âm này.') }}
            </div>
        </div>
    </div>
</template>
