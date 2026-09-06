export default () => ({
    levelTab: 'all', 
    leaderboardFilter: 'all_time', 
    leaderboardLevel: 'all',
    loadingLeaderboard: false,
    socialDockExpanded: true, 
    leaderboard: window.hskLeaderboardData || [],
    
    // Top 20 Leaderboard Modal state
    fullLeaderboardOpen: false,
    fullLeaderboardLevel: 'all',
    fullLeaderboardFilter: 'all_time',
    loadingFullLeaderboard: false,
    fullLeaderboard: [],
    currentUserRank: null,
    currentUserResult: null,

    init() {
        // Watch sidebar widget filter changes
        this.$watch('leaderboardLevel', () => {
            this.fetchLeaderboard();
        });
        this.$watch('leaderboardFilter', () => {
            this.fetchLeaderboard();
        });

        // Watch Top 20 Modal filter changes
        this.$watch('fullLeaderboardLevel', () => {
            if (this.fullLeaderboardOpen) {
                this.fetchFullLeaderboard();
            }
        });
        this.$watch('fullLeaderboardFilter', () => {
            if (this.fullLeaderboardOpen) {
                this.fetchFullLeaderboard();
            }
        });
    },

    // Fetch compact leaderboard (Top 8) for sidebar widget
    async fetchLeaderboard() {
        this.loadingLeaderboard = true;
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('leaderboard_level', this.leaderboardLevel);
            url.searchParams.set('timeframe', this.leaderboardFilter);
            url.searchParams.set('limit', '8');
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
    },

    // Open Top 20 leaderboard modal and fetch data
    async openFullLeaderboard() {
        this.fullLeaderboardOpen = true;
        this.fullLeaderboardLevel = this.leaderboardLevel || 'all';
        this.fullLeaderboardFilter = this.leaderboardFilter || 'all_time';
        await this.fetchFullLeaderboard();
    },

    // Fetch Top 20 leaderboard data for modal
    async fetchFullLeaderboard() {
        this.loadingFullLeaderboard = true;
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('leaderboard_level', this.fullLeaderboardLevel);
            url.searchParams.set('timeframe', this.fullLeaderboardFilter);
            url.searchParams.set('limit', '20');
            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.leaderboard) {
                    this.fullLeaderboard = data.leaderboard;
                }
                this.currentUserRank = data.currentUserRank;
                this.currentUserResult = data.currentUserResult;
            }
        } catch (error) {
            console.error('Error fetching Top 20 leaderboard:', error);
        } finally {
            this.loadingFullLeaderboard = false;
        }
    }
});
