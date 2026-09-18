<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SentenceTopic extends Model
{
    use HasFactory;

    /**
     * Database table name
     */
    protected $table = 'sentence_topics';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'slug',
        'level',
        'title',
        'title_vi',
        'hanzi',
        'pinyin',
        'total_sentences',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'level' => 'integer',
        'total_sentences' => 'integer',
    ];

    /**
     * Practice sentences belonging to this topic
     */
    public function sentences(): HasMany
    {
        return $this->hasMany(PracticeSentence::class, 'topic_id')->orderBy('order_index');
    }
}
