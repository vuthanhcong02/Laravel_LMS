<script>
    // Hàm chuẩn hóa mảng segment cho quiz reorder
    window.reorderSegments = function(segments) {
        if (!segments) return [];
        if (typeof segments === 'string') {
            try { segments = JSON.parse(segments); } catch(e) { return []; }
        }
        if (typeof segments === 'object' && !Array.isArray(segments)) {
            segments = Object.values(segments);
        }
        if (!Array.isArray(segments)) return [];
        
        return segments.map(function(item, idx) {
            var text = (typeof item === 'string') ? item : (item && item.text ? item.text : '');
            var html = (typeof item === 'object' && item !== null) ? (item.html || '') : '';
            var pinyin = (typeof item === 'object' && item !== null) ? (item.pinyin || '') : '';
            return { id: idx, text: text, html: html, pinyin: pinyin };
        });
    };

    // Hàm khởi tạo Alpine component cho quiz reorder
    // Alpine v3 hỗ trợ gọi x-data="reorderQuiz(quiz)" trực tiếp
    window.reorderQuiz = function(quiz) {
        return {
            available: [],
            selected: [],

            init() {
                this.available = window.reorderSegments(quiz.question_segments);
            },

            selectItem(index) {
                if (quiz.answered) return;
                var item = this.available[index];
                this.available.splice(index, 1);
                this.selected.push(item);
                this.updateAnswer();
            },

            unselectItem(index) {
                if (quiz.answered) return;
                var item = this.selected[index];
                this.selected.splice(index, 1);
                this.available.push(item);
                this.available.sort(function(a, b) { return a.id - b.id; });
                this.updateAnswer();
            },

            updateAnswer() {
                quiz.userAnswer = this.selected.map(function(i) { return i.text; }).join('');
                quiz.selected = quiz.userAnswer.length > 0 ? 1 : null;
            }
        };
    };
</script>
                        <div x-show="activeTab === 'luyen-tap'" x-transition:enter="transition ease-out duration-200" style="display: none;" x-init="initPracticeData()">
                            <!-- Empty State Practice -->
                            <template x-if="(!currentLesson?.practices || currentLesson?.practices.length === 0)">
                                <x-lms.empty-state 
                                    icon="fa-solid fa-pen-to-square"
                                    :title="__('Chưa có bài tập thực hành')"
                                    :description="__('Nội dung thực hành cho bài học này đang được biên soạn và sẽ sớm ra mắt. Vui lòng quay lại sau.')"
                                />
                            </template>

                            <template x-if="currentLesson?.practices && currentLesson?.practices.length > 0">
                                <div class="space-y-6 text-left">
                                    
                                    <!-- 1. Full-Width Sticky Control Bar (Sub-Tabs on Left + Audio Player on Right) -->
                                    <div class="bg-white dark:bg-[#181615] rounded-2xl border border-slate-200/80 dark:border-[#2d2926] p-3 sm:p-4 shadow-2xs sticky top-0 z-30 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4 text-left before:content-[''] before:absolute before:-top-12 before:-left-1 before:-right-1 before:h-12 before:bg-[#f8f6f3] dark:before:bg-[#0e0c0b]">
                                        <!-- Left: Sub-Tabs & Pinyin Toggle -->
                                        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                                            <!-- Sub-Tabs -->
                                            <div class="flex gap-1 p-1 bg-slate-100 dark:bg-[#23201e] rounded-xl shrink-0">
                                                <button 
                                                    type="button"
                                                    class="px-3.5 py-1.5 rounded-lg font-semibold text-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer"
                                                    :class="practiceTab === 'listening' ? 'bg-white dark:bg-[#2d2926] text-primary shadow-xs' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'listening'; practiceSectionIdx = 0"
                                                >
                                                    <span class="material-symbols-outlined text-[16px]">headphones</span> {{ __('Phần Nghe') }}
                                                </button>
                                                <button 
                                                    type="button"
                                                    class="px-3.5 py-1.5 rounded-lg font-semibold text-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer"
                                                    :class="practiceTab === 'reading' ? 'bg-white dark:bg-[#2d2926] text-primary shadow-xs' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'reading'; practiceSectionIdx = 0"
                                                >
                                                    <span class="material-symbols-outlined text-[16px]">menu_book</span> {{ __('Phần Đọc') }}
                                                </button>
                                                <button 
                                                    type="button"
                                                    class="px-3.5 py-1.5 rounded-lg font-semibold text-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer"
                                                    :class="practiceTab === 'writing' ? 'bg-white dark:bg-[#2d2926] text-primary shadow-xs' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'writing'; practiceSectionIdx = 0"
                                                    x-show="currentLesson?.practices?.find(p => p.type === 'writing')"
                                                >
                                                    <span class="material-symbols-outlined text-[16px]">edit_document</span> {{ __('Phần Viết') }}
                                                </button>
                                            </div>
                                            
                                            <!-- Pinyin Toggle (HSK 1, 2, 3 only) -->
                                            <template x-if="currentLevelObj && ['hsk-1', 'hsk-2', 'hsk-3'].includes(currentLevelObj.slug)">
                                                <div class="flex items-center gap-1.5 p-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl shrink-0">
                                                    <button 
                                                        type="button"
                                                        @click="shouldShowPinyin = !shouldShowPinyin"
                                                        :class="shouldShowPinyin ? 'bg-white dark:bg-[#181615] text-[#e07a5f] font-bold shadow-xs' : 'text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-700 dark:hover:text-slate-300'"
                                                        class="px-3 py-1.5 rounded-lg text-xs transition-all btn-tactile flex items-center gap-1.5 shrink-0 cursor-pointer"
                                                        title="{{ __('Bật/tắt hiển thị phiên âm Pinyin') }}"
                                                    >
                                                        <i class="fa-solid fa-eye" x-show="shouldShowPinyin"></i>
                                                        <i class="fa-solid fa-eye-slash opacity-50" x-show="!shouldShowPinyin"></i>
                                                        <span>Pinyin</span>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Right: Sticky Audio Player (when on listening tab) -->
                                        <template x-if="practiceTab === 'listening' && currentLesson?.practices?.find(p => p.type === 'listening')?.audio_path">
                                            <div class="flex items-center gap-2 flex-1 max-w-md bg-slate-50 dark:bg-[#23201e] border border-slate-200/80 dark:border-[#2d2926] rounded-xl px-3 py-1.5 shadow-2xs">
                                                <div class="w-6 h-6 rounded-md bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[16px]">headphones</span>
                                                </div>
                                                <audio controls class="w-full h-8 rounded-lg" :src="'/storage/hsk_media/' + currentLesson?.practices?.find(p => p.type === 'listening')?.audio_path"></audio>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- 2. Main Content Grid (Left Content + Right Sticky Navigation) -->
                                    <div class="flex flex-col md:flex-row gap-5 lg:gap-6 items-start text-left">
                                        <!-- Practice Main Content (Left column) -->
                                        <div class="flex-1 min-w-0 w-full">
                                            <div class="bg-white dark:bg-[#181615] rounded-2xl border border-slate-200/80 dark:border-[#2d2926] p-5 sm:p-6 shadow-2xs flex flex-col relative">

                                            <!-- Listening Tab Content -->
                                        <div x-show="practiceTab === 'listening'" x-transition class="space-y-6 pb-10">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'listening')">
                                                <div>

                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'listening')?.sections || [])" :key="sIdx">
                                                        <div class="space-y-4 scroll-mt-[100px]" :id="'practice-' + 'listening' + '-section-' + sIdx">
                                                            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800/60 border-l-4 border-l-primary">
                                                                <template x-if="shouldShowPinyin && sect.section_han_html">
                                                                    <h4 class="text-[13px] sm:text-sm font-bold text-slate-800 dark:text-white font-chinese tracking-wide flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom" x-html="sect.section_han_html" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></h4>
                                                                </template>
                                                                <template x-if="!shouldShowPinyin || !sect.section_han_html">
                                                                    <h4 class="text-[13px] sm:text-sm font-bold text-slate-800 dark:text-white font-chinese tracking-wide" x-text="sect.section_han" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></h4>
                                                                </template>
                                                                <template x-if="sect.section_vi">
                                                                    <div class="mt-2 text-xs">
                                                                        <template x-if="!sect.parsed_vi?.hasExample">
                                                                            <p class="text-primary dark:text-primary-light italic font-medium" x-text="sect.section_vi" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></p>
                                                                        </template>
                                                                        <template x-if="sect.parsed_vi?.hasExample">
                                                                            <div class="space-y-2 mt-1">
                                                                                <p class="text-primary dark:text-primary-light font-bold" x-text="sect.parsed_vi.mainText"></p>
                                                                                <div class="p-3.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 leading-relaxed font-normal shadow-2xs" :class="shouldShowPinyin ? 'flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom' : ''" x-html="shouldShowPinyin ? sect.parsed_vi.exampleHtml : sect.parsed_vi.exampleHtmlRaw"></div>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <template x-if="sect.audio_path">
                                                               <div class="sticky top-0 z-20 my-3 py-1 bg-[#f8f6f3]/95 dark:bg-[#0e0c0b]/95 backdrop-blur-md">
                                                                   <div class="bg-white dark:bg-[#181615] border border-slate-200/80 dark:border-[#2d2926] rounded-2xl p-3 sm:p-3.5 flex flex-col gap-2 shadow-sm">
                                                                        <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-primary dark:text-primary-light">
                                                                            <span class="material-symbols-outlined text-[20px]">volume_up</span>
                                                                            <span x-text="'Nghe đoạn hội thoại phần ' + (sIdx + 1) + ':'"></span>
                                                                        </div>
                                                                       <audio controls class="w-full h-10 rounded-xl" :src="'/storage/hsk_media/' + sect.audio_path"></audio>
                                                                   </div>
                                                               </div>
                                                            </template>
                                                            <template x-if="sect.image_path">
                                                                <div class="my-4 text-center border border-slate-200/80 dark:border-[#2d2926] rounded-2xl overflow-hidden bg-white dark:bg-[#181615] p-3 flex justify-center shadow-2xs">
                                                                    <img :src="'/storage/hsk_media/' + sect.image_path" class="max-h-96 object-contain rounded-xl">
                                                                </div>
                                                            </template>

                                                    <div class="flex flex-col gap-4">
                                                        <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                            <div class="bg-white dark:bg-[#181615] border border-slate-200/80 dark:border-[#2d2926] p-5 rounded-2xl space-y-4 text-left shadow-2xs">
                                                                      
                                                                      <template x-if="quiz.image_path">
                                                                          <div class="mb-4 text-center border border-slate-200/80 dark:border-[#2d2926] rounded-xl overflow-hidden bg-slate-50 dark:bg-[#23201e] p-2 flex justify-center">
                                                                              <img :src="'/storage/hsk_media/' + quiz.image_path" class="max-h-64 object-contain rounded-lg">
                                                                          </div>
                                                                      </template>

                                                                      <template x-if="quiz.ques_type === 'fill_blank_dropdrag'">
                                                                          <div class="space-y-6">
                                                                              <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-300 font-chinese text-[16px] leading-[2.5]">
                                                                                  <template x-for="(segment, idx) in quiz.parsed_context" :key="idx">
                                                                                      <span class="inline">
                                                                                          <span x-html="(shouldShowPinyin && quiz.parsed_context_html && quiz.parsed_context_html[idx]) ? quiz.parsed_context_html[idx] : segment"></span>
                                                                                          <template x-if="idx < (quiz.sub_questions ? quiz.sub_questions.length : 0)">
                                                                                              <span 
                                                                                                  class="inline-flex items-center justify-center min-w-[80px] px-3 h-8 mx-1 border-b-2 font-bold text-sm align-middle cursor-pointer transition-all relative overflow-visible"
                                                                                                  :class="quiz.sub_questions[idx].selected_option ? (quiz.sub_questions[idx].answered ? (quiz.sub_questions[idx].selected_option == quiz.sub_questions[idx].correct_answer ? 'border-emerald-500 text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20' : 'border-red-500 text-red-600 bg-red-50 dark:bg-red-900/20') : 'border-primary text-primary bg-primary/5 hover:bg-primary/10') : 'border-slate-400 border-dashed text-slate-400 bg-slate-50 dark:bg-slate-800'"
                                                                                                  @dragover.prevent=""
                                                                                                  @drop="onDrop($event, quiz, idx)"
                                                                                              >
                                                                                                  <span x-show="!quiz.sub_questions[idx].selected_option" x-text="'(' + (quiz.sub_questions[idx].ques_id || (idx + 24)) + ') Kéo vào đây'" class="opacity-50 text-[11px] uppercase tracking-wider"></span>
                                                                                                  <span x-show="quiz.sub_questions[idx].selected_option" x-text="quiz.sub_questions[idx].selected_option" class="font-chinese text-base"></span>
                                                                                                  
                                                                                                  <template x-if="quiz.sub_questions[idx].selected_option && !quiz.sub_questions[idx].answered">
                                                                                                      <button @click.stop="startDrag({dataTransfer:{setData:()=>{}}}, quiz.sub_questions[idx].selected_option, idx); onDrop({preventDefault:()=>{}}, quiz, 'pool')" class="absolute -top-2 -right-2 w-5 h-5 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 shadow-sm transition-all">
                                                                                                          <span class="material-symbols-outlined text-[12px]">close</span>
                                                                                                      </button>
                                                                                                  </template>
                                                                                              </span>
                                                                                          </template>
                                                                                      </span>
                                                                                  </template>
                                                                              </div>
                                                                              
                                                                              <!-- Draggable Options Pool -->
                                                                              <div class="p-4 bg-slate-100 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700 min-h-[80px]"
                                                                                   @dragover.prevent=""
                                                                                   @drop="onDrop($event, quiz, 'pool')"
                                                                              >
                                                                                  <div class="flex flex-wrap gap-3">
                                                                                      <template x-for="(opt, oIdx) in quiz.available_options" :key="oIdx">
                                                                                          <div 
                                                                                              class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg shadow-sm font-chinese text-base cursor-grab active:cursor-grabbing hover:border-primary transition-all select-none flex flex-col items-center justify-center"
                                                                                              :class="opt.used ? 'opacity-0 invisible absolute' : 'opacity-100'"
                                                                                              draggable="true"
                                                                                              @dragstart="startDrag($event, opt.text, 'pool')"
                                                                                              x-show="!opt.used"
                                                                                          >
                                                                                              <template x-if="opt.pinyin && shouldShowPinyin">
                                                                                                  <span class="text-[11px] text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-pinyin font-normal leading-none" x-text="opt.pinyin"></span>
                                                                                              </template>
                                                                                              <span x-text="opt.text"></span>
                                                                                          </div>
                                                                                      </template>
                                                                                      <div x-show="quiz.available_options && quiz.available_options.filter(o => !o.used).length === 0" class="w-full text-center text-sm text-slate-400 font-medium py-2">
                                                                                          Đã sử dụng hết từ khóa
                                                                                      </div>
                                                                                  </div>
                                                                              </div>

                                                                          </div>
                                                                      </template>

                                                                      
                                                                      <template x-if="quiz.ques_type === 'fill_blank_dropdown'">
                                                                          <div class="space-y-4">
                                                                              <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl">
                                                                                  <div class="flex items-start gap-3 mb-4">
                                                                                      <div class="shrink-0 pt-0.5">
                                                                                          <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-md text-xs font-black" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1))"></span>
                                                                                      </div>
                                                                                      <div class="font-chinese text-[18px] font-bold text-slate-800 dark:text-white leading-loose flex-1">
                                                                                          <template x-for="(segment, idx) in quiz.parsed_question" :key="idx">
                                                                                              <span class="inline">
                                                                                                  <span x-html="(shouldShowPinyin && quiz.parsed_question_html && quiz.parsed_question_html[idx]) ? quiz.parsed_question_html[idx] : segment"></span>
                                                                                                  <template x-if="idx < quiz.parsed_question.length - 1">
                                                                                                      <span class="inline-block mx-2 align-middle">
                                                                                                          <select class="bg-[#fcfaf7] dark:bg-[#23201e] border text-center text-xs sm:text-sm zh-text font-semibold py-1.5 px-3.5 rounded-xl shadow-2xs focus:ring-2 focus:ring-[#e07a5f]/30 min-w-[120px] sm:min-w-[140px] outline-none cursor-pointer disabled:opacity-70 transition-all" :class="quiz.answered ? (quiz.selected_answers[idx] == quiz.correct[idx] ? \'border-emerald-500 text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 font-bold\' : \'border-rose-500 text-rose-600 bg-rose-50 dark:bg-rose-950/30 font-bold\') : \'border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-slate-200 hover:border-[#e07a5f]\'" x-model="quiz.selected_answers[idx]" :disabled="quiz.answered">
                                                                                                              <option value="" disabled selected>Chọn...</option>
                                                                                                              <template x-for="(hint, hIdx) in (quiz.hints || quiz.options)" :key="hIdx">
                                                                                                                  <option :value="hIdx" x-text="(hint.pinyin && shouldShowPinyin) ? (hint.text || hint) + ' (' + hint.pinyin + ')' : (hint.text || hint)"></option>
                                                                                                              </template>
                                                                                                          </select>
                                                                                                      </span>
                                                                                                  </template>
                                                                                              </span>
                                                                                          </template>
                                                                                      </div>
                                                                                  </div>
                                                                                  
                                                                                  <template x-if="quiz.answered">
                                                                                      <div class="mt-4 p-4 rounded-xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/20">
                                                                                          <div class="font-bold text-sm text-emerald-700 dark:text-emerald-400 mb-2">Đáp án chính xác:</div>
                                                                                          <div class="font-chinese text-[16px] font-bold text-slate-800 dark:text-white leading-relaxed">
                                                                                              <template x-for="(segment, idx) in quiz.parsed_question" :key="idx">
                                                                                                  <span class="inline">
                                                                                                      <span x-html="(shouldShowPinyin && quiz.parsed_question_html && quiz.parsed_question_html[idx]) ? quiz.parsed_question_html[idx] : segment"></span>
                                                                                                      <template x-if="idx < quiz.parsed_question.length - 1">
                                                                                                          <span class="inline-flex items-end mx-1 font-bold border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 [&_span]:!text-emerald-600 dark:[&_span]:!text-emerald-400 [&_rt]:!text-emerald-500/80 dark:[&_rt]:!text-emerald-400/80" x-html="(shouldShowPinyin && ((quiz.hints || quiz.options)[quiz.correct[idx]]?.html)) ? ((quiz.hints || quiz.options)[quiz.correct[idx]].html) : (((quiz.hints || quiz.options)[quiz.correct[idx]]?.text) || (quiz.hints || quiz.options)[quiz.correct[idx]])"></span>
                                                                                                      </template>
                                                                                                  </span>
                                                                                              </template>
                                                                                          </div>
                                                                                          <template x-if="quiz.explain_vi">
                                                                                              <div class="mt-3 pt-3 border-t border-emerald-200/50 dark:border-emerald-800/50">
                                                                                                  <div class="font-bold text-sm text-emerald-700 dark:text-emerald-400 mb-1">Giải thích:</div>
                                                                                                  <div class="text-sm text-slate-600 dark:text-slate-300" x-html="quiz.explain_vi"></div>
                                                                                              </div>
                                                                                          </template>
                                                                                      </div>
                                                                                  </template>
                                                                              </div>
                                                                          </div>
                                                                      </template>

                                                                      <template x-if="quiz.ques_type !== 'fill_blank_dropdrag' && quiz.ques_type !== 'fill_blank_dropdown'">
                                                                          <div class="space-y-4">
                                                                              <template x-if="quiz.context">
                                                                                  <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl mb-4 text-slate-700 dark:text-slate-300 font-chinese text-base leading-relaxed" x-text="quiz.context"></div>
                                                                              </template>
                                                                              
                                                                              <template x-if="!quiz.sub_questions || quiz.sub_questions.length === 0">
                                                                                  <div>
                                                                                      <h5 class="text-sm font-extrabold text-slate-800 dark:text-white flex items-start gap-2">
                                                                                          <span class="shrink-0 text-slate-400 mt-1" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1)) + '.'"></span>
                                                                                          <template x-if="shouldShowPinyin && quiz.question_html">
    <div x-html="quiz.question_html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !quiz.question_html">
                                                                                                <div class="flex flex-col">
                                                                                                    <template x-if="quiz.question_pinyin && shouldShowPinyin">
                                                                                                        <span x-text="quiz.question_pinyin" class="text-[13px] text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-pinyin font-normal"></span>
                                                                                                    </template>
                                                                                                    <template x-if="quiz.question">
                                                                                                        <span x-text="quiz.question" class="font-chinese tracking-wide text-[18px]"></span>
                                                                                                    </template>
                                                                                                </div>
                                                                                            </template>
                                                                                      </h5>

                                                                                                                                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                                                                          <template x-for="(opt, oIdx) in quiz.options" :key="oIdx">
                                                                                              <button 
                                                                                                  type="button"
                                                                                                  class="text-left p-3 sm:p-3.5 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group cursor-pointer"
                                                                                                  :class="quiz.answered 
                                                                                                      ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                         (quiz.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-slate-50/50 dark:bg-[#23201e]/50 text-slate-400 dark:text-slate-500 border-slate-200 dark:border-[#2d2926] opacity-60'))
                                                                                                      : (quiz.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10 ring-2 ring-primary/20' : 'bg-white dark:bg-[#23201e] text-slate-700 dark:text-slate-200 border-slate-200 dark:border-[#2d2926] hover:bg-slate-50 dark:hover:bg-[#2c2825] hover:border-slate-300 dark:hover:border-stone-700 shadow-2xs')"
                                                                                                  @click="if(!quiz.answered) quiz.selected = oIdx"
                                                                                                  :disabled="quiz.answered"
                                                                                              >
                                                                                                  <div class="flex items-center gap-3 min-w-0">
                                                                                                      <span 
                                                                                                          class="w-7 h-7 shrink-0 rounded-lg flex items-center justify-center text-xs font-bold transition-all"
                                                                                                          :class="quiz.answered 
                                                                                                              ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500 text-white shadow-xs' : (quiz.selected === oIdx ? 'bg-red-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-stone-800 text-slate-400 dark:text-slate-500'))
                                                                                                              : (quiz.selected === oIdx ? 'bg-primary text-white shadow-xs' : 'bg-slate-100 dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-stone-800 group-hover:bg-primary/10 group-hover:text-primary group-hover:border-primary/30')"
                                                                                                          x-text="['A','B','C','D','E','F'][oIdx] || (oIdx + 1)"
                                                                                                      ></span>

                                                                                                      <template x-if="opt && (typeof opt === 'object' || !['A','B','C','D','E','F'].includes(String(opt).trim()))">
                                                                                                          <div class="flex flex-col group-hover:translate-x-0.5 transition-transform min-w-0">
                                                                                                              <template x-if="shouldShowPinyin && opt.html">
    <div x-html="opt.html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !opt.html">
                                                                                                                  <div class="flex flex-col">
                                                                                                                      <template x-if="opt.pinyin && shouldShowPinyin">
                                                                                                                          <span class="text-[11px] text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-pinyin font-normal" x-text="opt.pinyin"></span>
                                                                                                                      </template>
                                                                                                                      <span class="font-chinese text-xs sm:text-sm font-semibold" x-text="opt.text || opt"></span>
                                                                                                                  </div>
                                                                                                              </template>
                                                                                                          </div>
                                                                                                      </template>

                                                                                                      <template x-if="opt && typeof opt === 'string' && ['A','B','C','D','E','F'].includes(opt.trim())">
                                                                                                          <span class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300" x-text="'Lựa chọn ' + opt.trim()"></span>
                                                                                                      </template>
                                                                                                  </div>

                                                                                                  <template x-if="quiz.answered">
                                                                                                      <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                                                                          <template x-if="quiz.selected === oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                                              <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Chính xác</span>
                                                                                                          </template>
                                                                                                          <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                                              <span class="text-[10px] font-black uppercase text-red-600 bg-red-500/20 px-2 py-0.5 rounded-md">Bạn chọn</span>
                                                                                                          </template>
                                                                                                          <template x-if="quiz.selected !== oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                                              <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Đáp án</span>
                                                                                                          </template>
                                                                                                          <template x-if="(opt.text || opt) == quiz.correct_answer">
                                                                                                              <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                                          </template>
                                                                                                          <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                                              <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                                          </template>
                                                                                                      </div>
                                                                                                  </template>
                                                                                              </button>
                                                                                          </template>
                                                                                      </div>


                                                                                  </div>
                                                                              </template>
                                                                              
                                                                              <template x-if="quiz.sub_questions && quiz.sub_questions.length > 0">
                                                                                  <div class="space-y-4">
                                                                                      <template x-for="(sq, sqIdx) in quiz.sub_questions" :key="sqIdx">
                                                                                          <div class="bg-white dark:bg-[#181615] border border-slate-200/80 dark:border-[#2d2926] p-4 rounded-xl shadow-2xs">
                                                                                              <h6 class="text-sm font-bold text-slate-800 dark:text-white flex items-start gap-2 mb-3">
                                                                                                  <span class="shrink-0 text-primary" x-text="'Câu ' + (sq.ques_id || (sqIdx + 1)) + '.'"></span>
                                                                                                  <span x-text="sq.question" class="font-chinese text-[15px]"></span>
                                                                                              </h6>
                                                                                              
                                                                                                                                                                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                                                                      <template x-for="(opt, oIdx) in sq.options" :key="oIdx">
                                                                                                      <button 
                                                                                                          type="button"
                                                                                                          class="text-left p-3 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group cursor-pointer"
                                                                                                          :class="sq.answered 
                                                                                                              ? ((opt.text || opt) == sq.correct ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                                 (sq.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-slate-50/50 dark:bg-[#23201e]/50 text-slate-400 dark:text-slate-500 border-slate-200 dark:border-[#2d2926] opacity-60'))
                                                                                                              : (sq.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10 ring-2 ring-primary/20' : 'bg-white dark:bg-[#23201e] text-slate-700 dark:text-slate-200 border-slate-200 dark:border-[#2d2926] hover:bg-slate-50 dark:hover:bg-[#2c2825] hover:border-slate-300 dark:hover:border-stone-700 shadow-2xs')"
                                                                                                          @click="if(!sq.answered) sq.selected = oIdx"
                                                                                                          :disabled="sq.answered"
                                                                                                      >
                                                                                                          <div class="flex items-center gap-2.5 min-w-0">
                                                                                                              <span 
                                                                                                                  class="w-6 h-6 shrink-0 rounded-md flex items-center justify-center text-xs font-bold transition-all"
                                                                                                                  :class="sq.answered 
                                                                                                                      ? ((opt.text || opt) == sq.correct ? 'bg-emerald-500 text-white shadow-xs' : (sq.selected === oIdx ? 'bg-red-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-stone-800 text-slate-400 dark:text-slate-500'))
                                                                                                                      : (sq.selected === oIdx ? 'bg-primary text-white shadow-xs' : 'bg-slate-100 dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-stone-800 group-hover:bg-primary/10 group-hover:text-primary group-hover:border-primary/30')"
                                                                                                                  x-text="['A','B','C','D','E','F'][oIdx] || (oIdx + 1)"
                                                                                                              ></span>

                                                                                                              <template x-if="opt && (typeof opt === 'object' || !['A','B','C','D','E','F'].includes(String(opt).trim()))">
                                                                                                                  <div class="flex flex-col group-hover:translate-x-0.5 transition-transform min-w-0">
                                                                                                                      <template x-if="shouldShowPinyin && opt.html">
    <div x-html="opt.html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !opt.html">
                                                                                                                          <div class="flex flex-col">
                                                                                                                              <template x-if="opt.pinyin && shouldShowPinyin">
                                                                                                                                  <span class="text-[11px] text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-pinyin font-normal" x-text="opt.pinyin"></span>
                                                                                                                              </template>
                                                                                                                              <span class="font-chinese text-xs sm:text-sm font-semibold" x-text="opt.text || opt"></span>
                                                                                                                          </div>
                                                                                                                      </template>
                                                                                                                  </div>
                                                                                                              </template>

                                                                                                              <template x-if="opt && typeof opt === 'string' && ['A','B','C','D','E','F'].includes(opt.trim())">
                                                                                                                  <span class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300" x-text="'Lựa chọn ' + opt.trim()"></span>
                                                                                                              </template>
                                                                                                          </div>

                                                                                                          <template x-if="sq.answered">
                                                                                                              <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                                                                                  <template x-if="(opt.text || opt) == sq.correct">
                                                                                                                      <div class="flex items-center gap-1">
                                                                                                                          <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Đáp án</span>
                                                                                                                          <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                                                      </div>
                                                                                                                  </template>
                                                                                                                  <template x-if="sq.selected === oIdx && (opt.text || opt) != sq.correct">
                                                                                                                      <div class="flex items-center gap-1">
                                                                                                                          <span class="text-[10px] font-black uppercase text-red-600 bg-red-500/20 px-2 py-0.5 rounded-md">Bạn chọn</span>
                                                                                                                          <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                                                      </div>
                                                                                                                  </template>
                                                                                                              </div>
                                                                                                          </template>
                                                                                                      </button>
                                                                                                  </template>
                                                                                                  </div>


                                                                                          </div>
                                                                                      </template>
                                                                                  </div>
                                                                              </template>
                                                                          </div>
                                                                      </template>
                                                                  </div>
                                                                </template>
                                                                
                                                                <!-- Global Check / Retry Button Bar -->
                                                                <div class="flex justify-center items-center gap-3 mt-8 mb-10 pt-8 border-t border-slate-200 dark:border-slate-700 w-full" 
                                                                     x-show="sect.questions && sect.questions.length > 0">
                                                                    <button 
                                                                        x-show="sect.questions.some(q => !q.answered && (q.correct_answer || (q.sub_questions && q.sub_questions.some(sq => sq.correct))))"
                                                                        class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-bold text-xs rounded-lg shadow-md shadow-primary/30 transition-all active:scale-95 flex items-center gap-2"
                                                                        :disabled="!isSectionFullyAnswered(sect.questions)"
                                                                        :class="isSectionFullyAnswered(sect.questions) ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                        @click="checkAllSection(sect.questions)"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[18px]">fact_check</span> <span x-text="'KIỂM TRA ĐÁP ÁN (' + getSectionAnsweredProgress(sect.questions).answered + '/' + getSectionAnsweredProgress(sect.questions).total + ')'"></span>
                                                                    </button>
                                                                    <button 
                                                                        x-show="sect.questions.every(q => q.answered || (!q.correct_answer && (!q.sub_questions || !q.sub_questions.some(sq => sq.correct))))"
                                                                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 transition-all active:scale-95 flex items-center gap-2"
                                                                        @click="resetSection(sect.questions)"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[18px]">refresh</span> LÀM LẠI BÀI NÀY
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Reading Tab Content -->
                                        <div x-show="practiceTab === 'reading'" x-transition class="space-y-6 pt-2 pb-10">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'reading')">
                                                <div>
                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'reading')?.sections || [])" :key="sIdx">
                                                        <div class="space-y-4 scroll-mt-[100px]" :id="'practice-' + 'reading' + '-section-' + sIdx">
                                                            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800/60 border-l-4 border-l-primary">
                                                                <template x-if="shouldShowPinyin && sect.section_han_html">
                                             <h4 class="text-[13px] sm:text-sm font-bold text-slate-800 dark:text-white font-chinese tracking-wide flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom" x-html="sect.section_han_html" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></h4>
                                         </template>
                                         <template x-if="!shouldShowPinyin || !sect.section_han_html">
                                             <h4 class="text-[13px] sm:text-sm font-bold text-slate-800 dark:text-white font-chinese tracking-wide" x-text="sect.section_han" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></h4>
                                         </template>
                                         <template x-if="sect.section_vi">
                                              <div class="mt-2 text-xs">
                                                  <template x-if="!sect.parsed_vi?.hasExample">
                                                      <p class="text-primary dark:text-primary-light italic font-medium" x-text="sect.section_vi" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></p>
                                                  </template>
                                                  <template x-if="sect.parsed_vi?.hasExample">
                                                      <div class="space-y-2 mt-1">
                                                          <p class="text-primary dark:text-primary-light font-bold" x-text="sect.parsed_vi.mainText"></p>
                                                          <div class="p-3.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 leading-relaxed font-normal shadow-2xs" :class="shouldShowPinyin ? 'flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom' : ''" x-html="shouldShowPinyin ? sect.parsed_vi.exampleHtml : sect.parsed_vi.exampleHtmlRaw"></div>
                                                      </div>
                                                  </template>
                                              </div>
                                          </template>
                                                            </div>

                                                            <template x-if="sect.image_path">
                                                                <div class="my-4 text-center border border-slate-100 dark:border-slate-800 rounded-2xl overflow-hidden bg-white dark:bg-slate-900 p-2 flex justify-center shadow-sm">
                                                                    <img :src="'/storage/hsk_media/' + sect.image_path" class="max-h-96 object-contain rounded-xl">
                                                                </div>
                                                            </template>

                                                            <div class="flex flex-col gap-4">
                                                                <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                                                                                                                                                                                                                                                                                                            <div class="bg-white dark:bg-[#181615] border border-slate-200/80 dark:border-[#2d2926] p-5 rounded-2xl shadow-2xs space-y-4 text-left">
                                                                      
                                                                      <template x-if="quiz.image_path">
                                                                          <div class="mb-4 text-center border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50 p-2 flex justify-center">
                                                                              <img :src="'/storage/hsk_media/' + quiz.image_path" class="max-h-64 object-contain rounded-lg">
                                                                          </div>
                                                                      </template>

                                                                      <template x-if="quiz.ques_type === 'fill_blank_dropdrag'">
                                                                          <div class="space-y-6">
                                                                              <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-300 font-chinese text-[16px] leading-[2.5]">
                                                                                  <template x-for="(segment, idx) in quiz.parsed_context" :key="idx">
                                                                                      <span class="inline">
                                                                                          <span x-html="(shouldShowPinyin && quiz.parsed_context_html && quiz.parsed_context_html[idx]) ? quiz.parsed_context_html[idx] : segment"></span>
                                                                                          <template x-if="idx < (quiz.sub_questions ? quiz.sub_questions.length : 0)">
                                                                                              <span 
                                                                                                  class="inline-flex items-center justify-center min-w-[80px] px-3 h-8 mx-1 border-b-2 font-bold text-sm align-middle cursor-pointer transition-all relative overflow-visible"
                                                                                                  :class="quiz.sub_questions[idx].selected_option ? (quiz.sub_questions[idx].answered ? (quiz.sub_questions[idx].selected_option == quiz.sub_questions[idx].correct_answer ? 'border-emerald-500 text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20' : 'border-red-500 text-red-600 bg-red-50 dark:bg-red-900/20') : 'border-primary text-primary bg-primary/5 hover:bg-primary/10') : 'border-slate-400 border-dashed text-slate-400 bg-slate-50 dark:bg-slate-800'"
                                                                                                  @dragover.prevent=""
                                                                                                  @drop="onDrop($event, quiz, idx)"
                                                                                              >
                                                                                                  <span x-show="!quiz.sub_questions[idx].selected_option" x-text="'(' + (quiz.sub_questions[idx].ques_id || (idx + 24)) + ') Kéo vào đây'" class="opacity-50 text-[11px] uppercase tracking-wider"></span>
                                                                                                  <span x-show="quiz.sub_questions[idx].selected_option" x-text="quiz.sub_questions[idx].selected_option" class="font-chinese text-base"></span>
                                                                                                  
                                                                                                  <template x-if="quiz.sub_questions[idx].selected_option && !quiz.sub_questions[idx].answered">
                                                                                                      <button @click.stop="startDrag({dataTransfer:{setData:()=>{}}}, quiz.sub_questions[idx].selected_option, idx); onDrop({preventDefault:()=>{}}, quiz, 'pool')" class="absolute -top-2 -right-2 w-5 h-5 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 shadow-sm transition-all">
                                                                                                          <span class="material-symbols-outlined text-[12px]">close</span>
                                                                                                      </button>
                                                                                                  </template>
                                                                                              </span>
                                                                                          </template>
                                                                                      </span>
                                                                                  </template>
                                                                              </div>
                                                                              
                                                                              <!-- Draggable Options Pool -->
                                                                              <div class="p-4 bg-slate-100 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700 min-h-[80px]"
                                                                                   @dragover.prevent=""
                                                                                   @drop="onDrop($event, quiz, 'pool')"
                                                                              >
                                                                                  <div class="flex flex-wrap gap-3">
                                                                                      <template x-for="(opt, oIdx) in quiz.available_options" :key="oIdx">
                                                                                          <div 
                                                                                              class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg shadow-sm font-chinese text-base cursor-grab active:cursor-grabbing hover:border-primary transition-all select-none"
                                                                                              :class="opt.used ? 'opacity-0 invisible absolute' : 'opacity-100'"
                                                                                              draggable="true"
                                                                                              @dragstart="startDrag($event, opt.text, 'pool')"
                                                                                              x-show="!opt.used"
                                                                                              x-text="opt.text"
                                                                                          ></div>
                                                                                      </template>
                                                                                      <div x-show="quiz.available_options && quiz.available_options.filter(o => !o.used).length === 0" class="w-full text-center text-sm text-slate-400 font-medium py-2">
                                                                                          Đã sử dụng hết từ khóa
                                                                                      </div>
                                                                                  </div>
                                                                              </div>

                                                                          </div>
                                                                      </template>

                                                                      
                                                                      <template x-if="quiz.ques_type === 'fill_blank_dropdown'">
                                                                          <div class="space-y-4">
                                                                              <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl">
                                                                                  <div class="flex items-start gap-3 mb-4">
                                                                                      <div class="shrink-0 pt-0.5">
                                                                                          <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-md text-xs font-black" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1))"></span>
                                                                                      </div>
                                                                                      <div class="font-chinese text-[18px] font-bold text-slate-800 dark:text-white leading-loose flex-1">
                                                                                          <template x-for="(segment, idx) in quiz.parsed_question" :key="idx">
                                                                                              <span class="inline">
                                                                                                  <span x-html="(shouldShowPinyin && quiz.parsed_question_html && quiz.parsed_question_html[idx]) ? quiz.parsed_question_html[idx] : segment"></span>
                                                                                                  <template x-if="idx < quiz.parsed_question.length - 1">
                                                                                                      <span class="inline-block mx-2 align-middle">
                                                                                                          <select class="bg-[#fcfaf7] dark:bg-[#23201e] border text-center text-xs sm:text-sm zh-text font-semibold py-1.5 px-3.5 rounded-xl shadow-2xs focus:ring-2 focus:ring-[#e07a5f]/30 min-w-[120px] sm:min-w-[140px] outline-none cursor-pointer disabled:opacity-70 transition-all" :class="quiz.answered ? (quiz.selected_answers[idx] == quiz.correct[idx] ? \'border-emerald-500 text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 font-bold\' : \'border-rose-500 text-rose-600 bg-rose-50 dark:bg-rose-950/30 font-bold\') : \'border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 dark:text-slate-200 hover:border-[#e07a5f]\'" x-model="quiz.selected_answers[idx]" :disabled="quiz.answered">
                                                                                                              <option value="" disabled selected>Chọn...</option>
                                                                                                              <template x-for="(hint, hIdx) in (quiz.hints || quiz.options)" :key="hIdx">
                                                                                                                  <option :value="hIdx" x-text="(hint.pinyin && shouldShowPinyin) ? (hint.text || hint) + ' (' + hint.pinyin + ')' : (hint.text || hint)"></option>
                                                                                                              </template>
                                                                                                          </select>
                                                                                                      </span>
                                                                                                  </template>
                                                                                              </span>
                                                                                          </template>
                                                                                      </div>
                                                                                  </div>
                                                                                  
                                                                                  <template x-if="quiz.answered">
                                                                                      <div class="mt-4 p-4 rounded-xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/20">
                                                                                          <div class="font-bold text-sm text-emerald-700 dark:text-emerald-400 mb-2">Đáp án chính xác:</div>
                                                                                          <div class="font-chinese text-[16px] font-bold text-slate-800 dark:text-white leading-relaxed">
                                                                                              <template x-for="(segment, idx) in quiz.parsed_question" :key="idx">
                                                                                                  <span class="inline">
                                                                                                      <span x-html="(shouldShowPinyin && quiz.parsed_question_html && quiz.parsed_question_html[idx]) ? quiz.parsed_question_html[idx] : segment"></span>
                                                                                                      <template x-if="idx < quiz.parsed_question.length - 1">
                                                                                                          <span class="inline-flex items-end mx-1 font-bold border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 [&_span]:!text-emerald-600 dark:[&_span]:!text-emerald-400 [&_rt]:!text-emerald-500/80 dark:[&_rt]:!text-emerald-400/80" x-html="(shouldShowPinyin && ((quiz.hints || quiz.options)[quiz.correct[idx]]?.html)) ? ((quiz.hints || quiz.options)[quiz.correct[idx]].html) : (((quiz.hints || quiz.options)[quiz.correct[idx]]?.text) || (quiz.hints || quiz.options)[quiz.correct[idx]])"></span>
                                                                                                      </template>
                                                                                                  </span>
                                                                                              </template>
                                                                                          </div>
                                                                                          <template x-if="quiz.explain_vi">
                                                                                              <div class="mt-3 pt-3 border-t border-emerald-200/50 dark:border-emerald-800/50">
                                                                                                  <div class="font-bold text-sm text-emerald-700 dark:text-emerald-400 mb-1">Giải thích:</div>
                                                                                                  <div class="text-sm text-slate-600 dark:text-slate-300" x-html="quiz.explain_vi"></div>
                                                                                              </div>
                                                                                          </template>
                                                                                      </div>
                                                                                  </template>
                                                                              </div>
                                                                          </div>
                                                                      </template>

                                                                      <template x-if="quiz.ques_type !== 'fill_blank_dropdrag' && quiz.ques_type !== 'fill_blank_dropdown'">
                                                                          <div class="space-y-4">
                                                                              <template x-if="quiz.context">
                                                                                  <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl mb-4 text-slate-700 dark:text-slate-300 font-chinese text-base leading-relaxed" x-text="quiz.context"></div>
                                                                              </template>
                                                                              
                                                                              <template x-if="!quiz.sub_questions || quiz.sub_questions.length === 0">
                                                                                  <div>
                                                                                      <h5 class="text-sm font-extrabold text-slate-800 dark:text-white flex items-start gap-2">
                                                                                          <span class="shrink-0 text-slate-400 mt-1" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1)) + '.'"></span>
                                                                                            <template x-if="shouldShowPinyin && quiz.question_html">
    <div x-html="quiz.question_html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !quiz.question_html">
                                                                                                <div class="flex flex-col">
                                                                                                    <template x-if="quiz.question_pinyin && shouldShowPinyin">
                                                                                                        <span x-text="quiz.question_pinyin" class="text-[13px] text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-pinyin font-normal"></span>
                                                                                                    </template>
                                                                                                    <template x-if="quiz.question">
                                                                                                        <span x-text="quiz.question" class="font-chinese tracking-wide text-[18px]"></span>
                                                                                                    </template>
                                                                                                </div>
                                                                                            </template>
                                                                                      </h5>

                                                                                                                                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                                                                          <template x-for="(opt, oIdx) in quiz.options" :key="oIdx">
                                                                                              <button 
                                                                                                  type="button"
                                                                                                  class="text-left p-3 sm:p-3.5 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group cursor-pointer"
                                                                                                  :class="quiz.answered 
                                                                                                      ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                         (quiz.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-slate-50/50 dark:bg-[#23201e]/50 text-slate-400 dark:text-slate-500 border-slate-200 dark:border-[#2d2926] opacity-60'))
                                                                                                      : (quiz.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10 ring-2 ring-primary/20' : 'bg-white dark:bg-[#23201e] text-slate-700 dark:text-slate-200 border-slate-200 dark:border-[#2d2926] hover:bg-slate-50 dark:hover:bg-[#2c2825] hover:border-slate-300 dark:hover:border-stone-700 shadow-2xs')"
                                                                                                  @click="if(!quiz.answered) quiz.selected = oIdx"
                                                                                                  :disabled="quiz.answered"
                                                                                              >
                                                                                                  <div class="flex items-center gap-3 min-w-0">
                                                                                                      <span 
                                                                                                          class="w-7 h-7 shrink-0 rounded-lg flex items-center justify-center text-xs font-bold transition-all"
                                                                                                          :class="quiz.answered 
                                                                                                              ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500 text-white shadow-xs' : (quiz.selected === oIdx ? 'bg-red-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-stone-800 text-slate-400 dark:text-slate-500'))
                                                                                                              : (quiz.selected === oIdx ? 'bg-primary text-white shadow-xs' : 'bg-slate-100 dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-stone-800 group-hover:bg-primary/10 group-hover:text-primary group-hover:border-primary/30')"
                                                                                                          x-text="['A','B','C','D','E','F'][oIdx] || (oIdx + 1)"
                                                                                                      ></span>

                                                                                                      <template x-if="opt && (typeof opt === 'object' || !['A','B','C','D','E','F'].includes(String(opt).trim()))">
                                                                                                          <div class="flex flex-col group-hover:translate-x-0.5 transition-transform min-w-0">
                                                                                                              <template x-if="shouldShowPinyin && opt.html">
    <div x-html="opt.html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !opt.html">
                                                                                                                  <div class="flex flex-col">
                                                                                                                      <template x-if="opt.pinyin && shouldShowPinyin">
                                                                                                                          <span class="text-[11px] text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-pinyin font-normal" x-text="opt.pinyin"></span>
                                                                                                                      </template>
                                                                                                                      <span class="font-chinese text-xs sm:text-sm font-semibold" x-text="opt.text || opt"></span>
                                                                                                                  </div>
                                                                                                              </template>
                                                                                                          </div>
                                                                                                      </template>

                                                                                                      <template x-if="opt && typeof opt === 'string' && ['A','B','C','D','E','F'].includes(opt.trim())">
                                                                                                          <span class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300" x-text="'Lựa chọn ' + opt.trim()"></span>
                                                                                                      </template>
                                                                                                  </div>

                                                                                                  <template x-if="quiz.answered">
                                                                                                      <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                                                                          <template x-if="quiz.selected === oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                                              <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Chính xác</span>
                                                                                                          </template>
                                                                                                          <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                                              <span class="text-[10px] font-black uppercase text-red-600 bg-red-500/20 px-2 py-0.5 rounded-md">Bạn chọn</span>
                                                                                                          </template>
                                                                                                          <template x-if="quiz.selected !== oIdx && (opt.text || opt) == quiz.correct_answer">
                                                                                                              <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Đáp án</span>
                                                                                                          </template>
                                                                                                          <template x-if="(opt.text || opt) == quiz.correct_answer">
                                                                                                              <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                                          </template>
                                                                                                          <template x-if="quiz.selected === oIdx && (opt.text || opt) != quiz.correct_answer">
                                                                                                              <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                                          </template>
                                                                                                      </div>
                                                                                                  </template>
                                                                                              </button>
                                                                                          </template>
                                                                                      </div>


                                                                                  </div>
                                                                              </template>
                                                                              
                                                                              <template x-if="quiz.sub_questions && quiz.sub_questions.length > 0">
                                                                                  <div class="space-y-4">
                                                                                      <template x-for="(sq, sqIdx) in quiz.sub_questions" :key="sqIdx">
                                                                                          <div class="bg-white dark:bg-[#181615] border border-slate-200/80 dark:border-[#2d2926] p-4 rounded-xl shadow-2xs">
                                                                                              <h6 class="text-sm font-bold text-slate-800 dark:text-white flex items-start gap-2 mb-3">
                                                                                                  <span class="shrink-0 text-primary" x-text="'Câu ' + (sq.ques_id || (sqIdx + 1)) + '.'"></span>
                                                                                                  <span x-text="sq.question" class="font-chinese text-[15px]"></span>
                                                                                              </h6>
                                                                                              
                                                                                                                                                                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                                                                      <template x-for="(opt, oIdx) in sq.options" :key="oIdx">
                                                                                                      <button 
                                                                                                          type="button"
                                                                                                          class="text-left p-3 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group cursor-pointer"
                                                                                                          :class="sq.answered 
                                                                                                              ? ((opt.text || opt) == sq.correct ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                                 (sq.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-slate-50/50 dark:bg-[#23201e]/50 text-slate-400 dark:text-slate-500 border-slate-200 dark:border-[#2d2926] opacity-60'))
                                                                                                              : (sq.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10 ring-2 ring-primary/20' : 'bg-white dark:bg-[#23201e] text-slate-700 dark:text-slate-200 border-slate-200 dark:border-[#2d2926] hover:bg-slate-50 dark:hover:bg-[#2c2825] hover:border-slate-300 dark:hover:border-stone-700 shadow-2xs')"
                                                                                                          @click="if(!sq.answered) sq.selected = oIdx"
                                                                                                          :disabled="sq.answered"
                                                                                                      >
                                                                                                          <div class="flex items-center gap-2.5 min-w-0">
                                                                                                              <span 
                                                                                                                  class="w-6 h-6 shrink-0 rounded-md flex items-center justify-center text-xs font-bold transition-all"
                                                                                                                  :class="sq.answered 
                                                                                                                      ? ((opt.text || opt) == sq.correct ? 'bg-emerald-500 text-white shadow-xs' : (sq.selected === oIdx ? 'bg-red-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-stone-800 text-slate-400 dark:text-slate-500'))
                                                                                                                      : (sq.selected === oIdx ? 'bg-primary text-white shadow-xs' : 'bg-slate-100 dark:bg-[#181615] text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-stone-800 group-hover:bg-primary/10 group-hover:text-primary group-hover:border-primary/30')"
                                                                                                                  x-text="['A','B','C','D','E','F'][oIdx] || (oIdx + 1)"
                                                                                                              ></span>

                                                                                                              <template x-if="opt && (typeof opt === 'object' || !['A','B','C','D','E','F'].includes(String(opt).trim()))">
                                                                                                                  <div class="flex flex-col group-hover:translate-x-0.5 transition-transform min-w-0">
                                                                                                                      <template x-if="shouldShowPinyin && opt.html">
    <div x-html="opt.html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !opt.html">
                                                                                                                          <div class="flex flex-col">
                                                                                                                              <template x-if="opt.pinyin && shouldShowPinyin">
                                                                                                                                  <span class="text-[11px] text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-pinyin font-normal" x-text="opt.pinyin"></span>
                                                                                                                              </template>
                                                                                                                              <span class="font-chinese text-xs sm:text-sm font-semibold" x-text="opt.text || opt"></span>
                                                                                                                          </div>
                                                                                                                      </template>
                                                                                                                  </div>
                                                                                                              </template>

                                                                                                              <template x-if="opt && typeof opt === 'string' && ['A','B','C','D','E','F'].includes(opt.trim())">
                                                                                                                  <span class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300" x-text="'Lựa chọn ' + opt.trim()"></span>
                                                                                                              </template>
                                                                                                          </div>

                                                                                                          <template x-if="sq.answered">
                                                                                                              <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                                                                                  <template x-if="(opt.text || opt) == sq.correct">
                                                                                                                      <div class="flex items-center gap-1">
                                                                                                                          <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-500/20 px-2 py-0.5 rounded-md">Đáp án</span>
                                                                                                                          <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                                                      </div>
                                                                                                                  </template>
                                                                                                                  <template x-if="sq.selected === oIdx && (opt.text || opt) != sq.correct">
                                                                                                                      <div class="flex items-center gap-1">
                                                                                                                          <span class="text-[10px] font-black uppercase text-red-600 bg-red-500/20 px-2 py-0.5 rounded-md">Bạn chọn</span>
                                                                                                                          <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                                                      </div>
                                                                                                                  </template>
                                                                                                              </div>
                                                                                                          </template>
                                                                                                      </button>
                                                                                                  </template>
                                                                                                  </div>


                                                                                          </div>
                                                                                      </template>
                                                                                  </div>
                                                                              </template>
                                                                          </div>
                                                                      </template>
                                                                  </div>
                                                                </template>
                                                                
                                                                <!-- Global Check / Retry Button Bar -->
                                                                <div class="flex justify-center items-center gap-3 mt-8 mb-10 pt-8 border-t border-slate-200 dark:border-slate-700 w-full" 
                                                                     x-show="sect.questions && sect.questions.length > 0">
                                                                    <button 
                                                                        x-show="sect.questions.some(q => !q.answered && (q.correct_answer || (q.sub_questions && q.sub_questions.some(sq => sq.correct))))"
                                                                        class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-bold text-xs rounded-lg shadow-md shadow-primary/30 transition-all active:scale-95 flex items-center gap-2"
                                                                        :disabled="!isSectionFullyAnswered(sect.questions)"
                                                                        :class="isSectionFullyAnswered(sect.questions) ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                        @click="checkAllSection(sect.questions)"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[18px]">fact_check</span> <span x-text="'KIỂM TRA ĐÁP ÁN (' + getSectionAnsweredProgress(sect.questions).answered + '/' + getSectionAnsweredProgress(sect.questions).total + ')'"></span>
                                                                    </button>
                                                                    <button 
                                                                        x-show="sect.questions.every(q => q.answered || (!q.correct_answer && (!q.sub_questions || !q.sub_questions.some(sq => sq.correct))))"
                                                                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 transition-all active:scale-95 flex items-center gap-2"
                                                                        @click="resetSection(sect.questions)"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[18px]">refresh</span> LÀM LẠI BÀI NÀY
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <div x-show="practiceTab === 'writing'" x-transition class="space-y-6 pt-2 pb-10" style="display: none;">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'writing')?.sections?.length === 0">
                                                <div class="text-center py-10 text-slate-500">Chưa có bài tập Viết</div>
                                            </template>
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'writing')?.sections?.length > 0">
                                                <div class="space-y-6">
                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'writing')?.sections || [])" :key="sIdx">
                                                        <div class="space-y-4 scroll-mt-[100px]" :id="'practice-' + 'writing' + '-section-' + sIdx">
                                                            <!-- Section Header -->
                                                            <div class="bg-primary/5 border border-primary/20 rounded-xl p-4 mb-4">
                                                                <template x-if="shouldShowPinyin && sect.section_han_html">
                                                                    <h4 class="font-bold text-primary mb-1 text-lg flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom" x-html="sect.section_han_html" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></h4>
                                                                </template>
                                                                <template x-if="!shouldShowPinyin || !sect.section_han_html">
                                                                    <h4 class="font-bold text-primary mb-1 text-lg" x-text="sect.section_han" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></h4>
                                                                </template>
                                                                <template x-if="sect.section_vi">
                                                                    <div class="mt-2 text-xs">
                                                                        <template x-if="!sect.parsed_vi?.hasExample">
                                                                            <p class="text-slate-600 dark:text-slate-400 font-medium" x-text="sect.section_vi" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></p>
                                                                        </template>
                                                                        <template x-if="sect.parsed_vi?.hasExample">
                                                                            <div class="space-y-2 mt-1">
                                                                                <p class="text-primary dark:text-primary-light font-bold text-sm" x-text="sect.parsed_vi.mainText"></p>
                                                                                <div class="p-3.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 leading-relaxed font-normal shadow-2xs" :class="shouldShowPinyin ? 'flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom' : ''" x-html="shouldShowPinyin ? sect.parsed_vi.exampleHtml : sect.parsed_vi.exampleHtmlRaw"></div>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </template>
                                                            </div>

                                                            <template x-if="sect.image_path">
                                                                <div class="my-4 text-center border border-slate-100 dark:border-slate-800 rounded-2xl overflow-hidden bg-white dark:bg-slate-900 p-2 flex justify-center shadow-sm">
                                                                    <img :src="'/storage/hsk_media/' + sect.image_path" class="max-h-96 object-contain rounded-xl">
                                                                </div>
                                                            </template>

                                                            <div class="flex flex-col gap-4">
                                                                <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl shadow-sm mb-4">
                                                                        
                                                                        <!-- REORDER DUOLINGO LAYOUT -->
                                                                        <template x-if="quiz.ques_type === 'reorder'">
                                                                            <div
                                                                                x-data="{
                                                                                    available: (() => {
                                                                                        let segs = quiz.question_segments || [];
                                                                                        if (typeof segs === 'string') { try { segs = JSON.parse(segs); } catch(e) { segs = []; } }
                                                                                        if (typeof segs === 'object' && !Array.isArray(segs)) { segs = Object.values(segs); }
                                                                                        if (!Array.isArray(segs)) return [];
                                                                                        return segs.map((text, idx) => ({
                                                                                            id: idx,
                                                                                            text: typeof text === 'string' ? text : (text.text || ''),
                                                                                            html: typeof text === 'string' ? '' : (text.html || ''),
                                                                                            pinyin: typeof text === 'string' ? '' : (text.pinyin || '')
                                                                                        }));
                                                                                    })(),
                                                                                    selected: [],
                                                                                    selectItem(index) {
                                                                                        if(quiz.answered) return;
                                                                                        var item = this.available.splice(index, 1)[0];
                                                                                        this.selected.push(item);
                                                                                        quiz.userAnswer = this.selected.map(function(i){ return i.text; }).join('');
                                                                                        quiz.selected = quiz.userAnswer.length > 0 ? 1 : null;
                                                                                    },
                                                                                    unselectItem(index) {
                                                                                        if(quiz.answered) return;
                                                                                        var item = this.selected.splice(index, 1)[0];
                                                                                        this.available.push(item);
                                                                                        this.available.sort(function(a,b){ return a.id - b.id; });
                                                                                        quiz.userAnswer = this.selected.map(function(i){ return i.text; }).join('');
                                                                                        quiz.selected = quiz.userAnswer.length > 0 ? 1 : null;
                                                                                    }
                                                                                }"
                                                                                class="p-5 sm:p-6"
                                                                            >
                                                                                <div class="flex items-start gap-3 mb-5">
                                                                                    <div class="shrink-0 pt-0.5">
                                                                                        <span class="px-2 py-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-500 dark:text-slate-400 rounded-lg text-xs font-bold" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1))"></span>
                                                                                    </div>
                                                                                    <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                                                                                        Sắp xếp các từ sau thành câu hoàn chỉnh
                                                                                    </div>
                                                                                </div>



                                                                                <!-- Context / Pinyin if exists -->
                                                                                <template x-if="quiz.question_pinyin && shouldShowPinyin">
                                                                                    <div class="text-xs text-[#e07a5f] font-mono font-medium tracking-wide mb-3 px-1" x-text="quiz.question_pinyin"></div>
                                                                                </template>

                                                                                <template x-if="quiz.context">
                                                                                    <div class="p-3 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl mb-4 text-slate-700 dark:text-slate-300 zh-text text-sm font-medium leading-relaxed" x-text="quiz.context"></div>
                                                                                </template>

                                                                                <!-- Available Segments -->
                                                                                <div class="flex flex-wrap gap-2.5 mb-5 min-h-[50px] p-2.5 bg-[#fcfaf7] dark:bg-[#23201e] rounded-xl border border-[#e8e2d9] dark:border-[#2d2926]">
                                                                                    <template x-for="(item, i) in available" :key="item.id">
                                                                                        <button 
                                                                                            type="button"
                                                                                            @click="selectItem(i)"
                                                                                            :disabled="quiz.answered"
                                                                                            class="px-3.5 py-2 font-medium zh-text text-sm sm:text-base rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed select-none bg-white border-2 border-b-4 border-[#e8e2d9] dark:border-[#2d2926] text-slate-800 hover:bg-[#fff2ee]/50 hover:-translate-y-0.5 active:border-b-2 active:translate-y-0.5 dark:bg-[#181615] dark:text-slate-200"
                                                                                            :class="/^[.,?，。？！]+$/.test(item.text) ? '!border-[#fcdccf] !text-[#e07a5f] !bg-[#fff2ee] dark:!border-[#4a2e26] dark:!bg-[#2c221e]' : ''"
                                                                                        >
                                                                                            <span x-html="(shouldShowPinyin && item.html) ? item.html : item.text"></span>
                                                                                        </button>
                                                                                    </template>
                                                                                    <template x-if="available.length === 0">
                                                                                        <div class="w-full flex items-center justify-center text-slate-400 text-xs font-medium py-2">
                                                                                            Đã chọn hết từ
                                                                                        </div>
                                                                                    </template>
                                                                                </div>

                                                                                <!-- Selected Segments (Answer area) -->
                                                                                <div class="w-full bg-[#fcfaf7] dark:bg-[#23201e] border-2 border-dashed border-[#e8e2d9] dark:border-[#2d2926] rounded-xl p-4 min-h-[100px] mb-5 flex flex-wrap gap-2.5 items-start content-start transition-colors"
                                                                                     :class="quiz.answered ? (quiz.userAnswer === quiz.correct_answer ? 'border-emerald-400 bg-emerald-50/40 dark:bg-emerald-950/20' : 'border-rose-400 bg-rose-50/40 dark:bg-rose-950/20') : 'hover:border-[#e07a5f]/50'">
                                                                                    
                                                                                    <template x-if="selected.length === 0">
                                                                                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 text-xs font-medium py-5 gap-1.5">
                                                                                            <i class="fa-solid fa-hand-pointer text-lg opacity-40"></i>
                                                                                            <span>Nhấn vào các từ bên trên để ghép thành câu</span>
                                                                                        </div>
                                                                                    </template>

                                                                                    <template x-for="(item, i) in selected" :key="item.id">
                                                                                        <button 
                                                                                            type="button"
                                                                                            @click="unselectItem(i)"
                                                                                            :disabled="quiz.answered"
                                                                                            class="px-3.5 py-2 font-medium zh-text text-sm sm:text-base rounded-xl transition-all disabled:cursor-not-allowed select-none bg-[#fff2ee] border-2 border-b-4 border-[#fcdccf] text-[#e07a5f] hover:bg-[#ffe6de] hover:-translate-y-0.5 active:border-b-2 active:translate-y-0.5 dark:bg-[#2c221e] dark:border-[#4a2e26] dark:text-[#f4978e] shadow-2xs"
                                                                                            :class="/^[.,?，。？！]+$/.test(item.text) ? '!border-amber-300 !text-amber-700 !bg-amber-100 dark:!border-amber-600/80 dark:!bg-amber-900/50' : ''"
                                                                                        >
                                                                                            <span x-html="(shouldShowPinyin && item.html) ? item.html : item.text"></span>
                                                                                        </button>
                                                                                    </template>
                                                                                </div>

                                                                                <template x-if="quiz.answered">
                                                                                    <div class="mt-4 p-4 rounded-xl border border-emerald-300 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-950/20 mb-4 shadow-2xs">
                                                                                        <div class="font-bold text-xs text-emerald-700 dark:text-emerald-400 mb-1.5 flex items-center gap-1.5">
                                                                                            <i class="fa-solid fa-circle-check"></i> Đáp án đúng:
                                                                                        </div>
                                                                                        <div class="zh-text text-sm sm:text-base font-medium text-emerald-800 dark:text-emerald-300 leading-relaxed" x-text="quiz.correct_answer"></div>
                                                                                        <template x-if="quiz.explain_vi">
                                                                                            <div class="mt-2.5 pt-2.5 border-t border-emerald-200/50 dark:border-emerald-800/50">
                                                                                                <div class="font-bold text-xs text-emerald-700 dark:text-emerald-400 mb-0.5">Giải thích:</div>
                                                                                                <div class="text-xs text-slate-600 dark:text-slate-300" x-html="quiz.explain_vi"></div>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                </template>

                                                                            </div>
                                                                        </template>

                                                                        <!-- NORMAL VERTICAL LAYOUT (for paragraph etc) -->
                                                                        <template x-if="quiz.ques_type === 'fill_blank_dropdown'">
                                                                            <div class="space-y-4">
                                                                                <div class="p-5 sm:p-6 bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl">
                                                                                    <div class="flex items-start gap-3 mb-3">
                                                                                        <div class="shrink-0 pt-0.5">
                                                                                            <span class="px-2 py-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-500 dark:text-slate-400 rounded-lg text-xs font-bold" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1))"></span>
                                                                                        </div>
                                                                                        <div class="flex-1 space-y-1.5">
                                                                                            <!-- Pinyin prompt for HSK 1-3 -->
                                                                                            <template x-if="quiz.question_pinyin && shouldShowPinyin">
                                                                                                <div class="text-xs text-[#e07a5f] font-mono font-medium tracking-wide" x-text="quiz.question_pinyin"></div>
                                                                                            </template>
                                                                                            <div class="zh-text text-sm sm:text-base font-medium text-slate-800 dark:text-slate-100 leading-loose flex-1 flex flex-wrap items-end gap-x-[1px] gap-y-2">
                                                                                                <template x-for="(segment, idx) in quiz.parsed_question" :key="idx">
                                                                                                    <span class="inline-flex flex-wrap items-end gap-x-[1px]">
                                                                                                        <span x-html="(shouldShowPinyin && quiz.parsed_question_html && quiz.parsed_question_html[idx]) ? quiz.parsed_question_html[idx] : segment"></span>
                                                                                                        <template x-if="idx < quiz.parsed_question.length - 1">
                                                                                                            <span class="inline-block mx-1.5 mb-0.5 align-bottom">
                                                                                                                <select 
                                                                                                                    class="appearance-none text-center font-chinese font-bold text-xs sm:text-sm py-1 px-3 rounded-xl shadow-2xs cursor-pointer outline-none transition-all duration-200 min-w-[85px] sm:min-w-[100px]"
                                                                                                                    :class="quiz.answered ? (quiz.selected_answers[idx] == quiz.correct[idx] ? 'bg-emerald-500/15 border-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-bold' : 'bg-rose-500/15 border-2 border-rose-500 text-rose-600 dark:text-rose-400 font-bold') : (quiz.selected_answers[idx] !== '' && quiz.selected_answers[idx] !== undefined && quiz.selected_answers[idx] !== null ? 'bg-[#fff2ee] dark:bg-[#2c221e] border-2 border-[#e07a5f] text-[#e07a5f] font-bold shadow-xs' : 'bg-[#fcfaf7] dark:bg-[#23201e] border-2 border-dashed border-[#e07a5f]/50 text-[#e07a5f] hover:border-[#e07a5f] hover:bg-[#fff2ee] dark:hover:bg-[#2c221e]')"
                                                                                                                    x-model="quiz.selected_answers[idx]"
                                                                                                                    :disabled="quiz.answered"
                                                                                                                >
                                                                                                                    <option value="" disabled selected class="text-slate-400 font-normal"> ( ... ) </option>
                                                                                                                    <template x-for="(hint, hIdx) in (quiz.hints || quiz.options)" :key="hIdx">
                                                                                                                        <option :value="hIdx" class="text-slate-900 dark:text-slate-100 bg-white dark:bg-[#181615] font-chinese font-medium py-1" x-text="(hint.pinyin && shouldShowPinyin) ? (hint.text || hint) + ' (' + hint.pinyin + ')' : (hint.text || hint)"></option>
                                                                                                                    </template>
                                                                                                                </select>
                                                                                                            </span>
                                                                                                        </template>
                                                                                                    </span>
                                                                                                </template>
                                                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <template x-if="quiz.answered">
                                                                                        <div class="mt-4 p-4 rounded-xl border border-emerald-300 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-950/20">
                                                                                            <div class="font-bold text-xs text-emerald-700 dark:text-emerald-400 mb-1.5">Đáp án chính xác:</div>
                                                                                            <div class="zh-text text-sm sm:text-base font-medium text-slate-800 dark:text-white leading-relaxed flex flex-wrap items-end gap-x-[1px] gap-y-2">
                                                                                                <template x-for="(segment, idx) in quiz.parsed_question" :key="idx">
                                                                                                    <span class="inline-flex flex-wrap items-end gap-x-[1px]">
                                                                                                        <span x-html="(shouldShowPinyin && quiz.parsed_question_html && quiz.parsed_question_html[idx]) ? quiz.parsed_question_html[idx] : segment"></span>
                                                                                                        <template x-if="idx < quiz.parsed_question.length - 1">
                                                                                                            <span class="inline-flex items-end mx-1 font-bold border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 [&_span]:!text-emerald-600 dark:[&_span]:!text-emerald-400 [&_rt]:!text-emerald-500/80 dark:[&_rt]:!text-emerald-400/80" x-html="(shouldShowPinyin && ((quiz.hints || quiz.options)[quiz.correct[idx]]?.html)) ? ((quiz.hints || quiz.options)[quiz.correct[idx]].html) : (((quiz.hints || quiz.options)[quiz.correct[idx]]?.text) || (quiz.hints || quiz.options)[quiz.correct[idx]])"></span>
                                                                                                        </template>
                                                                                                    </span>
                                                                                                </template>
                                                                                            </div>
                                                                                            <template x-if="quiz.explain_vi">
                                                                                                <div class="mt-2.5 pt-2.5 border-t border-emerald-200/50 dark:border-emerald-800/50">
                                                                                                    <div class="font-bold text-xs text-emerald-700 dark:text-emerald-400 mb-0.5">Giải thích:</div>
                                                                                                    <div class="text-xs text-slate-600 dark:text-slate-300" x-html="quiz.explain_vi"></div>
                                                                                                </div>
                                                                                            </template>
                                                                                        </div>
                                                                                    </template>
                                                                                </div>
                                                                            </div>
                                                                        </template>

                                                                        <template x-if="quiz.ques_type !== 'reorder' && quiz.ques_type !== 'fill_blank_dropdown'">
                                                                            <div class="p-5 sm:p-6">
                                                                                <!-- Context text if exists -->
                                                                                <template x-if="quiz.context">
                                                                                    <div class="p-3.5 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl mb-3.5 text-slate-700 dark:text-slate-300 zh-text text-sm leading-relaxed">
                                                                                        <template x-if="shouldShowPinyin && quiz.context_html">
    <div x-html="quiz.context_html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !quiz.context_html">
                                                                                            <div class="flex flex-col">
                                                                                                <template x-if="quiz.context_pinyin && shouldShowPinyin">
                                                                                                    <span x-text="quiz.context_pinyin" class="text-xs text-slate-500 dark:text-slate-400 tracking-wide mb-1 font-normal"></span>
                                                                                                </template>
                                                                                                <span x-text="quiz.context"></span>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                </template>

                                                                                <div class="flex items-start gap-3 mb-2">
                                                                                    <div class="shrink-0 pt-0.5">
                                                                                        <span class="px-2 py-1 bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] text-slate-500 dark:text-slate-400 rounded-lg text-xs font-bold" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1))"></span>
                                                                                    </div>
                                                                                    <div class="flex flex-col gap-1 flex-1">
                                                                                        <template x-if="shouldShowPinyin && quiz.question_html">
    <div x-html="quiz.question_html" class="flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom"></div>
</template>
<template x-if="!shouldShowPinyin || !quiz.question_html">
                                                                                            <div class="flex flex-col">
                                                                                                <template x-if="quiz.question_pinyin && shouldShowPinyin">
                                                                                                    <span x-text="quiz.question_pinyin" class="text-xs text-slate-500 dark:text-slate-400 tracking-wide mb-0.5 font-normal"></span>
                                                                                                </template>
                                                                                                <template x-if="quiz.question">
                                                                                                    <span x-text="quiz.question" class="zh-text text-sm sm:text-base font-medium text-slate-800 dark:text-white leading-snug"></span>
                                                                                                </template>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="mt-3.5">
                                                                                    <textarea 
                                                                                        class="w-full bg-[#fcfaf7] dark:bg-[#23201e] border border-[#e8e2d9] dark:border-[#2d2926] rounded-xl p-3.5 text-sm zh-text text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-[#e07a5f] outline-none transition-all resize-none min-h-[100px]"
                                                                                        placeholder="Nhập câu trả lời của bạn..."
                                                                                        x-model="quiz.userAnswer"
                                                                                        :disabled="quiz.answered"
                                                                                        @input="if(quiz.userAnswer.trim().length > 0) quiz.selected = 1; else quiz.selected = null;"
                                                                                    ></textarea>
                                                                                </div>

                                                                                <template x-if="quiz.answered">
                                                                                    <div class="mt-4 p-4 rounded-xl border border-emerald-300 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-950/20">
                                                                                        <div class="font-bold text-xs text-emerald-700 dark:text-emerald-400 mb-1.5">Đáp án tham khảo:</div>
                                                                                        <div class="zh-text text-sm sm:text-base font-medium text-slate-800 dark:text-white leading-relaxed" x-text="quiz.correct_answer"></div>
                                                                                        <template x-if="quiz.explain_vi">
                                                                                            <div class="mt-2.5 pt-2.5 border-t border-emerald-200/50 dark:border-emerald-800/50">
                                                                                                <div class="font-bold text-xs text-emerald-700 dark:text-emerald-400 mb-0.5">Giải thích:</div>
                                                                                                <div class="text-xs text-slate-600 dark:text-slate-300" x-html="quiz.explain_vi"></div>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                </template>

                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </template>
                                                                
                                                                <!-- Global Check / Retry Button Bar -->
                                                                <div class="flex justify-center items-center gap-3 mt-8 mb-10 pt-8 border-t border-slate-200 dark:border-slate-700 w-full" 
                                                                     x-show="sect.questions && sect.questions.length > 0">
                                                                    <button 
                                                                        x-show="sect.questions.some(q => !q.answered && (q.correct_answer || (q.sub_questions && q.sub_questions.some(sq => sq.correct))))"
                                                                        class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-bold text-xs rounded-lg shadow-md shadow-primary/30 transition-all active:scale-95 flex items-center gap-2"
                                                                        :disabled="!isSectionFullyAnswered(sect.questions)"
                                                                        :class="isSectionFullyAnswered(sect.questions) ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                        @click="checkAllSection(sect.questions)"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[18px]">fact_check</span> <span x-text="'KIỂM TRA ĐÁP ÁN (' + getSectionAnsweredProgress(sect.questions).answered + '/' + getSectionAnsweredProgress(sect.questions).total + ')'"></span>
                                                                    </button>
                                                                    <button 
                                                                        x-show="sect.questions.every(q => q.answered || (!q.correct_answer && (!q.sub_questions || !q.sub_questions.some(sq => sq.correct))))"
                                                                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 transition-all active:scale-95 flex items-center gap-2"
                                                                        @click="resetSection(sect.questions)"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[18px]">refresh</span> LÀM LẠI BÀI NÀY
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        </div>

                                    </div>

                                    <!-- Navigation Sidebar (Right column) -->
                                    <div class="w-full md:w-64 lg:w-72 xl:w-80 shrink-0 space-y-4 sticky top-[92px] sm:top-[96px] self-start max-h-[calc(100vh-120px)] overflow-y-auto custom-scrollbar z-20">
                                        <!-- Sidebar List -->
                                        <div class="bg-white dark:bg-[#181615] border border-slate-200/80 dark:border-[#2d2926] shadow-2xs rounded-2xl p-3.5 sm:p-4 text-left">
                                            <h5 class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b border-slate-100 dark:border-[#2d2926] pb-2.5 mb-2.5 flex items-center justify-between">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[16px] text-primary">route</span>
                                                    <span>{{ __('Điều hướng bài tập') }}</span>
                                                </div>
                                                <span class="text-[9px] font-bold text-primary bg-primary/10 px-1.5 py-0.5 rounded uppercase" x-text="practiceTab === 'reading' ? 'Đọc' : (practiceTab === 'writing' ? 'Viết' : 'Nghe')"></span>
                                            </h5>

                                            <div class="flex flex-col space-y-0.5 relative before:content-[''] before:absolute before:left-[15px] before:top-2 before:bottom-2 before:w-px before:bg-slate-100 dark:before:bg-slate-800">
                                                <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === practiceTab)?.sections || [])" :key="sIdx">
                                                    <button 
                                                        type="button"
                                                        @click="practiceSectionIdx = sIdx; const el = document.getElementById('practice-' + practiceTab + '-section-' + sIdx); if(el) { el.scrollIntoView({ behavior: 'smooth', block: 'start' }) }"
                                                        class="group relative flex items-center gap-2.5 py-1.5 px-1.5 rounded-lg text-left z-10 cursor-pointer transition-colors"
                                                        :class="practiceSectionIdx === sIdx ? 'bg-primary/10 text-primary' : 'hover:bg-slate-50 dark:hover:bg-[#23201e] text-slate-600 dark:text-slate-300'"
                                                        :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"
                                                    >
                                                        <div 
                                                            class="h-6 w-6 shrink-0 rounded-full flex items-center justify-center text-[10px] font-bold transition-all border"
                                                            :class="practiceSectionIdx === sIdx ? 'bg-primary text-white border-primary shadow-xs' : 'bg-slate-100 dark:bg-[#23201e] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-stone-800 group-hover:border-primary/40 group-hover:text-primary'"
                                                        >
                                                            <span x-text="sIdx + 1"></span>
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span 
                                                                class="text-[11px] sm:text-xs font-semibold font-chinese transition-colors line-clamp-1"
                                                                :class="practiceSectionIdx === sIdx ? 'text-primary' : 'text-slate-700 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-primary-light'"
                                                                x-text="sect.section_han" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></span>
                                                            <span class="text-[9.5px] text-slate-400 dark:text-slate-500 font-normal line-clamp-1" x-show="sect.section_vi" x-text="sect.section_vi" :title="sect.section_han + (sect.section_vi ? ' (' + sect.section_vi + ')' : '')"></span>
                                                        </div>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                        <script>

                            window.parseSectionVi = function(text) {
                                if (!text) return { mainText: '', hasExample: false, exampleHtml: '' };

                                // === BƯỚC 1: Tìm điểm bắt đầu của ví dụ ===
                                const headerRx = /(例如(?:\s*[\(（]?\s*Ví dụ\s*[\)）]?)?\s*[:：]?|Ví dụ\s*[:：]?)/i;
                                const firstTagRx = /(男\s*[:：]|女\s*[:：]|问\s*[:：]|★|\s+[A-D]\s+|[\(（](?:ĐÚNG|SAI|✓|✕|v|x|√|N)[\)）])/i;

                                const headerMatch = text.match(headerRx);
                                const firstTagMatch = text.match(firstTagRx);

                                // Không có gì đặc biệt
                                if (!headerMatch && !firstTagMatch) {
                                    return { mainText: text, hasExample: false, exampleHtml: '' };
                                }

                                let mainText = '';
                                let exampleRaw = text;

                                if (headerMatch) {
                                    mainText = text.substring(0, headerMatch.index).trim();
                                    exampleRaw = text.substring(headerMatch.index).trim();
                                } else if (firstTagMatch) {
                                    mainText = text.substring(0, firstTagMatch.index).trim();
                                    exampleRaw = text.substring(firstTagMatch.index).trim();
                                }

                                // === BƯỚC 2: Tách 例如 header ra khỏi phần nội dung ví dụ ===
                                let exHeader = '';
                                let exBody = exampleRaw;
                                const hm = exampleRaw.match(headerRx);
                                if (hm && hm.index === 0) {
                                    exHeader = exampleRaw.substring(0, hm.index + hm[0].length).trim();
                                    exBody = exampleRaw.substring(hm.index + hm[0].length).trim();
                                }

                                // === BƯỚC 3: Xử lý tách dòng trên PLAIN TEXT (exBody) trước khi chèn HTML ===
                                // Tách tại: 女：/男：, 问：, ★, A/B/C/D đứng trước nội dung, (ĐÚNG)/(SAI)
                                let parts = [exBody];

                                // Tách theo lượt thoại nhân vật và câu hỏi
                                let splitRx = /(女\s*[:：]|男\s*[:：]|问\s*[:：]|★)/g;
                                let combined = exBody;
                                // Chèn ký tự phân tách trước mỗi keyword
                                combined = combined.replace(/(.)(\s*)(女\s*[:：]|男\s*[:：])/g, '$1\n$3');
                                combined = combined.replace(/(.)(\s*)(问\s*[:：])/g, '$1\n$3');
                                combined = combined.replace(/(.)(\s*)(★)/g, '$1\n$3');
                                combined = combined.replace(/\s+([A-D])\s+/g, '\n$1 ');

                                // Tách các dòng
                                const lines = combined.split('\n').map(l => l.trim()).filter(l => l.length > 0);

                                // === BƯỚC 4: Render từng dòng thành HTML ===
                                let htmlLines = lines.map((line, i) => {
                                    // Đánh dấu (ĐÚNG)/(SAI)/(✓)/(✕)
                                    line = line
                                        .replace(/([\(（](?:ĐÚNG|✓|v|√)[\)）])/gi, '<span class="inline-block bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-extrabold px-2 py-0.5 rounded-md text-xs ml-1">$1</span>')
                                        .replace(/([\(（](?:SAI|✕|x|N)[\)）])/gi, '<span class="inline-block bg-red-500/15 text-red-600 dark:text-red-400 font-extrabold px-2 py-0.5 rounded-md text-xs ml-1">$1</span>');

                                    // Đáp án A / B / C / D đứng đầu dòng
                                    const answerMatch = line.match(/^([A-D])\s+(.*)/s);
                                    if (answerMatch) {
                                        return '<div class="flex items-start gap-2 mt-1.5"><span class="shrink-0 inline-flex items-center justify-center w-5 h-5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-extrabold text-slate-700 dark:text-slate-200">' + answerMatch[1] + '</span><span>' + answerMatch[2] + '</span></div>';
                                    }

                                    // Câu hỏi 问：
                                    const wenMatch = line.match(/^(问\s*[:：])(.*)/s);
                                    if (wenMatch) {
                                        return '<div class="mt-2"><span class="font-bold text-primary">' + wenMatch[1] + '</span>' + wenMatch[2] + '</div>';
                                    }

                                    // Ngôi sao ★
                                    const starMatch = line.match(/^(★\s*)(.*)/s);
                                    if (starMatch) {
                                        return '<div class="mt-1.5"><span class="text-amber-500 font-bold">★</span> ' + starMatch[2] + '</div>';
                                    }

                                    // Lượt thoại 女：/男：
                                    const dialogMatch = line.match(/^(女\s*[:：]|男\s*[:：])(.*)/s);
                                    if (dialogMatch) {
                                        return '<div class="mt-1.5"><span class="font-bold text-slate-700 dark:text-slate-200">' + dialogMatch[1] + '</span>' + dialogMatch[2] + '</div>';
                                    }

                                    // Dòng thường (phần đầu tiên sau 例如)
                                    return (i === 0 ? '' : '<div class="mt-1">') + line + (i === 0 ? '' : '</div>');
                                });

                                // === BƯỚC 5: Ghép badge 例如 + nội dung ===
                                let exHeaderHtml = exHeader
                                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[11px] mr-1 align-middle">' + exHeader + '</span>'
                                    : '';

                                const formattedBody = exHeaderHtml + htmlLines.join('');

                                return {
                                    mainText: mainText || '',
                                    hasExample: true,
                                    exampleHtml: formattedBody
                                };
                            };
                        </script>


