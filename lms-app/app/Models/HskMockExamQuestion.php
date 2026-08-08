<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamQuestion extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public function group()
    {
        return $this->belongsTo(HskMockExamQuestionGroup::class, 'hsk_mock_exam_group_id');
    }

    public function options()
    {
        return $this->hasMany(HskMockExamOption::class)->orderBy('order_index');
    }
}
