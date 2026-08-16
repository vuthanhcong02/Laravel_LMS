<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'hsk_level_id',
        'title',
        'duration',
        'total_questions',
        'total_score',
        'pass_score',
        'audio_file',
        'view_count',
        'attempt_count',
        'is_published',
    ];

    public function hskLevel()
    {
        return $this->belongsTo(HskLevel::class);
    }

    public function sections()
    {
        return $this->hasMany(HskMockExamSection::class)->orderBy('order_index');
    }

    public function results()
    {
        return $this->hasMany(HskMockExamResult::class);
    }
}
