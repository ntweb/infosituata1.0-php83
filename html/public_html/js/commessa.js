var commessaMap = null;
var commessaMarker = null;
var commessaMarkers = [];

function addMarker(latlng) {
    for(var i = 0; i < commessaMarkers.length; i++){
        commessaMap.removeLayer(commessaMarkers[i]);
    }

    commessaMarker = new L.marker(latlng);
    commessaMap.addLayer(commessaMarker);
    commessaMarkers.push(commessaMarker);

    $('#lat').val(latlng.lat);
    $('#lng').val(latlng.lng);
    $('#frmMap').submit();

    commessaMap.setView([latlng.lat, latlng.lng], 10);

    $('#btnDeleteGeo').parent().removeClass('d-none');
}

function addMarkerEvent(e){
    addMarker(e.latlng);
}

function renderMap() {
    var lat = $('#map').attr('data-lat');
    var lng = $('#map').attr('data-lng');
    commessaMap = L.map('map').setView([lat ? lat : 42.371651, lng ? lng : 13.094561], lat ? 10 : 5);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(commessaMap);

    if (lat && lng) {
        commessaMarker = L.marker(new L.LatLng(lat, lng));
        commessaMap.addLayer(commessaMarker);
        commessaMarkers.push(commessaMarker);
    }

    commessaMap.on('click', function(e){
        addMarkerEvent(e);
    });
}

function searchItemAssignment(route) {
    var params = $('.searchParameter').serialize();
    getHtml(route+'?'+params, '#resultSearchItem');
}

function swapAssignment(itemID) {
    $('#item-id-'+itemID+'-assign').hide(0);
    $('#item-id-'+itemID+'-assigned').show(0);
}

function searchAftertInsert(module) {
    $('#frmCreateNode')[0].reset();
    var search = '';
    search = $('#extras1').val();

    $('#textSearchItem').val(search);
    $('.btnSearchItem').trigger('click');
}

function enableCtxMenu(selector) {
    $('.ctx-menu').hide(0);
    $(selector).show(0);
}

function refreshRapportini() {
    $('#btnRapportini').trigger('click');
}

function openLastRapportino() {
    setTimeout(function() {
        $('.openRapportinoLast').trigger('click');
    }, 1000);
}

function refreshChecklist() {
    closeAllModal();
    $('#btnChecklist').trigger('click');
}

function refreshOverviewTable() {
    if ($('#refreshOverviewTable').length) {
        enableCtxMenu('#ctx-overview');
        var route = $('#refreshOverviewTable').attr('data-route');

        getHtmlNoScroll(route, '#table-overview-container', function() {
            setTimeout(function() {
                extraFieldsColor();
                toggleNodeOverviewTable();
            }, 1500);
        });

        var routeHeaderRefresh = $('#refreshOverviewTable').attr('data-route-header-refresh');
        getHtml(routeHeaderRefresh, '#header-right-component');
    }
}

function toggleNodeOverviewTable() {
    if (localStorage.getItem('utente')) {
        $('button[data-node=utente]').removeClass('btn-outline-primary').addClass('btn-outline-light');
        $('tr[data-node-toggle=utente]').hide(0);
    }
    else {
        $('button[data-node=utente]').addClass('btn-outline-primary').removeClass('btn-outline-light');
        $('tr[data-node-toggle=utente]').show(0);
    }

    if (localStorage.getItem('mezzo')) {
        $('button[data-node=mezzo]').removeClass('btn-outline-primary').addClass('btn-outline-light');
        $('tr[data-node-toggle=mezzo]').hide(0);
    }
    else {
        $('button[data-node=mezzo]').addClass('btn-outline-primary').removeClass('btn-outline-light');
        $('tr[data-node-toggle=mezzo]').show(0);
    }

    if (localStorage.getItem('attrezzatura')) {
        $('button[data-node=attrezzatura]').removeClass('btn-outline-primary').addClass('btn-outline-light');
        $('tr[data-node-toggle=attrezzatura]').hide(0);
    }
    else {
        $('button[data-node=attrezzatura]').addClass('btn-outline-primary').removeClass('btn-outline-light');
        $('tr[data-node-toggle=attrezzatura]').show(0);
    }

    if (localStorage.getItem('materiale')) {
        $('button[data-node=materiale]').removeClass('btn-outline-primary').addClass('btn-outline-light');
        $('tr[data-node-toggle=materiale]').hide(0);
    }
    else {
        $('button[data-node=materiale]').addClass('btn-outline-primary').removeClass('btn-outline-light');
        $('tr[data-node-toggle=materiale]').show(0);
    }

    if (localStorage.getItem('costi')) {
        $('button[data-node=costi]').removeClass('btn-outline-primary').addClass('btn-outline-light');
        $('*[data-node-toggle=costi]').hide(0);
    }
    else {
        $('button[data-node=costi]').addClass('btn-outline-primary').removeClass('btn-outline-light');
        $('**[data-node-toggle=costi]').show(0);
    }

    if (localStorage.getItem('extras')) {
        $('button[data-node=extras]').removeClass('btn-outline-primary').addClass('btn-outline-light');
        $('*[data-node-toggle=extras]').hide(0);
    }
    else {
        $('button[data-node=extras]').addClass('btn-outline-primary').removeClass('btn-outline-light');
        $('**[data-node-toggle=extras]').show(0);
    }
}

function refreshLogsItemTable(route) {
    getHtml(route, '#logs-table');
}

function renderDataTableRapportini() {
    enableCtxMenu('#ctx-rapportini');
    if ($('#rapportini_dt').length) {
        $('#rapportini_dt').DataTable({
            retrieve: true,
            paging: false
        });
    }
}

function renderDataTableChecklist() {
    enableCtxMenu('#ctx-checklist');
    if ($('#checklist_dt').length) {
        $('#checklist_dt').DataTable({
            retrieve: true,
            paging: false
        });
    }
}

function renderDataTableAllegati() {
    enableCtxMenu('#ctx-allegati');
    if ($('#allegati_dt').length) {
        $('#allegati_dt').DataTable({
            retrieve: true,
            paging: false
        });
    }
}

function renderDataTableAvvisi() {
    enableCtxMenu('#ctx-avvisi');
    if ($('#avvisi_dt').length) {
        $('#avvisi_dt').DataTable({
            retrieve: true,
            paging: false
        });
    }
}

function refreshAvvisi() {
    $('#refreshAvvisi').trigger('click');
}


function extraFieldsColor() {
    $('.selectExtraField option:selected').each(function(i, el) {
        var color = $(el).attr('data-color');
        $(el).closest('td').css('background', color);
    });
}


$(document).ready(function($) {

    if($('#map').length) {
        renderMap();
    }

    if ($('#load-overview-trigger').length) {
        var w = $('#table-overview-container').width();
        var h = $('body').height() - $('.app-header').height() - $('.app-inner-layout').height() - 120;

        $('#table-overview-container').css('max-width', w+'px');
        $('#table-overview-container').css('max-height', h+'px');
        $('#gantt20').css('max-width', w+'px');
        $('#gantt20').css('max-height', h+'px');

        setTimeout(function(){
           refreshOverviewTable();
        }, 500);
    }

    $(document).on('click', '#refreshOverviewTable', function() {
        refreshOverviewTable();
    });

    $(document).on('click', '.autorizzazioni', function() {
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

    $(document).on('click', '.createAvvisoCommessa', function(event) {
        event.preventDefault();
        var route = $(this).data('route');
        if (!route) {
            route = $(this).attr('href');
        }

        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalAvvisoCommessa').modal('toggle');
            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    });

    $(document).on('click', '.createChecklistCommessa', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalChecklist').modal('toggle');
            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    });

    $(document).on('click', '.createChecklistCommessaDo', function() {
        var checklists_templates_id = $('#checklists_templates_id').val();
        var commesse_id = $('#commesse_checklist_id').val();

        if (checklists_templates_id && commesse_id) {
            $('.modal').modal('hide');

            var route = $(this).data('route').replaceAll('xxx', checklists_templates_id);
            route += '?node='+commesse_id;

            setTimeout(function() {
                $.get(route, function(data) {
                    $('#modal-ajax-html').html(data);
                    $('#modalChecklist').modal('toggle');
                    setTimeout(function() {
                        initUI();
                    }, 1000);
                }, 'html');
            }, 1000);

        }
    });

    $(document).on('click', '.openChecklistCommessa', function() {
        var route = $(this).data('route');
        setTimeout(function() {
            $.get(route, function(data) {
                $('#modal-ajax-html').html(data);
                $('#modalChecklist').modal('toggle');
                setTimeout(function() {
                    initUI();
                }, 1000);
            }, 'html');
        }, 1000);
    });



    // if ($('#refreshOverviewTable')) {
    //     extraFieldsColor();
    //     toggleNodeOverviewTable();
    // }

    $(document).on('click', '.btnSearchItem', function() {
        var route = $(this).data('route');
        searchItemAssignment(route);
    });

    $(document).on('click', '.btnCreateItem', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html-2').html(data);
            $('#modalCreateItem').modal('toggle');
            setTimeout(function(){
                $('#active').bootstrapToggle();
                $('#fl_external').bootstrapToggle();
                initUI();
            }, 1000);
        }, 'html');

    });

    $(document).on('click', '.uploadDocNode', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalUploadDocument').modal('toggle');
            setTimeout(function() {
                initAjaxForms();
                initDropzone();
            }, 1000);
        }, 'html');

    });

    $(document).on('keyup', '#textSearchItem', function(e) {
        var code = e.key;
        if(code==="Enter") {
            var route = $(this).data('route');
            searchItemAssignment(route);
        }
    });

    $(document).on('click', '.selectGruppo', function() {
        var id = $(this).attr('data-id');
        var label = $(this).attr('data-label');

        $('input[name=gruppo_id').val(id);
        $('#selectedGruppo').html(label);
    });

    $(document).on('click', '.addItemNode', function() {
        var route = $(this).attr('data-route');

        $('input[name="commessa_item_id"]').first().val($(this).attr('data-item-id'));
        $('input[name="_parent_id"]').first().val($(this).attr('data-to'));

        $('#frmCreateNode').attr('data-callback', 'refreshTree();refreshOverviewTable();swapAssignment('+$(this).attr('data-item-id')+')');

        if ($(this).hasClass('addSquadraNode')) {
            $('#frmCreateNode').attr('data-callback', 'closeAllModal();refreshTree();refreshOverviewTable();swapAssignment('+$(this).attr('data-item-id')+')');
        }

        $('#frmCreateNode').attr('action', route);
        $('#frmCreateNode').submit();
    });

    $(document).on('click', '.nodeChangeStatus', function() {
        var route = $(this).attr('data-route');

        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalChangeStatus').modal('toggle');
            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    });

    $(document).on('click', '.openRapportino', function() {
        var route = $(this).attr('data-route');

        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalRapportino').modal('toggle');

            setTimeout(function () {
                initAjaxForms();
                initDropzone();
            }, 1500);
        }, 'html');
    });

    $(document).on('mouseenter', '.hightlight-tr', function() {
       var selector = '#' + $(this).attr('data-hightlight');
       $(selector).addClass('bg-light');
    });

    $(document).on('mouseout', '.hightlight-tr', function() {
        $('tr').removeClass('bg-light');
    });

    $(document).on('mouseenter', '.hightlight-tr-dependant', function() {
        var selector = 'tr[data-depend-on=\''+$(this).attr('data-id')+'\']';
        $(selector).addClass('bg-light');
    });

    $(document).on('mouseout', '.hightlight-tr-dependant', function() {
        $('tr').removeClass('bg-light');
    });

    $(document).on('click', '.openNodeLog', function() {
        var route = $(this).attr('data-route');

        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalNodeLogs').modal('toggle');

            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    });

    $(document).on('keyup mouseup', 'input[name=costo_item_giornaliero_previsto]', function(){
        var dayToHours = parseFloat($(this).attr('data-day-to-hours'));
        var v = parseFloat($(this).val()) / dayToHours;
        if (v) {
            $('input[name=costo_item_orario_previsto]').val(v);
        }
    });

    $(document).on('keyup mouseup', 'input[name=costo_item_orario_previsto]', function(){
        var dayToHours = parseFloat($(this).attr('data-day-to-hours'));
        var v = parseFloat($(this).val()) * dayToHours;
        if (v) {
            $('input[name=costo_item_giornaliero_previsto]').val(v);
        }
    });

    $(document).on('click', '.deleteCommessaLog', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html-2').html(data);
            $('#modalDeleteLog').modal('toggle');
        }, 'html');
        // $('#modalNodeCreate').modal('toggle');
    });

    $(document).on('change', '.selectExtraField', function() {
        var route = $(this).attr('data-route');
        var nodeExtraField = $(this).attr('data-node-extra-field');

        var data = new FormData();
        data.append("_token", $('meta[name="csrf-token"]').attr('content'));
        $('select[data-node-extra-field='+nodeExtraField+']').each(function(i , el) {
            if ($(el).val()) {
                data.append($(el).attr('name'), $(el).val());
            }
        });

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
                    extraFieldsColor();
                }
                else toastr.error(data.payload, 'Errore');
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on('click', '.toggleNode, .toggleColumn', function() {
       var nodeToggle = $(this).attr('data-node');
       if (localStorage.getItem(nodeToggle)) {
           localStorage.removeItem(nodeToggle);
       }
       else {
           localStorage.setItem(nodeToggle, true);
       }

       toggleNodeOverviewTable();
    });

    $(document).on('click', '.eventoReadMore', function() {
        $(this).parent().hide(0);
        var selector = $(this).attr('data-toggle');
        $(selector).show(0);
    });

    $(document).on('click', '.eventoReadLess', function() {
        $(this).parent().hide(0);
        $(this).parent().prev().show(0);
    });

    $(document).on('click', '.ganttRender', function() {

        enableCtxMenu('#ctx-gantt');

        // JS
        var columnWidths = [120, 80, 80];
        var span = function(val, width) {
            return  '<span style="width:' + width + 'px;">' + val + '</span>';
        };

        var mapLabels = function(labels) {
            return labels
                .map(function(v, i) {
                    return span(v, columnWidths[i]);
                })
                .join('');
        };

        var headerText = '' + mapLabels(['Fase', 'Inizio', 'Fine']) + '';
        var tickTemplate = mapLabels([
            '%name',
            '%low',
            '%high'
        ]);
        boldTickTemplate = '<b>' + tickTemplate + '</b>';

        var route = $(this).attr('data-route');
        $.get(route, function(data) {
            // console.log(data);
            $('.chart').css('height', (data.rows * 28) + 'px');

            JSC.chart('chartDiv', {
                defaultCultureName: "it-IT",
                debug: true,
                /*Typical Gantt setup. Horizontal columns by default.*/
                type: 'horizontal column solid',
                /*Make columns overlap.*/
                zAxis_scale_type: 'stacked',

                defaultBox_boxVisible: false,
                defaultAnnotation: {
                    label_style_fontSize: '15px'
                },
                annotations: [
                    { position: '0,2', label_text: headerText },
                    {
                        position: 'top right',
                        label_text: 'Gantt preventivo'
                    }
                ],
                legend: {
                    visible: false,
                    // position: 'inside left bottom',
                    fill: '#D6E0E5',
                    outline_width: 0,
                    corners: 'round',
                    template: '%icon %name'
                },
                xAxis: {
                    defaultTick: { label_style: { fontSize: 12 } }
                },
                palette: 'fiveColor46',
                yAxis: {
                    id: 'yAx',
                    alternateGridFill: 'none',
                    scale: {
                        type: 'time',
                        range: data.range
                    },
                    scale_range_padding: 0.15,
                    markers: data.markers
                },
                defaultTooltip_combined: false,
                defaultPoint: {
                    xAxisTick_label_text: tickTemplate,
                    tooltip:
                        '<b>%name</b> %low - %high<br/>{days(%high-%low)} days'
                },
                defaultSeries: {
                    firstPoint: {
                        outline: { color: 'darkenMore', width: 2 },
                        hatch_style: 'light-downward-diagonal',
                        xAxisTick_label_text: boldTickTemplate
                    }
                },
                yAxis_scale_type: 'time',
                series: data.series.prev,
            });

            JSC.chart('chartDiv2', {
                defaultCultureName: "it-IT",
                debug: true,
                /*Typical Gantt setup. Horizontal columns by default.*/
                type: 'horizontal column solid',
                /*Make columns overlap.*/
                zAxis_scale_type: 'stacked',

                defaultBox_boxVisible: false,
                defaultAnnotation: {
                    label_style_fontSize: '15px'
                },
                annotations: [
                    { position: '0,2', label_text: headerText },
                    {
                        position: 'top right',
                        label_text: 'Gantt consuntivo'
                    }
                ],
                legend: {
                    visible: false,
                    // position: 'inside left bottom',
                    fill: '#D6E0E5',
                    outline_width: 0,
                    corners: 'round',
                    template: '%icon %name'
                },
                xAxis: {
                    defaultTick: { label_style: { fontSize: 12 } }
                },
                palette: 'fiveColor46',
                yAxis: {
                    id: 'yAx',
                    alternateGridFill: 'none',
                    scale: {
                        type: 'time',
                        range: data.range
                    },
                    scale_range_padding: 0.15,
                    markers: data.markers
                },
                defaultTooltip_combined: false,
                defaultPoint: {
                    xAxisTick_label_text: tickTemplate,
                    tooltip:
                        '<b>%name</b> %low - %high<br/>{days(%high-%low)} days'
                },
                defaultSeries: {
                    firstPoint: {
                        outline: { color: 'darkenMore', width: 2 },
                        hatch_style: 'light-downward-diagonal',
                        xAxisTick_label_text: boldTickTemplate
                    }
                },
                yAxis_scale_type: 'time',
                series: data.series.cons,
            });

            // console.log(data.series.cons);

        }, 'json');


    });

    $(document).on('click', '.ganttRender20', function() {

        enableCtxMenu('#ctx-gantt');

        var route = $(this).attr('data-route');
        getHtml(route, '#gantt20', function() {
           setTimeout(function() {
               drawGanttLines();
           }, 1000)
        });
    });


    setTimeout(function() {
        if (googleAutocomplete) {
            googleAutocomplete.addListener("place_changed", function() {
                var place = googleAutocomplete.getPlace();
                var latlng = L.latLng(place.geometry.location.lat(), place.geometry.location.lng());
                addMarker(latlng);
            });
        }
    }, 1500);

    $(document).on('click', '.openModalNote', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalNote').modal('toggle');
        }, 'html');
    });

    $(document).on('click', '.btnCheckAvviso', function() {
        var route = $(this).data('route');

        var data = new FormData();
        data.append("_token", $('meta[name="csrf-token"]').attr('content'));
        data.append("_method", 'PUT');

        console.log(data);

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
                    if ($('#refreshAvvisi').length) {
                        closeAllModal();
                        refreshAvvisi();
                    }
                    else {
                        closeAllModal();
                        location.reload();
                    }
                }
                else toastr.error(data.payload, 'Errore');
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on('click', '#btnDeleteGeo', function() {
        $('#_geo_delete').val(1);
        $(this).parent().addClass('d-none');

        for(var i = 0; i < commessaMarkers.length; i++){
            commessaMap.removeLayer(commessaMarkers[i]);
        }

        $('#frmMap').submit();
    });
});


