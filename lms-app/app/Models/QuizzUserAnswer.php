<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizzUserAnswer extends Model
{
    use HasFactory;
    protected $table = 'quizz_user_answer';
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function quizz()
    {
        return $this->belongsTo(Quizz::class);
    }
    public function question()
    {
        return $this->belongsTo(QuizzQuestion::class);
    }
}
