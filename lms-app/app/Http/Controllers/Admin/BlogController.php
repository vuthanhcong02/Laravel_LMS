<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\BlogFilterRequest;
use App\Http\Requests\Admin\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\Blog\BlogUpdateRequest;
use App\Models\Blog;
use App\Services\Admin\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(protected BlogService $blogService)
    {
    }

    public function index(BlogFilterRequest $request)
    {
        $blogs = $this->blogService->getByCondition($request->validated());
        $categories = $this->blogService->getCategories();
        $stats = [
            'total' => Blog::count(),
            'published' => Blog::published()->count(),
            'draft' => Blog::draft()->count(),
        ];

        return view('portal.admin.blogs.index', compact('blogs', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = $this->blogService->getCategories();
        return view('portal.admin.blogs.create', compact('categories'));
    }

    public function store(BlogStoreRequest $request)
    {
        $this->blogService->store($request->validated(), $request->file('thumbnail'));

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = $this->blogService->getCategories();

        return view('portal.admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(BlogUpdateRequest $request, Blog $blog)
    {
        $this->blogService->update($blog->id, $request->validated(), $request->file('thumbnail'));

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->blogService->destroy($blog->id);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }

    public function preview(Request $request)
    {
        $blog = $this->blogService->buildPreview($request);

        return view('portal.admin.blogs.preview', compact('blog'));
    }

    public function upload(Request $request)
    {
        $result = $this->blogService->uploadImage($request);
        return response()->json($result);
    }
}
