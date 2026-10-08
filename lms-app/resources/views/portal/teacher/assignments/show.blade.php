@extends('portal.layouts.dashboard')

@section('title', 'Chấm điểm bài tập')

@section('header')
    @include('portal.teacher.layouts.header')
@endsection

@section('sidebar')
    @include('portal.teacher.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full" x-data="gradingPanel({{ json_encode($assignment) }})">
        <div class="max-w-[1400px] mx-auto space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="flex items-center gap-4">
                    <a href="{{ route('teacher.assignments.index') }}" class="size-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white line-clamp-1 max-w-[600px]">{{ $assignment->title }}</h1>
                        <p class="text-slate-500 font-medium text-sm flex items-center gap-2">
                            <span>{{ __('Thống kê bài nộp') }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="text-primary font-bold" x-text="`${stats.graded}/${stats.total} {{ __('đã chấm') }}`"></span>
                        </p>
                    </div>
                </div>
            </div>

            <x-flash-message />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-lg font-bold">{{ __('Danh sách học viên đã nộp') }}</h3>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($submissions as $submission)
                                <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4"
                                     :class="selectedId === {{ $submission->id }} ? 'bg-blue-50/50 dark:bg-blue-900/20' : ''">
                                    <div class="flex items-center gap-4">
                                        <div class="size-12 rounded-full overflow-hidden bg-slate-100 shrink-0">
                                            <img src="{{ $submission->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($submission->user->first_name) }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white">{{ $submission->user->first_name }} {{ $submission->user->last_name }}</p>
                                            <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                                {{ __('Nộp lúc:') }} {{ $submission->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @if($submission->status === \App\Models\AssignmentSubmission::STATUS_GRADED)
                                            <div class="text-right">
                                                <p class="font-bold text-emerald-600 text-lg">{{ (float) $submission->score }}/10</p>
                                                <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider">{{ __('Đã chấm') }}</p>
                                            </div>
                                        @else
                                            <div class="text-right">
                                                <p class="font-bold text-orange-500 text-lg">---/10</p>
                                                <p class="text-[10px] text-orange-400 font-bold uppercase tracking-wider">{{ __('Chờ chấm') }}</p>
                                            </div>
                                        @endif
                                        <button @click="selectSubmission({{ json_encode($submission) }})" class="px-4 py-2 text-sm font-bold bg-primary text-white hover:bg-blue-600 rounded-xl transition-colors shadow-sm">
                                            {{ __('Chấm Bài') }}
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center text-slate-500">
                                    <span class="material-symbols-outlined text-4xl mb-3">sentiment_dissatisfied</span>
                                    <p class="font-bold">Chưa có học viên nào nộp bài.</p>
                                </div>
                            @endforelse
                        </div>
                        @if($submissions->hasPages())
                            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                                {{ $submissions->links('components.pagination') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sticky top-6">
                        <template x-if="!selected">
                            <div class="text-center py-12 text-slate-400">
                                <span class="material-symbols-outlined text-5xl mb-3 opacity-50">fact_check</span>
                                <p class="font-bold text-sm">{{ __('Chọn một học viên bên cạnh để xem file bài làm và tự động nhập điểm.') }}</p>
                            </div>
                        </template>

                        <template x-if="selected">
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">draw</span>
                                        {{ __('Chấm điểm chi tiết') }}
                                    </h3>
                                    <button @click="selected = null; selectedId = null" class="text-slate-400 hover:text-slate-600">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>

                                <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Bài làm của học viên:') }}</label>

                                    <template x-if="selected.attachments && selected.attachments.length > 0">
                                        <div class="space-y-2">
                                            <template x-for="file in selected.attachments" :key="file.path">
                                                <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-700 rounded-xl border border-slate-100 dark:border-slate-600 group">
                                                    <div class="flex items-center gap-3 overflow-hidden">
                                                        <span class="material-symbols-outlined text-slate-400" x-text="isImage(file.name) ? 'image' : (isAudio(file.name) ? 'audio_file' : (isPdf(file.name) ? 'picture_as_pdf' : 'description'))"></span>
                                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300 truncate" x-text="file.name"></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">

                                                        <template x-if="isImage(file.name) || isAudio(file.name) || isPdf(file.name)">
                                                            <button @click="openModal(file)" type="button" class="size-8 rounded-lg bg-slate-50 text-slate-400 hover:text-primary hover:bg-white flex items-center justify-center transition-all shadow-sm border border-slate-100 dark:border-slate-600">
                                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                            </button>
                                                        </template>
                                                        <a :href="getFileUrl(file.path)" target="_blank" class="size-8 rounded-lg bg-slate-50 text-slate-400 hover:text-primary flex items-center justify-center transition-all">
                                                            <span class="material-symbols-outlined text-[18px]">download</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!selected.attachments || selected.attachments.length === 0">
                                        <p class="text-sm text-slate-500 italic">{{ __('Không có tệp đính kèm.') }}</p>
                                    </template>
                                </div>

                                <form :action="`{{ url('portal/teacher/assignments') }}/${selected.assignment_id}/grade/${selected.id}`"
                                      method="POST"
                                      enctype="multipart/form-data"
                                      x-data="{ deleteExistingAudio: false }"
                                      class="space-y-5">
                                    @csrf
                                    <input type="hidden" name="delete_audio" :value="deleteExistingAudio ? 1 : 0">

                                    <div>
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Điểm (0-10)') }}</label>
                                        <input type="number" name="score" min="0" max="10" step="0.5" :value="selected.score" class="w-full mt-1 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary text-2xl font-bold text-center text-primary" required>
                                    </div>

                                    <div>
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-2">{{ __('Ghi âm nhận xét') }}</label>
                                        <template x-if="selected.teacher_audio_path && !deleteExistingAudio">
                                            <div class="mb-2 p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex items-center gap-2 border border-emerald-100">
                                                <audio :src="getFileUrl(selected.teacher_audio_path)" controls class="h-8 flex-1"></audio>
                                                <button type="button" @click="deleteExistingAudio = true" class="size-8 rounded-lg bg-white dark:bg-slate-800 text-red-500 hover:bg-red-50 flex items-center justify-center transition-all shadow-sm" title="Xóa đoạn ghi âm này">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </div>
                                        </template>
                                        <template x-if="deleteExistingAudio">
                                            <div class="mb-2 p-2 bg-red-50 dark:bg-red-900/20 rounded-xl flex items-center justify-between gap-2 border border-red-100">
                                                <span class="text-[10px] font-bold text-red-500 ml-2 italic">{{ __('Audio cũ sẽ được xóa khi bạn Lưu') }}</span>
                                                <button type="button" @click="deleteExistingAudio = false" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 underline px-2">{{ __('Hoàn tác') }}</button>
                                            </div>
                                        </template>
                                        <x-audio-recorder name="audio_feedback" />
                                    </div>

                                    <div>
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Nhận xét của Giáo viên') }}</label>
                                        <textarea name="teacher_feedback" rows="4" :value="selected.teacher_feedback" class="w-full mt-1 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 shadow-sm focus:border-primary focus:ring-primary text-sm font-medium" placeholder="{{ __('Khích lệ hoặc gợi ý cải thiện...') }}"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-bold shadow-sm transition-all text-sm flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">save</span>
                                        {{ __('Lưu kết quả chấm') }}
                                    </button>
                                </form>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
             style="display: none;">

            <div @click.away="closeModal()"
                 class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden relative border border-slate-200 dark:border-slate-800"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="scale-95 translate-y-4"
                 x-transition:enter-end="scale-100 translate-y-0">

                <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined" x-text="currentFile ? (isImage(currentFile.name) ? 'image' : (isAudio(currentFile.name) ? 'audio_file' : 'picture_as_pdf')) : ''"></span>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white" x-text="currentFile?.name"></h4>
                    </div>
                    <button @click="closeModal()" class="size-10 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-slate-500">close</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-800/20">
                    <template x-if="currentFile && isImage(currentFile.name)">
                        <img :src="getFileUrl(currentFile.path)" class="max-w-full max-h-[70vh] rounded-xl shadow-lg">
                    </template>

                    <template x-if="currentFile && isAudio(currentFile.name)">
                        <div class="p-12 bg-white dark:bg-slate-800 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700 flex flex-col items-center gap-6 w-full max-w-md">
                            <div class="size-20 bg-primary/10 rounded-full flex items-center justify-center text-primary animate-pulse">
                                <span class="material-symbols-outlined text-4xl">graphic_eq</span>
                            </div>
                            <audio :src="getFileUrl(currentFile.path)" controls class="w-full"></audio>
                        </div>
                    </template>

                    <template x-if="currentFile && isPdf(currentFile.name)">
                        <iframe :src="getFileUrl(currentFile.path) + '#toolbar=0'" class="w-full h-[70vh] rounded-xl border border-slate-200 dark:border-slate-700 bg-white" frameborder="0"></iframe>
                    </template>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                    <a :href="currentFile ? getFileUrl(currentFile.path) : '#'" target="_blank" class="px-6 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        Mở trong tab mới
                    </a>
                </div>
            </div>
        </div>
    </main>

<script>
function gradingPanel(assignment) {
    return {
        selected: null,
        selectedId: null,
        modalOpen: false,
        currentFile: null,
        stats: {
            graded: {{ $submissions->where('status', \App\Models\AssignmentSubmission::STATUS_GRADED)->count() }},
            total: {{ $submissions->count() }}
        },

        selectSubmission(sub) {
            this.selected = sub;
            this.selectedId = sub.id;
        },

        openModal(file) {
            this.currentFile = file;
            this.modalOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeModal() {
            this.modalOpen = false;
            // Stop audio if playing
            const audio = document.querySelector('.fixed audio');
            if (audio) {
                audio.pause();
                audio.currentTime = 0;
            }
            document.body.classList.remove('overflow-hidden');
        },

        getFileUrl(path) {
            return `{{ route('file.viewer') }}?path=${path}`;
        },

        isImage(name) {
            const ext = name.split('.').pop().toLowerCase();
            return ['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext);
        },

        isAudio(name) {
            const ext = name.split('.').pop().toLowerCase();
            return ['mp3', 'wav', 'ogg', 'webm', 'm4a'].includes(ext);
        },

        isPdf(name) {
            const ext = name.split('.').pop().toLowerCase();
            return ext === 'pdf';
        }
    }
}
</script>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
