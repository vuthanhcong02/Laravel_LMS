<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFlashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'deck_id',
        'user_id',
        'word',
        'pinyin',
        'meaning',
        'example',
        'example_meaning',
        'is_remembered',
        'remembered_at',
    ];

    protected $casts = [
        'is_remembered' => 'boolean',
        'remembered_at' => 'datetime',
    ];

    /**
     * Get the deck this flashcard belongs to.
     */
    public function deck(): BelongsTo
    {
        return $this->belongsTo(FlashcardDeck::class, 'deck_id');
    }

    /**
     * Get the user who owns this flashcard.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
