<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamQuestionGroup extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public function section()
    {
        return $this->belongsTo(HskMockExamSection::class, 'hsk_mock_exam_section_id');
    }

    public function questions()
    {
        return $this->hasMany(HskMockExamQuestion::class, 'hsk_mock_exam_group_id')->orderBy('order_index');
    }
}
