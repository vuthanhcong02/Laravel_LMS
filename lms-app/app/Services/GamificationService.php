<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserExpTransaction;
use App\Models\UserLearningLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Add experience points and process learning streak for users.
     *
     * @param User $user
     * @param string $actionType
     * @param int|null $referenceId
     * @param User $user
     * @param string $actionType
     * @param int|null $referenceId
     * @param array $context Additional context for calculation (e.g., quiz_length)
     * @return array|null Return EXP information or null if blocked
     */
    public function awardExp(User $user, string $actionType, ?int $referenceId = null, array $context = []): ?array
    {
        $config = config("gamification.actions.{$actionType}");
        if (!$config) {
            return null;
        }

        $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $now = Carbon::now($timezone);
        $todayDate = $now->toDateString();
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay = $now->copy()->endOfDay();

        // [SEC-1] Use Cache Atomic Lock to prevent Race Condition:
        // Ensure only one request calculates points for the same user + actionType at a time,
        // preventing multiple concurrent requests from bypassing daily_cap.
        $lockKey = "award_exp_{$user->id}_{$actionType}";
        $lock = Cache::lock($lockKey, 10); // Keep lock for max 10 seconds

        try {
            // block(5) = wait up to 5 seconds to acquire lock, throw LockTimeoutException if not acquired
            $lock->block(5);

            // 1. Prevent duplicate rewards for global one-time actions
            if (!empty($config['one_time']) && $referenceId !== null) {
                $alreadyAwarded = UserExpTransaction::where('user_id', $user->id)
                    ->where('action_type', $actionType)
                    ->where('reference_id', $referenceId)
                    ->exists();

                if ($alreadyAwarded) {
                    return null;
                }
            }

            // 2. Prevent duplicate rewards for daily one-time actions per specific reference ID (e.g. HSK mock exam on the same day)
            if (!empty($config['daily_one_time_exam']) && $referenceId !== null) {
                $alreadyAwardedToday = UserExpTransaction::where('user_id', $user->id)
                    ->where('action_type', $actionType)
                    ->where('reference_id', $referenceId)
                    ->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->exists();

                if ($alreadyAwardedToday) {
                    return null;
                }
            }

            // 3. Determine base EXP and apply diminishing returns if configured
            $baseExp = (int) ($config['exp'] ?? 0);
            if (!empty($config['exp_rates']) && !empty($context['quiz_length'])) {
                $length = (int) $context['quiz_length'];
                $baseExp = $config['exp_rates'][$length] ?? $baseExp;
            }

            if (!empty($config['diminishing_returns']) && is_array($config['diminishing_returns'])) {
                $todaySessionsCount = UserExpTransaction::where('user_id', $user->id)
                    ->where('action_type', $actionType)
                    ->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->count();

                $rate = 1.0;
                foreach ($config['diminishing_returns'] as $tier) {
                    if ($tier['max_sessions'] === null || $todaySessionsCount < $tier['max_sessions']) {
                        $rate = (float) $tier['rate'];
                        break;
                    }
                }
                $expToAdd = max(1, (int) round($baseExp * $rate));
            } else {
                $expToAdd = $baseExp;
            }

            $dailyCap = isset($config['daily_cap']) ? $config['daily_cap'] : null;

            // 4. Check daily EXP limit (Daily Cap) — inside lock to prevent race condition
            if ($dailyCap !== null) {
                $todayExpGained = (int) UserExpTransaction::where('user_id', $user->id)
                    ->where('action_type', $actionType)
                    ->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->sum('exp_gained');

                if ($todayExpGained >= $dailyCap) {
                    return null;
                }

                if ($todayExpGained + $expToAdd > $dailyCap) {
                    $expToAdd = $dailyCap - $todayExpGained;
                }
            }

            if ($expToAdd <= 0) {
                return null;
            }

            // 3. Execute database updates within a transaction
            return DB::transaction(function () use ($user, $actionType, $expToAdd, $referenceId, $now, $todayDate, $timezone) {
                // Record EXP transaction history
                UserExpTransaction::create([
                    'user_id'      => $user->id,
                    'action_type'  => $actionType,
                    'exp_gained'   => $expToAdd,
                    'reference_id' => $referenceId,
                    'created_at'   => $now,
                ]);

                // Update or create learning log by date
                $learningLog = UserLearningLog::firstOrCreate(
                    [
                        'user_id'       => $user->id,
                        'learning_date' => $todayDate,
                    ],
                    [
                        'exp_gained' => 0,
                    ]
                );

                $learningLog->increment('exp_gained', $expToAdd);
                $newTodayTotalExp = $learningLog->fresh()->exp_gained;

                // Update user total EXP
                $user->increment('exp_total', $expToAdd);

                // Streak is maintained at a low threshold (streak_min_exp) so a short session keeps the chain.
                // Daily goal is a separate, higher target used for the progress ring and leaderboard EXP.
                $streakMinExp = (int) config('gamification.streak_min_exp', 10);
                $dailyGoal    = (int) config('gamification.daily_goal_exp', 50);

                // Check if the learner just crossed the streak threshold for the first time today.
                $crossedStreakThreshold = ($newTodayTotalExp >= $streakMinExp && ($newTodayTotalExp - $expToAdd) < $streakMinExp);
                $streakIncreased = false;

                if ($crossedStreakThreshold) {
                    $yesterdayDate = Carbon::yesterday($timezone)->toDateString();
                    $lastDate = $user->last_learning_date ? Carbon::parse($user->last_learning_date)->toDateString() : null;

                    if ($lastDate === $yesterdayDate) {
                        // Consecutive day — continue the streak.
                        $user->current_streak = $user->current_streak + 1;
                        $streakIncreased = true;
                    } elseif ($lastDate === $todayDate) {
                        // Already counted streak for today, do not increase.
                    } else {
                        // Gap of more than one day, or first-ever session — reset to 1.
                        $user->current_streak = 1;
                        $streakIncreased = true;
                    }

                    $user->longest_streak    = max((int) $user->longest_streak, (int) $user->current_streak);
                    $user->last_learning_date = $todayDate;
                    $user->save();
                }

                // Retrieve updated user state once
                $freshUser = $user->fresh();

                // Calculate level progression info
                $levelInfo = $this->calculateLevelInfo((int) $freshUser->exp_total);

                return [
                    'exp_gained'       => $expToAdd,
                    'today_exp'        => $newTodayTotalExp,
                    'exp_total'        => $freshUser->exp_total,
                    'current_streak'   => $freshUser->current_streak,
                    'longest_streak'   => $freshUser->longest_streak,
                    'streak_increased' => $streakIncreased,
                    'reached_goal'     => $newTodayTotalExp >= $dailyGoal,
                    'level_info'       => $levelInfo,
                ];
            });
        } finally {
            // Always release lock whether successful or failed
            $lock->release();
        }
    }

    /**
     * Get level threshold mapping (Level => Cumulative EXP required).
     *
     * @return array<int, int>
     */
    public function getLevelThresholds(): array
    {
        return config('gamification.levels.thresholds', [
            1  => 0,
            2  => 50,
            3  => 125,
            4  => 225,
            5  => 350,
            6  => 500,
            7  => 675,
            8  => 875,
            9  => 1100,
            10 => 1350,
            11 => 1625,
            12 => 1925,
            13 => 2250,
            14 => 2600,
            15 => 2975,
            16 => 3375,
            17 => 3800,
            18 => 4250,
            19 => 4725,
            20 => 5225,
            21 => 5750,
            22 => 6300,
            23 => 6875,
            24 => 7475,
            25 => 8100,
            26 => 8750,
            27 => 9425,
            28 => 10125,
            29 => 10850,
            30 => 11600,
        ]);
    }

    /**
     * Calculate comprehensive level progression info for a given cumulative EXP.
     * Progression follows arithmetic curve (+25 EXP per level, Max Level: 30 at 11,600 EXP).
     *
     * @param int $expTotal
     * @return array
     */
    public function calculateLevelInfo(int $expTotal): array
    {
        $thresholds = $this->getLevelThresholds();
        $maxLevel = (int) (config('gamification.levels.max_level') ?? max(array_keys($thresholds)));
        $maxThreshold = $thresholds[$maxLevel] ?? 11600;

        $expTotal = max(0, $expTotal);

        if ($expTotal >= $maxThreshold) {
            $prevThreshold = $thresholds[$maxLevel - 1] ?? ($maxThreshold - 750);
            $expNeededInLevel = $maxThreshold - $prevThreshold;

            return [
                'level'                  => $maxLevel,
                'level_badge'            => 'Lv.' . $maxLevel,
                'current_level_base_exp' => $maxThreshold,
                'next_level_exp'         => $maxThreshold,
                'exp_in_level'           => $expNeededInLevel,
                'exp_needed_in_level'    => $expNeededInLevel,
                'progress_percent'       => 100,
                'is_max'                 => true,
            ];
        }

        $currentLevel = 1;
        foreach ($thresholds as $lvl => $reqExp) {
            if ($expTotal >= $reqExp) {
                $currentLevel = $lvl;
            } else {
                break;
            }
        }

        $currentBaseExp = $thresholds[$currentLevel];
        $nextLevel = min($maxLevel, $currentLevel + 1);
        $nextBaseExp = $thresholds[$nextLevel];
        $expNeededInLevel = $nextBaseExp - $currentBaseExp;
        $expInLevel = $expTotal - $currentBaseExp;

        $progressPercent = $expNeededInLevel > 0
            ? (int) round(($expInLevel / $expNeededInLevel) * 100)
            : 100;

        return [
            'level'                  => $currentLevel,
            'level_badge'            => 'Lv.' . $currentLevel,
            'current_level_base_exp' => $currentBaseExp,
            'next_level_exp'         => $nextBaseExp,
            'exp_in_level'           => $expInLevel,
            'exp_needed_in_level'    => $expNeededInLevel,
            'progress_percent'       => min(100, max(0, $progressPercent)),
            'is_max'                 => false,
        ];
    }

    /**
     * Get 90-day data (standard 13 weeks = 91 days) for contribution heatmap
     *
     * @param int|null $userId
     * @return array
     */
    public function getHeatmapData(?int $userId = null): array
    {
        $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $today = Carbon::today($timezone);

        // 13 weeks standard (91 days): from Sunday of 12 weeks before to Saturday of current week
        $startDate = $today->copy()->subWeeks(12)->startOfWeek(Carbon::SUNDAY);
        $endDate = $today->copy()->endOfWeek(Carbon::SATURDAY);

        $logs = collect();
        $transactions = collect();

        if ($userId) {
            $logs = UserLearningLog::where('user_id', $userId)
                ->where('learning_date', '>=', $startDate->toDateString())
                ->where('learning_date', '<=', $today->toDateString())
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->learning_date)->toDateString();
                });

            // Get detailed EXP transaction history to display specific activity names
            $transactions = UserExpTransaction::where('user_id', $userId)
                ->where('created_at', '>=', $startDate->copy()->startOfDay())
                ->where('created_at', '<=', $today->copy()->endOfDay())
                ->get()
                ->groupBy(function ($t) use ($timezone) {
                    return Carbon::parse($t->created_at)->timezone($timezone)->toDateString();
                });
        }

        $actionNames = [
            'hsk_mock_exam'      => 'Làm bài thi thử HSK',
            'course_vocab'       => 'Học từ vựng bài học',
            'course_dialogue'    => 'Học bài hội thoại',
            'course_grammar'     => 'Học ngữ pháp',
            'course_practice'    => 'Làm bài tập thực hành',
            'flashcard_remember' => 'Luyện thẻ ghi nhớ từ vựng',
            'pinyin_practice'    => 'Luyện phản xạ Pinyin',
        ];

        $days = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dateStr = $current->toDateString();
            $isFuture = $current->gt($today);
            $log = $isFuture ? null : $logs->get($dateStr);
            $exp = $log ? (int) $log->exp_gained : 0;

            // Determine color level (0 - 4)
            $level = 0;
            if (!$isFuture) {
                if ($exp > 0 && $exp < 20) {
                    $level = 1;
                } elseif ($exp >= 20 && $exp < 50) {
                    $level = 2;
                } elseif ($exp >= 50 && $exp < 80) {
                    $level = 3;
                } elseif ($exp >= 80) {
                    $level = 4;
                }
            }

            // Summarize list of activities in the day
            $activities = [];
            if (!$isFuture) {
                $dayTrans = $transactions->get($dateStr, collect());
                if ($dayTrans->isNotEmpty()) {
                    foreach ($dayTrans->groupBy('action_type') as $actType => $items) {
                        $activities[] = $actionNames[$actType] ?? 'Hoạt động học tập';
                    }
                } elseif ($exp > 0) {
                    $activities[] = 'Hoàn thành bài học';
                }
            }

            $days[] = [
                'date' => $dateStr,
                'day_of_week' => $current->dayOfWeek, // 0: Sun, 1: Mon, ...
                'formatted_date' => $current->translatedFormat('d/m/Y'),
                'full_formatted_date' => $current->locale('vi')->translatedFormat('l, j \t\h\á\n\g n, Y'),
                'exp' => $exp,
                'level' => $level,
                'is_today' => $current->isToday(),
                'is_future' => $isFuture,
                // A day is marked as "studied" only when the learner earned at least
                // streak_min_exp EXP — the same threshold used to maintain the streak.
                // This keeps the Heatmap and the Header streak indicator in sync.
                'is_checked_in' => ($exp >= (int) config('gamification.streak_min_exp', 10)),
                'activities' => $activities,
            ];

            $current->addDay();
        }

        return $days;
    }

    /**
     * Get Gamification Leaderboard by EXP & Streak
     *
     * @param string $timeframe 'all_time' | 'month' | 'week'
     * @param int $limit
     * @return array
     */
    public function getGamificationLeaderboard(string $timeframe = 'all_time', int $limit = 5): array
    {
        $timezone = config('gamification.timezone', 'Asia/Ho_Chi_Minh');
        $now = Carbon::now($timezone);

        $baseQuery = User::where(function ($q) {
            $q->where('role', '!=', User::ROLE_ADMIN)
                ->orWhereNull('role');
        });

        if ($timeframe === 'week') {
            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek = $now->copy()->endOfWeek();

            // Calculate total EXP within the week from user_exp_transactions
            $topUsers = (clone $baseQuery)
                ->withSum(['expTransactions as period_exp' => function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                }], 'exp_gained')
                ->having('period_exp', '>', 0)
                ->orderByDesc('period_exp')
                ->orderByDesc('current_streak')
                ->orderByDesc('exp_total')
                ->take($limit)
                ->get();
        } elseif ($timeframe === 'month') {
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            // Calculate total EXP within the month from user_exp_transactions
            $topUsers = (clone $baseQuery)
                ->withSum(['expTransactions as period_exp' => function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                }], 'exp_gained')
                ->having('period_exp', '>', 0)
                ->orderByDesc('period_exp')
                ->orderByDesc('current_streak')
                ->orderByDesc('exp_total')
                ->take($limit)
                ->get();
        } else {
            // All time: Based on total EXP and streak
            $topUsers = (clone $baseQuery)
                ->where('exp_total', '>', 0)
                ->orderByDesc('exp_total')
                ->orderByDesc('current_streak')
                ->orderByDesc('id')
                ->take($limit)
                ->get();
        }

        $leaderboard = $topUsers->map(function ($u, $index) use ($timeframe) {
            $fullName = $u->full_name;
            $expValue = ($timeframe === 'all_time') ? (int) ($u->exp_total ?? 0) : (int) ($u->period_exp ?? 0);
            $levelInfo = $this->calculateLevelInfo((int) ($u->exp_total ?? 0));

            return [
                'rank'           => $index + 1,
                'user_id'        => $u->id,
                'name'           => $fullName,
                'avatar'         => $u->avatar_url,
                'exp'            => number_format($expValue) . ' EXP',
                'raw_exp'        => $expValue,
                'streak'         => (int) ($u->current_streak ?? 0),
                'longest_streak' => (int) ($u->longest_streak ?? 0),
                'level'          => $levelInfo['level'],
                'level_badge'    => $levelInfo['level_badge'],
                'badge'          => $levelInfo['level_badge'],
            ];
        })->values()->toArray();

        return [
            'timeframe' => $timeframe,
            'leaderboard' => $leaderboard,
        ];
    }
}
