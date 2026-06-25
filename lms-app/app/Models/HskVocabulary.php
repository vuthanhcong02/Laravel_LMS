<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskVocabulary extends Model
{
    use HasFactory;

    protected $table = 'hsk_vocabularies';

    protected $fillable = [
        'word',
        'pinyin',
        'meaning',
        'meaning_en',
        'level',
        'topic',
        'hsk_version',
        'example',
        'example_meaning',
    ];
}
