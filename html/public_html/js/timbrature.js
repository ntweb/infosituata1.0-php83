var latitude = null;
var longitude = null;
var timbraturaDate = null;

$(document).ready(function($) {

    async function checkGpsPermission() {
        var permissionStatus = await navigator?.permissions?.query({name: 'geolocation'});
        var hasPermission = permissionStatus?.state;

        if (hasPermission === 'granted') {
            $('#btnAcceptGeololation').trigger('click');
        }
    }

    checkGpsPermission();

    $(document).on('click', '#btnTimbraIngresso', function(){
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        $('#type').val('in');

        $('#btnTimbraIngresso').attr('disabled', 'disabled');
        $('#btnTimbraUscita').attr('disabled', 'disabled');

        $(this).closest('form').submit();
    });

    $(document).on('click', '#btnTimbraUscita', function(){
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        $('#type').val('out');

        $('#btnTimbraIngresso').attr('disabled', 'disabled');
        $('#btnTimbraUscita').attr('disabled', 'disabled');

        $(this).closest('form').submit();
    });

    $(document).on('change', '#dateTimbrature', function(){
        $(this).closest('form').submit();
    });

    $(document).on('change', '#dateTimbratureMese', function(){
        $(this).closest('form').submit();
    });

    $(document).on('click', '#btnAcceptGeololation', function(){

        if (navigator.geolocation) {
            // navigator.geolocation.getCurrentPosition(showPosition, showError);
            $('#getGpsPermission').hide(0);
            init();
        } else {
            $('#timbratura-error-geolocation').show(0);
            $('#timbratura-card').hide(0);
        }

    });

    if ($('#countdown').length) {
        currentTime();
    }

    $(document).on('change', '#selPermessoType', function() {
        var v = $(this).val();
        var start_at = $('#start_at');
        var end_at = $('#end_at');
        end_at.removeAttr('disabled');
        switch (v) {
            case 'permesso giornaliero':
                start_at.attr('type', 'date');
                end_at.attr('type', 'date');
                end_at.attr('disabled', 'disabled').val('null');
                break;

            case 'permesso orario':
                start_at.attr('type', 'datetime-local');
                end_at.attr('type', 'datetime-local');
                break;

            default:
                start_at.attr('type', 'date');
                end_at.attr('type', 'date');

        }
    });

    if ($('#selPermessoType').length) {
        $('#selPermessoType').trigger('change');
    }

    function init() {
        watchLocation(function(coords) {

            $('#timbratura-card').show(0);
            mMap.invalidateSize();

            latitude = coords.latitude;
            longitude = coords.longitude;

            // console.log(latitude+','+longitude);
            setTimeout(function(){ setMarker(latitude, longitude) }, 2000);

        }, function(error) {
            showError(error);
        });
    }

    function watchLocation(successCallback, errorCallback) {
        successCallback = successCallback || function(){};
        errorCallback = errorCallback || function(){};

        // Try HTML5-spec geolocation.
        var geolocation = navigator.geolocation;

        if (geolocation) {
            // We have a real geolocation service.
            try {
                function handleSuccess(position) {
                    successCallback(position.coords);
                }

                geolocation.watchPosition(handleSuccess, errorCallback, {
                    enableHighAccuracy: true,
                    maximumAge: 5000 // 5 sec.
                });
            } catch (err) {
                errorCallback(err);
            }
        } else {
            errorCallback();
        }
    }

    function showPosition(position) {
        $('#timbratura-card').show(0);
        mMap.invalidateSize();

        latitude = position.coords.latitude;
        longitude = position.coords.longitude;

        // console.log(latitude+','+longitude);
        setTimeout(function(){ setMarker(latitude, longitude) }, 2000);
    }

    function showError(error) {
        $('#timbratura-error-geolocation').show(0);
        $('#timbratura-card').hide(0);
        switch(error.code) {
            case error.PERMISSION_DENIED:
                $('#timbratura-error-geolocation-message').html("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                $('#timbratura-error-geolocation-message').html("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                $('#timbratura-error-geolocation-message').html("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                $('#timbratura-error-geolocation-message').html("An unknown error occurred.");
                break;
        }
    }

    function currentTime() {
        if (!timbraturaDate) {
            timbraturaDate = moment.unix($('#countdown').attr('data-hms')).toDate();
        }

        var hh = timbraturaDate.getHours();
        var mm = timbraturaDate.getMinutes();
        var ss = timbraturaDate.getSeconds();

        /*
        if(hh === 0){
            hh = 12;
        }
        */

        hh = (hh < 10) ? "0" + hh : hh;
        mm = (mm < 10) ? "0" + mm : mm;
        ss = (ss < 10) ? "0" + ss : ss;

        // var time = hh + ":" + mm + ":" + ss + " ";
        var time = hh + ":" + mm;

        $('#countdown').html(time);
        timbraturaDate.setUTCSeconds(timbraturaDate.getUTCSeconds() + 1);

        setTimeout(function(){ currentTime() }, 1000);
    }

    $(document).on('click', '.timbraturaMapPosition', function() {
        var latitude = $(this).attr('data-latitude');
        var longitude = $(this).attr('data-longitude');

        if (latitude && longitude) {
            setMarker(latitude, longitude);
        }
    });

    $(document).on('change', '#_marked_at, #_users_id', function() {
       var d = $('#_marked_at').val();
       var user_id = $('#_users_id').val();
       // console.log(d);
       // console.log(user_id);

       if (user_id && d) {
           if ($('#commessa').length) {
               var route = $('#commessa').attr('data-route');
               route += '?date='+d+'&user_id='+user_id;
               getHtml(route, '#commessa');
           }
       }
    });

    $(document).on('click', '.openModalPermesso', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalPermesso').modal('toggle');
            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    });

    $(document).on('click', '.createNewPermessoPowerUser', function() {
        var route = $(this).data('route');
        $.get(route, function(data) {
            $('#modal-ajax-html').html(data);
            $('#modalPermesso').modal('toggle');
            setTimeout(function() {
                initUI();
            }, 1000);
        }, 'html');
    });

    $(document).on('change', 'input[name=start_at]', function() {
        var st = $(this).val();
        var end = $('input[name=end_at]').val();

        if (!end) {
            $('input[name=end_at]').val(st);
        }

        $('input[name=end_at]').removeAttr('min');
        if (st) {
            $('input[name=end_at]').attr('min', st);
        }
    });

    $(document).on('change', 'input[name=end_at]', function() {
        var end = $(this).val();
        var st = $('input[name=start_at]').val();

        if (!st) {
            $('input[name=start_at]').val(end);
        }

        $('input[name=start_at]').removeAttr('max');
        if (end) {
            $('input[name=start_at]').attr('max', end);
        }
    });

});

function refreshPermessiTable() {
    var route = $('#div-list-permessi').data('route');
    $.get(route, function(data) {
        $('#div-list-permessi').html(data);
    }, 'html');
}
