@php
    $action = isset($el) ? route('tipologia-scadenza.update', [$el->id, '_type' => 'json']) : route('tipologia-scadenza.store');
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

                @component('layouts.components.forms.moduli-details', ['name' => 'infosituata_moduli_details_id', 'value' => @$el->infosituata_moduli_details_id, 'class' => 'col-md-12'])
                    Modulo appartenenza
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12'])
                    Etichetta
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'description', 'value' => @$el->description, 'class' => 'col-md-12'])
                    Descrizione
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'mesi', 'id' => 'tip_scad_mesi', 'value' => @$el->mesi ? $el->mesi : 0, 'min' => 0, 'step' => 1, 'class' => 'col-md-4'])
                    Mesi per calcolo scadenza
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'giorni', 'id' => 'tip_scad_giorni', 'value' => @$el->giorni ? $el->giorni : 0, 'min' => 0, 'step' => 1, 'class' => 'col-md-4'])
                    Giorni per calcolo scadenza
                @endcomponent

                <div class="col-12">
                    <small>Attenzione: è possibile scegliere esclusivamente ai fini del calcolo i mesi o i giorni, non entrambi contemporaneamente</small>
                </div>

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
