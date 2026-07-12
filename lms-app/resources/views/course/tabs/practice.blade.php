                        <div x-show="activeTab === 'practice'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                            <!-- Empty State Practice -->
                            <template x-if="(!currentLesson?.practices || currentLesson?.practices.length === 0)">
                                <div class="flex flex-col items-center justify-center py-20 px-4 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl text-center mt-2 max-w-2xl mx-auto">
                                    <div class="w-20 h-20 bg-white dark:bg-slate-800 shadow-sm rounded-full flex items-center justify-center mb-5 border border-slate-100 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-500">draw</span>
                                    </div>
                                    <h4 class="text-base font-black text-slate-800 dark:text-white mb-2">Chưa có bài tập thực hành</h4>
                                    <p class="text-xs text-slate-500 max-w-md font-medium leading-relaxed">Nội dung thực hành cho bài học này đang được biên soạn và sẽ sớm ra mắt. Vui lòng quay lại sau.</p>
                                </div>
                            </template>

                            <template x-if="currentLesson?.practices && currentLesson?.practices.length > 0">
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-left">
                                    
                                    <!-- Practice Main Content (Left column) -->
                                    <div class="lg:col-span-3">
                                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-150/80 dark:border-slate-800/80 p-5 shadow-sm flex flex-col h-[calc(100vh-180px)] overflow-y-auto custom-scrollbar relative">
                                        <!-- Header & Tabs -->
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 dark:border-slate-800/60 pb-5 mb-5 text-left">
                                            <div class="flex gap-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                                                <button 
                                                    class="px-5 py-2 rounded-lg font-bold text-sm transition-all duration-200 flex items-center gap-2"
                                                    :class="practiceTab === 'listening' ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'listening'; practiceSectionIdx = 0"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">headphones</span> Phần Nghe
                                                </button>
                                                <button 
                                                    class="px-5 py-2 rounded-lg font-bold text-sm transition-all duration-200 flex items-center gap-2"
                                                    :class="practiceTab === 'reading' ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'reading'; practiceSectionIdx = 0"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">menu_book</span> Phần Đọc
                                                </button>
                                                <button 
                                                    class="px-5 py-2 rounded-lg font-bold text-sm transition-all duration-200 flex items-center gap-2"
                                                    :class="practiceTab === 'writing' ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary-light'"
                                                    @click="practiceTab = 'writing'; practiceSectionIdx = 0"
                                                    x-show="currentLesson?.practices?.find(p => p.type === 'writing')"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]">edit_document</span> Phần Viết
                                                </button>
                                            </div>


                                        </div>

                                        <!-- Listening Tab Content -->
                                        <div x-show="practiceTab === 'listening'" x-transition class="space-y-6">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'listening')">
                                                <div>
                                                    <!-- Global Audio for Listening -->
                                                    <template x-if="currentLesson?.practices?.find(p => p.type === 'listening')?.audio_path">
                                                        <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 mb-6 flex flex-col gap-3">
                                                            <h5 class="text-sm font-extrabold text-primary dark:text-primary-light">Tệp Âm Thanh Bài Nghe (Toàn bộ)</h5>
                                                            <audio controls class="w-full h-10 rounded-full" :src="'/storage/hsk_media/' + currentLesson?.practices?.find(p => p.type === 'listening')?.audio_path"></audio>
                                                        </div>
                                                    </template>

                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'listening')?.sections || [])" :key="sIdx">
                                                        <div class="space-y-4" x-show="practiceSectionIdx === sIdx">
                                                            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-805/60 border-l-4 border-l-primary">
                                                                <h4 class="text-sm font-black text-slate-800 dark:text-white font-chinese tracking-wider" x-text="sect.section_han"></h4>
                                                                <p class="text-xs text-primary italic mt-1" x-show="sect.section_vi" x-text="sect.section_vi"></p>
                                                            </div>
                                                            <template x-if="sect.audio_path">
                                                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4 mt-4 flex flex-col gap-2">
                                                                    <div class="text-sm font-bold text-blue-700 dark:text-blue-400" x-text="'Nghe đoạn hội thoại phần ' + (sIdx + 1) + ':'"></div>
                                                                    <audio controls class="w-full h-10 rounded-full" :src="'/storage/hsk_media/' + sect.audio_path"></audio>
                                                                </div>
                                                            </template>

                                                    <div class="flex flex-col gap-4">
                                                        <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                                                                                                                                                                                                                                                                                                    <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-105 dark:border-slate-800 p-5 rounded-2xl space-y-4 text-left">
                                                                      
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
                                                                                          <span x-html="segment"></span>
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

                                                                              <div class="flex justify-end pt-2 mt-4 border-t border-slate-200 dark:border-slate-700">
                                                                                  <button 
                                                                                      class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5"
                                                                                      :disabled="quiz.sub_questions.some(sq => !sq.selected_option)"
                                                                                      :class="quiz.sub_questions.every(sq => sq.selected_option) ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                                      x-show="quiz.sub_questions.some(sq => !sq.answered)"
                                                                                      @click="quiz.sub_questions.forEach(sq => sq.answered = true)"
                                                                                  >
                                                                                      <span class="material-symbols-outlined text-[16px]">task_alt</span> Xác nhận toàn bộ
                                                                                  </button>
                                                                              </div>
                                                                          </div>
                                                                      </template>

                                                                      <template x-if="quiz.ques_type !== 'fill_blank_dropdrag'">
                                                                          <div class="space-y-4">
                                                                              <template x-if="quiz.context">
                                                                                  <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl mb-4 text-slate-700 dark:text-slate-300 font-chinese text-base leading-relaxed" x-text="quiz.context"></div>
                                                                              </template>
                                                                              
                                                                              <template x-if="!quiz.sub_questions || quiz.sub_questions.length === 0">
                                                                                  <div>
                                                                                      <h5 class="text-sm font-extrabold text-slate-800 dark:text-white flex items-start gap-2">
                                                                                          <span class="shrink-0 text-slate-400" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1)) + '.'"></span>
                                                                                          <template x-if="quiz.question">
                                                                                              <span x-text="quiz.question" class="font-chinese tracking-wide text-base"></span>
                                                                                          </template>
                                                                                      </h5>

                                                                                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                                                                          <template x-for="(opt, oIdx) in quiz.options" :key="oIdx">
                                                                                              <button 
                                                                                                  class="text-left p-3.5 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group"
                                                                                                  :class="quiz.answered 
                                                                                                      ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                         (quiz.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-white dark:bg-slate-900 text-slate-400 border-slate-200 opacity-60'))
                                                                                                      : (quiz.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10' : 'bg-white dark:bg-slate-805 text-slate-700 dark:text-slate-305 border-slate-200 hover:bg-slate-50 hover:border-slate-300')"
                                                                                                  @click="if(!quiz.answered) quiz.selected = oIdx"
                                                                                                  :disabled="quiz.answered"
                                                                                              >
                                                                                                  <span class="group-hover:translate-x-1 transition-transform font-chinese text-base" x-text="opt.text || opt"></span>
                                                                                                  <template x-if="quiz.answered">
                                                                                                      <div class="flex items-center gap-2">
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

                                                                                      <div class="flex justify-end pt-2 mt-4 border-t border-slate-100 dark:border-slate-800" x-show="!quiz.answered">
                                                                                          <button 
                                                                                              class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5"
                                                                                              :disabled="quiz.selected === null"
                                                                                              :class="quiz.selected !== null ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                                              @click="quiz.answered = true"
                                                                                          >
                                                                                              <span class="material-symbols-outlined text-[16px]">task_alt</span> Xác nhận đáp án
                                                                                          </button>
                                                                                      </div>
                                                                                  </div>
                                                                              </template>
                                                                              
                                                                              <template x-if="quiz.sub_questions && quiz.sub_questions.length > 0">
                                                                                  <div class="space-y-4">
                                                                                      <template x-for="(sq, sqIdx) in quiz.sub_questions" :key="sqIdx">
                                                                                          <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 rounded-xl">
                                                                                              <h6 class="text-sm font-bold text-slate-800 dark:text-white flex items-start gap-2 mb-3">
                                                                                                  <span class="shrink-0 text-primary" x-text="'Câu ' + (sq.ques_id || (sqIdx + 1)) + '.'"></span>
                                                                                                  <span x-text="sq.question" class="font-chinese text-[15px]"></span>
                                                                                              </h6>
                                                                                              
                                                                                              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                                                                  <template x-for="(opt, oIdx) in sq.options" :key="oIdx">
                                                                                                      <button 
                                                                                                          class="text-left p-3 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group"
                                                                                                          :class="sq.answered 
                                                                                                              ? ((opt.text || opt) == sq.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                                 (sq.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-slate-50 dark:bg-slate-800 text-slate-400 border-slate-200 opacity-60'))
                                                                                                              : (sq.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 hover:bg-white hover:border-slate-300')"
                                                                                                          @click="if(!sq.answered) sq.selected = oIdx"
                                                                                                          :disabled="sq.answered"
                                                                                                      >
                                                                                                          <span class="group-hover:translate-x-1 transition-transform font-chinese text-base" x-text="opt.text || opt"></span>
                                                                                                          <template x-if="sq.answered">
                                                                                                              <div class="flex items-center gap-2">
                                                                                                                  <template x-if="(opt.text || opt) == sq.correct_answer">
                                                                                                                      <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                                                  </template>
                                                                                                                  <template x-if="sq.selected === oIdx && (opt.text || opt) != sq.correct_answer">
                                                                                                                      <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                                                  </template>
                                                                                                              </div>
                                                                                                          </template>
                                                                                                      </button>
                                                                                                  </template>
                                                                                              </div>

                                                                                              <div class="flex justify-end pt-2 mt-4 border-t border-slate-100 dark:border-slate-700" x-show="!sq.answered">
                                                                                                  <button 
                                                                                                      class="px-4 py-2 bg-primary hover:bg-primary/95 text-white font-extrabold text-[11px] rounded-lg shadow-sm transition-all active:scale-95 flex items-center gap-1"
                                                                                                      :disabled="sq.selected === null"
                                                                                                      :class="sq.selected !== null ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                                                      @click="sq.answered = true"
                                                                                                  >
                                                                                                      <span class="material-symbols-outlined text-[14px]">task_alt</span> Xác nhận
                                                                                                  </button>
                                                                                              </div>
                                                                                          </div>
                                                                                      </template>
                                                                                  </div>
                                                                              </template>
                                                                          </div>
                                                                      </template>
                                                                  </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Reading Tab Content -->
                                        <div x-show="practiceTab === 'reading'" x-transition class="space-y-6 pt-2">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'reading')">
                                                <div>
                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'reading')?.sections || [])" :key="sIdx">
                                                        <div class="space-y-4" x-show="practiceSectionIdx === sIdx">
                                                            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-805/60 border-l-4 border-l-primary">
                                                                <h4 class="text-sm font-black text-slate-800 dark:text-white font-chinese tracking-wider" x-text="sect.section_han"></h4>
                                                                <p class="text-xs text-primary dark:text-primary-light italic mt-1" x-text="sect.section_vi"></p>
                                                            </div>

                                                            <div class="flex flex-col gap-4">
                                                                <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                                                                                                                                                                                                                                                                                                            <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-105 dark:border-slate-800 p-5 rounded-2xl space-y-4 text-left">
                                                                      
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
                                                                                          <span x-html="segment"></span>
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

                                                                              <div class="flex justify-end pt-2 mt-4 border-t border-slate-200 dark:border-slate-700">
                                                                                  <button 
                                                                                      class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5"
                                                                                      :disabled="quiz.sub_questions.some(sq => !sq.selected_option)"
                                                                                      :class="quiz.sub_questions.every(sq => sq.selected_option) ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                                      x-show="quiz.sub_questions.some(sq => !sq.answered)"
                                                                                      @click="quiz.sub_questions.forEach(sq => sq.answered = true)"
                                                                                  >
                                                                                      <span class="material-symbols-outlined text-[16px]">task_alt</span> Xác nhận toàn bộ
                                                                                  </button>
                                                                              </div>
                                                                          </div>
                                                                      </template>

                                                                      <template x-if="quiz.ques_type !== 'fill_blank_dropdrag'">
                                                                          <div class="space-y-4">
                                                                              <template x-if="quiz.context">
                                                                                  <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl mb-4 text-slate-700 dark:text-slate-300 font-chinese text-base leading-relaxed" x-text="quiz.context"></div>
                                                                              </template>
                                                                              
                                                                              <template x-if="!quiz.sub_questions || quiz.sub_questions.length === 0">
                                                                                  <div>
                                                                                      <h5 class="text-sm font-extrabold text-slate-800 dark:text-white flex items-start gap-2">
                                                                                          <span class="shrink-0 text-slate-400" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1)) + '.'"></span>
                                                                                          <template x-if="quiz.question">
                                                                                              <span x-text="quiz.question" class="font-chinese tracking-wide text-base"></span>
                                                                                          </template>
                                                                                      </h5>

                                                                                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                                                                          <template x-for="(opt, oIdx) in quiz.options" :key="oIdx">
                                                                                              <button 
                                                                                                  class="text-left p-3.5 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group"
                                                                                                  :class="quiz.answered 
                                                                                                      ? ((opt.text || opt) == quiz.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                         (quiz.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-white dark:bg-slate-900 text-slate-400 border-slate-200 opacity-60'))
                                                                                                      : (quiz.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10' : 'bg-white dark:bg-slate-805 text-slate-700 dark:text-slate-305 border-slate-200 hover:bg-slate-50 hover:border-slate-300')"
                                                                                                  @click="if(!quiz.answered) quiz.selected = oIdx"
                                                                                                  :disabled="quiz.answered"
                                                                                              >
                                                                                                  <span class="group-hover:translate-x-1 transition-transform font-chinese text-base" x-text="opt.text || opt"></span>
                                                                                                  <template x-if="quiz.answered">
                                                                                                      <div class="flex items-center gap-2">
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

                                                                                      <div class="flex justify-end pt-2 mt-4 border-t border-slate-100 dark:border-slate-800" x-show="!quiz.answered">
                                                                                          <button 
                                                                                              class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5"
                                                                                              :disabled="quiz.selected === null"
                                                                                              :class="quiz.selected !== null ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                                              @click="quiz.answered = true"
                                                                                          >
                                                                                              <span class="material-symbols-outlined text-[16px]">task_alt</span> Xác nhận đáp án
                                                                                          </button>
                                                                                      </div>
                                                                                  </div>
                                                                              </template>
                                                                              
                                                                              <template x-if="quiz.sub_questions && quiz.sub_questions.length > 0">
                                                                                  <div class="space-y-4">
                                                                                      <template x-for="(sq, sqIdx) in quiz.sub_questions" :key="sqIdx">
                                                                                          <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 rounded-xl">
                                                                                              <h6 class="text-sm font-bold text-slate-800 dark:text-white flex items-start gap-2 mb-3">
                                                                                                  <span class="shrink-0 text-primary" x-text="'Câu ' + (sq.ques_id || (sqIdx + 1)) + '.'"></span>
                                                                                                  <span x-text="sq.question" class="font-chinese text-[15px]"></span>
                                                                                              </h6>
                                                                                              
                                                                                              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                                                                  <template x-for="(opt, oIdx) in sq.options" :key="oIdx">
                                                                                                      <button 
                                                                                                          class="text-left p-3 rounded-xl border text-sm font-bold transition-all flex justify-between items-center group"
                                                                                                          :class="sq.answered 
                                                                                                              ? ((opt.text || opt) == sq.correct_answer ? 'bg-emerald-500/10 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-extrabold shadow-sm' : 
                                                                                                                 (sq.selected === oIdx ? 'bg-red-500/10 border-red-500 text-red-600 dark:text-red-400' : 'bg-slate-50 dark:bg-slate-800 text-slate-400 border-slate-200 opacity-60'))
                                                                                                              : (sq.selected === oIdx ? 'bg-primary/10 border-primary text-primary font-extrabold shadow-sm shadow-primary/10' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 hover:bg-white hover:border-slate-300')"
                                                                                                          @click="if(!sq.answered) sq.selected = oIdx"
                                                                                                          :disabled="sq.answered"
                                                                                                      >
                                                                                                          <span class="group-hover:translate-x-1 transition-transform font-chinese text-base" x-text="opt.text || opt"></span>
                                                                                                          <template x-if="sq.answered">
                                                                                                              <div class="flex items-center gap-2">
                                                                                                                  <template x-if="(opt.text || opt) == sq.correct_answer">
                                                                                                                      <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                                                                                                                  </template>
                                                                                                                  <template x-if="sq.selected === oIdx && (opt.text || opt) != sq.correct_answer">
                                                                                                                      <span class="material-symbols-outlined text-[18px] text-red-500">cancel</span>
                                                                                                                  </template>
                                                                                                              </div>
                                                                                                          </template>
                                                                                                      </button>
                                                                                                  </template>
                                                                                              </div>

                                                                                              <div class="flex justify-end pt-2 mt-4 border-t border-slate-100 dark:border-slate-700" x-show="!sq.answered">
                                                                                                  <button 
                                                                                                      class="px-4 py-2 bg-primary hover:bg-primary/95 text-white font-extrabold text-[11px] rounded-lg shadow-sm transition-all active:scale-95 flex items-center gap-1"
                                                                                                      :disabled="sq.selected === null"
                                                                                                      :class="sq.selected !== null ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                                                      @click="sq.answered = true"
                                                                                                  >
                                                                                                      <span class="material-symbols-outlined text-[14px]">task_alt</span> Xác nhận
                                                                                                  </button>
                                                                                              </div>
                                                                                          </div>
                                                                                      </template>
                                                                                  </div>
                                                                              </template>
                                                                          </div>
                                                                      </template>
                                                                  </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <div x-show="practiceTab === 'writing'" x-transition class="space-y-6 pt-2" style="display: none;">
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'writing')?.sections?.length === 0">
                                                <div class="text-center py-10 text-slate-500">Chưa có bài tập Viết</div>
                                            </template>
                                            <template x-if="currentLesson?.practices?.find(p => p.type === 'writing')?.sections?.length > 0">
                                                <div class="space-y-6">
                                                    <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === 'writing')?.sections || [])" :key="sIdx">
                                                        <div x-show="practiceSectionIdx === sIdx">
                                                            <!-- Section Header -->
                                                            <div class="bg-primary/5 border border-primary/20 rounded-xl p-4 mb-4">
                                                                <h4 class="font-bold text-primary mb-1 text-lg" x-text="sect.section_han"></h4>
                                                                <p class="text-sm text-slate-600 dark:text-slate-400" x-show="sect.section_vi" x-text="sect.section_vi"></p>
                                                            </div>

                                                            <template x-if="sect.image_path">
                                                                <div class="my-4 text-center border border-slate-100 dark:border-slate-800 rounded-2xl overflow-hidden bg-white dark:bg-slate-900 p-2 flex justify-center shadow-sm">
                                                                    <img :src="'/storage/hsk_media/' + sect.image_path" class="max-h-96 object-contain rounded-xl">
                                                                </div>
                                                            </template>

                                                            <div class="flex flex-col gap-4">
                                                                <template x-for="(quiz, qIdx) in sect.questions" :key="qIdx">
                                                                    <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-105 dark:border-slate-800 p-5 rounded-2xl space-y-4 text-left">
                                                                        <div class="flex items-start gap-3 mb-2">
                                                                            <div class="shrink-0 pt-0.5">
                                                                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-md text-xs font-black" x-text="'Câu ' + (quiz.ques_id || (qIdx + 1))"></span>
                                                                            </div>
                                                                            <div class="flex flex-col gap-1 flex-1">
                                                                                <template x-if="quiz.question">
                                                                                    <span x-text="quiz.question" class="font-chinese text-[17px] font-bold text-slate-800 dark:text-white leading-snug"></span>
                                                                                </template>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Textarea for Writing -->
                                                                        <div class="mt-4">
                                                                            <textarea 
                                                                                class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-[16px] font-chinese focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none min-h-[120px]"
                                                                                placeholder="Nhập câu trả lời của bạn..."
                                                                                x-model="quiz.userAnswer"
                                                                                :disabled="quiz.answered"
                                                                                @input="if(quiz.userAnswer.trim().length > 0) quiz.selected = 1; else quiz.selected = null;"
                                                                            ></textarea>
                                                                        </div>

                                                                        <template x-if="quiz.answered">
                                                                            <div class="mt-4 p-4 rounded-xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/20">
                                                                                <div class="font-bold text-sm text-emerald-700 dark:text-emerald-400 mb-2">Đáp án tham khảo:</div>
                                                                                <div class="font-chinese text-[16px] font-bold text-slate-800 dark:text-white leading-relaxed" x-text="quiz.correct_answer"></div>
                                                                                <template x-if="quiz.explain_vi">
                                                                                    <div class="mt-3 pt-3 border-t border-emerald-200/50 dark:border-emerald-800/50">
                                                                                        <div class="font-bold text-sm text-emerald-700 dark:text-emerald-400 mb-1">Giải thích:</div>
                                                                                        <div class="text-sm text-slate-600 dark:text-slate-300" x-html="quiz.explain_vi"></div>
                                                                                    </div>
                                                                                </template>
                                                                            </div>
                                                                        </template>

                                                                        <div class="flex justify-end pt-2 mt-4 border-t border-slate-100 dark:border-slate-800" x-show="!quiz.answered">
                                                                            <button 
                                                                                class="px-5 py-2.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5"
                                                                                :disabled="!quiz.userAnswer || quiz.userAnswer.trim() === ''"
                                                                                :class="quiz.userAnswer && quiz.userAnswer.trim() !== '' ? '' : 'opacity-50 !cursor-not-allowed grayscale'"
                                                                                @click="quiz.answered = true"
                                                                            >
                                                                                <span class="material-symbols-outlined text-[16px]">task_alt</span> Xem đáp án
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        </div>

                                    </div>

                                    <!-- Navigation Sidebar (Right column) -->
                                    <div class="lg:col-span-1 space-y-4 sticky top-6 self-start">
                                        <!-- Sidebar List -->
                                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-2xl p-4 text-left">
                                            <h5 class="text-sm font-black text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[18px] text-primary">route</span>
                                                <span>Điều hướng bài tập</span>
                                            </h5>

                                            <div class="flex flex-col space-y-1 relative before:content-[''] before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-px before:bg-slate-100 dark:before:bg-slate-800">
                                                <template x-for="(sect, sIdx) in (currentLesson?.practices?.find(p => p.type === practiceTab)?.sections || [])" :key="sIdx">
                                                    <button 
                                                        @click="practiceSectionIdx = sIdx"
                                                        class="group relative flex items-center gap-3 py-2 text-left z-10"
                                                    >
                                                        <div 
                                                            class="h-10 w-10 shrink-0 rounded-full flex items-center justify-center text-xs font-bold transition-all border-4 border-white dark:border-slate-900"
                                                            :class="practiceSectionIdx === sIdx ? 'bg-primary text-white shadow-md' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 group-hover:bg-slate-200 dark:group-hover:bg-slate-700'"
                                                        >
                                                            <span x-text="sIdx + 1"></span>
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <span 
                                                                class="text-sm font-bold transition-colors line-clamp-2"
                                                                :class="practiceSectionIdx === sIdx ? 'text-primary' : 'text-slate-500 dark:text-slate-400 group-hover:text-primary dark:group-hover:text-primary-light'"
                                                                x-text="sect.section_han"
                                                            ></span>
                                                            <span class="text-[10px] text-slate-400 font-medium line-clamp-1" x-show="sect.section_vi" x-text="sect.section_vi"></span>
                                                        </div>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>
