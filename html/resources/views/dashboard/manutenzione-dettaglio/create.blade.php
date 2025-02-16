@php
    $action = isset($action) ? $action : route('manutenzione-dettaglio.store', ['_manutenzione_id' => $manutenzione->id, '_type' => 'json']);
@endphp
<form class="ns" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#form-create').html(null);$('#refreshDettagli').trigger('click');">
    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-tools icon-gradient bg-love-kiss"> </i>
                Dettaglio
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.select2-ricambi', ['name' => 'ricambi_id', 'class' => 'col-md-12', 'value' => @$el->ricambi_id])
                    Ricambio
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'magazzino', 'value' => @$el->magazzino ? $el->magazzino : 0, 'min' => 0, 'step' => 1, 'class' => 'col-md-6'])
                    Magazzino
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'acquistati', 'value' => @$el->acquistati ? $el->acquistati : 0, 'min' => 0, 'step' => 1, 'class' => 'col-md-6'])
                    Acquistati
                @endcomponent

            </div>
        </div>

        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg">Salva</button>
        </div>
    </div>
</form>
