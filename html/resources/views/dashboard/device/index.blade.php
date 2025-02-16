@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei terminali', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.device.components.index-header'])
        Terminali
    @endcomponent
@endsection

@section('search')
    <div class="search-wrapper @if(request()->has('q')) active @endif">
        <div class="input-holder">
            <input type="text" class="search-input" placeholder="Cerca" data-route="{{ route('device.index' ) }}?q=" value="{{ request()->get('q') }}">
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
                        <h5 class="card-title">Terminali</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
                                    <th>Etichetta</th>
                                    <th>Identificativo</th>
                                    <th></th>
                                    <th>Conf.</th>
                                    <th>Conf. update</th>
                                    <th>Modello</th>
                                    <th>Utilizzatore</th>
                                    @if(Auth::user()->superadmin)
                                    <th>Azienda</th>
                                    @endif
                                    <th>Stato</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
{{--                                        <td scope="row">{{ $el->id }}</td>--}}
                                        <td>{{ Str::title($el->label) }}</td>
                                        <td>{{ strtoupper($el->identifier) }}</td>
                                        <td>
                                            @component('layouts.components.device.icon', ['type'=>$el->type])
                                            @endcomponent

                                            {{ Str::title($el->type->hw) }}
                                        </td>
                                        <td>
                                            @if($el->configuration->active)
                                                <button class="btn btn-link btn-sm">Personalizzata</button>
                                            @else
                                                <button class="btn btn-link btn-sm">Globale</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($el->configuration->request_configuration_update)
                                                <button class="btn btn-link btn-sm btn-warning"><i class="fas fa-spinner fa-spin"></i> Da aggiornare </button>
                                            @else
                                                <button class="btn btn-link btn-sm">Disp. Aggiornato<span class="badge badge-success badge-dot badge-dot-sm"> </span></button>
                                            @endif
                                        </td>
                                        <td>{{ Str::title($el->type->brand).' - '.$el->type->label }}</td>
                                        <td>
                                            @if($el->utente)
                                                {{ Str::title($el->utente->label) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if(Auth::user()->superadmin)
                                        <td>{{ Str::title($el->azienda->label) }}</td>
                                        @endif
                                        <td>
                                            @if($el->active)
                                                @component('layouts.components.labels.success')
                                                    attivo
                                                @endcomponent
                                            @else
                                                @component('layouts.components.labels.error')
                                                    disattivato
                                                @endcomponent
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($el->latitude && $el->longitude)
                                                <button type="button" class="btn btn-primary btn-sm btnOpenDeviceMap" data-latitude="{{ $el->latitude }}" data-longitude="{{ $el->longitude }}"><i class="ion-android-map"></i> Mappa</button>
                                            @endif
                                            @can('can-create')
                                            <a href="{{ route('device.edit', [$el->id]) }}" class="btn btn-primary btn-sm">Edit</a>
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
    @include('dashboard.device.components.modal-device-map')
@endsection
