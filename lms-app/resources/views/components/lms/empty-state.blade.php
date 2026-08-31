@props([
    'icon' => 'fa-solid fa-folder-open',
    'title' => __('Chưa có dữ liệu'),
    'description' => __('Nội dung đang được biên soạn và sẽ sớm ra mắt. Vui lòng quay lại sau.')
])

<div {{ $attributes->merge(['class' => 'lms-card p-12 text-center bg-white dark:bg-[#181615] border border-[#e8e2d9] dark:border-[#2d2926] rounded-2xl flex flex-col items-center justify-center space-y-3']) }}>
    <div class="w-16 h-16 rounded-2xl bg-[#fff2ee] dark:bg-[#2c221e] text-[#e07a5f] dark:text-[#f4978e] flex items-center justify-center text-2xl shadow-xs border border-[#fcdccf] dark:border-[#4a2e26]">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="space-y-1">
        <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ $title }}</h4>
        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm font-normal leading-relaxed mx-auto">{{ $description }}</p>
    </div>
</div>
