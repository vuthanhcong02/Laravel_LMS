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
     * Automatically generate full URL for vocabulary audio from .env configured subdomain
     */
    public function getAudioUrlAttribute()
    {
        if (empty($this->audio_path)) {
            return null;
        }
        
        return Storage::disk('public')->url('hsk_media/audio/' . ltrim($this->audio_path, '/'));
    }
}
