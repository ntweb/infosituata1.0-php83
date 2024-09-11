$(document).ready(function($) {
    $(document).on('change', 'input[id=tip_scad_mesi]', function() {
        $('input[id=tip_scad_giorni]').val(0);
    });

    $(document).on('change', 'input[id=tip_scad_giorni]', function() {
        $('input[id=tip_scad_mesi]').val(0);
    });
});


