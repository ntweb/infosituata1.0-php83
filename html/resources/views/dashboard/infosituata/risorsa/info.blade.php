@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Infosituata', 'icon' => 'pe-7s-home', 'back' => $back, 'right_component' => 'dashboard.infosituata.risorsa.components.info-header', 'el' => $el])
        {{ Str::title($el->extras1) }}
    @endcomponent

@endsection

@section('content')

    @php
        $scadenze = getScadenze($el, null);
    @endphp

    @if(!$el->active)
        <div class="row">
            <div class="col-md-12">
                <div class="card-border mb-3 card card-body border-danger">
                    Attenzione questo item risulta disattivato
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3 card">
                <div class="tabs-lg-alternate card-header">
                    <ul class="nav nav-justified">
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-eg9-0" class="active nav-link">
                                <div class="widget-number"><i class="bx bx-calendar-check"></i></div>
                                <div class="tab-subheading">Informazioni</div>
                            </a></li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-eg9-1" class="nav-link">
                                <div class="widget-number"><i class="bx bx-paperclip"></i></div>
                                <div class="tab-subheading">Allegati</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-eg9-2" class="nav-link">
                                <div class="widget-number"><i class="bx bx-note"></i></div>
                                <div class="tab-subheading">Note</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-eg9-3" class="nav-link">
                                <div class="widget-number"><i class="bx bx-check-circle"></i></div>
                                <div class="tab-subheading">Checklist</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-eg9-4" class="nav-link">
                                <div class="widget-number"><i class="bx bx-notepad"></i></div>
                                <div class="tab-subheading">Rapportini</div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-eg9-0" role="tabpanel">
                        <div class="card-body">

                            <select class="btn-light multiselect-radio radioFilterScadenze">
                                <option value="">Seleziona tutti</option>
                                @foreach($module->details as $detail)
                                    <option value=".mod-{{ $detail->id }}">{{ Str::title($detail->label) }}</option>
                                @endforeach
                            </select>

                            <div class="mb-2"></div>

                            @if(count($scadenze))
                                <div class="table-responsive">
                                    <table class="mb-0 table table-hover">
                                        <thead>
                                        <tr>
                                            <th class="">Etichetta</th>
                                            <th class="">Data scadenza</th>
                                            <th class="">Data avviso</th>
                                            <th class=""><i class="fa fa-bullhorn"></i> Utente</th>
                                            <th class=""><i class="fa fa-bullhorn"></i> Gruppi</th>
                                            <th class=""><i class="fa fa-check"></i> Check</th>
                                            <th class=""></th>
                                            <th class=""></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($scadenze as $scadenza)
                                            @php
                                                $_class = null;
                                                $_status = statusScadenza($scadenza);
                                                if ($_status == 'in-scadenza') $_class = 'table-warning';
                                                if ($_status == 'scaduto') $_class = 'table-danger';
                                            @endphp

                                            <tr class="filter mod-{{ $scadenza->infosituata_moduli_details_id }} {{ $_class }}">
                                                <td>
                                                    {{ $scadenza->module->label }}
                                                    /
                                                    {{ $scadenza->detail ? Str::title($scadenza->detail->label) : 'Altro' }}
                                                </td>
                                                <td>{{ data($scadenza->end_at) }}</td>
                                                <td>{{ data($scadenza->advice_at) }}</td>
                                                <td>
                                                    @if($scadenza->advice_item)
                                                        @component('layouts.components.labels.success')
                                                            si
                                                        @endcomponent
                                                    @else
                                                        @component('layouts.components.labels.error')
                                                            no
                                                        @endcomponent
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(count($scadenza->gruppi))
                                                        @foreach($scadenza->gruppi as $gruppo)
                                                            @component('layouts.components.labels.info')
                                                                {{ $gruppo->label }}
                                                            @endcomponent
                                                        @endforeach
                                                    @else
                                                        @component('layouts.components.labels.error')
                                                            no
                                                        @endcomponent
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($scadenza->checked_at)
                                                        @component('layouts.components.labels.success')
                                                            {{ data($scadenza->checked_at) }}
                                                        @endcomponent
                                                    @else
                                                        @if(scaduto($scadenza))
                                                            @component('layouts.components.labels.error')
                                                                scaduto
                                                            @endcomponent
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $scadenza->checked_by ? Str::title($scadenza->checkedBy->name) : 'nd' }}</td>
                                                <td class="text-right">
                                                    <a href="{{ route('scadenzario.edit', [$scadenza->id]) }}" class="btn btn-primary btn-sm"> Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @component('layouts.components.alerts.warning')
                                    Nessun dato registrato
                                @endcomponent
                            @endif

                        </div>
                    </div>
                    <div class="tab-pane" id="tab-eg9-1" role="tabpanel">
                        <div class="card-body">
                            @include('dashboard.infosituata.tables.doc-allegati')
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-eg9-2" role="tabpanel">
                        <div class="card-body">
                            @if($el->big_extras1)
                                <div class="infosituata-embed">
                                    {!! $el->big_extras1 !!}
                                </div>
                            @else
                                @component('layouts.components.alerts.info')
                                    Nessuna nota presente
                                @endcomponent
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-eg9-3" role="tabpanel">
                        <div class="card-body">
                            @if(count($listChecklist))
                                @php
                                    $list = $listChecklist;
                                @endphp
                                @include('dashboard.checklist.tables.index')
                            @else
                                @component('layouts.components.alerts.info')
                                    Nessuna checklist presente
                                @endcomponent
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-eg9-4" role="tabpanel">
                        <div class="card-body">
                            @if(count($listRapportini))
                                @php
                                    $list = $listRapportini;
                                @endphp
                                @include('dashboard.rapportini.tables.index')
                            @else
                                @component('layouts.components.alerts.info')
                                    Nessun rapportino presente
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
