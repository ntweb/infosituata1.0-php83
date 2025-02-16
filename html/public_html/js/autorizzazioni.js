$(document).ready(function($) {

    $(document).on('click', '.rapportini-autorizzazioni', function() {
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


    $(document).on('click', '.checklist-autorizzazioni', function() {
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

});
