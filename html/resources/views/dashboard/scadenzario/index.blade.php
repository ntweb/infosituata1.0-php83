@extends('layouts.dashboard')

@php
    $back = url()->previous() ? url()->previous() : null;
@endphp

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista elementi', 'icon' => 'pe-7s-date', 'back' => $back])
        Scadenzario
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @include('dashboard.scadenzario.tables.index')

        </div>
    </div>

@endsection
