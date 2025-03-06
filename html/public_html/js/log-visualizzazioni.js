
$(document).ready(function($) {

    $(document).on('change', 'input[id=start_at]', function() {
        var st = $(this).val();
        var end = $('input[id=end_at]').val();

        // max end is start +1 week
        var max = new Date(st);
        max.setDate(max.getDate() + 7);
        max = max.toISOString().split('T')[0];
        $('input[id=end_at]').attr('max', max);

        // if (!end) {
            $('input[id=end_at]').val(st);
        // }

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

        // $('input[id=start_at]').removeAttr('max');
        // if (end) {
        //     $('input[id=start_at]').attr('max', end);
        // }
    });

});
