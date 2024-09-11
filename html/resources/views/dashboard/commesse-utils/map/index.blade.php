@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Visualizzatore su mappa delle commesse attive e non terminate', 'icon' => 'bx bx-map', 'right_component' => null])
        Mappa commesse
    @endcomponent
@endsection

@section('content')
    <div id="map" style="width: 100%; z-index: 1;" data-route="{{ route('commessa-utils.map', 'coord') }}"></div>
@endsection
