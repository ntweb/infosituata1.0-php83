function getGanttDaysDifferenceFromLineStart (d1) {

    d1 = d1.split('-');
    var date1 = new Date(d1[1]+'/'+d1[2]+'/'+d1[0]);
    var date2 = new Date($('#end').val());

    var difference = date2.getTime() - date1.getTime();
    var days = Math.ceil(difference / (1000 * 3600 * 24));

    // console.log('days', days);
    return days;
}

function invertColor(hex, bw) {
    if (hex.indexOf('#') === 0) {
        hex = hex.slice(1);
    }
    // convert 3-digit hex to 6-digits.
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    if (hex.length !== 6) {
        throw new Error('Invalid HEX color.');
    }
    var r = parseInt(hex.slice(0, 2), 16),
        g = parseInt(hex.slice(2, 4), 16),
        b = parseInt(hex.slice(4, 6), 16);
    if (bw) {
        // https://stackoverflow.com/a/3943023/112731
        return (r * 0.299 + g * 0.587 + b * 0.114) > 186
            ? '#000000'
            : '#FFFFFF';
    }
    // invert color components
    r = (255 - r).toString(16);
    g = (255 - g).toString(16);
    b = (255 - b).toString(16);
    // pad each with zeros and return
    return "#" + padZero(r) + padZero(g) + padZero(b);
}

function padZero(str, len) {
    len = len || 2;
    var zeros = new Array(len).join('0');
    return (zeros + str).slice(-len);
}

function drawGanttLines() {

    var data = JSON.parse($('#data-events').val());
    // console.log('drawGanttLines', data);

    var tdBorder = 1;
    var eventLineHeight = 22;
    var eventMarginFromPrevious = 24;

    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        var lines = item.lines;
        // lines = lines.sort(sortEvent);

        console.log('-------------');
        console.log(item.item_id);
        console.log(lines);

        var nEventsMax = lines.length;
        var calcStickyLeftMargin = $('#calcStickyLeftMargin').width();

        for (var ii = 0; ii < lines.length; ii++) {
            var line = lines[ii];
            var from = line.from;

            var td = $('td[data-item-id='+item.item_id+'][data-day="'+from+'"]');

            // var tdW = td.width();
            var tdW = 80; /** è la larghezza impostata da CSS di table.scheduler td.day **/

            var days = line.days;

            if ($('#start').length) {
                // var daysDifference = getDaysDifference();
                var daysDifference = getGanttDaysDifferenceFromLineStart(from);
                // alert(daysDifference);
                if (days > daysDifference) {
                    days = daysDifference;
                }
            }


            // console.log(nEvents +'*'+ eventLineHeight + '+' + nEvents + '*' + eventMarginFromPrevious);
            var margin = ii * eventMarginFromPrevious;
            // var bgColorAlpha = line.type === 'c' ? line.bgColor + '00' : line.bgColor;
            var l = $('<div>').addClass('event-line')
                .addClass(line.type)
                .addClass(line.class)
                //.css('width', (tdW * days) + ((tdBorder + 1) * days) +  'px')
                .css('background-color', line.bgColor)
                .css('width', (tdW * days) + 'px')
                .css('height', eventLineHeight +  'px')
                .css('color', invertColor(line.bgColor ?? '#ffffff', true))
                .css('top', (margin + 'px'))
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top')
                .attr('data-title', line.title)
                .html('<div class="gantt" style="left: '+calcStickyLeftMargin+'px"><span class="gantt-type">'+ line.type +'</span>'+ line.title +'</div>');
            l.appendTo(td);

            // console.log('tdW * days', tdW,  '*', days);

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
    showHideCommesseSchedulerDetails();
}

function showHideCommesseSchedulerDetails() {
    var showDetails = localStorage.getItem('commesse-scheduler-details');
    if (showDetails) {
        $('.can-hide-row').removeClass('d-none');
    }
    else {
        $('.can-hide-row').addClass('d-none');
    }
}

$(document).ready(function($) {
    if ($('#showHideCommessaDetailsCheckbox').length) {
        var showDetails = localStorage.getItem('commesse-scheduler-details');
        if (showDetails) {
            $('#showHideCommessaDetailsCheckbox').attr('checked', 'checked');
        }
    }

    $(document).on('change', '#showHideCommessaDetailsCheckbox', function() {
        localStorage.removeItem('commesse-scheduler-details');
        if ($(this).is(':checked')) {
            localStorage.setItem('commesse-scheduler-details', true);
        }

        showHideCommesseSchedulerDetails();
    });
});

