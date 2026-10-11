<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskLessonDialogue extends Model
{
    use HasFactory;

    protected $table = 'hsk_lesson_dialogues';

    protected $fillable = [
        'dialogue_section_id',
        'role',
        'character',
        'pinyin',
        'translation',
        'audio_path'
    ];

    protected $appends = [
        'audio_url',
    ];

    /**
     * Get dialogue audio URL from B2 Storage.
     */
    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio_path)) {
            return null;
        }

        return hskMediaUrl($this->audio_path);
    }

    public function section()
    {
        return $this->belongsTo(HskLessonDialogueSection::class, 'dialogue_section_id');
    }
}
