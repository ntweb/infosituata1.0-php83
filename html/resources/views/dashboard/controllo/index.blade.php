@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $el->extras1, 'icon' => 'pe-7s-home', 'back' => $back, 'el' => isset($el) ? $el : null, 'right_component' => 'dashboard.controllo.components.index-header'])
        Controllo
    @endcomponent
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(count($el->controlli))
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        <i class="header-icon lnr-screen icon-gradient bg-warm-flame"> </i>Controlli
                        <div class="btn-actions-pane-right">
                            <a href="{{ route('controllo.export', $el->id) }}" class="btn btn-light btn-sm">Export scheda controlli</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>Data</th>
                                    <th></th>
                                    <th class="text-right" style="min-width: 150px;">Costo</th>
                                    <th>Descrizione</th>
                                    <th class="text-right">Tempo impiegato</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $_tot_costi = 0;
                                    $_tot_tempo = 0;
                                    $_tot_ricambi = 0;
                                @endphp
                                @foreach($el->controlli as $l)
                                    @php
                                        // $_tot_magazzino = $l->dettagli->sum('magazzino');
                                        // $_tot_acquistati = $l->dettagli->sum('acquistati');
                                        $_tot_magazzino = 0;
                                        $_tot_acquistati = 0;

                                        $_tot_costi = $_tot_costi + $l->costo;
                                        $_tot_tempo = $_tot_tempo + $l->tempo;
                                        $_tot_ricambi = $_tot_ricambi + $_tot_magazzino + $_tot_acquistati;
                                    @endphp
                                    <tr>
                                        <td>{{ data($l->data) }}</td>
                                        <td>
                                            {{ $l->esecutore ? Str::title($l->esecutore) : '-' }}
                                            <br>
                                            <small>{{ $l->tipo_1 }} / {{ $l->tipo_2 }}</small>
                                        </td>
                                        <td class="text-right">
                                            {{ $l->costo ? euro($l->costo) : 'nd' }} &euro;
                                        </td>
                                        <td>{{ Str::limit(Html2Text($l->descrizione), 35) }}</td>
                                        <td class="text-right">{{ $l->tempo }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('controllo.edit', $l->id) }}" class="btn btn-primary btn-sm">
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
                                    <td class="text-right"><b>{{ euro($_tot_costi) }}</b> &euro;</td>
                                    <td></td>
                                    <td class="text-right"><b>{{ $_tot_tempo }}</b></td>
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
        </div>
    </div>
@endsection
