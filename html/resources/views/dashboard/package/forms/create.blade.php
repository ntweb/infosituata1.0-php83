@php
    $action = isset($el) ? route('package.update', [$el->id, '_type' => 'json']) : route('package.store');
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
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Anagrafica
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12'])
                    Etichetta
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'sedi', 'value' => @$el->sedi, 'min' => 0, 'step' => 1, 'class' => 'col-md-3'])
                    Sedi
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'gruppi', 'value' => @$el->gruppi, 'min' => 0, 'step' => 1, 'class' => 'col-md-3'])
                    Gruppi
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'utenti', 'value' => @$el->utenti, 'min' => 0, 'step' => 1, 'class' => 'col-md-3'])
                    Utenti
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'size', 'value' => @$el->size, 'min' => 0, 'step' => 1, 'help' => 'Esprimere il valore in byte', 'class' => 'col-md-3'])
                    Cloud
                @endcomponent

                <input name="active" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'active', 'value' => @$el->active, 'class' => 'col-md-12', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Stato di attivazione
                @endcomponent


                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>
        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>
