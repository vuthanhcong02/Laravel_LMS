export default function streakHeatmapWidget(initialData = {}) {
    return {
        selectedDay: initialData.today || null,
        pinnedDay: initialData.today || null,
        previewDay(day) {
            this.selectedDay = day;
        },
        selectDay(day) {
            this.pinnedDay = day;
            this.selectedDay = day;
        },
        resetPreview() {
            this.selectedDay = this.pinnedDay;
        }
    };
}

