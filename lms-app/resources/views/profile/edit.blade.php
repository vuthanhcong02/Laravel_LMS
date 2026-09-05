@extends('layouts.lms')
@section('title', __('Hồ sơ cá nhân - XIAOMU Tiếng Trung LMS'))
@section('alpine-data')
    activeTab: '{{ $errors->updatePassword->isNotEmpty() ? 'security' : 'profile' }}',
@endsection
@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
        <div>
            <x-lms.breadcrumb 
                class="mb-5"
                :links="[
                    ['label' => 'Trang chủ', 'url' => route('home')],
                    ['label' => 'Hồ sơ cá nhân']
                ]" 
            />
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                {{ __('Cài đặt Hồ sơ & Tài khoản') }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 text-xs font-bold shadow-xs">
                <i class="fa-solid fa-circle-check text-xs"></i>
                <span>{{ __('Tài khoản kích hoạt') }}</span>
            </span>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 space-y-4">
            <div class="lms-card overflow-hidden"
                 x-data="{ 
                     userName: '{{ addslashes($user->first_name . ' ' . $user->last_name) }}',
                     userAvatar: '{{ $user->avatar_url }}'
                 }"
                 @profile-updated.window="userName = $event.detail.name; userAvatar = $event.detail.avatar">
                <div class="h-24 bg-gradient-to-r from-[#e07a5f]/80 via-[#e07a5f] to-[#c86349] relative">
                    <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:12px_12px] opacity-20"></div>
                </div>
                <div class="px-5 pb-5 pt-0 relative">
                    <div class="flex justify-between items-end -mt-12 mb-3">
                        <div class="relative">
                            <img :src="userAvatar" 
                                 alt="Avatar" 
                                 class="w-20 h-20 rounded-2xl object-cover border-4 border-white dark:border-[#181615] shadow-md bg-white dark:bg-[#201d1b]">
                            @if($user->email_verified_at)
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] border-2 border-white dark:border-[#181615]" title="{{ __('Email đã xác thực') }}">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            @endif
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold border 
                            @if($user->role === \App\Models\User::ROLE_ADMIN)
                                bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border-purple-200 dark:border-purple-900/50
                            @elseif($user->role === \App\Models\User::ROLE_TEACHER)
                                bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-900/50
                            @else
                                bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-900/50
                            @endif">
                            @if($user->role === \App\Models\User::ROLE_ADMIN)
                                {{ __('Quản trị viên') }}
                            @elseif($user->role === \App\Models\User::ROLE_TEACHER)
                                {{ __('Giảng viên') }}
                            @else
                                {{ __('Học viên XIAOMU') }}
                            @endif
                        </span>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-tight" x-text="userName">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 break-all">
                            {{ $user->email }}
                        </p>
                    </div>
                    <div class="mt-3.5 text-center">
                        <span class="text-[11px] text-slate-400 flex items-center justify-center gap-1.5">
                            <i class="fa-regular fa-calendar text-[10px]"></i>
                            <span>{{ __('Tham gia từ :date', ['date' => $user->created_at ? $user->created_at->format('d/m/Y') : '2024']) }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="lms-card p-2 space-y-1">
                <button @click="activeTab = 'profile'" 
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all text-left btn-tactile cursor-pointer"
                        :class="activeTab === 'profile' ? 'bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-[#f8f6f3] dark:hover:bg-[#201d1b]'">
                    <i class="fa-regular fa-id-badge text-sm shrink-0" :class="activeTab === 'profile' ? 'text-[#e07a5f]' : 'text-slate-400'"></i>
                    <span class="flex-1">{{ __('Thông tin cá nhân') }}</span>
                    <i class="fa-solid fa-chevron-right text-[10px] opacity-60" :class="activeTab === 'profile' ? 'text-[#e07a5f]' : 'text-slate-400'"></i>
                </button>
                <button @click="activeTab = 'security'" 
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all text-left btn-tactile cursor-pointer"
                        :class="activeTab === 'security' ? 'bg-[#fff2ee] dark:bg-[#2a221f] text-[#e07a5f] shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-[#f8f6f3] dark:hover:bg-[#201d1b]'">
                    <i class="fa-solid fa-shield-halved text-sm shrink-0" :class="activeTab === 'security' ? 'text-[#e07a5f]' : 'text-slate-400'"></i>
                    <span class="flex-1">{{ __('Đổi mật khẩu & Bảo mật') }}</span>
                    <i class="fa-solid fa-chevron-right text-[10px] opacity-60" :class="activeTab === 'security' ? 'text-[#e07a5f]' : 'text-slate-400'"></i>
                </button>
            </div>
        </div>
        <div class="lg:col-span-8">
            <div class="lms-card p-6 sm:p-8 shadow-sm">
                <div x-show="activeTab === 'profile'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div x-show="activeTab === 'security'" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="display: none;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
