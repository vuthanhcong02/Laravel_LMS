<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinyinWord extends Model
{
    use HasFactory;

    protected $fillable = ['pinyin_tone_id', 'word', 'pinyin', 'meaning', 'level'];

    public function pinyinTone()
    {
        return $this->belongsTo(PinyinTone::class, 'pinyin_tone_id');
    }
}
