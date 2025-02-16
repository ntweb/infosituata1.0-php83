function initUIIva() {
    $('#fl_esenzione').trigger('change');
}

function ivaInsertedCallback() {
    var route = $('#div-list-iva').data('route');
    getHtmlNoScroll(route, '#div-list-iva', function () {
        $('#dashboard_user_index').DataTable({
            retrieve: true,
            paging: false
        });
    });
}

function ivaSearchCallback() {
    $('#modalIvaSearch').modal('hide');
    $('#dashboard_user_index').DataTable({
        retrieve: true,
        paging: false
    });
}


$(document).ready(function($) {
    $(document).on('click', '.btnEditIva', function() {
        var h = $('body').height() - 200;

        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalIva').modal('toggle');
            setTimeout(function() {
                initUI();
                initUIIva();
            }, 1000);
        }, 'html');
    });

    $(document).on('change', '#fl_esenzione', function() {
        if($(this).is(':checked')) {
            $('#iva').val(0).attr('disabled', 'disabled');
        }
        else {
            $('#iva').removeAttr('disabled');
        }
    });
});


