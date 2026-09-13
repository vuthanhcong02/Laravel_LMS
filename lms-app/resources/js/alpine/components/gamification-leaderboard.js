export default function gamificationLeaderboard(initialData = {}) {
    return {
        leaderboard: initialData.items || [],
        timeframe: initialData.timeframe || 'all_time',
        loading: false,

        async changeTimeframe(newTimeframe) {
            if (this.timeframe === newTimeframe || this.loading) return;
            this.timeframe = newTimeframe;
            this.loading = true;

            try {
                const response = await fetch(`/api/leaderboard/gamification?timeframe=${newTimeframe}&limit=5`, {
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
