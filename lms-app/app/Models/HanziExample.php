<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HanziExample extends Model
{
    use HasFactory;

    protected $table = 'hanzi_examples';

    protected $fillable = [
        'character_id',
        'sentence_zh',
        'sentence_pinyin',
        'sentence_vi',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Chữ Hán gốc
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(HanziCharacter::class, 'character_id');
    }
}
