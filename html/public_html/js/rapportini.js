function openModalRapportino() {
    setTimeout(function() {
        initUI();
        $('#rapportinoModal').modal('toggle');
    }, 1000);
}

function reopenLastSavedRapportino(route) {
    window.location.replace(route);
}

$(document).ready(function($) {

    $(document).on('change', '#controller_selector', function() {
        var v = $(this).val();
        $('#items_id').attr('data-controller', v);
        $('#items_id').val(null);
        initUI();

        if (v == 'rapportini-generica') {
            $('#items_id').parent().hide(0);
        }
        else {
            $('#items_id').parent().show(0);
        }
    });

    $(document).on('click', '#btnCreateRapportino', function() {
        var reference = $('#controller_selector').val();
        if (!reference) {
            toastr.error('Selezionare un target');
            return;
        }

        if (reference !== 'rapportini-generica') {
            var items_id = $('#items_id').val();
            if (!items_id) {
                toastr.error('Selezionare un elemento a cui associare il rapportino');
                return;
            }
        }

        var frm = $(this).closest('form');
        frm.submit();
    });

    $(document).on('click', '.showRapportinoGenerico', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#rapportinoModal').modal('toggle');
            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    });

    $(document).on('change', 'input[id=start_at]', function() {
        var st = $(this).val();
        var end = $('input[id=end_at]').val();

        if (!end) {
            $('input[id=end_at]').val(st);
        }

        $('input[id=end_at]').removeAttr('min');
        if (st) {
            $('input[id=end_at]').attr('min', st);
        }
    });

    $(document).on('change', 'input[id=end_at]', function() {
        var end = $(this).val();
        var st = $('input[id=start_at]').val();

        if (!st) {
            $('input[id=start_at]').val(end);
        }

        $('input[id=start_at]').removeAttr('max');
        if (end) {
            $('input[id=start_at]').attr('max', end);
        }
    });

});
