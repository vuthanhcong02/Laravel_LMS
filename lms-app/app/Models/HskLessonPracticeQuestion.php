<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskLessonPracticeQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'ques_id',
        'ques_type',
        'question',
        'context',
        'question_pinyin',
        'question_segments',
        'options',
        'sub_questions',
        'correct_answer',
        'image_path'
    ];

    protected $casts = [
        'options' => 'array',
        'sub_questions' => 'array',
        'question_segments' => 'array'
    ];

    protected $appends = [
        'image_url',
    ];

    /**
     * Get question image URL from B2 Storage.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        return hskMediaUrl($this->image_path);
    }

    public function section()
    {
        return $this->belongsTo(HskLessonPracticeSection::class, 'section_id');
    }
}
