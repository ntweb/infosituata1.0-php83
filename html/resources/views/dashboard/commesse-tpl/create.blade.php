@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bxs-objects-horizontal-left', 'right_component' => isset($el) ? 'dashboard.commesse-tpl.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('commessa-template.index') : null])
        Template commessa
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.commesse-tpl.forms.create')
            @if(isset($el))
                @component('dashboard.commesse-tpl.components.delete', ['el' => $el, 'redirect' => route('commessa-template.index')])
                @endcomponent
            @endif
        </div>

        @if(isset($el))
        <div class="col-md-8" id="node-tree" data-route="{{ route('commessa-template.refresh-tree', [$el->id]) }}">
            @include('dashboard.commesse-tpl.components.tree')
        </div>
        @endif

    </div>

@endsection

@if(isset($el))
    @section('modal')
        @include('dashboard.commesse-tpl.forms.create-extrafield')
    @endsection
@endif

