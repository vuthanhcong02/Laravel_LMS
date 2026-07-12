<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HskLessonDialogueSection extends Model
{
    use HasFactory;

    protected $table = 'hsk_lesson_dialogue_sections';

    protected $fillable = [
        'hsk_lesson_id',
        'title',
        'audio_path'
    ];

    protected $appends = ['audio_url'];

    public function lesson()
    {
        return $this->belongsTo(HskLesson::class, 'hsk_lesson_id');
    }

    public function dialogues()
    {
        return $this->hasMany(HskLessonDialogue::class, 'dialogue_section_id');
    }

    /**
     * Automatically generate full URL for dialogue audio from .env configured subdomain
     */
    public function getAudioUrlAttribute()
    {
        if (empty($this->audio_path)) {
            return null;
        }
        return asset('storage/hsk_media/audio/' . ltrim($this->audio_path, '/'));
    }
}
