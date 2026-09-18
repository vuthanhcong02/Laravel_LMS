<?php

namespace App\Services\Student;

use App\Models\PracticeSentence;
use App\Models\SentenceTopic;
use App\Models\User;
use App\Services\GamificationService;
use App\Services\Student\SentenceClozeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SentenceStudyService
{
    /**
     * Cloze question generator service
     */
    protected SentenceClozeService $clozeService;

    public function __construct(SentenceClozeService $clozeService)
    {
        $this->clozeService = $clozeService;
    }
    /**
     * Supported HSK levels
     *
     * @var array<string>
     */
    protected array $levels = ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'HSK7', 'HSK8', 'HSK9'];

    /**
     * Get list of supported HSK levels
     *
     * @return array<string>
     */
    public function getLevels(): array
    {
        return $this->levels;
    }

    /**
     * Normalize HSK level string to valid format
     *
     * @param string|null $level
     * @return string
     */
    public function normalizeLevel(?string $level): string
    {
        $upper = strtoupper((string) $level);
        return in_array($upper, $this->levels) ? $upper : 'HSK1';
    }

    /**
     * Extract integer level number from level string (e.g. 'HSK4' -> 4)
     *
     * @param string $level
     * @return int
     */
    public function extractLevelNumber(string $level): int
    {
        return (int) filter_var($level, FILTER_SANITIZE_NUMBER_INT) ?: 1;
    }

    /**
     * Get topics list with statistics for index view
     *
     * @param string $selectedLevel
     * @param string $search
     * @return array
     */
    public function getTopicsData(string $selectedLevel, string $search = ''): array
    {
        $normalizedLevel = $this->normalizeLevel($selectedLevel);
        $levelNumber = $this->extractLevelNumber($normalizedLevel);

        $query = SentenceTopic::where('level', $levelNumber);

        if ($search !== '') {
            $isMysql = DB::connection()->getDriverName() === 'mysql';

            $query->where(function ($q) use ($search, $isMysql) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('hanzi', 'like', "%{$search}%")
                  ->orWhere('pinyin', 'like', "%{$search}%");

                // Case-insensitive and accent-insensitive search for Vietnamese topic title
                if ($isMysql) {
                    $q->orWhereRaw('title_vi COLLATE utf8mb4_0900_ai_ci LIKE ?', ["%{$search}%"]);
                } else {
                    $q->orWhere('title_vi', 'like', "%{$search}%");
                }
            });
        }

        $topicRecords = $query
            ->orderByRaw('CASE WHEN total_sentences = 5 THEN 0 ELSE 1 END')
            ->orderBy('title', 'asc')
            ->get();

        $topics = $topicRecords->map(function ($topic) use ($normalizedLevel) {
            return [
                'id' => $topic->slug,
                'level' => $normalizedLevel,
                'title' => $topic->title,
                'titleVi' => $topic->title_vi ?: $topic->title,
                'hanzi' => $topic->hanzi,
                'totalSentences' => $topic->total_sentences,
            ];
        })->toArray();

        return [
            'selectedLevel' => $normalizedLevel,
            'topics' => $topics,
        ];
    }

    /**
     * Get practice data for specific topic slug
     *
     * @param string $level
     * @param string $slug
     * @return array
     */
    public function getPracticeData(string $level, string $slug): array
    {
        $selectedLevel = $this->normalizeLevel($level);
        $levelNumber = $this->extractLevelNumber($selectedLevel);

        $topic = SentenceTopic::with(['sentences' => fn($q) => $q->orderBy('order_index')])
            ->where('slug', $slug)
            ->first();

        // Fallback to first topic of this level if not found
        if (!$topic) {
            $topic = SentenceTopic::with(['sentences' => fn($q) => $q->orderBy('order_index')])
                ->where('level', $levelNumber)
                ->firstOrFail();
        }

        return [
            'topic' => $this->formatTopicForPractice($topic),
            'level' => $selectedLevel,
            'slug' => $slug,
        ];
    }

    /**
     * Get random sentences practice data
     *
     * @param string $level
     * @param string $mode
     * @param int $limit
     * @return array
     */
    public function getRandomPracticeData(string $level, string $mode, int $limit = 15): array
    {
        $selectedLevel = $this->normalizeLevel($level);
        $levelNumber = $this->extractLevelNumber($selectedLevel);

        $randomSentences = PracticeSentence::with('topic')
            ->whereHas('topic', fn($q) => $q->where('level', $levelNumber))
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        $modeNames = [
            'cloze' => __('Điền từ vào chỗ trống'),
            'dictation' => __('Nghe & Chép chính tả'),
            'scramble' => __('Ghép câu'),
        ];

        $topicData = [
            'id' => 'random-' . strtolower($selectedLevel),
            'title' => 'Random ' . $selectedLevel,
            'titleVi' => ($modeNames[$mode] ?? __('Luyện tập')) . ' - ' . $selectedLevel,
            'level' => $levelNumber,
            'sentences' => $this->formatSentencesCollection($randomSentences, $selectedLevel),
        ];

        return [
            'topic' => $topicData,
            'level' => $selectedLevel,
            'slug' => 'random',
            'levels' => $this->levels,
        ];
    }

    /**
     * Format a SentenceTopic model with sentences for Alpine component
     *
     * @param SentenceTopic $topic
     * @return array
     */
    public function formatTopicForPractice(SentenceTopic $topic): array
    {
        return [
            'id' => $topic->slug,
            'title' => $topic->title,
            'titleVi' => $topic->title_vi ?: $topic->title,
            'hanzi' => $topic->hanzi,
            'level' => $topic->level,
            'sentences' => $this->formatSentencesCollection($topic->sentences, 'HSK' . $topic->level),
        ];
    }

    /**
     * Format collection of PracticeSentence models into array
     *
     * @param Collection|array $sentences
     * @return array
     */
    public function formatSentencesCollection($sentences, string $level = 'HSK1'): array
    {
        $collection = $sentences instanceof Collection ? $sentences : collect($sentences);

        return $collection->map(function ($sentence) use ($level) {
            return [
                'id' => $sentence->id,
                'hanzi' => $sentence->hanzi,
                'pinyin' => $sentence->pinyin,
                'meaning' => $sentence->meaning,
                'meaningVi' => $sentence->meaning_vi ?: $sentence->meaning,
                'audioUrl' => $sentence->audio_path,
                'duration' => $sentence->duration,
                'words' => $sentence->words ?: [],
                'tokens' => $sentence->tokens ?: [],
                'cloze' => $this->clozeService->generateCloze($sentence, $level),
            ];
        })->values()->toArray();
    }

    /**
     * Get only formatted random sentences for AJAX endless mode
     *
     * @param string $level
     * @param int $limit
     * @return array
     */
    public function getRandomSentencesOnly(string $level, int $limit = 15): array
    {
        $selectedLevel = $this->normalizeLevel($level);
        $levelNumber = $this->extractLevelNumber($selectedLevel);

        $randomSentences = PracticeSentence::with('topic')
            ->whereHas('topic', fn($q) => $q->where('level', $levelNumber))
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        return $this->formatSentencesCollection($randomSentences, $selectedLevel);
    }

    /**
     * Complete a practice session and award EXP via GamificationService
     *
     * @param User $user
     * @param array $data
     * @return array
     */
    public function completePracticeSession(User $user, array $data): array
    {
        $level = (string) ($data['level'] ?? 'HSK1');
        $normalizedLevel = $this->normalizeLevel($level);
        $totalSentences = (int) ($data['total_sentences'] ?? 0);
        $correctCount = (int) ($data['correct_count'] ?? 0);
        $hintsUsed = (int) ($data['hints_used'] ?? 3);
        $topicId = !empty($data['topic_id']) && is_numeric($data['topic_id']) ? (int) $data['topic_id'] : null;

        $config = config('gamification.actions.sentence_practice');
        $minPercent = (float) ($config['min_correct_percent'] ?? 50);
        $minCorrect = $totalSentences > 0 ? (int) ceil($totalSentences * ($minPercent / 100)) : 1;

        $expResult = null;
        $bonusInfo = [];

        // Check if user passed minimum correct answers threshold
        if ($correctCount >= $minCorrect) {
            $baseExp = (int) ($config['exp_by_level'][$normalizedLevel] ?? ($config['exp'] ?? 15));

            // Bonus for completing without using any hints
            if ($hintsUsed === 0 && !empty($config['no_hint_bonus'])) {
                $hintBonus = (int) $config['no_hint_bonus'];
                $baseExp += $hintBonus;
                $bonusInfo[] = __('Thưởng không dùng gợi ý (+:bonus EXP)', ['bonus' => $hintBonus]);
            }

            /** @var GamificationService $gamificationService */
            $gamificationService = app(GamificationService::class);
            $expResult = $gamificationService->awardExp(
                $user,
                'sentence_practice',
                $topicId,
                [
                    'base_exp'        => $baseExp,
                    'level'           => $normalizedLevel,
                    'total_sentences' => $totalSentences,
                    'correct_count'   => $correctCount,
                ]
            );
        }

        $freshUser = $user->fresh();
        /** @var GamificationService $gamificationService */
        $gamificationService = app(GamificationService::class);
        $levelInfo = $gamificationService->calculateLevelInfo((int) ($freshUser->exp_total ?? 0));

        $message = __('Đã hoàn thành bài luyện tập.');
        if ($expResult && !empty($expResult['exp_gained'])) {
            $message = __('Chúc mừng! Bạn nhận được +:exp EXP từ bài luyện tập câu.', ['exp' => $expResult['exp_gained']]);
        }

        return [
            'success'      => true,
            'message'      => $message,
            'bonus_info'   => $bonusInfo,
            'exp_gained'   => $expResult['exp_gained'] ?? 0,
            'gamification' => $expResult,
            'user'         => [
                'current_streak'   => $freshUser->current_streak,
                'today_exp'        => $freshUser->today_exp,
                'exp_total'        => $freshUser->exp_total,
                'progress_percent' => $freshUser->daily_progress_percent,
                'level'            => $levelInfo['level'],
                'level_badge'      => $levelInfo['level_badge'],
                'level_progress'   => $levelInfo['progress_percent'],
                'level_info'       => $levelInfo,
            ],
        ];
    }
}
