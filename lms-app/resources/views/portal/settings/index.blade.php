@extends('portal.layouts.dashboard')

@section('title', 'Cài đặt')

@section('header')
    @if ($user->role === \App\Models\User::ROLE_ADMIN)
        @include('portal.admin.layouts.header')
    @elseif ($user->role === \App\Models\User::ROLE_TEACHER)
        @include('portal.teacher.layouts.header')
    @elseif ($user->role === \App\Models\User::ROLE_STUDENT)
        @include('portal.student.layouts.header')
    @endif
@endsection

@section('sidebar')
    @if ($user->role === \App\Models\User::ROLE_ADMIN)
        @include('portal.admin.layouts.sidebar')
    @elseif ($user->role === \App\Models\User::ROLE_TEACHER)
        @include('portal.teacher.layouts.sidebar')
    @elseif ($user->role === \App\Models\User::ROLE_STUDENT)
        @include('portal.student.layouts.sidebar')
    @endif
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1400px] mx-auto space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 mt-5">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-4xl">settings</span>
                        Cài đặt
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">Quản lý tùy chọn ngôn ngữ, thông báo và hiển thị.</p>
                </div>
            </div>

            <x-flash-message type="success" />
            <x-flash-message type="error" />

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm"
                x-data="{ activeTab: 'general', selectedTheme: '{{ $settings['theme'] ?? 'light' }}' }">

                {{-- Horizontal Tabs --}}
                <div class="flex items-center border-b border-slate-200 dark:border-slate-800 px-2 overflow-x-auto">
                    <button type="button" @click="activeTab = 'general'"
                        :class="activeTab === 'general' ? 'text-primary border-primary' :
                            'text-slate-500 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                        class="flex items-center gap-2 px-4 py-4 border-b-[3px] text-sm font-bold transition-colors whitespace-nowrap">
                        <span class="material-symbols-outlined text-[20px]">tune</span> Cài đặt chung
                    </button>
                    <button type="button" @click="activeTab = 'notifications'"
                        :class="activeTab === 'notifications' ? 'text-primary border-primary' :
                            'text-slate-500 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                        class="flex items-center gap-2 px-4 py-4 border-b-[3px] text-sm font-bold transition-colors whitespace-nowrap">
                        <span class="material-symbols-outlined text-[20px]">notifications_active</span> Thông báo
                    </button>
                    <button type="button" @click="activeTab = 'appearance'"
                        :class="activeTab === 'appearance' ? 'text-primary border-primary' :
                            'text-slate-500 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                        class="flex items-center gap-2 px-4 py-4 border-b-[3px] text-sm font-bold transition-colors whitespace-nowrap">
                        <span class="material-symbols-outlined text-[20px]">palette</span> Giao diện
                    </button>
                </div>

                {{-- Main Content --}}
                <div class="p-6 md:p-8">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf

                        {{-- Tab: General --}}
                        <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <h2 class="text-base font-bold text-slate-800 dark:text-white mb-6">Tùy chọn hiển thị</h2>

                            <div class="max-w-2xl space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Ngôn
                                        ngữ hiển thị</label>
                                    <select name="language"
                                        class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-4 py-2.5 outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                                        <option value="vi"
                                            {{ ($settings['language'] ?? '') === 'vi' ? 'selected' : '' }}>Tiếng Việt
                                        </option>
                                        <option value="en"
                                            {{ ($settings['language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                                        <option value="zh"
                                            {{ ($settings['language'] ?? '') === 'zh' ? 'selected' : '' }}>中文</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Múi
                                        giờ</label>
                                    <select name="timezone"
                                        class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-4 py-2.5 outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                                        <option value="Asia/Ho_Chi_Minh"
                                            {{ ($settings['timezone'] ?? '') === 'Asia/Ho_Chi_Minh' ? 'selected' : '' }}>
                                            (GMT+07:00) Indochina Time (Ho Chi Minh)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Tab: Notifications --}}
                        <div x-show="activeTab === 'notifications'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-cloak>
                            <h2 class="text-base font-bold text-slate-800 dark:text-white mb-6">Tùy chỉnh thông báo</h2>

                            <div class="max-w-2xl space-y-4">
                                <label
                                    class="flex items-start gap-4 cursor-pointer p-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <input type="hidden" name="notify_email" value="0">
                                    <input type="checkbox" name="notify_email" value="1"
                                        {{ ($settings['notify_email'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 text-primary bg-slate-100 border-slate-300 rounded focus:ring-primary focus:ring-2">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Thông báo qua
                                            Email</p>
                                        <p class="text-xs text-slate-500 mt-1">Nhận email thông báo mới (cập nhật khóa học,
                                            thông báo trung tâm).</p>
                                    </div>
                                </label>
                                <label
                                    class="flex items-start gap-4 cursor-pointer p-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <input type="hidden" name="notify_system" value="0">
                                    <input type="checkbox" name="notify_system" value="1"
                                        {{ ($settings['notify_system'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 text-primary bg-slate-100 border-slate-300 rounded focus:ring-primary focus:ring-2">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Thông báo hệ
                                            thống</p>
                                        <p class="text-xs text-slate-500 mt-1">Hiển thị thông báo trên biểu tượng chuông khi
                                            bạn đang trực tuyến.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Tab: Appearance --}}
                        <div x-show="activeTab === 'appearance'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-cloak>
                            <h2 class="text-base font-bold text-slate-800 dark:text-white mb-6">Tùy chỉnh giao diện</h2>

                            <div class="max-w-2xl grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <div :class="selectedTheme === 'light' ? 'border-primary bg-primary/5' :
                                        'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'"
                                        class="border-2 rounded-lg p-4 flex flex-col items-center gap-3 transition-colors">
                                        <div
                                            class="w-full max-w-[120px] aspect-[4/3] bg-white rounded shadow-sm border border-slate-200">
                                            <div class="h-4 w-full bg-slate-100 rounded-t border-b border-slate-200"></div>
                                        </div>
                                        <span
                                            :class="selectedTheme === 'light' ? 'text-primary' :
                                                'text-slate-600 dark:text-slate-400'"
                                            class="text-sm font-bold">Sáng (Light)</span>
                                    </div>
                                    <input type="radio" name="theme" value="light" x-model="selectedTheme"
                                        class="absolute opacity-0 w-0 h-0">
                                </label>
                                <label class="relative cursor-pointer">
                                    <div :class="selectedTheme === 'dark' ? 'border-primary' :
                                        'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'"
                                        class="border-2 rounded-lg p-4 flex flex-col items-center gap-3 transition-colors bg-slate-900">
                                        <div
                                            class="w-full max-w-[120px] aspect-[4/3] bg-slate-800 rounded shadow-sm border border-slate-700">
                                            <div class="h-4 w-full bg-slate-700 rounded-t border-b border-slate-700"></div>
                                        </div>
                                        <span :class="selectedTheme === 'dark' ? 'text-primary' : 'text-slate-300'"
                                            class="text-sm font-bold">Tối (Dark)</span>
                                    </div>
                                    <input type="radio" name="theme" value="dark" x-model="selectedTheme"
                                        class="absolute opacity-0 w-0 h-0">
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                            <button type="submit"
                                class="px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">save</span> Lưu cài đặt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
