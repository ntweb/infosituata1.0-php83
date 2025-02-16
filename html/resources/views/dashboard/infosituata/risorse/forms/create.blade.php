@php
    $action = isset($el) ? route('risorse.update', [$el->id, '_type' => 'json']) : route('risorse.store');
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

                @if(Auth::user()->superadmin)
                    @component('layouts.components.forms.aziende', ['name' => 'azienda_id', 'value' => isset($azienda_id) ? $azienda_id : @$el->azienda_id, 'class' => 'col-md-6'])
                        Azienda
                    @endcomponent
                    <div class="col-md-12"></div>
                @endif

                @component('layouts.components.forms.text', ['name' => 'extras1', 'value' => @$el->extras1, 'class' => 'col-md-12'])
                    Etichetta
                @endcomponent

                <input name="active" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'active', 'value' => @$el->active, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
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
