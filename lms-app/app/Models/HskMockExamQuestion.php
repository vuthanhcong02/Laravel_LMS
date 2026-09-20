<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExamQuestion extends Model
{
    protected $fillable = [
        'hsk_mock_exam_group_id',
        'hsk_mock_exam_section_id',
        'question_type',
        'title',
        'pinyin',
        'image',
        'audio_file',
        'points',
        'explanation',
        'order_index',
        'is_example',
    ];
    
    protected $casts = [
        'is_example' => 'boolean',
    ];
    
    use HasFactory;

    public function group()
    {
        return $this->belongsTo(HskMockExamQuestionGroup::class, 'hsk_mock_exam_group_id');
    }

    public function options()
    {
        return $this->hasMany(HskMockExamOption::class)->orderBy('order_index');
    }

    public function hskMockExamSection()
    {
        return $this->belongsTo(HskMockExamSection::class, 'hsk_mock_exam_section_id');
    }

    /**
     * Get explanation text for current or specified locale with fallback
     */
    public function getExplanationText(?string $locale = null): ?string
    {
        if (empty($this->explanation)) {
            return null;
        }

        $locale = $locale ?: app()->getLocale();
        $data = is_array($this->explanation) ? $this->explanation : json_decode($this->explanation, true);

        if (is_array($data)) {
            return $data[$locale] ?? $data['vi'] ?? $data['en'] ?? $data['zh'] ?? (is_string(reset($data)) ? reset($data) : null);
        }

        return (string) $this->explanation;
    }

    /**
     * Get normalized explanation translations array [vi, en, zh]
     */
    public function getExplanationTranslationsAttribute(): array
    {
        if (empty($this->explanation)) {
            return ['vi' => '', 'en' => '', 'zh' => ''];
        }

        $data = is_array($this->explanation) ? $this->explanation : json_decode($this->explanation, true);

        if (is_array($data)) {
            return [
                'vi' => $data['vi'] ?? '',
                'en' => $data['en'] ?? '',
                'zh' => $data['zh'] ?? '',
            ];
        }

        return [
            'vi' => (string) $this->explanation,
            'en' => '',
            'zh' => '',
        ];
    }
}
