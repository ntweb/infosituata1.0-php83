@extends('layouts.dashboard')

@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Modifica password', 'icon' => 'pe-7s-home', 'back' => $back])
        Utente
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('layouts.components.alerts.alert')
        </div>
        <div class="col-md-4">
            @if(isset($el))
                @include('dashboard.user.forms.password')
            @endif
        </div>
    </div>
@endsection

