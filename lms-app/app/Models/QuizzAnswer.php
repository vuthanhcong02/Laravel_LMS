<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizzAnswer extends Model
{
    use HasFactory;
    protected $table = 'quizz_answers';
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(QuizzQuestion::class);
    }
}
