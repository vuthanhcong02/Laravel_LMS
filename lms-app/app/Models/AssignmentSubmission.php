<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_SUBMITTED = 1;
    const STATUS_GRADED = 2;

    protected $fillable = [
        'assignment_id', 'user_id', 'attachments', 'score', 'teacher_feedback', 'teacher_audio_path', 'teacher_attachments', 'status'
    ];

    protected $casts = [
        'attachments'         => 'array',
        'teacher_attachments' => 'array',
    ];

    /**
     * Không dùng $appends để tránh tạo B2 presigned URL tự động mỗi lần serialize.
     * Gọi thủ công accessor khi cần: $submission->formatted_attachments, v.v.
     *
     * protected $appends = ['formatted_attachments', 'formatted_teacher_attachments', 'teacher_audio_url'];
     */

    /**
     * Get presigned B2 URL for teacher feedback audio.
     */
    public function getTeacherAudioUrlAttribute(): ?string
    {
        return !empty($this->teacher_audio_path) ? assignmentFileUrl($this->teacher_audio_path) : null;
    }

    /**
     * Get attachments with presigned B2 URLs for frontend display.
     */
    public function getFormattedAttachmentsAttribute(): array
    {
        $attachments = $this->attachments ?? [];
        if (!is_array($attachments)) {
            return [];
        }

        return array_map(function ($att) {
            $path = $att['path'] ?? '';
            $att['url'] = !empty($path) ? assignmentFileUrl($path) : '';
            return $att;
        }, $attachments);
    }

    /**
     * Get teacher attachments with presigned B2 URLs for frontend display.
     */
    public function getFormattedTeacherAttachmentsAttribute(): array
    {
        $attachments = $this->teacher_attachments ?? [];
        if (!is_array($attachments)) {
            return [];
        }

        return array_map(function ($att) {
            $path = $att['path'] ?? '';
            $att['url'] = !empty($path) ? assignmentFileUrl($path) : '';
            return $att;
        }, $attachments);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
