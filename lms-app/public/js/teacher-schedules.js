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
            var startTime = info.event.startStr.includes('T') ? info.event.startStr.split('T')[1].substring(0, 5) : '';
            var endTime = info.event.endStr.includes('T') ? info.event.endStr.split('T')[1].substring(0, 5) : '';
            alert('Lớp: ' + info.event.title + '\nThời gian: ' + (startTime && endTime ? startTime + ' - ' + endTime : 'Cả ngày'));
        }
    });

    calendar.render();
});
