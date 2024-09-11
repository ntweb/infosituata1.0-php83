@php
    $action = isset($el) ? route('mod-ot23_2024.update', [$el->id, '_type' => 'json']) : route('mod-ot23_2024.store');
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
                Modulo segnalazione mancato infortunio (Near Miss)
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @if(isset($el))
                @component('layouts.components.forms.text', ['label' => 'Codice evento', 'name' => 'codice_evento', 'value' => @$el->codice_evento ? $el->codice_evento : Str::uuid(), 'class' => 'col-md-6', '_read_only' => true])
                    Codice evento
                @endcomponent
                @endif

                @component('layouts.components.forms.date-native', ['name' => 'data_e_ora',  'value' => @$el->data_e_ora ?? null, 'class' => 'col-md-3', '_read_only' => @$_read_only])
                    Data
                @endcomponent

                @component('layouts.components.forms.select', ['name' => 'json_fascia_oraria', 'value' => @$json_fascia_oraria, 'elements' => $modot_23_2024_fascia_oraria, 'class' => 'col-md-3', '_read_only' => @$_read_only])
                    Fascia oraria
                @endcomponent

                <div class="col-md-12">
                    <div class="divider"></div>
                </div>

                @component('layouts.components.forms.text', ['name' => 'nome_e_cognome', 'value' => @$el->nome_e_cognome, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                    Nome e cognome
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'reparto', 'value' => @$el->reparto, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                    Reparto
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'attivita', 'value' => @$el->attivita, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                    Attività
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'descrizione_incidente', 'value' => @$el->descrizione_incidente, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                    Descrizione incidente
                @endcomponent

                <div class="col-md-12">
                    <h5 class="card-title">Possibili cause dell'evento</h5>
                </div>

                @component('layouts.components.forms.checkbox', [ 'name' => 'json_possibili_cause', 'elements' => $modot_23_2024_possibili_cause, 'value' => @$json_possibili_cause ?? []])
                    Possibili cause
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'json_possibili_cause_altro', 'value' => @$json_possibili_cause_altro, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                    Altro (specificare)
                @endcomponent

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>
            </div>
        </div>
    </div>

    @can('can_create_mancati_infortuni_rspp')
    <div class="mb-3 card main-card">
        <div class="card-body">
            <div class="form-row">

                    <div class="col-md-12">
                        <h5 class="card-title">A cura del servizio di Prevenzione e Protezione (consultando i Preposti, MC e RLS)</h5>
                    </div>

                    @component('layouts.components.forms.textarea', ['name' => 'descrizione_finale_evento', 'value' => @$el->descrizione_finale_evento, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                        Descrizione finale dell'evento
                    @endcomponent

                    <div class="col-md-12">
                        <h5 class="card-title">Incidente</h5>
                    </div>

                    @component('layouts.components.forms.checkbox', [ 'name' => 'json_incidente_poss_cause', 'elements' => $modot_23_2024_incidente_poss_cause, 'value' => @$json_incidente_poss_cause ?? []])
                        Tipologia di mancato infortunio
                    @endcomponent

                    @component('layouts.components.forms.textarea', ['name' => 'json_incidente_poss_cause_altro', 'value' => @$json_incidente_poss_cause_altro, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                        Altro (specificare)
                    @endcomponent

                    <div class="col-md-12">
                        <h5 class="card-title">Cause accertate dell'evento</h5>
                    </div>

                    @component('layouts.components.forms.checkbox', [ 'name' => 'json_cause_accertate', 'elements' => $modot_23_2024_cause_accertate, 'value' => @$json_cause_accertate ?? []])
                        A partire dal modulo di segnalazione si confermano o modificano le possibili cause lì indicate
                    @endcomponent

                    @component('layouts.components.forms.textarea', ['name' => 'json_cause_accertate_altro', 'value' => @$json_cause_accertate_altro, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                        Altro (specificare)
                    @endcomponent

                    @component('layouts.components.forms.select', ['name' => 'json_situazione_presentata', 'value' => @$json_situazione_presentata, 'elements' => $modot_23_2024_situazione_presentata, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                        La situazione rilevata si è già presentata in passato anche recente?
                    @endcomponent

                    <div id="criticita">
                        <div class="col-md-12">
                            <h5 class="card-title">Criticità organizzative collegate</h5>
                        </div>

                        @component('layouts.components.forms.checkbox', [ 'name' => 'json_critic_organizzative', 'elements' => $modot_23_2024_critic_organizzative, 'value' => @$json_critic_organizzative ?? []])
                            Se SI indicarne la tipologia
                        @endcomponent

                    </div>

                    <div class="col-md-12">
                        <h5 class="card-title">Danni</h5>
                    </div>

                    @component('layouts.components.forms.select', ['name' => 'json_danno', 'value' => @$json_danno, 'elements' => $modot_23_2024_danno, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                        Danno a strutture, impianti, attrezzature
                    @endcomponent

                    @component('layouts.components.forms.select', ['name' => 'json_potenziale_danno', 'value' => @$json_potenziale_danno, 'elements' => $modot_23_2024_potenziale_danno, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                        Potenziale danno alle persone
                    @endcomponent

                    <div class="col-md-12">
                        <h5 class="card-title">Sezione azioni intraprese</h5>
                    </div>

                    @component('layouts.components.forms.textarea', ['name' => 'prop_elim_pericolo', 'value' => @$el->prop_elim_pericolo, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                        Azioni immediate di rimedio
                    @endcomponent
            </div>
        </div>
    </div>

    <div class="mb-3 card">
        <div class="col-md-12 mx-2 mt-4">
            <h6 class="card-title">Azioni di miglioramento (correttive, preventive) - Tipologia intervento</h6>
        </div>
        <div class="card-header">
            <ul class="nav nav-justified">
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg7-0" class="nav-link show active">Tecnico</a></li>
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg7-1" class="nav-link show">Formazione</a></li>
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg7-2" class="nav-link show">Definizione</a></li>
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg7-3" class="nav-link show">Verifica</a></li>
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg7-4" class="nav-link show">Altro</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane show active" id="tab-eg7-0" role="tabpanel">
                    @component('layouts.components.forms.textarea', ['name' => 'azioni_migl_prev_tecnico', 'value' => @$el->azioni_migl_prev_tecnico, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                        Tecnico
                    @endcomponent
                </div>
                <div class="tab-pane show" id="tab-eg7-1" role="tabpanel">
                    @component('layouts.components.forms.textarea', ['name' => 'azioni_migl_prev_formazione', 'value' => @$el->azioni_migl_prev_formazione, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                        Formazione / Addestramento
                    @endcomponent
                </div>
                <div class="tab-pane show" id="tab-eg7-2" role="tabpanel">
                    @component('layouts.components.forms.textarea', ['name' => 'azioni_migl_prev_definizione', 'value' => @$el->azioni_migl_prev_definizione, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                        Definizione / revisione delle procedure e istruzioni lavorative
                    @endcomponent
                </div>
                <div class="tab-pane show" id="tab-eg7-3" role="tabpanel">
                    @component('layouts.components.forms.textarea', ['name' => 'azioni_migl_prev_verifica', 'value' => @$el->azioni_migl_prev_verifica, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                        Verifica applicazione procedure / istruzioni / comportamenti
                    @endcomponent
                </div>
                <div class="tab-pane show" id="tab-eg7-4" role="tabpanel">
                    @component('layouts.components.forms.textarea', ['name' => 'azioni_migl_prev_altro', 'value' => @$el->azioni_migl_prev_altro, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                        Altro (specificare)
                    @endcomponent
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 card">
        <div class="col-md-12 mx-2 mt-4">
            <h6 class="card-title">Verifica (follow up) azioni intraprese</h6>
        </div>
        <div class="card-header">
            <ul class="nav nav-justified">
                @for($i = 1; $i <= 3; $i++)
                <li class="nav-item"><a data-toggle="tab" href="#tab-f-up-{{ $i }}" class="nav-link show @if($i == 1) active @endif">Ver. Follow Up {{ $i }}</a></li>
                @endfor
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                @for($i = 1; $i <= 3; $i++)
                <div class="tab-pane show @if($i == 1) active @endif"" id="tab-f-up-{{ $i }}" role="tabpanel">
                    @php
                        $json_f_up_azioni = 'json_f_up_azioni_'.$i;
                        $json_f_up_resp = 'json_f_up_resp_'.$i;
                        $json_f_up_entro = 'json_f_up_entro_'.$i;
                        $json_f_up_data_att = 'json_f_up_data_att_'.$i;
                    @endphp
                    <div class="row">
                        @component('layouts.components.forms.textarea', ['name' => $json_f_up_azioni, 'value' => @$$json_f_up_azioni, 'class' => 'col-md-12', '_read_only' => @$_read_only, 'maxLength' => 5000])
                            Azioni di miglioramento (correttive, preventive)
                        @endcomponent

                        @component('layouts.components.forms.text', ['name' => $json_f_up_resp, 'value' => @$$json_f_up_resp, 'class' => 'col-md-12', '_read_only' => @$_read_only])
                            Responsabile attuazione
                        @endcomponent

                        @component('layouts.components.forms.date-native', ['name' => $json_f_up_entro,  'value' => @$$json_f_up_entro ?? null, 'class' => 'col-md-6', '_read_only' => @$_read_only])
                            Entro il
                        @endcomponent

                        @component('layouts.components.forms.date-native', ['name' => $json_f_up_data_att,  'value' => @$$json_f_up_data_att ?? null, 'class' => 'col-md-6', '_read_only' => @$_read_only])
                            Data attuazione
                        @endcomponent
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
    @endcan

    <div class="row">
        <div class="col d-none error-box">
            @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                Errore in fase di salvataggio
            @endcomponent
        </div>

        <div class="col">
            @if(!$_read_only)
                @if(isset($el))
                    <a href="{{ route('mod-ot23_2024.pdf', $el->id) }}" class="btn btn-light"><i class="fa fa-print fa-fw" aria-hidden="true"></i> Stampa</a>
                @endif
                <button class="btn btn-primary" type="submit">Salva</button>
            @endif
        </div>
    </div>

</form>
