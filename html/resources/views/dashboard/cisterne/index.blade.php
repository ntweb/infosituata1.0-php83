@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista cisterne', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.cisterne.components.index-header'])
        Cisterne
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Cisterne</h5>
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover" id="dashboard_mezzi_index">
                                <thead>
                                <tr>
                                    <th>Etichetta</th>
                                    <th>Livello attuale</th>
                                    <th>Livello avviso</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
                                        <td>
                                            {{ Str::title($el->label) }}
                                        </td>
                                        <td>{{ $el->livello_attuale }}</td>
                                        <td>{{ $el->livello_minimo }}</td>
                                        <td class="text-right">
                                            @can('can_create_mezzi')
                                            <a href="{{ route('cisterne.edit', [$el->id]) }}" class="bt btn-sm btn-primary">
                                                Edit
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

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
