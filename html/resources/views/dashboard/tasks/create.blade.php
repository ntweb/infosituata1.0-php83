@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bx-task', 'right_component' => isset($el) ? 'dashboard.tasks.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('task.index') : null])
        Task manager
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.tasks.forms.create')
        </div>

        @if(isset($el))
        <div class="col-md-8">
            <div class="mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title">
                    </div>
                    <ul class="nav">
                        <li class="nav-item">
                            <a data-toggle="tab"
                               href="#tab-lista-tasks"
                               class="nav-link active show">Lista tasks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab"
                               href="#tab-allegati"
                               class="nav-link">Allegati
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab-lista-tasks" role="tabpanel">
                            <div class="table-responsive" id="node-tree" data-route="{{ route('task.refresh-tree', [$el->id]) }}">
                                @include('dashboard.tasks.components.tree')
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-allegati" role="tabpanel">
                            <div class="table-responsive">
                                @include('dashboard.tasks.components.allegati')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

@endsection

@if(isset($el))
    @section('modal')
        @include('dashboard.tasks.modals.print')
        @include('dashboard.tasks.modals.autorizzazioni-copy')
    @endsection
@endif
