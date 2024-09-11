@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco rapportini', 'icon' => 'bx bx-notepad', 'right_component' => 'dashboard.rapportini.components.index-header'])
        Rapportini
    @endcomponent
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12" id="div-list-rapportini">
            @include('dashboard.rapportini.tables.index')
        </div>
    </div>
@endsection

@section('modal')
    @include('dashboard.rapportini.modals.rapportini-search')
@endsection
