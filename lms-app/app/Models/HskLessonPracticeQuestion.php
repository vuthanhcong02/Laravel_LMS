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

    public function section()
    {
        return $this->belongsTo(HskLessonPracticeSection::class, 'section_id');
    }
}
