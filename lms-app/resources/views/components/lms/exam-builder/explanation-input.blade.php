@props([
    'index',
    'explanations' => null,
])

@php
    $currentExps = $explanations ?? ($this->questionExplanations[$index] ?? ($questionExplanations[$index] ?? []));
@endphp

<div class="mt-3" x-data="{ expTab: 'vi' }">
    <div class="flex items-center justify-between flex-wrap gap-2 mb-1.5">
        <label class="block text-[11px] font-bold text-slate-500 uppercase">
            {{ __('Giải thích đáp án (Tuỳ chọn)') }}
        </label>
        <div class="inline-flex items-center p-0.5 rounded-lg bg-slate-200/80 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-[11px] font-bold">
            <button type="button"
                    @click="expTab = 'vi'"
                    :class="expTab === 'vi' ? 'bg-white dark:bg-slate-900 text-primary shadow-xs' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-2 py-0.5 rounded-md transition-all flex items-center gap-1 cursor-pointer">
                <span>🇻🇳</span>
                <span>VI</span>
            </button>
            <button type="button"
                    @click="expTab = 'zh'"
                    :class="expTab === 'zh' ? 'bg-white dark:bg-slate-900 text-primary shadow-xs' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-2 py-0.5 rounded-md transition-all flex items-center gap-1 cursor-pointer">
                <span>🇨🇳</span>
                <span>ZH</span>
            </button>
            <button type="button"
                    @click="expTab = 'en'"
                    :class="expTab === 'en' ? 'bg-white dark:bg-slate-900 text-primary shadow-xs' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-2 py-0.5 rounded-md transition-all flex items-center gap-1 cursor-pointer">
                <span>🇬🇧</span>
                <span>EN</span>
            </button>
        </div>
    </div>

    {{-- Vietnamese explanation textarea --}}
    <div x-show="expTab === 'vi'">
        <textarea wire:model="questionExplanations.{{ $index }}.vi" rows="2"
            placeholder="{{ __('Nhập giải thích tiếng Việt cho câu hỏi này...') }}"
            class="w-full text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary px-3 py-2">{{ $currentExps['vi'] ?? '' }}</textarea>
    </div>

    {{-- Chinese explanation textarea --}}
    <div x-show="expTab === 'zh'">
        <textarea wire:model="questionExplanations.{{ $index }}.zh" rows="2"
            placeholder="输入此题的中文解析..."
            class="w-full text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary px-3 py-2 zh-text font-medium">{{ $currentExps['zh'] ?? '' }}</textarea>
    </div>

    {{-- English explanation textarea --}}
    <div x-show="expTab === 'en'">
        <textarea wire:model="questionExplanations.{{ $index }}.en" rows="2"
            placeholder="Enter English explanation for this question..."
            class="w-full text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-primary focus:border-primary px-3 py-2">{{ $currentExps['en'] ?? '' }}</textarea>
    </div>
</div>
