function initUIFattura() {

}

function ivaInsertedCallback() {
    var route = $('#div-list-fattura').data('route');
    getHtmlNoScroll(route, '#div-list-fattura', function () {
        $('#dashboard_user_index').DataTable({
            retrieve: true,
            paging: false
        });
    });
}

function fatturaSearchCallback() {
    $('#modalFatturaSearch').modal('hide');
    $('#dashboard_user_index').DataTable({
        retrieve: true,
        paging: false
    });
}


$(document).ready(function($) {
    $(document).on('click', '.btnEditFattura', function() {
        var h = $('body').height() - 200;

        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalFattura').modal('toggle');
            setTimeout(function() {
                initUI();
                initUIIva();
            }, 1000);
        }, 'html');
    });
});


