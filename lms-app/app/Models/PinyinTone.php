<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinyinTone extends Model
{
    use HasFactory;

    protected $fillable = ['pinyin_id', 'tone', 'display', 'audio'];

    public function pinyin()
    {
        return $this->belongsTo(Pinyin::class, 'pinyin_id');
    }

    public function examples()
    {
        return $this->hasMany(PinyinExample::class, 'pinyin_tone_id');
    }

    public function words()
    {
        return $this->hasMany(PinyinWord::class, 'pinyin_tone_id');
    }
}
