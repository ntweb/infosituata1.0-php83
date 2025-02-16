@php
    $action = isset($action) ? $action : route('manutenzione.store', ['_item_id' => $item->id, '_type' => 'json']);
    $class = isset($el) ? 'ns' : null;
@endphp
<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">
    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-tools icon-gradient bg-love-kiss"> </i>
                D
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.date-picker', ['name' => 'data', 'value' => isset($el) ? data($el->data) : null, 'class' => 'col-md-12'])
                    Data manutenzione
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'tipo_1', 'value' => @$el->tipo_1, 'elements' => ['ordinario' => 'Ordinaria', 'straordinario' => 'Straordinaria'], 'class' => 'col-md-6'])
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'tipo_2', 'value' => @$el->tipo_2, 'elements' => ['interno' => 'Interna', 'esterno' => 'Esterna'], 'class' => 'col-md-6'])
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'tempo', 'value' => @$el->tempo ? $el->tempo : 0, 'min' => 0, 'step' => 1, 'class' => 'col-md-12', 'helper' => 'In minuti'])
                    Tempo impiegato
                @endcomponent

            </div>
        </div>

        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg">Salva</button>
        </div>
    </div>
</form>
