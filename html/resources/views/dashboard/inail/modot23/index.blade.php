@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista mancati infortuni', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.inail.modot23.components.index-header', 'years' => $years])
        Mancati infortuni
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Moduli {{ isset($anno) ? $anno : null }}</h5>
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
                                    <th>Lavoratore</th>
                                    <th>Azienda</th>
                                    <th>Reparto</th>
                                    <th>Tipologia</th>
                                    <th>Tipo incidente</th>
                                    <th>Data</th>
                                    <th>Anno</th>
                                    <th>Stato</th>
{{--                                    <th>Creato da</th>--}}
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
{{--                                        <th scope="row">{{ $el->id }}</th>--}}
                                        <td>{{ Str::title($el->nome_e_cognome) }}</td>
                                        <td>{{ Str::title($el->azienda->label) }}</td>
                                        <td>{{ Str::title($el->reparto) }}</td>
                                        <td>{{ Str::title($el->tipologia) }}</td>
                                        <td>{{ Str::title($el->tipo_incidente) }}</td>
                                        <td>{{ dataOra($el->data_e_ora) }}</td>
                                        <td>{{ $el->anno }}</td>
                                        <td>
                                            @if($el->status == 'active')
                                                @component('layouts.components.labels.success')
                                                    attivo
                                                @endcomponent
                                            @else
                                                @component('layouts.components.labels.error')
                                                    sospeso
                                                @endcomponent
                                            @endif
                                        </td>
{{--                                        <td>{{ Str::title(@$el->user->name) }}</td>--}}
                                        <td class="text-right">
                                            <div class="dropdown d-inline-block">
                                                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm"></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
                                                    @php
                                                     $url = route('mod-ot23.edit', [$el->id]);
                                                     if ($el->version == '2024') {
                                                         $url = route('mod-ot23_2024.edit', [$el->id]);
                                                     }
                                                    @endphp
                                                    <a href="{{ $url }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-edit mr-2"></i> Edit</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $list->links('vendor.pagination.default') }}


                       @if($export)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    @php
                                        $url = route('mod-ot23.export', $anno);
                                        if ($anno >= 2024) {
                                            $url = route('mod-ot23_2024.export', $anno);
                                        }
                                    @endphp
                                    <a href="{{ $url }}" class="btn btn-success">Export excel anno {{ $anno }}</a>
                                </div>
                            </div>
                       @endif
                        @if($charts)
                            <div class="row">
                                <div class="col-md-4 text-center text-uppercase">
                                    tipo lavoratore
                                    <canvas id="chartTipoLavoratore" data-values="{{ $chartTipoLavoratore['values'] }}" data-labels="{{ $chartTipoLavoratore['labels'] }}"></canvas>
                                </div>
                                <div class="col-md-4 text-center text-uppercase">
                                    tipologia
                                    <canvas id="chartTipologia" data-values="{{ $chartTipologia['values'] }}" data-labels="{{ $chartTipologia['labels'] }}"></canvas>
                                </div>
                                <div class="col-md-4 text-center text-uppercase">
                                    tipologia incidente
                                    <canvas id="chartTipologiaIncidente" data-values="{{ $chartTipologiaIncidente['values'] }}" data-labels="{{ $chartTipologiaIncidente['labels'] }}"></canvas>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4 text-center text-uppercase">
                                    reparto
                                    <canvas id="chartReparto" data-values="{{ $chartReparto['values'] }}" data-labels="{{ $chartReparto['labels'] }}"></canvas>
                                </div>
                                <div class="col-md-4 text-center text-uppercase">
                                    qualifica
                                    <canvas id="chartQualifica" data-values="{{ $chartQualifica['values'] }}" data-labels="{{ $chartQualifica['labels'] }}"></canvas>
                                </div>
                            </div>
                        @endif

                        @if(@isset($possibili_cause))
                            <h5 class="card-title">Possibili cause dell'evento</h5>
                            <div class="table-responsive pb-4">
                                <table class="mb-0 table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Passibile causa</th>
                                        <th class="text-right">Avvenimenti</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($possibili_cause as $el => $val)
                                        <tr>
                                            <td>{{ Str::title($el) }}</td>
                                            <td  class="text-right">{{ $val }}</td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if(@isset($incidente_poss_cause))
                            <h5 class="card-title">Incidente (Tipologia di mancato infortunio)</h5>
                            <div class="table-responsive pb-4">
                                <table class="mb-0 table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Passibile causa</th>
                                        <th class="text-right">Avvenimenti</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($incidente_poss_cause as $el => $val)
                                        <tr>
                                            <td>{{ Str::title($el) }}</td>
                                            <td class="text-right">{{ $val }}</td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if(@isset($cause_accertate))
                            <h5 class="card-title">Cause accertate dell'evento</h5>
                            <div class="table-responsive pb-4">
                                <table class="mb-0 table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Causa accertata</th>
                                        <th class="text-right">Avvenimenti</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cause_accertate as $el => $val)
                                        <tr>
                                            <td>{{ Str::title($el) }}</td>
                                            <td class="text-right">{{ $val }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>
            @else
                @component('layouts.components.alerts.warning')
                    Nessun elemento trovato
                @endcomponent
            @endif
        </div>
    </div>

@endsection
