<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskLessonPractice extends Model
{
    use HasFactory;

    protected $fillable = [
        'hsk_lesson_id',
        'type', // listening or reading
        'audio_path'
    ];

    public function lesson()
    {
        return $this->belongsTo(HskLesson::class, 'hsk_lesson_id');
    }

    public function sections()
    {
        return $this->hasMany(HskLessonPracticeSection::class, 'practice_id');
    }
}
