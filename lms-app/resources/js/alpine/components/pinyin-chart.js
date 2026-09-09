export const pinyinBoardApp = () => ({
    currentPinyin: null,
    selectedTone: null,
    isFullscreen: false,
    showGuideModal: false,
    isLoadingPinyin: false,
    isModalOpen: false,

    _cache: new Map(),
    _pendingId: null,

    init() {
        window.addEventListener('pinyin-load', (e) => {
            this.loadPinyin(e.detail.id, e.detail.full);
        });

        window.addEventListener('pinyin-prefetch', (e) => {
            this._prefetch(e.detail.id);
        });
    },

    _prefetch(id) {
        if (!id || this._cache.has(id)) return;
        fetch(`/bang-phien-am-pinyin/${id}/detail`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.ok ? r.json() : null)
            .then(data => { if (data) this._cache.set(id, data); })
            .catch(() => {});
    },

    async loadPinyin(id, full) {
        if (this.currentPinyin && this.currentPinyin.id === id && !this.isLoadingPinyin) return;

        this.isModalOpen = true;

        if (!id) {
            this.isLoadingPinyin = false;
            this.currentPinyin = { id: null, full, tones: [] };
            this.selectedTone = null;
            return;
        }

        if (this._cache.has(id)) {
            this.isLoadingPinyin = false;
            this.currentPinyin = this._cache.get(id);
            this.selectedTone = this.currentPinyin.tones?.length > 0
                ? this.currentPinyin.tones[0]
                : null;
            return;
        }

        this._pendingId = id;
        this.currentPinyin = { id, full, tones: [] };
        this.selectedTone = null;
        this.isLoadingPinyin = true;

        try {
            const res = await fetch(`/bang-phien-am-pinyin/${id}/detail`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();

            this._cache.set(id, data);

            if (this._pendingId === id) {
                this.currentPinyin = data;
                this.selectedTone = data.tones?.length > 0 ? data.tones[0] : null;
            }
        } catch (err) {
            console.error('[PinyinBoard] Fetch error:', err);
        } finally {
            if (this._pendingId === id) {
                this.isLoadingPinyin = false;
            }
        }
    },

    handleKeyup(event) {
        if ((event.key === 'f' || event.key === 'F') && !this.isModalOpen && !this.showGuideModal) {
            this.isFullscreen = !this.isFullscreen;
        }
        if (event.key === 'Escape') {
            if (this.isModalOpen) {
                this.isModalOpen = false;
                this.currentPinyin = null;
                this.selectedTone = null;
            } else {
                this.isFullscreen = false;
            }
        }
    }
});

export const pinyinDragScroll = () => ({
    isDown: false,
    isDragging: false,
    startX: 0,
    scrollLeft: 0,
    startY: 0,
    scrollTop: 0,
    initDrag(e) {
        this.isDown = true;
        this.isDragging = false;
        this.$el.classList.add('!cursor-grabbing', 'select-none');
        this.startX = e.pageX - this.$el.offsetLeft;
        this.scrollLeft = this.$el.scrollLeft;
        this.startY = e.pageY - this.$el.offsetTop;
        this.scrollTop = this.$el.scrollTop;
    },
    endDrag(e) {
        this.isDown = false;
        this.$el.classList.remove('!cursor-grabbing', 'select-none');
        setTimeout(() => {
            this.isDragging = false;
        }, 50);
    },
    doDrag(e) {
        if (!this.isDown) return;
        const wX = (e.pageX - this.$el.offsetLeft - this.startX) * 1.5;
        const wY = (e.pageY - this.$el.offsetTop - this.startY) * 1.5;
        if (Math.abs(wX) > 5 || Math.abs(wY) > 5) {
            this.isDragging = true;
        }
        if (this.isDragging) {
            e.preventDefault();
            this.$el.scrollLeft = this.scrollLeft - wX;
            this.$el.scrollTop = this.scrollTop - wY;
        }
    },
    handleClick(e) {
        if (this.isDragging) {
            e.stopPropagation();
            e.preventDefault();
        }
    }
});

export const pinyinCrosshair = () => ({
    activeCol: null,
    activeRowEl: null,
    activeInitialEl: null,
    activeThEl: null,
    activeCellEl: null,
    handleMouseOver(e) {
        const td = e.target.closest('td');
        if (!td || !td.dataset.col) {
            this.clearHighlight();
            return;
        }
        if (this.activeCellEl === td) return;

        this.clearHighlight();

        const colKey = td.dataset.col;
        const tr = td.closest('tr');
        const initialTd = tr ? tr.querySelector('td:first-child') : null;
        const th = this.$el.querySelector(`th[data-col='${colKey}']`);

        this.activeCol = colKey;
        this.activeRowEl = tr;
        this.activeInitialEl = initialTd;
        this.activeThEl = th;
        this.activeCellEl = td;

        td.classList.add('pinyin-cell-active');

        if (tr) {
            tr.querySelectorAll('td').forEach(cell => {
                if (cell !== initialTd && cell !== td) cell.classList.add('pinyin-highlight-row');
            });
        }

        this.$el.querySelectorAll(`td[data-col='${colKey}']`).forEach(cell => {
            if (cell !== td) cell.classList.add('pinyin-highlight-col');
        });

        if (th) th.classList.add('pinyin-header-active');
        if (initialTd) initialTd.classList.add('pinyin-initial-active');

        const btn = e.target.closest('[data-pid]');
        if (btn && btn.dataset.pid) {
            window.dispatchEvent(new CustomEvent('pinyin-prefetch', {
                detail: { id: +btn.dataset.pid }
            }));
        }
    },
    clearHighlight() {
        if (this.activeCellEl) this.activeCellEl.classList.remove('pinyin-cell-active');
        if (this.activeThEl) this.activeThEl.classList.remove('pinyin-header-active');
        if (this.activeInitialEl) this.activeInitialEl.classList.remove('pinyin-initial-active');
        if (this.activeRowEl) {
            this.activeRowEl.querySelectorAll('.pinyin-highlight-row').forEach(el => el.classList.remove('pinyin-highlight-row'));
        }
        if (this.activeCol) {
            this.$el.querySelectorAll(`td[data-col='${this.activeCol}'].pinyin-highlight-col`).forEach(el => el.classList.remove('pinyin-highlight-col'));
        }
        this.activeCol = null;
        this.activeRowEl = null;
        this.activeInitialEl = null;
        this.activeThEl = null;
        this.activeCellEl = null;
    }
});

export default {
    pinyinBoardApp,
    pinyinDragScroll,
    pinyinCrosshair
};
