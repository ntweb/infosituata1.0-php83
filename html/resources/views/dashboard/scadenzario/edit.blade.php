@php
    $back = url()->previous() ? url()->previous() : null;
    $title = strtoupper($item->label);;
@endphp

@extends('layouts.dashboard')
@section('header')

    @component('layouts.components.header', ['subtitle' => 'Modifica scadenza', 'icon' => 'pe-7s-date', 'back' => $back, 'right_component' => 'dashboard.scadenzario.components.edit-header', 'el' => $item])
        {{ $title }}
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col">
            @include('layouts.components.alerts.alert')
            @include('dashboard.scadenzario.forms.edit')
        </div>

    </div>

@endsection

@section('modal')
    @include('dashboard.scadenzario.components.modal-scadenza-checkcontrollata')
    @include('dashboard.scadenzario.components.modal-delete-attachment')
    @include('dashboard.infosituata-moduli.components.modal-scadenza-create')
@endsection
