<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HanziRadical extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hanzi_radicals';

    protected $fillable = [
        'code',
        'radical',
        'original',
        'sino_vietnamese',
        'pinyin',
        'stroke_count',
        'char_count',
        'tone',
        'meaning',
        'description',
        'word_type',
        'word_type_desc',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'stroke_count' => 'integer',
        'char_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Danh sách chữ Hán thuộc bộ thủ
     */
    public function characters(): HasMany
    {
        return $this->hasMany(HanziCharacter::class, 'radical_id');
    }

    /**
     * Scope lọc các bộ thủ đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope sắp xếp chuẩn theo số nét và thứ tự
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('stroke_count', 'asc')->orderBy('sort_order', 'asc');
    }
}
