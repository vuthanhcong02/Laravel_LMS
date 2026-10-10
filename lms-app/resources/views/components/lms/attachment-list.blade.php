@props([
    'attachments' => [],
    'variant' => 'badges', // 'badges' | 'cards'
    'title' => null,
])

@if(!empty($attachments) && count($attachments) > 0)
    <div {{ $attributes->merge(['class' => 'space-y-2']) }}>
        @if($title)
            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 uppercase tracking-wider">
                <span class="material-symbols-outlined text-primary text-base">attach_file</span>
                <span>{{ $title }}</span>
                <span class="text-slate-400 font-normal">({{ count($attachments) }})</span>
            </p>
        @endif

        @if($variant === 'cards')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($attachments as $file)
                    @php
                        $fileUrl = route('file.viewer', ['path' => $file['path'] ?? '']);
                        $fileName = $file['name'] ?? 'Tệp đính kèm';
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                        $isAudio = in_array($ext, ['mp3', 'wav', 'ogg', 'webm', 'm4a']);
                        $isPdf = ($ext === 'pdf');
                    @endphp
                    <div class="flex items-center justify-between gap-3 p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs hover:border-primary/40 transition-all group">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="size-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-xl">
                                    @if($isImg) image @elseif($isAudio) audio_file @elseif($isPdf) picture_as_pdf @elseif(in_array($ext, ['doc', 'docx'])) article @elseif(in_array($ext, ['xls', 'xlsx'])) table_view @elseif(in_array($ext, ['zip', 'rar'])) folder_zip @else description @endif
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate" title="{{ $fileName }}">{{ $fileName }}</p>
                                <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded mt-0.5 inline-block">
                                    {{ $ext ?: 'FILE' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <button type="button"
                                    @click="$dispatch('open-file-preview', { url: @js($fileUrl), name: @js($fileName) })"
                                    class="px-2.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold transition-colors flex items-center gap-1 cursor-pointer"
                                    title="{{ __('Xem trước') }}">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                <span class="hidden sm:inline">{{ __('Xem') }}</span>
                            </button>
                            <a href="{{ $fileUrl }}" download target="_blank"
                               class="size-8 rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center justify-center transition-colors"
                               title="{{ __('Tải về máy') }}">
                                <span class="material-symbols-outlined text-base">download</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($attachments as $file)
                    @php
                        $fileUrl = route('file.viewer', ['path' => $file['path'] ?? '']);
                        $fileName = $file['name'] ?? 'Tệp đính kèm';
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                        $isAudio = in_array($ext, ['mp3', 'wav', 'ogg', 'webm', 'm4a']);
                        $isPdf = ($ext === 'pdf');
                    @endphp
                    <div class="inline-flex items-center gap-1.5 p-1.5 pr-2 bg-slate-50 dark:bg-slate-800 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-primary/50 transition-all group/att">
                        <button type="button"
                                @click="$dispatch('open-file-preview', { url: @js($fileUrl), name: @js($fileName) })"
                                class="flex items-center gap-1.5 text-primary hover:text-primary/80 transition-colors text-left font-bold cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">
                                @if($isImg) image @elseif($isAudio) audio_file @elseif($isPdf) picture_as_pdf @elseif(in_array($ext, ['doc', 'docx'])) article @elseif(in_array($ext, ['xls', 'xlsx'])) table_view @elseif(in_array($ext, ['zip', 'rar'])) folder_zip @else attach_file @endif
                            </span>
                            <span class="truncate max-w-[130px] sm:max-w-[180px]" title="{{ $fileName }}">{{ $fileName }}</span>
                        </button>
                        <button type="button"
                                @click="$dispatch('open-file-preview', { url: @js($fileUrl), name: @js($fileName) })"
                                class="size-6 rounded-md hover:bg-primary/10 text-slate-400 hover:text-primary flex items-center justify-center transition-colors"
                                title="{{ __('Xem trước') }}">
                            <span class="material-symbols-outlined text-[14px]">visibility</span>
                        </button>
                        <a href="{{ $fileUrl }}" download target="_blank"
                           class="size-6 rounded-md hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition-colors"
                           title="{{ __('Tải về') }}">
                            <span class="material-symbols-outlined text-[14px]">download</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif
