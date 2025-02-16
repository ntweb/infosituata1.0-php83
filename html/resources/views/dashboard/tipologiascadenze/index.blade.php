@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista scadenze', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.tipologiascadenze.components.index-header'])
        Scadenze
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Scadenze</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover" id="dashboard_tip_scadenza_index">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Label</th>
                                    <th>Module</th>
                                    <th>Mesi</th>
                                    <th>Giorni</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
                                        <th scope="row">{{ $el->id }}</th>
                                        <td>{{ Str::limit(Str::title($el->label), 80) }}</td>
                                        <td>{{ Str::title($el->moduleDetail->module->label) }} / {{ Str::title($el->moduleDetail->label) }}</td>
                                        <td>{{ $el->mesi }}</td>
                                        <td>{{ $el->giorni }}</td>
                                        <td class="text-right">
                                            @can('can_create_tip_scadenza')
                                            <a href="{{ route('tipologia-scadenza.edit', [$el->id]) }}" class="btn btn-primary btn-sm">Edit</a>
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
