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
        'assignment_id', 'user_id', 'attachments', 'score', 'teacher_feedback', 'teacher_audio_path', 'status'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
