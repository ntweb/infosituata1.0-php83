$(document).ready(function($) {
    $(document).on('change', '#squadra_items_id', function() {
        var select = $(this);
        var id = select.val();
        var squadra_id = $('#squadra_id').val();

        if ($('#squadra-item-id-' + id).length) {
            toastr.warning('Risorsa già presente in squadra', 'Attenzione');
            return;
        }

        var data = new FormData();
        data.append("_token", $('meta[name="csrf-token"]').attr('content'));
        data.append("squadra_id", squadra_id);
        data.append("id", id);

        var route = url + '/squadra-item';

        $.ajax ({
            data: data,
            type: "POST",
            url: route,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                if (data.res === 'success') {
                    toastr.success(data.payload, 'Success');
                    select.val(null);
                    getHtmlNoScroll(route + '?squadra_id= '+squadra_id, '#squadra-items');
                }
                else toastr.error(data.payload, 'Errore');
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on('click', '.btnSquadraItemDelete', function() {
        var route = $(this).attr('data-route');
        var frm = $('#frmDeleteSquadraItem');

        frm.attr('action', route);
        frm.submit();
    });
});


