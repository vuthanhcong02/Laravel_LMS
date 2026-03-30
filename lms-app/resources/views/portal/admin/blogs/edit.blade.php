@extends('portal.layouts.dashboard')

@section('title', 'Edit Blog Post')

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
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.blogs.index') }}"
                    class="flex items-center justify-center size-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Post</h1>
                    <p class="text-sm text-slate-500 line-clamp-1">{{ $blog->title }}</p>
                </div>
            </div>

            <form id="blog-form" action="{{ route('admin.blogs.update', $blog) }}" method="POST"
                enctype="multipart/form-data" class="space-y-5" x-data="blogForm('{{ $blog->slug }}')">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-5">

                        {{-- Title --}}
                        <div
                            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Title <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}"
                                    @input="updateSlug($event.target.value)"
                                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border @error('title') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm"
                                    placeholder="Enter post title...">
                                @error('title')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Slug Preview --}}
                            <div
                                class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="material-symbols-outlined text-slate-400 text-base shrink-0">link</span>
                                <span class="text-xs text-slate-400 shrink-0">/blog/</span>
                                <span x-text="slug" class="text-xs text-primary font-mono truncate"></span>
                            </div>
                        </div>

                        {{-- Rich Text Editor (CKEditor) --}}
                        <div
                            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Content <span
                                    class="text-red-500">*</span></label>
                            @error('content')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <div class="rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                                <textarea name="content" id="content-editor">{!! old('content', $blog->content) !!}</textarea>
                            </div>
                        </div>

                    </div>

                    {{-- Sidebar Options --}}
                    <div class="space-y-5">

                        {{-- Publish Card --}}
                        <div
                            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-4">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Publish Settings</p>

                            {{-- Status: Radio buttons --}}
                            <div class="space-y-2">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</p>
                                <label
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-colors">
                                    <input type="radio" name="is_published" value="1"
                                        {{ old('is_published', $blog->is_published ? '1' : '0') == '1' ? 'checked' : '' }}
                                        class="accent-primary w-4 h-4">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Published</p>
                                        <p class="text-xs text-slate-400">Visible to all readers</p>
                                    </div>
                                </label>
                                <label
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-colors">
                                    <input type="radio" name="is_published" value="0"
                                        {{ old('is_published', $blog->is_published ? '1' : '0') == '0' ? 'checked' : '' }}
                                        class="accent-primary w-4 h-4">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Draft</p>
                                        <p class="text-xs text-slate-400">Only visible to you</p>
                                    </div>
                                </label>
                            </div>

                            {{-- Created info --}}
                            <p class="text-xs text-slate-400">Created {{ $blog->created_at->format('M d, Y') }}</p>

                            <div class="pt-1 space-y-2">
                                <button type="button" @click="openPreview()"
                                    class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-base">visibility</span> Preview
                                </button>
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors">
                                    Save Changes
                                </button>
                                <a href="{{ route('admin.blogs.index') }}"
                                    class="block w-full text-center px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div
                            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Category</p>
                            <select name="category_id"
                                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                <option value="">— No Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thumbnail --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3"
                            x-data="{ preview: null }">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Thumbnail</p>

                            <template x-if="preview">
                                <img :src="preview"
                                    class="w-full h-36 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                            </template>
                            <template x-if="!preview">
                                @if ($blog->thumbnail)
                                    <img src="{{ asset('storage/' . $blog->thumbnail) }}"
                                        class="w-full h-36 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                                @else
                                    <div
                                        class="w-full h-36 bg-slate-50 dark:bg-slate-800 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center gap-2 text-slate-400">
                                        <span class="material-symbols-outlined text-3xl">image</span>
                                        <span class="text-xs">No image selected</span>
                                    </div>
                                @endif
                            </template>

                            <label for="thumbnail-input"
                                class="flex items-center justify-center gap-2 w-full px-3 py-2 text-sm text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-base">upload</span>
                                {{ $blog->thumbnail ? 'Change Image' : 'Choose Image' }}
                            </label>
                            <input id="thumbnail-input" type="file" name="thumbnail" accept="image/*" class="hidden"
                                @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                            @error('thumbnail')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-400 text-center">JPG, PNG, WebP — max 3MB</p>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </main>

    @include('portal.admin.components.ckeditor', [
        'editorId' => 'content-editor',
        'uploadUrl' => route('admin.blogs.upload', ['_token' => csrf_token()])
    ])
    <script>
        function blogForm(initialSlug) {
            return {
                slug: initialSlug,
                updateSlug(title) {
                    this.slug = title
                        .toLowerCase()
                        .replace(/đ/g, 'd')
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                },
                openPreview() {
                    if (window.editor) {
                        document.querySelector('#content-editor').value = window.editor.getData();
                    }
                    const form = document.getElementById('blog-form');
                    const origAction = form.action;
                    const origTarget = form.target || '';
                    const methodInput = form.querySelector('input[name="_method"]');

                    form.action = "{{ route('admin.blogs.preview') }}";
                    form.target = "_blank";

                    if (methodInput) methodInput.disabled = true;

                    // Add old_thumbnail hidden input to send current db thumbnail to preview if no new file is selected
                    let oldThumbInput = document.getElementById('preview-old-thumb');
                    if (!oldThumbInput) {
                        oldThumbInput = document.createElement('input');
                        oldThumbInput.type = 'hidden';
                        oldThumbInput.id = 'preview-old-thumb';
                        oldThumbInput.name = 'old_thumbnail';
                        oldThumbInput.value = "{{ $blog->thumbnail }}";
                        form.appendChild(oldThumbInput);
                    }

                    form.submit();

                    setTimeout(() => {
                        form.action = origAction;
                        form.target = origTarget;
                        if (methodInput) methodInput.disabled = false;
                    }, 200);
                }
            };
        }
    </script>
@endsection
