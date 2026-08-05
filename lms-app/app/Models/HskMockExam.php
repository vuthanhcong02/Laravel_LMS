<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HskMockExam extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hskLevel()
    {
        return $this->belongsTo(HskLevel::class);
    }
}
