<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskLesson extends Model
{
    use HasFactory;

    protected $table = 'hsk_lessons';

    protected $fillable = [
        'hsk_level_id',
        'lesson_number',
        'title',
        'pinyin',
        'translation',
        'code'
    ];

    public function level()
    {
        return $this->belongsTo(HskLevel::class, 'hsk_level_id');
    }

    public function vocabList()
    {
        return $this->hasMany(HskLessonVocab::class, 'hsk_lesson_id');
    }

    public function grammarList()
    {
        return $this->hasMany(HskLessonGrammar::class, 'hsk_lesson_id');
    }

    public function dialogueSections()
    {
        return $this->hasMany(HskLessonDialogueSection::class, 'hsk_lesson_id');
    }

    public function practices()
    {
        return $this->hasMany(HskLessonPractice::class, 'hsk_lesson_id');
    }
}
