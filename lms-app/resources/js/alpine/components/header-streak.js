/**
 * Component Alpine.js quản lý Widget Chuỗi học tập (Streak) và Điểm kinh nghiệm (EXP) trên Header.
 *
 * @param {Object} config Cấu hình khởi tạo từ Blade view
 */
export const headerStreakWidget = (config = {}) => ({
    isLoggedIn: Boolean(config.isLoggedIn),
    streak: Number(config.streak || 0),
    longestStreak: Number(config.longestStreak || 0),
    todayExp: Number(config.todayExp || 0),
    goalExp: Number(config.goalExp || 50),
    expTotal: Number(config.expTotal || 0),
    tooltipOpen: false,

    /**
     * Phần trăm hoàn thành mục tiêu EXP ngày hôm nay (0 - 100%).
     */
    get percent() {
        if (this.goalExp <= 0) return 100;
        return Math.min(100, Math.round((this.todayExp / this.goalExp) * 100));
    },

    /**
     * Kiểm tra học viên đã hoàn thành mục tiêu ngày hôm nay chưa.
     */
    get reachedGoal() {
        return this.isLoggedIn && this.todayExp >= this.goalExp;
    },

    /**
     * Lắng nghe sự kiện cập nhật EXP từ các hành động (Quiz, Mock Exam, Flashcard, Lesson Tab).
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
