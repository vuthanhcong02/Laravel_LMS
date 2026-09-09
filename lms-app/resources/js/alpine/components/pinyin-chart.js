/**
 * Alpine Components cho Bảng Pinyin Tương Tác (Pinyin Chart)
 */

export const pinyinBoardApp = () => ({
    currentPinyin: null,
    selectedTone: null,
    isFullscreen: false,
    showGuideModal: false,
    handleKeyup(event) {
        if ((event.key === 'f' || event.key === 'F') && !this.currentPinyin && !this.showGuideModal) {
            this.isFullscreen = !this.isFullscreen;
        }
        if (event.key === 'Escape') {
            this.isFullscreen = false;
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

export default {
    pinyinBoardApp,
    pinyinDragScroll
};
