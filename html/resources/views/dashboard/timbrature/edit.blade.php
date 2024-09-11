@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Modifica timbrature', 'icon' => 'pe-7s-home', 'back' => $back])
        Gestione timbrature
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('layouts.components.alerts.alert')
        </div>
        <div class="col-md-12">
            @include('dashboard.timbrature.forms.edit')
        </div>
    </div>
@endsection
