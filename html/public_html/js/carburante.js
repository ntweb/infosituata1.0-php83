$(document).ready(function($) {

    $(document).on('click', '#btnExportSchede', function() {
        $('#export').val('1');
        $('#frmFilterSchedePeriod').submit();
    });

});


