@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $item->extras1, 'icon' => 'fas fa-gas-pump', 'back' => $back])
        Crea nuova scheda carburante
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.carburante.forms.create')
        </div>
    </div>
@endsection
