@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Lista permessi', 'icon' => 'pe-7s-home', 'back' => $back, 'right_component' => 'dashboard.timbrature-permessi.components.index-header'])
        Permessi
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12" id="div-list-permessi" data-route="{{ route('timbrature-permessi.index', ['_table' => true]) }}">
            @include('dashboard.timbrature-permessi.tables.power-user-index')
        </div>
    </div>
@endsection

@section('modal')
    @include('dashboard.timbrature-permessi.modals.permessi-search')
@endsection
