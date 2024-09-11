@php
    $action = isset($el) ? route('azienda.update', [$el->id, '_type' => 'json']) : route('azienda.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" value="package">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-user icon-gradient bg-love-kiss"> </i>
                Package
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.packages', ['name' => 'package_id', 'value' => $el->package_id, 'class' => 'col-md-12'])
                    Package
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'max_login', 'value' => @$el->max_login, 'min' => 0, 'step' => 1, 'class' => 'col-md-12', 'helper' => '0 = illimitato'])
                    Max login account simultanei
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'terminali', 'value' => @$el->terminali, 'min' => 0, 'step' => 1, 'class' => 'col-md-12'])
                    N°. Terminali
                @endcomponent

                <input name="module_timbrature" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'module_timbrature', 'value' => $el->module_timbrature, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Modulo timbrature
                @endcomponent

                <div class="col-12"></div>

                <input name="module_tasks" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'module_tasks', 'value' => $el->module_tasks, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Modulo task manager
                @endcomponent

                <div class="col-12"></div>

                <input name="module_commesse" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'module_commesse', 'value' => $el->module_commesse, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Modulo commesse
                @endcomponent

                <div class="col-12"></div>

                <input name="module_rapportini" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'module_rapportini', 'value' => $el->module_rapportini, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Modulo rapportini
                @endcomponent

                <div class="col-12"></div>

                <input name="module_checklist" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'module_checklist', 'value' => $el->module_checklist, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Modulo checklist
                @endcomponent

                <div class="col-12"></div>

                <input name="module_sms" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'module_sms', 'value' => $el->module_sms, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Modulo sms
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'module_sms_provider', 'value' => $el->module_sms_provider, 'class' => 'col-md-6', 'elements' => ['' => '-', 'trendoo' => 'Trendoo', 'esendex' => 'Esendex'] ])
                    Sms provider
                @endcomponent

                <div class="col-12"></div>

                <input name="module_whatsapp" type="hidden" value="0" />
                @component('layouts.components.forms.toggle', ['name' => 'module_whatsapp', 'value' => $el->module_whatsapp, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                    Modulo Whatsapp
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'module_whatsapp_tel', 'value' => $el->module_whatsapp_tel, 'class' => 'col-md-6'])
                    Whatsapp business tel
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'module_whatsapp_token', 'value' => $el->module_whatsapp_token, 'class' => 'col-md-12'])
                    Whatsapp Token
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'module_whatsapp_phone_number_id', 'value' => $el->module_whatsapp_phone_number_id, 'placeholder' => 'Es: 104395839067426', 'class' => 'col-md-12'])
                    Whatsapp Telephone id
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'module_whatsapp_endpoint', 'value' => $el->module_whatsapp_endpoint, 'placeholder' => 'Es: https://graph.facebook.com/v14.0/104395839067426/', 'class' => 'col-md-12'])
                    Whatsapp Endpoint
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
