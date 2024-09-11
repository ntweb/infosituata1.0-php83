@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista delle aziende', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.azienda.components.index-header'])
        Aziende
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Aziende</h5>
                    <div class="table-responsive pb-10">
                        <table class="mb-0 table table-hover">
                            <thead>
                            <tr>
{{--                                <th>#</th>--}}
                                <th>UID</th>
                                <th>Etichetta</th>
                                <th>Città</th>
                                <th>P.IVA</th>
                                <th>Email</th>
                                <th>Package</th>
                                <th class="text-right">Terminali</th>
                                <th class="text-right">Scadenza</th>
                                <th class="text-right">Stato</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $el)
                            <tr>
{{--                                <th scope="row">{{ $el->id }}</th>--}}
                                <td>{{ strtoupper($el->uid) }}</td>
                                <td>{{ Str::title($el->label) }}</td>
                                <td>{{ Str::title($el->citta) }}</td>
                                <td>{{ Str::title($el->piva) }}</td>
                                <td>{{ strtolower($el->user->email) }}</td>
                                <td>
                                    @if($el->package)
                                        {{ Str::title($el->package->label) }}
                                    @else
                                        @component('layouts.components.labels.error')
                                            <i class="pe-7s-attention"></i> Nessun pacchetto
                                        @endcomponent
                                    @endif
                                </td>
                                <td class="text-right"><b>{{ $el->devices->count() }}</b> / {{ $el->terminali }}</td>
                                <td class="text-right">{{ data($el->user->deactivate_at) }}</td>
                                <td class="text-right">
                                    @if($el->user->active)
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
                                            @can('can-create')
                                                <a href="{{ route('azienda.edit', [$el->id]) }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-edit mr-2"></i> Edit</a>
                                                @if(Auth::user()->superadmin)
                                                    <div tabindex="-1" class="dropdown-divider"></div>
                                                    <a href="{{ url('/dashboard/force/'.$el->user->id.'/login') }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-sign-in-alt mr-2"></i> Login</a>
                                                @endif
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
