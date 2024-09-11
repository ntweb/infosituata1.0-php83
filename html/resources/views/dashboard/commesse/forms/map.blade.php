@php
    $action = route('commessa.update', [$el->id, '_type' => 'json']);
    $class = 'ns';
@endphp

<form id="frmMap" class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#_geo_delete').val(0);">

    @csrf
    @method('PUT')

    <input type="hidden" name="_module" value="map">
    <input type="hidden" name="lat" id="lat" value="{{ $el->lat }}">
    <input type="hidden" name="lng" id="lng" value="{{ $el->lng }}">
    <input type="hidden" name="_geo_delete" id="_geo_delete" value="0">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                Mappa
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @component('layouts.components.forms.google-autocomplete', ['name' => 'google-autocomplete', 'class' => 'col-md-12', 'placeholder' => ' ', 'value' => null, 'helper' => 'Per georeferenziare la commessa, cliccare su un punto della mappa, oppure scrivere e selezionare un indirizzo e premere salva.'])
                    Inserire l'indirizzo
                @endcomponent

                <div class="col-12">
                    <div id="map" style="width: 100%; height: 400px; z-index: 1;" data-lat="{{ $el->lat }}" data-lng="{{ $el->lng }}"></div>
                </div>

            </div>
        </div>
        <div class="card-footer @if(!$el->lat) d-none @endif">
            <button class="btn btn-danger btn-lg" id="btnDeleteGeo" type="button">Elimina georeferenzazione</button>
        </div>
    </div>

</form>
