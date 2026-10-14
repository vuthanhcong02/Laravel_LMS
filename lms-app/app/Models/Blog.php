<?php

namespace App\Models;

use App\Services\Media\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    // status
    const STATUS_DRAFT = 0;

    const STATUS_PUBLISHED = 1;

    protected $fillable = [
        'author_id', 'category_id', 'title', 'slug', 'thumbnail', 'content', 'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array and JSON representation.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'thumbnail_url',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    // Accessor
    public function getStatusLabelAttribute(): string
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    /**
     * Get blog thumbnail URL from B2 or fallback to default image.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $defaultThumbnail = 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?auto=format&fit=crop&w=800&q=80';
        return app(MediaStorageService::class)->url($this->thumbnail, 10080, $defaultThumbnail);
    }


    public function author()
    {
        return $this->belongsTo(User::class , 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
