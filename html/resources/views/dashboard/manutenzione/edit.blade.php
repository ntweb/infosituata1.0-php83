@extends('layouts.dashboard')
@section('header')
    @php
        if(!isset($back))
            $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $item->extras1, 'icon' => 'pe-7s-home', 'back' => $back, 'el' => $el, 'right_component' => 'dashboard.manutenzione.components.edit-header'])
        Modifica manutenzione
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-5">
            @include('layouts.components.alerts.alert')
            @include('dashboard.manutenzione.forms.create')
            @include('dashboard.manutenzione.forms.delete')
        </div>
        <div class="col-md-7">
            <div id="dettagli">
                @include('dashboard.manutenzione.tables.dettagli')
            </div>
            <div id="form-create"></div>
        </div>
    </div>
@endsection
