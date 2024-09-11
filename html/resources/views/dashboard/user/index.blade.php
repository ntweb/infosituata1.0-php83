@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista degli utenti', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.user.components.index-header'])
        Utenti
    @endcomponent
@endsection

@section('search')
    <div class="search-wrapper @if(request()->has('q')) active @endif">
        <div class="input-holder">
            <input type="text" class="search-input" placeholder="Cerca" data-route="{{ route('user.index' ) }}?q=" value="{{ request()->get('q') }}">
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
                        <h5 class="card-title">Utenti</h5>
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover" id="dashboard_user_index">
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
                                    <th>Cognome</th>
                                    <th>Nome</th>
                                    <th>Matricola</th>
                                    <th>Azienda</th>
                                    <th>Email</th>
                                    <th>Power user</th>
                                    <th>2FA</th>
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
                                        <td>{{ Str::title($el->extras2) }}</td>
                                        <td>{{ strtoupper($el->extras3) }}</td>
                                        <td>{{ Str::title($el->azienda->label) }}</td>
                                        <td>{{ strtolower($el->user->email) }}</td>
                                        <td>
                                            @if($el->user->power_user)
                                                @component('layouts.components.labels.success')
                                                    attivo
                                                @endcomponent
                                            @else
                                                @component('layouts.components.labels.error')
                                                    no
                                                @endcomponent
                                            @endif
                                        </td>
                                        <td>
                                            @if($el->user->_2fa)
                                                @component('layouts.components.labels.success')
                                                    attivo
                                                @endcomponent
                                            @else
                                                @component('layouts.components.labels.error')
                                                    non attivo
                                                @endcomponent
                                            @endif
                                        </td>
                                        <td>
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
                                                    <a href="{{ route('infosituata.check', [md5($el->id)]) }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-qrcode mr-2"></i> Infosituata</a>
                                                    @can('can_create_utenti')
                                                        <div tabindex="-1" class="dropdown-divider"></div>
                                                        <a href="{{ route('user.edit', [$el->id]) }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-edit mr-2"></i> Edit</a>
                                                        @if(Auth::user()->superadmin)
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
