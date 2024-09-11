@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back])
        Sede
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-6">
            @include('layouts.components.alerts.alert')
            @include('dashboard.sede.forms.create')
        </div>

        @if(isset($el))
        <div class="col-md-12 col-lg-6">
            @include('dashboard.sede.utenti')
        </div>
        @endif

    </div>

@endsection
