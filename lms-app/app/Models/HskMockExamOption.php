<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamOption extends Model
{
    protected $fillable = [
        'hsk_mock_exam_question_id',
        'content',
        'pinyin',
        'image',
        'is_correct',
        'order_index',
    ];
    
    protected $casts = [
        'is_correct' => 'boolean',
    ];
    
    use HasFactory;

    public function question()
    {
        return $this->belongsTo(HskMockExamQuestion::class, 'hsk_mock_exam_question_id');
    }
}
