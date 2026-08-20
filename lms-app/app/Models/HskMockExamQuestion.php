<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamQuestion extends Model
{
    protected $fillable = [
        'hsk_mock_exam_group_id',
        'hsk_mock_exam_section_id',
        'question_type',
        'title',
        'pinyin',
        'image',
        'audio_file',
        'points',
        'explanation',
        'order_index',
        'is_example',
    ];
    
    protected $casts = [
        'is_example' => 'boolean',
    ];
    
    use HasFactory;

    public function group()
    {
        return $this->belongsTo(HskMockExamQuestionGroup::class, 'hsk_mock_exam_group_id');
    }

    public function options()
    {
        return $this->hasMany(HskMockExamOption::class)->orderBy('order_index');
    }

    public function hskMockExamSection()
    {
        return $this->belongsTo(HskMockExamSection::class, 'hsk_mock_exam_section_id');
    }
}
