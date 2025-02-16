$(document).ready(function($) {

    $(document).on('click', '.btnIniziaTask', function() {
        if (confirm('Segnare il task come iniziato?')) {
            var route = $(this).data('route');
            $.get(route, function(data) {
                var st = $('#start_at').val();
                if (st) {
                    $('#frmSearchTaskAssegnati').submit();
                }
                else {
                    location.reload();
                }
            }, 'json');
        }
    });

    $(document).on('click', '.btnTerminaTask', function() {
        if (confirm('Segnare il task come terminato?')) {
            var route = $(this).data('route');
            $.get(route, function(data) {
                var st = $('#start_at').val();
                if (st) {
                    $('#frmSearchTaskAssegnati').submit();
                }
                else {
                    location.reload();
                }
            }, 'json');
        }

    });

    $(document).on('click', '.autorizzazioni', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalAutorizzazioni').modal('toggle');
            setTimeout(function() {
                initUI();

                // $(".multiselect-dropdown").select2({
                //     theme: "bootstrap4",
                //     placeholder: "Select an option",
                // });

            }, 1000);
        }, 'html');

    });

    $(document).on('click', '.uploadDocNode', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalUploadDocument').modal('toggle');
            setTimeout(function() {
                initAjaxForms();
                initDropzone();
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

    $(document).on('change', 'input[id=data_inizio_prevista]', function() {
        var st = $(this).val();
        var end = $('input[id=data_fine_prevista]').val();

        if (!end) {
            $('input[id=data_fine_prevista]').val(st);
        }

        $('input[id=data_fine_prevista]').removeAttr('min');
        if (st) {
            $('input[id=data_fine_prevista]').attr('min', st);
        }
    });

    $(document).on('change', 'input[id=data_fine_prevista]', function() {
        var end = $(this).val();
        var st = $('input[id=data_inizio_prevista]').val();

        if (!st) {
            $('input[id=data_inizio_prevista]').val(end);
        }

        $('input[id=data_inizio_prevista]').removeAttr('max');
        if (end) {
            $('input[id=data_inizio_prevista]').attr('max', end);
        }
    });

});


