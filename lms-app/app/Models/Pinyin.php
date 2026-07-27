<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinyin extends Model
{
    use HasFactory;

    protected $fillable = [
        'initial_id', 'final_id', 'full', 'ipa', 
        'vietnamese_pronunciation', 'description', 'is_valid', 'is_special'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'is_special' => 'boolean',
    ];

    public function initial()
    {
        return $this->belongsTo(PinyinInitial::class, 'initial_id');
    }

    public function final()
    {
        return $this->belongsTo(PinyinFinal::class, 'final_id');
    }

    public function tones()
    {
        return $this->hasMany(PinyinTone::class, 'pinyin_id');
    }
}
