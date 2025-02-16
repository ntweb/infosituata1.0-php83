@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Modifica configurazione globale', 'icon' => 'pe-7s-wristwatch', 'back' => $back])
        Configurazione globale
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.device.forms.configuration-global-create')
        </div>

    </div>

@endsection
