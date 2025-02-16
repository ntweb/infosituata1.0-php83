@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco allegati', 'icon' => 'pe-7s-menu', 'right_component' => null])
        Infosituata - Manuale utente
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Documenti allegati</h5>
                    <ul>
                        <li><a href="{{ url('download/Manuale-A4.pdf') }}">Manuale utente sull'utilizzo di Infosituata</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

@endsection
