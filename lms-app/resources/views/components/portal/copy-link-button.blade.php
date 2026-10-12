@props([
    'url',
    'title' => __('Sao chép liên kết gửi học viên'),
    'variant' => 'icon', // 'icon' or 'button'
    'buttonText' => __('Sao chép link học viên'),
])

<div x-data="copyLinkButton({ url: '{{ $url }}' })" class="inline-block relative">
    @if($variant === 'button')
        <button type="button"
                @click="copy()"
                :class="copied ? 'border-emerald-300 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200'"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-semibold transition-all shadow-sm active:scale-95 cursor-pointer"
                :title="copied ? '{{ __('Đã sao chép liên kết!') }}' : '{{ $title }}'">
            <span class="material-symbols-outlined text-[16px] transition-transform duration-200"
                  :class="copied ? 'text-emerald-600 dark:text-emerald-400 rotate-12' : 'text-slate-400 dark:text-slate-400'">
                <template x-if="!copied">
                    <span>link</span>
                </template>
                <template x-if="copied">
                    <span>check</span>
                </template>
            </span>
            <span x-text="copied ? '{{ __('Đã sao chép!') }}' : '{{ $buttonText }}'"></span>
        </button>
    @else
        <button type="button"
                @click="copy()"
                :class="copied ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100' : 'text-slate-400 hover:text-sky-600 hover:bg-sky-50 dark:hover:bg-sky-950/40'"
                class="inline-flex items-center justify-center size-7.5 rounded-lg transition-colors cursor-pointer"
                :title="copied ? '{{ __('Đã sao chép liên kết!') }}' : '{{ $title }}'">
            <span class="material-symbols-outlined text-[18px] transition-transform duration-200"
                  :class="copied ? 'text-emerald-600 dark:text-emerald-400 scale-110' : ''">
                <template x-if="!copied">
                    <span>link</span>
                </template>
                <template x-if="copied">
                    <span>check</span>
                </template>
            </span>
        </button>
    @endif
</div>
