function refreshTree() {
    if ($('#node-tree').length) {
        var route = $('#node-tree').data('route');
        getHtml(route, '#node-tree');
    }
}

$(document).ready(function($) {

    $(document).on('click', '.addNode, .modNode', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalCreateNode').modal('toggle');
            setTimeout(function() {
                $('#modalCreateNode').find('input[type=text]').first().focus();
                initUI();
            }, 500);
        }, 'html');
        // $('#modalNodeCreate').modal('toggle');
    });

    $(document).on('click', '.deleteNode', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalDeleteNode').modal('toggle');
        }, 'html');
        // $('#modalNodeCreate').modal('toggle');
    });

    $(document).on('click', '.nodeMove', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            refreshTree();
            initUI();
        }, 'json');
    });

});


