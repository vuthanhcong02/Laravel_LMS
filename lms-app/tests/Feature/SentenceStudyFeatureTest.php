<?php

namespace Tests\Feature;

use App\Models\PracticeSentence;
use App\Models\SentenceTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SentenceStudyFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected SentenceTopic $topic;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'exp_total' => 0,
        ]);

        $this->topic = SentenceTopic::firstOrCreate(
            ['slug' => 'test-hsk1-cloze-topic'],
            [
                'title' => 'Test Cloze Topic',
                'title_vi' => 'Chủ đề kiểm tra Cloze',
                'level' => 1,
                'total_sentences' => 5,
            ]
        );

        $samples = [
            ['hanzi' => '我买了一张桌子。', 'tokens' => ['我', '买了', '一', '张', '桌子']],
            ['hanzi' => '他在图书馆看书。', 'tokens' => ['他', '在', '图书馆', '看书']],
            ['hanzi' => '虽然下雨，但是我们还要去。', 'tokens' => ['虽然', '下雨', '但是', '我们', '还要', '去']],
        ];

        foreach ($samples as $idx => $s) {
            PracticeSentence::firstOrCreate(
                ['hanzi' => $s['hanzi']],
                [
                    'topic_id' => $this->topic->id,
                    'order_index' => $idx + 1,
                    'pinyin' => 'pinyin',
                    'meaning' => 'meaning',
                    'tokens' => $s['tokens'],
                ]
            );
        }
    }

    /**
     * Test browsing sentence topics page returns 200 OK
     */
    public function test_sentence_index_page_loads_successfully(): void
    {
        $response = $this->get(route('sentences.index', ['level' => 'HSK1', 'mode' => 'cloze']));

        $response->assertStatus(200);
        $response->assertSee('HSK 1');
    }

    /**
     * Test browsing sentence topics via AJAX returns json payload without page reload
     */
    public function test_sentence_index_ajax_search_returns_json_payload(): void
    {
        $response = $this->getJson(route('sentences.index', [
            'level' => 'HSK1',
            'q' => 'Test',
            'mode' => 'cloze',
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'topics',
            'selectedLevel',
            'query',
            'mode',
            'count',
        ]);
        $response->assertJson([
            'success' => true,
            'selectedLevel' => 'HSK1',
            'query' => 'Test',
        ]);
    }

    /**
     * Test searching topics without Vietnamese accents matches accented database records
     */
    public function test_sentence_index_search_without_vietnamese_accents(): void
    {
        // $this->topic->title_vi is 'Chủ đề kiểm tra Cloze'
        // Search using unaccented 'chu de kiem tra'
        $response = $this->getJson(route('sentences.index', [
            'level' => 'HSK1',
            'q' => 'chu de kiem tra',
            'mode' => 'cloze',
        ]));

        $response->assertStatus(200);
        $topics = $response->json('topics');
        $this->assertNotEmpty($topics);
        $this->assertEquals($this->topic->slug, $topics[0]['id']);
    }

    /**
     * Test topic practice page loads cloze question data structure with options and pinyin
     */
    public function test_cloze_practice_page_contains_cloze_data(): void
    {
        $response = $this->actingAs($this->user)->get(route('sentences.practice', [
            'level' => 'HSK1',
            'slug' => $this->topic->slug,
            'mode' => 'cloze',
        ]));

        $response->assertStatus(200);
        $response->assertSee('cloze');
        $response->assertSee('prefix');
        $response->assertSee('suffix');
    }

    /**
     * Test endless random sentences page loads with cloze mode
     */
    public function test_random_sentences_practice_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('sentences.random', [
            'level' => 'HSK1',
            'mode' => 'cloze',
        ]));

        $response->assertStatus(200);
    }

    /**
     * Test completing a practice session successfully awards EXP and increments stats
     */
    public function test_complete_practice_awards_exp_successfully(): void
    {
        $payload = [
            'topic_id' => $this->topic->slug,
            'level' => 'HSK1',
            'mode' => 'cloze',
            'total_sentences' => 5,
            'correct_count' => 5,
            'hints_used' => 0,
            'score' => 50,
        ];

        $response = $this->actingAs($this->user)->postJson(route('sentences.complete'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $data = $response->json();
        $this->assertGreaterThanOrEqual(15, $data['exp_gained'] ?? 0);
    }

    /**
     * Test completing a practice session rejects cheated payloads where correct_count > total_sentences
     */
    public function test_complete_practice_rejects_invalid_score_payload(): void
    {
        $payload = [
            'topic_id' => $this->topic->slug,
            'level' => 'HSK1',
            'mode' => 'cloze',
            'total_sentences' => 5,
            'correct_count' => 999, // Cheated value!
            'hints_used' => 0,
            'score' => 50,
        ];

        $response = $this->actingAs($this->user)->postJson(route('sentences.complete'), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['correct_count']);
    }
}
