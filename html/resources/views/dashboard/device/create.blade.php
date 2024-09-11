@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-wristwatch', 'back' => $back])
        Terminale
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.device.forms.create')
        </div>

        @if(isset($el))
        <div class="col-md-12 col-lg-4">
            @include('dashboard.device.forms.utente')
{{--            @include('dashboard.device.forms.configuration-device-create')--}}
        </div>
        @endif

    </div>

@endsection
