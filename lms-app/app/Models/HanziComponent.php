<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HanziComponent extends Model
{
    use HasFactory;

    protected $table = 'hanzi_components';

    protected $fillable = [
        'character_id',
        'component_char',
        'component_name',
        'role',
        'meaning',
        'explanation',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Chữ Hán chứa thành phần này
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(HanziCharacter::class, 'character_id');
    }
}
