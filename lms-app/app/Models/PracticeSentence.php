<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeSentence extends Model
{
    use HasFactory;

    /**
     * Database table name
     */
    protected $table = 'practice_sentences';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'topic_id',
        'order_index',
        'hanzi',
        'pinyin',
        'meaning',
        'meaning_vi',
        'audio_path',
        'duration',
        'words',
        'tokens',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'topic_id' => 'integer',
        'order_index' => 'integer',
        'duration' => 'integer',
        'words' => 'array',
        'tokens' => 'array',
    ];

    /**
     * Parent sentence topic
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(SentenceTopic::class, 'topic_id');
    }

    /**
     * Full public URL for browser audio playback
     */
    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio_path)) {
            return null;
        }

        return asset('storage/' . ltrim($this->audio_path, '/'));
    }
}
