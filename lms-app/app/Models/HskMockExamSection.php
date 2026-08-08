<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamSection extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public function mockExam()
    {
        return $this->belongsTo(HskMockExam::class);
    }

    public function questionGroups()
    {
        return $this->hasMany(HskMockExamQuestionGroup::class)->orderBy('order_index');
    }
}
