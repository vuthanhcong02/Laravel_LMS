<?php

namespace App\Models;

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


    public function author()
    {
        return $this->belongsTo(User::class , 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
