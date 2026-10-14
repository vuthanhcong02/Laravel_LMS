<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    /**
     * The accessors to append to the model's array and JSON representation.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'audio_url',
    ];

    public function hskLevel()
    {
        return $this->belongsTo(HskLevel::class);
    }

    public function getFolderNameAttribute()
    {
        if (preg_match('/\((\w+)\)$/', $this->title, $matches)) {
            return $matches[1];
        }
        return Str::slug($this->title);
    }

    /**
     * Get accessible URL for exam audio file (90-minute TTL or local storage)
     */
    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio_file ? hsk_storage_url($this->audio_file) : null;
    }

    public function sections()
    {
        return $this->hasMany(HskMockExamSection::class)->orderBy('order_index');
    }

    public function results()
    {
        return $this->hasMany(HskMockExamResult::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($exam) {
            if (preg_match('/\((\w+)\)$/', $exam->title, $matches)) {
                $examId = $matches[1];
                $storagePath = storage_path('app/public/hsk_mock_exams/' . $examId);
                if (File::isDirectory($storagePath)) {
                    File::deleteDirectory($storagePath);
                }
            }
        });
    }
}
