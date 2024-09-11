var maincontainer = '#main-container';

/** ajaxForm **/
var ajaxFormTriggerInputId = '';
function initAjaxForms() {
    $('.ajaxForm').ajaxForm({
        dataType: 'json',
        beforeSend: function() {
            $('.ajaxFormTriggerInput').attr('disabled', 'disabled');
            $('.ajaxFormProgress').attr('aria-valuenow', '0').css('width', '0%');

            frm = $('#frmAjaxForm');

            frm.find('.trig-error').addClass('d-none');
            frm.find('.is-invalid').removeClass('is-invalid');
            frm.find('.error-box').addClass('d-none');
            blockElement(frm);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            $('.ajaxFormProgress').attr('aria-valuenow', percentComplete).css('width', percentComplete+'%');
        },
        error: function(data) {
            frm = $('#frmAjaxForm');
            if (data.responseJSON)  {
                frm.find('.error-box').removeClass('d-none');
                $.each(data.responseJSON.errors, function(key, error){
                    frm.find('.trig-error-'+key).removeClass('d-none');
                    frm.find('.help-block.trig-error-'+key).html('<strong>'+error[0]+'</strong>');
                    frm.find('*[name='+key+']').addClass('is-invalid');
                });

                if (data.responseJSON.message) alert(data.responseJSON.message);
            }
            else {
                if (data.status !== 200) alert(data.statusText);
            }

            $('.ajaxFormTriggerInput').removeAttr('disabled');
        },
        success: function(data) {
            console.log('success', data);

            $('.ajaxFormTriggerInput').removeAttr('disabled');
            frm = $('#frmAjaxForm');
            frm.trigger("reset");

            var btn = frm.find("button[type='submit']").first();
            btn.removeAttr("disabled");

            var percentComplete = 0;
            $('.ajaxFormProgress').attr('aria-valuenow', percentComplete).css('width', percentComplete+'%');

            var callback = frm.attr('data-callback');
            if (typeof callback === 'undefined') callback = null;

            if (callback) {
                if(typeof(callback) === 'string') {
                    eval(callback);
                }
                else callback();
            }
        },
        complete: function() {
            var btn = frm.find("button[type='submit']").first();
            btn.removeAttr("disabled");
        }
    });
    /** Fine ajaxForm **/
}

$(function() {

    $(document).ready(function() {
        $(window).keydown(function(event){
            var currentIdElement = event.target.id;
            // console.log(event);
            if (event.keyCode == 13 &&
                (currentIdElement.includes('tags') || event.target.tagName.toLowerCase() === 'textarea'))
            {
                return true;
            }

            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

    $(document).on('click', '.btnUploadAjax', function() {
        initAjaxForms();
        var frm = $(this).closest('form.ajaxForm');
        frm.submit();
    });

    $(document).on('submit', 'form:not(.ns,.ns-payload)', function(e){
        var btn = $(this).find("button");
        btn.prop('disabled', true);
        setTimeout(function() {
            btn.removeAttr('disabled');
        }, 2500);
    });

    $(document).on('submit', 'form.ns', function(e){
        e.preventDefault();

        var frm = $(this);
        var btn = frm.find("*[type='submit']").first();
        var route = frm.attr('action');

        var method = frm.attr('method') ? frm.attr('method') : 'GET';
        var _method = frm.find("*[type='d-none'][name='_method']").first();
        if (_method.length) method = _method.val();

        //console.log(route);
        //console.log(method);

        var check = frm.find("*[type='checkbox'][name='confirm']").first();
        if (check.length)
            if (!check.is(':checked')){
                toastr.warning('Confermare l\'operazione', 'Attenzione');
                return false;
            }

        var dataserialized = frm.serialize();
        var callback = frm.attr('data-callback');
        if (typeof callback === 'undefined') callback = null;

        var btnOldValue = btn.html();
        btn.attr('disabled', 'disabled');
        btn.html('Attendere...');

        var dataType = 'json';

        $.ajax({
            method: method,
            url: route,
            data: dataserialized,
            cache: false,
            //processData: processData,
            //contentType: contentType,
            dataType: dataType,
            timeout: 60000, // timeout ad 1 minuto
            error: function(jqXHR, textStatus, errorThrown){
                // Validation errors
                if (jqXHR.responseJSON) {
                    console.log(jqXHR);
                    console.log('errors', jqXHR.responseJSON.errors);

                    if (jqXHR.responseJSON.errors || jqXHR.responseJSON.message) {
                        toastr.error('Errore in fase di salvataggio', 'Errore');
                    }

                    if (jqXHR.responseJSON.payload) {
                        toastr.error(jqXHR.responseJSON.payload, 'Errore');
                    }

                    frm.find('.error-box').removeClass('d-none');
                    $.each(jqXHR.responseJSON.errors, function(key, error){
                        frm.find('.trig-error-'+key).removeClass('d-none');
                        frm.find('.help-block.trig-error-'+key).html('<strong>'+error[0]+'</strong>');
                        frm.find('*[name='+key+']').addClass('is-invalid');
                        //console.log(key);
                        //console.log(error[0]);
                    });
                }

            },
            beforeSend: function() {
                frm.find('.trig-error').addClass('d-none');
                frm.find('.is-invalid').removeClass('is-invalid');
                frm.find('.error-box').addClass('d-none');

                blockElement(frm);
            },
            success: function(data){
                if (data.res === 'success') toastr.success(data.payload, 'Success');
                else toastr.error(data.payload, 'Errore');

                if (data.res === 'error') {
                    frm.find('.error-box').removeClass('d-none');
                }

                if (callback && data.res !== 'error') {
                    if(typeof(callback) === 'string') {
                        eval(callback);
                    }
                    else callback();
                }

                if (data._redirect) {
                    $(window.location).attr('href', data._redirect);
                }


            },
            complete: function() {
                console.log('btn', btn);

                btn.html(btnOldValue);
                btn.removeAttr('disabled');
                btn.prop("disabled", false);

                unblockElement(frm);
            }
        });

    });

    $(document).on('submit', 'form.ns-payload', function(e){

        e.preventDefault();

        var frm = $(this);
        var btn = frm.find("*[type='submit']").first();
        var route = frm.attr('action');

        var method = frm.attr('method') ? frm.attr('method') : 'GET';
        var _method = frm.find("*[type='d-none'][name='_method']").first();
        if (_method.length)
            method = _method.val();

        //console.log(route);
        //console.log(method);

        var check = frm.find("*[type='checkbox'][name='confirm']").first();
        if (check.length)
            if (!check.is(':checked')){
                toastr.warning('Confermare l\'operazione', 'Attenzione');
                return false;
            }

        var dataserialized = frm.serialize();
        var callback = frm.attr('data-callback');
        if (typeof callback === 'undefined') {
            alert('Error. Define a callback');
            return;
        }

        var btnOldValue = btn.html();
        btn.attr('disabled', 'disabled');
        btn.html('Attendere...');

        var dataType = 'json';

        $.ajax({
            method: method,
            url: route,
            data: dataserialized,
            cache: false,
            //processData: processData,
            //contentType: contentType,
            dataType: dataType,
            timeout: 60000, // timeout ad 1 minuto
            error: function(jqXHR, textStatus, errorThrown){

                console.log(jqXHR);

                // Validation errors
                if (jqXHR.responseJSON) {
                    console.log('errors', jqXHR.responseJSON.errors);
                    if (jqXHR.responseJSON.errors) {
                        toastr.error('Errore in fase di salvataggio');
                        if (jqXHR.responseJSON.errors) {
                            frm.find('.error-box').removeClass('d-none');
                            $.each(jqXHR.responseJSON.errors, function(key, error){
                                frm.find('.trig-error-'+key).removeClass('d-none');
                                frm.find('.help-block.trig-error-'+key).html('<strong>'+error[0]+'</strong>');
                                frm.find('*[name='+key+']').addClass('is-invalid');
                                //console.log(key);
                                //console.log(error[0]);
                            });
                        }
                    }
                    else if (jqXHR.responseJSON.payload) {
                        toastr.error(jqXHR.responseJSON.payload, 'Errore');
                    }
                }
            },
            beforeSend: function() {
                frm.find('.trig-error').addClass('d-none');
                frm.find('.is-invalid').removeClass('is-invalid');
                frm.find('.error-box').addClass('d-none');
            },
            success: function(data){
                eval(callback+'(data)');
            },
            complete: function() {
                btn.html(btnOldValue);
                btn.removeAttr('disabled');
            }
        });

    });

    $(document).on('submit', 'form.ns-html', function(e){
        e.preventDefault();

        var frm = $(this);
        var btn = frm.find("*[type='submit']").first();
        var route = frm.attr('action');

        var method = frm.attr('method') ? frm.attr('method') : 'GET';
        var _method = frm.find("*[type='d-none'][name='_method']").first();
        if (_method.length) method = _method.val();

        var dataserialized = frm.serialize();
        var callback = frm.attr('data-callback');
        if (typeof callback === 'undefined') callback = null;

        var container = $(this).data('container');

        var btnOldValue = btn.html();
        btn.attr('disabled', 'disabled');
        btn.html('Attendere...');

        $(container).html('<div>Attendere...</div>');

        var dataType = 'html';

        $.ajax({
            method: method,
            url: route,
            data: dataserialized,
            cache: false,
            //processData: processData,
            //contentType: contentType,
            dataType: dataType,
            timeout: 60000, // timeout ad 1 minuto
            success: function(data){
                $(container).html(data);

                if (typeof callback !== 'undefined') {
                    if(typeof(callback) === 'string') {
                        eval(callback);
                    }
                    else callback();
                }
            },
            complete: function() {
                btn.html(btnOldValue);
                btn.removeAttr('disabled');
                btn.prop("disabled", false);
            }
        });

    });

    $(document).on('click', '.get-html', function() {
        var route = $(this).data('route');
        var container = $(this).data('container');
        var callback = $(this).data('callback');

        getHtml(route, container, callback);
    });

    $(document).on('click', '.btnLogout', function(){
        $('#frmLogout').submit();
    });

    $(document).on('change', '.selDetailScadenze', function(){
        var option = $(this).find(":selected").first();
        var m = option.data("m");
        var g = option.data("g");
        var id = option.data("id");

        $('.selDetailScadenzeDescription').hide(0);
        $('.selDetailScadenzeDescription-'+id).fadeIn(100);

        var frm = $(this).closest('form');

        var begin = frm.find("*[name=start_at]").first();
        var end = frm.find("*[name=end_at]").first();

        if (parseInt(m) > 0) {
            var d = moment(begin.val(), 'DD/MM/YYYY').add(parseInt(m), 'months');
            end.datepicker('setDate', d.format());
        }

        if (parseInt(g) > 0) {
            var d = moment(begin.val(), 'DD/MM/YYYY').add(parseInt(g), 'days');
            end.datepicker('setDate', d.format());
        }
    });

    $(document).on('change', '.detailScadenzeStartAt', function(){
        var frm = $(this).closest('form');
        var select = frm.find('.selDetailScadenze').first();
        select.trigger('change');
    });

    $(document).on('click', '.btnCreateNewScadenza', function(){
        var route = $(this).data('route');
        $('#frmScadenzaCreate').trigger('reset');
        $('#frmScadenzaCreate').attr('action', route);
        $('#modalScadenzaCreate').modal('toggle');
    });

    $(document).on('click', '.btnMessageSend', function(){
        $('#_module').val('send');
    });

    $(document).on('click', '.btnDelete', function(){
        var route = $(this).data('route');
        var message = $(this).data('message');
        var callback = $(this).data('callback');

        var modal = '#destroyModal';
        var frm = $('#frmDestroyModal');

        $('#destroy-message').html(null);
        if (message) {
            $('#destroy-message').html(message);
        }

        frm.attr('data-callback', 'closeAllModal(null);');
        if (callback) {
            frm.attr('data-callback', frm.data('callback')+callback);
        }

        frm.attr('action', route);
        $(modal).modal('toggle');
    });

    $(document).on('click', '.btnDeleteAttachment', function(){
        var route = $(this).data('route');
        $('#frmDeleteAttachment').trigger('reset');
        $('#frmDeleteAttachment').attr('action', route);
        $('#modalDeleteAttachment').css('z-index', 5000);
        $('#modalDeleteAttachment').modal('toggle');
    });

    $(document).on('click', '.btnOpenModulo', function(){
        var modulo = $(this).data('v');
        $('#scadenzario-moduli').hide(0);
        $(modulo).fadeIn(300);
    });

    $(document).on('click', '.openDrawer', function() {
        openDrawer();
    });

    $(document).on('click', '.btnCloseScadenzarioModulo', function(){
        var modulo = $(this).data('v');
        $('.scadenzario-modulo-details').hide(0);
        $('#scadenzario-moduli').fadeIn(300);
    });

    $(document).on('click', '.btnCheckScadenza', function(){
        $('#_new').val($(this).data('new'));
        $('#frmScadenzaCheckControllata').submit();
    });

    $(document).on('click', '.openUrl', function(){
        var route = $(this).data('route');
        window.open(route, '_self');
    });

    $(document).on('change', '.radioFilterScadenze', function(){
        var v = $(this).val();
        $('.filter').show(0);
        if (v != '') {
            $('.filter').fadeOut(0);
            $(v).fadeIn(300);
        }
    });

    $(document).on('click', '.btnOpenHumanActivityDetail', function(){
        var route = $(this).data('route');
        var update = $(this).data('update');

        $('#btnSaveHumanActivityCheck').show(0);
        if ($(this).data('save-btn') === 'hide') {
            $('#btnSaveHumanActivityCheck').hide(0);
            update = '#';
        }

        if($(this).data('latitude') !== '' && $(this).data('longitude') !== '') {
            setMarker($(this).data('latitude'), $(this).data('longitude'));
        } else {
            loadDefaultMap();
        }

        getHtml(route, '#activityDetail', function(){
            $('#frmHumanActivityDetail').trigger('reset');
            $('#frmHumanActivityDetail').attr('action', update);
            $('#modalHumanActivityDetail').modal('toggle');
            setTimeout(function() {
                mMap.invalidateSize();
            }, 1000);

        });

    });

    $(document).on('click', '.btnOpenDeviceMap', function(){
        if($(this).data('latitude') !== '' && $(this).data('longitude') !== '') {
            setMarker($(this).data('latitude'), $(this).data('longitude'));
        }

        $('#modalDeviceMap').modal('toggle');
    });

    $(document).on('keyup', '.search-input', function(e){
        if(e.keyCode === 13 && $(this).val().trim().length >= 3) {
            var route = $(this).data('route')+$(this).val().trim();
            window.open(route, '_self');
        }
    });

    $(document).on('click', '.search-icon', function(){
        $(this).prev().focus();
    });

    $(document).on('click', '.btnSubmitFormById', function() {
        var selector = $(this).attr('data-form-selector');
        $(selector).submit();
    })

});

initAjaxForms();

function getHtml(route, container, callback) {
    if(typeof container == "undefined")
        container = maincontainer;

    $.get(route, function(data){
        $(container).html(data);
        initUI();

        $('html, body').animate({
            scrollTop: $(container).offset().top
        }, 800);

        if (typeof callback !== 'undefined') {
            if(typeof(callback) === 'string') {
                eval(callback);
            }
            else callback();
        }
    }, 'html');
}

function getHtmlNoScroll(route, container, callback) {
    // alert('getHtmlNoScroll ' + callback);
    if(typeof container == "undefined")
        container = maincontainer;

    $.get(route, function(data){
        $(container).html(data);
        initUI();

        // alert(callback);
        if (typeof callback !== 'undefined') {
            if(typeof(callback) === 'string') {
                eval(callback);
            }
            else callback();
        }
    }, 'html');
}

function blockElement(el) {
    el.children().block({
        message: $('<div class="loader mx-auto">\n' +
            '                            <div class="ball-clip-rotate">\n' +
            '                                <div class="border-white"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });
}

function unblockElement(el) {
    el.children().unblock();
}

function refreshTipologiaScadenze(tip) {
    var id = tip.payload.id;
    var label = tip.payload.label;
    var m = tip.payload.mesi;
    var infosituata_moduli_details_scadenze_id = '#infosituata_moduli_details_scadenze_id_'+tip.payload.infosituata_moduli_details_id;

    $(infosituata_moduli_details_scadenze_id).append(`<option value="${id}" data-m="${m}">${label}</option>`);
    $(infosituata_moduli_details_scadenze_id +' option[value='+ id +']').attr('selected', 'selected');

    $(infosituata_moduli_details_scadenze_id).trigger('change');
    $('#modalScadenzaCreate').modal('toggle');
}

function refreshAttachmentTable(response) {
    var id = '#attachment-'+response.payload;
    $(id).fadeOut(200);

    $('#modalDeleteAttachment').modal('toggle');
}

function updateHumanActivityDetail(response) {
    var btn = $('#btnOpenHumanActivityDetail-' +response.payload);
    btn.next().remove();
    btn.fadeIn(200);

    $('#modalHumanActivityDetail').modal('toggle');
}

function openDrawer() {
    $('.app-drawer-wrapper').addClass('drawer-open');
    $('.app-drawer-overlay').removeClass('d-none');
}

function closeDrawer() {
    $('.app-drawer-wrapper').removeClass('drawer-open');
    $('.app-drawer-overlay').addClass('d-none');
}

function closeAllModal(data) {
    $('.modal').modal('hide');
}

function deleteElement(id) {
    $(id).fadeOut(300);
}

function changeAttachmentVisibility(element, url) {
    $.ajax({
        url,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            is_public: element.is(':checked') ? '1' : '0'
        },
        dataType: 'json',
        error: function() {
            toastr.error('Errore in fase di salvataggio', 'Errore');
        },
        success: function(data) {
            toastr.success('Visibilità modificata', 'Success');
        }
    });
}
