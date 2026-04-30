document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventsUrl = calendarEl.getAttribute('data-events-url');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hôm nay',
            month: 'Tháng',
            week: 'Tuần',
            day: 'Ngày'
        },
        dayHeaderFormat: { weekday: 'long' }, 
        slotMinTime: '07:00:00',
        slotMaxTime: '24:00:00',
        allDaySlot: false,
        editable: false, // Readonly
        selectable: false, // Readonly
        locale: 'vi',
        events: eventsUrl,
        
        eventClick: function(info) {
            // Optional: show info only
            alert('Lớp: ' + info.event.title + '\nThời gian: ' + info.event.startStr.split('T')[1].substring(0,5) + ' - ' + info.event.endStr.split('T')[1].substring(0,5));
        }
    });

    calendar.render();
});
