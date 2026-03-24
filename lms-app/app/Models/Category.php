<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const TYPE_COURSE = 1;

    const TYPE_BLOG = 2;

    protected $fillable = ['name', 'slug', 'type'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
