function renderMultiSelectDropdown() {
    $('.multiselect-dropdown').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            var _dropdownParent = $(el).attr('data-dropdownParent');
            console.log('_dropdownParent', _dropdownParent);
            $(el).select2(
                {
                    theme: "bootstrap4",
                    placeholder: "Select an option",
                    dropdownParent: _dropdownParent ? $(_dropdownParent) : null,
                }
            );
        }
    });
}

function initUI() {

    $('[data-toggle="tooltip"]').tooltip();

    $('.char_counter').each(function(i, el) {
        var text = $($(el).attr('data-selector')).val();
        $(el).html(text.length);
    });

    tinymce.init({
        selector: '.tinymce',
        language: 'it',
        relative_urls: false,
        remove_script_host: false,
        menubar: 'edit insert format',
        plugins: ['lists', 'image', 'link', 'media', 'table', 'code'],
        toolbar: 'undo redo | styles | bold italic | link image media | table tabledelete | numlist bullist | code',
        toolbar_mode: 'floating',
    });

    // Summernote WYSIWYG
    // $('.summernote').summernote({
    //     minHeight: 450,
    //     dialogsInBody: true,
    //     blockquoteBreakingLevel: 1,
    //     toolbar: [
    //         ['style', ['style']],
    //         ['font', ['bold', 'underline', 'clear']],
    //         ['color', ['color']],
    //         ['para', ['ul', 'ol', 'paragraph']],
    //         ['table', ['table']],
    //         ['insert', ['link', 'picture', 'video']],
    //         ['view', ['codeview', 'help']]
    //     ],
    //     callbacks:{
    //         onImageUpload: function(image) {
    //             uploadImage(image[0]);
    //         },
    //         onImageUploadError: function(msg){
    //             alert('Errore in fase di upload immagine');
    //         }
    //     }
    // });

    if ($('.editor').length) {
        var container = $('.editor').get(0);
        var options = {
            debug: 'info',
            modules: {
                toolbar: '#toolbar'
            },
            placeholder: 'Compose an epic...',
            readOnly: true,
            theme: 'snow'
        };
        var editor = new Quill(container, options);
    }

    //Initialize Select2 Elements
    $('.select2Classic').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            $(el).select2();
        }
    });

    $('.select2-ricambi-ajax').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            $(el).select2({
                ajax: {
                    url: url+'/ricambi',
                    dataType: 'json'
                }
            });
        }
    });

    $('.select2-tasks-ajax').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            var _dropdownParent = $(el).attr('data-dropdownParent');
            $(el).select2({
                ajax: {
                    url: url+'/dashboard/task/select2',
                    dataType: 'json'
                },
                dropdownParent: _dropdownParent ? $(_dropdownParent) : null,
            });
        }
    });

    $('.select2-commesse-ajax').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            var _dropdownParent = $(el).attr('data-dropdownParent');
            $(el).select2({
                ajax: {
                    url: url+'/dashboard/commessa/select2',
                    dataType: 'json'
                },
                dropdownParent: _dropdownParent ? $(_dropdownParent) : null,
            });
        }
    });

    $('.select2-commesse-template-ajax').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            $(el).select2({
                ajax: {
                    url: url+'/dashboard/commessa-tpl/select2',
                    dataType: 'json'
                }
            });
        }
    });

    $('.select2-tasks-template-ajax').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            $(el).select2({
                ajax: {
                    url: url+'/dashboard/task-tpl/select2',
                    dataType: 'json'
                }
            });
        }
    });

    $('.select2-item-ajax').each(function (i, el) {
        // console.log('.select2-item-ajax', el);
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            var _controller = $(el).attr('data-controller');
            var _dropdownParent = $(el).attr('data-dropdownParent');
            // console.log('_controller', _controller, '_dropdownParent', _dropdownParent);
            $(el).select2({
                ajax: {
                    url: url+'/dashboard/item/select2?_controller='+_controller,
                    dataType: 'json',
                },
                dropdownParent: _dropdownParent ? $(_dropdownParent) : null,
            });
        }
    });

    $('.select2-clienti-ajax').each(function (i, el) {
        // console.log('.select2-item-ajax', el);
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            var _controller = $(el).attr('data-controller');
            var _dropdownParent = $(el).attr('data-dropdownParent');
            $(el).select2({
                ajax: {
                    url: url+'/dashboard/cliente/select2?_controller='+_controller,
                    dataType: 'json',
                },
                dropdownParent: _dropdownParent ? $(_dropdownParent) : null,
            });
        }
    });

    $('.select2-fasi-ajax').each(function (i, el) {
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            $(el).select2({
                ajax: {
                    url: url+'/dashboard/commessa-utils/fasi/select2',
                    dataType: 'json'
                }
            });
        }
    });

    $('.select2-fasi-commessa-ajax').each(function (i, el) {
        var route = $(el).attr('data-route');
        try {
            $(el).select2('destroy');
        }
        catch (e) {

        }
        finally {
            $(el).select2({
                ajax: {
                    url: route,
                    dataType: 'json'
                }
            });
        }
    });

    renderMultiSelectDropdown();

    //InputMask
    $('[data-mask]').inputmask();

    //DateTime Picker
    $('.datePicker').datepicker({
        autoclose: true,
        language: 'it',
        orientation: 'bottom'
    });

    $('.dateTimePickerRange').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        locale: {
            format: 'DD/MM/YYYY HH:mm',
            applyLabel: "Applica",
            cancelLabel: "Annulla",
            fromLabel: "Da",
            toLabel: "A",
            customRangeLabel: "Custom",
            daysOfWeek: [
                "Do",
                "Lu",
                "Ma",
                "Me",
                "Gi",
                "Ve",
                "Sa"
            ],
            monthNames: [
                "Gennaio",
                "Febbraio",
                "Marzo",
                "Aprile",
                "Maggio",
                "Giugno",
                "Luglio",
                "Agosto",
                "Settembre",
                "Ottobre",
                "Novembre",
                "Dicembre"
            ]
        }
    });

    $('.dateTimePickerSingle').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        locale: {
            format: 'DD/MM/YYYY HH:mm',
            applyLabel: "Applica",
            cancelLabel: "Annulla",
            fromLabel: "Da",
            toLabel: "A",
            customRangeLabel: "Custom",
            daysOfWeek: [
                "Do",
                "Lu",
                "Ma",
                "Me",
                "Gi",
                "Ve",
                "Sa"
            ],
            monthNames: [
                "Gennaio",
                "Febbraio",
                "Marzo",
                "Aprile",
                "Maggio",
                "Giugno",
                "Luglio",
                "Agosto",
                "Settembre",
                "Ottobre",
                "Novembre",
                "Dicembre"
            ]
        }
    });

    $('.datePickerRange').daterangepicker({
        autoUpdateInput: false,
        timePicker: false,
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: "Applica",
            cancelLabel: "Annulla",
            fromLabel: "Da",
            toLabel: "A",
            customRangeLabel: "Custom",
            daysOfWeek: [
                "Do",
                "Lu",
                "Ma",
                "Me",
                "Gi",
                "Ve",
                "Sa"
            ],
            monthNames: [
                "Gennaio",
                "Febbraio",
                "Marzo",
                "Aprile",
                "Maggio",
                "Giugno",
                "Luglio",
                "Agosto",
                "Settembre",
                "Ottobre",
                "Novembre",
                "Dicembre"
            ]
        },
        ranges: {
            'Ultimi 7 giorni': [moment().subtract(6, 'days'), moment()],
            'Ultimi 30 giorni': [moment().subtract(29, 'days'), moment()],
            'Questo mese': [moment().startOf('month'), moment().endOf('month')],
            'Mese scorso': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('.datePickerRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('body').on('focus',".datepicker", function(){
        $(this).datepicker({
            autoclose: true,
            language: 'it',
            orientation: 'bottom'
        });
    });

    //Tags input
    $('.tags').tagsInput({
        defaultText: '',
        width: '100%',
        confirmKeys: [13, 44],
        allowDuplicates: false,
    });

    $('[data-toggle="popover-custom-bg"]').each(function (i, obj) {
        var popClass = $(this).attr('data-bg-class');
        $(this).popover({
            trigger: 'focus',
            placement: 'top',
            template: '<div class="popover popover-bg ' + popClass + '" role="tooltip"><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        });
    });

    // multi select
    $('.multiselect-radio').multiselect({
        inheritClass: true
    });

    // text autocomplete
    $('.text-autocomplete').each(function (i, el) {
        if (!$(el).hasClass('text-autocomplete-initialized')) {
            $(el).addClass('text-autocomplete-initialized');
            var onSelectItem = $(el).attr('data-on-selected-item');
            $.get($(el).attr('data-route'), function(data) {
                // console.log(data)
                $(el).autocomplete({
                    source: data,
                    onSelectItem: onSelectItem,
                    highlightClass: 'text-success',
                    treshold: 2,
                });
            }, 'json');


        }
    });

    // google autocomplete
    var input = document.getElementById("google-autocomplete")
    if (input) {
        googleAutocomplete = new google.maps.places.Autocomplete(input, {
            fields: ["place_id", "geometry", "formatted_address", "name"],
        });
    }

    // $(document).on('click', '#dropzone-container', function() {
    //     $(this).parent().trigger('click');
    // });

    initDropzone();
}

$(document).ready(function($) {

    $(document).on('click', '.copy-url', function(){
        var url = $(this).attr('data-url');
        navigator.clipboard.writeText(url);

        toastr.info('Link copiato');
    });

    $(document).on('keyup', '.enable-counter', function() {
        var text = $(this).val();
        var selector = '#' + $(this).attr('id') + '_counter';
        $(selector).html(text.length);
    });

    var img = $('.infosituata-embed').find('img');
    if (img.length) {
        img.each(function(i, el){
            $(el).addClass('img-fluid').removeAttr('height').css("height", "");
        });
    }

	var table = $('.infosituata-embed').find('table');
    if (table.length) {
        table.each(function(i, el){
            var tableHtml = $(el).wrap('<div>').parent().html();
			console.log(tableHtml);
			$(el).replaceWith( '<div class="table-responsive">'+tableHtml+'</h2>');
        });
    }

    $(".infosituata-embed").fitVids();

    $(document).on('keyup', '.text-autocomplete-form-group button', function(e){
        var code = e.key;
        if(code==="Enter") {
            $(this).trigger('click');
        }
    });

    $(document).on('change', '.changeAttachmentVisibility', function() {
        var url = $(this).attr('data-route');

        $.ajax({
            url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            error: function() {
                toastr.error('Errore in fase di salvataggio', 'Errore');
            },
            success: function(data) {
                toastr.success('Visibilità modificata', 'Success');
            }
        });
    });

    $(document).on('change', '.changeAttachmentEmbedding', function() {
        var url = $(this).attr('data-route');

        $.ajax({
            url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            error: function() {
                toastr.error('Errore in fase di salvataggio', 'Errore');
            },
            success: function(data) {
                toastr.success('Visibilità modificata', 'Success');
            }
        });
    });

    $(document).on('click', '.pointer-click', function() {
        var route = $(this).data('route');
        window.location = route;
    });

    initUI();
});

function initDropzone() {
    if ($('#my-dropzone').length) {
        if ($('#my-dropzone').hasClass('dz-started'))
            return;

        if ($('#my-dropzone').hasClass('dz-clickable'))
            return;

        var dz = $('#my-dropzone');
        var route = dz.attr('data-route');
        var dropzone_callback = dz.attr('data-callback');
        if (typeof dropzone_callback === 'undefined') dropzone_callback = null;

        new Dropzone("#my-dropzone", {
            url: route,
            // autoProcessQueue: false,
            maxFilesize: 20000000,
            // uploadMultiple: true,
            chunking: true,
            disablePreviews: true,
            createImageThumbnails: false,
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    // console.log(file);
                    // console.log(xhr);
                    // console.log(formData);
                    // console.log('----------------');
                    // console.log($('meta[name="csrf-token"]').attr('content'));
                    // console.log('----------------');
                    formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                    $('.dropzone-append').each(function(i, el) {
                        formData.append($(el).attr('name'), $(el).val());
                    });

                    // console.log('formData', formData);
                });
                this.on('addedfile', function(file) {
                    $('#dropzone-container').hide(0);

                    var name = md5(file.name);
                    var progress = $('#dropzone-progress-clone').clone().attr('id', name).addClass('dropzone-progress-cloned').css('display', 'block');
                    // console.log(progress);
                    // console.log(progress.find('.dropzone-filename-placeholder'));
                    progress.find('.dropzone-filename-placeholder').text(file.name);
                    progress.appendTo(dz);

                }),
                    this.on('uploadprogress', function(file, progress, bytesSent) {
                        // console.log('uploadprogress', file, progress);
                        var name = md5(file.name);
                        $('#'+name).find('.progress-bar').css('width', progress+'%');
                    }),
                    this.on('totaluploadprogress', function(progress) {
                        console.log('totaluploadprogress', progress);
                    }),
                    this.on('success', function() {
                        // console.log('success');
                        toastr.success('File caricati correttamente', 'Success');
                    });
                this.on('error', function(){
                    console.log('error');
                    toastr.error('Errore durante upload multiplo', 'Errore');
                });
                this.on('queuecomplete', function() {
                    $('.dropzone-progress-cloned').remove();
                    $('#dropzone-container').show(0);

                    // console.log('queuecomplete', dropzone_callback);
                    if (typeof dropzone_callback !== 'undefined') {
                        if(typeof(dropzone_callback) === 'string') {
                            eval(dropzone_callback);
                        }
                        else dropzone_callback();
                    }
                });
            }
        });

    }
}

function uploadImage(image) {
    var data = new FormData();
    data.append("image", image);
    data.append("_token", $('meta[name="csrf-token"]').attr('content'));

    $.ajax ({
        data: data,
        type: "POST",
        url: _url+"/image/resize",
        cache: false,
        contentType: false,
        processData: false,
        success: function(imageUrl) {
            $('.summernote').summernote('insertImage', imageUrl);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function md5(inputString) {
    var hc="0123456789abcdef";
    function rh(n) {var j,s="";for(j=0;j<=3;j++) s+=hc.charAt((n>>(j*8+4))&0x0F)+hc.charAt((n>>(j*8))&0x0F);return s;}
    function ad(x,y) {var l=(x&0xFFFF)+(y&0xFFFF);var m=(x>>16)+(y>>16)+(l>>16);return (m<<16)|(l&0xFFFF);}
    function rl(n,c)            {return (n<<c)|(n>>>(32-c));}
    function cm(q,a,b,x,s,t)    {return ad(rl(ad(ad(a,q),ad(x,t)),s),b);}
    function ff(a,b,c,d,x,s,t)  {return cm((b&c)|((~b)&d),a,b,x,s,t);}
    function gg(a,b,c,d,x,s,t)  {return cm((b&d)|(c&(~d)),a,b,x,s,t);}
    function hh(a,b,c,d,x,s,t)  {return cm(b^c^d,a,b,x,s,t);}
    function ii(a,b,c,d,x,s,t)  {return cm(c^(b|(~d)),a,b,x,s,t);}
    function sb(x) {
        var i;var nblk=((x.length+8)>>6)+1;var blks=new Array(nblk*16);for(i=0;i<nblk*16;i++) blks[i]=0;
        for(i=0;i<x.length;i++) blks[i>>2]|=x.charCodeAt(i)<<((i%4)*8);
        blks[i>>2]|=0x80<<((i%4)*8);blks[nblk*16-2]=x.length*8;return blks;
    }
    var i,x=sb(inputString),a=1732584193,b=-271733879,c=-1732584194,d=271733878,olda,oldb,oldc,oldd;
    for(i=0;i<x.length;i+=16) {olda=a;oldb=b;oldc=c;oldd=d;
        a=ff(a,b,c,d,x[i+ 0], 7, -680876936);d=ff(d,a,b,c,x[i+ 1],12, -389564586);c=ff(c,d,a,b,x[i+ 2],17,  606105819);
        b=ff(b,c,d,a,x[i+ 3],22,-1044525330);a=ff(a,b,c,d,x[i+ 4], 7, -176418897);d=ff(d,a,b,c,x[i+ 5],12, 1200080426);
        c=ff(c,d,a,b,x[i+ 6],17,-1473231341);b=ff(b,c,d,a,x[i+ 7],22,  -45705983);a=ff(a,b,c,d,x[i+ 8], 7, 1770035416);
        d=ff(d,a,b,c,x[i+ 9],12,-1958414417);c=ff(c,d,a,b,x[i+10],17,     -42063);b=ff(b,c,d,a,x[i+11],22,-1990404162);
        a=ff(a,b,c,d,x[i+12], 7, 1804603682);d=ff(d,a,b,c,x[i+13],12,  -40341101);c=ff(c,d,a,b,x[i+14],17,-1502002290);
        b=ff(b,c,d,a,x[i+15],22, 1236535329);a=gg(a,b,c,d,x[i+ 1], 5, -165796510);d=gg(d,a,b,c,x[i+ 6], 9,-1069501632);
        c=gg(c,d,a,b,x[i+11],14,  643717713);b=gg(b,c,d,a,x[i+ 0],20, -373897302);a=gg(a,b,c,d,x[i+ 5], 5, -701558691);
        d=gg(d,a,b,c,x[i+10], 9,   38016083);c=gg(c,d,a,b,x[i+15],14, -660478335);b=gg(b,c,d,a,x[i+ 4],20, -405537848);
        a=gg(a,b,c,d,x[i+ 9], 5,  568446438);d=gg(d,a,b,c,x[i+14], 9,-1019803690);c=gg(c,d,a,b,x[i+ 3],14, -187363961);
        b=gg(b,c,d,a,x[i+ 8],20, 1163531501);a=gg(a,b,c,d,x[i+13], 5,-1444681467);d=gg(d,a,b,c,x[i+ 2], 9,  -51403784);
        c=gg(c,d,a,b,x[i+ 7],14, 1735328473);b=gg(b,c,d,a,x[i+12],20,-1926607734);a=hh(a,b,c,d,x[i+ 5], 4,    -378558);
        d=hh(d,a,b,c,x[i+ 8],11,-2022574463);c=hh(c,d,a,b,x[i+11],16, 1839030562);b=hh(b,c,d,a,x[i+14],23,  -35309556);
        a=hh(a,b,c,d,x[i+ 1], 4,-1530992060);d=hh(d,a,b,c,x[i+ 4],11, 1272893353);c=hh(c,d,a,b,x[i+ 7],16, -155497632);
        b=hh(b,c,d,a,x[i+10],23,-1094730640);a=hh(a,b,c,d,x[i+13], 4,  681279174);d=hh(d,a,b,c,x[i+ 0],11, -358537222);
        c=hh(c,d,a,b,x[i+ 3],16, -722521979);b=hh(b,c,d,a,x[i+ 6],23,   76029189);a=hh(a,b,c,d,x[i+ 9], 4, -640364487);
        d=hh(d,a,b,c,x[i+12],11, -421815835);c=hh(c,d,a,b,x[i+15],16,  530742520);b=hh(b,c,d,a,x[i+ 2],23, -995338651);
        a=ii(a,b,c,d,x[i+ 0], 6, -198630844);d=ii(d,a,b,c,x[i+ 7],10, 1126891415);c=ii(c,d,a,b,x[i+14],15,-1416354905);
        b=ii(b,c,d,a,x[i+ 5],21,  -57434055);a=ii(a,b,c,d,x[i+12], 6, 1700485571);d=ii(d,a,b,c,x[i+ 3],10,-1894986606);
        c=ii(c,d,a,b,x[i+10],15,   -1051523);b=ii(b,c,d,a,x[i+ 1],21,-2054922799);a=ii(a,b,c,d,x[i+ 8], 6, 1873313359);
        d=ii(d,a,b,c,x[i+15],10,  -30611744);c=ii(c,d,a,b,x[i+ 6],15,-1560198380);b=ii(b,c,d,a,x[i+13],21, 1309151649);
        a=ii(a,b,c,d,x[i+ 4], 6, -145523070);d=ii(d,a,b,c,x[i+11],10,-1120210379);c=ii(c,d,a,b,x[i+ 2],15,  718787259);
        b=ii(b,c,d,a,x[i+ 9],21, -343485551);a=ad(a,olda);b=ad(b,oldb);c=ad(c,oldc);d=ad(d,oldd);
    }
    return rh(a)+rh(b)+rh(c)+rh(d);
}

function refreshAttachments() {
    var route = $('#show-attachments').attr('data-route');
    getHtmlNoScroll(route, '#show-attachments');
}
