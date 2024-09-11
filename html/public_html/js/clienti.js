function initUICliente() {
    $('#fl_soggetto_privato').trigger('change');
    $('#fl_persona_fisica').trigger('change');
    $('#fl_ente_pubblico').trigger('change');
}

function clienteInsertedCallback() {
    var route = $('#div-list-clienti').data('route');
    getHtmlNoScroll(route, '#div-list-clienti', function () {
        $('#dashboard_user_index').DataTable({
            paging: false
        });
    });
}

function clienteSearchCallback() {
    $('#modalClientiSearch').modal('hide');
    $('#dashboard_user_index').DataTable({
        paging: false
    });
}


$(document).ready(function($) {
    $(document).on('click', '.btnEditCliente', function() {
        var h = $('body').height() - 200;

        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalCliente').modal('toggle');
            $('#modal-body-cliente').css('max-height', h+'px');
            setTimeout(function() {
                initUI();
                initUICliente();
            }, 1000);
        }, 'html');
    });

    $(document).on('change', '#fl_soggetto_privato', function() {
        if($(this).is(':checked')) {
            $('#piva').val(null).attr('disabled', 'disabled');
            $('#fl_ente_pubblico').prop('checked', false).attr('disabled', 'disabled');
        }
        else {
            $('#piva').removeAttr('disabled');
            $('#fl_ente_pubblico').removeAttr('disabled');
        }
    });

    $(document).on('change', '#fl_persona_fisica', function() {
        if($(this).is(':checked')) {
            $('#cognome').removeAttr('disabled');
            $('#nome').removeAttr('disabled');
            $('#data_nascita').removeAttr('disabled');

            $('#fl_ente_pubblico').prop('checked', false).attr('disabled', 'disabled');
        }
        else {
            $('#cognome').val(null).attr('disabled', 'disabled');
            $('#nome').val(null).attr('disabled', 'disabled');
            $('#data_nascita').val(null).attr('disabled', 'disabled');
            $('#fl_ente_pubblico').removeAttr('disabled');
        }
    });

    $(document).on('change', '#fl_ente_pubblico', function() {
        if($(this).is(':checked')) {
            $('#fl_split_payment_da_data').val(null).attr('disabled', 'disabled');
            $('#tipo_fattura').val('pa');
            $('label[for=fl_split_payment]').html('Escludi da gestione Split payment');
        }
        else {
            $('#fl_split_payment_da_data').removeAttr('disabled');
            $('#tipo_fattura').val('b2b');
            $('label[for=fl_split_payment]').html('Altri soggetti Split payment');
        }
    });
});


