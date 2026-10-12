<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'user_id', 'score', 'raw_score', 'total_marks', 'grading_status', 'started_at', 'completed_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'float',
        'raw_score' => 'float',
        'total_marks' => 'float',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'attempt_id');
    }

    /**
     * Compute attempt status (in_progress, abandoned, needs_grading, completed)
     */
    public function getStatusAttribute(): string
    {
        if (!$this->completed_at) {
            return 'in_progress';
        }

        if ($this->answers()->count() === 0 && ((float) $this->score === 0.0 || $this->score === null)) {
            return 'abandoned';
        }

        if ($this->grading_status === 'needs_grading') {
            return 'needs_grading';
        }

        return 'completed';
    }
}
