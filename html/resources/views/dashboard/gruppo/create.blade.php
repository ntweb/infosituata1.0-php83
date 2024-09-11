@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back, 'right_component' => isset($el) ? 'dashboard.gruppo.components.edit-header' : null])
        Gruppo
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-6">
            @include('layouts.components.alerts.alert')
            @include('dashboard.gruppo.forms.create')
        </div>

        <div class="col-md-12 col-lg-6">
            @include('dashboard.gruppo.utenti')
        </div>

    </div>

@endsection

@if(isset($el))
    @section('modal')
        @include('dashboard.gruppo.modals.add-group-user')
    @endsection
@endif
