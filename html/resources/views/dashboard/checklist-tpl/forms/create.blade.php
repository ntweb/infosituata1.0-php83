@php
    $action = isset($el) ? route('checklist-template.update', [$el->id, '_type' => 'json']) : route('checklist-template.store');
    // $class = isset($el) ? 'ns' : null;
    $class = null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                Anagrafica
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12'])
                    Etichetta template
                @endcomponent

                @if(isset($el))

                    @php
                        $moduliIds = [
                            'commesse' => 'Commesse',
                            'utenti' => 'Utenti',
                            'mezzi' => 'Mezzi',
                            'attrezzature' => 'Attrezzature',
                            'materiali' => 'Materiali',
                            'risorse' => 'Risorse',
                            'checklist-generica' => 'Checklist generica',
                        ];
                    @endphp
                    @component('layouts.components.forms.select2-multiple', ['name' => 'modules_enabled', 'value' => '', 'class' => 'col-md-12', 'elements' => $moduliIds, 'elementsSelected' => $modulesEnabled])
                        Abilita su
                    @endcomponent

                    <input name="active" type="hidden" value="0"/>
                    @component('layouts.components.forms.toggle', ['name' => 'active', 'value' => @$el->active, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
                        Stato di attivazione
                    @endcomponent

                    <div class="col-12">
                        <hr>
                    </div>

                    @if($el->fl_prod)
                        <div class="col-12">
                            @component('layouts.components.alerts.info')
                                Checklist in produzione
                            @endcomponent
                        </div>
                    @else
                        @component('layouts.components.forms.checkbox', ['name' => 'fl_prod', 'elements' => ['1' => 'Manda in produzione'], 'value' => null])
                            Attenzione, una volta mandata in produzione la checklist,  non sarà più possibile
                            eliminare o aggiungere altri campi
                        @endcomponent
                    @endif
                @endif

                <div class="col-12 d-none error-box">
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
