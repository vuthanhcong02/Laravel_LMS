<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskLessonPracticeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'practice_id',
        'section_han',
        'section_vi',
        'audio_path',
        'image_path'
    ];

    protected $appends = [
        'image_url',
        'audio_url',
    ];

    /**
     * Get section image URL from B2 Storage.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        return hskMediaUrl($this->image_path);
    }

    /**
     * Get section audio URL from B2 Storage.
     */
    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio_path)) {
            return null;
        }

        return hskMediaUrl($this->audio_path);
    }

    public function practice()
    {
        return $this->belongsTo(HskLessonPractice::class, 'practice_id');
    }

    public function questions()
    {
        return $this->hasMany(HskLessonPracticeQuestion::class, 'section_id');
    }
}
