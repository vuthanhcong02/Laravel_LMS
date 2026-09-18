<?php

namespace App\Services\Student;

use App\Models\PracticeSentence;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SentenceClozeService
{
    /**
     * Cache key for HSK vocabularies grouped into level and length buckets
     */
    protected const BUCKETS_CACHE_KEY = 'hsk_cloze_vocab_buckets_v2';

    /**
     * In-memory cache for the current request
     */
    protected static ?array $memoryBuckets = null;
    protected static ?array $memoryPinyinMap = null;

    /**
     * Specialized Grammar & Functional Banks
     *
     * @var array<string, array>
     */
    protected static array $grammarBanks = [
        // 1. Measure Words (量词)
        'measure_words' => [
            'priority' => 10,
            'words' => [
                '个' => 'gè', '本' => 'běn', '张' => 'zhāng', '条' => 'tiáo', '件' => 'jiàn',
                '只' => 'zhī', '位' => 'wèi', '杯' => 'bēi', '瓶' => 'píng', '辆' => 'liàng',
                '双' => 'shuāng', '支' => 'zhī', '块' => 'kuài', '把' => 'bǎ', '节' => 'jié',
                '封' => 'fēng', '碗' => 'wǎn', '套' => 'tào', '间' => 'jiān', '家' => 'jiā',
            ],
            'pool' => ['个', '本', '张', '条', '件', '只', '位', '杯', '瓶', '辆', '双', '支', '块', '把'],
        ],

        // 2. Conjunctions & Connectors (连词)
        'conjunctions' => [
            'priority' => 9,
            'words' => [
                '虽然' => 'suīrán', '但是' => 'dànshì', '因为' => 'yīnwèi', '所以' => 'suǒyǐ',
                '如果' => 'rúguǒ', '不但' => 'búdàn', '而且' => 'érqiě', '还是' => 'háishì',
                '或者' => 'huòzhě', '只要' => 'zhǐyào', '只有' => 'zhǐyǒu', '尽管' => 'jǐnguǎn',
                '无论' => 'wúlùn', '即使' => 'jíshǐ', '不过' => 'búguò', '然后' => 'ránhòu',
            ],
            'pairs' => [
                '还是' => ['或者', '而且', '但是'],
                '或者' => ['还是', '而且', '所以'],
                '因为' => ['虽然', '如果', '为了'],
                '所以' => ['但是', '而且', '然后'],
                '虽然' => ['因为', '如果', '即使'],
                '但是' => ['所以', '而且', '或者'],
                '如果' => ['因为', '虽然', '只要'],
                '不但' => ['虽然', '因为', '只有'],
                '而且' => ['但是', '所以', '还是'],
                '只要' => ['只有', '如果', '虽然'],
                '只有' => ['只要', '如果', '因为'],
            ],
            'pool' => ['虽然', '但是', '因为', '所以', '如果', '而且', '还是', '或者', '只要', '只有'],
        ],

        // 3. Modal Verbs (能愿动词)
        'modal_verbs' => [
            'priority' => 8,
            'words' => [
                '能' => 'néng', '会' => 'huì', '可以' => 'kěyǐ', '想' => 'xiǎng',
                '要' => 'yào', '应该' => 'yīnggāi', '愿意' => 'yuànyì', '敢' => 'gǎn',
                '必须' => 'bìxū', '可能' => 'kěnéng',
            ],
            'pool' => ['能', '会', '可以', '想', '要', '应该', '愿意', '敢'],
        ],

        // 4. Prepositions (介词)
        'prepositions' => [
            'priority' => 8,
            'words' => [
                '在' => 'zài', '从' => 'cóng', '离' => 'lí', '往' => 'wǎng',
                '向' => 'xiàng', '跟' => 'gēn', '对' => 'duì', '给' => 'gěi',
                '为' => 'wèi', '把' => 'bǎ', '被' => 'bèi', '比' => 'bǐ',
            ],
            'pool' => ['在', '从', '离', '往', '向', '跟', '对', '给', '把', '被', '比'],
        ],

        // 5. Structural & Aspect Particles (助词)
        'particles' => [
            'priority' => 7,
            'words' => [
                '的' => 'de', '得' => 'de', '地' => 'de',
                '着' => 'zhe', '了' => 'le', '过' => 'guo',
            ],
            'pairs' => [
                '的' => ['得', '地', '了'],
                '得' => ['的', '地', '过'],
                '地' => ['的', '得', '着'],
                '着' => ['了', '过', '得'],
                '了' => ['过', '着', '的'],
                '过' => ['了', '着', '得'],
            ],
            'pool' => ['的', '得', '地', '着', '了', '过'],
        ],

        // 6. Time & Frequency Adverbs (时间/频率副词)
        'time_adverbs' => [
            'priority' => 7,
            'words' => [
                '已经' => 'yǐjīng', '刚才' => 'gāngcái', '刚' => 'gāng', '正在' => 'zhèngzài',
                '经常' => 'jīngcháng', '常常' => 'chángcháng', '总是' => 'zǒngshì',
                '一直' => 'yìzhí', '马上' => 'mǎshàng', '忽然' => 'hūrán', '突然' => 'tūrán',
            ],
            'pool' => ['已经', '刚才', '刚', '正在', '经常', '常常', '总是', '一直', '马上'],
        ],

        // 7. Degree Adverbs (程度副词)
        'degree_adverbs' => [
            'priority' => 6,
            'words' => [
                '很' => 'hěn', '非常' => 'fēicháng', '太' => 'tài', '更' => 'gèng',
                '最' => 'zuì', '特别' => 'tèbié', '比较' => 'bǐjiào',
            ],
            'pool' => ['很', '非常', '太', '更', '最', '特别', '比较'],
        ],

        // 8. Question Words (疑问代词)
        'question_words' => [
            'priority' => 6,
            'words' => [
                '谁' => 'shéi', '什么' => 'shénme', '哪' => 'nǎ', '哪里' => 'nǎlǐ',
                '哪儿' => 'nǎr', '怎么' => 'zěnme', '怎么样' => 'zěnmeyàng',
                '多少' => 'duōshao', '几' => 'jǐ', '为什么' => 'wèishénme',
            ],
            'pool' => ['谁', '什么', '哪儿', '哪里', '怎么', '怎么样', '多少', '为什么'],
        ],

        // 9. Direction & Position Words (方位词)
        'direction_words' => [
            'priority' => 5,
            'words' => [
                '上' => 'shàng', '下' => 'xià', '前' => 'qián', '后' => 'hòu',
                '左' => 'zuǒ', '右' => 'yòu', '里' => 'lǐ', '外' => 'wài',
                '中间' => 'zhōngjiān', '旁边' => 'pángbiān',
            ],
            'pool' => ['上', '下', '前', '后', '左', '右', '里', '外', '旁边', '中间'],
        ],
    ];

    /**
     * Core HSK Vocabulary Fallback Dictionary (used when database table is empty in testing/CI)
     *
     * @var array<string, array{pinyin: string, level: int}>
     */
    protected static array $coreHskFallback = [
        '爱好' => ['pinyin' => 'àihào', 'level' => 1],
        '高兴' => ['pinyin' => 'gāoxìng', 'level' => 1],
        '学习' => ['pinyin' => 'xuéxí', 'level' => 1],
        '看见' => ['pinyin' => 'kànjiàn', 'level' => 1],
        '喜欢' => ['pinyin' => 'xǐhuan', 'level' => 1],
        '准备' => ['pinyin' => 'zhǔnbèi', 'level' => 2],
        '希望' => ['pinyin' => 'xīwàng', 'level' => 2],
        '帮助' => ['pinyin' => 'bāngzhù', 'level' => 2],
        '介绍' => ['pinyin' => 'jièshào', 'level' => 2],
        '同意' => ['pinyin' => 'tóngyì', 'level' => 3],
        '发现' => ['pinyin' => 'fāxiàn', 'level' => 3],
        '解决' => ['pinyin' => 'jiějué', 'level' => 3],
        '选择' => ['pinyin' => 'xuǎnzé', 'level' => 3],
        '摄影' => ['pinyin' => 'shèyǐng', 'level' => 3],
        '朋友' => ['pinyin' => 'péngyou', 'level' => 1],
        '今天' => ['pinyin' => 'jīntiān', 'level' => 1],
        '明天' => ['pinyin' => 'míngtiān', 'level' => 1],
        '昨天' => ['pinyin' => 'zuótiān', 'level' => 1],
        '现在' => ['pinyin' => 'xiànzài', 'level' => 1],
        '时间' => ['pinyin' => 'shíjiān', 'level' => 1],
        '学校' => ['pinyin' => 'xuéxiào', 'level' => 1],
        '老师' => ['pinyin' => 'lǎoshī', 'level' => 1],
        '学生' => ['pinyin' => 'xuésheng', 'level' => 1],
        '中国' => ['pinyin' => 'zhōngguó', 'level' => 1],
        '北京' => ['pinyin' => 'běijīng', 'level' => 1],
        '汉语' => ['pinyin' => 'hànyǔ', 'level' => 1],
        '天气' => ['pinyin' => 'tiānqì', 'level' => 1],
        '身体' => ['pinyin' => 'shēntǐ', 'level' => 2],
        '健康' => ['pinyin' => 'jiànkāng', 'level' => 3],
        '事情' => ['pinyin' => 'shìqing', 'level' => 2],
        '工作' => ['pinyin' => 'gōngzuò', 'level' => 1],
        '说话' => ['pinyin' => 'shuōhuà', 'level' => 1],
        '晴朗' => ['pinyin' => 'qínglǎng', 'level' => 3],
    ];

    /**
     * Punctuation regex pattern to filter out tokens
     */
    protected const PUNCTUATION_PATTERN = '/[，。！？、；：“”‘’（）《》…,\.!\?;:"\'\(\)\s]/u';

    /**
     * Load HSK vocabulary buckets and Pinyin map from cache or database
     */
    protected function loadDictionaries(): void
    {
        if (self::$memoryBuckets !== null && self::$memoryPinyinMap !== null) {
            return;
        }

        $cached = Cache::rememberForever(self::BUCKETS_CACHE_KEY, function () {
            $raw = DB::table('hsk_vocabularies')
                ->select('word', 'pinyin', 'level')
                ->get();

            $buckets = [];
            $pinyinMap = [];

            foreach ($raw as $row) {
                // Split multi-word variations like "爸爸 / 爸"
                $parts = explode('/', $row->word);
                $cleanWord = trim($parts[0]);
                $cleanWord = preg_replace(self::PUNCTUATION_PATTERN, '', $cleanWord);

                if (empty($cleanWord)) {
                    continue;
                }

                $len = mb_strlen($cleanWord);
                $key = "{$row->level}_{$len}";

                if (!isset($buckets[$key])) {
                    $buckets[$key] = [];
                }

                $buckets[$key][] = [
                    'word' => $cleanWord,
                    'pinyin' => $row->pinyin,
                ];

                if (!isset($pinyinMap[$cleanWord])) {
                    $pinyinMap[$cleanWord] = $row->pinyin;
                }
            }

            // If database table is empty (e.g. testing environment), populate from static core fallback
            if (empty($buckets)) {
                foreach (self::$coreHskFallback as $w => $info) {
                    $len = mb_strlen($w);
                    $key = "{$info['level']}_{$len}";
                    $buckets[$key][] = [
                        'word' => $w,
                        'pinyin' => $info['pinyin'],
                    ];
                    $pinyinMap[$w] = $info['pinyin'];
                }
            }

            return [
                'buckets' => $buckets,
                'pinyinMap' => $pinyinMap,
            ];
        });

        self::$memoryBuckets = $cached['buckets'] ?? [];
        self::$memoryPinyinMap = $cached['pinyinMap'] ?? [];
    }

    /**
     * Look up Pinyin for a Chinese word
     */
    public function lookupPinyin(string $word): string
    {
        $this->loadDictionaries();

        if (isset(self::$memoryPinyinMap[$word])) {
            return self::$memoryPinyinMap[$word];
        }

        // Check in static core fallback
        if (isset(self::$coreHskFallback[$word])) {
            return self::$coreHskFallback[$word]['pinyin'];
        }

        // Check in grammar banks
        foreach (self::$grammarBanks as $bank) {
            if (isset($bank['words'][$word])) {
                return $bank['words'][$word];
            }
        }

        // Decompose into individual characters if multi-character word
        $len = mb_strlen($word);
        if ($len > 1) {
            $charPinyins = [];
            for ($i = 0; $i < $len; $i++) {
                $char = mb_substr($word, $i, 1);
                $charPinyin = self::$memoryPinyinMap[$char] ?? null;
                if (!$charPinyin) {
                    foreach (self::$grammarBanks as $bank) {
                        if (isset($bank['words'][$char])) {
                            $charPinyin = $bank['words'][$char];
                            break;
                        }
                    }
                }
                if ($charPinyin) {
                    $charPinyins[] = $charPinyin;
                }
            }
            if (count($charPinyins) === $len) {
                return implode('', $charPinyins);
            }
        }

        return '';
    }

    /**
     * Generate high-quality Cloze test question structure for a sentence
     *
     * @param PracticeSentence|array $sentence
     * @param string $level E.g. 'HSK1', 'HSK2'
     * @return array
     */
    public function generateCloze($sentence, string $level = 'HSK1'): array
    {
        $hanzi = is_array($sentence) ? ($sentence['hanzi'] ?? '') : $sentence->hanzi;
        $tokens = is_array($sentence) ? ($sentence['tokens'] ?? []) : ($sentence->tokens ?: []);
        $levelNum = (int) filter_var($level, FILTER_SANITIZE_NUMBER_INT) ?: 1;

        // Clean tokens of any empty or punctuation items
        $validTokenIndices = [];
        foreach ($tokens as $idx => $token) {
            $trimmed = trim($token);
            if ($trimmed !== '' && !preg_match(self::PUNCTUATION_PATTERN, $trimmed)) {
                $validTokenIndices[$idx] = $trimmed;
            }
        }

        if (empty($validTokenIndices)) {
            return [
                'prefix' => '',
                'suffix' => $hanzi,
                'target_word' => '',
                'options' => [],
            ];
        }

        // Tier 1: Try finding a match in the pedagogical Grammar Banks
        $candidate = $this->selectGrammarCandidate($validTokenIndices);

        // Tier 2: If no grammar bank match, select a compound word / core HSK keyword
        if (!$candidate) {
            $candidate = $this->selectHskCandidate($validTokenIndices, $levelNum);
        }

        // Tier 3: Fallback to the middle-most token
        if (!$candidate) {
            $keys = array_keys($validTokenIndices);
            $midKey = $keys[(int) floor(count($keys) / 2)];
            $targetWord = $validTokenIndices[$midKey];

            $candidate = [
                'token_index' => $midKey,
                'target_word' => $targetWord,
                'pinyin' => $this->lookupPinyin($targetWord),
                'distractors' => $this->getHskDistractors($targetWord, $levelNum, 3),
            ];
        }

        // Calculate boundary-accurate prefix and suffix in original sentence
        $slices = $this->calculateSentenceSlices(
            $hanzi,
            $tokens,
            $candidate['token_index'],
            $candidate['target_word']
        );

        // Build and shuffle 4 options A, B, C, D
        $options = $this->buildOptions(
            $candidate['target_word'],
            $candidate['pinyin'],
            $candidate['distractors']
        );

        return [
            'prefix' => $slices['prefix'],
            'suffix' => $slices['suffix'],
            'target_word' => $candidate['target_word'],
            'options' => $options,
        ];
    }

    /**
     * Tier 1: Match against specialized Grammar Banks
     */
    protected function selectGrammarCandidate(array $validTokenIndices): ?array
    {
        $bestMatch = null;
        $highestPriority = -1;

        foreach ($validTokenIndices as $idx => $token) {
            foreach (self::$grammarBanks as $bankKey => $bank) {
                if (isset($bank['words'][$token]) && $bank['priority'] > $highestPriority) {
                    $highestPriority = $bank['priority'];
                    $targetPinyin = $bank['words'][$token];

                    // Select distractors from pair definitions or pool
                    $pool = $bank['pairs'][$token] ?? array_values(array_filter($bank['pool'], fn($w) => $w !== $token));
                    shuffle($pool);
                    $distractorWords = array_slice($pool, 0, 3);

                    $distractors = [];
                    foreach ($distractorWords as $dw) {
                        $distractors[] = [
                            'text' => $dw,
                            'pinyin' => $bank['words'][$dw] ?? $this->lookupPinyin($dw),
                        ];
                    }

                    $bestMatch = [
                        'token_index' => $idx,
                        'target_word' => $token,
                        'pinyin' => $targetPinyin,
                        'distractors' => $distractors,
                    ];
                }
            }
        }

        return $bestMatch;
    }

    /**
     * Tier 2: Match against HSK core vocabulary (prefer compound words of 2+ chars)
     */
    protected function selectHskCandidate(array $validTokenIndices, int $levelNum): ?array
    {
        $this->loadDictionaries();

        // 1. First priority: Compound words (length >= 2) that are not numbers/dates
        $compoundCandidates = [];
        $singleCandidates = [];

        foreach ($validTokenIndices as $idx => $token) {
            $len = mb_strlen($token);
            if ($len >= 2 && $len <= 4) {
                $compoundCandidates[$idx] = $token;
            } elseif ($len === 1) {
                $singleCandidates[$idx] = $token;
            }
        }

        // Filter out words ending in possessive or grammatical suffixes (e.g. 我的, 他的, 看了) if better candidates exist
        $pureVocabularyCandidates = array_filter($compoundCandidates, function ($w) {
            return !in_array(mb_substr($w, -1), ['的', '得', '地', '了', '着', '过'], true);
        });

        $candidatesToPickFrom = !empty($pureVocabularyCandidates) ? $pureVocabularyCandidates : $compoundCandidates;

        // Prioritize words that exist in dictionary with valid pinyin
        $knownWordCandidates = array_filter($candidatesToPickFrom, function ($w) {
            return !empty($this->lookupPinyin($w));
        });

        $finalPool = !empty($knownWordCandidates) ? $knownWordCandidates : $candidatesToPickFrom;

        $chosenIdx = null;
        $chosenWord = null;

        if (!empty($finalPool)) {
            $keys = array_keys($finalPool);
            $chosenIdx = $keys[array_rand($keys)];
            $chosenWord = $finalPool[$chosenIdx];
        } elseif (!empty($singleCandidates)) {
            $keys = array_keys($singleCandidates);
            $chosenIdx = $keys[array_rand($keys)];
            $chosenWord = $singleCandidates[$chosenIdx];
        }

        if ($chosenWord === null) {
            return null;
        }

        $targetPinyin = $this->lookupPinyin($chosenWord);
        $distractors = $this->getHskDistractors($chosenWord, $levelNum, 3);

        return [
            'token_index' => $chosenIdx,
            'target_word' => $chosenWord,
            'pinyin' => $targetPinyin,
            'distractors' => $distractors,
        ];
    }

    /**
     * Get 3 smart distractors of identical character length and similar HSK level
     *
     * @param string $targetWord
     * @param int $levelNum
     * @param int $count
     * @return array<array{text: string, pinyin: string}>
     */
    protected function getHskDistractors(string $targetWord, int $levelNum, int $count = 3): array
    {
        $this->loadDictionaries();
        $len = mb_strlen($targetWord);

        // Try same level first, then adjacent levels
        $searchLevels = [$levelNum, max(1, $levelNum - 1), min(9, $levelNum + 1)];
        $candidates = [];

        foreach ($searchLevels as $lvl) {
            $bucketKey = "{$lvl}_{$len}";
            if (isset(self::$memoryBuckets[$bucketKey])) {
                foreach (self::$memoryBuckets[$bucketKey] as $item) {
                    if ($item['word'] !== $targetWord && !isset($candidates[$item['word']])) {
                        $candidates[$item['word']] = $item;
                    }
                }
            }
            if (count($candidates) >= 15) {
                break;
            }
        }

        $pool = array_values($candidates);
        shuffle($pool);
        $selected = array_slice($pool, 0, $count);

        $result = [];
        foreach ($selected as $item) {
            $result[] = [
                'text' => $item['word'],
                'pinyin' => $item['pinyin'],
            ];
        }

        // Safety fallback if not enough words in bucket
        $fallbackPool = [
            1 => [['text' => '高兴', 'pinyin' => 'gāoxìng'], ['text' => '学习', 'pinyin' => 'xuéxí'], ['text' => '看见', 'pinyin' => 'kànjiàn'], ['text' => '喜欢', 'pinyin' => 'xǐhuan']],
            2 => [['text' => '准备', 'pinyin' => 'zhǔnbèi'], ['text' => '希望', 'pinyin' => 'xīwàng'], ['text' => '帮助', 'pinyin' => 'bāngzhù'], ['text' => '介绍', 'pinyin' => 'jièshào']],
            3 => [['text' => '同意', 'pinyin' => 'tóngyì'], ['text' => '发现', 'pinyin' => 'fāxiàn'], ['text' => '解决', 'pinyin' => 'jiějué'], ['text' => '选择', 'pinyin' => 'xuǎnzé']],
        ];

        $fallbackGroup = $fallbackPool[$levelNum] ?? $fallbackPool[1];
        $fbIdx = 0;

        while (count($result) < $count) {
            $fb = $fallbackGroup[$fbIdx % count($fallbackGroup)];
            if ($fb['text'] !== $targetWord && !in_array($fb['text'], array_column($result, 'text'), true)) {
                $result[] = $fb;
            }
            $fbIdx++;
        }

        return $result;
    }

    /**
     * Calculate boundary-accurate prefix and suffix by tracing tokens through the original Hanzi sentence
     */
    public function calculateSentenceSlices(string $hanzi, array $tokens, int $targetIndex, string $targetWord): array
    {
        $cursor = 0;
        $totalLen = mb_strlen($hanzi);

        // Step 1: Trace all tokens before targetIndex in the original sentence
        for ($i = 0; $i < $targetIndex; $i++) {
            $tok = $tokens[$i];
            $tokLen = mb_strlen($tok);
            while ($cursor < $totalLen && mb_substr($hanzi, $cursor, $tokLen) !== $tok) {
                $cursor++;
            }
            $cursor += $tokLen;
        }

        // Step 2: Trace targetWord
        $wordLen = mb_strlen($targetWord);
        while ($cursor < $totalLen && mb_substr($hanzi, $cursor, $wordLen) !== $targetWord) {
            $cursor++;
        }

        $start = $cursor;
        $end = $start + $wordLen;

        $prefix = mb_substr($hanzi, 0, $start);
        $suffix = mb_substr($hanzi, $end);

        // Failsafe check: verify if reconstructed sentence matches original
        if ($prefix . $targetWord . $suffix !== $hanzi) {
            $pos = mb_strpos($hanzi, $targetWord);
            if ($pos !== false) {
                $prefix = mb_substr($hanzi, 0, $pos);
                $suffix = mb_substr($hanzi, $pos + $wordLen);
            }
        }

        return [
            'prefix' => $prefix,
            'suffix' => $suffix,
        ];
    }

    /**
     * Build options A, B, C, D and randomize positions
     *
     * @param string $targetWord
     * @param string $targetPinyin
     * @param array $distractors
     * @return array
     */
    protected function buildOptions(string $targetWord, string $targetPinyin, array $distractors): array
    {
        $rawOptions = [
            [
                'text' => $targetWord,
                'pinyin' => $targetPinyin,
                'correct' => true,
            ],
        ];

        foreach ($distractors as $d) {
            $rawOptions[] = [
                'text' => $d['text'],
                'pinyin' => $d['pinyin'] ?: $this->lookupPinyin($d['text']),
                'correct' => false,
            ];
        }

        shuffle($rawOptions);

        $labels = ['A', 'B', 'C', 'D'];
        $options = [];

        foreach ($rawOptions as $idx => $opt) {
            $options[] = [
                'id' => $idx,
                'label' => $labels[$idx] ?? chr(65 + $idx),
                'text' => $opt['text'],
                'pinyin' => $opt['pinyin'],
                'correct' => (bool) $opt['correct'],
            ];
        }

        return $options;
    }
}
