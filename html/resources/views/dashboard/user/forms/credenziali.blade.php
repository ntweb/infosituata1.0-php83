@php
    $action = isset($el) ? route('user.update', [$el->id, '_type' => 'json']) : route('user.store');
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


                <input name="_2fa" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => '_2fa', 'value' => $el->user->_2fa, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Autenticazione a 2 fattori
                @endcomponent


                <input name="active" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'active', 'value' => $el->user->active, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Stato di attivazione
                @endcomponent

                <input name="power_user" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'power_user', 'value' => $el->user->power_user, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Power user
                @endcomponent

{{--                <input name="module_timbrature" type="hidden" value="0" />--}}
{{--                @component('layouts.components.forms.toggle', ['name' => 'module_timbrature', 'value' => $el->user->module_timbrature, 'class' => 'col-md-12', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])--}}
{{--                    Modulo timbrature--}}
{{--                @endcomponent--}}


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
