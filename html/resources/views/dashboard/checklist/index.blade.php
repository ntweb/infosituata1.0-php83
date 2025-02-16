@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco checklist redatte', 'icon' => 'bx bx-check-circle', 'right_component' => 'dashboard.checklist.components.index-header'])
        Checklist redatte
    @endcomponent
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12" id="div-list-checklist">
            @include('dashboard.checklist.tables.index')
        </div>
    </div>
@endsection

@section('modal')
    @include('dashboard.checklist.modals.checklist-search')
@endsection
