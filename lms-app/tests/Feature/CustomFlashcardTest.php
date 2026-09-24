<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\CustomFlashcard;
use App\Models\FlashcardDeck;
use App\Models\HskVocabulary;
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

    /**
     * Test user can import multiple cards to deck with auto pinyin.
     */
    public function test_user_can_import_cards_to_deck(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Import Target Deck',
        ]);

        $payload = [
            'cards' => [
                [
                    'word' => '你好',
                    'meaning' => 'Xin chào',
                    // pinyin omitted to test auto generation
                ],
                [
                    'word' => '谢谢',
                    'pinyin' => 'xièxie',
                    'meaning' => 'Cảm ơn bạn',
                    'example' => '谢谢你的帮助。',
                    'example_meaning' => 'Cảm ơn sự giúp đỡ của bạn.',
                ],
            ],
        ];

        $response = $this->actingAs($this->user1)->postJson(route('custom-flashcards.decks.import', $deck->id), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'imported_count' => 2,
            ]);

        $this->assertDatabaseHas('custom_flashcards', [
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '你好',
            'meaning' => 'Xin chào',
        ]);

        $this->assertDatabaseHas('custom_flashcards', [
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '谢谢',
            'pinyin' => 'xièxie',
        ]);
    }

    /**
     * Test user cannot import cards into another user's deck.
     */
    public function test_user_cannot_import_to_other_users_deck(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'User1 Deck',
        ]);

        $response = $this->actingAs($this->user2)->postJson(route('custom-flashcards.decks.import', $deck->id), [
            'cards' => [
                ['word' => '入侵', 'meaning' => 'Xâm nhập trái phép'],
            ],
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test import cards validates required fields.
     */
    public function test_import_cards_validates_required_fields(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Validate Deck',
        ]);

        $response = $this->actingAs($this->user1)->postJson(route('custom-flashcards.decks.import', $deck->id), [
            'cards' => [
                ['word' => '', 'meaning' => ''],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cards.0.word', 'cards.0.meaning']);
    }

    /**
     * Test import cards automatically skips duplicate words already in deck or repeated in payload.
     */
    public function test_import_cards_automatically_skips_duplicate_words(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Dedup Deck',
        ]);

        // Existing word in deck
        CustomFlashcard::create([
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '苹果',
            'pinyin' => 'píngguǒ',
            'meaning' => 'Quả táo',
        ]);

        $payload = [
            'cards' => [
                ['word' => '苹果', 'meaning' => 'Quả táo (duplicate)'], // Duplicate with existing in deck
                ['word' => '香蕉', 'meaning' => 'Quả chuối'], // New word #1
                ['word' => '香蕉', 'meaning' => 'Quả chuối lặp'], // Duplicate within same payload
                ['word' => '西瓜', 'meaning' => 'Dưa hấu'], // New word #2
            ],
        ];

        $response = $this->actingAs($this->user1)->postJson(route('custom-flashcards.decks.import', $deck->id), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'imported_count' => 2,
                'skipped_count' => 2,
            ]);

        // Total cards in deck should be 3 (1 initial + 2 imported, 0 duplicates)
        $this->assertEquals(3, CustomFlashcard::where('deck_id', $deck->id)->count());
    }

    /**
     * Test guest cannot access check word in decks endpoint.
     */
    public function test_guest_cannot_access_check_word_in_decks(): void
    {
        $response = $this->getJson(route('custom-flashcards.decks.check-word', ['word' => '你好']));
        $response->assertStatus(401);
    }

    /**
     * Test guest cannot add HSK word to deck.
     */
    public function test_guest_cannot_add_hsk_word_to_deck(): void
    {
        $response = $this->postJson(route('custom-flashcards.decks.add-hsk-word', 1), [
            'word' => '你好',
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test user can check whether a word exists in their decks.
     */
    public function test_user_can_check_word_in_decks_with_status_indication(): void
    {
        // Deck 1 has the word '你好'
        $deck1 = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Deck 1',
        ]);
        CustomFlashcard::create([
            'deck_id' => $deck1->id,
            'user_id' => $this->user1->id,
            'word' => '你好',
            'pinyin' => 'nǐhǎo',
            'meaning' => 'Xin chào',
        ]);

        // Deck 2 does not have the word '你好'
        $deck2 = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Deck 2',
        ]);

        $response = $this->actingAs($this->user1)
            ->getJson(route('custom-flashcards.decks.check-word', ['word' => '你好']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $decks = collect($response->json('decks'));
        $checkedDeck1 = $decks->firstWhere('id', $deck1->id);
        $checkedDeck2 = $decks->firstWhere('id', $deck2->id);

        $this->assertNotNull($checkedDeck1);
        $this->assertTrue($checkedDeck1['has_word']);

        $this->assertNotNull($checkedDeck2);
        $this->assertFalse($checkedDeck2['has_word']);
    }

    /**
     * Test check word in decks validates required word parameter via FormRequest.
     */
    public function test_check_word_in_decks_validates_required_word(): void
    {
        $response = $this->actingAs($this->user1)
            ->getJson(route('custom-flashcards.decks.check-word'));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['word']);
    }

    /**
     * Test user can add an HSK vocabulary item using hsk_vocabulary_id.
     */
    public function test_user_can_add_hsk_word_with_hsk_vocabulary_id(): void
    {
        $hsk = HskVocabulary::create([
            'word' => '火车站',
            'pinyin' => 'huǒchēzhàn',
            'meaning' => 'Ga tàu hỏa',
            'level' => 1,
            'hsk_version' => '3.0',
            'example' => '我在火车站等你。',
            'example_meaning' => 'Tôi đợi bạn ở ga tàu hỏa.',
        ]);

        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Travel Deck',
        ]);

        $response = $this->actingAs($this->user1)
            ->postJson(route('custom-flashcards.decks.add-hsk-word', $deck->id), [
                'hsk_vocabulary_id' => $hsk->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'already_exists' => false,
            ]);

        $this->assertDatabaseHas('custom_flashcards', [
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '火车站',
            'pinyin' => 'huǒchēzhàn',
            'meaning' => 'Ga tàu hỏa',
            'example' => '我在火车站等你。',
            'example_meaning' => 'Tôi đợi bạn ở ga tàu hỏa.',
        ]);
    }

    /**
     * Test user can add word with custom payload without hsk_vocabulary_id.
     */
    public function test_user_can_add_word_with_custom_payload(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Custom Word Deck',
        ]);

        $response = $this->actingAs($this->user1)
            ->postJson(route('custom-flashcards.decks.add-hsk-word', $deck->id), [
                'word' => '咖啡馆',
                'pinyin' => 'kāfēiguǎn',
                'meaning' => 'Quán cà phê',
                'example' => '我们去咖啡馆吧。',
                'example_meaning' => 'Chúng ta đi quán cà phê nhé.',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'already_exists' => false,
            ]);

        $this->assertDatabaseHas('custom_flashcards', [
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '咖啡馆',
            'meaning' => 'Quán cà phê',
        ]);
    }

    /**
     * Test adding identical word to the same deck prevents duplicate cards.
     */
    public function test_add_hsk_word_prevents_duplicate_word_in_same_deck(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Dedup Word Deck',
        ]);

        CustomFlashcard::create([
            'deck_id' => $deck->id,
            'user_id' => $this->user1->id,
            'word' => '北京',
            'pinyin' => 'Běijīng',
            'meaning' => 'Bắc Kinh',
        ]);

        $response = $this->actingAs($this->user1)
            ->postJson(route('custom-flashcards.decks.add-hsk-word', $deck->id), [
                'word' => '北京',
                'meaning' => 'Bắc Kinh (duplicate)',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'already_exists' => true,
            ]);

        $this->assertEquals(1, CustomFlashcard::where('deck_id', $deck->id)->where('word', '北京')->count());
    }

    /**
     * Test user cannot add word to another user's deck.
     */
    public function test_user_cannot_add_hsk_word_to_other_users_deck(): void
    {
        $deckUser1 = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'User1 Deck',
        ]);

        $response = $this->actingAs($this->user2)
            ->postJson(route('custom-flashcards.decks.add-hsk-word', $deckUser1->id), [
                'word' => '你好',
            ]);

        $response->assertStatus(404);
    }

    /**
     * Test add HSK word validates required fields via FormRequest.
     */
    public function test_add_hsk_word_validates_required_fields(): void
    {
        $deck = FlashcardDeck::create([
            'user_id' => $this->user1->id,
            'title' => 'Validation Deck',
        ]);

        // Empty payload (no word and no hsk_vocabulary_id)
        $response = $this->actingAs($this->user1)
            ->postJson(route('custom-flashcards.decks.add-hsk-word', $deck->id), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['word']);
    }
}
