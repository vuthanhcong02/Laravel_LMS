<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinyinExample extends Model
{
    use HasFactory;

    protected $fillable = ['pinyin_tone_id', 'hanzi', 'pinyin', 'meaning', 'level'];

    public function pinyinTone()
    {
        return $this->belongsTo(PinyinTone::class, 'pinyin_tone_id');
    }
}
