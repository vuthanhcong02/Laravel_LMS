@extends('portal.layouts.dashboard')

@section('title', 'Blog Management')

@section('header')
    @include('portal.admin.layouts.header')
@endsection

@section('sidebar')
    @include('portal.admin.layouts.sidebar')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-[1200px] mx-auto space-y-6">

            {{-- Page Header --}}
            <div class="flex items-center justify-between mt-5">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Blog Management</h1>
                    <p class="text-sm text-slate-500">Create and manage blog posts for your platform.</p>
                </div>
                <a href="{{ route('admin.blogs.create') }}"
                    class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span> New Post
                </a>
            </div>

            {{-- Flash Messages --}}
            <x-admin.flash-message type="success" />
            <x-admin.flash-message type="error" />

            {{-- Stats Cards --}}
            <div class="flex items-center gap-4">
                <div
                    class="flex-1 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="size-11 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">article</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
                        <p class="text-xs text-slate-500">Total Posts</p>
                    </div>
                </div>
                <div
                    class="flex-1 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="size-11 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['published'] }}</p>
                        <p class="text-xs text-slate-500">Published</p>
                    </div>
                </div>
                <div
                    class="flex-1 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="size-11 rounded-xl bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <span class="material-symbols-outlined">edit_note</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['draft'] }}</p>
                        <p class="text-xs text-slate-500">Drafts</p>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm p-4">
                <form method="GET" action="{{ route('admin.blogs.index') }}"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Search --}}
                    <div
                        class="flex flex-1 items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus-within:ring-2 focus-within:ring-primary/40 transition-all">
                        <span class="material-symbols-outlined text-slate-400 text-[20px] shrink-0">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by title..."
                            class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 outline-none border-none focus:ring-0">
                        @if (request('search'))
                            <a href="{{ route('admin.blogs.index', array_filter(['status' => request('status'), 'category_id' => request('category_id')])) }}"
                                class="text-slate-400 hover:text-slate-600 shrink-0">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </a>
                        @endif
                    </div>

                    {{-- Status Filter --}}
                    <div
                        class="flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg sm:w-44">
                        <span class="material-symbols-outlined text-slate-400 text-[20px] shrink-0">filter_list</span>
                        <select name="status" onchange="this.form.submit()"
                            class="flex-1 bg-transparent text-sm text-slate-700 dark:text-slate-300 outline-none border-none focus:ring-0">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    {{-- Category Filter --}}
                    @if ($categories->isNotEmpty())
                        <div
                            class="flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg sm:w-48">
                            <span class="material-symbols-outlined text-slate-400 text-[20px] shrink-0">category</span>
                            <select name="category_id" onchange="this.form.submit()"
                                class="flex-1 bg-transparent text-sm text-slate-700 dark:text-slate-300 outline-none border-none focus:ring-0">
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if (request('search') || request('status') !== null || request('category_id'))
                        <a href="{{ route('admin.blogs.index') }}"
                            class="hidden sm:flex items-center gap-1.5 px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">filter_alt_off</span> Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Blog Table --}}
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Post</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Category</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Author</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Date</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($blogs as $blog)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    {{-- Thumbnail + Title --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($blog->thumbnail)
                                                <img src="{{ asset('storage/' . $blog->thumbnail) }}"
                                                    class="size-10 rounded-lg object-cover shrink-0 border border-slate-200 dark:border-slate-700">
                                            @else
                                                <div
                                                    class="size-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <span
                                                        class="material-symbols-outlined text-primary text-base">article</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p
                                                    class="font-semibold text-slate-900 dark:text-white text-sm line-clamp-1">
                                                    {{ $blog->title }}</p>
                                                <p class="text-xs text-slate-400 font-mono line-clamp-1">
                                                    /{{ $blog->slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $blog->category?->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $blog->author->first_name }} {{ $blog->author->last_name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($blog->is_published)
                                            <span
                                                class="px-5 py-2 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Published</span>
                                        @else
                                            <span
                                                class="px-5 py-2 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 text-center">
                                        {{ $blog->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-baseline justify-end gap-3">
                                            <a href="{{ route('admin.blogs.edit', $blog) }}"
                                                class="text-blue-500 hover:text-blue-700 transition-colors">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </a>
                                            <button type="button" data-url="{{ route('admin.blogs.destroy', $blog) }}"
                                                data-message="Are you sure you want to delete &quot;{{ $blog->title }}&quot;? This action cannot be undone."
                                                @click="$dispatch('open-delete-modal', { url: $el.dataset.url, message: $el.dataset.message })"
                                                class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <span
                                            class="material-symbols-outlined text-4xl text-slate-300 block mb-2">article</span>
                                        <p class="text-slate-500 text-sm">No blog posts found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($blogs->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>
@endsection
