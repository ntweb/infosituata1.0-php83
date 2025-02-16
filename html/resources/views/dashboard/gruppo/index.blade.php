@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista deli gruppi', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.gruppo.components.index-header'])
        Gruppi
    @endcomponent
@endsection

@section('search')
    <div class="search-wrapper @if(request()->has('q')) active @endif">
        <div class="input-holder">
            <input type="text" class="search-input" placeholder="Cerca" data-route="{{ route('gruppo.index' ) }}?q=" value="{{ request()->get('q') }}">
            <button class="search-icon"><span></span></button>
        </div>
        <button class="close"></button>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Gruppi</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
                                    <th>Etichetta</th>
                                    <th>Broadcast</th>
                                    <th>Notifiche contr. mezzi</th>
                                    <th>Notifiche contr. attrezzatura</th>
                                    <th>Azienda</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
{{--                                        <th scope="row">{{ $el->id }}</th>--}}
                                        <td>{{ Str::title($el->label) }}</td>
                                        <td>
                                            @if($el->broadcast)
                                                <i class="fa fa-bullhorn"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($el->manutenzione_mezzi)
                                                Si
                                            @else
                                                No
                                            @endif
                                        </td>
                                        <td>
                                            @if($el->manutenzione_attrezzatura)
                                                Si
                                            @else
                                                No
                                            @endif
                                        </td>
                                        <td>{{ Str::title($el->azienda->label) }}</td>
                                        <td class="text-right">
                                            @can('can_create_gruppi')
                                            <a href="{{ route('gruppo.edit', [$el->id]) }}" class="btn btn-primary btn-sm">Edit</a>
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
