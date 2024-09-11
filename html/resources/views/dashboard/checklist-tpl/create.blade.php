@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bx-list-check', 'right_component' => isset($el) ? 'dashboard.checklist-tpl.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('checklist-template.index') : null])
        Template checklist
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.checklist-tpl.forms.create')
            @if(isset($el))
                @if(!$el->fl_prod)
                    @component('dashboard.checklist-tpl.components.delete', ['el' => $el, 'redirect' => route('checklist-template.index')])
                    @endcomponent
                @endif
            @endif
        </div>

        @if(isset($el))
        <div class="col-md-8" id="node-tree" data-route="{{ route('checklist-template.refresh-tree', [$el->id]) }}">
            @include('dashboard.checklist-tpl.components.tree')
        </div>
        @endif

    </div>

@endsection
