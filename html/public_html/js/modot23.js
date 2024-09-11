function initUIModot23 () {
    $('#json_possibili_cause-altro').trigger('change');
    $('#json_incidente_poss_cause-altro').trigger('change');
    $('#json_cause_accertate-altro').trigger('change');
    $('#json_situazione_presentata').trigger('change');
}

$(document).ready(function($) {
    $(document).on('change', '#json_possibili_cause-altro', function() {
        if($(this).is(':checked')) {
           $('#json_possibili_cause_altro').closest('div').show();
        }
        else {
            $('#json_possibili_cause_altro').val('');
            $('#json_possibili_cause_altro').closest('div').hide();
        }
    });

    $(document).on('change', '#json_incidente_poss_cause-altro', function() {
        if($(this).is(':checked')) {
            $('#json_incidente_poss_cause_altro').closest('div').show();
        }
        else {
            $('#json_incidente_poss_cause_altro').val('');
            $('#json_incidente_poss_cause_altro').closest('div').hide();
        }
    });

    $(document).on('change', '#json_cause_accertate-altro', function() {
        if($(this).is(':checked')) {
            $('#json_cause_accertate_altro').closest('div').show();
        }
        else {
            $('#json_cause_accertate_altro').val('');
            $('#json_cause_accertate_altro').closest('div').hide();
        }
    });

    $(document).on('change', '#json_situazione_presentata', function() {
        var v = parseInt($(this).val());
        if (v == 1 || v == 2) {
            $('#criticita').show(0);
        }
        else {
            // uncheck all checkboxes named json_critic_organizzative[]
            $('input[name="json_critic_organizzative[]"]').prop('checked', false);
            $('#criticita').hide(0);
        }
    });


    initUIModot23();
});


