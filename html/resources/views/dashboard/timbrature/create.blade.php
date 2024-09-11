@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Inserimento nuova timbratura', 'icon' => 'pe-7s-home', 'back' => $back])
        Inserimento timbratura
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12" id="getGpsPermission">
            @component('layouts.components.alerts.warning')
                Per effettuare la timbratura è necessario autorizzare il browser al rilevamento della posizione
                <br>
                <button id="btnAcceptGeololation" class="btn btn-primary">Consenti geolocalizzazione</button>
            @endcomponent
        </div>

        <div class="col-md-12">
            @include('layouts.components.alerts.alert')
        </div>
        <div class="col-md-12">
            @include('dashboard.timbrature.forms.create')
        </div>
    </div>
@endsection
