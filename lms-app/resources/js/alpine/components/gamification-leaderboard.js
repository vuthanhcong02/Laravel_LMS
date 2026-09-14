export default function gamificationLeaderboard(initialData = {}) {
    return {
        leaderboard: initialData.items || [],
        timeframe: initialData.timeframe || 'all_time',
        loading: false,

        get top1() {
            return this.leaderboard.find(item => item.rank === 1) || null;
        },
        get top2() {
            return this.leaderboard.find(item => item.rank === 2) || null;
        },
        get top3() {
            return this.leaderboard.find(item => item.rank === 3) || null;
        },
        get restItems() {
            return this.leaderboard.filter(item => item.rank > 3);
        },
        get hasTop3() {
            return this.leaderboard.length > 0;
        },

        async changeTimeframe(newTimeframe) {
            if (this.timeframe === newTimeframe || this.loading) return;
            this.timeframe = newTimeframe;
            this.loading = true;

            try {
                const response = await fetch(`/api/leaderboard/gamification?timeframe=${newTimeframe}&limit=8`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        this.leaderboard = result.data || [];
                    }
                }
            } catch (error) {
                console.error('Lỗi tải bảng xếp hạng:', error);
            } finally {
                this.loading = false;
            }
        }
    };
}
