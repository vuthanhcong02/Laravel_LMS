<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskLessonGrammar extends Model
{
    use HasFactory;

    protected $table = 'hsk_lesson_grammars';

    protected $fillable = [
        'hsk_lesson_id',
        'title',
        'formula',
        'explanation',
        'examples'
    ];

    protected $casts = [
        'examples' => 'array'
    ];

    public function lesson()
    {
        return $this->belongsTo(HskLesson::class, 'hsk_lesson_id');
    }
}
