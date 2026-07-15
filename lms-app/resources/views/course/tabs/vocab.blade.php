<!-- Tab 1: Vocabulary Content Panel -->
<div x-show="activeTab === 'vocab'" x-transition:enter="transition ease-out duration-200" x-data="vocabStudyComponent({{ Js::from($currentLesson->vocabList) }})">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm flex flex-col">
        <!-- Header area -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 text-left border-b border-slate-100 dark:border-slate-800/60 pb-5">
            <div>
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]" x-text="viewMode === 'list' ? 'list_alt' : 'style'">list_alt</span>
                    </div>
                    <span x-text="viewMode === 'list' ? 'Từ vựng trọng tâm' : (viewMode === 'flashcard' ? 'Flashcard' : (viewMode === 'match' ? 'Nối từ' : (viewMode === 'quiz' ? 'Trắc nghiệm' : 'Luyện gõ')))">Từ vựng trọng tâm</span>
                </h3>
                <p x-show="viewMode === 'list'" class="text-sm text-slate-500 dark:text-slate-400 mt-1 sm:ml-10">Ghi nhớ và luyện phát âm các từ vựng mới của bài học.</p>
            </div>

            <!-- Header Action Button -->
            <button 
                x-show="viewMode === 'list'"
                @click="viewMode = 'flashcard'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-white font-bold text-xs rounded-xl shadow-sm shadow-primary/20 transition-all duration-200 cursor-pointer active:scale-95 shrink-0"
            >
                <span class="material-symbols-outlined text-[16px]">style</span>
                <span>Học Flashcard</span>
            </button>
            <button 
                x-show="viewMode !== 'list'"
                @click="viewMode = 'list'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-primary text-primary hover:bg-primary/5 font-bold text-xs rounded-xl transition-all duration-200 cursor-pointer active:scale-95 shrink-0"
            >
                <span>Danh sách</span>
            </button>
        </div>

        <!-- LIST VIEW -->
        <div x-show="viewMode === 'list'" x-transition.opacity.duration.300ms class="rounded-xl border border-slate-200/80 dark:border-slate-800/80 overflow-hidden">
            <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-slate-50 dark:bg-slate-800/90 backdrop-blur-sm z-10">
                        <tr class="border-b border-slate-200 dark:border-slate-700/80">
                            <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-12 text-center">#</th>
                            <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-32">Từ vựng</th>
                            <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-32">Pinyin</th>
                            <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24">Từ loại</th>
                            <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ý nghĩa</th>
                            <th class="py-3 px-4 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-16 text-center">Nghe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @foreach($currentLesson->vocabList as $idx => $vocab)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors group">
                                <td class="py-3.5 px-4 text-xs font-black text-slate-300 dark:text-slate-600 text-center">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="text-xl font-black text-slate-800 dark:text-white">{{ $vocab->word }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-[13px] text-slate-500 dark:text-slate-400 font-bold italic">[{{ $vocab->pinyin }}]</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $vocab->type }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-sm text-slate-600 dark:text-slate-300 font-medium line-clamp-2" title="{{ $vocab->meaning }}">{{ $vocab->meaning }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button 
                                        @click="playAudio('{{ $vocab->audio_url ?: 'https://dict.youdao.com/dictvoice?audio=' . urlencode($vocab->word) . '&type=1' }}')"
                                        class="h-8 w-8 rounded-full bg-primary/10 border border-primary/15 hover:bg-primary hover:text-white transition-all duration-200 inline-flex items-center justify-center text-primary focus:outline-none opacity-60 group-hover:opacity-100 mx-auto"
                                        title="Nghe phát âm"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">volume_up</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FLASHCARD COMPONENT -->
        <template x-if="viewMode === 'flashcard'">
            @include('course.components.games.flashcard')
        </template>
        
        <!-- MATCH COMPONENT -->
        <template x-if="viewMode === 'match'">
            @include('course.components.games.match-game')
        </template>

        <!-- QUIZ COMPONENT -->
        <template x-if="viewMode === 'quiz'">
            @include('course.components.games.quiz')
        </template>

        <!-- TYPING COMPONENT -->
        <template x-if="viewMode === 'typing'">
            @include('course.components.games.typing')
        </template>

    </div>
</div>

