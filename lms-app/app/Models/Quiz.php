<?php

namespace App\Models;

use App\Enums\QuizType;
use App\Models\Question;
use App\Services\Media\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id', 'title', 'type', 'time_limit', 'due_date', 'audio_path'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => QuizType::class,
        'due_date' => 'datetime',
    ];

    /**
     * Accessor to get public URL of global audio file
     */
    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio_path ? app(MediaStorageService::class)->url($this->audio_path) : null;
    }

    /**
     * Get the course that owns the quiz.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
