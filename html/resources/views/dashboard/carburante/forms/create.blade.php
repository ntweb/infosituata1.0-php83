@php
    $action = isset($action) ? $action : route('carburante.store', ['_item_id' => $item->id, '_type' => 'json']);
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
                <i class="header-icon fas fa-gas-pump icon-gradient bg-love-kiss"> </i>
                Scheda carburante
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.date-picker', ['name' => 'data', 'value' => isset($el) ? data($el->data) : null, 'class' => 'col-md-6'])
                    Data rifornimento
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'km', 'value' => @$el->km ? $el->km : 0, 'class' => 'col-md-6', 'min' => 0, 'step' => 1])
                    Km mezzo
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'litri', 'value' => @$el->litri ? $el->litri : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
                    Litri
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'costo', 'value' => @$el->costo ? $el->costo : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
                    Costo rifornimento
                @endcomponent

                @if (count($cisterne))
                    @component('layouts.components.forms.select', ['name' => 'cisterne_id', 'value' => @$el->cisterne_id, 'class' => 'col-md-12', 'elements' => array_merge([0 => '-------------------------'], $cisterne)])
                        Cisterna su cui effettuare lo scarico
                    @endcomponent
                @endif

            </div>
        </div>

        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg">Salva</button>
        </div>
    </div>
</form>

@if(isset($el))
    @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'manutenzioni'])
        Scheda carburante
    @endcomponent
@endif

@section('modal')
    @include('dashboard.carburante.components.modal-delete-attachment')
@endsection
