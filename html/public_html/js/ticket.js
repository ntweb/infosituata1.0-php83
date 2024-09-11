function generateUUID() { // Public Domain/MIT
    var d = new Date().getTime();//Timestamp
    var d2 = ((typeof performance !== 'undefined') && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16;//random number between 0 and 16
        if(d > 0){//Use timestamp until depleted
            r = (d + r)%16 | 0;
            d = Math.floor(d/16);
        } else {//Use microseconds since page-load if supported
            r = (d2 + r)%16 | 0;
            d2 = Math.floor(d2/16);
        }
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}

function onTicketSendCallback() {
    var uuid = $('#ticketId').val();

    $('#modalTicket').modal('toggle');
    $('#ticketFrm')[0].reset();

    var ps = document.getElementById('print-screen');
    html2canvas(ps).then(function(canvas) {
        var base64 = canvas.toDataURL();
        uploadPrintScreen(uuid, base64);
    });
}

function uploadPrintScreen(uuid, base64) {
    // console.log(uuid);
    // console.log(base64);

    var data = new FormData();
    data.append("_token", $('meta[name="csrf-token"]').attr('content'));
    data.append("ticketBase64PS", base64);
    data.append("ticketId", uuid);

    var route = url + '/dashboard/ticket/ps';

    $.ajax ({
        data: data,
        type: "POST",
        url: route,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data) {
            console.log(data);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

$(document).ready(function($) {
   $(document).on('click', '#btnSendTicket', function() {
       ticketBase64PS = null;

        if (!$('#ticketId').val()) {
            $('#ticketId').val(generateUUID());
        }

        $('#ticketUrl').val(window.location.href);
        $('#ticketFrm').submit();
   });
});



