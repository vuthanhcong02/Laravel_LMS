<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlashcardDeck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'color',
        'icon',
    ];

    /**
     * Get the user who owns the flashcard deck.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all custom flashcards belonging to this deck.
     */
    public function flashcards(): HasMany
    {
        return $this->hasMany(CustomFlashcard::class, 'deck_id')->orderBy('id', 'desc');
    }

    /**
     * Get the total count of cards in the deck.
     */
    public function getTotalCardsCountAttribute(): int
    {
        return $this->flashcards()->count();
    }

    /**
     * Get the count of remembered cards in the deck.
     */
    public function getRememberedCardsCountAttribute(): int
    {
        return $this->flashcards()->where('is_remembered', true)->count();
    }

    /**
     * Calculate progress percentage for this deck.
     */
    public function getProgressPercentageAttribute(): int
    {
        $total = $this->total_cards_count;
        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->remembered_cards_count / $total) * 100);
    }
}
