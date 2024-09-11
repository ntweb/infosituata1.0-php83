@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco', 'icon' => 'bx bx-list-ul', 'right_component' => 'dashboard.fattura.components.index-header'])
        Fatture
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12" id="div-list-fattura" data-route="{{ route('fattura.index') }}?_render_table=1">
            @include('dashboard.fattura.tables.index')
        </div>
    </div>
@endsection

@section('modal')
    @include('dashboard.fattura.modals.fattura-search')
@endsection
