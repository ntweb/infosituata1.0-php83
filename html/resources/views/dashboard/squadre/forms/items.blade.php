@php
    $action = route('squadra-item.add');
    $class = 'ns';
@endphp


<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">
    @csrf
    <input type="hidden" name="squadra_id" id="squadra_id" value="{{ $el->id }}">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                Squadra
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.select2-items', ['name' => 'squadra_items_id', 'value' => null, 'class' => 'col-md-12'])
                    Inserisci in squadra
                @endcomponent

                <div class="col-md-12" id="squadra-items">
                    @include('dashboard.squadre.forms.item-rows')
                </div>

            </div>
        </div>
        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>

<form class="ns" action="" id="frmDeleteSquadraItem" method="post" data-callback="getHtmlNoScroll('{{ route('squadra-item.index', ['squadra_id' => $squadra_id]) }}', '#squadra-items');">
    @csrf
    @method('DELETE')
</form>
