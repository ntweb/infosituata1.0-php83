function whatsappMessagesScrollDown () {
    var d = $('#whatsapp-messages-scroll');
    d.scrollTop(d.prop("scrollHeight"));

    // d.animate({
    //     scrollTop: d.prop("scrollHeight")
    // }, 800);
}

function reloadWhatsapp(route) {
    getHtmlNoScroll(route, '#messages', whatsappMessagesScrollDown);
}

$(document).ready(function($) {

    var firstLoad = $('.btnLoadWhatsappMessages');
    if (firstLoad.length) {
        if (firstLoad.length == 1) {
            loadMessages($(firstLoad).attr('data-url'));
        }
    }

    setTimeout(function() {

        if ($('#whatsapp-messages-scroll').length) {
            setInterval(function() {
                console.log('refreshing');
                var route = $('#whatsapp').attr('data-refresh-url');
                getHtmlNoScroll(route, '#messages', whatsappMessagesScrollDown);
            }, 10000);
        }

        if ($('#whatsapp-messages-scroll').length) {
            $('#whatsapp-messages-scroll').on('resize scroll', function()
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
                            var d = $('#whatsapp-messages-scroll');
                            d.scrollTop($('#scrolltome').position().top + $('#scrolltome').height() - d.height());
                        });
                    }
                }
            });
        }
    }, 500);
    //
    //

    $(document).on('click', '.btnLoadWhatsappMessages', function(){
       var route = $(this).attr('data-url');
       loadMessages(route);
    });

    function loadMessages(route) {
        getHtml(route, '#chat-container', function() {

            // setTimeout(function() {
            if ($('#messages').length) {
                $('#messages').html('<div class="col-12"><div class="loader"><div class="ball-beat"><div></div><div></div><div></div></div></div></div>');
                var route = $('#whatsapp').attr('data-refresh-url');
                getHtmlNoScroll(route, '#messages', whatsappMessagesScrollDown);
                $('#whatsappMessageText').focus();
            }
            // }, 1000);
            //
        });
    }

    $(document).on('keyup', '#whatsappMessageText', function (e){
        var code = e.key;
        if(code === "Enter") {
            e.preventDefault();
            var text = $(this).val().trim();
            if (text !== '') {

                $(this).val('');

                var url = $(this).attr('data-route');
                var refreshButton = $(this).attr('data-refresh-button');
                // console.log(text);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        text: text
                    },
                    dataType: 'json',
                    error: function() {
                        toastr.error('Errore in fase di invio', 'Errore');
                    },
                    success: function(data) {
                        // ricarico i messaggi
                        $(refreshButton).trigger('click');
                    }
                });

            }
        }
    });
});


