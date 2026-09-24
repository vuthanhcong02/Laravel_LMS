<?php

namespace App\Services\Student;

use App\Models\CustomFlashcard;
use App\Models\FlashcardDeck;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Overtrue\Pinyin\Pinyin;

class CustomFlashcardService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Retrieve all decks belonging to a user with statistics.
     */
    public function getUserDecks(?int $userId): Collection
    {
        if (!$userId) {
            return new Collection();
        }

        return FlashcardDeck::where('user_id', $userId)
            ->withCount([
                'flashcards as total_cards',
                'flashcards as remembered_cards' => function ($query) {
                    $query->where('is_remembered', true);
                }
            ])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($deck) {
                $total = $deck->total_cards ?? 0;
                $remembered = $deck->remembered_cards ?? 0;
                $deck->progress_percentage = $total > 0 ? (int) round(($remembered / $total) * 100) : 0;
                return $deck;
            });
    }

    /**
     * Find a deck by ID ensuring ownership by the specified user.
     */
    public function getDeckWithCards(int $deckId, int $userId): ?FlashcardDeck
    {
        $deck = FlashcardDeck::where('id', $deckId)
            ->where('user_id', $userId)
            ->with(['flashcards'])
            ->first();

        if ($deck) {
            $total = $deck->flashcards->count();
            $remembered = $deck->flashcards->where('is_remembered', true)->count();
            $deck->total_cards_count = $total;
            $deck->remembered_cards_count = $remembered;
            $deck->progress_percentage = $total > 0 ? (int) round(($remembered / $total) * 100) : 0;
        }

        return $deck;
    }

    /**
     * Create a new custom flashcard deck.
     */
    public function createDeck(int $userId, array $data): FlashcardDeck
    {
        return FlashcardDeck::create([
            'user_id' => $userId,
            'title' => trim($data['title']),
            'description' => isset($data['description']) ? trim($data['description']) : null,
            'color' => $data['color'] ?? '#e07a5f',
            'icon' => $data['icon'] ?? 'fa-layer-group',
        ]);
    }

    /**
     * Update an existing flashcard deck.
     */
    public function updateDeck(int $deckId, int $userId, array $data): FlashcardDeck
    {
        $deck = FlashcardDeck::where('id', $deckId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $deck->update([
            'title' => trim($data['title']),
            'description' => isset($data['description']) ? trim($data['description']) : null,
            'color' => $data['color'] ?? $deck->color,
            'icon' => $data['icon'] ?? $deck->icon,
        ]);

        return $deck;
    }

    /**
     * Delete a deck and all cards associated with it.
     */
    public function deleteDeck(int $deckId, int $userId): bool
    {
        $deck = FlashcardDeck::where('id', $deckId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return (bool) $deck->delete();
    }

    /**
     * Add a new card to a specific deck.
     */
    public function createCard(int $deckId, int $userId, array $data): CustomFlashcard
    {
        // Ensure the deck belongs to the user
        $deck = FlashcardDeck::where('id', $deckId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return CustomFlashcard::create([
            'deck_id' => $deck->id,
            'user_id' => $userId,
            'word' => trim($data['word']),
            'pinyin' => trim($data['pinyin']),
            'meaning' => trim($data['meaning']),
            'example' => isset($data['example']) ? trim($data['example']) : null,
            'example_meaning' => isset($data['example_meaning']) ? trim($data['example_meaning']) : null,
            'is_remembered' => false,
        ]);
    }

    /**
     * Update an existing card.
     */
    public function updateCard(int $cardId, int $userId, array $data): CustomFlashcard
    {
        $card = CustomFlashcard::where('id', $cardId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $card->update([
            'word' => trim($data['word']),
            'pinyin' => trim($data['pinyin']),
            'meaning' => trim($data['meaning']),
            'example' => isset($data['example']) ? trim($data['example']) : null,
            'example_meaning' => isset($data['example_meaning']) ? trim($data['example_meaning']) : null,
        ]);

        return $card;
    }

    /**
     * Delete a specific card.
     */
    public function deleteCard(int $cardId, int $userId): bool
    {
        $card = CustomFlashcard::where('id', $cardId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return (bool) $card->delete();
    }

    /**
     * Toggle the remembered status of a custom flashcard and award EXP if newly learned.
     */
    public function toggleRememberCard(int $cardId, int $userId): array
    {
        $card = CustomFlashcard::where('id', $cardId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $card->is_remembered = !$card->is_remembered;
        $card->remembered_at = $card->is_remembered ? now() : null;
        $card->save();

        $expAwarded = null;
        if ($card->is_remembered) {
            $user = User::find($userId);
            if ($user) {
                $expAwarded = $this->gamificationService->awardExp($user, 'flashcard_remember', $card->id);
            }
        }

        // Recalculate deck stats
        $deckStats = CustomFlashcard::where('deck_id', $card->deck_id)
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN is_remembered = 1 THEN 1 ELSE 0 END) as remembered')
            ->first();

        $total = $deckStats->total ?? 0;
        $remembered = $deckStats->remembered ?? 0;
        $progress = $total > 0 ? (int) round(($remembered / $total) * 100) : 0;

        return [
            'card_id' => $card->id,
            'is_remembered' => $card->is_remembered,
            'deck_id' => $card->deck_id,
            'total_cards' => $total,
            'remembered_cards' => (int) $remembered,
            'progress_percentage' => $progress,
            'exp_awarded' => $expAwarded,
        ];
    }

    /**
     * Reset the learning progress for an entire deck.
     */
    public function resetDeckProgress(int $deckId, int $userId): bool
    {
        $deck = FlashcardDeck::where('id', $deckId)
            ->where('user_id', $userId)
            ->firstOrFail();

        CustomFlashcard::where('deck_id', $deck->id)
            ->where('user_id', $userId)
            ->update([
                'is_remembered' => false,
                'remembered_at' => null,
            ]);

        return true;
    }

    /**
     * Batch import multiple vocabulary cards into a deck, automatically skipping duplicate words.
     */
    public function importCards(int $deckId, int $userId, array $cards): array
    {
        $deck = FlashcardDeck::where('id', $deckId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $now = now();
        $importedCount = 0;
        $skippedCount = 0;

        // Fetch existing words in this deck (normalized to lowercase) to skip duplicates
        $existingWords = CustomFlashcard::where('deck_id', $deck->id)
            ->pluck('word')
            ->map(fn($w) => mb_strtolower(trim($w)))
            ->flip()
            ->toArray();

        DB::transaction(function () use ($deck, $userId, $cards, $now, &$importedCount, &$skippedCount, &$existingWords) {
            $insertBatch = [];

            foreach ($cards as $item) {
                $word = trim($item['word'] ?? '');
                $meaning = trim($item['meaning'] ?? '');

                if (empty($word) || empty($meaning)) {
                    continue;
                }

                $wordKey = mb_strtolower($word);

                // Automatically skip duplicates (already in deck or duplicated within the same import payload)
                if (isset($existingWords[$wordKey])) {
                    $skippedCount++;
                    continue;
                }

                // Register word key to avoid duplicates within the same batch
                $existingWords[$wordKey] = true;

                // If pinyin is not provided, generate automatically
                $pinyin = trim($item['pinyin'] ?? '');
                if (empty($pinyin)) {
                    try {
                        $pinyin = Pinyin::sentence($word);
                    } catch (\Throwable $e) {
                        $pinyin = '';
                    }
                }

                $insertBatch[] = [
                    'deck_id' => $deck->id,
                    'user_id' => $userId,
                    'word' => $word,
                    'pinyin' => $pinyin,
                    'meaning' => $meaning,
                    'example' => !empty($item['example']) ? trim($item['example']) : null,
                    'example_meaning' => !empty($item['example_meaning']) ? trim($item['example_meaning']) : null,
                    'is_remembered' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $importedCount++;
            }

            if (!empty($insertBatch)) {
                // Insert in chunks of 100 for database efficiency
                foreach (array_chunk($insertBatch, 100) as $chunk) {
                    CustomFlashcard::insert($chunk);
                }
            }
        });

        // Retrieve fresh deck with cards for responsive frontend state
        $updatedDeck = $this->getDeckWithCards($deck->id, $userId);

        return [
            'imported_count' => $importedCount,
            'skipped_count' => $skippedCount,
            'deck' => $updatedDeck,
        ];
    }
}
