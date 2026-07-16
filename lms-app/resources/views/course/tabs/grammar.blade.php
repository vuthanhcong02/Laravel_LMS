<!-- Tab 3: Grammar Content Panel -->
                        <div x-show="activeTab === 'ngu-phap'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                        <div x-show="activeTab === 'ngu-phap'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                            @if(isset($currentLesson) && $currentLesson->grammarList && count($currentLesson->grammarList) > 0)
                                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm flex flex-col">
                                    <!-- Section Title - Unified -->
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 text-left border-b border-slate-100 dark:border-slate-800/60 pb-5">
                                        <div>
                                            <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2.5">
                                                <div class="h-8 w-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                                </div>
                                                <span>Quy tắc ngữ pháp</span>
                                            </h3>
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 sm:ml-10">Nắm vững cấu trúc, ý nghĩa và cách sử dụng qua các ví dụ.</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-6 text-left">
                                        @foreach($currentLesson->grammarList as $grammar)
                                            <div class="bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800/80 p-5 space-y-4">
                                                <!-- Title -->
                                                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                                                    <h4 class="text-sm font-extrabold text-primary dark:text-primary-light">{{ $grammar->title }}</h4>
                                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ $grammar->type }}</span>
                                                </div>
                                                
                                                <!-- Formula banner -->
                                                @if($grammar->formula)
                                                <div class="p-4 rounded-xl bg-gradient-to-r from-primary/10 via-primary/5 to-transparent border border-primary/20 text-slate-800 dark:text-white text-base font-black tracking-wide flex items-center text-left">
                                                    <div>
                                                        <span class="text-[8px] font-bold text-primary uppercase tracking-widest block mb-0.5">Cấu trúc công thức</span>
                                                        <span>{{ $grammar->formula }}</span>
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Detail explanation -->
                                                <div class="space-y-1 bg-slate-50 dark:bg-slate-800/30 p-3.5 rounded-xl border border-slate-100/50 dark:border-slate-800/60">
                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Giải thích ý nghĩa</span>
                                                    <p class="text-xs text-slate-605 dark:text-slate-305 leading-relaxed font-semibold">{{ $grammar->explanation }}</p>
                                                </div>

                                                <!-- Examples box mapping -->
                                                <div class="space-y-3 pt-1">
                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Các câu ví dụ mẫu</span>
                                                    <div class="flex flex-col gap-4">
                                                        @if(is_array($grammar->examples) || is_object($grammar->examples))
                                                            @foreach($grammar->examples as $ex)
                                                                <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-85 bg-slate-50/50 dark:bg-slate-800/40 relative group/ex">
                                                                    <p class="text-xs text-primary font-bold italic mb-0.5">{{ is_array($ex) ? ($ex['pinyin'] ?? '') : ($ex->pinyin ?? '') }}</p>
                                                                    <p class="text-xl font-black text-slate-800 dark:text-white leading-normal">{{ is_array($ex) ? ($ex['character'] ?? '') : ($ex->character ?? '') }}</p>
                                                                    <p class="text-xs text-slate-455 mt-2 pt-2 border-t border-slate-100 dark:border-slate-800/40 font-semibold">Nghĩa Việt: {{ is_array($ex) ? ($ex['translation'] ?? '') : ($ex->translation ?? '') }}</p>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                            <!-- Empty State Grammar -->
                                <div class="flex flex-col items-center justify-center py-20 px-4 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl text-center mt-2 max-w-2xl mx-auto">
                                    <div class="w-20 h-20 bg-white dark:bg-slate-800 shadow-sm rounded-full flex items-center justify-center mb-5 border border-slate-100 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-500">menu_book</span>
                                    </div>
                                    <h4 class="text-base font-black text-slate-800 dark:text-white mb-2">Không có ngữ pháp</h4>
                                    <p class="text-xs text-slate-500 max-w-md font-medium leading-relaxed">Bài học này không có trọng điểm ngữ pháp mới.</p>
                                </div>
                            @endif
                        </div>

                        