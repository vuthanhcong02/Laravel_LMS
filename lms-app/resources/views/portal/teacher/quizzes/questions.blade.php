@extends('portal.layouts.dashboard')

@section('title', __('Quản lý câu hỏi'))

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 overflow-y-auto w-full custom-scrollbar"
          x-data="questionBuilder({
              initialQuestions: {{ \Illuminate\Support\Js::from(collect(old('questions', $quiz->questions))->map(function($q, $index) use ($quiz) {
                  // Nếu là dữ liệu từ database (Eloquent Model)
                  if ($q instanceof \App\Models\Question) {
                      return [
                          'id' => $q->id,
                          'type' => $q->type->value,
                          'question_text' => $q->question_text,
                          'marks' => $q->marks,
                          'image_url' => $q->image_path ? asset('storage/' . $q->image_path) : null,
                          'audio_url' => $q->audio_path ? asset('storage/' . $q->audio_path) : null,
                          'options' => $q->options->map(fn($o) => [
                              'id' => $o->id,
                              'option_text' => $o->option_text,
                              'is_correct' => (bool)$o->is_correct
                          ])
                      ];
                  }
                  
                  // Nếu là dữ liệu từ old() (Mảng)
                  $dbQuestion = isset($q['id']) ? $quiz->questions->where('id', $q['id'])->first() : null;
                  return [
                      'id' => $q['id'] ?? null,
                      'type' => $q['type'] ?? 'multiple_choice',
                      'question_text' => $q['question_text'] ?? '',
                      'marks' => $q['marks'] ?? 1,
                      'image_url' => $dbQuestion && $dbQuestion->image_path ? asset('storage/' . $dbQuestion->image_path) : null,
                      'audio_url' => $dbQuestion && $dbQuestion->audio_path ? asset('storage/' . $dbQuestion->audio_path) : null,
                      'options' => $q['options'] ?? []
                  ];
              })) }},
              validationErrors: {{ \Illuminate\Support\Js::from($errors->getMessages()) }}
          })">

        {{-- ═══════════════════════════════════════════════
             Sticky Header — Ra ngoài max-w để full-width
             ═══════════════════════════════════════════════ --}}
        <div class="sticky top-0 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 px-6 lg:px-8 space-y-3 pb-3 pt-5">

            {{-- Title + Action buttons --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('teacher.quizzes.index') }}" class="size-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors shrink-0">
                        <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                            {{ __('Câu hỏi bài thi') }}: <span class="text-primary">{{ $quiz->title }}</span>
                        </h1>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Xóa tất cả câu hỏi --}}
                    <button type="button"
                            x-show="questions.length > 0"
                            @click="clearAllQuestions()"
                            class="px-4 py-2.5 bg-red-50 hover:bg-red-500 hover:text-white text-red-500 rounded-xl font-black flex items-center justify-center gap-2 transition-all text-sm border border-red-100 hover:border-red-500">
                        <span class="material-symbols-outlined text-lg">delete_sweep</span>
                        {{ __('Xóa tất cả') }}
                    </button>
                    <button type="button" @click="addQuestion()" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-black flex items-center justify-center gap-2 transition-all text-sm">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        {{ __('Thêm câu hỏi') }}
                    </button>
                    <button type="button" @click="submitForm()" class="px-4 py-2.5 bg-primary hover:bg-blue-600 text-white rounded-xl font-black flex items-center justify-center gap-2 transition-all text-sm">
                        <span class="material-symbols-outlined text-lg">save</span>
                        {{ __('Lưu tất cả') }}
                    </button>
                </div>
            </div>

            {{-- ────────────────────────────────────────────────────────────
                 Navigator bar — flex-wrap, xuống dòng khi đầy
                 ──────────────────────────────────────────────────────────── --}}
            <div x-show="questions.length > 0" class="border-t border-slate-100 dark:border-slate-800 pt-2.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap shrink-0">{{ __('Nhanh:') }}</span>
                    <template x-for="(q, i) in questions" :key="i">
                        <button :id="'nav-item-' + i"
                                @click="scrollToQuestion(i)"
                                type="button"
                                class="size-7 rounded-md flex items-center justify-center font-black text-[11px] transition-all border-2"
                                :class="activeQuestion === i ? 'bg-primary text-white border-primary shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 border-transparent hover:border-primary/30'">
                            <span x-text="i + 1"></span>
                        </button>
                    </template>
                    <span class="text-[10px] font-black text-slate-400 whitespace-nowrap ml-1" x-text="`(${questions.length} câu)`"></span>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="max-w-5xl mx-auto px-6 lg:px-8 space-y-8 py-8">

            <x-flash-message />

            <!-- Questions List -->
            <form id="questionsForm" action="{{ route('teacher.quizzes.questions.update', $quiz->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-40">
                @csrf
                @method('PUT')

                <template x-for="(question, index) in questions" :key="index">
                    <div :id="'question-' + index" class="bg-white dark:bg-slate-900 rounded-[2.5rem] border-2 border-slate-100 dark:border-slate-800 overflow-hidden group transition-all hover:border-primary/30 scroll-mt-24">
                        <!-- Question Header -->
                        <div class="bg-slate-50/50 dark:bg-slate-800/50 p-6 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-4">
                                <span class="size-10 rounded-2xl bg-primary text-white flex items-center justify-center font-black text-lg" x-text="index + 1"></span>
                                <select :name="`questions[${index}][type]`" x-model="question.type" class="rounded-xl border-none bg-white dark:bg-slate-800 text-sm font-black text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-primary h-10 px-4 min-w-[160px]">
                                    <option value="multiple_choice">{{ __('Trắc nghiệm') }}</option>
                                    <option value="true_false">{{ __('Đúng / Sai') }}</option>
                                    <option value="essay">{{ __('Tự luận') }}</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2 bg-white dark:bg-slate-800 px-4 h-10 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('Điểm') }}</span>
                                    <input type="number" :name="`questions[${index}][marks]`" x-model="question.marks" step="0.5" min="0.5" class="w-16 border-none bg-transparent p-0 text-center font-black text-primary focus:ring-0">
                                </div>
                                <template x-if="$errors && $errors.has(`questions.${index}.marks`)">
                                    <p class="text-[10px] text-red-500 font-bold" x-text="$errors.first(`questions.${index}.marks`)"></p>
                                </template>
                                <button type="button" @click="removeQuestion(index)" class="size-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </div>

                        <!-- Question Body -->
                        <div class="p-8 space-y-8">
                            <input type="hidden" :name="`questions[${index}][id]`" x-model="question.id">
                            
                            <!-- Question Text -->
                            <div class="space-y-3">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                                    {{ __('Nội dung câu hỏi') }}
                                </label>
                                <textarea :name="`questions[${index}][question_text]`" x-model="question.question_text" rows="3" class="w-full rounded-[1.5rem] border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-5 text-base font-bold text-slate-700 dark:text-white focus:border-primary focus:ring-primary shadow-inner" placeholder="{{ __('Nhập câu hỏi tại đây...') }}" required></textarea>
                                <template x-if="validationErrors[`questions.${index}.question_text`]">
                                    <p class="text-xs text-red-500 font-bold mt-1" x-text="validationErrors[`questions.${index}.question_text`][0]"></p>
                                </template>
                            </div>

                            <!-- Media Section -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-50/30 dark:bg-slate-800/20 p-6 rounded-[2rem] border border-slate-100/50 dark:border-slate-800/50">
                                <!-- Image Upload -->
                                <div class="space-y-3">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">image</span>
                                        {{ __('Hình ảnh minh họa') }}
                                    </label>
                                    <div class="relative group/img">
                                        <input type="file" :name="`questions[${index}][image]`" accept="image/*" @change="previewImage($event, index)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="h-32 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center bg-white dark:bg-slate-800 transition-colors group-hover/img:border-primary">
                                            <template x-if="!question.image_url">
                                                <div class="text-center">
                                                    <span class="material-symbols-outlined text-3xl text-slate-300">add_photo_alternate</span>
                                                    <p class="text-[10px] font-black text-slate-400 mt-1 uppercase">{{ __('Tải ảnh lên') }}</p>
                                                </div>
                                            </template>
                                            <template x-if="question.image_url">
                                                <div class="relative h-full w-full">
                                                    <img :src="question.image_url" class="h-full w-full object-contain p-2">
                                                    <button type="button" @click.stop="question.image_url = null; $el.closest('.group/img').querySelector('input').value = ''" class="absolute top-1 right-1 size-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors z-20">
                                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <!-- Audio Upload -->
                                <div class="space-y-3">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">graphic_eq</span>
                                        {{ __('Âm thanh (Audio)') }}
                                    </label>
                                    <div class="relative group/audio">
                                        <input type="file" :name="`questions[${index}][audio]`" accept="audio/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="h-32 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center bg-white dark:bg-slate-800 transition-colors group-hover/audio:border-primary">
                                            <template x-if="!question.audio_url">
                                                <div class="text-center">
                                                    <span class="material-symbols-outlined text-3xl text-slate-300">audio_file</span>
                                                    <p class="text-[10px] font-black text-slate-400 mt-1 uppercase">{{ __('Tải audio') }}</p>
                                                </div>
                                            </template>
                                            <template x-if="question.audio_url">
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="material-symbols-outlined text-3xl text-emerald-500">check_circle</span>
                                                    <p class="text-[10px] font-black text-emerald-600 uppercase">{{ __('Đã có audio') }}</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Options Section (Hidden for Essay) -->
                            <template x-if="question.type !== 'essay'">
                                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">checklist</span>
                                            {{ __('Danh sách đáp án') }}
                                        </label>
                                        <button type="button" @click="addOption(index)" class="text-xs font-black text-primary hover:text-blue-700 flex items-center gap-1 transition-colors">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                            {{ __('Thêm lựa chọn') }}
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <template x-for="(option, optIndex) in question.options" :key="optIndex">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 group/opt hover:border-primary/50 transition-all">
                                                    <input type="hidden" :name="`questions[${index}][options][${optIndex}][id]`" x-model="option.id">
                                                    
                                                    <!-- Correct Switch -->
                                                    <label class="relative inline-flex items-center cursor-pointer" @click.stop="toggleCorrect(index, optIndex)">
                                                        <input type="hidden" :name="`questions[${index}][options][${optIndex}][is_correct]`" :value="option.is_correct ? 1 : 0">
                                                        <div class="size-6 rounded-lg transition-all flex items-center justify-center border-2"
                                                             :class="option.is_correct ? 'bg-emerald-50 border-emerald-500 shadow-sm' : 'bg-slate-100 dark:bg-slate-700 border-transparent'">
                                                            <span class="material-symbols-outlined text-sm font-black transition-all"
                                                                  :class="option.is_correct ? 'scale-100 text-emerald-500' : 'scale-0 text-transparent'">check</span>
                                                        </div>
                                                    </label>

                                                    <input type="text" :name="`questions[${index}][options][${optIndex}][option_text]`" x-model="option.option_text" class="flex-1 border-none bg-transparent p-0 text-sm font-bold text-slate-700 dark:text-white focus:ring-0" placeholder="{{ __('Nhập đáp án...') }}">
                                                    
                                                    <button type="button" @click="removeOption(index, optIndex)" class="text-slate-400 hover:text-red-500 opacity-0 group-hover/opt:opacity-100 transition-all">
                                                        <span class="material-symbols-outlined text-sm">close</span>
                                                    </button>
                                                </div>
                                                <template x-if="validationErrors[`questions.${index}.options.${optIndex}.option_text`]">
                                                    <p class="text-[10px] text-red-500 font-bold ml-4" x-text="validationErrors[`questions.${index}.options.${optIndex}.option_text`][0]"></p>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Bottom Actions (Visible when multiple questions exist) -->
                <div x-show="questions.length >= 2" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     class="flex flex-col sm:flex-row items-center justify-center gap-4 py-12 border-t border-dashed border-slate-200 dark:border-slate-800 mt-8">
                    <button type="button" @click="addQuestion()" class="min-w-[150px] px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-black flex items-center justify-center gap-2 transition-all text-sm">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        {{ __('Thêm câu hỏi') }}
                    </button>
                    <button type="button" @click="submitForm()" class="min-w-[150px] px-5 py-2.5 bg-primary hover:bg-blue-600 text-white rounded-xl font-black flex items-center justify-center gap-2 transition-all text-sm">
                        <span class="material-symbols-outlined text-lg">save</span>
                        {{ __('Lưu tất cả') }}
                    </button>
                </div>

                <!-- Empty State -->
                <div x-show="questions.length === 0" class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-20 text-center border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <div class="size-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-4xl text-slate-300">post_add</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2">{{ __('Chưa có câu hỏi nào') }}</h3>
                    <p class="text-slate-500 font-bold mb-8">{{ __('Bắt đầu nhấn nút bên dưới để thêm câu hỏi đầu tiên.') }}</p>
                    <button type="button" @click="addQuestion()" class="min-w-[180px] px-8 py-3 bg-primary hover:bg-blue-600 text-white rounded-2xl font-black flex items-center justify-center gap-2 mx-auto transition-all">
                        <span class="material-symbols-outlined">add</span>
                        {{ __('Thêm câu hỏi ngay') }}
                    </button>
                </div>
            </form>
        </div>
    </main>

    <style>
        .custom-scrollbar-h::-webkit-scrollbar { height: 4px; }
        .custom-scrollbar-h::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar-h::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .custom-scrollbar-h::-webkit-scrollbar-thumb { background: #334155; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @push('scripts')
        <script>
            window.QuizBuilderConfig = {
                confirmRemoveQuestion: '{{ __('Bạn có chắc chắn muốn xóa câu hỏi này?') }}',
                confirmClearAll: '{{ __('Bạn có chắc chắn muốn xóa TẤT CẢ câu hỏi? Hành động này không thể hoàn tác!') }}'
            };
        </script>
        @vite('resources/js/quiz-builder.js')
    @endpush
@endsection
