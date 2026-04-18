@extends('portal.layouts.dashboard')

@section('title', 'User Management')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1200px] mx-auto space-y-6">
            <div class="flex items-center justify-between mt-5">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">User Management</h1>
                    <p class="text-sm text-slate-500">Manage your platform's administrators, teachers, and students.</p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                    class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span> New User
                </a>
            </div>

            <x-flash-message type="success" />
            <x-flash-message type="error" />

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm p-4">
                <form method="GET" action="{{ route('admin.users.index') }}"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Search Input --}}
                    <div
                        class="flex flex-1 items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus-within:ring-2 focus-within:ring-primary/40 transition-all">
                        <span class="material-symbols-outlined text-slate-400 text-[20px] shrink-0">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tìm kiếm theo tên hoặc email..."
                            class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 outline-none border-none focus:ring-0 min-w-0">
                        @if (request('search'))
                            <a href="{{ route('admin.users.index', ['role' => request('role')]) }}"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors shrink-0">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </a>
                        @endif
                    </div>

                    {{-- Role Filter --}}
                    <div
                        class="flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus-within:ring-2 focus-within:ring-primary/40 transition-all sm:w-48">
                        <span class="material-symbols-outlined text-slate-400 text-[20px] shrink-0">filter_list</span>
                        <select name="role" onchange="this.form.submit()"
                            class="flex-1 bg-transparent text-sm text-slate-700 dark:text-slate-300 outline-none border-none focus:ring-0">
                            <option value="">Tất cả vai trò</option>
                            @foreach ($roles as $key => $label)
                                <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit button (visible on mobile) --}}
                    <button type="submit"
                        class="sm:hidden px-4 py-2 bg-primary text-white font-bold text-sm rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">search</span> Tìm kiếm
                    </button>

                    @if (request('search') || request('role'))
                        <a href="{{ route('admin.users.index') }}"
                            class="hidden sm:flex items-center gap-1.5 px-4 py-2 text-sm text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">filter_alt_off</span>
                            Xóa bộ lọc
                        </a>
                    @endif
                </form>

                @if (request('search') || request('role'))
                    <p class="text-xs text-slate-400 mt-3 pl-1">Tìm thấy <span
                            class="font-bold text-primary">{{ $users->total() }}</span> kết quả.</p>
                @endif
            </div>

            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Email</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Role</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Joined</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 dark:text-white">{{ $user->first_name }}
                                            {{ $user->last_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-sm">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($user->role == \App\Models\User::ROLE_ADMIN)
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-700 rounded-md text-xs font-bold">Admin</span>
                                        @elseif($user->role == \App\Models\User::ROLE_TEACHER)
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-bold">Teacher</span>
                                        @elseif($user->role == \App\Models\User::ROLE_STUDENT)
                                            <span
                                                class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-bold">Student</span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-bold">Guest</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-sm text-center">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-baseline justify-end gap-3">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-blue-500 hover:text-blue-700 transition-colors">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </a>
                                            <button type="button"
                                                @click="$dispatch('open-delete-modal', { url: '{{ route('admin.users.destroy', $user) }}', message: 'Bạn có chắc chắn muốn xóa người dùng {{ $user->first_name }} {{ $user->last_name }}? Toàn bộ dữ liệu của người này sẽ bị xóa vĩnh viễn.' })"
                                                class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
