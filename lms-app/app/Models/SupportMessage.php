<?php

namespace App\Models;

use App\Services\Media\MediaStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment_path',
    ];

    protected $appends = [
        'attachment_url',
    ];

    public function getAttachmentUrlAttribute(): ?string
    {
        if (empty($this->attachment_path)) {
            return null;
        }

        return app(MediaStorageService::class)->url($this->attachment_path);
    }

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
