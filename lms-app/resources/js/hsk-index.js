export default () => ({
    levelTab: 'all', 
    leaderboardFilter: 'all_time', 
    leaderboardLevel: 'all',
    loadingLeaderboard: false,
    socialDockExpanded: true, 
    leaderboard: window.hskLeaderboardData || [],
    
    init() {
        this.$watch('leaderboardLevel', () => {
            this.fetchLeaderboard();
        });
    },

    async fetchLeaderboard() {
        this.loadingLeaderboard = true;
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('leaderboard_level', this.leaderboardLevel);
            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.leaderboard) {
                    this.leaderboard = data.leaderboard;
                }
            }
        } catch (error) {
            console.error('Error fetching leaderboard:', error);
        } finally {
            this.loadingLeaderboard = false;
        }
    }
});
