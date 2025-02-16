@php
    $action = isset($el) ? route('mod-ot23.update', [$el->id, '_type' => 'json']) : route('mod-ot23.store');
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
                Anagrafica modulo
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                @component('layouts.components.forms.number', ['name' => 'anno', 'value' => @$el->anno ? $el->anno : date('Y'), 'class' => 'col-md-4', 'min' => date('Y') - 1, 'max' => date('Y'), 'step' => 1, '_read_only' => @$_read_only])
                    Anno
                @endcomponent

                @component('layouts.components.forms.text-mask', ['label' => 'Data e ora', 'name' => 'data_e_ora', 'value' => @$el->data_e_ora ? dataOra($el->data_e_ora) : date('d/m/Y H:i'), 'mask'=>'99/99/9999 99:99', 'class' => 'col-md-3', '_read_only' => @$_read_only])
                    Data e ora
                @endcomponent

                <div class="col-md-12">
                    <div class="divider"></div>
                </div>

                @component('layouts.components.forms.text', ['name' => 'reparto', 'value' => @$el->reparto, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                    Reparto
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'qualifica', 'value' => @$el->qualifica, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                    Qualifica
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'nome_e_cognome', 'value' => @$el->nome_e_cognome ? $el->nome_e_cognome : Auth::user()->name, 'class' => 'col-md-8', '_read_only' => @$_read_only])
                    Nome e cognome
                @endcomponent

                @php
                    $elements = [
                        'dipendente' => 'Dipendente',
                        'consulente' => 'Consulente',
                        'lav. az. esterna' => 'Lavoratore azienda esterna'
                    ];
                @endphp

                @component('layouts.components.forms.select', ['name' => 'tipo_lavoratore', 'value' => @$el->tipo_lavoratore, 'elements' => $elements, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                    Tipo lavoratore
                @endcomponent

                <div class="col-md-12">
                    <div class="divider"></div>
                </div>

                @php
                    $elements = [
                        'urto' => 'Urto',
                        'contatto' => 'Contatto',
                        'attrezzatura' => 'Attrezzatura',
                        'mezzo' => 'Mezzo',
                        'movimenti' => 'Movimenti',
                        'sversamenti' => 'Sversamenti',
                        'altro' => 'Altro',
                    ];
                @endphp

                @component('layouts.components.forms.select', ['name' => 'tipologia', 'value' => @$el->tipologia, 'elements' => $elements, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                    Tipologia
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'descrizione_incidente', 'value' => @$el->descrizione_incidente, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                    Descrizione incidente
                @endcomponent

                @php
                    $elements = [
                        'mancato util DPC' => 'Mancato utilizzo dispositivi di protezione collettiva',
                        'mancato util DPI' => 'Mancato utilizzo dispositivi di protezione individuale',
                        'mancata appl PS' => 'Mancata applicazione procedura sicurezza',
                        'mancata appl PP' => 'Mancata applicazione procedura preventiva'
                    ];
                @endphp

                @component('layouts.components.forms.select', ['name' => 'tipo_incidente', 'value' => @$el->tipo_incidente, 'elements' => $elements, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                    Tipo incidente
                @endcomponent

                @can('can-create')
                    @component('layouts.components.forms.text', ['name' => 'preposto', 'value' => @$el->preposto, 'class' => 'col-md-8', '_read_only' => @$_read_only])
                        Preposto
                    @endcomponent
                @endcan

                @component('layouts.components.forms.textarea', ['name' => 'prop_elim_pericolo', 'value' => @$el->prop_elim_pericolo, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                    Azioni intraprese per eliminare condizioni di pericolo
                @endcomponent

                @can('can-create')
                    <div class="col-md-12">
                        <div class="divider"></div>
                    </div>

                    <div class="col-md-12">
                        <h5 class="card-title">A cura del servizio di Prevenzione e Protezione (consultando i Preposti, MC e RLS)</h5>
                    </div>

                    @php
                        $elements = [
                            'manc. infortunio' => 'Mancato infortunio',
                            'medicazione' => 'Medicazione',
                            'comportamento' => 'Comportamento',
                            'manc. appl. proc.' => 'Mancata applicazione procedure'
                        ];
                    @endphp

                    @component('layouts.components.forms.select2-multiple', ['name' => 'categoria', 'class' => 'col-md-12', 'elements' => $elements, 'elementsSelected' => @$el->categoria ? array_flip(explode(',', $el->categoria)) : [], '_read_only' => @$_read_only])
                        Categoria
                    @endcomponent

                    @component('layouts.components.forms.textarea', ['name' => 'analisi_cause_problema', 'value' => @$el->analisi_cause_problema, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                        Analisi delle cause che hanno generato il problema
                    @endcomponent

                    <div class="col-md-12">
                        <h5 class="card-title">Descrizione delle azioni correttive se necessarie</h5>
                    </div>

                    @component('layouts.components.forms.text', ['name' => 'resp_attuazione', 'value' => @$el->resp_attuazione, 'class' => 'col-md-8', '_read_only' => @$_read_only])
                        Responsabile attuazione
                    @endcomponent

                    @component('layouts.components.forms.text', ['name' => 'term_attuazione', 'value' => @$el->term_attuazione, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                        Termine di attuazione
                    @endcomponent

                    @component('layouts.components.forms.textarea', ['name' => 'azioni_da_intr', 'value' => @$el->azioni_da_intr, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                        Azione da intraprendere
                    @endcomponent

                    @php
                        $elements = [
                            'in corso' => 'In corso',
                            'chiusa' => 'Chiusa',
                            'non necessaria' => 'Non necessaria'
                        ];
                    @endphp

                    @component('layouts.components.forms.select', ['name' => 'stato_azioni_da_intr', 'value' => @$el->stato_azioni_da_intr, 'elements' => $elements, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                        Stato azioni da intraprendere
                    @endcomponent

                    @php
                        $elements = [
                            'active' => 'Attivo',
                            'canceled' => 'Annullato',
                        ];
                    @endphp

                    @component('layouts.components.forms.select', ['name' => 'status', 'value' => @$el->status, 'elements' => $elements, 'class' => 'col-md-4', '_read_only' => @$_read_only])
                        Stato modulo
                    @endcomponent
                @endcan

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>

        @if(!$_read_only)
            <div class="d-block text-right card-footer">
                @if(isset($el))
                    <a href="{{ route('mod-ot23.pdf', $el->id) }}" class="btn btn-light"><i class="fa fa-print fa-fw" aria-hidden="true"></i> Stampa</a>
                @endif
                <button class="btn btn-primary" type="submit">Salva</button>
            </div>
        @endif

    </div>

</form>
