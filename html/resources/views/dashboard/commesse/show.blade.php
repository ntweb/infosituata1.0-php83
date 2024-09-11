@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => 'Analisi', 'icon' => 'bx bx-bar-chart-alt', 'right_component' => 'dashboard.commesse.analisi.components.show-header', 'el' => isset($el) ? $el : null, 'back' => isset($el) ? route('commessa.edit', $el->id) : null])
        {{ $el->label }}
    @endcomponent
@endsection

@section('content')

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title">
                {{-- <i class="header-icon bx bx-bar-chart-alt icon-gradient bg-love-kiss"> </i>--}}
                {{-- $el->label --}}
                @include('dashboard.commesse.contextual-fn-menu.overview')
                @include('dashboard.commesse.contextual-fn-menu.rapportini')
                @include('dashboard.commesse.contextual-fn-menu.checklist')
                @include('dashboard.commesse.contextual-fn-menu.avvisi')
            </div>
            <ul class="nav">
                <li class="nav-item">
                    <a data-toggle="tab"
                       id="refreshOverviewTable"
                       href="#tab-overview"
                       data-route="{{ route('commessa.show', [$el->id, '_refresh' => 'overview-table']) }}"
                       data-route-header-refresh="{{ route('commessa.show', [$el->id, '_refresh' => 'header']) }}"
                       class="nav-link active show">Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a data-toggle="tab" href="#tab-gantt20" class="nav-link ganttRender20"
                       data-route="{{ route('commessa.gantt20', [$el->id]) }}">Gantt</a>
                </li>
                <li class="nav-item">
                    <a id="btnRapportini"
                        data-toggle="tab" href="#tab-rapportini" class="nav-link get-html d-flex align-items-center"
                        data-route="{{ route('commessa-rapportino.index', ['commesse_root_id' => $el->id]) }}"
                        data-container="#tab-rapportini" data-callback="renderDataTableRapportini()">
                        <i class="bx bx-edit mx-1"></i>
                        <span class="d-none d-lg-block">Rapportini</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="btnChecklist"
                       data-toggle="tab" href="#tab-checklist" class="nav-link get-html d-flex align-items-center"
                       data-route="{{ route('checklist.index', ['reference_id' => $el->id, 'reference_controller' => 'commesse']) }}"
                       data-container="#tab-checklist" data-callback="renderDataTableChecklist()">
                        <i class="bx bx-check-square mx-1"></i>
                        <span class="d-none d-lg-block">Checklist</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="refreshAllegati"
                        data-toggle="tab" href="#tab-allegati" class="nav-link get-html d-flex align-items-center"
                        data-route="{{ route('commessa.allegati', ['commesse_root_id' => $el->id]) }}"
                        data-container="#tab-allegati" data-callback="renderDataTableAllegati()">
                        <i class="bx bx-paperclip mx-1"></i>
                        <span class="d-none d-lg-block">Allegati</span>
                    </a>
                </li>
                @can('avvisi_create', $el)
                <li class="nav-item">
                    <a id="refreshAvvisi"
                       data-toggle="tab" href="#tab-avvisi" class="nav-link get-html d-flex align-items-center"
                       data-route="{{ route('commessa.avvisi', $el->id) }}"
                       data-container="#tab-avvisi" data-callback="renderDataTableAvvisi()">
                        <i class="bx bx-calendar-star mx-1"></i>
                        <span class="d-none d-lg-block">Avvisi</span>
                    </a>
                </li>
                @endcan
{{--                <li class="nav-item">--}}
{{--                    <a data-toggle="tab" href="#tab-costi-ricavi" class="nav-link">Analisi costi ricavi</a>--}}
{{--                </li>--}}
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-overview" role="tabpanel">
                    <div id="table-overview-container" class="table-responsive" style="padding-bottom: 120px">
                        <div id="load-overview-trigger"></div>
                    </div>
                </div>
{{--                <div class="tab-pane show" id="tab-gantt" role="tabpanel">--}}
{{--                    @include('dashboard.commesse.analisi.gantt')--}}
{{--                </div>--}}
                <div class="tab-pane show" id="tab-gantt20" role="tabpanel">
                    @include('dashboard.commesse.analisi.gantt20')
                </div>
                <div class="tab-pane show" id="tab-rapportini" role="tabpanel">
                    {{-- ajax load --}}
                </div>
                <div class="tab-pane show" id="tab-checklist" role="tabpanel">
                    {{-- ajax load --}}
                </div>
                <div class="tab-pane show" id="tab-allegati" role="tabpanel">
                    {{-- ajax load --}}
                </div>
                <div class="tab-pane show" id="tab-avvisi" role="tabpanel">
                    {{-- ajax load --}}
                </div>
{{--                <div class="tab-pane" id="tab-costi-ricavi" role="tabpanel">--}}
{{--                    <p>Lorem Ipsum has been the industry's--}}
{{--                        standard dummy text ever since the 1500s, when an unknown printer took a galley of type and--}}
{{--                        scrambled it to make a--}}
{{--                        type specimen book. It has--}}
{{--                        survived not only five centuries, but also the leap into electronic typesetting, remaining--}}
{{--                        essentially unchanged. --}}
{{--                    </p>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>

@endsection

@section('modal')

    @include('dashboard.commesse.modals.modal-delete-attachment')

    @include('dashboard.commesse.modals.print')

    {{-- Modale conferma associazione costi --}}
    <form class="ns" action="{{ route('commessa.calculate-costi-consuntivi', $el->id) }}"
          method="POST" data-callback="closeAllModal(); refreshTree(); refreshOverviewTable();">
        @csrf

        <div class="modal fade" id="associaCostiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Conferma associazione costi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @component('layouts.components.alerts.warning')
                            Confermando l'operazione saranno presi tutti i costi ricavati dai log relativi ad utenti,
                            mezzi, attrezzature e materiali e sovrascritti alle fasi e sottofasi di appartenenza.
                            <br>
                            <br>
                            L'operazione non sarà annullabile ed eventuali valori precedentemente immessi saranno
                            sovrascritti.
                        @endcomponent
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">Conferma</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Modale creazione rapportini --}}
    <form class="ns" id="frmRapportino" action="{{ route('commessa-rapportino.store') }}"
          method="POST" data-callback="closeAllModal();$('#frmRapportino')[0].reset();refreshRapportini();openLastRapportino();">
        @csrf

        <div class="modal fade" id="rapportinoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rapportino</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-8">

                                <h5>Scheda</h5>
                                <div class="row">
                                    @component('layouts.components.forms.text', ['name' => 'titolo', 'value' => null, 'class' => 'col-md-12'])
                                        Titolo
                                    @endcomponent

                                    @component('layouts.components.forms.textarea', ['name' => 'descrizione', 'value' => null, 'class' => 'col-md-12', 'maxLength' => 100000000])
                                        Descrizione
                                    @endcomponent

                                    @component('layouts.components.forms.select2-fasi-commessa', ['name' => 'commesse_id', 'value' => null, 'class' => 'col-md-8', 'commesse_id' => $el->id])
                                        Fase / sottofase riferimento
                                    @endcomponent

                                    @component('layouts.components.forms.date-native', ['name' => 'start', 'value' => null, 'class' => 'col-md-4'])
                                        Data riferimento
                                    @endcomponent


                                    @component('layouts.components.forms.radio', ['name' => 'livello', 'value' => 'basso', 'class' => 'col-md-6', 'elements' => ['basso' => 'Basso', 'medio' => 'Medio', 'alto' => 'Alto'], 'inline' => true])
                                        Livello priorità
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-lg-4">

                                <h5>Inoltro rapportino</h5>

                                <div class="row">
                                    @if(isset($gruppi))
                                        @component('layouts.components.forms.select2-multiple', ['name' => 'sedi_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $sedi, 'elementsSelected' => []])
                                            Sedi
                                        @endcomponent
                                    @endif

                                    @if(isset($gruppi))
                                        @component('layouts.components.forms.select2-multiple', ['name' => 'gruppi_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => []])
                                            Gruppi destinatari
                                        @endcomponent
                                    @endif

                                    @if(isset($utenti))
                                        @component('layouts.components.forms.select2-multiple', ['name' => 'utenti_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $utenti, 'elementsSelected' => []])
                                            Destinatari
                                        @endcomponent
                                    @endif

                                    <div class="col-12">
                                        <hr>
                                    </div>

                                    @component('layouts.components.forms.checkbox', [ 'name' => 'confirm', 'elements' => [0 => 'Conferma invio del rapportino'], 'value' => 1 ])
                                    @endcomponent

                                    <div class="col-12">
                                        @component('layouts.components.alerts.warning')
                                            Confermando l'invio del rapportino non sarà più possibile modificarlo
                                        @endcomponent
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">Conferma</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
