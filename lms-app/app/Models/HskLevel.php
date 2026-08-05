<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskLevel extends Model
{
    use HasFactory;

    protected $table = 'hsk_levels';

    protected $fillable = [
        'level_code',
        'title',
        'slug',
        'subtitle',
        'description',
        'color',
        'lessons_count',
        'vocab_count',
        'duration',
        'spine_color',
        'cover_bg',
        'number_color',
        'exam_structure'
    ];

    protected $casts = [
        'exam_structure' => 'array',
    ];

    public function lessons()
    {
        return $this->hasMany(HskLesson::class, 'hsk_level_id');
    }

    public function mockExams()
    {
        return $this->hasMany(HskMockExam::class, 'hsk_level_id');
    }
}
