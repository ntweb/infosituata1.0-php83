@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $item->extras1, 'icon' => 'pe-7s-home', 'back' => $back])
        Crea nuovo controllo
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.controllo.forms.create')
        </div>
    </div>
@endsection
