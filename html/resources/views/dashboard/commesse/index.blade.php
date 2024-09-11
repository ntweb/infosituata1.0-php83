@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista delle commesse', 'icon' => 'bx bxs-objects-horizontal-left', 'right_component' => 'dashboard.commesse.components.index-header'])
        Commesse
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Commesse</h5>
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover" id="dashboard_user_index">
                                <thead>
                                <tr>
                                    <th>Etichetta</th>
                                    <th>Cliente</th>
                                    <th>Protocollo</th>
                                    <th>Date previste</th>
                                    <th>Date effettive</th>
                                    <th class="text-right">Stato</th>
                                    <th style="display: none"></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr id="l-{{ $el->id }}">
                                        <td>{{ Str::title($el->label) }}</td>
                                        <td>{{ $el->cliente ? $el->cliente->label : '-' }}</td>
                                        <td>{{ $el->protocollo }}</td>
                                        <td>{{ data($el->data_inizio_prevista) }} - {{ data($el->data_fine_prevista) }}</td>
                                        <td>-</td>
                                        <td class="text-right">
                                            @if($el->fl_ritardo)
                                                @component('layouts.components.labels.error')
                                                    in ritardo
                                                @endcomponent
                                            @endif
                                        </td>
                                        <td style="display: none">{{ $el->tags }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('commessa.edit', [$el->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-edit mr-2"></i> Edit</a>

                                            @can('can_create_commesse')
                                            <button type="button" class="btn btn-danger btn-sm btnDelete"
                                                    data-message="Si conferma la cancellazione?"
                                                    data-route="{{ route('commessa-node.destroy', [$el->id, '_type' => 'json']) }}"
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
