<?php

namespace App\Models;

use App\Services\Media\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    protected $fillable = [
        'course_id', 'lesson_id', 'teacher_id', 'title', 'description', 'attachments', 'due_date', 'status'
    ];

    protected $casts = [
        'attachments' => 'array',
        'due_date' => 'datetime',
    ];

    /**
     * Appended attributes for serialized arrays/JSON.
     */
    protected $appends = [
        'attachments_with_urls',
    ];

    /**
     * Return attachments with generated secure presigned URLs (60 minutes TTL).
     */
    public function getAttachmentsWithUrlsAttribute(): array
    {
        $attachments = $this->attachments ?? [];
        if (!is_array($attachments)) {
            return [];
        }

        return array_map(function ($file) {
            $path = $file['path'] ?? '';
            $file['url'] = !empty($path) ? assignmentFileUrl($path, 60) : '';
            return $file;
        }, $attachments);
    }


    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
