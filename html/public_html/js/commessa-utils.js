function adjustGantt() {
    console.log('call adjustGantt()');
    var w = $('.app-inner-layout').width();
    var h = $('body').height() - $('.app-header').height() - $('.app-inner-layout').height() - 120;

    $('#gantt20').css('max-width', w+'px');
    $('#gantt20').css('max-height', h+'px');
}
function renderMap() {

    /** Setting height **/
    var h = $('.app-container').height() - $('.app-header').height() - $('.app-inner-layout').height() - 40;
    $('#map').css('height', h + 'px');
    var route = $('#map').attr('data-route');

    var map = L.map('map').setView([42.371651, 13.094561], 6);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var markers = L.markerClusterGroup();

    $.get(route, function(data){
        for (var i = 0; i < data.length; i++) {
            var coord = data[i];
            var marker = L.marker(new L.LatLng(coord.lat, coord.lng), { title: coord.title });
            marker.bindPopup(coord.title);

            markers.addLayer(marker);
        }
        map.addLayer(markers);
    }, 'json');
}

function searchItemAssignmentScheduler(route) {
    var params = $('.searchParameter').serialize();
    getHtml(route+'?'+params, '#resultSearchItem', checkItemInScheduler());
}

function checkItemInScheduler() {
    setTimeout(function() {
        $('input[name="item[]"]').each(function(i, el){
            var v = $(el).val();
            $('#select-'+v).addClass('btn-warning').removeClass('btn-outline-light').html('selezionato');
        });
    }, 1000);
}

function getDaysDifference () {
    /*
    var dates = $('#dates').val().replaceAll(' ', '').split('-');
    var d1 = dates[0].split('/');
    var d2 = dates[1].split('/');
    var date1 = new Date(d1[1]+'/'+d1[0]+'/'+d1[2]);
    var date2 = new Date(d2[1]+'/'+d2[0]+'/'+d2[2]);
    */

    var date1 = new Date($('#start').val());
    var date2 = new Date($('#end').val());

    var difference = date2.getTime() - date1.getTime();
    var days = Math.ceil(difference / (1000 * 3600 * 24));

    return days;
}

function getDaysDifferenceFromLineStart (d1) {
    // d1 = d1.split('-');

    /*
    var dates = $('#dates').val().replaceAll(' ', '').split('-');
    // console.log('d1', d1);
    d1 = d1.split('-');
    var d2 = dates[1].split('/');
    var date1 = new Date(d1[1]+'/'+d1[2]+'/'+d1[0]);
    // console.log('date1', date1, d1[1]+'/'+d1[2]+'/'+d1[0]);
    var date2 = new Date(d2[1]+'/'+d2[0]+'/'+d2[2]);
    */

    // var date1 = new Date($('#start').val());

    // console.log('d1', d1);
    var date1 = new Date(d1);
    var date2 = new Date($('#end').val());

    var difference = date2.getTime() - date1.getTime();
    var days = Math.ceil(difference / (1000 * 3600 * 24));

    // console.log('days', days);
    return days;
}

function getDates (startDate, endDate) {
    var dates = [];
    var currentDate = startDate;
    var addDays = function (days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    }
    while (currentDate <= endDate) {
        dates.push(currentDate);
        currentDate = addDays.call(currentDate, 1);
    }
    return dates;
}

function sortEvent( a, b ) {
    var aFrom = new Date(a.from);
    var bFrom = new Date(b.from);

    if ( aFrom < bFrom ){
        return -1;
    }
    if ( aFrom > bFrom ){
        return 1;
    }
    return 0;
}

function drawLinesBak() {

    var data = JSON.parse($('#data-events').val());

    var tdBorder = 1;
    var eventLineHeight = 22;
    var eventMarginFromPrevious = 24;

    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        var lines = item.lines;
        lines = lines.sort(sortEvent);
        // console.log(lines);
        var nEventsMax = 0;

        for (var ii = 0; ii < lines.length; ii++) {
            var line = lines[ii];
            var td = $('td[data-item-id='+item.item_id+'][data-day="'+line.from+'"]');
            var nEvents = parseInt(td.attr('data-n-events'));

            var tdW = td.width();

            // var daysDifference = getDaysDifference();
            var daysDifference = getDaysDifferenceFromLineStart(line.from);
            var days = line.days;
            if (days > daysDifference) {
                days = daysDifference + 1;
            }

            // console.log(nEvents +'*'+ eventLineHeight + '+' + nEvents + '*' + eventMarginFromPrevious);
            var margin = cc <= 1 ? nEvents : nEvents - 1;
            margin = margin * eventMarginFromPrevious;
            var l = $('<div>').addClass('event-line')
                .addClass(line.type)
                .css('width', (tdW * days) + ((tdBorder + 1) * days) +  'px')
                .css('height', eventLineHeight +  'px')
                // .css('top', ((nEvents * eventLineHeight) + (nEvents * eventMarginFromPrevious)) + 'px')
                // .css('top', ((nEvents * eventMarginFromPrevious) + 'px'))
                .css('top', (margin + 'px'))
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top')
                .attr('data-title', line.title)
                .html('<div>'+ line.title +'</div>');
            l.appendTo(td);

            // console.log(l.prev());
            if (l.prev().length && margin > 0) {
                l.css('top', 0);
            }

            var dates = getDates(new Date(line.from), new Date(line.to));
            $(dates).each(function (i, date) {
                var day = date.toISOString().split('T');
                var td = $('td[data-item-id='+item.item_id+'][data-day="'+day[0]+'"]');
                var nEvents = parseInt(td.attr('data-n-events'));
                td.attr('data-n-events', nEvents + 1);

                if ((nEvents + 1) > nEventsMax) {
                    nEventsMax = nEvents + 1;
                }
            });
        }

        if (nEventsMax > 1) {
            // var h = (nEventsMax * eventLineHeight) + (nEventsMax * eventMarginFromPrevious);
            var h = ((nEventsMax + 1) * eventMarginFromPrevious);
            $('#tr-item-' + item.item_id).css('min-height', h + 'px').css('height', h + 'px');
        }

    }

    initUI();
}

function drawLines() {
    //console.log('a');
    var data = JSON.parse($('#data-events').val());
    // console.log(data);

    var tdBorder = 1;
    var eventLineHeight = 22;
    var eventMarginFromPrevious = 24;

    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        var lines = item.lines;
        lines = lines.sort(sortEvent);
        // console.log(lines);

        var nEventsMax = lines.length;

        for (var ii = 0; ii < lines.length; ii++) {
            var line = lines[ii];
            var td = $('td[data-item-id='+item.item_id+'][data-day="'+line.from+'"]');

            // var tdW = td.width();
            var tdW = 80; /** è la larghezza impostata da CSS di table.scheduler td.day **/

            // var daysDifference = getDaysDifference();
            var daysDifference = getDaysDifferenceFromLineStart(line.from);
            var days = line.days;
            if (days > daysDifference) {
                days = daysDifference + 1;
            }

            // console.log(nEvents +'*'+ eventLineHeight + '+' + nEvents + '*' + eventMarginFromPrevious);
            var margin = ii * eventMarginFromPrevious;
            var l = $('<div>').addClass('event-line')
                .addClass(line.type)
                //.css('width', (tdW * days) + ((tdBorder + 1) * days) +  'px')
                .css('width', (tdW * days) + 'px')
                .css('height', eventLineHeight +  'px')
                .css('top', (margin + 'px'))
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top')
                .attr('data-title', line.title)
                .html('<div>'+ line.title +'</div>');
            l.appendTo(td);

            // console.log(l.prev());

            // if (l.prev().length && margin > 0) {
            if (l.prev().length && margin > 0) {
                l.css('top', 0);
            }
        }


        // var h = (nEventsMax * eventLineHeight) + (nEventsMax * eventMarginFromPrevious);
        var h = (nEventsMax  * eventMarginFromPrevious) + 8;
        $('#tr-item-' + item.item_id).css('min-height', h + 'px').css('height', h + 'px');

    }

    initUI();
}

$(document).ready(function($) {

    if ($('#map').length) {
        renderMap();
    }

    if ($('#gantt20').length) {
        adjustGantt();
    }

    $(document).on('click', '.btnCheckSovrapposizioni', function() {
        if (!$('#fase').val()) {
            toastr.error('Selezionare una fase', 'Errore');
            return;
        }

        var days = getDaysDifference();

        if (days > 60) {
            toastr.error('È possibile selezionare un range massimo di 60 giorni', 'Errore');
            return;
        }

        var w = $('#gantt20').width();
        $('#gantt20').css('max-width', w+'px');

        var frm = $(this).closest('form');
        frm.submit();

    });

    $(document).on('click', '.addItemSearch', function() {
        $('#collapseExample').collapse('show');

        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalAddItemSearch').modal('toggle');
            setTimeout(function() {
                $('#modalAddItemSearch').find('input[type=text]').first().focus();
                initUI();
            }, 500);
        }, 'html');
    });

    $(document).on('click', '.btnSearchItemScheduler', function() {
        var route = $(this).data('route');
        searchItemAssignmentScheduler(route);
    });

    $(document).on('click', '.addItemUl', function() {
        var ulSelector = $(this).attr('data-assign-selector');
        var ul = $(ulSelector);
        var itemId = $(this).attr('data-item-id');
        var itemIdSelector = '#item-' + itemId;
        var label = $(this).attr('data-item-label');

        if (!$(itemIdSelector).length) {
            var li = $('<li>').appendTo(ul);
            var button = $('<button>').addClass('mr-2 btn-transition btn btn-outline-link text-danger deleteItemUl');
            $('<i>').addClass('bx bx-trash').appendTo(button);
            button.appendTo(li);

            $('<span>').html(label).appendTo(li);
            $('<input>').attr('type', 'hidden').attr('name', 'item[]').attr('id', 'item-' + itemId).val(itemId).appendTo(li);
        }

        checkItemInScheduler();
    });

    $(document).on('click', '.deleteItemUl', function(){
        $(this).closest('li').remove();
    });

    $(document).on('click', '.btnShowSchedule', function() {

        var days = getDaysDifference();

        if (days > 60) {
            toastr.error('È possibile selezionare un range massimo di 60 giorni', 'Errore');
            return;
        }

        var inputs = $('input[name="item[]"]');
        if (inputs.length) {
            $('#collapseExample').collapse('hide');

            var frm = $(this).closest('form');
            var w = frm.width();
            $('#scheduler-container').css('max-width', w+'px');
            frm.submit();
        }
        else {
            toastr.error('Selezionare utenti, mezzi e attrezzature da contnrollare', 'Errore');
        }
    });

    $(document).on('click', '.btnShowScheduleCommesse', function() {
       var d1 = new Date($('#start').val());
       var d2 = new Date($('#end').val());

       if (d2 < d1) {
           toastr.warning('Date non conformi', 'Attenzione');
           return false;
       }

        var w = $('#gantt20').width();
        $('#gantt20').css('max-width', w+'px');

       var frm = $(this).closest('form');
       frm.submit();
    });

    $(document).on('change', 'input[id=start]', function() {
        var st = $(this).val();
        var end = $('input[id=end]').val();

        if (!end) {
            $('input[id=end]').val(st);
        }

        $('input[id=end]').removeAttr('min');
        if (st) {
            $('input[id=end]').attr('min', st);
        }
    });

    $(document).on('change', 'input[id=end]', function() {
        var end = $(this).val();
        var st = $('input[id=start]').val();

        if (!st) {
            $('input[id=start]').val(end);
        }

        $('input[id=start]').removeAttr('max');
        if (end) {
            $('input[id=start]').attr('max', end);
        }
    });
});




