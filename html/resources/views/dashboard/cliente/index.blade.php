@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco clienti', 'icon' => 'bx bx-user-pin', 'right_component' => 'dashboard.cliente.components.index-header'])
        Clienti
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12" id="div-list-clienti" data-route="{{ route('cliente.index') }}?_render_table=1">
            @include('dashboard.cliente.tables.index')
        </div>
    </div>
@endsection

@section('modal')
    @include('dashboard.cliente.modals.clienti-search')
@endsection
