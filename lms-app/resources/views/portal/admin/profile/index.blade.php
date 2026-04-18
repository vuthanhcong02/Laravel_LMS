@extends('portal.layouts.dashboard')

@section('title', 'My Profile')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto" x-data="{ tab: 'profile' }">
        <div class="max-w-[1200px] mx-auto space-y-6">

            {{-- Page Title --}}
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">My Profile</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage your personal information and security settings.
                </p>
            </div>

            {{-- Flash Messages --}}
            <x-flash-message type="success" />
            <x-flash-message type="error" />

            {{-- Tabs --}}
            <div class="flex border-b border-slate-200 dark:border-slate-700">
                <button @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'border-primary text-primary' :
                        'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-5 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">manage_accounts</span> Profile Info
                </button>
                <button @click="tab = 'security'"
                    :class="tab === 'security' ? 'border-primary text-primary' :
                        'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-5 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">lock</span> Security
                </button>
            </div>

            {{-- TAB: Profile Info --}}
            <div x-show="tab === 'profile'" x-transition>
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    @csrf
                    @method('PUT')

                    {{-- Avatar Section --}}
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center gap-6"
                        x-data="avatarPreview()">
                        {{-- Avatar Display --}}
                        <div class="relative shrink-0">
                            @php
                                $avatar = Auth::user()->avatar;
                                $avatarUrl = $avatar
                                    ? (str_starts_with($avatar, 'http')
                                        ? $avatar
                                        : asset('storage/' . $avatar))
                                    : null;
                            @endphp
                            <template x-if="preview">
                                <img :src="preview"
                                    class="size-24 rounded-full object-cover border-4 border-primary/30 shadow-md">
                            </template>
                            <template x-if="!preview">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}"
                                        class="size-24 rounded-full object-cover border-4 border-primary/30 shadow-md">
                                @else
                                    <div
                                        class="size-24 rounded-full bg-primary flex items-center justify-center text-white text-3xl font-bold border-4 border-primary/30 shadow-md">
                                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </template>
                            <label for="avatar-input"
                                class="absolute bottom-0 right-0 size-8 bg-primary rounded-full flex items-center justify-center text-white cursor-pointer shadow hover:bg-primary/90 transition-colors">
                                <span class="material-symbols-outlined text-sm">photo_camera</span>
                            </label>
                        </div>
                        <div class="space-y-1 text-center sm:text-left">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Profile Photo</p>
                            <p class="text-xs text-slate-400">JPG, PNG, WebP. Max 2MB.</p>
                            <input id="avatar-input" type="file" name="avatar" accept="image/*" class="hidden"
                                @change="onFileChange">
                            @error('avatar')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Fields --}}
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                    class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('first_name') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm">
                                @error('first_name')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                    class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('last_name') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm">
                                @error('last_name')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('email') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm">
                            @error('email')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit"
                                class="px-6 py-2 rounded-lg font-bold bg-primary text-white hover:bg-primary/90 transition-colors text-sm">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TAB: Security --}}
            <div x-show="tab === 'security'" x-transition style="display:none">
                <form action="{{ route('admin.profile.updatePassword') }}" method="POST"
                    class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Current Password</label>
                        <input type="password" name="current_password"
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('current_password') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter your current password">
                        @error('current_password')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">New Password</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('password') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm"
                            placeholder="At least 8 characters">
                        @error('password')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary text-sm"
                            placeholder="Repeat new password">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 rounded-lg font-bold bg-primary text-white hover:bg-primary/90 transition-colors text-sm">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <script>
        function avatarPreview() {
            return {
                preview: null,
                onFileChange(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        this.preview = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            };
        }
    </script>
@endsection
