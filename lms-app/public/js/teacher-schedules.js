document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventsUrl = calendarEl.getAttribute('data-events-url');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay,dayGridMonth,listWeek'
        },
        buttonText: {
            today: 'Hôm nay',
            month: 'Tháng',
            week: 'Tuần (Lưới)',
            day: 'Ngày',
            list: 'Danh sách'
        },
        dayHeaderFormat: { weekday: 'short', day: '2-digit', month: '2-digit' }, 
        slotMinTime: '07:00:00',
        slotMaxTime: '22:00:00',
        slotDuration: '01:00:00',
        scrollTime: '17:00:00',
        height: 'auto',
        contentHeight: 'auto',
        expandRows: false,
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        allDaySlot: false,
        editable: false, // Readonly
        selectable: false, // Readonly
        locale: 'vi',
        events: eventsUrl,
        nowIndicator: true,
        
        eventClick: function(info) {
            var startTime = info.event.startStr && info.event.startStr.includes('T')
                ? info.event.startStr.split('T')[1].substring(0, 5)
                : (info.event.extendedProps && info.event.extendedProps.time_range ? info.event.extendedProps.time_range.split(' - ')[0] : '');
            var endTime = info.event.endStr && info.event.endStr.includes('T')
                ? info.event.endStr.split('T')[1].substring(0, 5)
                : (info.event.extendedProps && info.event.extendedProps.time_range ? info.event.extendedProps.time_range.split(' - ')[1] : '');
            var timeRange = (startTime && endTime)
                ? (startTime + ' - ' + endTime)
                : (info.event.extendedProps && info.event.extendedProps.time_range ? info.event.extendedProps.time_range : 'Cả ngày');

            var detail = {
                title: info.event.title,
                color: info.event.backgroundColor,
                category: (info.event.extendedProps && info.event.extendedProps.category_name) || '',
                studentsCount: (info.event.extendedProps && info.event.extendedProps.students_count) || 0,
                dayName: (info.event.extendedProps && info.event.extendedProps.day_name) || '',
                timeRange: timeRange,
                startDate: (info.event.extendedProps && info.event.extendedProps.start_date) || '',
                endDate: (info.event.extendedProps && info.event.extendedProps.end_date) || '',
                courseUrl: (info.event.extendedProps && info.event.extendedProps.course_url) || ''
            };

            window.dispatchEvent(new CustomEvent('open-schedule-modal', { detail: detail }));
        }
    });

    calendar.render();
});
