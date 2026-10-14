<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamSection extends Model
{
    protected $fillable = [
        'hsk_mock_exam_id',
        'name',
        'skill_type',
        'description',
        'audio_file',
        'order_index',
    ];

    /**
     * The accessors to append to the model's array and JSON representation.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'audio_url',
    ];
    
    use HasFactory;

    public function mockExam()
    {
        return $this->belongsTo(HskMockExam::class, 'hsk_mock_exam_id');
    }

    /**
     * Get accessible URL for section audio file (90-minute TTL or local storage)
     */
    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio_file ? hsk_storage_url($this->audio_file) : null;
    }

    public function questionGroups()
    {
        return $this->hasMany(HskMockExamQuestionGroup::class)->orderBy('order_index');
    }
}
