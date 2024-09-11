// Sliders & Carousels

$( document ).ready(function() {

    if ($('#calendar').length) {

        console.log(url + '/dashboard/scadenzario/calendar');
        $('#calendar').fullCalendar({
            locale: 'it',
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            height: 650,
            themeSystem: 'bootstrap4',
            bootstrapFontAwesome: true,
            // defaultDate: new Date(), si basa su oggi
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: url + '/calendar'
        });

        // $('#calendar').fullCalendar('refetchEvents');

    }

});
