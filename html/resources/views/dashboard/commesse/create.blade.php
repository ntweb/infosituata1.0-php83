@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bxs-objects-horizontal-left', 'right_component' => isset($el) ? 'dashboard.commesse.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('commessa.index') : null])
        Commessa
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.commesse.forms.create')
            @if(isset($el))
                @include('dashboard.commesse.forms.map')
                @include('dashboard.commesse.forms.qr')
            @endif
        </div>

        @if(isset($el))
        <div class="col-md-8">
            <div class="table-responsive" id="node-tree" data-route="{{ route('commessa.refresh-tree', [$el->id]) }}">
                @include('dashboard.commesse.components.tree')
            </div>
        </div>
        @endif

    </div>

@endsection

@if(isset($el))
    @section('modal')
        @include('dashboard.commesse.modals.copy-extra-field')
        @include('dashboard.commesse.modals.massive-copy-item')
        @include('dashboard.commesse.modals.autorizzazioni-copy')

        @include('dashboard.commesse.forms.create-extrafield')
        @include('dashboard.commesse.forms.notification-status')
        @include('dashboard.commesse.modals.print')
        @include('dashboard.commesse.modals.giornale-lavori')
    @endsection
@endif

