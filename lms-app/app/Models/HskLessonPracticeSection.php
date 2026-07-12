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

    public function practice()
    {
        return $this->belongsTo(HskLessonPractice::class, 'practice_id');
    }

    public function questions()
    {
        return $this->hasMany(HskLessonPracticeQuestion::class, 'section_id');
    }
}
