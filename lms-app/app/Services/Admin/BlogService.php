<?php

namespace App\Services\Admin;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogService
{
    /**
     * Get paginated blogs with optional filters.
     */
    public function getByCondition(array $filters)
    {
        return Blog::with(['author', 'category'])
            ->latest()
            ->when($filters['search'] ?? null, function ($q, $search) {
            $q->where('title', 'like', "%{$search}%");
        })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($q) use ($filters) {
            $q->where('is_published', (bool)$filters['status']);
        })
            ->when($filters['category_id'] ?? null, function ($q, $catId) {
            $q->where('category_id', $catId);
        })
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Store a new blog post.
     */
    public function store(array $data, $thumbnailFile = null): Blog
    {
        $data['author_id'] = Auth::id();
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        $data['is_published'] = isset($data['is_published']) && $data['is_published'];

        if ($thumbnailFile) {
            $data['thumbnail'] = $thumbnailFile->store('blogs/thumbnails', 'public');
        }

        return Blog::create($data);
    }

    /**
     * Update an existing blog post.
     */
    public function update(int $id, array $data, $thumbnailFile = null): Blog
    {
        $blog = Blog::findOrFail($id);

        $data['is_published'] = isset($data['is_published']) && $data['is_published'];

        // Regenerate slug only if title changed
        if ($data['title'] !== $blog->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $blog->id);
        }

        if ($thumbnailFile) {
            // Delete old thumbnail if it's a local file
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $data['thumbnail'] = $thumbnailFile->store('blogs/thumbnails', 'public');
        }
        else {
            unset($data['thumbnail']);
        }

        $blog->update($data);

        return $blog;
    }

    /**
     * Delete a blog post and its thumbnail.
     */
    public function destroy(int $id): void
    {
        $blog = Blog::findOrFail($id);

        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();
    }

    /**
     * Get all blog categories.
     */
    public function getCategories()
    {
        return Category::where('type', Category::TYPE_BLOG)->orderBy('name')->get();
    }

    /**
     * Generate a unique slug from a title.
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
        Blog::where('slug', $slug)
        ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
        ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /**
     * Build a preview of the blog post.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \stdClass
     */
    public function buildPreview($request): \stdClass
    {
        $blog = new \stdClass();
        $blog->title = $request->input('title') ?: 'Untitled Post';
        $blog->content = $request->input('content');
        $blog->is_published = $request->input('is_published', 0);
        $blog->thumbnail_url = $this->resolveThumbnailUrl($request);
        $blog->author = auth()->user();
        $blog->created_at = now();

        return $blog;
    }

    /**
     * Resolve thumbnail URL.
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    private function resolveThumbnailUrl($request): ?string
    {
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            return 'data:image/' . $file->extension() . ';base64,' . base64_encode(file_get_contents($file));
        }

        if ($request->input('old_thumbnail')) {
            return asset('storage/' . $request->input('old_thumbnail'));
        }

        return null;
    }

    /**
     * Handle CKEditor image upload.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function uploadImage($request): array
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('blogs/content', $fileName, 'public');
            
            return [
                'url' => asset('storage/' . $path)
            ];
        }

        return [
            'error' => [
                'message' => 'Upload failed.'
            ]
        ];
    }
}
