@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back])
        Cisterna
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.cisterne.forms.create')
        </div>
    </div>
@endsection
