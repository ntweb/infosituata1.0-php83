@php
    $action = route('azienda-lite.update', [$azienda->id, '_type' => 'json']);
    $class = 'ns';
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @method('PUT')

    <input type="hidden" name="_module" id="_module" value="sms">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                SMS credenziali
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'module_sms_provider_username', 'value' => @$azienda->module_sms_provider_username, 'class' => 'col-md-12'])
                    Username
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'module_sms_provider_password', 'value' => null, 'class' => 'col-md-12'])
                    Password
                @endcomponent


                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>
        <div class="d-block text-right card-footer">
            @if(isset($el))
                <button class="btn btn-success btn-lg btnMessageSend" type="submit">Invia SMS</button>
            @endif
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>
