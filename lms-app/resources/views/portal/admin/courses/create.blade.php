@extends('portal.layouts.dashboard')

@section('title', 'Create Course')

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
                <a href="{{ route('admin.courses.index') }}"
                    class="flex items-center justify-center size-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">New Course</h1>
                    <p class="text-sm text-slate-500">Fill in the details below to create a new course.</p>
                </div>
            </div>

            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5" x-data="courseForm()">

                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Main Content: 2/3 --}}
                    <div class="lg:col-span-2 space-y-5">

                        {{-- Title + Slug --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                    @input="updateSlug($event.target.value)"
                                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border @error('title') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm"
                                    placeholder="Enter course title...">
                                @error('title')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Slug Preview --}}
                            <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="material-symbols-outlined text-slate-400 text-base shrink-0">link</span>
                                <span class="text-xs text-slate-400 shrink-0">/courses/</span>
                                <span x-text="slug || 'your-course-slug'" class="text-xs text-primary font-mono truncate"></span>
                            </div>
                        </div>

                        {{-- Description (Rich Text) --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Description</label>
                            @error('description')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <div class="rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                                <textarea name="description" id="course-description-editor">{!! old('description') !!}</textarea>
                            </div>
                        </div>

                    </div>

                    {{-- Sidebar Options: 1/3 --}}
                    <div class="space-y-5">

                        {{-- Publish Settings --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-4">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Publish Settings</p>

                            <div class="space-y-2">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</p>
                                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-colors">
                                    <input type="radio" name="is_published" value="1"
                                        {{ old('is_published', '0') == '1' ? 'checked' : '' }}
                                        class="accent-primary w-4 h-4">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Published</p>
                                        <p class="text-xs text-slate-400">Visible to all students</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-colors">
                                    <input type="radio" name="is_published" value="0"
                                        {{ old('is_published', '0') == '0' ? 'checked' : '' }}
                                        class="accent-primary w-4 h-4">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Draft</p>
                                        <p class="text-xs text-slate-400">Only visible to you</p>
                                    </div>
                                </label>
                            </div>

                            <div class="pt-2 space-y-2">
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors">
                                    Create Course
                                </button>
                                <a href="{{ route('admin.courses.index') }}"
                                    class="block w-full text-center px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Category</p>
                            <select name="category_id"
                                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                <option value="">— No Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Teacher --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Teacher</p>
                            <select name="teacher_id"
                                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                <option value="">— Unassigned —</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Price (VND)</p>
                            <div class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                                <span class="text-slate-400 text-sm shrink-0">₫</span>
                                <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="1000"
                                    class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-100 outline-none border-none focus:ring-0">
                            </div>
                            <p class="text-xs text-slate-400">Set to 0 for a free course.</p>
                            @error('price')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Thumbnail --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3"
                            x-data="{ preview: null }">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Thumbnail</p>
                            <template x-if="preview">
                                <img :src="preview" class="w-full h-36 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                            </template>
                            <template x-if="!preview">
                                <div class="w-full h-36 bg-slate-50 dark:bg-slate-800 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center gap-2 text-slate-400">
                                    <span class="material-symbols-outlined text-3xl">image</span>
                                    <span class="text-xs">No image selected</span>
                                </div>
                            </template>
                            <label for="thumbnail-input"
                                class="flex items-center justify-center gap-2 w-full px-3 py-2 text-sm text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-base">upload</span> Choose Image
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
        'editorId' => 'course-description-editor',
        'uploadUrl' => route('admin.blogs.upload', ['_token' => csrf_token()])
    ])
    <script>
        function courseForm() {
            return {
                slug: '',
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
            };
        }
    </script>
@endsection
