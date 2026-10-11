<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HskLessonVocab extends Model
{
    use HasFactory;

    protected $table = 'hsk_lesson_vocabs';

    protected $fillable = [
        'hsk_lesson_id',
        'word',
        'pinyin',
        'type',
        'meaning',
        'audio_path'
    ];

    protected $appends = ['audio_url'];

    public function lesson()
    {
        return $this->belongsTo(HskLesson::class, 'hsk_lesson_id');
    }

    /**
     * Automatically generate full URL for vocabulary audio from B2
     */
    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio_path)) {
            return null;
        }

        $path = str_starts_with($this->audio_path, 'audio/') 
            ? $this->audio_path 
            : 'audio/' . ltrim($this->audio_path, '/');
        
        return hskMediaUrl($path);
    }
}
