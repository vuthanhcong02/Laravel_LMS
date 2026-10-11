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
     * Get dialogue section audio URL from B2 Storage.
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
