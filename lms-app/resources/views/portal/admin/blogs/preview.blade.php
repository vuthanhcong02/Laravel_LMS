<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview: {{ $blog->title }}</title>
    
    {{-- Vite Scripts / CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Google Material Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    
    {{-- CKEditor 5 Content Styles --}}
    <link rel="stylesheet" href="{{ asset('ckeditor5/ckeditor5.css') }}">
    <style>
        /* Optional overrides for CKEditor content if needed */
        .ck-content {
            font-family: inherit;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased selection:bg-primary/30 selection:text-primary min-h-screen">
    
    {{-- Preview Banner --}}
    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 text-center text-sm font-medium border-b border-yellow-200 sticky top-0 z-50 shadow-sm flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-base">visibility</span>
        This is a live preview. Your changes are not saved yet.
        <button onclick="window.close()" class="ml-4 underline hover:text-yellow-900 text-xs">Close Preview</button>
    </div>

    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6">
        <article class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
            <div class="p-8 sm:p-12 space-y-8">
                
                {{-- Meta --}}
                <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                    <img src="{{ $blog->author->avatar ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($blog->author->first_name) }}"
                        class="size-12 rounded-full object-cover border border-slate-200 dark:border-slate-700">
                    <div>
                        <p class="font-bold text-slate-900 dark:text-white capitalize">{{ $blog->author->first_name }} {{ $blog->author->last_name }}</p>
                        <p class="text-xs mt-0.5">
                            {{ $blog->created_at->format('M d, Y') }} 
                            <span class="mx-1">·</span> 
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $blog->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $blog->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Title --}}
                <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white leading-[1.15] tracking-tight">
                    {{ $blog->title }}
                </h1>

                {{-- Thumbnail --}}
                @if($blog->thumbnail_url)
                    <div class="w-full aspect-video rounded-2xl overflow-hidden shadow-md bg-slate-100 dark:bg-slate-900">
                        <img src="{{ $blog->thumbnail_url }}" class="w-full h-full object-cover">
                    </div>
                @endif

                {{-- Content --}}
                <div class="ck-content text-slate-700 dark:text-slate-300 leading-relaxed text-lg pt-4">
                    {!! $blog->content !!}
                </div>
                
            </div>
        </article>
    </main>

</body>
</html>
