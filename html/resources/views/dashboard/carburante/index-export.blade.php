@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['icon' => 'fas fa-gas-pump', 'back' => $back, 'dates' => $dates, 'right_component' => 'dashboard.carburante.components.index-export-header'])
        Lista schede carburante
    @endcomponent
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(isset($list))
                @if(count($list))
                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            <i class="header-icon lnr-screen icon-gradient bg-warm-flame"> </i>Schede carburanti periodo selezionato
                            <div class="btn-actions-pane-right">
                                <a href="javascript:void(0)" id="btnExportSchede" class="btn btn-light btn-sm">Export schede carburanti</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive pb-10">
                                <table class="mb-0 table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Mezzo</th>
                                        <th>Data</th>
                                        <th>Eseguito da</th>
                                        <th>KM</th>
                                        <th>Litri</th>
                                        <th class="text-right" style="min-width: 150px;">Costo</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $_tot_costi = 0;
                                    @endphp
                                    @foreach($list as $l)
                                        @php
                                            $_tot_costi = $_tot_costi + $l->costo;
                                        @endphp
                                        <tr>
                                            <td>{{ Str::title($l->item->extras1) }} [{{ $l->item->extras3 }}]</td>
                                            <td>{{ data($l->data) }}</td>
                                            <td>{{ $l->createdBy->name }}</td>
                                            <td>{{ $l->km }}</td>
                                            <td>{{ $l->litri }}</td>
                                            <td class="text-right">
                                                {{ $l->costo ? euro($l->costo) : 'nd' }} &euro;
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('carburante.edit', $l->id) }}" class="btn btn-primary btn-sm">
                                                    edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>{{ euro($_tot_costi) }}</b> &euro;</td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                @else
                    @component('layouts.components.alerts.warning')
                        Nessun elemento trovato
                    @endcomponent
                @endif
            @else
                @component('layouts.components.alerts.info')
                    Filtrare un periodo di riferimento
                @endcomponent
            @endif
        </div>
    </div>
@endsection
