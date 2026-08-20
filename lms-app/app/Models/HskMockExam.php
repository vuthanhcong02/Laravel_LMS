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
