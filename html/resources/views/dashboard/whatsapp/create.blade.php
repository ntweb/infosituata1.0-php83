@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bxl-whatsapp', 'back' => $back])
        Whatsapp
    @endcomponent

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            @include('layouts.components.alerts.alert')
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('dashboard.whatsapp.forms.create-broadcast')
        </div>
    </div>

@endsection

@section('modal')
    @include('dashboard.messaggio.components.modal-delete-attachment')
@endsection

