@php
    $azienda = getAziendaBySessionUser();
@endphp

@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'configurazione', 'icon' => 'pe-7s-home', 'back' => $back])
        SMS configurazione
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.sms.forms.trendoo')
        </div>

    </div>

@endsection

