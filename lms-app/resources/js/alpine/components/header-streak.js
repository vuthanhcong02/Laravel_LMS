const defaultThresholds = {
    1: 0, 2: 50, 3: 125, 4: 225, 5: 350,
    6: 500, 7: 675, 8: 875, 9: 1100, 10: 1350,
    11: 1625, 12: 1925, 13: 2250, 14: 2600, 15: 2975,
    16: 3375, 17: 3800, 18: 4250, 19: 4725, 20: 5225,
    21: 5750, 22: 6300, 23: 6875, 24: 7475, 25: 8100,
    26: 8750, 27: 9425, 28: 10125, 29: 10850, 30: 11600
};

/**
 * Alpine.js component managing learning streak and level progress in header.
 *
 * @param {Object} config Initial configuration from Blade view
 */
export const headerStreakWidget = (config = {}) => ({
    isLoggedIn: Boolean(config.isLoggedIn),
    streak: Number(config.streak || 0),
    longestStreak: Number(config.longestStreak || 0),
    todayExp: Number(config.todayExp || 0),
    expTotal: Number(config.expTotal || 0),
    maxLevel: Number(config.maxLevel || 30),
    thresholds: config.thresholds || defaultThresholds,
    tooltipOpen: false,

    /**
     * Compute current level progression details based on tiered threshold curve.
     */
    get levelInfo() {
        const thresholds = this.thresholds || defaultThresholds;
        const maxLevel = Number(this.maxLevel || 30);
        const maxThreshold = Number(thresholds[maxLevel] || 11600);
        const total = Math.max(0, this.expTotal);

        if (total >= maxThreshold) {
            const prevThreshold = Number(thresholds[maxLevel - 1] || (maxThreshold - 750));
            const expNeeded = maxThreshold - prevThreshold;

            return {
                level: maxLevel,
                levelBadge: `Lv.${maxLevel}`,
                currentLevelBaseExp: maxThreshold,
                nextLevelExp: maxThreshold,
                expInLevel: expNeeded,
                expNeeded: expNeeded,
                progressPercent: 100,
                isMax: true
            };
        }

        let currentLevel = 1;
        const levels = Object.keys(thresholds).map(Number).sort((a, b) => a - b);
        for (const lvl of levels) {
            if (total >= thresholds[lvl]) {
                currentLevel = lvl;
            } else {
                break;
            }
        }

        const currentBaseExp = Number(thresholds[currentLevel]);
        const nextLevel = Math.min(maxLevel, currentLevel + 1);
        const nextBaseExp = Number(thresholds[nextLevel]);
        const expNeeded = nextBaseExp - currentBaseExp;
        const expInLevel = total - currentBaseExp;
        const progressPercent = expNeeded > 0
            ? Math.min(100, Math.max(0, Math.round((expInLevel / expNeeded) * 100)))
            : 100;

        return {
            level: currentLevel,
            levelBadge: `Lv.${currentLevel}`,
            currentLevelBaseExp: currentBaseExp,
            nextLevelExp: nextBaseExp,
            expInLevel,
            expNeeded,
            progressPercent,
            isMax: false
        };
    },

    get level() {
        return this.levelInfo.level;
    },

    get levelBadge() {
        return this.levelInfo.levelBadge;
    },

    get levelPercent() {
        return this.levelInfo.progressPercent;
    },

    get isMaxLevel() {
        return this.levelInfo.isMax;
    },

    /**
     * Handle EXP updated event from actions (Quiz, Mock Exam, Flashcard, Lesson Tab).
     */
    onExpUpdated(data) {
        if (!data) return;

        if (data.user) {
            this.streak = Number(data.user.current_streak ?? this.streak);
            this.todayExp = Number(data.user.today_exp ?? this.todayExp);
            this.expTotal = Number(data.user.exp_total ?? this.expTotal);
        } else if (data.gamification) {
            this.streak = Number(data.gamification.current_streak ?? this.streak);
            this.todayExp = Number(data.gamification.today_exp ?? this.todayExp);
            this.expTotal = Number(data.gamification.exp_total ?? this.expTotal);
            this.longestStreak = Number(data.gamification.longest_streak ?? this.longestStreak);
        }
    }
});

export default headerStreakWidget;
