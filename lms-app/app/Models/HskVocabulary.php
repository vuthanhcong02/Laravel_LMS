<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskVocabulary extends Model
{
    use HasFactory;

    protected $table = 'hsk_vocabularies';

    protected $fillable = [
        'word',
        'pinyin',
        'meaning',
        'meaning_en',
        'level',
        'topic',
        'hsk_version',
        'example',
        'example_meaning',
    ];

    /**
     * Many-to-many relationship with users table (users who learned this vocabulary).
     */
    public function usersWhoRemembered()
    {
        return $this->belongsToMany(User::class, 'user_remembered_vocabularies', 'hsk_vocabulary_id', 'user_id')
            ->withTimestamps();
    }
}
