@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco allegati', 'icon' => 'pe-7s-menu', 'right_component' => null])
        Micro formazione
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Documenti allegati</h5>
                    <ul>
                        <li><a href="{{ url('download/Opuscolo-esplicativo-DPI.pdf') }}">Opuscolo esplicativo DPI</a></li>
                        <li><a href="{{ url('download/PIEGHEVOLE-micro-formazione-DPI.pdf') }}">Pieghevole micro formazione DPI</a></li>
                        <li><a href="{{ url('download/PITTOGRAMMI-e-QR-code-DPI-Infosituata.pdf') }}">Pittogrammi e QR code DPI Infosituata</a></li>
                        <li><a href="{{ url('download/RELAZIONE-ILLUSTRATIVA-DEL-PROGRAMMA-DI-MICRO-FORMAZIONE.pdf') }}">Relazione illustrativa del programma di micro formazione</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

@endsection
