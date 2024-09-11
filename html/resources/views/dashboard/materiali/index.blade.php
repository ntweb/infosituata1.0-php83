@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei materiali', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.materiali.components.index-header'])
        Materiali
    @endcomponent
@endsection

@section('search')
    <div class="search-wrapper @if(request()->has('q')) active @endif">
        <div class="input-holder">
            <input type="text" class="search-input" placeholder="Cerca" data-route="{{ route('materiali.index' ) }}?q=" value="{{ request()->get('q') }}">
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
                        <h5 class="card-title">Materiali</h5>
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover" id="dashboard_materiali_index">
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
                                    <th>Etichetta</th>
                                    <th>Codice</th>
                                    <th>Fornitore</th>
                                    <th>Azienda</th>
                                    <th>Stato</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
{{--                                        <th scope="row">{{ $el->id }}</th>--}}
                                        <td>
                                            {{ Str::title($el->extras1) }}
                                            <span class="d-none">{{$el->tags}}</span>
                                        </td>
                                        <td>{{ strtoupper($el->extras3) }}</td>
                                        <td>{{ $el->cliente ? $el->cliente->label : '-' }}</td>
                                        <td>{{ Str::title($el->azienda->label) }}</td>
                                        <td>
                                            @if($el->active)
                                                @component('layouts.components.labels.success')
                                                    attivo
                                                @endcomponent
                                            @else
                                                @component('layouts.components.labels.error')
                                                    sospeso
                                                @endcomponent
                                            @endif
                                        </td>
                                        <td class="text-right">

                                            <div class="dropdown d-inline-block">
                                                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm"></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
                                                    <a href="{{ route('infosituata.check', [md5($el->id)]) }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-qrcode mr-2"></i> Infosituata</a>
                                                    @can('can_create_materiali')
                                                        <div tabindex="-1" class="dropdown-divider"></div>
                                                        <a href="{{ route('materiali.edit', [$el->id]) }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-edit mr-2"></i> Edit</a>
                                                    @endcan
                                                </div>
                                            </div>
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
