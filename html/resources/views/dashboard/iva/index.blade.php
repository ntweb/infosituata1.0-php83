@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Elenco', 'icon' => 'bx bx-list-ul', 'right_component' => 'dashboard.iva.components.index-header'])
        IVA ed esenzioni
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12" id="div-list-iva" data-route="{{ route('iva.index') }}?_render_table=1">
            @include('dashboard.iva.tables.index')
        </div>
    </div>
@endsection

@section('modal')
    @include('dashboard.iva.modals.iva-search')
@endsection
