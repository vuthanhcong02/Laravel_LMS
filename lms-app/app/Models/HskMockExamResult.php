<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HskMockExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hsk_mock_exam_id',
        'status',
        'started_at',
        'completed_at',
        'listening_score',
        'reading_score',
        'writing_score',
        'total_score',
    ];

    protected static function booted()
    {
        static::creating(function ($result) {
            $result->uuid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mockExam()
    {
        return $this->belongsTo(HskMockExam::class, 'hsk_mock_exam_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(HskMockExamUserAnswer::class, 'hsk_mock_exam_result_id');
    }
}
