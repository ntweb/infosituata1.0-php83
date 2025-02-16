@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back])
        SMS
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.sms.forms.create')
        </div>

    </div>

@endsection

