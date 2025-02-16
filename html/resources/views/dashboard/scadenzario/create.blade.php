@php

    $back = url()->previous() ? url()->previous() : null;
    switch ($item->controller) {
        case 'utente':
            $title = strtoupper($item->extras1.' '.$item->extras2);
            break;

        case 'mezzo':
        case 'attrezzatura':
        case 'risorsa':
            $title = strtoupper($item->extras1);
            break;

        default:
            $title = 'nd';
    }
@endphp

@extends('layouts.dashboard')
@section('header')

    @component('layouts.components.header', ['subtitle' => 'Crea nuova scadenza', 'icon' => 'pe-7s-date', 'back' => $back])
        {{ $title }}
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col">
            @include('dashboard.scadenzario.forms.create')
        </div>

    </div>

@endsection

@section('modal')
    @include('dashboard.infosituata-moduli.components.modal-scadenza-create')
@endsection
