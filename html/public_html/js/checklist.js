function openModalGeneratedChecklist() {
    $('#modalChecklist').modal('toggle');
    setTimeout(function() {
        initUI();
    }, 1000);
}

function reopenLastSavedChecklist(route) {
    closeAllModal();
    setTimeout(function(){
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalChecklist').modal('toggle');
            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    }, 1000);

}

$(document).ready(function($) {

    $(document).on('change', '#reference_controller_selector', function() {
        var v = $(this).val();
        $('#items_id').attr('data-controller', v);
        $('#items_id').val(null);
        initUI();

        if (v == 'checklist-generica') {
            $('#items_id').parent().hide(0);
        }
        else {
            $('#items_id').parent().show(0);
        }
    });

    $(document).on('click', '#btnSelectChecklistTarget', function() {
       var reference = $('#reference_controller_selector').val();
       if (!reference) {
           toastr.error('Selezionare un target');
           return;
       }

       if (reference !== 'checklist-generica') {
           var items_id = $('#items_id').val();
           if (!items_id) {
               toastr.error('Selezionare un elemento a cui associare la checklist');
               return;
           }
       }

       var frm = $(this).closest('form');
       frm.submit();

    });

    $(document).on('click', '#btnSelectChecklistTempleteRender', function() {
        var frm = $(this).closest('form');
        var placeholder = frm.attr('data-route-placeholder');
        var checklistTemplateId = $('#checklists_templates_id').val();

        var route = placeholder.replace('#', checklistTemplateId);
        frm.attr('action', route);
        frm.submit();
    });

    $(document).on('click', '.showChecklistGenerica', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalChecklist').modal('toggle');
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


