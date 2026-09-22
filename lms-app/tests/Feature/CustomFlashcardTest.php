<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\CustomFlashcard;
use App\Models\FlashcardDeck;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CustomFlashcardTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user1;
    protected User $user2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);

        $this->user1 = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'exp_total' => 0,
        ]);

        $this->user2 = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'exp_total' => 0,
        ]);
    }

    /**
     * Test flashcard page renders successfully for guest and authenticated user.
     */
    public function test_flashcard_page_loads_successfully(): void
    {
        $guestResponse = $this->get(route('flashcards'));
        $guestResponse->assertStatus(200);

        $userResponse = $this->actingAs($this->user1)->get(route('flashcards'));
        $userResponse->assertStatus(200);
    }

    /**
     * Test authenticated user can create a flashcard deck.
     */
    public function test_user_can_create_flashcard_deck(): void
    {
        $response = $this->actingAs($this->user1)->postJson(route('custom-flashcards.decks.store'), [
            'title' => 'My Restaurant Vocabulary',
            'description' => 'Words used when ordering food',
            'color' => '#e07a5f',
            'icon' => 'fa-utensils',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('flashcard_decks', [
            'user_id' => $this->user1->id,
            'title' => 'My Restaurant Vocabulary',
        ]);
    }

    /**
     * Test user cannot access another user's deck.
     */
    public function test_user_cannot_access_other_users_deck(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Secret Deck',
            'color' => '#3b82f6',
        ]);

        $response = $this->actingAs($this->user2)->getJson(route('custom-flashcards.decks.show', $deck->id));

        $response->assertStatus(404);
    }

    /**
     * Test user can add card to deck.
     */
    public function test_user_can_add_card_to_deck(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Daily Words',
            'color' => '#10b981',
        ]);

        $response = $this->actingAs($this->user1)->postJson(route('custom-flashcards.cards.store', $deck->id), [
            'word' => '苹果',
            'pinyin' => 'píng guǒ',
            'meaning' => 'Quả táo',
            'example' => '我喜欢吃苹果。',
            'example_meaning' => 'Tôi thích ăn táo.',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('custom_flashcards', [
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '苹果',
        ]);
    }

    /**
     * Test user cannot add card to another user's deck.
     */
    public function test_user_cannot_add_card_to_another_users_deck(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'User1 Deck',
            'color' => '#10b981',
        ]);

        $response = $this->actingAs($this->user2)->postJson(route('custom-flashcards.cards.store', $deck->id), [
            'word' => '香蕉',
            'pinyin' => 'xiāng jiāo',
            'meaning' => 'Quả chuối',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test toggle remember status and awarding EXP.
     */
    public function test_toggle_remember_card_awards_exp(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Study Deck',
        ]);

        $card = CustomFlashcard::create([
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '电脑',
            'pinyin' => 'diàn nǎo',
            'meaning' => 'Máy tính',
            'is_remembered' => false,
        ]);

        $response = $this->actingAs($this->user1)->postJson(route('custom-flashcards.cards.toggle-remember', $card->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'is_remembered' => true,
                ],
            ]);

        $this->assertDatabaseHas('custom_flashcards', [
            'id' => $card->id,
            'is_remembered' => true,
        ]);

        // Check user gained EXP
        $this->user1->refresh();
        $this->assertGreaterThan(0, $this->user1->exp_total);
    }

    /**
     * Test reset deck learning progress.
     */
    public function test_user_can_reset_deck_progress(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Reset Deck',
        ]);

        CustomFlashcard::create([
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '书',
            'pinyin' => 'shū',
            'meaning' => 'Sách',
            'is_remembered' => true,
            'remembered_at' => now(),
        ]);

        $response = $this->actingAs($this->user1)->postJson(route('custom-flashcards.decks.reset', $deck->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('custom_flashcards', [
            'deck_id' => $deck->id,
            'is_remembered' => false,
            'remembered_at' => null,
        ]);
    }
}
