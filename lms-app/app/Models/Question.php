<?php

namespace App\Models;

use App\Enums\QuestionType;
use App\Services\Media\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id', 'type', 'question_text', 'marks', 'image_path', 'audio_path',
        'essay_grading_type', 'correct_answer_text', 'case_sensitive'
    ];

    protected $appends = [
        'image_url',
        'audio_url',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        return app(MediaStorageService::class)->url($this->image_path);
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio_path)) {
            return null;
        }

        return app(MediaStorageService::class)->url($this->audio_path);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => QuestionType::class,
        'case_sensitive' => 'boolean',
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
