/**
 * 🔍 AJAX Topic Search & Level Filter for Sentence Study
 *
 * Provides real-time debounced AJAX search, instant HSK level filtering,
 * URL history synchronization, and loading indicators without full page reloads.
 */
export default function sentenceTopicSearch(config = {}) {
    return {
        topics: Array.isArray(config.initialTopics) ? config.initialTopics : [],
        selectedLevel: config.level || 'HSK1',
        mode: config.mode || 'scramble',
        searchQuery: config.search || '',
        searchUrl: config.searchUrl || '/luyen-ghep-cau',
        practiceBaseUrl: config.practiceBaseUrl || '/luyen-ghep-cau',
        isAuthenticated: Boolean(config.isAuthenticated),
        isLoading: false,
        abortController: null,

        init() {
            // Synchronize with browser back/forward buttons
            window.addEventListener('popstate', (e) => {
                if (e.state && e.state.level) {
                    this.selectedLevel = e.state.level;
                    this.searchQuery = e.state.q || '';
                    this.fetchTopics(false);
                }
            });
        },

        submitSearch() {
            this.fetchTopics(true);
        },

        clearSearch() {
            this.searchQuery = '';
            this.fetchTopics(true);
        },

        selectLevel(lvl) {
            if (this.selectedLevel === lvl && !this.searchQuery) return;
            this.selectedLevel = lvl;
            this.fetchTopics(true);
        },

        async fetchTopics(updateUrl = true) {
            if (this.abortController) {
                this.abortController.abort();
            }
            this.abortController = new AbortController();

            this.isLoading = true;

            const params = new URLSearchParams({
                mode: this.mode,
                level: this.selectedLevel,
            });

            if (this.searchQuery.trim() !== '') {
                params.set('q', this.searchQuery.trim());
            }

            const url = `${this.searchUrl}?${params.toString()}`;

            if (updateUrl && window.history && window.history.replaceState) {
                window.history.replaceState(
                    { level: this.selectedLevel, q: this.searchQuery, mode: this.mode },
                    '',
                    url
                );
            }

            try {
                const response = await fetch(url, {
                    signal: this.abortController.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }

                const data = await response.json();
                if (data && Array.isArray(data.topics)) {
                    this.topics = data.topics;
                    if (data.selectedLevel) {
                        this.selectedLevel = data.selectedLevel;
                    }
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Error fetching sentence topics:', err);
                }
            } finally {
                this.isLoading = false;
            }
        },

        getPracticeUrl(topic) {
            return `${this.practiceBaseUrl}/${topic.level}/${topic.id}?mode=${this.mode}`;
        },

        handleTopicClick(topic, event) {
            const url = this.getPracticeUrl(topic);
            if (!this.isAuthenticated) {
                event.preventDefault();
                window.dispatchEvent(new CustomEvent('open-auth-modal', {
                    detail: { redirect: url }
                }));
                return;
            }
            window.location.href = url;
        }
    };
}
