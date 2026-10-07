<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HanziCharacter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hanzi_characters';

    protected $fillable = [
        'radical_id',
        'character',
        'unicode_hex',
        'pinyin',
        'sino_vietnamese',
        'meaning',
        'word_types',
        'hsk_level',
        'stroke_count',
        'tone',
        'formation_type',
        'radical_relation',
        'formation_desc',
        'mnemonic_poem',
        'mnemonic_tip',
        'sino_vietnamese_mirror',
        'traditional_quote',
        'naming_significance',
        'popularity_rank',
        'stroke_file_path',
        'is_published',
    ];

    protected $casts = [
        'hsk_level' => 'integer',
        'stroke_count' => 'integer',
        'popularity_rank' => 'integer',
        'is_published' => 'boolean',
        'word_types' => 'array',
    ];

    /**
     * Bộ thủ chính của chữ Hán
     */
    public function radical(): BelongsTo
    {
        return $this->belongsTo(HanziRadical::class, 'radical_id');
    }

    /**
     * Các thành phần chiết tự cấu tạo nên chữ
     */
    public function components(): HasMany
    {
        return $this->hasMany(HanziComponent::class, 'character_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Danh sách từ ghép thường gặp
     */
    public function compoundWords(): HasMany
    {
        return $this->hasMany(HanziCompoundWord::class, 'character_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Danh sách câu ví dụ
     */
    public function examples(): HasMany
    {
        return $this->hasMany(HanziExample::class, 'character_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Scope lọc chữ Hán đã xuất bản
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope lọc theo cấp độ HSK
     */
    public function scopeHsk($query, int $level)
    {
        return $query->where('hsk_level', $level);
    }
}
