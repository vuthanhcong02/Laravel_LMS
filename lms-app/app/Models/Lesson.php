<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'record_url',
        'pdf_path',
        'note_file_path',
        'note_content',
        'order',
    ];

    /**
     * Course that owns this lesson.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Assignments associated with this lesson.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Public URL for lesson PDF slide/document.
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }

        return asset('storage/' . $this->pdf_path);
    }

    /**
     * Public URL for attached lesson notes/materials.
     */
    public function getNoteFileUrlAttribute(): ?string
    {
        if (!$this->note_file_path) {
            return null;
        }

        return asset('storage/' . $this->note_file_path);
    }

    /**
     * Effective video/record URL (prefers record_url with fallback to video_url).
     */
    public function getEffectiveRecordUrlAttribute(): ?string
    {
        return $this->record_url ?: $this->video_url;
    }
}
