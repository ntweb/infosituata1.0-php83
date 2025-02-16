$(document).ready(function($) {

    if ($('#dashboard_user_index').length) {
        $('#dashboard_user_index').DataTable({
            paging: false
        });
    }

    if ($('#dashboard_mezzi_index').length) {
        $('#dashboard_mezzi_index').DataTable({
            paging: false
        });
    }

    if ($('#dashboard_attrezzature_index').length) {
        $('#dashboard_attrezzature_index').DataTable({
            paging: false
        });
    }

    if ($('#dashboard_materiali_index').length) {
        $('#dashboard_materiali_index').DataTable({
            paging: false
        });
    }

    if ($('#dashboard_risorse_index').length) {
        $('#dashboard_risorse_index').DataTable({
            paging: false
        });
    }

    if ($('#dashboard_tip_scadenzario').length) {
        $('#dashboard_tip_scadenzario').DataTable({
            paging: false
        });
    }

    if ($('#dashboard_tip_scadenza_index').length) {
        $('#dashboard_tip_scadenza_index').DataTable({
            paging: false
        });
    }

    if ($('#dashboard_timbrature_index').length) {
        $('#dashboard_timbrature_index').DataTable({
            paging: false
        });
    }
});
