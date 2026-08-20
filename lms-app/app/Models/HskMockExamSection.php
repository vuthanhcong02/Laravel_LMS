<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamSection extends Model
{
    protected $fillable = [
        'hsk_mock_exam_id',
        'name',
        'skill_type',
        'description',
        'audio_file',
        'order_index',
    ];
    
    use HasFactory;

    public function mockExam()
    {
        return $this->belongsTo(HskMockExam::class, 'hsk_mock_exam_id');
    }

    public function questionGroups()
    {
        return $this->hasMany(HskMockExamQuestionGroup::class)->orderBy('order_index');
    }
}
