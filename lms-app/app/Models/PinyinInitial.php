<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinyinInitial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'symbol', 'order', 'description'];

    public function pinyins()
    {
        return $this->hasMany(Pinyin::class, 'initial_id');
    }
}
