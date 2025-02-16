@php
    $action = isset($el->configuration) ? route('device.update-configuration', [$el->configuration->id, '_type' => 'json']) : route('device.store');
    $class = isset($el->configuration) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el->configuration))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Configurazione device personalizzata
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.number-group', ['name' => 'hrm_bpm_min', 'value' => @$el->configuration->hrm_bpm_min ? $el->configuration->hrm_bpm_min : 0, 'min' => 0, 'step' => 1, 'group_text' => '<i class="fas fa-heartbeat"></i>', 'group_align' => 'right', 'class' => 'col-md-6'])
                    Battito cardiaco minimo
                @endcomponent

                @component('layouts.components.forms.number-group', ['name' => 'hrm_bpm_max', 'value' => @$el->configuration->hrm_bpm_max ? $el->configuration->hrm_bpm_max : 0, 'min' => 0, 'step' => 1, 'group_text' => '<i class="fas fa-heartbeat"></i>', 'group_align' => 'right', 'class' => 'col-md-6'])
                    Battito cardiaco massimo
                @endcomponent

                <div class="col-md-12">
                    <hr>
                </div>

                @component('layouts.components.forms.select', ['name' => 'geo_refresh', 'value' => @$el->configuration->geo_refresh, 'elements' => ['0' => 'Mai', '1' => 'Attiva'], 'helper' => 'Indipendentemente da un alert, georeferenzia i dispositivi', 'class' => 'col-md-12'])
                    Individuazione pos. GPS
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'fall_threshold', 'value' => @$el->configuration->fall_threshold, 'elements' => ['0' => 'Cadute violente', '1' => 'Cadute medio impatto', '2' => 'Cadute basso impatto'], 'class' => 'col-md-12'])
                    Soglia di impatto per uomo a terra
                @endcomponent

                <div class="col-md-12">
                    <hr>
                </div>

                @component('layouts.components.forms.tags', ['name' => 'emails_alert', 'value' => @$el->configuration->emails_alert, 'helper' => 'Email a cui inviare la notifica di alert','class' => 'col-md-12'])
                    Email
                @endcomponent

                @component('layouts.components.forms.tags', ['name' => 'telephones_alert', 'value' => @$el->configuration->telephones_alert, 'helper' => 'N. telefonici a cui inviare la notifica di alert','class' => 'col-md-12'])
                    Numeri telefonici
                @endcomponent

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

                <div class="col-md-12">
                    <hr>
                </div>

                @component('layouts.components.forms.select', ['name' => 'active', 'value' => @$el->configuration->active, 'elements' => ['1' => 'Attivata (Usa personalizzata)', '0' => 'Disattivata (Usa globale)'], 'class' => 'col-md-12'])
                    Stato configurazione personalizzata
                @endcomponent

{{--                @component('layouts.components.forms.tags', ['name' => 'emails_alert', 'value' => @$el->configuration->emails_alert, 'helper' => 'Email a cui inviare la notifica di alert','class' => 'col-md-6'])--}}
{{--                    Email--}}
{{--                @endcomponent--}}

{{--                @component('layouts.components.forms.tags', ['name' => 'telephones_alert', 'value' => @$el->configuration->telephones_alert, 'helper' => 'N. telefonici a cui inviare la notifica di alert','class' => 'col-md-6'])--}}
{{--                    Numeri telefonici--}}
{{--                @endcomponent--}}

            </div>
        </div>
        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>
