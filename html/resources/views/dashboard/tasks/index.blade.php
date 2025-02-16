@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei task', 'icon' => 'bx bx-task', 'right_component' => 'dashboard.tasks.components.index-header'])
        Task manager
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Task manager</h5>
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover" id="dashboard_user_index">
                                <thead>
                                <tr>
                                    <th>Etichetta</th>
                                    <th>Cliente</th>
                                    <th>Indirizzo spec.</th>
                                    <th>Tag</th>
                                    <th class="text-right">Stato</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr id="l-{{ $el->id }}">
                                        <td>{{ Str::title($el->label) }}</td>
                                        <td>{{ $el->cliente ? $el->cliente->label : '-' }}</td>
                                        <td>{{ $el->indirizzo_specifico ?? '-' }}</td>
                                        <td>{{ $el->tags }}</td>
                                        <td class="text-right">
                                            @component('dashboard.tasks.components.labels.node-label-stato', ['node' => $el])
                                            @endcomponent
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('task.edit', [$el->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-edit mr-2"></i> Edit</a>

                                            @can('can_create_tasks')
                                            <button type="button" class="btn btn-danger btn-sm btnDelete"
                                                    data-message="Si conferma la cancellazione?"
                                                    data-route="{{ route('task-node.destroy', [$el->id, '_type' => 'json']) }}"
                                                    data-callback="deleteElement('#l-{{ $el->id }}');"><i class="fas fa-trash fa-fw"></i></button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $list->links('vendor.pagination.default') }}

                    </div>
                </div>
            @else
                @component('layouts.components.alerts.warning')
                    Nessun elemento trovato
                @endcomponent
            @endif
        </div>
    </div>

@endsection

@section('modal')
    @include('dashboard.tasks.modals.index-tasks-search')
@endsection
