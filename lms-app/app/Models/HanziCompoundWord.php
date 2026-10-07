<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HanziCompoundWord extends Model
{
    use HasFactory;

    protected $table = 'hanzi_compound_words';

    protected $fillable = [
        'character_id',
        'word',
        'pinyin',
        'sino_vietnamese',
        'meaning',
        'hsk_level',
        'sort_order',
    ];

    protected $casts = [
        'hsk_level' => 'integer',
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
