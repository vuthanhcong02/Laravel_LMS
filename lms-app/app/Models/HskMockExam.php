<?php

namespace App\Models;

use App\Services\Media\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

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
            $extractedCode = null;
            if (preg_match('/\((\w+)\)$/', $exam->title, $matches)) {
                $extractedCode = $matches[1];
            } elseif (!empty($exam->audio_file) && preg_match('#hsk_mock_exams/(?:[A-Za-z0-9_-]+/)?(H\d+)#i', $exam->audio_file, $matches)) {
                $extractedCode = $matches[1];
            }

            // Clean up legacy local directory if exists
            if ($extractedCode) {
                $storagePath = storage_path('app/public/hsk_mock_exams/' . $extractedCode);
                if (File::isDirectory($storagePath)) {
                    File::deleteDirectory($storagePath);
                }
            }

            // Clean up Backblaze B2 directory
            try {
                $levelCode = strtoupper($exam->hskLevel->level_code ?? 'HSK');
                $examFolder = strtoupper($extractedCode ?: ('exam_' . $exam->id));
                app(MediaStorageService::class)->deleteDirectory("hsk_mock_exams/{$levelCode}/{$examFolder}");
            } catch (Throwable $e) {
                // Ignore cleanup errors on delete
            }
        });
    }
}
