function messagesScrollDown () {
    var d = $('#topic-messages-scroll');
    d.scrollTop(d.prop("scrollHeight"));

    // d.animate({
    //     scrollTop: d.prop("scrollHeight")
    // }, 800);
}

function reloadTopic(route) {
    getHtmlNoScroll(route, '#messages', messagesScrollDown);
}

$(document).ready(function($) {
    setTimeout(function() {

        if ($('#topic-messages-scroll').length) {
            setInterval(function() {
                console.log('refreshing');
                var route = $('#topic').attr('data-refresh-url');
                getHtmlNoScroll(route, '#messages', messagesScrollDown);
            }, 10000);
        }

        if ($('#topic-messages-scroll').length) {
            $('#topic-messages-scroll').on('resize scroll', function()
            {
                // console.log($(this).scrollTop(), $(this).innerHeight());
                if ($(this).scrollTop() <= 0) {
                    // console.log('load other');
                    if ($('#load-other-messages').length) {
                        var route = $('#load-other-messages').attr('data-url');
                        // console.log(route);
                        var container = $('#load-other-messages').attr('data-prev');
                        $('#load-other-messages').remove();
                        $('#scrolltome').remove();

                        $(container).html('<div class="d-flex justify-content-center align-items-center"><div class="loader"><div class="ball-beat"><div></div><div></div><div></div></div></div></div>');
                        // console.log($(container));
                        getHtmlNoScroll(route, container, function() {
                            var d = $('#topic-messages-scroll');
                            d.scrollTop($('#scrolltome').position().top + $('#scrolltome').height() - d.height());
                        });
                    }
                }
            });
        }
    }, 500);
    //
    //
    if ($('#topic').length) {
        $('#messages').html('<div class="loader"><div class="ball-beat"><div></div><div></div><div></div></div></div>');
        var route = $('#topic').attr('data-refresh-url');
        getHtmlNoScroll(route, '#messages', messagesScrollDown);
    }

});


