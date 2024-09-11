@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco allegati', 'icon' => 'pe-7s-menu', 'right_component' => null])
        Stop alcol e droghe
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Iniziativa contro l'uso di alcol e sostanze stupefacenti</h5>
                    <ul>
                        <li><a href="{{ url('download/PROGAMMA-ADOTTATO-POLITICA-AZIENDALE-SULLE-SOSTANZE.docx') }}">Progamma adottato politica aziendale sulle sostanze</a></li>
                        <li><a href="{{ url('download/OPUSCOLO-INFOSITUATA-CONTRO-ALCOL-E-SOSTANZE-STUPEFACENTI.pdf') }}">Opuscolo infosituata contro alcol e sostanze stupefacenti</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

@endsection
