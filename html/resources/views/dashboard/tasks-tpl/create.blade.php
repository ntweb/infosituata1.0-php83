@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bx-task', 'right_component' => isset($el) ? 'dashboard.tasks-tpl.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('task-template.index') : null])
        Template task manager
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.tasks-tpl.forms.create')
            @if(isset($el))
                @component('dashboard.tasks-tpl.components.delete', ['el' => $el, 'redirect' => route('task-template.index')])
                @endcomponent
            @endif
        </div>

        @if(isset($el))
        <div class="col-md-8" id="node-tree" data-route="{{ route('task-template.refresh-tree', [$el->id]) }}">
            @include('dashboard.tasks-tpl.components.tree')
        </div>
        @endif

    </div>

@endsection


