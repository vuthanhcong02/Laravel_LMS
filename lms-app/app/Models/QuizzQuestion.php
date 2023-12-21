<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizzQuestion extends Model
{
    use HasFactory;
    protected $table = 'quizz_questions';
    protected $guarded = [];

    public function quizz()
    {
        return $this->belongsTo(Quizz::class);
    }
    public function answers()
    {
        return $this->hasMany(QuizzAnswer::class);
    }
}
