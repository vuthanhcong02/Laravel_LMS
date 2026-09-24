<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ImportCustomFlashcardsRequest;
use App\Http\Requests\Student\StoreCustomFlashcardRequest;
use App\Http\Requests\Student\StoreFlashcardDeckRequest;
use App\Services\Student\CustomFlashcardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomFlashcardController extends Controller
{
    public function __construct(
        protected CustomFlashcardService $customFlashcardService
    ) {}

    /**
     * List all custom decks for the authenticated user.
     */
    public function getDecks(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $decks = $this->customFlashcardService->getUserDecks($userId);

        return response()->json([
            'success' => true,
            'decks' => $decks,
        ]);
    }

    /**
     * Get a single deck with its flashcards.
     */
    public function getDeck(int $deckId): JsonResponse
    {
        $userId = auth()->id();
        $deck = $this->customFlashcardService->getDeckWithCards($deckId, $userId);

        if (!$deck) {
            return response()->json([
                'success' => false,
                'message' => __('Không tìm thấy bộ thẻ hoặc bạn không có quyền truy cập.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'deck' => $deck,
        ]);
    }

    /**
     * Store a new custom flashcard deck.
     */
    public function storeDeck(StoreFlashcardDeckRequest $request): JsonResponse
    {
        $deck = $this->customFlashcardService->createDeck(auth()->id(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Tạo bộ thẻ mới thành công!'),
            'deck' => $deck,
        ], 201);
    }

    /**
     * Update an existing deck.
     */
    public function updateDeck(StoreFlashcardDeckRequest $request, int $deckId): JsonResponse
    {
        $deck = $this->customFlashcardService->updateDeck($deckId, auth()->id(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Cập nhật thông tin bộ thẻ thành công!'),
            'deck' => $deck,
        ]);
    }

    /**
     * Delete a deck.
     */
    public function destroyDeck(int $deckId): JsonResponse
    {
        $this->customFlashcardService->deleteDeck($deckId, auth()->id());

        return response()->json([
            'success' => true,
            'message' => __('Đã xóa bộ thẻ thành công.'),
        ]);
    }

    /**
     * Add a new flashcard to a deck.
     */
    public function storeCard(StoreCustomFlashcardRequest $request, int $deckId): JsonResponse
    {
        $card = $this->customFlashcardService->createCard($deckId, auth()->id(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Thêm từ vựng mới thành công!'),
            'card' => $card,
        ], 201);
    }

    /**
     * Update an existing flashcard.
     */
    public function updateCard(StoreCustomFlashcardRequest $request, int $cardId): JsonResponse
    {
        $card = $this->customFlashcardService->updateCard($cardId, auth()->id(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Cập nhật từ vựng thành công!'),
            'card' => $card,
        ]);
    }

    /**
     * Delete a flashcard.
     */
    public function destroyCard(int $cardId): JsonResponse
    {
        $this->customFlashcardService->deleteCard($cardId, auth()->id());

        return response()->json([
            'success' => true,
            'message' => __('Đã xóa từ vựng khỏi bộ thẻ.'),
        ]);
    }

    /**
     * Toggle the learned status of a card and award EXP.
     */
    public function toggleRemember(int $cardId): JsonResponse
    {
        $result = $this->customFlashcardService->toggleRememberCard($cardId, auth()->id());

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Reset study progress for a deck.
     */
    public function resetProgress(int $deckId): JsonResponse
    {
        $this->customFlashcardService->resetDeckProgress($deckId, auth()->id());

        return response()->json([
            'success' => true,
            'message' => __('Đã đặt lại tiến độ học của bộ thẻ này.'),
        ]);
    }

    /**
     * Bulk import cards into a deck.
     */
    public function importCards(ImportCustomFlashcardsRequest $request, int $deckId): JsonResponse
    {
        $cards = $request->validated()['cards'];
        $result = $this->customFlashcardService->importCards($deckId, auth()->id(), $cards);

        $imported = $result['imported_count'];
        $skipped = $result['skipped_count'];

        if ($imported > 0 && $skipped > 0) {
            $message = __('Đã nhập thành công :imported từ mới (tự động bỏ qua :skipped từ trùng lặp)!', [
                'imported' => $imported,
                'skipped' => $skipped,
            ]);
        } elseif ($imported === 0 && $skipped > 0) {
            $message = __('Tất cả :skipped từ đều đã có sẵn trong bộ thẻ này.', [
                'skipped' => $skipped,
            ]);
        } else {
            $message = __('Nhập thành công :count từ vựng vào bộ thẻ!', [
                'count' => $imported,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'imported_count' => $imported,
            'skipped_count' => $skipped,
            'deck' => $result['deck'],
        ]);
    }
}
