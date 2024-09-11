@php
    $action = isset($el) ? route('Device.update', [$el->id, '_type' => 'json']) : route('Device.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" value="utente">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Associa utente
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">


                @component('layouts.components.forms.utenti', ['name' => 'utente_id', 'value' => $el->utente_id, 'azienda_id' => $el->azienda_id,  'class' => 'col-md-12'])
                    Utente associato
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
