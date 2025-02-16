@php
    $action = isset($el) ? route('azienda.update', [$el->id, '_type' => 'json']) : route('azienda.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" value="account">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-user icon-gradient bg-love-kiss"> </i>
                Credenziali
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'email', 'value' => $el->user->email, 'class' => 'col-md-12'])
                    Email
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'password', 'value' => null, 'class' => 'col-md-12'])
                    Password
                @endcomponent

                @component('layouts.components.forms.date-picker', ['name' => 'deactivate_at', 'value' => data($el->user->deactivate_at), 'class' => 'col-md-6'])
                    Data chiusura
                @endcomponent

				<input name="active" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'active', 'value' => $el->user->active, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
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
            <button class="btn btn-primary btn-lg">Salva</button>
        </div>
    </div>
</form>
