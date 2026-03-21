@extends('portal.layouts.dashboard')

@section('title', 'Edit User')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[800px] mx-auto space-y-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center justify-center size-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit User</h1>
                    <p class="text-sm text-slate-500">Update {{ $user->first_name }} {{ $user->last_name }}'s details.</p>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST"
                class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('first_name') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary">
                        @error('first_name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('last_name') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary">
                        @error('last_name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('email') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary">
                    @error('email')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Role</label>
                        <select name="role" required
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('role') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary">
                            @foreach ($roles as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">New Password <span
                                class="text-slate-400 font-normal">(Leave blank to keep current)</span></label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border @error('password') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary">
                        @error('password')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2 rounded-lg font-bold text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg font-bold bg-primary text-white hover:bg-primary/90 transition-colors">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </main>
@endsection
