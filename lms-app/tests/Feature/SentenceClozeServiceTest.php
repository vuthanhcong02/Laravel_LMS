<?php

namespace Tests\Feature;

use App\Models\PracticeSentence;
use App\Models\SentenceTopic;
use App\Services\Student\SentenceClozeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SentenceClozeServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SentenceClozeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SentenceClozeService::class);
    }

    /**
     * Test sentence boundary slicing ensures prefix + target + suffix strictly equals original hanzi
     */
    public function test_sentence_boundary_slicing_matches_original_hanzi(): void
    {
        $testCases = [
            [
                'hanzi' => '昨天是五月十号，天气不热，下了一点儿小雨。',
                'tokens' => ['昨天', '是', '五', '月', '十', '号', '天气', '不', '热', '下了', '一点儿', '小雨'],
                'targetIndex' => 2, // '五'
                'targetWord' => '五',
                'expectedPrefix' => '昨天是',
                'expectedSuffix' => '月十号，天气不热，下了一点儿小雨。',
            ],
            [
                'hanzi' => '我们大学很大。',
                'tokens' => ['我们', '大学', '很', '大'],
                'targetIndex' => 3, // '大' at end, must NOT collide with '大' in '大学'
                'targetWord' => '大',
                'expectedPrefix' => '我们大学很',
                'expectedSuffix' => '。',
            ],
            [
                'hanzi' => '他想去，我也想去。',
                'tokens' => ['他', '想', '去', '我', '也', '想', '去'],
                'targetIndex' => 5, // second '想'
                'targetWord' => '想',
                'expectedPrefix' => '他想去，我也',
                'expectedSuffix' => '去。',
            ],
        ];

        foreach ($testCases as $case) {
            $slices = $this->service->calculateSentenceSlices(
                $case['hanzi'],
                $case['tokens'],
                $case['targetIndex'],
                $case['targetWord']
            );

            $this->assertEquals($case['expectedPrefix'], $slices['prefix']);
            $this->assertEquals($case['expectedSuffix'], $slices['suffix']);
            $this->assertEquals(
                $case['hanzi'],
                $slices['prefix'] . $case['targetWord'] . $slices['suffix']
            );
        }
    }

    /**
     * Test Grammar Bank priority matching for measure words
     */
    public function test_grammar_bank_matches_measure_word(): void
    {
        $sentence = [
            'hanzi' => '我买了一张桌子。',
            'tokens' => ['我', '买了', '一', '张', '桌子'],
        ];

        $cloze = $this->service->generateCloze($sentence, 'HSK1');

        $this->assertEquals('张', $cloze['target_word']);
        $this->assertEquals('我买了一', $cloze['prefix']);
        $this->assertEquals('桌子。', $cloze['suffix']);
        $this->assertEquals('我买了一张桌子。', $cloze['prefix'] . $cloze['target_word'] . $cloze['suffix']);

        // Verify options count & distractors belong to measure words pool
        $this->assertCount(4, $cloze['options']);
        $measureWordsPool = ['个', '本', '张', '条', '件', '只', '位', '杯', '瓶', '辆', '双', '支', '块', '把'];
        foreach ($cloze['options'] as $opt) {
            $this->assertContains($opt['text'], $measureWordsPool);
            $this->assertNotEmpty($opt['pinyin']);
        }
    }

    /**
     * Test Grammar Bank priority matching for modal verbs
     */
    public function test_grammar_bank_matches_modal_verbs(): void
    {
        $sentence = [
            'hanzi' => '高校教育也应该进行相应的改革。',
            'tokens' => ['高校', '教育', '也', '应该', '进行', '相应', '的', '改革'],
        ];

        $cloze = $this->service->generateCloze($sentence, 'HSK3');

        // Modal verb '应该' has priority 8, higher than particle '的' (7)
        $this->assertEquals('应该', $cloze['target_word']);
        $this->assertEquals('高校教育也', $cloze['prefix']);
        $this->assertEquals('进行相应的改革。', $cloze['suffix']);
        $this->assertEquals('高校教育也应该进行相应的改革。', $cloze['prefix'] . $cloze['target_word'] . $cloze['suffix']);

        // Verify distractors are modal verbs
        $modalPool = ['能', '会', '可以', '想', '要', '应该', '愿意', '敢'];
        foreach ($cloze['options'] as $opt) {
            $this->assertContains($opt['text'], $modalPool);
            $this->assertNotEmpty($opt['pinyin']);
        }
    }

    /**
     * Test Grammar Bank matching for structural particles de/de/de
     */
    public function test_grammar_bank_matches_structural_particles(): void
    {
        $sentence = [
            'hanzi' => '这是我买的书。',
            'tokens' => ['这', '是', '我', '买', '的', '书'],
        ];

        $cloze = $this->service->generateCloze($sentence, 'HSK1');

        $this->assertEquals('的', $cloze['target_word']);
        $this->assertEquals('这是我买', $cloze['prefix']);
        $this->assertEquals('书。', $cloze['suffix']);

        // Check options contain '的', '得', '地', etc.
        $particlePool = ['的', '得', '地', '着', '了', '过'];
        foreach ($cloze['options'] as $opt) {
            $this->assertContains($opt['text'], $particlePool);
        }
    }

    /**
     * Test options integrity: exactly 4 options, 1 correct, 3 false, valid labels A B C D
     */
    public function test_options_integrity_and_correctness(): void
    {
        $sentence = [
            'hanzi' => '我们在图书馆认真地学习汉语。',
            'tokens' => ['我们', '在', '图书馆', '认真', '地', '学习', '汉语'],
        ];

        $cloze = $this->service->generateCloze($sentence, 'HSK2');

        $options = $cloze['options'];
        $this->assertCount(4, $options);

        $labels = array_column($options, 'label');
        $this->assertEquals(['A', 'B', 'C', 'D'], $labels);

        $correctCount = 0;
        $texts = [];
        foreach ($options as $opt) {
            $this->assertNotEmpty($opt['text']);
            $this->assertNotEmpty($opt['pinyin']);
            if ($opt['correct']) {
                $correctCount++;
                $this->assertEquals($cloze['target_word'], $opt['text']);
            }
            $texts[] = $opt['text'];
        }

        // Must have exactly 1 correct answer
        $this->assertEquals(1, $correctCount);

        // All 4 options must be unique
        $this->assertCount(4, array_unique($texts));
    }

    /**
     * Test HSK vocabulary fallback selects matching character length and valid HSK pinyin
     */
    public function test_hsk_fallback_preserves_word_length(): void
    {
        $sentence = [
            'hanzi' => '我的爱好是摄影。',
            'tokens' => ['我的', '爱好', '是', '摄影'],
        ];

        $cloze = $this->service->generateCloze($sentence, 'HSK2');

        $targetWord = $cloze['target_word'];
        $targetLen = mb_strlen($targetWord);

        $this->assertGreaterThanOrEqual(1, $targetLen);
        $this->assertEquals(
            $sentence['hanzi'],
            $cloze['prefix'] . $targetWord . $cloze['suffix']
        );

        // Distractors must match the target word length
        foreach ($cloze['options'] as $opt) {
            $this->assertEquals($targetLen, mb_strlen($opt['text']));
            $this->assertNotEmpty($opt['pinyin'], "Option '{$opt['text']}' has empty pinyin in test_hsk_fallback_preserves_word_length");
        }
    }

    /**
     * Test batch of random database sentences to guarantee 100% boundary accuracy
     */
    public function test_batch_random_database_sentences(): void
    {
        $topic = SentenceTopic::firstOrCreate(
            ['slug' => 'test-topic-cloze'],
            [
                'title' => 'Test Topic Cloze',
                'title_vi' => 'Chủ đề kiểm tra Cloze',
                'level' => 1,
                'total_sentences' => 5,
            ]
        );

        $sampleSentences = [
            ['hanzi' => '昨天是五月十号，天气不热，下了一点儿小雨。', 'tokens' => ['昨天', '是', '五', '月', '十', '号', '天气', '不', '热', '下了', '一点儿', '小雨']],
            ['hanzi' => '他在图书馆看书，桌子上有一杯热茶。', 'tokens' => ['他', '在', '图书馆', '看书', '桌子', '上', '有', '一杯', '热茶']],
            ['hanzi' => '虽然今天下大雨，但是我们还要去上课。', 'tokens' => ['虽然', '今天', '下', '大雨', '但是', '我们', '还要', '去', '上课']],
            ['hanzi' => '我想买一本书，你能不能借我一点儿钱？', 'tokens' => ['我', '想', '买', '一本', '书', '你', '能', '不能', '借', '我', '一点儿', '钱']],
            ['hanzi' => '这家医院的医生非常认真负责。', 'tokens' => ['这家', '医院', '的', '医生', '非常', '认真', '负责']],
        ];

        foreach ($sampleSentences as $idx => $sample) {
            PracticeSentence::firstOrCreate(
                ['hanzi' => $sample['hanzi']],
                [
                    'topic_id' => $topic->id,
                    'order_index' => $idx + 1,
                    'pinyin' => 'test pinyin',
                    'meaning' => 'test meaning',
                    'tokens' => $sample['tokens'],
                ]
            );
        }

        $sentences = PracticeSentence::with('topic')->limit(20)->get();
        $this->assertNotEmpty($sentences);

        foreach ($sentences as $sentence) {
            $level = 'HSK' . ($sentence->topic->level ?? 1);
            $cloze = $this->service->generateCloze($sentence, $level);

            // 1. Boundary match must be 100%
            $reconstructed = $cloze['prefix'] . $cloze['target_word'] . $cloze['suffix'];
            $this->assertEquals(
                $sentence->hanzi,
                $reconstructed,
                "Boundary mismatch on sentence ID {$sentence->id}: {$sentence->hanzi}"
            );

            // 2. Options must have 4 items with 1 correct
            $this->assertCount(4, $cloze['options']);
            $correctOptions = array_filter($cloze['options'], fn($o) => $o['correct']);
            $this->assertCount(1, $correctOptions);

            $correctOption = reset($correctOptions);
            $this->assertEquals($cloze['target_word'], $correctOption['text']);

            // 3. Labels must be A, B, C, D
            $this->assertEquals(['A', 'B', 'C', 'D'], array_column($cloze['options'], 'label'));
        }
    }

    /**
     * Test edge cases: empty tokens or single character
     */
    public function test_edge_cases(): void
    {
        // Punctuation only
        $emptySentence = [
            'hanzi' => '。！？',
            'tokens' => ['。', '！', '？'],
        ];
        $clozeEmpty = $this->service->generateCloze($emptySentence, 'HSK1');
        $this->assertEquals('', $clozeEmpty['target_word']);
        $this->assertEmpty($clozeEmpty['options']);

        // Single word sentence
        $singleSentence = [
            'hanzi' => '你好。',
            'tokens' => ['你好'],
        ];
        $clozeSingle = $this->service->generateCloze($singleSentence, 'HSK1');
        $this->assertEquals('你好', $clozeSingle['target_word']);
        $this->assertEquals('', $clozeSingle['prefix']);
        $this->assertEquals('。', $clozeSingle['suffix']);
        $this->assertCount(4, $clozeSingle['options']);
    }
}
