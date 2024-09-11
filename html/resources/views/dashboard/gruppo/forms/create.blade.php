@php
    $action = isset($el) ? route('gruppo.update', [$el->id, '_type' => 'json']) : route('gruppo.store');
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
                @endif

                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-8'])
                    Etichetta
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'broadcast', 'value' => @$el->broadcast, 'elements' => ['0' => 'No', '1' => 'Si'], 'class' => 'col-md-4'])
                    Broadcast <i class="fa fa-bullhorn"></i>
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'manutenzione_mezzi', 'value' => @$el->manutenzione_mezzi, 'elements' => ['0' => 'No', '1' => 'Si'], 'class' => 'col-md-6'])
                    Ricevi notifiche email su controllo mezzi
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'manutenzione_attrezzatura', 'value' => @$el->manutenzione_attrezzatura, 'elements' => ['0' => 'No', '1' => 'Si'], 'class' => 'col-md-6'])
                    Ricevi notifiche email su controllo attrezzatura
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
