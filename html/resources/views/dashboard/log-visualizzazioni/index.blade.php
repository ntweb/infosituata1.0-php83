@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Log visualizzazioni', 'icon' => 'bx bx-list-check', 'right_component' => 'dashboard.log-visualizzazioni.components.index-header'])
        Log visualizzazioni
    @endcomponent
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12" id="div-list-log-visualizzazioni">
        </div>
    </div>
@endsection

@section('modal')
    @include('dashboard.log-visualizzazioni.modals.log-visualizzazioni-search')
@endsection
