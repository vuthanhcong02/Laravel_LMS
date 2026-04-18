@extends('portal.layouts.dashboard')

@section('title', 'Edit Course')

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
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Course</h1>
                    <p class="text-sm text-slate-500">Update the course details below.</p>
                </div>
            </div>

            {{-- Flash Messages --}}
            <x-flash-message type="success" />
            <x-flash-message type="error" />

            {{-- Edit Form --}}
            <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5" x-data="courseForm('{{ $course->slug }}')">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Main Content: 2/3 --}}
                    <div class="lg:col-span-2 space-y-5">

                        {{-- Title + Slug --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}"
                                    @input="updateSlug($event.target.value)"
                                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border @error('title') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-lg focus:ring-primary focus:border-primary text-sm"
                                    placeholder="Enter course title...">
                                @error('title')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="material-symbols-outlined text-slate-400 text-base shrink-0">link</span>
                                <span class="text-xs text-slate-400 shrink-0">/courses/</span>
                                <span x-text="slug || 'your-course-slug'" class="text-xs text-primary font-mono truncate"></span>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Description</label>
                            @error('description')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <div class="rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                                <textarea name="description" id="course-description-editor">{!! old('description', $course->description) !!}</textarea>
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
                                        {{ old('is_published', $course->is_published ? '1' : '0') == '1' ? 'checked' : '' }}
                                        class="accent-primary w-4 h-4">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Published</p>
                                        <p class="text-xs text-slate-400">Visible to all students</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-colors">
                                    <input type="radio" name="is_published" value="0"
                                        {{ old('is_published', $course->is_published ? '1' : '0') == '0' ? 'checked' : '' }}
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
                                    Save Changes
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
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>
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
                                    <option value="{{ $teacher->id }}"
                                        {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
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
                                <input type="number" name="price" value="{{ old('price', $course->price ?? 0) }}" min="0" step="1000"
                                    class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-100 outline-none border-none focus:ring-0">
                            </div>
                            <p class="text-xs text-slate-400">Set to 0 for a free course.</p>
                        </div>

                        {{-- Thumbnail --}}
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3"
                            x-data="{ preview: null }">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Thumbnail</p>

                            <template x-if="preview">
                                <img :src="preview" class="w-full h-36 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                            </template>
                            <template x-if="!preview">
                                @if ($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                        class="w-full h-36 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                                @else
                                    <div class="w-full h-36 bg-slate-50 dark:bg-slate-800 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center gap-2 text-slate-400">
                                        <span class="material-symbols-outlined text-3xl">image</span>
                                        <span class="text-xs">No image selected</span>
                                    </div>
                                @endif
                            </template>

                            <label for="thumbnail-input"
                                class="flex items-center justify-center gap-2 w-full px-3 py-2 text-sm text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-base">upload</span> Change Image
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

            {{-- ============================================================ --}}
            {{-- Lessons Panel --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

                {{-- Panel Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Lessons</h2>
                        <p class="text-xs text-slate-500">{{ $course->lessons->count() }} lesson(s) in this course.</p>
                    </div>
                    {{-- Toggle Add Lesson Form --}}
                    <button type="button" x-data x-on:click="$dispatch('toggle-add-lesson')"
                        class="px-3 py-1.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">add</span> Add Lesson
                    </button>
                </div>

                {{-- Add Lesson Form (collapsible) --}}
                <div x-data="{ open: false }" x-on:toggle-add-lesson.window="open = !open"
                    x-show="open" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-cloak>
                    <form action="{{ route('admin.lessons.store', $course) }}" method="POST"
                        class="p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 space-y-3">
                        @csrf
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">New Lesson</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-500">Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" placeholder="Lesson title..."
                                    class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-500">Video URL</label>
                                <input type="url" name="video_url" placeholder="https://youtube.com/..."
                                    class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-slate-500">Description</label>
                            <textarea name="description" rows="2" placeholder="Brief description..."
                                class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary resize-none"></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" x-on:click="open = false"
                                class="px-4 py-1.5 text-sm text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-1.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors">
                                Save Lesson
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Lessons List --}}
                <div class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($course->lessons as $lesson)
                        <div x-data="{ editing: false }" class="group">
                            {{-- Lesson Row (display mode) --}}
                            <div x-show="!editing" class="flex items-center gap-4 px-6 py-3">
                                {{-- Order Number --}}
                                <span class="flex-shrink-0 size-7 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500">
                                    {{ $lesson->order }}
                                </span>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $lesson->title }}</p>
                                    @if ($lesson->video_url)
                                        <p class="text-xs text-slate-400 font-mono truncate">{{ $lesson->video_url }}</p>
                                    @elseif($lesson->description)
                                        <p class="text-xs text-slate-400 truncate">{{ \Illuminate\Support\Str::limit($lesson->description, 80) }}</p>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{-- Move Up --}}
                                    <form action="{{ route('admin.lessons.moveUp', $lesson) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors" title="Move up">
                                            <span class="material-symbols-outlined text-base">keyboard_arrow_up</span>
                                        </button>
                                    </form>
                                    {{-- Move Down --}}
                                    <form action="{{ route('admin.lessons.moveDown', $lesson) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors" title="Move down">
                                            <span class="material-symbols-outlined text-base">keyboard_arrow_down</span>
                                        </button>
                                    </form>
                                    {{-- Edit --}}
                                    <button type="button" @click="editing = true"
                                        class="p-1 text-blue-400 hover:text-blue-600 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </button>
                                    {{-- Delete --}}
                                    <button type="button"
                                        data-url="{{ route('admin.lessons.destroy', $lesson) }}"
                                        data-message="Are you sure you want to delete &quot;{{ $lesson->title }}&quot;?"
                                        @click="$dispatch('open-delete-modal', { url: $el.dataset.url, message: $el.dataset.message })"
                                        class="p-1 text-red-400 hover:text-red-600 transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Lesson Edit (inline edit mode) --}}
                            <div x-show="editing" x-cloak
                                class="p-4 bg-slate-50 dark:bg-slate-800/50">
                                <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" class="space-y-3">
                                    @csrf
                                    @method('PUT')
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Editing: {{ $lesson->title }}</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-slate-500">Title <span class="text-red-500">*</span></label>
                                            <input type="text" name="title" value="{{ $lesson->title }}"
                                                class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-slate-500">Video URL</label>
                                            <input type="url" name="video_url" value="{{ $lesson->video_url }}"
                                                class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-slate-500">Description</label>
                                        <textarea name="description" rows="2"
                                            class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary resize-none">{{ $lesson->description }}</textarea>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="editing = false"
                                            class="px-4 py-1.5 text-sm text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-1.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">menu_book</span>
                            <p class="text-sm text-slate-500">No lessons yet. Click "Add Lesson" to get started.</p>
                        </div>
                    @endforelse
                </div>

            </div>
            {{-- End Lessons Panel --}}

        </div>
    </main>

    @include('portal.admin.components.ckeditor', [
        'editorId' => 'course-description-editor',
        'uploadUrl' => route('admin.blogs.upload', ['_token' => csrf_token()])
    ])
    <script>
        function courseForm(initialSlug) {
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
            };
        }
    </script>
@endsection
