<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamUserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'hsk_mock_exam_result_id',
        'hsk_mock_exam_question_id',
        'selected_option_id',
        'text_answer',
        'is_correct',
    ];

    public function result()
    {
        return $this->belongsTo(HskMockExamResult::class, 'hsk_mock_exam_result_id');
    }

    public function question()
    {
        return $this->belongsTo(HskMockExamQuestion::class, 'hsk_mock_exam_question_id');
    }

    public function option()
    {
        return $this->belongsTo(HskMockExamOption::class, 'selected_option_id');
    }
}
