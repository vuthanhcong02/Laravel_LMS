<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinyinFinal extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'order', 'description'];

    public function pinyins()
    {
        return $this->hasMany(Pinyin::class, 'final_id');
    }
}
